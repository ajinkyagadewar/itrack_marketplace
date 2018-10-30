<?php
// $Id: inscriptions_massives_form.php 352 2010-02-27 12:16:55Z ppollet $

require_once ($CFG->libdir . '/formslib.php');
require_once ('lib.php');
class course_extrasettings_form extends moodleform {

	function definition() {
		global $CFG,$COURSE,$DB;

		$mform = & $this->_form;
		$course = $this->_customdata['course'];
	
		$mform->addElement('header','filehdr', get_string('generals', 'local_course_extrasettings'));
		
		//used as summary field
        $mform->addElement('htmleditor', 'syllabus', get_string('syllabus', 'local_course_extrasettings'));
		$mform->setType('syllabus', PARAM_RAW);
		
		//as learning outcomes
		$mform->addElement('htmleditor', 'audience', get_string('audience', 'local_course_extrasettings'));
		$mform->setType('audience', PARAM_RAW);
		
		$mform->addElement('filepicker', 'courseimage', get_string('courseimage', 'local_course_extrasettings'));
		$mform->addRule('courseimage','required','required', null, 'client');
        $mform->addHelpButton('courseimage', 'course_pic','local_course_extrasettings');

		$specialname= $DB->get_records('block_eduopen_master_special');
		foreach($specialname as $specializationname){
		$options1[$specializationname->name] = $specializationname->name;
		}
        $options1['none']= 'None';
		$select = $mform->addElement('select', 'specializations', get_string('specializations', 'local_course_extrasettings'), $options1);
        $mform->addRule('specializations','required','required', null, 'client');
        $select->setMultiple(true);
       
        $instname= $DB->get_records('block_eduopen_master_inst');
		foreach($instname as $institutonname){
		$options[$institutonname->name] = $institutonname->name;
		}
		$select = $mform->addElement('select', 'institution', get_string('institution', 'local_course_extrasettings'), $options);

		$ids = array (
			'Online' => get_string('online', 'local_course_extrasettings'),
			'Classroom' => get_string('classroom', 'local_course_extrasettings'),
            'Blended' => get_string('blended', 'local_course_extrasettings'),
		);
		$mform->addElement('select', 'coursetype', get_string('coursetype', 'local_course_extrasettings'), $ids);
		//$mform->setDefault('coursetype', 'online');
		
		$cids = array (
			'Beginner' => get_string('clevel1', 'local_course_extrasettings'),
			'Intermediate' => get_string('clevel2', 'local_course_extrasettings'),
			'Advanced ' => get_string('clevel3', 'local_course_extrasettings'),
		);
		$mform->addElement('select', 'courselevel', get_string('courselevel', 'local_course_extrasettings'), $cids);
		$mform->setDefault('courselevel', 'beginner');

		$currencytype = array (
            'USD' => get_string('usdollar', 'local_course_extrasettings'),
			'AUD' => get_string('australiandollar', 'local_course_extrasettings'),
			'BRL' => get_string('brazilianreal', 'local_course_extrasettings'),
			'CAD' => get_string('canadiandollar', 'local_course_extrasettings'),
            'CZK' => get_string('czechkoruna', 'local_course_extrasettings'),          
			'DKK' => get_string('danishkrone', 'local_course_extrasettings'),
			'EUR' => get_string('euro', 'local_course_extrasettings'),
			'HKD' => get_string('hongkongdollar', 'local_course_extrasettings'),
			'HUF' => get_string('hungarianforint', 'local_course_extrasettings'),
			'ILS' => get_string('israeliheqel', 'local_course_extrasettings'),
            'JPY' => get_string('japaneseyen', 'local_course_extrasettings'),          
			'MYR' => get_string('malaysianringgit', 'local_course_extrasettings'),           
			'MXN' => get_string('mexicanpeso', 'local_course_extrasettings'),
			'NOK' => get_string('norwegiankrone', 'local_course_extrasettings'),
			'NZD' => get_string('newzdollar', 'local_course_extrasettings'),			
            'PHP' => get_string('philippinepeso', 'local_course_extrasettings'),          
			'PLN' => get_string('polishzloty', 'local_course_extrasettings'),
            '£' => get_string('poundsterling', 'local_course_extrasettings'),
            'SGD' => get_string('singaporedollar', 'local_course_extrasettings'),
			'SEK' => get_string('swedishkrona', 'local_course_extrasettings'),
			'CHF' => get_string('swissfranc', 'local_course_extrasettings'),
			'TWD' => get_string('taiwandollar', 'local_course_extrasettings'),
			'THB' => get_string('thaibaht', 'local_course_extrasettings'),
            'TRY' => get_string('turkishlira', 'local_course_extrasettings'),          
			
            'INR' => get_string('indianrupee', 'local_course_extrasettings'),
		);

        $mform->addElement('select', 'currency', get_string('exam', 'local_course_extrasettings'),$currencytype);
		//$mform->setDefault('currency', 'usdollar');
		
        $attributes=array('size'=>'15');
		$mform->addElement('text', 'cost', get_string('cost', 'local_course_extrasettings'), $attributes);
		$mform->setType('cost', PARAM_INT);
        
        $examid = array (
			'English' => get_string('english', 'local_course_extrasettings'),
			'Italian' => get_string('italian', 'local_course_extrasettings'),
			'Spanish' => get_string('spanish', 'local_course_extrasettings'),
			'French' => get_string('french', 'local_course_extrasettings'),
            'German' => get_string('german', 'local_course_extrasettings'),          
			'Others' => get_string('others', 'local_course_extrasettings'),
			
		);
        
        $mform->addElement('select', 'language', get_string('languagetype', 'local_course_extrasettings'), $examid);
		//$mform->setDefault('language', 'English');
     
		$mform->addElement('selectyesno', 'featurecourse', get_string('featurecourse', 'local_course_extrasettings'));
		$mform->setDefault('featurecourse', 0);
		
		/* $mform->addElement('selectyesno', 'lifetime', get_string('lifetime', 'local_course_extrasettings'));
		$mform->setDefault('lifetime', 1); */
		
		// Attendance certificate
		$mform->addElement('header','cert', get_string('att_cert', 'local_course_extrasettings'));
		
		$mform->addElement('selectyesno', 'certificate1', get_string('att_certificate', 'local_course_extrasettings'));
		$mform->setDefault('certificate1', 0);
		
		$mform->addElement('filepicker', 'certificatedownload1', get_string('att_cert', 'local_course_extrasettings'));
		
		$mform->addElement('htmleditor', 'examrule1', get_string('att_cert_rule', "local_course_extrasettings"));
		$mform->setType('examrule1', PARAM_RAW);
		
		$mform->addElement('selectyesno', 'attendancecompletion', get_string('attendancecompletion', 'local_course_extrasettings'));
		$mform->setDefault('attendancecompletion', 0);
		
		$attributes=array('size'=>'15');
		$mform->addElement('text', 'costforattendance', get_string('costforattendanceofcomplettion', 'local_course_extrasettings'), $attributes);
		$mform->setType('costforattendance', PARAM_INT);
		// end of Attendance Certificate
		
		$mform->addElement('html', '<hr>');
		// Verified Certificate
		$mform->addElement('header','examdetails2', get_string('verify_cert', 'local_course_extrasettings'));
		
		$mform->addElement('selectyesno', 'certificate2', get_string('verify_cert_course', 'local_course_extrasettings'));
		$mform->setDefault('certificate2', 0);
		
		$mform->addElement('filepicker', 'certificatedownload2', get_string('sverify_cert', 'local_course_extrasettings'));
		
        $mform->addElement('htmleditor', 'examrule2', get_string('verify_cert_rule', "local_course_extrasettings"));
		$mform->setType('examrule2', PARAM_RAW);
		
		$mform->addElement('selectyesno', 'vattendancecompletion', get_string('vattendancecompletion', 'local_course_extrasettings'));
		$mform->setDefault('vattendancecompletion', 0);
		
		$attributes=array('size'=>'15');
		$mform->addElement('text', 'vcostforattendance', get_string('vcostforattendanceofcomplettion', 'local_course_extrasettings'), $attributes);
		$mform->setType('vcostforattendance', PARAM_INT);
		
		//end of Verified Certificate
		
		$mform->addElement('html', '<hr>');
		// Exam Certificate
		$mform->addElement('header','examdetails', get_string('exam_cert', 'local_course_extrasettings'));
		
		$mform->addElement('selectyesno', 'certificate', get_string('exam_cert_course', 'local_course_extrasettings'));
		$mform->setDefault('certificate', 0);
		
		$mform->addElement('filepicker', 'certificatedownload', get_string('exam_cert', 'local_course_extrasettings'));
		
        $mform->addElement('htmleditor', 'examrule', get_string('exam_cert_rule', 'local_course_extrasettings'));
		$mform->setType('examrule', PARAM_RAW);
		
		$mform->addElement('selectyesno', 'formalcredit', get_string('formalcredit', 'local_course_extrasettings'));
		$mform->setDefault('formalcredit', 0);
		$attributes=array('size'=>'15');
	   
		$mform->addElement('text', 'costforformalcredit', get_string('costforformalcreditexam', 'local_course_extrasettings'), $attributes);
		$mform->setType('costforformalcredit', PARAM_INT);
       
        $attributes=array('size'=>'15');
		$mform->addElement('text', 'credits', get_string('noofcredit', 'local_course_extrasettings'), $attributes);
		$mform->setType('credits', PARAM_INT);
		//end of Exam Certificate

		$mform->addElement('html', '<hr>');
		// Others
		$mform->addElement('header','others', get_string('others', 'local_course_extrasettings'));
		
		$attributes=array('size'=>'20');
		$mform->addElement('text', 'length', get_string('length', 'local_course_extrasettings'),  $attributes);
		$mform->setType('length', PARAM_TEXT);
		$mform->addRule('length', 'required', 'required', null, 'client');
		$mform->addHelpButton('length', 'length_help', 'local_course_extrasettings');
		
		$attributes=array('size'=>'20');
		$mform->addElement('text', 'estimated', get_string('estimated', 'local_course_extrasettings'),  $attributes);
		$mform->setType('estimated', PARAM_TEXT);
		$mform->addRule('estimated', 'required', 'required', null, 'client');
		$mform->addHelpButton('estimated', 'estimated_help', 'local_course_extrasettings');
		
		$attributes=array('size'=>'30');
		$mform->addElement('textarea', 'videourl', get_string('videourl', 'local_course_extrasettings'),  $attributes);
		$mform->setType('videourl', PARAM_RAW);
		$mform->addHelpButton('videourl', 'videourl_help', 'local_course_extrasettings');
		
		/* $attributes=array('size'=>'20','optional' => true);
		$mform->addElement('text', 'license', get_string('license', 'local_course_extrasettings'),  $attributes);
		$mform->setType('license', PARAM_TEXT); */
		
		$mform->addElement('htmleditor', 'whatsinside', get_string('whatsinside', 'local_course_extrasettings'), 'wrap="virtual" rows="10" cols="50"');
		$mform->setType('whatsinside', PARAM_TEXT);
		
		//Course Format
		$mform->addElement('htmleditor', 'crecruitments', get_string('crecruitments', 'local_course_extrasettings'), 'wrap="virtual" rows="10" cols="50"');
		$mform->setType('crecruitments', PARAM_TEXT);
        
        $mform->addElement('htmleditor', 'textbook', get_string('textbook', 'local_course_extrasettings'));
		$mform->setType('textbook', PARAM_RAW);
		
		$mform->addElement('htmleditor', 'recommendedbackground', get_string('recommendedbackground', 'local_course_extrasettings'));
		$mform->setType('recommendedbackground', PARAM_RAW);
		
		
		//$mform->addElement('header','payments', get_string('payments', 'local_course_extrasettings'));
		
		/* $attributes=array('size'=>'15');
		$mform->addElement('text', 'cost', get_string('cost', 'local_course_extrasettings'), $attributes);
		$mform->setType('cost', PARAM_INT); */
		
		/* $radioarray=array();
		$radioarray[] =& $mform->createElement('radio', 'yesno', '', get_string('yes'), 1, $attributes);
		$radioarray[] =& $mform->createElement('radio', 'yesno', '', get_string('no'), 0, $attributes);
		$mform->addGroup($radioarray, 'radioar', get_string('pcode', 'local_course_extrasettings'), '', array(' '), false);
		$mform->setDefault('radioar[yesno]', 1);
		
			
		$attributes=array('size'=>'20');
		$mform->addElement('text', 'promocode', get_string('promocode', 'local_course_extrasettings'),  array('optional' => true, 'step' => 1));
		$mform->setType('promocode', PARAM_TEXT);
		$mform->disabledIf('promocode', 'radioar[yesno]', 'eq', 0);
		
		$attributes=array('size'=>'10');
		$mform->addElement('text', 'discount', get_string('discount', 'local_course_extrasettings'), $attributes);
		$mform->setType('discount', PARAM_INT);
		$mform->disabledIf('discount', 'radioar[yesno]', 'eq', 0);
		
		
		$mform->addElement('date_selector', 'promoenddate', get_string('promoenddate', 'local_course_extrasettings'));
        $mform->setDefault('promoenddate', time() + 3600 * 24);
		$mform->disabledIf('promoenddate', 'radioar[yesno]', 'eq', 0);
		
		$mform->addElement('selectyesno', 'active', get_string('active', 'local_course_extrasettings'));
		$mform->setDefault('active', 1);
		$mform->disabledIf('active', 'radioar[yesno]', 'eq', 0);
		 */
 		
		$this->add_action_buttons(true, get_string('savechanges1', 'local_course_extrasettings'));
		
		$mform->addElement('hidden', 'courseid', $course->id);
		$mform->setType('courseid', PARAM_INT);
		//var_dump($mform);
		
	}

	function validation($data, $files) {
		/* global $DB;
		
		$errors = parent :: validation($data, $files);
		if($data['radioar']['yesno'] =='1'){
			if(isset($data['promocode'])) {
				if($data['promocode']==''){
					$errors['promocode'] = 'required';
				}				
			}
			if(isset($data['discount'])) {
				if($data['discount']==''){
					$errors['discount'] = 'required';
				}				
			}			
		}
		if(isset($data['promocode'])) {	
			if ($paymentexists = $DB->record_exists('course_extrasettings_payment', array('courseid'=>$data['courseid'],'promocode'=>$data['promocode']))) {
					$errors['promocode'] = get_string('promocodetaken', 'local_course_extrasettings');  
			}
		}
		return $errors; */
	}
	 
	
	
	
}
?>
