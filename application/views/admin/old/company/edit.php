<div class="row page-titles mx-0">
    <div class="col-sm-6 p-md-0">
        <div class="welcome-text">
            <h4><?php echo $page_title; ?></h4>
            <span>Edit company information</span>
        </div>
    </div>
    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
        <a class="btn btn-primary waves-effect waves-light float-right"
                    href="<?php echo base_url(); ?>admin/company/manage/">
                    <i class="mdi mdi-plus mr-2"></i> VIEW ALL
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
                <form action="<?php echo base_url();?>admin/company/edit/<?php echo $default['id'];?>" method="post"
                    enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Company Name <span
                                        class="text-danger required">*</span></label>
                                <input type="text" class="form-control" placeholder="Enter Company Name"
                                    name="company_name" required value="<?php echo $default['company_name']; ?>">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Company Url <span
                                        class="text-danger required">*</span></label>
                                <input type="text" class="form-control" placeholder="Enter Company Url" name="website"
                                    required value="<?php echo $default['website']; ?>">
                            </div>
                        </div>
                    </div>
                    <div class="row">

                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Industry <span class="text-danger required">*</span></label>
                                <select name="industry" id="industry" class="form-control pc-selectpicker industry"
                                    required>
                                    <option value=""> -- Select Industry --</option>
                                    <?php
                            foreach($industry as  $row) {
                            ?>
                                    <option value="<?php echo $row->id;?>" <?php if($row->id==$default['industry']) {?>
                                        selected='selected' <?php }  ?>><?php echo $row->industry;?></option>
                                    <?php
                            }
                            ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Company Logo </label><br>
                                
                                <input type="file" name="company_logo" size="20" />
                           
                                <?php if($default['company_logo']!= '') { ?>

                                <img src="<?php echo $default['company_logo'] ?>" width="50px" height="50px">

                               <?php } else{?>
                              <img src="<?php echo base_url('uploads/basic/noimage.png'); ?>" width="50px" height="50px">
                               <?php } ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">

                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Active</label>
                                <select name="is_active" class="form-control pc-selectpicker">
                                    <option value="1" <?php echo ($default['is_active'] == '1')?'selected':''?>>Active
                                    </option>
                                    <option value="0" <?php echo ($default['is_active'] == '0')?'selected':''?>>
                                        In-active</option>
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