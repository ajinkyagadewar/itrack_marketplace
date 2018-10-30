<?php

//require_once('../../config.php');//D:\wamp\www\moodle2.8.2
//moodleform is defined in formslib.php
require_once($CFG->libdir . "/formslib.php");

class add_institution extends moodleform {

    //Add elements to form
    public function definition() {
        global $CFG, $DB;
        $editid = optional_param('editid', 0, PARAM_INT);
        if (isset($editid)) {
            $uid = $editid;
        } else {
            $uid = 0;
        }
        $mform = & $this->_form; // Don't forget the underscore!
        //Heading
        if ($editid) {
            $mform->addElement('header', 'formhead', get_string('edithead', 'block_institution'));
        } else {
            $mform->addElement('header', 'formhead', get_string('addhead', 'block_institution'));
        }
        //type name
        $mform->addElement('text', 'name', get_string('name', 'block_institution'));
        $mform->addRule('name', get_string('missingname'), 'required', null, 'client');
        $mform->setType('name', PARAM_TEXT);
        //type name
        $mform->addElement('text', 'itname', get_string('itname', 'block_institution'));
        $mform->setType('itname', PARAM_TEXT);
        //Type your Answer here
        $mform->addElement('htmleditor', 'description', get_string('description', 'block_institution'), 'wrap="virtual" rows="6" cols="50"');
        $mform->addElement('htmleditor', 'itdescription', get_string('itdescription', 'block_institution'), 'wrap="virtual" rows="6" cols="50"');
        //1st file picker
        $mform->addElement('filemanager', 'banner', get_string('banner', 'block_institution'), null, array('subdirs' => false, 'maxbytes' => $maxbytes, 'accepted_types' => '*', 'maxfiles' => 1));
        $mform->addHelpButton('banner', 'banner_first', 'block_institution');
        //$content = $mform->get_file_content('banner');
        //2nd file picker
        $mform->addElement('filemanager', 'logo', get_string('logo', 'block_institution'), null, array('subdirs' => false, 'maxbytes' => $maxbytes, 'accepted_types' => '*', 'maxfiles' => 1));
        $mform->addHelpButton('logo', 'logo_first', 'block_institution');
        //$content = $mform->get_file_content('logo');
        $mform->addElement('filemanager', 'logo1', get_string('logo1', 'block_institution'), null, array('subdirs' => false, 'maxbytes' => $maxbytes, 'accepted_types' => '*', 'maxfiles' => 1));
        $mform->addHelpButton('logo1', 'logo_second', 'block_institution');

        $mform->addElement('text', 'country', get_string('country', 'block_institution'));
        //$mform->addRule('country', get_string('missingcountry', 'block_institution'), 'required', null, 'client');
        $mform->setType('country', PARAM_TEXT);

        $mform->addElement('textarea', 'address', get_string('address', 'block_institution'), 'wrap="virtual" rows="5" cols="40"');
        //$mform->addRule('address', get_string('missingaddress', 'block_institution'), 'required', null, 'client');
        $mform->setType('address', PARAM_TEXT);

        $mform->addElement('text', 'refferencename', get_string('refferencename', 'block_institution'));
        $mform->setType('refferencename', PARAM_TEXT);

        // All Course language.
        $options = array(1 => get_string('insttype1', 'block_institution'),
            2 => get_string('insttype2', 'block_institution'));
        $mform->addElement('select', 'insttype', get_string('insttype', 'block_institution'), $options);
        //$mform->setDefault('crslang', array($comma_seprated));

        $mform->addElement('text', 'web', get_string('web', 'block_institution'));
        //$mform->addRule('web', get_string('missingweb', 'block_institution'), 'required', null, 'client');
        $mform->setType('web', PARAM_TEXT);

        $mform->addElement('text', 'facebook', get_string('facebook', 'block_institution'));
        //$mform->addRule('facebook', get_string('missingfacebook', 'block_institution'), 'required', null, 'client');
        $mform->setType('facebook', PARAM_TEXT);

        $mform->addElement('text', 'twitter', get_string('twitter', 'block_institution'));
        //$mform->addRule('twitter', get_string('missingtwitter', 'block_institution'), 'required', null, 'client');
        $mform->setType('twitter', PARAM_TEXT);

        $mform->addElement('text', 'youtube', get_string('youtube', 'block_institution'));
        //$mform->addRule('youtube', get_string('missingyoutube', 'block_institution'), 'required', null, 'client');
        $mform->setType('youtube', PARAM_TEXT);

        $mform->addElement('text', 'map1', get_string('map', 'block_institution'));
        //$mform->addRule('map1', get_string('missingmap', 'block_institution'), 'required', null, 'client');
        $mform->setType('map1', PARAM_TEXT);
        $mform->addHelpButton('map1', 'googlemap', 'block_institution');

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
