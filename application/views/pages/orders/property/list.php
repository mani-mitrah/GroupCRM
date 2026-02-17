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
                  <span class="d-inline-block">Property Registration Order</span>
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
                <a href="/orders/property">
                  <button type="button" class="btn btn-gradient-primary btn-shadow btn-wide">
                    <span class="btn-icon-wrapper pr-1 opacity-7">
                      <i class="fa fa-paste"></i>
                    </span>
                    Get Token
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

                    <div class="main-card mb-3 card mt-4">

                      <div class="card-body">
                        <table style="width: 100%;" class="table dTtable table-hover table-striped table-bordered">
                          <thead>
                            <tr>

                              <th># </th>
                              <th>Customer </th>
                              <th>service_name </th>
                              <th>remarks </th>
                              <th>token </th>
                              <th>assigned_to </th>
                              <th>order_status </th>
                              <th>service_amount </th>
                              <th>invoice </th>
                              <th>created_at </th>
                              <th>updated_at</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                            foreach ($order_details as $key => $value) {
                            ?>
                              <tr>
                                <td><?php echo $value["customer_id"]; ?></td>
                                <td><?php echo $value["customer_name"]; ?>
                                  <br><?php echo $value["customer_email"]; ?><br>
                                  <?php echo $value["customer_mobile"]; ?>
                                </td>
                                <td><?php echo $value["service_name"]; ?></td>
                                <td><?php echo $value["remarks"]; ?></td>
                                <td><?php echo $value["token"]; ?></td>
                                <td><?php echo $value["assigned_to"]; ?></td>
                                <td><?php echo $value["order_status"]; ?></td>
                                <td><?php echo $value["service_amount"]; ?></td>
                                <td>
                                  <?php
                                  if ($value['invoice_ref'] != NULL && $value['invoice_ref'] != "") {
                                  ?>
                                    <a href="<?php echo base_url() . 'orders/property/getinvoice?code=' . $value['invoice_ref']; ?>" class="btn btn-sm btn-info mt-2" target="_blank"><i class="fa fa-scroll"></i> Invoice</a>
                                  <?php } else { ?>
                                    --
                                  <?php } ?>
                                </td>
                                <td><?php echo $value["created_at"]; ?></td>
                                <td><?php echo $value["updated_at"]; ?></td>
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
          columns: [0, 1, 2, 3, 4, 5, 6, 7]
        }
      }, ],
    });
  });
</script>