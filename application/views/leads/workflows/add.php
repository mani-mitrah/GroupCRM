<div class="row page-titles mx-0">
    <div class="col-sm-6 p-md-0">
        <div class="welcome-text">
            <h4><?php echo $page_title; ?></h4>
            <span>Add new workflow</span>
        </div>
    </div>
    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
        <a class="btn btn-primary waves-effect waves-light float-right"
            href="<?php echo base_url(); ?>leads/settings/workflows/">
            <i class="mdi mdi-plus mr-2"></i> VIEW ALL
        </a>
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
                <form action="<?php echo base_url(); ?>leads/settings/workflows/add" method="post">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Workflow Name <span class="text-danger required">*</span>
                                </label>
                                <input type="text" class="form-control" placeholder="Enter workflow name" name="workflow_name" required="">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Workflow Description <span class="text-danger required">*</span>
                                </label>
                                <input type="text" class="form-control" placeholder="Enter workflow description" name="workflow_description" required="">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">For service <span class="text-danger required">*</span>
                                </label>
                                <select class="form-control" name="service_id">
                                    <option value="">--Select Service --</option>
                                    <?php foreach ($services as $key => $value) {
                                        ?>
                                        <option value="<?php echo $value['service_id']; ?>"><?php echo $value['service_name']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <input name="submit" type="submit" class="btn btn-primary" value="Create" />

                </form>
            </div>
        </div>
    </div>
</div>