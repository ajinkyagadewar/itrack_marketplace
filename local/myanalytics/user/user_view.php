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

$userid = optional_param('userid', null, PARAM_INT);
$courseid = optional_param('courseid', null, PARAM_INT);
function lang($lang) {
    return  get_string($lang,'local_myanalytics');
}
require_once("{$CFG->libdir}/datalib.php");
require_once("{$CFG->dirroot}/local/myanalytics/classes/user_report.php");
require_once("{$CFG->dirroot}/local/myanalytics/classes/site.php");
$context = context_course::instance($courseid, MUST_EXIST);
$PAGE->set_context($context);
$PAGE->set_pagelayout('analytics_report');
$PAGE->set_title(lang('courselevelreport'));
$PAGE->requires->jquery();
$PAGE->requires->js('/local/myanalytics/js/cdn/jsapi.js',true);
$PAGE->requires->js('/local/myanalytics/js/course_level.js');
$PAGE->requires->js('/local/myanalytics/js/stack_bar.js');
$PAGE->requires->js('/local/myanalytics/js/fav.js');
$PAGE->requires->js('/local/myanalytics/js/pie_chart.js');
$PAGE->requires->js('/local/myanalytics/js/cdn/jquery.dataTables.min.js',true);
$PAGE->requires->js('/local/myanalytics/js/cdn/dataTables.bootstrap.js',true);
$PAGE->requires->js('/local/myanalytics/js/cdn/dataTables.fixedColumns.min.js',true);
$PAGE->requires->js('/local/myanalytics/tabletool/js/dataTables.tableTools.js',true);
//$PAGE->requires->css('/local/myanalytics/css/bootstrap.min.css');
$PAGE->requires->css('/local/myanalytics/css/cdn/dataTables.bootstrap.min.css');
$PAGE->requires->css('/local/myanalytics/css/course_level.css');
//$PAGE->requires->css('/local/myanalytics/css/bootstrap.min.css');
$PAGE->requires->css('/local/myanalytics/tabletool/css/dataTables.tableTools.css');
$PAGE->set_heading(lang('courselevelreport'));
$PAGE->set_url($CFG->wwwroot . '/local/myanalytics/course/course_level_report.php');
echo $OUTPUT->header();
if(!has_capability('moodle/course:update',$context)){
   echo html_writer::div(lang('permissiondeniedcourse'), 'alert alert-warning');
} else {
$user = new \myanalytics\userlevel\user_report($userid, $courseid);
?>
<style type="text/css">
   td.details-control {
        background: url('<?php echo $CFG->wwwroot.'/local/myanalytics/pix/details_open.png';?>') no-repeat center center;
        cursor: pointer;
    }
    tr.shown td.details-control {
        background: url('<?php echo $CFG->wwwroot.'/local/myanalytics/pix/details_close.png';?>') no-repeat center center;
    }
</style>
<?php
echo html_writer::start_div('row');
echo html_writer::start_div('col-md-10 col-sm-9 col-xs-9');
echo html_writer::label("<i class='fa fa-dashboard'></i>&nbsp;&nbsp;".''.lang('userlrf').' '.$user->get_fullname().' - '.$user->get_courses_name(), '',true,['class'=>'lead bottomline']);
echo html_writer::end_div();
$link = 'user/user_view.php?userid='.$userid.'&courseid='.$courseid;
$flag = \myanalytics\sitelevel\site_report::check_fav($link);
echo html_writer::start_div('col-md-2 col-sm-3 col-xs-3');
echo html_writer::checkbox('Add favourite',$link ,$flag, lang('addfav'),['id'=>'fav-check']);
echo html_writer::end_div();
echo html_writer::end_div();

echo html_writer::start_div('row');
echo html_writer::start_div('col-md-5');
echo html_writer::start_div('panel panel-warning');
echo html_writer::label("<i class='fa fa-pie-chart'></i>&nbsp;&nbsp;".''.lang('activitycompletenotcomplete'), '',true,['class'=>'panel-heading leadwdt']);
echo html_writer::div('','',['id'=>'usercompletion', 'class' => 'panel-body']);
echo html_writer::end_div();
echo html_writer::end_div();
echo html_writer::start_div('col-md-7');
echo html_writer::start_div('panel panel-warning');
echo html_writer::label("<i class='fa fa-bar-chart'></i>&nbsp;&nbsp;".''.lang('quizscorechart'), '',true,['class'=>'panel-heading leadwdt']);
echo html_writer::div('','',['id'=>'useractivity', 'class' => 'panel-body']);
echo html_writer::end_div();
echo html_writer::end_div();
echo html_writer::end_div();

echo html_writer::start_div('row');
echo html_writer::start_div('col-md-12');
echo html_writer::start_div('panel-warning');
echo html_writer::label("<i class='fa fa-random'></i>&nbsp;&nbsp;".''.lang('quizattemptdetails'), '',true,['class'=>'panel-heading leadwdt']);
echo html_writer::end_div();
echo html_writer::end_div();
echo html_writer::end_div();

echo html_writer::start_div('row');
echo html_writer::start_div('col-md-12');
echo html_writer::start_div('panel panel-warning');
$table = new html_table();
$table->id = 'quiztable';
$table->attributes = ['class'=>'table  table-bordered display'];
$table->head = ['',lang('quizname'),  lang('score')];
echo html_writer::table($table);
echo html_writer::end_div();
echo html_writer::end_div();
echo html_writer::end_div();

echo html_writer::start_div('row');
echo html_writer::start_div('col-md-12');
echo html_writer::start_div('panel panel-warning');
echo html_writer::label("<i class='fa fa-random'></i>&nbsp;&nbsp;".''.lang('allactivitydetails'), '',true,['class'=>'panel-heading leadwdt']);
$table = new html_table();
$table->id = 'activitytable';
$table->attributes = ['class'=>'table table-striped table-bordered nowrap'];
$lang = get_strings(['activityname', 'activitytype','score', 'completion','timespent','duetime'], 'local_myanalytics');
$table->head = array($lang->activityname, $lang->activitytype, $lang->score, $lang->completion,$lang->timespent, $lang->duetime);
$records = $user->get_course_activity();  
if(!empty($records) && $records != null ) {
    foreach ($records as $key => $row) {
              $table->data[] = [ html_writer::link(new moodle_url('/mod/'.$row['type'].'/view.php?id='.$row['id']), $row['name']), $row['type'], $row['score'], $row['completionstate'],$row['timespent'],$row['duetime']];
    }
    unset($records);
} else {
    $table->data[] = [lang('norecordavailable')];
}
echo html_writer::table($table);
echo html_writer::end_div();
echo html_writer::end_div();
echo html_writer::end_div();
}
?>
<script type="text/javascript">
 var stack_bar =   {
            'url':'<?php echo $CFG->wwwroot.'/local/myanalytics/ajax/course_ajax.php?mode=COURSE_QUIZ_MODULE_USER&courseid='.$courseid.'&userid='.$userid;?>',
            'chartdiv':'useractivity',
            'title':'<?php echo lang('quizgraph');?>'
        };  
 var pie =   {
            'url':'<?php echo $CFG->wwwroot.'/local/myanalytics/ajax/user_ajax.php?mode=USER_COURSE_COMPLETED&id='.$userid.'&courseid='.$courseid;?>',
            'chartdiv':'usercompletion',
            'title':'<?php echo lang('coursecompleted');?>'
        };
 var fav = {
            'level' : 'user',
            'addUrl':'<?php echo $CFG->wwwroot.'/local/myanalytics/ajax/site_ajax.php?mode=ADD_FAV';?>',
            'deleteUrl': '<?php echo $CFG->wwwroot.'/local/myanalytics/ajax/site_ajax.php?mode=REMOVE_FAV';?>'
           }  

        /* Formatting function for row details - modify as you need */
    function format ( course ) {
        var table = '<table class="subtable table-striped">';
            table += '<thead><tr><th width="2%">Attempt</th><th width="14%">Time start</th><th width="14%">Time finish</th><th>Grade</th><th>Correct</th><th>Incorrect</th><th>Not answer</th><th>Review</th></tr></thead>';
        for(var key in course.list) {
            table += '<tr>'+'<td>'+course.list[key].attempt+'</td>'+'<td>'+course.list[key].timestart+'</td>'+'<td>'+course.list[key].timestart+'</td>'+'<td>'+course.list[key].sumgrades+'</td>'+'<td>'+course.list[key].correct+'</td>'+'<td>'+course.list[key].incorrect+'</td>'+'<td>'+course.list[key].notanswer+'</td>'+'<td><a href="<?php echo $CFG->wwwroot;?>/mod/quiz/review.php?attempt='+course.list[key].id+'">Review</a></td>'+'</tr>';
        }
        table +='</table>';
        return table;
    }
 
    $(document).ready(function() {
        var table2 =  $('#activitytable').DataTable({
             "dom": '<"top"f>T<"clear">lrtip',
            "tableTools": {
                "sSwfPath": "<?php echo $CFG->wwwroot;?>/local/myanalytics/tabletool/swf/copy_csv_xls_pdf.swf"
            }
         });
    var table = $('#quiztable').DataTable( {
        "ajax": "<?php echo $CFG->wwwroot.'/local/myanalytics/ajax/user_ajax.php?mode=USER_QUIZ_INFO&id='.$userid.'&courseid='.$courseid;?>",
        "columns": [
            {
                "className":      'details-control',
                "orderable":      false,
                "data":           null,
                "defaultContent": ''
            },
            { "data": "name" },
            { "data": "score" }
        ],
        "order": [[1, 'asc']],
        "dom": '<"top"f>T<"clear">lrtip',
        "tableTools": {
            "sSwfPath": "<?php echo $CFG->wwwroot;?>/local/myanalytics/tabletool/swf/copy_csv_xls_pdf.swf"
        }
    } );
     
    // Add event listener for opening and closing details
    $('#quiztable tbody').on('click', 'td.details-control', function () {
        var tr = $(this).closest('tr');
        var row = table.row( tr );
 
        if ( row.child.isShown() ) {
            // This row is already open - close it
            row.child.hide();
            tr.removeClass('shown');
        }
        else {
            // Open this row
            row.child( format(row.data()) ).show();
            tr.addClass('shown');
        }
    } );
} );
</script>
<?php
echo $OUTPUT->footer();