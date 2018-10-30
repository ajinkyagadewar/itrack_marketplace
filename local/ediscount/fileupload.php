<?php

// This file is part of MoodleofIndia - http://www.moodleofindia.com/
/**
 * My Moodle --  View License  dashboard
 *
 * - only the admin and teacher can see their own dashboard
 * - Gives detail Information view about client license
 * 
 *
 * This script implements the manage_license of the dashboard, and allows display of fooboo License
 * 
 *
 * @package    block_manage_license
 * @copyright  moodleofindia  {@link  http://www.moodleofindia.com}
 * @license     http://www.moodleofindia.comcopyleft/gpl.html GNU GPL v3 or later
 * @author     moodleofindia<http://www.moodleofindia.com/>
 */
require_once(dirname(__FILE__) . '../../../config.php');
require_once(dirname(__FILE__) . '../../../my/lib.php');
require_once($CFG->libdir . '/adminlib.php');
require_once($CFG->libdir . '/csvlib.class.php');
require_once('uploadform.php');
redirect_if_major_upgrade_required();

// TODO Add sesskey check to edit
$edit = optional_param('edit', null, PARAM_BOOL);    // Turn editing on and off
$uploadid = optional_param('id', null, PARAM_BOOL);    // Turn editing on and off

require_login();

$strmymoodle = get_string('myhome');

if (isguestuser()) {  // Force them to see system default, no editing allowed
    // If guests are not allowed my moodle, send them to front page.
    if (empty($CFG->allowguestmymoodle)) {
        redirect(new moodle_url('/', array('redirect' => 0)));
    }

    $userid = NULL;
    $USER->editing = $edit = 0;  // Just in case
    $context = context_system::instance();
    $PAGE->set_blocks_editing_capability('moodle/blocks/paypal_promo:configsyspages');  // unlikely :)
    $header = "$SITE->shortname: $strmymoodle (GUEST)";
}
else {        // We are trying to view or edit our own My Moodle page
    $userid = $USER->id;  // Owner of the page
    $context = context_user::instance($USER->id);
    $PAGE->set_blocks_editing_capability('moodle/my:manageblocks');
    $header = "$SITE->shortname: $strmymoodle";
}

// Get the My Moodle page info.  Should always return something unless the database is broken.
if (!$currentpage = my_get_page($userid, MY_PAGE_PRIVATE)) {
    print_error('mymoodlesetup');
}

if (!$currentpage->userid) {
    $context = context_system::instance();  // So we even see non-sticky blocks
}

// Start setting up the page
$params = array();
$PAGE->set_context($context);
$PAGE->set_url('/blocks/paypal_promo/fileupload.php', $params);
$PAGE->set_pagelayout('standard');
$PAGE->set_pagetype('view License');
$PAGE->blocks->add_region('content');
$PAGE->set_subpage($currentpage->id);
$PAGE->set_title($header);
$PAGE->set_heading($header);

if (!isguestuser()) {   // Skip default home page for guests
    if (get_home_page() != HOMEPAGE_MY) {
        if (optional_param('setdefaulthome', false, PARAM_BOOL)) {
            set_user_preference('user_home_page_preference', HOMEPAGE_MY);
        }
        else if (!empty($CFG->defaulthomepage) && $CFG->defaulthomepage == HOMEPAGE_USER) {
            $PAGE->settingsnav->get('usercurrentsettings')->add(get_string('makethismyhome'), new moodle_url('/my/', array('setdefaulthome' => true)), navigation_node::TYPE_SETTING);
        }
    }
}

// Toggle the editing state and switches
if ($PAGE->user_allowed_editing()) {
    if ($edit !== null) {             // Editing state was specified
        $USER->editing = $edit;       // Change editing state
        if (!$currentpage->userid && $edit) {
            // If we are viewing a system page as ordinary user, and the user turns
            // editing on, copy the system pages as new user pages, and get the
            // new page record
            if (!$currentpage = my_copy_page($USER->id, MY_PAGE_PRIVATE)) {
                print_error('mymoodlesetup');
            }
            $context = context_user::instance($USER->id);
            $PAGE->set_context($context);
            $PAGE->set_subpage($currentpage->id);
        }
    }
    else {                          // Editing state is in session
        if ($currentpage->userid) {   // It's a page we can edit, so load from session
            if (!empty($USER->editing)) {
                $edit = 1;
            }
            else {
                $edit = 0;
            }
        }
        else {                      // It's a system page and they are not allowed to edit system pages
            $USER->editing = $edit = 0;          // Disable editing completely, just to be safe
        }
    }

    // Add button for editing page
    $params = array('edit' => !$edit);

    if (!$currentpage->userid) {
        // viewing a system page -- let the user customise it
        $editstring = get_string('updatemymoodleon');
        $params['edit'] = 1;
    }
    else if (empty($edit)) {
        $editstring = get_string('updatemymoodleon');
    }
    else {
        $editstring = get_string('updatemymoodleoff');
    }

    $url = new moodle_url("$CFG->wwwroot/my/index.php", $params);
    $button = $OUTPUT->single_button($url, $editstring);
    $PAGE->set_button($button);
}
else {
    $USER->editing = $edit = 0;
}

