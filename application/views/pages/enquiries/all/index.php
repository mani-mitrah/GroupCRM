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

.modal.fade .modal-dialog {
    transition: transform .3s ease-out;
    transform: translate(0, 50px);
}

.modal {
    position: fixed;
    top: 0;
    left: 0;
    z-index: 1050;
    display: none;
    width: 100%;
    height: 100%;
    overflow: hidden;
    background: #00000091;
    outline: 0;
}

.select2-container .select2-selection--single {
    border: 1px solid #ced4da;
    border-radius: 3px;
    padding-top: 7px;
    height: 38px;
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
                                    <span class="d-inline-block">Enquiries</span>
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
                                                <a href="<?php echo base_url(); ?>">Dashboard</a>
                                            </li>
                                            <li class="active breadcrumb-item">
                                                <a href="javascript:void(0);">Enquiries</a>
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
                                    <li class="nav-item">
                                        <a role="tab" data-toggle="tab" class="nav-link dt_filter" data-item="Enquiry"
                                            href="#tab-content-1">
                                            <span>Enquiries</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a role="tab" data-toggle="tab" class="nav-link dt_filter"
                                            data-item="Complaints" href="#tab-content-1">
                                            <span>Complaints</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a role="tab" data-toggle="tab" class="nav-link dt_filter" data-item="Meetings"
                                            href="#tab-content-2">
                                            <span>Meetings</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="app-inner-layout__wrapper">
                        <div class="app-inner-layout__content">
                            <div class="tab-content container-fluid">
                                <div class="row justify-content-center">
                                    <div class="col-lg-10">
                                        <div class="main-card mb-3 card mt-4">
                                            <div class="card-body table-responsive">
                                                <table style="width: 100%;" id="enq_dTable"
                                                    class="table table-hover table-striped table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Customer</th>
                                                            <th>Description</th>
                                                            <th>Type</th>
                                                            <th>Website</th>
                                                            <th>Status</th>
                                                            <th>Last Follow-up</th>
                                                            <th>Created at</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $enq_type[1] = "Enquiry";
                                                        $enq_type[2] = "Complaints";
                                                        $enq_type[3] = "Meetings";
                                                        foreach ($enquiries as $enq) {
                                                            // print_r($enq);
                                                            // exit();
                                                        ?>
                                                        <tr>
                                                            <td><?php echo $enq["id"]; ?></td>
                                                            <td <?php if ($enq["status_id"] < 305 && $this->auth_user_role > 5) { ?>
                                                                class="enq-assign" data-id="<?php echo $enq["id"]; ?>"
                                                                <?php }
                                                                                                                                                                                                ?>>
                                                                <?php echo "<b>" . $enq["name"] . "</b><br>" . $enq["phone"] . "<br>" . $enq["email"]; ?>
                                                            </td>
                                                            <td><?php echo substr(strip_tags($enq["subject"]), 0, 25) . '...'; ?>
                                                            </td>
                                                            <td><?php echo $enq_type[$enq["enquiry"]]; ?></td>
                                                            <td><?php echo $enq["website_name"]; ?></td>
                                                            <td><?php echo $enq["status_name"]; ?>
                                                            </td>
                                                            <td><?php echo $enq["last_followup"]; ?></td>
                                                            <td><?php echo $enq["created_date"]; ?></td>
                                                            <td>
                                                                <a
                                                                    href="https://crm.ontimegroup.com/enquiries/all/view?id=<?php echo $enq['id']; ?>">
                                                                    <button class="btn btn-primary">View</button>
                                                                </a>
                                                                <?php
                                                                    if ($this->auth_user_role > 5) {
                                                                    ?>
                                                                <br>
                                                                <div style="margin-top: 10px;">
                                                                <a
                                                                    class="enq-assign text-white btn btn-gradient-primary">Assign</a>
                                                                    </div>
                                                                <?php } ?>
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

<!-- Modal -->
<div class="modal" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Assign #<span id="ass_enq_id"></span> To:</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <input type="hidden" name="ass_enq_id" value="">
                    <div class="form-group">
                        <label>Assigned Group: </label>

                        <select data-child="group" class="form-control single-select" id="assigned_group">
                            <option value="">-- Select --</option>
                            <?php
                            $groups = $this->master_model->get_groups();
                            // print_r($groups);
                            log_message('error', $this->db->last_query());
                            foreach ($groups as $keys => $values) {
                            ?>
                            <option value="<?php echo $values['group_id']; ?>">
                                <?php
                                    echo $values['group_name'];
                                    ?>
                            </option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">

                        <label>Assigned to: </label>

                        <select data-parent="group" class="form-control single-select" id="assigned_to">
                            <option value="">-- Please Select the Group --</option>
                        </select>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// $('#exampleModal').on('show.bs.modal', event => {
//     var button = $(event.relatedTarget);
//     var modal = $(this);
//     // Use above variables to manipulate the DOM

// });
</script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>

<script src="<?php echo base_url(); ?>global/vendor/datatables/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(); ?>global/node_modules/select2/dist/js/select2.min.js"></script>
<script>
function assign_csa(item_id, assigned_to) {
    // console.log(item_id, assigned_to);
    if (assigned_to == null || assigned_to == '') return;
    Swal.fire({
        title: 'Please confirm',
        text: "Want to assign to this user ?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, Assign!'
    }).then((result) => {
        if (result.isConfirmed) {

            $.ajax({
                url: "<?php echo base_url(); ?>api/v1/assign/enquiry",
                type: 'POST',
                data: {
                    assigned_to: assigned_to,
                    item_id: item_id,
                    assigned_by: <?php echo $this->auth_user_id; ?>
                },
                success: function(res) {
                    $('#o' + item_id).hide();
                    Swal.fire(
                        'Assigned!',
                        res,
                        'success'
                    );
                    location.reload();
                },
                error: function(e) {
                    Swal.fire(
                        'Sorry!',
                        e,
                        'error'
                    )
                }
            });

        }
    });
}

$('#assigned_group').change(function() {
        // Empty the Assigned To dropdown
        $('#assigned_to').html('<option value="">-- Please Select the Group --</option>');
});

document.addEventListener('DOMContentLoaded', function () {
    // Retrieve the selected type from localStorage
    const selectedType = localStorage.getItem('selectedType');
    const navLinks = document.querySelectorAll('.nav-link.dt_filter');
    const defaultType = ''; // Default type corresponds to "Overall"

    // Helper function to set the active tab
    const setActiveTab = (type) => {
        navLinks.forEach(link => {
            link.classList.remove('active');
            if (link.dataset.item === type) {
                link.classList.add('active');
            }
        });
    };

    // Determine which tab to activate
    if (history.state && history.state.isBackNavigation) {
        // Handle "Go Back" navigation
        if (selectedType) {
            setActiveTab(selectedType);
        }
    } else {
        // On first load or reload, show the default type
        setActiveTab(defaultType);
        localStorage.setItem('selectedType', defaultType);
    }

    // Attach click event listeners to nav links
    navLinks.forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();

            // Save the selected type to localStorage
            const type = this.dataset.item || '';
            localStorage.setItem('selectedType', type);

            // Set the active tab
            setActiveTab(type);

            // Update history state to track navigation
            history.replaceState({ isBackNavigation: true }, document.title);
        });
    });
});

