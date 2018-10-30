<?php
require_once("{$CFG->libdir}/formslib.php");
class select_enrol_form extends moodleform{
    
    protected function definition() {
        $mform = $this->_form;
        global  $COURSE, $DB;
        $course = $this->_customdata;
        $mform->addElement('select', 'courseid', get_string('courses'), [$course->id => $course->fullname], ['id'=> 'id_courses', 'readonly'=>'readonly']);
        $mform->setType('courseid', PARAM_INT);
        $mform->addRule('courseid', get_string('requiredcourse', 'local_ediscount'), 'required', null, 'server');
        $erolls = $DB->get_records('enrol', ['courseid' => $course->id,'enrol' => 'edupay']);
        $enrollist = [];
        foreach ($erolls as $id => $enrol) {
            $enrollist[$id] = $enrol->name;
        }  
        $mform->addElement('select', 'enrolid', 'Enrol', $enrollist, ['id'=> 'id_enrol']);
        $mform->setType('enrolid', PARAM_INT);
        $mform->addRule('enrolid', get_string('requiredenroll', 'local_ediscount'), 'required', null, 'server');
        $this->add_action_buttons(true, get_string('search', 'local_ediscount'));
    }
}
