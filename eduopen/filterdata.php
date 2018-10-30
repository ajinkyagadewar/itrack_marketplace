<?php
require_once(dirname(__FILE__) . '/../config.php');
//require_once($CFG->dirroot . '/my/lib.php');
require_once('lib.php');
global $CFG, $DB, $USER;
$context = context_system::instance();
$PAGE->set_context($context);

if (isset($_POST['countryname'])) {
    $country = $_POST['countryname'];

    $intdata = $DB->get_records('block_eduopen_master_inst', array('country' => $country));
    echo "<div class='col-md-12'>";
    foreach ($intdata as $institutiondata) {
        $intlogo = cinstitution_images($institutiondata);
        echo "<div class='col-md-2'>";
        echo "<a href='".$CFG->wwwroot."/eduopen/institution_details.php?institutionid=".$institutiondata->id."'>";
        echo "<image src=". $intlogo."  />";
        echo "</a>";
        echo "<p class='pdtop'>";
        echo "<a href='".$CFG->wwwroot."/eduopen/institution_details.php?institutionid=".$institutiondata->id."'>".
        $institutiondata->name."</a>"; echo "</p>";
        echo "</div>";
    }
    echo "</div>";
}
/* else if (isset($_POST['notification'])) {
$notificationid = $_POST['notification'];

echo '<div class="row">';
          echo  '<div class="col-md-12">';
			echo '<h1>"'.get_string('msg-not','theme_eduopen').'" </h1>';
           echo '</div>';
        echo '</div>';
		echo '<div class="table-responsive">';
			echo '<table class="table table-striped table-bordered">';
				echo '<thead>';
				echo	'<tr>';
					echo	"<th class='table-head'>";
                          echo "<h3>'.get_string('sino','theme_eduopen').' </h3>";
                        echo '</th>';
						echo "<th class='table-head'> '<h3>".get_string('coursename','theme_eduopen')."</h3>'</th>";
						echo "<th class='table-head'> '<h3>". get_string('msg','theme_eduopen')."</h3>'</th>";
						echo "<th class='table-head'> '<h3>". get_string('status','theme_eduopen')."</h3>'</th>";
					echo '</tr>';
				echo '</thead>';
				echo '<tbody>';
					echo '<tr>';
							$crs_name = $DB->get_records_sql("SELECT crs.id,crs.fullname,app.intro,app.id FROM {course} crs
							JOIN {apply} app ON crs.id = app.course");
							foreach($crs_name as $course_name){
							$apply= $DB->get_records('apply_submit',array('apply_id'=>$course_name->id));
							foreach($apply as $apply_sub){
							if($apply_sub->user_id == $notificationid){
						echo '<td>"'. $course_name->id.'"</td>';
						echo '<td>"'. $course_name->fullname.'"</td>';
						echo "<td>";
								$app_name = $DB->get_records('apply',array('id'=>$course_name->id));
								foreach($app_name as $apply_name){
								echo $apply_name->name;
							 }
						echo"</td>";
						echo "<td>";
								if($apply_sub->acked == 0){
							echo "<button class='btn btn-warning btn_wdt'>". get_string('none','theme_eduopen')."</button>";
							} elseif($apply_sub->acked == 1){
							echo "<button class='btn btn-success btn_wdt'>".get_string('accept','theme_eduopen')."</button>";
							 } else {
							echo "<button class='btn btn-danger btn_wdt'>". get_string('reject','theme_eduopen')."</button>";
							 }
                           echo "<br>";
						echo "</td>";
					echo "</tr>";
					 }}}
				echo "</tbody>";
			echo "</table>";
		echo "</div>";
} */
