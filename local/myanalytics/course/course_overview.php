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
if (!is_siteadmin()) {
    echo die(lang('unauthorisedaccess'));
}
require_once("{$CFG->libdir}/datalib.php");

function lang($lang) {
    return get_string($lang, 'local_myanalytics');
}

//require_login();
require_once("{$CFG->dirroot}/local/myanalytics/classes/site.php");
$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout(get_layout());
$PAGE->requires->jquery();
$PAGE->requires->js('/local/myanalytics/js/cdn/jsapi.js', true);
$PAGE->requires->js('/local/myanalytics/js/fav.js');
$PAGE->requires->js('/local/myanalytics/js/course_level.js');
$PAGE->requires->js('/local/myanalytics/js/cdn/jquery.dataTables.min.js', true);
$PAGE->requires->js('/local/myanalytics/js/cdn/dataTables.bootstrap.js', true);
$PAGE->requires->js('/local/myanalytics/js/cdn/dataTables.fixedColumns.min.js', true);
$PAGE->requires->js('/local/myanalytics/tabletool/js/dataTables.tableTools.js', true);
$PAGE->requires->css('/local/myanalytics/css/course_level.css');
//$PAGE->requires->css('/local/myanalytics/css/bootstrap.min.css');
$PAGE->requires->css('/local/myanalytics/css/cdn/dataTables.bootstrap.min.css');
$PAGE->requires->css('/local/myanalytics/css/cdn/fixedColumns.bootstrap.min.css');
$PAGE->requires->css('/local/myanalytics/tabletool/css/dataTables.tableTools.css');
$PAGE->set_title(lang('courseoverview'));
$PAGE->set_heading(lang('courseoverview'));
$PAGE->set_url($CFG->wwwroot . '/local/myanalytics/course/course_overview.php');
echo $OUTPUT->header();
echo html_writer::start_div('row');
echo html_writer::start_div('col-md-10 col-sm-8 col-xs-9');
echo html_writer::label("<i class='fa fa-cogs'></i>&nbsp;&nbsp;" . '' . lang('courseoverview'), '', true, ['class' => 'lead bottomline']);
echo html_writer::end_div();
$link = 'course/course_overview.php';
$flag = \myanalytics\sitelevel\site_report::check_fav($link);
echo html_writer::start_div('col-md-2 col-sm-4 col-xs-3');
echo html_writer::checkbox('Add favourite', $link, $flag, lang('addfav'), ['id' => 'fav-check']);
echo html_writer::end_div();
echo html_writer::end_div();

echo html_writer::start_div('row');
echo html_writer::start_div('col-md-6');
echo html_writer::start_div('panel panel-warning');
echo html_writer::label("<i class='fa fa-bar-chart'></i>&nbsp;&nbsp;" . '' . lang('ccc'), '', true, ['class' => 'panel-heading leadwdt']);
echo html_writer::div('', '', ['id' => 'coursecomplete', 'class' => 'panel-body']);
echo html_writer::end_div();
echo html_writer::end_div();
echo html_writer::start_div('col-md-6');
echo html_writer::start_div('panel panel-warning');
echo html_writer::label("<i class='fa fa-pie-chart'></i>&nbsp;&nbsp;" . '' . lang('categoryincourses'), '', true, ['class' => 'panel-heading leadwdt']);
echo html_writer::div('', '', ['id' => 'categorycourses', 'class' => 'panel-body']);
echo html_writer::end_div();
echo html_writer::end_div();
echo html_writer::end_div();

