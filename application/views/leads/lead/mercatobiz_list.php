<style>
    a[role="tab"].active.nav-link::after {
        top: 38px;
    }

    span.select2.select2-container.select2-container--default {
        border: 1px solid #ced4da;
        border-radius: 3px;
        padding: 10px 5px 4px 5px;
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
                                    <span class="d-inline-block">Mercato Biz Leads</span>
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
                                                <a href="<?php echo base_url();?>">Dashboard</a>
                                            </li>
                                            <li class="breadcrumb-item">
                                                <a href="#">Leads</a>
                                            </li>
                                            <li class="active breadcrumb-item">
                                                <a href="javascript:void(0);">Mercato Biz Leads</a>
                                            </li>
                                        </ol>
                                    </nav>
                                </div>
                            </div>
                        </div>
                        <div class="page-title-actions">
                            <div class="d-inline-block dropdown">
                                <a href="<?php echo base_url();?>leads/lead/mercatobiznew">
                                    <button type="button" class="btn btn-outline-primary btn-shadow btn-wide">
                                        <span class="btn-icon-wrapper pr-1 opacity-7">
                                            <i class="fa fa-plus"></i>
                                        </span>
                                        Create MercatoBiz Lead
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
                            <div class="tab-pane tabs-animation fade show active" id="new" role="tabpanel">
                                <div class="row justify-content-center m-0">
                                    <div class="col-lg-10">
                                        <div class="main-card mb-3 card mt-4">
                                            <div class="card-body table-responsive">
                                                <table border="0" cellspacing="5" cellpadding="5">
                                                    <tbody>
                                                        <tr>
                                                            <td>From Date:</td>
                                                            <td><input type="date" id="biz-minnoc" class="form-control" name="min"></td>
                                                        </tr>
                                                        <tr>
                                                            <td>To Date:</td>
                                                            <td><input type="date" id="biz-maxnoc" class="form-control" name="max"></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                <table style="width: 100%;" id="dTtable" class="table dTtable table-hover table-striped table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>Lead ID</th>
                                                            <th>Customer</th>
                                                            <th>Category / Services</th>
                                                            <th>Branch</th>
                                                            <th>Desc</th>
                                                            <th>Status</th>
                                                            <th>Created By</th>
                                                            <th>Created Group</th>
                                                            <th>Created At</th>
                                                            <th>updated At</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        foreach ($mercatobiz_leads->data as $value) {
                                                            // echo "<pre>";
                                                            // print_r($value->customer_name);
                                                            // echo "</pre>";
                                                            // exit();
                                                        ?>
                                                            <tr>
                                                                <td><?php echo $value->id; ?></td>
                                                                <td><strong><?php echo $value->customer_name . "<br>" . $value->customer_email . "<br>" . $value->customer_mobile; ?></strong></td>
                                                                <td><?php echo $value->category_name . " / " . $value->service_name; ?></td>
                                                                <td><?php echo $value->branch_name; ?></td>
                                                                <td><?php echo strip_tags($value->remarks); ?></td>

                                                                <td><b><em><?php echo $value->current_status; ?></em></b></td>
                                                                <!-- <td><?php 
                                                                $created_user_data = get_user_display_data($value->lead_created_by);
                                                                echo '<strong>' . $created_user_data['first_name'] . ' ' . $created_user_data['last_name'] . '</strong><br />' . $created_user_data['mobile'] . '<br />' . $created_user_data['email']; ?></td> -->
                                                                <td><?php 
                                                                $created_crm_user_data = get_crm_user_display_data($value->id, 'mercatobiz');
                                                                echo '<strong>' . $created_crm_user_data['first_name'] . ' ' . $created_crm_user_data['last_name'] . '</strong><br />' . $created_crm_user_data['mobile'] . '<br />' . $created_crm_user_data['email']. '<br /> <strong>Employee ID : ' . $created_crm_user_data['employee_id']. '</strong><br />'; ?></td>
                                                                <?php $created_user_group = get_user_created_group($value->id, 'mercatobiz'); ?>
                                                                <td><?php echo $created_user_group; ?></td>
                                                                <td><?php echo $value->created_at; ?></td>
                                                                <td><?php echo $value->updated_at; ?></td>
                                                                <!-- <td><?php //echo $value->zoho_response; 
                                                                            ?></td> -->
                                                                <td><a href="<?php echo base_url(); ?>leads/lead/get_mercatobiz_details/<?php echo $value->id; ?>"
                                                                class="btn btn-sm btn-primary" target="_blank">View</a></td>
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
    $("document").ready(function() {
        function init() { directInvoice();
            // alert("Inititated");
            $(".lead_preview,.lead_view").off();
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
                    $("[data-child='group']").change(function(e) {
                        e.preventDefault();
                        // alert($(this).val());
                        $("[data-parent='group']").select2({
                            placeholder: 'Select the Group',
                            ajax: {
                                url: '/admin/group/group_members',
                                dataType: 'json',
                                data: function(params) {
                                    var query = {
                                        search: params.term,
                                        group_id: $(
                                                "[data-child='group']")
                                            .val(),
                                    }

                                    // Query parameters will be ?search=[term]&type=public
                                    return query;
                                },
                                // delay: 250,
                                processResults: function(data) {
                                    return {
                                        results: data
                                    };
                                },
                                cache: true
                            }
                        });
                    });
                });
            });

            $(".lead_view").on('click', function(e) {
                e.preventDefault();
                var link = $(this).data("href");
                location.href = link;
            });
        }

        var minDate, maxDate;

        // Custom filtering function which will search data in column four between two values
        $.fn.dataTable.ext.search.push(
            function (settings, data, dataIndex) {
                if (settings.nTable.id !== 'dTtable') {
                    return true;
                }

                if($('#biz-minnoc').val().length && $('#biz-maxnoc').val().length){
                    var min = new Date($('#biz-minnoc').val()) ;
                    var max = new Date($('#biz-maxnoc').val());
                } else {
                    var max = new Date();
                    var min = new Date();
                    min = new Date(min.setDate(min.getDate() - 30));
                    let maxDate = max.toISOString().split('T')[0];
                    let minDate = min.toISOString().split('T')[0];
                    $('#biz-minnoc').val(minDate);
                    $('#biz-maxnoc').val(maxDate);
                }

                max.setHours(23, 59, 59, 999);
                var lead_created = new Date(data[8]);

                if ((isNaN(min.getTime()) && isNaN(max.getTime())) ||
                    (isNaN(min.getTime()) && lead_created.getTime() <= max.getTime()) ||
                    (min.getTime() <= lead_created.getTime() && isNaN(max.getTime())) ||
                    (min.getTime() <= lead_created.getTime() && lead_created.getTime() <= max.getTime())) {
                    return true;
                }
                return false;
            }
        );

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
                // {
                //     text: 'Clear',
                //     action: function(e, dt, node, config) {
                //         location.href = "/lead/bizlist";
                //     }
                // }
            ],

        });

        $('#biz-minnoc, #biz-maxnoc').on('change', function () {
            dTtable.fnDraw();
        });

        var hash = document.location.hash;
        if (hash) {
            console.log(hash);
            $(".nav-tabs a[href=\\" + hash + "]").tab('show');
        }

        // Change hash for page-reload
        $('.nav-tabs a').on('shown.bs.tab', function(e) {
            window.location.hash = e.target.hash;
        });
    });

    $(document).on("click", ".open-meetingDialog", function() {
        var lead_id = $(this).data('leadid');
        $(".modal-body #lead_id").val(lead_id);
    });
</script>

<script type="text/javascript">
    function assign_csa(item_id, assigned_to, assigned_by) {

        console.log("item_id: " + item_id + " assigned_to: " + assigned_to + " assigned_by: " + assigned_by);
        Swal.fire({
            title: 'Please confirm',
            text: "If lead is assigned to CSA, it cannot be reverted.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Assign!'
        }).then((result) => {
            if (result.isConfirmed) {

                $.ajax({
                    url: "<?php echo base_url(); ?>api/v1/assign/lead",
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


<script src="/global/js/plugins-init/select2-init.js"></script>
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
            window.location.hash = e.target.hash;
        });
        $('#main-wrapper').attr('class', 'show menu-toggle');
    })
</script>