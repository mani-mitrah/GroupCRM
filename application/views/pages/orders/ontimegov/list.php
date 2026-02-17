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
                  <span class="d-inline-block">OnTime Gov - Orders</span>
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
                        <a href="javascript:void(0);">OnTime Gov</a>
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
                    <div class="col-lg-10">
                      <div class="main-card mb-3 card mt-4">
                        <div class="card-body">
                          <table style="width: 100%;" class="table dTtable table-hover table-striped table-bordered">

                            <thead>
                              <tr>
                                <th>#</th>
                                <th>Customer</th>
                                <th>Order</th>
                                <th>AssignTo</th>
                                <th>Amount</th>
                                <th>Priority</th>
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
                                  <td><?php echo 'SE' . $value['order_id'] . '-' . $value['order_details_id']; ?></td>
                                  <td><?php echo '<strong>' . $value['first_name'] . '</strong><br />' . $value['mobile']; ?></td>
                                  <td>
                                    <?php
                                    echo $value['trans_name'];
                                    ?>
                                  </td>
                                  <td <?php if ($value["item_status"] != 106) { ?>class="lead_preview" <?php } ?> data-href="/orders/ontimegov/preview?id=<?php echo $value['id'] ?>">
                                    <?php
                                    if ($value["as_fname"] != NULL) {
                                      echo $value["as_fname"] . " " . $value["as_lname"] . " (" . $value["as_emp_id"] . ")";
                                    } else {
                                      echo "--";
                                    }
                                    ?>
                                  </td>
                                  <td><?php echo 'AED ' . $value['net_total']; ?></td>
                                  <td><span class="badge badge-primary text-white"><?php echo $value['sla']; ?></span></td>
                                  <td>
                                    <?php
                                    $status = $value['item_status'];
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
                                  <td><?php echo $value['order_item_date']; ?></td>
                                  <td>

                                    <a href="<?php echo base_url() . 'orders/ontimegov/view?code=' . $value['id']; ?>" class="btn btn-sm btn-primary">View</a>
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

<script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script src="<?php echo base_url(); ?>global/vendor/datatables/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(); ?>assets_new/node_modules/datatables.net-responsive/js/dataTables.responsive.min.js">
</script>
<script src="<?php echo base_url(); ?>assets_new/node_modules/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js">
</script>
<script src="<?php echo base_url(); ?>global/node_modules/select2/dist/js/select2.min.js"></script>

<link rel="stylesheet" href="<?php echo base_url(); ?>assets_new/node_modules/datatables.net-responsive-bs4/css/responsive.bootstrap4.css">
<script>
  function onChangeEves() {
    $('[name="assign_group"] option').addClass("d-none");
    $('[name="assign_to"] option:not([value=""])').addClass("d-none");
  }

  $("document").ready(function() {


    $(".lead_preview").on('click', function(e) {
      // alert("Hi");
      e.preventDefault();
      var link = $(this).data("href");
      $.get(link, function(response) {



        console.log(response);

        $("#lead_preview").html(response);
        $("#modelId").modal();


        // $("[data-child='group']").select2();
        // $("[data-child='group'],[data-parent='group']").select2("destroy");
        onChangeEves();

      });

    });

    $(".dTtable").dataTable({
      order: [
        [0, 'desc']
      ],
      dom: '<"row"<"col"l><"col"f>>rt<"row"<"col"i><"col my-3"p>>',
      responsive: true
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
</script>