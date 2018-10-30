<?php

// This file is part of MoodleofIndia - http://moodleofindia.com/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.
    
require_once('./../../config.php');
require_once('forms/report_form.php');
$courseid = optional_param('courseid', 1, PARAM_INT);
$course = $DB->get_record('course', array('id' => $courseid));
require_course_login($course);
$context = context_course::instance($course->id);
require_capability('moodle/course:update', $context);
$url = new moodle_url($CFG->wwwroot.'/local/ediscount/reports.php',['courseid' => $courseid]);
/// Start making page
$PAGE->set_pagelayout('course');
$PAGE->set_context($context);
$PAGE->set_url($url);
$PAGE->requires->jquery();
$PAGE->requires->js('/local/ediscount/scripts.js');
$PAGE->set_title(get_string('coursediscountreports', 'local_ediscount'));
$PAGE->set_heading(get_string('coursediscountreports', 'local_ediscount'));
echo $OUTPUT->header();
if(!is_siteadmin()) {
    throw new moodle_exception('nopermissiontoshow');
}
$selectform = new selectform($CFG->wwwroot.'/local/ediscount/report_list.php',$course,'post');
$records = null;
if ($selectform->is_cancelled()) {
    redirect(new \moodle_url($CFG->wwwroot));
} else if ($data = $selectform->get_data()) {
    $selectform = null;
    }
if ($selectform != null) {
    echo html_writer::tag('p', get_string('coursediscountreports', 'local_ediscount'), ['class'=>'lead bottomline']);
    $selectform->display();
}
echo $OUTPUT->footer();