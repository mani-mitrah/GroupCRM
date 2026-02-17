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
        font-size: 14px;
        display: none;
    }
</style>
<div class="row page-titles mx-0">
    <div class="col-sm-6 p-md-0">
        <div class="welcome-text">
            <h4><?php echo $page_title; ?></h4>
            <span>Add new Timeslot</span>
        </div>
    </div>
    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
        <a class="btn btn-primary waves-effect waves-light float-right" href="<?php echo base_url(); ?>admin/timeslot">
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
                                <label for="exampleInputEmail1">Timeslot Name <span class="text-danger required">*</span></label>
                                <input type="text" class="form-control" placeholder="Enter Timeslot Name" name="timeslot_name" required>
                            </div>
                        </div>

                    </div>
                    
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Timeslot Description <span class="text-danger required">*</span></label>
                                <textarea class="form-control" placeholder="Description" name="timeslot_desc" required rows="5"></textarea>
                            </div>
                        </div>
                    </div>
                    <button name="submit" type="submit" class="btn btn-primary">Create</button>

                </form> -->
                <form id="timeslotForm" action="" method="post">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="timeslot_name">Timeslot Name <span class="text-danger required">*</span></label>
                                <input type="text" class="form-control" placeholder="Enter Timeslot Name" name="timeslot_name" id="timeslot_name">
                                <span class="error-message" id="timeslot_name_error">Timeslot Name is required.</span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="timeslot_desc">Timeslot Description <span class="text-danger required">*</span></label>
                                <textarea class="form-control" placeholder="Description" name="timeslot_desc" id="timeslot_desc" rows="5"></textarea>
                                <span class="error-message" id="timeslot_desc_error">Timeslot Description is required.</span>
                            </div>
                        </div>
                    </div>
                    <button name="submit" type="submit" class="btn btn-primary">Create</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const form = document.getElementById("timeslotForm");
        const timeslotName = document.getElementById("timeslot_name");
        const timeslotDesc = document.getElementById("timeslot_desc");

        const timeslotNameError = document.getElementById("timeslot_name_error");
        const timeslotDescError = document.getElementById("timeslot_desc_error");

        function validateField(field, errorElement) {
            if (field.value.trim() === "") {
                errorElement.style.display = "block";
                return false;
            } else {
                errorElement.style.display = "none";
                return true;
            }
        }

        timeslotName.addEventListener("input", function() {
            validateField(timeslotName, timeslotNameError);
        });

        timeslotDesc.addEventListener("input", function() {
            validateField(timeslotDesc, timeslotDescError);
        });

        form.addEventListener("submit", function(e) {
            let isValid = true;

            if (!validateField(timeslotName, timeslotNameError)) isValid = false;
            if (!validateField(timeslotDesc, timeslotDescError)) isValid = false;

            if (!isValid) {
                e.preventDefault(); // Prevent form submission if validation fails
            }
        });
    });
</script>