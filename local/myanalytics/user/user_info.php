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
 * @package    local_myanalytics-
 * @copyright  2015 MoodleOfIndia
 * @author     shambhu kumar
 * @license    MoodleOfIndia {@web http://www.moodleofindia.com}
 */
require_once('./../../../config.php');
require_login();
include("../locallib.php");
require_once("{$CFG->libdir}/datalib.php");
include_once($CFG->dirroot . '/local/myanalytics/classes/user_report.php');
require_once("{$CFG->dirroot}/local/myanalytics/classes/site.php");
$userid = optional_param('id', null, PARAM_INT);
$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('analytics_report');

function lang($lang) {
    return get_string($lang, 'local_myanalytics');
}

$PAGE->set_title(lang('userlevelreport'));
$PAGE->requires->jquery();
$PAGE->set_heading(lang('userlevelreport'));
$PAGE->set_url($CFG->wwwroot . '/local/myanalytics/user/user_search.php');
$visibleUsers = all_visible_roles();
if (!(is_siteadmin() || $visibleUsers)) {
    $PAGE->navbar->add('User Level Report', $CFG->wwwroot . '/local/myanalytics/user/user_search.php');
}
$PAGE->requires->js('/local/myanalytics/js/cdn/jsapi.js', true);
$PAGE->requires->js('/local/myanalytics/js/course_level.js');
$PAGE->requires->js('/local/myanalytics/js/fav.js');
$PAGE->requires->js('/local/myanalytics/js/cdn/jquery.dataTables.min.js', true);
$PAGE->requires->js('/local/myanalytics/js/cdn/dataTables.bootstrap.js', true);
$PAGE->requires->js('/local/myanalytics/js/cdn/dataTables.fixedColumns.min.js', true);
$PAGE->requires->js('/local/myanalytics/tabletool/js/dataTables.tableTools.js', true);
//$PAGE->requires->css('/local/myanalytics/css/bootstrap.min.css');
$PAGE->requires->css('/local/myanalytics/css/userinfo.css');
$PAGE->requires->css('/local/myanalytics/css/cdn/dataTables.bootstrap.min.css');
$PAGE->requires->css('/local/myanalytics/css/cdn/fixedColumns.bootstrap.min.css');
$PAGE->requires->css('/local/myanalytics/tabletool/css/dataTables.tableTools.css');
$CFG->ajaxpath = $CFG->wwwroot . '/local/myanalytics/ajax/user_ajax.php?mode=';
$obj = new \myanalytics\userlevel\user_report(optional_param('id', false, PARAM_RAW));
$obj->init();
$obj->init_course();
$completed = count($obj->course->user_curse_completed($userid));
?>
<style type="text/css">
    .subtable {
        margin-left: 66px;
    }
    td.details-control {
        background: url('<?php echo $CFG->wwwroot . '/local/myanalytics/pix/details_open.png'; ?>') no-repeat center center;
        cursor: pointer;
    }
    tr.shown td.details-control {
        background: url('<?php echo $CFG->wwwroot . '/local/myanalytics/pix/details_close.png'; ?>') no-repeat center center;
    }   
</style>
<?php
echo $OUTPUT->header();
echo html_writer::start_div('row');
echo html_writer::start_div('col-md-10 col-sm-8 col-xs-9');
echo html_writer::label("<i class='fa fa-dashboard'></i>&nbsp;&nbsp;" . '' . lang('reportfor') . ' - ' . $obj->get_fullname(), '', true, ['class' => 'lead bottomline']);
echo html_writer::end_div();
$link = 'user/user_info.php?id=' . $userid;
$flag = \myanalytics\sitelevel\site_report::check_fav($link);
echo html_writer::start_div('col-md-2 col-sm-4 col-xs-3');
echo html_writer::checkbox('Add favourite', $link, $flag, lang('addfav'), ['id' => 'fav-check']);
echo html_writer::end_div();
echo html_writer::end_div();

echo html_writer::start_div('row');
echo html_writer::start_div('col-md-6');
echo html_writer::start_div('panel panel-warning');
echo html_writer::label("<i class='fa fa-user'></i>&nbsp;&nbsp;" . '' . lang('userprofile'), '', true, ['class' => 'panel-heading leadwdt']);
?>
<div class="panel-body">
    <div class="col-md-4">
        <figure>
            <img src="<?php echo $CFG->wwwroot . '/user/pix.php?file=/' . $userid . '/f1.jpg'; ?>" alt="" class="img-circle img-responsive">
            <figcaption class="ratings">
                <p><strong>Role :</strong> <?php echo true ? 'Student' : 'Not approved'; ?> </p>
            </figcaption>
        </figure>
    </div>
    <div class="col-md-8">
        <h2><?php echo $obj->get_fullname(); ?></h2>
        <p><?php echo lang('email'); ?><strong> : <?php echo $obj->info->email; ?></strong> </p>
        <p><?php
            if (!empty($obj->info->icq)) {
                echo lang('phone') . '<strong>' . ' : ' . $obj->info->phone1 . '</strong>';
            }
            ?>
        <p>
            <?php
            if (!empty($obj->info->icq)) {
                echo '<a target=_blank href="https://www.facebook.com/' . $obj->info->icq . '"><img height="24" src="' . $CFG->wwwroot . '/local/myanalytics/pix/icq.png"></img></a>';
            }
            if (!empty($obj->info->skype)) {
                echo '<a target=_blank href="https://secure.skype.com/portal/' . $obj->info->skype . '"><img height="24" src="' . $CFG->wwwroot . '/local/myanalytics/pix/skype.png"></img></a>';
            }
            if (!empty($obj->info->skype)) {
                echo '<a target=_blank href="https://in.yahoo.com/' . $obj->info->yahoo . '"><img height="24" src="' . $CFG->wwwroot . '/local/myanalytics/pix/yahoo.png"></img></a>';
            }
            if (!empty($obj->info->skype)) {
                echo '<a target=_blank href="https://www.google.co.in/#q=' . $obj->info->msn . '"><img height="24" src="' . $CFG->wwwroot . '/local/myanalytics/pix/msn.png"></img></a>';
            }
            ?>    
        </p>
    </div>             
</div>                 
<?php
echo html_writer::end_div();
echo html_writer::end_div();
echo html_writer::start_div('col-md-6');
echo html_writer::start_div('panel panel-warning');
echo html_writer::label("<i class='fa fa-bar-chart'></i>&nbsp;&nbsp;" . '' . lang('coursegrade'), '', true, ['class' => 'panel-heading leadwdt']);
echo html_writer::div('', '', ['id' => 'usercoursesgrade', 'class' => 'panel-body']);
echo html_writer::end_div();
echo html_writer::end_div();
echo html_writer::end_div();
?>
<div class="row">
    <div class="col-md-12 pad0A">
        <div class="col-md-3">
            <div class="panel panel-default">
                <div class="panel-heading panelhdcs">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-book fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge"><?php echo $obj->enrolledcourses; ?></div>
                            <div>Course enrolled</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="panel panel-default">
                <div class="panel-heading panelhdcs">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-user fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge"><?php echo $completed; ?></div>
                            <div>Course completed</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="panel panel-default">
                <div class="panel-heading panelhdcs">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-desktop fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div><?php echo $obj->lastlogin; ?></div>
                            <br/>
                            <div>Last loggedin</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="panel panel-default">
                <div class="panel-heading panelhdcs">
                    <div class="row">
                        <div class="col-xs-12">

                            <?php $completedpercent = $completed != 0 ? round(($completed / $obj->enrolledcourses) * 100, 2) : 0; ?>
                            <p class="no-marg">
                                <strong><?php echo $completedpercent; ?> % of assigned courses have been completed</strong>
                            </p>
                            <div class="progress progress-striped active" style="margin-bottom:6px">
                                <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="<?php echo $completedpercent; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $completedpercent; ?>%">
                                    <span class="sr-only"><?php echo $completedpercent; ?> Complete</span>
                                </div>                            
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
echo html_writer::start_div('row');
echo html_writer::start_div('col-md-12');
echo html_writer::start_div('panel panel-warning');
echo html_writer::label("<i class='fa fa-database'></i>&nbsp;&nbsp;" . '' . lang('ccr'), '', true, ['class' => 'panel-heading leadwdt']);
$table = new html_table();
$table->id = 'courseactivity';
$table->attributes = ['class' => 'table  table-bordered display'];
$table->head = ['', lang('name'), lang('completion'), lang('coursetotal')];
echo html_writer::table($table);
echo html_writer::end_div();
echo html_writer::end_div();
echo html_writer::end_div();
?>
<script type="text/javascript">
    google.load("visualization", "1.1", {packages: ["corechart", "bar"]});
    google.setOnLoadCallback(drawColumnChart);
    function drawColumnChart() {
        var jsonData = $.ajax({
            url: '<?php echo $CFG->ajaxpath . 'GET_ENROLL_COURSE&id=' . $userid; ?>',
            dataType: "json",
            async: false
        }).responseText;
        var data = new google.visualization.DataTable(jsonData);
        //if (data.Gf.length != 0) {
        var myData = JSON.parse(jsonData);
        if ((myData["rows"].length !== 0) && (myData["rows"] !== "undefined")) {
            var options = {
                legend: {'position': 'none'},
                bar: {groupWidth: "20%"},
                'chartArea': {'width': '90%', 'height': '80%'},
                tooltip: {
                    'isHtml': true,
                    'textStyle': {'color': 'red', 'fontSize': '10px'},
                }
            };
            var chart = new google.visualization.ColumnChart(document.getElementById('usercoursesgrade'));
            chart.draw(data, options);

        } else {
            document.getElementById('usercoursesgrade').innerHTML = '<p class="datanfound">Data Not Found</p>';
        }
    }
    var fav = {
        'level': 'user',
        'addUrl': '<?php echo $CFG->wwwroot . '/local/myanalytics/ajax/site_ajax.php?mode=ADD_FAV'; ?>',
        'deleteUrl': '<?php echo $CFG->wwwroot . '/local/myanalytics/ajax/site_ajax.php?mode=REMOVE_FAV'; ?>'
    }

    /* Formatting function for row details - modify as you need */
    function format(course) {
        var table = '<table class="subtable">';
        table += '<thead><tr><th>Activity Name</th><th>Activity Type</th><th>Completion</th><th>Score</th><th>Timespent</th></tr></thead>';
        for (var key in course.activity) {
            console.log(course.activity);
            table += '<tr><td>' + course.activity[key].name + '</td>' + '<td>' + course.activity[key].type + '</td>' + '<td>' + course.activity[key].completionstate + '</td>' + '<td>' + course.activity[key].score + '</td>' + '<td>' + course.activity[key].timespent + '</td>' + '</tr>';
        }
        table += '</table>';
        return table;
    }
    $(document).ready(function () {
        var table = $('#courseactivity').DataTable({
            "ajax": "<?php echo $CFG->wwwroot . '/local/myanalytics/ajax/user_ajax.php?mode=ACTIVITY_LIST_IN_COURSES&id=' . $userid ?>",
            "columns": [
                {
                    "className": 'details-control',
                    "orderable": false,
                    "data": null,
                    "defaultContent": ''
                },
                {"data": "name"},
                {"data": "completion"},
                {"data": "coursetotal"}
            ],
            "dom": '<"top"f>T<"clear">lrtip',
            "tableTools": {
                "sSwfPath": "<?php echo $CFG->wwwroot; ?>/local/myanalytics/tabletool/swf/copy_csv_xls_pdf.swf"
            },
            "order": [[1, 'asc']],
            "columnDefs": [{
                    "targets": 1,
                    "render": function (data, type, row, meta) {
                        var id = row['id'];
                        return '<a href="<?php echo $CFG->wwwroot; ?>/course/view.php?id=' + id + '">' + data + '</a>';
                    }
                }]

        });
        // Add event listener for opening and closing details
        $('#courseactivity tbody').on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = table.row(tr);

            if (row.child.isShown()) {
                // This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
            } else {
                // Open this row
                row.child(format(row.data())).show();
                tr.addClass('shown');
            }
        });
    });
</script>
<?php
echo $OUTPUT->footer();
