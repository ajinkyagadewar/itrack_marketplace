<?php

/**
 * cron
 * @return boolean
 */

namespace local_course_extrasettings\task;

defined('MOODLE_INTERNAL') || die();

class task_archived_course extends \core\task\scheduled_task {

    public function get_name() {
        // Shown in admin screens
        return get_string('archived_crs', 'local_course_extrasettings');
    }

    public function execute() {
        global $DB, $CFG;
        // Make the course as archived if it has no nextedition  and  if time()>close date.
        $extraSettings1 = $DB->get_records_sql("SELECT * FROM {course_extrasettings_general}
        WHERE crsclosed IS NOT NULL OR nexteditiondate IS NOT NULL");
        foreach ($extraSettings1 as $extraSetting1) {
        //    if (($extraSetting1->nexteditiondate == 0)) {
                $courseClosed = ($extraSetting1->crsclosed == 0) ?
                        ($extraSetting1->crsclosed + 2556100799) : $extraSetting1->crsclosed;
                if (time() > $courseClosed) {
                    // Update the course status=0 in course extra settings.
                    $crsStatusUpdate = $DB->execute("UPDATE {course_extrasettings_general} SET coursestatus = 0
                    WHERE id=$extraSetting1->id AND coursestatus=1");
                    // If the course become archived.
                    // 1. get the course category(Top / not archived).
                    $courseCat = $DB->get_record('course', array('id' => $extraSetting1->courseid), 'category');
                    // 2. From category table get the idnumber.
                    $courseCatIdNum = $DB->get_record('course_categories', array('id' => $courseCat->category), 'idnumber');
                    // 3. From category table merge the idnumber(not archived) to archived one.
                    $archivedCtg = 'ARC-' . $courseCatIdNum->idnumber;
                    // 4. From category table get the archived category idnumber.
                    $courseCatIdNum1 = $DB->get_record('course_categories', array('idnumber' => $archivedCtg), 'id');
                    // If there is no corresponding match.
                    $courseCatIdNums = $DB->get_record('course_categories', array('idnumber' => 'ARC000'), 'id');
                    // 5. Move the course to corresponding archived sub-category.
                    //** Don't process the courses under archived category again.**//
                    if (substr($courseCatIdNum->idnumber, 0, 3) !== "ARC") {
                        if ($crsStatusUpdate && $courseCatIdNum1) {
                            $DB->execute("UPDATE {course} SET category = $courseCatIdNum1->id
                        WHERE id=$extraSetting1->courseid");
                        } else if ($crsStatusUpdate && !$courseCatIdNum1) {
                            $DB->execute("UPDATE {course} SET category = $courseCatIdNums->id
                        WHERE id=$extraSetting1->courseid");
                        }
                    }
                }
           // }
        }
        mtrace( 'Archived Course task api is working fine..');
    }

}
