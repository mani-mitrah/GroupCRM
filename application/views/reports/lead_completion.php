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
                                    <span class="d-inline-block">Leads</span>
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
                                            <li class="breadcrumb-item">
                                                <a href="#">Reports</a>
                                            </li>
                                            <li class="active breadcrumb-item">
                                                <a href="javascript:void(0);">Leads</a>
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
                                            <div class="card-title">
                                                <div class="row justify-content-end mt-3">
                                                    <form action="" method="post" id="pcr_date_form" class="d-inline-flex justify-content-end w-100">

                                                        <div class="col-lg-4">
                                                            <div class="form-group mr-2">
                                                                <label for="" class="w-100">Branches</label>
                                                                <select class="form-control" name="branches" id="branches" multiple>
                                                                    <?php
                                                                    foreach ($branches as $branch) {
                                                                        echo "<option value='" . $branch["branch_code"] . "'>" . $branch["branch_name"] . "</option>";
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-2">
                                                            <div class="form-group mr-2">
                                                                <label for="" class="w-100">Categories</label>
                                                                <select class="form-control" name="categories" id="categories" multiple>
                                                                    <?php
                                                                    foreach ($categories as $category) {
                                                                        echo "<option value='" . $category["category_id"] . "'>" . $category["category_name"] . "</option>";
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-2">
                                                            <div class="form-group">
                                                                <label for="">From Date:</label>
                                                                <input type="date" name="date" class="form-control" id="from_date" value="" aria-describedby="helpId" placeholder="">
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-2">
                                                            <div class="form-group">
                                                                <label for="">To Date:</label>
                                                                <input type="date" name="date" class="form-control" id="to_date" value="" aria-describedby="helpId" placeholder="">
                                                            </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body pt-0">
                                            <table style="width: 100%;" id="dTtable" class="table dTtable table-hover table-striped table-bordered">
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
<script src="<?php echo base_url(); ?>assets_new/node_modules/pdfmake/build/pdfmake.js"></script>
<script src="<?php echo base_url(); ?>assets_new/node_modules/pdfmake/build/vfs_fonts.js"></script>

<script src="<?php echo base_url(); ?>assets_new/node_modules/datatables.net-responsive/js/dataTables.responsive.min.js">
</script>
<script src="<?php echo base_url(); ?>assets_new/node_modules/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js">
</script>
<link rel="stylesheet" href="<?php echo base_url(); ?>assets_new/node_modules/datatables.net-responsive-bs4/css/responsive.bootstrap4.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>assets_new/node_modules/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css">
<script src="<?php echo base_url(); ?>global/vendor/select2/js/select2.full.min.js"></script>
<link rel="stylesheet" href="<?php echo base_url(); ?>global/vendor/select2/css/select2.min.css">

<script>
    window.dTtable = "";

    function dTinit(is_reload = null) {
        if (is_reload != null) dTtable.fnDestroy();
        window.dTtable = $("#dTtable").dataTable({
            ajax: "/reports/lead_complete_getDtdata?from=" + $("#from_date").val() + "&to=" + $("#to_date").val() +
                "&branches=" + $("#branches").val() + "&categories=" + $("#categories").val(),
            processing: true,
            columns: [{
                    title: "#Lead",
                    data: "lead_id"
                },
                {
                    title: "Customer",
                    data: "customer"
                },
              
                {
                    title: "Source",
                    data: "source"
                },
                {
                    title: "CreatedBy",
                    data: "created_by"
                },
                {
                    title: "CompletedBy",
                    data: "completed_by"
                },
                {
                    title: "Created On",
                    data: "lead_created_at"
                },
                {
                    title: "Lead Type",
                    data: "lead_type"
                },
                {
                    title: "Completed On",
                    data: "action_on"
                },
                {
                    title: "Invoice",
                    data: "invoice"
                },
                {
                    title: "Duration",
                    data: "timeline",
                    render: function(data, id, row) {
                        console.log(data, id, row);
                        dd = data;
                        // dd = "( "+row.mindiff+" ) "+data;
                        return dd;
                    }
                },
                {
                    title: "Status",
                    data: "status"
                },
                {
                    title: "Action",
                    data: "lead_id",
                    render: function(data) {
                        return "<a href='/leads/lead/view/" + data + "' class='btn btn-primary'>View</a>";
                    }
                }
            ],
            order: [
                [0, 'desc']
            ],
            dom: '<"row"<"col"B><"col"l><"col"f>>rt<"row"<"col"i><"col my-3"p>>',
            responsive: true,
            buttons: [{
                    extend: 'csv',
                    text: "Export as CSV",
                },
                {
                    text: "Download as PDF",
                    extend: 'pdf',
                    orientation: "landscape",
                    pageSize: "A4"
                },
                {
                    text: 'Reload',
                    action: function(e, dt, node, config) {
                        dt.ajax.reload();
                    }
                }
            ],
            initComplete: function() {
                this.api().columns().every(function() {
                    var column = this;
                    console.log(column.index());
                    if (column.index() == 4) { //skip if column 0
                        $(column.header()).append("<br>")
                        var select = $(
                                '<select class="form-control"><option value=""></option></select>')
                            .appendTo($(column.header()))
                            .on('change', function() {
                                var val = $.fn.dataTable.util.escapeRegex(
                                    $(this).val()
                                );

                                column
                                    .search(val ? '^' + val + '$' : '', true,
                                        false)
                                    .draw();
                            });
                        column.data().unique().sort().each(function(d, j) {
                            select.append('<option value="' + d + '">' + d +
                                '</option>')
                        });
                    } //end of if

                });
            }
        });
    }

    $("#branches").select2({
        placeholder: "Select Branches..."
    });

    $("#categories").select2({
        placeholder: "Select Categories..."
    });

    dTinit();

    $("document").ready(function() {

        $("#from_date,#to_date,#branches,#categories").change(function(e) {
            e.preventDefault();
            dTinit("des");
        });


    });
</script>