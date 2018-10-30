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
 * @package    local_note
 * @copyright  2015 MoodleOfIndia
 * @author     shambhu kumar
 * @license    MoodleOfIndia {@web http://www.moodleofindia.com}
 */
require_once('./../../../config.php');
include_once($CFG->dirroot.'/local/myanalytics/classes/course.php');   
$action = optional_param('mode', false, PARAM_RAW);
header('Content-Type:application\json');
$obj = new \myanalytics\courselevel\course_report();
$PAGE->set_context(context_system::instance());
switch ($action) {
    case 'COURSE_OVERVIEW':
        echo $obj->get_course_overview($_POST);
        break;
    case 'ACTIVITY_LIST':
        echo $obj->activity_list($_POST);
        break;
    case 'COURSE_COMPLETED':
        echo $obj->course_completed();
        break;
    case 'CATEGORY_COURSE':
        echo $obj->category_course();
        break;
    case 'CATEGORY':
        echo $obj->get_all_category();
        break;
    case 'SUB_CATEGORY_QUERY':
        echo $obj->get_sub_category(optional_param('id', null, PARAM_INT));
        break;
    case 'COURSE_QUERY':
        echo $obj->get_category_courses(optional_param('id', null, PARAM_INT));
        break;
    case 'USER_QUERY':
        echo $obj->get_course_users(optional_param('id', null, PARAM_INT));
        break;
    case 'COURSE_COMPLETED_BY_ID':
        echo $obj->get_course_complete_by_id(optional_param('id', null, PARAM_INT));
        break;
    case 'COURSE_USER_LIST':
        echo $obj->get_course_user_list(optional_param('id', null, PARAM_INT));
        break;
    case 'ACTIVITY_QUERY':
        echo $obj->get_activities_types(optional_param('id', null, PARAM_INT), true);
        break;    
    case 'COURSE_QUIZ_MODULE':
        echo $obj->course_quiz_module(optional_param('id', null, PARAM_INT), true);
        break;
    case 'DISUSSION_COURSE':
        echo $obj->get_forums(optional_param('id', null, PARAM_INT), true);
        break;
    case 'COURSE_QUIZ_MODULE_USER':
        echo $obj->course_quiz_module_user(optional_param('courseid', null, PARAM_INT), optional_param('userid', null, PARAM_INT));
        break;
    default:
        break;
}