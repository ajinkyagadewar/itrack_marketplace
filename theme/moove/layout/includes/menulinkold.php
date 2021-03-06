<?php

//For HOME
$home = $CFG->wwwroot . '/my';

//For My Courses

$mycourses = $CFG->wwwroot . '/course';

$assignedcourses = $CFG->wwwroot . '/my/listcourse.php?section=assigned';

$mylearningplan = $CFG->wwwroot . '/my/listcourse.php?section=personallearningplan';

$selfenrolled = $CFG->wwwroot . '/my/listcourse.php?section=selfenrol';

$recomendedforu = $CFG->wwwroot . '/my/listcourse.php?section=personallearningplan';

$availablecourses = $CFG->wwwroot . '/my/listcourse.php?section=avail';

//For My Reports

$myreport = $CFG->wwwroot . '/my/mycsrpt.php?section=csrpt';

$coursereport = $CFG->wwwroot . '/my/mycsrpt.php?section=csrpt';

$activityreport = $CFG->wwwroot . '/my/mycsrpt.php?section=assignrpt';

$summaryreport = $CFG->wwwroot . '/my/courses_report.php';

$mycertificates = $CFG->wwwroot . '/my/certificate.php';

//For Social Learning

$forums = $CFG->wwwroot . '/admin/settings.php?section=modsettingforum';

$newsandnotification = $CFG->wwwroot . '/message/index.php';

$survey = $CFG->wwwroot . '/course/modedit.php?add=survey&type=&course='.$COURSE->id.'&section=0&return=0&sr=0';

// $faq = $CFG->wwwroot . '/course';

$blog = $CFG->wwwroot . '/blog/index.php';

$wiki = $CFG->wwwroot . '/course/modedit.php?add=wiki&type=&course='.$COURSE->id.'&section=0&return=0&sr=0';

// $announcement = $CFG->wwwroot . '/course';

//For Notification

$message = $CFG->wwwroot . '/message/index.php';

$event = $CFG->wwwroot . '/calendar/view.php?view=month';

//For My Profile

$myprofile = $CFG->wwwroot . '/user/profile.php';

$changepassword = $CFG->wwwroot . '/login/change_password.php';

$profileprivacy = $CFG->wwwroot . '/local/userfieldprivacy/index.php?type=user';

//For Client administration

$addaclient = $CFG->wwwroot . '/Multitenant/web/create_client.php';

$editclient = $CFG->wwwroot . '/Multitenant/web/client_settings.php';

$manageclient = $CFG->wwwroot . '/Multitenant/web/client_activeinactive.php';

$managemodule = $CFG->wwwroot . '/Multitenant/web/client_plugins.php';

$managemenu = $CFG->wwwroot . '/course';

$managelobalcatalog = $CFG->wwwroot . '/Multitenant/web/live_login_reports.php';

$loggeduserc = $CFG->wwwroot . '/Multitenant/web/live_login_reports.php';

//For System administration

$administration = $CFG->wwwroot . '/admin';

$loggeduser = $CFG->wwwroot . '/report/log/index.php?id=0';

$managesystempolicies = $CFG->wwwroot . '/admin/settings.php?section=sitepolicies';

$managelocationsettings = $CFG->wwwroot . '/admin/settings.php?section=locationsettings';

$languagecustomisation = $CFG->wwwroot . '/admin/tool/customlang/index.php';

// $manageloginpage = $CFG->wwwroot . '/course';

// $manageuserinterface = $CFG->wwwroot . '/course';

$managetheme = $CFG->wwwroot . '/admin/settings.php?section=theme_defaultlms_general';

$manageuserprofilefields = $CFG->wwwroot . '/user/profile/index.php';

$manageuserfieldprivacy = $CFG->wwwroot . '/local/userfieldprivacy/index.php?type=user';

$managecalendar = $CFG->wwwroot . '/calendar/view.php';

$managedefaultmessageoutputs = $CFG->wwwroot . '/message/index.php?id=2';

