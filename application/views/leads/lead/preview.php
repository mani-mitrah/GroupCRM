<style type="text/css">
    .dtp div.dtp-date,
    .dtp div.dtp-time {
        background: #2196f3 !important;
    }

    .dtp table.dtp-picker-days tr>td>a.selected {
        background: #2196f3 !important;
    }

    .dtp>.dtp-content>.dtp-date-view>header.dtp-header {
        background: #3f51b5 !important;
    }

    .dtp .p10>a {
        color: #fff !important;
    }

    .year-picker-item.active {
        color: #3f51b5 !important;
    }

    .bg-secondary {
        background-color: #051469 !important;
    }
</style>

<?php
//print_r($lead_details);
$fake_domain = "no";
$email = $lead_details['customer_email'];
$domain = substr(strrchr($email, "@"), 1);
if ($domain == 'ontimecustomer.com') {
    $fake_domain = "yes";
}
?>
<!-- <div class="row page-titles mx-0">
    <div class="col-sm-6 p-md-0">
        <div class="welcome-text">
            <h4>Lead information</h4>
            <span>View lead details</span>
        </div>
    </div>
    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
        <a class="btn btn-primary waves-effect waves-light float-right" href="<?php echo base_url(); ?>leads/lead/manage">
            <i class="fa fa-back mr-2"></i> Back to leads
        </a>
    </div>
