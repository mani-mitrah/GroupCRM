<style>
.paginate_button,
.ellipsis {
    border: 1px solid;
    padding: 6px 10px;
}

.paginate_button.current {
    background: #01297c;
    color: white;
    border-color: #01297c;
}

a.paginate_button:hover {
    text-decoration: none;
    cursor: pointer;
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
                                    <span class="d-inline-block">Orders</span>
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
                                                <a href="javascript:void(0);">SmartEjari</a>
                                            </li>
                                        </ol>
                                    </nav>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="page-title-actions">
                                    <div class="d-inline-block pr-3">
                                        <select id="custom-inp-top" type="select" class="custom-select">
                                            <option>Select period...</option>
                                            <option>Last Week</option>
                                            <option>Last Month</option>
                                            <option>Last Year</option>
                                        </select>
                                    </div>
                                    <button type="button" data-toggle="tooltip" data-placement="left" class="btn btn-dark" title="Show a Toastr Notification!">
                                        <i class="fa fa-battery-three-quarters"></i>
                                    </button>
                                </div>
                            </div> -->
                    </div>
                </div>
                <div class="app-inner-layout app-inner-layout-page">
                    <div class="app-inner-bar">
                        <div class="container fiori-container">
                            <div class="inner-bar-center">
                                <ul class="nav">
                                    <li class="nav-item">
                                        <a role="tab" data-toggle="tab" class="nav-link dt_filter active" data-item=""
                                            href="#tab-content-0">
                                            <span>Overall</span>
                                        </a>
                                    </li>
                                    <!-- <li class="nav-item">
                    <a role="tab" data-toggle="tab" class="nav-link dt_filter" data-item="Enquiry" href="#tab-content-1">
                      <span>Enquiries</span>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a role="tab" data-toggle="tab" class="nav-link dt_filter" data-item="Complaints" href="#tab-content-1">
                      <span>Complaints</span>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a role="tab" data-toggle="tab" class="nav-link dt_filter" data-item="Meetings" href="#tab-content-2">
                      <span>Meetings</span>
                    </a>
                  </li> -->
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="app-inner-layout__wrapper">
                        <div class="app-inner-layout__content">
                            <div class="tab-content container-fluid">
                                <div class="row justify-content-center">
                                    <div class="col-lg-8">

                                        <div class="main-card mb-3 card mt-4">
                                            <div class="card-body table-responsive">
                                                <table style="width: 100%;" id="enq_dTable"
                                                    class="table table-hover table-striped table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Order Ref</th>
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
                                                            <td><?php echo $value['se_order_id']; ?></td>
                                                            <td><?php echo "SEOT" . $value['order_id']; ?></td>
                                                            <td>
                                                                <?php echo '<strong>' . $value['first_name'] . '</strong><br />' . $value['mobile']; ?>
                                                            </td>
                                                            <td>
                                                                <?php
                                  echo $value['transaction_name'];
                                  ?>
                                                            </td>
                                                            <td>
                                                                <?php
                                  $status = $value['order_status'];
                                  if ($status == 410 || $status == 106) {
                                    $badge = 'success';
                                  } else {
                                    $badge = 'info';
                                  }
                                  ?>
                                                                <span
                                                                    class="badge font-weight-light light badge-<?php echo $badge; ?>"><?php echo $value['status_name']; ?></span>
                                                            </td>
                                                            <td><?php
                                    echo date("d-m-Y H:i:s", strtotime($value['updated_at']) + 14400); ?></td>
                                                            <td>

                                                                <a href="<?php echo base_url() . 'orders/smartejari/view?code=' . $value['se_order_id']; ?>"
                                                                    class="btn btn-sm btn-primary">View</a>
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
    <!-- <script src="/global/js/plugins-init/datatables.init.js"></script> -->
    <script src="<?php echo base_url(); ?>global/vendor/datatables/js/jquery.dataTables.min.js"></script>
    <script>
    $("document").ready(function() {
        $("#enq_dTable").dataTable({
            order: [
                [0, 'desc']
            ],
        });


    });
    </script>