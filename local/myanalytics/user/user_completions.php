<?php
// This file is part of Moodle - http://www.moodle.org/
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
 * Home page of ereport 
 * @desc this file contains search user and display their activities.
 * 
 * @package    local_ereport
 * @copyright  2015 
 * @author     
 * @license    
 */
require_once('./../../../config.php');
include("../locallib.php");
require_login();

require_once("{$CFG->libdir}/datalib.php");
$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout("analytics_report");

$PAGE->requires->jquery();
$PAGE->requires->js('/local/myanalytics/js/completion.js');
$PAGE->set_title(get_string('activitycompletionreport', 'local_myanalytics'));
$PAGE->set_heading(get_string('activitycompletionreport', 'local_myanalytics'));
$PAGE->set_url($CFG->wwwroot . '/local/myanalytics/user/user_completions.php');
echo $OUTPUT->header();
//if (!is_siteadmin()) {
//    throw new moodle_exception('invalid');
//}

global $USER, $DB;
$userRole = all_visible_roles();
if (is_siteadmin() || $userRole) {
    $search = '<input type="text" list="users" id="username" class="form-control" placeholder="Type/Double Click to get User Name" autocomplete="off">';
    $userasad = '1';
    $userasstudent = '0';
} else {
    $search = '<input type="text" id="username" value="' . $USER->firstname . ' ' . $USER->lastname . '" class="form-control" disabled="disabled">';
    $userasstudent = '1';
    $userasad = '0';
}

$userid = $USER->id;

function alluser_search_in_courses($userid, $roleid) {
    global $DB;
    $courses = enrol_get_my_courses(['id']);
    $list = array();
    foreach ($courses as $id => $useless) {
        $context = \context_course::instance($id);
        //if(has_capability('moodle/course:update',$context, $userid)) {
        $sql = "SELECT u.id as id, u.firstname,u.lastname FROM {course} c
                        JOIN {context} ct ON c.id = ct.instanceid
                        JOIN {role_assignments} ra ON ra.contextid = ct.id
                        JOIN {user} u ON u.id = ra.userid
                        JOIN {role} r ON r.id = ra.roleid
                        WHERE c.id =$id AND r.id =$roleid ORDER BY u.id ASC";
        $results = $DB->get_records_sql($sql);
        foreach ($results as $userid => $row) {
            if (!array_key_exists($userid, $list)) {
                $list[$userid] = $row;
            }
        }

        //}
    }
    return $list;
}
?>
<div class="row bottomline">
    <div class="col-md-12">
        <p class="lead" style="margin-bottom: 0px; color:#000000 !important;">
            <i class='fa fa-dashboard'></i>&nbsp;&nbsp;
            <?php echo get_string('activitycompletionreport', 'local_myanalytics') . ': ' ?>
        </p>
    </div>
    <?php if (!user_has_role_assignment($USER->id, 5)) { ?>
        <div class="col-md-12">
            <div class="alert alert-info">
                <?php echo get_string('activity_cmp_rep', 'local_myanalytics'); ?>
            </div>
        </div>
    <?php } ?>
    <div class="col-md-12 ">
        <div class="col-md-6 pad0A">
            <div class="input-group">
                <?php echo $search; ?>
                <datalist id="users">
                    <?php
                    if (is_siteadmin()) {
                        $users = get_users(true, '', false, null, 'firstname ASC', '', '', '', '', $fields = 'id,firstname,lastname');
                        foreach ($users as $key => $rows) {
                            echo '<option value="' . $rows->firstname . ' ' . $rows->lastname . '" data-xyz="' . $rows->id . '"/>';
                        }
                    } else if ($userRole) {
                        $liststudent = alluser_search_in_courses($USER->id, 5);
                        foreach ($liststudent as $lists) {
                            echo '<option value="' . $lists->firstname . ' ' . $lists->lastname . '" data-xyz="' . $lists->id . '"/>';
                        }
                    } else {
                        $users = $DB->get_record('user', array('id' => $USER->id, 'confirmed' => 1));
                        echo '<option value="' . $users->firstname . ' ' . $users->lastname . '" data-xyz="' . $users->id . '"/>';
                    }
                    ?>           
                </datalist>
                <?php if (is_siteadmin() || $userRole) { ?>
                    <span class="input-group-btn">
                        <button class="btn btn-success" onclick="get_data()" type="button"><i class="fa fa-bar-chart-o fa-fw"></i> Generate report</button>
                    </span>
                <?php } else { ?>
                    <span class="input-group-btn">
                        <button onclick="get_data()" disabled="disabled" class="btn btn-success" type="button"><i class="fa fa-bar-chart-o fa-fw"></i> Generate report</button>
                    </span>
                <?php } ?>
            </div><!-- /input-group -->
        </div>
    </div>
</div>    
<div class="row1">
    <div id="main-panel" class="col-md-12">       
    </div>   
</div>
<script type="text/javascript">
    var url = '<?php echo $CFG->wwwroot . "/local/myanalytics/ajax/"; ?>';
    var coursepath = '<?php echo $CFG->wwwroot . "/course/view.php?id="; ?>';
</script> 
<script>
    var path = {pathurl: '<?php echo $CFG->wwwroot ?>'};
</script>
<?php
echo $OUTPUT->footer();
?>

<script type="text/javascript">
    $(document).ready(function () {
        var adminteach = <?php echo $userasad; ?>;
        var student = <?php echo $userasstudent; ?>;
        if (!adminteach && student) {
            window.onload = get_data();
        }
    });
</script>

