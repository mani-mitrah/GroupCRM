<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta name="viewport" content="width=device-width" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Slot Booked and Payment Done</title>
    <link href="styles.css" media="all" rel="stylesheet" type="text/css" />
    <style type="text/css">
        /* -------------------------------------
    GLOBAL
------------------------------------- */
        * {
            margin: 0;
            padding: 0;
            font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif;
            box-sizing: border-box;
            font-size: 14px;
        }

        img {
            max-width: 100%;
        }

        body {
            -webkit-font-smoothing: antialiased;
            -webkit-text-size-adjust: none;
            width: 100% !important;
            height: 100%;
            line-height: 1.6;
        }

        /* Let's make sure all tables have defaults */
        table td {
            vertical-align: top;
        }

        /* -------------------------------------
    BODY & CONTAINER
------------------------------------- */
        body {
            background-color: #f6f6f6;
        }

        .body-wrap {
            background-color: #f6f6f6;
            width: 100%;
        }

        .container {
            display: block !important;
            max-width: 600px !important;
            margin: 0 auto !important;
            /* makes it centered */
            clear: both !important;
        }

        .content {
            max-width: 600px;
            margin: 0 auto;
            display: block;
            padding: 20px;
        }

        /* -------------------------------------
    HEADER, FOOTER, MAIN
------------------------------------- */
        .main {
            background: #fff;
            border: 1px solid #e9e9e9;
            border-radius: 3px;
        }

        .content-wrap {
            padding: 20px;
        }

        .content-block {
            padding: 0 0 20px;
        }

        .header {
            width: 100%;
            margin-bottom: 20px;
        }

        .footer {
            width: 100%;
            clear: both;
            color: #999;
            padding: 20px;
        }

        .footer a {
            color: #999;
        }

        .footer p,
        .footer a,
        .footer unsubscribe,
        .footer td {
            font-size: 12px;
        }

        /* -------------------------------------
    GRID AND COLUMNS
------------------------------------- */
        .column-left {
            float: left;
            width: 50%;
        }

        .column-right {
            float: left;
            width: 50%;
        }

        /* -------------------------------------
    TYPOGRAPHY
------------------------------------- */
        h1,
        h2,
        h3 {
            font-family: "Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif;
            color: #000;
            margin: 40px 0 0;
            line-height: 1.2;
            font-weight: 400;
        }

        h1 {
            font-size: 32px;
            font-weight: 500;
        }

        h2 {
            font-size: 24px;
        }

        h3 {
            font-size: 18px;
        }

        h4 {
            font-size: 14px;
            font-weight: 600;
        }

        p,
        ul,
        ol {
            margin-bottom: 10px;
            font-weight: normal;
        }

        p li,
        ul li,
        ol li {
            margin-left: 5px;
            list-style-position: inside;
        }

        /* -------------------------------------
    LINKS & BUTTONS
------------------------------------- */
        a {
            color: #348eda;
            text-decoration: underline;
        }

        .btn-primary {
            text-decoration: none;
            color: #FFF;
            background-color: #348eda;
            border: solid #348eda;
            border-width: 10px 20px;
            line-height: 2;
            font-weight: bold;
            text-align: center;
            cursor: pointer;
            display: inline-block;
            border-radius: 5px;
            text-transform: capitalize;
        }

        /* -------------------------------------
    OTHER STYLES THAT MIGHT BE USEFUL
------------------------------------- */
        .last {
            margin-bottom: 0;
        }

        .first {
            margin-top: 0;
        }

        .padding {
            padding: 10px 0;
        }

        .aligncenter {
            text-align: center;
        }

        .alignright {
            text-align: right;
        }

        .alignleft {
            text-align: left;
        }

        .clear {
            clear: both;
        }

        /* -------------------------------------
    Alerts
------------------------------------- */
        .alert {
            font-size: 16px;
            color: #fff;
            font-weight: 500;
            padding: 20px;
            text-align: center;
            border-radius: 3px 3px 0 0;
        }

        .alert a {
            color: #fff;
            text-decoration: none;
            font-weight: 500;
            font-size: 16px;
        }

        .alert.alert-warning {
            background: #ff9f00;
        }

        .alert.alert-bad {
            background: #d0021b;
        }

        .alert.alert-good {
            background: #68b90f;
        }

        /* -------------------------------------
    INVOICE
------------------------------------- */
        .invoice {
            margin: 40px auto;
            text-align: left;
            width: 80%;
        }

        .invoice td {
            padding: 5px 0;
        }

        .invoice .invoice-items {
            width: 100%;
        }

        .invoice .invoice-items td {
            border-top: #eee 1px solid;
        }

        .invoice .invoice-items .total td {
            border-top: 2px solid #333;
            border-bottom: 2px solid #333;
            font-weight: 700;
        }

        /* -------------------------------------
    RESPONSIVE AND MOBILE FRIENDLY STYLES
------------------------------------- */
        @media only screen and (max-width: 640px) {

            h1,
            h2,
            h3,
            h4 {
                font-weight: 600 !important;
                margin: 20px 0 5px !important;
            }

            h1 {
                font-size: 22px !important;
            }

            h2 {
                font-size: 18px !important;
            }

            h3 {
                font-size: 16px !important;
            }

            .container {
                width: 100% !important;
            }

            .content,
            .content-wrapper {
                padding: 10px !important;
            }

            .invoice {
                width: 100% !important;
            }
        }
    </style>
