<div class="row page-titles mx-0">
    <div class="col-sm-6 p-md-0">
        <div class="welcome-text">
            <h4><?php echo $page_title; ?></h4>
            <span>Manage Package Services</span>
        </div>
    </div>
    <!-- <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
        <a class="btn btn-primary waves-effect waves-light float-right"
            href="<?php echo base_url(); ?>leads/settings/pservices/add/">
            <i class="mdi mdi-plus mr-2"></i> ADD NEW
        </a>
    </div> -->
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
                <table id="datatable" class="table table-bordered dt-responsive nowrap"
                    style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Package Name</th>
                            <th>Service Name</th>
                            <th>Govt Fee</th>
                            <th>Typing Fee</th>
                            <!-- <th>Actions</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php
			                foreach ($default as $value) 
			                {
			             ?>
                        <tr>
                            <td><?php echo $value['pservice_id'];?></td>
                            <td><?php echo $value['package_name'];?></td>
                            <td><strong><?php echo $value['service_name'];?></strong></td>
                            <td>AED <?php echo $value['govt_fee'];?></td>
                            <td>AED <?php echo $value['typing_fee'];?></td>
                            <!-- <td><a href="<?php echo base_url(); ?>leads/settings/pservices/edit/<?php echo $value['pservice_id'];?>" class="btn btn-sm btn-primary">Edit</a>&nbsp;<a href="<?php echo base_url(); ?>leads/settings/pservices/delete/<?php echo $value['pservice_id'];?>" class="btn btn-sm btn-primary">Delete</a></td> -->
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