<?php

require_once('../../config.php'); //D:\wamp\www\moodle2.8.2
//moodleform is defined in formslib.php
require_once($CFG->libdir . "/formslib.php");

class add_specialization extends moodleform {

    //Add elements to form
    function definition() {
        global $CFG, $DB;
        $editid = optional_param('editid', 0, PARAM_INT);
        if (isset($editid)) {
            $uid = $editid;
        } else {
            $uid = 0;
        }

        $degree = array('0' => get_string('nodeg', 'block_specialization'),
            '1' => get_string('mdeg', 'block_specialization'),
            '2' => get_string('adeg', 'block_specialization'),
            '3' => get_string('odeg', 'block_specialization'));

        $pathlang = get_string_manager()->get_list_of_translations();

        $mform = $this->_form; // Don't forget the underscore!
        //Heading
        if ($editid) {
            $mform->addElement('header', 'formhead', get_string('edithead', 'block_specialization'));
        } else {
            $mform->addElement('header', 'formhead', get_string('addhead', 'block_specialization'));
        }

        //type name
        $mform->addElement('text', 'name', get_string('pathengname', 'block_specialization'));
        $mform->addRule('name', get_string('missingname'), 'required', null, 'client');
        $mform->setType('name', PARAM_TEXT);

        $mform->addElement('htmleditor', 'overview', get_string('overview', 'block_specialization'), 'wrap="virtual" rows="10" cols="50"');
        $mform->setType('overview', PARAM_RAW);
        $mform->addRule('overview', null, 'required', null, 'client');

        //Select Category.
        $categoryrs = $DB->get_records('course_categories', array('visible' => '1'));
        foreach ($categoryrs as $category) {
            $options2[$category->id] = $category->name;
        }
        $select = $mform->addElement('select', 'category', get_string('select_cat', 'block_specialization'), $options2);
        $mform->setType('category', PARAM_INT);

        $mform->addElement('select', 'pathlanguage', get_string('pathformlang', 'block_specialization'), $pathlang);
        $mform->setType('pathlanguage', PARAM_TEXT);

        //if ($pathlang[0] != 'English') {
        $mform->addElement('text', 'localname', get_string('localpathname', 'block_specialization'));
        $mform->setType('localname', PARAM_TEXT);

        $mform->addElement('htmleditor', 'localoverview', get_string('localoverview', 'block_specialization'), 'wrap="virtual" rows="10" cols="50"');
        $mform->setType('localoverview', PARAM_RAW);
        //}

        $select = $mform->addElement('select', 'degree', get_string('degree', 'block_specialization'), $degree, $attributes);
        $mform->setType('deg_title', PARAM_INT);

        $mform->addElement('text', 'deg_title', get_string('deg_title', 'block_specialization'));
        $mform->setType('deg_title', PARAM_TEXT);

        $codes = array(
            'AUD', 'BRL', 'CAD', 'CHF', 'CZK', 'DKK', 'EUR', 'GBP', 'HKD', 'HUF', 'ILS', 'JPY',
            'MXN', 'MYR', 'NOK', 'NZD', 'PHP', 'PLN', 'RUB', 'SEK', 'SGD', 'THB', 'TRY', 'TWD', 'USD');
        $currencies = array();
        foreach ($codes as $c) {
            $currencies[$c] = new lang_string($c, 'core_currencies');
        }
        $mform->addElement('select', 'deg_currency', get_string('deg_currency', 'block_specialization'), $currencies);
        $mform->setDefault('deg_currency', 'usdollar');

        $mform->addElement('text', 'deg_cost', get_string('deg_cost', 'block_specialization'));
        $mform->setType('deg_cost', PARAM_TEXT);
        $mform->addElement('filemanager', 'specialization_picture', get_string('specialization_picture', 'block_specialization'), null, array('subdirs' => false, 'maxbytes' => $maxbytes, 'accepted_types' => '*', 'maxfiles' => 1));
        $mform->addHelpButton('specialization_picture', 'spec_pic', 'block_specialization');

        $mform->addElement('textarea', 'overview_video', get_string('overview_video', 'block_specialization'), 'wrap="virtual" rows="6" cols="50"');
        //$mform->addRule('overview_video', get_string('missingoverview','block_specialization'), 'required', null, 'client');
        $mform->setType('overview_video', PARAM_RAW);
        $mform->addHelpButton('overview_video', 'over_video', 'block_specialization');
        /* $mform->addElement('filepicker', 'certificate', get_string('certificate', 'block_specialization'), 
          null, array('maxbytes' => $maxbytes, 'accepted_types' => '*')); */
        // Box 1 Title
        $mform->addElement('text', 'title1', get_string('title1', 'block_specialization'));
        $mform->setType('title1', PARAM_TEXT);
        // Box 1 Text
        $mform->addElement('htmleditor', 'text1', get_string('text1', 'block_specialization'), 'wrap="virtual" rows="10" cols="50"');
        $mform->setType('text1', PARAM_RAW);
        // Box 2 Title
        $mform->addElement('text', 'title2', get_string('title2', 'block_specialization'));
        $mform->setType('title2', PARAM_TEXT);
        // Box 2 Text
        $mform->addElement('htmleditor', 'text2', get_string('text2', 'block_specialization'), 'wrap="virtual" rows="10" cols="50"');
        $mform->setType('text2', PARAM_RAW);

        $mform->addElement('htmleditor', 'background', get_string('background', 'block_specialization'), 'wrap="virtual" rows="10" cols="50"');
        $mform->setType('background', PARAM_RAW);
        $mform->addRule('background', null, 'required', null, 'client');
        $mform->addHelpButton('background', 'background_spec', 'block_specialization');

        $status = array('0' => 'Inactive', '1' => 'Active');
        $mform->addElement('select', 'status', get_string('status', 'block_specialization'), $status, $attributes);
        $mform->addRule('status', null, 'required', null, 'client');
 
        $featuredstatus = array('0' => get_string('feturesdno', 'block_specialization'),
            '1' => get_string('feturesdyes', 'block_specialization'));
        $mform->addElement('select', 'featuredpathway', get_string('featuredpath', 'block_specialization'), $featuredstatus, $attributes);
        
        // Pathway Status active/inactive.
        if (is_siteadmin()) {
        // Pathway Archival.
            $mform->addElement('advcheckbox', 'pathwaystatus', get_string('pathstatus', 'block_specialization'), get_string('pathtatus_level', 'block_specialization'), array('group' => 0), array(1, 0));
            $mform->setDefault('pathwaystatus', 1);
        }

        $mform->addElement('hidden', 'updateid', $uid);
        $mform->setType('updateid', PARAM_TEXT);
        //Submit Button
        $buttonarray = array();
        $buttonarray[] = &$mform->createElement('submit', 'submitbutton', get_string('savechanges'));
        $buttonarray[] = &$mform->createElement('reset', 'resetbutton', get_string('reset'));
        $mform->addGroup($buttonarray, 'buttonar', '', array(' '), false);
        $mform->closeHeaderBefore('buttonar');
        //$this->add_action_buttons();
    }

    //Custom validation should be added here
    function validation($data, $files) {
        return array();
    }

}