<?php
// $Id: inscriptions_massives.php 356 2010-02-27 13:15:34Z ppollet $
/**
 * A bulk enrolment plugin that allow teachers to massively enrol existing accounts to their courses,
 * with an option of adding every user to a group
 * Version for Moodle 1.9.x courtesy of Patrick POLLET & Valery FREMAUX  France, February 2010
 * Version for Moodle 2.x by pp@patrickpollet.net March 2012
 */

require(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once('lib.php');
require_once('editform_pay.php');

$generalid = required_param('general', PARAM_INT);
$paymentid = required_param('payment', PARAM_INT);

if ($general = $DB->get_record('course_extrasettings_general', array('id' => $generalid))) {
    $payment = $DB->get_record('course_extrasettings_payment', array('id' => $paymentid));
    $course = $DB->get_record('course', array('id' => $general->courseid));



    /// Security and access check

    require_course_login($course);
    $context = context_course::instance($course->id);
    require_capability('moodle/role:assign', $context);
}
/// Start making page
$PAGE->set_pagelayout('incourse');
$PAGE->set_url('/local/course_extrasettings/edit_pay.php');
$payment = 'Payments';
$PAGE->set_title($payment);


$mform = new course_extrasettings_form($CFG->wwwroot . '/local/course_extrasettings/edit_pay.php',
array('course' => $course, 'general' => $generalid, 'payment' => $paymentid));

$mform->set_data($payment);

if ($mform->is_cancelled()) {
    redirect(new moodle_url('/course'));
} else if ($data = $mform->get_data(false)) {
    $paymentupd = new stdClass();
    $paymentupd->id = $data->payment;
    $paymentupd->pcode  = $data->radioar['yesno'];
    if (isset($data->promocode)) {
        $paymentupd->promocode  = $data->promocode;
    } else {
        $paymentupd->promocode  = 'null';
    }
    if ($paymentupd->pcode != 1) {
        $paymentupd->discount   = 'null';
        $paymentupd->promoenddate   = 'null';
        $paymentupd->active = 'null';
        $paymentupd->promocode  = 'null';
    } else {
        $paymentupd->discount   = $data->discount;
        $paymentupd->promoenddate   = $data->promoenddate;
        $paymentupd->active = $data->active;
    }
    $paymentupdate = $DB->update_record('course_extrasettings_payment', $paymentupd);
}
if (isset($paymentupdate)) {
    if ($paymentupdate) {
        redirect($CFG->wwwroot . "/local/course_extrasettings/view_promo.php?id=$course->id");
    }
}
echo $OUTPUT->header();
$strinscriptions = get_string('course_extrasettings', 'local_course_extrasettings');
echo $OUTPUT->heading_with_help($strinscriptions, 'course_extrasettings', 'local_course_extrasettings', 'icon',
get_string('course_extrasettings', 'local_course_extrasettings'));
echo $OUTPUT->box (get_string('course_extrasettings_info', 'local_course_extrasettings'), 'center');
$mform->display();
echo $OUTPUT->footer();

