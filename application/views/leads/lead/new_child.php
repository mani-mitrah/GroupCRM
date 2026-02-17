<script type="text/javascript">
    $(document).ready(function() {
        $('#category_block').show();
        $('#service_block').show();
        $('#package_block').hide();

        //add new
        $('#btnAddNewAttachment').click(function(e) {
            e.preventDefault();
            var newDiv = $('<div class="row mt-3"><div class="col-md-6"><input type="text" class="form-control" required="" name="attachment_name[]" placeholder="Attachment Name" /></div><div class="col-md-5"><input type="file" class="form-control" required="" name="files[]" placeholder="" /></div><div class="col-md-1 float-right"><a href="#" class="text-danger close-div"><i class="fa fa-trash"></i></a></div></div>');
            $('body').animate({
                scrollTop: eval($('#attachment_area').offset().top - 70)
            }, 1000);
            $('#attachments').append(newDiv);

            $('.close-div').click(function(e) {
                e.preventDefault();
                $(this).parent().parent().remove();
                $('body').animate({
                    scrollTop: eval($('#attachment_area').offset().top - 70)
                }, 1000);
            });
        });
    });
</script>
<div class="row page-titles mx-0">
    <div class="col-sm-6 p-md-0">
        <div class="welcome-text">
            <h4>New Sub Lead on <?php echo $_GET['lead_parent_id']; ?> </h4>
            <span>Ontime Leads Management</span>
        </div>
    </div>
    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
        <a class="btn btn-primary waves-effect waves-light float-right" href="<?php echo base_url(); ?>leads/lead/manage"> VIEW LEADS
        </a>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <?php if ($this->session->flashdata('alert')) { ?>
            <div class="alert alert-<?php echo $this->session->flashdata('alert'); ?> alert-dismissible fade show" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <strong>
                    <?php echo $this->session->flashdata('alert_message'); ?>
                </strong>
            </div>
            <script>
                $(".alert").alert();
            </script>
        <?php } ?>
        <form action="<?php echo base_url(); ?>leads/lead/new_child" method="post" enctype="multipart/form-data">
            <input type="hidden" name="lead_parent_id" value="<?php echo $_GET['lead_parent_id']; ?>">
            <input type="hidden" class="form-control" required="" name="lead_name" value="<?php echo $lead_customer->first_name . ' ' . $lead_customer->last_name; ?>">
            <input type="hidden" class="form-control" required="" name="lead_country_code" value="+971" value="<?php echo $lead_customer->country_code; ?>">
            <input type="hidden" class="form-control" required="" name="lead_contact" value="<?php echo $lead_customer->mobile; ?>">
            <input type="hidden" class="form-control" name="lead_email" value="<?php echo $lead_customer->email; ?>">

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Select Branch &nbsp;<span class="text-danger required">*</span></label>
                        <select class="form-control" name="branch_id" id="branch_id">
                            <option value="">-- Select Branch --</option>
                            <?php
                            foreach ($branches as $key => $value) {
                            ?>
                                <option value="<?php echo $value['branch_code']; ?>"><?php echo $value['branch_name']; ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="lead_type" id="normal" value="normal" onclick="javascript:normal_lead();" checked="">
                            <label class="form-check-label" for="normal">Normal Lead</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="lead_type" id="package" value="package" onclick="javascript:package_lead();">
                            <label class="form-check-label" for="package">Package Lead</label>
                        </div>

                    </div>
                </div>
                <div class="col-md-12" id="category_block">
                    <div class="form-group">
                        <?php //print_r($categories);
                        ?>
                        <label>Select Category &nbsp;<span class="text-danger required">*</span></label>
                        <select class="form-control" name="category_id" id="category_id" onchange="javascript:select_services(this.value);">
                            <option value="">-- Select Category --</option>
                            <?php
                            foreach ($categories as $key => $value) {
                                // print_r($value);
                            ?>
                                <option value="<?php echo $value['category_id']; ?>"><?php if ($value["category_code"] == NULL) {
                                                                                            echo $value['category_name'];
                                                                                        } else echo $value['category_code']; ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-12" id="service_block">
                    <div class="form-group">
                        <label>Select Service &nbsp;<span class="text-danger required">*</span></label>
                        <select class="form-control" name="service_id" id="service_id">
                        </select>
                    </div>
                </div>
                <div class="col-md-12" id="package_block">
                    <div class="form-group">
                        <label>Select Package &nbsp;<span class="text-danger required">*</span></label>
                        <select class="form-control" name="package_id" id="package_id">
                            <option value="">-- Select Package --</option>
                            <?php
                            foreach ($packages as $key => $value) {
                            ?>
                                <option value="<?php echo $value['package_id']; ?>"><?php echo $value['package_name']; ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Remarks(in English/Arabic)&nbsp;<span class="text-danger required">*</span></label>
                        <textarea rows="7" class="form-control" required="" name="lead_remarks"></textarea>
                    </div>
                </div>
                <a id="attachment_area"></a>
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Lead Attachments&nbsp;&nbsp;&nbsp;&nbsp; <button id="btnAddNewAttachment" class="btn btn-sm btn-primary">Add new</button></label>
                        <div class="" id="attachments">
                            <!-- <div class="row mt-3">
                                <div class="col-md-6">
                                    <input type="text" class="form-control" required="" name="attachment_name[]" placeholder="Attachment Name" />
                                </div>
                                <div class="col-md-5">
                                    <input type="file" class="form-control" required="" name="files[]" placeholder="" />
                                </div>
                                <div class="col-md-1 float-right">
                                    <a href="#" class="text-danger close-div"><i class="fa fa-trash"></i></a>
                                </div>
                            </div> -->
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <input type="hidden" name="lead_created_by" value="<?php echo $this->auth_user_id; ?>">
                        <input type="submit" class="btn btn-primary" name="submit" value="CREATE LEAD" />
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
    function normal_lead() {
        $('#category_block').show();
        $('#service_block').show();
        $('#package_block').hide();
    }

    function package_lead() {
        $('#category_block').hide();
        $('#service_block').hide();
        $('#package_block').show();
    }

    function select_services(category_id) {
        $.ajax({
            url: "<?php echo base_url(); ?>leads/lead/get_services?category_id=" + category_id,
            method: "GET",
            type: 'ajax',
            success: function(data) {
                var result = JSON.parse(data);
                $('#service_id').html("");
                if (result.length == 0) {
                    //$('#existing_items').append('<span class="badge light badge-danger">There are no services existing in this workflow</span>');
                } else {
                    for (var i = 0; i < result.length; i++) {
                        if (result[i]['service_code'] == "" || result[i]['service_code'] == null) {
                            $('#service_id').append('<option value="' + result[i]['service_id'] + '">' + result[i]['service_name'] + '</option>');
                        } else {
                            $('#service_id').append('<option value="' + result[i]['service_id'] + '">' + result[i]['service_code'] + '</option>');
                        }

                    }
                }
            },
            error: function(err) {
                console.log(err);
            }
        });
    }
</script>