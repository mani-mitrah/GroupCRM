<div class="row page-titles mx-0">
    <div class="col-sm-6 p-md-0">
        <div class="welcome-text">
            <h4><?php echo $page_title; ?></h4>
            <span>Group information</span>
        </div>
    </div>
    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
        <a class="btn btn-primary waves-effect waves-light float-right"
                    href="<?php echo base_url(); ?>admin/assign_group_company/">
                    <i class="mdi mdi-plus mr-2"></i> ASSIGN GROUP COMPANY
                </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <?php
				if($this->session->flashdata('alert'))
				{
					?>
                <div class="alert alert-<?php echo $this->session->flashdata('alert'); ?>">
                    <?php echo $this->session->flashdata('alert_message'); ?>
                </div>
                <?php
				}
				?>
                <form action="<?php echo base_url();?>admin/assign_group/edit/<?php echo $default['gu_id'];?>" method="post"
                    enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Group <span class="text-danger required">*</span></label>
                                <?php //print_r($default);?>
                                <select name="group_id" id="group_id" class="select2 form-control mb-3 custom-select"
                                    style="width: 100%; height:36px;" required>
                                    <option value=""> -- Select Group --</option>
                                    <?php
                                    
                            foreach($group as  $row) {
                            ?>
                                    <option value="<?php echo $row->g_id;?>"
                                        <?php if($row->g_id==$default['group_id']) {?> selected='selected'
                                        <?php }  ?>><?php echo $row->group_name;?></option>
                                    <?php
                            }
                            ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                            <label>Users <span class="text-danger required">*</span></label>
                                <select name="user_id[]" id="user_id" class="select2 form-control mb-3 custom-select users"
                                    style="width: 100%; height:36px;" required multiple="multiple">


                                    <option value=""> -- Select User --</option>
                                    <?php
                                        foreach ($user as  $value) {
                                        ?>
                                    <option value="<?php echo $value->user_id; ?>"
                                    <?php if($value->user_id==$default['user_id']) {?> selected='selected' <?php }  ?>
                                    >
                                        <?php echo $value->username; ?></option>
                                    <?php
                                        }
                                        ?>
                                </select>
                            </div>
                        </div>
                        <button name="submit" type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>