<?php
require(dirname(dirname(dirname(__FILE__))).'/config.php');

$courseid = optional_param('general', 0, PARAM_INT);
/* $paymentid = optional_param('payment', 0, PARAM_INT);*/
if ($courseid) {
    $course = $DB->get_record('course', array('id' => $courseid));
    $generaldel = $DB->delete_records('course_extrasettings_general', array('courseid' => $courseid));
    // Added by Shiuli Delete all entries from Course_sequence table where courseid=$courseid.
    $deletesequence = $DB->delete_records('eduopen_special_course_seq', array('courseid' => $courseid));
    // Added by Shiuli on 11/11/16-to delete entries on from event table.
    $deleteopenev = $DB->delete_records('event', ['courseid' => $courseid, 'format' => 8, 'eventtype' => 'course']);
    $deletecloseev = $DB->delete_records('event', ['courseid' => $courseid, 'format' => 9, 'eventtype' => 'course']);
}
if (isset($generaldel)) {
    redirect(new moodle_url($CFG->wwwroot . '/course/view.php?id='.$courseid));
}

echo $OUTPUT->footer();
