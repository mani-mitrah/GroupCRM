<?php 
foreach($assigned_enquriy as $en)
{

}
?>
<style type="text/css">
    a {
  color: red;
}
</style>
<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box">
            <h4 class="page-title">Enquiry Details
            </h4>
            <div style="float:right;margin-top: -30px;">
            <a href="<?php echo site_url('enquiry');?>"><button type="button" class="btn btn-sm btn-info">Back</button></a>
            </div>
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
            <table style="width:100%" class="table table-bordered">
            <?php foreach($enquiry as $e){?>
  <tr>
    <th>Name:</th>
    <td><?php echo $e->name;?></td>
  </tr>
  <tr>
    <th>Phone:</th>
    <td><?php echo $e->phone;?></td>
  </tr>
  <tr>
    <th>Email:</th>
    <td><?php echo $e->email;?></td>
  </tr>
  <tr>
    <th>Company:</th>
    <td><?php echo "Weqayati";?></td>
  </tr>
 
  <tr>
    <th>Source:</th>
    <td><?php echo $e->source;?></td>
  </tr>
  <tr>
    <th>Priority:</th>
    <td>
    <?php 
  if($e->priority==1){
    echo "Low";
}else if($e->priority==2){
    echo "Medium";
}else if($e->priority==3){
    echo "High";
}else{
    echo "Priority Not Set"; 
}
    ?>
  </td>
  </tr>
  <tr>
    <th>Message:</th>
    <td><?php echo $e->subject;?></td>
  </tr>
  <tr>
    <th>Assign To:</th>
    <td><?php 
                         $this->db->select("*");
                         $this->db->from("assigned_enquriy as sa");
                         $this->db->join("users as u","u.user_id=sa.assigned_user");
                         $this->db->where("sa.service_id",$e->id);
                         $query=$this->db->get();
                         $ret = $query->row();
                        echo $ret->first_name.''.$ret->last_name;
    //echo $e->assign_to;?></td>
  </tr>
  <tr>
    <th>Group:</th>
    <td><?php 
    $this->db->select("*");
    $this->db->from("assigned_enquriy as ae");
    $this->db->join("group_master as gm","gm.g_id=ae.group_id");
    $this->db->where("ae.service_id",$e->id);
    $query=$this->db->get();
    $ret = $query->row();
    echo $ret->group_name;?>
</td>
  </tr>
  <tr>
    <th>Remarks:</th>
    <td><?php echo $e->remarks;?></td>
  </tr>

  <tr>
    <th>Created Time:</th>
    <td><?php echo $e->created_time;?></td>
  </tr>
  <tr>
    <th>Assigned Time:</th>
    <td><?php  $this->db->select("*");
    $this->db->from("assigned_enquriy as ae");
    $this->db->join("group_master as gm","gm.g_id=ae.group_id");
    $this->db->where("ae.service_id",$e->id);
    $query=$this->db->get();
    $ret = $query->row();
    echo $ret->assigned_time;?></td>
  </tr>
  <tr>
    <th>Closed Time:</th>
    <td><?php echo $e->closed_time;?></td>
  </tr>
  <tr>
    <th>SLA:</th>
    <td><?php echo $e->sla;?></td>
  </tr>
  <tr>
    <th>Status:</th>
    <td><?php echo $e->status;?></td>
  </tr>
  <tr>
    <th>Created date:</th>
    <td><?php echo  date("d-m-Y", strtotime($e->created_date));?></td>
  </tr>
  <?php } ?>
</table>
            </div>
        </div>
    </div>
</div>

<?php foreach($user_services as $us){
?>
<!-- Modal -->
<div id="myModals<?php echo $us->id;?>" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Assign User</h4>
      </div>
      <div class="modal-body">
      <form action="<?php echo site_url('enquiry/assign_user');?>" method="post">
            <div class="form-group">
            <input type="hidden" name="service_id" value="<?php echo $us->id;?>">
            <label for="email">User:</label>
            <select class="form-control" name="users" required="required">
                <option value="">Select user</option>
                <?php foreach($company_user as $u){?>
                    <option value="<?php echo $u->user_id;?>"><?php echo $u->first_name.''.$u->last_name;?></option>
                <?php }?>
            </select>
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