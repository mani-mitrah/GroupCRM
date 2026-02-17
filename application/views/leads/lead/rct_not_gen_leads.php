<style>
    a[role="tab"].active.nav-link::after {
        top: 40px !important;
    }

    span.select2-selection.select2-selection--multiple {
        font-size: 15px;
    }

    span.select2.select2-container.select2-container--default {
        width: 100% !important;
    }
</style>

<div class="app-main">
    <div class="app-main__outer w-100">
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
                                    <span class="d-inline-block">Receipt Not Generated Leads</span>
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
                                                <a href="/">Dashboard</a>
                                            </li>
                                            <li class="active breadcrumb-item">
                                                <a href="javascript:void(0);">Receipt Not Generated Leads</a>
                                            </li>
                                        </ol>
                                    </nav>
                                </div>
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
                                            <div>
                                                <div class="row justify-content-end mt-3">
                                                    <div class="col-lg-12">
                                                        <form action="" method="post" id="pcr_date_form" class="d-inline-flex w-100">
                                                            <div class="col-lg-2">
                                                                <div class="form-group">
                                                                    <label for="">From Date:</label>
                                                                    <input type="date" name="from_date" class="form-control" id="from_date"     value="<?php if (isset($request)) { if (isset($request["from_date"])) { echo $request["from_date"]; } } ?>" aria-describedby="helpId" placeholder="" max="<?php echo date('Y-m-d'); ?>">
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-2">
                                                                <div class="form-group">
                                                                    <label for="">To Date:</label>
                                                                    <input type="date" name="to_date" class="form-control" id="to_date" value="<?php if (isset($request)) { if (isset($request["to_date"])) { 
                                                                        echo $request["to_date"]; } } ?>" aria-describedby="helpId" placeholder="" max="<?php echo date('Y-m-d'); ?>">
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-2">
                                                                <div class="form-group">
                                                                    <label for="">Action:</label>
                                                                    <button type="submit" class="btn btn-primary form-control" name="action" value="submit"
                                                                    style="background-image: linear-gradient(140deg, #3641b6 -30%, #00287c 90%) !important;
                                                                        background-color: #3641b6 !important;
                                                                        border-color: #3641b6 !important;
                                                                        color: white !important; box-shadow: none !important;
                                                                        transition: none !important;">Apply</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body pt-0">
                                                <table style="width: 100%;" id="dTtable" class="table dTtable table-hover table-striped table-bordered table-responsive">
                                                    <thead>
                                                        <tr>
                                                            <th>Main Lead</th>
                                                            <th>Customer</th>
                                                            <th>Created By</th>
                                                            <th>Created Group</th>
                                                            <th>Created Time</th>
                                                            <th>Assigned to</th>
                                                            <th>Assigned Group</th>
                                                            <th>Assigned Time</th>
                                                            <th>Status</th>
                                                            <th>Applicant Name</th>
                                                            <th>Remarks</th>
                                                            <th>Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($receipt_not as $key => $value) {
                                                            if ($this->auth_user_role > 5 && $value["lead_parent_id"] != 0) continue;
                                                        ?>
                                                            <tr id="o<?php echo $value['id']; ?>">
                                                                <td data-href="<?php echo base_url(); ?>leads/lead/view/<?php echo $value['id']; ?>" class="lead_view_popup"><?php echo $value['id']; ?>
                                                                </td>
                                                                <td data-href="<?php echo base_url(); ?>leads/lead/view/<?php echo $value['id']; ?>" class="lead_view">
                                                                    <?php
                                                                    if ($value['lead_zoho_id'] == NULL) {
                                                                        if (in_array($value['lead_from'], ['OntimeGOV', 'GoldenCube', 'Baraha Van', 'DLD'])) {
                                                                            echo '<strong>' . $value['customer_name'] . ' <span class="badge-pill zoho-pill badge badge-info">WEBSITE</span></strong><br />' . $value['customer_mobile'] . '<br />' . $value['customer_email'];
                                                                        } else {
                                                                            echo '<strong>' . $value['customer_name'] . '</strong><br />' . $value['customer_mobile'] . '<br />' . $value['customer_email'];
                                                                        }
                                                                    } else {
                                                                        // $zoho_value = $value['lead_from'] != NULL && $value['lead_from'] != '' ? $value['lead_from'] : 'ZOHO';
                                                                        echo '<strong>' . $value['customer_name'] . ' <span class="badge-pill zoho-pill badge badge-primary">zoho</span></strong><br />' . $value['customer_mobile'] . '<br />' . $value['customer_email'];
                                                                    } ?></a>
                                                                </td>
                                                                <td data-href="<?php echo base_url(); ?>leads/lead/view/<?php echo $value['id']; ?>" class="lead_view"> <?php echo $value['creator']; ?></td>
                                                                <td data-href="<?php echo base_url(); ?>leads/lead/view/<?php echo $value['id']; ?>" class="lead_view"> <?php echo $value['creator_group']; ?></td>
                                                                <td data-href="<?php echo base_url(); ?>leads/lead/view/<?php echo $value['id']; ?>" class="lead_view">
                                                                    <?php echo date('d M Y H:i A', strtotime($value['lead_added_on'])); ?>
                                                                </td>
                                                                <td data-href="<?php echo base_url(); ?>leads/lead/preview/<?php echo $value['id']; ?>" class="<?php if (in_array($this->auth_user_role, [6, 7, 84, 86, 87])) { echo "lead_preview"; } else { echo ""; } ?>">
                                                                <?php if ($value['assigned_to'] == $this->auth_user_id) {
                                                                        echo 'Self';
                                                                    } else {
                                                                        $assigned_user_data = get_user_display_data($value['assigned_to']);
                                                                        echo '<strong>' . $assigned_user_data['first_name'] . ' ' . $assigned_user_data['last_name'] . '</strong><br />' . $assigned_user_data['mobile'] . '<br />' . $assigned_user_data['email'];
                                                                    }
                                                                    ?>
                                                                </td>
                                                                <td data-href="<?php echo base_url(); ?>leads/lead/view/<?php echo $value['id']; ?>" class="lead_view"> <?php echo $value['assigned_group']; ?></td>
                                                                <td data-href="<?php echo base_url(); ?>leads/lead/view/<?php echo $value['id']; ?>" class="lead_view">
                                                                    <?php echo date('d M Y H:i A', strtotime($value['assigned_on'])); ?>
                                                                </td>
                                                                <td <?php if ($value["lead_created_by"] == 2906815795) {
                                                                    ?> class="lead_status_update"
                                                                    data-href="<?php echo base_url(); ?>leads/lead/statusUpdate/<?php echo $value['id']; ?>"
                                                                    <?php } ?>> <?php echo $value['current_status']; ?>
                                                                </td>
                                                                <td data-href="<?php echo base_url(); ?>leads/lead/view/<?php echo $value['id']; ?>"
                                                                    class="lead_view">
                                                                    <?php echo $value['applicant_name']; ?>
                                                                </td>
                                                                <!-- <td><?php if ($value["total_no_subleads"] > 0) {
                                                                        echo $value['no_of_open_subleads'] . "/" . $value["total_no_subleads"];
                                                                    } else {
                                                                        echo "-";
                                                                    } ?></td>
                                                                <td><?php if ($value["total_no_subleads"] > 0) {
                                                                    echo $value['no_of_closed_subleads'] . "/" . $value["total_no_subleads"];
                                                                } else {
                                                                    echo "-";
                                                                } ?></td> -->
                                                                <td data-href="<?php echo base_url(); ?>leads/lead/view/<?php echo $value['id']; ?>" class="lead_view d-none">
                                                                        <?php echo $value['country_options']; ?></td>
                                                                <td><?php echo $value['last_446_remarks']; ?> </td>
                                                                <td><a href="<?php echo base_url(); ?>leads/lead/view/<?php echo $value['id']; ?>" class="btn btn-sm btn-primary">View</a>
                                                                    <!-- <br>
                                                                    <?php if (!empty($value['last_446_remarks'])) {
                                                                        $str = $value['last_446_remarks'];
                                                                        preg_match('/href=([\'"]?)([^\'"\s>]+)\1/i', $str, $matches);
                                                                        $url = $matches[2] ?? null;
                                                                        if (!empty($url)) {
                                                                            echo '<a href="' . $url . '" target="_blank" class="btn mt-1 btn-sm btn-primary">Fetch Payment Status</a>';
                                                                        }
                                                                    }?> -->
                                                                </td>
                                                            </tr>
                                                        <?php } ?>
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
<script src="<?php echo base_url(); ?>assets_new/node_modules/datatables.net-buttons/js/dataTables.buttons.min.js">
</script>
<script src="<?php echo base_url(); ?>assets_new/node_modules/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js">
</script>
<script src="<?php echo base_url(); ?>assets_new/node_modules/datatables.net-buttons/js/buttons.html5.min.js"></script>
<script src="<?php echo base_url(); ?>assets_new/node_modules/datatables-export-document/dataTables.export.js"></script>
<script src="<?php echo base_url(); ?>assets_new/node_modules/datatables.net-responsive/js/dataTables.responsive.min.js">
</script>
<script src="<?php echo base_url(); ?>assets_new/node_modules/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js">
</script>
<link rel="stylesheet" href="<?php echo base_url(); ?>assets_new/node_modules/datatables.net-responsive-bs4/css/responsive.bootstrap4.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>assets_new/node_modules/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css">
<script src="<?php echo base_url(); ?>global/vendor/select2/js/select2.full.min.js"></script>
<link rel="stylesheet" href="<?php echo base_url(); ?>global/vendor/select2/css/select2.min.css">
<script>
    window.dTtable = $("#dTtable").dataTable({
        order: [
            [0, 'asc']
        ],
        dom: '<"row"<"col"B><"col"l><"col"f>>rt<"row"<"col"i><"col my-3"p>>',
        // responsive: true,
        buttons: [{
                extend: 'csv',
                text: "Export as CSV",
                exportOptions: {
                    format: {
                        header: function(data, column, row) {
                            // console.log('header column :' + data);
                            return data.replace(/<br><select>.*lect>/gm, "").trim();
                        }
                    }
                },
                attr: {
                    style: 'margin-right: 10px; border-radius: 3px;' // Add inline margin
                }
            },
            {
                text: 'Clear',
                action: function(e, dt, node, config) {
                    location.href = "/leads/lead/rct_leads";
                }
            }
        ],
    });
</script>