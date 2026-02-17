<?php
$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
?>
<!DOCTYPE html>
<html lang="en">

<head>
        <meta charset="utf-8" />
        <title>LetsFame</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta content="LetsFame" name="description" />
        <meta content="LetsFame" name="author" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />

        <!-- App favicon -->
        <link rel="shortcut icon" href="global/assets/images/favicon.ico">

        <!-- App css -->
        <link href="global/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="global/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
        <link href="global/assets/css/theme.min.css" rel="stylesheet" type="text/css" />
        <link href="global/assets/css/letsfame.min.css" rel="stylesheet" type="text/css" />

    </head>

    <body>

        <div class="login_bg">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 offset-lg-3 col-sm-12">
                        <div class="d-flex align-items-center min-vh-100">
                            <div class="w-100 d-block bg-white shadow-lg rounded my-5">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="p-5">
                                            <div class="text-center">
                                                <a href="index-2.html" class="d-block mb-5">
                                                    <img src="global/assets/images/logo-dark.png" alt="app-logo" height="18" />
                                                </a>
                                            </div>
                                            
                                            <div class="text-center">
                                                <img src="global/assets/images/404-error.svg" alt="error" height="140">
                                                <h1 class="h4 mb-3 mt-4">Page Not Found</h1>
                                                <p class="text-muted mb-4 w-75 m-auto">It's looking like you may have taken a wrong turn. Don't worry... it happens to the best of us. Here's a little tip that might help you get back on track.</p>
                                            </div>

                                            <div class="row mt-4">
                                                <div class="col-12 text-center">
                                                    <a href="<?php echo $actual_link; ?>" class="btn btn-success"><i class="mdi mdi-home mr-2"></i>Back to Home </a>
                                                </div> <!-- end col -->
                                            </div>
                                            <!-- end row -->
                                        </div> <!-- end .padding-5 -->
                                    </div> <!-- end col -->
                                </div> <!-- end row -->
                            </div> <!-- end .w-100 -->
                        </div> <!-- end .d-flex -->
                    </div> <!-- end col-->
                </div> <!-- end row -->
            </div>
            <!-- end container -->
        </div>
        <!-- end page -->

        <!-- jQuery  -->
        <script src="global/assets/js/jquery.min.js"></script>
        <script src="global/assets/js/bootstrap.bundle.min.js"></script>
        <script src="global/assets/js/metismenu.min.js"></script>
        <script src="global/assets/js/waves.js"></script>
        <script src="global/assets/js/simplebar.min.js"></script>

        <!-- App js -->
        <script src="global/assets/js/theme.js"></script>

    </body>
</html>