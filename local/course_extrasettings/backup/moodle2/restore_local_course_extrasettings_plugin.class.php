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
 * Grid Information
 *
 * @package    local_course_extrasettings
 * @version    1.0
 * @copyright  &copy; 2016 Shiuli Jana <shiuli@elearn10.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU Public License
 *
 */
defined('MOODLE_INTERNAL') || die();

/**
 * Restore plugin class that provides the necessary information
 * needed to restore course_extrasettings
 */
class restore_local_course_extrasettings_plugin extends restore_local_plugin {

    /**
     * Returns the paths to be handled by the plugin at course level.
     */
    protected function define_course_plugin_structure() {
        $paths = array();
        $elename = 'course_extrasettings'; // This defines the postfix of 'process_*' below.
        $elepath = $this->get_pathfor('/');
        $paths[] = new restore_path_element($elename, $elepath);
        return $paths; // And we return the interesting paths.
    }

    /**
     * Process the course_extrasettings element.
     */
    public function process_course_extrasettings($data) {
        global $DB;
        $data = (object) $data;
//        $oldquestionid = $this->get_old_parentid('course');
//        $newquestionid = $this->get_new_parentid('course');
        // $restoredDetails = $this->get_mapping('course', $data->courseid);
        // $restoredCourseid = $restoredDetails->newitemid;

        $newcourseid = $this->task->get_courseid();

        // Check whether the backup and restored courseid is same or not.
        $data->courseid = $newcourseid;
        $context = context_course::instance($newcourseid);
        $data->contextid = $context->id;
        $DB->insert_record('course_extrasettings_general', $data);

        // Add entry into `mdl_files` for new courses.
        $maxbytes = 5000000;
        //Course Image.
        file_save_draft_area_files($data->courseimage, $context->id, 'local_course_extrasettings', 'content', $data->courseimage, array('subdirs' => 0, 'maxbytes' => $maxbytes, 'maxfiles' => 1));
        // Attendance certificate
        file_save_draft_area_files($data->certificatedownload1, $context->id, 'local_course_extrasettings', 'content', $data->certificatedownload1, array('subdirs' => 0, 'maxbytes' => $maxbytes, 'maxfiles' => 1));
        //end of Attendance certificate
        // Verified Certificate
        file_save_draft_area_files($data->certificatedownload2, $context->id, 'local_course_extrasettings', 'content', $data->certificatedownload2, array('subdirs' => 0, 'maxbytes' => $maxbytes, 'maxfiles' => 1));
        //end of Verified Certificate
        // Exam Certificate
        file_save_draft_area_files($data->certificatedownload, $context->id, 'local_course_extrasettings', 'content', $data->certificatedownload, array('subdirs' => 0, 'maxbytes' => $maxbytes, 'maxfiles' => 1));
        //end of Exam Certificate
        // Badge Image.
        file_save_draft_area_files($data->badgeimage, $context->id, 'local_course_extrasettings', 'content', $data->badgeimage, array('subdirs' => 0, 'maxbytes' => $maxbytes, 'maxfiles' => 1));
    }

}
