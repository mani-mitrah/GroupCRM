<div class="row page-titles mx-0">
    <div class="col-sm-6 p-md-0">
        <div class="welcome-text">
            <h4><?php echo $page_title; ?></h4>
            <span>Edit package service</span>
        </div>
    </div>
    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
        <a class="btn btn-primary waves-effect waves-light float-right"
            href="<?php echo base_url(); ?>leads/settings/pservices/">
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
                <form action="<?php echo base_url(); ?>leads/settings/pservices/edit/<?php echo $default['id'];?>" method="post">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Package Name <span class="text-danger required">*</span>
                                </label>
                                <select class="form-control" name="package_id">
                                    <option value="">--Select package --</option>
                                    <?php foreach ($packages as $key => $value) {
                                        ?>
                                        <option value="<?php echo $value['id'];?>" <?php echo ($value['id']==$default['package_id'])? 'selected': ''; ?>><?php echo $value['package_name']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Service Name <span class="text-danger required">*</span>
                                </label>
                                <select class="form-control" name="service_id">
                                    <option value="">--Select Service --</option>
                                    <?php foreach ($services as $key => $value) {
                                        ?>
                                        <option value="<?php echo $value['service_id']; ?>" <?php echo ($value['service_id']==$default['service_id'])? 'selected': '';?>><?php echo $value['service_name']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Government Fee <span class="text-danger required">*</span>
                                </label>
                                <input type="text" class="form-control" placeholder="Enter Government Fee" name="govt_fee" required="" value="<?php echo $default['govt_fee']; ?>">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Typing Fee <span class="text-danger required">*</span>
                                </label>
                                <input type="text" class="form-control" placeholder="Enter Typing Fee" name="typing_fee" required="" value="<?php echo $default['typing_fee']; ?>">
                            </div>
                        </div>
                    </div>
                    <input name="submit" type="submit" class="btn btn-primary" value="Create" />

                </form>
            </div>
        </div>
    </div>
</div>