<div class="app-main">
  <div class="app-main__outer">
    <div class="app-main__inner p-0">
      <div class="app-page-title">
        <div class="container fiori-container">
          <div class="page-title-wrapper">
            <div class="page-title-heading">
              <div>
                PCR Test Order
                <div class="page-title-subheading">Details of Examinee and Appointment Information.</div>
              </div>
            </div>
            <div class="page-title-actions">
              <div class="d-inline-block dropdown">
                <a href="<?php echo base_url(); ?>orders/baraha/pcr_index">
                  <button type="button" class="btn btn-gradient-primary btn-shadow btn-wide">
                    <span class="btn-icon-wrapper pr-1 opacity-7">
                      <i class="fa fa-list"></i>
                    </span>
                    All PCR Orders
                  </button>
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="fiori-container container">
        <div class="app-inner-layout chat-layout justify-content-center mt-5">
          <script type="text/javascript">
            function check_appointment_slot() {
              var total_examinees = $('#total_examinees').val();
              var appointment_date = $('#preferred_date').val();
              if (appointment_date != '') {
                $.ajax({
                  url: "<?php echo base_url(); ?>orders/baraha/check_appointment",
                  method: "POST",
                  type: 'ajax',
                  data: {
                    total_examinees: total_examinees,
                    appointment_date: appointment_date
                  },
                  success: function(output) {
                    $("#booking_slots").show();
                    console.log(output);
                    let json_obj = JSON.parse(output);
                    let forenoon = json_obj.forenoon;
                    let afternoon = json_obj.afternoon;
                    $('#forenoon').html("");
                    $('#afternoon').html("");
                    $.each(forenoon, function(index, value) {
                      if (value.color == 'success') {
                        let forenoon_data = $('<tr id="t_' + value.timeslot_id + '"><td>' + value.slot_timing + '</td><td><span class="text-' + value.color + '">' + value.availability + '</span></td><td><form action="<?php echo base_url(); ?>orders/baraha/pcr_view?code=<?php echo $_GET['code']; ?>" method="post"><input type="hidden" name="timeslot_id" value="' + value.timeslot_id + '"><input type="hidden" name="appointment_date" value="' + appointment_date + '"><input type="hidden" name="total_examinees" value="' + total_examinees + '"><input type="hidden" name="order_id" value="<?php echo $_GET['code']; ?>"><button type="submit" name="update_appointment" class="btn btn-sm btn-success" id="ts_' + value.timeslot_id + '">Choose</button></form></td></tr>')
                        forenoon_data.appendTo('#forenoon');
                      } else {
                        let forenoon_data = $('<tr><td>' + value.slot_timing + '</td><td><span class="text-' + value.color + '">' + value.availability + '</span></td><td></td></tr>')
                        forenoon_data.appendTo('#forenoon');
                      }

                    });
                    $.each(afternoon, function(index, value) {
                      if (value.color == 'success') {
                        let afternoon_data = $('<tr id="t_' + value.timeslot_id + '"><td>' + value.slot_timing + '</td><td><span class="text-' + value.color + '">' + value.availability + '</span></td><td><form action="<?php echo base_url(); ?>orders/baraha/pcr_view?code=<?php echo $_GET['code']; ?>" method="post"><input type="hidden" name="timeslot_id" value="' + value.timeslot_id + '"><input type="hidden" name="appointment_date" value="' + appointment_date + '"><input type="hidden" name="total_examinees" value="' + total_examinees + '"><input type="hidden" name="order_id" value="<?php echo $_GET['code']; ?>"><button type="submit" name="update_appointment" class="btn btn-sm btn-success" id="ts_' + value.timeslot_id + '">Choose</button></form></td></tr>')
                        afternoon_data.appendTo('#afternoon');
                      } else {
                        let afternoon_data = $('<tr><td>' + value.slot_timing + '</td><td><span class="text-' + value.color + '">' + value.availability + '</span></td><td></td></tr>')
                        afternoon_data.appendTo('#afternoon');
                      }
                    });
                  },
                  error: function(err) {
                    console.log(err);
                    $("#booking_slots").show();
                  },
                });
              } else {
                alert('Please select appointment date');
              }

            }

            function choose_slot(timeslot_id) {
              var choosed_timeslot = timeslot_id;
              var preferred_date = $('#preferred_date').val();
              var order_id = $('#preferred_date').val();
              var total_examinees = $('#total_examinees').val();


            }
            //hide elements at first
            $(document).ready(function() {
              //$('#order_details_block').hide();
              //$('#no_card').show();
              $('#booking_slots').hide();
              //    $('#main-wrapper').attr('class','show menu-toggle');
              var today = new Date();
              var tomorrow = new Date(today.getTime());
              $('#preferred_date').bootstrapMaterialDatePicker({
                format: 'YYYY-MM-DD',
                minDate: tomorrow,
                time: false,
              });
            });
          </script>

          <div class="row">
            <div class="col-lg-12">
              <div class="card" id="order_details_block">
                <div class="card-header d-flex justify-content-between">
                  <?php
                  // print_r($order_data["pcr_order_id"]);
                  $is_completed = $order_data['is_completed'];
                  $payment_id = $order_data['payment_id'];
                  $badge = ($payment_id != '') ? '<span class="badge light badge-success">Order Booked & Paid</span>' : '<span class="badge light badge-danger">Payment unsuccessful</span>';
                  $badge_complete = ($is_completed == 0) ? '<span class="badge light badge-danger">Not completed</span>' : '<span class="badge light badge-success">Completed</span>';
                  ?>
                  <div>
                    <h5 class="card-title my-auto font-weight-bold">PCR ORDER DETAILS&nbsp;&nbsp;<?php echo $badge; ?>&nbsp;&nbsp;<?php echo $badge_complete; ?></h5>
                  </div>

                  <?php
                  if ($payment_id != '' && $order_data['is_completed'] != 1) {
                  ?>
                    <div>
                      <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#modelId">
                        Resend Receipt
                      </button>
                    </div>
                  <?php
                  }
                  ?>
                </div>
                <div class="card-body">

                  <?php if ($this->session->flashdata('alert')) { ?>
                    <div class="alert alert-<?php echo $this->session->flashdata('alert'); ?>">
                      <?php echo $this->session->flashdata('alert_message'); ?>
                    </div>
                  <?php } ?>
                  <div class="customer_block">
                    <h5><strong>CUSTOMER INFORMATION</strong></h5>
                    <table class="table">
                      <tr>
                        <td style="padding: 10px; width: 50%; vertical-align: top; text-align: left;" >
                          <strong>Customer Name:</strong><br />
                          <?php echo $customer_data['first_name'] . ' ' . $customer_data['last_name']; ?>
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
                          <br>
                          <br>
                          <strong>Order Created DateTime:</strong><br />
                          <?php echo $order_data['created_at']; ?>
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
                          <th>TIME SLOT</th>
                          <th>AMOUNT</th>
                        </tr>
                      </thead>
                      <?php
                      foreach ($examinee_data as $key => $value) {
                        $is_completed = $value['is_completed'];
                        $badge = ($is_completed == 1) ? '<span class="badge light badge-success">Test Completed</span>' : '<span class="badge light badge-danger">YET TO TEST</span>';
                      ?>
                        <tr>
                          <td><strong><?php echo 'PCR' . $value['pcr_order_id'] . '-' . $value['pcr_order_item_id']; ?></strong></td>
                          <td><?php echo $value['examinee_name']; ?></td>
                          <td><?php echo $badge; ?></td>
                          <td><?php echo $value['slot_timings']; ?></td>
                          <td align="left">AED <?php echo $value['amount']; ?></td>
                        </tr>
                      <?php
                      }
                      ?>
                      <tr>
                        <td colspan="4" align="right"></td>
                        <td align="left">AED <?php echo $order_data['total_amount']; ?></td>
                      </tr>
                    </table>
                  </div>
                  <!-- <div id="pos_response">
                    <h5><strong>POS RESPONSE</strong></h5>
                    <table class="table">
                      <tr>
                        <td>
                          <strong>POS Response code:</strong><br />
                          <?php echo $order_data['pos_response_code']; ?>
                        </td>
                        <td>
                          <strong>POS Response:</strong><br />
                          <?php echo $order_data['pos_response']; ?>
                        </td>
                      </tr>
                    </table>
                    <br />
                    <?php
                    $user_id = $this->mcommon->specific_row_value('pcr_order', array('pcr_order_id' => $_GET['code']), 'user_id');
                    if ($order_data['pos_response_code'] != 200) {
                    ?>
                      <a class="btn btn-sm btn-success" href="https://crm.ontimegroup.com/pcr/test_pos/<?php echo $_GET['code']; ?>/<?php echo $user_id; ?>/<?php echo $payment_data['approval_code']; ?>">Update POS</a>
                    <?php
                    }
                    ?>

                  </div> -->
                  <?php
                  if ($order_data['pcr_status'] == 0) {
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
          <?php
          if ($payment_id != '' && $order_data['is_completed'] != 1) {
          ?>

            <div class="row">

              <!-- Modal -->

            </div>

            <div class="row">
              <div class="col-lg-12">
                <div class="card" id="order_details_block">
                  <div class="card-header">
                    <h5 class="card-title">UPDATE APPOINTMENT</h5>
                  </div>
                  <div class="card-body">
                    <form action="<?php echo base_url(); ?>orders/baraha/pcr_view?code=<?php echo $_GET['code']; ?>">
                      <div class="form-group">
                        <label>Choose Next Available Date</label>
                        <input type="date" id="preferred_date" name="preferred_date" class="form-control" value="<?php echo (isset($_POST['preferred_date'])) ? $_POST['preferred_date'] : ''; ?>" min="<?php echo date('Y-m-d'); ?>">
                        <input type="hidden" name="total_examinees" value="<?php echo count($examinee_data); ?>" id="total_examinees">
                        <input type="hidden" name="order_id" id="order_id" value="<?php echo $_GET['code']; ?>" id="total_examinees">
                        <br />
                        <button type="button" onclick="javascript:check_appointment_slot();" class="btn btn-success">Check Appointment Slots</button>
                      </div>
                    </form>

                    <div class="" id="booking_slots">
                      <h5>Forenoon</h5>
                      <table class="table table-striped" id="forenoon">

                      </table>
                      <h5>Afternoon</h5>
                      <table class="table table-striped" id="afternoon">

                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          <?php
          }
          ?>
          <script>
            $("document").ready(function() {
              $("#resend_form").submit(function(e) {
                e.preventDefault();
                $.ajax({
                  url: "https://baraha.ae/resend_receipt_byorder?token=<?php echo md5($order_data["pcr_order_id"]); ?>&mail=" + $('[name="email"]').val(),
                  beforeSend: function() {
                    $('[type="submit"]').attr("disabled", "true");
                  },
                  success: function(data) {
                    if (data == "true") {
                      alert("Resend mail successfully sent.");
                      $("#modelId").modal("hide");
                    } else {
                      alert("Something went wrong. Check the email address or contact support.");
                    }
                  }
                });
              });
            });
          </script>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="#" method="post" id="resend_form">
        <div class="modal-header">
          <h5 class="modal-title">Resend Receipt</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group mb-0">
            <input type="email" name="email" id="" class="form-control" required placeholder="Email Address" value="<?php echo $customer_data['email']; ?>" />
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn p-1 pl-3 pr-3" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary p-1 pl-3 pr-3">Send</button>
        </div>
      </form>
    </div>
  </div>
</div>