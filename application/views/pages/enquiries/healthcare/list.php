<style type="text/css">
  .text-ontime
  {
    color:#3d4465 !important;
  }
  .dataTables_wrapper .dataTables_paginate .paginate_button.previous.disabled, .dataTables_wrapper .dataTables_paginate .paginate_button.next.disabled {
      color: #969ba0 !important;
  }
</style>
<script type="text/javascript">
  //hide elements at first
  $( document ).ready(function() {
    $('#order_details_block').hide();
    $('#no_card').show();
//    $('#main-wrapper').attr('class','show menu-toggle');
    $('#mrn_block').hide();
  });
  
</script>
<div class="row page-titles mx-0">
    <div class="col-sm-6 p-md-0">
        <div class="welcome-text">
            <h4><?php echo $website_details['website_name'].' ENQUIRIES'; ?></h4>
            <span>Manage <?php echo $website_details['website_name'].' Enquiries'; ?></span>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <table id="datatable" cellpadding="2" cellspacing="1" class="table table-striped">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Name</th>
                      <th>Contact</th>
                      <th>Last Action</th>
                      <th>Status</th>
                      <th>Enquiry Date</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    foreach ($enquiries as $key => $value) 
                    {
                    ?>
                      <tr>
                        <td><?php echo $value['id']; ?></td>
                        <td><?php echo $value['name']; ?></td>
                        <td><?php echo $value['phone']; ?><br/><?php echo $value['email']; ?></td>
                        <td><?php $dd = last_action_details($value["id"]); echo $dd[1]."<br>".$dd[0]."<br>".$dd[2];?></td>
                        <td><?php echo $value['status_name'];?></td>
                        <td><?php echo date("F jS, Y", strtotime($value['created_date'])); ?></td>
                        <td><a href="<?php echo site_url('enquiries/'.$website_details["short_name"].'/view?id='.$value['id'])?>"><button type="button" class="btn btn-sm btn-primary">View</button></a></td>
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