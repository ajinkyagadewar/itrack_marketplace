<?php
/**
 * Lesson Automation block caps.
 *
 * @package    block_attendance_completion
 * @author	   Pratim Sarangi <Moodle Of India>
 * @copyright  Moodle Of India <http://www.moodleofindia.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 // cost and currency need to be changed to pick from course extra settings
 */
 
class block_attendance_completion extends block_base {
    public function init() {
        $this->title = get_string('attendance_completion', 'block_attendance_completion');
    }
    // The PHP tag and the curly bracket for the class definition 
    // will only be closed after there is another function added in the next section.
	public function get_content() {
		global $CFG,$USER,$DB,$COURSE;
		$user = $USER;
		if ($this->content !== null) {
			return $this->content;
		}
 
		$this->content =  new stdClass;
		$this->content->text = '';
		$paypalurl = empty($CFG->usepaypalsandbox) ? 'https://www.paypal.com/cgi-bin/webscr' : 'https://www.sandbox.paypal.com/cgi-bin/webscr';
	
	// first check for the curreny in csettings general
	// cost and currency needs to change field is currency in csettings_general table
		$chk_currency=$DB->get_field_sql("SELECT currency from {course_extrasettings_general} where courseid = $COURSE->id");
		//$chk_currency = 'EUR';
		//for paypal payment for just attendnce which is 'cost' field in course extra settings
		$plugin = $DB->get_record('enrol',array('enrol'=>"edupay",'courseid'=>"$COURSE->id",'name'=>'firstattend'));
		$pay = enrol_get_plugin('edupay');

		$chk_firstatt=$DB->get_record('enrol_edupay',array('item_name'=>'firstattend','userid'=>$user->id,'courseid'=>$COURSE->id));
		
		// cost and currency needs to change field is cost in csettings_general table
		$chk_firstcost=$DB->get_field_sql("SELECT cost from {course_extrasettings_general} where courseid = $COURSE->id");
		
		if ($chk_firstcost !=0 and $plugin) { // we want to show this only if there is actual cost
		
			if(!$chk_firstatt){
			
			//the below is to check dependency... now not needed
			/* $chk_attendanceat1 = $DB->get_record('enrol_paypal',array('item_name'=>'attendance_of_completion','userid'=>$user->id,'courseid'=>$COURSE->id));
			if($chk_attendanceat1){
				$this->content->text .= '<img src="'.$CFG->wwwroot.'/blocks/attendance_completion/pix/msg_icon.png'.'" height="20px" width="20px" style="margin-right: 3px"/>'.get_string('Youhavenotattemptedtheexamination', 'block_attendance_completion');
			} else {
				$this->content->text .= '<img src="'.$CFG->wwwroot.'/blocks/attendance_completion/pix/msg_icon.png'.'" height="20px" width="20px" style="margin-right: 3px"/>'.get_string('Youhavenotattemptedtheexaminationdisable', 'block_attendance_completion');
			} */
			
			$this->content->text .= '<img src="'.$CFG->wwwroot.'/blocks/attendance_completion/pix/msg_icon.png'.'" height="20px" width="20px" style="margin-right: 3px"/>'.get_string('Youhavenotpaidfirst', 'block_attendance_completion');
			
			//$this->content->text .='<button type="button">Click to Pay</button>';
			
			//paypal form
			$this->content->text .='<form name="mypaypal" id="mypaypal" action="'.$paypalurl .'" method="post">';
			$this->content->text .='<input type="hidden" name="cmd" value="_xclick" />';
			$this->content->text .='<input type="hidden" name="charset" value="utf-8" />';
			$this->content->text .='<input type="hidden" name="business" value="'.$pay->get_config('edupaybusiness').'" />';
			$this->content->text .='<input type="hidden" name="item_name" value="firstattend" />';
			$this->content->text .='<input type="hidden" name="item_number" value="'.get_string('signuppaypal', 'block_attendance_completion').'" />';
			$this->content->text .='<input type="hidden" name="quantity" value="1" />';
			$this->content->text .='<input type="hidden" name="on0" value="'.$user->username.'" />';
			$this->content->text .='<input type="hidden" name="os0" value="'.$user->firstname.' '.$user->lastname.'" />';
			$this->content->text .='<input type="hidden" name="custom" value="'.$user->id.'-'.$plugin->courseid.'-'.$plugin->id.'" />';

			//$this->content->text .='<input type="hidden" name="currency_code" value="'.$plugin->currency.'" />';
			//$this->content->text .='<input type="hidden" name="amount" value="'.$plugin->cost.'" />';
			
			$this->content->text .='<input type="hidden" name="currency_code" value="'.$chk_currency.'" />';
			$this->content->text .='<input type="hidden" name="amount" value="'.$chk_firstcost.'" />';

			$this->content->text .='<input type="hidden" name="for_auction" value="false" />';
			$this->content->text .='<input type="hidden" name="no_note" value="1" />';
			$this->content->text .='<input type="hidden" name="no_shipping" value="1" />';
			$this->content->text .='<input type="hidden" name="notify_url" value="'.$CFG->wwwroot.'/blocks/attendance_completion/ipn.php'.'" />';
			$this->content->text .='<input type="hidden" name="return" value="'.$CFG->wwwroot.'/my/index.php'.'" />';
			$this->content->text .='<input type="hidden" name="cancel_return" value="'.$CFG->wwwroot.'" />';
			$this->content->text .='<input type="hidden" name="rm" value="2" />';
			$this->content->text .='<input type="hidden" name="cbt" value="'.get_string('ClickheretoenteryourunimoreDashboard', 'block_attendance_completion').'" />';

			$this->content->text .='<input type="hidden" name="first_name" value="'.$user->firstname.'" />';
			$this->content->text .='<input type="hidden" name="last_name" value="'.$user->lastname.'" />';
			$this->content->text .='<input type="hidden" name="address" value="" />';
			$this->content->text .='<input type="hidden" name="city" value="'.$user->city.'" />';
			$this->content->text .='<input type="hidden" name="email" value="'.$user->email.'" />';
			$this->content->text .='<input type="hidden" name="country" value="'.$user->country.'" />';
			
			// check if user has paid the attendance of completion as we will disable if not paid
			// this is not needed now as we are not going to disable anything Mihir
			/* $chk_attendanceat = $DB->get_record('enrol_paypal',array('item_name'=>'attendance_of_completion','userid'=>$user->id,'courseid'=>$COURSE->id));
			if($chk_attendanceat){
			$disable='';
			}else {
			$disable='disabled';
			}
			$this->content->text .='<input type="submit" value="'.get_string('Clicktopay', 'block_attendance_completion').'"'.$disable.'/>'; */
			
			$this->content->text .='<input type="submit" value="'.get_string('Clicktopay', 'block_attendance_completion').'" />';
			$this->content->text .='</form>';
			
			}else{
			$this->content->text .= '<img src="'.$CFG->wwwroot.'/blocks/attendance_completion/pix/payment_done.png'.'" height="20px" width="20px" style="margin-right: 3px"/>'.get_string('Youhavesuccessfullydonethepaymentforexamination', 'block_attendance_completion');
			}
		
		}
		
		
		//for paypal payment for attendance of completion
		$plugin = $DB->get_record('enrol',array('enrol'=>"edupay",'courseid'=>"$COURSE->id",'name'=>'attendance_of_completion'));
		$pay = enrol_get_plugin('edupay');
		$chk_attendance = $DB->get_record('enrol_edupay',array('item_name'=>'attendance_of_completion','userid'=>$user->id,'courseid'=>$COURSE->id));
		
		// cost and currency need to be changed to pick from course extra settings the field is costforattendance in csettings_general table
		$chk_attendcost=$DB->get_field_sql("SELECT costforattendance from {course_extrasettings_general} where courseid = $COURSE->id");
		
		if($chk_attendcost != 0 and $plugin) {
		
			if(!$chk_attendance){
			$this->content->text .= '<img src="'.$CFG->wwwroot.'/blocks/attendance_completion/pix/msg_icon.png'.'" height="20px" width="20px" style="margin-right: 3px"/>'.get_string('Youhavenotgettheattendanceofcompletion', 'block_attendance_completion');
			//paypal form
			$this->content->text .='<form name="mypaypal" id="mypaypal" action="'.$paypalurl .'" method="post">';
			$this->content->text .='<input type="hidden" name="cmd" value="_xclick" />';
			$this->content->text .='<input type="hidden" name="charset" value="utf-8" />';
			$this->content->text .='<input type="hidden" name="business" value="'.$pay->get_config('edupaybusiness').'" />';
			$this->content->text .='<input type="hidden" name="item_name" value="attendance_of_completion" />';
			$this->content->text .='<input type="hidden" name="item_number" value="'.get_string('signuppaypal', 'block_attendance_completion').'" />';
			$this->content->text .='<input type="hidden" name="quantity" value="1" />';
			$this->content->text .='<input type="hidden" name="on0" value="'.$user->username.'" />';
			$this->content->text .='<input type="hidden" name="os0" value="'.$user->firstname.' '.$user->lastname.'" />';
			$this->content->text .='<input type="hidden" name="custom" value="'.$user->id.'-'.$plugin->courseid.'-'.$plugin->id.'" />';

			//$this->content->text .='<input type="hidden" name="currency_code" value="'.$plugin->currency.'" />';
			//$this->content->text .='<input type="hidden" name="amount" value="'.$plugin->cost.'" />';
			
			$this->content->text .='<input type="hidden" name="currency_code" value="'.$chk_currency.'" />';
			$this->content->text .='<input type="hidden" name="amount" value="'.$chk_attendcost.'" />';

			$this->content->text .='<input type="hidden" name="for_auction" value="false" />';
			$this->content->text .='<input type="hidden" name="no_note" value="1" />';
			$this->content->text .='<input type="hidden" name="no_shipping" value="1" />';
			$this->content->text .='<input type="hidden" name="notify_url" value="'.$CFG->wwwroot.'/blocks/attendance_completion/ipn.php'.'" />';
			$this->content->text .='<input type="hidden" name="return" value="'.$CFG->wwwroot.'/my/index.php'.'" />';
			$this->content->text .='<input type="hidden" name="cancel_return" value="'.$CFG->wwwroot.'" />';
			$this->content->text .='<input type="hidden" name="rm" value="2" />';
			$this->content->text .='<input type="hidden" name="cbt" value="'.get_string('ClickheretoenteryourunimoreDashboard', 'block_attendance_completion').'" />';

			$this->content->text .='<input type="hidden" name="first_name" value="'.$user->firstname.'" />';
			$this->content->text .='<input type="hidden" name="last_name" value="'.$user->lastname.'" />';
			$this->content->text .='<input type="hidden" name="address" value="" />';
			$this->content->text .='<input type="hidden" name="city" value="'.$user->city.'" />';
			$this->content->text .='<input type="hidden" name="email" value="'.$user->email.'" />';
			$this->content->text .='<input type="hidden" name="country" value="'.$user->country.'" />';

			$this->content->text .='<input type="submit" value="'.get_string('Clicktopay', 'block_attendance_completion').'" />';

			$this->content->text .='</form>';
			
			}else{
			$this->content->text .= '<img src="'.$CFG->wwwroot.'/blocks/attendance_completion/pix/payment_done.png'.'" height="20px" width="20px" style="margin-right: 3px"/>'.get_string('Youhavesuccessfullydonethepaymentforattendanceofcompletion', 'block_attendance_completion').'</br>';
			}
		
		}
		
		
		// this is for verififed certificate
		$plugin = $DB->get_record('enrol',array('enrol'=>"edupay",'courseid'=>"$COURSE->id",'name'=>'verifiedcerti'));
		$pay = enrol_get_plugin('edupay');
	
		$chk_verifiedcerti=$DB->get_record('enrol_edupay',array('item_name'=>'verifiedcerti','userid'=>$user->id,'courseid'=>$COURSE->id));
		// cost and currency need to be changed to pick from course extra settings. the field is vcostforattendance in csettings_general table
		$chk_verifiedcost=$DB->get_field_sql("SELECT vcostforattendance from {course_extrasettings_general} where courseid = $COURSE->id");
		
		if($chk_verifiedcost !=0 and $plugin) {
		
			if(!$chk_verifiedcerti){
			$this->content->text .= '<img src="'.$CFG->wwwroot.'/blocks/attendance_completion/pix/msg_icon.png'.'" height="20px" width="20px" style="margin-right: 3px"/>'.get_string('applyverified', 'block_attendance_completion');
			//paypal form
			$this->content->text .='<form name="mypaypal" id="mypaypal" action="'.$paypalurl .'" method="post">';
			$this->content->text .='<input type="hidden" name="cmd" value="_xclick" />';
			$this->content->text .='<input type="hidden" name="charset" value="utf-8" />';
			$this->content->text .='<input type="hidden" name="business" value="'.$pay->get_config('edupaybusiness').'" />';
			$this->content->text .='<input type="hidden" name="item_name" value="verifiedcerti" />';
			$this->content->text .='<input type="hidden" name="item_number" value="'.get_string('signuppaypal', 'block_attendance_completion').'" />';
			$this->content->text .='<input type="hidden" name="quantity" value="1" />';
			$this->content->text .='<input type="hidden" name="on0" value="'.$user->username.'" />';
			$this->content->text .='<input type="hidden" name="os0" value="'.$user->firstname.' '.$user->lastname.'" />';
			$this->content->text .='<input type="hidden" name="custom" value="'.$user->id.'-'.$plugin->courseid.'-'.$plugin->id.'" />';

			//$this->content->text .='<input type="hidden" name="currency_code" value="'.$plugin->currency.'" />';
			//$this->content->text .='<input type="hidden" name="amount" value="'.$plugin->cost.'" />';
			
			$this->content->text .='<input type="hidden" name="currency_code" value="'.$chk_currency.'" />';
			$this->content->text .='<input type="hidden" name="amount" value="'.$chk_verifiedcost.'" />';

			$this->content->text .='<input type="hidden" name="for_auction" value="false" />';
			$this->content->text .='<input type="hidden" name="no_note" value="1" />';
			$this->content->text .='<input type="hidden" name="no_shipping" value="1" />';
			$this->content->text .='<input type="hidden" name="notify_url" value="'.$CFG->wwwroot.'/blocks/attendance_completion/ipn.php'.'" />';
			$this->content->text .='<input type="hidden" name="return" value="'.$CFG->wwwroot.'/my/index.php'.'" />';
			$this->content->text .='<input type="hidden" name="cancel_return" value="'.$CFG->wwwroot.'" />';
			$this->content->text .='<input type="hidden" name="rm" value="2" />';
			$this->content->text .='<input type="hidden" name="cbt" value="'.get_string('ClickheretoenteryourunimoreDashboard', 'block_attendance_completion').'" />';

			$this->content->text .='<input type="hidden" name="first_name" value="'.$user->firstname.'" />';
			$this->content->text .='<input type="hidden" name="last_name" value="'.$user->lastname.'" />';
			$this->content->text .='<input type="hidden" name="address" value="" />';
			$this->content->text .='<input type="hidden" name="city" value="'.$user->city.'" />';
			$this->content->text .='<input type="hidden" name="email" value="'.$user->email.'" />';
			$this->content->text .='<input type="hidden" name="country" value="'.$user->country.'" />';

			$this->content->text .='<input type="submit" value="'.get_string('Clicktopay', 'block_attendance_completion').'" />';

			$this->content->text .='</form>';
			
			}else{
			$this->content->text .= '<img src="'.$CFG->wwwroot.'/blocks/attendance_completion/pix/payment_done.png'.'" height="20px" width="20px" style="margin-right: 3px"/>'.get_string('successverifiedcerti', 'block_attendance_completion').'</br>';
			}
		
		}
		
		
		$this->content->footer = '';
		
		return $this->content;
	}
}   // Here's the closing bracket for the class definition