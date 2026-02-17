<style type="text/css">
    .field-icon {
        float: right;
        margin-left: -25px;
        margin-top: -25px;
        position: relative;
        z-index: 2;
    }
</style>
<div class="row page-titles mx-0">
    <div class="col-sm-6 p-md-0">
        <div class="welcome-text">
            <h4><?php echo $page_title; ?></h4>
            <span>Edit Exceptional Date</span>
        </div>
    </div>
    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
        <a class="btn btn-primary waves-effect waves-light float-right" href="<?php echo base_url(); ?>admin/exceptiondate">
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
                <!-- <form action="" method="post">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Exceptional Date <span class="text-danger required">*</span></label>
                                <input type="date" class="form-control" name="timeslot_except_date" value="<?php echo $default->timeslot_except_date;?>" required>
                            </div>
                        </div>

                    </div>
                    
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Timeslot Description <span class="text-danger required">*</span></label>
                                <textarea class="form-control" placeholder="Description" name="timeslot_except_desc" id="timeslot_except_desc" required rows="5"></textarea>
                                <script>document.getElementById("timeslot_except_desc").value=`<?php echo $default->timeslot_except_desc;?>`;</script>
                            </div>
                        </div>
                    </div>
                    <button name="submit" type="submit" class="btn btn-primary">Update</button>

                </form> -->
                <form action="" method="post" onsubmit="return validateForm()">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Exceptional Date <span class="text-danger required">*</span></label>
                                <input type="date" class="form-control" name="timeslot_except_date" value="<?php echo $default->timeslot_except_date; ?>">
                                <!-- Error message for date -->
                                <span id="date-error" class="text-danger"></span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Timeslot Description <span class="text-danger required">*</span></label>
                                <textarea class="form-control" placeholder="Description" name="timeslot_except_desc" id="timeslot_except_desc" rows="5"><?php echo $default->timeslot_except_desc; ?></textarea>
                                <!-- Error message for description -->
                                <span id="desc-error" class="text-danger"></span>
                            </div>
                        </div>
                    </div>

                    <button name="submit" type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    // Validate the form when submitting
    function validateForm() {
        // Get the fields
        var dateField = document.querySelector('input[name="timeslot_except_date"]');
        var descField = document.querySelector('textarea[name="timeslot_except_desc"]');

        // Get the error message containers
        var dateError = document.getElementById('date-error');
        var descError = document.getElementById('desc-error');

        // Validation flags
        var isValid = true;

        // Validate the date field
        if (dateField.value.trim() === "") {
            dateError.textContent = "Please select a date.";
            isValid = false;
        }

        // Validate the description field
        if (descField.value.trim() === "") {
            descError.textContent = "Please enter a description.";
            isValid = false;
        }

        // Return false if any validation failed, preventing form submission
        return isValid;
    }

    // Hide the error message once the user starts typing or selects a value
    document.querySelector('input[name="timeslot_except_date"]').addEventListener('input', function() {
        var dateError = document.getElementById('date-error');
        if (this.value.trim() !== "") {
            dateError.textContent = ''; // Hide error if input is not empty
        }
    });

    document.querySelector('textarea[name="timeslot_except_desc"]').addEventListener('input', function() {
        var descError = document.getElementById('desc-error');
        if (this.value.trim() !== "") {
            descError.textContent = ''; // Hide error if input is not empty
        }
    });
</script>