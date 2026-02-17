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
                                    <span class="d-inline-block">Leads</span>
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
                                                <a href="javascript:void(0);">Meetings</a>
                                            </li>
                                        </ol>
                                    </nav>
                                </div>
                            </div>
                        </div>
                        <div class="page-title-actions">
                            <div class="d-inline-block dropdown">
                                <a href="/leads/lead/new">
                                    <button type="button" class="btn btn-gradient-primary btn-shadow btn-wide">
                                        <span class="btn-icon-wrapper pr-1 opacity-7">
                                            <i class="fa fa-plus"></i>
                                        </span>
                                        Create Lead
                                    </button>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="app-inner-bar">
                    <div class="container fiori-container">
                        <div class="inner-bar-center">
                            <ul class="nav nav_tabs">
                                <li class="nav-item">
                                    <a role="tab" data-toggle="tab" class="nav-link active" href="#manage">
                                        <button class="btn">
                                            Upcoiming <span class="badge badge-primary"><?php echo count($upcoming_meetings); ?></span>
                                        </button>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <!-- <a class="nav-link" data-toggle="tab" href="#converted">Converted</a> -->
                                    <a role="tab" data-toggle="tab" class="nav-link" href="#converted">
                                        <button class="btn">
                                            Completed <span class="badge badge-primary"><?php echo count($completed_meetings); ?></span>
                                        </button>
                                    </a>
                                </li>

                            </ul>
                        </div>
                    </div>
                </div>
                <div class="app-inner-layout app-inner-layout-page">
                    <div class="app-inner-layout__wrapper">
                        <div class="app-inner-layout__content">
                            <div class="tab-content container-fluid">
                                <?php if ($this->session->flashdata('alert')) { ?>
                                    <div class="alert alert-<?php echo $this->session->flashdata('alert'); ?>">
                                        <?php echo $this->session->flashdata('alert_message'); ?>
                                    </div>
                                <?php } ?>
                                <div class="tab-pane tabs-animation fade show active" id="manage" role="tabpanel">
                                    <div class="row justify-content-center">
                                        <div class="col-lg-8">
                                            <div class="main-card mb-3 card mt-4">
                                                <div class="card-body">
                                                    <table style="width: 100%;" class="table dTtable table-hover table-striped table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th>#</th>
                                                                <th>Meeting Details</th>
                                                                <th>Date &amp; time</th>
                                                                <th>Remarks</th>
                                                                <th>Actions</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            foreach ($upcoming_meetings as $key => $value) {


                                                            ?>
                                                                <tr>
                                                                    <td><?php echo $value['id']; ?></td>
                                                                    <td><?php echo '<strong>Meeting with ' . $value['first_name'] . '</strong>'; ?></td>
                                                                    <td><?php echo date('d M Y H:i A', strtotime($value['meeting_date_time'])); ?></td>
                                                                    <td><?php echo $value['remarks']; ?></td>

                                                                    <td class="text-center"><a href="<?php echo base_url(); ?>leads/lead/view/<?php echo $value['lead_id']; ?>" class="btn btn-sm btn-primary" target="_blank">View</a></td>
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
                                <div class="tab-pane tabs-animation fade" id="converted" role="tabpanel">
                                    <div class="row justify-content-center">
                                        <div class="col-lg-8">
                                            <div class="main-card mb-3 card mt-4">
                                                <div class="card-body">
                                                    <table style="width: 100%;" class="table dTtable table-hover table-striped table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th>#</th>
                                                                <th>Meeting Details</th>
                                                                <th>Date &amp; time</th>
                                                                <th>Remarks</th>
                                                                <th>Actions</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            foreach ($completed_meetings as $key => $value) {
                                                            ?>
                                                                <tr>
                                                                    <td><?php echo $value['id']; ?></td>
                                                                    <td><?php echo '<strong>Meeting with ' . $value['first_name'] . '</strong>'; ?></td>
                                                                    <td><?php echo date('d M Y H:i A', strtotime($value['meeting_date_time'])); ?></td>
                                                                    <td><?php echo $value['remarks']; ?></td>

                                                                    <td class="text-center"><a href="<?php echo base_url(); ?>leads/lead/view/<?php echo $value['lead_id']; ?>" class="btn btn-sm btn-primary">View</a></td>
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
                                <div class="tab-pane tabs-animation fade" id="disqualified" role="tabpanel">
                                    <div class="row justify-content-center">
                                        <div class="col-lg-8">
                                            <div class="main-card mb-3 card mt-4">
                                                <div class="card-body">
                                                    <table style="width: 100%;" class="table dTtable table-hover table-striped table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th>#</th>
                                                                <th>Meeting Details</th>
                                                                <th>Date &amp; time</th>
                                                                <th>Remarks</th>
                                                                <th>Actions</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            foreach ($completed_meetings as $key => $value) {
                                                            ?>
                                                                <tr>
                                                                    <td><?php echo $value['id']; ?></td>
                                                                    <td><?php echo '<strong>Meeting with ' . $value['first_name'] . '</strong>'; ?></td>
                                                                    <td><?php echo date('d M Y H:i A', strtotime($value['meeting_date_time'])); ?></td>
                                                                    <td><?php echo $value['remarks']; ?></td>

                                                                    <td><a href="<?php echo base_url(); ?>leads/lead/view/<?php echo $value['lead_id']; ?>" class="btn btn-sm btn-primary">View</a></td>
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
                <h4>Meetings</h4>
                <span>Lead meetings - upcoming &amp; history</span>
            </div>
        </div>
    </div>
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a class="nav-link active" data-toggle="tab" href="#new">Upcoming
                &nbsp;<span class="badge light text-white bg-primary rounded-circle"><?php echo count($upcoming_meetings); ?></span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#manage">Completed&nbsp;<span class="badge light text-white bg-primary rounded-circle"><?php echo count($completed_meetings); ?></span></a>
        </li>
    </ul>

    <!-- Tab panes --
    <div class="tab-content">
        <div id="new" class="tab-pane active" style="background: #fff;padding: 30px 30px;border: 1px solid #dee2e6;">
            <div class="row">
                <div class="col-12">
                    <?php if ($this->session->flashdata('alert')) { ?>
                        <div class="alert alert-<?php echo $this->session->flashdata('alert'); ?>">
                            <?php echo $this->session->flashdata('alert_message'); ?>
                        </div>
                    <?php } ?>
                    <table id="datatable_leads" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Meeting Details</th>
                                <th>Date &amp; time</th>
                                <th>Remarks</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($upcoming_meetings as $key => $value) {


                            ?>
                                <tr>
                                    <td><?php echo $value['id']; ?></td>
                                    <td><?php echo '<strong>Meeting with ' . $value['first_name'] . '</strong>'; ?></td>
                                    <td><?php echo date('d M Y H:i A', strtotime($value['meeting_date_time'])); ?></td>
                                    <td><?php echo $value['remarks']; ?></td>

                                    <td><a href="<?php echo base_url(); ?>leads/lead/view/<?php echo $value['lead_id']; ?>" class="btn btn-sm btn-primary" target="_blank">View</a></td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
        <div id="manage" class="tab-pane fade" style="background: #fff;padding: 30px 30px;border: 1px solid #dee2e6;">
            <div class="row">
                <div class="col-12">
                    <table id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Meeting Details</th>
                                <th>Date &amp; time</th>
                                <th>Remarks</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($completed_meetings as $key => $value) {


                            ?>
                                <tr>
                                    <td><?php echo $value['id']; ?></td>
                                    <td><?php echo '<strong>Meeting with ' . $value['first_name'] . '</strong>'; ?></td>
                                    <td><?php echo date('d M Y H:i A', strtotime($value['meeting_date_time'])); ?></td>
                                    <td><?php echo $value['remarks']; ?></td>

                                    <td><a href="<?php echo base_url(); ?>leads/lead/view/<?php echo $value['lead_id']; ?>" class="btn btn-sm btn-primary">View</a></td>
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

    <script type="text/javascript">
        $(document).ready(function() {
            $('#datatable_leads').DataTable({
                aaSorting: [
                    [1, 'asc']
                ]
            });
        });
    </script> -->