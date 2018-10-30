<?php
defined('MOODLE_INTERNAL') || die();

include_once($CFG->dirroot."/theme/itrackglobal/lib.php"); 
$standardlayout = (empty($PAGE->theme->settings->layout)) ? false : $PAGE->theme->settings->layout;
$haslogo = (!empty($PAGE->theme->settings->logo));
global $CFG, $USER, $DB;
//include_once("$CFG->dirroot/my/locallib.php");
if (right_to_left()) {
    $regionbsid = 'region-bs-main-and-post';
} else {
    $regionbsid = 'region-bs-main-and-pre';
}
echo $OUTPUT->doctype();

?>
<!DOCTYPE html>
<html <?php echo $OUTPUT->htmlattributes(); ?> lang="en">
<head>
<title><?php echo $OUTPUT->page_title(); ?></title>
<link rel="shortcut icon" href="<?php echo $OUTPUT->favicon(); ?>" />
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<!-- Tell the browser to be responsive to screen width -->
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="">
<meta name="author" content="">
<!-- Favicon icon -->

<title>iTrack</title>
<script src="http://code.jquery.com/jquery-1.11.3.min.js"></script>

<?php
echo '<link rel="icon" type="image/png" sizes="16x16" href="'.$CFG->wwwroot.'/theme/itrackglobal/assets/images/favicon.png">
    <!-- <script src="'.$CFG->wwwroot.'/theme/itrackglobal/js/custom.js"></script> -->
<!-- Bootstrap Core CSS -->
    <link href="'.$CFG->wwwroot.'/theme/itrackglobal/assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">

<!-- chartist CSS -->
    <link href="'.$CFG->wwwroot.'/theme/itrackglobal/assets/plugins/chartist-js/dist/chartist.min.css" rel="stylesheet">
    <link href="'.$CFG->wwwroot.'/theme/itrackglobal/assets/plugins/chartist-js/dist/chartist-init.css" rel="stylesheet">
    <link href="'.$CFG->wwwroot.'/theme/itrackglobal/assets/plugins/chartist-plugin-tooltip-master/dist/chartist-plugin-tooltip.css" rel="stylesheet">
    <link href="'.$CFG->wwwroot.'/theme/itrackglobal/assets/plugins/css-chart/css-chart.css" rel="stylesheet">
    <link href="'.$CFG->wwwroot.'/theme/itrackglobal/assets/plugins/horizontal-timeline/css/horizontal-timeline.css" rel="stylesheet">

<!--This page css - Morris CSS -->
    <link href="'.$CFG->wwwroot.'/theme/itrackglobal/assets/plugins/morrisjs/morris.css" rel="stylesheet">

<!-- Custom CSS -->
    <link href="'.$CFG->wwwroot.'/theme/itrackglobal/css/style.css" rel="stylesheet">

<!-- You can change the theme colors from here -->
    <link href="'.$CFG->wwwroot.'/theme/itrackglobal/css/colors/blue.css" id="theme" rel="stylesheet">
    ';
?>
<?php echo $OUTPUT->standard_head_html(); 
?>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

