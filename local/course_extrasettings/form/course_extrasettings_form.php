<?php

require_once ($CFG->libdir . '/formslib.php');
require_once ('lib.php');

class course_extrasettings_form extends moodleform {

    function definition() {
        global $CFG, $COURSE, $DB;
        $mform = & $this->_form;
        $course = $this->_customdata['course'];

        $mform->addElement('header', 'others', get_string('generals', 'local_course_extrasettings'));
        //used as english title field
        $mform->addElement('htmleditor', 'engtitle', get_string('engtitle', 'local_course_extrasettings'), 'wrap="virtual" rows="10" cols="50"');
        $mform->setType('engtitle', PARAM_RAW);

        //used as summary field
        $mform->addElement('htmleditor', 'syllabus', get_string('syllabus', 'local_course_extrasettings'), 'wrap="virtual" rows="10" cols="50"');
        $mform->setType('syllabus', PARAM_RAW);

        //as learning outcomes
        $mform->addElement('htmleditor', 'audience', get_string('audience', 'local_course_extrasettings'), 'wrap="virtual" rows="10" cols="50"');
        $mform->setType('audience', PARAM_RAW);

        $mform->addElement('filemanager', 'courseicon', get_string('courseicon', 'local_course_extrasettings'), null, array('subdirs' => false, 'maxbytes' => '10MB', 'accepted_types' => '*', 'maxfiles' => 1));

        $mform->addElement('filemanager', 'courseimage', get_string('courseimage', 'local_course_extrasettings'), null, array('subdirs' => false, 'maxbytes' => '10MB', 'accepted_types' => '*', 'maxfiles' => 1));
        $mform->addRule('courseimage', 'required', 'required', null, 'client');
        $mform->addHelpButton('courseimage', 'course_pic', 'local_course_extrasettings');

        $specialname = $DB->get_records('block_eduopen_master_special');
        foreach ($specialname as $specializationname) {
            $options1[$specializationname->id] = $specializationname->name;
        }
        $options1['none'] = 'None';
        $select = $mform->addElement('select', 'specializations', get_string('specializations', 'local_course_extrasettings'), $options1);
        $mform->addRule('specializations', 'required', 'required', null, 'client');
        $select->setMultiple(true);

        $instname = $DB->get_records('block_eduopen_master_inst');
        $options = array();
        foreach ($instname as $institutonname) {
            $options[$institutonname->id] = $institutonname->name;
        }
        $mform->addElement('select', 'institution', get_string('institution', 'local_course_extrasettings'), $options);

        $ids = array('Online' => get_string('online', 'local_course_extrasettings'),
            'Classroom' => get_string('classroom', 'local_course_extrasettings'),
            'Blended' => get_string('blended', 'local_course_extrasettings'),);
        $mform->addElement('select', 'coursetype', get_string('coursetype', 'local_course_extrasettings'), $ids);

        $cids = array('Beginner' => get_string('clevel1', 'local_course_extrasettings'),
            'Intermediate' => get_string('clevel2', 'local_course_extrasettings'),
            'Advanced' => get_string('clevel3', 'local_course_extrasettings'),);
        $mform->addElement('select', 'courselevel', get_string('courselevel', 'local_course_extrasettings'), $cids);
        $mform->setDefault('courselevel', 'beginner');

        $codes = array(
            'AUD', 'BRL', 'CAD', 'CHF', 'CZK', 'DKK', 'EUR', 'GBP', 'HKD', 'HUF', 'ILS', 'JPY',
            'MXN', 'MYR', 'NOK', 'NZD', 'PHP', 'PLN', 'RUB', 'SEK', 'SGD', 'THB', 'TRY', 'TWD', 'USD');
        $currencies = array();
        foreach ($codes as $c) {
            $currencies[$c] = new lang_string($c, 'core_currencies');
        }
        $mform->addElement('select', 'currency', get_string('exam', 'local_course_extrasettings'), $currencies);
        $mform->setDefault('currency', 'usdollar');

        $costattributes = array('size' => '15');
        $mform->addElement('text', 'cost', get_string('cost', 'local_course_extrasettings'), $costattributes);
        $mform->setType('cost', PARAM_INT);

        $lang = get_string_manager()->get_list_of_translations();
        $mform->addElement('select', 'language', get_string('languagetype', 'local_course_extrasettings'), $lang);
        //Course milestone/ capstone.
        $mform->addElement('advcheckbox', 'capstonecrs', get_string('capstonecrs', 'local_course_extrasettings'), get_string('capstonecrs_level', 'local_course_extrasettings'), array('group' => 0), array(0, 1));
        $mform->setDefault('capstonecrs', 0);

        if (is_siteadmin()) {
            $mform->addElement('selectyesno', 'featurecourse', get_string('featurecourse', 'local_course_extrasettings'));
            $mform->setDefault('featurecourse', 1);
        }
        $mform->addElement('html', '<hr>');

        // Certificate Mod Added on 28/10/16.
        $mform->addElement('header', 'other_modcert', get_string('certimod', 'local_course_extrasettings'));
        $mform->addElement('text', 'instructname', get_string('instructname', 'local_course_extrasettings'), null);
        $mform->setType('instructname', PARAM_RAW);

        $mform->addElement('filepicker', 'instcsign', get_string('instcsign', 'local_course_extrasettings'), null, array('subdirs' => false, 'maxbytes' => '10MB', 'accepted_types' => '*', 'maxfiles' => 1));
        $mform->addHelpButton('instcsign', 'instcsign_help', 'local_course_extrasettings');
        
        // Attendance certificate
        $mform->addElement('header', 'other', get_string('att_cert', 'local_course_extrasettings'));

        $mform->addElement('selectyesno', 'certificate1', get_string('att_certificate', 'local_course_extrasettings'));
        $mform->setDefault('certificate1', 0);

        $mform->addElement('filemanager', 'certificatedownload1', get_string('att_cert', 'local_course_extrasettings'), null, array('subdirs' => false, 'maxbytes' => '10MB', 'accepted_types' => '*', 'maxfiles' => 1));

        $mform->addElement('htmleditor', 'examrule1', get_string('att_cert_rule', "local_course_extrasettings"), 'wrap="virtual" rows="10" cols="50"');
        $mform->setType('examrule1', PARAM_RAW);

        $mform->addElement('selectyesno', 'attendancecompletion', get_string('attendancecompletion', 'local_course_extrasettings'));
        $mform->setDefault('attendancecompletion', 0);

        $attencostattributes = array('size' => '15');
        $mform->addElement('text', 'costforattendance', get_string('costforattendanceofcomplettion', 'local_course_extrasettings'), $attencostattributes);
        $mform->setType('costforattendance', PARAM_INT);
        // end of Attendance Certificate

        $mform->addElement('html', '<hr>');
        // Verified Certificate
        $mform->addElement('header', 'other', get_string('verify_cert', 'local_course_extrasettings'));

        $mform->addElement('selectyesno', 'certificate2', get_string('verify_cert_course', 'local_course_extrasettings'));
        $mform->setDefault('certificate2', 0);

        $mform->addElement('filemanager', 'certificatedownload2', get_string('sverify_cert', 'local_course_extrasettings'), null, array('subdirs' => false, 'maxbytes' => '10MB', 'accepted_types' => '*', 'maxfiles' => 1));

        $mform->addElement('htmleditor', 'examrule2', get_string('verify_cert_rule', "local_course_extrasettings"), 'wrap="virtual" rows="10" cols="50"');
        $mform->setType('examrule2', PARAM_RAW);

        $mform->addElement('selectyesno', 'vattendancecompletion', get_string('vattendancecompletion', 'local_course_extrasettings'));
        $mform->setDefault('vattendancecompletion', 0);

        $vcostattributes = array('size' => '15');
        $mform->addElement('text', 'vcostforattendance', get_string('vcostforattendanceofcomplettion', 'local_course_extrasettings'), $vcostattributes);
        $mform->setType('vcostforattendance', PARAM_INT);
        //end of Verified Certificate

        $mform->addElement('html', '<hr>');
        // Exam Certificate
        $mform->addElement('header', 'other', get_string('exam_cert', 'local_course_extrasettings'));

        $mform->addElement('selectyesno', 'certificate', get_string('exam_cert_course', 'local_course_extrasettings'));
        $mform->setDefault('certificate', 0);

        $mform->addElement('filemanager', 'certificatedownload', get_string('exam_cert', 'local_course_extrasettings'), null, array('subdirs' => false, 'maxbytes' => '10MB', 'accepted_types' => '*', 'maxfiles' => 1));

        $mform->addElement('htmleditor', 'examrule', get_string('exam_cert_rule', 'local_course_extrasettings'), 'wrap="virtual" rows="10" cols="50"');
        $mform->setType('examrule', PARAM_RAW);

        $mform->addElement('selectyesno', 'formalcredit', get_string('formalcredit', 'local_course_extrasettings'));
        $mform->setDefault('formalcredit', 0);

        $cformalattributes = array('size' => '15');
        $mform->addElement('text', 'costforformalcredit', get_string('costforformalcreditexam', 'local_course_extrasettings'), $cformalattributes);
        $mform->setType('costforformalcredit', PARAM_INT);

        $creditattributes = array('size' => '15');
        $mform->addElement('text', 'credits', get_string('noofcredit', 'local_course_extrasettings'), $creditattributes);
        $mform->setType('credits', PARAM_INT);
        //end of Exam Certificate

        $mform->addElement('html', '<hr>');
        // Others
        $mform->addElement('header', 'others', get_string('others', 'local_course_extrasettings'));

        $lengthattributes = array('size' => '20');
        $mform->addElement('text', 'length', get_string('length', 'local_course_extrasettings'), $lengthattributes);
        $mform->setType('length', PARAM_TEXT);
        $mform->freeze('length');
        $mform->addHelpButton('length', 'length_help', 'local_course_extrasettings');

        $estimatedattributes = array('size' => '20');
        $mform->addElement('text', 'estimated', get_string('estimated', 'local_course_extrasettings'), $estimatedattributes);
        $mform->setType('estimated', PARAM_TEXT);
        $mform->freeze('estimated');
        $mform->addHelpButton('estimated', 'estimated_help', 'local_course_extrasettings');

        // Added by Shiuli on 28/10/16.
        $mform->addElement('text', 'totalduration', get_string('totalduration', 'local_course_extrasettings'), array('size' => '20'));
        $mform->setType('totalduration', PARAM_TEXT);
        $mform->addHelpButton('totalduration', 'totalduration_help', 'local_course_extrasettings');

        // New Course length added for Course length and estimated effort.
        for ($i = 0; $i <= 25; $i++) {
            $dropoption[] = $i;
        }
        $mform->addElement('select', 'durationweek', get_string('durationweek', 'local_course_extrasettings'), $dropoption);
        $mform->setType('durationweek', PARAM_TEXT);
        $mform->addRule('durationweek', 'required', 'required', null, 'client');
        $mform->addHelpButton('durationweek', 'durationweek_help', 'local_course_extrasettings');

        $mform->addElement('select', 'estimatedweek', get_string('estimatedweek', 'local_course_extrasettings'), $dropoption);
        $mform->setType('estimatedweek', PARAM_TEXT);
        $mform->addRule('estimatedweek', 'required', 'required', null, 'client');
        $mform->addHelpButton('estimatedweek', 'estimatedweek_help', 'local_course_extrasettings');

        $attributes = array('size' => '30');
        $mform->addElement('textarea', 'videourl', get_string('videourl', 'local_course_extrasettings'), $attributes);
        $mform->setType('videourl', PARAM_RAW);
        $mform->addHelpButton('videourl', 'videourl_help', 'local_course_extrasettings');

        $mform->addElement('htmleditor', 'whatsinside', get_string('whatsinside', 'local_course_extrasettings'), 'wrap="virtual" rows="10" cols="50"');
        $mform->setType('whatsinside', PARAM_TEXT);

        //Course Format
        $mform->addElement('htmleditor', 'crecruitments', get_string('crecruitments', 'local_course_extrasettings'), 'wrap="virtual" rows="10" cols="50"');
        $mform->setType('crecruitments', PARAM_TEXT);

        $mform->addElement('htmleditor', 'textbook', get_string('textbook', 'local_course_extrasettings'), 'wrap="virtual" rows="10" cols="50"');
        $mform->setType('textbook', PARAM_RAW);

        $mform->addElement('htmleditor', 'recommendedbackground', get_string('recommendedbackground', 'local_course_extrasettings'), 'wrap="virtual" rows="10" cols="50"');
        $mform->setType('recommendedbackground', PARAM_RAW);

        // Course Date and Archival.
        $mform->addElement('header', 'others', get_string('dateandarchival', 'local_courselifecycle'));
        // Course Mode.
        $cmode = array('1' => get_string('crsmode-tutored', 'local_courselifecycle'),
            '0' => get_string('crsmode-selfed', 'local_courselifecycle'));
        $mform->addElement('select', 'coursemode', get_string('crsmode', 'local_courselifecycle'), $cmode);
        // Course Maintainance.
        $cmaintain = array('1' => get_string('crsmaintenance-tutored', 'local_courselifecycle'),
            '0' => get_string('crsmaintenance-selfed', 'local_courselifecycle'));
        $mform->addElement('select', 'crsmaintenance', get_string('crsmaintenance', 'local_courselifecycle'), $cmaintain);

        // Sync Status.
        $syncstatus = array('0' => get_string('syncnone', 'local_courselifecycle'),
            '1' => get_string('syncforward', 'local_courselifecycle'),
            '2' => get_string('syncbackward', 'local_courselifecycle'));
        $mform->addElement('select', 'syncstatus', get_string('syncstatus', 'local_courselifecycle'), $syncstatus);
        $mform->addHelpButton('syncstatus', 'syncstatus_help', 'local_courselifecycle');
        $mform->setDefault('syncstatus', 0);

        $cstart = $DB->get_record('course', array('id' => $COURSE->id), 'startdate');
        $yearLimit = array(
            'startyear' => 2014,
            'stopyear' => 2050
        );
        // T0.
        $T0Min = $cstart->startdate - (15 * 24 * 60 * 60);
        $mform->addElement('date_selector', 'enrolstart', get_string('enrolstartT0', 'local_courselifecycle'), $yearLimit);
        $mform->disabledIf('enrolstart', 'syncstatus', 'eq', 2);
        $mform->setType('enrolstart', PARAM_TEXT);
        $mform->addHelpButton('enrolstart', 'enrolstartT0_help', 'local_courselifecycle');
        $mform->setDefault('enrolstart', $T0Min);

        // T1.
        $mform->addElement('date_selector', 'crsopen', get_string('crsopenT1', 'local_courselifecycle'), $yearLimit);
        $mform->disabledIf('crsopen', 'syncstatus', 'eq', 2);
        $mform->setType('crsopen', PARAM_TEXT);
        $mform->addHelpButton('crsopen', 'crsopenT1_help', 'local_courselifecycle');
        $mform->setDefault('crsopen', $cstart->startdate);

        // T2.
        $mform->addElement('date_selector', 'tutoringstart', get_string('tutoringstartT2', 'local_courselifecycle'), $yearLimit);
        $mform->disabledIf('tutoringstart', 'syncstatus', 'eq', 2);
        $mform->setType('tutoringstart', PARAM_TEXT);
        $mform->addHelpButton('tutoringstart', 'tutoringstartT2_help', 'local_courselifecycle');
        $mform->setDefault('tutoringstart', $cstart->startdate);

        // T3.
        $T3Min = $cstart->startdate + (8 * 24 * 60 * 60) + (14 * 24 * 60 * 60);
        $mform->addElement('date_selector', 'tutoringstop', get_string('tutoringstopT3', 'local_courselifecycle'), $yearLimit);
        $mform->disabledIf('tutoringstop', 'syncstatus', 'eq', 2);
        $mform->setType('tutoringstop', PARAM_TEXT);
        $mform->addHelpButton('tutoringstop', 'tutoringstopT3_help', 'local_courselifecycle');
        $mform->setDefault('tutoringstop', $T3Min);

        // T5.
        $ff = array(
            'startyear' => 2014,
            'stopyear' => 2050,
            'optional' => true
        );
        $mform->addElement('date_selector', 'enrolstop', get_string('enrolstopT5', 'local_courselifecycle'), $ff);
        $mform->disabledIf('enrolstop', 'syncstatus', 'eq', 2);
        $mform->addHelpButton('enrolstop', 'enrolstopT5_help', 'local_courselifecycle');
        $mform->disabledIf('enrolstop', 'enrolstopenabled');
        $mform->setDefault('enrolstop', 2556100799);

        // T6.
        $mform->addElement('date_selector', 'crsclosed', get_string('crsclosedT6', 'local_courselifecycle'), $ff);
        $mform->disabledIf('crsclosed', 'syncstatus', 'eq', 2);
        $mform->addHelpButton('crsclosed', 'crsclosedT6_help', 'local_courselifecycle');
        $mform->disabledIf('crsclosed', 'crsclosedenabled');
        $mform->setDefault('crsclosed', 2556100799);

        // T7.
        $mform->addElement('date_selector', 'nexteditiondate', get_string('nextEditionT7', 'local_courselifecycle'), $ff);
        $mform->disabledIf('nexteditiondate', 'syncstatus', 'eq', 2);
        $mform->addHelpButton('nexteditiondate', 'nextEditionT7_help', 'local_courselifecycle');
        $mform->disabledIf('nexteditiondate', 'nexteditionenabled');
        $mform->setDefault('nexteditiondate', 2556100799);

        // Course Archival.
//        $csstatus = array('1' => get_string('cactive', 'local_course_extrasettings'),
//            '0' => get_string('carchive', 'local_course_extrasettings'));
//        $mform->addElement('select', 'coursestatus', get_string('coursestatus', 'local_course_extrasettings'), $csstatus);
//        $mform->setDefault('coursestatus', 1)
        if (is_siteadmin()) {
            // Course Archival.
            $mform->addElement('advcheckbox', 'coursestatus', get_string('coursestatus', 'local_courselifecycle'), get_string('coursestatus_level', 'local_courselifecycle'), array('group' => 0), array(1, 0));
            $mform->setDefault('coursestatus', 1);

            // Badge Status.
            $mform->addElement('html', '<hr>');
            $mform->addElement('header', 'other', get_string('badgesec', 'local_course_extrasettings'));
            $status = array('0' => get_string('inactivebadge', 'local_course_extrasettings'),
                '1' => get_string('activebadge', 'local_course_extrasettings'));
            $select = $mform->addElement('select', 'badgestatus', get_string('badgestatus', 'local_course_extrasettings'), $status);
            $mform->setDefault('badgestatus', 0);
        }

        //get course category.
        $coursecat = $DB->get_record('course', array('id' => $COURSE->id));
        $categ = $DB->get_record('course_categories', array('id' => $coursecat->category));

        // Badge section in English.
        $mform->addElement('html', '<hr>');
        $mform->addElement('header', 'other', get_string('enbadgehead', 'local_course_extrasettings'));

        $mform->addElement('filemanager', 'badgeimage', get_string('badgeimage', 'local_course_extrasettings'), null, array('subdirs' => false, 'maxbytes' => '10MB', 'accepted_types' => '*', 'maxfiles' => 1));

        $mform->addElement('textarea', 'enbadgecriteria', get_string('badgeccriteria', 'local_course_extrasettings'), 'wrap="virtual" rows="5" cols="80"');
        $mform->setType('enbadgecriteria', PARAM_TEXT);

        $mform->addElement('textarea', 'encompetence', get_string('competence', 'local_course_extrasettings'), 'wrap="virtual" rows="5" cols="80"');
        $mform->setType('encompetence', PARAM_TEXT);

        /*$mform->addElement('tags', 'enbadgetags', get_string('btags', 'local_course_extrasettings'));
        $mform->setType('enbadgetags', PARAM_RAW);
        $mform->addHelpButton('enbadgetags', 'enbadgetagshelp', 'local_course_extrasettings');
        $mform->setDefault('enbadgetags', array('othertags' => $categ->name));
*/
        // Badge section in Italian.
        $mform->addElement('html', '<hr>');
        $mform->addElement('header', 'other', get_string('itbadgehead', 'local_course_extrasettings'));

        $mform->addElement('textarea', 'itbadgecriteria', get_string('badgeccriteria', 'local_course_extrasettings'), 'wrap="virtual" rows="5" cols="80"');
        $mform->setType('itbadgecriteria', PARAM_TEXT);

        $mform->addElement('textarea', 'itcompetence', get_string('competence', 'local_course_extrasettings'), 'wrap="virtual" rows="5" cols="80"');
        $mform->setType('itcompetence', PARAM_TEXT);

        /*$mform->addElement('tags', 'itbadgetags', get_string('btags', 'local_course_extrasettings'));
        $mform->setType('itbadgetags', PARAM_RAW);
        $mform->addHelpButton('itbadgetags', 'itbadgetagshelp', 'local_course_extrasettings');
        $mform->setDefault('itbadgetags', array('othertags' => $categ->name));*/

        if (isset($course) && !empty($course)) {
            $cid = $course->id;
        } else {
            $cid = $COURSE->id;
        }
        $this->add_action_buttons(true, get_string('savechanges1', 'local_course_extrasettings'));
        $mform->addElement('hidden', 'courseid', $cid);
        $mform->setType('courseid', PARAM_INT);
    }

