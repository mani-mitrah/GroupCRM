<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box">
            <h4 class="page-title"><?php echo $page_title; ?>
                <a class="btn btn-primary waves-effect waves-light float-right"
                    href="<?php echo base_url(); ?>weqayati/attachment/add/">
                    <i class="mdi mdi-plus mr-2"></i> Add Attachment
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
                            <th>S.no</th>
                            <th>Category</th>
                            <th>Sub Category</th>
                            <th>Gender</th>
                            <th>Attachment No</th>
                            <th>Labels</th>
                            <th>Action</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
			                foreach ($default as $key=>$value) 
			                {
			                ?>
                        <tr>
                            <td><strong><?php echo $key+1;?></strong></td>
                            <td><?php echo $value->category_name;?></td>
                            <td><?php echo $value->sub_category_name;?></td>
                            <td><?php if($value->gender==1){
                                echo "Male";
                            }else{
                                echo "Female";
                            }?></td>
                            <td><?php echo $value->attachment_number;?></td>
                            <td><?php echo $value->labels;?></td>
                            <td><a href="<?php echo site_url('weqayati/attachment/edit/'.$value->a_id);?>"><button type="button" class="btn btn-sm btn-success">Edit</button></a></td>
                            <td><a href="<?php echo site_url('weqayati/attachment/delete/'.$value->a_id);?>"><button type="button" class="btn btn-sm btn-danger">Delete</button></a></td>
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