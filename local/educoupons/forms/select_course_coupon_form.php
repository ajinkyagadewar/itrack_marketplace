<?php
require_once("{$CFG->libdir}/formslib.php");

class select_course_coupon_form extends moodleform{
    
    protected function definition() {
        $mform = $this->_form;
        global  $COURSE, $DB;
        //$course = $this->_customdata;
        $courseid = optional_param('courseid', null, PARAM_INT);  
        $sitecourse = $DB->get_record('course',array('id' => 1));
        $categories = $DB->get_records('course_categories',array());
        $courselist = [];
        $finallistlist = [];
        $arccat = $DB->get_record('course_categories',array('idnumber' => 'ARC000'),'id');
        unset($categories[$arccat->id]);
        //courselisting oin dropdown
        $site[$sitecourse->id] = $sitecourse->fullname.' (site level)';
        //$seperator = array('-----------------------------------------------------------------');
        foreach ($categories as $catid => $category) {
            if(($category->parent) != ($arccat->id)){
                $courses = $DB->get_records('course',array('category' => $category->id));
                foreach ($courses as $id => $course) {
                   $courselist[$id] = $category->name.' / '.$course->fullname;
                } 
            }
        } 
        $courselistfinal = $site  + $courselist;
        $select = $mform->addElement('select', 'courseid', 'Course', $courselistfinal, ['id'=> 'id_course']);
        $mform->setType('courseid', PARAM_INT);
        if($courseid){
            $select->setSelected($courseid);
        }
        $mform->addRule('courseid', get_string('requiredcourse', 'local_educoupons'), 'required', null, 'server');
        $this->add_action_buttons(true, get_string('search', 'local_educoupons'));
    }
}
