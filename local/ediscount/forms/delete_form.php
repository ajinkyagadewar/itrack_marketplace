<?php
require_once("{$CFG->libdir}/formslib.php");

class delete_form extends moodleform {
      
    public function __construct() {
        parent::__construct();
    }
    public function definition() {
        $form = $this->_form;              
        $form->addElement('hidden', 'courseid');
        $form->setType('courseid', PARAM_RAW);
        $form->addElement('hidden', 'id');
        $form->setType('id', PARAM_RAW);
        $this->add_action_buttons();
    }   
}