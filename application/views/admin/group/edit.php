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
            <span>Edit Group information</span>
        </div>
    </div>
    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
        <a class="btn btn-primary waves-effect waves-light float-right" href="<?php echo base_url(); ?>admin/group/">
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
                <!-- <form action="<?php echo base_url(); ?>admin/group/edit/<?php echo $default['group_id']; ?>" method="post">

                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Group Name <span class="text-danger required">*</span></label>
                                <input type="text" class="form-control" placeholder="Enter Group Name" value="<?php echo $default['group_name']; ?>" name="group_name" required>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Accessable Categories <span class="text-danger required">*</span></label>
                                <select class="form-control" name="group_categories[]" required multiple>
                                    <?php
                                    foreach ($categories as $category) {
                                        $is_selected = ($category["is_selected"] == 1) ? 'selected' : '';
                                        echo "<option value='" . $category['category_id'] . "' " . $is_selected . ">" . $category["category_name"] . "</option>";
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
                                <label for="exampleInputEmail1">Branch <span class="text-danger required">*</span></label>
                                <select class="form-control" name="group_branch_id" required>
                                    <?php
                                    $branch_id = $default["group_branch_id"];
                                    foreach ($branches as $web) {
                                        $is_selected = ($web["id"] == $branch_id) ? 'selected' : '';
                                        echo "<option value='" . $web['id'] . "' " . $is_selected . ">" . $web["branch_name"] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Group Description <span class="text-danger required">*</span></label>
                                <textarea class="form-control" placeholder="Description" name="group_desc" rows="5" required><?php echo $default['group_desc']; ?></textarea>
                            </div>
                        </div>
                    </div>

                    <button name="submit" type="submit" class="btn btn-primary">Update</button>
                    <?php
                    ($websites);
                    ?>
                </form> -->
                <form action="<?php echo base_url(); ?>admin/group/edit/<?php echo $default['group_id']; ?>" method="post" id="groupForm" onsubmit="return validateForm()">

                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Group Name <span class="text-danger required">*</span></label>
                                <input type="text" class="form-control" placeholder="Enter Group Name" value="<?php echo $default['group_name']; ?>" name="group_name" id="group_name">
                                <span id="group_name_error" class="text-danger"></span>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Accessable Categories <span class="text-danger required">*</span></label>
                                <select class="form-control" name="group_categories[]" id="group_categories">
                                    <?php
                                    foreach ($categories as $category) {
                                        $is_selected = ($category["is_selected"] == 1) ? 'selected' : '';
                                        echo "<option value='" . $category['category_id'] . "' " . $is_selected . ">" . $category["category_name"] . "</option>";
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
                                <label for="exampleInputEmail1">Websites <small>(Team Lead Access)</small> <span class="text-danger required">*</span></label>
                                <select class="form-control" name="group_websites[]" id="group_websites" multiple>
                                    <?php
                                    foreach ($websites as $web) {
                                        $is_selected = ($web["is_selected"] == "1") ? 'selected' : '';
                                        echo "<option value='" . $web['id'] . "' " . $is_selected . ">" . $web["website_name"] . "</option>";
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
                                <label for="exampleInputEmail1">Branch <span class="text-danger required">*</span></label>
                                <select class="form-control" name="group_branch_id" id="group_branch_id">
                                    <?php
                                    $branch_id = $default["group_branch_id"];
                                    foreach ($branches as $web) {
                                        $is_selected = ($web["branch_code"] == $branch_id) ? 'selected' : '';
                                        echo "<option value='" . $web['branch_code'] . "' " . $is_selected . ">" . $web["branch_name"] . "</option>";
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
                                <label for="exampleInputEmail1">Group Description <span class="text-danger required">*</span></label>
                                <textarea class="form-control" placeholder="Description" name="group_desc" rows="5" id="group_desc"><?php echo $default['group_desc']; ?></textarea>
                                <span id="group_desc_error" class="text-danger"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Alpha Pro Invoice</label>
                                <input type="radio" name="alpha_pro_invoice" id='alpha_pro_invoice' value="enable" style="margin: 10px;"
                                <?php echo ($default['alpha_pro_invoice'] == 'enable')?'checked':''?> required>Enable
                                <input type="radio" name="alpha_pro_invoice" id='alpha_pro_invoice' value="disable" style="margin: 10px;"
                                    <?php echo ($default['alpha_pro_invoice'] == 'disable')?'checked':''?>>Disable
                            </div>
                        </div>
                    </div>

                    <button name="submit" type="submit" class="btn btn-primary">Update</button>
                    <?php
                    ($websites);
                    ?>
                </form>

            </div>
        </div>
    </div>
</div>

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

<script type="text/javascript">
    $("document").ready(function() {
        // Initialize Select2 for the multiple select dropdowns
        $('[name="group_categories[]"]').select2({
            placeholder: "Select the Category(ies)"
        });

        $('[name="group_websites[]"]').select2({
            placeholder: "Select the Website(s)"
        });

        // Real-time validation for Group Name
        $('#group_name').on('input', function() {
            var groupName = this.value.trim();
            if (groupName === '') {
                $('#group_name_error').text('Group Name is required.');
            } else {
                $('#group_name_error').text('');
            }
        });

        // Real-time validation for Group Categories
        $('[name="group_categories[]"]').on('change', function() {
            var selectedCategories = $(this).val();
            if (!selectedCategories || selectedCategories.length === 0) {
                $('#group_categories_error').text('At least one category is required.');
            } else {
                $('#group_categories_error').text('');
            }
        });

        // Real-time validation for Group Websites
        $('[name="group_websites[]"]').on('change', function() {
            var selectedWebsites = $(this).val();
            if (!selectedWebsites || selectedWebsites.length === 0) {
                $('#group_websites_error').text('At least one website is required.');
            } else {
                $('#group_websites_error').text('');
            }
        });

        // Real-time validation for Branch
        $('#group_branch_id').on('change', function() {
            var groupBranch = this.value;
            if (groupBranch === '') {
                $('#group_branch_id_error').text('Branch selection is required.');
            } else {
                $('#group_branch_id_error').text('');
            }
        });

        // Real-time validation for Group Description
        $('#group_desc').on('input', function() {
            var groupDesc = this.value.trim();
            if (groupDesc === '') {
                $('#group_desc_error').text('Group Description is required.');
            } else {
                $('#group_desc_error').text('');
            }
        });

        // Form submission validation
        $('#groupForm').on('submit', function() {
            var isValid = true;

            // Trigger the real-time validation events before submitting
            $('#group_name').trigger('input');
            $('[name="group_categories[]"]').trigger('change');
            $('[name="group_websites[]"]').trigger('change');
            $('#group_branch_id').trigger('change');
            $('#group_desc').trigger('input');

            // Check if there are any validation errors
            if (
                $('#group_name_error').text() ||
                $('#group_categories_error').text() ||
                $('#group_websites_error').text() ||
                $('#group_branch_id_error').text() ||
                $('#group_desc_error').text()
            ) {
                isValid = false;
            }

            return isValid;
        });
    });
</script>