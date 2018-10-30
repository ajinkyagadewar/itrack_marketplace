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
require_once('lib.php');

//require_login();
$strcourses = get_string('myhome'); //set the page title
$header = "$SITE->shortname: $strcourses";

// Start setting up the page
$params = array();
$PAGE->set_context(context_system::instance());
$PAGE->set_url('/eduopen/a.php', $params);
$PAGE->set_pagelayout('eduopen_institution');
$PAGE->set_pagetype('my-index');
$PAGE->blocks->add_region('content');
$specheader = 'Pathway Details';
$PAGE->set_title($specheader);
$PAGE->set_heading($header);
$PAGE->requires->jquery();
echo $OUTPUT->header();

echo $OUTPUT->custom_block_region('content');

global $CFG, $DB, $PAGE, $OUTPUT;

// For featured course.
if ($_POST['mode'] == 'course') {
    if (isset($_POST['checked']) && ($_POST['checked'] == 1)) {
        $featured = $_POST['featuredcrs'];
        $crsid = $_POST['cid'];
        if ($featured == 1) {
            $DB->execute("UPDATE {course_extrasettings_general} SET featurecourse=0
            WHERE courseid=$crsid AND featurecourse=$featured");
        } else {
            $DB->execute("UPDATE {course_extrasettings_general} SET featurecourse=1
            WHERE courseid=$crsid AND featurecourse=0");
        }
    } else {
        $featured = $_POST['featuredcrs'];
        $crsid = $_POST['cid'];
        if ($featured == 0) {
            $DB->execute("UPDATE {course_extrasettings_general} SET featurecourse=1
            WHERE courseid=$crsid AND featurecourse=$featured");
        } else {
            $DB->execute("UPDATE {course_extrasettings_general} SET featurecourse=0
            WHERE courseid=$crsid AND featurecourse=1");
        }
    }
}
// For featured Pathway.
if (($_POST['mode'] == 'pathway')) {
    if (isset($_POST['checked']) && ($_POST['checked'] == 1)) {
        $featured = $_POST['featuredpath'];
        $pathid = $_POST['pathid'];
        if ($featured == 1) {
            $DB->execute("UPDATE {block_eduopen_master_special} SET featuredpathway=0
            WHERE id=$pathid AND featuredpathway=$featured");
        } else {
            $DB->execute("UPDATE {block_eduopen_master_special} SET featuredpathway=1
            WHERE id=$pathid AND featuredpathway=0");
        }
    } else {
        $featured = $_POST['featuredpath'];
        $pathid = $_POST['pathid'];
        if ($featured == 0) {
            $DB->execute("UPDATE {block_eduopen_master_special} SET featuredpathway=1
            WHERE id=$pathid AND featuredpathway=$featured");
        } else {
            $DB->execute("UPDATE {block_eduopen_master_special} SET featuredpathway=0
            WHERE id=$pathid AND featuredpathway=1");
        }
    }
}

echo $OUTPUT->footer();
?>
