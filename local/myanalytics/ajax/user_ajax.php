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
require_login();
include_once($CFG->dirroot.'/local/myanalytics/classes/user_report.php');
global $PAGE;
$PAGE->set_context(context_system::instance());

$action = optional_param('mode', false, PARAM_RAW);
header('Content-Type:application/json');
$obj = new \myanalytics\userlevel\user_report(optional_param('id', false, PARAM_RAW), optional_param('courseid', false, PARAM_RAW));
switch ($action) {
    case 'GET_ENROLL_COURSE':
        echo $obj->get_enrol_courses();
        break;
    default:
    case 'ACTIVITY_LIST_IN_COURSES':        
        echo $obj->get_course_activities();
        break;
    case 'USER_QUIZ_INFO':
        echo $obj->user_quiz_info();
        break;
    case 'USER_COURSE_COMPLETED':
        echo $obj->user_course_completed(true);
        break;
    default:
        break;
}
