<?php

// This file is part of the Buykart module for Moodle - http://moodle.org/
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
 * Handles uploading files
 *
 * @package    local_buykart
 * @copyright  Arjun Singh <arjunsingh@elearn10.com>
 * @copyright  Dhruv Infoline Pvt Ltd <lmsofindia.com>
 * @license    http://www.lmsofindia.com 2017 or later
 */

if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.');    ///  It must be included from a Moodle page
}
require_once($CFG->libdir.'/formslib.php');
class local_buykart_upload_image_form extends moodleform {

    function definition() {
        global $CFG;

        $mform =& $this->_form;
        $mform->addElement('filepicker', 'invoiceimage', '');
        $mform->addRule('invoiceimage', null, 'required', null, 'client');

        $this->add_action_buttons();
    }

    function validation($data, $files) {
        $errors = parent::validation($data, $files);

        $supportedtypes = array('jpe' => 'image/jpeg',
                                'jpeIE' => 'image/pjpeg',
                                'jpeg' => 'image/jpeg',
                                'jpegIE' => 'image/pjpeg',
                                'jpg' => 'image/jpeg',
                                'jpgIE' => 'image/pjpeg');

        $files = $this->get_draft_files('invoiceimage');
        if ($files) {
            foreach ($files as $file) {
                if (!in_array($file->get_mimetype(), $supportedtypes)) {
                    $errors['invoiceimage'] = get_string('unsupportedfiletype', 'local_buykart');
                }
                if($file->get_filesize() > 1048576){
                        $errors['invoiceimage'] = get_string('filesizelimit', 'local_buykart');
                }
            }
        } else {
            $errors['invoiceimage'] = get_string('nofileselected', 'local_buykart');
        }

        return $errors;
    }
}
