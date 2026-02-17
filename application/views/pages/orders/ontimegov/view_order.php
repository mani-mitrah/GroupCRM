<?php
$lead_current_status = $order_details['item_status'];
?>
<div class="app-main">
  <div class="app-main__outer">
    <div class="app-main__inner p-0">
      <div class="app-page-title">
        <div class="container fiori-container">
          <div class="page-title-wrapper">
            <div class="page-title-heading">
              <div>
                OnTimeGov Orders
                <div class="page-title-subheading">Details of Examinee and Appointment Information.</div>
              </div>
            </div>
            <div class="page-title-actions">
              <div class="d-inline-block dropdown">
                <a href="/orders/ontimegov">
                  <button type="button" class="btn btn-gradient-primary btn-shadow btn-wide">
                    <span class="btn-icon-wrapper pr-1 opacity-7">
                      <i class="fa fa-list"></i>
                    </span>
                    All OnTimeGov Orders
                  </button>
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="fiori-container container">
        <div class="app-inner-layout chat-layout justify-content-center mt-5">
          <div class="row">
            <div class="col-lg-12">
              <?php
              if ($this->session->flashdata('alert')) {
              ?>
                <div class="alert alert-<?php echo $this->session->flashdata('alert'); ?>">
                  <?php echo $this->session->flashdata('alert_message'); ?>
                </div>
              <?php
              }
              ?>
              <div class="card" id="order_details_block">
                <div class="card-header d-flex justify-content-between">
                  <?php
                  $status = $order_details['item_status'];
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
                  <h5 class="card-title my-auto">ORDER DETAILS <span class="badge badge-primary text-white"><?php echo 'BOOKING ID: SE' . $order_details['order_id'] . '-' . $order_details['order_details_id']; ?></span>&nbsp;&nbsp;<span class="badge badge-<?php echo $badge; ?>"><?php echo 'STATUS: ' . $order_details['status_name']; ?></span></h5>
                  <?php if ($this->auth_rold_id != 2) { ?>
                    <button class="lead_preview btn btn-primary" data-href="/orders/ontimegov/preview?id=<?php echo $order_details['id'] ?>">
                      <?php if ($order_details["assigned_user"] == NULL) { ?>
                        Assign To <i class="fa fa-chevron-right ml-2"></i>
                      <?php } else { ?>

                        ReAssign To <i class="fa fa-chevron-right ml-2"></i>
                      <?php } ?>
                    </button>
                  <?php } ?>


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
                        <td>
                          <strong>Gender:</strong><br />
                          <span id="o_cus_gender" class="text-ontime"><?php echo ($order_details['gender'] != '') ? $order_details['gender'] != '' : ''; ?></span>
                        </td>
                      </tr>
                    </table>
                  </div>
                  <div class="order_block">
                    <h5><strong>ORDER INFORMATION</strong></h5>
                    <table class="table">
                      <tr>
                        <td>
                          <strong>Order Reference Number:</strong><br />
                          <span id="o_ord_sub_category" class="text-ontime"><?php echo 'SE' . $order_details['order_id'] . '-' . $order_details['order_details_id']; ?></span>
                        </td>
                        <td>
                          <strong>Transaction Name:</strong><br />
                          <span id="o_ord_category" class="text-ontime"><?php echo $order_details['trans_name']; ?></span>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <strong>Card Number:</strong><br />
                          <span id="o_ord_sub_category2" class="text-ontime"><?php echo $order_details['card_num']; ?></span>
                        </td>
                        <td>
                          <strong>Transaction Number</strong><br />
                          <span id="o_ord_mrn" class="text-ontime"><?php echo $order_details['transaction_number']; ?></span>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <strong>Receipt Number:</strong><br />
                          <span id="o_ord_gender" class="text-ontime"><?php echo $order_details['receipt_no']; ?></span>
                        </td>
                        <td>
                          <strong>Net Total</strong><br />
                          <span id="o_ord_amount" class="text-ontime"><?php echo $order_details['net_total']; ?></span>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <strong>SLA:</strong><br />
                          <span id="o_ord_service_hours" class="text-ontime"><?php echo $order_details['sla']; ?></span>
                        </td>
                        <td>

                        </td>
                      </tr>
                      <tr>
                        <td colspan="2">
                          <?php echo $order_details['description']; ?>
                        </td>
                      </tr>
                    </table>
                  </div>
                </div>
              </div>

              <!-- <div class="card">
                <div class="card-header">
                  <h5 class="card-title">UPDATE ORDER STATUS</h5>
                </div>
                <div class="card-body">

                  <label>Select status</label>
                  <select class="form-control" id="order_status" onchange="load_card(this.value);">
                    <option value="">-- Select Status --</option>
                    <?php
                    foreach ($order_statuses as $key => $value) {
                      if ($value['id'] != "104" && $value['id'] != "105") {
                    ?>
                        <option value="<?php echo $value['id']; ?>"><?php echo $value['status_name']; ?></option>
                    <?php
                      }
                    }
                    ?>
                  </select>
                  </form>

                  <div id="block_102" class="mt-4">
                    <form accept="<?php echo base_url(); ?>orders/ontimegov/view?code=<?php echo $_GET['code']; ?>" method="post">
                      <div class="form-group">
                        <label>Reason for additional payment</label>
                        <textarea class="form-control" name="reason"></textarea>
                      </div>
                      <div class="form-group">
                        <label>Additional Fee Amount in AED</label>
                        <input type="number" class="form-control" name="additional_fee" />
                      </div>
                      <div class="form-group">
                        <button class="btn btn-primary btn-block" type="submit" name="submit_add_fee">Notify customer</button>
                      </div>
                    </form>
                  </div>

                  <div id="block_103">
                  </div>

                  <div id="block_106">
                  </div>

                </div>
              </div> -->
            </div>
          </div>

          <div class="row justify-content-center">
            <!-- Follow-ups -->
            <div class="col-lg-12 col-12 mt-4">

              <?php if ($lead_current_status != 106) { ?>
                <div class="app-inner-layout__header text-white bg-night-sky br-tr br-tl">
                  <div class="app-page-title app-page-title-simple">
                    <div class="page-title-wrapper">
                      <div class="page-title-heading">
                        <div>Order Actions
                          <div class="page-title-subheading">Manage the Order</div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="app-inner-layout__wrapper row-fluid no-gutters">
                  <div class="app-inner-layout__sidebar bg-transparent card" style="">
                    <div class="p-3 stick-to-parent" style="">
                      <div class="dropdown-menu nav p-0 dropdown-menu-inline dropdown-menu-rounded dropdown-menu-hover-primary">
                        <h6 tabindex="-1" class="pt-0 dropdown-header">Menu</h6>
                        <!-- <a href="#tab-faq-3" data-toggle="tab" tabindex="0" class="mb-1 dropdown-item show active" aria-expanded="true">Custom</a> -->
                        <a href="#tab-faq-4" data-toggle="tab" tabindex="0" class="mb-1 dropdown-item show" aria-expanded="true">Order
                          Completed</a>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-12 app-inner-layout__content card">
                    <div class="pb-5 pl-5 pr-5 pt-3">
                      <div class="mobile-app-menu-btn mb-3">
                        <button type="button" class="hamburger hamburger--elastic">
                          <span class="hamburger-box">
                            <span class="hamburger-inner"></span>
                          </span>
                        </button>
                      </div>
                      <div class="tab-content">

                        <!-- <div class="tab-pane show active" id="tab-faq-3">
                          <form action="<?php echo base_url(); ?>leads/lead/action_custom/<?php echo $this->uri->segment(4); ?>" method="post">
                            <div class="row">
                              <div class="col-md-12">
                                <div class="form-group">
                                  <label>Remarks&nbsp;<span class="text-danger required">*</span></label>
                                  <textarea rows="5" class="form-control" name="custom_remarks" id="custom_remarks"></textarea>
                                </div>
                                <div class="form-group">
                                  <label>Next Contactable Date&nbsp;<span class="text-danger required">*</span></label>

                                  <input type="text" id="custom_date" name="contactable_date" class="form-control" placeholder="" value="<?php echo date('Y-m-d H:i:s', time() + 86400); ?>">
                                </div>
                                <div class="form-group">
                                  <input type="submit" name="action_custom" class="btn btn-primary btn-block" value="UPDATE TIMELINE">
                                </div>
                              </div>
                            </div>
                          </form>
                        </div> -->
                        <div class="tab-pane show active" id="tab-faq-4">
                          <form action="<?php echo base_url(); ?>orders/ontimegov/action_order" method="post" id="order_confirm_form">
                            <div class="row">
                              <div class="col-md-12">
                                <div class="form-group">
                                  <input type="hidden" name="order_id" value="<?php echo $order_details['id']; ?>">
                                  <label>ORDER REFERENCE NUMBER (INVOICE REFERENCE
                                    NUMBER)&nbsp;<span class="text-danger required">*</span></label>
                                  <input type="text" name="order_ref" id="order_id" required="" class="form-control">
                                </div>
                                <div class="form-group">
                                  <input type="submit" name="action_order" class="btn btn-primary btn-block" value="COMPLETE ORDER">
                                </div>
                              </div>
                            </div>
                          </form>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              <?php } ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Order Details</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="lead_preview">
        Body
      </div>
      <div class="modal-footer">
        <button type="button" class="bg-ontime btn p-2 pl-4 pr-4" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script>
  function onChangeEves() {
    $('[name="assign_group"] option').addClass("d-none");
    $('[name="assign_to"] option:not([value=""])').addClass("d-none");
  }

  $(".lead_preview").on('click', function(e) {
    // alert("Hi");
    e.preventDefault();
    var link = $(this).data("href");
    $.get(link, function(response) {
      console.log(response);
      $("#lead_preview").html(response);
	onChangeEves();
      $("#modelId").modal();
    });

  });

