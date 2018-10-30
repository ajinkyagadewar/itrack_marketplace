<?php

// This file is part of Moodle - http://moodle.org/
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
 * Ajax calls
 * @desc This file will receive ajax calls and return JSON format 
 *       data.
 * 
 * @package    local_ereport
 * @copyright  2015
 * @author
 * @license
 */
require_once('./../../../config.php');
require_once("../locallib.php");

require_once("{$CFG->libdir}/datalib.php");
require_once("{$CFG->libdir}/enrollib.php");
require_once("{$CFG->libdir}/completionlib.php");
require_once("{$CFG->libdir}/datalib.php");
require_once($CFG->dirroot . '/blocks/timestat/locallib.php');
global $DB, $CFG;
//if (!is_siteadmin()) {
//    echo json_encode(['message' => 'Unauthorised access'], JSON_PRETTY_PRINT);
//}
header('Content-Type:application/json');
$callback = required_param('callback', PARAM_RAW);

$params = array();
unset($_REQUEST['callback']);

foreach ($_REQUEST as $field => $value) {
    $params[$field] = $value;
}

echo json_encode(call_user_func_array($callback, $params));

function courseinfo($userid) {
    global $DB;
    $courses = enrol_get_users_courses($userid);

    $listarray = array();
    $completed = array();
    $incompleted = array();
    $enrolled = array();
    $completioninfolist = [];

    foreach ($courses as $course) {
        $enrolled[$course->id] = $course->fullname;
        $completioninfolist[] = new completion_info($course);
        //$cmcm = new completion_completion(array('userid' => $userid, 'courseid' => $course->id));
    }

    foreach ($completioninfolist as $cminfo) {

        $class = new \ReflectionClass("completion_info");
        $property = $class->getProperty("course");
        $property->setAccessible(true);
        $list = $property->getValue($cminfo);

        $listarray[$list->id] = ['id' => $list->id, 'fullname' => $list->fullname, 'activity' => array()];

        $completions = $cminfo->get_completions($userid);
        if ($cminfo->is_course_complete($userid)) {
            $completed[$list->id] = $list->fullname;
        } else {
            $incompleted[$list->id] = $list->fullname;
        }
        foreach ($completions as $completion) {

            $criteria = $completion->get_criteria();
            $row = array();
            if ($criteria->module != '') { // check whether the type is null or not by Shiuli.
                $row['name'] = $criteria->get_title_detailed();
                $row['type'] = $criteria->module;
                $row['completionstate'] = (int) $completion->is_complete();

                $row['date'] = (int) $completion->timecompleted != 0 ? userdate((int) $completion->timecompleted, '%d %B %Y') : '-';
                // $timespentlog = block_timespent_print_log((object)array('id'=>$list->id,'shortname'=>$list->shortname,'groupmode'=>$list->groupmode), '', 1426502700, time(), $order = "l.time ASC", $page = 0, $perpage = 100, $url = "", "", $criteria->moduleinstance, $modaction = "", $groupid = 0);
                //added by Shiuli on 10th feb.
                $timelogs = $DB->get_record_sql("SELECT SUM(timespent) as sum FROM {custom_log} WHERE
                 cmid=$criteria->moduleinstance AND userid=$userid");
                if ($timelogs->sum) {
                    $spenttime = $timelogs->sum;
                } else {
                    $spenttime = 0;
                }
                $timee = timestat_seconds_to_stringtime($spenttime);
                $row['timespent'] = $timee;
            }
            if (!empty($row)) { // Assign only not null types by Shiuli.
            $listarray[$list->id]['activity'][] = $row;
            }
        }
    }
    if ($listarray != null) {
        $response = array();
        $response['status'] = true;
        $response['activitylist'] = $listarray;
        $response['completed'] = $completed;
        $response['enrolled'] = $enrolled;
        $response['incompleted'] = $incompleted;

        return $response;
    } else {
        return ['status' => false, 'message' => get_string('nocourseenrolluser', 'local_myanalytics')];
    }
}

function chartdata($userid) {
    $courses = enrol_get_all_users_courses($userid);
    $completed = 0;
    $incompleted = 0;
    $completioninfolist = [];
    foreach ($courses as $course) {
        $completioninfolist[] = new completion_info($course);
    }
    foreach ($completioninfolist as $cminfo) {
        if ($cminfo->is_course_complete($userid)) {
            $completed++;
        } else {
            $incompleted++;
        }
    }
    return array('enrolled' => count($courses), 'complete' => $completed, 'incomplete' => $incompleted);
}