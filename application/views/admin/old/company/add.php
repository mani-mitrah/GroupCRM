<div class="row page-titles mx-0">
    <div class="col-sm-6 p-md-0">
        <div class="welcome-text">
            <h4><?php echo $page_title; ?></h4>
            <span>Add company</span>
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
                <form action="<?php echo base_url(); ?>admin/company/add" method="post" enctype="multipart/form-data">
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
                                <label for="exampleInputEmail1">Company Logo </label><br>
                                <input type="file" name="company_logo" size="20" />
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Industry <span class="text-danger required">*</span></label>
                                <select name="industry" id="industry" class="form-control pc-selectpicker industry"
                                    required="">
                                    <option value=""> -- Select Industry --</option>
                                    <?php
			                        foreach ($industry as  $value) {
			                        ?>
                                    <option value="<?php echo $value->id; ?>">
                                        <?php echo $value->industry; ?></option>
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