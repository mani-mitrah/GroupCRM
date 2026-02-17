<div class="row page-titles mx-0">
    <div class="col-sm-6 p-md-0">
        <div class="welcome-text">
            <h4><?php echo $page_title; ?></h4>
            <span>Add company</span>
        </div>
    </div>
    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
        <a class="btn btn-primary waves-effect waves-light float-right"
                    href="<?php echo base_url(); ?>admin/assign_group/">
                    <i class="mdi mdi-plus mr-2"></i> ASSIGN GROUP
                </a>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <?php
				if($this->session->flashdata('alert'))
				{
					?>
                <div class="alert alert-<?php echo $this->session->flashdata('alert'); ?>">
                    <?php echo $this->session->flashdata('alert_message'); ?>
                </div>
                <?php
				}
				?>
                <form action="<?php echo base_url(); ?>admin/assign_group/add" method="post"
                    enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6">

                        <div class="form-group">
                                <label>Group <span class="text-danger required">*</span></label>
                                <select name="group_name" id="group_name" class="select2 form-control mb-3 custom-select"
                                    style="width: 100%; height:36px;" required onchange="get_company_users(this.value);">


                                    <option value=""> -- Select Group --</option>
                                    <?php
                                        foreach ($group as  $value) {
                                        ?>
                                    <option value="<?php echo $value->g_id; ?>">
                                        <?php echo $value->group_name; ?></option>
                                    <?php
                                        }
                                        ?>
                                </select>
                        </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                            <label>Users <span class="text-danger required">*</span></label>
                                <select name="user_id[]" id="user_id" class="select2 form-control mb-3 custom-select users"
                                    style="width: 100%; height:36px;" required multiple="multiple">


                                    <option value=""> -- Select User --</option>
                                    <!-- <?php
                                        foreach ($user as  $value) {
                                        ?>
                                    <option value="<?php echo $value->user_id; ?>">
                                        <?php echo $value->username; ?></option>
                                    <?php
                                        }
                                        ?> -->
                                </select>
                            </div>
                        </div>
                    </div>
                    <button name="submit" type="submit" class="btn btn-primary">Create</button>

                </form>
            </div>
        </div>
    </div>
</div>
</div>

<script>
function get_company_users(group_id){
    $.ajax({
        url:"<?php echo site_url('admin/Assign_group/get_group_user');?>",
        method:"POST",
        data:{ group_id:group_id},
        type:'ajax',
        success:function(res){
            var result=JSON.parse(res);
            console.log(result);
            $('select[name="user_id[]"]').empty();
                    $(".users").append("");
                        $.each(result, function(key, value) {
                            var name=value.first_name+' '+value.last_name;
                            $('select[name="user_id[]"]').append('<option value="' + value.user_id +'">'+ name +'</option>');
                        });
        },err:function(error){
            console.log(error);
        }
    });
}
</script>