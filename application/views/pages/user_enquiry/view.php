<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box">
            <h4 class="page-title">Enquriy
                <!-- <a class="btn btn-primary waves-effect waves-light float-right"
                    href="<?php echo base_url(); ?>weqayati/amount/add/">
                    <i class="mdi mdi-plus mr-2"></i> Add Amount
                </a> -->
            </h4>
        </div>
    </div>
</div>
<?php
if ($this->session->flashdata('alert_success')) {
?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
        <strong>Success!</strong> <?php echo $this->session->flashdata('alert_success'); ?>
    </div>
<?php
}

if ($this->session->flashdata('alert_danger')) {
?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
        <strong>Success!</strong> <?php echo $this->session->flashdata('alert_danger'); ?>
    </div>
<?php
}

if ($this->session->flashdata('alert_warning')) {
?>
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
        <strong>Success!</strong> <?php echo $this->session->flashdata('alert_warning'); ?>
    </div>
<?php
}
?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <table id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                    <thead>
                        <tr>
                            <th>S.no</th>
                            <th>#</th>
                            <th>Name</th>
                            <th>Mobile</th>
                            <th>Email</th>
                            <th>Message</th>
                            <th>Source</th>
                            <th>Created Date</th>
                            <th>Assigned Date</th>
                            <th>SLA</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($user_services as $key => $value) {
                        ?>
                            <tr>
                                <td><strong><?php echo $key + 1; ?></strong></td>
                                <td><?php echo $value->service_id; ?></td>
                                <td><?php echo $value->name; ?></td>
                                <td><?php echo $value->phone; ?></td>
                                <td><?php echo $value->e_email; ?></td>

                                <td><?php echo $value->subject; ?></td>
                                <td><?php echo $value->source; ?></td>
                                <td><?php echo date("d-m-Y h:i:s", strtotime($value->created_date)); ?></td>
                                <td><?php echo date("d-m-Y h:i:s", strtotime($value->assigned_date)); ?></td>
                                <td><?php echo $value->subjects; ?></td>
                                <td><?php  $this->db->select("*");
                             $this->db->from("assigned_enquriy as sa");
                             $this->db->where("sa.service_id",$value->id);
                             $query=$this->db->get();
                             $row = $query->row();
                             $status=$row->status;
                            if($status==1){
                                echo "Open";
                            }else if($status==2){
                                echo "Converted to Lead";
                            }else if($status==3){
                                echo "Closed";
                            }else{
                                echo "Open";
                            }
                            ?></td>

                                <?php
                                if ($value->status == 2) {
                                ?>
                                    <td>
                                        <!-- <a href="<?php echo site_url('user_enquiry/edit/' . $value->as_id); ?>"><button type="button" class="btn btn-primary">Edit</button></a> -->
                                        <a href="#"><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModals<?php echo $value->as_id; ?>">Edit</button></a>
                                    </td>

                                <?php

                                } elseif ($value->status == 3 || $value->status == 4) {

                                ?>

                                    <td>
                                    </td>
                                <?php
                                } else {
                                ?>
                                    <td>
                                        <!-- <a href="<?php echo site_url('user_enquiry/accept/' . $value->as_id); ?>"><button type="button" class="btn btn-sm btn-success">Accept</button></a>
                                        <a href="<?php echo site_url('user_enquiry/reject/' . $value->as_id); ?>"><button type="button" class="btn btn-sm btn-danger">Reject</button></a> -->
                                        
                                        <?php 
                                         $this->db->select("*");
                                         $this->db->from("assigned_enquriy as sa");
                                         $this->db->where("sa.service_id",$value->id);
                                         $query=$this->db->get();
                                         $row = $query->row();
                                         $status=$row->status;
                                       if($status==1){?>

<a href="#"><button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#status<?php echo $value->as_id; ?>">Status</button></a>
                                       <?php }else{?>
Status Changed
                                       <?php }?>
                                       <a href="<?php echo site_url('user_enquiry/view_enquiry/'.$value->id)?>"><button type="button" class="btn btn-sm btn-info">View</button></a>
                                    </td>
                                    </td>
                                <?php
                                } ?>
                               
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

<?php foreach ($user_services as $us) {
?>
    <!-- Modal -->
    <div id="status<?php echo $us->as_id; ?>" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Update Status</h4>
                </div>
                <div class="modal-body">
                    <form action="<?php echo site_url('User_enquiry/update_status'); ?>" method="post">
                        <div class="form-group">
                            <input type="hidden" name="enquiry_id" value="<?php echo $us->as_id; ?>">
                            <input type="hidden" name="service_id" value="<?php echo $us->service_id; ?>">
                            <label for="group">Status</label>
                            <select class="form-control" name="status" class="priority">
                                <option value="">--Select--</option>
                                <option value="1">Open</option>
                                <option value="2">Convert to Lead</option>
                                <option value="3">Closed</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="remarks">Remarks</label>
                            <textarea id="review" name="review" rows="4" cols="50" class="form-control">

</textarea>
                        </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-info">Submit</button>
                    </form>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
<?php } ?>

<?php
// print_r($user_services);exit();
foreach ($user_services as $us) { ?>
    <!-- Modal -->
    <div id="myModals<?php echo $us->as_id; ?>" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Status</h4>
                </div>
                <div class="modal-body">
                    <form action="<?php echo site_url('user_enquiry/edit'); ?>" method="post">
                        <div class="form-group">
                            <input type="hidden" name="assign_id" value="<?php echo $us->as_id; ?>">
                            <label for="email">Status:</label>
                            <select class="form-control" name="final_status" required="required">
                                <option value="0">Select status</option>
                                <option value="1">Closed</option>
                                <option value="2">Convert to lead</option>

                            </select>
                        </div>
                        <div class="form-group">
                            <label for="email">Remarks:</label>
                            <textarea id="remarks" name="remarks" rows="4" cols="40">

           </textarea>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-info">Submit</button>
                    </form>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
<?php } ?>