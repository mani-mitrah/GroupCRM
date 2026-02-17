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
            <span>Timeslot</span>
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
                                <input type="text" class="form-control" placeholder="Enter Timeslot Name" name="timeslot_name" value="<?php echo $default->timeslot_name; ?>" required>
                            </div>
                        </div>

                    </div>
                    
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Timeslot Description <span class="text-danger required">*</span></label>
                                <textarea class="form-control" placeholder="Description" name="timeslot_desc" id="timeslot_desc" required rows="5"></textarea>
                                <script>
                                    document.getElementById('timeslot_desc').value = `<?php echo $default->timeslot_desc; ?>`;
                                </script>
                            </div>
                        </div>
                    </div>
                    <button name="submit" type="submit" class="btn btn-primary">Update</button>

                </form> -->
                <form action="" method="post" onsubmit="return validateForm()">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="timeslot_name">Timeslot Name <span class="text-danger required">*</span></label>
                                <input type="text" class="form-control" placeholder="Enter Timeslot Name" name="timeslot_name" id="timeslot_name" value="<?php echo $default->timeslot_name; ?>">
                                <small id="timeslot_name_error" class="text-danger" style="display:none;">Timeslot Name is required</small>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="timeslot_desc">Timeslot Description <span class="text-danger required">*</span></label>
                                <textarea class="form-control" placeholder="Description" name="timeslot_desc" id="timeslot_desc" rows="5"></textarea>
                                <small id="timeslot_desc_error" class="text-danger" style="display:none;">Timeslot Description is required</small>
                                <script>
                                    document.getElementById('timeslot_desc').value = `<?php echo $default->timeslot_desc; ?>`;
                                </script>
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
    function validateForm() {
        let isValid = true;

        let timeslotName = document.getElementById("timeslot_name");
        let timeslotDesc = document.getElementById("timeslot_desc");

        let nameError = document.getElementById("timeslot_name_error");
        let descError = document.getElementById("timeslot_desc_error");

        if (timeslotName.value.trim() === "") {
            nameError.style.display = "block";
            isValid = false;
        } else {
            nameError.style.display = "none";
        }

        if (timeslotDesc.value.trim() === "") {
            descError.style.display = "block";
            isValid = false;
        } else {
            descError.style.display = "none";
        }

        return isValid;
    }

    function restrictWhitespace(inputField, errorField) {
        inputField.addEventListener("input", function() {
            if (inputField.value.trim() === "") {
                errorField.style.display = "block";
            } else {
                errorField.style.display = "none";
            }
        });
    }

    restrictWhitespace(document.getElementById("timeslot_name"), document.getElementById("timeslot_name_error"));
    restrictWhitespace(document.getElementById("timeslot_desc"), document.getElementById("timeslot_desc_error"));
</script>