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
            <h4>Al-baraha Orders</h4>
            <span>Al-Baraha Orders</span>
        </div>
    </div>
    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
        <a class="btn btn-primary waves-effect waves-light float-right"
            href="<?php echo base_url(); ?>orders/baraha/new/">
            <i class="mdi mdi-plus mr-2"></i> NEW ORDER
        </a>
    </div>
</div>
<div class="row">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <table id="datatable" cellpadding="2" cellspacing="1" class="mytable table table-striped">
                </table>
                <!-- <div class="email-left-box px-0 mb-3">
                </div>
                <div class="email-right-box ml-0 ml-sm-4 ml-sm-0">
                </div> -->
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card" id="order_details_block">
            <div class="card-header">
                <h5 class="card-title">ORDER DETAILS</h5>
            </div>
            <div class="card-body">
                <div class="text-center" id="order_loading">
                  <img src="<?php echo base_url(); ?>global/images/order_loading.gif" />
                </div>
                
                <div class="customer_block">
                  <h5><strong>CUSTOMER INFORMATION</strong></h5>
                  <table class="table">
                    <tr>
                      <td>
                        <strong>Customer Name:</strong><br />
                        <span id="o_cus_name" class="text-ontime">--</span>
                      </td>
                      <td>
                        <strong>Mobile Number:</strong><br />
                        <span id="o_cus_mobile" class="text-ontime">--</span>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <strong>Customer Email:</strong><br />
                        <span id="o_cus_email" class="text-ontime">--</span>
                      </td>
                      <td>
                        <strong>Gender:</strong><br />
                        <span id="o_cus_gender" class="text-ontime">--</span>
                      </td>
                    </tr>
                  </table>
                </div>
                <div class="order_block">
                  <h5><strong>ORDER INFORMATION</strong></h5>
                  <table class="table">
                    <tr>
                      <td>
                        <strong>Category:</strong><br />
                        <span id="o_ord_category" class="text-ontime">--</span>
                      </td>
                      <td>
                        <strong>Subcategory:</strong><br />
                        <span id="o_ord_sub_category" class="text-ontime">--</span>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <strong>Subcategory 2:</strong><br />
                        <span id="o_ord_sub_category2" class="text-ontime">--</span>
                      </td>
                      <td>
                        <strong>Medical Reference Number</strong><br />
                        <span id="o_ord_mrn" class="text-ontime">--</span>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <strong>Gender:</strong><br />
                        <span id="o_ord_gender" class="text-ontime">--</span>
                      </td>
                      <td>
                        <strong>ORDER AMOUNT</strong><br />
                        <span id="o_ord_amount" class="text-ontime">--</span>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <strong>SERVICE HOURS:</strong><br />
                        <span id="o_ord_service_hours" class="text-ontime">--</span>
                      </td>
                      <td>
                        
                      </td>
                    </tr>
                  </table>
                </div>
                <div id="pregnancy_block">
                  <h5><strong>PREGNANCY QUESTIONS</strong></h5>
                  <table class="table">
                    <tr>
                      <td>
                        <strong>Are you pregnant:</strong><br />
                        <span id="o_ord_pregnant" class="text-ontime">--</span>
                      </td>
                      <td>
                        <strong>Last Menstrual Period (LMP):</strong><br />
                        <span id="o_ord_lmp" class="text-ontime">--</span>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <strong>History of any recent abortion?</strong><br />
                        <span id="o_ord_abortion" class="text-ontime">--</span>
                      </td>
                      <td>
                        <strong>Are you taking contraceptive pills?</strong><br />
                        <span id="o_ord_cpills" class="text-ontime">--</span>
                      </td>
                    </tr>
                    <tr>
                      <td colspan="2">
                        <strong>I agree to do the X-Ray on my responsibility</strong><br />
                        <span id="o_ord_xray" class="text-ontime">--</span>
                      </td>
                    </tr>
                  </table>
                </div>
                <div id="payment_block">
                  <h5><strong>PAYMENT DETAILS</strong></h5>
                  <table class="table">
                    <tr>
                      <td>
                        <strong>Payment Status:</strong><br />
                        <span id="o_ord_pay_status" class="text-ontime">--</span>
                      </td>
                      <td>
                        <strong>Approval code:</strong><br />
                        <span id="o_ord_approval_code" class="text-ontime">--</span>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <strong>Card Number</strong><br />
                        <span id="o_ord_card_number" class="text-ontime">--</span>
                      </td>
                      <td>
                        <strong>Card Expiry</strong><br />
                        <span id="o_ord_card_expiry" class="text-ontime">--</span>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <strong>Card Brand</strong><br />
                        <span id="o_ord_card_brand" class="text-ontime">--</span>
                      </td>
                      <td>
                        <strong>Unique ID</strong><br />
                        <span id="o_ord_unique_id" class="text-ontime">--</span>
                      </td>
                    </tr>
                  </table>
                </div>
                <div id="attachments_block">
                  <h5><strong>ORDER ATTACHMENTS</strong></h5>
                  <table class="table" id="attachment_data">
                    <tr><td>No attachments found</td></tr>
                  </table>
                </div>
                <div id="mrn_block">
                  <h5><strong>UPDATE MEDICAL REFERENCE NUMBER</strong></h5>
                  <form accept="<?php echo base_url();?>orders/baraha" method="post">
                    <label>Medical Reference Number</label>
                    <input required="" type="text" class="form-control" name="med_number" value=""/>
                    <br />
                    <label>Ref2</label>
                    <input required="" type="text" class="form-control" name="ref2" value=""/>
                    <label><br /><br /></label>
                    <input type="hidden" name="item_id" value="<?php echo base_url(); ?>" id="item_id">
                    <input type="submit" class="btn btn-primary" name="submit_mrn" id="mrn_update">
                  </form>
                  <br /><br />
                </div>
            </div>
        </div>
        <div class="card" id="no_card">
          <div class="card-header">
              <h5 class="card-title">PLEASE SELECT ORDER</h5>
          </div>
          <div class="card-body">
            Please select the order to view the information
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
    $('#datatable_length').hide();
    $('#datatable_filter input').attr('style','width="100%"');
    var oTable = $('#datatable').dataTable
          ({
              "language": { search: '', searchPlaceholder: "Search..." },
              "lengthMenu"      : [ [ 10, 25, 50,100, -1], [10,25, 50,100,"All"] ],
              "bLengthChange" : false,
              "sScrollX"        : "100%", 
              "scrollY"         : "600px",
              "scrollCollapse"  : true,
              "sScrollXInner"   : "100%",
              "bProcessing"     : true,
              //"serverSide"      : true,
              "responsive"      : true,
              "sAjaxSource"     : '<?php echo base_url(); ?><?php echo $dataTableUrl;?>',
              "bJQueryUI"       : false,
              "sPaginationType" : "simple",
              "iDisplayStart "  : 20,
              "aaSorting"       : [],
                  "oLanguage"   : {
              "sProcessing"     :   "<img src='<?php echo base_url(); ?>global/assets/ajax-loader_dark.gif'>"
                                  },

              "columns": [{ "orders": "id" }],
              "fnInitComplete": function()
               {
                  //oTable.fnAdjustColumnSizing();
                  $("#datatableit_filter").detach().appendTo('#newSearch');
               },
              'fnServerData': function(sSource, aaData, fnCallback)
              {
                $.ajax
                ({
                  'dataType': 'json',
                  'type'    : 'POST',
                  'url'     : sSource,
                  'data'    : aaData,
                  'success' : fnCallback
                });
              }
          });

          

</script>