// HACK WARNING!  This loads up all this page's blocks in the system context
if ($currentpage->userid == 0) {
    $CFG->blockmanagerclass = 'my_syspage_block_manager';
}
//addingbreadcrumb
$PAGE->navbar->add('Partner License Report', new moodle_url('/blocks/paypal_promo/viewlicense.php'));

echo $OUTPUT->header();
$courses = enrol_get_my_courses();
$isstudent = false;
foreach ($courses as $course) {
    $context = get_context_instance(CONTEXT_COURSE, $course->id);
    if (!has_capability('moodle/course:update', $context)) {
        $isstudent = true;
    }
}
if ($isstudent) {
    echo '<p style="color:red">You are not supposed to open this page.</p>';
    exit;
}
//echo $OUTPUT->blocks_for_region('content');
$mform = new admin_uploadpromo_form();
if ($uploadid) {
    if ($formdata = $mform->get_data()) {
        $iid = csv_import_reader::get_new_iid('uploaduser');
        $cir = new csv_import_reader($iid, 'uploaduser');
        $content = $mform->get_file_content('userfile');
        $readcount = $cir->load_csv_content($content, 'UTF-8', 'comma');
    }
}
else {
    $mform->display();
}
if (isset($readcount)) {
    $data = array();
    $cir->init();
    $linenum = 1; //column header is first line
    $noerror = true; // Keep status of any error.
    $einsert = new stdClass();
    $eupdate = new stdClass();
    $format = 'd-m-Y';
    while ($linenum <= $readcount and $fields = $cir->next()) {
        $linenum++;
        $strdate = DateTime::createFromFormat($format, $fields['3']);
        $strdates = $strdate->format('d-m-Y') . "\n";
        $ppstartdate = str_replace("00", "20", "$strdates");
        $enddate = DateTime::createFromFormat($format, $fields['4']);
        $enddates = $enddate->format('d-m-Y') . "\n";
        $ppenddate = str_replace("00", "20", "$enddates");

        $einsert->ppcode = $fields['0'];
        $einsert->ppcent = $fields['1'];
        $einsert->ppuse = $fields['2'];
        $einsert->ppstartdate = strtotime($ppstartdate);
        $einsert->ppenddate = strtotime($ppenddate);
        $einsert->ppflag = $fields['5'];
        $einsert->ppreason = $fields['6'];
        $einsert->ppaplied = $fields['7'];

        $paypalexits = $DB->record_exists('enrol_paypal_promo', array('ppcode' => $fields['0']));
        if ($paypalexits) {
            $paypalid = $DB->get_record('enrol_paypal_promo', array('ppcode' => $fields['0']));


            //var_dump(strtotime($ppstartdate));
            //var_dump(strtotime($ppenddate));
            $eupdate->id = $paypalid->id;
            $eupdate->ppcent = $fields['1'];
            $eupdate->ppuse = $fields['2'];
            $eupdate->ppstartdate = strtotime($ppstartdate);
            $eupdate->ppenddate = strtotime($ppenddate);
            $eupdate->ppflag = $fields['5'];
            $eupdate->ppreason = $fields['6'];
            $eupdate->ppaplied = $fields['7'];

            $paypalupdate = $DB->update_record('enrol_paypal_promo', $eupdate);
        }
        else {
            $einsertid = $DB->insert_record('enrol_paypal_promo', $einsert);
        }
    }
}

if (isset($einsertid) || isset($paypalupdate)) {
    if (isset($paypalupdate)) {
        $status = "Promotions Updated Successfully";
    }
    elseif ($einsertid) {
        $status = "Promotions Updated Successfully";
    }
    $cir->close();
    $cir->cleanup(true);
}
if (isset($status)) {
    //$uploadurl = "$CFG->wwwroot/blocks/paypal_promo/viewlicense.php?status=$status";
    //header("Location:$uploadurl");
    //exit;
    echo '<div style="color:green"><h5> Promotions Updated  Successfully</h5></div>';
}
echo $OUTPUT->footer();