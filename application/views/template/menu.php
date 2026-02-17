<?php
$active_menu = $this->uri->segment(1);
$segment2 = $this->uri->segment(2);
$segment3 = $this->uri->segment(3);
?>
<?php if($this->auth_level==9){?>
<!-- leftbar-tab-menu -->
        <div class="leftbar-tab-menu">
            <div class="main-icon-menu slimscroll-menu">
                <a href="<?php echo base_url(); ?>" class="logo logo-metrica d-block text-center">
                    <span>
                        <img src="<?php echo base_url(); ?>public/assets/images/logo.png" alt="logo-small" class="logo-sm">
                    </span>
                </a>
                <!-- <?php echo $this->auth_level;?> -->
                <nav class="nav">
                    <a href="<?php echo base_url(); ?>dashboard" class="nav-link <?php echo ($active_menu=='dashboard' || $active_menu=='')?'active':''; ?>" data-toggle="tooltip-custom" data-placement="right"  data-trigger="hover" title="" data-original-title="Dashboard">
                        <i data-feather="home" class="align-self-center menu-icon icon-dual"></i>
                    </a> 
                    

                    <a href="<?php echo base_url(); ?>admin/company/manage" class="nav-link <?php echo ($active_menu=='admin')?'active':''; ?>" data-toggle="tooltip-custom" data-placement="right"  data-trigger="hover" title="" data-original-title="Administration">
                        <i data-feather="tool" class="align-self-center menu-icon icon-dual"></i>             
                    </a><!--end MetricaPages-->

                    <a href="<?php echo base_url(); ?>weqayati/category" class="nav-link <?php echo ($active_menu=='weqayati')?'active':''; ?>" data-toggle="tooltip-custom" data-placement="right"  data-trigger="hover" title="" data-original-title="Weqayati">
                        <i data-feather="anchor" class="align-self-center menu-icon icon-dual"></i>
                    </a> <!--end MetricaWeqayati--> 

                    <a href="<?php echo base_url(); ?>customers" class="nav-link" data-toggle="tooltip-custom" data-placement="right"  data-trigger="hover" title="" data-original-title="Customers">
                        <i data-feather="user" class="align-self-center menu-icon icon-dual"></i>
                    </a> <!--end MetricaAuthentication-->

                    <a href="<?php echo base_url(); ?>logout" class="nav-link" data-toggle="tooltip-custom" data-placement="right"  data-trigger="hover" title="" data-original-title="Logout">
                        <i data-feather="power" class="align-self-center menu-icon icon-dual"></i>
                    </a> <!--end MetricaAuthentication--> 
                </nav><!--end nav-->
                <div class="pro-metrica-end">
                    <a href="#" class="profile">
                        <img src="<?php echo base_url(); ?>public/assets/images/users/user-4.jpg" alt="profile-user" class="rounded-circle thumb-sm"> 
                    </a>
                </div>
            </div><!--end main-icon-menu-->

            <div class="main-menu-inner" id="inner_menu">
                <!-- LOGO -->
                <div class="topbar-left">
                    <a href="<?php echo base_url(); ?>" class="logo">
                        <span>
                            <img src="<?php echo base_url(); ?>public/assets/images/ontime_logo_blue.png" alt="logo-large" class="logo-lg logo-dark">
                            <img src="<?php echo base_url(); ?>public/assets/images/ontime_logo.png" alt="logo-large" class="logo-lg logo-light">
                        </span>
                    </a>
                </div>
                <!--end logo-->
                <div class="menu-body slimscroll">                    
                    <div id="ontimeDash" class="main-icon-menu-pane <?php echo ($active_menu=='dashboard' || $active_menu=='')?'active':''; ?>">
                        <div class="title-box">
                            <h6 class="menu-title">Dashboard</h6>       
                        </div>
                        <ul class="nav">
                            
                            <li class="nav-item"><a class="nav-link" href="<?php echo base_url(); ?>enquiry">Enquiry</a></li>

                            <li class="nav-item"><a class="nav-link" href="<?php echo base_url(); ?>complaints">Complaints</a></li>

                            <li class="nav-item"><a class="nav-link" href="<?php echo base_url(); ?>meeting">Meeting</a></li>

                            <li class="nav-item"><a class="nav-link" href="<?php echo base_url(); ?>leads">Orders</a></li>

                            <!-- <li class="nav-item"><a class="nav-link" href="<?php echo base_url(); ?>orders">Orders</a></li> -->

                            <li class="nav-item"><a class="nav-link" href="<?php echo base_url(); ?>packages">Packages</a></li>

                            <li class="nav-item"><a class="nav-link" href="<?php echo base_url(); ?>appointments">Appointments</a></li>
                            <li class="nav-item"><a class="nav-link" href="<?php echo base_url(); ?>assigned_leads">Leads</a></li>

                            <!-- <li class="nav-item"><a class="nav-link" href="<?php echo base_url(); ?>visits">Branch Visit</a></li>

                            <li class="nav-item"><a class="nav-link" href="<?php echo base_url(); ?>tasks">Tasks</a></li>

                            <li class="nav-item"><a class="nav-link" href="<?php echo base_url(); ?>reminders">Reminders</a></li> -->

                            <li class="nav-item"><a class="nav-link" href="<?php echo base_url(); ?>feedback">Feedback</a></li>

                            <li class="nav-item"><a class="nav-link" href="<?php echo base_url(); ?>survey">Survey</a></li>
                            <li class="nav-item">
                                <a class="nav-link" href="javascript: void(0);"><span class="w-100">Reports</span><span class="menu-arrow"><i class="mdi mdi-chevron-right"></i></span></a>
                                <ul class="nav-second-level" aria-expanded="false">
                                    <li><a href="<?php echo base_url(); ?>admin/projects/create">Lead Report</a></li>     
                                </ul>            
                            </li><!--end nav-item-->
                            
                        </ul>
                    </div><!-- end Dashboards -->                

                    
                    <div id="ontimeCompany" class="main-icon-menu-pane <?php echo ($active_menu=='admin')?'active':''; ?>">
                        
                        <div class="title-box">
                            <h6 class="menu-title">Administration</h6>     
                        </div>
                        <ul class="nav metismenu">
                            <li class="nav-item <?php echo ($segment2=='company')?'mm-active active':''; ?>">
                                <a class="nav-link <?php echo ($segment2=='company')?'active':''; ?>" href="javascript: void(0);"><span class="w-100">Company</span><span class="menu-arrow"><i class="mdi mdi-chevron-right"></i></span></a>
                                <ul class="nav-second-level" aria-expanded="false">
                                    <li <?php echo ($segment3=='manage'||$segment3=='add')?'class="active"':''; ?>><a href="<?php echo base_url(); ?>admin/company/manage">Manage</a></li>           
                                </ul>            
                            </li><!--end nav-item-->
                             <li class="nav-item <?php echo ($segment2=='company')?'mm-active active':''; ?>">
                                <a class="nav-link <?php echo ($segment2=='users')?'active':''; ?>" href="javascript: void(0);"><span class="w-100">Masters</span><span class="menu-arrow"><i class="mdi mdi-chevron-right"></i></span></a>
                                <ul class="nav-second-level" aria-expanded="false">
                                    <li><a href="<?php echo base_url(); ?>admin/master/industry">Industry</a></li>
                                    <li><a href="<?php echo base_url(); ?>admin/master/group">Group</a></li>
                                </ul>            
                            </li><!--end nav-item-->    
                            <li class="nav-item <?php echo ($segment2=='users')?'mm-active active':''; ?>">
                                <a class="nav-link" href="javascript: void(0);"><span class="w-100">Users</span><span class="menu-arrow"><i class="mdi mdi-chevron-right"></i></span></a>
                                <ul class="nav-second-level" aria-expanded="false">
                                    <li class="nav-item  <?php echo ($segment3 =='role' || $segment3=='add')?'class="active"':''; ?>">
                                        <a href="<?php echo base_url(); ?>admin/role">Role</a></li>
                                    <li class="nav-item <?php echo ($segment3=='users'||$segment3=='create')?'class="active"':''; ?>"><a href="<?php echo base_url(); ?>admin/users/create">Add new user</a></li>
                                    <li class="nav-item <?php echo ($segment3=='assign_user'||$segment3=='add')?'class="active"':''; ?>"><a href="<?php echo base_url(); ?>admin/assign_user">Assign Users</a></li>
                                    <li class="nav-item <?php echo ($segment3=='assign_group'||$segment3=='add')?'class="active"':''; ?>"><a href="<?php echo base_url(); ?>admin/assign_group_company">Assign Group Company</a></li>
                                    <li class="nav-item <?php echo ($segment3=='assign_group'||$segment3=='add')?'class="active"':''; ?>"><a href="<?php echo base_url(); ?>admin/assign_group">Assign Group</a></li>
                                </ul>            
                            </li>
                            <!--end nav-item-->    
                        </ul>
                        <!-- <div class="alert alert-success">
                            <strong>Did you know?</strong>
                            <p>Since you are an admin, you can only see these settings.</p>
                        </div> -->

                        
                    </div>


            <div id="ontimeWeqayati" class="main-icon-menu-pane <?php echo ($active_menu=='weqayati')?'active':''; ?>">
                        <div class="title-box">
                            <h6 class="menu-title">Weqayati Services</h6>     
                        </div>
                        <ul class="nav">
                            
                            <li class="nav-item"><a class="nav-link" href="<?php echo base_url(); ?>weqayati/category">Category</a></li>
                            <li class="nav-item"><a class="nav-link" href="<?php echo base_url(); ?>weqayati/sub_category">Sub Category</a></li>
                            <li class="nav-item"><a class="nav-link" href="<?php echo base_url(); ?>weqayati/sub_category2">Sub Category Two</a></li>
                            <li class="nav-item"><a class="nav-link" href="<?php echo base_url(); ?>weqayati/service_hours">Service Hours</a></li>
                            <li class="nav-item"><a class="nav-link" href="<?php echo base_url(); ?>weqayati/attachment">Manage Attchment</a></li>
                            <li class="nav-item"><a class="nav-link" href="<?php echo base_url(); ?>weqayati/amount">Manage Amount</a></li>

                
                            </ul>
                        <!-- <ul class="nav metismenu">
                        <li class="nav-item <?php echo ($active_menu=='category')?'mm-active active':''; ?>">
                                <a class="nav-link" href="javascript: void(0);"><span class="w-100">Masters</span><span class="menu-arrow"><i class="mdi mdi-chevron-right"></i></span></a>
                                <ul class="nav-second-level" aria-expanded="false">
                                    <li class="nav-item  <?php echo ($segment2 =='category' || $segment3=='create')?'class="active"':''; ?>">
                                        <a href="<?php echo base_url(); ?>weqayati/category">Category</a></li>
                                    <li class="nav-item  <?php echo ($segment2 =='category' || $segment3=='create')?'class="active"':''; ?>">
                                        <a href="<?php echo base_url(); ?>weqayati/sub_category">Sub Category</a></li>     
                                    <li class="nav-item  <?php echo ($segment2 =='category' || $segment3=='create')?'class="active"':''; ?>">
                                        <a href="<?php echo base_url(); ?>weqayati/sub_category2">Sub Category Two</a></li>     
                                    <li class="nav-item <?php echo ($segment2=='service_hours'||$segment3=='create')?'class="active"':''; ?>"><a href="<?php echo base_url(); ?>weqayati/service_hours">Service Hours</a></li>
                                    <li class="nav-item <?php echo ($segment2=='attachment'||$segment3=='create')?'class="active"':''; ?>"><a href="<?php echo base_url(); ?>weqayati/attachment">Manage Attchment</a></li>
                                    <li class="nav-item <?php echo ($segment2=='amount'||$segment3=='create')?'class="active"':''; ?>"><a href="<?php echo base_url(); ?>weqayati/amount">Manage Amount</a></li>
                                </ul>            
                            </li>
                        </ul> -->
                        <!-- <div class="alert alert-success">
                            <strong>Did you know?</strong>
                            <p>Since you are an admin, you can only see these settings.</p>
                        </div> -->

                        
                    </div>

                    <div id="ontimeDash" class="main-icon-menu-pane <?php echo ($active_menu=='dashboard')?'active':''; ?>">
                        <div class="title-box">
                            <h6 class="menu-title">Customers</h6>       
                        </div>
                        <ul class="nav">
                            
                            <li class="nav-item"><a class="nav-link" href="<?php echo base_url(); ?>customers">Manage</a></li>

                            <li class="nav-item"><a class="nav-link" href="<?php echo base_url(); ?>customers/new">New Customer</a></li>
                            
                        </ul>
                    </div><!-- end Dashboards -->  
                </div><!--end menu-body-->
            </div><!-- end main-menu-inner-->
        </div>
        <!-- end leftbar-tab-menu-->
<?php } ?>

