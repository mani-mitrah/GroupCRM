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
                                    <span class="d-inline-block">Business Setup Leads</span>
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
                                                <a href="javascript:void(0);">Business Setup Leads</a>
                                            </li>
                                        </ol>
                                    </nav>
                                </div>
                            </div>
                        </div>
                        <div class="page-title-actions">
                            <div class="d-inline-block dropdown">
                                <a href="<?php echo base_url();?>leads/lead/biznew">
                                <!-- <a href="https://forms.zohopublic.com/ontimebusinesssetup1/form/LeadGenerationGovernmentServices/formperma/oSsJQQeK-RvytjgubMs-LRRgfPlnb_bmTrrojx8iXpY" target="_blank"> -->
                                    <button type="button" class="btn btn-outline-primary btn-shadow btn-wide">
                                        <span class="btn-icon-wrapper pr-1 opacity-7">
                                            <i class="fa fa-plus"></i>
                                        </span>
                                        Create Biz Lead
                                    </button>
                                </a>
                                <!-- <a href="<?php echo base_url();?>leads/lead/new">
                                    <button type="button" class="btn btn-gradient-primary btn-shadow btn-wide">
                                        <span class="btn-icon-wrapper pr-1 opacity-7">
                                            <i class="fa fa-plus"></i>
                                        </span>
                                        Create Lead
                                    </button>
                                </a> -->
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
                                                        foreach ($biz_leads->data as $value) {
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
                                                                $created_crm_user_data = get_crm_user_display_data($value->id,'biz');
                                                                echo '<strong>' . $created_crm_user_data['first_name'] . ' ' . $created_crm_user_data['last_name'] . '</strong><br />' . $created_crm_user_data['mobile'] . '<br />' . $created_crm_user_data['email']. '<br /> <strong>Employee ID : ' . $created_crm_user_data['employee_id']. '</strong><br />'; ?></td>
                                                                <?php $created_user_group = get_user_created_group($value->id,'biz'); ?>
                                                                <td><?php echo $created_user_group; ?></td>
                                                                <td><?php echo $value->created_at; ?></td>
                                                                <td><?php echo $value->updated_at; ?></td>
                                                                <!-- <td><?php //echo $value->zoho_response; 
                                                                            ?></td> -->
                                                                <td><a href="<?php echo base_url(); ?>leads/lead/get_biz_details/<?php echo $value->id; ?>"
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















<?php /*

<style type="text/css">
    .nav-tabs .nav-link.active,
    .nav-tabs .nav-item.show .nav-link {
        color: #ffffff;
        background-color: #00287c;
        border-color: #00287c #00287c #F9F9F9;
    }

    .lead_preview:hover,
    .lead_view:hover {
        background: #80808026 !important;
        cursor: pointer;
    }

    .add_sub {
        border: 2px solid gray;
        padding: 10px;
        border-radius: 7px;
    }

    .select2-container {
        width: 100% !important;
        z-index: 99999999;
    }
</style>

<div class="row page-titles mx-0" style="margin-bottom: 0px;">
    <div class="col-sm-6 p-md-0">
        <div class="welcome-text">
            <h4>Leads Management</h4>
            <span>Ontime leads management</span>
        </div>
    </div>
    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
        <a class="btn btn-primary waves-effect waves-light float-right" href="<?php echo base_url(); ?>leads/lead/new">
    <i class="mdi mdi-plus mr-2"></i> CREATE LEAD
    </a>
</div>
</div>
<div class="row justify-content-center">
    <?php
    if ($this->auth_user_role == 6) {
    ?>
    <div class="col-xl-3 col-sm-6 m-t35" onclick="location.hash='#new'">
        <div class="card card-coin shadow">
            <div class="card-body text-center">

                <h2 class="text-black mb-2 font-w600"><?php echo count($unassigned_leads); ?></h2>
                <p class="mb-0 fs-14">
                    New Leads
                </p>
            </div>
        </div>
    </div>
    <?php
    }
    ?>
    <div class="col-xl-3 col-sm-6 m-t35" onclick="location.hash='#manage'">
        <div class="card card-coin shadow">
            <div class="card-body text-center">

                <h2 class="text-black mb-2 font-w600"><?php echo count($accepted_leads); ?></h2>
                <p class="mb-0 fs-13">
                    Current Leads
                </p>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 m-t35" onclick="location.hash='#converted'">
        <div class="card card-coin shadow">
            <div class="card-body text-center">

                <h2 class="text-black mb-2 font-w600"><?php echo count($converted_leads); ?></h2>
                <p class="mb-0 fs-13">
                    Converted
                </p>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 m-t35" onclick="location.hash='#disqualified'">
        <div class="card card-coin shadow">
            <div class="card-body text-center">

                <h2 class="text-black mb-2 font-w600"><?php echo count($disqualified_leads); ?></h2>
                <p class="mb-0 fs-13">
                    Disqualified
                </p>
            </div>
        </div>
    </div>
</div>
<ul class="nav nav-tabs">
    <?php
    if ($this->auth_user_role == 6) {
    ?>
    <li class="nav-item">
        <a class="nav-link active" data-toggle="tab" href="#new">New Leads
        </a>
    </li>
    <!-- <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#assign">Assign Leads</a>
        </li> -->
    <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#assigned">Assigned Leads</a>
    </li>
    <?php
    }
    ?>
    <li class="nav-item">
        <a class="nav-link <?php if ($this->auth_user_role != 6) { ?> active<?php } ?>" data-toggle="tab"
            href="#manage">Your Leads</a>
    </li>

    <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#converted">Converted</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#disqualified">Disqualified</a>
    </li>
</ul>

<!-- Tab panes -->
<div class="tab-content">
    <?php
    if ($this->auth_user_role == 6) {
    ?>
    <div id="new" class="tab-pane table-responsive active"
        style="background: #fff;padding: 30px 30px;border: 1px solid #dee2e6;">
        <div class="row">
            <div class="col-12">
                <?php if ($this->session->flashdata('alert')) { ?>
                <div class="alert alert-<?php echo $this->session->flashdata('alert'); ?>">
                    <?php echo $this->session->flashdata('alert_message'); ?>
                </div>
                <?php } ?>
                <?php
                    if ($this->session->flashdata('alert_complete')) {
                    ?>
                <div class="alert alert-<?php echo $this->session->flashdata('alert_complete'); ?>">
                    <?php echo $this->session->flashdata('alert_complete_message'); ?>
                </div>
                <?php
                    }
                    ?>
                <table id="new_datatable" class="table table-bordered dt-responsive nowrap"
                    style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Customer</th>
                            <th>Category</th>
                            <th>Service</th>
                            <th>From Branch</th>
                            <th>Created Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            foreach ($unassigned_leads as $key => $value) {


                            ?>
                        <tr>
                            <td class="lead_preview" data-href="/leads/lead/preview/<?php echo $value['id']; ?>">
                                <?php echo $value['id']; ?></td>
                            <td class="lead_preview" data-href="/leads/lead/preview/<?php echo $value['id']; ?>">
                                <?php echo '<strong>' . $value['customer_name'] . '</strong><br />' . $value['customer_mobile'] . '<br />' . $value['customer_email']; ?>
                            </td>
                            <td class="lead_preview" data-href="/leads/lead/preview/<?php echo $value['id']; ?>">
                                <?php echo $value['category_name']; ?></td>
                            <td class="lead_preview" data-href="/leads/lead/preview/<?php echo $value['id']; ?>">
                                <?php echo $value['service_name']; ?></td>
                            <td class="lead_preview" data-href="/leads/lead/preview/<?php echo $value['id']; ?>">
                                <?php echo $value['branch_name']; ?></td>
                            <td class="lead_preview" data-href="/leads/lead/preview/<?php echo $value['id']; ?>">
                                <?php echo date('d M Y H:i A', strtotime($value['lead_added_on'])); ?></td>
                            <td>
                                <a href="<?php echo base_url(); ?>leads/lead/accept/<?php echo $value['id']; ?>"
                                    class="btn btn-block btn-sm btn-success">Accept</a>
                                <a href="#meetingModal" class="btn btn-danger btn-block btn-sm open-meetingDialog"
                                    data-toggle="modal" data-leadid="<?php echo $value['id']; ?>">
                                    Reject
                                </a>
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

    <!-- <div id="assign" class="tab-pane table-responsive" style="background: #fff;padding: 30px 30px;border: 1px solid #dee2e6;">
            <div class="row">
                <div class="col-12">
                    <?php if ($this->session->flashdata('alert')) { ?>
                        <div class="alert alert-<?php echo $this->session->flashdata('alert'); ?>">
                            <?php echo $this->session->flashdata('alert_message'); ?>
                        </div>
                    <?php } ?>
                    <table id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Customer</th>
                                <th>Category</th>
                                <th>Service</th>
                                <th>From Branch</th>
                                <th>Created Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($unassigned_leads as $key => $value) {


                            ?>
                                <tr id="o<?php echo $value['id']; ?>">
                                    <td><?php echo $value['id']; ?></td>
                                    <td><?php echo '<strong>' . $value['customer_name'] . '</strong><br />' . $value['customer_mobile'] . '<br />' . $value['customer_email']; ?></td>
                                    <td><?php echo $value['category_name']; ?></td>
                                    <td><?php echo $value['service_name']; ?></td>
                                    <td><?php echo $value['branch_name']; ?></td>
                                    <td><?php echo date('d M Y H:i A', strtotime($value['lead_added_on'])); ?></td>
                                    <td>
                                        <select onchange="javascript:assign_csa(<?php echo $value['id']; ?>,this.value,<?php echo $this->auth_user_id; ?>);" class="form-control single-select">
                                            <option value="">-- Select --</option>
                                            <?php
                                            // print_r($value);
                                            $lead_users = $this->user_model->get_lead_category_users($value['category_id']);
                                            log_message('error', $this->db->last_query());
                                            foreach ($lead_users as $keys => $values) {
                                            ?>
                                                <option value="<?php echo $values['user_id']; ?>">
                                                    <?php
                                                    $urole_id = $values['role_id'];
                                                    $user_role = ($urole_id == 2) ? 'CSA' : 'Coordinator';
                                                    echo $values['first_name'] . '  (' . $user_role . ')';
                                                    ?>
                                                </option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>

                </div>
            </div>
        </div> -->
    <div id="assigned" class="tab-pane table-responsive"
        style="background: #fff;padding: 30px 30px;border: 1px solid #dee2e6;">
        <div class="row">
            <div class="col-12">
                <?php if ($this->session->flashdata('alert')) { ?>
                <div class="alert alert-<?php echo $this->session->flashdata('alert'); ?>">
                    <?php echo $this->session->flashdata('alert_message'); ?>
                </div>
                <?php } ?>
                <table id="assigned_datatable" class="table table-bordered dt-responsive nowrap"
                    style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Customer</th>
                            <th>Category</th>
                            <th>Service</th>
                            <th>Assigned to</th>
                            <th>Created Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            foreach ($assigned_leads as $key => $value) {
                            ?>
                        <tr id="o<?php echo $value['id']; ?>">
                            <td data-href="/leads/lead/view/<?php echo $value['id']; ?>" class="lead_view">
                                <?php echo $value['id']; ?></td>
                            <td data-href="/leads/lead/view/<?php echo $value['id']; ?>" class="lead_view">
                                <?php echo '<strong>' . $value['customer_name'] . '</strong><br />' . $value['customer_mobile'] . '<br />' . $value['customer_email']; ?></a>
                            </td>
                            <td data-href="/leads/lead/view/<?php echo $value['id']; ?>" class="lead_view">
                                <?php echo $value['category_name']; ?></td>
                            <td data-href="/leads/lead/view/<?php echo $value['id']; ?>" class="lead_view">
                                <?php echo $value['service_name']; ?></td>
                            <td data-href="/leads/lead/preview/<?php echo $value['id']; ?>" class="lead_preview">
                                <?php
                                        if ($value['assigned_to'] == $this->auth_user_id) {
                                            echo 'Self';
                                        } else {
                                            $assigned_user_data = get_user_display_data($value['assigned_to']);
                                            echo '<strong>' . $assigned_user_data['first_name'] . ' ' . $assigned_user_data['last_name'] . '</strong><br />' . $assigned_user_data['mobile'] . '<br />' . $assigned_user_data['email'];
                                        }
                                        ?>
                            </td>
                            <td data-href="/leads/lead/view/<?php echo $value['id']; ?>" class="lead_view">
                                <?php echo date('d M Y H:i A', strtotime($value['lead_added_on'])); ?></td>
                            <td><a href="<?php echo base_url(); ?>leads/lead/view/<?php echo $value['id']; ?>"
                                    class="btn btn-sm btn-primary">View</a></td>
                        </tr>
                        <?php
                            }
                            ?>
                    </tbody>
                </table>

            </div>
        </div>
    </div>
    <?php
    }
    ?>
    <div id="manage"
        class="tab-pane table-responsive <?php if ($this->auth_user_role != 6) { ?>active<?php } else { ?> fade<?php } ?> "
        style="background: #fff;padding: 30px 30px;border: 1px solid #dee2e6;">
        <div class="row">
            <div class="col-12">
                <table id="datatable_leads" class="table table-bordered dt-responsive nowrap"
                    style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                    <thead>
                        <tr>
                            <th>#</th>
                            <!-- <th>Sub Lead</th> -->
                            <th>Customer</th>
                            <th>Category</th>
                            <th>Service</th>
                            <th>From Branch</th>
                            <th>Created Date</th>
                            <th>Contact Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($accepted_leads as $key => $value) {
                        ?>
                        <tr>
                            <td class="lead_view"
                                data-href="<?php echo base_url(); ?>leads/lead/view/<?php echo $value['id']; ?>">
                                <?php echo $value['id']; ?></td>
                            <!-- <td class="text-center"><a href="new_child?lead_parent_id=<?php echo $value['id']; ?>"><i class="fa fa-plus add_sub"></i></td> -->
                            <td class="lead_view"
                                data-href="<?php echo base_url(); ?>leads/lead/view/<?php echo $value['id']; ?>">
                                <?php echo '<strong>' . $value['customer_name'] . '</strong><br />' . $value['customer_mobile'] . '<br />' . $value['customer_email']; ?>
                            </td>
                            <td class="lead_view"
                                data-href="<?php echo base_url(); ?>leads/lead/view/<?php echo $value['id']; ?>">
                                <?php echo $value['category_name']; ?></td>
                            <td class="lead_view"
                                data-href="<?php echo base_url(); ?>leads/lead/view/<?php echo $value['id']; ?>">
                                <?php echo $value['service_name']; ?></td>
                            <td class="lead_view"
                                data-href="<?php echo base_url(); ?>leads/lead/view/<?php echo $value['id']; ?>">
                                <?php echo $value['branch_name']; ?></td>
                            <td class="lead_view"
                                data-href="<?php echo base_url(); ?>leads/lead/view/<?php echo $value['id']; ?>">
                                <?php echo date('d M Y H:i A', strtotime($value['lead_added_on'])); ?></td>
                            <td class="lead_view"
                                data-href="<?php echo base_url(); ?>leads/lead/view/<?php echo $value['id']; ?>">
                                <?php echo date('d M Y H:i A', strtotime($value['contactable_date'])); ?></td>
                            <td><a href="<?php echo base_url(); ?>leads/lead/view/<?php echo $value['id']; ?>"
                                    class="btn btn-sm btn-primary">View</a></td>
                        </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div id="converted" class="tab-pane table-responsive fade"
        style="background: #fff;padding: 30px 30px;border: 1px solid #dee2e6;">
        <div class="row">
            <div class="col-12">
                <table id="datatable" class="table table-bordered dt-responsive nowrap"
                    style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Customer</th>
                            <th>Category</th>
                            <th>Service</th>
                            <th>From Branch</th>
                            <th>Created Date</th>
                            <th>Order No#</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($converted_leads as $key => $value) {
                        ?>
                        <tr>
                            <td><?php echo $value['id']; ?></td>
                            <td><?php echo '<strong>' . $value['customer_name'] . '</strong><br />' . $value['customer_mobile'] . '<br />' . $value['customer_email']; ?>
                            </td>
                            <td><?php echo $value['category_name']; ?></td>
                            <td><?php echo $value['service_name']; ?></td>
                            <td><?php echo $value['branch_name']; ?></td>
                            <td><?php echo date('d M Y H:i A', strtotime($value['lead_added_on'])); ?></td>
                            <td><?php echo $value['order_receipt']; ?></td>
                            <td><a href="<?php echo base_url(); ?>leads/lead/view/<?php echo $value['id']; ?>"
                                    class="btn btn-sm btn-primary">View</a></td>
                        </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div id="disqualified" class="tab-pane table-responsive fade"
        style="background: #fff;padding: 30px 30px;border: 1px solid #dee2e6;">
        <div class="row">
            <div class="col-12">
                <table id="datatable" class="table table-bordered dt-responsive nowrap"
                    style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Customer</th>
                            <th>Category</th>
                            <th>Service</th>
                            <th>From Branch</th>
                            <th>Remarks</th>
                            <th>Contact Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($disqualified_leads as $key => $value) {
                        ?>
                        <tr>
                            <td><?php echo $value['id']; ?></td>
                            <td><?php echo '<strong>' . $value['customer_name'] . '</strong><br />' . $value['customer_mobile'] . '<br />' . $value['customer_email']; ?>
                            </td>
                            <td><?php echo $value['category_name']; ?></td>
                            <td><?php echo $value['service_name']; ?></td>
                            <td><?php echo $value['branch_name']; ?></td>
                            <td><?php echo $value['remarks']; ?></td>
                            <td><?php echo date('d M Y H:i A', strtotime($value['contactable_date'])); ?></td>
                            <td><a href="<?php echo base_url(); ?>leads/lead/view/<?php echo $value['id']; ?>"
                                    class="btn btn-sm btn-primary">View</a></td>
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

<script type="text/javascript">
$(document).ready(function() {
    $('#datatable_leads').DataTable({
        aaSorting: [
            [6, 'asc']
        ]
    });

    function init() { directInvoice();
        // alert("Inititated");
        $(".lead_preview,.lead_view").off();
        $(".lead_preview").on('click', function(e) {
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
                                    group_id: $("[data-child='group']")
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

    $('#new_datatable').on('draw.dt', init).DataTable({
        aaSorting: [
            [0, 'desc']
        ]
    });

    $('#assigned_datatable').on('draw.dt', init).DataTable({
        aaSorting: [
            [0, 'desc']
        ]
    });



});

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

<!-- Modal -->
<div class="modal fade" id="meetingModal" tabindex="-1" role="dialog" aria-labelledby="meetingModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="meetingModalLabel">Remarks (Mandatory)</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="<?php echo base_url(); ?>leads/lead/manage" method="post">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Please enter the reason for rejection&nbsp;<span
                                        class="text-danger required">*</span></label>
                                <textarea rows="5" class="form-control" name="rejection_remarks" required=""
                                    id="rejection_remarks"></textarea>
                            </div>
                            <div class="form-group">
                                <input type="hidden" name="lead_id" id="lead_id" value="">
                                <input type="submit" name="rejection_submit" class="btn btn-primary btn-block"
                                    value="Add remark and reject">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Lead Details</h5>
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
</script> */ ?>