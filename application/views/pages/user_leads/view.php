<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box">
            <h4 class="page-title">Leads
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
                <table id="datatable" class="table table-bordered dt-responsive nowrap"
                    style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                    <thead>
                        <tr>
                            <th>S.no</th>
                            <th>User</th>
                            <th>Category</th>
                            <th>Sub Category</th>
                            <th>Gender</th>
                            <th>Service Hours</th>
                            <th>Amount</th>
                            <th>Payment Date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
			                foreach ($user_services as $key=>$value) 
			                {
			                ?>
                        <tr>
                            <td><strong><?php echo $key+1;?></strong></td>
                            <td><?php 
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
                        
                            $sql = "SELECT * FROM users WHERE user_id=".$value->user_id;
                        
                            $result1 = $conn->query($sql);
                            if ($result1->num_rows > 0) {
                                // output data of each row
                                while ($rowa1 = $result1->fetch_assoc()) {
                                    $username = $rowa1["first_name"].' '.$rowa1["last_name"];
                                }
                            } else {
                                $username = "";
                            }
                            echo $username;
                            ?></td>
                            <td><?php echo $value->category_name;?></td>
                            <td><?php echo $value->sub_category_name;?></td>
                            <td><?php if($value->gender==1){
                                echo "Male";
                            }else{
                                echo "Female";
                            }?></td>
                            <td><?php echo $value->service_hour;?></td>
                            <td><?php echo $value->amount;?></td>
                            <td><?php echo $value->payment_date;?></td>
                            <td><?php if($value->status==1){?>
                                Accepted
                            <?php }else{?>
                                Rejected
                            <?php }?></td>
                            
                            <td>
                            <?php 
                            $this->db->select("*");
                            $this->db->from("assigned_service");
                            $this->db->where("service_id",$value->o_id);
                            $query = $this->db->get();
$ret = $query->row();
$application_status=$ret->status;
if($application_status==2){
    echo "Application Closed";
}else{?>
       <button type="button" class="btn btn-sm btn-success"  data-toggle="modal" data-target="#myModal<?php echo $value->as_id;?>" >Edit</button>
<?php }?>
                        
                            <!-- <a href="<?php echo site_url('user_leads/reject/'.$value->as_id);?>"><button type="button" class="btn btn-sm btn-danger">Reject</button></a> -->
                          
                  
                            
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

<?php foreach($user_services as $us){?>
<!-- Modal -->
<div id="myModal<?php echo $us->us_id;?>" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Assign User</h4>
      </div>
      <div class="modal-body">
      <form action="<?php echo site_url('leads/assign_user');?>" method="post">
            <div class="form-group">
            <input type="hidden" name="service_id" value="<?php echo $us->us_id;?>">
            <label for="email">User:</label>
            <select class="form-control" name="users" required="required">
                <option value="">Select user</option>
                <?php foreach($users as $u){?>
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


<?php
			                foreach ($user_services as $key=>$value) 
			                {
			                ?>
<!-- Modal -->
<div id="myModal<?php echo $value->as_id;?>" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Edit Service</h4>
      </div>
      <div class="modal-body">
      <form class="mt25" method="post" action="<?php echo site_url('user_leads/application_edit/');?>" enctype='multipart/form-data'>
                                <div class="row">
                                    <input name="user_id" type="hidden" value="<?php echo $_SESSION['user_id']; ?>">
                                    <input type="hidden" name="service_id" value="<?php echo $value->service_id;?>">
                                    <input type="hidden" name="language" class="language">
                                    <input name="sess_id" type="hidden" value="<?php echo $_SESSION['ses_id']; ?>">
                                    <div class="form-group col-md-12">
                                        <select name="category" id="category" class="form-control category" required>
                                            <option value="" selected="">Select Category</option>
                                            <?php foreach($category as $c){?>
                                                <option <?php if($value->category==$c->id){ echo "selected"; } ?>  value="<?php echo $c->id?>"><?php echo $c->category_name?></option>
                                            <?php }?>
                                           
                                        </select>
                                    </div>
                                   
                                    <div class="form-group col-md-12" id="msubcategory">
                                        <select name="sub_category" class="form-control sub_category" id="sub_category" onchange="get_subcategory2(this.value);">
                                       
                                            <option value="" selected="">Select Sub Category</option>
                                            <?php foreach($sub_category as $sc){?>
                                                <option <?php if($value->sub_category==$sc->id){ echo "selected"; } ?> value="<?php echo $sc->id?>"><?php echo $sc->sub_category_name?></option>
                                            <?php }?>
                                           
                                        </select>
                                    </div>
                                    <div class="form-group col-md-12" id="msubcategory2">
                                        <select name="sub_categoty_two" class="form-control subcategory2" required id="subcategory2">
                                            <option value="" selected="">Select Sub Category 2</option>
                                            <?php foreach($sub_category2 as $sc2){?>
                                                <option <?php if($value->sub_category_two ==$sc2->id){ echo "selected"; } ?> value="<?php echo $sc2->id?>"><?php echo $sc2->sub_categorytwo?></option>
                                            <?php }?>
                                        </select>
                          
                                    </div>
                                    <div class="form-group col-md-12" id="mgender">
                                        <select name="gender" id="gender" class="form-control gender" required>
                                            <option value="" selected="">Select Gender</option>
                                            <option <?php if($value->gender ==1){ echo "selected"; } ?> value="1">Male</option>
                                            <option <?php if($value->gender ==2){ echo "selected"; } ?> value="2">Female</option>

                                        </select>
                                    </div>
                                  
                                    <div class="form-group col-md-12" id="mhours">
                                        <select name="service_time" class="form-control service_time" required>
                                            <option value="" selected="">Select Service Hours</option>
                                            <?php foreach($service_hours as $sh){?>
                                                <option <?php if($value->service_time == $sh->id){ echo "selected"; } ?> value="<?php echo $sh->id?>"><?php echo $sh->service_hour;?></option>
                                            <?php }?>
                                        </select>
                                    </div>

                                    <?php if($value->med_number==0){?>
                                        <div class="form-group col-md-12" id="mhours">
                                       <input  type="text" name="med_number" class="form-control" placeholder="Enter Medical Reference Number" >
                                    </div>
                                    <?php }?>

                                    <div class="form-group col-md-12" id="mhours">
                                        <select name="status" class="form-control status" required>
                                            <option value="" selected="">Select Application Status</option>
                                           <option value="1">Application Opened</option>
                                           <option value="2">Application Closed</option>
                                        </select>
                                    </div>

                                </div>
                        
      </div>
      <div class="modal-footer">
      <button type="submit" class="btn btn-success">Submit</button>
      </form>
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
<?php } ?>