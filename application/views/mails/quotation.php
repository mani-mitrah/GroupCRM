<!doctype html>
<html>

<head>
  <meta name="viewport" content="width=device-width">
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <link href="<?php echo base_url();?>/assets_new/main.5fb56474af9319a1be42.css" rel="stylesheet">

  <script src="<?php echo base_url();?>/global/js/jquery-3.6.0.min.js"></script>
  <script src="<?php echo base_url();?>/assets_new/node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets_new/node_modules/sweetalert2/dist/sweetalert2.min.css">
  <script src="<?php echo base_url(); ?>assets_new/node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>
  <title>OnTime CRM - Quotation</title>
  <style>
    @media only screen and (max-width: 620px) {
      table[class=body] h1 {
      font-size: 28px !important;
      margin-bottom: 10px !important;
    }

    table[class=body] p,
    table[class=body] ul,
    table[class=body] ol,
    table[class=body] td,
    table[class=body] span,
    table[class=body] a {
      font-size: 16px !important;
    }

    table[class=body] .wrapper,
    table[class=body] .article {
      padding: 10px !important;
    }

    table[class=body] .content {
      padding: 0 !important;
    }

    table[class=body] .container {
      padding: 0 !important;
      width: 100% !important;
    }

    table[class=body] .main {
      border-left-width: 0 !important;
      border-radius: 0 !important;
      border-right-width: 0 !important;
    }

    table[class=body] .btn table {
      width: 100% !important;
    }

    table[class=body] .btn a {
      width: 100% !important;
    }

    table[class=body] .img-responsive {
      height: auto !important;
      max-width: 100% !important;
      width: auto !important;
    }
    }

    @media all {
      .ExternalClass {
      width: 100%;
    }

    .ExternalClass,
    .ExternalClass p,
    .ExternalClass span,
    .ExternalClass font,
    .ExternalClass td,
    .ExternalClass div {
      line-height: 100%;
    }

    .apple-link a {
      color: inherit !important;
      font-family: inherit !important;
      font-size: inherit !important;
      font-weight: inherit !important;
      line-height: inherit !important;
      text-decoration: none !important;
    }

    #MessageViewBody a {
      color: inherit;
      text-decoration: none;
      font-size: inherit;
      font-family: inherit;
      font-weight: inherit;
      line-height: inherit;
    }

    .btn-primary table td:hover {
      background-color: #34495e !important;
    }

    .btn-primary a:hover {
      background-color: #34495e !important;
      border-color: #34495e !important;
    }
    }

    .buttons a {
      opacity: 0.4;
    }
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
</head>

