<?php
// This file is part of Moodle - http://moodle.org/
//
/**
 * Moodle's
 * This file is part of eduopen LMS Product
 *
 * @package   theme_eduopen
 * @copyright http://www.moodleofindia.com
 * @license   This file is copyrighted to Dhruv Infoline Pvt Ltd, http://www.moodleofindia.com
 */

require_once(dirname(__FILE__) . '/../config.php');

require_once($CFG->dirroot . '/my/lib.php');
require_once('lib.php');
redirect_if_major_upgrade_required();
$id = optional_param('id', null, PARAM_INT); 
//require_login();
$strcourses = get_string('myhome');//set the page title
$header = "$SITE->shortname: $strcourses";
$strmymoodle = get_string('myhome');

$params = array();
$PAGE->set_context(context_system::instance());
$PAGE->set_url('/eduopen/instructor.php', $params);
$PAGE->set_pagelayout('eduopen_institution');
$PAGE->set_pagetype('my-index');
$PAGE->blocks->add_region('content');
$instructorheader = 'Instructors';
$PAGE->set_title($instructorheader);
$PAGE->set_heading($header);
$PAGE->requires->jquery();
echo $OUTPUT->header();

echo $OUTPUT->custom_block_region('content');

global $CFG, $DB, $USER;

require('templates/instructor.html');

echo $OUTPUT->footer();
?>

<style>
.insttnm {
    color: #546E7A !important;
    font-size: 13px !important;
    font-weight: normal !important;
}

.insttnm a {
    font-size: 13px !important;
}

.timess {
    color: #4CAF50;
    font-size: 13px !important;
    font-weight: normal !important;
}

.prf-border-head a {
	color: #546E7A !important;
}
</style>