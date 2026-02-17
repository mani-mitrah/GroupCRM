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
            <span>Add new group</span>
        </div>
    </div>
    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
        <a class="btn btn-primary waves-effect waves-light float-right" href="<?php echo base_url(); ?>admin/group">
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
                <form action="<?php echo base_url(); ?>admin/group/add/" method="post" id="groupForm">
    <div class="row">
        <div class="col-md-8">
            <div class="form-group">
                <label for="group_name">Group Name <span class="text-danger required">*</span></label>
                <input type="text" class="form-control" placeholder="Enter Group Name" name="group_name" id="group_name">
                <span id="group_name_error" class="text-danger"></span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="form-group">
                <label for="group_categories">Accessible Categories <span class="text-danger required">*</span></label>
                <select class="form-control" name="group_categories[]" id="group_categories" multiple>
                    <?php
                    foreach ($categories as $category) {
                        echo "<option value='" . $category['category_id'] . "'>" . $category["category_name"] . "</option>";
                    }
                    ?>
                </select>
                <span id="group_categories_error" class="text-danger"></span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="form-group">
                <label for="group_websites">Websites <small>(Team Lead Access)</small> <span class="text-danger required">*</span></label>
                <select class="form-control" name="group_websites[]" id="group_websites" multiple>
                    <?php
                    foreach ($websites as $web) {
                        echo "<option value='" . $web['id'] . "'>" . $web["website_name"] . "</option>";
                    }
                    ?>
                </select>
                <span id="group_websites_error" class="text-danger"></span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="form-group">
                <label for="group_branch_id">Branch <span class="text-danger required">*</span></label>
                <select class="form-control" name="group_branch_id" id="group_branch_id">
                    <option value="">--Select the Branch--</option>
                    <?php
                    foreach ($branches as $web) {
                        echo "<option value='" . $web['branch_code'] . "'>" . $web["branch_name"] . "</option>";
                    }
                    ?>
                </select>
                <span id="group_branch_id_error" class="text-danger"></span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="form-group">
                <label for="group_desc">Group Description <span class="text-danger required">*</span></label>
                <textarea class="form-control" placeholder="Description" name="group_desc" id="group_desc" rows="5"></textarea>
                <span id="group_desc_error" class="text-danger"></span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8">
            <div class="form-group">
                <label for="exampleInputEmail1">Alpha Pro Invoice<span class="text-danger required">*</span></label>
                <input type="radio" name="alpha_pro_invoice" id="alpha_pro_invoice" value="enable" style="margin: 10px;" checked>Enable
                <input type="radio" name="alpha_pro_invoice" id="alpha_pro_invoice" value="disable" style="margin: 10px;">Disable
            </div>
        </div>
    </div>

    <button name="submit" type="submit" class="btn btn-primary">Create</button>
</form>

            </div>
        </div>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script type="text/javascript">
    $("document").ready(function() {
        $('[name="group_categories[]"]').select2({
            placeholder: "Select the Category(ies)"
        });
        $('[name="group_websites[]"]').select2({
            placeholder: "Select the Website(s)"
        });
    });
</script>

<script>
    $("document").ready(function() {
        // Initialize select2 for multiple selects
        $('[name="group_categories[]"]').select2({
            placeholder: "Select the Category(ies)"
        });

        $('[name="group_websites[]"]').select2({
            placeholder: "Select the Website(s)"
        });

        // Real-time validation for the Group Name field
        function validateGroupName() {
            var groupName = document.getElementById('group_name').value.trim();
            var errorElement = document.getElementById('group_name_error');
            if (groupName === '') {
                errorElement.textContent = 'Group Name is required.';
            } else {
                errorElement.textContent = ''; // Clear error if valid
            }
        }

        // Real-time validation for the Group Categories field
        function validateGroupCategories() {
            var groupCategories = document.getElementById('group_categories');
            var errorElement = document.getElementById('group_categories_error');

            // Check if at least one option is selected in the multi-select
            if (groupCategories.selectedOptions.length === 0) {
                errorElement.textContent = 'At least one category is required.';
            } else {
                errorElement.textContent = ''; // Clear error if valid
            }
        }

        // Real-time validation for the Group Websites field
        function validateGroupWebsites() {
            var groupWebsites = document.getElementById('group_websites');
            var errorElement = document.getElementById('group_websites_error');

            // Check if at least one option is selected in the multi-select
            if (groupWebsites.selectedOptions.length === 0) {
                errorElement.textContent = 'At least one website is required.';
            } else {
                errorElement.textContent = ''; // Clear error if valid
            }
        }

        // Real-time validation for the Branch field
        function validateGroupBranch() {
            var groupBranch = document.getElementById('group_branch_id').value;
            var errorElement = document.getElementById('group_branch_id_error');
            if (groupBranch === '') {
                errorElement.textContent = 'Branch selection is required.';
            } else {
                errorElement.textContent = ''; // Clear error if valid
            }
        }

        // Real-time validation for the Group Description field
        function validateGroupDesc() {
            var groupDesc = document.getElementById('group_desc').value.trim();
            var errorElement = document.getElementById('group_desc_error');
            if (groupDesc === '') {
                errorElement.textContent = 'Group Description is required.';
            } else {
                errorElement.textContent = ''; // Clear error if valid
            }
        }

        // Attach real-time validation to each input field
        document.getElementById('group_name').addEventListener('input', validateGroupName);
        document.getElementById('group_categories').addEventListener('change', validateGroupCategories);
        document.getElementById('group_websites').addEventListener('change', validateGroupWebsites);
        document.getElementById('group_branch_id').addEventListener('change', validateGroupBranch);
        document.getElementById('group_desc').addEventListener('input', validateGroupDesc);

        // Custom event for select2 change to trigger validation
        $('[name="group_categories[]"]').on('change', function() {
            validateGroupCategories();
        });

        $('[name="group_websites[]"]').on('change', function() {
            validateGroupWebsites();
        });

        // Function to validate the form before submission
        function validateForm(event) {
            var isValid = true;

            // Perform validations on all fields
            validateGroupName();
            validateGroupCategories();
            validateGroupWebsites();
            validateGroupBranch();
            validateGroupDesc();

            // If any error is found, prevent form submission
            if (
                document.getElementById('group_name_error').textContent ||
                document.getElementById('group_categories_error').textContent ||
                document.getElementById('group_websites_error').textContent ||
                document.getElementById('group_branch_id_error').textContent ||
                document.getElementById('group_desc_error').textContent
            ) {
                isValid = false;
            }

            // Prevent form submission if validation fails
            if (!isValid) {
                event.preventDefault();
            }
        }

        // Attach the submit event listener to the form
        document.getElementById('groupForm').addEventListener('submit', validateForm);
    });
</script>