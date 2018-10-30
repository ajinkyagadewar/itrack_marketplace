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

require_once(__DIR__ . '/../../config.php');
require_once($CFG->libdir . '/adminlib.php');
require_once($CFG->libdir.'/csvlib.class.php');
require_once($CFG->dirroot . '/local/bulkvideo/locallib.php');
require_once($CFG->dirroot . '/course/modlib.php');

$pageparams = array();

admin_externalpage_setup('local_bulkvideo', '', $pageparams);

$mform = new local_bulkvideo_form(new moodle_url('/local/bulkvideo/'));
if ($mform->is_cancelled()) {
    $url = new moodle_url('/admin/index.php');
    redirect($url);
} else if ($fromform = $mform->get_data()) {

    $iid = csv_import_reader::get_new_iid('uploaduser');

    $cir = new csv_import_reader($iid, 'uploaduser');

    $content = $mform->get_file_content('userfile');

    $readcount = $cir->load_csv_content($content, 'UTF-8', 'comma');

    $csvloaderror = $cir->get_error();

    unset($content);

    // Init csv import helper.

    $cir->init();

    $linenum = 1; // Column header is first line.
    $modcreated = false;

    while ($linenum <= $readcount and $fields = $cir->next()) {
        $linenum++;
        $videourl = $fields[0];
        $videoiframeurl = preg_replace("/\s*[a-zA-Z\/\/:\.]*youtube.com\/watch\?v=([a-zA-Z0-9\-_]+)([a-zA-Z0-9\/\*\-\_\?\&\;\%\=\.]*)/i",
            "<iframe width=\"420\" height=\"315\" src=\"//www.youtube.com/embed/$1\" frameborder=\"0\" allowfullscreen></iframe>",
            $videourl);
        $courseid = $fields[1];
        $sectionid = $fields[2];
        $modname = $fields[3];
        $display = $fields[4];
        if ($display === "open") {
            $display = 5;
        } else { // Popup.
            $display = 6;
        }

        $record = new stdclass();
        $record->name = $modname;
        $record->content = $videoiframeurl;
        $record->contentformat = 1; // HTML.
        $record->display = 5; // Display in open change to 6 to display in pop-up.
        $record->visible = 1;
        $record->printheading = 1;
        $record->printintro = 1;
        $record->course = $courseid;
        $record->instance = 0;
        $record->section = $sectionid;
        $record->module = $DB->get_field('modules', 'id', array('name' => 'eduvideo'));
        $record->modulename = "eduvideo";
        $record->add = "eduvideo";
        $record->update = 0;
        $record->return = 1;

        if (!$DB->record_exists('local_bulkvideo_log', array('courseid' => $courseid,
            'sectionid' => $sectionid, 'modname' => $modname))) {
            $course = $DB->get_record('course', array('id' => $courseid), "*", MUST_EXIST);
            $data = add_moduleinfo($record, $course);
            if ($data->id) {
                $timenow = time();
                $logrecord = new stdclass();
                $logrecord->videourl = $videourl;
                $logrecord->courseid = $courseid;
                $logrecord->sectionid = $sectionid;
                $logrecord->modname = $modname;
                $logrecord->modcreated = 1;
                $logrecord->timecreated = $timenow;
                $logrecord->timemodified = $timenow;
                $DB->insert_record('local_bulkvideo_log', $logrecord);
                $modcreated = true;
            }
        }
    }
    if ($modcreated) {
        redirect(new moodle_url('/local/bulkvideo/'), get_string('modcreatedsuccess', 'local_bulkvideo'));
    }
}

echo $OUTPUT->header();

$mform->display();

echo $OUTPUT->footer();
