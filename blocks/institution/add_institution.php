<?php
// This file is part of MoodleofIndia - http://www.moodleofindia.com/

require_once(dirname(__FILE__) . '../../../config.php');
require_once(dirname(__FILE__) . '../../../my/lib.php');
require_once('lib.php');

$editid = optional_param('editid', '', PARAM_INT);
$blockcontext = required_param('context',PARAM_INT);
error_reporting(0);
//require_once($CFG->dirroot . '/blocks/institution/add_institution_form.php');
redirect_if_major_upgrade_required();

// TODO Add sesskey check to edit
$edit   = optional_param('edit', null, PARAM_BOOL);    // Turn editing on and off

require_login();

$strmymoodle = get_string('add', 'block_institution');

if (isguestuser()) {  // Force them to see system default, no editing allowed
    // If guests are not allowed my moodle, send them to front page.
    if (empty($CFG->allowguestmymoodle)) {
        redirect(new moodle_url('/', array('redirect' => 0)));
    }

    $userid = NULL; 
    $USER->editing = $edit = 0;  // Just in case
    $context = context_system::instance();
    $PAGE->set_blocks_editing_capability('moodle/blocks/institution:configsyspages');  // unlikely :)
    $header = "$SITE->shortname: $strmymoodle (GUEST)";

} else {        // We are trying to view or edit our own My Moodle page
    $userid = $USER->id;  // Owner of the page
    $context = context_user::instance($USER->id);
    $PAGE->set_blocks_editing_capability('moodle/my:manageblocks');
    $header = "$SITE->shortname: $strmymoodle";
}

// Get the My Moodle page info.  Should always return something unless the database is broken.
if (!$currentpage = my_get_page($userid, MY_PAGE_PRIVATE)) {
    print_error('mymoodlesetup');
}

if (!$currentpage->userid) {
    $context = context_system::instance();  // So we even see non-sticky blocks
}

// Start setting up the page
$params = array();

$PAGE->set_context($context);
$PAGE->set_url('/blocks/institution/add_institution.php', $params);
$PAGE->set_pagelayout('admin');
$PAGE->set_pagetype('add_institution');
$PAGE->blocks->add_region('content');
$PAGE->set_subpage($currentpage->id);
$PAGE->set_title($header);
$PAGE->set_heading($header);

if (!isguestuser()) {   // Skip default home page for guests
    if (get_home_page() != HOMEPAGE_MY) {
        if (optional_param('setdefaulthome', false, PARAM_BOOL)) {
            set_user_preference('user_home_page_preference', HOMEPAGE_MY);
        } else if (!empty($CFG->defaulthomepage) && $CFG->defaulthomepage == HOMEPAGE_USER) {
            $PAGE->settingsnav->get('usercurrentsettings')->add(get_string('makethismyhome'),
            new moodle_url('/my/', array('setdefaulthome' => true)), navigation_node::TYPE_SETTING);
        }
    }
}

// Toggle the editing state and switches
if ($PAGE->user_allowed_editing()) {
    if ($edit !== null) {             // Editing state was specified
        $USER->editing = $edit;       // Change editing state
        if (!$currentpage->userid && $edit) {
            // If we are viewing a system page as ordinary user, and the user turns
            // editing on, copy the system pages as new user pages, and get the
            // new page record
            if (!$currentpage = my_copy_page($USER->id, MY_PAGE_PRIVATE)) {
                print_error('mymoodlesetup');
            }
            $context = context_user::instance($USER->id);
            $PAGE->set_context($context);
            $PAGE->set_subpage($currentpage->id);
        }
    } else {                          // Editing state is in session
        if ($currentpage->userid) {   // It's a page we can edit, so load from session
            if (!empty($USER->editing)) {
                $edit = 1;
            } else {
                $edit = 0;
            }
        } else {                      // It's a system page and they are not allowed to edit system pages
            $USER->editing = $edit = 0;          // Disable editing completely, just to be safe
        }
    }

    // Add button for editing page
    $params = array('edit' => !$edit);

    if (!$currentpage->userid) {
        // viewing a system page -- let the user customise it
        $editstring = get_string('updatemymoodleon');
        $params['edit'] = 1;
    } else if (empty($edit)) {
        $editstring = get_string('updatemymoodleon');
    } else {
        $editstring = get_string('updatemymoodleoff');
    }

    $url = new moodle_url("$CFG->wwwroot/my/index.php", $params);
    $button = $OUTPUT->single_button($url, $editstring);
    $PAGE->set_button($button);

} else {
    $USER->editing = $edit = 0;
}

// HACK WARNING!  This loads up all this page's blocks in the system context
if ($currentpage->userid == 0) {
    $CFG->blockmanagerclass = 'my_syspage_block_manager';
}

