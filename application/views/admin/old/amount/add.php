<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box">
            <h4 class="page-title"><?php echo $page_title; ?>
                <a class="btn btn-primary waves-effect waves-light float-right"
                    href="<?php echo base_url(); ?>weqayati/amount/">
                    <i class="mdi mdi-plus mr-2"></i> View Amount
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
                <form action="<?php echo base_url(); ?>weqayati/amount/add" method="post">
                <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('Category Name'); ?> <span class="text-danger required">*</span>
                                </label>
                                <select class="form-control" name="category" id="category" required="required" onchange="get_subcategory(this.value);">
                                <option value="">Select Category</option>
                                <?php foreach($category as $c){?>
                                <option value="<?php echo $c->id;?>"><?php echo $c->category_name;?>/<?php echo $c->category_arabic;?></option>
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
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('do'); ?> <span class="text-danger required">*</span>
                                </label>
                                <select class="form-control" name="medical_ref" id="medical_ref" required="required" onchange="select_ref(this);">
                                <option value="0">Select/يختار</option>
                                 <option value="1">Yes/نعم</option>
                                 <option value="2">No/لا</option>
                                
                                </select>
                                <!-- <input type="text" class="form-control" placeholder="<?php echo $this->lang->line('Category Name'); ?>" name="sub_category" required=""> -->
                            </div>
                        </div>

                        <div class="col-md-6" id="sub_category1">
                        <div class="form-group">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('Sub Category'); ?> <span class="text-danger required">*</span>
                                </label>
                                <select class="form-control" name="sub_category" id="sub_category" required="required" onchange="get_subcategory_two(this.value);">
                                <option value="">Select Sub Category</option>
                                
                                </select>
                                <!-- <input type="text" class="form-control" placeholder="<?php echo $this->lang->line('Category Name'); ?>" name="sub_category" required=""> -->
                            </div>
                        </div>

                        <div class="col-md-6" id="sub_category_two1">
                        <div class="form-group">
                                <label for="exampleInputEmail1">Sub Category Two <span class="text-danger required">*</span>
                                </label>
                                <select class="form-control" name="sub_category_two" id="sub_category_two">
                                <option value="">Select Sub Category Two</option>
                                
                                </select>
                                <!-- <input type="text" class="form-control" placeholder="<?php echo $this->lang->line('Category Name'); ?>" name="sub_category" required=""> -->
                            </div>
                        </div>
                   
                        <div class="col-md-6" id="gender">
                            <div class="form-group">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('Gender'); ?> <span class="text-danger required">*</span>
                                </label>
                               <select class="form-control" name="gender">
                               <option value="">Select Gender</option>
                               <option value="1">Male/ذكر</option>
                               <option value="2">Female/أنثى</option>
                               </select>
                            </div>
                        </div>
                        <!-- <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">جنس تذكير أو تأنيث<span class="text-danger required">*</span>
                                </label>
                                <select class="form-control" name="gender_arabic" required="required">
                               <option value="">حدد نوع الجنس</option>
                               <option value="1">ذكر</option>
                               <option value="2">أنثى</option>
                               </select>
                            </div>
                        </div> -->
                        <div class="col-md-6" id="service_hours">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Service Hours<span class="text-danger required">*</span>
                                </label>
                              <select class="form-control" name="service_hours">
                              <option value="">Select Hours</option>
                              <?php foreach($service_hours as $sh){?>
                                <option value="<?php echo $sh->id;?>"><?php echo $sh->service_hour;?>/<?php echo $sh->service_hour_arabic;?></option>
                              <?php }?>
                              </select>
                            </div>
                        </div>
                        <div class="col-md-6" id="amount">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Amount<span class="text-danger required">*</span>
                                </label>
                                <input type="number" class="form-control" name="amount">
                            </div>
                        </div>
                        <div class="col-md-12" id="attachments">
                        
                        </div>
                    </div>
                    <button name="submit" type="submit" class="btn btn-primary"><?php echo $this->lang->line('Create'); ?></button>

                </form>
            </div>
        </div>
    </div>
</div>

<script>
function append_div(num){
    var wrapper=$("#attachments");
                $("#attachments").html('');
               if($.isNumeric(num)){
                   var n1=1;
                for(var i=0;i<num;i++){
                    $(wrapper).append('<div class="row"><div class="col-md-6"><div class="form-group"><label for="exampleInputEmail1">Label'+n1+'<span class="text-danger required">*</span></label><input type="text" class="form-control" name="label[]"> </div></div><div class="col-md-6"><div class="form-group"><label for="exampleInputEmail1">ضع الكلمة المناسبة'+n1+'<span class="text-danger required">*</span></label><input type="text" class="form-control" name="label_arabic[]"> </div></div></div>');
                    n1++;
                }
               }
}

function get_subcategory(category_id){
    $.ajax({
        url:"<?php echo base_url('weqayati/attachment/get_sub_category')?>",
        method:"POST",
        type:'ajax',
        data:{category_id:category_id},
        success:function(data){
            var result=JSON.parse(data);
            $('select[name="sub_category"]').empty();
                    $("#sub_category").append("");
                        $.each(result, function(key, value) {
                            $('select[name="sub_category"]').append('<option value="' + value.id +'">'+ value.sub_category_name +'   /  '+value.sub_cat_arabic+'</option>');
                        });
        },
        error:function(err){
            console.log(err);
        }
    });
}
function get_subcategory_two(category_id){
    $.ajax({
        url:"<?php echo base_url('weqayati/amount/get_sub_category_two')?>",
        method:"POST",
        type:'ajax',
        data:{category_id:category_id},
        success:function(data){
            var result=JSON.parse(data);
            $('select[name="sub_category_two"]').empty();
                    $("#sub_category_two").append("<option>--Select--</option>");
                        $.each(result, function(key, value) {
                            $('select[name="sub_category_two"]').append('<option value="' + value.id +'">'+ value.sub_categorytwo +'   /  '+value.sub_cattwo_arabic+'</option>');
                        });
        },
        error:function(err){
            console.log(err);
        }
    });
}
</script>

    <script type="text/javascript">
        function select_ref(data) {
            let selected_category = data.value;
            if (selected_category == 1) {
                $('#sub_category1').hide();
                $('#sub_category_two1').hide();
                $('#gender').hide();
                $('#service_hours').show();
                $('#amount').show();


            }

            if (selected_category == 2) {
               $('#sub_category1').show();
                $('#sub_category_two1').show();
                $('#gender').show();
                $('#service_hours').show();
                $('#amount').show();

            }
        }
        
    </script>