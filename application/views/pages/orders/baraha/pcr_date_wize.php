<style>
    .breadcrumb .breadcrumb-item a {
      color: inherit; /* Default link color */
      text-decoration: none; /* Remove underline by default */
      transition: color 0.3s, text-decoration 0.3s; /* Smooth transition */
    }


    .breadcrumb .breadcrumb-item a:hover {
      color: #007bff !important; /* Replace with the exact color code of "Orders" */
      text-decoration: underline !important; /* Add underline on hover */
    }

    a[role="tab"].active.nav-link::after {
      top: 40px !important;
    }
</style>
<div class="app-main">
  <div class="app-main__outer">
    <div class="app-main__inner">
      <div class="app-page-title">
        <div class="container fiori-container">
          <div class="page-title-wrapper">
            <div class="page-title-heading">
              <div>
                <div class="page-title-head center-elem">
                  <span class="d-inline-block pr-2">
                    <i class=""></i>
                  </span>
                  <span class="d-inline-block">PCR Test Examinee</span>
                </div>
                <div class="page-title-subheading opacity-10">
                  <nav class="" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                      <li class="breadcrumb-item">
                        <a href="javascript:void(0);">
                          <i aria-hidden="true" class="fa fa-home"></i>
                        </a>
                      </li>
                      <li class="breadcrumb-item">
                        <a href="#">Dashboard</a>
                      </li>
                      <li class="breadcrumb-item">
                        <a href="#">Orders</a>
                      </li>
                      <li class="breadcrumb-item">
                        <a href="#">Baraha</a>
                      </li>
                      <li class="active breadcrumb-item">
                        <a href="javascript:void(0);">PCR Test</a>
                      </li>
                    </ol>
                  </nav>
                </div>
              </div>
            </div>
            <div class="page-title-actions">
                <div class="d-inline-block dropdown">
                  <a href="/orders/baraha/pcr_index">
                    <button type="button" class="btn btn-gradient-primary btn-shadow btn-wide">
                      <span class="btn-icon-wrapper pr-1 opacity-7">
                        <i class="fa fa-paste"></i>
                      </span>
                      PCR Orders
                    </button>
                  </a>
                </div>
              </div>
          </div>
          </div>
        </div>
        <div class="app-inner-layout app-inner-layout-page">
          <div class="app-inner-layout__wrapper">
            <div class="app-inner-layout__content">
              <div class="tab-content container-fluid">
                <div class="tab-pane tabs-animation fade show active" id="manage" role="tabpanel">
                  <div class="row justify-content-center">
                    <div class="col-lg-10">
                      <div class="main-card mb-3 card mt-4">
                        <div class="card-title">
                          <div class="row justify-content-end">
                            <div class="col-lg-2">
                              <form action="" method="post" id="pcr_date_form">
                                <div class="">
                                  <label for="">Date:</label>
                                  <input type="date" name="date" onchange="$('#pcr_date_form').submit();" class="form-control" id="pcr_date" value="<?php if ($preferred_date != NULL) { echo $preferred_date; } else { echo date('Y-m-d'); } ?>" aria-describedby="helpId" placeholder="">
                                </div>
                              </form>
                            </div>
                          </div>
                        </div>
                        <div class="card-body">
                          <table style="width: 100%;" class="table dTtable table-hover table-striped table-bordered">
                            <thead>
                              <tr>
                                <th>S.no</th>
                                <th>#</th>
                                <th>Customer</th>
                                <th>Order</th>
                                <th>Amount</th>
                                <th>Referred</th>
                                <th>Status</th>
                                <th class="text-center">Test Status</th>
                                <th>Preferred Date</th>
                                <th>View</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php
                              foreach ($today_order_details as $key => $value) {
                              ?>
                                <tr>
                                  <td><?php echo $key+1; ?></td>
                                  <td><?php echo "PCR" . $value['pcr_order_id']; ?></td>
                                  <td>
                                    <?php echo '<strong>' . $value['customer_name'] . '</strong><br />' . $value['mobile']; ?>
                                  </td>
                                  <td>
                                    <?php
                                    $total_examinee = pcr_order_item_count($value['pcr_order_id']);
                                    echo $value['category_name'];
                                    echo $value['sub_category_name'] != '' ? ' -> ' . $value['sub_category_name'] : '';
                                    echo '<br /><strong>No of examinees:</strong> ' . $total_examinee;
                                    ?>
                                  </td>
                                  <td><?php echo 'AED ' . $value['total_amount']; ?></td>
                                  <td><?php echo $value['referred']; ?></td>
                                  <td>
                                    <?php
                                    $status = $value['pcr_status'];
                                    $is_complete = $value['is_completed'];
                                    if ($is_complete == 0) {
                                      $badge = 'info';
                                      $badge_name = 'Order Booked & Paid';
                                    } elseif ($is_complete == 1) {
                                      $badge = 'success';
                                      $badge_name = 'Order Completed';
                                    }
                                    ?>
                                    <span class="badge light badge-<?php echo $badge; ?>"><?php echo $badge_name; ?></span>
                                  </td>
                                  <td class="text-center">
                                    <?php
                                    $completed_counts = pcr_order_completed_count($value['pcr_order_id']);
                                    $test_status = $completed_counts . ' / ' . $total_examinee;
                                    $per_test_status = $completed_counts / $total_examinee;
                                    if ($per_test_status == 0) {
                                      $per_test_badge = 'danger';
                                    } elseif ($per_test_status == 1) {
                                      $per_test_badge = 'success';
                                    } else {
                                      $per_test_badge = 'warning';
                                    }
                                    echo "<span class='badge badge-" . $per_test_badge . "'>" . $test_status . ' Completed</span>';
                                    ?>

                                  </td>
                                  <td><?php echo date('d M Y', strtotime($value['preferred_date'])); ?><br />
                                    <?php echo $value['slot_timings']; ?></td>
                                  <td>
                                    <a href="<?php echo base_url() . 'orders/baraha/pcr_view?code=' . $value['pcr_order_id']; ?>#payment_block" class="btn btn-sm btn-warning" target="_blank"><i class="fa fa-edit"></i> VIEW</a>
                                    <?php
                                    if ($value["is_completed"] == 1) {
                                    ?>
                                      <a href="<?php echo base_url() . 'orders/baraha/getinvoice?code=' . $value['pcr_order_id']; ?>" class="btn btn-sm btn-info mt-2" target="_blank"><i class="fa fa-scroll"></i> Invoice</a>
                                    <?php
                                    } else {
                                    ?>
                                      <a href="<?php echo base_url() . 'orders/baraha/completion'; ?>" data-id="<?php echo $value['pcr_order_id']; ?>" class="btn btn-sm btn-info mt-2 btn-completion"><i class="fa fa-check"></i>
                                        Complete</a>
                                    <?php
                                    }
                                    ?>
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
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="<?php echo base_url(); ?>global/vendor/datatables/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(); ?>assets_new/node_modules/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
<script src="<?php echo base_url(); ?>assets_new/node_modules/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js"></script>
<script src="<?php echo base_url(); ?>assets_new/node_modules/datatables.net-buttons/js/buttons.html5.min.js"></script>
<script src="<?php echo base_url(); ?>assets_new/node_modules/datatables-export-document/dataTables.export.js"></script>
<script src="<?php echo base_url(); ?>assets_new/node_modules/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?php echo base_url(); ?>assets_new/node_modules/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>
<link rel="stylesheet" href="<?php echo base_url(); ?>assets_new/node_modules/datatables.net-responsive-bs4/css/responsive.bootstrap4.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>assets_new/node_modules/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css">

<script>
  $("document").ready(function() {
    $(".dTtable").dataTable({
      order: [
        [0, 'asc']
      ],
      dom: '<"row"<"col"B><"col"l><"col"f>>rt<"row"<"col"i><"col my-3"p>>',
      responsive: true,
      buttons: [{
          extend: 'csv',
          text: "Export as CSV",
          exportOptions: {
            columns: [0,1,2,3,4,5,6,7]
          }
        },
      ],
    });
  });
</script>