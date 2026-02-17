<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="en">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>OnTime CRM - Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
    <meta name="description" content="Fiori HTML Bootstrap 4 Dashboard Template">

    <meta name="msapplication-tap-highlight" content="no">
    <link href="<?php echo base_url(); ?>assets_new/main.5fb56474af9319a1be42.css" rel="stylesheet">
    <script type="text/javascript" src="<?php echo base_url(); ?>assets_new/scripts/main.5fb56474af9319a1be42.js"></script>
    <script src="<?php echo base_url(); ?>global/js/jquery-3.6.0.min.js"></script>
    <style>
        .bg-animation {
            background-image: url("<?php echo base_url(); ?>global/images/timeline.png");
            background-repeat: no-repeat;
            background-size: cover;
            background-color: #00000042;
            background-blend-mode: color;
        }
    </style>
</head>

<body>
    <div class="app-container app-theme-white body-tabs-shadow">
        <div class="app-container">
            <div class="h-100  bg-animation">
                <div class="d-flex h-100 justify-content-center align-items-center">
                    <div class="mx-auto app-login-box col-md-8">
                        <!-- <div class="app-logo-inverse mx-auto mb-3"></div> -->
                        <div class="modal-dialog w-100 mx-auto">
                            <?php
                                echo $content;
                            ?>
                        </div>
                        <div class="text-center text-white opacity-8 mt-3">Copyright © OnTime <?php echo date("Y"); ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>


</html>


