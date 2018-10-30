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
    <link href="'.$CFG->wwwroot.'/theme/itrackglobal/assets/plugins/calendar/dist/fullcalendar.css" rel="stylesheet" />
<!-- Bootstrap Core CSS -->
    <link href="'.$CFG->wwwroot.'/theme/itrackglobal/assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">

<!-- chartist CSS -->
    <link href="'.$CFG->wwwroot.'/theme/itrackglobal/assets/plugins/chartist-js/dist/chartist.min.css" rel="stylesheet">
    <link href="'.$CFG->wwwroot.'/theme/itrackglobal/assets/plugins/chartist-js/dist/chartist-init.css" rel="stylesheet">
    <link href="'.$CFG->wwwroot.'/theme/itrackglobal/assets/plugins/chartist-plugin-tooltip-master/dist/chartist-plugin-tooltip.css" rel="stylesheet">
    <link href="'.$CFG->wwwroot.'/theme/itrackglobal/assets/plugins/css-chart/css-chart.css" rel="stylesheet">

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

<?php echo "<div style='display: none;'>".$OUTPUT->main_content()."</div>";  ?>



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
                            <li class="breadcrumb-item active">Calendar</li>
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
                 <!-- ============================================================== -->
                <!-- ============================================================== -->
                <!-- Start Page Content -->
                <!-- ============================================================== -->
                <div class="row">
                    <div class="col-12">
                        <div class="card m-b-0">
                            <!-- .chat-row -->
                            <div class="chat-main-box">
                                <!-- .chat-left-panel -->
                                <div class="chat-left-aside">
                                    <div class="open-panel"><i class="ti-angle-right"></i></div>
                                    <div class="chat-left-inner">
                                        <div class="form-material">
                                            <input class="form-control p-20" type="text" placeholder="Search Contact">
                                        </div>
                                        <ul class="chatonline style-none ">
                                            <li>
                                                <a href="javascript:void(0)">
                                                  <?php echo '<img src="'.$CFG->wwwroot.'/theme/itrackglobal/assets/images/users/1.jpg" alt="user-img" class="img-circle">';?>
                                                  <span>Account Manager <small class="text-success">online</small></span></a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0)" class="active">
                                                  <?php echo '<img src="'.$CFG->wwwroot.'/theme/itrackglobal/assets/images/users/2.jpg" alt="user-img" class="img-circle">';?> <span>Admin User <small class="text-warning">Away</small></span></a>
                                            </li>
                                            
                                            
                                            <li class="p-20"></li>
                                        </ul>
                                    </div>
                                </div>
                                <!-- .chat-left-panel -->
                                <!-- .chat-right-panel -->
                                <div class="chat-right-aside">
                                    <div class="chat-main-header">
                                        <div class="p-20 b-b">
                                            <h3 class="box-title">Chat Message</h3>
                                        </div>
                                    </div>
                                    <div class="chat-rbox">
                                      <?php 
                                        echo '<ul class="chat-list p-20">
                                            <!--chat Row -->
                                            <li>
                                                <div class="chat-img"><img src="'.$CFG->wwwroot.'/theme/itrackglobal/assets/images/users/2.jpg" alt="user" /></div>
                                                <div class="chat-content">
                                                    <h5>Admin User</h5>
                                                    <div class="box bg-light-info">Lorem Ipsum is simply dummy text of the printing & type setting industry.</div>
                                                </div>
                                                <div class="chat-time">10:56 am</div>
                                            </li>
                                            <!--chat Row -->
                                            <li>
                                                <div class="chat-img"><img src="'.$CFG->wwwroot.'/theme/itrackglobal/assets/images/users/2.jpg" alt="user" /></div>
                                                <div class="chat-content">
                                                    <h5>Admin User</h5>
                                                    <div class="box bg-light-success">It’s Great opportunity to work.</div>
                                                </div>
                                                <div class="chat-time">10:57 am</div>
                                            </li>
                                            <!--chat Row -->
                                            <li class="reverse">
                                                <div class="chat-time">10:57 am</div>
                                                <div class="chat-content">
                                                    <h5>'.$USER->firstname.'</h5>
                                                    <div class="box bg-light-inverse">It’s Great opportunity to work.</div>
                                                </div>
                                                <div class="chat-img"><img src="'.$CFG->wwwroot.'/theme/itrackglobal/assets/images/users/1.jpg" alt="user" /></div>
                                            </li>
                                            <!--chat Row -->
                                            <li class="reverse">
                                                <div class="chat-time">10:57 am</div>
                                                <div class="chat-content">
                                                    <h5>'.$USER->firstname.'</h5>
                                                    <div class="box bg-light-inverse">It’s Great opportunity to work.</div>
                                                </div>
                                                <div class="chat-img"><img src="'.$CFG->wwwroot.'/theme/itrackglobal/assets/images/users/1.jpg" alt="user" /></div>
                                            </li>
                                           
                                        </ul>';?>
                                    </div>
                                    <div class="card-body b-t">
                                        <div class="row">
                                            <div class="col-8">
                                                <textarea placeholder="Type your message here" class="form-control b-0"></textarea>
                                            </div>
                                            <div class="col-4 text-right">
                                                <button type="button" class="btn btn-info btn-circle btn-lg"><i class="fa fa-paper-plane-o"></i> </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- .chat-right-panel -->
                            </div>
                            <!-- /.chat-row -->
                        </div>
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- End PAge Content -->
                <!-- ============================================================== -->
                <!-- ============================================================== -->
                <!-- ============================================================== -->
                <!-- Right sidebar -->
                <!-- ============================================================== -->
                <!-- .right-sidebar -->
                <div class="right-sidebar">
                    <div class="slimscrollright">
                        <div class="rpanel-title"> Service Panel <span><i class="ti-close right-side-toggle"></i></span> </div>
                        <div class="r-panel-body">
                            <ul id="themecolors" class="m-t-20">
                                <li><b>With Light sidebar</b></li>
                                <li><a href="javascript:void(0)" data-theme="default" class="default-theme">1</a></li>
                                <li><a href="javascript:void(0)" data-theme="green" class="green-theme">2</a></li>
                                <li><a href="javascript:void(0)" data-theme="red" class="red-theme">3</a></li>
                                <li><a href="javascript:void(0)" data-theme="blue" class="blue-theme working">4</a></li>
                                <li><a href="javascript:void(0)" data-theme="purple" class="purple-theme">5</a></li>
                                <li><a href="javascript:void(0)" data-theme="megna" class="megna-theme">6</a></li>
                                <li class="d-block m-t-30"><b>With Dark sidebar</b></li>
                                <li><a href="javascript:void(0)" data-theme="default-dark" class="default-dark-theme">7</a></li>
                                <li><a href="javascript:void(0)" data-theme="green-dark" class="green-dark-theme">8</a></li>
                                <li><a href="javascript:void(0)" data-theme="red-dark" class="red-dark-theme">9</a></li>
                                <li><a href="javascript:void(0)" data-theme="blue-dark" class="blue-dark-theme">10</a></li>
                                <li><a href="javascript:void(0)" data-theme="purple-dark" class="purple-dark-theme">11</a></li>
                                <li><a href="javascript:void(0)" data-theme="megna-dark" class="megna-dark-theme ">12</a></li>
                            </ul>
                            <?php echo '<ul class="m-t-20 chatonline">
                                <li><b>Chat option</b></li>
                                <li>
                                    <a href="javascript:void(0)"><img src="'.$CFG->wwwroot.'/theme/itrackglobal/assets/images/users/1.jpg" alt="user-img" class="img-circle"> <span>Varun Dhavan <small class="text-success">online</small></span></a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)"><img src="'.$CFG->wwwroot.'/theme/itrackglobal/assets/images/users/2.jpg" alt="user-img" class="img-circle"> <span>Genelia Deshmukh <small class="text-warning">Away</small></span></a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)"><img src="'.$CFG->wwwroot.'/theme/itrackglobal/assets/images/users/3.jpg" alt="user-img" class="img-circle"> <span>Ritesh Deshmukh <small class="text-danger">Busy</small></span></a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)"><img src="'.$CFG->wwwroot.'/theme/itrackglobal/assets/images/users/4.jpg" alt="user-img" class="img-circle"> <span>Arijit Sinh <small class="text-muted">Offline</small></span></a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)"><img src="'.$CFG->wwwroot.'/theme/itrackglobal/assets/images/users/5.jpg" alt="user-img" class="img-circle"> <span>Govinda Star <small class="text-success">online</small></span></a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)"><img src="'.$CFG->wwwroot.'/theme/itrackglobal/assets/images/users/6.jpg" alt="user-img" class="img-circle"> <span>John Abraham<small class="text-success">online</small></span></a>
                                </li>
                               
                            </ul>';?>
                        </div>
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- End Right sidebar -->
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

    <!-- Calendar JavaScript -->
    <script src="'.$CFG->wwwroot.'/theme/itrackglobal/assets/plugins/calendar/jquery-ui.min.js"></script>
    <script src="'.$CFG->wwwroot.'/theme/itrackglobal/assets/plugins/moment/moment.js"></script>
    <script src="'.$CFG->wwwroot.'/theme/itrackglobal/assets/plugins/calendar/dist/fullcalendar.min.js"></script>
    <script src="'.$CFG->wwwroot.'/theme/itrackglobal/assets/plugins/calendar/dist/cal-init.js"></script>

    <!-- ============================================================== -->
    <!-- Style switcher -->
    <!-- ============================================================== -->
    <script src="'.$CFG->wwwroot.'/theme/itrackglobal/assets/plugins/styleswitcher/jQuery.style.switcher.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-sweetalert/1.0.1/sweetalert.css">';
    ?>
</body>

</html>