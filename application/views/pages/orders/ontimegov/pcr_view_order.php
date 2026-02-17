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
    //$('#order_details_block').hide();
    //$('#no_card').show();
//    $('#main-wrapper').attr('class','show menu-toggle');
  });
  
</script>
<div class="row page-titles mx-0">
    <div class="col-sm-6 p-md-0">
        <div class="welcome-text">
            <h4>Al-baraha PCR Order</h4>
            <span>Al-Baraha PCR Order</span>
        </div>
    </div>
    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
        <a class="btn btn-primary waves-effect waves-light float-right"
            href="<?php echo base_url(); ?>orders/baraha/pcr_index">
            <i class="fa fa-back mr-2"></i> Back to pcr orders
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card" id="order_details_block">
            <div class="card-header">
                <?php
                $is_completed = $order_data['is_completed'];
                $badge = ($is_completed==1)?'<span class="badge light badge-danger">Order Completed</span>':'<span class="badge light badge-success">Order Booked & Paid</span>';
                ?>
                <h5 class="card-title">PCR ORDER DETAILS&nbsp;&nbsp;<?php echo $badge; ?></h5>
            </div>
            <div class="card-body">
                
                
                <div class="customer_block">
                  <h5><strong>CUSTOMER INFORMATION</strong></h5>
                  <table class="table">
                    <tr>
                      <td>
                        <strong>Customer Name:</strong><br />
                        <?php echo $customer_data['first_name'].' '.$customer_data['last_name']; ?>
                      </td>
                      <td>
                        <strong>Mobile Number:</strong><br />
                        <?php echo $customer_data['mobile']; ?>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <strong>Customer Email:</strong><br />
                        <?php echo $customer_data['email']; ?>
                      </td>
                      <td>
                        
                      </td>
                    </tr>
                  </table>
                </div>
                <div class="order_block">
                  <h5><strong>ORDER INFORMATION</strong></h5>
                  <table class="table">
                    <tr>
                      <td>
                        <strong>Category:</strong><br />
                        <?php echo $category_name; ?>
                      </td>
                      <td>
                        <strong>Subcategory:</strong><br />
                        <?php echo $sub_category_name; ?>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <strong>Total Amount Paid:</strong><br />
                        <?php echo $order_data['total_amount']; ?>
                      </td>
                      <td>
                        <strong>Preffered date:</strong><br />
                        <?php echo $order_data['preferred_date']; ?>
                      </td>
                    </tr>
                  </table>
                </div>
                
                <div id="payment_block">
                  <h5><strong>PAYMENT DETAILS</strong></h5>
                  <table class="table">
                    <tr>
                      <td>
                        <strong>Payment Status:</strong><br />
                        <?php echo $payment_data['response_class_description']; ?>
                      </td>
                      <td>
                        <strong>Approval code:</strong><br />
                        <?php echo $payment_data['approval_code']; ?>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <strong>Card Number</strong><br />
                        <?php echo $payment_data['card_number']; ?>
                      </td>
                      <td>
                        <strong>Card Expiry</strong><br />
                        <?php echo $payment_data['card_expiry']; ?>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <strong>Card Brand</strong><br />
                        <?php echo $payment_data['card_brand']; ?>
                      </td>
                      <td>
                        <strong>Unique ID</strong><br />
                        <?php echo $payment_data['unique_id']; ?>
                      </td>
                    </tr>
                  </table>
                </div>
                <div id="attachments_block">
                  <h5><strong>EXAMINIEES</strong></h5>
                  <table class="table">
                    <thead>
                      <tr>
                        <th>BOOKING ID</th>
                        <th>EXAMINEE NAME</th>
                        <th>TEST STATUS</th>
                        <th>AMOUNT</th>
                      </tr>
                    </thead>
                    <?php
                    foreach ($examinee_data as $key => $value) 
                    {
                      $is_completed = $value['is_completed'];
                      $badge = ($is_completed==1)?'<span class="badge light badge-success">Test Completed</span>':'<span class="badge light badge-danger">YET TO TEST</span>';
                      ?>
                      <tr>
                        <td><strong><?php echo 'PCR'.$value['pcr_order_id'].'-'.$value['pcr_order_item_id'];?></strong></td>
                        <td><?php echo $value['examinee_name']; ?></td>
                        <td><?php echo $badge; ?></td>
                        <td align="right">AED <?php echo $value['amount']; ?></td>
                      </tr>
                      <?php
                    }
                    ?>
                    <tr><td colspan="4" align="right">AED <?php echo $order_data['total_amount']; ?></td></tr>
                  </table>
                </div>
                <?php
                if($order_data['pcr_status']==0)
                {
                ?>
                  <div id="attachments_block">
                    <form action="<?php echo base_url(); ?>orders/baraha/pcr_view?code=<?php echo $order_data['']; ?>">
                      
                    </form>
                  </div>
                <?php
                }
                ?>
                
            </div>
        </div>
    </div>
</div>
