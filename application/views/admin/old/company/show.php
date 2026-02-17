<div class="row page-titles mx-0">
    <div class="col-sm-6 p-md-0">
        <div class="welcome-text">
            <h4><?php echo $page_title; ?></h4>
            <span>Manage company information</span>
        </div>
    </div>
    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
        <a class="btn btn-primary waves-effect waves-light float-right"
            href="<?php echo base_url(); ?>admin/company/add/">
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
                        <div class="table table-responsive-md">
                            <table id="datatable" class="table table-bordered dt-responsive nowrap"
                                style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead>
                                    <tr>
                                        <th>COMPANY NAME</th>
                                        <th>LOGO</th>
                                        <th>STATUS</th>
                                        <th>ACTIONS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
            			                foreach ($default as $value) 
            			                {
            			                ?>
                                    <tr>
                                        <td><strong><?php echo $value->company_name;?></strong></td>
                                        <td> <?php if($value->company_logo != '') 
            		                      {
            		                        ?><img width="50" height="50" src="<?php echo $value->company_logo; ?>"></td>
                                        <?php }
            		                    else
            		                        {
            		                            ?>
                                        <img width="50" height="50" src="<?php echo base_url('uploads/basic/noimage.png'); ?>">
                                        <?php 
            		                        }?>
                                        <td><?php $statu=$value->is_active;
            			                                if($statu=='1')
            			                                {
            			                                    ?>
                                            <span class="badge badge-light-success">Active</span>
                                            <?php
            			                           }else
            			                                {
            			                                    ?>
                                            <span class="badge badge-light-danger">In-active</span>
                                            <?php    
            			                             }   
            			                             ?>
                                        </td>
                                        <td><a href="<?php echo base_url(); ?>admin/company/edit/<?php echo $value->id;?>"
                                                class="btn btn-sm btn-primary">Edit</a>&nbsp;<a
                                                href="<?php echo base_url(); ?>admin/company/status_change/<?php echo $value->id;?>"
                                                class="btn btn-sm btn-primary">Change Status</a></td>
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
    