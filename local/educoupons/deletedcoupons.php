<?php

// This file is part of MoodleofIndia - http://www.moodleofindia.com/
/**
 * This script implements the manage_license of the dashboard, and allows editing
 * of the fooboo License.
 *
 * Local Educoupons 
 * @author     Arjun Singh <arjunsingh@elearn10.com>
 * @package    local_educoupons
 * @copyright  20/10/2016 lms of india
 * @license    http://lmsofindia.com/
 */

require_once(dirname(__FILE__) . '../../../config.php');
require_once('forms/delete_form.php');
redirect_if_major_upgrade_required();
$id = required_param('id', PARAM_INT);       
$courseid = required_param('courseid', PARAM_INT);  
$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('course');
$url = new moodle_url($CFG->wwwroot.'/local/educoupons/editcoupons.php',['id' => $id]);
$navlink = new moodle_url($CFG->wwwroot.'/local/educoupons/coupons.php',['courseid' => $courseid]);
$redirecturl = new \moodle_url($CFG->wwwroot.'/local/educoupons/coupons.php',array('courseid' => $courseid));
$PAGE->set_url($navlink);
$PAGE->requires->jquery();
$PAGE->set_title(get_string('deletecoupon', 'local_educoupons'));
$PAGE->set_heading(get_string('deletecoupon', 'local_educoupons'));
$PAGE->navbar->add(get_string('deletecoupon', 'local_educoupons'));
global $USER;
$deleteform = new delete_form($url,$id);
$coupon = $DB->get_record('educoupons', array('id' => $id));
$deleteform->set_data($coupon);
$message = false;
if ($deleteform->is_cancelled()) {
    redirect(new \moodle_url($CFG->wwwroot.'/local/educoupons/coupons.php',array('courseid'=>$courseid)));
} else if ($data = $deleteform->get_data()) {  
	$data->id = $id;
	$data->deleted = 0;
	$data->timemodified = time();
	$data->userid = $USER->id;      
    if ($DB->update_record('educoupons', $data)){
        //$message = \html_writer::div(get_string("updateconfirm", "local_educoupons"), 'alert alert-success');
		redirect($redirecturl,get_string('updateconfirm','local_educoupons'));
        $deleteform = null;
    }
}
echo $OUTPUT->header();
if($message) {
    echo $message;
}
if ($deleteform != null) {
    echo \html_writer::tag('p', get_string('deleteconfirm', 'local_educoupons'), ['class' => 'lead']);
    $deleteform->display();
}
echo $OUTPUT->footer();