<?php
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
//require_login();
$strcourses = get_string('myhome');//set the page title
$header = "$SITE->shortname: $strcourses";

// Start setting up the page
$params = array();
$PAGE->set_context(context_system::instance());
$PAGE->set_url('/eduopen/condition_elearning(it).php', $params);
$PAGE->set_pagelayout('eduopen_institution');
$PAGE->set_pagetype('my-index');
$PAGE->blocks->add_region('content');
$aboutushead = 'Condition For Using Elearning platform Setting(it)';
$PAGE->set_title($aboutushead);
$PAGE->set_heading($header);
$PAGE->requires->jquery();

echo $OUTPUT->header();

echo $OUTPUT->custom_block_region('content');

global $CFG, $DB, $USER, $PAGE, $OUTPUT;
$header_title = (!empty($PAGE->theme->settings->policy_it_condition_header));
$content1title = (!empty($PAGE->theme->settings->policy_it_condition_content1title));
$content1 = (!empty($PAGE->theme->settings->policy_it_condition_content1));
$content2title = (!empty($PAGE->theme->settings->policy_it_condition_content2title));
$content2 = (!empty($PAGE->theme->settings->policy_it_condition_content2));

require("templates/privacy-it.html");

echo $OUTPUT->footer();