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
require_once($CFG->libdir . '/accesslib.php');

require_once($CFG->dirroot . '/my/lib.php');
require_once('lib.php');
redirect_if_major_upgrade_required();
$id = optional_param('specialid', null, PARAM_INT);
if ($event = optional_param('event', null, PARAM_RAW)){
    require_login();
}

//require_login();
$strcourses = get_string('myhome');//set the page title
$header = "$SITE->shortname: $strcourses";

// Start setting up the page
$params = array();
$PAGE->set_context(context_system::instance());
$PAGE->set_url('/eduopen/pathway_details.php', $params);
$PAGE->set_pagelayout('eduopen_institution');
$PAGE->set_pagetype('my-index');
$PAGE->blocks->add_region('content');
$specheader = 'Pathway Details';
$PAGE->set_title($specheader);
$PAGE->set_heading($header);
$PAGE->requires->jquery();
echo $OUTPUT->header();

echo $OUTPUT->custom_block_region('content');

global $CFG, $DB, $USER, $PAGE, $OUTPUT;
$OUTPUT->body_attributes(array('bodyspecial'));

$myspec = $DB->get_records('block_eduopen_master_special', array('id' => $id));
foreach ($myspec as $special) {
    $spec = specialization_certificate($special);
	require('templates/pathway_details.html');

}// End of main foreach loop
echo $OUTPUT->footer();

?>
