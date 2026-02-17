<?php
$groups = get_groups($this->auth_user_id);
?>
<style>
    .selected_row {cursor: pointer !important;}
</style>
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
                                <?php if (in_array(77, $groups)) { ?>
                                <a href="<?php echo base_url(); ?>leads/lead/attestationnew">
                                    <button type="button" class="btn btn-outline-primary btn-shadow btn-wide">
                                        <span class="btn-icon-wrapper pr-1 opacity-7">
                                            <i class="fa fa-plus"></i>
                                        </span>
                                        Create Attestation Lead
                                    </button>
                                </a>
                                <?php } ?>
                                <a href="<?php echo base_url();?>leads/lead/biznew">
                                <!-- <a href="https://forms.zohopublic.com/ontimebusinesssetup1/form/LeadGenerationGovernmentServices/formperma/oSsJQQeK-RvytjgubMs-LRRgfPlnb_bmTrrojx8iXpY" target="_blank"> -->
                                    <button type="button" class="btn btn-outline-primary btn-shadow btn-wide">
                                        <span class="btn-icon-wrapper pr-1 opacity-7">
                                            <i class="fa fa-plus"></i>
                                        </span>
                                        Create Biz Lead
                                    </button>
                                </a>
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
                        $(document).ready(function () {
                            $('#category_block').show();
                            $('#service_block').show();
                            $('#package_block').hide();

                            $("#assign_type").change(function(e){
                                var assign_type = $(this).val();
                                if(assign_type == "self"){
                                    $("[name='assign_group']").attr("disabled","true");
                                    $("[name='assign_group']").attr("readonly","true");
                                    $("[name='assign_to']").attr("disabled","true");
                                    $("[name='assign_to']").attr("readonly","true");
                                } else if(assign_type == "unassigned") {
                                    $("[name='assign_group']").removeAttr("disabled");
                                    $("[name='assign_group']").removeAttr("readonly");
                                    $("[name='assign_to']").attr("disabled","true");
                                    $("[name='assign_to']").attr("readonly","true");
                                } else {
                                    $("[name='assign_group']").removeAttr("disabled");
                                    $("[name='assign_group']").removeAttr("readonly");
                                    $("[name='assign_to']").removeAttr("disabled");
                                    $("[name='assign_to']").removeAttr("readonly");
                                }
                            });
                            //add new
                            // $('#btnAddNewAttachment').click(function (e) {
                            //     e.preventDefault();
                            //     var newDiv = $(
                            //         '<div class="row mt-3"><div class="col-md-6"><input type="text" class="form-control" required="" name="attachment_name[]" placeholder="Attachment Name" /></div><div class="col-md-5"><input type="file" class="form-control" required="" name="files[]" placeholder="" /></div><div class="col-md-1 float-right"><a href="#" class="text-danger close-div"><i class="fa fa-trash"></i></a></div></div>'
                            //     );
                            //     $('body').animate({
                            //         scrollTop: eval($('#attachment_area').offset().top - 70)
                            //     }, 1000);
                            //     $('#attachments').append(newDiv);

                            //     $('.close-div').click(function (e) {
                            //         e.preventDefault();
                            //         $(this).parent().parent().remove();
                            //         $('body').animate({
                            //             scrollTop: eval($('#attachment_area').offset()
                            //                 .top -
                            //                 70)
                            //         }, 1000);
                            //     });
                            // });
                             $(document).ready(function() {
                                $('input').keydown(function(e) {
                                    if (e.key === 'Enter') {
                                        e.preventDefault();
                                    }
                                });
                                $('#btnAddNewAttachment').keydown(function(e) {
                                    if (e.key === 'Enter') {
                                        e.preventDefault();
                                    }
                                });
                                $('#attachments').on('keypress', 'input[name="attachment_name[]"]', function(e) {
                                    if (e.key === 'Enter') {
                                        e.preventDefault();
                                    }
                                });
                                let attachmentIndex = 0;

                                $('#btnAddNewAttachment').click(function(e) {
                                    e.preventDefault();

                                    var newDiv = $(
                                        '<div class="row mt-3">' +
                                        '<div class="col-md-6"><input type="text" class="form-control" required="" name="attachment_name[]" placeholder="Attachment Name" /></div>' +
                                        '<div class="col-md-5"><input type="file" class="form-control attachment-file" required="" multiple  name="attachments_lead[' + attachmentIndex + '][]" placeholder="" />' + '<small class="text-muted file-count-msg">No files selected</small>' + '<small class="text-danger file-size-error  d-none">One or more files exceed 30 MB limit.</small></div>' +
                                        '<div class="col-md-1 float-right"><a href="#" class="text-danger close-div"><i class="fa fa-trash"></i></a></div>' +
                                        '</div>'
                                    );
                                    $('body').animate({
                                        scrollTop: $('#attachment_area').offset().top - 70
                                    }, 1000);
                                    $('#attachments').append(newDiv);
                                    newDiv.find('.attachment-file').on('change', function() {
                                        if (!this.files || this.files.length === 0) {
                                            return;
                                        }
                                        var maxSizeMB = 30;
                                        var errorFound = false;
                                        var count = this.files.length;
                                        var $parent = $(this).closest('.col-md-5');
                                        var $fileCountMsg = $parent.find('.file-count-msg');
                                        var $fileSizeError = $parent.find('.file-size-error');
                                        // Reset previous error
                                        $fileSizeError.addClass('d-none').removeClass('d-block');
                                        for (let i = 0; i < this.files.length; i++) {
                                            if (this.files[i].size > maxSizeMB * 1024 * 1024) {
                                                errorFound = true;
                                                break;
                                            }
                                        }
                                        if (errorFound) {
                                            $fileSizeError.removeClass('d-none').addClass('d-block');

                                            $(this).val(''); // clear file input
                                            $fileCountMsg.text('No files selected');
                                        } else {
                                            $fileCountMsg.text(count + ' file(s) selected');
                                        }
                                    });


                                    $('.close-div').click(function(e) {
                                        e.preventDefault();
                                        $(this).parent().parent().remove();
                                        $('body').animate({
                                            scrollTop: $('#attachment_area').offset().top - 70
                                        }, 1000);
                                    });
                                    attachmentIndex++;
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
                            <form action="<?php echo base_url(); ?>leads/lead/new" method="post"
                                enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="">Lead Source Type</label>
                                            <select class="form-control" name="lead_source_name" id="lead_source_name"
                                                required>
                                                <option value="" selected default disabled>-- Source -</option>
                                                <option>Walk-in</option>
                                                <option>WhatsApp</option>
                                                <option>Email</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Select Branch &nbsp;<span
                                                    class="text-danger required">*</span></label>
                                            <?php
                                            $lead_users = $this->user_model->get_accessable_groups();
                                            $branchs = [];
                                            if (!empty($lead_users)) {
                                                foreach ($lead_users as $ld) {
                                                    array_push($branchs, $ld["branch_id"]);
                                                }
                                                // print_r(json_encode($branchs));
                                                // exit();
                                            }
                                            ?>
                                            <select class="form-control" name="branch_id" id="branch_id" required>
                                                <option value="">-- Select Branch --</option>
                                                <?php
                                                $biz_usrs = ['2082520640','2149704895'];
                                                foreach ($branches as $key => $value) {
                                                    if($value["branch_code"] == 107 && !in_array($this->auth_user_id, $biz_usrs)){
                                                        continue;
                                                    }
                                                    // if (!empty($lead_users)) {
                                                        if (!empty($branchs)) {
                                                            if (!in_array($value["branch_code"], $branchs)) {
                                                                continue;
                                                            }
                                                        }
                                                    // }
                                                ?>
                                                <option value="<?php echo $value['branch_code']; ?>">
                                                    <?php echo $value['branch_name']; ?></option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6 assign_group_dd">
                                        <div class="form-group">
                                            <label>Assign Type <span class="text-danger required">*</span></label>
                                            <select name="assign_type" id="assign_type" class="form-control" required>
                                                <option value="group" selected default>Group(s)</option>
                                                <option value="self">Self</option>
                                                <?php if (in_array($primary_group_id, [54, 58, 104])) { ?> 
                                                    <option value="unassigned">Unassigned</option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 assign_group_dd">
                                        <div class="form-group">
                                            <label>Directly Assign to:(Group) <span class="text-danger required">*</span></label>
                                            <select name="assign_group" class="form-control single-select" required>
                                                <option value="">-- Select Group--</option>
                                                <?php
                                                // print_r($lead_details);
                                                $lead_users = $this->user_model->get_accessable_groups();
                                                if (empty($lead_users)) {
                                                    $lead_users = $this->user_model->get_lead_category_groups();
                                                }
                                                // print_r($lead_users);
                                                log_message('error', $this->db->last_query());
                                                foreach ($lead_users as $keys => $values) {
                                                ?>
                                                <option value="<?php echo str_replace(' ', '', $values["group_s"]); ?>"
                                                    data-branch="<?php echo $values['branch_id']; ?>">
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

                                    <input type="hidden" name="lead_type" value="package">
                                    <input type="hidden" name="package_id" value="109">

                                    <!-- <div class="col-md-12">
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
                                                <option value="109">Custom Package</option>
                                            </select>
                                        </div>
                                    </div> -->
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
                                                            name="" value="CMP -" id="">
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
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="">Is Corporate</label>
                                            <select class="form-control" name="is_corporate" id="is_corporate"
                                                required>
                                                <option value="" selected default disabled>-- Select -</option>
                                                <option value="Corporate">Corporate</option>
                                                <option value="Others">Others</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6" id="applicant_name_group" style="display: none;">
                                        <div class="form-group">
                                            <label>Applicant Name&nbsp;<span class="text-danger required">*</span></label>
                                            <input type="text" class="form-control selected_field" required="" id="applicant_name" name="applicant_name" autofocus pattern="[^0-9]*" title="Numbers are not allowed">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Lead Name&nbsp;<span class="text-danger required"> * </span></label>
                                            <input type="text" class="form-control selected_field" id="lead_name" required="" name="lead_name" autofocus>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Country Code&nbsp;<span class="text-danger required">*</span></label>
                                            <input type="text" class="form-control" pattern="[+][0-9]{1,}" required=""
                                                name="lead_country_code" value="+971" id="lead_country_code">
                                        </div>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="form-group">
                                            <label>Lead Contact number without country code&nbsp;<span
                                                    class="text-danger required">*</span></label>
                                            <input type="text" class="form-control selected_field" id="lead_contact" pattern="^[0-9]{0,10}$" title="Enter valid contact number" required=""
                                                name="lead_contact" >
                                        </div>
                                    </div>

                                    <!-- <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Nationality </label>
                                            <select name="nationality" class="form-control single-select">
                                                <option value="">--Select--</option>
                                                <?php
                                                // foreach ($nationality as $national) {
                                                ?>
                                                    <option value="<?php //echo str_replace(' ', '', $national["nationality_name"]); 
                                                                    ?>">
                                                        <?php
                                                        // echo $national["nationality_name"];
                                                        ?>
                                                    </option>
                                                <?php
                                                // }
                                                ?>
                                            </select>
                                        </div>
                                    </div> -->

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Lead Email Address <span
                                            class="text-danger required">*</span></label>
                                            <input id="email" type="text" class="form-control selected_field" name="email" required>
                                            <span id="email_error" class="text-danger"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group" id="div_assign_to">
                                            <label>Directly Assign to: &nbsp;<span
                                                    class="text-danger required">*</span></label>
                                            <select name="assign_to" class="form-control single-select" required>
                                                <option value="">-- Select --</option>
                                                <?php
                                                // print_r($lead_details);
                                                $lead_users = $this->user_model->get_lead_category_users($lead_details['category_id']);
                                                // print_r($lead_users);
                                                log_message('error', $this->db->last_query());
                                                foreach ($lead_users as $keys => $values) {
                                                    if (stripos($values['first_name'], 'zoho') === 0) continue;
                                                ?>
                                                <option value="<?php echo $values['user_id']; ?>"
                                                    data-filter="<?php echo str_replace(',', '', $values["group_s"]); ?>">
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
                                            <label>Remarks(in English/Arabic)&nbsp;<span
                                                    class="text-danger required">*</span></label>
                                            <textarea rows="7" class="form-control" required=""
                                                name="lead_remarks"></textarea>
                                        </div>
                                    </div>
                                    <a id="attachment_area"></a>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Lead Attachments&nbsp;&nbsp;&nbsp;&nbsp; <button
                                                    id="btnAddNewAttachment" class="btn btn-sm btn-primary">Add
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
                        var branch = $("#branch_id").val();
                        // if (branch == "") {
                        //     swal.fire({
                        //         icon: "info",
                        //         text: "Please Select the Branch"
                        //     });
                        //     $("#normal").prop("checked", "true");
                        //     normal_lead();
                        //     return;
                        // } else if (branch != 106) {
                        //     swal.fire({
                        //         icon: "info",
                        //         text: "Selected Branch have no package"
                        //     });
                        //     $("#normal").prop("checked", "true");
                        //     normal_lead();
                        //     return;
                        // }
                        $('#category_block').hide();
                        $('#service_block').hide();
                        $('#package_block').show();
                    }

                    $("#branch_id").change(function () {
                        var biz = [107];
                        var attest = [103];
                        var val = parseInt($(this).val());
                        // console.log("There=> ", val);
                        // alert(val);

                        if (biz.indexOf(val) != -1) {
                            // zoho_biz_url = "https://forms.zohopublic.com/ontimebusinesssetup1/form/LeadGenerationGovernmentServices/formperma/oSsJQQeK-RvytjgubMs-LRRgfPlnb_bmTrrojx8iXpY";

                            // swal.fire({
                            //     icon: "info",
                            //     text: "Redirecting to Create the Lead for Business",
                            //     didOpen: function() {
                            //         swal.enableLoading(); 
                            //         setTimeout(() => {
                            //             location.href = zoho_biz_url;
                            //         }, 1000);
                            //     }
                            // });

                            // $('[name="assign_to"]').attr("required",false);
                            // $("#div_assign_to span").hide();


                            // location.href = "/leads/lead/biznew";
                            // swal.fire({
                            //      icon: "info",
                            //      text: "Redirecting to Business Setup Leads Page",
                            //      didOpen: function() {
                            //          swal.enableLoading();
                            //          setTimeout(() => {
                            //              location.href = "/leads/lead/biznew";
                            //          }, 1000);
                            //      }
                            //  })
                        } else {
                            //  $('[name="assign_to"]').attr("required",true);
                            // $("#div_assign_to span").show();
                        }

                        $('[name="assign_group"]').val("").trigger("change");
                        $('[name="assign_group"] option').addClass("d-none");
                        $('[name="assign_group"] option[data-branch="' + val + '"]').removeClass("d-none");

                        if ($('[name="assign_group"] option:not(.d-none)').length == 1) {
                            var val = $('[name="assign_group"] option:not(.d-none)').val();
                            $('[name="assign_group"]').val(val).trigger("change");
                        }

                        if (attest.indexOf(val) != -1) {
                            swal.fire({
                                icon: "info",
                                text: "Redirecting to Attestation Leads Page",
                                didOpen: function () {
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
                            success: function (data) {
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
                            error: function (err) {
                                console.log(err);
                            }
                        });
                    }

                    $('[name="assign_group"]').change(function () {
                        $('[name="assign_to"]').val("").trigger("change");
                        console.log('val: ' + $(this).val());
                        // alert($(this).val());
                        //if ($(this).val() == "") {
                        // $('[name="assign_to"] option').removeClass("d-none");
                        // return;
                        // }
                        $('[name="assign_to"] option:not([value=""])').addClass("d-none");
                        $('[name="assign_to"] option:not([value=""])[data-filter*="' + $(this).val() + '"]')
                            .removeClass("d-none");

                        // $('[name="assign_to"] option:not([value="3771749283"])[data-filter*="BusinessSetup"]')
                        //   .addClass("d-none");
                        if ($(this).val() === "DLD" || $(this).val() === "MazayaDLD") {
                            // Check if "Unassigned" is not already in the options
                            if ($('[name="assign_to"] option[value="unassigned"]').length === 0) {
                                $('[name="assign_to"]').append('<option value="unassigned">Unassigned</option>');
                                // $('[name="assign_to"]').prepend('<option value="unassigned">Unassigned</option>');
                            }
                        } else {
                            // Remove "Unassigned" option if it exists and it's not "DLD"
                            $('[name="assign_to"] option[value="unassigned"]').remove();
                        }
                    });


                    $("#category_id").change(function (e) {
                        if ($(this).val() == 125) {
                            swal.fire({
                                icon: "info",
                                text: "Redirecting to Attestation Leads Page",
                                didOpen: function () {
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

    $('[name="assign_group"] option').addClass("d-none");
    $('[name="assign_to"] option:not([value=""])').addClass("d-none");
    $(document).ready(function() {
        $('#is_corporate').on('change', function() {
            if ($(this).val() === 'Corporate') {
                $('#applicant_name_group').show();
                $('#applicant_name').attr('required', true);
            } else {
                $('#applicant_name_group').hide();
                $('#applicant_name').val('').removeAttr('required');
            }
        });
    });
</script>
<script>
        function validateForm() {
            // Reset previous error messages
            document.getElementById('countryCodeError').innerText = '';

            // Get the value of the lead_country_code input field
            var countryCode = document.getElementById('lead_country_code').value;

            // Check if the country code is not empty
            if (countryCode.trim() === '') {
                document.getElementById('countryCodeError').innerText = 'Country code is required.';
                return false;
            }

            // Check if the country code is exactly three letters
            if (countryCode.length !== 3) {
                document.getElementById('countryCodeError').innerText = 'Country code must be three letters.';
                return false;
            }

            // You can add more specific validation rules if needed

            // If all validations pass, the form will be submitted
            return true;
        }
    </script>