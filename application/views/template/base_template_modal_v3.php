<!doctype html>
<html lang="en">

<meta http-equiv="content-type" content="text/html;charset=UTF-8" />

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="en">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <!-- <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests"> -->

    <title>OnTime Digital CRM - <?php echo $page_title; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
    <meta name="description" content="This is the OnTime Digital CRM - v2">

    <meta name="msapplication-tap-highlight" content="no">
    <link href="<?php echo  base_url(); ?>assets_new/main.5fb56474af9319a1be42.css" rel="stylesheet">
    <script src="<?php echo  base_url(); ?>global/js/jquery-3.6.0.min.js"></script>
    <script src="<?php echo base_url(); ?>assets_new/node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url(); ?>assets_new/scripts/main.5fb56474af9319a1be42.js"></script>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets_new/node_modules/sweetalert2/dist/sweetalert2.min.css">
    <script src="<?php echo  base_url(); ?>assets_new/node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>

</head>
<style>
    html, body {
        overflow-y: hidden;
    }
    
    .header-section {
        background-color: #f8f9fa;
    }

    .sidebar-menu .nav-item {
        cursor: pointer;
        margin-bottom: 5px;
    }

    .sidebar-menu .nav-item.active {
        background-color: #e9ecef;
        border-radius: 4px;
    }

    .content-section {
        display: none;
    }

    .content-section.active {
        display: block;
    }

    .timeline-content {
        display: none;
    }

    .timeline-content.active {
        display: block;
    }

    .timeline-item {
        display: flex;
        margin-bottom: 20px;
    }

    .timeline-date {
        width: 100px;
        font-weight: bold;
    }

    .toggle-container {
        position: relative;
        display: inline-flex;
        background: #f8f9fa;
        border-radius: 50px;
        padding: 4px;
        border: 1px solid #dee2e6;
    }

    .toggle-btn {
        position: relative;
        padding: 8px 35px;
        border: none;
        background: transparent;
        z-index: 1;
        transition: color 0.3s ease;
        font-weight: 500;
        color: #6c757d;
    }

    .toggle-btn:first-child {
        border-radius: 50px 0 0 50px;
    }

    .toggle-btn:last-child {
        border-radius: 0 50px 50px 0;
    }

    .highlight {
        position: absolute;
        top: -2px;
        left: 0px;
        height: calc(100% + 4px);
        width: 50%;
        border: 2px solid #007bff;
        border-radius: 50px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .content-section {
        display: none;
    }

    .content-section.active {
        display: block;
    }

    .toggle-btn.active {
        color: #007bff;
    }
</style>
    <body>
        <div class="app-container app-theme-white">
            <div class="app-top-bar bg-plum-plate top-bar-text-light">
                <div class="container fiori-container">
                    <div class="top-bar-left">
                        <ul class="nav">
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    E-Gov Powered
                                </a>
                            </li>

                        </ul>
                    </div>
                    <div class="top-bar-right">
                        <ul class="nav">
                            <li class="nav-item mr-1">
                                <a href="javascript:void(0);" class="nav-link">
                                    Create Account
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <?php
                include "top_nav.php";
                echo $content;
            ?>
        
        </div>
        <?php
                include "footer.php";
            ?>
    
        <div class="app-drawer-overlay d-none animated fadeIn"></div>
        <?php unset($_SESSION["alert"]); ?>
    </body>
</html>