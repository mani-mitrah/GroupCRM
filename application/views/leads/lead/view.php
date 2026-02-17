<script src="https://cdn.tiny.cloud/1/gn7eycc65hlmm5lk1xyd7c239x58e8q1m5vy486xs46svb2a/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/file-icon-vectors@1.0.0/dist/file-icon-vectors.min.css" />

<style type="text/css">
    .dtp div.dtp-date,
    .dtp div.dtp-time {
        background: #2196f3 !important;
    }

    .dtp table.dtp-picker-days tr>td>a.selected {
        background: #2196f3 !important;
    }

    .dtp>.dtp-content>.dtp-date-view>header.dtp-header {
        background: #3f51b5 !important;
    }

    .dtp .p10>a {
        color: #fff !important;
    }

    .year-picker-item.active {
        color: #3f51b5 !important;
    }

    .bg-secondary {
        background-color: #051469 !important;
    }

    .sub_cards {
        border: 1.1px solid gray;
        border-radius: 10px;
        box-shadow: 2px 2px 18px -2px #0000004d;
        padding: 10px;
        margin-bottom: 30px;
    }
</style>
<script>
    tinymce.init({
        selector: 'textarea#email_editor',
        menubar: false
    });

    tinymce.init({
        selector: 'textarea#email_editor2',
        menubar: false
    });

    tinymce.init({
        selector: 'textarea#call_remarks',
        menubar: false
    });
    tinymce.init({
        selector: 'textarea#custom_remarks',
        menubar: false
    });
    tinymce.init({
        selector: 'textarea#meeting_remarks',
        menubar: false
    });
    tinymce.init({
        selector: 'textarea#meeting_update_remarks',
        menubar: false
    });
    tinymce.init({
        selector: 'textarea#close_remarks',
        menubar: false
    });
</script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#customer_info_update').hide();
        $('#action_email_block').hide();
        $('#action_sms_block').hide();
        $('#action_call_block').hide();
        $('#action_meeting_block').hide();
        $('#action_custom_block').hide();
        $('#action_order_block').hide();
        $('#action_close_block').hide();
        $('#action_payment').hide();

        $('#email_date').bootstrapMaterialDatePicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            minDate: new Date()
        });

        $('#meeting_date').bootstrapMaterialDatePicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            minDate: new Date()
        });

        $('#call_date').bootstrapMaterialDatePicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            minDate: new Date()
        });

        $('#sms_date').bootstrapMaterialDatePicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            minDate: new Date()
        });

        $('#custom_date').bootstrapMaterialDatePicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            minDate: new Date()
        });
        $('#meeting_contactable_date').bootstrapMaterialDatePicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            minDate: new Date()
        });




    });

    function enable_customer_info() {
        $('#customer_info_update').show();
        $("html, body").animate({
            scrollTop: 0
        }, "slow");
        $('#uc_customer_name').focus();
        return false;
    }
</script>
<?php
$fake_domain = "no";
// print_r($lead_details);
$email = $lead_details['customer_email'];
$domain = substr(strrchr($email, "@"), 1);
if ($domain == 'ontimecustomer.com') {
    $fake_domain = "yes";
}
$lead_current_status = $lead_details['lead_status'];

?>
<div class="row page-titles mx-0">
    <div class="col-sm-6 p-md-0">
        <div class="welcome-text">
            <h4>Lead information</h4>
            <span>View lead details</span>
        </div>
    </div>
    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
        <a class="btn btn-primary waves-effect waves-light float-right" href="<?php echo base_url(); ?>leads/lead/manage">
            <i class="fa fa-back mr-2"></i> Back to leads
        </a>
    </div>
</div>
<div class="row" id="customer_info_update">
    <div class="col-lg-12">
        <div class="card" id="order_details_block">
            <div class="card-header bg-secondary">
                <h5 class="card-title text-white">UPDATE CUSTOMER INFORMATION</h5>
            </div>
            <div class="card-body">
                <form action="<?php echo base_url(); ?>leads/lead/view/<?php echo $lead_details['id']; ?>" method="post">
                    <div class="form-group">
                        <label>Customer Name</label>
                        <input id="uc_customer_name" type="text" name="customer_name" class="form-control" placeholder="" value="<?php echo $lead_details['customer_name']; ?>">
                    </div>

                    <div class="form-group">
                        <label>Customer Email Address<?php echo ($fake_domain == 'yes') ? '&nbsp;&nbsp;<span class="text-danger required">*</span>' : ''; ?></label>
                        <input type="text" name="customer_email" class="form-control" placeholder="" <?php echo ($fake_domain == 'yes') ? 'required="required"' : ''; ?> value="<?php echo ($fake_domain == 'yes') ? '' : $lead_details['customer_email']; ?>">

                    </div>
                    <div class="form-group">
                        <label>Customer Mobile Number</label>
                        <div class="row">
                            <div class="col-md-2">
                                <input type="text" name="customer_country_code" class="form-control" placeholder="" value="<?php echo $lead_details['customer_country_code']; ?>">
                            </div>
                            <div class="col-md-10">
                                <input type="text" name="customer_mobile" class="form-control" placeholder="" value="<?php echo $lead_details['customer_mobile']; ?>">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="hidden" name="lead_id" id="lead_id" value="<?php echo $lead_details['id']; ?>">
                        <input type="submit" name="update_customer_info" class="btn btn-primary btn-block" value="UPDATE CUSTOMER RECORD">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php
