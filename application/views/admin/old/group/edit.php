<div class="row page-titles mx-0">
    <div class="col-sm-6 p-md-0">
        <div class="welcome-text">
            <h4><?php echo $page_title; ?></h4>
            <span>Edit group information</span>
        </div>
    </div>
    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
        <a class="btn btn-primary waves-effect waves-light float-right"
            href="<?php echo base_url(); ?>admin/master/group/">
            <i class="mdi mdi-plus mr-2"></i> View All
        </a>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <?php if($this->session->flashdata('alert')) { ?>
                <div class="alert alert-<?php echo $this->session->flashdata('alert'); ?>">
                    <?php echo $this->session->flashdata('alert_message'); ?>
                </div>
                <?php } ?>
                <form action="<?php echo base_url();?>admin/master/group/edit/<?php echo $default['g_id'];?>" method="post">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Group Name <span class="text-danger required">*</span>
                                </label>
                                <input type="text" class="form-control" placeholder="Enter Group Name" name="group_name" required value="<?php echo $default['group_name']; ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Status</label>
                                <select name="status" class="form-control pc-selectpicker">
                                    <option value="1" <?php echo ($default[ 'status']=='1' )? 'selected': ''?>>Active
                                    </option>
                                    <option value="0" <?php echo ($default[ 'status']=='0' )? 'selected': ''?>> In-active
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <button name="submit" type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>