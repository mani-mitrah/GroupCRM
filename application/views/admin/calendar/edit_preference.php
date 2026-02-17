<script src="/assets_new/richtext/jquery.richtext.js"></script>
    <link rel="stylesheet" href="/assets_new/richtext/richtext.min.css">
    <style>
        .select_slot,
        .select_booking,
        .select_cancel {
            display: flex;

        }

        .form-control-sm {
            border-radius: 20px;
            height: calc(1.8125rem + 2px);
        }

        .form-control-sm.select-rounded {
            width: 100px;
            border-radius: 20px;
        }

        .button-right {
            text-align: right;
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

        .hr-divider {
            border-bottom: 0.5px dotted #D3D3D3;
            margin: 10px 0;
        }

        .form-check-input {
            margin-top: 0.375rem;
        }

        .underline-title {
            position: relative;
            margin: 5px 0;
        }

        .underline-title::after {
            content: "";
            position: absolute;
            left: 0;
            bottom: 0;
            width: 100%;
            height: 2px;
            background: linear-gradient(to right, black, transparent 10%);

        }

        .html-editor .bold {
            font-weight: 700;
        }

        .html-editor .html-editor-toolbar button.btn {
            padding: 6px 10px;
            margin-bottom: 0 !important;
            background-color: transparent;
            cursor: pointer;
        }

        /* Container for the toggle switch */
        .custom-toggle {
            display: inline-block;
            position: relative;
            width: 60px;
            /* Adjust based on your design */
            height: 28px;
            /* Adjust based on your design */
        }

        /* Hide the default checkbox */
        .custom-toggle input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        /* Style the slider (the moving part) */
        .custom-toggle label {
            position: absolute;
            cursor: pointer;
            background-color: #ccc;
            /* Background color when off */
            border-radius: 34px;
            /* Curved corners */
            height: 100%;
            width: 100%;
            transition: background-color 0.3s;
        }

        /* Style the slider handle */
        .custom-toggle label::before {
            content: "";
            position: absolute;
            height: 20px;
            /* Adjust based on your design */
            width: 20px;
            /* Adjust based on your design */
            border-radius: 50%;
            background-color: white;
            transition: transform 0.3s;
            left: 4px;
            /* Offset from the left edge */
            top: 4px;
            /* Offset from the top edge */
        }

        /* Change the background color when the checkbox is checked */
        .custom-toggle input:checked+label {
            background-color: #4CAF50;
            /* Background color when on */
        }

        /* Move the slider handle when checked */
        .custom-toggle input:checked+label::before {
            transform: translateX(26px);
            /* Move the handle */
        }

        #cancellationCard {
            display: block;
            /* Ensure it's shown by default if toggle is checked */
        }
    </style>
    <div class="card">
        <div class="card-body">
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
                <!-- Start of the single form -->
                <form id="settingsForm" action="<?php echo base_url();?>admin/calendar/save_preference" method="post">

                    <!-- Scheduling Interval Section -->
                    <div class="container">
                        <label>Scheduling Interval</label>
                        <div class="underline-title" style="border-bottom: 1px dotted #D3D3D3; margin: 5px 0;"></div><br>

                        <div class="card">
                            <div class="card-body">
                                <div class="form-group">
                                    <small class="form-text text-muted" style="font-size: 15px;">
                                        Set the interval for the appointment start times. For example, if you choose 30 minutes, your scheduled start times might look like this: 9:00 AM, 9:30 AM, 10:00 AM, and so on.
                                    </small>
                                </div>
                                <div class="form-group">
                                    <small class="form-text text-muted font-weight-bold" style="font-size: 15px;">
                                        How should the scheduling intervals work?
                                    </small>
                                </div>
                                <div class="row select_booking">
                                    <div class="col">
                                        <div class="row">
                                            <!-- Hours Dropdown -->
                                            <div class="col-md-4">
                                                <label class="form-text text-muted" for="hoursDropdown1">Hours</label>
                                                <select id="hoursDropdown1" class="form-control form-control-sm select-rounded hoursDropdown" name="scheduling_interval_hr">
                                                    <!-- Options will be populated dynamically -->
                                                </select>
                                            </div>
                                            <!-- Minutes Dropdown -->
                                            <div class="col-md-4">
                                                <label class="form-text text-muted" for="minutesDropdown1">Minutes</label>
                                                <select id="minutesDropdown1" class="form-control form-control-sm select-rounded minutesDropdown" name="scheduling_interval_mi">
                                                    <!-- Options will be populated dynamically -->
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <small class="form-text text-muted" style="font-size: 10px;">
                                        Time slots adjust to accommodate other appointments and external events (e.g., for 30-minute intervals, if there's an event scheduled from 10:00 to 10:15, the slots will be 9:00, 9:30, 10:15, 10:45, etc.).
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Booking Notice Section -->
                    <div class="container mt-5">
                        <label>Booking Notice</label>
                        <div class="underline-title" style="border-bottom: 1px dotted #D3D3D3; margin: 5px 0;"></div><br>

                        <div class="card mb-4">
                            <div class="card-body">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <small class="form-text text-muted" style="font-size: 15px;">Minimum Booking Notice</small>
                                            <small class="form-text text-muted" style="font-size: 15px;">To avoid last-minute bookings, choose how much notice is required when an appointment is booked.</small>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-row">
                                                <div class="form-group col">
                                                    <label class="form-text text-muted" for="days">Days</label>
                                                    <input id="days" class="form-control form-control-sm select-rounded p-2 m-1" type="text" name="minimum_booking_notice_set_value[]" value="<?php echo empty(json_decode($preference['minimum_booking_notice_set_value'])[0]) ? '0' : json_decode($preference['minimum_booking_notice_set_value'])[0]; ?>">
                                                </div>
                                                <div class="form-group col">
                                                    <label class="form-text text-muted" for="hoursDropdown2">Hours</label>
                                                    <select id="hoursDropdown2" class="form-control form-control-sm select-rounded hoursDropdown" name="minimum_booking_notice_set_value[]">
                                                        <!-- Options will be populated dynamically -->
                                                    </select>
                                                </div>
                                                <div class="form-group col">
                                                    <label class="form-text text-muted" for="minutesDropdown2">Minutes</label>
                                                    <select id="minutesDropdown2" class="form-control form-control-sm select-rounded minutesDropdown" name="minimum_booking_notice_set_value[]">
                                                        <!-- Options will be populated dynamically -->
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card mb-4">
                            <div class="card-body">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <small class="form-text text-muted" style="font-size: 15px;">Maximum Booking Notice</small>
                                            <small class="form-text text-muted" style="font-size: 15px;">To avoid bookings that are too far out, choose how far in advance an appointment can be booked.</small>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-row">
                                                <div class="form-group col">
                                                    <label class="form-text text-muted" for="days">Days</label>
                                                    <input id="days" class="form-control form-control-sm select-rounded p-2 m-1" type="text" name="maximum_booking_notice_set_value[]" value="<?php echo empty(json_decode($preference['maximum_booking_notice_set_value'])[0]) ? '0' : json_decode($preference['maximum_booking_notice_set_value'])[0]; ?>">
                                                </div>
                                                <div class="form-group col">
                                                    <label class="form-text text-muted" for="hoursDropdown3">Hours</label>
                                                    <select id="hoursDropdown3" class="form-control form-control-sm select-rounded hoursDropdown" name="maximum_booking_notice_set_value[]">
                                                        <!-- Options will be populated dynamically -->
                                                    </select>
                                                </div>
                                                <div class="form-group col">
                                                    <label class="form-text text-muted" for="minutesDropdown3">Minutes</label>
                                                    <select id="minutesDropdown3" class="form-control form-control-sm select-rounded minutesDropdown" name="maximum_booking_notice_set_value[]">
                                                        <!-- Options will be populated dynamically -->
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Buffer Time -->
                    <div class="container mt-5">
                        <label>Buffer Time</label>
                        <div class="underline-title" style="border-bottom: 1px dotted #D3D3D3; margin: 5px 0;"></div><br>

                        <div class="card mb-4">
                            <div class="card-body">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <small class="form-text text-muted" style="font-size: 15px;">Buffer Time</small>
                                            <small class="form-text text-muted" style="font-size: 15px;">To extend the slot time.</small>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-row">
                                                <div class="form-group col">
                                                    <label class="form-text text-muted" for="hoursDropdown4">Hours</label>
                                                    <select id="hoursDropdown4" class="form-control form-control-sm select-rounded hoursDropdown" name="buffer_time_hr">
                                                        <!-- Options will be populated dynamically -->
                                                    </select>
                                                </div>
                                                <div class="form-group col">
                                                    <label class="form-text text-muted" for="minutesDropdown4">Minutes</label>
                                                    <select id="minutesDropdown4" class="form-control form-control-sm select-rounded minutesDropdown" name="buffer_time_min">
                                                        <!-- Options will be populated dynamically -->
                                                    </select>
                                                    <input type="hidden" name="calendar_id" value="<?php echo $preference['calendar_id'];?>" >
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Terms and Conditions Section -->
                    <div class="container mt-5">
                        <label>Terms and Conditions</label>
                        <!-- Toggle Switch -->
                        <div class="custom-toggle">
                            <input type="checkbox" id="termsToggle" name="terms_and_conditions" <?php echo $preference['terms_and_conditions'] == 'enable' ? "checked":"" ;?>>
                            <label for="termsToggle"></label>
                        </div>

                        <div class="underline-title" style="border-bottom: 1px dotted #D3D3D3;"></div>
                        <br>

                        <div class="card" id="termsCard">
                            <div class="card-body">
                                <div class="form-group">
                                    <small class="form-text text-muted" style="font-size: 15px;">Message</small>
                                    <textarea class="form-control" id="textarea-field" style="height: 90px !important; border-radius: 1px; border-color: #DADADA;" rows="3" name="terms_and_conditions_data"> <?php echo empty($preference["terms_and_conditions_data"]) ? '' : $preference["terms_and_conditions_data"]; ?></textarea>
                                </div>
                            </div>
                            <div class="form-text">
                                <small class="form-text text-muted" style="font-size: 12px;">This adds a message and a mandatory checkbox on your booking page. Include a link in your message and redirect customers to read your privacy policy or terms and conditions.</small>
                            </div>
                        </div>


                        <label>Cancellations & Reschedule</label>
                        <!-- Toggle Switch -->
                        <div class="custom-toggle">
                            <input type="checkbox" id="customToggle" name="cancellations_and_reschedule" <?php echo $preference['cancellations_and_reschedule'] == 'enable' ? "checked":"" ;?>>
                            <label for="customToggle"></label>
                        </div>

                        <div class="underline-title" style="border-bottom: 1px dotted #D3D3D3; margin: 5px 0;"></div><br>

                        <div class="card" id="cancellationCard">
                            <div class="card-body">
                                <div class="form-group select_cancel" style="display: flex; align-items: center;">
                                    <div class="col-md-6">
                                        <small class="form-text text-muted" style="font-size: 15px;">Cancellation Notice</small>
                                        <small class="form-text text-muted" style="font-size: 15px;">Specify how much notice is required for cancellations or rescheduling.</small>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-row">
                                            <div class="form-group col">
                                                <label class="form-text text-muted" for="days">Days</label>
                                                <input id="days" class="form-control form-control-sm select-rounded p-2 m-1" type="text" name="cancellation_and_rescheduling_set_value[]" value="<?php echo empty(json_decode($preference['cancellation_and_rescheduling_set_value'])[0]) ? '0' : json_decode($preference['cancellation_and_rescheduling_set_value'])[0]; ?>">
                                            </div>
                                            <div class="form-group col">
                                                <label class="form-text text-muted" for="hoursDropdown5">Hours</label>
                                                <select id="hoursDropdown5" class="form-control form-control-sm select-rounded hoursDropdown" name="cancellation_and_rescheduling_set_value[]">
                                                    <!-- Options will be populated dynamically -->
                                                </select>
                                            </div>
                                            <div class="form-group col">
                                                <label class="form-text text-muted" for="minutesDropdown5">Minutes</label>
                                                <select id="minutesDropdown5" class="form-control form-control-sm select-rounded minutesDropdown" name="cancellation_and_rescheduling_set_value[]">
                                                    <!-- Options will be populated dynamically -->
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Save Button -->
                    <div class="container mt-5 text-right">
                        <button type="submit" class="btn btn-primary" name="submit">Save All Preferences</button>
                    </div>
                </form>
                <!-- End of the single form -->
            </div>
        </div>
    </div>
    <?php $preferenceJson = json_encode($preference); ?>
    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            var preferenceData = <?php echo $preferenceJson; ?>;
            // Function to populate a dropdown with options
            // Function to populate a dropdown with options
            function populateDropdown(dropdown, options, unitSingular, unitPlural) {
                dropdown.innerHTML = ''; // Clear existing options
                options.forEach(option => {
                    const opt = document.createElement('option');
                    const formattedOption = option < 10 ? `0${option}` : option; // Format single-digit hours with leading zero
                    opt.value = formattedOption;
                    opt.textContent = `${formattedOption} ${option === 1 ? unitSingular : unitPlural}`;
                    dropdown.appendChild(opt);
                });
            }

            // Function to set the selected value in a dropdown
            function setDropdownValue(dropdown, value) {
                if (dropdown) {
                dropdown.querySelector(`option[value="${value}"]`).selected = true;
                }
            }

            // Populate all hours and minutes dropdowns
            function populateAllDropdowns() {
                const hoursOptions = Array.from({
                    length: 24
                }, (_, i) => i);
                const minutesOptions = [0, 15, 30, 45, 55];

                // Populate hours dropdowns
                document.querySelectorAll('.hoursDropdown').forEach(dropdown => {
                    populateDropdown(dropdown, hoursOptions, 'hr', 'hrs');
                });

                // Populate minutes dropdowns
                document.querySelectorAll('.minutesDropdown').forEach(dropdown => {
                    populateDropdown(dropdown, minutesOptions, 'min', 'mins');
                });

                // Set selected values based on the preferenceData
                const setValue = (type, selector, defaultValue) => {
                    // console.log(preferenceData[type]);
                    const [hours, minutes] = preferenceData[type].split(':');
                    setDropdownValue(document.querySelector(`${selector}`), hours);
                    setDropdownValue(document.querySelector(`${defaultValue}`), minutes);
                };

                setValue('scheduling_interval', '#hoursDropdown1', '#minutesDropdown1');
                setValue('minimum_booking_notice_time', '#hoursDropdown2', '#minutesDropdown2');
                setValue('maximum_booking_notice_time', '#hoursDropdown3', '#minutesDropdown3');
                setValue('buffer_time', '#hoursDropdown4', '#minutesDropdown4');
                setValue('cancellation_and_rescheduling_time', '#hoursDropdown5', '#minutesDropdown5');
            }

            // Call the function to populate dropdowns
            populateAllDropdowns();

            
        });
        const toggleSwitch = document.getElementById('customToggle');
            const cancellationCard = document.getElementById('cancellationCard');

            // Function to handle toggle change
            function handleToggleChange() {
                if (toggleSwitch.checked) {
                    cancellationCard.style.display = 'block';
                } else {
                    cancellationCard.style.display = 'none';
                }
            }

            // Initial check
            handleToggleChange();

            // Add event listener to handle changes
            toggleSwitch.addEventListener('change', handleToggleChange);

            const termsToggle = document.getElementById('termsToggle');
            const termsCard = document.getElementById('termsCard');

            // Function to handle toggle change
            function handleTermsToggleChange() {
                if (termsToggle.checked) {
                    termsCard.style.display = 'block';
                } else {
                    termsCard.style.display = 'none';
                }
            }

            // Initial check
            handleTermsToggleChange();

            // Add event listener to handle changes
            termsToggle.addEventListener('change', handleTermsToggleChange);

        $('#textarea-field').richText();
    </script>