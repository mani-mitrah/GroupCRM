<!doctype html>
<html>

<head>
  <meta name="viewport" content="width=device-width">
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <title>Payment Email</title>
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
  </style>
</head>

<body class="" style="background-color: #f6f6f6; font-family: sans-serif; -webkit-font-smoothing: antialiased; font-size: 14px; line-height: 1.4; margin: 0; padding: 0; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;">
  <table border="0" cellpadding="0" cellspacing="0" class="body" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; background-color: #f6f6f6;">
    <tr>
      <td style="font-family: sans-serif; font-size: 14px; vertical-align: top;">&nbsp;</td>
      <td class="container" style="font-family: sans-serif; font-size: 14px; vertical-align: top; display: block; Margin: 0 auto; max-width: 780px; padding: 10px; width: 780px;">
        <div class="content" style="box-sizing: border-box; display: block; Margin: 0 auto; max-width: 780px; padding: 10px;">

          <!-- START CENTERED WHITE CONTAINER -->
          <table class="main" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; background: #ffffff; border-radius: 3px;">

            <!-- START MAIN CONTENT AREA -->
            <tr>
              <td class="wrapper" style="font-family: sans-serif; font-size: 14px; vertical-align: top; box-sizing: border-box; padding: 20px;">
                <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;">
                  <tr>
                    <td align="left">
                      <h2>RECEIPT</h2>
                    </td>
                    <td align="right">
                      <?php
                      if ($branch_id == 106 && $main_service_type != 23) {
                      ?>
                        <img src="https://ontimegov.com/assets/goldencube/GoldenCube.png" width="158px">
                      <?php
                      } else if ($branch_id == 109) {
                      ?>
                        <img src="https://crm.ontimegroup.com/global/images/otlab_158.png" width="158px" />
                      <?php
                      } else {
                      ?>
                        <img src="https://crm.ontimegroup.com/global/images/otg_158.png" width="158px" />
                      <?php
                      }
                      ?>
                    </td>
                  <tr>
                  <tr>
                    <td align="left">
                      <strong>PAYMENT DATE:</strong> <?php if(isset($action)){ echo date('d/m/Y',strtotime($action["created_at"]));}else { echo date('d/m/Y');} ?><br />
                    </td>
                    <td align="right">
                      <strong>RECEIPT NO:</strong> <?php echo $reference; ?><br />
                      <?php if ($so_order) { ?><strong>ORDER REF #:</strong> <?php echo $so_order;
                                                                            } ?><br />
                      <?php if ($payment_type) { ?><strong>PAYMENT TYPE :</strong> <?php echo $payment_type;
                                                                                  } else { ?><strong>PAYMENT TYPE :</strong> <?php echo "ONLINE";
                                                                                                                            } ?>

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
                      <p align="left" style="border-bottom: 2px solid #ddd;"><strong>Bill To</strong></p>
                      <?php if($is_corporate == 'Corporate'){ ?>
                        <strong>Applicant Name</strong> - <?php echo $applicant_name; ?>,<br />
                      <?php } ?>
                      <strong><?php $is_corporate == 'Corporate'?"Corporate ":"";?>Name</strong> - <?php echo $name; ?>,<br />
                      <strong>Email</strong> - <?php echo $email; ?><br />
                      <?php if ($mobile != "") { ?><strong>Contact</strong> - <?php echo $country_code . ' '. $mobile; } ?>
                    </td>
                  </tr>
                  <tr>
                    <td><br /><br /></td>
                  </tr>
                  <tr>
                    <td colspan="2">
                      <br />
                      <?php $nacost=0;
                      if (isset($details)) {
                        if (count($details) > 0) {
                          foreach ($details as $dtm) {
                            $nacost=$nacost+($dtm["govt_fee"] + $dtm["card_amount"] + $dtm["typing_fee"]) * $dtm["qty"];   
                          } 
                        }
                      } ?>
                      <table cellpadding='10' width='100%' cellspacing='0' border='1' border-color='#eee'>
                        <thead>
                          <tr>
                            <th style='background-color:#00287c;color: white;'>DESCRIPTION</th>
                            <th style='background-color:#00287c;color: white;'>QTY</th>
                            <th style='background-color:#00287c;color: white;'>UNIT PRICE</th>
                            <th style='background-color:#00287c;color: white;'>TOTAL</th>
                          </tr>
                        <tbody>
                          <tr>
                            <td><?php echo $service; ?></td>
                            <td>1</td>
                            <!-- <td align='center'><?php echo $amount . " AED"; ?></td>
                            <td align='right'><?php echo $amount . " AED"; ?></td> -->
                            <td align='center'><?php echo $amount . " AED"; ?></td>
                            <td align='right'><?php echo $amount . " AED"; ?></td>
                          </tr>
                          <tr>
                            <td colspan='2'></td>
                            <td align='right'>TOTAL</td>
                            <!-- <td align='right'><?php echo $nacost . " AED"; ?></td> -->
                            <td align='right'><?php echo !empty($nacost) ? $nacost . " AED" : $amount . " AED"; ?></td>

                          </tr>
                        </tbody>
                      </table>
                    </td>
                  </tr>
                  <tr>
                    <td><br /><br /></td>
                  </tr>
                  <?php if (isset($details)) {
                    if (count($details) > 0) {
                  ?>
                      <tr>
                        <td colspan="4">
                          <p>Service Payment Details</p>
                          <table cellpadding='10' width='100%' cellspacing='0' border='1' border-color='#eee'>
                            <thead>
                              <tr>
                                <th style='background-color:#00287c;color: white;'>S.no</th>
                                <th style='background-color:#00287c;color: white;'>Service</th>
                                <th style='background-color:#00287c;color: white;'>Qty</th>
                                <th style='background-color:#00287c;color: white;'>Govt Fee(Incl CardAmt)</th>
                                <th style='background-color:#00287c;color: white;'>Typing Fee(Incl VAT)</th>
                                <th style='background-color:#00287c;color: white;'>Total</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php
                              $i = 1;
                              foreach ($details as $det) { ?>
                                <tr>
                                  <td><?php echo $i; ?></td>
                                  <td><?php echo $det["service_name"]; ?></td>
                                  <td><?php echo $det["qty"]; ?></td>
                                  <td><?php echo $det["govt_fee"] + $det["card_amount"]; ?></td>
                                  <td><?php echo $det["typing_fee"]; ?></td>
                                  <td><?php echo ($det["govt_fee"] + $det["card_amount"] + $det["typing_fee"]) * $det["qty"]; ?></td>
                                </tr>
                              <?php
                                $i = $i + 1;
                              } ?>
                            </tbody>
                          </table>
                        </td>
                      </tr>
                  <?php }
                  }
                  ?>
                </table>
              </td>
            </tr>
            <tr>
              <td>
                <p style="font-size: 70%;">Note: This is system auto generated receipt.</p>
              </td>
            </tr>
            <tr>
              <td style="background: #00287c"><br /></td>
            </tr>

            <!-- END MAIN CONTENT AREA -->
          </table>

          <!-- END CENTERED WHITE CONTAINER -->
        </div>
      </td>
      <td style="font-family: sans-serif; font-size: 14px; vertical-align: top;">&nbsp;</td>
    </tr>

  </table>
</body>

</html>