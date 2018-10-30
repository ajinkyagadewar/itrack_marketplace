<?php
require_once("{$CFG->libdir}/formslib.php");
require_once("{$CFG->libdir}/coursecatlib.php");
class selectform extends moodleform{
    
    protected function definition() {
        $mform = $this->_form;
        $coursecat = array();
        $coursecat[] = get_string('selectcategory', 'local_ediscount'); 
        foreach (coursecat::make_categories_list() as $id => $category) {
            $coursecat[$id] = $category;
        }
        $mform->addElement('select', 'category', get_string('coursecategory'), $coursecat);
        $mform->setType('category', PARAM_INT);
        
        $mform->addElement('select', 'courses', get_string('courses'));
        $mform->setType('courses', PARAM_INT);
        $mform->addRule('courses', get_string('requiredcourse', 'local_ediscount'), 'required', null, 'server');
        
        $mform->addElement('select', 'enrol', 'Enrol');
        $mform->setType('enrol', PARAM_INT);
        $mform->addRule('enrol', get_string('requiredenroll', 'local_ediscount'), 'required', null, 'server');
        $this->add_action_buttons();
    }
}
