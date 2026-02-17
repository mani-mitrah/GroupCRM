<div class="app-main">
    <div class="app-main__outer">
        <div class="app-main__inner p-0">
            <div class="app-page-title">
                <div class="container fiori-container">
                    <div class="page-title-wrapper">
                        <div class="page-title-heading">
                            <div>
                                New Lead
                                <div class="page-title-subheading">Creation and Assign on the Lead</div>
                            </div>
                        </div>
                        <div class="page-title-actions">
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
                            <form action="<?php echo base_url(); ?>leads/lead/new" method="post" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Select Branch &nbsp;<span class="text-danger required">*</span></label>
                                            <select class="form-control" name="branch_id" id="branch_id">
                                                <option value="">-- Select Branch --</option>
                                                <?php
                                                foreach ($branches as $key => $value) {
                                                ?>
                                                    <option value="<?php echo $value['branch_code']; ?>">
                                                        <?php echo $value['branch_name']; ?></option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="lead_type" id="normal" value="normal" onclick="javascript:normal_lead();" checked="">
                                                <label class="form-check-label" for="normal">Normal Lead</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="lead_type" id="package" value="package" onclick="javascript:package_lead();">
                                                <label class="form-check-label" for="package">Package Lead</label>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-md-12" id="category_block">
                                        <div class="form-group">
                                            <?php //print_r($categories);
                                            ?>
                                            <label>Select Category &nbsp;<span class="text-danger required">*</span></label>
                                            <select class="form-control" name="category_id" id="category_id" onchange="javascript:select_services(this.value);">
                                                <option value="">-- Select Category --</option>
                                                <?php
                                                foreach ($categories as $key => $value) {
                                                    // print_r($value);
                                                ?>
                                                    <option value="<?php echo $value['category_id']; ?>">
                                                        <?php if ($value["category_code"] == NULL) {
                                                            echo $value['category_name'];
                                                        } else echo $value['category_code']; ?></option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12" id="service_block">
                                        <div class="form-group">
                                            <label>Select Service &nbsp;<span class="text-danger required">*</span></label>
                                            <select class="form-control" name="service_id" id="service_id">
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12" id="package_block">
                                        <div class="form-group">
                                            <label>Select Package &nbsp;<span class="text-danger required">*</span></label>
                                            <select class="form-control" name="package_id" id="package_id">
                                                <option value="">-- Select Package --</option>
                                                <?php
                                                foreach ($packages as $key => $value) {
                                                ?>
                                                    <option value="<?php echo $value['package_id']; ?>">
                                                        <?php echo $value['package_name']; ?></option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Lead Name&nbsp;<span class="text-danger required">*</span></label>
                                            <input type="text" class="form-control" required="" name="lead_name" autofocus>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Country Code&nbsp;<span class="text-danger required">*</span></label>
                                            <input type="text" class="form-control" required="" name="lead_country_code" value="+971">
                                        </div>
                                    </div>
                                    <div class="col-md-10">
                                        <div class="form-group">
                                            <label>Lead contact number without country code&nbsp;<span class="text-danger required">*</span></label>
                                            <input type="number" class="form-control" required="" name="lead_contact">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Lead Email Address</label>
                                            <input type="email" class="form-control" name="lead_email">
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Directly Assign to:(Group) </label>
                                            <select name="assign_group" class="form-control single-select">
                                                <option value="">-- Select Group--</option>
                                                <?php
                                                // print_r($lead_details);
                                                $lead_users = $this->user_model->get_lead_category_groups();
                                                // print_r($lead_users);
                                                log_message('error', $this->db->last_query());
                                                foreach ($lead_users as $keys => $values) {
                                                ?>
                                                    <option value="<?php echo str_replace(' ', '', $values["group_s"]); ?>">
                                                        <?php
                                                        echo $values["group_s"];
                                                        ?>
                                                    </option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>


                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Directly Assign to: </label>
                                            <select name="assign_to" class="form-control single-select">
                                                <option value="">-- Select --</option>
                                                <?php
                                                // print_r($lead_details);
                                                $lead_users = $this->user_model->get_lead_category_users($lead_details['category_id']);
                                                // print_r($lead_users);
                                                log_message('error', $this->db->last_query());
                                                foreach ($lead_users as $keys => $values) {
                                                ?>
                                                    <option value="<?php echo $values['user_id']; ?>" data-filter="<?php echo str_replace(',', '', $values["group_s"]); ?>">
                                                        <?php
                                                        $urole_id = $values['role_id'];
                                                        $user_role = ($urole_id == 2) ? 'CSA' : (($urole_id == 6) ? 'Cordinator' : 'Team Lead');
                                                        echo $values["first_name"] . ' ' . $values["last_name"] . "  (" . $user_role . ")";
                                                        ?>
                                                    </option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Remarks(in English/Arabic)&nbsp;<span class="text-danger required">*</span></label>
                                            <textarea rows="7" class="form-control" required="" name="lead_remarks"></textarea>
                                        </div>
                                    </div>
                                    <a id="attachment_area"></a>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Lead Attachments&nbsp;&nbsp;&nbsp;&nbsp; <button id="btnAddNewAttachment" class="btn btn-sm btn-primary">Add
                                                    new</button></label>
                                            <div class="" id="attachments">
                                                <!-- <div class="row mt-3">
                                <div class="col-md-6">
                                    <input type="text" class="form-control" required="" name="attachment_name[]" placeholder="Attachment Name" />
                                </div>
                                <div class="col-md-5">
                                    <input type="file" class="form-control" required="" name="files[]" placeholder="" />
                                </div>
                                <div class="col-md-1 float-right">
                                    <a href="#" class="text-danger close-div"><i class="fa fa-trash"></i></a>
                                </div>
                            </div> -->
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group text-right">
                                            <input type="hidden" name="lead_created_by" value="<?php echo $this->auth_user_id; ?>">
                                            <input type="submit" class="btn btn-lg btn-primary btn-square p-3 pl-5 pr-5" name="submit" value="CREATE LEAD" />
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
                        console.log("There=> ", val);
                        // alert(val);
                        // if (biz.indexOf(val) != -1) {

                        //     // location.href = "/leads/lead/biznew";
                        //     swal.fire({
                        //         icon: "info",
                        //         text: "Redirecting to Business Setup Leads Page",
                        //         didOpen: function() {
                        //             swal.enableLoading();
                        //             setTimeout(() => {
                        //                 location.href = "/leads/lead/biznew";
                        //             }, 1000);
                        //         }
                        //     })
                        // }

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
<script src="<?php echo base_url(); ?>global/node_modules/select2/dist/js/select2.min.js"></script>
<link rel="stylesheet" href="<?php echo base_url(); ?>global/node_modules/select2/dist/css/select2.min.css">
<script>
    $("#branch_id").select2();

    $(document).on('select2:open', () => {
        document.querySelector('.select2-search__field').focus();
    });
</script>