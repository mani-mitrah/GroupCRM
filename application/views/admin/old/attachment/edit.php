<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box">
            <h4 class="page-title"><?php echo $page_title; ?>
                <a class="btn btn-primary waves-effect waves-light float-right"
                    href="<?php echo base_url(); ?>weqayati/attachment/">
                    <i class="mdi mdi-plus mr-2"></i> View attachments
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
                <form action="<?php echo base_url();?>weqayati/attachment/edit/<?php echo $default['a_id'];?>" method="post">
                <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('Category Name'); ?> <span class="text-danger required">*</span>
                                </label>
                                <select class="form-control" name="category" id="category" required="required">
                                <option value="">Select Category</option>
                                <?php foreach($category as $c){?>
                                <option <?php if($default['category']==$c->id){ echo "selected"; }?> value="<?php echo $c->id;?>"><?php echo $c->category_name;?></option>
                                <?php }?>
                                </select>
                                <?php if (form_error('category')) {?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                            <?php echo form_error('category'); ?>
                        </div>
                        <?php }?>
                                <!-- <input type="text" class="form-control" placeholder="<?php echo $this->lang->line('Category Name'); ?>" name="category" required=""> -->
                            </div>
                        </div>
                        <div class="col-md-6">
                        <div class="form-group">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('Sub Category'); ?> <span class="text-danger required">*</span>
                                </label>
                                <select class="form-control" name="sub_category" id="sub_category" required="required">
                                <!-- <option value="">Select Sub_category</option>
                                <?php foreach($sub_category as $sc){?>
                                <option <?php if($default['sub_category']==$sc->id){ echo "selected"; }?> value="<?php echo $sc->id;?>"><?php echo $sc->sub_category_name;?></option>
                                <?php }?> -->
                                </select>
                            </div>
                        </div>
                   
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('Gender'); ?> <span class="text-danger required">*</span>
                                </label>
                               <select class="form-control" name="gender" required="required">
                               <option value="">Select Gender</option>
                               <option <?php if($default['gender']==1){ echo "selected"; }?> value="1">Male</option>
                               <option <?php if($default['gender']==2){ echo "selected"; }?> value="2">Female</option>
                               </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">جنس تذكير أو تأنيث<span class="text-danger required">*</span>
                                </label>
                                <select class="form-control" name="gender_arabic" required="required">
                               <option value="">حدد نوع الجنس</option>
                               <option <?php if($default['gender_arabic']==1){ echo "selected"; }?> value="1">ذكر</option>
                               <option <?php if($default['gender_arabic']==2){ echo "selected"; }?> value="2">أنثى</option>
                               </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                        <div class="row">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Number Of Attachments<span class="text-danger required">*</span>
                                </label>
                                <input value="<?php echo $default['attachment_number'];?>" type="number" class="form-control" name="no_of_attachments" oninput="append_div(this.value);" required="required">
                            </div>
                        </div>
                        </div>
                        <div class="col-md-12" id="attachments">
                        <?php $alabels=explode(',', $labels);
                        foreach($alabels as $kety=>$l){?>
                        <div class="row"><div class="col-md-6"><div class="form-group">
                        <label for="exampleInputEmail1">Label<?php echo $kety+1;?><span class="text-danger required">*</span></label>
                        <input type="text" class="form-control" name="label[]" value="<?php echo $l;?>">
                         </div></div>
                         <?php }?>
                         <?php $alabel_arabic=explode(',', $labels_arabic);
                         foreach($alabel_arabic as $key=>$al){?>
                         <div class="col-md-6">
                         <div class="form-group">
                         <label for="exampleInputEmail1">ضع الكلمة المناسبة<?php echo $key+1?><span class="text-danger required">*</span></label>
                         <input type="text" class="form-control" name="label_arabic[]" value="<?php echo $al;?>"> 
                         </div>
                         </div>
                         </div>
                         <?php }?>

                       
                        </div>
                        </div>
                    </div>
                    <button name="submit" type="submit" class="btn btn-primary">Edit</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

$(function(){

    $('#category').on('change', function() {
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


    var category_id=$("#category").val();
    var sub="<?php echo $default['sub_category'];?>";
    
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

                            if(value.id==sub){
                                 var options='<option selected value="' + value.id +'">'+ value.sub_category_name +'   /  '+value.sub_cat_arabic+'</option>'; 
                            }else{
                                var options='<option  value="' + value.id +'">'+ value.sub_category_name +'   /  '+value.sub_cat_arabic+'</option>';
                            }

                            $('#sub_category').append(options);
                        });
        },
        error:function(err){
            console.log(err);
        }
    });
});

</script>