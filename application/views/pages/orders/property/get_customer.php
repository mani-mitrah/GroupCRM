<style>
    h2#swal2-title {
        margin-top: 15px;
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
                                Get Customer Information
                                <div class="page-title-subheading">For Property Registration</div>
                            </div>
                        </div>
                        <div class="page-title-actions">
                            <div class="d-inline-block dropdown btn btn-primary">
                                <h2 class="font-weight-bold mb-0"><?php echo $customer->token; ?></h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="fiori-container container">
                <div class="app-inner-layout chat-layout justify-content-center mt-5">
                    <div class="card">
                        <div class="card-body">
                            <?php if ($this->session->flashdata('alert')) { ?>
                                <div class="alert alert-<?php echo $this->session->flashdata('alert'); ?>">
                                    <?php echo $this->session->flashdata('alert_message'); ?>
                                </div>
                            <?php }
                            // print_r($customer);
                            if (!empty($customer)) {

                                $token_id = json_decode($customer->token_api);
                                $token_id = $token_id->Content->TokenId;
                                // print_r($token_id);
                            ?>

                                <form action="" method="post" enctype="multipart/form-data" id="customer_registration_form" autocomplete="off">
                                    <input type="hidden" name="token_id" value="<?php echo $token_id; ?>">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Customer Name&nbsp;<span class="text-danger required">*</span></label>
                                                <input readonly type="hidden" name="customer_id" value="<?php echo $customer->customer_id; ?>">
                                                <input readonly type="text" class="form-control" required="" value="<?php echo $customer->customer_name; ?>" autocomplete="none">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Customer contact number&nbsp;<span class="text-danger required">*</span></label>
                                                <input readonly type="number" class="form-control" required="" value="<?php echo $customer->customer_mobile; ?>" autocomplete="none">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Customer Email Address<span class="text-danger required">*</span></label>
                                                <input readonly type="email" class="form-control" value="<?php echo $customer->customer_email; ?>" autocomplete="none">
                                            </div>
                                        </div>
                                        <div class="col-md-12" id="service_block">
                                            <div class="form-group">
                                                <label>Selected Service &nbsp;<span class="text-danger required">*</span></label>
                                                <select class="form-control" name="service_id" id="service_id" required>
                                                    <option value="" disabled selected>Please select or enter the Service</option>
                                                    <?php
                                                    // print_r($customer);
                                                    // print_r($services);
                                                    foreach ($services as $service) {
                                                        $selected = ($service["id"] == $customer->service_id) ? "selected" : "";
                                                        $option =  "<option value=" . $service["id"] . " " . $selected . ">" . $service["text"] . "</option>";
                                                        echo $option;
                                                    }
                                                    ?>
                                                </select>
                                                <input readonly type="hidden" class="form-control" value="<?php echo $customer->service_name; ?>" name="service_name" id="service_name">
                                                <!-- <input readonly type="hidden" class="form-control" value="<?php echo $customer->service_id; ?>" id="service_id"> -->
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Receptionist Remarks(in English/Arabic)&nbsp;<span class="text-danger required">*</span></label>
                                                <textarea readonly rows="5" class="form-control" name="recep_remarks" required=""><?php echo $customer->remarks; ?></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Your Remarks(in English/Arabic)&nbsp;<span class="text-danger required">*</span></label>
                                                <textarea rows="5" class="form-control" name="csa_remarks" autofocus></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">Payment Mode</label>
                                                <select class="form-control" name="payment_type" id="payment_type" required>
                                                    <option value="">-- Select Payment Type --</option>
                                                    <option value="no_payment">No Payment</option>
                                                    <option value="cash">Cash</option>
                                                    <option value="card">Card</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Service Amount&nbsp;<span class="text-danger required">*</span></label>
                                                <input rows="5" type="number" class="form-control" value="0.00" step="0.01" name="service_amount" onfocus="this.select();" autofocus>
                                            </div>
                                        </div>
                                        <div class="col-12 file_group">
                                            <label for="">Attachment</label><br>
                                            <div id="file_row_0" class="file_row row">
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <input type="text" name="attachment_name" class="form-control" placeholder="Attachment Name" aria-describedby="helpId">
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="form-group">
                                                        <input type="file" name="attachment_file" class="form-control" aria-describedby="helpId">
                                                    </div>
                                                </div>
                                                <!-- <button class="btn btn-primary add-btn">+</button> -->
                                            </div>
                                        </div>
                                        <div class="col-md-12 mt-3">
                                            <div class="d-flex form-group justify-content-between text-right">
                                                <input type="hidden" name="created_by" value="<?php echo $this->auth_user_id; ?>">
                                                <span>
                                                    <input type="submit" class="btn btn-gradient-primary p-3 pl-5 pr-5" name="lead" value="Mark as Lead" />
                                                </span>
                                                <span>
                                                    <button type="submit" class="btn btn-outline-primary p-3 pl-5 pr-5" name="skip" value="Skip and Call Next" >Skip and Call Next <i class="fa fa-angle-double-right ml-2"></i></button>
                                                </span>
                                                <span>
                                                    <input type="submit" class="btn btn-gradient-primary btn-square p-3 pl-5 pr-5 shadow" name="sales" value="Sales Order" />
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            <?php
                            } else {
                                if ($message != NULL) {
                                    echo "<h2 class='text-center'>" . $message . "</h2>";
                                } else echo "<h2 class='text-center'>No Customers in the Queue</h2>";
                            }
                            ?>

                        </div>
                    </div>
                </div>
                <script src="<?php echo base_url(); ?>global/vendor/select2/js/select2.full.min.js"></script>

                <script type="text/javascript">
                    var file_row = $("#file_row_0").html();

                    $("[name='service_amount']").on("keyup keydown keypress", function() {
                        var val = $(this).val();
                        if (val == "" || val == 0 || val <= "0") {
                            $("#payment_type [value='no_payment']").removeAttr("disabled");
                        } else $("#payment_type [value='no_payment']").attr("disabled", "true");
                    });

                    function product_init() {
                        $(".file_row:nth-child(1) .add-btn").off();
                        $(".file_row:not(:nth-child(1)) .add-btn").off();
                        $(".file_row:nth-child(1) .add-btn").on("click", function(e) {
                            // alert("New");
                            e.preventDefault();
                            // $(this).html("+");
                            var len = ($(".file_row").length) + 1;
                            $(".file_group").append("<div class='row file_row' id='file_row_" + len + "'>" + file_row + "</div>");

                            product_init();

                            $(".file_row:not(:nth-child(1)) .add-btn").on("click", function(e) {
                                e.preventDefault();
                                // $(this).html("+");
                                // var len = $(".file_row").length;
                                $(".file_row:last-child").remove();
                                product_init();
                            });
                        });


                    }

                    product_init();

                    $("#service_id").select2({
                        placeholder: 'Select Category',
                        width: "100%",
                        theme: 'bootstrap4',
                        tags: true,
                    }).on('select2:close', function() {
                        var element = $(this);
                        // console.log(element);
                        // console.log($(this).text());
                        // console.log($.trim(element.text()));
                        var new_category = $.trim(element.val());
                        var new_category_name = $("#service_id option:selected").text();
                        $("#service_name").val(new_category_name);
                        if (new_category != '' && new_category_name != '') {
                            $.ajax({
                                url: "addService",
                                method: "POST",
                                data: {
                                    service_name: new_category_name
                                },
                                success: function(data) {
                                    // console.log(data);
                                    var data = JSON.parse(data);
                                    if (data.status == 'created') {
                                        console.log("Here Value");
                                        var studentSelect = $('#service_id');

                                        $('#service_id option[value="' + data.text + '"]').remove();
                                        var option = new Option(data.text, data.id, true, true);

                                        studentSelect.append(option).trigger('change');
                                    }
                                }
                            })
                        }

                    });

                    $("#service_id").change(function() {
                        $("#service_id option:selected").text();
                    });

                    // $("#customer_registration_form").submit(function(e) {
                    //     e.preventDefault();
                    //     $.ajax({
                    //         url: '/orders/property/new',
                    //         type: "POST",
                    //         data: $(this).serialize(),
                    //         beforeSend: function() {
                    //             console.log($(this).serialize());
                    //         },
                    //         success: function(data) {
                    //             // console.log("Success==> ", data);
                    //             var data = JSON.parse(data);
                    //             if (data.status == "true") {
                    //                 swal.fire({
                    //                     icon: "success",
                    //                     title: data.message
                    //                 }).then((value) => {
                    //                     if (value.isConfirmed) {
                    //                         location.reload();
                    //                     }
                    //                 });
                    //             } else {
                    //                 swal.fire({
                    //                     icon: "warning",
                    //                     title: data.message,
                    //                     text: data.status
                    //                 }).then((value) => {
                    //                     if (value.isConfirmed) {
                    //                         location.reload();
                    //                     }
                    //                 });
                    //             }
                    //         }
                    //     });
                    // });


                    // function add_file() {
                    //     $(".add-btn").remove();
                    //     $(".file_group").append(file_row);
                    //     $(".add-btn,.add-btn").off("click");
                    //     $(".add-btn,.add-btn").on("click", function(e) {
                    //         e.preventDefault();
                    //         add_file();
                    //     });
                    // }


                    // $(".add-btn,.add-btn").on("click", function(e) {
                    //     e.preventDefault();
                    //     $(".add-btn").remove();
                    //     var len = ($(".file_row").length) + 1;
                    //     $(".file_group").append("<div class='row file_row' id='file_row_" + len + "'>" +
                    //         file_row + "</div>");
                    //     product_init();
                    // });
                </script>
            </div>
        </div>
    </div>
</div>