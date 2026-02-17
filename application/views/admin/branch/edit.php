<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box">
            <h4 class="page-title"><?php echo $page_title; ?>
                <a class="btn btn-primary waves-effect waves-light float-right"
                    href="<?php echo base_url(); ?>admin/branch/">
                    <i class="mdi mdi-plus mr-2"></i> View Branch
                </a>
            </h4>
        </div>
    </div>
</div>
<div>
    <?php foreach ($errors as $error) { ?>

        <font color="red"><?php echo $error; ?></font>
    <?php } ?>
</div>

<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <?php
                if ($this->session->flashdata('alert')) {
                ?>
                    <div class="alert alert-<?php echo $this->session->flashdata('alert'); ?>">
                        <?php echo $this->session->flashdata('alert_message'); ?>
                    </div>
                <?php
                }  ?>


                <!-- <form action="<?php echo base_url(); ?>admin/branch/edit/<?php echo $default['id']; ?>"
                    method="post" enctype="multipart/form-data">
                     <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Branch Name <span
                                        class="text-danger required">*</span></label>
                                <input type="text" class="form-control" placeholder="Enter Branch Name" name="branch_name"
                                    required=""  value="<?php echo $default['branch_name']; ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Branch Code <span
                                        class="text-danger required">*</span></label>
                                <input type="text" class="form-control" placeholder="Enter Branch Code" name="branch_code"
                                pattern="\d*" required="" title="Only numbers are allowed"  value="<?php echo $default['branch_code']; ?>">
                            </div>
                        </div>
                      
                    </div>
                    <div class="row">
                         <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Branch Icon <span
                                        class="text-danger required">*</span> 
                                        <a href="<?php echo base_url() . '/uploads/' . $default['branch_icon']; ?>" 
                                            target="_blank" style="color: blue; text-decoration: underline;">View Current File
                                        </a>
                                </label>
                                <input type="file" class="form-control"  name="branch_icon"
                                >
                                <font color="red">Please upload only .gif,jpg,.png files only</font>                     
                            </div>
                        </div> 
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">
                                    Branch Icon <span class="text-danger required">*</span>
                                    <?php if (!empty($default['branch_icon'])): ?>
                                        <a href="<?php echo base_url() . '/uploads/' . $default['branch_icon']; ?>" 
                                        target="_blank" style="color: blue; text-decoration: underline;">
                                            View Current File
                                        </a>
                                    <?php endif; ?>
                                </label>
                                <input type="file" class="form-control" name="branch_icon">

                                <?php if (empty($default['branch_icon'])): ?>
                                    <font color="red">Please upload only .gif, .jpg, .png files only</font>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Is Payment Allowed <span
                                        class="text-danger required">*</span></label>
                                <input type="radio"  name="is_allow_payment" value="1" <?php echo ($default['is_allow_payment'] == 1) ? 'checked="checked"' : ''; ?> >
                                    Yes
                                    <input type="radio"   name="is_allow_payment" value="0" <?php echo ($default['is_allow_payment'] == 0) ? 'checked="checked"' : ''; ?> >
                                    No
                            </div>
                        </div>
                      
                    </div>
                   <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Is Active <span
                                        class="text-danger required">*</span></label>
                               <input type="radio"   name="is_active" value="1" <?php echo ($default['is_active'] == 1) ? 'checked="checked"' : ''; ?> >
                                    Yes
                                    <input type="radio"   name="is_active" value="0" <?php echo ($default['is_active'] == 0) ? 'checked="checked"' : ''; ?> >
                                    No
                            </div>
                        </div>
                      
                    </div>
                   
                    
                    <button name="submit" type="submit" class="btn btn-primary">Update</button>
                </form> -->

                <form action="<?php echo base_url(); ?>admin/branch/edit/<?php echo $default['id']; ?>" method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Branch Name <span class="text-danger required">*</span></label>
                                <input type="text" class="form-control" placeholder="Enter Branch Name" name="branch_name" value="<?php echo $default['branch_name']; ?>">
                                <span id="branch_name_error" class="text-danger"></span> <!-- Error message span -->
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Branch Code <span class="text-danger required">*</span></label>
                                <input type="text" class="form-control" placeholder="Enter Branch Code" name="branch_code" pattern="\d*" title="Only numbers are allowed" value="<?php echo $default['branch_code']; ?>">
                                <span id="branch_code_error" class="text-danger"></span> <!-- Error message span -->
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Branch Icon <span class="text-danger required">*</span>
                                    <?php if (!empty($default['branch_icon'])): ?>
                                        <a href="<?php echo base_url() . '/uploads/' . $default['branch_icon']; ?>" target="_blank" style="color: blue; text-decoration: underline;">
                                            View Current File
                                        </a>
                                    <?php endif; ?>
                                </label>
                                <input type="file" class="form-control" name="branch_icon">
                                <span id="branch_icon_error" class="text-danger"></span> <!-- Error message span -->
                                <?php if (empty($default['branch_icon'])): ?>
                                    <font color="red">Please upload only .gif, .jpg, .png files only</font>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Is Payment Allowed <span class="text-danger required">*</span></label>
                                <input type="radio" name="is_allow_payment" value="1" <?php echo ($default['is_allow_payment'] == 1) ? 'checked="checked"' : ''; ?>> Yes
                                <input type="radio" name="is_allow_payment" value="0" <?php echo ($default['is_allow_payment'] == 0) ? 'checked="checked"' : ''; ?>> No
                                <span id="is_allow_payment_error" class="text-danger"></span> <!-- Error message span -->
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Is Active <span class="text-danger required">*</span></label>
                                <input type="radio" name="is_active" value="1" <?php echo ($default['is_active'] == 1) ? 'checked="checked"' : ''; ?>> Yes
                                <input type="radio" name="is_active" value="0" <?php echo ($default['is_active'] == 0) ? 'checked="checked"' : ''; ?>> No
                                <span id="is_active_error" class="text-danger"></span> <!-- Error message span -->
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
    $(document).ready(function() {
        // Validate Branch Name (Required, non-empty)
        $('input[name="branch_name"]').on('input', function() {
            var branchName = $(this).val().trim();
            if (branchName === '') {
                $('#branch_name_error').text('Branch Name is required.');
            } else {
                $('#branch_name_error').text('');
            }
        });

        // Validate Branch Code (Required, only numbers allowed)
        $('input[name="branch_code"]').on('input', function() {
            var branchCode = $(this).val().trim();
            var pattern = /^\d+$/; // Only numbers allowed
            if (branchCode === '') {
                $('#branch_code_error').text('Branch Code is required.');
            } else if (!pattern.test(branchCode)) {
                $('#branch_code_error').text('Branch Code must be numeric.');
            } else {
                $('#branch_code_error').text('');
            }
        });

        // Validate Branch Icon (Required, .gif, .jpg, .png files only)
        $('input[name="branch_icon"]').on('change', function() {
            var fileInput = $(this)[0];
            var filePath = fileInput.value;
            var allowedExtensions = /(\.gif|\.jpg|\.png)$/i;

            if (filePath !== '' && !allowedExtensions.exec(filePath)) {
                $('#branch_icon_error').text('Please upload only .gif, .jpg, or .png files.');
            } else {
                $('#branch_icon_error').text('');
            }
        });

        // Validate Is Payment Allowed (Required, must choose Yes or No)
        $('input[name="is_allow_payment"]').on('change', function() {
            var paymentAllowed = $('input[name="is_allow_payment"]:checked').val();
            if (!paymentAllowed) {
                $('#is_allow_payment_error').text('Please select whether payment is allowed.');
            } else {
                $('#is_allow_payment_error').text('');
            }
        });

        // Validate Is Active (Required, must choose Yes or No)
        $('input[name="is_active"]').on('change', function() {
            var isActive = $('input[name="is_active"]:checked').val();
            if (!isActive) {
                $('#is_active_error').text('Please select whether the branch is active.');
            } else {
                $('#is_active_error').text('');
            }
        });

        // Form submission validation
        $('form').on('submit', function(e) {
            // Trigger validation on input fields before submitting the form
            $('input[name="branch_name"]').trigger('input');
            $('input[name="branch_code"]').trigger('input');
            $('input[name="branch_icon"]').trigger('change');
            $('input[name="is_allow_payment"]').trigger('change');
            $('input[name="is_active"]').trigger('change');

            // Check if there are any validation errors
            if (
                $('#branch_name_error').text() ||
                $('#branch_code_error').text() ||
                $('#branch_icon_error').text() ||
                $('#is_allow_payment_error').text() ||
                $('#is_active_error').text()
            ) {
                e.preventDefault(); // Prevent form submission if errors are present
            }
        });
    });
</script>