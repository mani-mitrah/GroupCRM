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

    table.table-bordered.dataTable th {
        border-left-width: 0;
        white-space: nowrap;
    }

    #loading {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(255, 255, 255, 0.8);
        z-index: 9999;
    }

    #loading-bar {
        width: 0%;
        height: 4px;
        background-color: #007BFF;
    }

    #loading-percentage {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-size: 24px;
        font-weight: bold;
        color: #007BFF;
    }
</style>
<?php
function DateTimeDifference($FromDate, $ToDate)
{
    $FromDate = new DateTime($FromDate);
    $ToDate   = new DateTime($ToDate);
    $Interval = $FromDate->diff($ToDate);

    $Difference = $Interval->h . " Hours<br>";
    $Difference .= ($Interval->d % 7) . " Days<br>";
    $Difference .= floor($Interval->d / 7) . " Weeks<br>";
    $Difference .= $Interval->m . " Months";

    return $Difference;
}
?>
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
                                    <span class="d-inline-block">Reports</span>
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
                                                <a href="javascript:void(0);">Reports</a>
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
                                                        <form action="" method="get" id="pcr_date_form"
                                                            class="d-inline-flex w-100">
                                                            <div class="col-lg-4">
                                                                <div class="form-group mr-2">
                                                                    <label for="" class="w-100">Report Type</label>
                                                                    <select class="form-control" name="report_type"
                                                                        id="report_type" required>
                                                                        <?php if (!isset($request)) { ?>
                                                                        <option value="" default disabled selected>--
                                                                            Select Report Type --</option>
                                                                        <?php } ?>
                                                                        <option value="created" <?php if (isset($request)) {
                                                                                                        if ($request["report_type"] == "created") {
                                                                                                            echo "selected";
                                                                                                        }
                                                                                                    } ?>>Created
                                                                        </option>
                                                                        <option value="received" <?php if (isset($request)) {
                                                                                                        if ($request["report_type"] == "received") {
                                                                                                            echo "selected";
                                                                                                        }
                                                                                                    } ?>>Received
                                                                        </option>

                                                                       
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-2">
                                                                <div class="form-group mr-2">
                                                                    <label for="" class="w-100">Branches</label>
                                                                    <select class="form-control" name="branch_id"
                                                                        id="branches">
                                                                        <option value="">-- Select Branch --</option>
                                                                        <?php
                                                                        foreach ($branches as $branch) {
                                                                            $selected = "";
                                                                            if (isset($request)) {
                                                                                if (isset($request["branch_id"])) {
                                                                                    if ($request["branch_id"] == $branch["branch_code"]) {
                                                                                        $selected = "selected";
                                                                                    }
                                                                                }
                                                                            }
                                                                            echo "<option value='" . $branch["branch_code"] . "' " . $selected . ">" . $branch["branch_name"] ." - ".  $branch['group_name']. "</option>";
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-2">
                                                                <div class="form-group">
                                                                    <label for="">From Date:</label>
                                                                    <input type="date" name="from_date"
                                                                        class="form-control" id="from_date"
                                                                        value="<?php if (isset($request)) {
                                                                        if (isset($request["from_date"])) { echo $request["from_date"]; } } ?>"
                                                                        aria-describedby="helpId" placeholder="" max="<?php echo date("Y-m-d"); ?>">
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-2">
                                                                <div class="form-group">
                                                                    <label for="">To Date:</label>
                                                                    <input type="date" name="to_date"
                                                                        class="form-control" id="to_date"
                                                                        value="<?php if (isset($request)) {
                                                                        if (isset($request["to_date"])) { echo $request["to_date"]; }} ?>"
                                                                        aria-describedby="helpId" placeholder="" max="<?php echo date("Y-m-d"); ?>">
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-2">
                                                                <div class="form-group">
                                                                    <label for="">Action:</label>
                                                                    <button type="submit"
                                                                        class="btn btn-primary form-control"
                                                                        name="action" value="submit" style="background-image: linear-gradient(140deg, #3641b6 -30%, #00287c 90%) !important;
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
                                                <table style="width: 100%;" id="dTtable"
                                                    class="table dTtable table-hover table-striped table-bordered table-responsive">

                                                </table>
                                                <div id="loading">
                                                    <div id="loading-bar"></div>
                                                    <div id="loading-percentage">Processing...</div>
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
</div>

<script src="<?php echo base_url(); ?>global/vendor/datatables/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(); ?>assets_new/node_modules/datatables.net-buttons/js/dataTables.buttons.min.js">
</script>
<script src="<?php echo base_url(); ?>assets_new/node_modules/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js">
</script>
<script src="<?php echo base_url(); ?>assets_new/node_modules/datatables.net-buttons/js/buttons.html5.min.js"></script>
<script src="<?php echo base_url(); ?>assets_new/node_modules/datatables-export-document/dataTables.export.js"></script>
<script
    src="<?php echo base_url(); ?>assets_new/node_modules/datatables.net-responsive/js/dataTables.responsive.min.js">
</script>
<script
    src="<?php echo base_url(); ?>assets_new/node_modules/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js">
</script>
<link rel="stylesheet"
    href="<?php echo base_url(); ?>assets_new/node_modules/datatables.net-responsive-bs4/css/responsive.bootstrap4.css">
<link rel="stylesheet"
    href="<?php echo base_url(); ?>assets_new/node_modules/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css">
