<style>
    .selected_row {cursor: pointer !important;}

    .otp-input {
        width: 50px;
        height: 50px;
        text-align: center;
        font-size: 24px;
        margin: 5px;
        border-radius: 8px;
        border: 1px solid #ccc;
    }
    .otp-container {
        display: flex;
        justify-content: center;
    }
</style>
<div class="app-main">
    <div class="app-main__outer">
        <div class="app-main__inner p-0">
            <div class="app-page-title">
                <div class="container fiori-container">
                    <div class="page-title-wrapper">
                        <div class="page-title-heading">
                            <div>
                                General Package - New Sale
                                <div class="page-title-subheading">Creation of new Sale</div>
                            </div>
                        </div>
                        <div class="page-title-actions">
                            <div class="d-inline-block dropdown">
                                <a href="<?php echo base_url(); ?>leads/lead/manage">
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

                            <?php
                               // if (in_array(510, $branches)) {
                                //$branch_id = getUserBranch($this->auth_user_id);
                                //echo "<pre>";
                                //print_r($branch_id);

                            ?>


                            <form id="createLeadForm" action="<?php echo base_url(); ?>leads/lead/general_pack_sale_new" method="post" enctype="multipart/form-data">
                                <!-- <input type="hidden" name="branch_id" value="106"> -->
                                <!-- 106,109 -->
                                <input type="hidden" name="lead_type" value="package">
                                <!-- <input type="hidden" name="assign_group" value="GoldenCube"> -->
                                <input type="hidden" name="assign_to" value="<?php echo $this->auth_user_id; ?>">
                                <input type="hidden" name="customer_otp" value="">
                                <input type="hidden" name="user_email" value="<?php echo $created_user_email; ?>">
                                <input type="hidden" name="user_pos_id" value="<?php echo $user_pos_id; ?>">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Select Branch &nbsp;<span class="text-danger required">*</span></label>
                                            <select class="form-control" name="branch_id" id="branch_id">
                                                <option value="">-- Select Branch --</option>
                                                <?php
                                                $branch_id = getUserBranch($this->auth_user_id);
                                                foreach ($branches as $key => $value) {
                                                    if ($value['branch_code'] == 106 || $value['branch_code'] == 109 || $value['id'] == 106 || $value['id'] == 109 || $value['id'] == 103 || $value['branch_code'] == 103) {
                                                        continue;
                                                    }

                                                    // Check if the branch code exists in the branch_id array
                                                    if (in_array($value['id'], $branch_id)) {
                                                        ?>
                                                        <option value="<?php echo $value['branch_code']; ?>">
                                                            <?php echo $value['branch_name']; ?></option>
                                                        <?php
                                                    } 
                                                }
                                                ?>
                                            </select>

                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>Payment Type&nbsp;<span class="text-danger required">*</span></label>
                                            <?php
                                                $options = array(
                                                    ''=>'Select payment type',
                                                    'card' => 'Card',
                                                    'cash' => 'Cash',
                                                    'online' => 'Online',
                                                );
                                                echo form_dropdown('payment_type', $options, "", array('class' => 'form-control', 'id' => 'payment_type2', 'required' => 'required'));
                                            ?>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>Select Package &nbsp;<span class="text-danger required">*</span></label>
                                            <select class="form-control" name="package_id" id="package_id" required autofocus onchange="get_dataset(this.value);">
                                                <option value="">-- Select Package --</option>
                                                <?php
                                                foreach ($packages as $key => $value) {
                                                ?>
                                                    <option data-branch="<?php echo $value['package_branch'];?>" data-amount="<?php echo $value["no_card_amount"]; ?>" data-amount="<?php echo $value["package_amount"]; ?>" data-payment-type="<?php echo $value["payment_type"]; ?>" value="<?php echo $value['package_id']; ?>" class="package-option">
                                                        <?php echo $value['package_name'] . " - " . $value["package_amount"] . "AED - ".$value["payment_type"]; ?>
                                                    </option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                    <style>
                                        .service-row {
                                            counter-increment: sno;
                                        }

                                        .s-no {
                                            width: 20px;
                                        }

                                        .s-no:after {
                                            content: counter(sno)".";
                                        }

                                        .serv-desc {
                                            width: calc(100% - 20px)
                                        }

                                        .service-row:not(:nth-child(1)) label {
                                            display: none;
                                        }

                                        input[name='service_qty[]']::-webkit-outer-spin-button,
                                        input[name='service_qty[]']::-webkit-inner-spin-button {
                                            -webkit-appearance: none;
                                            margin: 0;
                                        }

                                        /* Firefox */
                                        input[name='service_qty[]'][type=number] {
                                            -moz-appearance: textfield;
                                        }
                                    </style>
                                    <div class="col-lg-12 d-none" id="services">
                                        <fieldset class="border mb-3">
                                            <legend class="bg-plum-plate font-weight-lighter ml-1 p-1 pl-3 pr-3 text-white w-auto">Package Services</legend>
                                            <div class="serices-content">
                                                <div class="row m-0 service-row" id="service-row">
                                                    <input type="hidden" name="service_id[]">
                                                    <input type="hidden" name="is_meeting_contain[]">
                                                    <div class="col-lg-4 d-flex">
                                                        <div class="s-no m-auto"></div>
                                                        <div class="form-group serv-desc">
                                                            <label for="">Service Description</label>
                                                            <input type="text" name="service_name[]" id="" class="form-control" placeholder="" aria-describedby="helpId" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-1 pl-0 pr-0 text-center">
                                                        <div class="form-group">
                                                            <label for="" class="w-100">Qty</label>
                                                            <input type="number" name="service_qty[]" min="1" id="" class="form-control text-center" placeholder="" aria-describedby="helpId" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-2">
                                                        <div class="form-group">
                                                            <label for="">Govt Fee</label>
                                                            <input type="number" name="govt_fee[]" id="" class="form-control" placeholder="" aria-describedby="helpId" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-2">
                                                        <div class="form-group">
                                                            <label for="">Typing Fee (Incl Vat)</label>
                                                            <input type="number" name="typing_fee[]" id="" class="form-control" placeholder="" aria-describedby="helpId" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-2">
                                                        <div class="form-group">
                                                            <label for="">Total</label>
                                                            <input type="hidden" name="msd_key[]">
                                                            <input type="hidden" name="is_pos_typing_fee[]">
                                                            <input type="hidden" name="is_direct_invoice[]">
                                                            <input type="number" name="sub_total[]" id="" class="form-control" placeholder="" aria-describedby="helpId" readonly required>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-1 text-center">
                                                        <div class="form-group">
                                                            <label for="">Action</label>
                                                            <button class="btn btn-primary action-btn form-control" type="button">x</button>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12 meeting-user d-none">
                                                        <div class="row justify-content-center">
                                                            <div class="col-lg-4">
                                                                <div class="form-group">
                                                                    <label>User &nbsp;<span class="text-danger required">*</span></label>
                                                                    <select class="form-control slot_user_id" name="slot_user_id[]" id="slot_user_id" data-id="" required autofocus>
                                                                        <option value="">-- Select User --</option>
                                                                        <?php
                                                                        // print_r($slot_users);
                                                                        foreach ($slot_users as $value) {
                                                                        ?>
                                                                            <option value="<?php echo $value['user_id']; ?>">
                                                                                <?php echo $value['first_name'] . " " . $value["last_name"] . " [ " . $value['employee_id'] . "]"; ?></option>
                                                                        <?php
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-3">
                                                                <div class="form-group">
                                                                    <label>Date &nbsp;<span class="text-danger required">*</span></label>
                                                                    <input type="text" name="slot_date[]" id="slot_date" class="form-control slot_date" disabled required>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-3">
                                                                <div class="form-group">
                                                                    <label>Slot &nbsp;<span class="text-danger required">*</span></label>
                                                                    <select class="form-control slot" name="slot[]" id="slot" disabled required>
                                                                        <option value="">-- Select Slot --</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="divider"></div>
                                            <div class="row m-0">
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label for="service_addition">Add Service</label>
                                                        <select id="service_addition" class="custom-select" name="">
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </fieldset>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <label>Customer Search</label>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control"  readonly
                                                            name="" value="CMP" id="">
                                                    </div> 
                                                </div>
                                        
                                                <div class="col-md-7">
                                                    <input type="text" class="form-control" placeholder="Customer Search" id="lead_value" title="search lead" value="">                                                    
                                                </div>
                                                 <div class="col-md-3">
                                                    <button type="button" class="btn btn-primary" id="lead_search"> Customer Search</button>                                                
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" id="cust_id" class="form-control" name="cust_id" value="">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Customer Name&nbsp;<span class="text-danger required">*</span></label>
                                            <input type="text"  id="lead_name" class="form-control selected_field" required="" name="lead_name" autofocus>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Country Code&nbsp;<span class="text-danger required">*</span></label>
                                            <input type="text" pattern="+[0-9]" class="form-control" title="Country Code like +971" required="" name="lead_country_code" value="+971">
                                        </div>
                                    </div>
                                    <div class="col-md-10">
                                        <div class="form-group">
                                            <label>Customer contact number without country code&nbsp;<span class="text-danger required">*</span></label>
                                            <input type="number"  id="lead_contact" class="form-control selected_field" required="" pattern="[5|6][0-9]{8}" name="lead_contact">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Customer Email Address</label>
                                            <input type="email" id="email"  class="form-control selected_field" name="lead_email">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Remarks(in English/Arabic)&nbsp;<span class="text-danger required">*</span></label>
                                            <textarea rows="7" class="form-control" required="" name="lead_remarks" required></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="">Subtotal</label>
                                            <input type="number" name="amount_payment" id="amount_payment" class="form-control" placeholder="Subtotal Amount" readonly aria-describedby="helpId">
                                        </div>
                                    </div>
                                    <div class="col-md-6 card-amount">
                                        <div class="form-group">
                                            <label for="">Card Amount</label>
                                            <input type="number" name="card_amount" id="card_amount" class="form-control" placeholder="Card Amount" readonly aria-describedby="helpId">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="">Payment Type</label><br>
                                            <div class="form-check form-check-inline">
                                                <label class="form-check-label">
                                                    <input class="form-check-input" type="radio" name="payment_type" id="" value="online" checked>Payment Link
                                                </label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <label class="form-check-label">
                                                    <input class="form-check-input" type="radio" name="payment_type" id="" value="cash" onclick="show_popup()">Pay by Cash
                                                </label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <label class="form-check-label">
                                                    <input class="form-check-input" type="radio" name="payment_type" id="" value="card">Pay by Card
                                                </label>
                                            </div>
                                            <div class="helper-text payment-desc mt-2 d-none">
                                                <small>Payment Receipt will be sent to the customer</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group online-approval">
                                            <label>Email Body&nbsp;<span class="text-danger required">*</span></label>
                                            <textarea rows="5" class="form-control ckeditor" name="email_message" id="email_editor2"></textarea>
                                        </div>
                                    </div>
                                    <!-- <div class="col-md-12">
                                        <div class="payment-approval form-group d-none">
                                            <label>Approval Code&nbsp;<span class="text-danger required">*</span></label>
                                            <input type="text" class="form-control" placeholder="Card Payment Approval Code" name="approval_code">
                                        </div>
                                    </div> -->
                                    <div class="col-md-12">
                                        <div class="form-group text-right">
                                            <input type="hidden" name="lead_created_by" value="<?php echo $this->auth_user_id; ?>">
                                            <input type="submit" class="btn btn-lg btn-primary btn-square p-3 pl-5 pr-5 createLead" name="submitForm" value="CREATE"/>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <script src="/assets_new/richtext/jquery.richtext.js"></script>
                <link rel="stylesheet" href="/assets_new/richtext/richtext.min.css">
                <script src="<?php echo base_url(); ?>global/node_modules/select2/dist/js/select2.min.js"></script>
                <!-- <script src="/public/assets/js/jquery-ui.min.js"></script> -->
                <link rel="stylesheet" href="<?php echo base_url(); ?>global/node_modules/select2/dist/css/select2.min.css">
                <link rel="stylesheet" href="/global/bs-datepicker/css/bootstrap-datepicker.min.css">
                <script src="/global/bs-datepicker/js/bootstrap-datepicker.min.js"></script>
                <script>

                var is_payment_type = '';
                var is_branch = '';
                $("#branch_id").change(function() {
                        var biz = [6, 13, 14, 20, 21];
                        var attest = [103];
                        var val = parseInt($(this).val());
                        is_branch = val;
                        console.log('is_branch1= '+is_branch);
                        
                        $(".serices-content").html("");
                        $('[name="package_id"] option').addClass("d-none");
                        console.log($('[name="package_id"] option[data-branch="' + val + '"]'));
                        $('[name="package_id"] option[data-branch="' + val + '"]').removeClass("d-none");
                        $("#package_id").val("").trigger("change");

                        if (attest.indexOf(val) != -1) {
                            swal.fire({
                                icon: "info",
                                text: "Redirecting to Attestation Leads Page",
                                didOpen: function() {
                                    swal.enableLoading();
                                    setTimeout(() => {
                                        location.href = "/leads/lead/attestationnew";
                                    }, 1000);
                                }
                            })
                        }
                });
                console.log('is_branch2= '+is_branch);
                
                $('.package-option').hide();

                        $('#payment_type2').change(function() {
                            $('#package_id').val('');
                            get_dataset("");
                            console.log('is_branch3= '+is_branch);
                            var selectedPaymentType = $(this).val();
                            if (selectedPaymentType !== '') {
                                $("[name='payment_type'][value='" + selectedPaymentType + "']").prop("checked", true);
                                payment_typeCheckList(selectedPaymentType);
                                is_payment_type = selectedPaymentType;
                                $('.package-option').hide();
                                $('.package-option[data-payment-type="' + selectedPaymentType + '"]').show();


                                // Update the ajax URL with the new payment_type value
                                $('#service_addition').select2({
                                    placeholder: "Please Select the Service",
                                    ajax: {
                                        url: '<?php echo base_url()?>/leads/package/new_getPackageDetails?payment_type=' + is_payment_type + '&branch=' + is_branch,
                                        dataType: 'json'
                                    }
                                });

                            } else {
                                $('.package-option').show();
                            }
                        });

                  //  $(".ckeditor").richText();

                    var package_service = $("#service-row").html();
                    $("#service-row").remove();


                    function meeting_slot(service_id) {
                        var today = new Date(today);
                        var tom = today.setDate(today.getDate());
                        // var tom = today.setDate(today.getDate());
                        var today_date = new Date(tom);
                        $('[name="slot_date[' + service_id + ']"]').removeAttr("disabled");
                        $('[name="slot_date[' + service_id + ']"]').attr("data-id", service_id);
                        $.get("/admin/exceptiondate/dates", function(res) {
                            // console.log("Res==> ", res);
                            var dates = JSON.parse(res);

                            $('[name="slot_date[' + service_id + ']"]').datepicker({
                                format: "yyyy-mm-dd",
                                uiLibrary: 'bootstrap',
                                weekStart: 0,
                                autoclose: true,
                                // daysOfWeekDisabled: [6],
                                // daysOfWeekHighlighted: [6],
                                startDate: "<?php echo date('Y-m-d') ?>",
                                datesDisabled: dates,
                            });
                        });

                        $('[name="slot_date[' + service_id + ']"]').off();
                        $('[name="slot_date[' + service_id + ']"]').on("change", function() {
                            var s_id = $(this).data("id");
                            $("[name='slot[" + s_id + "]']").removeAttr("disabled");
                            // console.log("SlotDD==> ",$(this).val());
                            $.get("/admin/usertimeslot/slots?user_id=" + $("[name='slot_user_id[" + service_id + "]']").val() + "&day=" + $("[name='slot_date[" + service_id + "]']").val(), function(dd) {
                                console.log("DD==> ", dd);
                                var dus = JSON.parse(dd);
                                $("[name='slot[" + service_id + "]']").html("");
                                dus.forEach(function(i) {
                                    $("[name='slot[" + service_id + "]']").append("<option value='" + i.user_timeslot_slot_id + "'>" + i.timeslot_name + "</option>");
                                });
                            });
                        });

                    }




                    function amount_calc() {
                        var pay_type = $("[name='payment_type']:checked").val();
                        var total = 0;
                        var total_govt_fee = 0;
                        $('.service-row').each(function() {
                            var qty = parseInt($(this).find('[name="service_qty[]"]').val());
                            var govt_fee = parseFloat($(this).find('[name="govt_fee[]"]').val());
                            var typing_fee = parseFloat($(this).find('[name="typing_fee[]"]').val());
                            var subtotal = (govt_fee + typing_fee) * qty;
                            total = total + subtotal;
                            total_govt_fee = total_govt_fee + (govt_fee * qty);
                            $(this).find('[name="sub_total[]"]').val(parseFloat(subtotal).toFixed(2));
                        });
                        $("#amount_payment").val(parseFloat(total).toFixed(2));
                        var card_per = 0;
                        //if (pay_type == "online") card_per = 0;

                        if($("#payment_type2").val()=='cash'){
                            card_per = 0;
                        }else if($("#payment_type2").val()=='online'){
                           card_per = 2.25;
                        }else{
                            card_per = 1; 
                        }
                        $("#card_amount").val(parseFloat(total_govt_fee * (card_per / 100)).toFixed(2));
                    }

                    function action_init() {
                        $(".action-btn").off();
                        $(".action-btn").on("click", function(i) {
                            var serv_name = $(this).closest(".service-row").find('[name="service_name[]"]').val();
                            swal.fire({
                                icon: "info",
                                title: "Are you sure to remove ?",
                                text: serv_name,
                                confirmButtonText: "Yes",
                                showCancelButton: true,
                                cancelButtonText: "Cancel"
                            }).then((val) => {
                                if (val.isConfirmed) {
                                    $(this).closest(".service-row").remove();
                                    amount_calc();

                                }
                                amount_calc();

                            });


                            amount_calc();
                        });

                        $("[name='service_qty[]']").off();
                        $("[name='service_qty[]']").on("focus", function(e) {
                            $(this).select();
                        });

                        $("[name='service_qty[]']").on("keyup keydown keypress", function(e) {
                            var qty = parseInt($(this).val());
                            // console.log("Qty ==> ",qty);
                            var govt_fee = parseFloat($(this).closest('.service-row').find('[name="govt_fee[]"]').val());
                            var typing_fee = parseFloat($(this).closest('.service-row').find('[name="typing_fee[]"]').val());
                            var total = (govt_fee + typing_fee) * qty;

                            $(this).closest('.service-row').find('[name="sub_total[]"]').val(total);
                            amount_calc();
                        });
                        amount_calc();
                    }

                    //$("#package_id").change(function(e) {
                        function get_dataset(package_id){
                        //e.preventDefault();
                        // var amount = $("#package_id option:selected").data("amount");
                        // $('[name="amount_payment"]').val(amount);
                        //var package_id = $(this).val();
                        if (package_id == "" || package_id == null) {
                            $("#services").addClass("d-none")
                            $(".serices-content").html("");
                            return;
                        }
                        $.ajax({
                            url: "<?php echo base_url();?>/leads/package/getPackageDetails?package_id=" + package_id,
                            beforeSend: function() {
                                $("#package_id").attr('disabled', 'disabled');
                            },
                            success: function(data) {
                                $("#package_id").removeAttr('disabled');
                                var package = JSON.parse(data);
                                // console.log(package);

                                if (package.data.length > 0) {
                                    $(".serices-content").html("");
                                    package.data.forEach(function(i) {
                                        $(".serices-content").append("<div class='row m-0 service-row'>" + package_service + "</div>");
                                        var qty = 1;
                                        var govt_fee = parseFloat(i.govt_fee);
                                        var typing_fee = parseFloat(i.typing_fee);
                                        var total = govt_fee + typing_fee;
                                        // console.log("Total ==> "+total);
                                        $('.service-row:nth-last-child(1) [name="service_id[]"]').val(i.service_id);
                                        $('.service-row:nth-last-child(1) [name="is_meeting_contain[]"]').val(i.is_meeting_contain);
                                        $('.service-row:nth-last-child(1) [name="service_name[]"]').val(i.service_name);
                                        $('.service-row:nth-last-child(1) [name="service_qty[]"]').val(qty);
                                        $('.service-row:nth-last-child(1) [name="govt_fee[]"]').val(govt_fee);
                                        $('.service-row:nth-last-child(1) [name="typing_fee[]"]').val(typing_fee);
                                        $('.service-row:nth-last-child(1) [name="sub_total[]"]').val(total);
                                        $('.service-row:nth-last-child(1) [name="is_direct_invoice[]"]').val(i.is_direct_invoice);
                                        $('.service-row:nth-last-child(1) [name="msd_key[]"]').val(i.msd_key);
                                        $('.service-row:nth-last-child(1) [name="is_pos_typing_fee[]"]').val(i.is_pos_typing_fee);
                                        if (i.is_meeting_contain == 1) {
                                            $('.service-row:nth-last-child(1) .meeting-user').removeClass("d-none");
                                            $('.service-row:nth-last-child(1) .meeting-user [name="slot_user_id[]"]').attr("name", "slot_user_id[" + i.service_id + "]");
                                            $('.service-row:nth-last-child(1) .meeting-user #slot_user_id').attr("data-id", i.service_id);
                                            $('.service-row:nth-last-child(1) .meeting-user [name="slot_date[]"]').attr("name", "slot_date[" + i.service_id + "]");
                                            $('.service-row:nth-last-child(1) .meeting-user [name="slot[]"]').attr("name", "slot[" + i.service_id + "]");

                                            $(".slot_user_id").off();
                                            $(".slot_user_id").on("change", function(e) {
                                                e.preventDefault();
                                                var d = $(this).data("id");
                                                console.log("DD => ", d);
                                                meeting_slot(d);
                                            });
                                        } else {
                                            $('.service-row:nth-last-child(1) .meeting-user').remove();
                                        }
                                    });
                                    action_init();
                                    $("#services").removeClass("d-none")
                                    amount_calc();
                                }
                            }
                        })
                    };

                    

                    $("#service_addition").change(function() {
                        var service_id = $(this).val();
                        if (service_id == "" || service_id == null) return false;
                        $.ajax({
                            url: "<?php echo base_url();?>/leads/package/getPackageDetail?service_id=" + service_id,
                            beforeSend: function() {
                                Swal.fire({
                                    allowOutsideClick: false,
                                    allowEscapeKey: false,
                                    didOpen: function() {
                                        swal.enableLoading();
                                    }
                                });
                            },
                            success: function(data) {
                                Swal.close();
                                var data = JSON.parse(data);

                                if (data != "false") {
                                    // $(".serices-content").html("");
                                    $(".serices-content").append("<div class='row m-0 service-row'>" + package_service + "</div>");
                                    var qty = 1;
                                    var govt_fee = parseFloat(data.govt_fee);
                                    var typing_fee = parseFloat(data.typing_fee);
                                    var total = govt_fee + typing_fee;
                                    // console.log("Total ==> "+total);
                                    $('.service-row:nth-last-child(1) [name="service_id[]"]').val(data.service_id);
                                    $('.service-row:nth-last-child(1) [name="service_name[]"]').val(data.service_name);
                                    $('.service-row:nth-last-child(1) [name="service_qty[]"]').val(qty);
                                    $('.service-row:nth-last-child(1) [name="govt_fee[]"]').val(govt_fee);
                                    $('.service-row:nth-last-child(1) [name="typing_fee[]"]').val(typing_fee);
                                    $('.service-row:nth-last-child(1) [name="sub_total[]"]').val(total);
                                    $('.service-row:nth-last-child(1) [name="is_direct_invoice[]"]').val(data.is_direct_invoice);
                                    $('.service-row:nth-last-child(1) [name="msd_key[]"]').val(data.msd_key);
                                    $('.service-row:nth-last-child(1) [name="is_pos_typing_fee[]"]').val(data.is_pos_typing_fee);
                                    if (data.is_meeting_contain == 1) {
                                        $('.service-row:nth-last-child(1) .meeting-user').removeClass("d-none");
                                        $('.service-row:nth-last-child(1) .meeting-user [name="slot_user_id[]"]').attr("name", "slot_user_id[" + data.service_id + "]");
                                        $('.service-row:nth-last-child(1) .meeting-user #slot_user_id').attr("data-id", data.service_id);
                                        $('.service-row:nth-last-child(1) .meeting-user [name="slot_date[]"]').attr("name", "slot_date[" + data.service_id + "]");
                                        $('.service-row:nth-last-child(1) .meeting-user [name="slot[]"]').attr("name", "slot[" + data.service_id + "]");

                                        $(".slot_user_id").off();
                                        $(".slot_user_id").on("change", function(e) {
                                            e.preventDefault();
                                            var d = $(this).data("id");
                                            console.log("DD => ", d);
                                            meeting_slot(d);
                                        });
                                    } else {
                                        $('.service-row:nth-last-child(1) .meeting-user').remove();
                                    }
                                    action_init();
                                    $("#services").removeClass("d-none")
                                    amount_calc();
                                }
                                $("#service_addition").val("").trigger("change");
                            }
                        })
                    });



                    function payment_typeCheckList(val){
                        if (val == "online") {
                            $(".online-approval").removeClass("d-none");
                            $(".payment-approval").addClass("d-none");
                            $(".payment-desc").addClass("d-none");
                            $(".card-amount").removeClass("d-none");
                        }
                        if (val == "card") {
                            $(".online-approval").addClass("d-none");
                            $(".payment-approval").removeClass("d-none");
                            $(".payment-desc").removeClass("d-none");
                            $(".card-amount").removeClass("d-none");
                        }
                        if (val == "cash") {
                            show_popup();
                            $(".online-approval").addClass("d-none");
                            $(".payment-approval").addClass("d-none");
                            $(".payment-desc").removeClass("d-none");
                            $(".card-amount").addClass("d-none");
                        }
                    }

                    $('input[type="radio"][name="payment_type"]').prop('disabled', true);


                    $("[name='payment_type']").change(function() {
                        var val = $(this).val();
                        payment_typeCheckList(val);
                    });

                    function normal_lead() {
                        $('#category_block').show();
                        $('#service_block').show();
                        $('#package_block').hide();
                    }

                    function package_lead() {
                        var branch = $("#branch_id").val();
                        if (branch == "") {
                            swal.fire({
                                icon: "info",
                                text: "Please Select the Branch"
                            });
                            $("#normal").prop("checked", "true");
                            normal_lead();
                            return;
                        } else if (branch != 106) {
                            swal.fire({
                                icon: "info",
                                text: "Selected Branch have no package"
                            });
                            $("#normal").prop("checked", "true");
                            normal_lead();
                            return;
                        }
                        $('#category_block').hide();
                        $('#service_block').hide();
                        $('#package_block').show();
                    }

                    $('[name="package_id"] option').addClass("d-none");

                    

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
                                        if (result[i]['service_code'] == "" || result[i]['service_code'] == null) {
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
                                // console.log(err);
                            }
                        });
                    }

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


                    $("#category_id").change(function(e) {
                        if ($(this).val() == 125) {
                            swal.fire({
                                icon: "info",
                                text: "Redirecting to Attestation Leads Page",
                                didOpen: function() {
                                    swal.enableLoading();
                                    setTimeout(() => {
                                        location.href = "/leads/lead/attestationnew";
                                    }, 2000);
                                }
                            })
                        }
                    })
                </script>
            </div>
        </div>
    </div>