    function validation($data, $files) {
        global $DB;
        $errors = parent::validation($data, $files);
        $courseMode = $data['coursemode'];
        $T0 = $data['enrolstart'];
        $T1 = $data['crsopen'];
        $T2 = $data['tutoringstart'];
        $T3 = $data['tutoringstop'];
        $T5 = $data['enrolstop'];
        $T6 = $data['crsclosed'];
        $T7 = $data['nexteditiondate'];

        // Validation on durationweek and estimatedweek.
        if ($data['durationweek'] == 0) {
            $errors['durationweek'] = get_string('durationweekerror', 'local_course_extrasettings', '');
        }
        if ($data['estimatedweek'] == 0) {
            $errors['estimatedweek'] = get_string('estimatedweekerror', 'local_course_extrasettings', '');
        }

        // Perform validation if syncstatus is Forward.
        if ($data['syncstatus'] == 1) {
            // T0 should <= T1 - 2weeks(14days).
            if ($T0 >= ($T1 - (14 * 24 * 60 * 60))) {
                $errors['enrolstart'] = get_string('enrolstarttaken', 'local_courselifecycle', '');
            }
            if ($courseMode == 1) {
                // T2 should >= T1.
                if ($T2 < $T1) {
                    $errors['tutoringstart'] = get_string('tutoringstarttaken', 'local_courselifecycle', '');
                }

                // T3 should >=  T2+Course_Duration+2_weeks.
                if (($T3 <= ($T2 + (7 * 24 * 60 * 60) + (14 * 24 * 60 * 60))) || ($T3 <= $T2)) {
                    $errors['tutoringstop'] = get_string('tutoringstoptaken', 'local_courselifecycle', '');
                }
            }
            // T5 should >= T3 OR T1 and < T6.
            if ($courseMode == 1) { // Tutored mode.
                if (($T5 != 0) && ($T5 < $T3)) {
                    $errors['enrolstop'] = get_string('enrolstoptutoredtaken1', 'local_courselifecycle', '');
                }
            } else {
                if (($T5 != 0) && ($T5 < $T1)) {
                    $errors['enrolstop'] = get_string('enrolstoptutoredtaken1a', 'local_courselifecycle', '');
                }
            }
            if (($T6 != 0) && ($T5 > $T6)) {
                $errors['enrolstop'] = get_string('enrolstoptutoredtaken2', 'local_courselifecycle', '');
            }
            // T7 should greater T6
            if ($T7 != 0) {
                if ($T7 < $T6) {
                    $errors['nexteditiondate'] = get_string('nexteditiontaken', 'local_courselifecycle', '');
                }
            }
        }
        return $errors;
    }

}
