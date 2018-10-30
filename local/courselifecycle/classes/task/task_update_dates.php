<?php

/**
 * cron
 * @return boolean
 */
/**
 * Sync Status:
 * 0 -> None
 * 1 -> Forward
 * 2 -> Backward
 */

namespace local_courselifecycle\task;

defined('MOODLE_INTERNAL') || die();

class task_update_dates extends \core\task\scheduled_task {

    public function get_name() {
        // Shown in admin screens
        return get_string('pluginname', 'local_courselifecycle');
    }

    public function execute() {
        require_once(dirname(__FILE__) . '/../../../../config.php');
        global $DB;
        $generl = $DB->get_records('course_extrasettings_general', null, 'id, courseid,'
                . 'enrolstart, enrolstop, crsopen, syncstatus');
        // Change Course start date in course table.
        foreach ($generl as $genrl1) {
            // Do Sync if syncstatus!=0.
            if ($genrl1->syncstatus != 0) {
                $generalStartDate = (int) $genrl1->courseid;
                $course = $DB->get_record('course', array('id' => $generalStartDate), 'id, startdate');
                if (!empty($genrl1->crsopen)) {
                    $startdate = $genrl1->crsopen;
                } else {
                    $startdate = $course->startdate;
                }
                // Update startdate field only when the course stratdate!=extrasettings startdate.
                if ($course->startdate != $genrl1->crsopen) {
                    if ($genrl1->syncstatus == 1) {
                        // B. Forward >> Sync FROM extrasettings TO course-settings.
                        $DB->execute("UPDATE {course} SET startdate=$startdate WHERE id=$course->id");
                    } else if ($genrl1->syncstatus == 2) {
                        // C. Backward >> Sync FROM course-settings TO extrasettings.
                        $DB->execute("UPDATE {course_extrasettings_general} SET crsopen=$course->startdate WHERE id=$genrl1->id");
                    }
                }
            }
        }

        // Change Enrolment Strat & End date in self enrolment plugin.
        foreach ($generl as $genrl) {
            // Do Sync if syncstatus!=0.
            if ($genrl->syncstatus != 0) {
                $enrol = $DB->get_record('enrol', array('courseid' => $genrl->courseid, 'enrol' => 'self'), 'id, enrolstartdate, enrolenddate');
                $crs = $DB->get_record('course', array('id' => $genrl->courseid), 'startdate');
                if ((enrol_is_enabled('self')) && !empty($enrol)) {
                    if (!empty($genrl->enrolstart)) {
                        $enrolstartdate = $genrl->enrolstart;
                    } else {
                        if (($enrol->enrolstartdate == 0)) {
                            $enrolstartdate = $enrol->enrolstartdate + $crs->startdate;
                        } else {
                            $enrolstartdate = $enrol->enrolstartdate;
                        }
                    }
                    // Update only when the enrol startdate!=extrasettings enrolstart.
                    if ($enrol->enrolstartdate != $genrl->enrolstart) {
                        if ($genrl->syncstatus == 1) {
                            // B. Forward >> Sync FROM extrasettings enrollment-plugin.
                            $DB->execute("UPDATE {enrol} SET enrolstartdate=$enrolstartdate
                            WHERE id=$enrol->id");
                        } else if ($genrl->syncstatus == 2) {
                            // C. Backward >> Sync FROM enrollment-plugin TO extrasettings.
                            $DB->execute("UPDATE {course_extrasettings_general}
                            SET enrolstart=$enrol->enrolstartdate
                            WHERE id=$genrl->id");
                        }
                    }

                    if ($genrl->enrolstop != 0) {
                        $enrolenddate = $genrl->enrolstop;
                    } else {
                        $enrolenddate = $enrol->enrolenddate + 2556143999; // Default 31/12/2050
                    }
                    // Update only when the enrol enddate!=extrasettings enrolstop.

                    if ($enrol->enrolenddate != $genrl->enrolstop) {
                        if ($genrl->syncstatus == 1) {
                            // B. Forward >> Sync FROM extrasettings TO enrollment-plugin.
                            $DB->execute("UPDATE {enrol} SET enrolenddate=$enrolenddate
                            WHERE id=$enrol->id");
                        } else if ($genrl->syncstatus == 2) {
                            // C. Backward >> Sync FROM enrollment-plugin TO extrasettings.
//                            $DB->execute("UPDATE {course_extrasettings_general}
//                            SET enrolstop=$enrol->enrolenddate
//                            WHERE id=$genrl->id");
                        }
                    }
                }
            }
        }
    }

}
