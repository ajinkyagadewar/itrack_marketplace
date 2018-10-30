<?php

// This file is part of MoodleofIndia - http://moodleofindia.com/
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
 * Note class is build for Manage Notes (Create/Update/Delete)
 * @desc Note class have one parameterized constructor to receive global 
 *       resources.
 * 
 * @package    local_myanalytics
 * @copyright  2015 MoodleOfIndia
 * @author     shambhu kumar
 * @license    MoodleOfIndia {@web http://www.moodleofindia.com}
 */

/*
 * Site level 
*/
$string['plugin']='My analytics';
$string['pluginname']='My analytics';
$string['sitelevel'] = 'Site level';
$string['siteoverview'] = 'Site overview';
$string['sl'] = 'S.No.';
$string['label'] = 'Label';
$string['name'] = 'Name';
$string['email'] = 'Email';
$string['fullname'] = 'Fullname';
$string['active'] = 'Active';
$string['inactive'] = 'Inactive';
$string['viewall'] = 'View All Alerts';
$string['numofuniquelogin'] = 'Unique logins - date chart';
$string['inweeks'] = 'In weeks';
$string['active'] = 'Active';
$string['sociallinks'] =  'Social links';
$string['startdate'] =  'Start date';
$string['image'] =  'Pic';
$string['activity'] =  'Activity';
$string['mayfav'] =  'My favourite';
$string['quizstatus'] =  'Quiz';
$string['quizgraph'] =  'Quiz max/min score';
$string['quiztitle'] =  'Quiz overview report';
$string['totalfourm'] =  'Total forum discussion';
$string['quizname'] =  'Quiz name';
$string['score'] =  'Score';
$string['coursegrade'] =  'Course grade';
$string['phone'] =  'Phone';
$string['discussionchart'] =  'Discussion chart';
$string['upcomingactivity'] =  'Upcoming activity';
$string['notyetloggedin'] =  'Not yet loggedin';
$string['coursequizreport'] =  'Course quiz report';
$string['quizreport'] =  'Quiz report';
$string['usersgradereportfor'] =  'Users grade report for ';
$string['maxviews'] =  'Max views';
$string['maxdiscussion'] =  'Max discussion';
// Added by Shiuli for eduplayer and edustream.
$string['maxeduplayer'] =  'Max Eduplayer';
$string['maxedustream'] =  'Max Edustream';

$string['nomatchrecordfound'] =  'No match record found!!';
$string['norecordavailable'] =  'No record found!!';
$string['uniquelogin'] =  'Unique login';
$string['addfav'] =  'Add favourite';
$string['myfavourite'] =  'My favourite';
$string['level'] =  'Level';
$string['incomplete'] =  'Incomplete';
$string['complete'] =  'Complete';
$string['unauthorisedaccess'] =  'You don\'t have permission to see this page';
$string['permissiondeniedcourse'] =  'You don\'t have permission to see this course';
$string['nofavavailable'] =  'No favourites available.';
$string['nocourseenrolluser'] =  'No course enrolled for this user.';

/*
 * Course level
*/
$string['course'] = 'Course overview';
$string['courseoverview'] = 'Course overview';
$string['courselevel'] = 'Course level';
$string['courselevelreport'] = 'Course level report';
$string['coursename'] = 'Course name';
$string['subcategory'] = 'Sub category';
$string['coursecompleted'] =  'Course completed';
$string['numcourse'] =  'Number of Course';
$string['numcoursecompleted'] =  'Number of course completed';
$string['numberofactivity'] =  'Number of activities';
$string['coursecategory'] =  'Course category';
$string['top5course'] =  'Top 5 Course';
$string['courseenrolled'] =  'Course enrolled';
$string['numoflearners'] =  'Number of learners';
$string['top5users'] =  'Top 5 User';
$string['course'] = 'Course';
$string['coursedate'] = 'Course date';
$string['enrolledcourses'] = 'Enrolled in Course';
$string['courses'] = 'Courses';
$string['locourses'] = 'List of courses';
$string['numofcourses'] = 'Number of Courses';
$string['newcoursecreatedin30'] = 'New courses created in last 30 Days';
$string['notpublishcourse'] = 'Non published courses';
$string['mostpopularcourse'] = ' Most popular course';
$string[''] = ' Courses completed for last $day days';
$string[''] = ' Courses completed for last 15 days';
$string[''] = ' Courses completed for last 30 days';
$string[''] = ' Courses completed for last 60 days';
$string[''] = ' Courses completed for last 90 days';
$string['coursebasedonuser'] = 'Courses based on number of user enrollment';
$string['completionnotenabled'] = 'Completion not enabled.';
$string['category'] = 'Category';
$string['subcatnotfound'] = 'Sub category not found!! please go ahead and select course';
$string['coursenotfound'] = 'Course not found';
$string['activityname'] = 'Activity name';
$string['score'] = 'Score';
$string['completedtime'] = 'Completed time';
$string['duetime'] = 'Due time';
$string['userenollcouses'] = 'User enroll courses';
$string['attemptdate'] = 'Attempt date';
$string['attempts'] = 'Attempt';
$string['numofcorrect'] = 'Num of Correct';
$string['numofworng'] = 'Num of worng';
$string['numofnotanswer'] = 'Num of not answer';
$string['timetaken'] = 'Timetaken';
$string['activitytype'] = 'Activity type';
$string['activitytype'] = 'Activity type';
$string['usercompleted'] = 'Course completed';
$string['userincompleted'] = 'Course incomplete';
$string['completion'] = 'Completion';
$string['timespent'] = 'Time Spent';
$string['ccr'] = 'Course completion report';
$string['ccc'] = 'Course completion chart';
$string['userlrf'] = 'User level report for';
$string['quizattemptdetails'] = 'Quiz attempt details';
$string['quizscorechart'] = 'Quiz score chart in details';
$string['coursecompletenotcomplete'] = 'Course complete - notcomplete';
$string['allactivitydetails'] = 'All activity details';
$string['coursetotal'] = 'Course total';
$string['coursetotal'] = 'Course total';
$string['courseanduser'] = 'Course and user details';
$string['courseecd'] = 'Course enroll & completion details';
$string['activitydetailsreport'] = 'Activity details report';
$string['activitylevelreport'] = 'Activity level report';
$string['activityselect'] = 'Activity report for ';
$string['activityattempt'] = 'User attempt for ';
$string['eventname'] = 'Event name ';
$string['eventdesc'] = 'Event desc';
$string['eventtime'] = 'Event date';
$string['categoryincourses'] = 'Category in courses';
$string['activitycompletenotcomplete'] = 'Activity complete - Incomplete';
$string['coursenotavalilable'] = 'Course not available';
$string['noactivityincourse'] = 'No activity available in this course';


/*
 * User level
 */

$string['user'] = 'User';
$string['User'] = 'User';
$string['users'] = 'Users';
$string['userlevel'] = 'User level';
$string['userlevelreport'] = 'User level report';
$string['top5users'] = 'Number of users';
$string['numofusersenrolled'] = 'Number of users enrolled';
$string['numofusers'] = 'Number of Users';
$string['notconfirm'] = 'Not confirmed';
$string['numoftutors'] = 'Number of tutors';
$string['activelearners'] = 'Active learners';
$string['suspendedlearner'] = 'suspended learner';
$string['numberofnewsignup'] = 'Number of new signup for last 30 days.';
$string['numberofactivetutors'] = ' Number of active tutors';
$string['mostactivetutor'] = ' Most active tutor';
$string['mostpopulartutor'] = ' Most popular tutor';
$string['userbasedoncourse'] = 'User based on number of course enrollment.';
$string['nouserincourse'] = 'No user in this course';
$string['reportfor'] = 'Report for user';
$string['assignedpeople'] = 'Assigned people';
$string['completedpeople'] = 'Completed people';
$string['completedper'] = 'Completed %';
$string['usersearch'] = 'User search';
$string['userprofile'] = 'User Profile';

$string['courselevelreportgen'] = 'text';
$string['usercompletion'] = 'User completion';

$string['activitycompletionreport'] = 'Activity completion report';
$string['view'] = 'view';
$string['useruncompleted'] = 'User Incomplete';
$string['typesearch'] = 'Type search name in below Textbox';

// added by shiuli for all comments.
$string['crslevel_cat_commen'] = '(Please type or double click to see the list of category)';
//$string['crslevel_course_commen'] = '(Double click to see the options of courses to select. You can get the course leve report by clicking on the "Generate Report" button)';
//$string['crslevel_activity_commen'] = '(To generate activity level report double click and select a particular activity and then click on generate report)';
//$string['crslevel_user_commen'] = '(Type or double click on the textbox to see the list of optinos to select. Once you select the user click on "Generate Report" button to get the details report with timespent.)';
$string['select_cat'] = 'Select Category';
$string['select_crs'] = 'Course';
$string['select_act'] = 'Activity';
$string['select_user'] = 'User';

$string['crs_generate'] = 'After selecting a course, click on "Generate Report" button to generate report at Course Level OR go to the next level';
$string['activity_generate'] = 'After selecting an activity, click on "Generate Report" button to generate report at Activity Level OR go to the next level';
$string['user_generate'] = 'After selecting an user, click on "Generate Report" button to generate detailed report with timespent';
$string['activity_cmp_rep'] = 'Type/Double click on the searchbox and then click on "Generate Report" button to generate the activity completion report with timespent for a particular user';