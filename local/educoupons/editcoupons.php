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
require_once('forms/add_form.php');
redirect_if_major_upgrade_required();
$id = required_param('id', PARAM_INT);
$courseid = optional_param('courseid', null, PARAM_INT);  
$course = $DB->get_record('course', array('id' => $courseid));
require_course_login($course);
$PAGE->set_context(context_system::instance());
$url = new moodle_url($CFG->wwwroot.'/local/educoupons/editcoupons.php',['id' => $id]);
$navlink = new moodle_url($CFG->wwwroot.'/local/educoupons/coupons.php',['courseid' => $courseid]);
$redirecturl = new \moodle_url($CFG->wwwroot.'/local/educoupons/coupons.php',array('courseid' => $courseid));
$PAGE->set_pagelayout('course');
$PAGE->set_url($navlink);
$PAGE->requires->jquery();
$PAGE->set_title(get_string('editcoupon', 'local_educoupons'));
$PAGE->set_heading(get_string('editcoupon', 'local_educoupons'));
$PAGE->navbar->add(get_string('editcoupon', 'local_educoupons'));
global $USER,$DB;
$editform = new add_form($url,$id);
$coupon = $DB->get_record('educoupons', array('id' => $id));
$editform->set_data($coupon);
if ($editform->is_cancelled()) {
    redirect();
}
else if ($data = $editform->get_data()) {
    $data->id = $id;
    $data->timemodified = time();
    $data->userid = $USER->id;
    if ($DB->update_record('educoupons', $data)) {
    	$message = \html_writer::div(get_string("couponupdated", "local_educoupons"), 'alert alert-success');
		redirect($redirecturl,get_string('updateconfirm','local_educoupons'));
		$editform = null;
    }
}
echo $OUTPUT->header();
if ($editform != null) {
    echo \html_writer::tag('p', get_string('editcoupon', 'local_educoupons'), ['class' => 'lead bottomline']);
    $editform->display();
}
echo $OUTPUT->footer();