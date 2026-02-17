<style type="text/css">
    .field-icon {
        float: right;
        margin-left: -25px;
        margin-top: -25px;
        position: relative;
        z-index: 2;
    }

    .error-message {
        color: red;
        font-size: 0.9em;
    }
</style>
<div class="row page-titles mx-0">
    <div class="col-sm-6 p-md-0">
        <div class="welcome-text">
            <h4><?php echo $page_title; ?></h4>
            <span>Edit Calendar information</span>
        </div>
    </div>
    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
        <a class="btn btn-primary waves-effect waves-light float-right" href="<?php echo base_url(); ?>admin/calendar/workspace">
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
                <!-- <form action="<?php echo base_url(); ?>admin/calendar/edit/<?php echo $default['calendar_id']; ?>" method="post">

                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Calendar Name <span class="text-danger required">*</span></label>
                                <input type="text" class="form-control" placeholder="Enter Calendar Name" value="<?php echo $default['calendar_name']; ?>" name="calendar_name" required>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Accessable Categories<span class="text-danger required">*</span></label>
                                <select class="form-control" name="group_categories[]" required multiple>
                                    <?php
                                    foreach ($categories as $category) {
                                        $is_selected = ($category["is_selected"] == 1) ? 'selected' : '';
                                        echo "<option value='" . $category['id'] . "' " . $is_selected . ">" . $category["category_name"] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                    </div> 

                     <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Branch<span class="text-danger required">*</span></label>
                                <select class="form-control" name="group_branch_id" required>
                                    <?php

                                    $branch_id = $default["group_branch_id"];
                                    foreach ($branches as $web) {
                                        $is_selected = ($web["branch_code"] == $branch_id) ? 'selected' : '';
                                        echo "<option value='" . $web['branch_code'] . "' " . $is_selected . ">" . $web["branch_name"] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div> 
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Websites <small>(Team Lead Access)</small> <span class="text-danger required">*</span></label>
                                <select class="form-control" name="group_websites[]" required multiple>
                                    <?php
                                    foreach ($websites as $web) {
                                        $is_selected = ($web["is_selected"] == "1") ? 'selected' : '';
                                        echo "<option value='" . $web['id'] . "' " . $is_selected . ">" . $web["website_name"] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Calendar Description <span class="text-danger required">*</span></label>
                                <textarea class="form-control" placeholder="Description" required name="calendar_desc" rows="5"><?php echo $default['calendar_desc']; ?></textarea>
                            </div>
                        </div>
                    </div>

                    <button name="submit" type="submit" class="btn btn-primary">Update</button>
                    <?php
                    ($websites);
                    ?>
                </form> -->

                <form id="calendarForm" action="<?php echo base_url(); ?>admin/calendar/edit/<?php echo $default['calendar_id']; ?>" method="post">

                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="calendar_name">Calendar Name <span class="text-danger required">*</span></label>
                                <input type="text" class="form-control" id="calendar_name" placeholder="Enter Calendar Name" value="<?php echo $default['calendar_name']; ?>" name="calendar_name">
                                <span id="calendar_name_error" class="error-message" style="display:none;">Calendar Name is required.</span>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="group_websites">Websites <small>(Team Lead Access)</small> <span class="text-danger required">*</span></label>
                                <select class="form-control" id="group_websites" name="group_websites[]" multiple>
                                    <?php
                                    foreach ($websites as $web) {
                                        $is_selected = ($web["is_selected"] == "1") ? 'selected' : '';
                                        echo "<option value='" . $web['id'] . "' " . $is_selected . ">" . $web["website_name"] . "</option>";
                                    }
                                    ?>
                                </select>
                                <span id="group_websites_error" class="error-message" style="display:none;">Please select at least one website.</span>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="calendar_desc">Calendar Description <span class="text-danger required">*</span></label>
                                <textarea class="form-control" id="calendar_desc" placeholder="Description" name="calendar_desc" rows="5"><?php echo $default['calendar_desc']; ?></textarea>
                                <span id="calendar_desc_error" class="error-message" style="display:none;">Calendar Description is required.</span>
                            </div>
                        </div>
                    </div>


                    <button name="submit" type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- <script type="text/javascript">
    $("document").ready(function() {
        $('[name="group_categories[]"]').select2({
            placeholder: "Select the Category(ies)"
        });

        $('[name="group_websites[]"]').select2({
            placeholder: "Select the Website(s)"
        });
    });
</script> -->

<script type="text/javascript">
    $("document").ready(function() {
        $('[name="group_categories[]"]').select2({
            placeholder: "Select the Category(ies)"
        });

        $('[name="group_websites[]"]').select2({
            placeholder: "Select the Website(s)"
        });

        // Function to check the website selection length
        function checkWebsiteSelection() {
            let websites = $("#group_websites").val();
            if (websites && websites.length > 0) {
                $("#group_websites_error").hide();
            } else {
                $("#group_websites_error").show();
            }
        }

        // Call the checkWebsiteSelection function on document ready to ensure it works immediately
        checkWebsiteSelection();

        // Monitor changes to the select element for websites
        $("#group_websites").on("change", function() {
            checkWebsiteSelection();
        });

        // Monitor changes to the calendar_name field
        $("#calendar_name").on("input", function() {
            let calendarName = $(this).val().trim();
            if (calendarName !== "") {
                $("#calendar_name_error").hide();
            }
        });

        // Monitor changes to the calendar_desc field
        $("#calendar_desc").on("input", function() {
            let calendarDesc = $(this).val().trim();
            if (calendarDesc !== "") {
                $("#calendar_desc_error").hide();
            }
        });

        $("#calendarForm").on("submit", function(event) {
            let isValid = true;

            // Trim spaces and validate Calendar Name
            let calendarName = $("#calendar_name").val().trim();
            if (calendarName === "") {
                $("#calendar_name_error").show();
                isValid = false;
            }

            // Validate Calendar Description
            let calendarDesc = $("#calendar_desc").val().trim();
            if (calendarDesc === "") {
                $("#calendar_desc_error").show();
                isValid = false;
            }

            // Validate Group Websites - This is now handled dynamically by checkWebsiteSelection()
            if (!$("#group_websites").val() || $("#group_websites").val().length === 0) {
                $("#group_websites_error").show();
                isValid = false;
            }

            // Prevent form submission if any field is invalid
            if (!isValid) {
                event.preventDefault();
            }
        });
    });
</script>