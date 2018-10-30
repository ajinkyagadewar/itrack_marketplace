<?php
require_once(dirname(__FILE__) . '../../../config.php');
global $DB, $CFG;
$action = optional_param('action', false, PARAM_RAW);
$json_object = array();
switch ($action) {
    case 'COURSE_LIST':
        $categoryid = optional_param('id', false, PARAM_INT);
        $list = $DB->get_records_sql('select id,fullname from {course} where category=' . $categoryid);
        foreach ($list as $value) {
            $json_object[$value->id] = $value->fullname;
        }
        echo json_encode($json_object);
        break;
    case 'ENROLL_LIST':
        $courseid = optional_param('id', false, PARAM_INT);
        $list = $DB->get_records_sql("select id,enrol,name from {enrol} where courseid=$courseid and enrol='paypal'");
        foreach ($list as $value) {
            $json_object[$value->id] = $value->name;
        }
        echo json_encode($json_object);
        break;
}