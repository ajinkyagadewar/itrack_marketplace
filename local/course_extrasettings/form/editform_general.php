<?php

// $Id: inscriptions_massives_form.php 352 2010-02-27 12:16:55Z ppollet $

require_once ($CFG->libdir . '/formslib.php');
require_once ('lib.php');

class course_extrasettings_form extends moodleform {

    function definition() {
        global $CFG, $COURSE, $DB;
        $mform = & $this->_form;
        //$general = $this->_customdata['general'];
        //$payment = $this->_customdata['payment'];
        $mform->addElement('header', 'other', get_string('generals', 'local_course_extrasettings'));

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
        $specialname = $DB->get_records_sql("SELECT * FROM {block_eduopen_master_special} ORDER BY name");
        foreach ($specialname as $specializationname) {
            $options1[$specializationname->id] = $specializationname->name;
        }
        $options1['none'] = 'None';
        $select = $mform->addElement('select', 'specializations', get_string('specializations', 'local_course_extrasettings'), $options1);
        $mform->addRule('specializations', 'required', 'required', null, 'client');
        // This will select the colour blue.
        $select->setMultiple(true);
        /* $attributes=array('size'=>'44');
          $mform->addElement('text', 'institution', get_string('institution', 'local_course_extrasettings'),  $attributes);
          $mform->setType('institution', PARAM_TEXT); */

        $instname = $DB->get_records_sql("SELECT * FROM {block_eduopen_master_inst} ORDER BY name");
        foreach ($instname as $institutonname) {
            $options[$institutonname->id] = $institutonname->name;
        }
        $select = $mform->addElement('select', 'institution', get_string('institution', 'local_course_extrasettings'), $options);
        $ids = array('Online' => get_string('online', 'local_course_extrasettings'),
            'Classroom' => get_string('classroom', 'local_course_extrasettings'),
            'Blended' => get_string('blended', 'local_course_extrasettings'),);
        $mform->addElement('select', 'coursetype', get_string('coursetype', 'local_course_extrasettings'), $ids);
        //$mform->setDefault('coursetype', 'online');
        $cids = array('Beginner' => get_string('clevel1', 'local_course_extrasettings'),
            'Intermediate' => get_string('clevel2', 'local_course_extrasettings'),
            'Advanced' => get_string('clevel3', 'local_course_extrasettings'));
        $mform->addElement('select', 'courselevel', get_string('courselevel', 'local_course_extrasettings'), $cids);
        //$mform->setDefault('courselevel', 'beginner');

        $codes = array(
            'AUD', 'BRL', 'CAD', 'CHF', 'CZK', 'DKK', 'EUR', 'GBP', 'HKD', 'HUF', 'ILS', 'JPY',
            'MXN', 'MYR', 'NOK', 'NZD', 'PHP', 'PLN', 'RUB', 'SEK', 'SGD', 'THB', 'TRY', 'TWD', 'USD');
        $currencies = array();
        foreach ($codes as $c) {
            $currencies[$c] = new lang_string($c, 'core_currencies');
        }
        $mform->addElement('select', 'currency', get_string('exam', 'local_course_extrasettings'), $currencies);
        $mform->setDefault('currency', 'usdollar');

        $attributes = array('size' => '15');
        $mform->addElement('text', 'cost', get_string('cost', 'local_course_extrasettings'), $attributes);
        $mform->setType('cost', PARAM_INT);

        $lang = get_string_manager()->get_list_of_translations();

        $mform->addElement('select', 'language', get_string('languagetype', 'local_course_extrasettings'), $lang);
        //$mform->setDefault('language', 'english');

        if (is_siteadmin()) {
            $mform->addElement('selectyesno', 'featurecourse', get_string('featurecourse', 'local_course_extrasettings'));
            $mform->setDefault('featurecourse', 1);
        }

        /* $mform->addElement('selectyesno', 'lifetime', get_string('lifetime', 'local_course_extrasettings'));
          $mform->setDefault('lifetime', 1); */
        $mform->addElement('html', '<hr>');
        // Attendance certificate
        $mform->addElement('header', 'others1', get_string('att_cert', 'local_course_extrasettings'));
        $mform->addElement('selectyesno', 'certificate1', get_string('att_certificate', 'local_course_extrasettings'));
        $mform->setDefault('certificate1', 0);
        $mform->addElement('filemanager', 'certificatedownload1', get_string('att_cert', 'local_course_extrasettings'), null, array('subdirs' => false, 'maxbytes' => '10MB', 'accepted_types' => '*', 'maxfiles' => 1));
        $mform->addElement('htmleditor', 'examrule1', get_string("att_cert_rule", "local_course_extrasettings"), 'wrap="virtual" rows="10" cols="50"');
        $mform->setType('examrule1', PARAM_RAW);
        $mform->addElement('selectyesno', 'attendancecompletion', get_string('attendancecompletion', 'local_course_extrasettings'));
        $mform->setDefault('attendancecompletion', 0);
        $attributes = array('size' => '15');
        $mform->addElement('text', 'costforattendance', get_string('costforattendanceofcomplettion', 'local_course_extrasettings'), $attributes);
        $mform->setType('costforattendance', PARAM_INT);
        //end of Attendance Certificate
        $mform->addElement('html', '<hr>');
        // Verified Certificate
        $mform->addElement('header', 'others1', get_string('verify_cert', 'local_course_extrasettings'));
        $mform->addElement('selectyesno', 'certificate2', get_string('verify_cert_course', 'local_course_extrasettings'));
        $mform->setDefault('certificate2', 0);
        $mform->addElement('filemanager', 'certificatedownload2', get_string('sverify_cert', 'local_course_extrasettings'), null, array('subdirs' => false, 'maxbytes' => '10MB', 'accepted_types' => '*', 'maxfiles' => 1));
        $mform->addElement('htmleditor', 'examrule2', get_string("verify_cert_rule", "local_course_extrasettings"), 'wrap="virtual" rows="10" cols="50"');
        $mform->setType('examrule2', PARAM_RAW);
        $mform->addElement('selectyesno', 'vattendancecompletion', get_string('vattendancecompletion', 'local_course_extrasettings'));
        $mform->setDefault('vattendancecompletion', 0);
        $attributes = array('size' => '15');
        $mform->addElement('text', 'vcostforattendance', get_string('vcostforattendanceofcomplettion', 'local_course_extrasettings'), $attributes);
        $mform->setType('vcostforattendance', PARAM_INT);
        //end of Verified Certificate
        $mform->addElement('html', '<hr>');
        // Exam Certificate
        $mform->addElement('header', 'others1', get_string('exam_cert', 'local_course_extrasettings'));
        $mform->addElement('selectyesno', 'certificate', get_string('exam_cert_course', 'local_course_extrasettings'));
        $mform->setDefault('certificate', 0);
        $mform->addElement('filemanager', 'certificatedownload', get_string('exam_certificate', 'local_course_extrasettings'), null, array('subdirs' => false, 'maxbytes' => '10MB', 'accepted_types' => '*', 'maxfiles' => 1));
        $mform->addElement('htmleditor', 'examrule', get_string("exam_cert_rule", "local_course_extrasettings"), 'wrap="virtual" rows="10" cols="50"');
        $mform->setType('examrule', PARAM_RAW);
        $mform->addElement('selectyesno', 'formalcredit', get_string('formalcredit', 'local_course_extrasettings'));
        $mform->setDefault('formalcredit', 0);
        $attributes = array('size' => '15');
        $mform->addElement('text', 'costforformalcredit', get_string('costforformalcreditexam', 'local_course_extrasettings'), $attributes);
        $mform->setType('costforformalcredit', PARAM_INT);
        $attributes = array('size' => '15');
        $mform->addElement('text', 'credits', get_string('noofcredit', 'local_course_extrasettings'), $attributes);
        $mform->setType('credits', PARAM_INT);
        //end of Exam Certificate
        $mform->addElement('html', '<hr>');

        // Starting the other details.
        $mform->addElement('header', 'other', get_string('others', 'local_course_extrasettings'));
        $attributes = array('size' => '20');
        $mform->addElement('text', 'length', get_string('length', 'local_course_extrasettings'), $attributes);
        $mform->setType('length', PARAM_TEXT);
        $mform->addRule('length', 'required', 'required', null, 'client');
        $attributes = array('size' => '20');
        $mform->addElement('text', 'estimated', get_string('estimated', 'local_course_extrasettings'), $attributes);
        $mform->setType('estimated', PARAM_TEXT);
        $mform->addRule('estimated', 'required', 'required', null, 'client');
        $attributes = array('size' => '30');
        $mform->addElement('textarea', 'videourl', get_string('videourl', 'local_course_extrasettings'), $attributes);
        $mform->setType('videourl', PARAM_RAW);
        /* $attributes=array('size'=>'20','optional' => true);
          $mform->addElement('text', 'license', get_string('license', 'local_course_extrasettings'),  $attributes);
          $mform->setType('license', PARAM_TEXT);
         */
        $mform->addElement('htmleditor', 'whatsinside', get_string('whatsinside', 'local_course_extrasettings'), 'wrap="virtual" rows="10" cols="50"');
        $mform->setType('whatsinside', PARAM_TEXT);
        //as course format
        $mform->addElement('htmleditor', 'crecruitments', get_string('crecruitments', 'local_course_extrasettings'), 'wrap="virtual" rows="10" cols="50"');
        $mform->setType('crecruitments', PARAM_TEXT);
        $mform->addElement('htmleditor', 'textbook', get_string("textbook", "local_course_extrasettings"), 'wrap="virtual" rows="10" cols="50"');
        $mform->setType('textbook', PARAM_RAW);
        $mform->addElement('htmleditor', 'recommendedbackground', get_string("recommendedbackground", "local_course_extrasettings"), 'wrap="virtual" rows="10" cols="50"');
        $mform->setType('recommendedbackground', PARAM_RAW);

        // Course Date and Archival.
        $mform->addElement('header', 'other', get_string('dateandarchival', 'local_courselifecycle'));
        // Course Mode.
        $cmode = array('1' => get_string('crsmode-tutored', 'local_courselifecycle'),
            '0' => get_string('crsmode-selfed', 'local_courselifecycle'));
        $mform->addElement('select', 'coursemode', get_string('crsmode', 'local_courselifecycle'), $cmode);
        //$mform->setDefault('courselevel', 'beginner');
        // Course Maintainance.
        $cmaintain = array('1' => get_string('crsmaintenance-tutored', 'local_courselifecycle'),
            '0' => get_string('crsmaintenance-selfed', 'local_courselifecycle'));
        $mform->addElement('select', 'crsmaintenance', get_string('crsmaintenance', 'local_courselifecycle'), $cmaintain);

        // T0.
        $yearLimit = array(
            'startyear' => 2014,
            'stopyear' => 2099
        );
        $mform->addElement('date_selector', 'enrolstart', get_string('enrolstartT0', 'local_courselifecycle'), $yearLimit);
        $mform->setType('enrolstart', PARAM_TEXT);
        $mform->addHelpButton('enrolstart', 'enrolstartT0_help', 'local_courselifecycle');

        // T1.
        $cstart = $DB->get_record('course', array('id' => $COURSE->id), 'startdate');
        $mform->addElement('date_selector', 'crsopen', get_string('crsopenT1', 'local_courselifecycle'), $yearLimit);
        $mform->setType('crsopen', PARAM_TEXT);
        $mform->addHelpButton('crsopen', 'crsopenT1_help', 'local_courselifecycle');
        //$defaultvalue = mktime(0, 0, 0, 1, 1, 1900);
        $mform->setDefault('crsopen', $cstart->startdate);

        // T2.
        $mform->addElement('date_selector', 'tutoringstart', get_string('tutoringstartT2', 'local_courselifecycle'), $yearLimit);
        $mform->setType('tutoringstart', PARAM_TEXT);
        $mform->addHelpButton('tutoringstart', 'tutoringstartT2_help', 'local_courselifecycle');
        $mform->setDefault('tutoringstart', $cstart->startdate);

        // T3.
        $mform->addElement('date_selector', 'tutoringstop', get_string('tutoringstopT3', 'local_courselifecycle'), $yearLimit);
        $mform->setType('tutoringstop', PARAM_TEXT);
        $mform->addHelpButton('tutoringstop', 'tutoringstopT3_help', 'local_courselifecycle');

        // T5.
        $ff = array(
            'startyear' => 2014,
            'stopyear' => 2099,
            'optional' => true
        );
        $mform->addElement('date_selector', 'enrolstop', get_string('enrolstopT5', 'local_courselifecycle'), $ff);
        $mform->addHelpButton('enrolstop', 'enrolstopT5_help', 'local_courselifecycle');
        $mform->disabledIf('enrolstop', 'enrolstopenabled');
        $mform->setDefault('enrolstop', 4102354800);

        // T6.
        $mform->addElement('date_selector', 'crsclosed', get_string('crsclosedT6', 'local_courselifecycle'), $ff);
        $mform->addHelpButton('crsclosed', 'crsclosedT6_help', 'local_courselifecycle');
        $mform->disabledIf('crsclosed', 'crsclosedenabled');
        $mform->setDefault('crsclosed', 4102354800);

        // T7.
        $mform->addElement('date_selector', 'nexteditiondate', get_string('nextEditionT7', 'local_courselifecycle'), $ff);
        $mform->addHelpButton('nexteditiondate', 'nextEditionT7_help', 'local_courselifecycle');
        $mform->disabledIf('nexteditiondate', 'nexteditionenabled');
        $mform->setDefault('nexteditiondate', 4102354800);

        // Course Archival.
        $csstatus = array('1' => get_string('cactive', 'local_course_extrasettings'),
            '0' => get_string('carchive', 'local_course_extrasettings'));
        $mform->addElement('select', 'coursestatus', get_string('coursestatus', 'local_course_extrasettings'), $csstatus);
        $mform->setDefault('coursestatus', 1);

        // Badge Status.
        if (is_siteadmin()) {
            $mform->addElement('html', '<hr>');
            $mform->addElement('header', 'others1', get_string('badgesec', 'local_course_extrasettings'));
            $status = array('0' => get_string('inactivebadge', 'local_course_extrasettings'),
                '1' => get_string('activebadge', 'local_course_extrasettings'));
            $select = $mform->addElement('select', 'badgestatus', get_string('badgestatus', 'local_course_extrasettings'), $status);
            $mform->setDefault('badgestatus', 0);
        }

        // Badge section in English.
        $mform->addElement('html', '<hr>');
        $mform->addElement('header', 'others1', get_string('enbadgehead', 'local_course_extrasettings'));

        $mform->addElement('filemanager', 'badgeimage', get_string('badgeimage', 'local_course_extrasettings'), null, array('subdirs' => false, 'maxbytes' => '10MB', 'accepted_types' => '*', 'maxfiles' => 1));

        $mform->addElement('textarea', 'enbadgecriteria', get_string('badgeccriteria', 'local_course_extrasettings'), 'wrap="virtual" rows="5" cols="80"');
        $mform->setType('enbadgecriteria', PARAM_TEXT);

        $mform->addElement('textarea', 'encompetence', get_string('competence', 'local_course_extrasettings'), 'wrap="virtual" rows="5" cols="80"');
        $mform->setType('encompetence', PARAM_TEXT);

        $mform->addElement('tags', 'enbadgetags', get_string('btags', 'local_course_extrasettings'));
        $mform->setType('enbadgetags', PARAM_RAW);
        $mform->addHelpButton('enbadgetags', 'enbadgetagshelp', 'local_course_extrasettings');

        // Badge section in Italian.
        $mform->addElement('html', '<hr>');
        $mform->addElement('header', 'others1', get_string('itbadgehead', 'local_course_extrasettings'));

        $mform->addElement('textarea', 'itbadgecriteria', get_string('badgeccriteria', 'local_course_extrasettings'), 'wrap="virtual" rows="5" cols="80"');
        $mform->setType('itbadgecriteria', PARAM_TEXT);

        $mform->addElement('textarea', 'itcompetence', get_string('competence', 'local_course_extrasettings'), 'wrap="virtual" rows="5" cols="80"');
        $mform->setType('itcompetence', PARAM_TEXT);

        $mform->addElement('tags', 'itbadgetags', get_string('btags', 'local_course_extrasettings'));
        $mform->setType('itbadgetags', PARAM_RAW);
        $mform->addHelpButton('itbadgetags', 'itbadgetagshelp', 'local_course_extrasettings');

        $this->add_action_buttons(true, get_string('savechanges1', 'local_course_extrasettings'), 'wrap="virtual" rows="10" cols="50"');
        $mform->addElement('hidden', 'courseid', $COURSE->id);
        $mform->setType('courseid', PARAM_INT);
    }