<?php if($this->auth_level==1){?>
   <!-- leftbar-tab-menu -->
   <div class="leftbar-tab-menu">
            <div class="main-icon-menu slimscroll-menu">
                <a href="<?php echo base_url(); ?>" class="logo logo-metrica d-block text-center">
                    <span>
                        <img src="<?php echo base_url(); ?>public/assets/images/logo.png" alt="logo-small" class="logo-sm">
                    </span>
                </a>
                <nav class="nav">
                    <a href="<?php echo base_url(); ?>dashboard" class="nav-link <?php echo ($active_menu=='dashboard' || $active_menu=='')?'active':''; ?>" data-toggle="tooltip-custom" data-placement="right"  data-trigger="hover" title="" data-original-title="Dashboard">
                        <i data-feather="home" class="align-self-center menu-icon icon-dual"></i>
                    </a> 

                    <a href="<?php echo base_url(); ?>logout" class="nav-link" data-toggle="tooltip-custom" data-placement="right"  data-trigger="hover" title="" data-original-title="Logout">
                        <i data-feather="power" class="align-self-center menu-icon icon-dual"></i>
                    </a> <!--end MetricaAuthentication--> 
                    
                </nav><!--end nav-->
            
            </div><!--end main-icon-menu-->

            <div class="main-menu-inner" id="inner_menu">
                <!-- LOGO -->
             
                <!--end logo-->
                <div class="menu-body slimscroll">                    
                    <div id="ontimeDash" class="main-icon-menu-pane <?php echo ($active_menu=='dashboard' || $active_menu=='')?'active':''; ?>">
                        <div class="title-box">
                            <h6 class="menu-title">Dashboard</h6>       
                        </div>
                        <ul class="nav">
                            
                            <li class="nav-item"><a class="nav-link" href="<?php echo base_url(); ?>user_leads">Orders</a></li>
                            <li class="nav-item"><a class="nav-link" href="<?php echo base_url(); ?>user_enquiry">Enquriy</a></li>
                            <li class="nav-item"><a class="nav-link" href="<?php echo base_url(); ?>user_compaints">Complaints</a></li>
                            <li class="nav-item"><a class="nav-link" href="<?php echo base_url(); ?>user_meeting">Meeting</a></li>
                            <li class="nav-item"><a class="nav-link" href="<?php echo base_url(); ?>User_appointment">Appointment</a></li>
                            <li class="nav-item"><a class="nav-link" href="<?php echo base_url(); ?>Assigned_user_leads">Leads</a></li>
                        </ul>
                    </div><!-- end Dashboards -->                

                    
                </div><!--end menu-body-->
            </div><!-- end main-menu-inner-->
        </div>
        <!-- end leftbar-tab-menu-->
<?php }?>