<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box">
            <h4 class="page-title"><?php echo $page_title; ?>
                <a class="btn btn-primary waves-effect waves-light float-right"
                    href="<?php echo base_url(); ?>admin/role/">
                    <i class="mdi mdi-plus mr-2"></i> View Role
                </a>
            </h4>
        </div>
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
                <form action="<?php echo base_url();?>admin/role/edit/<?php echo $default['role_id'];?>"
                    method="post">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Role Name <span
                                        class="text-danger required">*</span></label>
                                <input type="text" class="form-control" placeholder="Enter Role Name" name="role_name"
                                    required value="<?php echo $default['role_name']; ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleFormControlTextarea1">Role Description</label>
                                <textarea class="form-control" id="exampleFormControlTextarea1" name="role_description"
                                    rows="2"><?php echo $default['role_description']; ?></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Active</label>
                                <select name="is_active" class="form-control pc-selectpicker">
                                    <option value="1" <?php echo ($default['is_active'] == '1')?'selected':''?>>Active
                                    </option>
                                    <option value="0" <?php echo ($default['is_active'] == '0')?'selected':''?>>
                                        In-active</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Lead Reassign<span
                                        class="text-danger required">*</span></label>
                                <input type="radio" name="lead_reassign" id='lead_reassign' value="enable" style="margin: 10px;"
                                   <?php echo ($default_privilege['lead_reassign'] == 'enable')?'checked':''?> required>Enable
                                <input type="radio" name="lead_reassign" id='lead_reassign' value="disable" style="margin: 10px;"
                                    <?php echo ($default_privilege['lead_reassign'] == 'disable')?'checked':''?>>Disable
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Lead Reopen<span
                                        class="text-danger required">*</span></label>
                                <input type="radio" name="lead_reopen" id='lead_reopen' value="enable" style="margin: 10px;"
                                   <?php echo ($default_privilege['lead_reopen'] == 'enable')?'checked':''?> required>Enable
                                <input type="radio" name="lead_reopen" id='lead_reopen' value="disable" style="margin: 10px;"
                                    <?php echo ($default_privilege['lead_reopen'] == 'disable')?'checked':''?>>Disable
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Request Followup <span
                                        class="text-danger required">*</span></label>
                                <input type="radio" name="request_followup" id='request_followup' value="enable" style="margin: 10px;"
                                    onclick="handleClick(this);" <?php echo ($default_privilege['request_followup'] == 'enable')?'checked':''?> required>Enable
                                <input type="radio" name="request_followup" id='request_followup' value="disable" style="margin: 10px;"
                                    onclick="handleClick(this);" <?php echo ($default_privilege['request_followup'] == 'disable')?'checked':''?>>Disable
                            </div>
                        </div>
                    </div>

                    <div class="row show_req_follow">
                            <div class="col-md-6">
                            <div class="form-group">
                                <input type="checkbox" id='setup_meeting' name="setup_meeting" value="true" style="margin: 10px;"
                                    <?php echo ($default_privilege['setup_meeting'] == 'true')?'checked':''?>>
                                <label for="setup_meeting">Setup Meeting</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="checkbox" id='followup_through_email' name="followup_through_email" value="true" style="margin: 10px;"
                                <?php echo ($default_privilege['followup_through_email'] == 'true')?'checked':''?>>
                                <label for="followup_through_email">Followup Through Email </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="checkbox" id='followup_through_call' name="followup_through_call" value="true" style="margin: 10px;"
                                <?php echo ($default_privilege['followup_through_call'] == 'true')?'checked':''?>>
                                <label for="followup_through_call">Followup Through Call </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="checkbox" id='custom' name="custom" value="true" style="margin: 10px;"
                                <?php echo ($default_privilege['custom'] == 'true')?'checked':''?>>
                                <label for="custom">Custom </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="checkbox" id='through_payment_link' name="through_payment_link" value="true" style="margin: 10px;"
                                <?php echo ($default_privilege['through_payment_link'] == 'true')?'checked':''?>>
                                <label for="through_payment_link">Through Payment Link </label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Request Status Update <span class="text-danger required">*</span></label>
                                <input type="radio" name="request_status_update" id="request_status_update" value="enable" style="margin: 10px;"
                                    onclick="handleStatusClick(this);" <?php echo ($default_privilege['request_status_update'] == 'enable')? 'checked' :''?> required>Enable
                                <input type="radio" name="request_status_update" id="request_status_update" value="disable" style="margin: 10px;" 
                                    onclick="handleStatusClick(this);" <?php echo ($default_privilege['request_status_update'] == 'disable')?'checked':''?> >Disable
                            </div>
                        </div>
                    </div>

                    <div class="row show_status_update">
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="checkbox" id='eligibility_check' name="eligibility_check" value="true" style="margin: 10px;"
                                <?php echo ($default_privilege['eligibility_check'] == 'true')?'checked':''?>>
                                <label for="eligibility_check">Eligibility Check</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="checkbox" id='enquiry' name="enquiry" value="true" style="margin: 10px;"
                                <?php echo ($default_privilege['enquiry'] == 'true')?'checked':''?>>
                                <label for="enquiry">Enquiry </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="checkbox" id='missing_documents' name="missing_documents" value="true" style="margin: 10px;"
                                <?php echo ($default_privilege['missing_documents'] == 'true')?'checked':''?>>
                                <label for="missing_documents">Missing Documents</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="checkbox" id='order_confirm' name="order_confirm" value="true" style="margin: 10px;"
                                <?php echo ($default_privilege['order_confirm'] == 'true')?'checked':''?>>
                                <label for="order_confirm">Order Confirm</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="checkbox" id='disqualified' name="disqualified" value="true" style="margin: 10px;"
                                <?php echo ($default_privilege['disqualified'] == 'true')?'checked':''?>>
                                <label for="disqualified">Disqualified</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="checkbox" id='lead_hold' name="lead_hold" value="true" style="margin: 10px;"
                                <?php echo ($default_privilege['lead_hold'] == 'true')?'checked':''?>>
                                <label for="lead_hold">Lead Hold</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Calendar Role<span
                                        class="text-danger required">*</span></label>
                                <input type="radio" name="calendar_role" id='calendar_role' value="enable" style="margin: 10px;"
                                    onclick="handleCalClick(this);" <?php echo ($default_privilege['calendar_role'] == 'enable')?'checked':''?> required>Enable
                                <input type="radio" name="calendar_role" id='request_calendar' value="disable" style="margin: 10px;"
                                    onclick="handleCalClick(this);" <?php echo ($default_privilege['calendar_role'] == 'disable')?'checked':''?>>Disable
                            </div>
                        </div>
                    </div>
                    <div class="row show_calendar_roles">
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="checkbox" id='add_appointment_menu' name="add_appointment_menu" value="true" style="margin: 10px;"
                                <?php echo ($default_privilege['add_appointment_menu'] == 'true')?'checked':''?>>
                                <label for="add_appointment_menu">Add Appointment Menu</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="checkbox" id='booked' name="booked" value="true" style="margin: 10px;"
                                <?php echo ($default_privilege['booked'] == 'true')?'checked':''?>>
                                <label for="booked">Booked </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="checkbox" id='confirmed' name="confirmed" value="true" style="margin: 10px;"
                                <?php echo ($default_privilege['confirmed'] == 'true')?'checked':''?>>
                                <label for="confirmed">Confirmed</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="checkbox" id='rescheduled' name="rescheduled" value="true" style="margin: 10px;"
                                <?php echo ($default_privilege['rescheduled'] == 'true')?'checked':''?>>
                                <label for="rescheduled">Rescheduled</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="checkbox" id='canceled' name="canceled" value="true" style="margin: 10px;"
                                <?php echo ($default_privilege['canceled'] == 'true')?'checked':''?>>
                                <label for="canceled">Canceled</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="checkbox" id='no_show' name="no_show" value="true" style="margin: 10px;"
                                <?php echo ($default_privilege['no_show'] == 'true')?'checked':''?>>
                                <label for="no_show">No Show</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="checkbox" id='all_day_booking' name="all_day_booking" value="true" style="margin: 10px;"
                                <?php echo ($default_privilege['all_day_booking'] == 'true')?'checked':''?>>
                                <label for="all_day_booking">All Day Booking</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="checkbox" id='allow_to_select_multiple_slot' name="allow_to_select_multiple_slot" value="true" style="margin: 10px;"
                                <?php echo ($default_privilege['allow_to_select_multiple_slot'] == 'true')?'checked':''?>>
                                <label for="allow_to_select_multiple_slot">Allow to select Multiple Slot</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="checkbox" id='booking_info' name="booking_info" value="true" style="margin: 10px;"
                                <?php echo ($default_privilege['booking_info'] == 'true')?'checked':''?>>
                                <label for="booking_info">Booking Info</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="checkbox" id='additional_info' name="additional_info" value="true" style="margin: 10px;"
                                <?php echo ($default_privilege['additional_info'] == 'true')?'checked':''?>>
                                <label for="additional_info">Additional Info</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="checkbox" id='show_user_fields' name="show_user_fields" value="true" style="margin: 10px;"
                                <?php echo ($default_privilege['show_user_fields'] == 'true')?'checked':''?>>
                                <label for="show_user_fields">Show User Fields</label>
                            </div>
                        </div>
                    </div>
                    <button name="submit" type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>

    $(document).ready(function() {
        var myValue = $("input[type='radio'][name='request_followup']:checked").val();
        console.log(myValue);
        if(myValue == 'enable') {
            $('.show_req_follow').show();
        } else {
            $('.show_req_follow').hide();
        }

        var myStatusValue = $("input[type='radio'][name='request_status_update']:checked").val();
        console.log(myStatusValue);
        if(myStatusValue == 'enable') {
            $('.show_status_update').show();
        } else {
            $('.show_status_update').hide();
        }

        var myCal = $("input[type='radio'][name='calendar_role']:checked").val();
        console.log(myCal);
        if(myCal == 'enable') {
            $('.show_calendar_roles').show();
        } else {
            $('.show_calendar_roles').hide();
        }
    });

    function handleClick(myRadio) {
        console.log('New value: ' + myRadio.value);
        $val = myRadio.value;
        if($val == 'enable'){
            $('.show_req_follow').show();
        } else {
            $('.show_req_follow').hide();
        }
    }

    function handleStatusClick(myRadio) {
        console.log('New value: ' + myRadio.value);
        $val = myRadio.value;
        if($val == 'enable'){
            $('.show_status_update').show();
        } else {
            $('.show_status_update').hide();
        }
    }

    function handleCalClick(myRadio) {
        console.log('New value: ' + myRadio.value);
        $val = myRadio.value;
        if($val == 'enable'){
            $('.show_calendar_roles').show();
        } else {
            $('.show_calendar_roles').hide();
        }
    }
</script>