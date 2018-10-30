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
 * @package    local_myanalytics
 * @copyright  2015 MoodleOfIndia
 * @author     shambhu kumar
 * @license    MoodleOfIndia {@web http://www.moodleofindia.com}
 */

require_once('./../../../config.php');
include("../locallib.php");
require_login();
function lang($lang) {
    return  get_string($lang,'local_myanalytics');
}
require_once("{$CFG->libdir}/datalib.php");
require_once("{$CFG->dirroot}/local/myanalytics/classes/site.php");
$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('analytics_report');
$PAGE->set_title(lang('myfavourite'));
$PAGE->requires->jquery();
$PAGE->requires->js('/local/myanalytics/js/course_level.js');
$PAGE->requires->css('/local/myanalytics/css/course_level.css');
//$PAGE->requires->css('/local/myanalytics/css/bootstrap.min.css');
$PAGE->set_heading(lang('myfavourite'));
$PAGE->set_url($CFG->wwwroot.'/local/myanalytics/site/favourites.php');
echo $OUTPUT->header();
echo html_writer::start_div('row');
echo html_writer::start_div('col-md-12');
echo html_writer::label('<i class="fa fa-star"></i> ' .lang('myfavourite'), '',true,['class'=>'lead bottomline']);
$fav = \myanalytics\sitelevel\site_report::view_fav();
if($fav != null) {
    $table =  new \html_table();
    $table->attributes = ['class'=>'table table-striped  no-footer tablcs'];
    $table->head = [lang('sl'), lang('label'), lang('level')];
    $table->size = ['20%', '50%', '30%'];
    $sl =1;
    foreach ($fav as  $value) {
       $key = 1;
       $table->data[] = [$sl++,  html_writer::link(new moodle_url($CFG->wwwroot.'/local/myanalytics/'.$value->link), $value->label),$value->level];
    }
    echo \html_writer::table($table);
} else {
    echo html_writer::div(lang('nofavavailable'), 'alert alert-warning');
}
echo html_writer::end_div();
echo html_writer::end_div();
echo $OUTPUT->footer();