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
            <span>Add new User's Timeslot</span>
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

                <form action="<?php echo base_url();?>admin/usertimeslot/create" method="post" id="userslots_form">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="exampleInputEmail1">User <span class="text-danger required">*</span></label>
                                <select name="user_timeslot_user_id" class="form-control" onchange="handleChange(this)">
                                    <option value="">-- Select --</option>
                                    <?php
                                    // print_r($lead_details);
                                    $lead_users = $this->user_model->get_lead_category_users($lead_details['category_id']);
                                    // print_r($lead_users);
                                    log_message('error', $this->db->last_query());
                                    foreach ($lead_users as $keys => $values) {
                                        if (in_array($values['user_id'], $existusers)) {continue;}
                                        ?>
                                        <option value="<?php echo $values['user_id']; ?>"
                                            data-filter="<?php echo str_replace(',', '', $values["group_s"]); ?>">
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
                                <div class="row timeslot" id="timeslot">
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Day <span
                                                    class="text-danger required">*</span></label>
                                            <select name="user_timeslot_day[]" class="form-control">
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
                                            <label for="exampleInputEmail1">Timeslot <span
                                                    class="text-danger required">*</span></label>
                                            <select name="user_timeslot_slot_id[]" class="form-control">
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
                                            <label for="exampleInputEmail1">Slot Count <span
                                                    class="text-danger required">*</span></label>
                                            <input type="number" class="form-control" name="user_timeslot_slot_count[]" onkeydown="preventEnter(event)">
                                        </div>
                                    </div>
                                    <div class="col-1">
                                        <div class="form-group">
                                            <label for="action">Action</label>
                                            <button class="btn btn-primary action-btn"></button>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>

                        </div>
                    </div>

                    <button name="submit" type="submit" class="btn btn-primary">Create</button>

                </form>
            </div>
        </div>
    </div>
</div>

<!-- <script>
    var timeslot = $("#timeslot").html();
    function slotInit() {
        $(".timeslot:not(#timeslot) .action-btn").off();
        $(".timeslot:not(#timeslot) .action-btn").click(function (e) {
            e.preventDefault();
            $(this).closest(".timeslot").remove();
        });
    }
    $("#timeslot .action-btn").click(function (e) {
        e.preventDefault();
        $(".timeslots").append("<div class='row timeslot'>" + timeslot + "</div>");
        slotInit();
    });

    $("#userslots_form").on("submit",function(e){
        // e.preventDefault();
        // alert("There");
        $(".timeslot").removeClass("bg-danger-alt shadow");
        var slot = [];
        $(".timeslot").each(function(){
            var s = $(this).find("[name='user_timeslot_day[]']").val()+"-"+$(this).find("[name='user_timeslot_slot_id[]']").val();
            if(slot.indexOf(s) != -1){
                alert("Timeslot Duplicate Found. Please resolve that");
                e.preventDefault();
                $(this).addClass("bg-danger-alt shadow");
                return false;
            }
            slot.push(s);
        });
        $("#userslots_form").off();
        $("#userslots_form").submit();
    });

</script> -->

<script>
    var timeslot = $("#timeslot").html();
    function slotInit() {
        $(".timeslot:not(#timeslot) .action-btn").off();
        $(".timeslot:not(#timeslot) .action-btn").click(function (e) {
            e.preventDefault();
            $(this).closest(".timeslot").remove();
        });
    }
    $("#timeslot .action-btn").click(function (e) {
        e.preventDefault();
        $(".timeslots").append("<div class='row timeslot'>" + timeslot + "</div>");
        slotInit();
    });

$("#userslots_form").on("submit", function(e) {
    $(".timeslot").removeClass("shadow");
    $(".error-message").remove(); 
    var slot = [];
    var isValid = true;


    var userSelection = $("select[name='user_timeslot_user_id']").val();
    if (!userSelection) {
        $("select[name='user_timeslot_user_id']").after('<div class="error-message text-danger">Please select a User.</div>');
        isValid = false;
    }

    $(".timeslot").each(function() {
        var day = $(this).find("[name='user_timeslot_day[]']").val();
        var slotId = $(this).find("[name='user_timeslot_slot_id[]']").val();
        var slotCount = $(this).find("[name='user_timeslot_slot_count[]']").val();

        var hasError = false;
        $(this).find(".error-message").remove();

        if (!day) {
            $(this).find("[name='user_timeslot_day[]']").after('<div class="error-message text-danger">Please select a Day.</div>');
            isValid = false;
        }

        if (!slotId) {
            $(this).find("[name='user_timeslot_slot_id[]']").after('<div class="error-message text-danger">Please select a Timeslot.</div>');
            isValid = false;
            hasError = true;
        }

        if (!slotCount) {
            $(this).find("[name='user_timeslot_slot_count[]']").after('<div class="error-message text-danger">Please enter a Slot Count.</div>');
            isValid = false;
            hasError = true;
        } else if (parseInt(slotCount) <= 0 && !hasError) {
            $(this).find("[name='user_timeslot_slot_count[]']").after('<div class="error-message text-danger">Slot Count must be a positive number.</div>');
            isValid = false;
            hasError = true;
        }

        var slotCombination = day + "-" + slotId;
        if (slot.indexOf(slotCombination) !== -1 && !hasError) {
            $(this).find("[name='user_timeslot_day[]']").after('<div class="error-message text-danger">This timeslot has already been selected.</div>');
            isValid = false;
        } else {
            slot.push(slotCombination);
        }
    });
    if (!isValid) {
        e.preventDefault();
    }
});


$(document).on("input", "[name='user_timeslot_day[]'], [name='user_timeslot_slot_id[]'], [name='user_timeslot_slot_count[]'], #user_timeslot_user_id", function() {
    var $field = $(this); 
    var $parent = $field.closest('.timeslot'); 
    $parent.find(".error-message").remove();
    var isValid = true;
    var day = $parent.find("[name='user_timeslot_day[]']").val();
    var slotId = $parent.find("[name='user_timeslot_slot_id[]']").val();
    var slotCount = $parent.find("[name='user_timeslot_slot_count[]']").val();
    var userSelection = $('#user_timeslot_user_id').find(":selected").text();// User selection

    if (!userSelection || userSelection === "") {
        $("#user_timeslot_user_id").after('<div class="error-message text-danger">Please select a User.</div>');
        isValid = false;

    }
    if (!day) {
        $parent.find("[name='user_timeslot_day[]']").after('<div class="error-message text-danger">Please select a Day.</div>');
        isValid = false;
    }
    if (!slotId) {
        $parent.find("[name='user_timeslot_slot_id[]']").after('<div class="error-message text-danger">Please select a Timeslot.</div>');
        isValid = false;
    }

    if (!slotCount) {
        $parent.find("[name='user_timeslot_slot_count[]']").after('<div class="error-message text-danger">Please enter a Slot Count.</div>');
        isValid = false;
    } else if (parseInt(slotCount) <= 0) {
        $parent.find("[name='user_timeslot_slot_count[]']").after('<div class="error-message text-danger">Slot Count must be a positive number.</div>');
        isValid = false;
    }
    
    var slotCombination = day + "-" + slotId;
    var slotCombinations = $(".timeslot").map(function() {
        var day = $(this).find("[name='user_timeslot_day[]']").val();
        var slotId = $(this).find("[name='user_timeslot_slot_id[]']").val();
        return day + "-" + slotId;
    }).get();

    if (slotCombinations.filter(function(item) { return item === slotCombination }).length > 1) {
        $parent.find("[name='user_timeslot_day[]']").after('<div class="error-message text-danger">This timeslot has already been selected.</div>');
        isValid = false;
    }
    if (isValid) {
        $parent.removeClass("shadow");
    }
});
// function handleChange(selectElement) {
//     var selectedValue = selectElement.value;
//     $(selectElement).siblings(".error-message").remove(); 

//     if (!selectedValue || selectedValue === "") {
//         $(selectElement).after('<div class="error-message text-danger">Please select a User.</div>');
//     }
// }

function handleChange(selectElement) {
    var selectedValue = selectElement.value;
    $(selectElement).siblings(".error-message").remove();

    if (!selectedValue || selectedValue === "") {
        $(selectElement).after('<div class="error-message text-danger">Please select a user to allocate timeslots.</div>');
    } else {
        $(".timeslot").each(function() {
            $(this).find("[name='user_timeslot_day[]']").val('');
            $(this).find("[name='user_timeslot_slot_id[]']").val('');
            $(this).find("[name='user_timeslot_slot_count[]']").val('');
            $(this).find(".error-message").remove();
        });

        slot = []; 
    }
}

function preventEnter(event) {
     
     if (event.key === "Enter") {
         event.preventDefault(); 
     }
 }

</script>