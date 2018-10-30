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
$courseid = optional_param('id', null, PARAM_INT);
$activity = strtolower(optional_param('activity', null, PARAM_RAW));

function lang($lang) {
    return get_string($lang, 'local_myanalytics');
}

require_once("{$CFG->libdir}/datalib.php");
require_once("{$CFG->dirroot}/local/myanalytics/classes/course.php");
require_once("{$CFG->dirroot}/local/myanalytics/classes/site.php");
$context = context_course::instance($courseid);
$PAGE->set_context($context);
$PAGE->set_pagelayout('analytics_report');
$PAGE->set_title(lang('activitylevelreport'));
$PAGE->requires->jquery();
$PAGE->requires->js('/local/myanalytics/js/cdn/jsapi.js', true);
$PAGE->requires->js('/local/myanalytics/js/course_level.js');
$PAGE->requires->js('/local/myanalytics/js/stack_bar.js');
$PAGE->requires->js('/local/myanalytics/js/fav.js');
$PAGE->requires->js('/local/myanalytics/js/cdn/jquery.dataTables.min.js', true);
$PAGE->requires->js('/local/myanalytics/tabletool/js/dataTables.tableTools.js', true);
$PAGE->requires->css('/local/myanalytics/css/course_level.css');
//$PAGE->requires->css('/local/myanalytics/css/bootstrap.min.css');
$PAGE->requires->css('/local/myanalytics/css/cdn/dataTables.bootstrap.min.css');
$PAGE->requires->css('/local/myanalytics/css/cdn/fixedColumns.bootstrap.min.css');
$PAGE->requires->css('/local/myanalytics/tabletool/css/dataTables.tableTools.css');
$PAGE->set_heading(lang('activitylevelreport'));
$PAGE->set_url($CFG->wwwroot . '/local/myanalytics/course/course_level_report.php');
echo $OUTPUT->header();
if (!has_capability('moodle/course:update', $context)) {
    echo html_writer::div(lang('permissiondeniedcourse'), 'alert alert-warning');
} else {
    $course = new \myanalytics\courselevel\course_report($courseid);
    $stacklinechart = $course->get_activity_grade_line($activity);
    $decode = json_decode($stacklinechart);
    if (!empty($decode->rows)) {
        $valueExist = sizeof($decode->rows);
    } else {
        $valueExist = 0;
    }

    $link = 'course/activity_view.php?&id=' . $courseid . '&activity=' . $activity;
    $flag = \myanalytics\sitelevel\site_report::check_fav($link);
    echo html_writer::start_div('row');
    echo html_writer::start_div('col-md-10 col-sm-8 col-xs-9');
    echo html_writer::label("<i class='fa fa-dashboard'></i>&nbsp;&nbsp;" . '' . 'Course - ' . $course->get_courses_name($courseid) . ' - ' . $activity . ' report', '', true, ['class' => 'lead bottomline']);
    echo html_writer::end_div();
    echo html_writer::start_div('col-md-2 col-sm-4 col-xs-3');
    echo html_writer::checkbox('Add favourite', $link, $flag, lang('addfav'), ['id' => 'fav-check']);
    echo html_writer::end_div();
    echo html_writer::end_div();

    echo html_writer::start_div('row');
    echo html_writer::start_div('col-md-6');
    echo html_writer::start_div('panel panel-warning');
    echo html_writer::label("<i class='fa fa-bar-chart'></i>&nbsp;&nbsp;" . '' . lang('activityselect') . ' - ' . $activity, '', true, ['class' => 'panel-heading leadwdt']);
    echo html_writer::div('', '', ['id' => 'activitylinechart', 'class' => 'panel-body']);
    echo html_writer::end_div();
    echo html_writer::end_div();
    echo html_writer::start_div('col-md-6');
    echo html_writer::start_div('panel panel-warning');
    echo html_writer::label("<i class='fa fa-pie-chart'></i>&nbsp;&nbsp;" . '' . lang('quizreport'), '', true, ['class' => 'panel-heading leadwdt']);
    echo html_writer::div('', '', ['id' => 'quiztable', 'class' => 'panel-body']);
    echo html_writer::end_div();
    echo html_writer::end_div();
    echo html_writer::end_div();

    if ($piechart = $course->get_activity_grade_pie($activity)) {
        $decodepie = json_decode($piechart);
        if (!empty($decodepie->rows)) {
            $valueExistPie = sizeof($decodepie->rows);
        } else {
            $valueExistPie = 0;
        }
        echo html_writer::start_div('row');
        echo html_writer::start_div('col-md-6');
        echo html_writer::start_div('panel panel-warning');
        echo html_writer::label(lang('activityattempt') . ' - ' . $activity, '', true, ['class' => 'panel-heading leadwdt']);
        echo html_writer::div('', '', ['id' => 'activitypiechart', 'class' => 'panel-body']);
        echo html_writer::end_div();
        echo html_writer::end_div();
        echo html_writer::end_div();
    }
    if ($record = $course->get_activity_grade_user($activity)) {
        $table = new \html_table();
        $table->id = 'reporttable';
        $table->attributes = ['class' => 'table table-striped table-bordered nowrap'];
        $table->head = $record['cols'];
        foreach ($record['rows'] as $key => $value) {
            $table->data[] = $value;
        }
        echo html_writer::start_div('row');
        echo html_writer::start_div('col-md-12');
        echo html_writer::start_div('panel panel-warning');
        echo html_writer::label(lang('usersgradereportfor') . ' - ' . $activity, '', true, ['class' => 'panel-heading leadwdt']);
        echo html_writer::table($table);
        echo html_writer::end_div();
        echo html_writer::end_div();
        echo html_writer::end_div();
    }
    unset($course);
}
?>
<script type="text/javascript">
    var stack_bar = {
        'url': '<?php echo $CFG->wwwroot . '/local/myanalytics/ajax/course_ajax.php?mode=COURSE_QUIZ_MODULE&id=' . $courseid; ?>',
        'chartdiv': 'quiztable',
        'title': ''
    };
    var fav = {
        'level': 'activity',
        'addUrl': '<?php echo $CFG->wwwroot . '/local/myanalytics/ajax/site_ajax.php?mode=ADD_FAV'; ?>',
        'deleteUrl': '<?php echo $CFG->wwwroot . '/local/myanalytics/ajax/site_ajax.php?mode=REMOVE_FAV'; ?>'
    }
    $(document).ready(function () {
        var table = $('#reporttable').DataTable({
            scrollY: "400px",
            scrollX: true,
            scrollCollapse: true,
            paging: true,
            fixedColumns: true,
            "dom": '<"top"f>T<"clear">lrtip',
            "tableTools": {
                "sSwfPath": "<?php echo $CFG->wwwroot; ?>/local/myanalytics/tabletool/swf/copy_csv_xls_pdf.swf"
            }
        });
    });
    google.load("visualization", "1.1", {packages: ["corechart", "bar"]});
    google.setOnLoadCallback(drawChart);
    function drawChart() {
        var data = new google.visualization.DataTable(<?php echo $stacklinechart; ?>);
        var sizeArray = <?php echo $valueExist ?>;
        if (sizeArray != 0) {
            var options = {
                width: '100%',
                height: 300,
                'legend': 'bottom',
                bar: {groupWidth: "50%"},
                'chartArea': {'width': '90%', 'height': '60%', 'top': 10, 'bottom': 10},
                animation: {duration: 1000, easing: 'out'}
            };
            var chart = new google.visualization.ColumnChart(document.getElementById('activitylinechart'));
            chart.draw(data, options);
        } else {
            document.getElementById('activitylinechart').innerHTML = '<p class="datanfound">Data Not Found</p>';
        }
    }
    google.load("visualization", "1", {packages: ["corechart"]});
    google.setOnLoadCallback(drawPieChart);
    function drawPieChart() {
        //  var cd = <?php //echo $piechart;            ?>;
        var data = new google.visualization.DataTable(<?php echo $piechart; ?>);

        //if (data.Gf.length != 0) {
        var sizeArray1 = <?php echo $valueExistPie ?>;
        console.log(sizeArray1);
        if (sizeArray1 != 0) {
            var options = {
                width: '100%',
                height: 300,
                legend: 'bottom',
                bar: {groupWidth: "40%"},
                'chartArea': {'width': '100%', 'height': '80%'},
                animation: {duration: 1000, easing: 'out'}
            };
            var chart = new google.visualization.PieChart(document.getElementById('activitypiechart'));
            chart.draw(data, options);
        } else {
            document.getElementById('activitypiechart').innerHTML = '<p class="datanfound">Data Not Found</p>';
        }
    }
</script>
<?php
echo $OUTPUT->footer();