</head>
<body 
<?php echo $OUTPUT->body_attributes(); ?> class="fix-header fix-sidebar card-no-border">
<?php echo $OUTPUT->standard_top_of_body_html(); ?>





    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div class="preloader">
        <svg class="circular" viewBox="25 25 50 50">
            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" /> </svg>
    </div>
    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <div id="main-wrapper">
        <!-- ============================================================== -->
        <!-- Topbar header - style you can find in pages.scss -->
        <!-- ============================================================== -->
        <?php
        /*header start*/
        include_once("$CFG->dirroot/theme/itrackglobal/layout/includes/customheader.php");
        include_once("$CFG->dirroot/theme/itrackglobal/layout/includes/sidemenu.php");
        ?>
        
        
        <!-- ============================================================== -->
        <!-- Page wrapper  -->
        <!-- ============================================================== -->
        <div class="page-wrapper">
            <!-- ============================================================== -->
            <!-- Container fluid  -->
            <!-- ============================================================== -->
            <div class="container-fluid" style="margin-top:-23px">
                <!-- ============================================================== -->
                <!-- Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->
                <div class="row page-titles">
                    <div class="col-md-5 col-8 align-self-center">
                        <h3 class="text-themecolor m-b-0 m-t-0">Profile</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?php echo $CFG->wwwroot.'/my';?>">Home</a></li>
                            <li class="breadcrumb-item active">Course</li>
                        </ol>
                    </div>
                    <div class="col-md-7 col-4 align-self-center">
                        <div class="d-flex m-t-10 justify-content-end">
                            <div class="d-flex m-r-20 m-l-10 hidden-md-down">
                                <div class="chart-text m-r-10">
                                    <h6 class="m-b-0"><small>THIS MONTH</small></h6>
                                    <h4 class="m-t-0 text-info">$58,356</h4></div>
                                <div class="spark-chart">
                                    <div id="monthchart"></div>
                                </div>
                            </div>
                            <div class="d-flex m-r-20 m-l-10 hidden-md-down">
                                <div class="chart-text m-r-10">
                                    <h6 class="m-b-0"><small>LAST MONTH</small></h6>
                                    <h4 class="m-t-0 text-primary">$48,356</h4></div>
                                <div class="spark-chart">
                                    <div id="lastmonthchart"></div>
                                </div>
                            </div>
                            <div class="">
                                <button class="right-side-toggle waves-effect waves-light btn-success btn btn-circle btn-sm pull-right m-l-10"><i class="ti-settings text-white"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- End Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->
                <!-- ============================================================== -->
                <!-- Start Page Content -->
                <!-- ============================================================== -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <section class="cd-horizontal-timeline" style="margin: 1em;">
                                    <div class="timeline">
                                        <div class="events-wrapper">
                                            <div class="events">
                                                <ol>
                                                    
                                                <?php
                                                $all_sections = timeline($PAGE->course->id);
                                                //print_object($all_sections);
                                                $li = '<li><a id="0" onclick="add_customclass(this.id)" href="#0" data-date="01/01/2018" class="selected">Step 1</a></li>';
                                                    
                                                    foreach ($all_sections as $key => $value) {
                                                        //echo $key;
                                                        
                                                        if($key == 0){
                                                        }else{
                                                            $key = $key + 1;
                                                            $li .='<li><a onclick="add_customclass(this.id)" href="#0" data-date="01/'.$key.'/2018" id="'.$key.'">Step '.$key.'</a></li>';
                                                        }
                                                    }
                                                    echo $li;
                                                ?>    
                                                </ol>
                                                <span class="filling-line" aria-hidden="true"></span>
                                            </div>
                                            <!-- .events -->
                                        </div>
                                        <!-- .events-wrapper -->
                                        <ul class="cd-timeline-navigation">
                                            <li><a href="#0" class="prev inactive">Prev</a></li>
                                            <li><a href="#0" class="next">Next</a></li>
                                        </ul>
                                        <!-- .cd-timeline-navigation -->
                                    </div>
                                    <!-- .timeline -->
                                    <div class="events-content">
                                        <ol>
                                            <?php
                                                echo '<li id="lidesc_0" class="selected" data-date="01/01/2018">
                                                                <h2><img class="img-circle pull-left m-r-20 m-b-10" width="60" alt="user" src="'.$CFG->wwwroot.'/theme/itrackglobal/assets/images/course/introduction.png" alt="user"> Introduction<br/><small>January 15th, 2018</small></h2>
                                                                <hr class="m-t-40">
                                                                <p class="m-t-40">
                                                                    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Illum praesentium officia, fugit recusandae ipsa, quia velit nulla adipisci? Consequuntur aspernatur at, eaque hic repellendus sit dicta consequatur quae, ut harum ipsam molestias maxime non nisi reiciendis eligendi! Doloremque quia pariatur harum ea amet quibusdam quisquam, quae, temporibus dolores porro doloribus.
                                                                    <button class="btn btn-info btn-rounded btn-outline m-t-20">Read more</button>
                                                                </p>
                                                            </li>';
                                                foreach ($all_sections as $key => $value) {
                                                        
                                                    if($key == 0){
                                                    }else{
                                                        $key = $key + 1;
                                                        
                                                        $image = '';
                                                        if($key == 2){
                                                            $image = 'about';
                                                        }elseif ($key == 3) {
                                                            $image = 'tutorial';
                                                        }elseif ($key == 4) {
                                                            $image = 'collaboration';
                                                        }elseif ($key == 5) {
                                                            $image = 'feedback';
                                                        }
                                                        foreach ($value as $key1 => $value1) {
                                                            $section_name = $value1['sectionname'];
                                                        }
                                                        echo $li_desc ='<li id="lidesc_'.$key.'" data-date="01/'.$key.'/2018">
                                                                    <h2><img class="img-circle pull-left m-r-20 m-b-10" width="60" alt="user" src="'.$CFG->wwwroot.'/theme/itrackglobal/assets/images/course/'.$image.'.png" alt="'.$image.'"> '.$section_name.'<br/><small>January '.$key.', 2018</small></h2>
                                                                    <hr class="m-t-40">
                                                                    <p class="m-t-40">
                                                                        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Illum praesentium officia, fugit recusandae ipsa, quia velit nulla adipisci? Consequuntur aspernatur at, eaque hic repellendus sit dicta consequatur quae, ut harum ipsam molestias maxime non nisi reiciendis eligendi! Doloremque quia pariatur harum ea amet quibusdam quisquam, quae, temporibus dolores porro doloribus.
                                                                        <button class="btn btn-info btn-rounded btn-outline m-t-20">Read more</button>
                                                                    </p>';
                                                               
                                                        foreach ($value as $key2 => $value3) {
                                                            //echo $value1['sectionname'].'=>'.$value1['url'].'=>'.$value1['type'].'</br>';
                                                            if($value3['url']){
                                                                $arr_mod [$value3['sectionid']][] = array('type'=>$value3['type'],'url'=>$value3['url'],'name'=>$value3['modname']);  
                                                            }
                                                            
                                                        }
                                                        //print_object($arr_mod);
                                                        $url = '';
                                                        $color_arr = array('info','success','danger','primary','secondary', 'dark');
                                                        foreach ($arr_mod as $kk => $modurl) {
                                                             
                                                            foreach ($modurl as $k1 => $mods) {
                                                                //shuffle($color_arr);                                       
                                                                /*foreach ($color_arr as $ck => $cv) {
                                                                    $colorclass = $cv;
                                                                }*/
                                                                if($mods['type'] == 'choice'){
                                                                    $type = 'Selection';
                                                                }elseif ($mods['type'] == 'survey') {
                                                                    $type = 'Contemplate';
                                                                }elseif ($mods['type'] == 'forum') {
                                                                    $type = 'Discussion';
                                                                }elseif ($mods['type'] == 'page') {
                                                                    $type = 'Content';
                                                                }elseif ($mods['type'] == 'book') {
                                                                    $type = 'Learning';
                                                                }elseif ($mods['type'] == 'quiz') {
                                                                    $type = 'Assessment';
                                                                }elseif ($mods['type'] == 'feedback') {
                                                                    $type = 'Feedback';
                                                                }
                                                                $colorclass = $color_arr[$k1];
                                                                $url .= '<div class="d-flex flex-row " style="margin-top:5px">
                                                                            <div class="p-10 bg-'.$colorclass.'">
                                                                                <h3 class="text-white box m-b-0"><i class="ti-wallet"></i></h3></div>
                                                                            <div class="align-self-center m-l-20">
                                                                                <h3 class="m-b-0 text-'.$colorclass.'"><a style="color:#000" href="'.$mods['url'].'">'.$mods['name'].'</a></h3>
                                                                                <h5 class="text-muted m-b-0">'.$type.'</h5></div>
                                                                        </div>';
                                                            }
                                                        }
                                                        echo $url;
                                                        echo ' </li>';
                                                        $arr_mod = [];
                                                        $url = '';
                                                    }
                                                }
                                                //echo $li_desc;
                                            ?>
                                        </ol>
                                    </div>
                                    <!-- .events-content -->
                                </section>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- End PAge Content -->
                <!-- ============================================================== -->
                <!-- ============================================================== -->
                
                <?php 
                //print_object($PAGE->course->id);
                    // $all_sections = timeline($PAGE->course->id);
                    // print_object($all_sections);
                    echo $OUTPUT->main_content();  
                ?>
                <!-- ============================================================== -->
                
            </div>
            <!-- ============================================================== -->
            <!-- End Container fluid  -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- footer -->
            <!-- ============================================================== -->
            <footer class="footer"> © 2018 Transneuron Technologies Pvt Ltd </footer>
            <!-- ============================================================== -->
            <!-- End footer -->
            <!-- ============================================================== -->
        </div>
        <!-- ============================================================== -->
        <!-- End Page wrapper  -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- All Jquery -->
    <!-- ============================================================== -->
    <?php
    echo '
    <script src="'.$CFG->wwwroot.'/theme/itrackglobal/assets/plugins/bootstrap/js/popper.min.js"></script>
    <script src="'.$CFG->wwwroot.'/theme/itrackglobal/assets/plugins/bootstrap/js/bootstrap.min.js"></script>
    
    <!-- slimscrollbar scrollbar JavaScript -->
    <script src="'.$CFG->wwwroot.'/theme/itrackglobal/js/jquery.slimscroll.js"></script>
    <!--Wave Effects -->
    <script src="'.$CFG->wwwroot.'/theme/itrackglobal/js/waves.js"></script>
    <!--Menu sidebar -->
    <script src="'.$CFG->wwwroot.'/theme/itrackglobal/js/sidebarmenu.js"></script>
    <!--stickey kit -->
    <script src="'.$CFG->wwwroot.'/theme/itrackglobal/assets/plugins/sticky-kit-master/dist/sticky-kit.min.js"></script>
    <script src="'.$CFG->wwwroot.'/theme/itrackglobal/assets/plugins/sparkline/jquery.sparkline.min.js"></script>
    <script src="'.$CFG->wwwroot.'/theme/itrackglobal/assets/plugins/horizontal-timeline/js/horizontal-timeline.js"></script>
    <!--Custom JavaScript -->
    <script src="'.$CFG->wwwroot.'/theme/itrackglobal/js/custom.min.js"></script>
    <!-- ============================================================== -->
    <!-- This page plugins -->
    <!-- ============================================================== -->
    <!-- chartist chart -->
    <script src="'.$CFG->wwwroot.'/theme/itrackglobal/assets/plugins/chartist-js/dist/chartist.min.js"></script>
    <script src="'.$CFG->wwwroot.'/theme/itrackglobal/assets/plugins/chartist-plugin-tooltip-master/dist/chartist-plugin-tooltip.min.js"></script>
    <!--c3 JavaScript -->
    <script src="'.$CFG->wwwroot.'/theme/itrackglobal/assets/plugins/d3/d3.min.js"></script>
    <script src="'.$CFG->wwwroot.'/theme/itrackglobal/assets/plugins/c3-master/c3.min.js"></script>
    <!-- Chart JS -->
    <script src="'.$CFG->wwwroot.'/theme/itrackglobal/js/dashboard1.js"></script>
    <!-- ============================================================== -->
    <!-- Style switcher -->
    <!-- ============================================================== -->
    <script src="'.$CFG->wwwroot.'/theme/itrackglobal/assets/plugins/styleswitcher/jQuery.style.switcher.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-sweetalert/1.0.1/sweetalert.css">';
    ?>
    <script type="text/javascript">
        function add_customclass(id){
            $( "li.selected" ).removeClass( "selected enter-right" );
            $('#lidesc_'+id).addClass('selected enter-right');
        }
    </script>
</body>

</html>