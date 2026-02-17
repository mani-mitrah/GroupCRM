<style>
  a[role="tab"].active.nav-link::after {
    top: 38px;
  }
  .breadcrumb .breadcrumb-item a {
   color: inherit; /* Default link color */
   text-decoration: none; /* Remove underline by default */
   transition: color 0.3s, text-decoration 0.3s; /* Smooth transition */
  }
  .breadcrumb .breadcrumb-item a:hover {
   color: #007bff !important; /* Replace with the exact color code of "Orders" */
   text-decoration: underline !important; /* Add underline on hover */
  }

</style>
<?php
// helper("encrypt");
$this->load->helper('crypt');
?>
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
                  <span class="d-inline-block">PCR Test</span>
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
                  <a href="<?php echo base_url(); ?>orders/baraha/pcr_date_wise">
                    <button type="button" class="btn btn-gradient-primary btn-shadow btn-wide">
                      <span class="btn-icon-wrapper pr-1 opacity-7">
                        <i class="fa fa-paste"></i>
                      </span>
                      Day-wize List
                    </button>
                  </a>
                </div>
              </div>
          </div>
        </div>
        <div class="app-inner-bar">
          <div class="container fiori-container">
            <div class="inner-bar-center">
              <ul class="nav nav_tabs">
                <li class="nav-item">
                  <a role="tab" class="nav-link active" data-toggle="tab" href="#today">
                    <button class="btn">
                      Today Orders <span class="badge badge-primary"><?php echo count($today_order_details); ?></span>
                    </button>
                  </a>
                </li>
                <li class="nav-item">
                  <a role="tab" class="nav-link" data-toggle="tab" href="#upcoming">
                    Upcoming Orders <span class="badge badge-primary"><?php echo count($upcoming_order_details); ?></span>
                  </a>
                </li>

                <li class="nav-item">
                  <a role="tab" class="nav-link" data-toggle="tab" href="#completed">
                    <button class="btn">
                      Completed Orders <span class="badge badge-primary"><?php echo count($completed_order_details); ?></span>
                    </button>
                  </a>
                </li>
                <li class="nav-item">
                  <a role="tab" class="nav-link" data-toggle="tab" href="#unpaid">
                    <button class="btn">
                      Unpaid Orders <span class="badge badge-primary"><?php echo count($unpaid_order_details); ?></span>
                    </button>
                  </a>
                </li>
                <li class="nav-item">
                  <a role="tab" class="nav-link" data-toggle="tab" href="#incomplete">
                    <button class="btn">
                      Incomplete Orders <span class="badge badge-primary"><?php echo count($incomplete_order_details); ?></span>
                    </button>
                  </a>
                </li>
              </ul>
            </div>
          </div>
        </div>
        <div class="app-inner-layout app-inner-layout-page">
          <div class="app-inner-layout__wrapper">
            <div class="app-inner-layout__content">
              <div class="tab-content container-fluid">

                <?php if ($this->session->flashdata('alert')) { ?>
                  <div class="alert alert-<?php echo $this->session->flashdata('alert'); ?> alert-dismissible fade show" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                    <strong>
                      <?php echo $this->session->flashdata('alert_message'); ?>
                    </strong>
                  </div>
                <?php } ?>

                <div id="today" class="tab-pane active" style="background: #fff;padding: 30px 30px;border: 1px solid #dee2e6;">
                  <div class="row justify-content-center">
                    <div class="col-lg-10">
                      <div class="main-card mb-3 card mt-4">
                        <div class="card-body table-responsive">
                          <table style="width: 100%;" class="table dTBtntable table-hover table-striped table-bordered">
                            <thead>
                              <tr>
                                <th>#</th>
                                <th>Customer</th>
                                <th>Order</th>
                                <th>Amount (AED)</th>
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
                                  <td><?php echo $value['pcr_order_id']; ?></td>
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
                                  <td><?php echo $value['total_amount']; ?></td>
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
                <div id="upcoming" class="tab-pane" style="background: #fff;padding: 30px 30px;border: 1px solid #dee2e6;">

                  <div class="row justify-content-center">
                    <div class="col-lg-10">
                      <div class="main-card mb-3 card mt-4">
                        <div class="card-body">
                          <table style="width: 100%;" class="table dTtable table-hover table-striped table-bordered">
                            <thead>
                              <tr>
                                <th>#</th>
                                <th>Customer</th>
                                <th>Order</th>
                                <th>Amount (AED)</th>
                                <th>Referred</th>

                                <th>Status</th>
                                <th class="text-center">Test Status</th>
                                <th>Preferred Date</th>
                                <th>Actions</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php
                              foreach ($upcoming_order_details as $key => $value) {
                              ?>
                                <tr>
                                  <td><?php echo $value['pcr_order_id']; ?></td>
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
                                  <td><?php echo $value['total_amount']; ?></td>
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
                                  <td><?php echo date('d M Y', strtotime($value['preferred_date'])); ?></td>
                                  <td>
                                    <?php
                                    if ($status == 0) {
                                    ?>
                                      <a href="<?php echo base_url() . 'orders/baraha/pcr_view?code=' . $value['pcr_order_id']; ?>" class="btn btn-sm btn-warning"><i class="fa fa-edit"></i> VIEW</a>
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
                <div id="completed" class="tab-pane" style="background: #fff;padding: 30px 30px;border: 1px solid #dee2e6;">

                  <div class="row justify-content-center">
                    <div class="col-lg-10">
                      <div class="main-card mb-3 card mt-4">
                        <div class="card-body">
                          <table style="width: 100%;" class="table dTtable table-hover table-striped table-bordered">
                            <thead>
                              <tr>
                                <th>#</th>
                                <th>Customer</th>
                                <th>Order</th>
                                <th>Amount (AED)</th>
                                <th>Referred</th>

                                <th>Status</th>
                                <th class="text-center">Test Status</th>
                                <th>Preferred Date</th>
                                <th>Actions</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php
                              foreach ($completed_order_details as $key => $value) {
                              ?>
                                <tr>
                                  <td><?php echo $value['pcr_order_id']; ?></td>
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
                                  <td><?php echo $value['total_amount']; ?></td>
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
                                  <td><?php echo date('d M Y', strtotime($value['preferred_date'])); ?></td>
                                  <td>
                                    <a href="<?php echo base_url() . 'orders/baraha/pcr_view?code=' . $value['pcr_order_id']; ?>" class="btn btn-sm btn-warning"><i class="fa fa-edit"></i> VIEW</a><br>
                                    <a href="<?php echo base_url() . 'orders/baraha/getinvoice?code=' . $value['pcr_order_id']; ?>" class="btn btn-sm btn-info mt-2" target="_blank"><i class="fa fa-scroll"></i> Invoice</a>
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
                <div id="unpaid" class="tab-pane" style="background: #fff;padding: 30px 30px;border: 1px solid #dee2e6;">
                  <div class="row justify-content-center">
                    <div class="col-lg-10">
                      <div class="main-card mb-3 card mt-4">
                        <div class="card-body">
                          <table style="width: 100%;" class="table dTtable table-hover table-striped table-bordered">
                            <thead>
                              <tr>
                                <th>#</th>
                                <th>Customer</th>
                                <th>Order</th>
                                <th>Amount (AED)</th>
                                <th>Referred</th>
                                <th>Status</th>
                                <th class="text-center">Test Status</th>
                                <th>Preferred Date</th>
                                <th>Actions</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php
                              foreach ($unpaid_order_details as $key => $value) {
                              ?>
                                <tr>
                                  <td><?php echo $value['pcr_order_id']; ?></td>
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
                                  <td><?php echo $value['total_amount']; ?></td>
                                  <td><?php echo $value['referred']; ?></td>

                                  <td>
                                    <?php
                                    $status = $value['pcr_status'];
                                    $is_complete = $value['is_completed'];
                                    if ($is_complete == 0) {
                                      $badge = 'success';
                                      $badge_name = 'Payment Failed';
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
                                  <td><?php echo date('d M Y', strtotime($value['preferred_date'])); ?></td>
                                  <td>
                                    <?php
                                    if ($status == 0) {
                                    ?>
                                      <a href="<?php echo base_url() . 'orders/baraha/pcr_view?code=' . $value['pcr_order_id']; ?>" class="btn btn-sm btn-warning"><i class="fa fa-edit"></i> VIEW</a>
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
                <div id="incomplete" class="tab-pane" style="background: #fff;padding: 30px 30px;border: 1px solid #dee2e6;">

                  <div class="row justify-content-center">
                    <div class="col-lg-10">
                      <div class="main-card mb-3 card mt-4">
                        <div class="card-body table-responsive">
                          <table style="width: 100%;" class="table dTBtntable table-hover table-striped table-bordered">
                            <thead>
                              <tr>
                                <th>#</th>
                                <th>Customer</th>
                                <th>Order</th>
                                <th>Amount (AED)</th>
                                <th>Referred</th>
                                <th>Status</th>
                                <th class="text-center">Test Status</th>
                                <th>Preferred Date</th>
                                <th>Actions</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php
                              foreach ($incomplete_order_details as $key => $value) {
                              ?>
                                <tr>
                                  <td><?php echo $value['pcr_order_id']; ?></td>
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
                                  <td><?php echo $value['total_amount']; ?></td>
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
                                  <td><?php echo date('d M Y', strtotime($value['preferred_date'])); ?></td>
                                  <td>
                                    <?php
                                    if ($status == 0) {
                                    ?>
                                      <a href="<?php echo base_url() . 'orders/baraha/pcr_view?code=' . $value['pcr_order_id']; ?>" class="btn btn-sm btn-warning"><i class="fa fa-edit"></i> VIEW</a>
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
  <script src="<?php echo base_url(); ?>global/vendor/datatables/js/jquery.dataTables.min.js"></script>
  <script src="<?php echo base_url(); ?>assets_new/node_modules/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
  <script src="<?php echo base_url(); ?>assets_new/node_modules/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>

  <link rel="stylesheet" href="<?php echo base_url(); ?>assets_new/node_modules/datatables.net-responsive-bs4/css/responsive.bootstrap4.css">
  <script>
    $("document").ready(function() {
      $(".dTtable").dataTable({
        order: [
          [0, 'desc']
        ],
        dom: '<"row"<"col"l><"col"f>>rt<"row"<"col"i><"col my-3"p>>',
        
        responsive: true,
        columnDefs: [{
          targets: 7, // The date column index
          render: function(data, type, row) {
            if (type === 'sort') {
              console.log('Raw Date:', data);
              // Parse the date using moment.js
              var timestamp = moment(data.trim(), "DD MMM YYYY", true); // Strict parsing
              if (timestamp.isValid()) {
                return timestamp.format("YYYYMMDD"); // Format the date into sortable string (e.g., "19980604")
              } else {
                console.error('Invalid Date:', data);
                return "00000000"; // Return an invalid "lowest" sortable value
              }
            }
            return data; // Return raw data for display
          }
        }],
        initComplete: function(settings, json) {
          // Initialization logic if needed
          console.log("DataTable initialized successfully!");
        }
      });

      function dTSelection() {
        $(".btn-completion").off();
        $(".btn-completion").on("click", function(e) {
          e.preventDefault();
          $.ajax({
            url: $(this).attr("href"),
            type: "POST",
            data: "code=" + $(this).data("id"),
            beforeSend: function() {
              $(this).attr("disabled");
              Swal.fire({
                allowOutsideClick: false,
                allowEscapeKey: false,
                allowEnterKey: false,
                didOpen: function() {
                  swal.enableLoading();
                }
              });
            },
            success: function(data) {
              $(this).removeAttr("disabled");
              console.log(data);
              data = JSON.parse(data);
              if (data.status == "true") {
                Swal.fire({
                  icon: "success",
                  text: "PCR Order Marked as Completed",
                }).then((val) => {
                  location.reload();
                });
              } else {
                Swal.fire({
                  icon: "warning",
                  text: data.message,
                });
              }
            }
          });
        });
      }

      $(".dTBtntable").on('draw.dt', dTSelection).dataTable({
        order: [
          [0, 'desc']
        ],
        dom: '<"row"<"col"l><"col"f>>rt<"row"<"col"i><"col my-3"p>>',
        columnDefs: [
             {
                 targets: 7,
                 render: function(data, type, row) {
                     if (type === 'sort') {
                         var timestamp = moment(data, "DD MMM YYYY").valueOf();
                         return timestamp;
                     }
                     return data;
                 }
             }
         ],
         initComplete: function(settings, json) {
             var api = this.api();
             var dateColumn = api.column(7);
             dateColumn.data().unique().sort().each(function(d, j) {
                 var timestamp = moment(d, "DD MMM YYYY").valueOf();
                 dateColumn.data()[j] = timestamp;
             });
         }
     });
    });


  </script>

  <script type="text/javascript">
    function get_order_item_details(order_item_id) {
      $('#order_details_block').show();
      $('#no_card').hide();
      $('#order_loading').show();
      $('#mrn_block').hide();

      $.ajax({
        url: "<?php echo base_url(); ?>api/v1/order/item?item_id=" + order_item_id,
        type: 'GET',
        dataType: 'json', // added data type
        success: function(res) {

          //let order_json = JSON.parse(res);
          console.log(res);
          let item_id = res[0].item_id;
          let order_id = res[0].order_id;
          let med_number = res[0].med_number;
          let ref2 = res[0].ref2;
          let category = res[0].category;
          let sub_category = res[0].sub_category;
          let sub_category_two = res[0].sub_category_two;
          let gender = res[0].gender;
          let pregnancy = res[0].pregnancy;
          let menstrual_period = res[0].menstrual_period;
          let abortion = res[0].abortion;
          let contraceptive_pills = res[0].contraceptive_pills;
          let x_ray = res[0].x_ray;
          let attachment_no = res[0].attachment_no;
          let service_time = res[0].service_time;
          let item_amount = res[0].amount;
          let created_date = res[0].created_date;
          let source = res[0].source;
          let remarks = res[0].remarks;
          let created_time = res[0].created_time;
          let item_status = res[0].item_status;
          let o_id = res[0].o_id;
          let user_id = res[0].user_id;
          let company_id = res[0].company_id;
          let order_amount = res[0].order_amount;
          let status = res[0].status;
          let priority = res[0].priority;

          //Payment page items
          let response_code = res[0].response_code;
          let response_description = res[0].response_description;
          let response_class_description = res[0].response_class_description;
          let approval_code = res[0].approval_code;
          let card_number = res[0].card_number;
          let card_brand = res[0].card_brand;
          let unique_id = res[0].unique_id;
          let card_expiry = res[0].card_expiry;
          //let pid = res[0].pid;
          //let response_class = res[0].response_class;
          //let language = res[0].language;
          //let account = res[0].account;
          //let balance = res[0].balance;
          //let fees = res[0].fees;
          //let payer = res[0].payer;
          //let card_token = res[0].card_token;
          //
          //let card_type = res[0].card_type;


          let role_id = res[0].role_id;
          let first_name = res[0].first_name;
          let last_name = res[0].last_name;
          let country = res[0].country;
          let country_code = res[0].country_code;
          let mobile = res[0].mobile;
          let otp = res[0].otp;
          let username = res[0].username;
          let email = res[0].email;
          let auth_level = res[0].auth_level;
          let banned = res[0].banned;
          let passwd = res[0].passwd;
          let passwd_recovery_code = res[0].passwd_recovery_code;
          let passwd_recovery_date = res[0].passwd_recovery_date;
          let passwd_modified_at = res[0].passwd_modified_at;
          let last_login = res[0].last_login;
          let created_at = res[0].created_at;
          let modified_at = res[0].modified_at;
          let is_mobile_verified = res[0].is_mobile_verified;
          let is_email_verified = res[0].is_email_verified;
          let is_active = res[0].is_active;
          let email_otp = res[0].email_otp;
          let referal_code = res[0].referal_code;
          let otp_verified = res[0].otp_verified;
          let device_id = res[0].device_id;
          let confirm_password = res[0].confirm_password;
          let profile_pic = res[0].profile_pic;
          let service_name = res[0].service_hour;

          //category details
          let category_name = res[0].category_name;
          let sub_category_name = res[0].sub_category_name;
          let sub_category_two_name = res[0].sub_categorytwo;
          //let mr_number = res[0].med_number;

          let gender_name = '';
          //set gender name
          if (gender == 1) {
            gender_name = 'Male';
            $('#pregnancy_block').hide();
          } else if (gender == 2) {
            gender_name = 'Female';
            $('#pregnancy_block').show();
          }

          let loader = '<img src="<?php echo base_url(); ?>global/images/data_load.gif" />';
          //update customer block
          $('#o_cus_name').html(first_name);
          $('#o_cus_mobile').html(country_code + ' ' + mobile);
          $('#o_cus_email').html(email);
          $('#o_cus_gender').html(gender_name);

          //update order information
          $('#o_ord_category').html(category_name);
          $('#o_ord_sub_category').html(sub_category_name);
          $('#o_ord_sub_category2').html(sub_category_two_name);
          $('#o_ord_mrn').html(med_number);
          $('#o_ord_gender').html(gender_name);
          $('#o_ord_amount').html('AED ' + item_amount);
          $('#o_ord_service_hours').html('<strong>' + service_name + '</strong>');

          //update pregnancy information
          if (gender == 2) {
            $('#o_ord_pregnant').html((pregnancy == 1) ? 'YES' : 'NO');
            $('#o_ord_lmp').html(menstrual_period);
            $('#o_ord_abortion').html((abortion == 1) ? 'YES' : 'NO');
            $('#o_ord_cpills').html((contraceptive_pills == 1) ? 'YES' : 'NO');
            $('#o_ord_xray').html((x_ray == 1) ? 'YES, CUSTOMER AGREED FOR XRAY' : 'NO');
          }

          //update card details
          $('#o_ord_pay_status').html(response_class_description + '-' + response_description);
          $('#o_ord_approval_code').html(approval_code);
          $('#o_ord_card_number').html(card_number);
          $('#o_ord_card_expiry').html(card_expiry);
          $('#o_ord_card_brand').html(card_brand);
          $('#o_ord_unique_id').html(unique_id);


          $('#order_loading').hide();
          $('#item_id').val(item_id);
          if (med_number == '' || med_number == 0) {
            $('#mrn_block').show();
          }
        }
      });
      $.ajax({
        url: "<?php echo base_url(); ?>api/v1/order/attachments?item_id=" + order_item_id,
        type: 'GET',
        dataType: 'json', // added data type
        success: function(res) {
          $('#attachment_data').html("");
          console.log(res);
          console.log(res.length);
          if (res.length == 0) {
            $('#attachments_block').hide();
          }
          for (var i = 0; i < res.length; i++) {
            let attachment_data = $('<tr><td><a target="_blank" href="' + res[i].attachment +
              '">View Attachment</a></td></tr>');
            attachment_data.appendTo('#attachment_data');
          }
        }
      });
    }
    $(document).ready(function() {
      $('#datatable_today').DataTable({
        // aaSorting: false
      });
    });
  </script>

  <script type="text/javascript">
    $(document).ready(function() {
      var hash = location.hash.replace(/^#/, ''); // ^ means starting, meaning only match the first hash
      if (hash) {
        $('.nav_tabs a[href="#' + hash + '"]').tab('show');
      }

      window.onhashchange = function(e) {
        // console.log(e);
        // alert(e.target.location.hash);
        $('.nav_tabs a[href="' + e.target.location.hash + '"]').tab('show');

      }

      $('.nav_tabs a').on('show.bs.tab', function(e) {
        // Change hash for page-reload
        // alert("Nav");
        window.location.hash = e.target.hash;
      });
    })
  </script>