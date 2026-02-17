<div class="row page-titles mx-0">
    <div class="col-sm-6 p-md-0">
        <div class="welcome-text">
            <h4><?php echo $page_title; ?></h4>
            <span>Manage Exceptional Dates</span>
        </div>
    </div>
    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
        <a class="btn btn-primary waves-effect waves-light float-right" href="/admin/exceptiondate/create">
            <i class="mdi mdi-plus mr-2"></i> ADD NEW EXCEPTIONAL DATE
        </a>
    </div>
</div>
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
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body  table-responsive">
                <table id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Exceptional Date</th>
                            <th>Desc</th>
                            <th>Status</th>
                            <th>updated By</th>
                            <th>Last Updated at</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

                        foreach ($default as $value) {
                        ?>
                            <tr>
                                <td><strong><?php echo $value->timeslot_except_id; ?></strong></td>
                                <td><strong><?php echo $value->timeslot_except_date; ?></strong></td>
                                <td><strong><?php echo $value->timeslot_except_desc; ?></strong></td>
                                <td><?php echo $value->status == 1 ? '<span class="badge badge-light-success">Active</span>' : '<span class="badge badge-light-warning">In-Active</span>'; ?></td>
                                <td><?php echo $value->creator ?></td>
                                <td><?php echo $value->updated_at ?></td>
                                <td><a href="<?php echo base_url(); ?>admin/exceptiondate/edit/<?php echo $value->timeslot_except_id; ?>" class="btn btn-sm btn-primary">Edit</a>&nbsp;
                                    <a href="<?php echo base_url(); ?>admin/exceptiondate/status_change/<?php echo $value->timeslot_except_id; ?>" class="btn btn-sm btn-primary">Change Status</a>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>

                    </tbody>

                </table>
            </div>
        </div>
    </div>
</div>