    function validation($data, $files) {
        global $DB;
        $errors = parent::validation($data, $files);
        $T0 = $data['enrolstart'];
        $T1 = $data['crsopen'];
        $T2 = $data['tutoringstart'];
        $T3 = $data['tutoringstop'];
        $T5 = $data['enrolstop'];
        $T6 = $data['crsclosed'];

        // T0 should <= T1 - 2weeks(14days).
        if ($T0 >= ($T1 - (14 * 24 * 60 * 60))) {
            $errors['enrolstart'] = get_string('enrolstarttaken', 'local_courselifecycle', '');
        }
        // T2 should >= T1.
        if ($T2 < $T1) {
            $errors['tutoringstart'] = get_string('tutoringstarttaken', 'local_courselifecycle', '');
        }
        // T3 should >=  T2+Course_Duration+2_weeks.
        if (($T3 <= ($T2 + (7 * 24 * 60 * 60) + (14 * 24 * 60 * 60))) || ($T3 <= $T2)) {
            $errors['tutoringstop'] = get_string('tutoringstoptaken', 'local_courselifecycle', '');
        }
        // T5 should >= T0 and < T6. (2*Course_Duration weeks)
//        if ($courseMode == 1) { // Tutored mode.
//            if (($T5 < $T0) || ($T5 >= $T6)) {
//                $errors['enrolstop'] = get_string('enrolstoptutoredtaken', 'local_courselifecycle', '');
//            }
//        } else {
//            if (($T5 < $T0) || ($T5 >= ($T6 - (2 * 7 * 24 * 60 * 60)))) {
//                $errors['enrolstop'] = get_string('enrolstopselftaken', 'local_courselifecycle', '');
//            }
//        }
//
//        // T6 should >= T1. For one-shot edition.(T7 is unset)
//        if (($T7 == 4102354800) || ($T7 == 0)) {
//            if ($T6 <= $T1) {
//                $errors['crsclosed'] = get_string('crsclosedtaken', 'local_courselifecycle', '');
//            }
//        } else { // T6 should >= T7-2months. For several shot edition.(T7 is set).
//            if (($T6 <= $T1) && ($T6 <= ($T7 - (60 * 24 * 60 * 60)))) {
//                $errors['crsclosed'] = get_string('crsclosedT7taken', 'local_courselifecycle', '');
//            }
//        }

        return $errors;
    }

}
