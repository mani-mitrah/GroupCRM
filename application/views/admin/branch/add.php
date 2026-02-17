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
 <?php foreach($errors as $error){ ?>

                    <font color="red"><?php echo $error; ?></font>
               <?php } ?>
</div>    
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <?php if ($this->session->flashdata('alert')) { ?>
                <div class="alert alert-<?php echo $this->session->flashdata(
                    'alert'
                ); ?>">
                    <?php echo $this->session->flashdata('alert_message'); ?>
                </div>
                <?php } 
                    ?>
                <!-- <form action="<?php echo base_url(); ?>admin/branch/add" method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Branch Name <span
                                        class="text-danger required">*</span></label>
                                <input type="text" class="form-control" placeholder="Enter Branch Name" name="branch_name"
                                    required="">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Branch Code <span
                                        class="text-danger required">*</span></label>
                                <input type="text" class="form-control" placeholder="Enter Branch Code" name="branch_code"
                                pattern="\d*" required="" title="Only numbers are allowed">
                            </div>
                        </div>
                      
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Branch Icon <span
                                        class="text-danger required">*</span></label>
                                <input type="file" class="form-control"  name="branch_icon"
                                    required="">
                                    <font color="red">Please upload only .gif,jpg,.png files only</font>                     
                            </div>
                
                         </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Is Payment Allowed <span
                                        class="text-danger required">*</span></label>
                                <input type="radio"name="is_allow_payment" value="Yes" required
                                >Yes
                                <input type="radio"   name="is_allow_payment" value="No" required
                                >No
                            </div>
                        </div>
                      
                    </div>
                    
                        <button name="submit" type="submit" class="btn btn-primary">Create</button>

                </form> -->
                <form action="<?php echo base_url(); ?>admin/branch/add" method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Branch Name <span class="text-danger required">*</span></label>
                                <input type="text" class="form-control" placeholder="Enter Branch Name" name="branch_name">
                                <span id="branch_name_error" class="text-danger"></span> <!-- Error message span -->
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Branch Code <span class="text-danger required">*</span></label>
                                <input type="text" class="form-control" placeholder="Enter Branch Code" name="branch_code" pattern="\d*" title="Only numbers are allowed">
                                <span id="branch_code_error" class="text-danger"></span> <!-- Error message span -->
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Branch Icon <span class="text-danger required">*</span></label>
                                <input type="file" class="form-control" name="branch_icon">
                                <font color="red">Please upload only .gif,jpg,.png files only</font>
                                <span id="branch_icon_error" class="text-danger"></span> <!-- Error message span -->
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Is Payment Allowed <span class="text-danger required">*</span></label>
                                <input type="radio" name="is_allow_payment" value="Yes" >Yes
                                <input type="radio" name="is_allow_payment" value="No" >No
                                <span id="is_allow_payment_error" class="text-danger"></span> <!-- Error message span -->
                            </div>
                        </div>
                    </div>

                    <button name="submit" type="submit" class="btn btn-primary">Create</button>
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

            if (filePath === '') {
                $('#branch_icon_error').text('Branch Icon is required.');
            } else if (!allowedExtensions.exec(filePath)) {
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

        // Form submission validation
        $('form').on('submit', function(e) {
            // Trigger validation on input fields before submitting the form
            $('input[name="branch_name"]').trigger('input');
            $('input[name="branch_code"]').trigger('input');
            $('input[name="branch_icon"]').trigger('change');
            $('input[name="is_allow_payment"]').trigger('change');

            // Check if there are any validation errors
            if (
                $('#branch_name_error').text() ||
                $('#branch_code_error').text() ||
                $('#branch_icon_error').text() ||
                $('#is_allow_payment_error').text()
            ) {
                e.preventDefault(); // Prevent form submission if errors are present
            }
        });
    });
</script>