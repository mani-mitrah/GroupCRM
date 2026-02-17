<?php
$groups = get_groups($this->auth_user_id);
?>
<div class="app-main">
    <div class="app-main__outer">
        <div class="app-main__inner p-0">
            <div class="app-page-title">
                <div class="container fiori-container">
                    <div class="page-title-wrapper">
                        <div class="page-title-heading">
                            <div>
                                New Attestation Lead
                                <div class="page-title-subheading">Creation and Assign on the Lead</div>
                            </div>
                        </div>
                        <div class="page-title-actions">
                            <div class="d-inline-block dropdown">
                                <a href="<?php echo base_url();?>leads/lead/new">
                                    <button type="button" class="btn btn-outline-primary btn-shadow btn-wide">
                                        <span class="btn-icon-wrapper pr-1 opacity-7">
                                            <i class="fa fa-plus"></i>
                                        </span>
                                        Create Lead
                                    </button>
                                </a>
                                <a href="<?php echo base_url();?>leads/lead/manage">
                                    <button type="button" class="btn btn-gradient-primary btn-shadow btn-wide">
                                        <span class="btn-icon-wrapper pr-1 opacity-7">
                                            <i class="fa fa-list"></i>
                                        </span>
                                        All Leads
                                    </button>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="fiori-container container">
                <div class="app-inner-layout chat-layout justify-content-center mt-5">
                    <script type="text/javascript">
                    $(document).ready(function() {
                        $('#category_block').show();
                        $('#service_block').show();
                        $('#package_block').hide();

                        //add new
                        $('#btnAddNewAttachment').click(function(e) {
                            e.preventDefault();
                            var newDiv = $(
                                '<div class="row mt-3"><div class="col-md-6"><input type="text" class="form-control" required="" name="attachment_name[]" placeholder="Attachment Name" /></div><div class="col-md-5"><input type="file" class="form-control" required="" name="files[]" placeholder="" /></div><div class="col-md-1 float-right"><a href="#" class="text-danger close-div"><i class="fa fa-trash"></i></a></div></div>'
                            );
                            $('body').animate({
                                scrollTop: eval($('#attachment_area').offset().top - 70)
                            }, 1000);
                            $('#attachments').append(newDiv);

                            $('.close-div').click(function(e) {
                                e.preventDefault();
                                $(this).parent().parent().remove();
                                $('body').animate({
                                    scrollTop: eval($('#attachment_area').offset().top -
                                        70)
                                }, 1000);
                            });
                        });
                    });
                    </script>
                    <!-- <div class="row page-titles mx-0">
                        <div class="col-sm-6 p-md-0">
                            <Sdiv class="welcome-text">
                                <h4>New Lead</h4>
                                <span>Ontime Leads Management</span>
                            </div>
                        </div>
                        <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                            <a class="btn btn-primary waves-effect waves-light float-right" href="<?php echo base_url(); ?>leads/lead/manage"> VIEW LEADS
                            </a>
                        </div>
                    </div> -->
                    <div class="card">
                        <div class="card-body">
                            <?php if ($this->session->flashdata('alert')) { ?>
                            <div class="alert alert-<?php echo $this->session->flashdata('alert'); ?>">
                                <?php echo $this->session->flashdata('alert_message'); ?>
                            </div>
                            <?php } ?>
                            <form action="<?php echo base_url(); ?>leads/lead/attestationnew" method="post"
                                enctype="multipart/form-data" id="attest_lead_form">
                                <input type="hidden" class="lead_cross_sale_pmt" name="lead_cross_sale_pmt">
                                <input type="hidden" name="lead_emp_id" value="0">
                                <input type="hidden" name="lead_emp_discount_per" value="0">
                                <div class=" row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Lead / Company Name&nbsp;<span
                                                    class="text-danger required">*</span></label>
                                            <input type="text" class="form-control" required="" name="lead_name"
                                                autofocus>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Lead Type</label>
                                            <select name="lead_type" id="" class="form-control lead_type" required>
                                                <option value="lead" selected>Lead</option>
                                                <?php if (in_array(77, $groups)) { ?>
                                                <option value="walkin">Walkin</option>
                                                <option value="cross sales">Cross Sales</option>
                                                <option value="emp">OnTime Employee</option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Lead Customer Type</label>
                                            <select name="customer_type" id="" class="form-control" required>
                                                <option value="individual" selected>Individual</option>
                                                <option value="corporate">Corporate</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12 d-none receipt">
                                        <div class="form-group">
                                            <label>Cross Sale Receipt Number</label>
                                            <div class="input-group mb-3">
                                                <input type="text" class="form-control lead_cross_sale_pmt" disabled
                                                    aria-describedby="basic-addon2">
                                                <div class="input-group-append clear-cross-sale"
                                                    style="cursor: pointer;">
                                                    <span class="input-group-text" id="basic-addon2">X</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Country Code&nbsp;<span class="text-danger required">*</span></label>
                                            <input type="number" class="form-control" required=""
                                                name="lead_country_code" value="971">
                                        </div>
                                    </div>
                                    <div class="col-md-10">
                                        <div class="form-group">
                                            <label>Lead contact number without country code&nbsp;<span
                                                    class="text-danger required">*</span></label>
                                            <input type="number" class="form-control" required="" name="lead_contact">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Lead Email Address</label>
                                            <input type="email" class="form-control" name="lead_email">
                                        </div>
                                    </div>


                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Lead Address (If Corporate: Put as on VAT Certificate)</label>
                                            <input type="text" class="form-control" name="customer_address">
                                        </div>
                                    </div>

                                    <div class="col-md-6 individual">
                                        <div class="form-group">
                                            <label>Alternate Contact No</label>
                                            <input type="number" class="form-control" name="alt_mobile">
                                        </div>
                                    </div>
                                    <div class="col-md-6 individual">
                                        <div class="form-group">
                                            <label>Alternate Email Address</label>
                                            <input type="email" class="form-control" name="alt_email">
                                        </div>
                                    </div>
                                    <div class="col-md-6 corporate d-none">
                                        <div class="form-group">
                                            <label>TRN</label>
                                            <input type="text" class="form-control" name="trn_no">
                                        </div>
                                    </div>
                                    <div class="col-md-6 corporate d-none">
                                        <div class="form-group">
                                            <label>Trade No</label>
                                            <input type="text" class="form-control" name="trade_no">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="">Attestation Service (Incl of VAT)<span
                                                    class="text-danger required">*</span></label>
                                            <select class="form-control" name="service" id="service_id" required>
                                                <!-- <option value="" disabled default selected>-- Select the service --  -->
                                                </option>
                                                <option value="">-- Select the service --</option>
                                                <?php
                                                // print_r($services->data);
                                                foreach ($services->Data as $serve) {
                                                    $fee = $serve->TypingFee + $serve->GovtFee;
                                                    echo "<option data-id='" . $serve->Id . "' data-amount='$fee' value='$serve->ServiceName'>".$serve->ServiceName." - AED " . $fee . "/-</option>";
                                                }
                                                echo "<option data-id='1' data-amount='0' value='Other - For Enquiry'>Other - For Enquiry</option>";

                                                ?>
                                            </select>
                                            <input type="hidden" name="service_amount" value="0">
                                            <input type="hidden" name="bot_id" value="0">
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Remarks(in English/Arabic)&nbsp;<span
                                                    class="text-danger required">*</span></label>
                                            <textarea rows="7" class="form-control" required=""
                                                name="lead_remarks"></textarea>
                                        </div>
                                    </div>
                                    <a id="attachment_area"></a>
                                    <div class="col-md-6">
                                        <div class="form-group text-left">
                                            <input type="reset" id ="reset"
                                                class="btn btn-lg btn-outline-primary btn-square p-3 pl-5 pr-5"
                                                value="RESET" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group text-right">
                                            <input type="hidden" name="lead_created_by"
                                                value="<?php echo $this->auth_user_id; ?>">
                                            <input type="submit" class="btn btn-lg btn-primary btn-square p-3 pl-5 pr-5"
                                                name="submit" value="CREATE LEAD" />
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <script type="text/javascript">
                function normal_lead() {
                    $('#category_block').show();
                    $('#service_block').show();
                    $('#package_block').hide();
                }

                function package_lead() {
                    $('#category_block').hide();
                    $('#service_block').hide();
                    $('#package_block').show();
                }

                $("#branch_id").change(function() {
                    var biz = [6, 13, 14, 20, 21]
                    var val = parseInt($(this).val());
                    console.log("There=> ", val);
                    // alert(val);
                    if (biz.indexOf(val) != -1) {

                        // location.href = "/leads/lead/biznew";
                        swal.fire({
                            icon: "info",
                            text: "Redirecting to Business Setup Leads Page",
                            didOpen: function() {
                                swal.enableLoading();
                                setTimeout(() => {
                                    location.href = "/leads/lead/biznew";
                                }, 2000);
                            }
                        })
                    }
                });

                function select_services(category_id) {
                    $.ajax({
                        url: "<?php echo base_url(); ?>leads/lead/get_services?category_id=" + category_id,
                        method: "GET",
                        type: 'ajax',
                        success: function(data) {
                            var result = JSON.parse(data);
                            $('#service_id').html("");
                            if (result.length == 0) {
                                //$('#existing_items').append('<span class="badge light badge-danger">There are no services existing in this workflow</span>');
                            } else {
                                for (var i = 0; i < result.length; i++) {
                                    if (result[i]['service_code'] == "" || result[i]['service_code'] ==
                                        null) {
                                        $('#service_id').append('<option value="' + result[i][
                                                'service_id'
                                            ] + '">' + result[i]['service_name'] +
                                            '</option>');
                                    } else {
                                        $('#service_id').append('<option value="' + result[i][
                                                'service_id'
                                            ] + '">' + result[i]['service_code'] +
                                            '</option>');
                                    }

                                }
                            }
                        },
                        error: function(err) {
                            console.log(err);
                        }
                    });
                }

                const servicesData = <?php echo json_encode($services->Data); ?>;
                $(document).ready(function () {
                    $("#reset").on('click', function () {
                        console.log('Reset button clicked.');

                        const serviceSelect = $('#service_id');
                        serviceSelect.empty(); // Clear all existing options
                        serviceSelect.append('<option value="">-- Select the service --</option>');

                        // Loop through `servicesData` to repopulate options
                        servicesData.forEach(service => {
                            const fee = service.TypingFee + service.GovtFee;
                            const option = `<option data-id="${service.Id}" data-amount="${fee}" value="${service.ServiceName}">${service.ServiceName} - AED ${fee}/-</option>`;
                            serviceSelect.append(option);
                        });

                        // Add the "Other - For Enquiry" option
                        serviceSelect.append('<option data-id="1" data-amount="0" value="Other - For Enquiry">Other - For Enquiry</option>');

                        // Reset the selected index
                        serviceSelect.prop('selectedIndex', 0);
                        serviceSelect.val('');
                    });
                });


                $('[name="assign_group"]').change(function() {
                    $('[name="assign_to"]').val("").trigger("change");
                    // alert($(this).val());
                    if ($(this).val() == "") {
                        $('[name="assign_to"] option').removeClass("d-none");
                        return;
                    }
                    $('[name="assign_to"] option:not([value=""])').addClass("d-none");
                    $('[name="assign_to"] option:not([value=""])[data-filter*="' + $(this).val() + '"]')
                        .removeClass("d-none");
                });
                </script>
            </div>
        </div>
    </div>
</div>

<!-- Cross Sales Modal -->
<div class="modal fade" id="cross_sales" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cross Sales</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <input type="text" name="cross_sales_rct" placeholder="RCT-XXXXXX" class="form-control" id=""
                        autofocus>
                </div>
                <div class="form-group">
                    <button class="btn btn-block btn-primary cross-sale-btn">Fetch Details</button>
                </div>
            </div>
        </div>
    </div>
</div>



<!-- <script src="<?php echo base_url(); ?>global/js/jquery-3.3.1.min.js"></script> -->
<script src="<?php echo base_url(); ?>global/node_modules/select2/dist/js/select2.min.js"></script>

<link rel="stylesheet" href="<?php echo base_url(); ?>global/node_modules/select2/dist/css/select2.min.css">
<script src="<?php echo base_url(); ?>global/vendor/datatables/js/jquery.dataTables.min.js"></script>
<script
    src="<?php echo base_url(); ?>assets_new/node_modules/datatables.net-responsive/js/dataTables.responsive.min.js">
</script>
<script
    src="<?php echo base_url(); ?>assets_new/node_modules/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js">
</script>
<link rel="stylesheet"
    href="<?php echo base_url(); ?>assets_new/node_modules/datatables.net-responsive-bs4/css/responsive.bootstrap4.css" />


<?php if (in_array(77, $groups)) { ?>
<!-- Employee Master Modal -->
<div class="modal fade" id="getemp_customer" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Employee Master Model</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered table-hoverable table-striped w-100" id="getemp_ddtable"></table>
            </div>
        </div>
    </div>
</div>
<script>
$("form").on("reset", function() {
    $('[name="lead_cross_sale_pmt"]').val("");
    $('[name="lead_emp_id"]').val("0");
    $('[name="lead_emp_discount_per"]').val("0");
});

function dTSelection() {

    // alert("Init");
    $('#getemp_ddtable tbody tr td').off();
    $('#getemp_ddtable tbody tr td').on('click', '.emp-select', function() {
        var data = dTtable.row($(this).closest("tr")).data();
        console.log(data);
        $('[name="lead_name"]').val(data.Cust_EngName);
        $('[name="lead_email"]').val(data.Cust_Email);
        $('[name="lead_contact"]').val(data.Cust_Mobile);
        $('[name="lead_emp_id"]').val(data.Cust_Key);
        $('[name="lead_emp_discount_per"]').val(data.Disc_Per);
        // return false;
        $("#getemp_customer").modal("hide")
    });
}

window.dTtable = $("#getemp_ddtable").on('draw.dt', dTSelection).DataTable({
    "ajax": "/leads/lead/getEmpCustomers",
    "lengthMenu": [
        [5, 10, 25, 50, -1],
        [5, 10, 25, 50, "All"]
    ],
    "columns": [{
            "data": "Cust_EngName",
            "title": "Name",
        },
        {
            "data": "Cust_Mobile",
            "title": "Mobile"
        },
        {
            "data": "Cust_Email",
            "title": "Email"
        },

        {
            "data": "Disc_Per",
            "title": "Discount %"
        },
        {
            "data": "Cust_Key",
            "title": "Action",
            "render": function(data) {
                var button = "<button class='btn btn-primary emp-select'>Use</button>";
                return button;
            }
        }
    ]
});
</script>

<?php } ?>

<script>
$("#service_id").select2({
    closeOnSelect: true
});

$(document).on('select2:open', () => {
    document.querySelector('.select2-search__field').focus();
});

$("#service_id").change(function(e) {
    e.preventDefault();
    var service_amount = $("#service_id option:selected").data("amount");
    var bot_id = $("#service_id option:selected").data("id");
    $("[ame='service_amount']").val(service_amount);
    $("[name='bot_id']").val(bot_id);
});

$("form").submit(function(e) {
    var service_amount = $("#service_id option:selected").data("amount");
    $("[name='service_amount']").val(service_amount);
    // $("form").submit();
});


$(".clear-cross-sale").click(function() {
    $("#attest_lead_form")[0].reset();
    $(".receipt").addClass("d-none");
});

$("[name='customer_type']").change(function() {
    var val = $(this).val();
    if (val == "individual") {
        $(".individual").removeClass("d-none");
        $(".corporate input").val("");
        $(".corporate").addClass("d-none");
        $('[name="customer_address"]').removeAttr("required");
        $("[name='trn_no']").removeAttr("required");
    } else {
        $(".individual").addClass("d-none");
        $(".corporate").removeClass("d-none");
        $(".individual input").val("");
        $('[name="customer_address"]').attr("required", "required");
        $("[name='trn_no']").attr("required", "true");
    }
});

$(".lead_type").change(function(e) {
    e.preventDefault();
    var val = $(this).val();
    if (val == "cross sales") {
        $('[name="cross_sales_rct"]').val("");
        $(".receipt").addClass("d-none");
        $("#cross_sales").modal("show");
    }
    if (val == "emp") {
        $(".receipt").addClass("d-none");
        $("#getemp_customer").modal("show");
    } else {
        $('.lead_cross_sale_pmt').val("");
        $('[name="lead_emp_id"]').val("");
        $('[name="lead_emp_discount_per"]').val("");
        $(".receipt").addClass("d-none");
    }
});
$("#cross_sales").on("hidden.bs.modal hide.bs.modal", function() {
    var cross_pmt = $('[name="lead_cross_sale_pmt"]').val();
    if (cross_pmt.trim().length < 3) {
        $(".lead_type").val("lead").trigger("change");
    }
});

$("#getemp_customer").on("hidden.bs.modal hide.bs.modal", function() {
    var cross_pmt = $('[name="lead_emp_id"]').val();
    if (cross_pmt.trim().length < 2) {
        $(".lead_type").val("lead").trigger("change");
    }
});

$(".cross-sale-btn").click(function(e) {
    e.preventDefault();
    var rct = $('[name="cross_sales_rct"]').val();
    if (rct.trim().length == 0) {
        swal.fire({
            icon: "info",
            text: "Please enter Payment Recipt number",
        }).then((val) => {
            $('[name="cross_sales_rct"]')[0].focus();
        });
        return;
    }
    Swal.fire({
        allowEscapeKey: false,
        allowOutsideClick: false,
        didOpen: function() {
            swal.enableLoading();
        }
    });
    $.get("/leads/lead/getcrosssales?RCT=" + rct, function(data) {
        if (data != "" && data != null) {
            datum = JSON.parse(data);
            if (datum.ResponseCode != 0) {
                swal.fire({
                    icon: "info",
                    text: datum.ResponseMsg
                }).then((val) => {
                    $('[name="cross_sales_rct"]')[0].focus();
                });
            } else {
                var name = datum.Data[0].Cust_EngName;
                var email = datum.Data[0].Customer.Cust_Email;
                var mobile = datum.Data[0].Customer.Cust_Mobile;
                var pmt = datum.Data[0].DocPrefix + datum.Data[0].DocNumb;
                Swal.fire({
                    icon: "info",
                    text: "Cutomer Details",
                    html: '<table class="table text-left table-bordered"><tr><td>Name</td><td class="text-bold">' +
                        name +
                        '</td></tr><tr><td>Email</td><td class="text-bold">' + email +
                        '</td></tr><tr><td>Mobile</td><td class="text-bold">' + mobile +
                        '</td></tr><tr><td>Receipt</td><td class="text-bold">' + pmt +
                        '</td></tr></table>',
                    showCancelButton: true,
                    allowOutsideClick: false,
                    allowEscapeKey: false
                }).then((val) => {
                    console.log(val);
                    if (val.isConfirmed == true) {
                        swal.fire({
                            showConfirmButton: false,
                            showCancelButton: false,
                            didOpen: function() {
                                swal.showLoading();
                            },
                            allowOutsideClick: false,
                            allowEscapeKey: false
                        });


                        $('[name="lead_name"]').val(name);
                        $('[name="lead_email"]').val(email);
                        $('[name="lead_contact"]').val(mobile);
                        $('.lead_cross_sale_pmt').val(pmt);
                        $(".receipt").removeClass("d-none");
                        swal.close();
                        $("#cross_sales").modal("hide");
                    }
                });
                console.log(datum);
            }
        }
    });
});
</script>