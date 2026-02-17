<!DOCTYPE html>
<html lang="en">
<head>
        <meta charset="utf-8" />
        <title><?php echo ($title!='')?$title:config_item('app_name'); ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta content="" name="author" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />

        <!-- App favicon -->
        <link rel="shortcut icon" href="<?php echo base_url(); ?>public/assets/images/favicon.png">

        <!-- App css -->
        <link href="<?php echo base_url(); ?>public/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url(); ?>public/assets/css/jquery-ui.min.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>public/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url(); ?>public/assets/css/metisMenu.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url(); ?>public/assets/css/app.min.css" rel="stylesheet" type="text/css" />

    </head>

    <body class="bg-card">
        <div class="container-fluid">
            <!-- Log In page -->
            <div class="row vh-100">
                
                <div class="col-lg-8 p-0 h-100vh d-flex justify-content-center auth-bg">
                    <div class="accountbg d-flex align-items-center"> 
                        <div class="account-title text-center text-white">
                            <img src="<?php echo base_url(); ?>public/assets/images/ontime_logo.png" alt="" >
                            <h4 class="mt-3 text-white"><span class="text-primary"><?php echo config_item('app_name'); ?></span> </h4>
                            <h3 class="text-white mt-4">An all-in-one digital platform for customer service.</h3>
                            
                            <div class="border mt-4 w-25 mx-auto border-primary"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 pl-0">
                    <div class="auth-page">
                        <div class="card mb-0 shadow-none h-100">
                            <div class="card-body">
            
                                <div class="mb-5">
                                    <!-- <a href="crm-index.html" class="logo logo-admin text-center">
                                        <span><img src="<?php echo base_url(); ?>public/assets/images/logo.png" height="60" alt="logo" class="my-3"></span>
                                        
                                    </a> -->
                                </div>
            
                                <div class="px-3">
                                    <h2 class="font-weight-semibold font-22 mb-2">Welcome  to <span class="text-primary"><?php echo config_item('app_name'); ?></span>.</h2>
                                    <!-- <p class="text-muted">Try our fully featured cloud document platform.</p> -->

                                    <ul class="nav-border nav nav-pills" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active font-weight-semibold" data-toggle="tab" href="#LogIn_Tab" role="tab">Log In</a>
                                        </li>
                                        <!-- <li class="nav-item">
                                            <a class="nav-link font-weight-semibold" data-toggle="tab" href="#Register_Tab" role="tab">Register</a>
                                        </li> -->
                                    </ul>

                                    <!-- Tab panes -->
                                    <div class="tab-content">
                                        <div class="tab-pane active p-3" id="LogIn_Tab" role="tabpanel">  
                                            <?php echo $content; ?>
                                        </div>

                                        <!-- <div class="tab-pane px-3 pt-3" id="Register_Tab" role="tabpanel">
                                            <form class="form-horizontal auth-form my-4" action="https://mannatthemes.com/metrica_mvc5/default/index.html">
            
                                                <div class="form-group">
                                                    <label for="username">Username</label>
                                                    <div class="input-group mb-3">
                                                        <span class="auth-form-icon">
                                                            <i data-feather="user" class="icon-xs"></i>
                                                        </span>                                                                                                              
                                                        <input type="text" class="form-control" name="username" id="username" placeholder="Enter username">
                                                    </div>                                    
                                                </div>
            
                                                <div class="form-group">
                                                    <label for="useremail">Email</label>
                                                    <div class="input-group mb-3">
                                                        <span class="auth-form-icon">
                                                            <i data-feather="mail" class="icon-xs"></i>
                                                        </span>                                                                                                              
                                                        <input type="email" class="form-control" name="email" id="useremail" placeholder="Enter Email">
                                                    </div>                                    
                                                </div>
                    
                                                <div class="form-group">
                                                    <label for="userpassword">Password</label>                                            
                                                    <div class="input-group mb-3"> 
                                                        <span class="auth-form-icon">
                                                            <i data-feather="lock" class="icon-xs"></i> 
                                                        </span>                                                       
                                                        <input type="password" class="form-control" name="password" id="userpassword" placeholder="Enter password">
                                                    </div>                               
                                                </div>
            
                                                <div class="form-group">
                                                    <label for="conf_password">Confirm Password</label>                                            
                                                    <div class="input-group mb-3"> 
                                                        <span class="auth-form-icon">
                                                            <i data-feather="key" class="icon-xs"></i> 
                                                        </span>                                                       
                                                        <input type="password" class="form-control" name="conf-password" id="conf_password" placeholder="Enter Confirm Password">
                                                    </div>  
                                                    
                                                    <div class="form-group">
                                                        <label for="mo_number">Mobile Number</label>                                            
                                                        <div class="input-group mb-3"> 
                                                            <span class="auth-form-icon">
                                                                <i data-feather="phone" class="icon-xs"></i>  
                                                            </span>                                                       
                                                            <input type="text" class="form-control" name="mobile number" id="mo_number" placeholder="Enter Mobile Number">
                                                        </div>                               
                                                    </div> 
                                                </div>
                    
                                                <div class="form-group row mt-4">
                                                    <div class="col-sm-12">
                                                        <div class="custom-control custom-switch switch-success">
                                                            <input type="checkbox" class="custom-control-input" id="customSwitchSuccess">
                                                            <label class="custom-control-label text-muted" for="customSwitchSuccess">You agree to the Metrica <a href="#" class="text-primary">Terms of Use</a></label>
                                                        </div>
                                                    </div>                                      
                                                </div>
                                                
                                                <div class="form-group mb-0 row">
                                                    <div class="col-12 mt-2">
                                                        <button class="btn btn-gradient-primary btn-round btn-block waves-effect waves-light" type="button">Register <i class="fas fa-sign-in-alt ml-1"></i></button>
                                                    </div>
                                                </div>                            
                                            </form>
                                            <div class="mx-3 mt-3 mb-0 text-center text-muted">
                                                <p class="mb-0">Already have an account ? <a href="auth-login-alt.html" class="text-primary ml-2">Log in</a></p>
                                            </div>
                                        </div> -->
                                    </div> 
                                </div>
                            </div>
                        </div>
                      <?php include('footer.php'); ?>

                    </div>
                </div>
            </div>
        </div>

        


        <!-- jQuery  -->
        <script src="<?php echo base_url(); ?>public/assets/js/jquery.min.js"></script>
        <script src="<?php echo base_url(); ?>public/assets/js/jquery-ui.min.js"></script>
        <script src="<?php echo base_url(); ?>public/assets/js/bootstrap.bundle.min.js"></script>
        <script src="<?php echo base_url(); ?>public/assets/js/metismenu.min.js"></script>
        <script src="<?php echo base_url(); ?>public/assets/js/waves.js"></script>
        <script src="<?php echo base_url(); ?>public/assets/js/feather.min.js"></script>
        <script src="<?php echo base_url(); ?>public/assets/js/jquery.slimscroll.min.js"></script>
        <script>
            feather.replace()
        </script>
        
    </body>
</html>