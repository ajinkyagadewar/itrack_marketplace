<?php

// This file is part of MoodleofIndia - http://www.moodleofindia.com/
/**
 * My Moodle --  Manage License  dashboard
 *
 * - only the admin can see their own dashboard
 * - Admin and teacher can add any blocks they want
 * - the administrators can only update license of any user
 * 
 *
 * This script implements the manage_license of the dashboard, and allows editing
 * of the fooboo License.
 *
 *
 * @package    block_manage_license
 * @copyright  moodleofindia  {@link  http://www.moodleofindia.com}
 * @license     http://www.moodleofindia.comcopyleft/gpl.html GNU GPL v3 or later
 * @author     moodleofindia<http://www.moodleofindia.com/>
 */
require_once(dirname(__FILE__) . '../../../config.php');
require_once('forms/add_form.php');
redirect_if_major_upgrade_required();
$courseid = optional_param('courseid', null, PARAM_INT);    // Turn editing on and off
$course = $DB->get_record('course', array('id' => $courseid));
require_course_login($course);
$context = context_course::instance($course->id);
require_capability('moodle/course:update', $context);
$url = new moodle_url($CFG->wwwroot.'/local/ediscount/adddiscounts.php',['courseid' => $courseid]);
$navlink = new moodle_url($CFG->wwwroot.'/local/ediscount/discounts.php',['courseid' => $courseid]);
$PAGE->set_pagelayout('course');
$PAGE->set_context($context);
$PAGE->set_url($url);
$PAGE->requires->jquery();
$PAGE->requires->js('/local/ediscount/scripts.js');
$PAGE->set_title(get_string('creatediscount', 'local_ediscount'));
$PAGE->set_heading(get_string('creatediscount', 'local_ediscount'));
global $USER;
$message = false;
$addform = new add_form($url,$course);
$addform->is_validated();
if ($addform->is_cancelled()) {
    redirect(new \moodle_url($CFG->wwwroot.'/course/view.php',['id'=>$courseid]));
}
else if ($data = $addform->get_data()) {
    $data->userid = $USER->id;
    $data->timecreated = time();
    if ($DB->insert_record('ediscount', $data)) {
        $message = \html_writer::div(get_string("discountcreated", "local_ediscount"), 'alert alert-success');
        $addform = null;
    }
}
echo $OUTPUT->header();
if($message){
    redirect($navlink,$message,1);
}
if ($addform != null) {
    echo \html_writer::tag('p', get_string('creatediscount', 'local_ediscount'), ['class' => 'lead bottomline']);
    echo $addform->display();
}
echo $OUTPUT->footer();