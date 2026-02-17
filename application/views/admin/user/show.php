<div class="row page-titles mx-0">
    <div class="col-sm-6 p-md-0">
        <div class="welcome-text">
            <h4><?php echo $page_title; ?></h4>
            <span>Manage Users</span>
        </div>
    </div>
    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
        <a class="btn btn-primary waves-effect waves-light float-right"
            href="<?php echo base_url(); ?>admin/user/add/">
            <i class="mdi mdi-plus mr-2"></i> ADD NEW USER
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
    <strong>Error!</strong> <?php echo $this->session->flashdata('alert_danger'); ?>
</div>
<?php
}

if ($this->session->flashdata('alert_warning')) {
    ?>
<div class="alert alert-warning alert-dismissible fade show" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">×</span>
    </button>
    <strong>Warning!</strong> <?php echo $this->session->flashdata('alert_warning'); ?>
</div>
<?php
}
?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body table-responsive">
                <table id="datatable" class="table table-bordered dt-responsive nowrap"
                    style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                    <thead>
                        <tr>
                            <th>Sl.no</th>
                            <th>Emp Id</th>
                            <th>Pos Usr Id</th>
                            <th>Name</th>
                            <th>Contact</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
	                foreach ($users as $value) 
	                {
	                ?>
                        <tr>
                            <td><?php echo $i;$i++;?></td>
                            <td><strong><?php echo $value['employee_id'];?></strong></td>
                            <td><strong><?php echo $value['pos_user_id'];?></strong></td>
                            <td><strong><?php echo $value['first_name'];?></strong></td>
                            <td><strong><?php echo $value['email'];?></strong><br/><strong><?php echo $value['mobile'];?></strong></td>
                            <td><?php echo $value['role_name'];?></td>
                             <!-- <td><?php $lan=$value['language'];
                                    if($lan=='1')
                                    {
                                        ?>
                                <span class="badge badge-light-success">English</span>
                                <?php
                               }elseif($lan=='2')
                                    {
                                        ?>
                                <span class="badge badge-light-danger">عربي</span>
                                <?php    
                                 }else
                                 {   
                                 ?>
                                 <span class="badge badge-light-danger"></span>
                                 <?php 
                             }?>
                            </td> -->
                            <td><?php $statu=$value['is_active'];
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
                            <td><a href="<?php echo base_url(); ?>admin/user/edit/<?php echo $value['user_id'];?>"
                                    class="btn btn-sm btn-primary">Edit</a>&nbsp;<!-- <a
                                    href="<?php echo base_url(); ?>admin/user/status_change/<?php echo $value['user_id'];?>"
                                    class="btn btn-sm btn-primary">Change Status</a> --></td>
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