<?php

require_once('../../config.php');

$redirectpath = $_SERVER['HTTP_REFERER'];
if (empty($_POST) or !empty($_GET)) {
    redirect($redirectpath, get_string('worngdatapost', 'block_edupayments'), 3);
}

$data = new stdClass();

foreach ($_POST as $key => $value) {
    $data->$key = $value;
}

$custom = explode('|', $data->custom);
$data->userid = (int) $custom[0];
$data->courseid = (int) $custom[1];
$data->instanceid = (int) $custom[2];
$data->promocode = $custom[3];
$data->payment_type = 'freeenrol';
$data->timeupdated = time();
$data->txn_id = 'selfenrol';
$data->payment_status = 'Completed';
$data->item_name = $data->item_number;

if ($DB->record_exists('enrol_edupay', array('courseid' => $data->courseid, 'userid' => $data->userid, 'instanceid' => $data->instanceid))) {
    redirect($redirectpath, get_string('paymentalreadydone', 'block_edupayments'), 3);
}


$course = $DB->get_record('course', array('id' => $data->courseid));
$user = $DB->get_record('user', array('id' => $data->userid));
$insertid = $DB->insert_record("enrol_edupay", $data);


/**
 * Update coupon in database
 */
if ($rs = $DB->get_record('ediscount', array('courseid' => $data->courseid, 'enrolid' => $data->instanceid, 'ppcode' => $data->promocode, 'ppflag' => 1), 'id, ppaplied')) {
    $rs->ppaplied += 1;
    $DB->update_record('ediscount', $rs);
}

$plugin = enrol_get_plugin('edupay');
$mailstudents = $plugin->get_config('mailstudents');
$mailteachers = $plugin->get_config('mailteachers');
$mailadmins = $plugin->get_config('mailadmins');
$a = new stdClass();
$site = get_site();
$admin = get_admin();
$mailsent = array();
$context = context_course::instance($course->id);
$teacher = false;
if ($users = get_users_by_capability($context, 'moodle/course:update', 'u.*', 'u.id ASC', '', '', '', '', false, true)) {
    $users = sort_by_roleassignment_authority($users, $context);
    $teacher = array_shift($users);
}

$a->coursename = format_string($course->fullname, true, array('context' => $context));
$a->profileurl = "$CFG->wwwroot/user/view.php?id=$user->id";
$a->firstname = fullname($user);
$a->user = fullname($user);
$a->sitename = $site->fullname;
$a->amount = $data->amount;
$a->currency = $data->currency_code;

$eventdata = new stdClass();
$eventdata->modulename = 'moodle';
$eventdata->component = 'enrol_edupay';
$eventdata->name = 'edupay_enrolment';
$eventdata->userfrom = empty($teacher) ? $admin : $teacher;
$eventdata->userto = $user;

if ($data->item_name == 'firstattend') {
    $eventdata->subject = get_string("thankpaymentfirst", 'block_edupayments');
    $eventdata->fullmessage = get_string('welcomefirst', 'block_edupayments', $a);
} else if ($data->item_name == 'attendance_of_completion') {
    $eventdata->subject = get_string("thankpaymentattendcert", 'block_edupayments');
    $eventdata->fullmessage = get_string('welcomeattend', 'block_edupayments', $a);
} else if ($data->item_name == 'verifiedcerti') {
    $eventdata->subject = get_string("thankpaymentverified", 'block_edupayments');
    $eventdata->fullmessage = get_string('welcomeverified', 'block_edupayments', $a);
} else if ($data->item_name == 'examination') {
    $eventdata->subject = get_string("thankpaymentexam", 'block_edupayments');
    $eventdata->fullmessage = get_string('welcomeexam', 'block_edupayments', $a);
}

$eventdata->fullmessageformat = FORMAT_PLAIN;
$eventdata->fullmessagehtml = '';
$eventdata->smallmessage = '';
$mailsent[] = $eventdata;

