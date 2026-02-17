<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box">
            <h4 class="page-title"><?php echo $page_title; ?>
                <a class="btn btn-primary waves-effect waves-light float-right"
                    href="<?php echo base_url(); ?>weqayati/service_hours/">
                    <i class="mdi mdi-plus mr-2"></i> View Service Hours
                </a>
            </h4>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <?php if ($this ->session ->flashdata('alert')) { ?>
                <div class="alert alert-<?php echo $this->session->flashdata('alert'); ?>">
                    <?php echo $this ->session ->flashdata('alert_message'); ?>
                </div>
                <?php } ?>
                <form action="<?php echo base_url(); ?>weqayati/service_hours/add" method="post">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Service Hours<span class="text-danger required">*</span>
                                </label>
                                <input type="text" class="form-control" placeholder="Service Hours" name="service_hour" required="">
                            </div>
                        </div>
                         <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">ساعات الخدمة<span class="text-danger required">*</span>
                                </label>
                                <input type="text" class="form-control" placeholder="ساعات الخدمة" name="service_hour_arabic" required="">
                            </div>
                        </div>
                    </div>
                    <button name="submit" type="submit" class="btn btn-primary"><?php echo $this->lang->line('Create'); ?></button>

                </form>
            </div>
        </div>
    </div>
</div>