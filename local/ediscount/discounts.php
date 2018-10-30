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
require_once('forms/select_enrol_form.php');
require_once(dirname(__FILE__) . '../../../config.php');
require_once('forms/add_form.php');
redirect_if_major_upgrade_required();
$courseid = optional_param('courseid', null, PARAM_INT);    // Turn editing on and off
$course = $DB->get_record('course', array('id' => $courseid));
require_course_login($course);
$context = context_course::instance($course->id);
require_capability('moodle/course:update', $context);
$url = new moodle_url($CFG->wwwroot.'/local/ediscount/discounts.php',['courseid' => $courseid]);
$newurl = new moodle_url($CFG->wwwroot.'/local/ediscount/adddiscounts.php',['courseid' => $courseid]);
/// Start making page
$PAGE->set_pagelayout('course');
$PAGE->set_context($context);
$PAGE->set_url($url);
$PAGE->requires->jquery();
$PAGE->requires->js('/local/ediscount/scripts.js');
$PAGE->set_title(get_string('viewdiscounts', 'local_ediscount'));
$PAGE->set_heading(get_string('viewdiscounts', 'local_ediscount'));
$records = null;
$records = $DB->get_records('ediscount',['courseid' => $courseid]);
//print_object($records);
echo $OUTPUT->header();
echo html_writer::tag('p', get_string('viewdiscounts', 'local_ediscount'), ['class'=>'lead bottomline']);
if($records != null) {
      $table = new html_table();    
      $table->head = (array) get_strings(['sl', 'promotionscode', 'promotionsstartdate', 'promotionsenddate', 'discount', 'use', 'active', 'action'], 'local_ediscount');
      $table->warp = array(null, 'nowrap');
      $sl = 1;
      foreach ($records as $row) {
         if(($row->status) == '1' ){
           $status= html_writer::tag('span', get_string('active', 'local_ediscount'), array('class' => 'label label-success'));
         }else{
           $status= html_writer::tag('span', get_string('inactive', 'local_ediscount'), array('class' => 'label label-danger'));
         }
          if(!$row->ppuse) {
              $status= html_writer::tag('span', get_string('inactive', 'local_ediscount'), array('class' => 'label label-success'));
          } 
          if($row->ppuse == 'M') {
            $type = 'Multiple';
          }else{
            $type = 'Single';
          }             
          $table->data[] = array(
                                  $sl++,
                                  $row->ppcode,
                                  userdate($row->ppstartdate, '%d-%m-%Y'),
                                  userdate($row->ppenddate, '%d-%m-%Y'),
                                  $row->ppcent.get_string('percent', 'local_ediscount'),
                                  $type,
                                  $status,
                                  html_writer::link(new moodle_url($CFG->wwwroot.'/local/ediscount/editdiscounts.php',['id'=>$row->id, 'courseid' => $courseid]), '<span style="font-size:30px;padding-right:10px;display: -webkit-inline-box;margin-top: -12px;">&#x270D;</span>').
                                  html_writer::link(new moodle_url($CFG->wwwroot.'/local/ediscount/deletediscounts.php',['id'=>$row->id, 'courseid' => $courseid]), '<span style="font-size:20px;display: -webkit-inline-box;padding-right:10px;margin-top: -12px;">&#x1F5D1;</span>').
                                  html_writer::link(new moodle_url($CFG->wwwroot.'/local/ediscount/assigndiscoounts.php',['id'=>$row->id, 'courseid' => $courseid]), '<span style="font-size:20px;display: -webkit-inline-box;margin-top: -12px;">&#x260D;</span>'));
      }            
      echo html_writer::table($table);
      echo html_writer::tag('button','Add More Coupons', array("onclick"=>"window.location='{$newurl}';","class"=>"btn btn-outline-primary"));
} else{
    echo \html_writer::div(get_string("discountnotfound", "local_ediscount"), 'alert alert-warning');
    echo html_writer::tag('button','Add Coupons', array("onclick"=>"window.location='{$newurl}';","class"=>"btn btn-outline-primary"));
}
echo $OUTPUT->footer();