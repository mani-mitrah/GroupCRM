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
    $('#main-wrapper').attr('class','show menu-toggle');
    $('#mrn_block').hide();
  });
  
</script>
<div class="row page-titles mx-0">
    <div class="col-sm-6 p-md-0">
        <div class="welcome-text">
            <h4>Al-baraha Enquiries</h4>
            <span>Al-Baraha Enquiries</span>
        </div>
    </div>
    <!--div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
        <a class="btn btn-primary waves-effect waves-light float-right"
            href="<?php echo base_url(); ?>enquiries/baraha/new/">
            <i class="mdi mdi-plus mr-2"></i> NEW ENQUIRY
        </a>
    </div-->
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
                      <th>Phone</th>
                      <th>Email</th>
                      <th>Subject</th>
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
                        <td><?php echo $value['phone']; ?></td>
                        <td><?php echo $value['email']; ?></td>
                        <td><?php 
                        $out = strlen($value['subject']) > 50 ? substr($in,0,50)."..." : $value['subject'];
                        echo $out; 
                        ?></td>
                        <td><?php echo date("F jS, Y", strtotime($value['created_date'])); ?></td>
                        <td><a href="<?php echo site_url('enquiries/baraha/view?id='.$value['id'])?>"><button type="button" class="btn btn-sm btn-primary">View</button></a></td>
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