$("document").ready(function () {
    $('[data-dismiss="modal"]').click(function (e) {
        e.preventDefault();
        $("#modelId").hide("slow");
    });

    $("#enq_dTable")
        .on("draw.dt", function () {
            $(".enq-assign").off();
            $(".enq-assign").on("click", function (e) {
                e.preventDefault();
                var id = $(this).data("id");
                $("#ass_enq_id").html(id);
                $("[name='ass_enq_id']").val(id);
                $('[data-child="group"]').val("");
                $('[data-parent="group"]').val("").trigger("change");
                $("#modelId").show("slow");
                $('[data-parent="group"]').change(function (e) {
                    e.preventDefault();
                    var vals = $(this).val();
                    assign_csa(id, vals);
                });
            });
        })
        .dataTable({
            order: [[0, "desc"]],
            initComplete: function () {
                this.api()
                    .columns()
                    .every(function () {
                        var column = this;
                        if (column.index() == 3 || column.index() == 4 || column.index() == 5) {
                            // Add dropdown only for specific columns
                            $(column.header()).append("<br>");
                            var select = $(
                                '<select id="dt_drop_' +
                                    column.index() +
                                    '" style="width: 120px;"></select>'
                            )
                                .appendTo($(column.header()))
                                .on("change", function () {
                                    var val = $.fn.dataTable.util.escapeRegex($(this).val());
                                    column.search(val ? "^" + val + "$" : "", true, false).draw();
                                });

                            // Add empty option at the start
                            select.append('<option value=""></option>');

                            // Populate unique values in dropdown
                            column
                                .data()
                                .unique()
                                .sort()
                                .each(function (d, j) {
                                    if (d.trim() !== "") {
                                        select.append('<option value="' + d + '">' + d + "</option>");
                                    }
                                });

                            // Set default behavior for column index 3
                            if (column.index() === 3) {
                                select.val("").trigger("change");
                            }
                        }
                    });

                // Handle custom filter clicks
                $(".dt_filter").click(function (e) {
                    e.preventDefault();
                    var filterValue = $(this).data("item");

                    // Handle "Overall" selection for column 3 only
                    if (filterValue === "") {
                        $("select#dt_drop_3").val("").trigger("change");
                        $("select#dt_drop_4").val("").trigger("change");   
                        $("select#dt_drop_5").val("").trigger("change");
                    } else {
                        $("select#dt_drop_3").val(filterValue).trigger("change");
                    }
                });
            },
        });
});

$("[data-child='group']").change(function (e) {
    e.preventDefault();
    $("[data-parent='group']").select2({
        placeholder: "Select the Group",
        ajax: {
            url: "/admin/group/group_members",
            dataType: "json",
            data: function (params) {
                var query = {
                    search: params.term,
                    group_id: $("[data-child='group']").val(),
                };
                return query;
            },
            processResults: function (data) {
                return {
                    results: data,
                };
            },
            cache: true,
        },
    });
});

</script>