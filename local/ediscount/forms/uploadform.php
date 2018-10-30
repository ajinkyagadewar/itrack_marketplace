<?php


defined('MOODLE_INTERNAL') || die();

require_once $CFG->libdir.'/formslib.php';


/**
 * Upload a file CVS file with user information.
 *
 * @copyright  2007 Petr Skoda  {@link http://skodak.org}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class admin_uploadpromo_form extends moodleform {
    function definition () {
        $mform = $this->_form;

        $mform->addElement('header', 'settingsheader', get_string('upload'));

        $mform->addElement('filepicker', 'userfile', get_string('file'));
        $mform->addRule('userfile', null, 'required');

  		$mform->addElement('hidden', 'id',1);
        $mform->setType('id', PARAM_INT);

        $this->add_action_buttons(false, 'Upload Promo');
    }
}

?>
