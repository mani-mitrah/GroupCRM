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
            <h4 class="page-title">Leads
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
                <table id="datatable" class="table table-bordered dt-responsive nowrap"
                    style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                    <thead>
                        <tr>
                            <th>S.no</th>
                            <th>Name</th>
                            <th>Mobile</th>
                            <th>Email</th>
                            <th>Message</th>
                            <th>Source</th>
                            <th>Group</th>
                            <th>Assigned To</th>
                            <th>Created Time</th>
                            <th>Closed Time</th>      
                            <th>Status</th>
                            <!-- <th>Action</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php
			                foreach ($enquiry as $key=>$value) 
			                {
			                ?>
                        <tr>
                            <td><strong><?php echo $key+1;?></strong></td>
                            <td><?php echo $value->name;?></td>
                            
                            <td><?php echo $value->phone;?></td>
                            <td><?php echo $value->email;?></td>
                            <td><?php echo $value->subject;?></td>
                            <td><?php echo $value->source;?></td>
                            <td><?php 
                          $this->db->select("*");
                          $this->db->from("assigned_enquriy as ae");
                          $this->db->join("group_master as gm","gm.g_id=ae.group_id");
                          $this->db->where("ae.service_id",$value->id);
                          $query=$this->db->get();
                          $ret = $query->row();
                          echo $ret->group_name;
                         ?></td>
                            <td>
                            <?php 
                            $this->db->select("*");
                            $this->db->from("assigned_enquriy as sa");
                            $this->db->join("users as u","u.user_id=sa.assigned_user");
                            $this->db->where("sa.service_id",$value->id);
                            $query=$this->db->get();
                            $ret = $query->row();
                            echo $ret->first_name.''.$ret->last_name;
                            ?>
                            </td>
                            <td><?php echo $value->created_time;?></td>
                            <td><?php echo $value->closed_time;?></td>
                            <td><?php
                             $this->db->select("*");
                             $this->db->from("assigned_leads as sa");
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
                            <!-- <td><?php echo date("d-m-Y h:i:s", strtotime($value->created_date));?></td> -->
                            <!-- <td><?php echo $value->created_time;?></td> -->
                            <!-- <td>
                            <?php 
                            $this->db->select("*");
                            $this->db->from("assigned_enquriy as sa");
                            $this->db->where("sa.service_id",$value->id);
                            $query=$this->db->get();
                            $ret_date = $query->row();
                            if($ret_date)
                            {
                                 echo date("d-m-Y h:i:s", strtotime($ret_date->assigned_date));

                            }else
                            {
                            }
                           
                            ?>
                                
                            </td> -->
                            
                           
                            <!-- <td>
                            <?php if($value->id == $en->service_id){?>
                                Assigned
                            <?php }else{?>
                                <a href="#"><button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#myModals<?php echo $value->as_id;?>" >Assign Group</button></a>
                            <?php }?>
 
                            
                            <a href="<?php echo site_url('enquiry/view_enquiry/'.$value->id)?>"><button type="button" class="btn btn-sm btn-info">View</button></a>
                            </td> -->
                            
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
<?php foreach($enquiry as $us){
?>
<!-- Modal -->
<div id="priority<?php echo $us->id;?>" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Set Priority</h4>
      </div>
      <div class="modal-body">
      <form action="<?php echo site_url('enquiry/set_priority');?>" method="post">
      <div class="form-group">
      <input type="hidden" name="enquiry_id" value="<?php echo $us->id;?>">
      <label for="group">Prioriy</label>
      <select class="form-control" name="priority" class="priority">
      <option value="">--Select--</option>
      <option value="1">Low</option>
      <option value="2">Medium</option>
      <option value="3">High</option>
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


<?php foreach($enquiry as $us){
?>
<!-- Modal -->
<div id="myModals<?php echo $us->as_id;?>" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Assign Group</h4>
      </div>
      <div class="modal-body">
      <form action="<?php echo site_url('assigned_leads/assign_user');?>" method="post">
      <div class="form-group">

      <label for="group">Group</label>
      <select class="form-control" name="group" class="group" onchange="get_users(this.value);">
      <option value="">--Select--</option>
      <?php foreach($group as $g){?>
      <option value="<?php echo $g->g_id;?>"><?php echo $g->group_name;?></option>
      <?php } ?>
      </select>
      </div>
            <div class="form-group">
            <input type="hidden" name="service_id" value="<?php echo $us->service_id;?>">
            <label for="email">User:</label>
            <select class="form-control" name="users" class="users" required="required">
                <option value="">Select user</option>
          
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

<script type="text/javascript">
function get_users(group_id){
   $.ajax({
    url:"<?php echo site_url('enquiry/get_group_users');?>",
    method:"POST",
    type:"json",
    data:{group_id:group_id},
    success:function(res){
        var result=JSON.parse(res);
            $('select[name="users"]').empty();
                    $(".users").prepend("<option>--Select--</option>");
                        $.each(result, function(key, value) {
                            var name=value.first_name+' '+value.last_name;
                            console.log(name);
                            $('select[name="users"]').append('<option value="' + value.user_id +'">'+ name +'</option>');
                        });
    },error:function(err){
        console.log(err);
    }
   });
}
</script>