function branch_change(branch) {
    var biz = [107];
    var attest = [103];
    var val = parseInt(branch);


    $('[name="assign_group"]').val("").trigger("change");
    $('[name="assign_group"] option').addClass("d-none");
    $('[name="assign_group"] option[data-branch="' + val + '"]').removeClass("d-none");

    if ($('[name="assign_group"] option:not(.d-none)').length == 1) {
      var val = $('[name="assign_group"] option:not(.d-none)').val();
      $('[name="assign_group"]').val(val).trigger("change");
    }
  }

  function group_change(group) {
    $('[name="assign_to"]').val("").trigger("change");
    // alert($(this).val());
    //if ($(this).val() == "") {
    // $('[name="assign_to"] option').removeClass("d-none");
    // return;
    // }
    $('[name="assign_to"] option:not([value=""])').addClass("d-none");
    $('[name="assign_to"] option:not([value=""])[data-filter*="' + group + '"]')
      .removeClass("d-none");

    $('[name="assign_to"] option:not([value="3771749283"])[data-filter*="BusinessSetup"]')
      .addClass("d-none");
  }


  function assign_otg_csa(item_id, assigned_to, assigned_by) {
    if (assigned_to == "" || assigned_to == null) return false;

    console.log("item_id: " + item_id + " assigned_to: " + assigned_to + " assigned_by: " + assigned_by);
	item_id = <?php echo $_GET['code']; ?>;
    Swal.fire({
      title: 'Please confirm to Assign',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, Assign!'
    }).then((result) => {
      if (result.isConfirmed) {

        $.ajax({
          url: "<?php echo base_url(); ?>api/v1/assign/otgorder",
          type: 'POST',
          data: {
            assigned_to: assigned_to,
            lead_id: item_id,
            assigned_by: assigned_by
          },
          success: function(res) {
            console.log(res);
            $('#o' + item_id).hide();
            Swal.fire(
              'Assigned!',
              res,
              'success'
            ).then((value) => {
              location.reload();
            });
          },
          error: function(e) {
            Swal.fire(
              'Something went wrong!',
              e,
              'error'
            )
          }
        });

      }
    });
  }

  $("#order_confirm_form").on("submit", function(e) {
        e.preventDefault();
        let order_ref = $("#order_confirm_form #order_id").val();
        let is_alphapro = order_ref.match("^RV-");
        if (is_alphapro) {
            $("#order_confirm_form").off();
            $("#order_confirm_form").submit();
        }

        var uri = "https://verify.ontime-pos.com/posinvoice/VerifyInvoice/" + $(
                "#order_confirm_form #order_id")
            .val();
        console.log(uri);
        $.ajax({
            url: uri,
            type: "POST",
            beforeSend: function() {
                swal.fire({
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    allowEnterKey: false,
                    didOpen: () => {
                        swal.enableLoading();
                    }
                });
            },
            success: function(data) {
                console.log(data);
                if (data.Status == 0) {
                    swal.fire({
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        allowEnterKey: false,
                        icon: "warning",
                        title: "Invoice is not matched",
                        text: "Please put the exact Invoice number"
                    });
                    return;
                } else {
                    $("#order_confirm_form").off();
                    $("#order_confirm_form").submit();
                }
            }
        })
    });
</script>