</div>
<!-- leads customer name search details-->
<div class="modal fade" id="data_search" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title details-title">Customers</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body table-responsive" id="searchDTtable_container">
                <table class="table table-striped table-hoverable table-hover w-100" id="searchDTtable"></table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalOTP" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="modelTitleId" >
    <div class="modal-dialog" >
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title details-title"> OTP Verification</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body table-responsive">
            <div class="text-center font-weight-bold my-1 text-success">OTP is sent successfully</div>
                <div class="form-group">
                    <div class="otp-container">
                        <input type="text" class="otp-input" maxlength="1" id="otp1">
                        <input type="text" class="otp-input" maxlength="1" id="otp2">
                        <input type="text" class="otp-input" maxlength="1" id="otp3">
                        <input type="text" class="otp-input" maxlength="1" id="otp4">
                        <input type="text" class="otp-input" maxlength="1" id="otp5">
                        <input type="text" class="otp-input" maxlength="1" id="otp6">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="submitOTP">Submit</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo base_url(); ?>global/node_modules/select2/dist/js/select2.min.js"></script>
<link rel="stylesheet" href="<?php echo base_url(); ?>global/node_modules/select2/dist/css/select2.min.css">
<script src="<?php echo base_url(); ?>global/vendor/datatables/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
<script src="<?php echo base_url(); ?>global/js/leads/search-data.js"></script>
<script>
    $("#branch_id").select2();

    $(document).on('select2:open', () => {
        document.querySelector('.select2-search__field').focus();
    });

    $(document).ready(function() {
        $("#createLeadForm").submit(function(e){
            let paymentType = $('#createLeadForm #payment_type2').val();
            let country_code = $('#createLeadForm').find("input[name=lead_country_code]").val();
            let type = country_code == '+971'?'mobile':'email';
            let customer_mobile = $('#createLeadForm').find("#lead_contact").val();
            let customer_email = $('#createLeadForm').find("#email").val();
            let customer_name = $('#createLeadForm').find("#lead_name").val();
            let customer_id = '';
            let user_id = $('#createLeadForm').find("input[name=user_pos_id]").val();
            let user_email = $('#createLeadForm').find("input[name=user_email]").val();
           
            if(paymentType == 'cash'){
                e.preventDefault();
                $.ajax({
                    "url": "https://ontimesmartpos.net/api/ApiPos/CrmPaymentOtpVerfication",
                    "method": "POST",
                    beforeSend: function() {
                        swal.fire({
                            // text: "Sending OTP to customer Mobile or Email",
                            didOpen: function() {
                                swal.enableLoading();
                            }
                        })
                    },
                    "data": {
                        "type": type,
                        "customer_email": customer_email,
                        "customer_mobile": customer_mobile,
                        "customer_name": customer_name,
                        "customer_id": "0",
                        "user_id": user_id,
                        "user_email": user_email
                    },
                    success: function(response) {
                        let res = response;
                        if(res.ResponseCode == 0){
                            Swal.close();
                            $("#modalOTP").modal();
                        } else if(res.ResponseCode == 1) {
                            Swal.fire('Error!', res.ResponseMsg, 'error');
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.close();
                        Swal.fire('Error!', 'Something went wrong with the request.', 'error');
                    }
                });
            }
        })
    
        $('.otp-input').on('input', function () {
            var value = $(this).val();
            if (!/^\d$/.test(value)) {
                $(this).val('');
            }
            var currentVal = $(this).val();
            var nextInput = $(this).next('.otp-input');
            if (currentVal.length == 1 && nextInput.length) {
                nextInput.focus();
            }
            if (currentVal.length == 0) {
                var prevInput = $(this).prev('.otp-input');
                if (prevInput.length) {
                    prevInput.focus();
                }
            }
        });
    
        $('#submitOTP').click(function() {
            var otp = '';
            let customer_email =  $("#createLeadForm").find("#email").val();
            for (var i = 1; i <= 6; i++) {
                otp += $('#otp' + i).val();
            }
            if (otp.length === 6) {
                $("#createLeadForm input[name=customer_otp]").val(otp);
                    $('#modalOTP').modal('hide');
                    $.ajax({
                        "url": "https://ontimesmartpos.net/api/ApiPos/ValidateCrmOTP",
                        "method": "GET",
                        beforeSend: function() {
                            Swal.fire({
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });
                        },
                        "data": {
                            "OTP": otp,
                            "customer_email": customer_email,
                        },
                        success: function(response) {
                            let res = response;
                            if(res.ResponseCode == 0){
                                $('#modalOTP').modal('hide');
                                var newInput = $('<input>', {
                                    type: 'hidden',
                                    name: 'submitForm',
                                    value: 'CREATE'
                                });
                                $("#createLeadForm").append(newInput);
                                $("#createLeadForm")[0].submit();
                            } else if(res.ResponseCode == 1) {
                                $('.otp-input').val('');
                                Swal.fire('Error!', res.ResponseMsg, 'error');
                            }
                        },
                        error: function(xhr, status, error) {
                            Swal.close();
                            Swal.fire('Error!', 'Something went wrong with the request.', 'error');
                        }
                    });
            } else {
                Swal.fire('Error!', 'Please enter the full OTP.' + otp, 'error');
            }
        });

        $('.otp-input').on('paste', function(e) {
            var pastedData = e.originalEvent.clipboardData.getData('text');
            if (!/^\d{6}$/.test(pastedData)) {
                Swal.fire('Error!',"Enter Valid 6-digit OTP.<br>'"+pastedData+"' is not allowed.", 'error');
                return;
            }
            var inputs = $('.otp-input');

            $.each(pastedData.split(''), function(index, value) {
                if (index < inputs.length) {
                    $(inputs[index]).val(value).focus();
                }
            });
        });
    })
</script>
<script type="text/javascript">
    function show_popup() {
        Swal.fire({
            title: 'Please confirm you have received cash to proceed.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#DCDCDC',
            confirmButtonText: 'OK'
        })
    }
</script>