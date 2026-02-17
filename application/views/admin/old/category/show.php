<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box">
            <h4 class="page-title"><?php echo $page_title; ?>
                <a class="btn btn-primary waves-effect waves-light float-right"
                    href="<?php echo base_url(); ?>weqayati/category/add/">
                    <i class="mdi mdi-plus mr-2"></i> Add New Category
                </a>
            </h4>
        </div>
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
                <table id="datatable" class="table table-bordered dt-responsive nowrap"
                    style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                    <thead>
                        <tr>
                            <th><?php echo $this->lang->line('Category Name'); ?></th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
			                foreach ($default as $value) 
			                {
			                ?>
                        <tr>
                            <td><strong><?php echo $value->category_name;?><?php echo "      /    ";?><?php echo $value->category_arabic;?></strong></td>
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
                            <td><a href="<?php echo base_url(); ?>weqayati/category/edit/<?php echo $value->id;?>"
                                    class="btn btn-sm btn-success">Edit</a>&nbsp;<a
                                    href="<?php echo base_url(); ?>weqayati/category/status_change/<?php echo $value->id;?>"
                                    class="btn btn-sm btn-danger">Change Status</a></td>
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