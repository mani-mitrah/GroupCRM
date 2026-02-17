<div class="row page-titles mx-0">
    <div class="col-sm-6 p-md-0">
        <div class="welcome-text">
            <h4><?php echo $page_title; ?></h4>
            <span>Al-Baraha Orders</span>
        </div>
    </div>
    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
        <a class="btn btn-primary waves-effect waves-light float-right"
            href="<?php echo base_url(); ?>admin/company/add/">
            <i class="mdi mdi-plus mr-2"></i> NEW ORDER
        </a>
    </div>
</div>
<div class="row">
  
</div>

<style type="text/css">
    a {
  color: red;
}
</style>
<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box">
            <h4 class="page-title">Orders
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
                            <th>Mobile Number</th>
                            <th>Email Address</th>
                            <th>Source</th>
                            <th>Category</th>
                            <th>Sub Category</th>
                            <th>Group</th>
                            <th>Assigned To</th>
                            <th>Created Time</th>
                            <th>Closed Time</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                       //print_r($user_services);
			                foreach ($user_services as $key=>$value) 
			                {
			                ?>
                        <tr>
                            <td><strong><?php echo $key+1;?></strong></td>
                            <td>
                            <?php 
                             if ($_SERVER['HTTP_HOST'] == 'localhost') {
                                $servername = 'localhost';
                                $username = 'chatbot_user';
                                $password = 'chatbot_user';
                                $dbname = 'ontimedigital_oneauth';
                            } else {
                                $servername = 'localhost';
                                $username = 'chatbot_user';
                                $password = '5700d$A4';
                                $dbname = 'ontimedigital_oneauth';
                            }
                            // Create connection
                            $conn = new mysqli($servername, $username, $password, $dbname);
                            // Check connection
                            if ($conn->connect_error) {
                                echo "Error: " . $sql . "<br>" . $conn->error;
                            }
                        
                            $sql = "SELECT * FROM users WHERE user_id=". $value['user_id'];
                        
                            $result1 = $conn->query($sql);
                            if ($result1->num_rows > 0) {
                                // output data of each row
                                while ($rowa1 = $result1->fetch_assoc()) {
                                    $username = $rowa1["first_name"].' '.$rowa1["last_name"];
                                    $mobile=$rowa1['mobile'];
                                    $email=$rowa1['email'];
                                }
                            } else {
                                $username = "";
                                $mobile="";
                                $email="";
                            }
                            echo $username;
                            ?>
          
                            </td>
                           
                             <td><?php echo $mobile;?></td>
                             <td>
                            <?php echo $email;?>
                            </td>
                            <td><?php echo "Web site"; ?></td>
                            <td><?php echo  $value['category_name'];?></td>

                            <td><?php 
                                 if( $value['sub_category_name'] != '')
                                 {
                                     echo $value['sub_category_name'];
                                 }else
                                 {
                                    echo '-';
                                 }
                             ?></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>
                            <?php
                             $this->db->select("*");
                             $this->db->from("assigned_order as sa");
                             $this->db->where("sa.order_id",$value['order_id']);
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
                            ?>
                            </td>
                            <td>
                            <?php 
                                 $this->db->select("*");
                                 $this->db->from("assigned_order as sa");
                                 $this->db->where("sa.order_id",$value['order_id']);
                                 $query=$this->db->get();
                                 $ret = $query->row();
                                 $order_id=$ret->order_id;
                            if($order_id){?>
                                Assigned
                            <?php }else{?>
                                <a href="#"><button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#myModal<?php echo $value['order_id'];?>" >Assign Group</button></a>
                            <?php }?>
 
                            <?php if($value['priority']!=0){
                                if($value['priority']==1){
                                    echo "Priority: Low";
                                }else if($value['priority']==2){
                                    echo "Priority: Medium";
                                }else if($value['priority']==3){
                                    echo "Priority: High";
                                }?>
                            <?php }else{?>
                                <a href="#"><button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#priority<?php echo $value['order_id'];?>">Priority</button></a>
                            <?php }?>
                            <a href="<?php echo site_url('leads/view_orders/'.$value['order_id'])?>"><button type="button" class="btn btn-sm btn-info">View</button></a>
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


<?php foreach($user_services as $us){
?>
<!-- Modal -->
<div id="priority<?php echo $us['item_id'];?>" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Set Priority</h4>
      </div>
      <div class="modal-body">
      <form action="<?php echo site_url('Leads/set_priority');?>" method="post">
      <div class="form-group">
      <input type="hidden" name="enquiry_id" value="<?php echo $us['order_id'];?>">
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



<?php foreach($user_services as $us){
?>
<!-- Modal -->
<div id="myModal<?php echo $us['order_id'];?>" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Assign Group</h4>
      </div>
      <div class="modal-body">
      <form action="<?php echo site_url('leads/assign_user');?>" method="post">
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
            <input type="hidden" name="service_id" value="<?php echo $us['order_id'];?>">
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
    url:"<?php echo site_url('meeting/get_group_users');?>",
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