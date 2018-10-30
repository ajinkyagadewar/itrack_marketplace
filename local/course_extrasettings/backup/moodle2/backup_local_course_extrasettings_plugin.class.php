<?php

// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * @package    local_course_extrsettings
 * @version    1.0
 * @copyright  &copy; 2016 Shiuli Jana <shiuli@elearn10.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();

class backup_local_course_extrasettings_plugin extends backup_local_plugin {

    /**
     * Define (add) particular settings this activity can have
     */
    protected function define_my_settings() {
        // No particular settings for this activity
    }

    protected function define_course_plugin_structure() {
        global $DB, $COURSE;
        $courseid = $COURSE->id;
        $plugin = $this->get_plugin_element(null, null, null);
        $pluginwrapper = new backup_nested_element($this->get_recommended_name(), array('id'), array("courseimage",
            "specializations",
            "institution",
            "coursetype",
            "courselevel",
            "language",
            "currency",
            "featurecourse",
            "lifetime",
            "certificate",
            "courseid",
            "cost",
            "certificatedownload",
            "formalcredit",
            "credits",
            "examrule",
            "costforformalcredit",
            "attendancecompletion",
            "costforattendance",
            "length",
            "estimated",
            "videourl",
            "license",
            "whatsinside",
            "textbook",
            "recommendedbackground",
            "syllabus",
            "audience",
            "crecruitments",
            "contextid",
            "certificate1",
            "certificatedownload1",
            "examrule1",
            "certificate2",
            "certificatedownload2",
            "examrule2",
            "vattendancecompletion",
            "vcostforattendance",
            "engtitle",
            "encompetence",
            "enbadgecriteria",
            "enbadgetags",
            "itcompetence",
            "itbadgecriteria",
            "itbadgetags",
            "badgeimage",
            "badgestatus",
            "coursemode",
            "crsmaintenance",
            "enrolstart",
            "crsopen",
            "tutoringstart",
            "tutoringstop",
            "enrolstop",
            "crsclosed",
            "nexteditiondate",
            "coursestatus",
            "syncstatus",
            "durationweek",
            "estimatedweek",
            "capstonecrs"));
        // Connect the visible container ASAP.
        $plugin->add_child($pluginwrapper);

        // Set source to populate the data.
        $pluginwrapper->set_source_sql('SELECT * FROM {course_extrasettings_general} WHERE
            courseimage=:courseimage OR
            specializations=:specializations OR
            institution=:institution OR
            coursetype=:coursetype OR
            courselevel=:courselevel OR
            language=:language OR
            currency=:currency OR
            featurecourse=:featurecourse OR
            lifetime=:lifetime OR
            certificate=:certificate OR
            courseid=:courseid OR
            cost=:cost OR
            certificatedownload=:certificatedownload OR
            formalcredit=:formalcredit OR
            credits=:credits OR
            examrule=:examrule OR
            costforformalcredit=:costforformalcredit OR
            attendancecompletion=:attendancecompletion OR
            costforattendance=:costforattendance OR
            length=:length OR
            estimated=:estimated OR
            videourl=:videourl OR
            license=:license OR
            whatsinside=:whatsinside OR
            textbook=:textbook OR
            recommendedbackground=:recommendedbackground OR
            syllabus=:syllabus OR
            audience=:audience OR
            crecruitments=:crecruitments OR
            contextid=:contextid OR
            certificate1=:certificate1 OR
            certificatedownload1=:certificatedownload1 OR
            examrule1=:examrule1 OR
            certificate2=:certificate2 OR
            certificatedownload2=:certificatedownload2 OR
            examrule2=:examrule2 OR
            vattendancecompletion=:vattendancecompletion OR
            vcostforattendance=:vcostforattendance OR
            engtitle=:engtitle OR
            encompetence=:encompetence OR
            enbadgecriteria=:enbadgecriteria OR
            enbadgetags=:enbadgetags OR
            itcompetence=:itcompetence OR
            itbadgecriteria=:itbadgecriteria OR
            itbadgetags=:itbadgetags OR
            badgeimage=:badgeimage OR
            badgestatus=:badgestatus OR
            coursemode=:coursemode OR
            crsmaintenance=:crsmaintenance OR
            enrolstart=:enrolstart OR
            crsopen=:crsopen OR
            tutoringstart=:tutoringstart OR
            tutoringstop=:tutoringstop OR
            enrolstop=:enrolstop OR
            crsclosed=:crsclosed OR
            nexteditiondate=:nexteditiondate OR
            coursestatus=:coursestatus OR
            syncstatus=:syncstatus OR
            durationweek=:durationweek OR
            estimatedweek=:estimatedweek OR
            capstonecrs=:capstonecrs', array(
            "courseimage" => backup::VAR_PARENTID,
            "specializations" => backup::VAR_PARENTID,
            "institution" => backup::VAR_PARENTID,
            "coursetype" => backup::VAR_PARENTID,
            "courselevel" => backup::VAR_PARENTID,
            "language" => backup::VAR_PARENTID,
            "currency" => backup::VAR_PARENTID,
            "featurecourse" => backup::VAR_PARENTID,
            "lifetime" => backup::VAR_PARENTID,
            "certificate" => backup::VAR_PARENTID,
            "courseid" => backup::VAR_COURSEID,
            "cost" => backup::VAR_PARENTID,
            "certificatedownload" => backup::VAR_PARENTID,
            "formalcredit" => backup::VAR_PARENTID,
            "credits" => backup::VAR_PARENTID,
            "examrule" => backup::VAR_PARENTID,
            "costforformalcredit" => backup::VAR_PARENTID,
            "attendancecompletion" => backup::VAR_PARENTID,
            "costforattendance" => backup::VAR_PARENTID,
            "length" => backup::VAR_PARENTID,
            "estimated" => backup::VAR_PARENTID,
            "videourl" => backup::VAR_PARENTID,
            "license" => backup::VAR_PARENTID,
            "whatsinside" => backup::VAR_PARENTID,
            "textbook" => backup::VAR_PARENTID,
            "recommendedbackground" => backup::VAR_PARENTID,
            "syllabus" => backup::VAR_PARENTID,
            "audience" => backup::VAR_PARENTID,
            "crecruitments" => backup::VAR_PARENTID,
            "contextid" => backup::VAR_PARENTID,
            "certificate1" => backup::VAR_PARENTID,
            "certificatedownload1" => backup::VAR_PARENTID,
            "examrule1" => backup::VAR_PARENTID,
            "certificate2" => backup::VAR_PARENTID,
            "certificatedownload2" => backup::VAR_PARENTID,
            "examrule2" => backup::VAR_PARENTID,
            "vattendancecompletion" => backup::VAR_PARENTID,
            "vcostforattendance" => backup::VAR_PARENTID,
            "engtitle" => backup::VAR_PARENTID,
            "encompetence" => backup::VAR_PARENTID,
            "enbadgecriteria" => backup::VAR_PARENTID,
            "enbadgetags" => backup::VAR_PARENTID,
            "itcompetence" => backup::VAR_PARENTID,
            "itbadgecriteria" => backup::VAR_PARENTID,
            "itbadgetags" => backup::VAR_PARENTID,
            "badgeimage" => backup::VAR_PARENTID,
            "badgestatus" => backup::VAR_PARENTID,
            "coursemode" => backup::VAR_PARENTID,
            "crsmaintenance" => backup::VAR_PARENTID,
            "enrolstart" => backup::VAR_PARENTID,
            "crsopen" => backup::VAR_PARENTID,
            "tutoringstart" => backup::VAR_PARENTID,
            "tutoringstop" => backup::VAR_PARENTID,
            "enrolstop" => backup::VAR_PARENTID,
            "crsclosed" => backup::VAR_PARENTID,
            "nexteditiondate" => backup::VAR_PARENTID,
            "coursestatus" => backup::VAR_PARENTID,
            "syncstatus" => backup::VAR_PARENTID,
            "durationweek" => backup::VAR_PARENTID,
            "estimatedweek" => backup::VAR_PARENTID,
            "capstonecrs" => backup::VAR_PARENTID,
        ));
        // Define file annotations
        $plugin->annotate_files('local_course_extrsettings', 'courseimage', null);
        $plugin->annotate_files('local_course_extrsettings', 'certificatedownload', null);
        $plugin->annotate_files('local_course_extrsettings', 'certificatedownload1', null);
        $plugin->annotate_files('local_course_extrsettings', 'certificatedownload2', null);
        $plugin->annotate_files('local_course_extrsettings', 'badgeimage', null);

        return $plugin;
    }

}
