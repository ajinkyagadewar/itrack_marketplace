<?php
/**
 * @author    Shiuli Jana <shiuli@elearn10.com>
 * @package    filterdata
 * @copyright  2015 onwards Moodle of India  {@link  http://www.moodleofindia.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


/**
 * @param stdClass $data
 * @param stdClass $mform
 */

require_once('../../config.php');
global $DB, $CFG, $USER;
//array
$sequence = $_POST['sequence'];
$icon = $_POST['icon'];
$courseids = $_POST['courseid'];
//variable
$specilizationsids = $_POST['specilization_id'];
$result = array();
foreach ($courseids as $course) {
    $row = $DB->get_record('eduopen_special_course_seq',array('specializationid'=>$specilizationsids,'courseid'=>$course));
    //var_dump($row);
    if ($row) {
            $updaterow = (object)array('id'=>$row->id,'sequence'=>$sequence[$course],'icontype'=>$icon[$course]); 
            $DB->update_record('eduopen_special_course_seq',$updaterow);
    } else {
            $record = new stdClass();
            $record->specializationid = $specilizationsids;
            $record->courseid = $course;
            $record->sequence = $sequence[$course];
            $record->icontype = $icon[$course];
            $result[] = $DB->insert_record('eduopen_special_course_seq', $record);
        }
}
// This portion is added on 23rd dec for deleting Extra records from sequence table  by sj.
if ($specilizationsids) {
    $oldarrayrs = $DB->get_records('eduopen_special_course_seq',array('specializationid'=>$specilizationsids));
    foreach ($oldarrayrs as $oldarrayrs1) {
        $oldarray[] = $oldarrayrs1->courseid;
    }
}
$cdelete = array_diff($oldarray, $courseids);
$string = implode(',', $cdelete);
if ($string) {
    $sql = $DB->execute("DELETE FROM {eduopen_special_course_seq} WHERE specializationid=$specilizationsids
    AND courseid IN ($string)");
    redirect($CFG->wwwroot . '/eduopen/pathway_details.php?specialid=' . $specilizationsids);
} else {
    redirect($CFG->wwwroot . '/eduopen/pathway_details.php?specialid=' . $specilizationsids);
}