<style type="text/css">
    .field-icon {
        float: right;
        margin-left: -25px;
        margin-top: -25px;
        position: relative;
        z-index: 2;
    }

    .action-btn {
        height: 50px;
    }
    
    .timeslot:not(#timeslot) .action-btn:after {
        content: "-";
        position: relative;
        top: -2px;
    }

    #timeslot .action-btn:after {
        content: "+";
        position: relative;
        top: -2px;
    }

    .timeslots div:not(#timeslot) label {
        display: none;
    }

    .timeslots #timeslot label {
        display: block !important;
    }
</style>
<div class="row page-titles mx-0">
    <div class="col-sm-6 p-md-0">
        <div class="welcome-text">
            <h4>
                <?php echo $page_title; ?>
            </h4>
            <span>Edit User's Timeslot</span>
        </div>
    </div>
    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
        <a class="btn btn-primary waves-effect waves-light float-right"
            href="<?php echo base_url(); ?>admin/usertimeslot">
            <i class="mdi mdi-plus mr-2"></i> VIEW ALL
        </a>
    </div>
</div>


<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
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
                <form action="" method="post" id="userslots_form">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <input type="hidden" name="user_timeslot_user_id" value="<?php echo $default[0]['user_timeslot_user_id']; ?>">
                                <label for="exampleInputEmail1">User <span class="text-danger required">*</span></label>
                                <select class="form-control" required disabled>
                                    <!-- <option value="">-- Select --</option> -->
                                    <?php
                                    // print_r($lead_details);
                                    $lead_users = $this->user_model->get_lead_category_users($lead_details['category_id']);
                                    // print_r($lead_users);
                                    log_message('error', $this->db->last_query());
                                    foreach ($lead_users as $keys => $values) {
                                    ?>
                                        <option value="<?php echo $values['user_id']; ?>"
                                            data-filter="<?php echo str_replace(',', '', $values["group_s"]); ?>"
                                            <?php if ($default[0]["user_timeslot_user_id"] == $values['user_id']) {
                                                echo "selected";
                                            } ?>>
                                            <?php
                                            $urole_id = $values['role_id'];
                                            $user_role = ($urole_id == 2) ? 'CSA' : (($urole_id == 6) ? 'Cordinator' : 'Team Lead');
                                            echo $values["first_name"] . ' ' . $values["last_name"] . "  (" . $user_role . ") [" . $values["employee_id"] . "]";
                                            ?>
                                        </option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                    </div>

                    <div class="row border">
                        <div class="col-12">
                            <fieldset class="timeslots">
                                <legend>Slot Allocation</legend>
                                <?php $i = 0;
                                foreach ($default as $slot) {
                                    $i = $i + 1;
                                ?>
                                <div class="row timeslot" <?php if ($i == 1) { ?> id="timeslot" <?php } ?>>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Day <span
                                                    class="text-danger required">*</span></label>
                                            <select name="user_timeslot_day[]" class="form-control" required>
                                                <option value="">-- Select --</option>
                                                <option value="0" <?php if ($slot['user_timeslot_day'] == 0) {
                                                                        echo 'selected';
                                                                    } ?>>Monday</option>
                                                <option value="1" <?php if ($slot['user_timeslot_day'] == 1) {
                                                                        echo 'selected';
                                                                    } ?>>Tuesday</option>
                                                <option value="2" <?php if ($slot['user_timeslot_day'] == 2) {
                                                                        echo 'selected';
                                                                    } ?>>Wednesday</option>
                                                <option value="3" <?php if ($slot['user_timeslot_day'] == 3) {
                                                                        echo 'selected';
                                                                    } ?>>Thursday</option>
                                                <option value="4" <?php if ($slot['user_timeslot_day'] == 4) {
                                                                        echo 'selected';
                                                                    } ?>>Friday</option>
                                                <option value="5" <?php if ($slot['user_timeslot_day'] == 5) {
                                                                        echo 'selected';
                                                                    } ?>>Saturday</option>
                                                <option value="6" <?php if ($slot['user_timeslot_day'] == 6) {
                                                                        echo 'selected';
                                                                    } ?>>Sunday</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Timeslot <span
                                                    class="text-danger required">*</span></label>
                                            <select name="user_timeslot_slot_id[]" class="form-control" required>
                                                <?php
                                                foreach ($timeslot as $keys => $values) {
                                                ?>
                                                    <option value="<?php echo $values['timeslot_id']; ?>" <?php if ($slot['user_timeslot_slot_id'] == $values["timeslot_id"]) {
                                                                                                                echo 'selected';
                                                                                                            } ?>>
                                                        <?php
                                                        echo $values["timeslot_name"];
                                                        ?>
                                                    </option>
                                                    <?php
                                                }
                                                    ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-2">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Slot Count <span
                                                    class="text-danger required">*</span></label>
                                            <input type="number" class="form-control" name="user_timeslot_slot_count[]" value="<?php echo $slot['user_timeslot_slot_count']; ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-1">
                                        <div class="form-group">
                                            <label for="action">Action</label>
                                            <button class="btn btn-primary action-btn"></button>
                                        </div>
                                    </div>
                                </div>
                                <?php } ?>
                            </fieldset>

                        </div>
                    </div>

                    <button name="submit" type="submit" class="btn btn-primary mt-4">Update</button>

                </form>


                <div class="d-none" id="default_timeslot">
                    <div class="col-4">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Day<span
                                    class="text-danger required">*</span></label>
                            <select name="user_timeslot_day[]" class="form-control" required>
                                <option value="">-- Select --</option>
                                <option value="0">Monday</option>
                                <option value="1">Tuesday</option>
                                <option value="2">Wednesday</option>
                                <option value="3">Thursday</option>
                                <option value="4">Friday</option>
                                <option value="5">Saturday</option>
                                <option value="6">Sunday</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Timeslot<span
                                    class="text-danger required">*</span></label>
                            <select name="user_timeslot_slot_id[]" class="form-control" required>
                                <option value="">-- Select --</option>
                                <?php
                                foreach ($timeslot as $keys => $values) {
                                ?>
                                    <option value="<?php echo $values['timeslot_id']; ?>">
                                        <?php
                                        echo $values["timeslot_name"];
                                        ?>
                                    </option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-2">
    <div class="form-group">
        <label for="exampleInputEmail1">Slot Count<span class="text-danger required">*</span></label>
        <input type="number" 
               class="form-control" 
               name="user_timeslot_slot_count[]" 
               required 
               onkeypress="preventEnter(event)">
    </div>
</div>
                    <div class="col-1">
                        <div class="form-group">
                            <label for="action">Action</label>
                            <button class="btn btn-primary action-btn"></button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    var timeslot = $("#default_timeslot").html();

    function slotInit() {
        $(".timeslot:not(#timeslot) .action-btn").off();
        $(".timeslot:not(#timeslot) .action-btn").click(function(e) {
            e.preventDefault();
            $(this).closest(".timeslot").remove();
        });
    }
    $("#timeslot .action-btn").click(function(e) {
        e.preventDefault();
        $(".timeslots").append("<div class='row timeslot'>" + timeslot + "</div>");
        slotInit();
    });

    // $("#userslots_form").on("submit", function(e) {
    //     // e.preventDefault();
    //     // alert("There");
    //     $(".timeslot").removeClass("bg-danger-alt shadow");
    //     var slot = [];
    //     $(".timeslot").each(function() {
    //         var s = $(this).find("[name='user_timeslot_day[]']").val() + "-" + $(this).find("[name='user_timeslot_slot_id[]']").val();
    //         if (slot.indexOf(s) != -1) {
    //             alert("Timeslot Duplicate Found. Please resolve that");
    //             e.preventDefault();
    //             $(this).addClass("bg-danger-alt shadow");
    //             return false;
    //         }
    //         slot.push(s);
    //     });
    //     $("#userslots_form").off();
    //     $("#userslots_form").submit();
    // });

    var timeslot = $("#default_timeslot").html();

    function slotInit() {
        $(".timeslot:not(#timeslot) .action-btn").off();
        $(".timeslot:not(#timeslot) .action-btn").click(function(e) {
            e.preventDefault();
            $(this).closest(".timeslot").remove();
            $(".validation-message").remove(); // Remove validation message when slot is removed
        });
    }

    $("#timeslot .action-btn").click(function(e) {
        e.preventDefault();
        $(".timeslots").append("<div class='row timeslot'>" + timeslot + "</div>");
        slotInit();
    });

    function checkDuplicateSlots() {
        let slots = [];
        let isDuplicate = false;

        $(".validation-message").remove(); // Clear previous messages

        $(".timeslot").each(function() {
            let daySelect = $(this).find("select[name='user_timeslot_day[]']");
            let slotSelect = $(this).find("select[name='user_timeslot_slot_id[]']");
            let slotCountInput = $(this).find("input[name='user_timeslot_slot_count[]']");

            let day = daySelect.val();
            let slot = slotSelect.val();
            let slotCount = parseInt(slotCountInput.val(), 10);

            let slotKey = day + "-" + slot;

            // Check for duplicate timeslot
            if (slots.includes(slotKey)) {
                isDuplicate = true;

                // Show message below the timeslot field
                if (!daySelect.next(".validation-message").length) {
                    daySelect.after("<div class='validation-message' style='color: red; font-size: 12px; margin-top: 5px;'>Duplicate timeslot selected!</div>");
                }
            } else {
                slots.push(slotKey);
            }

            // Check if slot count is less than 0
            if (slotCount <= 0) {
                if (!slotCountInput.next(".validation-message").length) {
                    slotCountInput.after("<div class='validation-message' style='color: red; font-size: 12px; margin-top: 5px;'>Slot count cannot be less than 0!</div>");
                }
                isDuplicate = true; // Set to true to prevent form submission
            }
        });

        return isDuplicate;
    }

    // Form validation before submission
    $("#userslots_form").on("submit", function(e) {
        $(".validation-message").remove(); // Clear previous messages

        if (checkDuplicateSlots()) {
            e.preventDefault();
            return false;
        }
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const timeslotsContainer = document.querySelector('.timeslots');

        // Event delegation for the action buttons
        timeslotsContainer.addEventListener('click', function (e) {
            if (e.target.classList.contains('action-btn')) {
                const parentTimeslot = e.target.closest('.timeslot');
                const isAddButton = parentTimeslot.id === 'timeslot';

                if (isAddButton) {
                    // Clone the first timeslot and reset its values
                    const newTimeslot = parentTimeslot.cloneNode(true);
                    newTimeslot.id = '';
                    newTimeslot.querySelector('.action-btn:after').content = '-';

                    // Reset all input/select fields
                    newTimeslot.querySelectorAll('input, select').forEach(field => {
                        field.value = '';
                    });

                    // Append the new timeslot
                    timeslotsContainer.appendChild(newTimeslot);
                } else {
                    // Remove the current timeslot
                    parentTimeslot.remove();
                }
            }
        });
    });
</script>


<script>

function preventEnter(event) {
    if (event.key === "Enter") {
        event.preventDefault(); // Prevent the default action
    }
}

// Use 'onkeypress' for broader compatibility
document.querySelectorAll('input[name="user_timeslot_slot_count[]"]').forEach(input => {
    input.addEventListener('keypress', preventEnter);
});


</script>