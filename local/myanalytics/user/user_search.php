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
function lang($lang) {
    return get_string($lang,'local_myanalytics');
}
require_login();
require_once("{$CFG->libdir}/datalib.php");
$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('analytics_report');
$PAGE->set_title(lang('userlevelreport'));
$PAGE->requires->jquery();
$PAGE->requires->js(new moodle_url($CFG->wwwroot.'/local/myanalytics/js/user_search.js'));
//$PAGE->requires->css(new moodle_url($CFG->wwwroot.'/local/myanalytics/css/bootstrap.min.css'));
$PAGE->set_heading(lang('userlevelreport'));
$PAGE->set_url($CFG->wwwroot . '/local/myanalytics/user/user_search.php');
echo $OUTPUT->header();
global $USER;
$visibleUsers = all_visible_roles();
// Check whether user is teacher or admin, student.
if ($visibleUsers || is_siteadmin()) {
    if ($visibleUsers) {
        $rowxtraclass = 'teacherrowdiv';
    } else if (is_siteadmin()) {
        $rowxtraclass = 'adminrowdiv';
    } else {
        $rowxtraclass = 'unknown';
    }
    $button = '<button class="btn btn-success" id="generate-report" type="button">Generate report</button>';
    $notetousers = '<div class="notetouser">'.get_string('typesearch', 'local_myanalytics').'</div>';
    $reportbtn = 'hide';
    $searchquery = '<input class="form-control search-input-box" type="text" id="search-query">';
} else {
    $url = $CFG->wwwroot.'/local/myanalytics/user/user_info.php?id='.$USER->id;
    $button = '<a href="'.$url.'"><button class="btn btn-success">Generate report</button></a>';
    $notetousers = '';
    $reportbtn = '';
    $rowxtraclass = 'studentrowdiv';
    $searchquery = '<input class="form-control search-input-box" type="text" id="search-query" value="'.$USER->firstname.' '.$USER->lastname.'" readonly="readonly">
                    <input type="hidden" id="self" value="1"/>';
}

echo html_writer::start_div('row', array('class' => $rowxtraclass));
echo html_writer::start_div('col-md-11');
echo html_writer::label('<i class="fa fa-users "></i>&nbsp;&nbsp;'.lang('usersearch'), '',true,['class'=>'lead bottomline']);
echo html_writer::end_div();
echo html_writer::end_div();
?>
<div class="row <?php echo $rowxtraclass; ?>">
    <div class="col-md-6">
       <div class="form-group">
            <label>Search option</label>
            <label class="radio-inline">
                <input type="radio" checked="checked" value="name" id="optionsRadiosInline1" name="option">Name
            </label>
            <label class="radio-inline">
                <input type="radio" value="email" id="optionsRadiosInline2" name="option"> Email 
            </label>
            <label class="radio-inline">
                <input type="radio" value="username" id="optionsRadiosInline3" name="option">Username
            </label>
        </div>
        <?php echo $notetousers; ?>
        <div class="error-div">        
        </div>
        
        <div class="form-group input-group" id="formgroupid">
            <?php echo $searchquery; ?>
            <span class="input-group-btn">
                <label class="control-label" for="inputError"></label>
            </span>
        </div>
        <div class="search-response">
        </div>
    </div>
</div>
<div class="row <?php echo $rowxtraclass; ?>">
    <div class="col-md-6">
        <div id="results">    
        </div>
        <div class="<?php echo $reportbtn ?>" id="button-div">
            <?php echo $button; ?>
        </div>
    </div>    
</div>
<script type="text/javascript">    
   var searchUrl = '<?php echo $CFG->wwwroot.'/local/myanalytics/ajax/user_search.php';?>';
   var imagepath = '<?php echo $CFG->wwwroot.'/local/myanalytics/pix/sm-ajax-loader.gif';?>';
   var destinationUrl = '<?php echo $CFG->wwwroot.'/local/myanalytics/user/user_info.php?id=';?>';
</script>
<?php
echo $OUTPUT->footer();