<body class=""
  style="background-color: #f6f6f6; font-family: sans-serif; -webkit-font-smoothing: antialiased; font-size: 14px; line-height: 1.4; margin: 0; padding: 0; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;">
  <table border="0" cellpadding="0" cellspacing="0" class="body"
    style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; background-color: #f6f6f6;">
    <tr>
      <td style="font-family: sans-serif; font-size: 14px; vertical-align: top;">&nbsp;</td>
      <td class="container"
        style="font-family: sans-serif; font-size: 14px; vertical-align: top; display: block; Margin: 0 auto; max-width: 780px; padding: 10px; width: 780px;">
        <div class="content"
          style="box-sizing: border-box; display: block; Margin: 0 auto; max-width: 780px; padding: 10px;">

          <!-- START CENTERED WHITE CONTAINER -->
          <table class="main"
            style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; background: #ffffff; border-radius: 3px;">

            <!-- START MAIN CONTENT AREA -->
            <tr>
              <td class="wrapper"
                style="font-family: sans-serif; font-size: 14px; vertical-align: top; box-sizing: border-box; padding: 20px;">
                <table border="0" cellpadding="0" cellspacing="0"
                  style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;">
                  <tr>
                    <td colspan="2">
                      <table style="width: 100%">
                        <td style="width: 33.33%" align="left">
                          <img src="https://crm.ontimegroup.com/assets/images/golden_cube_logo.png" width="158px">
                        </td>
                        <td style="width: 33.33%" align="center">
                          <h2>QUOTATION</h2>
                        </td>
                        <td style="width: 33.33%"></td>
                      </table>
                    </td>
                  <tr>
                  <tr>
                    <td align="left">
                      <?php
                      if(isset($created_at)){
                        ?>
                        <strong>DATE:</strong> <?php echo date('d/m/Y',strtotime($created_at)); ?><br />
                        <?php
                      }
                      else {
                      ?>
                      <strong>DATE:</strong> <?php echo date('d/m/Y'); ?><br />
                      <?php } ?>

                    </td>
                    <td align="right">
                      <strong>QUOTE NO:</strong> <?php echo $reference; ?><br />
                      <?php if ($so_order) { ?><strong>REF #:</strong> <?php echo $so_order;
                                                                      } ?><br />


                    </td>
                  </tr>
                  <tr>
                    <td><br /><br /></td>
                    <td><br /><br /></td>
                  </tr>
                  <tr>
                    <td></td>
                  </tr>
                  <tr>
                    <td colspan="2">
                      <p align="left" style="border-bottom: 2px solid #ddd;"><strong>Quotation To</strong></p>
                      <strong>Name</strong> - <?php echo $name; ?>,<br />
                      <strong>Email</strong> - <?php echo $email; ?><br />
                      <?php if ($mobile != "") { ?><strong>Contact</strong> - <?php echo $mobile;} ?><br />
                    <strong>Package Name</strong> - <?php echo $details[0]["package_name"]; ?><br />
                    <strong>Payment Type</strong> - <?php echo $payment_type; ?><br />
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <?php
                      echo $message;
                      ?>
                    </td>
                  </tr>
                  <tr>
                    <td colspan="2">
                    </td>
                  </tr>

                </table>
              </td>
            </tr>
            <tr>
              <?php
              // print_r($details);
              if (isset($details)) {
              ?>
            <tr>
              <td colspan="4" style="padding: 20px;">
                <p>Details</p>
                <table cellpadding='10' width='100%' cellspacing='0' border='1' border-color='#eee'>
                  <thead>
                    <tr>
                      <th style='background-color:#02297d;color: white;'>S.no</th>
                      <th style='background-color:#02297d;color: white;'>Service(s)</th>
                      <th style='background-color:#02297d;color: white;'>Govt Fee</th>
                      <th style='background-color:#02297d;color: white;'>Typing Fee</th>
                      <th style='background-color:#02297d;color: white;text-align: center;'>Qty</th>
                      <th style='background-color:#02297d;color: white;text-align: right'>SubTotal</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $i = 1;
                    $total_subtotal = 0;
                    $cardAmount = 0;
                    foreach ($details as $det) { ?>
                    <tr>
                      <td><?php echo $i; ?></td>
                      <td><?php echo $det["service_name"]; ?></td>
                      <td style="text-align: center"><?php echo $det["govt_fee"]; ?></td>
                      <td style="text-align: center"><?php echo $det["typing_fee"]; ?></td>
                      <td style="text-align: center"><?php echo $det["qty"]; ?></td>
                      <td style="text-align: right">
                        <?php //echo number_format((($det["govt_fee"] + $det["card_amount"] + $det["typing_fee"]) * $det["qty"]), 2); ?>
                        <?php echo number_format(($det["sub_total"]*$det["qty"]) - ($det["card_amount"]*$det["qty"]), 2); ?>
                        AED
                      </td>
                    </tr>
                    <?php
                      $i = $i + 1;
                      $total_subtotal += $det["sub_total"] * $det["qty"];
                      $cardAmount += ($det["card_amount"]*$det["qty"]);
                    } ?>
                     <!-- <tr>
                        <td colspan='4' align='right'><b>Service Fee</b></td>
                        <td align='right'><?php echo number_format($admin_charges, 2) . " AED"; ?></td>
                      </tr> -->
                      <?php
                      // $cardAmount = $cardAmount + $det["card_amount"];
                      ?>
                          <tr>
                            <td colspan='5' align='right'><b>Card Amount</b></td>
                            <td align='right'><?php echo number_format($cardAmount, 2) . " AED"; ?></td>
                          </tr>
                          <tr>
                            <td colspan='5' align='right'><b>Total</b></td>
                            <td align='right'><?php echo number_format($total_subtotal, 2) . " AED"; ?></td>
                          </tr>
                  </tbody>
                </table>
              </td>
            </tr>
            <?php }

          ?>
    </tr>
    <tr>

      <td>
        <table width="100%" style="text-align: center;margin: 10px 20px;">
          <?php if (!isset($this->auth_user_id)) { ?>
          <tr>
            <td>
              <div class="form-check text-left mb-3">
                <label class="form-check-label">
                  <input type="checkbox" class="form-check-input" name="" id="terms" value="checkedValue">
                  I hereby acknowledge that I have read and understood the <a href="#"
                    class="font-weight-bold terms">terms and conditions</a> as provided by
                  Golden cube and I agree to all the terms.
                </label>
              </div>
            </td>
          </tr>
          <tr>
            <td>
              <div class="actions buttons">
                <?php
              if ($lead["lead_status"] == 642 || $lead["lead_status"] == 310 || $lead["lead_status"] == 304) {
                ?>
                <a id="acceptBtn"
                  data-href='<?php echo base_url(); ?>/leads/lead/quotationCallback?token=<?php echo encrypt_decrypt($lead_id); ?>&action=accept&ref=<?php echo encrypt_decrypt($action_id); ?>'><button
                    style="font-weight: bold;background: #02297d;color: white;cursor: pointer;border: 3px solid #02297d;padding: 13px 30px;border-radius: 1px;box-shadow: 2px 2px 9px -2px black;">ACCEPT</button></a>

                <a
                  data-href='<?php echo base_url(); ?>/leads/lead/quotationCallback?token=<?php echo encrypt_decrypt($lead_id); ?>&action=decline&ref=<?php echo encrypt_decrypt($action_id); ?>'><button
                    style='font-weight: bold;background: #000000;margin-left: 30px;cursor: pointer;color: white;border: 3px solid black;padding: 13px 30px;border-radius: 1px;box-shadow: 2px 2px 9px -2px black;'>DECLINE</button></a>
                <?php
              }
              if ($lead["lead_status"] == 643) {
                ?>
                <a
                  data-href='<?php echo base_url(); ?>/leads/lead/quotationCallback?token=<?php echo encrypt_decrypt($lead_id); ?>&action=paynow&ref=<?php echo encrypt_decrypt($action_id); ?>'><button
                    style="font-weight: bold;background: #02297d;color: white;cursor: pointer;border: 3px solid #02297d;padding: 13px 30px;border-radius: 1px;box-shadow: 2px 2px 9px -2px black;">PAY
                    NOW</button></a>
                <?php
              }
              ?>
              </div>
            </td>
            <!-- <td>
      <a href='<?php //echo $_SERVER["HTTP_ORIGIN"]; 
        ?>/leads/lead/quotationCallback?token=<?php //echo encrypt_decrypt($lead_id);
          ?>&action=accept_paynow'><button style='font-weight: bold;background: #02297d;color: white;border: 0;padding: 13px 30px;border-radius: 1px;box-shadow: 2px 2px 9px -2px black;'>ACCEPT
          & PAY NOW</button></a>
    </td> -->


          </tr>
          <?php } ?>
          <tr>
            <td colspan="3">
              <?php
              // Get the current date and time
              $currentDateTime = new DateTime($created_at);
              $currentDate = $currentDateTime->format('Y-m-d H:i:s');

              // Calculate the date and time after 7 days
              $futureDateTime = clone $currentDateTime;
              $futureDateTime->modify('+2 days');
              $futureDate = $futureDateTime->format('Y-m-d H:i:s');
              ?>
              <br></br>
              <div>
                <p style="white-space: pre-line;">Note: These above links buttons will be expired on
                  <b><?php echo $futureDate; ?></b>. So please use this actions on or before that.</p>
              </div>
            </td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td style="background: #02297d"><br /></td>
    </tr>

    <!-- END MAIN CONTENT AREA -->
  </table>

  <!-- END CENTERED WHITE CONTAINER -->
  </div>
  </td>
  <td style="font-family: sans-serif; font-size: 14px; vertical-align: top;">&nbsp;</td>
  </tr>

  </table>
  <!-- Button trigger modal -->

    <!-- Modal -->
  <div class="modal fade" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title w-100">
            <div class="w-100 d-flex justify-content-between">
              <div>Terms & Conditions</div>
              <div>الشروط واألحكام</div>
            </div>
          </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th style="width: 50%;">Undertakings and Declarations </th>
                    <th style="text-align: right;">إقرارات و تعهدات</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>
                      I, the applicant, acknowledge that I will abide by the instructions for applying for residency in the Emirate of Dubai in the United Arab Emirates, as stated in this application at the Land and Property Department Centre “Golden Cube”, which is licensed to provide services to real estate investors and their companions and related transactions. 
                    </td>
                    <td>
                      <div lang="ar" dir="rtl" style="font-family: initial;font-size: 14px; font-weight: normal; Margin-bottom: 15px;text-align: right;">
                        <p>أقر أنا مقدم الطلب أن ألتزم بتعليمات التقديمللحصول ع	 الإقامة 	 إمارة د	 بدولة الإمارات الع بية المتحدة، الموضحة بهذا الطلب لدى مركز دائرة الأراضي والأملاك "قولدن كيوب"، المرخص لتقديم خدمات المستثم ين العقا يين ومرافقيهم والمعاملات ذات الصلة. </p>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      I undertake to provide all requested documents and information fully and accurately.
                    </td>
                    <td>
                      <div lang="ar" dir="rtl" style="font-family: initial;font-size: 14px; font-weight: normal; Margin-bottom: 15px;text-align: right;">
                        <p>أتعهد بتقديم كافة المستندات والمعلوماتالمطلوبة بشكل صحيح وكامل.</p>
                      </div>
                    </td>   
                  </tr>
                  <tr>
                    <td>
                      In the case of applying for a Golden Visa, I confirm I am inside the UAE and undertake not to leave the country nor allow any dependent to leave until the Emirates ID is issued and residency is approved. 
                    </td>
                    <td>
                      <div lang="ar" dir="rtl" style="font-family: initial;font-size: 14px; font-weight: normal; Margin-bottom: 15px;text-align: right;">
                        <p> حال التقديم ع	 تأشيرة ذهبية، أقر بأنني متواجد داخل الدولة وأتعهد بعدم مغادرتها أو أي مرافق 	 إ	 حين طباعة الهوية والموافقة ع	 الإقامة. </p>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      I undertake not to visit the Centre unless explicitly instructed via official email or phone communication. 
                    </td>
                    <td>
                      <div lang="ar" dir="rtl" style="font-family: initial;font-size: 14px; font-weight: normal; Margin-bottom: 15px;text-align: right;">
                        <p>أتعهد بعدم يارة المركز إلا عند الطلب من قبلالمركز بواسطة الاتصال أو الب يد الإلكترو . </p>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      I undertake to pay all administrative and government fees in advance and in UAE Dirhams only. 
                    </td>
                    <td>
                      <div lang="ar" dir="rtl" style="font-family: initial;font-size: 14px; font-weight: normal; Margin-bottom: 15px;text-align: right;">
                        <p>أتعهد بدفع كافة الرسوم الإدا ية والحكومية مقدمًا وبعملة الدرهم الإمارا . </p>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      I undertake to settle all fines or penalties arising from my actions or delays. 
                    </td>
                    <td>
                      <div lang="ar" dir="rtl" style="font-family: initial;font-size: 14px; font-weight: normal; Margin-bottom: 15px;text-align: right;">
                        <p>أتعهد بسداد أي غرامات أو عقوبات ناتجة عن أفعا أو تأخيري.  </p>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <p>
                        I confirm that admin fees and service charges are non-refundable. Only government fees for unrendered services may be refunded within 30 days. I acknowledge I am not entitled to claim any admin or government fees in case of service rejection by the relevant government authority. 
                      </p>
                      <p>
                        However, in immigration-related applications, partial refunds of application fees may be permitted strictly as per the immigration authority's refund policy and applicable timelines. 
                      </p>
                    </td>
                    <td>
                      <div lang="ar" dir="rtl" style="font-family: initial;font-size: 14px; font-weight: normal; Margin-bottom: 15px;text-align: right;">
                        <p>أقر بأن الرسوم الإدا ية ورسوم الخدمات غير قابلة للاسترداد. وأي استرداد سيكون فقط للرسوم الحكومية للخدمات التي لم يتم تقديمها، وذلك خلال 30 يومًا فقط. كما أقر بأنه لا يحق 	 المطالبة بأي رسوم إدا ية أو حكومية 	 حال رفض الخدمة من قبل الجهة الحكومية. </p>
                        <p>مع ذلك ،	 حالات طلبات الهجرة فقط، يمكن استرداد جزء من رسوم الطلب وفقًا لسياسة الاسترداد المعتمدة لدى الجهة المختصة وضمن الفترة الزمنية المحددة من قبلهم. </p>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      I undertake not to request fees if transactions are processed via any centre other than "Golden Cube." 
                    </td>
                    <td>
                      <div lang="ar" dir="rtl" style="font-family: initial;font-size: 14px; font-weight: normal; Margin-bottom: 15px;text-align: right;">
                        <p>أتعهد بعدم المطالبة بأي رسوم حال تقديم المعاملات من مركز غير "قولدن كيوب." </p>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      I acknowledge the Dubai Land Department Committee as the sole dispute resolution authority and accept all terms as a legally binding agreement. 
                    </td>
                    <td>
                      <div lang="ar" dir="rtl" style="font-family: initial;font-size: 14px; font-weight: normal; Margin-bottom: 15px;text-align: right;">
                        <p>أقر بأن لجنة دائرة الأراضي والأملاك 	 د	 هي الجهة المختصة لفصل النزاعات وأنني ملتزم قانونيًا بهذه الإقرارات. </p>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      I acknowledge that all the terms and conditions stated above apply to any additional services provided by the Golden Cube Centre.
                    </td>
                    <td>
                      <div lang="ar" dir="rtl" style="font-family: initial;font-size: 14px; font-weight: normal; Margin-bottom: 15px;text-align: right;">
                        <p>أقر بأن جميع الشروط والأحكام المذكورة أعلاه تنطبق ع	 أي خدمات إضافية يقدمها مركز قولدن كيوب. </p>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
              
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modalOTP" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="modelTitleId">
    <div class="modal-dialog">
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
  <script>
    let payment_type = '<?php echo $payment_type; ?>';
    let customer_name = `<?php echo $name; ?>`;
    let customer_email = '<?php echo $email; ?>';
    let customer_mobile = '<?php echo $mobile; ?>';
    let user_id = '<?php echo $user_pos_id; ?>';
    let user_email = '<?php echo $user_email; ?>';
    user_email = "Gowtham@egovllc.com";

    $(".terms").click(function (e) {
      e.preventDefault();
      $("#modelId").modal();
    });

    $("#terms").change(function () {
      var prop = $(this).prop("checked");
      console.log(prop);
      if (prop == true) {
        $(".actions").removeClass("buttons");
      } else {
        $(".actions").addClass("buttons");
      }
    });

    
    $("[data-href]").click(function (e) {
      e.preventDefault();
      var terms = $("#terms").prop("checked");
      
      if (terms) {
        var href = $(this).data("href");
        location.href = href;
      } else {
        alert('Please accept the terms and conditions');
      }
    });

    $(document).ready(function() {
      $('.otp-input').on('input', function() {
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
          for (var i = 1; i <= 6; i++) {
              otp += $('#otp' + i).val();
          }
          if (otp.length === 6) {
              $.ajax({
                  "url": "<?php echo getenv('POS_URL'); ?>/api/ApiPos/ValidateCrmOTP",
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
                      if (res.ResponseCode == 0) {
                        $('#modalOTP').modal('hide');
                        let link = $('a#acceptBtn').data("href");
                        link = link + "&OTP="+otp;
                        location.href = link;
                      } else if (res.ResponseCode == 1) {
                          Swal.close();
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
              Swal.fire('Error!', "Enter Valid 6-digit OTP.<br>'" + pastedData + "' is not allowed.", 'error');
              return;
          }
          var inputs = $('.otp-input');

          $.each(pastedData.split(''), function(index, value) {
              if (index < inputs.length) {
                  $(inputs[index]).val(value).focus();
              }
          });
      });

      $('a#acceptBtn').click(function(e) {
        if(payment_type == "cash" && false){
          e.preventDefault();
          $.ajax({
            "url": "<?php echo getenv('POS_URL'); ?>/api/ApiPos/CrmPaymentOtpVerfication",
            "method": "POST",
            beforeSend: function() {
                Swal.fire({
                    // text: "Sending OTP to customer Mobile or Email",
                    didOpen: function() {
                        Swal.enableLoading();
                    }
                })
            },
            "data": {
                "type": "mobile",
                "customer_email": customer_email,
                "customer_mobile": '971' + customer_mobile,
                "customer_name": customer_name,
                "customer_id": "0",
                "user_id": user_id,
                "user_email": user_email
            },
            success: function(response) {
                let res = response;
                if (res.ResponseCode == 0) {
                    Swal.close();
                    $("#modalOTP").modal();
                } else if (res.ResponseCode == 1) {
                    Swal.fire('Error!', res.ResponseMsg, 'error');
                }
            },
            error: function(xhr, status, error) {
                Swal.close();
                Swal.fire('Error!', 'Something went wrong with the request.', 'error');
            }
          });
          return false;
        }
      })
    });
  </script>
</body>

</html>