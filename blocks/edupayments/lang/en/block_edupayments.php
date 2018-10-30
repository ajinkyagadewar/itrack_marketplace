<?php
/**
 * Payments block language file.
 *
 * @package    block_edupayments
 * @author	   Arjun Singh <arjunsingh@elearn10.com>
 * @copyright  Lms Of India <http://www.lmsofindia.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
 
 
$string['pluginname'] = 'Payments Block';
$string['edupayments'] = 'Payments';
$string['edupayments:addinstance'] = 'Add a new payments block';
$string['edupayments:myaddinstance'] = 'Add a new payments to the My Moodle page';
$string['create_lesson'] = 'Create a new session/lesson';
$string['student_name'] = 'Student Name';
$string['tutor_name'] = 'Tutor Name';
$string['subject'] = 'Subject';
$string['course'] = 'Course';
$string['post_lesson'] = 'Create Lesson';
$string['timezone'] = 'Timezone';
$string['duration'] = 'Lesson Duration';
$string['defalut_timezone'] = 'Please select a timezone';
$string['defalut_duration'] = 'Please select a duration';
$string['paymentalreadydone'] = 'Payment already done';
$string['date_time'] = 'Lesson date and time';
$string['page_title'] = 'Create A Lesson';
$string['no_student_name'] = 'Please enter a student name.';
$string['no_tutuor_name'] = 'Please enter a tutor name.';
$string['no_course'] = 'Please enter a course name.';
$string['no_subject'] = 'Please enter a subject.';
$string['form_header'] = 'Create a Lesson';
 
$string['notallowed'] = 'Sorry! You are not allowed to view the content of this page.';
$string['coursenotcreated'] = 'Failed to create course, please try again.';
$string['wiziqnotfound'] = 'The module \'WizIQ\' not found, please install the \'WizIQ\' module.';
$string['wiziqnotcreated'] = 'Failed to create the \'WizIQ\' module for the course, please try again.';
$string['allsuccess'] = 'The automated lesson has been created successfully.';
$string['starttimeinvalid'] = 'Current/past date and time are not allowed.';
$string['usernotfound'] = 'User not found! please ensure that you have entered a valid user name';
$string['categoryfound'] = 'Subject not found! please ensure that you have entered a valid subject name.';
$string['sectionnotupdated'] = 'Failed to update section info';
$string['sectionnotfound'] = 'Section not found.';
$string['modnotupdated'] = 'Failed to update module info';
$string['wiziqinstancenotcreated'] = 'Failed to create \'WizIQ\' instance.';
$string['modnotcreated'] = 'Failed to create a new module for the course.';
//haraprasad added get string 23march2015
$string['Youhavenotgettheattendanceofcompletion'] = 'Please pay by clicking below button to get the attendance certificate';
$string['Youhavesuccessfullydonethepaymentforattendanceofcompletion']='You have successfully done the payment for attendance certificate';

$string['Youhavenotpaidfirst']=' Please pay attedance fee by clicking below button.';
$string['Youhavenotattemptedtheexaminationdisable']='Please complete the payment for attendance of completion to enable the button.';
$string['Youhavesuccessfullydonethepaymentforexamination']='You have successfully paid attedance fee';

$string['applyverified']='Please pay by clicking below button to apply for verified certificate.';
$string['applyexamination']='Please pay by clicking below button to apply for examiantion.';
$string['successverifiedcerti']='You have successfully done the payment for verified certificate';

$string['Clicktopay'] = 'Click to pay';
$string['ClickheretoenteryourunimoreDashboard'] = 'Click here to enter your Dashboard';

$string['signuppaypalfirstattend'] = 'firstattend';
$string['signuppaypalatdc'] = 'attendance_of_completion';
$string['signuppaypalverified'] = 'verifiedcerti';
$string['signuppaypalexam'] = 'examination';

$string['thankpaymentfirst'] = 'Thank you for your payment for Attendance fee';
$string['thankpaymentattendcert'] = 'Thank you for your payment for Attendance Certificate';
$string['thankpaymentverified'] = 'Thank you for your payment for Verififed Certificate ';
$string['thankpaymentexam'] = 'Thank you for your payment for Examination';

/* $string['attenditemname'] =  'Attendance Fee';
$string['attendcertitemname'] = 'Attendance Certificate';
$string['verifieditemname'] = 'Verified Certificate';
 */
// this below strings are used in ipn
$string['welcomefirst'] = 'Dear  {$a->user},
We confirm we have received the payment of  {$a->currency} {$a->amount} 
for Attendance Fee for the Eduopen Course {$a->coursename}.

Now you can attend the course: {$a->coursename}.

Eduopen Team
';
// this below strings are used in ipn
$string['welcomeattend'] = 'Dear {$a->user},
We confirm we have received the payment of {$a->currency} {$a->amount} 
for Attendance Certificate for the Eduopen Course {$a->coursename}.

Now you can download the Attendance Certificate for the course: {$a->coursename}.

Eduopen Team
';

// this below strings are used in ipn
$string['welcomeverified'] = 'Dear {$a->user},
We confirm we have received the payment of {$a->currency} {$a->amount} 
for Verified Certificate for the Eduopen Course  {$a->coursename}.

Now you can download the Verified Certificate for the course: {$a->coursename}.

Eduopen Team
';

// this below strings are used in ipn
$string['welcomeexam'] = 'Dear {$a->user},
We confirm we have received the payment of {$a->currency} {$a->amount} 
for Examination Fee for the Course {$a->coursename}.

Now you can attend the examination for the course: {$a->coursename}.

Eduopen Team
';

$string['havepromocd'] = 'Have a Promo Code?';
$string['ediscoutnotexists'] = 'Ediscount not exists.';
$string['promotionsexperid'] = 'Promotion code expired!!';
$string['promotionsinvalid'] = 'Discount code invalid!!';
$string['promotionsvalid'] = 'Discount code valid!!';
$string['promotionfull'] = '100% discount';
$string['freeenrollmentcompleted'] = 'Free enrollment completed';
$string['freepromotion'] = 'Be patient while processing..';
$string['promotionssoldout'] = 'Coupon are sold out!!';
$string['worngdatapost'] =  'Sorry wrong post data';
$string['promotioninactive'] = 'Promotion code is inactive try later';