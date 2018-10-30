<?php

/**
 * cron
 * @return boolean
 */

namespace block_specialization\task;

defined('MOODLE_INTERNAL') || die();

class task_pathway_archive extends \core\task\scheduled_task {

    public function get_name() {
        // Shown in admin screens
        return get_string('pathwayarchived', 'block_specialization');
    }

    public function execute() {
        global $DB, $CFG;
        require_once($CFG->dirroot . "/eduopen/lib.php");

        /* If there is no record in course table then delete records from {eduopen_special_course_seq} table.
         * records by using courseid.
         * added by SHiuli on 23/11/16.
         */
        $sequences = $DB->get_records('eduopen_special_course_seq');
        foreach ($sequences as $sequence) {
            $courseExist = $DB->record_exists_sql("SELECT * FROM {course} WHERE id=$sequence->courseid");
            if (!$courseExist) {
                echo 'Extra records with courseid ' . $sequence->courseid . ' has been deleted from eduopen_special_course_seq table';
                $DB->delete_records('eduopen_special_course_seq', array('courseid' => $sequence->courseid));
            }
        }

        // pathway archival.
        $pathways = $DB->get_records('block_eduopen_master_special', array('status' => 1));
        foreach ($pathways as $pathway) {
            $coursess = course_under_pathway_withsequence($pathway->id);
            $length = count($coursess);
            for ($i = 0; $i < $length; $i++) {
                $courseid = $coursess[$i];
                $coursestatusArr = coursedetails_course_status($courseid);
                $coursestatus[$pathway->id][$courseid] = $coursestatusArr['coursestatus'];
            }
        }
        foreach ($pathways as $pathway1) {
            $cstatus = $coursestatus[$pathway1->id];
            if ((count(array_unique($cstatus)) === 1) &&
                    (end($cstatus) == (get_string('crsstatus_condiH', 'local_courselifecycle')))) {
                // Change the pathway status into 0.
                $DB->execute("UPDATE {block_eduopen_master_special} SET pathwaystatus=0
                WHERE pathwaystatus=1 AND id=$pathway1->id");
                // Move the pathway into archived category.
                $pathcateg = $DB->get_record('block_eduopen_master_special', array('id' => $pathway1->id), 'category, pathwaystatus');
                $crscat = $DB->get_field('course_categories', 'idnumber', array('id' => $pathcateg->category));
                if ($pathcateg->pathwaystatus == 0) {
                    $archivedCateg = 'ARC-' . $crscat;
                    $newpathcategid = $DB->get_field('course_categories', 'id', array('idnumber' => $archivedCateg));
                    if (isset($newpathcategid) && !empty($newpathcategid)) {
                        $DB->execute("UPDATE {block_eduopen_master_special} SET
                        category=$newpathcategid WHERE pathwaystatus=0 AND id=$pathway1->id");
                    }
                }
                echo 'Pathway id ' . $pathway1->id . ' is archived';
            }
        }
        return true;
    }

}
