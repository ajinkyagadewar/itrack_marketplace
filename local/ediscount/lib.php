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
 * @package    local_ediscount
 * @copyright  2015 MoodleOfIndia
 * @author     shambhu kumar
 * @license    MoodleOfIndia {@web http://www.moodleofindia.com}
 */

function local_ediscount_extend_settings_navigation($settingsnav, $context) {
    global $CFG, $COURSE;
    if (has_capability('moodle/role:assign', context_course::instance($COURSE->id))) {
        $coursenode = $settingsnav->get('courseadmin');
        if ($coursenode) {
            $node = $coursenode->add(get_string('discount', 'local_ediscount'));
            $node->add(get_string('creatediscount', 'local_ediscount'),new moodle_url($CFG->wwwroot.'/local/ediscount/adddiscounts.php', ['courseid' => $COURSE->id]));
            $node->add(get_string('viewdiscount', 'local_ediscount'),new moodle_url($CFG->wwwroot.'/local/ediscount/discounts.php', ['courseid' => $COURSE->id]));
        }
    }
}