<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function get_layout(){
    return 'admin';
}

//added by shiuli to convert time into h,m,s.
function timestat_seconds_to_stringtime($seconds) {
    $conmin = 60;
    $conhour = $conmin * 60;
    $conday = $conhour * 24;

    $tempday = (int)((int)$seconds / (int)$conday);
    $seconds = $seconds - $tempday * $conday;
    $temphour = (int)((int)$seconds / (int)$conhour);
    $seconds = $seconds - $temphour * $conhour;
    $tempmin = (int)((int)$seconds / (int)$conmin);
    $seconds = $seconds - $tempmin * $conmin;

    $str = '';
    if ($tempday != 0) {
        $str = $str.$tempday.get_string('days', 'block_timestat');
    }
    if ($temphour != 0) {
        $str = $str.$temphour.get_string('hours', 'block_timestat');
    }
    if ($tempmin != 0) {
        $str = $str.$tempmin.get_string('minuts', 'block_timestat');
    }
    $str = $str.$seconds.get_string('seconds', 'block_timestat');
    return $str;
}


// get all roles except student.
function all_visible_roles() {
    global $DB, $USER;
    $contentrole = $DB->get_record('role', array('shortname' => 'contenteditor'));
    $tutorrole = $DB->get_record('role', array('shortname' => 'teacher'));
    $student = user_has_role_assignment($USER->id, 5);
    $editingTeacher = user_has_role_assignment($USER->id, 3);
    $contentEditor = user_has_role_assignment($USER->id, $contentrole->id);
    $tutor = user_has_role_assignment($USER->id, $tutorrole->id);
    if ($editingTeacher || $contentEditor || $tutor) {
        $visibleUsers = 1;
    } else {
        $visibleUsers = 0;
    }
    return $visibleUsers;
}

