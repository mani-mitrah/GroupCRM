<style>
    a[role="tab"].active.nav-link::after {
        top: 38px;
    }
    
    .breadcrumb .breadcrumb-item a {
        color: inherit; /* Default link color */
        text-decoration: none; /* Remove underline by default */
        transition: color 0.3s, text-decoration 0.3s; /* Smooth transition */
    }

    .breadcrumb .breadcrumb-item a:hover {
        color: #007bff !important; /* Replace with the exact color code of "Orders" */
        text-decoration: underline !important; /* Add underline on hover */
    }

</style>
<div class="app-main">
    <div class="app-main__outer">
        <div class="app-main__inner">
            <div class="app-page-title">
                <div class="container fiori-container">
                    <div class="page-title-wrapper">
                        <div class="page-title-heading">
                            <div>
                                <div class="page-title-head center-elem">
                                    <span class="d-inline-block pr-2">
                                        <i class=""></i>
                                    </span>
                                    <span class="d-inline-block">Manage Templates</span>
                                </div>
                                <div class="page-title-subheading opacity-10">
                                    <nav class="" aria-label="breadcrumb">
                                        <ol class="breadcrumb">
                                            <li class="breadcrumb-item">
                                                <a href="javascript:void(0);">
                                                    <i aria-hidden="true" class="fa fa-home"></i>
                                                </a>
                                            </li>
                                            <li class="breadcrumb-item">
                                                <a href="#">Dashboard</a>
                                            </li>
                                            <li class="breadcrumb-item">
                                                <a href="#">Manage</a>
                                            </li>
                                            <li class="active breadcrumb-item">
                                                <a href="javascript:void(0);">Templates</a>
                                            </li>
                                        </ol>
                                    </nav>
                                </div>
                            </div>
                        </div>
                        <div class="page-title-actions">
                            <div class="d-inline-block dropdown">
                                <a href="/leads/templates/add/">
                                    <button type="button" class="btn btn-gradient-primary btn-shadow btn-wide">
                                        <span class="btn-icon-wrapper pr-1 opacity-7">
                                            <i class="fa fa-plus"></i>
                                        </span>
                                        Create Template
                                    </button>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="app-page-title">
                <div class="app-inner-layout app-inner-layout-page">
                    <div class="app-inner-layout__wrapper">
                        <div class="app-inner-layout__content">
                            <div class="tab-content container-fluid">
                                <?php
                                if ($this->session->flashdata('alert_success')) {
                                ?>
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                        <strong>Success!</strong> <?php echo $this->session->flashdata('alert_success'); ?>
                                    </div>
                                <?php
                                }

                                if ($this->session->flashdata('alert_danger')) {
                                ?>
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                        <strong>Success!</strong> <?php echo $this->session->flashdata('alert_danger'); ?>
                                    </div>
                                <?php
                                }

                                if ($this->session->flashdata('alert_warning')) {
                                ?>
                                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                        <strong>Success!</strong> <?php echo $this->session->flashdata('alert_warning'); ?>
                                    </div>
                                <?php
                                }
                                ?>
                                <div class="tab-pane tabs-animation fade show active" id="manage" role="tabpanel">
                                    <div class="row justify-content-center">
                                        <div class="col-lg-8">
                                            <div class="main-card mb-3 card mt-4">
                                                <div class="card-body">
                                                    <table style="width: 100%;" class="table dTtable table-hover table-striped table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th>#</th>
                                                                <th>Template Type</th>
                                                                <th>Template Name</th>
                                                                <th>Created Date</th>
                                                                <th>Status</th>
                                                                <th>Actions</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            foreach ($default as $value) {
                                                            ?>
                                                                <tr>
                                                                    <td><?php echo $value['id']; ?></td>
                                                                    <td><?php $type = $value['template_type'];
                                                                        if ($type == '1') {
                                                                        ?>
                                                                            <span class="badge badge-light-success">EMAIL</span>
                                                                        <?php
                                                                        } else {
                                                                        ?>
                                                                            <span class="badge badge-light-danger">SMS</span>
                                                                        <?php
                                                                        }
                                                                        ?>
                                                                    </td>
                                                                    <td><?php echo $value['template_name']; ?></td>
                                                                    <td><?php echo $value['created_date']; ?></td>
                                                                    <td><?php $status = $value['is_active'];
                                                                        if ($status == '1') {
                                                                        ?>
                                                                            <span class="badge badge-light-success">Active</span>
                                                                        <?php
                                                                        } else {
                                                                        ?>
                                                                            <span class="badge badge-light-danger">In-active</span>
                                                                        <?php
                                                                        }
                                                                        ?>
                                                                    </td>
                                                                    <td class="text-center"><a href="<?php echo base_url(); ?>leads/templates/edit/<?php echo $value['id']; ?>" class="btn btn-sm btn-primary">Edit</a>&nbsp;<a href="<?php echo base_url(); ?>leads/templates/status_change/<?php echo $value['id']; ?>" class="btn btn-sm btn-primary">Change Status</a></td>
                                                                </tr>
                                                            <?php
                                                            }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="<?php echo base_url(); ?>global/vendor/datatables/js/jquery.dataTables.min.js"></script>
    <script src="<?php echo base_url(); ?>assets_new/node_modules/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script src="<?php echo base_url(); ?>assets_new/node_modules/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets_new/node_modules/datatables.net-responsive-bs4/css/responsive.bootstrap4.css">
    <script>
        $("document").ready(function() {
            $(".dTtable").dataTable({
                order: [
                    [0, 'desc']
                ],
                dom: '<"row"<"col"l><"col"f>>rt<"row"<"col"i><"col my-3"p>>',
                responsive: true
            });

            var hash = document.location.hash;
            if (hash) {
                console.log(hash);
                $(".nav-tabs a[href=\\" + hash + "]").tab('show');
            }

            // Change hash for page-reload
            $('.nav-tabs a').on('shown.bs.tab', function(e) {
                window.location.hash = e.target.hash;
            });
        });
    </script>



    <!-- 

    <div class="row page-titles mx-0">
        <div class="col-sm-6 p-md-0">
            <div class="welcome-text">
                <h4><?php echo $page_title; ?></h4>
                <span>Manage Templates</span>
            </div>
        </div>
        <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
            <a class="btn btn-primary waves-effect waves-light float-right" href="<?php echo base_url(); ?>leads/templates/add/">
                <i class="mdi mdi-plus mr-2"></i> ADD NEW
            </a>
        </div>
    </div>
    <?php
    if ($this->session->flashdata('alert_success')) {
    ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
            <strong>Success!</strong> <?php echo $this->session->flashdata('alert_success'); ?>
        </div>
    <?php
    }

    if ($this->session->flashdata('alert_danger')) {
    ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
            <strong>Success!</strong> <?php echo $this->session->flashdata('alert_danger'); ?>
        </div>
    <?php
    }

    if ($this->session->flashdata('alert_warning')) {
    ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
            <strong>Success!</strong> <?php echo $this->session->flashdata('alert_warning'); ?>
        </div>
    <?php
    }
    ?>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <table id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Template Type</th>
                                <th>Template Name</th>
                                <th>Created Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($default as $value) {
                            ?>
                                <tr>
                                    <td><?php echo $value['id']; ?></td>
                                    <td><?php $type = $value['template_type'];
                                        if ($type == '1') {
                                        ?>
                                            <span class="badge badge-light-success">EMAIL</span>
                                        <?php
                                        } else {
                                        ?>
                                            <span class="badge badge-light-danger">SMS</span>
                                        <?php
                                        }
                                        ?>
                                    </td>
                                    <td><?php echo $value['template_name']; ?></td>
                                    <td><?php echo $value['created_date']; ?></td>
                                    <td><?php $status = $value['is_active'];
                                        if ($status == '1') {
                                        ?>
                                            <span class="badge badge-light-success">Active</span>
                                        <?php
                                        } else {
                                        ?>
                                            <span class="badge badge-light-danger">In-active</span>
                                        <?php
                                        }
                                        ?>
                                    </td>
                                    <td><a href="<?php echo base_url(); ?>leads/templates/edit/<?php echo $value['id']; ?>" class="btn btn-sm btn-primary">Edit</a>&nbsp;<a href="<?php echo base_url(); ?>leads/templates/status_change/<?php echo $value['id']; ?>" class="btn btn-sm btn-primary">Change Status</a></td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div> -->