<!doctype html>
<html lang="en">

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
    <link href="<?php echo base_url(); ?>assets_new/main.5fb56474af9319a1be42.css" rel="stylesheet">
    <script src="<?php echo base_url(); ?>global/js/jquery-3.6.0.min.js"></script>
    <!-- <script src="<?php echo base_url(); ?>/global/node_modules/popper.js/dist/umd/popper.min.js"></script> -->
    <script src="<?php echo base_url(); ?>assets_new/node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url(); ?>assets_new/scripts/main.5fb56474af9319a1be42.js"></script>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets_new/node_modules/sweetalert2/dist/sweetalert2.min.css">
    <script src="<?php echo base_url(); ?>assets_new/node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- Kanban Custom Styles -->
    <!-- jQuery UI (Add this) -->
    <!-- <script src="<?php echo base_url(); ?>global/js/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="<?php echo base_url(); ?>global/css/jquery-ui.css"> -->
    <style>
        /* Kanban Board Styles */
        .app-main {
            height: calc(100vh - 60px);
            /* Adjust based on your header height */
            overflow: hidden;
        }

        .kanban-wrapper {
            overflow-x: auto;
            overflow-y: hidden;
            flex-grow: 1;
            width: 100%;
        }

        .kanban-container {
            display: flex;
            flex-wrap: nowrap;
            gap: 10px;
            min-width: max-content;
        }

        .kanban-row {
            display: flex;
            flex-wrap: nowrap;
            height: calc(50vh - 60px);
        }

        .kanban-column {
            width: 300px;
            background: #f8f9fa;
            border-radius: 8px;
            flex-shrink: 0;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .kanban-column-header {
            border-radius: 8px 8px 0 0 !important;
            padding: 12px 15px;
            flex-shrink: 0;
        }


        .sidebar_overflow {
            overflow-y: auto !important;
            overflow-x: hidden !important;
            height: 83vh;
        }


        .kanban-column-body {
            overflow-x: hidden !important;
            overflow-y: auto;
            flex-grow: 1;
            padding: 0 10px;
        }

        .kanban-card {
            position: relative;
            background: white;
            margin: 10px;
            padding: 15px;
            border-radius: 6px;
            border-left: 4px solid;
            transition: all 0.2s ease;
            width: 100%;
            box-sizing: border-box;
        }



        .kanban-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        /* .country-flag {
            position: absolute;
            bottom: 10px;
            right: 10px;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            object-fit: cover;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
        } */

        /* Color Coding */
        .bg-purple {
            background-color: #6f42c1 !important;
        }

        .border-purple {
            border-color: #6f42c1 !important;
        }

        .bg-orange {
            background-color: rgba(237, 119, 9, 0.9) !important;
        }

        .border-orange {
            border-color: rgba(237, 119, 9, 0.9) !important;
        }

        .bg-lg {
            background-color: #17a2b8 !important;
        }

        .border-lg {
            border-color: #17a2b8 !important;
        }

        .bg-prospecting {
            background-color: #007bff !important;
        }

        .border-prospecting {
            border-color: #007bff !important;
        }

        .bg-quoted {
            background-color: #20c997 !important;
        }

        .border-quoted {
            border-color: #20c997 !important;
        }

        .bg-closed-won {
            background-color: #28a745 !important;
        }


        #todo .kanban-card {
            border-left-color: #84d6ff;
        }

        #first .kanban-card {
            border-left-color: #ffc107;
        }

        #second .kanban-card {
            border-left-color: #6f42c1;
        }

        #third .kanban-card {
            border-left-color: #17a2b8;
        }

        #junk .kanban-card {
            border-left-color: #dc3545;
        }

        #prospecting .kanban-card {
            border-left: 4px solid #007bff;
        }

        #quoted .kanban-card {
            border-left: 4px solid #20c997;
        }

        #closed .kanban-card {
            border-left: 4px solid #28a745;
        }

        /* Remove Bootstrap column padding */
        .kanban-container>[class^="col-"] {
            padding-left: 0;
            padding-right: 0;
        }

        .card-date {
            position: absolute;
            top: 10px;
            right: 10px;
            background: rgba(0, 0, 0, 0.05);
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.8rem;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .card-date i {
            color: #666;
        }

        .expired-card {
            background: #fff5f5;
        }

        .expired-card .contactable-date {
            color: #dc3545;
            background: rgba(220, 53, 69, 0.1);
            padding: 2px 6px;
            border-radius: 4px;
        }

        .type-filter {
            position: relative;
            min-width: 120px;
            transition: all 0.3s ease;
        }

        .type-filter.active {
            background-color: #007bff;
            color: white !important;
        }

        .type-filter .badge {
            position: absolute;
            top: -8px;
            right: -8px;
            font-size: 0.7rem;
        }

        .btn-outline-primary.active {
            background-color: #007bff;
            color: white !important;
            border-color: #007bff;
        }

        .btn-outline-primary:not(.active):hover {
            color: #007bff;
            background-color: transparent;
        }

        .sidebar {
            background-color: #f8f9fa;
            transition: width 0.1s ease;
        }

        /* .sidebar.collapsed {
            width: 50px !important;
        } */

        .sidebar.collapsed .collapse {
            display: none;
        }

        /* .sidebar.collapsed .toggle-btn {
            transform: rotate(180deg);
        } */

        .form-check-input:checked {
            background-color: #007bff;
            border-color: #007bff;
        }

        .bg-none {
            background: none !important;
            color: black !important;
        }

        @media (min-width: 768px) {
            .kanban-container {
                height: calc(100vh - 120px);
            }

            .kanban-column {
                min-width: 280px;
            }
        }
    </style>
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
<!-- <script>
    $(function() {
        $(".kanban-column").sortable({
            connectWith: ".kanban-column",
            placeholder: "ui-state-highlight",
            items: ".kanban-card",
            cancel: "a"
        });
    });
</script> -->

</html>