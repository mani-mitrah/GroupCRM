<div class="row page-titles mx-0">
    <div class="col-sm-6 p-md-0">
        <div class="welcome-text">
            <h4><?php echo $page_title; ?></h4>
            <span>Manage Calendar Preferences</span>
        </div>
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
                            <th>Calendar Name</th>
                            <th>Description</th>
                            <th>Calendar Member(s)</th>
                            <th>Status</th>
                            <th>updated By</th>
                            <th>Last Updated at</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($users as $value) {
                        ?>
                            <tr>
                                <td><strong><?php echo $value['calendar_id']; ?></strong></td>
                                <td><strong><?php echo $value['calendar_name']; ?></strong></td>
                                <td><strong><?php echo $value['calendar_desc']; ?></strong></td>
                                <td><strong><?php echo $value['members_count']; ?></strong></td>
                                <td><?php echo $value['status'] == 1 ? '<span class="badge badge-light-success">Active</span>' : '<span class="badge badge-light-warning">In-Active</span>'; ?></td>
                                <td><?php echo $value['creator'] ?></td>
                                <td><?php echo $value['updated_at'] ?></td>
                                <td><a href="<?php echo base_url(); ?>admin/calendar/edit_preferences/<?php echo $value['calendar_id']; ?>" class="btn btn-sm btn-primary">Edit</a>&nbsp;
                                    <a href="<?php echo base_url(); ?>admin/calendar/status_change/<?php echo $value['calendar_id']; ?>" class="btn btn-sm btn-primary">Change Status</a>
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