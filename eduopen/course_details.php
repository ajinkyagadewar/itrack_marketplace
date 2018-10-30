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
require_once($CFG->dirroot . '/theme/eduopen/lib.php');
require_once('lib.php');
redirect_if_major_upgrade_required();
$id = optional_param('courseid', null, PARAM_INT);
//require_login();
$strcourses = get_string('myhome'); //set the page title
$header = "$SITE->shortname: $strcourses";

// Start setting up the page
$params = array();
$PAGE->set_context(context_system::instance());
$PAGE->set_url('/eduopen/course_details.php', $params);
$PAGE->set_pagelayout('eduopen_institution');
$PAGE->set_pagetype('my-index');
$PAGE->blocks->add_region('content');
$crsdetails = 'Course Details';
$PAGE->set_title($crsdetails);
$PAGE->set_heading($header);
$PAGE->requires->jquery();
echo $OUTPUT->header();

echo $OUTPUT->custom_block_region('content');

global $CFG, $DB, $USER, $OUTPUT;

require('templates/course_details.html');

echo $OUTPUT->footer();

?>
<script>
	$(".panel-body").hide();
</script>

<script>
    $('.cdetail-plus-align').on('click', function () {
        if ($('.cdetail-plus-align').hasClass("fa-chevron-down")) {
            $('.cdetail-plus-align').removeClass("fa-chevron-down").addClass("fa-chevron-up");
        } else {
            $('.cdetail-plus-align').removeClass("fa-chevron-up").addClass("fa-chevron-down");
        }
        //$('#activitypanel a').toggleClass('fa-chevron-up','fa-chevron-down');
        var ela = $('#activitypanel a').parents(".panel").children(".panel-body");
        if ($('#activitypanel a').hasClass("fa-chevron-down")) {
            $('#activitypanel a').removeClass("fa-chevron-down").addClass("fa-chevron-up");
            ela.slideDown(200);
        } else {
            $('#activitypanel a').removeClass("fa-chevron-up").addClass("fa-chevron-down");
            ela.slideUp(200);
        }
    });
</script>
<script>
    $('#ChevronIcon').on('click', function () {
        if ($('.cdetail-plus-align').hasClass("fa-chevron-down")) {
            $('.cdetail-plus-align').removeClass("fa-chevron-down").addClass("fa-chevron-up");
        } else {
            $('.cdetail-plus-align').removeClass("fa-chevron-up").addClass("fa-chevron-down");
        }
        var ela = $('#activitypanel a').parents(".panel").children(".panel-body");
        if ($('#activitypanel a').hasClass("fa-chevron-down")) {
            $('#activitypanel a').removeClass("fa-chevron-down").addClass("fa-chevron-up");
            ela.slideDown(200);
        } else {
            $('#activitypanel a').removeClass("fa-chevron-up").addClass("fa-chevron-down");
            ela.slideUp(200);
        }
    });
</script>