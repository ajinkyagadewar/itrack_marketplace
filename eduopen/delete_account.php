<?php
/**
 * Moodle's
 * This file is part of eduopen LMS Product
 *
 * @package   theme_eduopen
 * @copyright http://www.moodleofindia.com
 * @license   This file is copyrighted to Dhruv Infoline Pvt Ltd, http://www.moodleofindia.com
 */
require_once(dirname(__FILE__) . '/../config.php');
require_once($CFG->libdir . '/accesslib.php');

require_once($CFG->dirroot . '/my/lib.php');
require_once('lib.php');
redirect_if_major_upgrade_required();

require_login();

$strcourses = get_string('myhome'); //set the page title
$header = "$SITE->shortname: $strcourses";

// Start setting up the page
$params = array();
$PAGE->set_context(context_system::instance());
$PAGE->set_url('/eduopen/delete_account.php', $params);
$PAGE->set_pagelayout('eduopen_institution');
$PAGE->set_pagetype('my-index');
$PAGE->blocks->add_region('content');
$specheader = 'Delete Account Page';
$PAGE->set_title($specheader);
$PAGE->set_heading($header);
$PAGE->requires->jquery();
echo $OUTPUT->header();
if(is_siteadmin()) {
   exit('<div class="alert alert-danger">'.get_string('unauthorizeaccess', 'theme_eduopen').'</div>');
}
echo $OUTPUT->custom_block_region('content');

global $CFG, $DB, $USER, $PAGE, $OUTPUT;
$OUTPUT->body_attributes(array('bodyspecial'));

echo '<div class="row-fluid delRow">';
echo '<div class="col-md-12 col-md-12 col-xs-12 DltMsg"><p class="marBot">'
. get_string('deleted_msg', 'theme_eduopen') . 
        '</p></div>';
echo '<div class="col-md-12 col-md-12 col-xs-12 DltMsgbtn">'
 . '<div id="Dlbtn" class="btn" onclick="user_delete();">'.get_string('deleteaccount', 'theme_eduopen').'</div></div>';
echo '</div>';

$userObj = $USER;
$id = $USER->id;

echo $OUTPUT->footer();
?>

<script>
    function user_delete() {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function () {
            if (xhttp.readyState == 4 && xhttp.status == 200) {
                var json = JSON.parse(xhttp.responseText);
              //  console.log(json);
                if (json) {
                    window.location.assign("<?php echo $CFG->wwwroot; ?>");
                    //document.getElementById("Dlbtn").innerHTML = 'Deleted';
                }
            }
        };
        xhttp.open("GET", "UserCallback.php?callback=custom_user_delete&userid=<?php echo $id; ?>", true);
        xhttp.send();
    }
</script>