<script type="text/javascript">
    $( document ).ready(function() {
        $('#existing_block').hide();
    });
</script>
<div class="row page-titles mx-0">
    <div class="col-sm-6 p-md-0">
        <div class="welcome-text">
            <h4><?php echo $page_title; ?></h4>
            <span>Add services to workflow</span>
        </div>
    </div>
    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
        <a class="btn btn-primary waves-effect waves-light float-right"
            href="<?php echo base_url(); ?>leads/settings/wentries/">
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
                <form action="<?php echo base_url(); ?>leads/settings/wentries/add" method="post">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Select workflow <span class="text-danger required">*</span>
                                </label>
                                <select class="form-control" name="workflow_id" onchange="javascript:exisiting_services(this.value);">
                                    <option value="">--Select workflow --</option>
                                    <?php foreach ($workflows as $key => $value) {
                                        ?>
                                        <option value="<?php echo $value['id']; ?>"><?php echo $value['workflow_name']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="form-group" id="existing_block">
                                <label for="exampleInputEmail1">Already existing services</label><br />
                                <div id="existing_items"></div>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Select service <span class="text-danger required">*</span>
                                </label>
                                <select class="form-control" name="target_service_id">
                                    <option value="">--Select Service --</option>
                                    <?php foreach ($services as $key => $value) {
                                        ?>
                                        <option value="<?php echo $value['service_id']; ?>"><?php echo $value['service_name']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <input name="submit" type="submit" class="btn btn-primary" value="Add to workflow" />

                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function exisiting_services(workflow_id) 
    {
        $.ajax({
            url:"<?php echo base_url(); ?>leads/settings/wentries/get_existing?workflow_id="+workflow_id,
            method:"GET",
            type:'ajax',
            success:function(data){
                var result=JSON.parse(data);
                $('#existing_block').show();
                $('#existing_items').empty();
                if(result.length==0)
                {
                    $('#existing_items').append('<span class="badge light badge-danger">There are no services existing in this workflow</span>');
                }
                else
                {
                    for (var i = 0; i < result.length; i++) 
                    {
                         $('#existing_items').append('<span class="badge light badge-success"><i class="fa fa-circle text-success mr-1 mb-1"></i>'+result[i]['service_name']+'</span>&nbsp;&nbsp;&nbsp;');
                    }    
                }  
            },
            error:function(err){
                console.log(err);
            }
        });
    }
</script>