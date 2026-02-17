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
            <h4 class="page-title">Order Details
            </h4>
            <div style="float:right;margin-top: -30px;">
            <a href="<?php echo site_url('meeting');?>"><button type="button" class="btn btn-sm btn-info">Back</button></a>
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
            <?php foreach ($data as $d) { ?>
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

                        $sql = "SELECT * FROM users WHERE user_id=" . $d['user_id'];

                        $result1 = $conn->query($sql);
                        if ($result1->num_rows > 0) {
                            // output data of each row
                            while ($rowa1 = $result1->fetch_assoc()) {
                                $username = $rowa1["first_name"] . ' ' . $rowa1["last_name"];
                                $email = $rowa1['email'];
                                $mobile = $rowa1['mobile'];
                            }
                        } else {
                            $username = "";
                            $email = "";
                            $mobile = "";
                        }

                        ?>

                 
            <?php } ?>
<h4>User Details:</h4>
  <tr>
    <th>Name:</th>
    <td><?php echo $username;?></td>
  </tr>
  <tr>
    <th>Phone:</th>
    <td><?php echo $mobile;?></td>
  </tr>
  <tr>
    <th>Email:</th>
    <td><?php echo $email;?></td>
  </tr>
  <tr>
    <th>Company:</th>
    <td><?php echo "Weqayati";?></td>
  </tr>

</table>
<hr>
<h4>Service Details:</h4>
<table style="width:100%" class="table table-bordered">
<tr>
    <th>Category Name:</th>
    <td>  <?php echo $d['category_name']; ?></td>
  </tr>
  <tr>
    <th>Sub Category Name :</th>
    <td><?php echo $d['sub_category_name']; ?></td>
  </tr>
  <tr>
    <th>Sub Category Two:</th>
    <td><?php echo $d['sub_categorytwo']; ?></td>
  </tr>
  <tr>
    <th>Gender:</th>
    <td><?php if ($d['gender'] == 1) {
                                echo "Male";
                            } else {
                                echo "Female";
                            } ?></td>
  </tr>
  <tr>
  <th>Service Hours:</th>
  <td> <?php echo $d['service_hour']; ?></td>
  </tr>

  <tr>
  <th>Medical Ref.no :</th>
  <td><?php if ($d['med_number'] != 0) {
                                echo $d['med_number'];
                            } ?></td>
  </tr>

  <tr>
  <th>Pregnancy :</th>
  <td> <?php if ($d['pregnancy'] == 1) {
                                echo "YES";
                            } else {
                                echo "No";
                            } ?></td>
  </tr>

  <tr>
  <th>LMP :</th>
  <td>     <?php if ($d['period']) {
                                echo $d['menstrual_period'];
                            } ?></td>
  </tr>

  <tr>
  <th>Abortion :</th>
  <td><?php if ($d['abortion'] == 1) {
                                echo "YES";
                            } else {
                                echo "No";
                            } ?></td>
  </tr>

  <tr>
  <th>Contraceptive Pills :</th>
  <td><?php if ($d['contraceptive_pills'] == 1) {
                                echo "YES";
                            } else {
                                echo "No";
                            } ?></td>
  </tr>

  <tr>
  <th>Source :</th>
  <td>  <?php if($d['source']!=''){ echo "Web"; } else { echo "Wifi"; } ?></td>
  </tr>

  <tr>
  <th>Amount :</th>
  <td><?php echo 'AED ' . $d['amount']; ?></td>
  </tr>

<?php foreach($d['img'] as $ke=>$a){?>
<tr>
<th>Attachment: <?php echo $ke+1;?></th>
<td><iframe src="<?php echo $a;?>" width="400" height="100"></td>
</tr>
<?php }?>

</table>
<hr>
<table style="width:100%" class="table table-bordered">
<?php if(!empty($payment_details)){?>
    <h3>Payment Details</h3>
<?php }?>

<?php foreach($payment_details as $pd){?>
    <tr>
  <th>Response Description :</th>
  <td><?php echo $pd->response_description; ?></td>
  </tr>
  <tr>
  <th>Response Class Description :</th>
  <td><?php echo $pd->response_class_description; ?></td>
  </tr>
  <tr>
  <th>Approval Code :</th>
  <td><?php echo $pd->approval_code; ?></td>
  </tr>
  <tr>
  <th>Account:</th>
  <td><?php echo $pd->account; ?></td>
  </tr>
  <tr>
  <th>Amount:</th>
  <td><?php echo $pd->amount; ?></td>
  </tr>
  <tr>
  <th>Unique Id:</th>
  <td><?php echo $pd->unique_id; ?></td>
  </tr>
<?php }?>
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