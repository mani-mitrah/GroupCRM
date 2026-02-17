<div class="card" id="order_details_block">
    <div class="card-header">
        <?php
        $status = $order_details['item_status'];
        if ($status == 101) {
            $badge = 'success  text-white';
        } else if ($status == 102) {
            $badge = 'warning text-white';
        } else if ($status == 103) {
            $badge = 'primary text-white';
        } else if ($status == 104) {
            $badge = 'info  text-white';
        } else if ($status == 105) {
            $badge = 'danger  text-white';
        }
        ?>
        <h5 class="card-title my-auto">ORDER DETAILS <span class="badge badge-primary text-white"><?php echo 'BOOKING ID: SE' . $order_details['order_id'] . '-' . $order_details['order_details_id']; ?></span>&nbsp;&nbsp;<span class="badge badge-<?php echo $badge; ?>"><?php echo 'STATUS: ' . $order_details['status_name']; ?></span></h5>
    </div>
    <div class="card-body">
        <div class="customer_block">
            <h5><strong>CUSTOMER INFORMATION</strong></h5>
            <table class="table">
                <tr>
                    <td>
                        <strong>Customer Name:</strong><br />
                        <span id="o_cus_name" class="text-ontime"><?php echo $order_details['first_name'] . ' ' . $order_details['last_name']; ?></span>
                    </td>
                    <td>
                        <strong>Mobile Number:</strong><br />
                        <span id="o_cus_mobile" class="text-ontime"><?php echo $order_details['country_code'] . ' ' . $order_details['mobile']; ?></span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong>Customer Email:</strong><br />
                        <span id="o_cus_email" class="text-ontime"><?php echo $order_details['email']; ?></span>
                    </td>
                    <td>
                        <strong>Gender:</strong><br />
                        <span id="o_cus_gender" class="text-ontime"><?php echo ($order_details['gender'] != '') ? $order_details['gender'] != '' : ''; ?></span>
                    </td>
                </tr>
            </table>
        </div>
        <div class="order_block">
            <h5><strong>ORDER INFORMATION</strong></h5>
            <table class="table">
                <tr>
                    <td>
                        <strong>Order Reference Number:</strong><br />
                        <span id="o_ord_sub_category" class="text-ontime"><?php echo 'SE' . $order_details['order_id'] . '-' . $order_details['order_details_id']; ?></span>
                    </td>
                    <td>
                        <strong>Transaction Name:</strong><br />
                        <span id="o_ord_category" class="text-ontime"><?php echo $order_details['trans_name']; ?></span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong>Card Number:</strong><br />
                        <span id="o_ord_sub_category2" class="text-ontime"><?php echo $order_details['card_num']; ?></span>
                    </td>
                    <td>
                        <strong>Transaction Number</strong><br />
                        <span id="o_ord_mrn" class="text-ontime"><?php echo $order_details['transaction_number']; ?></span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong>Receipt Number:</strong><br />
                        <span id="o_ord_gender" class="text-ontime"><?php echo $order_details['receipt_no']; ?></span>
                    </td>
                    <td>
                        <strong>Net Total</strong><br />
                        <span id="o_ord_amount" class="text-ontime"><?php echo $order_details['net_total']; ?></span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong>SLA:</strong><br />
                        <span id="o_ord_service_hours" class="text-ontime"><?php echo $order_details['sla']; ?></span>
                    </td>
                    <td>

                    </td>
                </tr>
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
                        <tr>
                            <td colspan="2">
                                <label>Assigned Group: </label>

                                <select data-child="group" onchange="group_change(this.value)" class="form-control single-select" name="assign_group">
                                    <option value="">-- Select --</option>
                                    <?php
                                    // print_r($lead_details);
                                    $lead_users = $this->user_model->get_lead_category_groups();
                                    // print_r($lead_users);
                                    log_message('error', $this->db->last_query());
                                    foreach ($lead_users as $keys => $values) {
                                        if ($values['branch_id'] == 107 || $values['branch_id'] == 103) continue;
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

                                <select data-parent="group" name="assign_to" onchange="javascript:assign_otg_csa(<?php echo $order_details['id']; ?>,this.value,<?php echo $this->auth_user_id; ?>);"  class="form-control single-select">
                                    <option value="">-- Please Select the Group --</option>
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
                            </td>
                        </tr>
                
            </table>
        </div>
    </div>
</div>

<style>
    #order_details_block .selection {
        display: block;
        width: 100%;
        height: calc(2.25rem + 2px);
        padding: 0.375rem 0.75rem;
        font-size: 1rem;
        font-weight: 400;
        line-height: 1.5;
        color: #495057;
        background-color: #fff;
        background-clip: padding-box;
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
        transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
    }
</style>