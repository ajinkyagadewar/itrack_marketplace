<?php
// This file is part of Moodle - http://moodle.org/
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

defined('MOODLE_INTERNAL') || die;

require_once($CFG->libdir . '/formslib.php');
class local_bulkvideo_form extends moodleform {
    protected function definition() {
        $mform = $this->_form;

        $mform->addElement('header', 'settingsheader', get_string('upload'));

        $mform->addElement('filepicker', 'userfile','Please select a \'.csv\' file to upload', null,
            array('maxbytes' =>'1000000', 'accepted_types' => '.csv'));

        $mform->addRule('userfile', get_string('required'), 'required', null, 'client');

        $this->add_action_buttons($cancel = true, get_string('upload'));
    }
}
