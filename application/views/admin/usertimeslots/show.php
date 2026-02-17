<div class="row page-titles mx-0">
    <div class="col-sm-6 p-md-0">
        <div class="welcome-text">
            <h4><?php echo $page_title; ?></h4>
            <span>Manage User's Timeslots</span>
        </div>
    </div>
    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
        <a class="btn btn-primary waves-effect waves-light float-right" href="/admin/usertimeslot/create">
            <i class="mdi mdi-plus mr-2"></i> ADD NEW USER's TIMESLOT
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
                            <th>User</th>
                            <th>Slot Days</th>
                            <th>Total Slots</th>
                            <th>Status</th>
                            <th>updated By</th>
                            <th>Last Updated at</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 0;
                        // print_r($user_timeslots);
                        foreach ($user_timeslots as $value) {
                            $i = $i + 1;
                        ?>
                            <tr>
                                <td><strong><?php echo $i; ?></strong></td>
                                <td><strong><?php echo $value['first_name']." ".$value['last_name']; ?></strong></td>
                                <td><strong><?php echo $value['slot_days']; ?></strong></td>
                                <td><strong><?php echo $value['total_slots']; ?></strong></td>
                                <td><?php $statu=$value['status'];
	                                if($statu==1)
	                                {
	                                    ?>
                                <span class="badge badge-light-success">Active</span>
                                <?php
	                           }else
	                                {
	                                    ?>
                                <span class="badge badge-light-danger">In-active</span>
                                <?php    
	                             }   
	                             ?>
                            </td>
                                <td><?php echo $value['creator']; ?></td>
                                <td><?php echo $value['created_at'] ?></td>
                                <td><a href="<?php echo base_url(); ?>admin/usertimeslot/edit/<?php echo $value['user_id']; ?>" class="btn btn-sm btn-primary">Edit</a>&nbsp;
                                    <a href="<?php echo base_url(); ?>admin/usertimeslot/status_change/<?php echo $value['user_id']; ?>" class="btn btn-sm btn-primary">Change Status</a>
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