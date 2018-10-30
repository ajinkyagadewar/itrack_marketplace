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
global $USER;
require_once("{$CFG->libdir}/datalib.php");
if (!is_siteadmin($USER)) {
    die(get_string('unauthorisedaccess', 'local_myanalytics'));
}
require_once("{$CFG->dirroot}/local/myanalytics/classes/site.php");

function lang($lang) {
    return get_string($lang, 'local_myanalytics');
}

$PAGE->set_context(context_system::instance());
//echo count_role_users(5, context_course::instance(3), false);
//echo count_role_users(3, context_course::instance(3), false);
//count_enrolled_users($context, $withcapability, $groupid, $onlyactive);
$PAGE->set_pagelayout(get_layout());
$PAGE->requires->jquery();
$PAGE->requires->js('/local/myanalytics/js/cdn/jsapi.js', true);
$PAGE->requires->js('/local/myanalytics/js/column_chart.js');
$PAGE->requires->js('/local/myanalytics/js/pie_chart.js');
$PAGE->requires->js('/local/myanalytics/js/area_chart.js');
$PAGE->requires->js('/local/myanalytics/js/fav.js');
$PAGE->set_title(lang('siteoverview'));
$PAGE->set_heading(lang('siteoverview'));
$PAGE->set_url($CFG->wwwroot . '/local/myanalytics/site/site_overview.php');
$siteoverview = lang('siteoverview');
//$PAGE->requires->css('/local/myanalytics/css/bootstrap.min.css');
$PAGE->requires->css('/local/myanalytics/styles.css', true);
$PAGE->requires->css('/local/myanalytics/js/cdn/tooltip.css', true);
echo $OUTPUT->header();
$site = new \myanalytics\sitelevel\site_report();
echo html_writer::start_div('row');
echo html_writer::start_div('col-md-10 col-sm-8 col-xs-8');
echo html_writer::label("<i class='fa fa-globe'></i>&nbsp;&nbsp;".''.lang('siteoverview'), '', true, ['class' => 'lead bottomline']);
echo html_writer::end_div();
$link = 'site/site_overview.php';
$flag = \myanalytics\sitelevel\site_report::check_fav($link);
echo html_writer::start_div('col-md-2 col-sm-4 col-xs-4');
echo html_writer::checkbox('Add favourite', $link, $flag, lang('addfav'), ['id' => 'fav-check']);
echo html_writer::end_div();
echo html_writer::end_div();

echo html_writer::start_div('row');
echo html_writer::start_div('col-md-6');
echo html_writer::start_div('panel panel-warning');
echo html_writer::label("<i class='fa fa-bar-chart'></i>&nbsp;&nbsp;".''.lang('userbasedoncourse'), '', true, ['class' => 'panel-heading leadwdt']);
echo html_writer::div('', '', ['id' => 'user_chart', 'class' => 'panel-body']);
echo html_writer::end_div();
echo html_writer::end_div();
echo html_writer::start_div('col-md-6');
echo html_writer::start_div('panel panel-warning');
echo html_writer::label("<i class='fa fa-pie-chart'></i>&nbsp;&nbsp;".''.lang('coursebasedonuser'), '', true, ['class' => 'panel-heading leadwdt']);
echo html_writer::div('', '', ['id' => 'course_chart', 'class' => 'panel-body']);
echo html_writer::end_div();
echo html_writer::end_div();
echo html_writer::end_div();
?>
<div class="row">
    <div class="col-md-6">
        <div class="panel panel-warning">
            <div class="panel-heading">
                <i class="fa fa-book fa-fw"></i> <?php echo lang('numofcourses'); ?><span class="badge pull-right"><?php echo $site->get_course()->total_courses();?></span>
            </div>
            <div class="panel-body">
                <div class="list-group">
                    <p class="list-group-item">
                        <?php echo lang('newcoursecreatedin30'); ?>
                        <span class="pull-right text-muted small"><span class="badge"><?php echo count($site->get_course()->course_created_in()); ?></span>
                        </span>
                    </p>
                    <p class="list-group-item">
                        <?php echo lang('notpublishcourse'); ?>
                        <span class="pull-right text-muted small"><span class="badge"><?php echo count($site->get_course()->not_pulish_course()); ?></span>
                        </span>
                    </p>
                    <p class="list-group-item">
                        <?php echo lang('mostpopularcourse'); ?>
                        <span class="pull-right text-muted small"><span class="badge"><?php echo $site->get_course()->most_popular_course(0, 1); ?></span>
                        </span>
                    </p>
                </div>
            </div>
        </div>       
    </div>  
    <div class="col-md-6">
        <div class="panel panel-warning">
            <div class="panel-heading">
                <i class="fa fa-users fa-fw"></i> <?php echo lang('numoflearners'); ?> <span class="badge pull-right"><?php echo $site->get_user()->number_of_learners(); ?></span>
            </div>
            <div class="panel-body">
                <div class="list-group">
                    <p class="list-group-item">
                        <?php echo lang('activelearners'); ?>
                        <span class="pull-right text-muted small"><span class="badge"><?php echo $site->get_user()->get_active_user(); ?></span>
                        </span>
                    </p>
                    <p class="list-group-item">
                        <?php echo lang('notconfirm'); ?> 
                        <span class="pull-right text-muted small"><span class="badge"><?php echo $site->get_user()->get_not_confirmed(); ?></span>
                        </span>
                    </p>
                    <p class="list-group-item">
                        <?php echo lang('numberofnewsignup'); ?>
                        <span class="pull-right text-muted small"><span class="badge"><?php echo $site->get_user()->get_signup_users_in(); ?></span>
                        </span>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>    
