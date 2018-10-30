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
    
/**
 * Note class is build for Manage Notes (Create/Update/Delete)
 * @desc Note class have one parameterized constructor to receive global 
 *       resources.
 * 
 * @package    local_questionextend
 * @copyright  2015 MoodleOfIndia
 * @author     shambhu kumar
 * @license    MoodleOfIndia {@web http://www.moodleofindia.com}
 */
require_once('./../../config.php');
require_once('forms/report_form.php');
try{
    $courseid = required_param('courses',PARAM_INT);
    $enrolid  = required_param('enrol',PARAM_INT);
} catch (Exception $ex) {
    redirect( new moodle_url($CFG->wwwroot.'/local/ediscount/reports.php'));
}

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
echo html_writer::tag('p', get_string('coursediscountreports', 'local_ediscount'), ['class'=>'lead bottomline']);
$records = $DB->get_records('ediscount',['courseid' => $courseid, 'enrolid' => $enrolid]);
$selectform = null;
if($records != null) {
      $table = new html_table();
      $table->head = (array) get_strings(['sl', 'promotionscode', 'promotionsstartdate', 'promotionsenddate', 'discount', 'ause','active', 'course', 'enrol', 'action'], 'local_ediscount');
      $table->warp = array(null, 'nowrap');
      $sl = 1;
      foreach ($records as $key => $row) {
          $status= html_writer::tag('span', get_string('active', 'local_ediscount'), array('class' => 'label label-success'));
          if(!$row->ppflag) {
              $status= html_writer::tag('span', get_string('inactive', 'local_ediscount'), array('class' => 'label label-success'));
          }               
          $table->data[] = array(
                                  $sl++,
                                  $row->ppcode,
                                  userdate($row->ppstartdate, '%d-%m-%Y'),
                                  userdate($row->ppenddate, '%d-%m-%Y'),
                                  $row->ppcent.get_string('percent', 'local_ediscount'),
                                  $row->ppuse,
                                  $status,
                                  $course->fullname,
                                  $DB->get_record('enrol', array('id' => $row->enrolid))->name,
                                  html_writer::link(new moodle_url($CFG->wwwroot.'/local/ediscount/editdiscounts.php',['id'=>$row->id, 'courseid' =>$courseid, 'enrolid'=>$row->enrolid]), '<i class="icon icon-edit"></i>').
                                  html_writer::link(new moodle_url($CFG->wwwroot.'/local/ediscount/deletediscounts.php',['id'=>$row->id, 'courseid' =>$courseid, 'enrolid'=>$row->enrolid]), '<i class="icon icon-trash"></i>')
                                );
      }
      echo html_writer::table($table);
} else {
    echo html_writer::div(get_string("nodiscountfound", "local_ediscount"), 'alert alert-warning');
}
echo $OUTPUT->footer();