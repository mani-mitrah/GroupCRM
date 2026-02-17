<style>
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
                  <span class="d-inline-block">Al Adheed - Orders</span>
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
                      <li class="active breadcrumb-item">
                        <a href="javascript:void(0);">Al Adheed</a>
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
                      Overall <span class="badge badge-primary"><?php echo count($order_details); ?></span>
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
                    <div class="col-lg-8">
                      <div class="main-card mb-3 card mt-4">
                        <div class="card-body">
                          <table style="width: 100%;" class="table dTtable table-hover table-striped table-bordered">

                            <thead>
                              <tr>
                                <th>#</th>
                                <th>Customer</th>
                                <th>Transaction</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Actions</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php

                              foreach ($order_details as $key => $value) {
                              ?>
                                <tr>
                                  <td><?php echo $value['alad_order_id']; ?></td>
                                  <td>
                                    <?php echo '<strong>' . $value['first_name'] . '</strong><br />' . $value['mobile']; ?></td>
                                  <td>
                                    <?php
                                    echo $value['transaction_name'];
                                    ?>
                                  </td>
                                  <td>
                                    <?php
                                    $status = $value['order_status'];
                                    if ($status == 101) {
                                      $badge = 'success';
                                    } else if ($status == 102) {
                                      $badge = 'warning';
                                    } else if ($status == 103) {
                                      $badge = 'primary';
                                    } else if ($status == 104) {
                                      $badge = 'info';
                                    } else if ($status == 105) {
                                      $badge = 'danger';
                                    }
                                    ?>
                                    <span class="badge light badge-<?php echo $badge; ?>"><?php echo $value['status_name']; ?></span>
                                  </td>
                                  <td><?php echo $value['updated_at']; ?></td>
                                  <td>

                                    <a href="<?php echo base_url() . 'orders/aladheed/view?code=' . $value['alad_order_id']; ?>" class="btn btn-sm btn-primary">View</a>
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