</div> -->
<?php
if ($this->session->flashdata('alert_cu')) {
?>
    <div class="alert alert-<?php echo $this->session->flashdata('alert_cu'); ?>">
        <?php echo $this->session->flashdata('alert_message_cu'); ?>
    </div>
<?php
}
?>
<div class="row">
    <div class="col-lg-12">
        <div class="card" id="order_details_block">
            <!-- <div class="card-header bg-secondary">
                <h5 class="card-title text-white">LEAD DETAILS</h5>
            </div> -->
            <div class="card-body pb-0">
                <div class="customer_block">
                    <table class="table">
                        <tr>
                            <td>
                                <strong>Customer Name:</strong><br />
                                <span id="o_cus_name" class="text-ontime"><?php echo $lead_details['customer_name']; ?></span>
                            </td>
                            <td>
                                <strong>Mobile Number:</strong><br />
                                <span id="o_cus_mobile" class="text-ontime"><?php echo $lead_details['customer_country_code'] . ' ' . $lead_details['customer_mobile']; ?></span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Customer Email:</strong><br />
                                <span id="o_cus_email" class="text-ontime">
                                    <?php echo ($fake_domain == 'yes') ? '-- EMAIL NOT PROVIDED--' : $lead_details['customer_email']; ?>
                                </span>
                            </td>
                            <td>

                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Category:</strong><br />
                                <span id="o_ord_category" class="text-ontime"><?php echo $lead_details['category_name']; ?></span>
                            </td>
                            <td>
                                <strong>Service Name:</strong><br />
                                <span id="o_ord_sub_category" class="text-ontime"><?php echo $lead_details['service_name']; ?></span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Lead by:</strong><br />
                                <span id="o_ord_sub_category2" class="text-ontime"><?php echo $lead_details['branch_name']; ?></span>
                            </td>
                            <td>
                                <strong>Created by:</strong><br />
                                <span id="o_ord_mrn" class="text-ontime"><?php echo $lead_by_details['first_name'] . ' ' . $lead_by_details['last_name']; ?><br /><?php echo $lead_by_details['email']; ?><br /><?php echo $lead_by_details['mobile']; ?></span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <strong>Description:</strong><br />
                                <?php echo $lead_details['remarks']; ?>
                            </td>
                        </tr>
                        <?php
                        if (!empty($lead_attachments)) {
                        ?>
                            <tr>
                                <td colspan="2"><strong>Attachments</strong></td>
                            </tr>
                            <?php
                            foreach ($lead_attachments as $key => $attach) {
                            ?>
                                <tr>
                                    <td><?php echo strtoupper($attach['attachment_name']); ?></td>
                                    <td>
                                        <?php

                                        $file_url = $attach['attachment_url'];
                                        $file_name = str_replace("https://crm.ontimegroup.com/uploads/leads/", "", $file_url);
                                        ?>

                                        <a target="blank" href="<?php echo $attach['attachment_url']; ?>"> <span class="fiv-viv fiv-icon-doc"></span>&nbsp;&nbsp;View file</a>
                                    </td>
                                </tr>
                        <?php
                            }
                        }
                        ?>
                        <?php
                        if ($lead_details['order_receipt'] != "0") {
                        ?>
                            <tr>
                                <td colspan="2">
                                    <strong>ORDER ID#:</strong><br />
                                    <span id="o_ord_sub_category2" class="text-ontime"><?php echo $lead_details['order_receipt']; ?></span>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                        <tr>
                            <td colspan="2">
                                <label>Select Branch &nbsp;<span class="text-danger required">*</span></label>
                                <select class="form-control" name="branch_id" id="branch_id" onchange="branch_change(this.value)">
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
                            </td>
                        </tr>
                        <script>
                            $( document ).ready(function() {
                                var selectBox = document.getElementById("branch_id");
                                var numberOfOptions = selectBox.options.length;
                                if(numberOfOptions>1){
                                    $('[name="assign_group"]').val("").trigger("change");
                                    $('[name="assign_group"] option').addClass("d-none");
                                }

                                $('[name="assign_group"]').change(function () {
                                    $('[name="assign_to"]').val("").trigger("change");
                                    // console.log('val: ' + $(this).val());

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
                            });  
                        </script>
                        <tr>
                            <td colspan="2">
                                <label>Assigned Group: </label>

                                <select data-child="group" onchange="group_change(this.value)" class="form-control single-select" name="assign_group">
                                    <option value="">--- Select ---</option>
                                    <?php
                                        $biz_usrs = ['2082520640','2149704895'];
                                        // print_r($lead_details);
                                        $lead_users = $this->user_model->get_lead_category_groups();
                                        // print_r($lead_users);
                                        log_message('error', $this->db->last_query());
                                        foreach ($lead_users as $keys => $values) {
                                            // if ($values['branch_id'] == 107 || $values['branch_id'] == 103) continue;
                                            //  if($values["branch_id"] == 107 && !in_array($this->auth_user_id, $biz_usrs)){
                                            //     continue;
                                            // }
                                            // if($values['group_id'] == 110){
                                            if(in_array($values['group_id'], [110,123,115,118,114,84,109,121,125,111,117,116])){
                                                continue;
                                            }
                                        ?>
                                        <option value="<?php echo str_replace(' ', '', $values["group_s"]); ?>" data-branch="<?php echo $values['branch_id']; ?>">
                                            <?php
                                            echo $values["group_s"];
                                            ?>
                                        </option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <label>Assigned to: </label>

                                <select data-parent="group" name="assign_to" onchange="javascript:assign_csa(<?php echo $lead_details['id']; ?>,this.value,<?php echo $this->auth_user_id; ?>);" class="form-control single-select">
                                    <option value="">-- Please Select the Group --</option>
                                    <?php
                                                // print_r($lead_details);
                                                $lead_users = $this->user_model->get_lead_category_users($lead_details['category_id']);
                                                // print_r($lead_users);
                                                log_message('error', $this->db->last_query());
                                                foreach ($lead_users as $keys => $values) {
                                                    if (stripos($values['first_name'], 'zoho') === 0) continue;
                                                    $user_avail = '';
                                                    if($values['group_id'] == 58){
                                                        if($values['is_available'] == 0){
                                                            $user_avail = 'red';
                                                        } else {
                                                            $user_avail = 'green';
                                                        }
                                                        $count = $this->leads_model->get_opened_leads_count($values['user_id'], 58);
                                                    } elseif($values['role_id'] == 7){
                                                        $count = $this->leads_model->get_opened_leads_count($values['user_id'], $values['group_id']);
                                                    }else {
                                                        $count = '';
                                                    }
                                                ?>
                                                    <option value="<?php echo $values['user_id']; ?>" data-filter="<?php echo str_replace(',', '', $values["group_s"]); ?>" style="color: <?php echo $user_avail; ?>;">
                                                        <?php
                                                        $urole_id = $values['role_id'];
                                                        // $user_role = ($urole_id == 2) ? 'CSA' : (($urole_id == 6) ? 'Cordinator' : 'Team Lead');
                                                        $user_role = ($urole_id == 2) ? 'CSA' : (($urole_id == 6) ? 'Cordinator' : (($urole_id == 84) ? 'Goldencube Web Cordinator' : 'Team Lead'));
                                                        echo $values["first_name"] . ' ' . $values["last_name"] . "  (" . $user_role . ") " . (($count != '') ? " - Opened Leads: " . $count : "");
                                                        ?>
                                                    </option>
                                                <?php
                                                }
                                                ?>
                                </select>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
