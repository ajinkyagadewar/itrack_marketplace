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

function local_myanalytics_extend_navigation(global_navigation $navigation) {
    global $CFG, $USER, $PAGE, $DB;
    $contentrole = $DB->get_record('role', array('shortname' => 'coursecreator'));
    $tutorrole = $DB->get_record('role', array('shortname' => 'teacher'));
    $editingTeacher = user_has_role_assignment($USER->id, 3);
    $contentEditor = user_has_role_assignment($USER->id, $contentrole->id);
    $tutor = user_has_role_assignment($USER->id, $tutorrole->id);
    if ($editingTeacher || $contentEditor || $tutor) {
        $visibleUsers = 1;
    } else {
        $visibleUsers = 0;
    }

    $userVisible = $visibleUsers;
    if (isloggedin()) {
        $lang = function($langkey) {
            return get_string($langkey, 'local_myanalytics');
        };

        $node = $navigation->add($lang('plugin'));

        $node->add($lang('myfavourite'), new moodle_url($CFG->wwwroot . '/local/myanalytics/site/favourites.php'));

        if (is_siteadmin($USER)) {
            $sub = $node->add($lang('sitelevel'));
            $sub->add($lang('siteoverview'), $CFG->wwwroot . '/local/myanalytics/site/site_overview.php');
        }
        if ($userVisible || is_siteadmin()) {
            $sub = $node->add($lang('courselevel'));
        }
        if (is_siteadmin($USER)) {
            $sub->add($lang('courseoverview'), $CFG->wwwroot . '/local/myanalytics/course/course_overview.php');
        }
        if ($userVisible || is_siteadmin()) {
            $sub->add($lang('courselevelreport'), $CFG->wwwroot . '/local/myanalytics/course/course_level_report.php');
        }
        
        if ($userVisible || is_siteadmin()) {
            $sub = $node->add($lang('userlevel'));
            $sub->add($lang('userlevelreport'), $CFG->wwwroot . '/local/myanalytics/user/user_search.php');
        } else {
            $sub = $node->add($lang('userlevel'));
            $sub->add($lang('userlevelreport'), $CFG->wwwroot . '/local/myanalytics/user/user_info.php?id='.$USER->id);
        }
        //if(is_siteadmin($USER)) {
        $sub->add($lang('usercompletion'), $CFG->wwwroot . '/local/myanalytics/user/user_completions.php');
        //}
    }
}

//function local_myanalytics_extends_navigation(global_navigation $navigation) {
//    global $CFG, $USER, $PAGE;
//    if (isloggedin()) {
//        $lang = function($langkey) {
//            return get_string($langkey, 'local_myanalytics');
//        };
//
//        $node = $navigation->add($lang('plugin'));
//
//        $node->add($lang('myfavourite'), new moodle_url($CFG->wwwroot . '/local/myanalytics/site/favourites.php'));
//
//        if (is_siteadmin($USER)) {
//            $sub = $node->add($lang('sitelevel'));
//            $sub->add($lang('siteoverview'), $CFG->wwwroot . '/local/myanalytics/site/site_overview.php');
//        }
//        $sub = $node->add($lang('courselevel'));
//        if (is_siteadmin($USER)) {
//            $sub->add($lang('courseoverview'), $CFG->wwwroot . '/local/myanalytics/course/course_overview.php');
//        }
//        if ($userVisible && is_siteadmin()) {
//            $sub->add($lang('courselevelreport'), $CFG->wwwroot . '/local/myanalytics/course/course_level_report.php');
//        }
//
//        $sub = $node->add($lang('userlevel'));
//        $sub->add($lang('userlevelreport'), $CFG->wwwroot . '/local/myanalytics/user/user_search.php');
//        //if(is_siteadmin($USER)) {
//        $sub->add($lang('usercompletion'), $CFG->wwwroot . '/local/myanalytics/user/user_completions.php');
//        //}
//    }
//}