<div class="row">
    <div class="col-md-6">
        <div class="panel panel-warning">
            <div class="panel-heading">               
                <i class="fa fa-users fa-fw"></i> <?php echo lang('numoftutors'); ?> <span class="badge pull-right"><?php echo $site->get_user()->get_tutors_count(); ?></span>
            </div>
            <div class="panel-body">
                <div class="list-group">
                    <p class="list-group-item">
                        <?php echo lang('numberofactivetutors'); ?> <span class="badge pull-right"><?php echo $site->get_user()->get_active_tutors(); ?></span>
                        <span class="pull-right text-muted small"><em></em>
                        </span>
                    </p>
                    <p class="list-group-item">
                        <?php echo lang('mostactivetutor'); ?><span class="badge pull-right"><?php echo $site->get_user()->get_active_tutor()['name']; ?></span>
                        <span class="pull-right text-muted small"><em></em>
                        </span>
                    </p>
                    <p class="list-group-item">
                        <?php echo lang('mostpopulartutor'); ?> <span class="badge pull-right"> <?php echo $site->get_user()->get_popular_tutor()['name']; ?></span>
                        <span class="pull-right text-muted small">
                        </span>
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="panel panel-warning">
            <div class="panel-heading">               
                <i class="fa fa-calendar-o"></i> <?php echo lang('numofuniquelogin'); ?> <span class="badge pull-right"></span>
            </div>
            <div class="panel-body">
                <div id="unique_session"></div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-warning">
            <div class="panel-heading">               
                <i class="fa fa-book"></i>  <?php echo lang('top5course'); ?>
            </div>
            <div class="panel-body">
                <?php
                $table = new html_table();
                $table->attributes = ['class' => 'table table-striped  no-footer'];
                $lang = get_strings(['coursename', 'numofusers', 'numberofactivity', 'startdate'], 'local_myanalytics');
                $table->head = array($lang->coursename, $lang->numofusers, $lang->numberofactivity, $lang->startdate);
                foreach ($site->get_course()->get_top_courses(false, false, 5) as $key => $row) {
                    $activityflag = $site->get_course()->get_activity($row['id']);
                    $activity = $activityflag ? $activityflag : lang('completionnotenabled');
                    $date = $row['startdate'] != 0 ? date('m M Y', $row['startdate']) : '-';
                    $table->data[] = [html_writer::link(new moodle_url($CFG->wwwroot . '/course/view.php?id=' . $row['id']), $row['name']), '<span class="badge">' . $row['count'] . '</span>', $activity, $date];
                }
                echo html_writer::start_tag('div');
                echo html_writer::table($table);
                echo html_writer::end_tag('div');
                ?>
            </div>
        </div>
    </div>  
