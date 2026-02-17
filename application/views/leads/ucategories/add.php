<div class="row page-titles mx-0">
    <div class="col-sm-6 p-md-0">
        <div class="welcome-text">
            <h4><?php echo $page_title; ?></h4>
            <span>Add new user category</span>
        </div>
    </div>
    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
        <a class="btn btn-primary waves-effect waves-light float-right"
            href="<?php echo base_url(); ?>leads/settings/ucategories/">
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
                <form action="<?php echo base_url(); ?>leads/settings/ucategories/add" method="post">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="exampleInputEmail1">User<span class="text-danger required">*</span>
                                </label>
                                <select class="form-control" name="user_id">
                                    <option value="">--Select User --</option>
                                    <?php foreach ($users as $key => $value) {
                                        ?>
                                        <option value="<?php echo $value['user_id']; ?>"><?php echo $value['first_name'].' '.$value['last_name']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Select Categories <span class="text-danger required">*</span>
                                </label><br />
                                <?php 
                                foreach ($categories as $key => $value) 
                                {
                                ?>
                                <div class="form-check form-check-inline">
                                  <input name="chkid[]" class="form-check-input" type="checkbox" id="c<?php echo $value['category_id']; ?>" value="<?php echo $value['category_id']; ?>">
                                  <label class="form-check-label" for="c<?php echo $value['category_id']; ?>"><?php echo strtoupper($value['category_name']); ?></label>
                                </div>
                                <?php 
                                } 
                                ?>
                            </div>
                        </div>
                    </div>
                    <input name="submit" type="submit" class="btn btn-primary" value="Create" />

                </form>
            </div>
        </div>
    </div>
</div>