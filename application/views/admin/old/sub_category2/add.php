<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box">
            <h4 class="page-title"><?php echo $page_title; ?>
            <a class="btn btn-primary waves-effect waves-light float-right"
            href="<?php echo base_url(); ?>weqayati/sub_category2/">
            <i class="mdi mdi-plus mr-2"></i> <?php echo " view sub category"; ?>
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

                <form action="<?php echo base_url(); ?>weqayati/sub_category2/add" method="post">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="exampleInputEmail1"><?php echo "Category Name"; ?> <span class="text-danger required">*</span>
                                </label>
                                   <select name="main_category" id="main_category" class="form-control" required>
                                  <option value=""> -- Select Category --</option>

                                  <?php
                                  foreach($m_category as  $value) {

                                    ?>
                                    <option value="<?php echo $value->id;?>"><?php echo $value->category_name;?><?php echo "    /    ";?><?php echo $value->category_arabic;?></option>
                                    <?php
                                }
                                ?>
                            </select>

                        </div>

                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="exampleInputEmail1"><?php echo "Sub Category Name"; ?> <span class="text-danger required">*</span>
                            </label>
                            <select name="sub_category" id="sub_category" class="form-control" required>
                                <option value=""> -- Select Sub Category --</option>

                                <?php
                                foreach($m_sub_category as  $value) {
                                    
                                    ?>
                                    <option value="<?php echo $value->id;?>"><?php echo $value->sub_category_name;?><?php echo "    /    ";?><?php echo $value->sub_category_name;?></option>
                                    <?php
                                }
                                ?>

                            </select>

                        </div>

                    </div>
                    <div class="col-md-3">
                        <div class="form-group">

                           <label for="exampleInputEmail1"><?php echo "Sub Category Two Name"; ?> <span class="text-danger required">*</span>
                           </label>
                           <input type="text" class="form-control" placeholder="Sub category Two" name="sub_categorytwo" required="">

                       </div>
                   </div>


                   <div class="col-md-3" >
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo "تصنيف فرعي"; ?> <span class="text-danger required">*</span>
                        </label>
                        <input type="text" class="form-control" placeholder="  تصنيف فرعي" name="sub_cattwo_arabic" required="">
                    </div>
                </div>
            </div>
            <button name="submit" type="submit" class="btn btn-primary"><?php echo $this->lang->line('Create'); ?></button>

        </form>
    </div>
</div>
</div>
</div>
<script type="text/javascript">


    $(document).ready(function() {
        $('#main_category').on('change', function() {
            var main_category = $(this).val();
            //alert(main_category);
          
              if(main_category) {
                $.ajax({
                    url:"<?php echo site_url('weqayati/sub_category2/get_subcategory');?>",
                    type: "post",
                    data: {main_category: main_category},
                    dataType: "json",
                    success:function(data) {
                    $('select[name="sub_category"]').empty();
                    $("#sub_category").append("<option>--Select--</option>");
                        $.each(data, function(key, value) {
                            $('select[name="sub_category"]').append('<option value="' + value.id +'">'+ value.sub_category_name +'   /   '+  value.sub_cat_arabic+'</option>');
                        });
                    }
                });
            }
            else{
                $('select[name="pgroup_sub"]').empty();
            }
        });
 
        

    });
</script>