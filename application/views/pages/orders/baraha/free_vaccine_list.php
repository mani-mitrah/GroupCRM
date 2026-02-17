<style>
  a[role="tab"].active.nav-link::after {
      top: 40px !important;
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
                  <span class="d-inline-block">Free Vaccination</span>
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
                        <a href="javascript:void(0);">Free Vaccine</a>
                      </li>
                    </ol>
                  </nav>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="app-inner-bar">
          <div class="container fiori-container">
            <div class="inner-bar-center">
              <ul class="nav nav_tabs">
                <li class="nav-item">
                  <a role="tab" data-toggle="tab" class="nav-link active" href="#new">
                    <button class="btn">
                      Free Vaccination <span class="badge badge-primary"><?php echo count($order_details); ?></span>
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
                <div class="tab-pane tabs-animation fade show active" id="manage" role="tabpanel">
                  <div class="row justify-content-center">
                    <div class="col-lg-10">
                      <div class="main-card mb-3 card mt-4">
                        <div class="card-body">
                          <table style="width: 100%;" class="table dTtable table-hover table-striped table-bordered">

                            <thead>
                              <tr>
                                <th>#</th>
                                <th>Inv-Reference</th>
                                <th>Examinee</th>
                                <th>Booked by</th>
                                <th>Appointment</th>
                                <th>Booked on</th>
                                <!-- <th>Address</th> -->
                                <th>Report</th>
                                <th>Actions</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php
                              foreach ($order_details as $key => $value) {
                              ?>
                                <tr>
                                  <td><?php echo $value['free_order_id']; ?></td>
                                  <td><?php echo 'FVB' . $value['free_order_id'] . '-' . $value['examinee_id']; ?></td>
                                  <td><?php echo ($value['gender'] == 'male') ? '<span style="color:#62a0ff;"><i class="fa fa-male"></i> </span>' : '<span style="color:#d015af;"><i class="fa fa-female"></i> </span>'; ?>&nbsp;<?php echo '<strong>' . $value['name'] . '</strong><br />' . $value['email'] . '<br />' . $value['mobile']; ?></td>
                                  <td>
                                    <?php echo '<strong>' . $value['customer_name'] . '</strong><br />' . $value['customer_email'] . '<br />' . $value['customer_mobile']; ?>
                                  </td>
                                  <td>
                                    <center><strong><?php echo date('d M Y', strtotime($value['booked_date'])); ?></strong><br />
                                      <?php echo $value['slot_timings']; ?>

                                      <strong><?php echo ($value['day_session'] == 1) ? 'Forenoon' : 'Afternoon';  ?></strong>
                                    </center>
                                  </td>
                                  <td><?php echo date('d M Y H:i A', strtotime($value['created_date'])); ?></td>

                                  <td>
                                    <?php echo ($value['report'] == 0) ? '<span class="badge badge-danger">NO</span>' : '<span class="badge badge-success">YES</span>'; ?>
                                  </td>

                                  <td>

                                    <a href="<?php echo base_url() . 'orders/baraha/vaccine_view?code=' . $value['free_order_id']; ?>" class="btn btn-sm btn-primary">View</a>
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
<script src="<?php echo base_url(); ?>assets_new/node_modules/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?php echo base_url(); ?>assets_new/node_modules/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>
<link rel="stylesheet" href="<?php echo base_url(); ?>assets_new/node_modules/datatables.net-responsive-bs4/css/responsive.bootstrap4.css">
<script>
  $("document").ready(function() {
    $(".dTtable").dataTable({
      order: [
        [0, 'desc']
      ],
      dom: '<"row"<"col"l><"col"f>>rt<"row"<"col"i><"col my-3"p>>',
      responsive: true
    });
  });
</script>