if ($this->session->flashdata('alert_cu')) {
?>
    <div class="alert alert-<?php echo $this->session->flashdata('alert_cu'); ?>">
        <?php echo $this->session->flashdata('alert_message_cu'); ?>
    </div>
<?php
}
?>
<div class="row">
    <div class="col-lg-12">
        <div class="card" id="order_details_block">
            <div class="card-header bg-secondary">
                <h5 class="card-title text-white">LEAD DETAILS</h5>
                <button type="button" class="btn btn-rounded btn-warning float-right" data-name="<?php echo $lead_details['customer_name']; ?>" data-email="<?php echo $lead_details['customer_email']; ?>" data-countrycode="<?php echo $lead_details['customer_country_code']; ?>" data-mobile="<?php echo $lead_details['customer_mobile']; ?>" onclick="javascript:enable_customer_info();"><span class="btn-icon-left text-warning"><i class="fa fa-pencil color-warning"></i></span>Update customer information</button>
            </div>
            <div class="card-body">
                <div class="customer_block">
                    <table class="table">
                        <tr>
                            <td>
                                <strong>Customer Name:</strong><br />
                                <span id="o_cus_name" class="text-ontime"><?php echo $lead_details['customer_name']; ?></span>
                            </td>
                            <td>
                                <strong>Mobile Number:</strong><br />
                                <span id="o_cus_mobile" class="text-ontime"><?php echo $lead_details['customer_country_code'] . ' ' . $lead_details['customer_mobile']; ?></span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Customer Email:</strong><br />
                                <span id="o_cus_email" class="text-ontime">
                                    <?php echo ($fake_domain == 'yes') ? '-- EMAIL NOT PROVIDED--' : $lead_details['customer_email']; ?>
                                </span>
                            </td>
                            <td>

                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Category:</strong><br />
                                <span id="o_ord_category" class="text-ontime"><?php echo $lead_details['category_name']; ?></span>
                            </td>
                            <td>
                                <strong>Service Name:</strong><br />
                                <span id="o_ord_sub_category" class="text-ontime"><?php echo $lead_details['service_name']; ?></span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Lead by:</strong><br />
                                <span id="o_ord_sub_category2" class="text-ontime"><?php echo $lead_details['branch_name']; ?></span>
                            </td>
                            <td>
                                <strong>Created by:</strong><br />
                                <span id="o_ord_mrn" class="text-ontime"><?php echo $lead_by_details['first_name'] . ' ' . $lead_by_details['last_name']; ?><br /><?php echo $lead_by_details['email']; ?><br /><?php echo $lead_by_details['mobile']; ?></span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <strong>Description:</strong><br />
                                <?php echo $lead_details['remarks'];
                                ?>
                            </td>
                        </tr>
                        <?php
                        if (isset($lead_attachments) && $lead_attachments != NULL) {
                        ?>
                            <tr>
                                <td colspan="2"><strong>Attachments</strong></td>
                            </tr>
                            <?php
                            foreach ($lead_attachments as $key => $value) {
                            ?>
                                <tr>
                                    <td><?php echo strtoupper($value['attachment_name']); ?></td>
                                    <td>
                                        <?php

                                        $file_url = $value['attachment_url'];
                                        $file_name = str_replace("https://crm.ontimegroup.com/uploads/leads/", "", $file_url);
                                        ?>

                                        <a target="blank" href="<?php echo $value['attachment_url']; ?>"> <span class="fiv-viv fiv-icon-doc"></span>&nbsp;&nbsp;View file</a>
                                    </td>
                                </tr>
                        <?php
                            }
                        }
                        ?>
                        <?php
                        if ($lead_details['order_receipt'] != "0") {
                        ?>
                            <tr>
                                <td colspan="2">
                                    <strong>ORDER ID#:</strong><br />
                                    <span id="o_ord_sub_category2" class="text-ontime"><?php echo $lead_details['order_receipt']; ?></span>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>

                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4>New Sub Lead on <?php echo $lead_details['id']; ?> </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
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

                <div class="card mb-0">
                    <div class="card-body p-0">
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
                            <input type="hidden" name="lead_parent_id" value="<?php echo $lead_details['id']; ?>">
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
            </div>
            <!-- <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save</button>
            </div> -->
        </div>
    </div>
</div>


<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header bg-secondary">
                <h5 class="card-title text-white">SUB LEAD(S)</h5>
                <?php
                // if(isset($lead_current_status)){
                if (($lead_current_status > 303 && $lead_current_status < 305) || $lead_current_status > 306) {
                ?>
                    <button type="button" class="btn btn-rounded btn-warning float-right" data-toggle="modal" data-target="#modelId"><span class="btn-icon-left text-warning"><i class="fa fa-plus color-warning"></i></span>Add Sub Lead</button>
                <?php
                }    
            // }    
                ?>
            </div>
            <div class="card-body">

                <?php
                if ($sub_leads) {
                    // echo "<pre>";
                    // print_r($sub_leads);
                    // echo "</pre>";
                    // exit();
                    foreach ($sub_leads as $key => $value) {
                        $iter = $key + 1;
                        $sub_lead_id = $value->id;
                ?>
                        <div class="sub_cards">
                            <table class="table">
                                <tr>
                                    <td colspan="2">
                                        <strong>Sub Lead - <?php echo $key + 1; ?> #<?php echo $sub_lead_id; ?></strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>Category:</strong><br />
                                        <span id="o_ord_category" class="text-ontime"><?php echo $value->category; ?></span>
                                    </td>
                                    <td>
                                        <strong>Service Name:</strong><br />
                                        <span id="o_ord_sub_category" class="text-ontime"><?php echo $value->service; ?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <strong>Description:</strong><br>
                                        <span>
                                            <?php echo $value->remarks; ?>

                                        </span>
                                    </td>
                                </tr>
                                <?php
                                // $lead_attachements = $this->db->select("*")->from("lead_attachments")->get()->result_array();
                                $lead_attachments = sub_lead_attachment($sub_lead_id);
                                if (count($lead_attachments) > 0) {
                                    foreach ($lead_attachments as $key => $attach) {
                                ?>
                                        <tr>
                                            <td><?php echo strtoupper($attach['attachment_name']); ?></td>
                                            <td>
                                                <?php

                                                $file_url = $attach['attachment_url'];
                                                $file_name = str_replace("https://crm.ontimegroup.com/uploads/leads/", "", $file_url);
                                                ?>

                                                <a target="blank" href="<?php echo $attach['attachment_url']; ?>"> <span class="fiv-viv fiv-icon-doc"></span>&nbsp;&nbsp;View file</a>
                                            </td>
                                        </tr>
                                    <?php
                                    }
                                }
                                if ($value->lead_status != 305 && $value->lead_status != 306) {
                                    ?>
                                    <tr>
                                        <td colspan="2">
                                            <div class="card-body p-0">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <?php
                                                        if ($this->session->flashdata('alert_complete')) {
                                                        ?>
                                                            <div class="alert alert-<?php echo $this->session->flashdata('alert_complete'); ?>">
                                                                <?php echo $this->session->flashdata('alert_complete_message'); ?>
                                                            </div>
                                                        <?php
                                                        }
                                                        ?>
                                                        <div class="form-group mb-0">
                                                            <label>Select the closing activity &nbsp;<span class="text-danger required">*</span></label>
                                                            <select class="form-control" name="action_id" id="action_id" data-id="main<?php echo $iter; ?>" onchange="javascript:choose_action(this.value,this);">
                                                                <option value="">-- Choose action --</option>
                                                                <?php
                                                                foreach ($complete_actions as $key => $value) {
                                                                ?>
                                                                    <option value="<?php echo $value['id']; ?>"><?php echo $value['action_name']; ?></option>
                                                                <?php
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="action_order_block" id="action_order_block" data-id="main<?php echo $iter; ?>">
                                                    <form action="<?php echo base_url(); ?>leads/lead/action_order/<?php echo $sub_lead_id; ?>" enctype="multipart/form-data" method="post">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group mt-3">
                                                                    <label>ORDER REFERENCE ATTACHMENT</label>
                                                                    <input type="file" name="file" id="file" class="form-control">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>ORDER REFERENCE NUMBER&nbsp;<span class="text-danger required">*</span></label>
                                                                    <input type="text" name="order_id" id="order_id" required="" class="form-control">
                                                                </div>
                                                                <div class="form-group">
                                                                    <input type="submit" name="action_order" class="btn btn-primary btn-block" value="COMPLETE ORDER">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                                <div class="action_close_block" id="action_close_block" data-id="main<?php echo $iter; ?>">
                                                    <form action="<?php echo base_url(); ?>leads/lead/action_close/<?php echo $sub_lead_id; ?>" method="post">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label>Remarks&nbsp;<span class="text-danger required">*</span></label>
                                                                    <textarea rows="5" class="form-control" name="close_remarks" id="close_remarks"></textarea>
                                                                </div>
                                                                <div class="form-group">
                                                                    <input type="submit" name="action_close" class="btn btn-primary btn-block" value="CLOSE LEAD">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>

                                        </td>
                                    </tr>
                                <?php
                                }
                                ?>
                                <?php
                                if (isset($value->order_receipt)) {
                                    if ($value->order_receipt == "0") {

                                ?>
                                        <tr>
                                            <td colspan="2">
                                                <strong>Remark</strong><br />
                                                <span id="o_ord_sub_category2" class="text-ontime">-- Lead Closed --</span>
                                            </td>
                                        </tr>
                                    <?php
                                    } else {
                                    ?>
                                         <tr>
                                            <td colspan="2">
                                                <strong>ORDER ID#:</strong><br />
                                                <span id="o_ord_sub_category2" class="text-ontime"><?php echo $value->order_receipt; ?></span>
                                            </td>
                                        </tr>
                                <?php
                                    }
                                }
                                ?>
                            </table>
                        </div>
                    <?php
                    }
                } else {
                    ?>
                    <center>Lead have no sub lead(s)</center>
                <?php
                }
                ?>
            </div>
        </div>
    </div>
</div>



<?php
if ($lead_current_status < 305  || $lead_current_status > 306) {
?>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header bg-secondary">
                    <h5 class="card-title text-white">UPCOMING MEETINGS</h5>
                </div>
                <div class="card-body">
                    <?php
                    if (empty($upcoming_meetings)) {
                    ?>
                        <center>You have no meetings scheduled with this customer.</center>
                        <?php
                    } else {
                        foreach ($upcoming_meetings as $key => $value) {
                        ?>
                            <div class="bg-primary coin-holding mt-4 flex-wrap">
                                <div class="mb-2 coin-bx">
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <i class="fa fa-2x text-white fa-calendar"></i>
                                        </div>
                                        <div class="ml-3">
                                            <h4 class="coin-font font-w600 mb-0 text-white">Meeting with <?php echo $lead_details['customer_name']; ?></h4>
                                            <p class="mb-0 text-white"><?php echo date('d M Y H:i A', strtotime($value['meeting_date_time'])); ?></p>
                                        </div>
                                        <div class="ml-3">
                                            <a href="#meetingModal" class="btn btn-primary open-meetingDialog" data-toggle="modal" data-meetingid="<?php echo $value['id']; ?>" data-leadid="<?php echo $lead_details['id']; ?>">
                                                Update meeting remarks
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <div class="d-flex align-items-center">
                                        <p class="mb-0 ml-2 font-w400 text-white">Upcoming</p>
                                        <?php

                                        ?>
                                    </div>
                                </div>
                            </div>
                    <?php
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header bg-secondary">
                    <h5 class="card-title text-white">LEAD FOLLOWUP</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <?php
                            if ($this->session->flashdata('alert')) {
                            ?>
                                <div class="alert alert-<?php echo $this->session->flashdata('alert'); ?>">
                                    <?php echo $this->session->flashdata('alert_message'); ?>
                                </div>
                            <?php
                            }
                            ?>
                            <div class="form-group">
                                <l abel>Select the followup activity &nbsp;<span class="text-danger required">*</span></label>
                                <select class="form-control" name="action_id" id="action_id" onchange="javascript:choose_action(this.value);">
                                    <option value="">-- Choose action --</option>
                                    <?php
                                    foreach ($followup_actions as $key => $value) {
                                    ?>
                                        <option value="<?php echo $value['id']; ?>"><?php echo $value['action_name']; ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div id="action_email_block">
                        <?php
                        if ($fake_domain == 'yes') {
                        ?>
                            <center class="pt-4">
                                CUSTOMER EMAIL IS INVALID/NOT PROVIDED. PLEASE UPDATE THE CUSTOMER EMAIL TO USE THIS OPTION.<br /><br /><br />
                                <button type="button" class="btn btn-rounded btn-warning" data-name="<?php echo $lead_details['customer_name']; ?>" data-email="<?php echo $lead_details['customer_email']; ?>" data-countrycode="<?php echo $lead_details['customer_country_code']; ?>" data-mobile="<?php echo $lead_details['customer_mobile']; ?>" onclick="javascript:enable_customer_info();"><span class="btn-icon-left text-warning"><i class="fa fa-pencil color-warning"></i></span>Update customer information</button>

                            </center>
                        <?php
                        } else {
                        ?>
                            <form action="<?php echo base_url(); ?>leads/lead/action_email/<?php echo $this->uri->segment(4); ?>" method="post">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>From Email&nbsp;<span class="text-danger required">*</span></label>
                                            <input type="email" class="form-control" placeholder="from" readonly="" value="<?php echo $this->auth_email; ?>" name="from_email">
                                        </div>
                                        <div class="form-group">
                                            <label>To Email (Customer)&nbsp;<span class="text-danger required">*</span></label>
                                            <input type="email" class="form-control" placeholder="To Email Address" value="<?php echo $lead_details['customer_email']; ?>" name="customer_email">
                                            <input type="hidden" name="agent_email" value="<?php echo $this->auth_email; ?>">
                                        </div>
                                        <div class="form-group">
                                            <label>Subject&nbsp;<span class="text-danger required">*</span></label>
                                            <input type="text" class="form-control" placeholder="from" value="ONTIME - Followup regarding <?php echo $lead_details['category_name']; ?> - <?php echo $lead_details['service_name']; ?>" name="email_subject">
                                        </div>
                                        <div class="form-group">
                                            <label>Choose email template</label>
                                            <select class="form-control" name="template_id" id="template_id" onchange="javascript:apply_email_template(this.value);">
                                                <option value="">-- Choose template --</option>
                                                <?php
                                                foreach ($email_templates as $key => $value) {
                                                ?>
                                                    <option value="<?php echo $value['id']; ?>"><?php echo $value['template_name']; ?></option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>Body&nbsp;<span class="text-danger required">*</span></label>
                                            <textarea rows="10" class="form-control" name="email_message" id="email_editor"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label>Remarks for your reference (Optional)</label>
                                            <textarea rows="3" class="form-control" name="email_remarks" id="editor"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label>Next Contactable Date&nbsp;<span class="text-danger required">*</span></label>

                                            <input type="text" id="email_date" name="contactable_date" class="form-control" placeholder="" value="<?php echo date('Y-m-d H:i:s', time() + 86400); ?>">
                                        </div>
                                        <div class="form-group">
                                            <input type="submit" name="action_email" class="btn btn-primary btn-block" value="SEND EMAIL">
                                        </div>
                                    </div>
                                </div>
                            </form>
                        <?php
                        }
                        ?>
                    </div>

                    <div id="action_call_block">
                        <form action="<?php echo base_url(); ?>leads/lead/action_call/<?php echo $this->uri->segment(4); ?>" method="post">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Remarks&nbsp;<span class="text-danger required">*</span></label>
                                        <textarea rows="5" class="form-control" name="call_remarks" id="call_remarks"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label>Next Contactable Date&nbsp;<span class="text-danger required">*</span></label>

                                        <input type="text" id="call_date" name="contactable_date" class="form-control" placeholder="" value="<?php echo date('Y-m-d H:i:s', time() + 86400); ?>">
                                    </div>
                                    <div class="form-group">
                                        <input type="submit" name="action_call" class="btn btn-primary btn-block" value="UPDATE CALL STATUS">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div id="action_sms_block">
                        <form action="<?php echo base_url(); ?>leads/lead/action_sms/<?php echo $this->uri->segment(4); ?>" method="post">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Customer Mobile Number&nbsp;<span class="text-danger required">*</span></label>
                                        <input type="text" class="form-control" name="mobile_number" value="<?php echo "971" . $lead_details['customer_mobile']; ?>" />
                                    </div>
                                    <div class="form-group">
                                        <label>Choose sms template</label>
                                        <select class="form-control" name="template_id" id="template_id" onchange="javascript:apply_sms_template(this.value);">
                                            <option value="">-- Choose template --</option>
                                            <?php
                                            foreach ($sms_templates as $key => $value) {
                                            ?>
                                                <option value="<?php echo $value['id']; ?>"><?php echo $value['template_name']; ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>SMS Message&nbsp;<span class="text-danger required">*</span></label>
                                        <textarea rows="5" name="message_body" class="form-control" id="sms_editor"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label>Remarks for your reference(Optional)&nbsp;<span class="text-danger required">*</span></label>
                                        <textarea id="sms_remarks" name="sms_remarks" rows="3" class="form-control" id="editor"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label>Next Contactable Date&nbsp;<span class="text-danger required">*</span></label>

                                        <input type="text" id="sms_date" class="form-control" placeholder="" name="contactable_date" value="<?php echo date('Y-m-d H:i:s', time() + 86400); ?>">
                                    </div>
                                    <div class="form-group">
                                        <input type="submit" name="action_sms" class="btn btn-primary btn-block" value="SEND SMS">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div id="action_meeting_block">
                        <form action="<?php echo base_url(); ?>leads/lead/action_meeting/<?php echo $this->uri->segment(4); ?>" method="post">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Meeting Date &amp; Time&nbsp;<span class="text-danger required">*</span></label>

                                        <input type="text" id="meeting_date" name="contactable_date" class="form-control" placeholder="" value="<?php echo date('Y-m-d H:i:s', time() + 86400); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label>Remarks&nbsp;<span class="text-danger required">*</span></label>
                                        <textarea rows="3" class="form-control" name="meeting_remarks" id="meeting_remarks"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <input type="submit" name="action_meeting" class="btn btn-primary btn-block" value="SETUP MEETING">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div id="action_custom_block">
                        <form action="<?php echo base_url(); ?>leads/lead/action_custom/<?php echo $this->uri->segment(4); ?>" method="post">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Remarks&nbsp;<span class="text-danger required">*</span></label>
                                        <textarea rows="5" class="form-control" name="custom_remarks" id="custom_remarks"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label>Next Contactable Date&nbsp;<span class="text-danger required">*</span></label>

                                        <input type="text" id="custom_date" name="contactable_date" class="form-control" placeholder="" value="<?php echo date('Y-m-d H:i:s', time() + 86400); ?>">
                                    </div>
                                    <div class="form-group">
                                        <input type="submit" name="action_custom" class="btn btn-primary btn-block" value="UPDATE TIMELINE">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div id="action_payment">
                        <?php
                        if ($fake_domain == 'yes') {
                        ?>
                            <center class="pt-4">
                                CUSTOMER EMAIL IS INVALID/NOT PROVIDED. PLEASE UPDATE THE CUSTOMER EMAIL TO USE THIS OPTION.<br /><br /><br />
                                <button type="button" class="btn btn-rounded btn-warning" data-name="<?php echo $lead_details['customer_name']; ?>" data-email="<?php echo $lead_details['customer_email']; ?>" data-countrycode="<?php echo $lead_details['customer_country_code']; ?>" data-mobile="<?php echo $lead_details['customer_mobile']; ?>" onclick="javascript:enable_customer_info();"><span class="btn-icon-left text-warning"><i class="fa fa-pencil color-warning"></i></span>Update customer information</button>

                            </center>
                        <?php
                        } else {
                        ?>
                            <form action="<?php echo base_url(); ?>leads/lead/action_payment/<?php echo $this->uri->segment(4); ?>" method="post">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>From Email&nbsp;<span class="text-danger required">*</span></label>
                                            <input type="email" required class="form-control" placeholder="from" readonly="" value="<?php echo $this->auth_email; ?>" name="from_email">
                                        </div>
                                        <div class="form-group">
                                            <label>To Email (Customer)&nbsp;<span class="text-danger required">*</span></label>
                                            <input type="email" required class="form-control" placeholder="from" readonly="" value="<?php echo $lead_details['customer_email']; ?>" name="customer_email">
                                            <input type="hidden" name="agent_email" value="<?php echo $this->auth_email; ?>">
                                        </div>
                                        <div class="form-group">
                                            <label>Subject&nbsp;<span class="text-danger required">*</span></label>
                                            <input type="text" required class="form-control" placeholder="from" value="ONTIME - Followup regarding <?php echo $lead_details['category_name']; ?> - <?php echo $lead_details['service_name'] . ' - Payment Link'; ?>" name="email_subject">
                                        </div>

                                        <div class="form-group">
                                            <label>Body&nbsp;<span class="text-danger required">*</span></label>
                                            <textarea rows="10" class="form-control" name="email_message" id="email_editor2"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label>Amount&nbsp;<span class="text-danger required">*</span></label>
                                            <input type="number" required class="form-control" placeholder="AED" step="0.01" name="amount_payment">
                                        </div>
                                        <div class="form-group">
                                            <label>Remarks for your reference (Optional)</label>
                                            <textarea rows="3" class="form-control" name="email_remarks" id="editor2"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label>Next Contactable Date&nbsp;<span class="text-danger required">*</span></label>
                                            <input type="text" id="email_date" name="contactable_date" class="form-control" placeholder="" value="<?php echo date('Y-m-d H:i:s', time() + 86400); ?>">
                                        </div>
                                        <div class="form-group">
                                            <input type="submit" name="action_payment" class="btn btn-primary btn-block" value="SEND EMAIL">
                                        </div>
                                    </div>
                                </div>
                            </form>
                        <?php
                        }
                        ?>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php
}
?>

<?php
if (($lead_current_status > 303 && $lead_current_status < 305) || $lead_current_status > 306) {
?>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header bg-secondary">
                    <h5 class="card-title text-white">CONFIRM ORDER / CLOSE LEAD</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <?php
                            if ($this->session->flashdata('alert_complete')) {
                            ?>
                                <div class="alert alert-<?php echo $this->session->flashdata('alert_complete'); ?>">
                                    <?php echo $this->session->flashdata('alert_complete_message'); ?>
                                </div>
                            <?php
                            }
                            ?>
                            <div class="form-group">
                                <label>Select the closing activity &nbsp;<span class="text-danger required">*</span></label>
                                <select class="form-control" name="action_id" id="action_id" data-id="main" onchange="javascript:choose_action(this.value,this);">
                                    <option value="">-- Choose action --</option>
                                    <?php
                                    foreach ($complete_actions as $key => $value) {
                                    ?>
                                        <option value="<?php echo $value['id']; ?>"><?php echo $value['action_name']; ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="action_order_block" id="action_order_block" data-id="main">
                        <form action="<?php echo base_url(); ?>leads/lead/action_order/<?php echo $this->uri->segment(4); ?>" enctype="multipart/form-data" method="post">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>ORDER REFERENCE ATTACHMENT</label>
                                        <input type="file" name="file" id="file" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label>ORDER REFERENCE NUMBER&nbsp;<span class="text-danger required">*</span></label>
                                        <input type="text" name="order_id" id="order_id" required="" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <input type="submit" name="action_order" class="btn btn-primary btn-block" value="COMPLETE ORDER">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="action_close_block" id="action_close_block" data-id="main">
                        <form action="<?php echo base_url(); ?>leads/lead/action_close/<?php echo $this->uri->segment(4); ?>" method="post">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Remarks&nbsp;<span class="text-danger required">*</span></label>
                                        <textarea rows="5" class="form-control" name="close_remarks" id="close_remarks"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <input type="submit" name="action_close" class="btn btn-primary btn-block" value="CLOSE LEAD">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
}
?>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header bg-secondary">
                <h5 class="card-title text-white">LEAD TIMELINE</h5>
            </div>
            <div class="card-body">
                <div id="DZ_W_TimeLine" class="widget-timeline">
                    <ul class="timeline">
                        <?php
                        foreach ($timeline as $key => $value) {
                        ?>
                            <li>
                                <div class="timeline-badge info"></div>
                                <span class="timeline-panel text-muted" href="#">
                                    <span><?php echo date('d M Y H:i A', strtotime($value['action_on'])); ?></span>
                                    <h4 class="mb-0 text-primary"><?php echo $value['action_name']; ?></h4>
                                    <?php echo $value['remarks']; ?>
                                </span>
                            </li>
                        <?php
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    function choose_action(action_id, data_id = "") {
        if (action_id == 404) {
            $('#action_email_block').show();
            $('#action_sms_block').hide();
            $('#action_call_block').hide();
            $('#action_meeting_block').hide();
            $('#action_custom_block').hide();
            $('#action_order_block').hide();
            $('#action_close_block').hide();
            $('#action_payment').hide();
        } else if (action_id == 405) {
            $('#action_email_block').hide();
            $('#action_sms_block').hide();
            $('#action_call_block').show();
            $('#action_meeting_block').hide();
            $('#action_custom_block').hide();
            $('#action_order_block').hide();
            $('#action_close_block').hide();
            $('#action_payment').hide();
        } else if (action_id == 406) {
            $('#action_email_block').hide();
            $('#action_sms_block').show();
            $('#action_call_block').hide();
            $('#action_meeting_block').hide();
            $('#action_custom_block').hide();
            $('#action_order_block').hide();
            $('#action_close_block').hide();
            $('#action_payment').hide();
        } else if (action_id == 407) {
            $('#action_email_block').hide();
            $('#action_sms_block').hide();
            $('#action_call_block').hide();
            $('#action_meeting_block').show();
            $('#action_custom_block').hide();
            $('#action_order_block').hide();
            $('#action_close_block').hide();
            $('#action_payment').hide();
        } else if (action_id == 408) {
            $('#action_email_block').hide();
            $('#action_sms_block').hide();
            $('#action_call_block').hide();
            $('#action_meeting_block').hide();
            $('#action_custom_block').show();
            $('#action_order_block').hide();
            $('#action_close_block').hide();
            $('#action_payment').hide();
        } else if (action_id == 410) {
            data_id = $(data_id).data("id");
            $('#action_email_block[data-id="' + data_id + '"]').hide();
            $('#action_sms_block[data-id="' + data_id + '"]').hide();
            $('#action_call_block[data-id="' + data_id + '"]').hide();
            $('#action_meeting_block[data-id="' + data_id + '"]').hide();
            $('#action_custom_block[data-id="' + data_id + '"]').hide();
            $('#action_order_block[data-id="' + data_id + '"]').show();
            $('#action_close_block[data-id="' + data_id + '"]').hide();
            $('#action_payment[data-id="' + data_id + '"]').hide();
        } else if (action_id == 411) {
            data_id = $(data_id).data("id");
            $('#action_email_block[data-id="' + data_id + '"]').hide();
            $('#action_sms_block[data-id="' + data_id + '"]').hide();
            $('#action_call_block[data-id="' + data_id + '"]').hide();
            $('#action_meeting_block[data-id="' + data_id + '"]').hide();
            $('#action_custom_block[data-id="' + data_id + '"]').hide();
            $('#action_order_block[data-id="' + data_id + '"]').hide();
            $('#action_close_block[data-id="' + data_id + '"]').show();
            $('#action_payment[data-id="' + data_id + '"]').hide();
        } else if (action_id == 412) {
            $('#action_email_block').hide();
            $('#action_sms_block').hide();
            $('#action_call_block').hide();
            $('#action_meeting_block').hide();
            $('#action_custom_block').hide();
            $('#action_order_block').hide();
            $('#action_close_block').hide();
            $('#action_payment').show();
        } else {
            data_id = $(data_id).data("id");

            $('#action_email_block').hide();
            $('#action_sms_block').hide();
            $('#action_call_block').hide();
            $('#action_meeting_block').hide();
            $('#action_custom_block').hide();
            $('#action_order_block').hide();
            $('#action_close_block').hide();
            $('.action_order_block[data-id="' + data_id + '"]').hide();
            $('.action_close_block[data-id="' + data_id + '"]').hide();
            $('#action_payment').hide();
        }
    }
    $(".action_order_block,.action_close_block").hide();

    function apply_email_template(template_id) {
        $.ajax({
            url: "<?php echo base_url(); ?>leads/lead/get_template?template_id=" + template_id,
            method: "GET",
            type: 'ajax',
            dataType: 'html',
            success: function(data) {
                $('#email_editor').html("");
                tinymce.get('email_editor').getBody().innerHTML = data;
            },
            error: function(err) {
                tinymce.get("email_editor").setContent("");
            }
        });
    }

    function apply_sms_template(template_id) {
        $.ajax({
            url: "<?php echo base_url(); ?>leads/lead/get_template?template_id=" + template_id,
            method: "GET",
            type: 'ajax',
            dataType: 'html',
            success: function(data) {
                $('#sms_editor').html("");
                $('#sms_editor').html(data);
            },
            error: function(err) {
                $('#sms_editor').html("");
            }
        });
    }

    $(document).on("click", ".open-meetingDialog", function() {
        var meeting_id = $(this).data('meetingid');
        var lead_id = $(this).data('leadid');
        $(".modal-body #lead_id").val(lead_id);
        $(".modal-body #meeting_id").val(meeting_id);
    });
</script>


<!-- Modal -->
<div class="modal fade" id="meetingModal" tabindex="-1" role="dialog" aria-labelledby="meetingModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="meetingModalLabel">Update Minutes of Meeting</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="<?php echo base_url(); ?>leads/lead/action_meeting_minutes" method="post">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Remarks&nbsp;<span class="text-danger required">*</span></label>
                                <textarea rows="5" class="form-control" name="meeting_update_remarks" id="meeting_update_remarks"></textarea>
                            </div>
                            <div class="form-group">
                                <label>Next Contactable Date&nbsp;<span class="text-danger required">*</span></label>

                                <input type="text" id="meeting_contactable_date" name="meeting_contactable_date" class="form-control" placeholder="" value="<?php echo date('Y-m-d H:i:s', time() + 86400); ?>">
                            </div>
                            <div class="form-group">
                                <input type="hidden" name="lead_id" id="lead_id" value="">
                                <input type="hidden" name="meeting_id" id="meeting_id" value="">
                                <input type="submit" name="action_meeting_minutes" class="btn btn-primary btn-block" value="UPDATE MINUTES OF MEETING ">
                            </div>
                        </div>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>