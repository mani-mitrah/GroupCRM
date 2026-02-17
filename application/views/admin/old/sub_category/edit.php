<?php 
foreach($default as $rows)
{

}
?>

<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box">
            <h4 class="page-title"><?php echo $page_title; ?>
                <a class="btn btn-primary waves-effect waves-light float-right"
                    href="<?php echo base_url(); ?>weqayati/sub_category/">
                    <i class="mdi mdi-plus mr-2"></i> View Sub Category
                </a>
            </h4>
        </div>

    </div>
</div>
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <?php if($this->session->flashdata('alert')) { ?>
                <div class="alert alert-<?php echo $this->session->flashdata('alert'); ?>">
                    <?php echo $this->session->flashdata('alert_message'); ?>
                </div>
                <?php } ?>
                <form action="<?php echo base_url();?>weqayati/sub_category/edit/<?php echo $rows->id;?>" method="post">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1"> Category <span class="text-danger required">*</span>
                                </label>
                                <Select name="main_category" class="form-control pc-selectpicker>
                               <option value=""> -- category --</option>
                                    <?php
                            foreach($category as  $c) {

                            ?>
                                    <option value="<?php echo $c->id;?>"
                                        <?php if($c->id==$rows->category_id) {?> selected='selected'
                                        <?php }  ?>><?php echo $c->category_name;?><?php echo "   /    ";?><?php echo $c->category_arabic;?></option>
                                    <?php
                            }
                            ?>                                    
                                </Select>
                                
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Sub Category <span class="text-danger required">*</span>
                                </label>
                                <input type="text" class="form-control" placeholder="<?php echo $this->lang->line('Category Name'); ?>" name="sub_category" required value="<?php echo $rows->sub_category_name ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1"> تصنيف فرعي <span class="text-danger required">*</span>
                                </label>
                                <input type="text" class="form-control" placeholder="تصنيف فرعي" name="sub_cat_arabic" required value="<?php echo $rows->sub_cat_arabic; ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Active</label>
                                <select name="is_active" class="form-control pc-selectpicker">
                                    <option value="1" <?php echo ($rows->is_active=='1' )? 'selected': ''?>>Active  /   نشيط
                                    </option>
                                    <option value="0" <?php echo ($rows->is_active=='0' )? 'selected': ''?>> In-active   /   غير نشط
                                    </option>
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