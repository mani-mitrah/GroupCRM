<style>
    a[role="tab"].active.nav-link::after {
        top: 38px;
    }

    span.select2.select2-container.select2-container--default {
        border: 1px solid #ced4da;
        border-radius: 3px;
        padding: 10px 5px 4px 5px;
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
                                    <span class="d-inline-block">Service List</span>
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
                                                <a href="/">Dashboard</a>
                                            </li>
                                            <li class="active breadcrumb-item">
                                                <a href="javascript:void(0);">Service List</a>
                                            </li>
                                        </ol>
                                    </nav>
                                </div>
                            </div>
                        </div>
                        <div class="page-title-actions">
                            <div class="d-inline-block dropdown">
                                <a href="<?php echo base_url(); ?>/leads/service/new">
                                    <button type="button" class="btn btn-gradient-primary btn-shadow btn-wide">
                                        <span class="btn-icon-wrapper pr-1 opacity-7">
                                            <i class="fa fa-plus"></i>
                                        </span>
                                        Create Service
                                    </button>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="app-inner-layout app-inner-layout-page">
                <div class="app-inner-layout__wrapper">
                    <div class="app-inner-layout__content">
                        <div class="tab-content container-fluid">
                            <div class="tab-pane tabs-animation fade show active" id="new" role="tabpanel">
                                <div class="row justify-content-center m-0">
                                    <div class="col-lg-9">
                                        <?php
                                        if (trim($this->session->flashdata('alert')) != NULL) {

                                        ?>
                                            <div class="alert alert-<?php echo $this->session->flashdata('alert'); ?>">
                                                <?php echo $this->session->flashdata('alert_message');
                                                unset($_SESSION["alert_message"]); ?>
                                            </div>
                                        <?php
                                        }
                                        ?>
                                        <div class="main-card mb-3 card mt-4">
                                            <div class="card-body table-responsive">
                                                <table style="width: 100%;" id="enq_dTable" class="table table-hover table-striped table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Service Name</th>
                                                            <th>Service Group</th>
                                                            <!-- <th>Branch</th> -->
                                                            <th>Active Status</th>
                                                            <th>Created at</th>
                                                            <th>Updated at</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($services as $service) { ?>
                                                            <tr>
                                                                <td><?php echo $service["id"]; ?></td>
                                                                <td><?php echo $service["service_name"]; ?></td>
                                                                <td><?php echo $service["group_name"]; ?></td>
                                                                <!-- <td><?php echo $service["service_branch"]; ?></td> -->
                                                                <td><?php echo ($service["is_active"] == 1) ? 'Active' : "Inactive"; ?></td>
                                                                <td><?php echo $service["created_at"]; ?></td>
                                                                <td><?php echo $service["updated_at"]; ?></td>
                                                                <td>
                                                                    <a href="<?php echo base_url()?>/leads/service/edit?id=<?php echo $service["id"]; ?>"><button class="btn btn-primary" title="edit"><i class="fa fa-edit"></i></button></a> 
                                                                </td>
                                                            </tr>
                                                        <?php } ?>
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
    <script src="<?php echo base_url(); ?>global/vendor/datatables/js/jquery.dataTables.min.js"></script>
    <script src="<?php echo base_url(); ?>assets_new/node_modules/datatables.net-responsive/js/dataTables.responsive.min.js">
    </script>
    <script src="<?php echo base_url(); ?>assets_new/node_modules/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js">
    </script>
    <script src="<?php echo base_url(); ?>global/node_modules/select2/dist/js/select2.min.js"></script>

    <link rel="stylesheet" href="<?php echo base_url(); ?>assets_new/node_modules/datatables.net-responsive-bs4/css/responsive.bootstrap4.css">
    <script src="/global/js/plugins-init/select2-init.js"></script>
    <script>
        $("#enq_dTable").dataTable();
    </script>