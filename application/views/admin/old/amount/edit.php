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
                <?php if($this->session->flashdata('alert')) { ?>
                <div class="alert alert-<?php echo $this->session->flashdata('alert'); ?>">
                    <?php echo $this->session->flashdata('alert_message'); ?>
                </div>
                <?php } ?>
                <form action="<?php echo base_url();?>weqayati/amount/edit/<?php echo $default['sa_id'];?>" method="post">
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
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('do'); ?> <span class="text-danger required">*</span>
                                </label>
                               <select class="form-control" name="medical_ref" id="medical_ref" required="required" onchange="select_ref(this);">
                               <option value="">Select/يختار</option>
                               <option <?php if($default['medical_ref']==1){ echo "selected"; }?> value="1">Yes/نعم</option>
                               <option <?php if($default['medical_ref']==2){ echo "selected"; }?> value="2">No/لا</option>
                               </select>
                            </div>
                        </div>
                        <div class="col-md-6" id="sub_category1">
                        <div class="form-group">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('Sub Category'); ?> <span class="text-danger required">*</span>
                                </label>
                                <select class="form-control" name="sub_category" id="sub_category" required="required">
                                <option value="">Select Sub Category</option>
                           
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6" id="sub_category_two1">
                        <div class="form-group">
                                <label for="exampleInputEmail1">Sub Category Two<span class="text-danger required">*</span>
                                </label>
                                <select class="form-control" name="sub_category_two" id="sub_category_two">
                                <option value="">Select Sub Category Two</option>
                           
                                </select>
                            </div>
                        </div>
                   
                        <div class="col-md-6" id="gender">
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
                        <div class="col-md-6"  id="service_hours">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Service Hours<span class="text-danger required">*</span>
                                </label>
                              <select class="form-control" name="service_hours">
                              <option value="">Select Hours</option>
                              <?php foreach($service_hours as $sh){?>
                                <option <?php if($default['service_hours']==$sh->id){ echo "selected"; }?> value="<?php echo $sh->id;?>"><?php echo $sh->service_hour;?>/<?php echo $sh->service_hour_arabic;?></option>
                              <?php }?>
                              </select>
                            </div>
                        </div>
                        
                        <div class="col-md-6" id="amount">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Amount<span class="text-danger required">*</span>
                                </label>
                                <input type="number" class="form-control" name="amount" value="<?php echo $default['amount'];?>">
                            </div>
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
    $(document).ready(function() {
        $('#category').on('change', function() {
            var main_category = $(this).val();
            
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
        $.ajax({
        url:"<?php echo base_url('weqayati/attachment/get_sub_category')?>",
        method:"POST",
        type:'ajax',
        data:{category_id:category_id},
        success:function(data){
            var sub="<?php echo $default['sub_category'];?>";
            var result=JSON.parse(data);
            var selected="";
                        $.each(result, function(key, value) {
                            if(value.id===sub){

                                 var options='<option selected value="' + value.id +'">'+ value.sub_category_name +'   /  '+value.sub_cat_arabic+'</option>'; 
                            }else{
                                var options='<option value="' + value.id +'">'+ value.sub_category_name +'   /  '+value.sub_cat_arabic+'</option>';
                            }
                            $('select[name="sub_category"]').append(options);
                        });
       var category=$("#sub_category").val();
        $.ajax({
        url:"<?php echo base_url('weqayati/amount/get_sub_category_two')?>",
        method:"POST",
        type:'ajax',
        data:{category_id:category},
        success:function(data){
            var sub="<?php echo $default['sub_category_two'];?>";
            var result=JSON.parse(data);    
            var selected="";
                        $.each(result, function(key, value) {
                            if(value.id===sub){
                                 var options='<option selected value="' + value.id +'">'+ value.sub_categorytwo +'   /  '+value.sub_cattwo_arabic+'</option>'; 
                            }else{
                                var options='<option value="' + value.id +'">'+ value.sub_categorytwo +'   /  '+value.sub_cattwo_arabic+'</option>';
                            }
                            $('select[name="sub_category_two"]').append(options);
                        });
        },
        error:function(err){
            console.log(err);
        }
    });   

        },
        error:function(err){
            console.log(err);
        }
    });


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



});
</script>
<script type="text/javascript">
    var shows = $('#medical_ref').val();
     if (shows == 1) {
                $('#sub_category1').hide();
                $('#sub_category_two1').hide();
                $('#gender').hide();
                $('#service_hours').show();
                $('#amount').show();


            }

            if (shows == 2) {
                $('#ref_no').hide();
               $('#sub_category1').show();
                $('#sub_category_two1').show();
                $('#gender').show();
                $('#service_hours').show();
                $('#amount').show();

            }
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
                $('#ref_no').hide();
               $('#sub_category1').show();
                $('#sub_category_two1').show();
                $('#gender').show();
                $('#service_hours').show();
                $('#amount').show();

            }
        }
        
    </script>