if (!empty($mailteachers) && !empty($teacher)) {
    $a->course = format_string($course->fullname, true, array('context' => $context));
    $a->user = fullname($user);

    $eventdata = new stdClass();
    $eventdata->modulename = 'moodle';
    $eventdata->component = 'enrol_edupay';
    $eventdata->name = 'edupay_enrolment';
    $eventdata->userfrom = $user;
    $eventdata->userto = $teacher;
    if ($data->item_name == 'firstattend') {
        $eventdata->subject = get_string("thankpaymentfirst", 'block_edupayments');
        $eventdata->fullmessage = get_string('welcomefirst', 'block_edupayments', $a);
    } else if ($data->item_name == 'attendance_of_completion') {
        $eventdata->subject = get_string("thankpaymentattendcert", 'block_edupayments');
        $eventdata->fullmessage = get_string('welcomeattend', 'block_edupayments', $a);
    } else if ($data->item_name == 'verifiedcerti') {
        $eventdata->subject = get_string("thankpaymentverified", 'block_edupayments');
        $eventdata->fullmessage = get_string('welcomeverified', 'block_edupayments', $a);
    } else if ($data->item_name == 'examination') {
        $eventdata->subject = get_string("thankpaymentexam", 'block_edupayments');
        $eventdata->fullmessage = get_string('welcomeexam', 'block_edupayments', $a);
    }
    $eventdata->fullmessageformat = FORMAT_PLAIN;
    $eventdata->fullmessagehtml = '';
    $eventdata->smallmessage = '';
    $mailsent[] = $eventdata;
}

$a->course = format_string($course->fullname, true, array('context' => $context));
$a->user = fullname($user);
$admins = get_admins();
foreach ($admins as $admin) {
    $eventdata = new stdClass();
    $eventdata->modulename = 'moodle';
    $eventdata->component = 'enrol_edupay';
    $eventdata->name = 'edupay_enrolment';
    $eventdata->userfrom = $user;
    $eventdata->userto = $admin;
    if ($data->item_name == 'firstattend') {
        $eventdata->subject = get_string("thankpaymentfirst", 'block_edupayments');
        $eventdata->fullmessage = get_string('welcomefirst', 'block_edupayments', $a);
    } else if ($data->item_name == 'attendance_of_completion') {
        $eventdata->subject = get_string("thankpaymentattendcert", 'block_edupayments');
        $eventdata->fullmessage = get_string('welcomeattend', 'block_edupayments', $a);
    } else if ($data->item_name == 'verifiedcerti') {
        $eventdata->subject = get_string("thankpaymentverified", 'block_edupayments');
        $eventdata->fullmessage = get_string('welcomeverified', 'block_edupayments', $a);
    } else if ($data->item_name == 'examination') {
        $eventdata->subject = get_string("thankpaymentexam", 'block_edupayments');
        $eventdata->fullmessage = get_string('welcomeexam', 'block_edupayments', $a);
    }
    $eventdata->fullmessageformat = FORMAT_PLAIN;
    $eventdata->fullmessagehtml = '';
    $eventdata->smallmessage = '';
    $mailsent[] = $eventdata;
}

foreach ($mailsent as $key => $senitem) {
    message_send($senitem);
}

redirect($redirectpath, get_string('freeenrollmentcompleted', 'block_edupayments'), 3);

function message_paypal_error_to_admin($subject, $data) {
    echo $subject;
    $admin = get_admin();
    $site = get_site();

    $message = "$site->fullname:  Transaction failed.\n\n$subject\n\n";

    foreach ($data as $key => $value) {
        $message .= "$key => $value\n";
    }

    $eventdata = new stdClass();
    $eventdata->modulename = 'moodle';
    $eventdata->component = 'enrol_edupay';
    $eventdata->name = 'edupay_enrolment';
    $eventdata->userfrom = $admin;
    $eventdata->userto = $admin;
    $eventdata->subject = "PAYPAL ERROR: " . $subject;
    $eventdata->fullmessage = $message;
    $eventdata->fullmessageformat = FORMAT_PLAIN;
    $eventdata->fullmessagehtml = '';
    $eventdata->smallmessage = '';
    message_send($eventdata);
}

/**
 * Silent exception handler.
 *
 * @param Exception $ex
 * @return void - does not return. Terminates execution!
 */
function enrol_edupay_ipn_exception_handler($ex) {
    $info = get_exception_info($ex);

    $logerrmsg = "enrol_edupay IPN exception handler: " . $info->message;
    if (debugging('', DEBUG_NORMAL)) {
        $logerrmsg .= ' Debug: ' . $info->debuginfo . "\n" . format_backtrace($info->backtrace, true);
    }
    error_log($logerrmsg);

    exit(0);
}
