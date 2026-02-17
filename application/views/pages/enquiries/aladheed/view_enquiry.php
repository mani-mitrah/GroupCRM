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