<script src="<?php echo base_url(); ?>global/vendor/select2/js/select2.full.min.js"></script>
<link rel="stylesheet" href="<?php echo base_url(); ?>global/vendor/select2/css/select2.min.css">
<script>
    var loadingBar = document.getElementById('loading-bar');
    var loadingPercentage = document.getElementById('loading-percentage');

    function initializeDropdownFilters() {
        $(".ddStatus").remove();
        dTtable.columns().every(function () {
            var is_exist = [3,9,14,21,22]; 
            var column = this;
            if (is_exist.indexOf(column.index()) !== -1) {
                $(column.header()).append("<br class='ddStatus'>")
                var select = $(
                        '<select class="form-control ddStatus dStatus"><option value=""></option></select>')
                    .appendTo($(column.header()))
                    .on('change', function () {
                        var val = $.fn.dataTable.util.escapeRegex($(this).val());
                        column.search(val ? '^' + val + '$' : '', true, false).draw();
                    });
                column.data().unique().sort().each(function (d, j) {
                    // console.log("Data", d);
                    select.append('<option value="' + d + '">' + d + '</option>')
                });
            }
        });
    }

    function dTdraw(dTurl = "/reports/statreport_getDtdata") {
        window.dTtable = $("#dTtable").DataTable({
            ajax: {
                url: dTurl,
                dataSrc: function (data) {
                    console.log("DataSource");
                    $(".ddStatus").remove();
                    $('#loading').fadeOut();
                    return data.data;
                }
            },
            // drawCallback:initializeDropdownFilters,
            processing: true,
            columns: [
                {
                    "data": "lead_id",
                    "title": "Lead History",
                    "render": function(data){
                        return "<a target='_blank' href='/leads/lead/view/"+data+"#timeline' class='btn btn-primary'>View History</a>";
                    }
                },
                {
                    "data": "lead_id",
                    "title": "#"
                },
                {
                    "data": "lead_parent_id",
                    "title": "Main Lead"
                },
                {
                    "data": "status_group",
                    "title": "Status Group"
                },
                {
                    "data": "customer_name",
                    "title": "Customer Name"
                },
                {
                    "data": "customer_email",
                    "title": "Email"
                },
                {
                    "data": "customer_mobile",
                    "title": "Mobile"
                },
                // {
                //     "data": "source",
                //     "title": "Lead Source"
                // },
                {
                    "data": "created_by_employee_id",
                    "title": "Creator EMP ID"
                },
                {
                    "data": "created_by",
                    "title": "Created By"
                },
                {
                    "data": "creator_by_branch_name",
                    "title": "Creator Branch"
                },
                {
                    "data": "creator_by_group_name",
                    "title": "Creator Group"
                },
                {
                    "data": "created_by_company_name",
                    "title": "Creator Company"
                },
                {
                    "data": "assigned_to_employee_id",
                    "title": "Assigned Emp#"
                },
                {
                    "data": "assigned_to",
                    "title": "Assigned"
                },
                {
                    "data": "assigned_to_branch",
                    "title": "Assigned Branch"
                },
                {
                    "data": "assigned_to_group",
                    "title": "Assigned Group"
                },
                {
                    "data": "assigned_to_company_name",
                    "title": "Assigned Company Name"
                },
                {
                    "data": "total_no_subleads",
                    "title": "Total SubLeads"
                },
                {
                    "data": "no_of_open_subleads",
                    "title": "Open Subleads"
                },
                {
                    "data": "no_of_closed_subleads",
                    "title": "Closed Subleads"
                },
                {
                    "data": "receipt_id",   //"order_receipt",
                    "title": "Order Receipt"
                },
                {
                    "data": "status",
                    "title": "Status"
                },
                {
                    "data": "completed_by_employee_id",
                    "title": "Completed Emp#"
                },
                {
                    "data": "completed_by",
                    "title": "Completed"
                },
                {
                    "data": "completed_by_branch",
                    "title": "Completed Branch"
                },
                {
                    "data": "completed_by_group",
                    "title": "Completed Group"
                },
                {
                    "data": "completed_by_company_name",
                    "title": "Completed Company"
                },
                {
                    "data": "lead_created_at",
                    "title": "Lead Created At"
                },
                {
                    "data": "lead_last_action_at",
                    "title": "Lead Last ACtion At"
                },
                {
                    "data": "completed_at",
                    "title": "Completed At"
                },
                {
                    "data": "timeline_in_days",
                    "title": "Timeline (in days)"
                },
                {
                    "data": "invoice",
                    "title": "Invoice"
                },
                {
                    "data": "lead_sale_type",
                    "title": "Lead Sale Type"
                },
            ],
            order: [
                [1, 'desc']
            ],
            dom: '<"row"<"col"B><"col"l><"col"f>>rt<"row"<"col"i><"col my-3"p>>',
            // responsive: true,
            buttons: [{
                    extend: 'csv',
                    text: "Export as CSV",
                    exportOptions: {
                        format: {
                            header: function (data, column, row) {
                                // console.log('header column :' + data);
                                
                                return data.replace(/<[^>]*>/g, '').trim();
                            }
                        }
                    },
                },
                {
                    text: 'Clear',
                    action: function (e, dt, node, config) {
                        location.href = "/reports/statreport";
                    }
                }
            ],
            initComplete: initializeDropdownFilters,
            destroy: true
        });
    }
    dTdraw();
    $("#pcr_date_form").submit(function (e) {
        e.preventDefault();
        $('#loading').fadeIn();
        var data_url = "/reports/statreport_getDtdata";
        data_url += "?" + $(this).serialize();
        dTdraw(data_url);
    })
</script>