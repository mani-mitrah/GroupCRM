<script src="<?php echo  base_url();?>global/js/jquery-3.6.0.min.js"></script>
<script src="<?php echo base_url(); ?>assets_new/node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
<div class="app-main">
    <div class="app-main__outer">
        <div class="app-main__inner p-0">
            <div class="app-page-title">
                <div class="container fiori-container">
                    <div class="page-title-wrapper">
                        <div class="page-title-heading">
                            <div>
                                Golden Cube - New Sale
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
                            <form action="<?php echo base_url(); ?>leads/lead/golden_cube_new" method="post" enctype="multipart/form-data">
                                <input type="hidden" name="branch_id" value="106">
                                <input type="hidden" name="lead_type" value="package">
                                <input type="hidden" name="assign_group" value="GoldenCube">
                                <input type="hidden" name="assign_to" value="<?php echo $this->auth_user_id; ?>">
                                <div class="row">
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
                                            <?php //echo "<pre>"; print_r($packages);?>
                                            <label>Select Package &nbsp;<span class="text-danger required">*</span></label>
                                            <select class="form-control" name="package_id" id="package_id" required autofocus onchange="get_dataset(this.value);">
                                                <option value="">-- Select Package --</option>
                                                <?php
                                                foreach ($packages as $key => $value) {
                                                ?>
                                                    <option data-amount="<?php echo $value["package_amount"]; ?>" data-payment-type="<?php echo $value["payment_type"]; ?>" value="<?php echo $value['package_id']; ?>" class="package-option">
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
                                                            <input type="hidden" name="is_direct_invoice[]">
                                                            <input type="hidden" name="msd_key[]">
                                                            <input type="hidden" name="is_pos_typing_fee[]">
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
                                            <label>Customer Name&nbsp;<span class="text-danger required">*</span></label>
                                            <input type="text" class="form-control" required="" name="lead_name" autofocus>
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
                                            <input type="number" class="form-control" required="" pattern="[5|6][0-9]{8}" name="lead_contact">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Customer Email Address</label>
                                            <input type="email" class="form-control" name="lead_email">
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
                                    <div class="col-md-12">
                                        <div class="payment-approval form-group d-none">
                                            <label>Approval Code&nbsp;<span class="text-danger required">*</span></label>
                                            <input type="text" class="form-control" placeholder="Card Payment Approval Code" name="approval_code">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-group  form-check-label" style="margin-left: 20px; margin-bottom: 20px;">
                                        <input type="checkbox" class="form-check-input acceptTerms" name="" id="acceptTerms" value="checkedValue">
                                        I hereby acknowledge that I have read and understood the <a href="#"
                                            class="font-weight-bold terms">terms and conditions</a> as provided by
                                        Goldencube, and I agree to all the terms.
                                        </label>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group text-right">
                                            <input type="hidden" name="lead_created_by" value="<?php echo $this->auth_user_id; ?>">
                                            <input type="submit" class="btn btn-lg btn-primary btn-square p-3 pl-5 pr-5 createLead" name="submit" value="CREATE" disabled/>
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
                        var is_branch = $("input[name='branch_id']").val();
                        $('.package-option').hide();

                        $('#payment_type2').change(function() {
                            $('#package_id').val('');
                            get_dataset("");
                            
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


                 

                    //$(".ckeditor").richText();

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
                                console.log("DD==> ",dd);
                                var dus = JSON.parse(dd);
                                $("[name='slot["+service_id+"]']").html("");
                                dus.forEach(function(i){
                                    $("[name='slot["+service_id+"]']").append("<option value='"+i.user_timeslot_slot_id+"'>"+i.timeslot_name+"</option>");
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
                   
                    //$("#package_id").change(get_dataset(this));

                    function get_dataset(package_id){
                        //console.log(e);
                        //e.preventDefault();
                        // var amount = $("#package_id option:selected").data("amount");
                        // $('[name="amount_payment"]').val(amount);
                        //var package_id = $(e).val();
                        if (package_id == "" || package_id == null) {
                            $("#services").addClass("d-none")
                            $(".serices-content").html("");
                            return;
                        }
                        $.ajax({
                            url: "<?php echo base_url()?>/leads/package/getPackageDetails?package_id=" + package_id,
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
                            url: "<?php echo base_url()?>/leads/package/getPackageDetail?service_id=" + service_id,
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

                    $("#branch_id").change(function() {
                        var biz = [6, 13, 14, 20, 21];
                        var attest = [103];
                        var val = parseInt($(this).val());
                        // console.log("There=> ", val);
                        // alert(val);
                        // if (biz.indexOf(val) != -1) {

                        // // location.href = "/leads/lead/biznew";
                        // swal.fire({
                        // icon: "info",
                        // text: "Redirecting to Business Setup Leads Page",
                        // didOpen: function() {
                        // swal.enableLoading();
                        // setTimeout(() => {
                        // location.href = "/leads/lead/biznew";
                        // }, 1000);
                        // }
                        // })
                        // }

                        if (attest.indexOf(val) != -1) {
                            swal.fire({
                                icon: "info",
                                text: "Redirecting to Attestation Leads Page",
                                didOpen: function() {
                                    swal.enableLoading();
                                    setTimeout(() => {
                                        location.href = "<?php echo base_url()?>/leads/lead/attestationnew";
                                    }, 1000);
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
                                        location.href = "<?php echo base_url()?>/leads/lead/attestationnew";
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
<script src="<?php echo base_url(); ?>global/node_modules/select2/dist/js/select2.min.js"></script>
<link rel="stylesheet" href="<?php echo base_url(); ?>global/node_modules/select2/dist/css/select2.min.css">
<script>
    $("#branch_id").select2();

    $(document).on('select2:open', () => {
        document.querySelector('.select2-search__field').focus();
    });

    $(".terms").click(function (e) {
      e.preventDefault();
      $("#modelId").modal();
    });

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
    // $(".createLead").hide();
    $(".acceptTerms").click(function() {
        if($(this).is(":checked")) {
            $(".createLead").attr('disabled', false);
        } else {
            $(".createLead").attr('disabled', true);
        }
    });

</script>


  <!-- Modal -->
  <div class="modal fade" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title w-100">
            <div class="w-100 d-flex justify-content-between">
              <div>Terms & Conditions</div>
              <div>الشروط والأحكام</div>
            </div>
          </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div style="direction: rtl; text-align: right">
            <p class="font-weight-bold text-underline">الشروط والأحكام:</p>
            <p class="mb-0">1. ‫في‬ ‫دبي‬ ‫بإمارة‬ ‫اإلقامة‬ ‫على‬ ‫للحصول‬ ‫التقديم‬ ‫بتعليمات‬ ‫التزم‬ ‫ان‬ ‫الطلب‬ ‫مقدم‬ ‫انا‬ ‫اقر‬
‫األراضي‬ ‫دائرة‬ ‫مركز‬ ‫لدى‬ ‫الطلب‬ ‫بهذا‬ ‫الموضحة‬ ‫و‬ ‫المتحدة‬ ‫العربية‬ ‫االمارات‬ ‫دولة‬
‫العقاريين‬ ‫المستثمرين‬ ‫خدمات‬ ‫لتقديم‬ ‫مرخص‬‫ال‬ ‫و‬ "‫"المركز‬ ‫أو‬ "‫كيوب‬ ‫"ذا‬ ‫واألمالك‬
.‫الصلة‬ ‫ذات‬ ‫والمعامالت‬ ‫ومرافقيهم‬</p>
            <p class="mb-0">2. ‫و‬ ‫التفاصيل‬ ‫بكافة‬ ‫المركز‬ ‫بتزويد‬ ‫التزم‬ ‫وان‬ ‫صحيح‬ ‫بشكل‬ ‫البيانات‬ ‫كافة‬ ‫بتقديم‬ ‫اتعهد‬
            .‫صحيح‬ ‫و‬ ‫كامل‬ ‫بشكل‬ ‫المطلوبة‬ ‫المستندات‬</p>
            <p class="mb-0">3. ‫اقر‬ ،‫للمركز‬ ‫مقبولة‬ ‫أخرى‬ ‫طريقة‬ ‫أي‬ ‫خالل‬ ‫من‬ ‫او‬ ‫الكتروني‬ ‫بطلب‬ ‫التقدم‬ ‫حالة‬ ‫في‬
‫المتحدة‬ ‫العربية‬ ‫االمارات‬ ‫دولة‬ ‫حدود‬ ‫داخل‬ ‫متواجد‬‫و‬ ‫العالقة‬ ‫و‬ ‫الطلب‬ ‫صاحب‬ ‫انا‬ ‫انني‬
‫المرافقين‬ ‫من‬ ‫أي‬ ‫مغادرة‬ ‫(او‬ ‫الدولة‬ ‫تي‬‫مغادر‬ ‫بعدم‬ ‫االلتزام‬‫ب‬ ‫واتعهد‬ ‫الطلب‬ ‫تقديم‬ ‫وقت‬
‫وطباعه‬ ‫اإلقامة‬ ‫طلب‬ ‫موافقه‬ ‫على‬ ‫الحصول‬ ‫حين‬ ‫إلى‬ )‫المرافقين‬ ‫طلبات‬ ‫حالة‬ ‫في‬
‫وغير‬ ‫للنزاع‬ ‫قابل‬ ‫غير‬ ‫و‬ ‫ملغي‬ ‫و‬ ‫مرفوض‬ ‫الطلب‬ ‫يعتبر‬ ‫وإال‬ ‫اإلمارتية‬ ‫الهوية‬ ‫طلب‬
‫البريد‬ ‫و‬ ‫المحلي‬ ‫الهاتف‬ ‫رقم‬ ‫ان‬ ‫واقر‬ ،‫حكومية‬ ‫او‬ ‫مهنية‬ ‫رسوم‬ ‫أية‬ ‫السترداد‬ ‫قابل‬
‫أي‬ ‫يانات‬‫ب‬ ‫ليس‬ ‫و‬ ‫الشخصية‬ ‫بياناتي‬ ‫عن‬ ‫يعبر‬ ‫الطلب‬ ‫مع‬ ‫مني‬ ‫المقدم‬ ‫االلكتروني‬
‫الجهات‬ ‫لموافقات‬ ‫سيخضع‬ ‫لإلنجاز‬ ‫الزمني‬ ‫اإلطار‬ ‫ان‬ ‫بعلمي‬ ‫واقر‬ ،‫آخر‬ ‫ثالث‬ ‫طرف‬
‫من‬ ‫أي‬ ‫على‬ ‫الطلب‬ ‫بهذا‬ ‫التعهدات‬ ‫و‬ ‫االقرارات‬ ‫كافة‬ ‫و‬ ‫الشرط‬ ‫هذا‬ ‫يسري‬ ‫و‬ ،‫المعنية‬
.‫مرافق‬ ‫عن‬ ‫نيابة‬ ‫بطلب‬ ‫التقدم‬ ‫حالة‬ ‫في‬ ‫المرافقين‬</p>
            <p>4. ‫عبر‬ ‫معي‬ ‫التواصل‬ ‫خالل‬ ‫من‬ ‫ذلك‬ ‫مني‬ ‫طلب‬ ‫ة‬‫حال‬ ‫في‬ ‫إال‬ ‫المركز‬ ‫زيارة‬ ‫بعدم‬ ‫اتعهد‬
            .‫لزم‬ ‫إذا‬ ‫هاتفي‬ ‫اتصال‬ ‫خالل‬ ‫من‬ ‫او‬ ‫به‬ ‫المركز‬ ‫بتزويد‬ ‫قمت‬ ‫الذي‬ ‫االلكتروني‬ ‫بالبريد‬</p>
            <p>5. .‫فقط‬ ‫اإلماراتي‬ ‫الدرهم‬ ‫الدولة‬ ‫وبعملة‬ ‫ا‬‫م‬ً ‫مقد‬ ‫كاملة‬ ‫الرسوم‬ ‫جميع‬ ‫بسداد‬ ‫اتعهد‬</p>
            <p>6. .‫افعالي‬ ‫او‬ ‫تأخيري‬ ‫عن‬ ‫الناتجة‬ ‫الغرامات‬ ‫كافة‬ ‫بسداد‬ ‫اتعهد‬</p>
            <p>7. ‫لدى‬ ‫بالفعل‬ ‫المقدمة‬ ‫الخدمات‬ ‫عن‬ ‫الرسوم‬ ‫استرداد‬ ‫يمكن‬ ‫ال‬ ‫انه‬ ‫علم‬ ‫على‬ ‫انني‬ ‫اقر‬
‫و‬ ‫بعد‬ ‫تقديمها‬ ‫يتم‬ ‫لم‬ ‫التي‬ ‫الخدمات‬ ‫عن‬ ‫فقط‬ ‫يكون‬ ‫استرداد‬ ‫أي‬ ‫وان‬ ‫الحكومية‬ ‫الجهات‬
.‫فقط‬ ‫الطلب‬ ‫لهذا‬ ‫تقديمي‬ ‫تاريخ‬ ‫من‬ 90 ‫غضون‬ ‫في‬ ‫ذلك‬</p>
            <p class="mb-0">8. ‫الحكومية‬ ‫الرسوم‬ ‫او‬ ‫المهنية‬ ‫الخدمة‬ ‫برسوم‬ ‫بالمطالبة‬ ‫لي‬ ‫يحق‬ ‫ال‬ ‫انه‬ ‫علم‬ ‫على‬ ‫انني‬ ‫اقر‬
            .‫الحكومية‬ ‫الجهات‬ ‫قبل‬ ‫من‬ ‫المرفوضة‬ ‫للمعامالت‬</p>
            <p class="mb-0">9. ‫المقدمة‬ ‫للمعامالت‬ ‫إال‬ ‫مهنية‬ ‫رسوم‬ ‫او‬ ‫حكومية‬ ‫رسوم‬ ‫اية‬ ‫برد‬ ‫المطالبة‬ ‫بعدم‬ ‫اتعهد‬
            ."‫كيوب‬ ‫"مركز‬ ‫المركز‬ ‫خالل‬ ‫من‬</p>
            <p class="mb-0">10. ‫المختصة‬ ‫الجهة‬ ‫هي‬ ‫واألمالك‬ ‫األراضي‬ ‫دائرة‬ ‫لجنة‬ ‫تكون‬ ‫ان‬ ‫عن‬ ‫بموافقتي‬ ‫اقر‬
‫االقرارات‬ ‫هذه‬ ‫على‬ ‫موافقتي‬ ‫تعتبر‬ ‫و‬ ‫المركز‬ ‫وبين‬ ‫بيني‬ ‫تنشأ‬ ‫نزاعات‬ ‫اية‬ ‫في‬ ‫بالنظر‬
‫رجعة‬ ‫ال‬ ‫لي‬ ‫ملزم‬ ‫عقد‬ ‫بمثابة‬ ‫الخدمات‬ ‫نظير‬ ‫المطلوب‬ ‫للمبلغ‬ ‫سدادي‬ ‫و‬ ‫التعهدات‬ ‫و‬
.‫فيه‬</p>
          </div>
          <hr class="mt-5 mb-5">
          <div class="mt-2">
            <p class="font-weight-bold text-underline">Terms and Conditions:</p>
            <p>1. I, the applicant, undertake and declare that I shall abide by the instructions for applying for residence in the Emirate of Dubai
            in the United Arab Emirates, as indicated in this application, at the Dubai Land Department “The Cube” Center, a licensed Center to provide services to real estate investors and their dependants and related transactions.</p>
            <p>2. I undertake to provide the Center with all the required details
            and documents completely and accurately.</p>
            <p>3. In case of application electronically, or via any other acceptable means for the Center, I declare that I am the applicant(or on
                behalf of my dependents), and the local number and e-mail address provided are my personal details and not for any
                another third-party and I am physically present in the UAE and I undertake to commit not to leave the country either myself (or
                my dependents if the application is for dependents) until the issuance of the residency approval & printing the Emirates ID,
                and I acknowledge that the time frame for completion will be subject to the authorities approvals, and the application will be
                considered rejected, cancelled, non-refundable, undisputable in the event of non-compliance with this condition.</p>
            <p>4. I undertake not to visit the Center unless it is required by
                communicating with me via the e-mail that I provided the
                Center with, or through or a telephone call, if necessary.</p>
            <p>5. I undertake to pay upfront the complete professional and
                government fees due for my transaction solely in UAE Dirhams.</p>
            <p>6. I undertake to pay all fines and penalties resulting from my actions.</p>
            <p>7. I declare that I am aware that professional and government
                fees cannot be refunded for services already submitted, and
                that any refund is within 90 days only for services that have
                not yet been submitted.</p>
            <p>8. I declare that I have no right to seek refund for professional
                fees or government fees concerning services refused by the
                respective government authority.</p>
            <p>9. I declare that I have no right to seek refund for services not processed through Cube Center.</p>
            <p>10. I declare my acceptance for Dubai Land Department
                Committee to be the sole jurisdiction for disputes, and that my
                consent for these undertakings and declarations are legally
                binding irreversible commitment.</p>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>