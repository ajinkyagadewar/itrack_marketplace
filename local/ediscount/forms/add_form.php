<?php

// This file is part of MoodleofIndia - http://moodleofindia.com/
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
 * Note class is build for Manage Notes (Create/Update/Delete)
 * @desc Note class have one parameterized constructor to receive global 
 *       resources.
 * 
 * @package    local_questionextend
 * @copyright  2015 MoodleOfIndia
 * @author     shambhu kumar
 * @license    MoodleOfIndia {@web http://www.moodleofindia.com}
 */

require_once("{$CFG->libdir}/formslib.php");
require_once("{$CFG->libdir}/coursecatlib.php");
class add_form extends moodleform{
    
    protected function definition() {
        $mform = $this->_form;
        global  $COURSE, $DB;
        $course = $this->_customdata;
        $mform->addElement('select', 'courseid', get_string('courses'), [$course->id => $course->fullname], ['id'=> 'id_courses', 'disabled'=>'disabled']);
        $mform->setType('courseid', PARAM_INT);
        $mform->addRule('courseid', get_string('requiredcourse', 'local_ediscount'), 'required', null, 'server');
        $erolls = $DB->get_records('enrol', ['courseid' => $course->id,'enrol' => 'edupay']);
        $enrollist = [];
        foreach ($erolls as $id => $enrol) {
            $enrollist[$id] = $enrol->name;
        }  
        
        $mform->addElement('text', 'ppcode', get_string('promotionscode', 'local_ediscount'),array('maxlength'=>'10'));
        $mform->setType('ppcode', PARAM_RAW);
        $mform->setDefaults('ppcode', '');
        $mform->addRule('ppcode', get_string('requiredppcode', 'local_ediscount'), 'required', null, 'server');

        $mform->addElement('text', 'ppcent', get_string('percentdiscount', 'local_ediscount'),array('size'=>'2','maxlength'=>'2'));
        $mform->setType('ppcent', PARAM_RAW);
        $mform->addRule('ppcent', null, 'required', null, 'server');
        $mform->addRule('ppcent', null, 'numeric', null, 'server');

        
        $mform->addElement('select', 'ppuse', get_string('multisingle', 'local_ediscount'),['M' => 'Multiple Use', 'S' => 'Single Use']);
        $mform->setType('ppuse', PARAM_ALPHA);

        
        $mform->addElement('date_selector', 'ppstartdate', get_string('promostart', 'local_ediscount'));
        $mform->addElement('date_selector', 'ppenddate', get_string('promoend', 'local_ediscount'));
        

        $mform->addElement('select', 'status', get_string('status', 'local_ediscount'),[1=>'Active', 0 => 'In-active']);
        $mform->setType('status', PARAM_INT);      
        
        $mform->addElement('text', 'ppreason', get_string('usetimes', 'local_ediscount'));
        $mform->setType('ppreason', PARAM_RAW);
        $mform->addRule('ppreason', null, 'numeric', null, 'server');

        
        $mform->addElement('hidden', 'ppaplied', 0);
        $mform->setType('ppaplied', PARAM_INT);
        
        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);
        $this->add_action_buttons();
    }
    
     
   
}