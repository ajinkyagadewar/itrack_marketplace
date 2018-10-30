<?php

$string['pluginname'] = 'Update enrollment, course dates in couse life cycle';
$string['pluginnames'] = 'Update enrollment, course dates in couse life cycle';

// Course Life Cycle from Course Extra Settings.
// Course Date.
$string['dateandarchival'] = 'Course Dates';

// Course mode.
$string['crsmode'] = 'Course Mode';
$string['crsmode-tutored'] = 'Tutored';
$string['crsmode-selfed'] = 'Self Paced';
// Maintainance mode.
$string['crsmaintenance'] = 'Maintenance Mode';
$string['crsmaintenance-tutored'] = 'Soft Tutored';
$string['crsmaintenance-selfed'] = 'Self Paced';

$string['enrolstartT0'] = 'Enrollments Start(T0)';
$string['enrolstartT0_help'] = 'Enrollment Start Help: ';
$string['enrolstartT0_help_help'] = 'T0 is at least 2 weeks before T1';

$string['crsopenT1'] = 'Course Opens(T1)';
$string['crsopenT1_help'] = 'Course Open Help: ';
$string['crsopenT1_help_help'] = 'The value is coming from Course start sate which is coming from Course edit settings';

$string['tutoringstartT2'] = 'Tutoring Starts(T2)';
$string['tutoringstartT2_help'] = 'Enrollment Start Help: ';
$string['tutoringstartT2_help_help'] = 'T2 is, usually equal to T1';

$string['tutoringstopT3'] = 'Tutoring Stops(T3)';
$string['tutoringstopT3_help'] = 'Active Tutoring Stop Help: ';
$string['tutoringstopT3_help_help'] = 'T3 is at least T2+Course_Duration+2_weeks';

$string['selfpacedstartT4'] = 'Soft Tutoring / Self-Paced Start(T4)';
$string['selfpacedstartT4_help'] = 'Soft Tutoring / Self-Paced Start Help: ';
$string['selfpacedstartT4_help_help'] = 'T4 is equal to T3+1_day';

$string['enrolstopT5'] = 'Enrollment Stops(T5)';
$string['enrolstopT5_help'] = 'Enrollment Stop Help: ';
$string['enrolstopT5_help_help'] = 'Course mode Self-Paced: is at least 2*Course_Duration weeks before T6.' .
        '<br />' . 'Course mode Tutored: T5 is Course_Duration before T6';

$string['crsclosedT6'] = 'Course Closes(T6)';
$string['crsclosedT6_help'] = 'Course Closed Help: ';
$string['crsclosedT6_help_help'] = 'T6 is 2 months before next edition';

$string['nextedition'] = 'Next Session';
$string['nexteditionNo'] = 'No';
$string['nexteditionYes'] = 'Yes';
$string['enableedition'] = 'Enable';

$string['nextEditionT7'] = 'Next Session(T7)';
$string['nextEditionT7_help'] = 'Next Edition';
$string['nextEditionT7_help_help'] = 'Next edition Course Open Date';

// Form validation.
$string['enrolstarttaken'] = 'T0 is at least 2 weeks(14days) before T1';
$string['tutoringstarttaken'] = 'T2 should not less than T1';
$string['tutoringstoptaken'] = 'T3 is at least T2+Course_Duration(min. 1week)+2_weeks';
$string['enrolstoptutoredtaken1'] = 'T5 should be greater than T3';
$string['enrolstoptutoredtaken1a'] = 'T5 should be greater than T1';
$string['enrolstoptutoredtaken2'] = 'T5 should be greater than T3 and less than T6';
$string['nexteditiontaken'] = 'T7 should be greater than T6';

// Course Status.
$string['coursestatus'] = 'Course Archival Status';
$string['cactive'] = 'Open';
$string['carchive'] = 'Archive';
$string['coursestatus_level'] = 'Admin can forcefully check this as Archived';

// Sync Status.
$string['syncstatus'] = 'Sync Status';
$string['syncstatus_help'] = 'Sync Status Help: ';
$string['syncstatus_help_help'] = "Sync_None: Dates for this course have not to be synced.<br />
        Sync_Forward: Sync FROM extrasettings TO course-settings and enrollment-plugin.<br />
        Sync_Backward: Sync FROM course-settings and enrollment-plugin TO extrasettings.";
$string['syncnone'] = 'None';
$string['syncforward'] = 'Forward';
$string['syncbackward'] = 'Backward';


$string['selfpacedfrom'] = 'Self-paced from ';
$string['scheduledSess'] = 'Scheduled Sessions:';
$string['crsclsnotset'] = 'Not Set';

