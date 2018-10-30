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
require_once('forms/delete_form.php');
redirect_if_major_upgrade_required();
$id = required_param('id', PARAM_INT);    // Turn editing on and off
$courseid = required_param('courseid', PARAM_INT);    // Turn editing on and off
$course = $DB->get_record('course', array('id' => $courseid));
require_course_login($course);
$context = context_course::instance($course->id);
require_capability('moodle/course:update', $context);
$url = new moodle_url($CFG->wwwroot.'/local/ediscount/deletediscounts.php',['id'=>$id,'courseid'=>$courseid]);
$navlink = new moodle_url($CFG->wwwroot.'/local/ediscount/discounts.php',['courseid' => $courseid]);
$PAGE->set_pagelayout('course');
$PAGE->set_context($context);
$PAGE->set_url($navlink);
$PAGE->requires->jquery();
$PAGE->requires->js('/local/ediscount/scripts.js');
$PAGE->set_title(get_string('deletediscount', 'local_ediscount'));
$PAGE->set_heading(get_string('deletediscount', 'local_ediscount'));
$PAGE->navbar->add(get_string('deletediscount', 'local_ediscount'));

global $USER;
$deleteform = new delete_form($url,$course);
$discount = $DB->get_record('ediscount', array('id' => $id));
$data = new stdClass();
$data->id = $id;
$deleteform->set_data($discount);
$message = false;
if ($deleteform->is_cancelled()) {
    redirect(new \moodle_url($CFG->wwwroot.'/local/ediscount/discounts.php',array('courseid'=>$courseid)));
} else if ($data = $deleteform->get_data()) {        
    if ($DB->delete_records('ediscount', array('id'=>$data->id, 'userid' => $USER->id))) {
        $message = \html_writer::div(get_string("discountdeleted", "local_ediscount"), 'alert alert-success');
        $deleteform = null;
    }
}
echo $OUTPUT->header();
if($message) {
    redirect($navlink,$message,1);
}
if ($deleteform != null) {
    echo \html_writer::tag('p', get_string('deleteconfirm', 'local_ediscount'), ['class' => 'lead']);
    $deleteform->display();
}
echo $OUTPUT->footer();