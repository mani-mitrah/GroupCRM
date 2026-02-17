<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box">
            <h4 class="page-title"><?php echo $page_title; ?>
                <a class="btn btn-primary waves-effect waves-light float-right"
                    href="<?php echo base_url(); ?>weqayati/category/">
                    <i class="mdi mdi-plus mr-2"></i> <?php echo $this->lang->line('Category'); ?>
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
                <form action="<?php echo base_url(); ?>weqayati/category/add" method="post">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('Category Name'); ?> <span class="text-danger required">*</span>
                                </label>
                                <input type="text" class="form-control" placeholder="<?php echo $this->lang->line('Category Name'); ?>" name="category" required="">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">اسم التصنيف<span class="text-danger required">*</span>
                                </label>
                                <input type="text" class="form-control" placeholder="اسم التصنيف" name="category_arabic" required="">
                            </div>
                        </div>
                    </div>
                    <button name="submit" type="submit" class="btn btn-primary"><?php echo $this->lang->line('Create'); ?></button>

                </form>
            </div>
        </div>
    </div>
</div>