//Course Details Page.
$string['crsAgenda'] = 'Course Agenda';
$string['crsstatus'] = 'Course Status';
$string['cagenda_t0'] = 'Enrollments Start';
$string['cagenda_t1'] = 'Course Opens';
$string['cagenda_t2'] = 'Tutoring Starts';
$string['cagenda_t3'] = 'Tutoring Stops';
$string['cagenda_t5'] = 'Enrollment Stops';
$string['cagenda_t6a'] = 'Course Closes';
$string['cagenda_t6b'] = 'Course Closed';
$string['cagenda_t7'] = 'Next Session';

$string['notAvailable'] = 'N/A';
$string['notSet'] = 'Not Set';

$string['crsstatus_condiA'] = 'Upcoming';
$string['crsstatus_condiB'] = 'Pre-enrolling';
$string['crsstatus_condiC'] = 'Tutoring';
$string['crsstatus_condiD'] = 'Soft Tutoring';
$string['crsstatus_condiE'] = 'Self Pacement';
$string['crsstatus_condiF'] = 'Self Pacement';
$string['crsstatus_condiG'] = 'Closing';
$string['crsstatus_condiH'] = 'Archived';

$string['softtutoringFrom'] = 'Soft Tutoring from';
$string['selfpacedFrom'] = 'Self Paced from';

// Course boxes startdate and green/yellow/red dot.
$string['crsbox_selfpaced'] = 'Self Paced';
$string['crsbox_closing'] = 'Closing';
$string['crsbox_archived'] = 'Archived';
$string['crsbox_open'] = 'Current';
$string['crsbox_softtutoring'] = 'Self Paced';

// Pathway Status.
$string['path_crsstatus'] = 'Status';
$string['path_status'] = 'Pathway Status';
$string['pathstatus_current'] = 'Current';

$string['pathstatus_comingsoon'] = 'Coming Soon';
$string['pathstatus_comingsoon_secondary'] = 'Enrollments from ';
$string['pathstatus_upcoming'] = 'Upcoming';
$string['pathstatus_closing'] = 'Closing';
$string['pathstatus_closing_secondary'] = 'New enrollments not allowed';

$string['pathstatus_archived'] = 'Archived';

$string['pathwaystatus_secondarytext_enrol'] = 'Enrol now';
$string['pathwaystatus_secondarytext_enrolled'] = 'Already enrolled, continue!';

$string['path_enrolstart'] = 'Enrollment';
$string['path_active'] = 'Active';
$string['path_to'] = 'to';
$string['notavail'] = 'N/A';
$string['datefrom'] = 'from ';

// Single Course Enrolment Button.
$string['crsenrol_T0_ptext'] = 'Upcoming';
$string['crsenrol_T0_stext'] = 'Enrollments from';
$string['crsenrol_T0T1_ptext'] = 'Coming Soon';
$string['crsenrol_T0T1_stext_loggedin_alreadyenrolled'] = 'Already enrolled!';
$string['crsenrol_ptext_selfpaced'] = 'Self Paced';
$string['crsenrol_stext_notloggedin_timeup'] = "Sorry, time is up!";
$string['crsenrol_stext_loggedin_lectureonly'] = 'Lectures Only!';
$string['crsenrol_T5T6_ptext'] = 'Closing';
$string['crsenrol_T5T6_stext_loggedin'] = 'Access to complete!';
$string['crsenrol_Archived'] = 'Archived';

$string['crsenrol_ptext_active'] = 'Active';
$string['crsenrol_stext_enrolnow'] = 'Enroll now!';
$string['crsenrol_stext_welcomeback'] = 'Welcome back!';
$string['crsenrol_ptext_nextedition'] = 'Next Edition';

// Use in Course rss feed.-- 22/11/16.
$string['apiit_crsstatus_condiA'] = 'Prossimamente';
$string['apiit_crsstatus_condiB'] = 'Pre-iscrizione';
$string['apiit_crsstatus_condiC'] = 'Tutoraggio';
$string['apiit_crsstatus_condiD'] = 'Tutoraggio Soft';
$string['apiit_crsstatus_condiE'] = 'Auto apprendimento';
$string['apiit_crsstatus_condiF'] = 'Auto apprendimento';
$string['apiit_crsstatus_condiG'] = 'In chiusura';
$string['apiit_crsstatus_condiH'] = 'Archiviati';

$string['apien_crsstatus_condiA'] = 'Upcoming';
$string['apien_crsstatus_condiB'] = 'Pre-enrolling';
$string['apien_crsstatus_condiC'] = 'Tutoring';
$string['apien_crsstatus_condiD'] = 'Soft Tutoring';
$string['apien_crsstatus_condiE'] = 'Self Pacement';
$string['apien_crsstatus_condiF'] = 'Self Pacement';
$string['apien_crsstatus_condiG'] = 'Closing';
$string['apien_crsstatus_condiH'] = 'Archived';

$string['apien_pathstatus_current'] = 'Current';
$string['apiit_pathstatus_current'] = 'In corso';
