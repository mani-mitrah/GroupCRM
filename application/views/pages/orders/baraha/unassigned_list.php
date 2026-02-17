<script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<style type="text/css">
  .text-ontime
  {
    color:#3d4465 !important;
  }
  .dataTables_wrapper .dataTables_paginate .paginate_button.previous.disabled, .dataTables_wrapper .dataTables_paginate .paginate_button.next.disabled {
      color: #969ba0 !important;
  }
</style>
<script type="text/javascript">
  //hide elements at first
  $( document ).ready(function() {
    $('#order_details_block').hide();
    $('#no_card').show();
//    $('#main-wrapper').attr('class','show menu-toggle');
    $('#mrn_block').hide();
  });
  
</script>
<div class="row page-titles mx-0">
    <div class="col-sm-6 p-md-0">
        <div class="welcome-text">
            <h4>Al-baraha Un-assigned Orders</h4>
            <span>Unassigned Orders</span>
        </div>
    </div>
    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <table id="datatable" cellpadding="2" cellspacing="1" class="table table-striped">
                  <thead>
                    <tr>
                      <tr>
                      <th>#</th>
                      <th>Customer</th>
                      <th>Order</th>
                      <th>Status</th>
                      <th>Date</th>
                      <th>Actions</th>
                    </tr>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    foreach ($unassigned_orders as $key => $value) 
                    {
                    ?>
                      <tr id="o<?php echo $value['item_id']; ?>">
                        <td><?php echo $value['item_id']; ?></td>
                        <td><?php echo ($value['gender']==1)?'<span style="color:#62a0ff;"><i class="fa fa-male"></i> </span>':'<span style="color:#d015af;"><i class="fa fa-female"></i> </span>'; ?>&nbsp;<?php echo '<strong>'.$value['first_name'].'</strong><br />'.$value['mobile']; ?></td>
                        <td>
                          <?php 
                          echo $value['category_name']; 
                          echo ($value['sub_category_name']!='')?' -> '.$value['sub_category_name']:"";
                          echo ($value['sub_categorytwo']!='')?' -> '.$value['sub_categorytwo']:"";
                          echo '<br /><strong>Amount:</strong> AED '.$value['amount'];
                          echo '<br /><strong>Service Hours:</strong> '.$value['service_hour'];
                          ?>
                        </td>
                        <td>
                          <?php 
                          $status=$value['item_status']; 
                          if($status==101)
                          {
                            $badge = 'success';
                          }
                          else if($status==102)
                          {
                            $badge = 'warning';
                          }
                          else if($status==103)
                          {
                            $badge = 'primary';
                          }
                          else if($status==104)
                          {
                            $badge = 'info';
                          }
                          ?>
                          <span class="badge light badge-<?php echo $badge; ?>"><?php echo $value['status_name']; ?></span>
                        </td>
                        <td><?php echo $value['order_item_date']; ?></td>
                        <td>
                          <select onchange="javascript:assign_csa(<?php echo $value['item_id']; ?>,this.value,<?php echo $this->auth_user_id; ?>);">
                            <option>-- Select CSA --</option>
                            <?php
                            foreach ($baraha_csas as $key => $value) 
                            {
                              ?>
                              <option value="<?php echo $value['user_id']; ?>"><?php echo $value['first_name']." ".$value['last_name'];
                              ?></option>
                              <?php
                            }
                            ?>
                          </select>
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
<script type="text/javascript">

    function get_order_item_details(order_item_id) 
    {
      $('#order_details_block').show();
      $('#no_card').hide();
      $('#order_loading').show();
      $('#mrn_block').hide();

      $.ajax({
        url: "<?php echo base_url(); ?>api/v1/order/item?item_id="+order_item_id,
        type: 'GET',
        dataType: 'json', // added data type
        success: function(res) {

            //let order_json = JSON.parse(res);
            console.log(res);
            let item_id = res[0].item_id;
            let order_id = res[0].order_id;
            let med_number = res[0].med_number;
            let ref2 = res[0].ref2;
            let category = res[0].category;
            let sub_category = res[0].sub_category;
            let sub_category_two = res[0].sub_category_two;
            let gender = res[0].gender;
            let pregnancy = res[0].pregnancy;
            let menstrual_period = res[0].menstrual_period;
            let abortion = res[0].abortion;
            let contraceptive_pills = res[0].contraceptive_pills;
            let x_ray = res[0].x_ray;
            let attachment_no = res[0].attachment_no;
            let service_time = res[0].service_time;
            let item_amount = res[0].amount;
            let created_date = res[0].created_date;
            let source = res[0].source;
            let remarks = res[0].remarks;
            let created_time = res[0].created_time;
            let item_status = res[0].item_status;
            let o_id = res[0].o_id;
            let user_id = res[0].user_id;
            let company_id = res[0].company_id;
            let order_amount = res[0].order_amount;
            let status = res[0].status;
            let priority = res[0].priority;

            //Payment page items
            let response_code = res[0].response_code;
            let response_description = res[0].response_description;
            let response_class_description = res[0].response_class_description;
            let approval_code = res[0].approval_code;
            let card_number = res[0].card_number;
            let card_brand = res[0].card_brand;
            let unique_id = res[0].unique_id;
            let card_expiry = res[0].card_expiry;
            //let pid = res[0].pid;
            //let response_class = res[0].response_class;
            //let language = res[0].language;
            //let account = res[0].account;
            //let balance = res[0].balance;
            //let fees = res[0].fees;
            //let payer = res[0].payer;
            //let card_token = res[0].card_token;
            //
            //let card_type = res[0].card_type;

            
            let role_id = res[0].role_id;
            let first_name = res[0].first_name;
            let last_name = res[0].last_name;
            let country = res[0].country;
            let country_code = res[0].country_code;
            let mobile = res[0].mobile;
            let otp = res[0].otp;
            let username = res[0].username;
            let email = res[0].email;
            let auth_level = res[0].auth_level;
            let banned = res[0].banned;
            let passwd = res[0].passwd;
            let passwd_recovery_code = res[0].passwd_recovery_code;
            let passwd_recovery_date = res[0].passwd_recovery_date;
            let passwd_modified_at = res[0].passwd_modified_at;
            let last_login = res[0].last_login;
            let created_at = res[0].created_at;
            let modified_at = res[0].modified_at;
            let is_mobile_verified = res[0].is_mobile_verified;
            let is_email_verified = res[0].is_email_verified;
            let is_active = res[0].is_active;
            let email_otp = res[0].email_otp;
            let referal_code = res[0].referal_code;
            let otp_verified = res[0].otp_verified;
            let device_id = res[0].device_id;
            let confirm_password = res[0].confirm_password;
            let profile_pic = res[0].profile_pic;
            let service_name = res[0].service_hour;

            //category details
            let category_name = res[0].category_name;
            let sub_category_name = res[0].sub_category_name;
            let sub_category_two_name = res[0].sub_categorytwo;
            //let mr_number = res[0].med_number;

            let gender_name='';
            //set gender name
            if(gender==1)
            {
              gender_name = 'Male';
              $('#pregnancy_block').hide();
            }
            else if(gender==2)
            {
              gender_name = 'Female';
              $('#pregnancy_block').show();
            }

            let loader = '<img src="<?php echo base_url(); ?>global/images/data_load.gif" />';
            //update customer block
            $('#o_cus_name').html(first_name);
            $('#o_cus_mobile').html(country_code+' '+mobile);
            $('#o_cus_email').html(email);
            $('#o_cus_gender').html(gender_name);

            //update order information
            $('#o_ord_category').html(category_name);
            $('#o_ord_sub_category').html(sub_category_name);
            $('#o_ord_sub_category2').html(sub_category_two_name);
            $('#o_ord_mrn').html(med_number);
            $('#o_ord_gender').html(gender_name);
            $('#o_ord_amount').html('AED '+item_amount);
            $('#o_ord_service_hours').html('<strong>'+service_name+'</strong>');

            //update pregnancy information
            if(gender==2)
            {
              $('#o_ord_pregnant').html((pregnancy==1)?'YES':'NO');
              $('#o_ord_lmp').html(menstrual_period);
              $('#o_ord_abortion').html((abortion==1)?'YES':'NO');
              $('#o_ord_cpills').html((contraceptive_pills==1)?'YES':'NO');
              $('#o_ord_xray').html((x_ray==1)?'YES, CUSTOMER AGREED FOR XRAY':'NO');
            }

            //update card details
            $('#o_ord_pay_status').html(response_class_description+'-'+response_description);
            $('#o_ord_approval_code').html(approval_code);
            $('#o_ord_card_number').html(card_number);
            $('#o_ord_card_expiry').html(card_expiry);
            $('#o_ord_card_brand').html(card_brand);
            $('#o_ord_unique_id').html(unique_id);


            $('#order_loading').hide();
            $('#item_id').val(item_id);
            if(med_number=='' || med_number==0)
            {
              $('#mrn_block').show();
            }
        }
      });
      $.ajax({
        url: "<?php echo base_url(); ?>api/v1/order/attachments?item_id="+order_item_id,
        type: 'GET',
        dataType: 'json', // added data type
        success: function(res) {
            $('#attachment_data').html("");
            console.log(res);
            console.log(res.length);
            if(res.length==0)
            {
              $('#attachments_block').hide();
            }
            for (var i = 0; i < res.length; i++) {
              let attachment_data = $('<tr><td><a target="_blank" href="'+res[i].attachment+'">View Attachment</a></td></tr>');
              attachment_data.appendTo('#attachment_data');
            }
        }
      });
    }
    
    function assign_csa(item_id,assigned_to,assigned_by) 
    {

      Swal.fire({
        title: 'Please confirm',
        text: "An order once assigned cannot be reverted.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, Assign!'
      }).then((result) => {
        if (result.isConfirmed) {

          $.ajax({
            url: "<?php echo base_url(); ?>api/v1/assign/order",
            type: 'POST',
            data:{assigned_to:assigned_to,item_id:item_id,assigned_by:assigned_by},
            success: function(res) {
              $('#o'+item_id).hide();
              Swal.fire(
                'Assigned!',
                res,
                'success'
              )
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
</script>