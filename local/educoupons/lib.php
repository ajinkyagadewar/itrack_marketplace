<?php

// This file is part of MoodleofIndia - http://www.moodleofindia.com/
/**
 * This script implements the manage_license of the dashboard, and allows editing
 * of the fooboo License.
 *
 * Local Educoupons 
 * @author     Arjun Singh <arjunsingh@elearn10.com>
 * @package    local_educoupons
 * @copyright  20/10/2016 lms of india
 * @license    http://lmsofindia.com/
 */
function local_educoupons_extend_settings_navigation($settingsnav) {
    global $CFG, $COURSE;
    if (is_siteadmin()) {
        $node = $settingsnav->add(get_string('coupons', 'local_educoupons'));
        $node->add(get_string('createcoupons', 'local_educoupons'), new moodle_url($CFG->wwwroot . '/local/educoupons/addcoupons.php'));
        $node->add(get_string('viewcoupons', 'local_educoupons'), new moodle_url($CFG->wwwroot . '/local/educoupons/coupons.php'));

        //$node->add(get_string('coursecouponsreports', 'local_educoupons'),new moodle_url($CFG->wwwroot.'/local/educoupons/reports.php', ['courseid' => $COURSE->id]));
    }
}
