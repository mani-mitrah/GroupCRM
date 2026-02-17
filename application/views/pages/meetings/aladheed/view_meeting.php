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
    $('#main-wrapper').attr('class','show menu-toggle');
    $('#mrn_block').hide();
  });
  
</script>
<div class="row page-titles mx-0">
    <div class="col-sm-6 p-md-0">
        <div class="welcome-text">
            <h4>Al-baraha Meetings</h4>
            <span>Al-Baraha Meetings</span>
        </div>
    </div>
    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
        <a class="btn btn-primary waves-effect waves-light float-right"
            href="<?php echo base_url(); ?>meetings/baraha/">
            <i class="fa fa-back mr-2"></i> Back to meetings
        </a>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="card" id="order_details_block">
            <div class="card-header">
                <h5 class="card-title">MEETING DETAILS</h5>
            </div>
            <div class="card-body">
                <?php
                  foreach ($enquiry_details as $key => $value) 
                  {
                ?>
                
                <div class="customer_block">
                  <h5><strong>CUSTOMER INFORMATION</strong></h5>
                  <table class="table">
                    <tr>
                      <td>
                        <strong>Customer Email:</strong><br />
                        <span id="o_cus_name" class="text-ontime"><?php echo $value['email'] ?></span>
                      </td>
                      <td>
                        <strong>Mobile Number:</strong><br />
                        <span id="o_cus_mobile" class="text-ontime"><?php echo $value['phone'] ?></span>
                      </td>
                    </tr>
                    <!--tr>
                      <td>
                        <strong>Customer Email:</strong><br />
                        <span id="o_cus_email" class="text-ontime">--</span>
                      </td>
                      <td>
                        <strong>Gender:</strong><br />
                        <span id="o_cus_gender" class="text-ontime">--</span>
                      </td>
                    </tr-->
                  </table>
                </div>
                <div class="order_block">
                  <h5><strong>MEETING INFORMATION</strong></h5>
                  <table class="table">
                    <tr>
                      <td>
                        <strong>Name:</strong><br />
                        <span id="o_ord_category" class="text-ontime"><?php echo $value['name']; ?></span>
                      </td>
                      <td>
                        <strong>Subject:</strong><br />
                        <span id="o_ord_sub_category" class="text-ontime"><?php echo $value['subject']; ?></span>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <strong>Created Date:</strong><br />
                        <span id="o_ord_sub_category2" class="text-ontime"><?php echo date("F jS, Y", strtotime($value['created_date'])); ?></span>
                      </td>
                      <td></td>
                      <?php
                    }
                    ?>
                      <!--td>
                        <strong>Medical Reference Number</strong><br />
                        <span id="o_ord_mrn" class="text-ontime">--</span>
                      </td-->
                    </tr>
                  </table>
                </div>
            </div>
        </div>
        </div>
    </div>
</div>