echo html_writer::start_div('row');
echo html_writer::start_div('col-md-12');
echo html_writer::start_div('panel panel-warning');
echo html_writer::label("<i class='fa fa-group'></i>&nbsp;&nbsp;" . '' . lang('courseanduser'), '', true, ['class' => 'panel-heading leadwdt']);
$table = new html_table();
$table->id = 'coursesuserdetails';
$table->size = ['35%', '20%', '20%', '15%', '10%'];
$table->attributes = ['class' => 'table table-striped table-bordered'];
$table->head = [lang('course'), lang('assignedpeople'), lang('completedpeople'), lang('completedper'), lang('active')];
echo html_writer::table($table);
echo html_writer::end_div();
echo html_writer::end_div();
echo html_writer::end_div();
?>
<script type="text/javascript">
    var fav = {
        'level': 'course',
        'addUrl': '<?php echo $CFG->wwwroot . '/local/myanalytics/ajax/site_ajax.php?mode=ADD_FAV'; ?>',
        'deleteUrl': '<?php echo $CFG->wwwroot . '/local/myanalytics/ajax/site_ajax.php?mode=REMOVE_FAV'; ?>'
    }

    var pie = {
        'url': '<?php echo $CFG->wwwroot . '/local/myanalytics/ajax/course_ajax.php?mode=CATEGORY_COURSE' ?>',
        'chartdiv': 'categorycourses',
        'title': '<?php echo lang('categoryincourses'); ?>',
    };
    $(document).ready(function () {
        $('#coursesuserdetails').dataTable({
            "processing": true,
            "serverSide": true,
            "aLengthMenu": [[10, 25, 50, 75, 100, 150, 200, -1], [10, 25, 50, 75, 100, 150, 200, "All"]],
            "ajax": {
                "url": "<?php echo $CFG->wwwroot . '/local/myanalytics/ajax/course_ajax.php?mode=COURSE_OVERVIEW' ?>",
                "type": "POST"
            },
            "dom": '<"top"f>T<"clear">lrtip',
            "tableTools": {
                "sSwfPath": "<?php echo $CFG->wwwroot; ?>/local/myanalytics/tabletool/swf/copy_csv_xls_pdf.swf"
            },
            "columns": [
                {"data": "course"},
                {"data": "assigned"},
                {"data": "completed"},
                {"data": "completedpercent"},
                {"data": "active"}
            ],
            "columnDefs": [{
                    "targets": 0,
                    "render": function (data, type, row, meta) {
                        var id = row['id'];
                        return '<a href="<?php echo $CFG->wwwroot; ?>/course/view.php?id=' + id + '">' + data + '</a>';
                    }
                }]
        });
    });

    google.load("visualization", "1", {packages: ["corechart"]});
    google.setOnLoadCallback(drawPieChart);
    function drawPieChart() {

        var jsonData = $.ajax({
            url: pie.url,
            dataType: "json",
            async: false
        }).responseText;

        var data = new google.visualization.DataTable(jsonData);

        var options = {
            width: '100%',
            height: 300,
            legend: 'bottom',
            bar: {groupWidth: "40%"},
            chartArea: {top: 50, 'width': '100%', left: 10},
            animation: {duration: 1000, easing: 'out', }
        };

        var chart = new google.visualization.PieChart(document.getElementById(pie.chartdiv));
        chart.draw(data, options);
    }
    google.load("visualization", "1", {packages: ["corechart"]});
    google.setOnLoadCallback(drawChart);
    function drawChart() {
        var pie = {
            'url': '<?php echo $CFG->wwwroot . '/local/myanalytics/ajax/course_ajax.php?mode=COURSE_COMPLETED' ?>',
            'chartdiv': 'coursecomplete',
            'title': '<?php echo lang('coursecompleted'); ?>',
        };
        var jsonData = $.ajax({
            url: pie.url,
            dataType: "json",
            async: false
        }).responseText;

        var data = new google.visualization.DataTable(jsonData);

        var options = {
            width: '100%',
            height: data.getNumberOfRows() * 50,
            bar: {groupWidth: "95%"},
            legend: {position: "none"},
            hAxis: {gridlines: {color: '#000000', count: 5}},
            vAxis: {textStyle: {color: 'red', count: 5, italic: true}},
            chartArea: {top: 50, height: data.getNumberOfRows() * 15},
        };
        var chart = new google.visualization.BarChart(document.getElementById(pie.chartdiv));
        chart.draw(data, options);
    }
</script>
<?php
echo $OUTPUT->footer();
