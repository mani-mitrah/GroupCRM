<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box">
            <h4 class="page-title">Meeting</h4>
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
                            <th>Created Date</th>
                            <th>Assigned Date</th>
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
                           <td><?php echo $value->name;?></td>
                             <td><?php echo $value->phone;?></td>
                            <td><?php echo $value->e_email;?></td>
                           
                            <td><?php echo $value->subject;?></td>
                            <td><?php echo $value->source;?></td>
                            <td><?php echo date("d-m-Y", strtotime($value->created_date));?></td>
                            <td><?php echo date("d-m-Y", strtotime($value->assigned_date));?></td>
                            <td><?php if($value->status==1){?>
                                Pending
                            <?php }elseif($value->status==2){?>
                                Accepted
                            <?php 
                        }
                            else{
                                ?>
                                Rejected
                                <?php
                            }
                            ?></td>
                            <?php if($value->status==2 || $value->status==3) 
                            {?>
                            
                            <td>
                            </td>
                             <?php 
                      }
                      else
                        {
                            ?>
                            <td>
                             <!-- <a href="<?php echo site_url('user_meeting/accept/'.$value->as_id);?>"><button type="button" class="btn btn-sm btn-success">Accept</button></a>
                            <a href="<?php echo site_url('user_meeting/reject/'.$value->as_id);?>"><button type="button" class="btn btn-sm btn-danger">Reject</button></a> -->
                            <a href="#"><button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#status<?php echo $value->as_id;?>">Status</button></a>
                        </td>
                            <?php 
                        }?>
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

<?php foreach($user_services as $us){?>
<!-- Modal -->
<div id="status<?php echo $us->as_id;?>" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Update Status</h4>
      </div>
      <div class="modal-body">
      <form action="<?php echo site_url('user_meeting/update_status');?>" method="post">
      <div class="form-group">
      <input type="hidden" name="enquiry_id" value="<?php echo $us->as_id;?>">
      <label for="group">Status</label>
      <select class="form-control" name="status" class="priority">
      <option value="">--Select--</option>
      <option value="1">Open</option>
      <option value="2">Resolved</option>
      </select>
      </div>

      <div class="form-group">
      <input type="time" name="meeting_time" class="form-control" id="timeInput" onchange="onTimeChange()">
      <input type="hidden" name="original_time" class="original_time">
      </div>

      <div class="form-group">
      <label for="remarks">Remarks</label>
      <textarea id="review" name="review" rows="4" cols="50" class="form-control"></textarea>
      </div>
            
      </div>
      <div class="modal-footer">
      <label for="meeting_time">Meeting Proposed Time</label>
      <button type="submit" class="btn btn-info">Submit</button>
     </form>
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
<?php } ?>

<script>
function onTimeChange() {
    var inputEle = document.getElementById('timeInput');
  var timeSplit = inputEle.value.split(':'),
    hours,
    minutes,
    meridian;
  hours = timeSplit[0];
  minutes = timeSplit[1];
  if (hours > 12) {
    meridian = 'PM';
    hours -= 12;
  } else if (hours < 12) {
    meridian = 'AM';
    if (hours == 0) {
      hours = 12;
    }
  } else {
    meridian = 'PM';
  }
  var time=hours + ':' + minutes + ' ' + meridian;
  $(".original_time").val(time);

}

</script>