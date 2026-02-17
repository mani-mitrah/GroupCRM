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
                    href="<?php echo base_url(); ?>weqayati/sub_category2/">
                    <i class="mdi mdi-plus mr-2"></i> View Sub Category
                </a>
            </h4>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-body">
    <form action="<?php echo base_url();?>weqayati/sub_category2/edit/<?php echo $rows->id;?>" method="post">

        <div class="row">

            <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1"> Category <span class="text-danger required">*</span>
                                </label>
                                <Select name="main_category" id=
                                "main_category" class="form-control pc-selectpicker>
                               <option value=""> -- category --</option>
                                    <?php
                            foreach($category as  $c) {
                            ?>
                                    <option value="<?php echo $c->id;?>"
                                        <?php if($c->id==$rows->category_id) {?> selected='selected'
                                        <?php }  ?>><?php echo $c->category_name;?><?php echo"    /     ";?><?php echo $c->category_arabic;?></option>
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
                                <Select name="sub_category" id="sub_category" class="form-control pc-selectpicker>
                              
                              <option value=""> -- Select Sub Category --</option>

                                 

                                                                                         
                                </Select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Sub Category Two <span class="text-danger required">*</span>
                                </label>
                                <input type="text" class="form-control" placeholder="Sub category Two" name="sub_categorytwo" required value="<?php echo $rows->sub_categorytwo ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1"> الفئة الفرعية الثانية <span class="text-danger required">*</span>
                                </label>
                                <input type="text" class="form-control" placeholder="الفئة الفرعية الثانية" name="sub_cattwo_arabic" required value="<?php echo $rows->sub_cattwo_arabic; ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Active</label>
                                
                                <select name="is_active" class="form-control pc-selectpicker">
                                    <option value="1" <?php echo ($rows->is_active=='1' )? 'selected': ''?>>Active   /   نشيط
                                    </option>
                                    <option value="0" <?php echo ($rows->is_active=='0' )? 'selected': ''?>> In-active    /    غير نشط
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
                            $('select[name="sub_category"]').append('<option value="' + value.id +'">'+ value.sub_category_name +'   /  '+value.sub_cat_arabic+'</option>');
                        });
                    }
                });
            }
            else{
                $('select[name="pgroup_sub"]').empty();
            }
        });
 
        var category_id=$("#main_category").val();
        var sub="<?php echo $rows->sub_category;?>";
        //alert(sub);
        $.ajax({
        url:"<?php echo base_url('weqayati/attachment/get_sub_category')?>",
        method:"POST",
        type:'ajax',
        data:{category_id:category_id},
        success:function(data){
            
            var result=JSON.parse(data);
            var selected="";
            // $('select[name="sub_category"]').empty();
            //         $("#sub_category").append("<option>--Select--</option>");
                        $.each(result, function(key, value) {

                            if(value.id===sub){
                                 var options='<option selected value="' + value.id +'">'+ value.sub_category_name +'   /  '+value.sub_cat_arabic+'</option>'; 
                            }else{
                                var options='<option value="' + value.id +'">'+ value.sub_category_name +'   /  '+value.sub_cat_arabic+'</option>';
                            }
                            $('select[name="sub_category"]').append(options);
                        });
        },
        error:function(err){
            console.log(err);
        }
    });
        

    });
</script>