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
$strcourses = get_string('myhome'); //set the page title
$header = "$SITE->shortname: $strcourses";

// Start setting up the page
$params = array();
$PAGE->set_context(context_system::instance());
$PAGE->set_url('/eduopen/collaboration.php', $params);
$PAGE->set_pagelayout('eduopen_institution');
$PAGE->set_pagetype('my-index');
$PAGE->blocks->add_region('content');
$aboutushead = 'Collaboration with Us';
$PAGE->set_title($aboutushead);
$PAGE->set_heading($header);
$PAGE->requires->jquery();

echo $OUTPUT->header();

echo $OUTPUT->custom_block_region('content');

global $CFG, $DB, $USER, $PAGE, $OUTPUT;
$img5 = (!empty($PAGE->theme->settings->image5));
$img6 = (!empty($PAGE->theme->settings->image6));
$img7 = (!empty($PAGE->theme->settings->image7));
$img8 = (!empty($PAGE->theme->settings->image8));
$img9 = (!empty($PAGE->theme->settings->image9));
$img10 = (!empty($PAGE->theme->settings->image10));
$img11 = (!empty($PAGE->theme->settings->image11));

if (current_language() == 'en') {
    require("templates/collaboration_en.html");
} else {
    require("templates/collaboration_it.html");
}


echo $OUTPUT->footer();
?>
