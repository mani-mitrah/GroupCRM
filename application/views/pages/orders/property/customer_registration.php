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
                                Customer Registration
                                <div class="page-title-subheading">For Property Registration</div>
                            </div>
                        </div>
                        <!-- <div class="page-title-actions">
                            <div class="d-inline-block dropdown">
                                <a href="/leads/lead/manage">
                                    <button type="button" class="btn btn-gradient-primary btn-shadow btn-wide">
                                        <span class="btn-icon-wrapper pr-1 opacity-7">
                                            <i class="fa fa-list"></i>
                                        </span>
                                        All Leads
                                    </button>
                                </a>
                            </div>
                        </div> -->
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
                            <?php } ?>
                            <form action="" method="post" enctype="multipart/form-data" id="customer_registration_form" autocomplete="off">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Customer Name&nbsp;<span class="text-danger required">*</span></label>
                                            <input type="text" class="form-control" id="Name" required="" name="customer_name" autocomplete="none">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Customer contact number&nbsp;<span class="text-danger required">*</span></label>
                                            <input type="number" class="form-control" id="Phone" required="" name="customer_mobile" autocomplete="none">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Customer Email Address<span class="text-danger required">*</span></label>
                                            <input type="email" class="form-control" id="Email" name="customer_email" autocomplete="none">
                                        </div>
                                    </div>
                                    <div class="col-md-12" id="service_block">
                                        <div class="form-group">
                                            <!-- <input type="hidden" name="service_name"> -->
                                            <label>Select Service &nbsp;<span class="text-danger required">*</span></label>
                                            <select class="form-control" name="service_id" id="service_id" required>
                                                <option value="" disabled selected>Please select or enter the Service</option>
                                                <?php
                                                foreach ($services as $service) {
                                                    echo "<option value=" . $service["id"] . ">" . $service["text"] . "</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Remarks(in English/Arabic)&nbsp;<span class="text-danger required">*</span></label>
                                            <textarea rows="5" class="form-control" required="" name="remarks"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group text-right">
                                            <input type="hidden" name="created_by" value="<?php echo $this->auth_user_id; ?>">
                                            <input type="submit" class="btn btn-lg btn-primary btn-square p-3 pl-5 pr-5" name="submit" value="TAKE TOKEN" />
                                        </div>
                                    </div>
                                </div>
                                <!-- <input type="hidden" name="eid_json" id="completejson"> -->
                                <input type="hidden" id="completejson">
                            </form>
                        </div>
                    </div>
                </div>
                <script src="<?php echo base_url(); ?>global/vendor/select2/js/select2.full.min.js"></script>

                <script type="text/javascript">
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

                    $("#customer_registration_form").submit(function(e) {
                        e.preventDefault();
                        $.ajax({
                            url: '/orders/property/new',
                            type: "POST",
                            data: $(this).serialize(),
                            beforeSend: function() {
                                console.log($(this).serialize());
                            },
                            success: function(data) {
                                // console.log("Success==> ", data);
                                var data = JSON.parse(data);
                                if (data.status == "true") {
                                    swal.fire({
                                        icon: "success",
                                        title: data.message
                                    }).then((value) => {
                                        if (value.isConfirmed) {
                                            location.reload();
                                        }
                                    });
                                } else {
                                    swal.fire({
                                        icon: "warning",
                                        title: data.message,
                                        text: data.status
                                    }).then((value) => {
                                        if (value.isConfirmed) {
                                            location.reload();
                                        }
                                    });
                                }
                            }
                        });
                    });

                    function read_eid(close = false) {
                        swal.fire({
                            imageUrl: "/global/images/ontime_digital.png",
                            imageHeight: "110px",
                            title: "Emirates ID is reading...",
                            allowOutsideClick: false,
                            showCloseButton: true,
                            didOpen: function() {
                                swal.showLoading()
                            }
                        });
                        var event = document.createEvent('Event');
                        console.log("Event Before", event);
                        event.initEvent('EID_EVENT');
                        console.log("Event Init");
                        document.dispatchEvent(event);
                        console.log("Event After", event);
                        // swal.disableLoading();
                        return true;
                    }
                    $("document").ready(function() {

                        $(".read_eid_button").click(function(e) {
                            e.preventDefault();
                            $("form")[0].reset();
                            $("form input").removeAttr("readonly");
                            read_eid(true);
                        });

                        var valid = setInterval(function() {
                            if ($("#Name").val() != "") {
                                swal.close();
                                clearInterval(valid);
                            }
                        }, 1000);

                        swal.fire({
                            imageUrl: "/global/images/ontime_digital.png",
                            imageHeight: "150px",
                            allowOutsideClick: false,
                            title: "Are you want to read from EID ?",
                            showConfirmButton: true,
                            confirmButtonText: "Yes, Read card",
                            cancelButtonText: "No, Cancel.",
                            showCancelButton: true
                        }).then((e) => {
                            if (e.isConfirmed) {
                                read_eid();
                            }
                        });
                        // disable enter key press
                        $(document).keypress(
                            function(event) {
                                if (event.which == '13') {
                                    event.preventDefault();
                                }
                            });
                    });
                </script>
            </div>
        </div>
    </div>
</div>