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
$institutionid   = optional_param('institutionid', null, PARAM_INT); 
//require_login();
$strcourses = get_string('myhome');//set the page title
$header = "$SITE->shortname: $strcourses";

// Start setting up the page
$params = array();
$PAGE->set_context(context_system::instance());
$PAGE->set_url('/eduopen/institution_details.php', $params);
$PAGE->set_pagelayout('eduopen_institution');
$PAGE->set_pagetype('my-index');
$PAGE->blocks->add_region('content');
$insthead = 'Institution Details';
$PAGE->set_title($insthead);
$PAGE->set_heading($header);
$PAGE->requires->jquery();

echo $OUTPUT->header();

echo $OUTPUT->custom_block_region('content');

global $CFG, $DB, $USER, $PAGE, $OUTPUT;

require('templates/institution_details.html');

echo $OUTPUT->footer();
?>
<style>
.wrapper {
	padding-top: 0px;
	/*margin-top: 75px;*/
}
.box-shadowinst {
        min-height: auto !important;
        max-height: auto !important;
}
</style>
