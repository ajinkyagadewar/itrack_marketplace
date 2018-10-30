<?php

// This file is part of MoodleofIndia - http://www.moodleofindia.com/
/**
 * This script implements the manage_license of the dashboard, and allows editing
 * of the fooboo License.
 *
 * Local Educoupons 
 * @author     Arjun Singh <arjunsingh@elearn10.com>
 * @package    local_educoupons
 * @copyright  20/10/2016 lms of india
 * @license    http://lmsofindia.com/
 */


require_once("{$CFG->libdir}/formslib.php");
require_once("{$CFG->libdir}/coursecatlib.php");
class add_form extends moodleform{
    
    protected function definition() {
        $mform = $this->_form;
        global  $COURSE, $DB,$USER,$PAGE;
        $courses = $DB->get_records('course',array());
        $courselist = [];
        $site[$courses[1]->id] = $courses[1]->fullname.' (site level)';
        //$seperator = array('-----------------------------------------------------------------');
        unset($courses[1]);
        foreach ($courses as $id => $course) {
            $courselist[$id] = $course->fullname;
        }  
        $courselistfinal = $site + $courselist;
        //print_object($PAGE->url);   
        if (strpos($PAGE->url, "add") !== false){
            $disabled = "['id'=> 'id_course']";
            $readonly = 'maxlength="3"';
        }else{
            $disabled = "['id'=> 'id_course','disabled' => 'disabled']";
            $readonly = 'maxlength="3",readonly="readonly"';
        }
        
        $mform->addElement('select', 'courseid', 'Course', $courselistfinal,['id'=> 'id_course']);
        $mform->setType('courseid', PARAM_INT);
        $mform->addRule('courseid', get_string('requiredcourse', 'local_educoupons'), 'required', null, 'server');

        // Added by Shiuli on 4/11/16.
        $mform->addElement('text', 'couponid', get_string('couponid', 'local_educoupons'));
        $mform->setType('couponid', PARAM_RAW);
        $mform->addRule('couponid', 'required', 'required', null, 'client');
        $mform->addHelpButton('couponid', 'couponid_help', 'local_educoupons');
        
        $mform->addElement('text', 'noc', get_string('noc', 'local_educoupons'),$readonly);
        $mform->setType('noc', PARAM_RAW);
        $mform->addRule('noc', null, 'numeric', null, 'server');
        $mform->addRule('noc', null, 'required', null, 'server');
     
        $mform->addElement('text', 'cpnpercent', get_string('percentdiscount', 'local_educoupons'),$readonly);
        $mform->setType('cpnpercent', PARAM_RAW);
        $mform->addRule('cpnpercent', null, 'numeric', null, 'server');
        $mform->addRule('cpnpercent', null, 'required', null, 'server');

        $mform->addElement('text', 'courseprefix', get_string('courseprefix', 'local_educoupons'),$readonly);
        $mform->setType('courseprefix', PARAM_RAW);
        $mform->addRule('courseprefix', null, 'required', null, 'server');

        $mform->addElement('text', 'recprefix', get_string('recprefix', 'local_educoupons'),$readonly);
        $mform->setType('recprefix', PARAM_RAW);
        $mform->addRule('recprefix', null, 'required', null, 'server');

        $mform->addElement('date_selector', 'cpnstartdate', get_string('cpnstart', 'local_educoupons'));
        $mform->addElement('date_selector', 'cpnenddate', get_string('cpnend', 'local_educoupons'));
        
        $mform->addElement('select', 'status', get_string('status', 'local_educoupons'),[1=>'Active', 0 => 'In-active']);
        $mform->setType('status', PARAM_INT);
        
        $mform->addElement('hidden', 'trackid', 0);
        $mform->setType('trackid', PARAM_INT);

        $mform->addElement('hidden', 'userid', 0);
        $mform->setType('userid', PARAM_INT);

        $mform->addElement('hidden', 'timecreated', 0);
        $mform->setType('timecreated', PARAM_INT);

        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);

        $this->add_action_buttons();
    }
    
     
   
}