</head>

<body>

    <table class="body-wrap">
        <tr>
            <td></td>
            <td class="container" width="600">
                <div class="content">
                    <table class="main" cellpadding="0" cellspacing="0">
                        <tr>
                            <td align="center">
                                <br />
                            </td>
                        </tr>
                        <tr>
                            <td class="content-wrap">
                                <center>
                                    <img src="https://ontimegov.com/digital/assets/img/english/gov-logo.png" alt="Logo" />
                                </center>
                                <table width="100%" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td class="content-block">
                                            <strong><?php echo "Dear " . htmlspecialchars($name); ?></strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="content-block">
                                            We are happy to confirm that your payment has been successfully received,
                                            and your appointment is now fully <strong>confirmed.</strong> Please find
                                            your payment receipt attached for your records.<br />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Appointment Details:</strong></td><br />
                                    </tr>
                                    <tr>
                                        <td>
                                            <ul>
                                                <li style="padding: 5px 0;"><strong>Booking Reference Number:</strong>
                                                    <?php echo htmlspecialchars($booking_reference_number); ?></li>
                                                <li style="padding: 5px 0;"><strong>Service/Product:</strong>
                                                    <?php echo htmlspecialchars($service_name . '/' . $package_name); ?></li>
                                                <li style="padding: 5px 0;"><strong>Date and Time:</strong>
                                                    <?php echo htmlspecialchars($date) . " at " . htmlspecialchars($time); ?></li>
                                                <li style="padding: 5px 0;"><strong>Location:</strong>
                                                    <?php echo htmlspecialchars($location); ?></li>
                                                <li style="padding: 5px 0;"><strong>Total Amount Paid:</strong>
                                                    <?php echo htmlspecialchars($total_amount); ?> AED</li>
                                                <li style="padding: 5px 0;"><strong>Payment Status:</strong> Paid in Full</li>
                                            </ul>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <?php
                                            $url = "https://ontimesmartpos.net/api/ApiPos/GetReceiptReportAPI/{$pos_pmt_number}/54";
                                            ?>
                                            <span style="font-weight: bold;">Payment Receipt:</span>
                                            <a href="<?php echo $url; ?>" style="margin-left: 5px; text-decoration: none; color: #007BFF;">
                                                Download Receipt
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="content-block">
                                            We look forward to serving you on <?php echo htmlspecialchars($date); ?>. If you have any
                                            questions about your receipt or need further assistance, feel free to
                                            contact us at <a href="mailto:mobile.medical@ontimegov.com">mobile.medical@ontimegov.com</a>.<br /><br />
                                            Thank you for choosing <strong>Mobile Medical Examination,</strong> and we look forward to
                                            seeing you!<br /><br />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="content-block">
                                            Best regards,<br />
                                            Team Ontime<br />
                                            <a href="https://ontimegov.com/digital">Ontime Gov</a><br />
                                            <a href="mailto:mobile.medical@ontimegov.com">mobile.medical@ontimegov.com</a>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                    <div class="footer">

                    </div>
                </div>
            </td>
            <td></td>
        </tr>
    </table>

</body>

</html>