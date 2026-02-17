<div class="app-main">
  <div class="app-main__outer">
    <div class="app-main__inner p-0">
      <div class="app-page-title">
        <div class="container fiori-container">
          <div class="page-title-wrapper">
            <div class="page-title-heading">
              <div>
              Al Adheed Orders
                <div class="page-title-subheading">Details of Examinee and Appointment Information.</div>
              </div>
            </div>
            <div class="page-title-actions">
              <div class="d-inline-block dropdown">
                <a href="/orders/aladheed">
                  <button type="button" class="btn btn-gradient-primary btn-shadow btn-wide">
                    <span class="btn-icon-wrapper pr-1 opacity-7">
                      <i class="fa fa-list"></i>
                    </span>
                    All Al Adheed Orders
                  </button>
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="fiori-container container">
        <div class="app-inner-layout chat-layout justify-content-center mt-5">
          <style type="text/css">
            .card {
              height: auto !important;
            }

            .text-ontime {
              color: #3d4465 !important;
            }

            .dataTables_wrapper .dataTables_paginate .paginate_button.previous.disabled,
            .dataTables_wrapper .dataTables_paginate .paginate_button.next.disabled {
              color: #969ba0 !important;
            }

            #gpdf {
              display: none;
            }
          </style>
          <script type="text/javascript">
            //hide elements at first
            $(document).ready(function() {
              //$('#order_details_block').hide();
              //$('#no_card').show();
              //    $('#main-wrapper').attr('class','show menu-toggle');
              $('#block_102').hide();
              $('#block_103').hide();
              $('#block_106').hide();
            });
          </script>
         
          <div class="row">
            <div class="col-lg-12">

              <div class="card" id="order_details_block">
                <div class="card-header">
                  <?php
                  $status = $order_details['order_status'];
                  if ($status == 101) {
                    $badge = 'success  text-white';
                  } else if ($status == 102) {
                    $badge = 'warning text-white';
                  } else if ($status == 103) {
                    $badge = 'primary text-white';
                  } else if ($status == 104) {
                    $badge = 'info  text-white';
                  } else if ($status == 105) {
                    $badge = 'danger  text-white';
                  }
                  ?>
                  <h5 class="card-title">ORDER DETAILS <span class="badge badge-primary text-white"><?php echo 'BOOKING ID: ' . $order_details['alad_order_id']; ?></span>&nbsp;&nbsp;<span class="badge badge-<?php echo $badge; ?>"><?php echo 'STATUS: ' . $order_details['status_name']; ?></span></h5>
                </div>
                <div class="card-body">
                  <div class="customer_block">
                    <h5><strong>CUSTOMER INFORMATION</strong></h5>
                    <table class="table">
                      <tr>
                        <td>
                          <strong>Customer Name:</strong><br />
                          <span id="o_cus_name" class="text-ontime"><?php echo $order_details['first_name'] . ' ' . $order_details['last_name']; ?></span>
                        </td>
                        <td>
                          <strong>Mobile Number:</strong><br />
                          <span id="o_cus_mobile" class="text-ontime"><?php echo $order_details['country_code'] . ' ' . $order_details['mobile']; ?></span>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <strong>Customer Email:</strong><br />
                          <span id="o_cus_email" class="text-ontime"><?php echo $order_details['email']; ?></span>
                        </td>
                      </tr>
                    </table>
                  </div>
                  <div class="order_block">
                    <h5><strong>ORDER INFORMATION</strong></h5>
                    <?php
                    echo $order_details['details'];
                    ?>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <script type="text/javascript">
            function load_card(selected_status) {
              console.log(selected_status);
              if (selected_status == 102) {
                $('#block_102').show();
              } else if (selected_status == 103) {
                $('#block_103').show();
              } else if (selected_status = 106) {
                $('#block_106').show();
              }
            }
          </script>
        </div>
      </div>
    </div>
  </div>
</div>