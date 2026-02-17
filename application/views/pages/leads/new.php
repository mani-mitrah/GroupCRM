<div class="row page-titles mx-0">
    <div class="col-sm-6 p-md-0">
        <div class="welcome-text">
            <h4>New Lead</h4>
            <span>Ontime Leads Management</span>
        </div>
    </div>
    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
        <a class="btn btn-primary waves-effect waves-light float-right"
            href="<?php echo base_url(); ?>leads/baraha/">
            <i class="mdi mdi-plus mr-2"></i> VIEW LEADS
        </a> 
    </div>
</div>
<div class="card">
    <div class="card-body">
        <div class="row">
             <div class="col-md-12">
                <div class="form-group">
                    <label>Select companies &nbsp;<span class="text-danger required">*</span></label><br />
                    <?php
                    foreach ($companies as $key => $value) 
                    {
                        ?>
                        <div class="form-check form-check-inline">
                            <input type="checkbox" class="form-check-input" checked="" id="c<?php echo $value['id'];?>">
                            <label class="form-check-label" for="c<?php echo $value['id'];?>"><?php echo $value['company_name'];?></label>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div> 
            <div class="col-md-12">
                <div class="form-group">
                    <label>Lead Name&nbsp;<span class="text-danger required">*</span></label>
                    <input type="text" class="form-control" required="" name="lead_name">
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label>Lead Transaction&nbsp;<span class="text-danger required">*</span></label>
                    <input type="text" class="form-control" required="" name="lead_transaction">
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label>Lead Contact Number&nbsp;<span class="text-danger required">*</span></label>
                    <input type="text" class="form-control" required="" name="lead_contact">
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label>Lead Email Address&nbsp;<span class="text-danger required">*</span></label>
                    <input type="text" class="form-control" required="" name="lead_email">
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label>Remarks(in English/Arabic)&nbsp;<span class="text-danger required">*</span></label>
                    <textarea rows="7" class="form-control" required="" name="lead_remarks"></textarea>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <input type="hidden" name="lead_created_by" value="<?php echo $this->auth_user_id; ?>">
                    <button class="btn btn-primary" name="submit">CREATE LEAD</button>
                </div>
            </div>
        </div>
    </div>
</div>