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
            <span>Add new calendar</span>
        </div>
    </div>
    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
        <a class="btn btn-primary waves-effect waves-light float-right" href="<?php echo base_url(); ?>admin/calendar/workspace/">
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
                <form action="<?php echo base_url(); ?>admin/calendar/add/" method="post">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Calendar Name<span class="text-danger required">*</span></label>
                                <input type="text" class="form-control" placeholder="Enter Calendar Name" name="calendar_name" required>
                            </div>
                        </div>

                    </div>
                    <!-- <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Accessable Categories<span class="text-danger required">*</span></label>
                                <select class="form-control" name="group_categories[]" required multiple>
                                    <?php
                                    foreach ($categories as $category) {
                                        echo "<option value='" . $category['id'] . "'>" . $category["category_name"] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                    </div> -->
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Websites <small>(Team Lead Access)</small><span class="text-danger required">*</span></label>
                                <select class="form-control" name="group_websites[]" required multiple>
                                    <?php
                                    foreach ($websites as $web) {
                                        echo "<option value='" . $web['id'] . "'>" . $web["website_name"] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Branch</label>
                                <select class="form-control" name="group_branch_id">
                                    <?php
                                    foreach ($branches as $web) {
                                        echo "<option value='" . $web['branch_code'] . "'>" . $web["branch_name"] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div> -->

                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Calendar Description<span class="text-danger required">*</span></label>
                                <textarea class="form-control" placeholder="Description" name="calendar_desc" rows="5"></textarea>
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