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
 * 
 * @package    local_myanalytics
 * @copyright  2015 MoodleOfIndia
 * @author     shambhu kumar
 * @license    MoodleOfIndia {@web http://www.moodleofindia.com}
 */
require_once('./../../../config.php');
include("../locallib.php");
require_login();
$courseid = required_param('id', PARAM_INT);
$activityid = null;

function lang($lang) {
    return get_string($lang, 'local_myanalytics');
}

require_once("{$CFG->dirroot}/local/myanalytics/classes/site.php");
require_once("{$CFG->libdir}/datalib.php");
require_once("{$CFG->dirroot}/local/myanalytics/classes/course.php");
$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('analytics_report');
$PAGE->set_title(lang('courselevelreport'));
$PAGE->requires->jquery();
$PAGE->requires->js('/local/myanalytics/js/cdn/jsapi.js', true);
$PAGE->requires->js('/local/myanalytics/js/course_level.js');
$PAGE->requires->js('/local/myanalytics/js/pie_chart.js');
$PAGE->requires->js('/local/myanalytics/js/stack_bar.js');
$PAGE->requires->js('/local/myanalytics/js/fav.js');
$PAGE->requires->js('/local/myanalytics/js/cdn/jquery.dataTables.min.js', true);
$PAGE->requires->js('/local/myanalytics/js/cdn/dataTables.bootstrap.js', true);
$PAGE->requires->js('/local/myanalytics/js/cdn/dataTables.fixedColumns.min.js', true);
$PAGE->requires->js('/local/myanalytics/tabletool/js/dataTables.tableTools.js', true);
$PAGE->requires->css('/local/myanalytics/css/course_level.css');
//$PAGE->requires->css('/local/myanalytics/css/bootstrap.min.css');
$PAGE->requires->css('/local/myanalytics/css/cdn/dataTables.bootstrap.min.css');
$PAGE->requires->css('/local/myanalytics/css/cdn/fixedColumns.bootstrap.min.css');
$PAGE->requires->css('/local/myanalytics/tabletool/css/dataTables.tableTools.css');
$PAGE->set_heading(lang('courselevelreport'));
$PAGE->set_url($CFG->wwwroot . '/local/myanalytics/course/course_level_report.php');
echo $OUTPUT->header();
$context = \context_course::instance($courseid);
if (!has_capability('moodle/course:update', $context)) {
    echo html_writer::div(lang('permissiondeniedcourse'), 'alert alert-warning');
} else {
    $course = new \myanalytics\courselevel\course_report($courseid);
    $link = 'course/course_status.php?id=' . $courseid;
    $flag = \myanalytics\sitelevel\site_report::check_fav($link);
    $ajax = $CFG->wwwroot . '/local/myanalytics/ajax/';
    echo html_writer::start_div('row');
    echo html_writer::start_div('col-md-10 col-sm-8 col-xs-9');
    echo html_writer::label("<i class='fa fa-dashboard'></i>&nbsp;&nbsp;" . '' . lang('courselevelreport') . ' for - ' . $course->get_courses_name(), '', true, ['class' => 'lead bottomline']);
    echo html_writer::end_div();
    echo html_writer::start_div('col-md-2 col-sm-4 col-xs-3');
    echo html_writer::checkbox('Add favourite', $link, $flag, lang('addfav'), ['id' => 'fav-check']);
    echo html_writer::end_div();
    echo html_writer::end_div();

    echo html_writer::start_div('row');
    echo html_writer::start_div('col-md-5');
    echo html_writer::start_div('panel panel-warning');
    echo html_writer::label("<i class='fa fa-pie-chart'></i>&nbsp;&nbsp;" . '' . lang('ccc'), '', true, ['class' => 'panel-heading leadwdt']);
    echo html_writer::div('', '', ['id' => 'coursecomplete', 'class' => 'panel-body']);
    echo html_writer::end_div();
    echo html_writer::end_div();
    echo html_writer::start_div('col-md-7');
    echo html_writer::start_div('panel panel-warning');
    echo html_writer::label("<i class='fa fa-bar-chart'></i>&nbsp;&nbsp;" . '' . lang('coursequizreport'), '', true, ['class' => 'panel-heading leadwdt']);
    echo html_writer::div('', '', ['id' => 'quizreport', 'class' => 'panel-body']);
    echo html_writer::end_div();
    echo html_writer::end_div();
    echo html_writer::end_div();

    echo html_writer::start_div('row');
    echo html_writer::start_div('col-md-7');
    echo html_writer::start_div('panel panel-warning');
    echo html_writer::label("<i class='fa fa-bell-o'></i>&nbsp;&nbsp;" . '' . lang('upcomingactivity'), '', true, ['class' => 'panel-heading leadwdt']);
    $eventtbl = new \html_table();
    $eventtbl->attributes = ['class' => 'table table-striped nowrap panel-body'];
    $eventlist = (array) $course->upcoming_activity($courseid);
    if (!empty($eventlist)) {
        $eventtbl->head = [lang('eventname'), lang('eventdesc'), lang('eventtime')];
        foreach ($eventlist as $key => $event) {
            $eventtbl->data[] = [$event->name, substr($event->description, 0, 25), $event->time];
        }
        echo html_writer::table($eventtbl);
    } else {
        echo '<div class="panel-body"><p class="datanfound">Data Not Found</p></div>';
    }
    echo html_writer::end_div();
    echo html_writer::end_div();
    echo html_writer::start_div('col-md-5');
    echo html_writer::start_div('panel panel-warning');
    echo html_writer::label("<i class='fa fa-random'></i>&nbsp;&nbsp;" . '' . lang('discussionchart'), '', true, ['class' => 'panel-heading leadwdt']);
    echo html_writer::div('', '', ['id' => 'discussionchart', 'class' => 'panel-body']);
    echo html_writer::end_div();
    echo html_writer::end_div();
    echo html_writer::end_div();

    echo html_writer::start_div('row');
    $table = new \html_table();
    $table->id = 'reporttable';
    $table->attributes = ['class' => 'table table-striped table-bordered'];

    $table->head = $course->activity_list_head($courseid);
    echo html_writer::start_div('col-md-12');
    echo html_writer::start_div('panel panel-warning');
    echo html_writer::label("<i class='fa fa-th-list'></i>&nbsp;&nbsp;" . '' . lang('activitydetailsreport'), '', true, ['class' => 'panel-heading leadwdt']);
    if (sizeof($course->activity_list_head($courseid)) >= 2) {
        echo html_writer::table($table);
    } else {
        echo '<p id="ndf" class="nodatafound">No Data found</p>';
    }
    echo html_writer::end_div();
    echo html_writer::end_div();
}
?>
<script type="text/javascript">
    google.load("visualization", "1", {packages: ["corechart"]});
    google.setOnLoadCallback(drawPieChart);
    function drawPieChart() {
        var jsonData = $.ajax({
            url: '<?php echo $ajax . 'course_ajax.php?mode=DISUSSION_COURSE&id=' . $courseid; ?>',
            dataType: "json",
            async: false
        }).responseText;
        var myData = JSON.parse(jsonData);
        var data = new google.visualization.DataTable(jsonData);
        if (typeof myData["rows"] !== 'undefined') {
            var options = {
                title: '',
                width: '100%',
                height: 270,
                legend: 'bottom',
                bar: {groupWidth: "60%"},
                'chartArea': {'width': '90%', 'height': '80%'},
            };
            var chart = new google.visualization.PieChart(document.getElementById('discussionchart'));
            chart.draw(data, options);
        } else {
            document.getElementById('discussionchart').innerHTML = '<p class="datanfound">Data Not Found</p>';
        }
    }
    var stack_bar = {
        'url': '<?php echo $ajax . 'course_ajax.php?mode=COURSE_QUIZ_MODULE&id=' . $courseid; ?>',
        'chartdiv': 'quizreport',
        'title': ''
    };
    var pie = {
        'url': '<?php echo $ajax . 'course_ajax.php?mode=COURSE_COMPLETED_BY_ID&id=' . $courseid; ?>',
        'chartdiv': 'coursecomplete',
        'title': null,
        'color1': '#F00000',
        'color2': '#0033FF',
        'color3': '#33CC33',
    };
    var fav = {
        'level': 'course',
        'addUrl': '<?php echo $ajax . 'site_ajax.php?mode=ADD_FAV'; ?>',
        'deleteUrl': '<?php echo $ajax . 'site_ajax.php?mode=REMOVE_FAV'; ?>'
    }

    $(document).ready(function () {
        var oTable = $('#reporttable').dataTable({
            "processing": true,
            "serverSide": true,
            "aLengthMenu": [[10, 25, 50, 75, 100, 150, 200, -1], [10, 25, 50, 75, 100, 150, 200, "All"]],
            "ajax": {
                "url": "<?php echo $CFG->wwwroot . '/local/myanalytics/ajax/course_ajax.php?mode=ACTIVITY_LIST' ?>",
                "type": "POST",
                "data": function (d) {
                    d.id = <?php echo $courseid; ?>
                }
            },
            "dom": '<"top"f>T<"clear">lrtip',
            "tableTools": {
                "sSwfPath": "<?php echo $CFG->wwwroot; ?>/local/myanalytics/tabletool/swf/copy_csv_xls_pdf.swf"
            },
            "columnDefs": [
                {"width": "10% !important", "targets": 0}
            ],
            fixedColumns: true,
            "sScrollX": "100%",
            "sScrollXInner": "150%",
            "bScrollCollapse": true

        });
    });
</script>
<?php
echo $OUTPUT->footer();
