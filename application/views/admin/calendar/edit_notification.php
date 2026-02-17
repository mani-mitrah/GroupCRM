<style>
    .select_booking {
        display: flex;
        flex: 1;
    }

    .button-right {
        text-align: right;
    }

    .before_date,
    .after_date {
        display: flex;
        align-items: center;
    }

    .link_head {
        margin-bottom: 1rem;
    }

    .link {
        margin-right: 15px;
        font-weight: bold;
        font-size: large;
    }

    .link:hover {
        text-decoration: underline;
    }

    .card-body {
        padding: 1.5rem;
    }

    .form-control-sm {
        border-radius: 20px;
        height: calc(1.8125rem + 2px);
    }

    .form-control-sm.select-rounded {
        width: 100px;
        border-radius: 20px;
    }

    .hr-divider {
        border-bottom: 0.5px dotted #D3D3D3;
        margin: 10px 0;
    }

    .form-check-input {
        margin-top: 0.375rem;
    }
</style>

<div class="container mt-5">
    <?php
    if ($this->session->flashdata('alert') != NULL) {
        $message = $this->session->flashdata('alert');
        unset($_SESSION["alert"]);
    ?>
        <div class="alert alert-<?php echo $message['class']; ?> alert-dismissible fade show" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
            <?php echo $message['message']; ?>
        </div>
    <?php
    }
    ?>
    <h2>Notification Settings</h2>
    <div class="card">
        <div class="card-body">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="staff-tab" data-toggle="tab" href="#staff" role="tab" aria-controls="staff" aria-selected="true">Staff</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="customer-tab" data-toggle="tab" href="#customer" role="tab" aria-controls="customer" aria-selected="false">Customer</a>
                </li>
            </ul>

            <!-- Tab panes -->
            <form id="notificationForm" action="<?php echo base_url();?>admin/calendar/save_notification" method="POST">
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="staff" role="tabpanel" aria-labelledby="staff-tab">
                        <div class="row select_booking">
                            <div class="col">
                                <small class="form-text text-muted">When an Appointment is</small>
                            </div>
                            <div class="col" style="padding-right: 80px;">
                                <label class="form-text">Email</label>
                            </div>
                        </div>
                        <div class="hr-divider"></div>

                        <div class="row select_booking">
                            <div class="col">
                                <label class="form-text">Booked</label>
                            </div>
                            <div class="col">
                                <input class="form-check-input" type="checkbox" id="staffBooked" name="s_email_booked" <?php echo isset($notification) && $notification['s_email_booked'] == 1 ? 'checked' : ''; ?>>
                            </div>
                        </div>
                        <div class="hr-divider"></div>

                        <div class="row select_booking">
                            <div class="col">
                                <label class="form-text">Rescheduled</label>
                            </div>
                            <div class="col">
                                <input class="form-check-input" type="checkbox" id="staffRescheduled" name="s_email_rescheduled" <?php echo isset($notification) && $notification['s_email_rescheduled'] == 1 ? 'checked' : ''; ?>>
                            </div>
                        </div>
                        <div class="hr-divider"></div>

                        <div class="row select_booking">
                            <div class="col">
                                <label class="form-text">Canceled</label>
                            </div>
                            <div class="col">
                                <input class="form-check-input" type="checkbox" id="staffCanceled" name="s_email_cancelled" <?php echo isset($notification) && $notification['s_email_cancelled'] == 1 ? 'checked' : ''; ?>>
                            </div>
                        </div>
                        <div class="hr-divider"></div>

                        <div class="row select_booking">
                            <div class="col">
                                <label class="form-text">Marked As Completed</label>
                            </div>
                            <div class="col">
                                <input class="form-check-input" type="checkbox" id="staffCompleted" name="s_email_complete" <?php echo isset($notification) && $notification['s_email_complete'] == 1 ? 'checked' : ''; ?>>
                            </div>
                        </div>
                        <div class="hr-divider"></div>

                        <div class="row select_booking">
                            <div class="col">
                                <label class="form-text">Marked As NoShow</label>
                            </div>
                            <div class="col">
                                <input class="form-check-input" type="checkbox" id="staff_NoShow" name="s_email_noshow" <?php echo isset($notification) && $notification['s_email_noshow'] == 1 ? 'checked' : ''; ?>>

                            </div>
                        </div>
                        <div class="hr-divider"></div>

                        <div class="row select_booking">
                            <div class="col">
                                <small class="form-text text-muted">Remind Upcoming Appointments</small>
                            </div>
                        </div>
                        <div class="hr-divider"></div>
                        <div class="row select_booking">
                            <div class="col">
                                <div class="row">
                                    <div class="col-md-4 before_date">
                                        <label class="form-text">Before:</label>
                                        <input class="form-control form-control-sm select-rounded p-2 m-1" type="text" name="s_email_before_num[]" value="<?php echo empty(json_decode($notification['s_email_before_num'])[0]) ? '' : json_decode($notification['s_email_before_num'])[0]; ?>">
                                    </div>
                                    <div class="col-md-4 before_date">
                                        <?php
                                        // Decode JSON-encoded value
                                        $s_email_before_num_value = json_decode($notification['s_email_before_num_value'], true);

                                        // Set default selected value (assuming it's at index 1)
                                        $selected_value = isset($s_email_before_num_value[0]) ? $s_email_before_num_value[0] : '';
                                        ?>
                                        <select class="form-control form-control-sm select-rounded" name="s_email_before_num_value[]" id="s_timeUnitSelect2">
                                            <option value="minutes" <?php echo $selected_value === 'minutes' ? 'selected' : ''; ?>>Minutes</option>
                                            <option value="days" <?php echo $selected_value === 'days' ? 'selected' : ''; ?>>Days</option>
                                            <option value="hours" <?php echo $selected_value === 'hours' ? 'selected' : ''; ?>>Hours</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <input class="form-check-input" type="checkbox" id="staffNoShow1" <?php echo empty(json_decode($notification['s_email_before_num'])[0]) ? '' : 'checked'; ?>>
                            </div>
                        </div>
                        <div class="row select_booking">
                            <div class="col">
                                <div class="row">
                                    <div class="col-md-4 before_date">
                                        <label class="form-text">Before:</label>
                                        <input class="form-control form-control-sm select-rounded p-2 m-1" type="text" name="s_email_before_num[]" value="<?php echo empty(json_decode($notification['s_email_before_num'])[1]) ? '' : json_decode($notification['s_email_before_num'])[1]; ?>">
                                    </div>
                                    <div class="col-md-4 before_date">
                                        <?php
                                        // Decode JSON-encoded value
                                        $s_email_before_num_value = json_decode($notification['s_email_before_num_value'], true);

                                        // Set default selected value (assuming it's at index 1)
                                        $selected_value = isset($s_email_before_num_value[1]) ? $s_email_before_num_value[1] : '';
                                        ?>
                                        <select class="form-control form-control-sm select-rounded" name="s_email_before_num_value[]" id="s_timeUnitSelect2">
                                            <option value="minutes" <?php echo $selected_value === 'minutes' ? 'selected' : ''; ?>>Minutes</option>
                                            <option value="days" <?php echo $selected_value === 'days' ? 'selected' : ''; ?>>Days</option>
                                            <option value="hours" <?php echo $selected_value === 'hours' ? 'selected' : ''; ?>>Hours</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <input class="form-check-input" type="checkbox" id="staffNoShow2" <?php echo empty(json_decode($notification['s_email_before_num'])[1]) ? '' : 'checked'; ?>>
                            </div>
                        </div>
                        <div class="row select_booking">
                            <div class="col">
                                <div class="row">
                                    <div class="col-md-4 before_date">
                                        <label class="form-text">Before:</label>
                                        <input class="form-control form-control-sm select-rounded p-2 m-1" type="text" name="s_email_before_num[]" value="<?php echo empty(json_decode($notification['s_email_before_num'])[2]) ? '' : json_decode($notification['s_email_before_num'])[2]; ?>">
                                    </div>
                                    <div class="col-md-4 before_date">
                                        <?php
                                        // Decode JSON-encoded value
                                        $s_email_before_num_value = json_decode($notification['s_email_before_num_value'], true);

                                        // Set default selected value (assuming it's at index 1)
                                        $selected_value = isset($s_email_before_num_value[2]) ? $s_email_before_num_value[2] : '';
                                        ?>
                                        <select class="form-control form-control-sm select-rounded" name="s_email_before_num_value[]" id="s_timeUnitSelect3">
                                            <option value="minutes" <?php echo $selected_value === 'minutes' ? 'selected' : ''; ?>>Minutes</option>
                                            <option value="days" <?php echo $selected_value === 'days' ? 'selected' : ''; ?>>Days</option>
                                            <option value="hours" <?php echo $selected_value === 'hours' ? 'selected' : ''; ?>>Hours</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <input class="form-check-input" type="checkbox" id="staffNoShow3" <?php echo empty(json_decode($notification['s_email_before_num'])[2]) ? '' : 'checked'; ?>>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="customer" role="tabpanel" aria-labelledby="customer-tab">
                        <div class="row select_booking">
                            <div class="col">
                                <small class="form-text text-muted">When an Appointment is</small>
                            </div>
                            <div class="col" style="padding-right: 80px;">
                                <label class="form-text">Email</label>
                            </div>
                        </div>
                        <div class="hr-divider"></div>

                        <div class="row select_booking">
                            <div class="col">
                                <label class="form-text">Booked</label>
                            </div>
                            <div class="col">
                                <input class="form-check-input" type="checkbox" id="customerBooked" name="c_email_booked" <?php echo isset($notification) && $notification['c_email_booked'] == 1 ? 'checked' : ''; ?>>
                            </div>
                        </div>
                        <div class="hr-divider"></div>

                        <div class="row select_booking">
                            <div class="col">
                                <label class="form-text">Rescheduled</label>
                            </div>
                            <div class="col">
                                <input class="form-check-input" type="checkbox" id="customerRescheduled" name="c_email_rescheduled" <?php echo isset($notification) && $notification['c_email_rescheduled'] == 1 ? 'checked' : ''; ?>>
                            </div>
                        </div>
                        <div class="hr-divider"></div>

                        <div class="row select_booking">
                            <div class="col">
                                <label class="form-text">Canceled</label>
                            </div>
                            <div class="col">
                                <input class="form-check-input" type="checkbox" id="customerCanceled" name="c_email_cancelled" <?php echo isset($notification) && $notification['c_email_cancelled'] == 1 ? 'checked' : ''; ?>>
                            </div>
                        </div>
                        <div class="hr-divider"></div>

                        <div class="row select_booking">
                            <div class="col">
                                <label class="form-text">Marked As Completed</label>
                            </div>
                            <div class="col">
                                <input class="form-check-input" type="checkbox" id="customerCompleted" name="c_email_complete" <?php echo isset($notification) && $notification['c_email_complete'] == 1 ? 'checked' : ''; ?>>
                            </div>
                        </div>
                        <div class="hr-divider"></div>

                        <div class="row select_booking">
                            <div class="col">
                                <label class="form-text">Marked As NoShow</label>
                            </div>
                            <div class="col">
                                <input class="form-check-input" type="checkbox" id="customer_NoShow" name="c_email_noshow" <?php echo isset($notification) && $notification['c_email_noshow'] == 1 ? 'checked' : ''; ?>>

                            </div>
                        </div>
                        <div class="hr-divider"></div>

                        <div class="row select_booking">
                            <div class="col">
                                <small class="form-text text-muted">Remind Upcoming Appointments</small>
                            </div>
                        </div>
                        <div class="hr-divider"></div>
                        <div class="row select_booking">
                            <div class="col">
                                <div class="row">
                                    <div class="col-md-4 before_date">
                                        <label class="form-text">Before:</label>
                                        <input class="form-control form-control-sm select-rounded p-2 m-1" type="text" name="c_email_before_num[]"  value="<?php echo empty(json_decode($notification['c_email_before_num'])[0]) ? '' : json_decode($notification['c_email_before_num'])[0]; ?>">
                                    </div>
                                    <div class="col-md-4 before_date">
                                        <?php
                                        // Decode JSON-encoded value
                                        $c_email_before_num_value = json_decode($notification['c_email_before_num_value'], true);

                                        // Set default selected value (assuming it's at index 1)
                                        $selected_value = isset($c_email_before_num_value[0]) ? $c_email_before_num_value[0] : '';
                                        ?>
                                        <select class="form-control form-control-sm select-rounded" name="c_email_before_num_value[]" id="c_timeUnitSelect1">
                                            <option value="minutes" <?php echo $selected_value === 'minutes' ? 'selected' : ''; ?>>Minutes</option>
                                            <option value="days" <?php echo $selected_value === 'days' ? 'selected' : ''; ?>>Days</option>
                                            <option value="hours" <?php echo $selected_value === 'hours' ? 'selected' : ''; ?>>Hours</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <input class="form-check-input" type="checkbox" id="customerNoShow1" name="s_email_mark_num_value_check" <?php echo empty(json_decode($notification['c_email_before_num'])[0]) ? '' : 'checked'; ?>>
                            </div>
                        </div>
                        <div class="row select_booking">
                            <div class="col">
                                <div class="row">
                                    <div class="col-md-4 before_date">
                                        <label class="form-text">Before:</label>
                                        <input class="form-control form-control-sm select-rounded p-2 m-1" type="text" name="c_email_before_num[]"  value="<?php echo empty(json_decode($notification['c_email_before_num'])[1]) ? '' : json_decode($notification['c_email_before_num'])[1]; ?>">
                                    </div>
                                    <div class="col-md-4 before_date">
                                        <?php
                                        // Decode JSON-encoded value
                                        $c_email_before_num_value = json_decode($notification['c_email_before_num_value'], true);

                                        // Set default selected value (assuming it's at index 1)
                                        $selected_value = isset($c_email_before_num_value[1]) ? $c_email_before_num_value[1] : '';
                                        ?>
                                        <select class="form-control form-control-sm select-rounded" name="c_email_before_num_value[]" id="c_timeUnitSelect2">
                                            <option value="minutes" <?php echo $selected_value === 'minutes' ? 'selected' : ''; ?>>Minutes</option>
                                            <option value="days" <?php echo $selected_value === 'days' ? 'selected' : ''; ?>>Days</option>
                                            <option value="hours" <?php echo $selected_value === 'hours' ? 'selected' : ''; ?>>Hours</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <input class="form-check-input" type="checkbox" id="customerNoShow2" <?php echo empty(json_decode($notification['c_email_before_num'])[1]) ? '' : 'checked'; ?>>
                            </div>
                        </div>
                        <div class="row select_booking">
                            <div class="col">
                                <div class="row">
                                    <div class="col-md-4 before_date">
                                        <label class="form-text">Before:</label>
                                        <input class="form-control form-control-sm select-rounded p-2 m-1" type="text" name="c_email_before_num[]"  value="<?php echo empty(json_decode($notification['c_email_before_num'])[2]) ? '' : json_decode($notification['c_email_before_num'])[2]; ?>">
                                    </div>
                                    <div class="col-md-4 before_date">
                                        <?php
                                        // Decode JSON-encoded value
                                        $c_email_before_num_value = json_decode($notification['c_email_before_num_value'], true);

                                        // Set default selected value (assuming it's at index 1)
                                        $selected_value = isset($c_email_before_num_value[2]) ? $c_email_before_num_value[2] : '';
                                        ?>
                                        <select class="form-control form-control-sm select-rounded" name="c_email_before_num_value[]" id="c_timeUnitSelect3">
                                            <option value="minutes" <?php echo $selected_value === 'minutes' ? 'selected' : ''; ?>>Minutes</option>
                                            <option value="days" <?php echo $selected_value === 'days' ? 'selected' : ''; ?>>Days</option>
                                            <option value="hours" <?php echo $selected_value === 'hours' ? 'selected' : ''; ?>>Hours</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <input class="form-check-input" type="checkbox" id="customerNoShow3" <?php echo empty(json_decode($notification['c_email_before_num'])[2]) ? '' : 'checked'; ?>>
                            </div>
                            <input type="hidden" name="calendar_id" value="<?php echo $notification['calendar_id'];?>">
                        </div>
                    </div>
                    <!-- Save Button -->
                    <div class="mt-4 button-right">
                        <button type="submit" class="btn btn-primary" id="saveButton" name="submit">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {

        const saveButton = document.getElementById('saveButton');

        saveButton.addEventListener('click', function() {
            // Perform any form validation or data processing here if needed

            // Redirect to the specified URL
            window.location.href = 'admin/calendar/notification';
        });
        const dropdowns = document.querySelectorAll('select[id^="c_timeUnitSelect"]');
        const checkboxes = document.querySelectorAll('input[id^="customerNoShow"]');

        function updateCheckboxNameCustomer(dropdown, checkbox) {
            const selectedValue = dropdown.value;
            let newName = '';

            switch (selectedValue) {
                case 'days':
                    newName = 'c_email_mark_num_value_check_days';
                    break;
                case 'minutes':
                    newName = 'c_email_mark_num_value_check_minutes';
                    break;
                case 'hours':
                    newName = 'c_email_mark_num_value_check_hours';
                    break;
                default:
                    newName = 'c_email_mark_num_value_check';
                    break;
            }

            checkbox.name = newName;
        }

        // Initialize names based on the default selection
        dropdowns.forEach((dropdown, index) => {
            const checkbox = checkboxes[index];
            updateCheckboxNameCustomer(dropdown, checkbox);
        });

        // Update checkbox name whenever the selection changes
        dropdowns.forEach((dropdown, index) => {
            dropdown.addEventListener('change', function() {
                const checkbox = checkboxes[index];
                updateCheckboxNameCustomer(dropdown, checkbox);
            });
        });

        const dropdownstaff = document.querySelectorAll('select[id^="s_timeUnitSelect"]');
        const checkboxestaff = document.querySelectorAll('input[id^="staffNoShow"]');

        function updateCheckboxNameCustomer(dropdown, checkbox) {
            const selectedValue = dropdown.value;
            let newName = '';

            switch (selectedValue) {
                case 'days':
                    newName = 'c_email_mark_num_value_check_days';
                    break;
                case 'minutes':
                    newName = 'c_email_mark_num_value_check_minutes';
                    break;
                case 'hours':
                    newName = 'c_email_mark_num_value_check_hours';
                    break;
                default:
                    newName = 'c_email_mark_num_value_check';
                    break;
            }

            checkbox.name = newName;
        }

        // Initialize names based on the default selection
        dropdownstaff.forEach((dropdown, index) => {
            const checkbox = checkboxestaff[index];
            updateCheckboxNameCustomer(dropdown, checkbox);
        });

        // Update checkbox name whenever the selection changes
        dropdownstaff.forEach((dropdown, index) => {
            dropdown.addEventListener('change', function() {
                const checkbox = checkboxestaff[index];
                updateCheckboxNameCustomer(dropdown, checkbox);
            });
        });
    });
</script>