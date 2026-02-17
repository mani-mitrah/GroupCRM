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
    <meta name="viewport"
        content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
    <meta name="description" content="This is the OnTime Digital CRM - v2">

    <meta name="msapplication-tap-highlight" content="no">
    <link href="<?php echo base_url(); ?>assets_new/main.5fb56474af9319a1be42.css" rel="stylesheet">
    <script src="<?php echo base_url(); ?>global/js/jquery-3.6.0.min.js"></script>
    <script src="<?php echo base_url(); ?>assets_new/node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url(); ?>assets_new/scripts/main.5fb56474af9319a1be42.js"></script>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets_new/node_modules/sweetalert2/dist/sweetalert2.min.css">
    <script src="<?php echo base_url(); ?>assets_new/node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <style>
    .paginate_button,
    .ellipsis {
        border: 1px solid;
        padding: 6px 10px;
    }

    .paginate_button.current {
        background: #01297c;
        color: white;
        border-color: #01297c;
    }

    a.paginate_button:hover {
        text-decoration: none;
        cursor: pointer;
    }

    a[role="tab"].active.nav-link::after {
        content: "";
        background: #00287c;
        padding: 2px;
        position: absolute;
        width: 50px;
        border-radius: 20%;
        left: 0;
        top: 30px;
        right: 0;
        margin: auto;
    }

    .dataTables_wrapper input[type="search"] {
        border: 1px solid #9c9c9c;
        border-radius: 5px;
        padding: 5px 13px;
    }

    @media (max-width: 991px) {
        .dropdown-mega-menu-sm {
            width: 100%;
        }
    }

    .breadcrumb-item a:hover {
        cursor: context-menu;
        text-decoration: none;
        color: unset;
    }

    span.select2-selection.select2-selection--single {
        height: calc(2.25rem + 2px);
        padding: 3px;
    }

    span.select2-selection__arrow {
        height: 100% !important;
    }
    </style>
    <script>
    function removeTags(str) {
        if ((str === null) || (str === ''))
            return false;
        else
            str = str.toString();
        return str.replace(/(<([^>]+)>)/ig, '');
    }
    </script>
</head>

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

    <div class="app-drawer-overlay d-none animated fadeIn"></div>
    <?php unset($_SESSION["alert"]); ?>
</body>

</html>