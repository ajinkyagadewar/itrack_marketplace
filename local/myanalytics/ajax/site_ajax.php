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
include_once($CFG->dirroot.'/local/myanalytics/classes/user_report.php');   
include_once($CFG->dirroot.'/local/myanalytics/classes/course.php');   
include_once($CFG->dirroot.'/local/myanalytics/classes/site.php');   
$action = optional_param('mode', false, PARAM_RAW);
header('Content-Type:application\json');
switch ($action) {
    case 'TOP5_USER':
        $obj = new \myanalytics\userlevel\user_report();
        echo $obj->get_top_users_in_course(true,false,10);
        break;
    case 'TOP5_COURSE':
        $obj = new \myanalytics\courselevel\course_report();
        echo $obj->get_top_courses(true,false,5);
        break;
    case 'COURSE_COMPLETION':
        $obj = new \myanalytics\userlevel\user_report();
        echo $obj->course_enroll_completion();
        break;
    case 'UNIQUE_LOGIN':
        $obj = new \myanalytics\userlevel\user_report();
        echo $obj->get_unique_sessions();
        break;
    case 'ADD_FAV':
        $obj = new \myanalytics\sitelevel\site_report();
        echo $obj->add_fav(optional_param('level', false, PARAM_RAW),optional_param('label', false, PARAM_RAW),optional_param('link', false, PARAM_RAW));
        break;
    case 'REMOVE_FAV':
        $obj = new \myanalytics\sitelevel\site_report();
        echo $obj->remove_fav(optional_param('link', false, PARAM_RAW));
        break;
    default:
        break;
}