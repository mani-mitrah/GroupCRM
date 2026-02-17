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
                <form action="<?php echo base_url();?>admin/assign_group_company/edit/<?php echo $default['gc_id'];?>" method="post"
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
                                <label>Company <span class="text-danger required">*</span></label><br>
                                <select name="company_id[]" id="company_id" class="select2 mb-3 select2-multiple" style="width: 100%"
                                    multiple="multiple">
                                    <option value=""> -- Select Company --</option>
                                    <?php
                                        foreach ($company as  $row) {
                                        ?>
                                    <option value="<?php echo $row->id;?>"
                                        <?php if($row->id==$default['company_id']) {?> selected='selected' <?php }  ?>>
                                        <?php echo $row->company_name;?></option>
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