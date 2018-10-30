<?php
defined('MOODLE_INTERNAL') || die();
include_once($CFG->dirroot."/theme/itrackglobal/lib.php"); 
global $DB,$CFG,$USER;
?>
<!-- ============================================================== -->
        <!-- Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <aside class="left-sidebar">
            <!-- Sidebar scroll-->
            <div class="scroll-sidebar">
                <!-- User profile -->
                <?php echo '<div class="user-profile" style="background: url('.$CFG->wwwroot.'/theme/itrackglobal/assets/images/background/user-info.jpg) no-repeat;">
                    <!-- User profile image -->
                    <div class="profile-img"> <img src="'.$CFG->wwwroot.'/theme/itrackglobal/assets/images/logo/hu.png" alt="user" title="Training Partner"/> </div>';?>
                    <!-- User profile text-->
                    <div class="profile-text"> <a href="#" class="dropdown-toggle u-dropdown" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true"><?php echo $USER->firstname;?></a>
                        <div class="dropdown-menu animated flipInY"> 
                            <a href="<?php echo $CFG->wwwroot.'/user/profile.php?id='.$USER->id;?>" class="dropdown-item">
                                <i class="ti-user"></i> My Profile
                            </a> 
                            <a href="<?php echo $CFG->wwwroot.'/calendar/view.php?view=month';?>" class="dropdown-item">
                                <i class="ti-wallet"></i> My Calender
                            </a> 
                            <a href="<?php echo $CFG->wwwroot.'/message/index.php?id='.$USER->id;?>" class="dropdown-item">
                                <i class="ti-email"></i> My Chats
                            </a>
                            
                            <div class="dropdown-divider"></div> 

                            <a href="<?php echo $CFG->wwwroot.'/user/profile.php?id='.$USER->id;?>" class="dropdown-item">
                                <i class="ti-settings"></i> Account Setting
                            </a>
                            
                            <div class="dropdown-divider"></div> 
                            
                            <a href="login.html" class="dropdown-item"><i class="fa fa-power-off"></i> Logout
                            </a> 
                        </div>
                    </div>
                </div>
                <!-- End User profile text-->
                <!--starts sidebar nav -->
                <?php
                    echo left_menubar();
                ?>
                <!--ends sidebar nav -->
            </div>
            <!-- End Sidebar scroll-->
            <!-- Bottom points-->
            <div class="sidebar-footer">
                <!-- item--><a href="" class="link" data-toggle="tooltip" title="Settings"><i class="ti-settings"></i></a>
                <!-- item--><a href="" class="link" data-toggle="tooltip" title="Email"><i class="mdi mdi-gmail"></i></a>
                <!-- item--><a href="" class="link" data-toggle="tooltip" title="Logout"><i class="mdi mdi-power"></i></a> </div>
            <!-- End Bottom points-->
        </aside>
        <!-- ============================================================== -->
        <!-- End Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->