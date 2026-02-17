<style>
    .app-inner-layout__wrapper.row-fluid.no-gutters {
        min-height: unset;
    }

    pre {
        font-size: unset !important;
    }
</style>
<?php

$enquiry_current_status = $enquiry_details['enquiry_status'];
?>
<div class="app-main">
    <div class="app-main__outer">
        <div class="app-main__inner p-0">
            <div class="app-page-title">
                <div class="container fiori-container">
                    <div class="page-title-wrapper">
                        <div class="page-title-heading">
                            <div>
                                Manage Enquiries
                                <div class="page-title-subheading">Manage <?php echo $website_details['website_name']; ?> Enquiries
                                </div>
                            </div>
                        </div>
                        <div class="page-title-actions">
                            <div class="d-inline-block dropdown">
                                <button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn-shadow mr-3 dropdown-toggle btn btn-dark">
                                    <i class="fa fa-star" data-toggle="tooltip" title="" data-placement="bottom" data-original-title="Example Tooltip"></i>
                                </button>
                                <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu dropdown-menu-right">
                                    <ul class="nav flex-column">
                                        <li class="nav-item">
                                            <a href="javascript:void(0);" class="nav-link">
                                                <i class="nav-link-icon lnr-inbox"></i>
                                                <span>
                                                    Inbox
                                                </span>
                                                <div class="ml-auto badge badge-pill badge-secondary">86</div>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="javascript:void(0);" class="nav-link">
                                                <i class="nav-link-icon lnr-book"></i>
                                                <span>
                                                    Book
                                                </span>
                                                <div class="ml-auto badge badge-pill badge-danger">5</div>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="javascript:void(0);" class="nav-link">
                                                <i class="nav-link-icon lnr-picture"></i>
                                                <span>
                                                    Picture
                                                </span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a disabled="" href="javascript:void(0);" class="nav-link disabled">
                                                <i class="nav-link-icon lnr-file-empty"></i>
                                                <span>
                                                    File Disabled
                                                </span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="fiori-container">
                <div class="app-inner-layout chat-layout row justify-content-center m-0">
                    <div class="col-lg-10">
                        <?php
                        // print_r($enquiry_details);
                        // exit();
                        if ($this->session->flashdata('alert')) {
                        ?>
                            <div class="alert alert-<?php echo $this->session->flashdata('alert_complete'); ?>">
                                <?php echo $this->session->flashdata('alert_complete_message'); ?>
                            </div>
                        <?php
                        }
                        ?>

                        <div class="row justify-content-center">
                            <div class="col-lg-8 col-12 mt-4">
                                <?php if ($enquiry_current_status !=  305 && $enquiry_current_status !=  306) { ?>
                                    <div class="app-inner-layout__header text-white bg-night-sky br-tr br-tl">
                                        <div class="app-page-title app-page-title-simple">
                                            <div class="page-title-wrapper">
                                                <div class="page-title-heading">
                                                    <div>Follow-ups
                                                        <div class="page-title-subheading">Manage the enquiry</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="app-inner-layout__wrapper row-fluid no-gutters">
                                        <div class="app-inner-layout__sidebar bg-transparent card" style="">
                                            <div class="p-3 stick-to-parent" style="">
                                                <div class="dropdown-menu nav p-0 dropdown-menu-inline dropdown-menu-rounded dropdown-menu-hover-primary">
                                                    <h6 tabindex="-1" class="pt-0 dropdown-header">Menu</h6>
                                                    <a href="#tab-faq-1" data-toggle="tab" tabindex="0" class="mb-1 dropdown-item show active" aria-expanded="true">Through Email </a>
                                                    <a href="#tab-faq-2" data-toggle="tab" tabindex="0" class="mb-1 dropdown-item show" aria-expanded="true">Through Call</a>
                                                    <a href="#tab-faq-3" data-toggle="tab" tabindex="0" class="mb-1 dropdown-item show" aria-expanded="true">Custom</a>
                                                    <?php
                                                    if ($enquiry_current_status > 303 && $enquiry_current_status < 305) {
                                                    ?>
                                                        <a href="#tab-faq-4" data-toggle="tab" tabindex="0" class="mb-1 dropdown-item show" aria-expanded="true">Order Confirm</a>
                                                        <a href="#tab-faq-5" data-toggle="tab" tabindex="0" class="mb-1 dropdown-item show" aria-expanded="true">Close the Enquiry</a>
                                                    <?php
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 app-inner-layout__content card">
                                            <div class="pb-5 pl-5 pr-5 pt-3">
                                                <div class="mobile-app-menu-btn mb-3">
                                                    <button type="button" class="hamburger hamburger--elastic"><span class="hamburger-box"><span class="hamburger-inner"></span></span></button>
                                                </div>
                                                <div class="tab-content">
                                                    <div class="tab-pane show active" id="tab-faq-1">
                                                        <form action="" method="post" id="action_email">
                                                            <input type="hidden" name="website_id" value="<?php echo $website_details['id']; ?>">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label>From Email&nbsp;<span class="text-danger required">*</span></label>
                                                                        <input type="email" class="form-control" placeholder="from" readonly="" value="<?php echo $this->auth_email; ?>" name="from_email">
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label>To Email (Customer)&nbsp;<span class="text-danger required">*</span></label>
                                                                        <input type="email" class="form-control" value="<?php echo $enquiry_details['email']; ?>" name="customer_email">
                                                                        <input type="hidden" name="agent_email" value="<?php echo $this->auth_email; ?>">
                                                                    </div>
                                                                    <?php
                                                                    if ($enquiry_details['enquiry'] == 1) {
                                                                        $type = 'enquiry';
                                                                    } else if ($enquiry_details['enquiry'] == 2) {
                                                                        $type = 'meeting';
                                                                    } else if ($enquiry_details['enquiry'] == 3) {
                                                                        $type = 'complaint';
                                                                    }
                                                                    ?>
                                                                    <div class="form-group">
                                                                        <label>Subject&nbsp;<span class="text-danger required">*</span></label>
                                                                        <input type="text" class="form-control" placeholder="from" value="<?php echo $website_details['website_name'] . " - ##EQ" . $enquiry_details['id'] . "##"; ?> - Followup regarding - <?php echo $type; ?>" name="email_subject" required>
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
                                                                        <textarea rows="10" class="form-control ckeditor" name="email_message" id="email_editor" required></textarea>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label>Remarks for your reference (Optional)</label>
                                                                        <textarea class="form-control" name="email_remarks" id="editor"></textarea>
                                                                    </div>
                                                                    <input type="hidden" id="email_date" name="contactable_date" class="form-control" placeholder="" value="<?php echo date('Y-m-d H:i:s', time() + 86400); ?>">
                                                                    <div class="form-group">
                                                                        <input type="submit" name="action_email" class="btn btn-primary btn-block" value="SEND EMAIL">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                    <div class="tab-pane show" id="tab-faq-2">
                                                        <form action="" method="post">
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
                                                    <div class="tab-pane show" id="tab-faq-3">
                                                        <form action="" method="post">
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
                                                    <?php
                                                    if ($enquiry_current_status > 303 && $enquiry_current_status < 305) {
                                                    ?>
                                                        <div class="tab-pane show" id="tab-faq-4">
                                                            <form action="<?php echo $action_url; ?>" method="post">
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <label>ORDER REFERENCE NUMBER (INVOICE REFERENCE NUMBER)&nbsp;<span class="text-danger required">*</span></label>
                                                                            <input type="text" name="order_id" id="order_id" required="" class="form-control">
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <input type="submit" name="action_order" class="btn btn-primary btn-block" value="COMPLETE ENQUIRY">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        <div class="tab-pane show" id="tab-faq-5">
                                                            <form action="<?php echo $action_url; ?>" method="post">
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <label>Remarks&nbsp;<span class="text-danger required">*</span></label>
                                                                            <textarea rows="5" class="form-control" name="close_remarks" id="close_remarks"></textarea>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <input type="submit" name="action_close" class="btn btn-primary btn-block" value="CLOSE ENQUIRY">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>

                                                    <?php
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                                <div class="main-card mb-3 card">
                                    <div class="card-body">
                                        <h5 class="card-title">Timeline</h5>
                                        <div class="vertical-timeline vertical-timeline--animate vertical-timeline--one-column">
                                            <?php
                                            foreach ($timeline as $key => $value) {
                                                // print_r($value);
                                                $class = "success";
                                                if ($value["action_id"] == 404) $class = "info";
                                                if ($value["action_id"] == 405) $class = "alternate";
                                                if ($value["action_id"] == 406) $class = "secondary";
                                                if ($value["action_id"] == 407) $class = "warning";
                                                if ($value["action_id"] == 408) $class = "primary";
                                                if ($value["action_id"] == 411) $class = "danger";

                                            ?>
                                                <div class="vertical-timeline-item vertical-timeline-element">
                                                    <div>
                                                        <span class="vertical-timeline-element-icon bounce-in">
                                                            <i class="badge badge-dot badge-dot-xl badge-<?php echo $class; ?>"> </i>
                                                        </span>
                                                        <div class="vertical-timeline-element-content bounce-in">

                                                            <a class="collapsed" data-toggle="collapse" href="#collapsable<?php echo $key; ?>" role="button" aria-expanded="false" aria-controls="collapsable<?php echo $key; ?>">
                                                                <h4 class="timeline-title"><?php echo $value['action_name']; ?></h4>
                                                            </a>
                                                            <div class="collapse" id="collapsable<?php echo $key; ?>">
                                                                <?php
                                                                echo "<pre>" . quoted_printable_decode($value['remarks']) . "</pre>";
                                                                ?>
                                                            </div>
                                                            <span class="vertical-timeline-element-date"><?php echo date('H:i A', strtotime($value['action_on'])) . "<br>" . date('d M Y', strtotime($value['action_on'])); ?></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-lg-3 mt-4 order-first order-lg-last">
                                <div class="card-shadow-primary card-border text-white mb-3 card bg-primary stick-to-parent">
                                    <div class="dropdown-menu-header">
                                        <div class="dropdown-menu-header-inner bg-primary">
                                            <div class="menu-header-content">
                                                <div class="avatar-icon-wrapper mb-3 avatar-icon-xl">
                                                    <div class="avatar-icon"><img src="/assets_new/images/avatars/3.jpg" alt="Avatar 5"></div>
                                                </div>
                                                <div>
                                                    <h5 class="menu-header-title"><?php echo $enquiry_details['name']; ?></h5>
                                                    <h6 class="menu-header-subtitle"><?php echo $enquiry_details['email']; ?></h6>
                                                    <h6 class="menu-header-subtitle"><?php echo $enquiry_details['phone']; ?></h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-left d-block card-footer">
                                        <?php
                                        echo $enquiry_details['subject'];
                                        ?>
                                        <br>
                                        <div class="text-center mt-3">
                                            <button class="btn-shadow-dark btn-wider btn btn-dark"><?php echo $enquiry_details['created_date']; ?></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="/assets_new/richtext/jquery.richtext.js"></script>
<link rel="stylesheet" href="/assets_new/richtext/richtext.min.css">
<script>
    $("document").ready(function() {
        $("blockquote").remove();
    });

    $(".ckeditor").richText();


    $("form#action_email").submit(function(e) {
        var body = $("form#action_email textarea").val();
        body = removeTags(body);
        if (body.trim() == '') {
            swal.fire({
                icon: "info",
                text: "Please enter the Email Body"
            });
            e.preventDefault();
        }
    });

    function apply_email_template(template_id) {
        $.ajax({
            url: "<?php echo base_url(); ?>leads/lead/get_template?template_id=" + template_id,
            method: "GET",
            type: 'ajax',
            dataType: 'html',
            success: function(data) {
                $("#email_editor").val(data).trigger("change");
            },
            error: function(err) {
                $("#email_editor").val("").trigger("change");
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

<?php /*
<script src="https://cdn.tiny.cloud/1/gn7eycc65hlmm5lk1xyd7c239x58e8q1m5vy486xs46svb2a/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/file-icon-vectors@1.0.0/dist/file-icon-vectors.min.css" />

<style type="text/css">
.dtp div.dtp-date, .dtp div.dtp-time
{
background: #2196f3 !important;
}
.dtp table.dtp-picker-days tr > td > a.selected
{
background: #2196f3 !important;
}
.dtp > .dtp-content > .dtp-date-view > header.dtp-header
{
background: #3f51b5 !important;
}
.dtp .p10 > a
{
color: #fff !important;
}
.year-picker-item.active
{
color: #3f51b5 !important; 
}
.bg-secondary {
background-color: #051469 !important;
}
</style>
<script>
tinymce.init({
selector: 'textarea#email_editor',
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
$('#action_email_block').hide();
$('#action_sms_block').hide();
$('#action_call_block').hide();
$('#action_meeting_block').hide();
$('#action_custom_block').hide();
$('#action_order_block').hide();
$('#action_close_block').hide();

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
</script>
<style type="text/css">
.text-ontime
{
color:#3d4465 !important;
}
.dataTables_wrapper .dataTables_paginate .paginate_button.previous.disabled, .dataTables_wrapper .dataTables_paginate .paginate_button.next.disabled {
color: #969ba0 !important;
}
</style>
<script type="text/javascript">
//hide elements at first
$( document ).ready(function() {
$('#main-wrapper').attr('class','show menu-toggle');
$('#mrn_block').hide();
});

</script>
<div class="row page-titles mx-0">
<div class="col-sm-6 p-md-0">
<div class="welcome-text">
<?php
?>
<h4><?php echo $website_details['website_name']; ?> ENQUIRIES</h4>
<span>Manage <?php echo $website_details['website_name']; ?> Enquiries</span>
</div>
</div>
<div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
<a class="btn btn-primary waves-effect waves-light float-right"
href="<?php echo base_url(); ?>enquiries/<?php echo $website_details['short_name']; ?>/">
<i class="fa fa-back mr-2"></i> Back to enquiries
</a>
</div>
</div>
<div class="row">
<div class="col-lg-12">
<div class="card" id="order_details_block">
<div class="card-header">
<h5 class="card-title">ENQUIRY DETAILS</h5>
</div>
<div class="card-body">
<div class="customer_block">
<h5><strong>CUSTOMER INFORMATION</strong></h5>
<table class="table">
<tr>
<td>
<strong>Name:</strong><br />
<span id="o_ord_category" class="text-ontime"><?php echo $enquiry_details['name']; ?></span>
</td>
<td>
<strong>Customer Email:</strong><br />
<span id="o_cus_name" class="text-ontime"><?php echo $enquiry_details['email'] ?></span>
</td>
</tr>
<tr>
<td>
<strong>Mobile Number:</strong><br />
<span id="o_cus_mobile" class="text-ontime"><?php echo $enquiry_details['phone'] ?></span>
</td>
<td>
<strong>Created Date:</strong><br />
<span id="o_ord_sub_category2" class="text-ontime"><?php echo date("F jS, Y", strtotime($enquiry_details['created_date'])); ?></span>
</td>
</tr>
<tr>
<td colspan="2">
<strong>Subject:</strong><br />
<span id="o_ord_sub_category" class="text-ontime"><?php echo $enquiry_details['subject']; ?></span>
</td>
</tr>
</table>
</div>
</div>
</div>
</div>
</div>

<?php
$enquiry_current_status = $enquiry_details['enquiry_status']; 
if($enquiry_current_status < 305)
{
?>
<div class="row">
<div class="col-lg-12">
<div class="card">
<div class="card-header bg-secondary">
<h5 class="card-title text-white">FOLLOWUP</h5>
</div>
<div class="card-body">
<div class="row">
<div class="col-md-12">
<?php 
if ($this ->session ->flashdata('alert')) 
{ 
?>
<div class="alert alert-<?php echo $this->session->flashdata('alert'); ?>">
<?php echo $this ->session ->flashdata('alert_message'); ?>
</div>
<?php 
} 
?>
<div class="form-group">
<label>Select the followup activity &nbsp;<span class="text-danger required">*</span></label>
<select class="form-control" name="action_id" id="action_id" onchange="javascript:choose_action(this.value);">
<option value="">-- Choose action --</option>
<?php
foreach ($followup_actions as $key => $value) 
{
?>
<option value="<?php echo $value['id'];?>"><?php echo $value['action_name'];?></option>
<?php
}
?>
</select>
</div>
</div> 
</div>
<div id="action_email_block">
<form action="<?php echo base_url(); ?>leads/lead/action_email/<?php echo $this->uri->segment(4);?>" method="post">
<div class="row">
<div class="col-md-12">
<div class="form-group">
<label>From Email&nbsp;<span class="text-danger required">*</span></label>
<input type="email" class="form-control" placeholder="from" readonly="" value="<?php echo $this->auth_email; ?>" name="from_email">
</div>
<div class="form-group">
<label>To Email (Customer)&nbsp;<span class="text-danger required">*</span></label>
<input type="email" class="form-control" readonly="" value="<?php echo $enquiry_details['email']; ?>" name="customer_email">
<input type="hidden" name="agent_email" value="<?php echo $this->auth_email; ?>">
</div>
<div class="form-group">
<label>Subject&nbsp;<span class="text-danger required">*</span></label>
<input type="text" class="form-control" placeholder="from"  value="<?php echo $website_details['website_name']; ?> - Followup regarding <?php  
if($enquiry_details['enquiry']==1) {
echo 'enquiry';
} else if($enquiry_details['enquiry']==2) {
echo 'meeting';
} else if($enquiry_details['enquiry']==3) {
echo 'complaint';
}

?>" name="email_subject">
</div>
<div class="form-group">
<label>Choose email template</label>
<select class="form-control" name="template_id" id="template_id" onchange="javascript:apply_email_template(this.value);">
<option value="">-- Choose template --</option>
<?php
foreach ($email_templates as $key => $value) 
{
?>
<option value="<?php echo $value['id'];?>"><?php echo $value['template_name'];?></option>
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

<input type="text" id="email_date" name="contactable_date" class="form-control" placeholder="" value="<?php echo date('Y-m-d H:i:s',time() + 86400); ?>">
</div>
<div class="form-group">
<input type="submit" name="action_email" class="btn btn-primary btn-block" value="SEND EMAIL">
</div>
</div>
</div> 
</form>
</div>
<div id="action_call_block">
<form action="<?php echo base_url(); ?>leads/lead/action_call/<?php echo $this->uri->segment(4);?>"  method="post">
<div class="row">
<div class="col-md-12">
<div class="form-group">
<label>Remarks&nbsp;<span class="text-danger required">*</span></label>
<textarea rows="5" class="form-control" name="call_remarks" id="call_remarks"></textarea>
</div>
<div class="form-group">
<label>Next Contactable Date&nbsp;<span class="text-danger required">*</span></label>

<input type="text" id="call_date" name="contactable_date" class="form-control" placeholder="" value="<?php echo date('Y-m-d H:i:s',time() + 86400); ?>">
</div>
<div class="form-group">
<input type="submit" name="action_call" class="btn btn-primary btn-block" value="UPDATE CALL STATUS">
</div>
</div>
</div>
</form>
</div>
<div id="action_sms_block">
<form action="<?php echo base_url(); ?>leads/lead/action_sms/<?php echo $this->uri->segment(4);?>"  method="post">
<div class="row">
<div class="col-md-12">
<div class="form-group">
<label>Customer Mobile Number&nbsp;<span class="text-danger required">*</span></label>
<input type="text" class="form-control" name="mobile_number" value="<?php echo "971".$enquiry_details['phone']; ?>" />
</div>
<div class="form-group">
<label>Choose sms template</label>
<select class="form-control" name="template_id" id="template_id" onchange="javascript:apply_sms_template(this.value);">
<option value="">-- Choose template --</option>
<?php
foreach ($sms_templates as $key => $value) 
{
?>
<option value="<?php echo $value['id'];?>"><?php echo $value['template_name'];?></option>
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

<input type="text" id="sms_date" class="form-control" placeholder="" name="contactable_date" value="<?php echo date('Y-m-d H:i:s',time() + 86400); ?>">
</div>
<div class="form-group">
<input type="submit" name="action_sms" class="btn btn-primary btn-block" value="SEND SMS">
</div>
</div>
</div>
</form>
</div>
<!--
<div id="action_meeting_block">
<form action="<?php echo base_url(); ?>leads/lead/action_meeting/<?php echo $this->uri->segment(4);?>"  method="post">
<div class="row">
<div class="col-md-12">
<div class="form-group">
<label>Meeting Date &amp; Time&nbsp;<span class="text-danger required">*</span></label>

<input type="text" id="meeting_date" name="contactable_date" class="form-control" placeholder="" value="<?php echo date('Y-m-d H:i:s',time() + 86400); ?>">
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
-->
<div id="action_custom_block">
<form action="<?php echo base_url(); ?>leads/lead/action_custom/<?php echo $this->uri->segment(4);?>"  method="post">
<div class="row">
<div class="col-md-12">
<div class="form-group">
<label>Remarks&nbsp;<span class="text-danger required">*</span></label>
<textarea rows="5" class="form-control" name="custom_remarks" id="custom_remarks"></textarea>
</div>
<div class="form-group">
<label>Next Contactable Date&nbsp;<span class="text-danger required">*</span></label>

<input type="text" id="custom_date" name="contactable_date" class="form-control" placeholder="" value="<?php echo date('Y-m-d H:i:s',time() + 86400); ?>">
</div>
<div class="form-group">
<input type="submit" name="action_custom" class="btn btn-primary btn-block" value="UPDATE TIMELINE">
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
<h5 class="card-title text-white">TIMELINE</h5>
</div>
<div class="card-body">
<div id="DZ_W_TimeLine" class="widget-timeline">
<ul class="timeline">
<?php
foreach ($timeline as $key => $value) 
{
?>
<li>
<div class="timeline-badge info"></div>
<a class="timeline-panel text-muted" href="#">
<span><?php echo date('d M Y H:i A',strtotime($value['action_on'])); ?></span>
<h4 class="mb-0 text-primary"><?php echo $value['action_name'];?></h4>
<p class="mb-0"><?php echo $value['remarks'];?></p>
</a>
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
function choose_action(action_id) 
{
if(action_id==404)
{
$('#action_email_block').show(); 
$('#action_sms_block').hide();
$('#action_call_block').hide();
$('#action_meeting_block').hide();
$('#action_custom_block').hide();
$('#action_order_block').hide();
$('#action_close_block').hide();
}
else if(action_id==405)
{
$('#action_email_block').hide(); 
$('#action_sms_block').hide();
$('#action_call_block').show();
$('#action_meeting_block').hide();
$('#action_custom_block').hide();
$('#action_order_block').hide();
$('#action_close_block').hide();
}
else if(action_id==406)
{
$('#action_email_block').hide(); 
$('#action_sms_block').show();
$('#action_call_block').hide();
$('#action_meeting_block').hide();
$('#action_custom_block').hide();
$('#action_order_block').hide();
$('#action_close_block').hide();
}
else if(action_id==407)
{
$('#action_email_block').hide(); 
$('#action_sms_block').hide();
$('#action_call_block').hide();
$('#action_meeting_block').show();
$('#action_custom_block').hide();
$('#action_order_block').hide();
$('#action_close_block').hide();
}
else if(action_id==408)
{
$('#action_email_block').hide(); 
$('#action_sms_block').hide();
$('#action_call_block').hide();
$('#action_meeting_block').hide();
$('#action_custom_block').show();
$('#action_order_block').hide();
$('#action_close_block').hide();
}
else if(action_id==410)
{
$('#action_email_block').hide(); 
$('#action_sms_block').hide();
$('#action_call_block').hide();
$('#action_meeting_block').hide();
$('#action_custom_block').hide();
$('#action_order_block').show();
$('#action_close_block').hide();
}
else if(action_id==411)
{
$('#action_email_block').hide(); 
$('#action_sms_block').hide();
$('#action_call_block').hide();
$('#action_meeting_block').hide();
$('#action_custom_block').hide();
$('#action_order_block').hide();
$('#action_close_block').show();
}
else
{
$('#action_email_block').hide();
$('#action_sms_block').hide();
$('#action_call_block').hide();
$('#action_meeting_block').hide();
$('#action_custom_block').hide();
$('#action_order_block').hide();
$('#action_close_block').hide();
}
}

function apply_email_template(template_id) 
{
$.ajax({
url:"<?php echo base_url(); ?>leads/lead/get_template?template_id="+template_id,
method:"GET",
type:'ajax',
dataType:'html',
success:function(data)
{
$('#email_editor').html("");
tinymce.get('email_editor').getBody().innerHTML =data;
},
error:function(err){
tinymce.get("email_editor").setContent("");
}
});
}

function apply_sms_template(template_id) 
{
$.ajax({
url:"<?php echo base_url(); ?>leads/lead/get_template?template_id="+template_id,
method:"GET",
type:'ajax',
dataType:'html',
success:function(data)
{
$('#sms_editor').html("");
$('#sms_editor').html(data);
},
error:function(err){
$('#sms_editor').html("");
}
});
}

$(document).on("click", ".open-meetingDialog", function () {
var meeting_id = $(this).data('meetingid');
var lead_id = $(this).data('leadid');
$(".modal-body #lead_id").val(lead_id);
$(".modal-body #meeting_id").val(meeting_id);
});


</script>
*/ ?>