//addingbreadcrumb
if(!empty($editid)) {
$PAGE->navbar->add(get_string('view', 'block_institution'), new moodle_url('/blocks/institution/view_institution.php?context='.$blockcontext));
$PAGE->navbar->add(get_string('edit', 'block_institution'), new moodle_url('/blocks/institution/add_institution.php?editid='.$editid.'&context='.$blockcontext));
} else {
$PAGE->navbar->add(get_string('add', 'block_institution'), new moodle_url('/blocks/institution/add_institution.php?context='.$blockcontext));
}
global $CFG;
$PAGE->requires->css('/css/styles.css');
echo $OUTPUT->header();
?>

<?php
require_once('form/add_institution_form.php');

//added by nihar
$cururi = "$CFG->wwwroot/blocks/institution/add_institution.php?context=$blockcontext";

//Instantiate institution
$mform = new add_institution($cururi);

//Form processing and displaying is done here
if ($mform->is_cancelled()) {
    //Handle form cancel operation, if cancel button is present on form
} else if ($formdata = $mform->get_data()) {
    if ($formdata->updateid == '0') {
        $formsave = add_institution($formdata);
        //added by nihar
        file_save_draft_area_files($formdata->banner, $blockcontext, 'block_institution', 'content',
                   $formdata->banner, array('subdirs' => 0, 'maxbytes' => $maxbytes, 'maxfiles' => 1));
        file_save_draft_area_files($formdata->logo, $blockcontext, 'block_institution', 'content',
                   $formdata->logo, array('subdirs' => 0, 'maxbytes' => $maxbytes, 'maxfiles' => 1));
        file_save_draft_area_files($formdata->logo1, $blockcontext, 'block_institution', 'content',
                   $formdata->logo1, array('subdirs' => 0, 'maxbytes' => $maxbytes, 'maxfiles' => 1));
    } else {
        $formsavedata = update_institution($formdata);
        //added by nihar
        file_save_draft_area_files($formdata->banner, $blockcontext, 'block_institution', 'content',
                   $formdata->banner, array('subdirs' => 0, 'maxbytes' => $maxbytes, 'maxfiles' => 1));
        file_save_draft_area_files($formdata->logo, $blockcontext, 'block_institution', 'content',
                   $formdata->logo, array('subdirs' => 0, 'maxbytes' => $maxbytes, 'maxfiles' => 1));
        file_save_draft_area_files($formdata->logo1, $blockcontext, 'block_institution', 'content',
                   $formdata->logo1, array('subdirs' => 0, 'maxbytes' => $maxbytes, 'maxfiles' => 1));
    }
    if ($formsave) {
        echo '<h3 class = "upinstitution">';
        echo get_string('insertinstitution', 'block_institution');
        echo '</h3>';
        echo '<a href ="view_institution.php?context='.$blockcontext.'"><button type ="button" class ="btn btn-warning ">'.
        get_string('view', 'block_institution').'</button></a> &nbsp; &nbsp; &nbsp; &nbsp;';
        echo '<a href ="add_institution.php?context='.$blockcontext.'"><button type = "button" class ="btn btn-warning ">'.
        get_string('add', 'block_institution').'</button></a>';
    }
    if ($formsavedata) {
        echo '<h3 class = "upinstitution">';
        echo get_string('updateinstitution', 'block_institution');
        echo '</h3>';
        echo '<a href ="view_institution.php?context='.$blockcontext.'"><button type ="button" class="btn btn-warning ">'.
        get_string('view', 'block_institution').'</button></a> &nbsp; &nbsp; &nbsp; &nbsp;';
        echo '<a href = "add_institution.php?context='.$blockcontext.'"><button type ="button" class ="btn btn-warning ">'.
        get_string('add', 'block_institution').'</button></a>';
    }
} else {
    if ($editid !== '' ) {
        $data = $DB->get_record('block_eduopen_master_inst', array('id' => $editid));
        $draftitemid = file_get_submitted_draft_itemid('banner');
        file_prepare_draft_area($draftitemid, $blockcontext, 'block_institution', 'content',
                   $data->banner,  array('subdirs' => 0, 'maxbytes' => $maxbytes, 'maxfiles' => 1));
        $data->banner = $draftitemid;
        $draftitemid1 = file_get_submitted_draft_itemid('logo');
        file_prepare_draft_area($draftitemid1, $blockcontext, 'block_institution', 'content',
                   $data->logo,  array('subdirs' => 0, 'maxbytes' => $maxbytes, 'maxfiles' => 1));
        $data->logo = $draftitemid1;
        $draftitemid2 = file_get_submitted_draft_itemid('logo1');
        file_prepare_draft_area($draftitemid2, $blockcontext, 'block_institution', 'content',
                   $data->logo1,  array('subdirs' => 0, 'maxbytes' => $maxbytes, 'maxfiles' => 1));
        $data->logo1 = $draftitemid2;
        $mform->set_data($data);
    }
    $mform->display();
}

echo $OUTPUT->footer();
?>
<script>
    $(".breadcrumbs-alt span").text("Institution") 
</script>