</div> 
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-warning">
            <div class="panel-heading">               
                <i class="fa fa-users"></i> <?php echo lang('top5users'); ?>
            </div>
            <div class="panel-body">
                <?php
                $table = null;
                $table = new html_table();
                $table->attributes = ['class' => 'table table-striped  no-footer'];
                $lang = get_strings(['image', 'name', 'email', 'enrolledcourses', 'sociallinks'], 'local_myanalytics');
                $table->head = array($lang->image, $lang->name, $lang->email, $lang->enrolledcourses, $lang->sociallinks);
                foreach ($site->get_user()->get_top_users_in_course(false, false, 5) as $key => $row) {
                    $sociallink = '';
                    foreach ($row['social'] as $name => $links) {
                        if ($links != null) {
//                            if (strpos($links,'https://') !== false) {
//                                $sitelinks = str_replace("https://","",$links);
//                                $alink = '<a target="_blank" href="///';
//                            } else if (strpos($links,'http://') !== false) {
//                                $sitelinks = str_replace("http://","",$links);
//                                $alink = '<a target="_blank" href="http://';
//                            } else {
//                                $sitelinks = 'http://'.$links;
//                                $alink = '<a target="_blank" href="';
//                            }
                            $alink = '<a target="_blank" href="';
                            $sociallink .=  $alink. $links . '"><img height="24" src="' . $CFG->wwwroot . '/local/myanalytics/pix/' . $name . '.png' . '" alt="' . $name . '"></img></a>';
//                            $sociallink .=  $alink. $sitelinks . '"><img height="24" src="' . $CFG->wwwroot . '/local/myanalytics/pix/' . $name . '.png' . '" alt="' . $name . '"></img></a>';
                        }
                    }
                    $img = '';
                    if (method_exists('html_writer', 'img')) {
                        $img = html_writer::img($CFG->wwwroot . '/user/pix.php?file=/' . $row['id'] . '/f1.jpg', $row['name'], array('height' => 32, 'class' => 'img-circle'));
                    } else {
                        $img = '<img src="' . $CFG->wwwroot . '/user/pix.php?file=/' . $row['id'] . '/f1.jpg" height="32" class="img-circle"></img>';
                    }

                    $table->data[] = [$img, html_writer::link(new moodle_url($CFG->wwwroot . '/user/profile.php?id=' . $row['id']), $row['name'], ['target' => '_blank']), $row['email'], '<span class="badge">' . $row['count'] . '</span>', $sociallink];
                }
                echo html_writer::start_tag('div');
                echo html_writer::table($table);
                echo html_writer::end_tag('div');
                ?>
            </div>
        </div>    
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-warning">
            <div class="panel-heading">               
                <i class="fa fa-laptop"></i> <?php echo lang('courseecd'); ?>
            </div>
            <div class="panel-body">
                <div id="course_enroll_completion">    
                </div>
            </div>
        </div>
    </div> 
</div> 
<script type="text/javascript">
    var column = {
        'url': '<?php echo $CFG->wwwroot . '/local/myanalytics/ajax/site_ajax.php?mode=TOP5_USER' ?>',
        'chartdiv': 'user_chart',
        'title': null
    };
    var pie = {
        'url': '<?php echo $CFG->wwwroot . '/local/myanalytics/ajax/site_ajax.php?mode=TOP5_COURSE' ?>',
        'chartdiv': 'course_chart',
        'title': null
    };
    var area = {
        'url': '<?php echo $CFG->wwwroot . '/local/myanalytics/ajax/site_ajax.php?mode=COURSE_COMPLETION' ?>',
        'chartdiv': 'course_enroll_completion',
        'title': '<?php echo lang('numofuniquelogin'); ?>'
    };
    var fav = {
        'level': 'site',
        'addUrl': '<?php echo $CFG->wwwroot . '/local/myanalytics/ajax/site_ajax.php?mode=ADD_FAV'; ?>',
        'deleteUrl': '<?php echo $CFG->wwwroot . '/local/myanalytics/ajax/site_ajax.php?mode=REMOVE_FAV'; ?>'
    }

    google.load("visualization", "1", {packages: ["corechart"]});
    google.setOnLoadCallback(drawAreaChart);
    function drawAreaChart() {

        var jsonData = $.ajax({
            url: '<?php echo $CFG->wwwroot . '/local/myanalytics/ajax/site_ajax.php?mode=UNIQUE_LOGIN' ?>',
            dataType: "json",
            async: false
        }).responseText;

        var data = new google.visualization.DataTable(jsonData);
        var options = {
            bar: {groupWidth: "20%"},
            'chartArea': {'width': '90%', 'height': '80%'},
            legend: {position: 'bottom'},
            pointSize: 5,
            series: {
                0: {pointShape: 'circle'},
                1: {pointShape: 'circle'}
            },
            animation: {duration: 1000, easing: 'out'}
        };

        var chart = new google.visualization.AreaChart(document.getElementById('unique_session'));
        chart.draw(data, options);
    }
</script>
<?php
unset($site);
echo $OUTPUT->footer();
