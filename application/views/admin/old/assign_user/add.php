<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box">
            <h4 class="page-title"><?php echo $page_title; ?>
                <a class="btn btn-primary waves-effect waves-light float-right"
                    href="<?php echo base_url(); ?>admin/assign_user/">
                    <i class="mdi mdi-plus mr-2"></i> View Assign User
                </a>
            </h4>
        </div>
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
                <form action="<?php echo base_url(); ?>admin/assign_user/add" method="post"
                    enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>User <span class="text-danger required">*</span></label>
                                <select name="user_id" id="user_id" class="select2 form-control mb-3 custom-select"
                                    style="width: 100%; height:36px;" required>


                                    <option value=""> -- Select User --</option>
                                    <?php
                                        foreach ($user as  $value) {
                                        ?>
                                    <option value="<?php echo $value->user_id; ?>">
                                        <?php echo $value->username; ?></option>
                                    <?php
                                        }
                                        ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Company <span class="text-danger required">*</span></label><br>
                                <select name="company_id[]" class="select2 mb-3 select2-multiple" style="width: 100%"
                                    multiple="multiple">
                                    <option value=""> -- Select Company --</option>
                                    <?php
                                        foreach ($company as  $value) {
                                        ?>
                                    <option value="<?php echo $value->id; ?>">
                                        <?php echo $value->company_name; ?></option>
                                    <?php
                                        }
                                        ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <button name="submit" type="submit" class="btn btn-primary">Create</button>

                </form>
            </div>
        </div>
    </div>
</div>
</div>