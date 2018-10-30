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
redirect_if_major_upgrade_required();

//require_login();
$strcourses = get_string('myhome');//set the page title
$header = "$SITE->shortname: $strcourses";

// Start setting up the page
$params = array();
$PAGE->set_context(context_system::instance());
$PAGE->set_url('/eduopen/instructor.php', $params);
$PAGE->set_pagelayout('eduopen_institution');
$PAGE->set_pagetype('my-index');
$PAGE->blocks->add_region('content');
$instheader = 'Institution';
$PAGE->set_title($instheader);
$PAGE->set_heading($header);
$PAGE->requires->jquery();

echo $OUTPUT->header();

echo $OUTPUT->custom_block_region('content');
global $CFG, $DB, $USER;

require('templates/institution.html');
$PAGE->requires->js(new moodle_url($CFG->wwwroot.'/eduopen/js/eduopen.js'));

echo $OUTPUT->footer();
