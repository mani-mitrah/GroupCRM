<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box">
            <h4 class="page-title"><?php echo $page_title; ?>
                <a class="btn btn-primary waves-effect waves-light float-right"
                    href="<?php echo base_url(); ?>admin/assign_group_company/">
                    <i class="mdi mdi-plus mr-2"></i> Assign Group Company
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
                <form action="<?php echo base_url(); ?>admin/assign_group_company/add" method="post"
                    enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6">

                        <div class="form-group">
                                <label>Group <span class="text-danger required">*</span></label>
                                <select name="group_name" id="group_name" class="select2 form-control mb-3 custom-select"
                                    style="width: 100%; height:36px;" required>


                                    <option value=""> -- Select Group --</option>
                                    <?php
                                        foreach ($group as  $value) {
                                        ?>
                                    <option value="<?php echo $value->g_id; ?>">
                                        <?php echo $value->group_name; ?></option>
                                    <?php
                                        }
                                        ?>
                                </select>
                        </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                            <label>Company <span class="text-danger required">*</span></label>
                                <select name="company_id[]" id="company_id" class="select2 form-control mb-3 custom-select"
                                    style="width: 100%; height:36px;" required multiple="multiple">


                                    <option value=""> -- Select company --</option>
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