$managecustomnotifications = $CFG->wwwroot . '/message/index.php?id=2';

$manageauthentication = $CFG->wwwroot . '/admin/settings.php?section=manageauths';

// $managecoursecompletion = $CFG->wwwroot . '/course';

$managecourseformats = $CFG->wwwroot . '/admin/settings.php?section=manageformats';

$manageenrol = $CFG->wwwroot . '/admin/settings.php?section=manageenrols';

$managewebservices = $CFG->wwwroot . '/admin/settings.php?section=webservicetokens';

$managesupportcontact = $CFG->wwwroot . '/admin/settings.php?section=supportcontact';

$managemaintenancemode = $CFG->wwwroot . '/admin/settings.php?section=maintenancemode';

$managesystemcleanup = $CFG->wwwroot . '/admin/settings.php?section=cleanup';

$managecaches = $CFG->wwwroot . '/admin/purgecaches.php';

$managescheduledtasks = $CFG->wwwroot . '/admin/tool/task/scheduledtasks.php';

// $notificationdeliverystatus = $CFG->wwwroot . '/course';

//For User management

$addauser = $CFG->wwwroot . '/user/editadvanced.php?id=-1';

$manageuser = $CFG->wwwroot . '/local/usermanagement/index.php';

$manageenrolmentu = $CFG->wwwroot . '/admin/settings.php?section=manageenrols';

$managecohort = $CFG->wwwroot . '/cohort/index.php';

$uploaduser = $CFG->wwwroot . '/admin/tool/uploaduser/index.php';

$changeuserpassword = $CFG->wwwroot . '/local/usermanagement/index.php';

//For Catalogue management

$managecategory = $CFG->wwwroot . '/course/management.php';

$managecourse = $CFG->wwwroot . '/course/management.php';

$manageenrolmentmethod = $CFG->wwwroot . '/course';

$manageenrolment = $CFG->wwwroot . '/course';

//For Courses management

$addacategory = $CFG->wwwroot . '/course/editcategory.php?parent=0';

$addacourse = $CFG->wwwroot . '/course/edit.php?category=1&returnto=catmanage';

$addactivity = $CFG->wwwroot . '/course';

$manageactivity = $CFG->wwwroot . '/admin/modules.php';

// $managerules = $CFG->wwwroot . '/course';

$managegrades = $CFG->wwwroot . '/admin/settings.php?section=gradeitemsettings';

$managetags = $CFG->wwwroot . '/tag/manage.php';

$managebadges = $CFG->wwwroot . '/badges/index.php?type=1';

$manageenrolmentmethodc = $CFG->wwwroot . '/enrol/instances.php?id='.$COURSE->id;

$manageuserenrolment = $CFG->wwwroot . '/enrol/users.php?id='.$COURSE->id;


//For Assessment management

$addcategory = $CFG->wwwroot . '/question/category.php?courseid='.$COURSE->id;

// $addlevel = $CFG->wwwroot . '/course';

$addquestion = $CFG->wwwroot . '/question/edit.php?courseid='.$COURSE->id;

$uploadquestion = $CFG->wwwroot . '/question/import.php?courseid='.$COURSE->id;

$managequesbank = $CFG->wwwroot . '/question/edit.php?courseid='.$COURSE->id;

$createassessment = $CFG->wwwroot . '/course/modedit.php?add=quiz&type=&course='.$COURSE->id.'&section=0&return=0&sr=0';

//For Certificates management

$addcrtfct = $CFG->wwwroot . '/course/modedit.php?add=certificate&type=&course='.$COURSE->id.'&section=2&return=0&sr=0';

// $managecrtfct = $CFG->wwwroot . '/course';

//For Reports management

$createrpt = $CFG->wwwroot . '/course';

$managerpt = $CFG->wwwroot . '/course';

$profilefiledmap = $CFG->wwwroot.'/local/profile_report_mapping/';
