<?php
// $Id: inscriptions_massives_form.php 352 2010-02-27 12:16:55Z ppollet $

require_once($CFG->libdir . '/formslib.php');
require_once('lib.php');
class course_extrasettings_form extends moodleform {
    public function definition() {
        global $CFG, $COURSE;

        $mform = & $this->_form;
        $course = $this->_customdata['course'];
        $general = $this->_customdata['general'];
        $payment = $this->_customdata['payment'];

        $mform->addElement('header', 'payments', get_string('payments', 'local_course_extrasettings'));

        $radioarray = array();
        $attributes = array('size' => '2');
        $radioarray[] =& $mform->createElement('radio', 'yesno', '', get_string('yes'), 1, $attributes);
        $radioarray[] =& $mform->createElement('radio', 'yesno', '', get_string('no'), 0, $attributes);
        $mform->addGroup($radioarray, 'radioar', get_string('pcode', 'local_course_extrasettings'), '', array(' '), false);
        $mform->setDefault('radioar[yesno]', 1);

        $attributes = array('size' => '20');
        $mform->addElement('text', 'promocode', get_string('promocode', 'local_course_extrasettings'),
        array('optional' => true, 'step' => 1));
        $mform->setType('promocode', PARAM_TEXT);
        $mform->disabledIf('promocode', 'radioar[yesno]', 'eq', 0);

        $attributes = array('size' => '10');
        $mform->addElement('text', 'discount', get_string('discount', 'local_course_extrasettings'), $attributes);
        $mform->setType('discount', PARAM_INT);
        $mform->disabledIf('discount', 'radioar[yesno]', 'eq', 0);

        $mform->addElement('date_selector', 'promoenddate', get_string('promoenddate', 'local_course_extrasettings'));
        $mform->setDefault('promoenddate', time() + 3600 * 24);
        $mform->disabledIf('promoenddate', 'radioar[yesno]', 'eq', 0);

        $mform->addElement('selectyesno', 'active', get_string('active', 'local_course_extrasettings'));
        $mform->setDefault('active', 1);
        $mform->disabledIf('active', 'radioar[yesno]', 'eq', 0);

        $this->add_action_buttons(true, get_string('savechanges', 'local_course_extrasettings'));

        $mform->addElement('hidden', 'courseid', $course->id);
        $mform->setType('courseid', PARAM_INT);
        $mform->addElement('hidden', 'general', $general);
        $mform->setType('general', PARAM_INT);
        $mform->addElement('hidden', 'payment', $payment);
        $mform->setType('payment', PARAM_INT);
    }
    public function validation($data, $files) {
        global $DB;
        $errors = parent::validation($data, $files);

        $payment = $DB->get_record('course_extrasettings_payment', array('id' => $data['payment']));
        if ($data['radioar']['yesno'] == '1') {
            if (isset($data['promocode'])) {
                if ($data['promocode'] == '') {
                    $errors['promocode'] = 'required';
                }
            }
            if (isset($data['discount'])) {
                if ($data['discount'] == '') {
                    $errors['discount'] = 'required';
                }
            }
        }
        if (isset($data['promocode'])) {
            if ($data['promocode'] !== $payment->promocode) {
                $paymentexists = $DB->record_exists('course_extrasettings_payment', array('courseid' => $data['courseid'],
                'promocode' => $data['promocode']));
                if ($paymentexists) {
                    $errors['promocode'] = get_string('promocodetaken', 'local_course_extrasettings');
                }
            }
        }
        return $errors;
    }
}

