<script>
    function openInNewTab(url) {
        var newUrl = url + <?php echo date('Ymdi'); ?>;
        window.open(newUrl, '_blank').focus();
    }

    function openInNewTabs(url) {
        var randomString = generateRandomString(10);
        var newUrl = url + randomString;
        window.open(newUrl, '_blank').focus();
    }

    function generateRandomString(length) {
        var result = '';
        var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        var charactersLength = characters.length;

        for (var i = 0; i < length; i++) {
            result += characters.charAt(Math.floor(Math.random() * charactersLength));
        }

        return result;
    }
</script>
<?php
// print_r($lead_details);
// exit();
?>
<style>
    .app-inner-layout__wrapper.row-fluid.no-gutters {
        min-height: unset;
    }

    div#swal2-html-container {
        margin: 0;
    }

    pre {
        font-size: unset !important;
    }

    .action-column {
        display: none;
    }

    .service-row:nth-child(1) .action-column,
    .service-row:nth-last-child(1) .action-column {
        display: block;
    }

    .service-row:nth-child(1) .action-column button span::after {
        content: "+"
    }

    .service-row:not(:nth-child(1)) .action-column button span::after {
        content: "-"
    }

    /* span.d-flex span:nth-child(1) {
        width: 100px;
    } */

    span.select2-selection.select2-selection--single {
        height: 40px;
        padding: 4px;
    }

    span.select2-selection__arrow {
        height: 40px !important;
    }

    #quotation_modal table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        table-layout: fixed;
    }

    #quotation_modal th,
    #quotation_modal td {
        border: 1px solid #ddd;
        padding: 10px;
        text-align: center;
        width: 100%;
        overflow: hidden;
    }

    .validation {
        text-align: center !important;
    }

    .is_taxable {
        border: none !important;
        width: 120px !important;
        height: 25px !important;
    }

    .is_taxable:focus {
        outline: none !important;
        box-shadow: none !important;
        border: none !important;
    }

    #quotation_modal .action-icons {
        cursor: pointer;
        padding: 5px;
        font-size: 18px;
    }

    #quotation_modal .select2-container {
        width: 100% !important;
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
<?php

// Function to calculate the time difference between two timestamps
function time_difference($start_time, $end_time)
{
    echo "<script>console.log('" . $start_time . "','" . $end_time . "')</script>";
    // Convert the timestamps to DateTime objects
    $start_time = new DateTime($start_time);
    $end_time = new DateTime($end_time);

    // Calculate the time difference in seconds
    $time_difference = $end_time->getTimestamp() - $start_time->getTimestamp();

    echo "<script>console.log('" . $time_difference . "')</script>";
    if ($time_difference == 0) {
        return "0";
    }
    if ($time_difference < 1440) {
        return round($time_difference / 600) . " minutes";
    }
    // Check if the time difference is less than 24 hours
    else if ($time_difference < 86400) {

        // Return the time difference in hours
        return round($time_difference / 3600) . " hours";

        // Check if the time difference is greater than or equal to 7 days
    } elseif ($time_difference >= 604800) {

        // Return the time difference in weeks
        return round($time_difference / 604800) . " weeks";

        // Otherwise, return the time difference in days
    } else {

        // Return the time difference in days
        return round($time_difference / 86400) . " days";
    }
}

// Example usage
// $start_time = "2023-03-08 12:00:00";
// $end_time = "2023-03-09 14:30:00";

// echo time_difference($start_time, $end_time); // Output: 26.5 hours



$fake_domain = "no";
// print_r($lead_details);
$email = $lead_details['customer_email'];
$domain = substr(strrchr($email, "@"), 1);
if ($domain == 'ontimecustomer.com') {
    $fake_domain = "yes";
}
$lead_current_status = $lead_details['lead_status'];
$branch_id = $lead_details['branch_id'];
$is_biz_lead = isset($lead_details['is_biz_lead']) ? $lead_details['is_biz_lead'] : 0;

$CI = &get_instance();
$pay = array();
$pay = $CI->app_model->get_payment_status($this->uri->segment(4));
$payst = 0;
if ($pay[0]['is_allow_payment'] == 1) {
    $payst = 1;
}

$groups = get_groups($this->auth_user_id);

//echo "<pre>";;
//print_r($lead_details);
//die();
$is_gc_eligible = 1;
if ($lead_details["lead_parent_id"] != 0 || ($lead_details["branch_id"] != 106 && $lead_details["category_id"] != 10020) || ($lead_details["total_no_subleads"] != 0 && $lead_details["total_no_subleads"] != NULL) || ($lead_details["pos_salesorder"] != NULL && $lead_details["pos_pmt_number"] != NULL)) {
    $is_gc_eligible = 0;
}
?>

<div class="app-main">
    <div class="app-main__outer">
        <div class="app-main__inner p-0">
            <div class="app-page-title">
                <div class="container fiori-container">
                    <div class="page-title-wrapper">
                        <div class="page-title-heading">
                            <div>
                                Manage Leads <?php //echo date('Y-m-d H:i');
                                                ?>
                                <div class="page-title-subheading">Customer Info update, Follow-ups, Order Converts
                                </div>
                            </div>
                        </div>
                        <div class="page-title-actions">
                            <div class="d-inline-block dropdown <?php echo $lead_current_status; ?>">





                                <?php
                                $complete_status = [305, 306];
                                // if (($lead_current_status > 303 && $lead_current_status < 305) || $lead_current_status > 306) {
                                if (!in_array($lead_current_status, $complete_status)) {
                                ?>
                                    <a href="#">
                                        <button type="button" class="btn btn-outline-primary btn-shadow btn-wide"
                                            data-toggle="modal" data-target="#subLead_modal">
                                            <span class="btn-icon-wrapper pr-1 opacity-7">
                                                <i class="fa fa-plus"></i>
                                            </span>
                                            Add Sub Lead(s)
                                        </button>
                                    </a>
                                <?php
                                }
                                ?>

                                <?php
                                if (isset($lead_details['pos_pmt_number']) && !empty($lead_details['pos_pmt_number'])) {
                                ?>
                                    <a href="#">
                                        <!-- Button trigger modal -->
                                        <button type="button" id="editOrderButton" class="btn btn-primary">
                                            Edit Order
                                        </button>
                                    </a>
                                <?php
                                }
                                ?>

                                <a href="<?php echo getenv('CRM_URL'); ?>leads/lead/manage">
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
            <div class="fiori-container">
                <div class="app-inner-layout chat-layout row justify-content-center m-0">
                    <div class="col-lg-10">
                        <div class="row justify-content-center">
                            <div class="col-lg-11">
                                <div class="card card-border card-shadow-primary mb-3 p-0 profile-responsive mt-5">
                                    <?php
                                    if (trim($this->session->flashdata('alert_cu')) != null) {

                                    ?>
                                        <div class="alert alert-<?php echo $this->session->flashdata('alert_cu'); ?>">
                                            <?php echo $this->session->flashdata('alert_message_cu');
                                            unset($_SESSION["alert_message_cu"]); ?>
                                        </div>
                                    <?php
                                    }
                                    ?>
                                    <div class="dropdown-menu-header bg-night-sky">
                                        <div class="dropdown-menu-header-inner">
                                            <div class="menu-header-image opacity-2"></div>
                                            <div class="menu-header-content btn-pane-right">
                                                <div class="avatar-icon-wrapper mr-2 avatar-icon-xl">
                                                    <div class="avatar-icon rounded"><img
                                                            src="/assets_new/images/avatars/7.jpg" alt="Avatar 5"></div>
                                                </div>
                                                <div>
                                                    <h5 class="menu-header-title">
                                                        <?php echo $lead_details['customer_name']; ?></h5>
                                                    <p class="mb-0"><?php echo $lead_details['customer_email']; ?></p>
                                                    <p class="mb-0">
                                                        <?php echo $lead_details['customer_country_code'] . ' ' . $lead_details['customer_mobile']; ?>
                                                    </p>
                                                </div>

                                                <div class="menu-header-btn-pane">
                                                    <div>
                                                        <!-- <div role="group" class="btn-group text-center">
                                                            <div class="nav">
                                                                <a href="/leads/lead/action_accepted/<?php echo $lead_details['id']; ?>" class="btn btn-lg btn-light mr-1">Accept </a>
                                                            </div>
                                                        </div> -->

                                                        <?php if ($lead_details["lead_status"] == 305 || $lead_details["lead_status"] == 306) { ?>
                                                            <div role="group" class="btn-group text-center">
                                                                <div class="nav">
                                                                    <a href="#" data-toggle="modal"
                                                                        data-target="#" class="btn btn-lg btn-light mr-1 reopen_leads">
                                                                        Reopen Lead</a>
                                                                </div>
                                                            </div>
                                                        <?php } ?>

                                                        <?php if ($lead_details["branch_id"] == 106 && $open_sublead_count == 0 && $lead_details["lead_status"] != 305 && $lead_details["lead_status"] != 306 && ($lead_details['order_receipt'] == NULL && $lead_details['order_receipt'] == '' && $lead_details['order_receipt'] != '0')) { ?>
                                                            <div role="group" class="btn-group text-center">
                                                                <div class="nav">
                                                                    <a href="#" data-toggle="modal"
                                                                        data-target="#" class="btn btn-lg btn-light mr-1 convert_leads">
                                                                        Convert Lead</a>
                                                                </div>
                                                            </div>
                                                        <?php } ?>

                                                        <?php if($lead_details["lead_status"] != 305 && $lead_details["lead_status"] != 306) { ?>
                                                        <div role="group" class="btn-group text-center">
                                                            <div class="nav">
                                                                <div data-href="/leads/lead/preview/<?php echo $lead_details['id']; ?>" class="btn btn-lg btn-light mr-1 lead_preview"> 
                                                                    Assigned to</div>
                                                            </div>
                                                        </div>
                                                        <?php } ?>

                                                        <?php if ($is_biz_lead != 1 && $show_edit_customer) { ?>

                                                            <div role="group" class="btn-group text-center">
                                                                <div class="nav">
                                                                    <a href="#tab-example-161" data-toggle="modal"
                                                                        data-target="#modelId"
                                                                        class="btn btn-lg btn-light mr-1"><i
                                                                            class="fa fa-edit mr-2"></i> Edit Customer</a>
                                                                </div>
                                                            </div>

                                                        <?php } ?>


                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="tab-content">
                                        <div class="tab-pane show active" id="tab-example-162">
                                            <div class="p-3">
                                                <table class="table">
                                                    <tr>
                                                        <td>
                                                            <strong>Lead Additional Details:</strong><br />
                                                            <div>

                                                                <?php if ($lead_details["customer_type"]) { ?><span
                                                                        class="d-flex mb-0"><span
                                                                            style="font-weight: 500;">Type:</span>
                                                                        <span><?php echo $lead_details["customer_type"]; ?></span></span>
                                                                <?php } ?>
                                                                <?php if ($lead_details["customer_address"]) { ?><span
                                                                        class="d-flex mb-0"><span
                                                                            style="font-weight: 500;">Address:</span>
                                                                        <span><?php echo $lead_details["customer_address"]; ?></span></span>
                                                                <?php } ?>
                                                                <?php if ($lead_details["alt_mobile"]) { ?><span
                                                                        class="d-flex mb-0"><span
                                                                            style="font-weight: 500;">Alter Mobile:</span>
                                                                        <span><?php echo $lead_details["alt_mobile"]; ?></span></span>
                                                                <?php } ?>
                                                                <?php if ($lead_details["alt_email"]) { ?><span
                                                                        class="d-flex mb-0"><span
                                                                            style="font-weight: 500;">Alter Email:</span>
                                                                        <span><?php echo $lead_details["alt_email"]; ?></span
                                                                            <?php } ?>
                                                                            <?php if ($lead_details["trn_no"]) { ?><span
                                                                            class="d-flex mb-0"><span
                                                                            style="font-weight: 500;">TRN:</span>
                                                                        <span><?php echo $lead_details["trn_no"]; ?></span></span>
                                                                <?php } ?>
                                                                <?php if ($lead_details["trade_no"]) { ?><span
                                                                        class="d-flex mb-0"><span
                                                                            style="font-weight: 500;">Trade No:</span>
                                                                        <span><?php echo $lead_details["trade_no"]; ?></span></span>
                                                                <?php } ?>
                                                            </div>
                                                        </td>
                                                        <?php if ($lead_details['branch_name'] == "OnTime Gov") { ?>
                                                            <td>
                                                                <strong>Receipt Number:</strong><br />
                                                                <span class="text-ontime"><?php echo $lead_details['pos_pmt_number']; ?></span>
                                                            </td>
                                                        <?php } ?>
                                                        <?php if ($lead_details['is_corporate'] == "Corporate") { ?>
                                                            <td>
                                                                <strong>Is Corporate:</strong>
                                                                <span class="text-ontime"><?php echo $lead_details['is_corporate']; ?></span>
                                                                <br/><strong>Applicant Name:</strong>
                                                                <span class="text-ontime"><?php echo $lead_details['applicant_name']; ?></span><br/>
                                                            </td>
                                                        <?php } ?>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <strong>Category:</strong><br />
                                                            <span id="o_ord_category"
                                                                class="text-ontime"><?php echo $lead_details['category_name']; ?></span>
                                                            <?php
                                                            if (($this->auth_user_role == 7 || $this->auth_user_role == 89) && in_array(77, $groups)) {
                                                            ?>
                                                                <div class="mt-2">

                                                                    <button class="convert-btn btn btn-primary">
                                                                        <?php
                                                                        if ($lead_details["category_id"] == 125) {
                                                                            echo "Convert to Normal Lead";
                                                                        } else {
                                                                            echo "Convert to Attestation Lead";
                                                                        }
                                                                        ?>
                                                                    </button>
                                                                </div>
                                                            <?php } ?>
                                                        </td>
                                                        <td>
                                                            <strong>Service Name:</strong><br />
                                                            <span id="o_ord_sub_category"
                                                                class="text-ontime"><?php echo $lead_details['service_name']; ?></span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <strong>Lead by:</strong><br />
                                                            <span id="o_ord_sub_category2"
                                                                class="text-ontime"><?php echo $lead_details['branch_name']; ?></span>
                                                        </td>
                                                        <td>
                                                            <strong>Created by:</strong><br />
                                                            <span id="o_ord_mrn"
                                                                class="text-ontime"><?php echo $lead_by_details['first_name'] . ' ' . $lead_by_details['last_name']; ?><br /><?php echo $lead_by_details['email']; ?><br /><?php echo $lead_by_details['mobile']; ?></span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <strong>Assigned Branch:</strong><br />
                                                            <span id="o_ord_sub_category2"
                                                                class="text-ontime"><?php echo $lead_details['assigned_branch']; ?></span>
                                                        </td>
                                                        <td data-href="/leads/lead/preview/<?php echo $lead_details['id']; ?>"
                                                            class="<?php if ($lead_details["lead_status"] != 305 && $lead_details["lead_status"] != 306) {
                                                                        echo "lead_preview";
                                                                    } else {
                                                                        echo "";
                                                                    } ?>">
                                                            <strong>Assigned to:</strong><br />
                                                            <span id="o_ord_mrn"
                                                                class="text-ontime"><?php echo $lead_assigned_to_details['first_name'] . ' ' . $lead_assigned_to_details['last_name']; ?><br /><?php echo $lead_assigned_to_details['email']; ?><br /><?php echo $lead_assigned_to_details['mobile']; ?></span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <strong>Status</strong><br>
                                                            <span
                                                                class="text-ontime"><?php echo $lead_details["current_status"]; ?></span>

                                                            <!-- <?php if ($lead_details["pos_salesorder"] && $lead_details["lead_status"] != 305 && $lead_details["lead_status"] != 312) { ?>
                                                                <div>
                                                                    <a data-href="/leads/lead/complete_lead/<?php echo $lead_details["id"]; ?>"
                                                                        class="invoice-convert">
                                                                        <button class="btn btn-primary"
                                                                            data-lead="<?php echo $lead_details["id"]; ?>">Convert
                                                                            To Invoice</button>
                                                                    </a>
                                                                </div>
                                                            <?php } ?> -->
                                                        </td>
                                                        <td>
                                                            <strong>Lead Type</strong><br>
                                                            <span style="text-transform: capitalize;">
                                                                <?php echo $lead_details["lead_type"];
                                                                if ($lead_details["lead_cross_sale_pmt"] != "" && $lead_details["lead_cross_sale_pmt"] != null) {
                                                                    echo " - " . $lead_details["lead_cross_sale_pmt"];
                                                                }
                                                                if ($lead_details["lead_emp_id"] != 0 && $lead_details["lead_emp_id"] != null && $lead_details["lead_emp_id"] != "") {
                                                                    echo " - " . $lead_details["lead_emp_discount_per"] . "%";
                                                                }

                                                                ?>

                                                            </span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <strong>Descriptions:</strong><br />
                                                            <?php $anc = $lead_details['remarks'];
                                                            if ($lead_details['branch_name'] == "Golden Cube") {
                                                                $search = 'Documents';
                                                            } elseif ($lead_details['branch_name'] == "Baraha Van") {
                                                                $search = 'Documents';
                                                            } else {
                                                                $search = 'DOCUMENTS';
                                                            }
                                                            $pos = strpos($anc, $search);

                                                            if ($pos !== false) {
                                                                $pos += strlen($search); // Move position to end of "DOCUMENTS"
                                                                $bbn = trim(substr($anc, $pos)); // Extract and trim the text after "DOCUMENTS"
                                                            } else {
                                                                $bbn = ''; // Default value if "DOCUMENTS" is not found
                                                            }
                                                            $doc = new DOMDocument();

                                                            // Load the HTML content
                                                            libxml_use_internal_errors(true); // Suppress warnings for malformed HTML
                                                            $doc->loadHTML('<?xml encoding="utf-8" ?>' . $bbn); // Load HTML

                                                            // Get all anchor tags
                                                            $anchors = $doc->getElementsByTagName('a');

                                                            // Initialize arrays to store text values and URL-encoded values
                                                            $textValues = [];
                                                            $encodedValues = [];

                                                            // Define the base path to look for
                                                            if ($lead_details['branch_name'] == "Golden Cube") {
                                                                $basePath = 'assets/uploads/gc/';
                                                            } else if ($lead_details['branch_name'] == "Baraha Van") {
                                                                $basePath = 'assets/uploads/bv/';
                                                            } else {
                                                                $basePath = 'assets/uploads/';
                                                                $basePath1 = 'serve_filemain/';
                                                                $basePath2 = 'api/serve_file/';
                                                            }

                                                            // Function to URL-encode the string
                                                            function encodeUrl($string)
                                                            {
                                                                return rawurlencode($string);
                                                            }

                                                            // Iterate through each anchor tag
                                                            foreach ($anchors as $anchor) {
                                                                // Get the text content of the anchor tag
                                                                $textContent = $anchor->textContent;
                                                                $textValues[] = $textContent; // Store text content in array


                                                                if (($lead_details['branch_name'] == "Golden Cube") || ($lead_details['branch_name'] == "Baraha Van")) {
                                                                    $href = $anchor->getAttribute('href');
                                                                } else {
                                                                    $cleanUrl = $anchor->getAttribute('href');
                                                                    $parsedUrl = parse_url($cleanUrl);

                                                                    // Build the URL without the query string
                                                                    $href = $parsedUrl['scheme'] . '://' . $parsedUrl['host'] . $parsedUrl['path'];
                                                                }





                                                                // Extract the part after 'assets/uploads/'
                                                                $pos = strpos($href, $basePath);
                                                                if ($pos == '') {
                                                                    // $pos = strpos($href, $basePath1);
                                                                    $pos = strpos($href, $basePath2);
                                                                }
                                                                if ($pos == '') {
                                                                    $pos = strpos($href, $basePath1);
                                                                }
                                                                // echo '<pre>';print_r($href);
                                                                // echo '<pre>';print_r($pos);die;


                                                                if ($pos !== false) {
                                                                    $filePath = substr($href, $pos + strlen($basePath));
                                                                    $filePath = str_replace("?token=tdhi", "", $filePath);
                                                                    $filePath = str_replace("tdhiOrATsvWorX", "", $filePath);

                                                                    //print_r($filePath);
                                                                    // URL-encode the file path
                                                                    $encodedPath = encodeUrl($filePath);

                                                                    $encodedValues[] = $encodedPath; // Store URL-encoded value in array
                                                                }
                                                            } ?>

                                                            <?php
                                                            $remarks = $lead_details['remarks'];

                                                            // Define the keyword and find its position
                                                            if ($lead_details['branch_name'] == "Golden Cube") {
                                                                $keyword = 'Documents';
                                                            } elseif ($lead_details['branch_name'] == "Baraha Van") {
                                                                $keyword = 'Documents';
                                                            } else {
                                                                $keyword = 'DOCUMENTS';
                                                            }
                                                            $keywordPos = strpos($remarks, $keyword);

                                                            if ($keywordPos !== false) {
                                                                // Extract the portion of the string up to the keyword
                                                                $beforeKeyword = substr($remarks, 0, $keywordPos + strlen($keyword));

                                                                // Use regex to remove everything after the last <a> tag after "DOCUMENTS"
                                                                $pattern = '/(<a\b[^>]*>.*?<\/a>)(.*)$/is';
                                                                $replacement = '$1'; // Keep only the first part of the match (the <a> tags)

                                                                $result = preg_replace($pattern, $replacement, $beforeKeyword);
                                                            } else {
                                                                // If "DOCUMENTS" is not found, return the original string
                                                                $result = $remarks;
                                                            }

                                                            ?>
                                                            <?php echo "<pre>" . $result . "</pre>";

                                                            /* if ($is_gc_eligible == 1) {
                                                                echo "<br><br>
                                                                <a href='/leads/lead/golden_cube_lead/" . $lead_details["id"] . "' class='btn btn-primary'>Convert To GoldenCube Order</a>
                                                                <br>";
                                                            } */ ?>
  
                                                            <?php if (!empty($textValues)) {
                                                                //print_r($encodedValues);
                                                                $nn3 = count($textValues);
                                                                for ($i = 0; $i < $nn3; $i++) {
                                                                    //echo $encodedValues[$i];/*
                                                                    if ($lead_details['branch_name'] == "Golden Cube") {
                                                                        $url = 'https://ontimegov.com/sapi/serve_file_gc/' . $encodedValues[$i] . '?token=tdhi';
                                                                        echo '<a href="' . $url . '" target="_blank" onclick="openInNewTab(this.href); return false;">' . $textValues[$i] . '</a><br>';
                                                                    } else if ($lead_details['branch_name'] == "Baraha Van") {
                                                                        $url = 'https://ontimegov.com/sapi/serve_file_bv/' . $encodedValues[$i] . '?token=tdhi';
                                                                        echo '<a href="' . $url . '" target="_blank" onclick="openInNewTab(this.href); return false;">' . $textValues[$i] . '</a><br>';
                                                                    }  else {
                                                                        $url = 'https://ontimegov.com/sapi/serve_file/' . $encodedValues[$i] . '?token=tdhi';
                                                                        echo '<a href="' . $url . '" target="_blank" onclick="openInNewTabs(this.href); return false;">' . $encodedValues[$i] . '</a><br>';
                                                                    }

                                                                    // Generate and output the anchor tag

                                                                    //echo '<a href="https://ontimegov.com/sapi/serve_file/'.$textContent[$i].'?token=abcdef1234567890'" target="_blank">'.$textValues[$i].'</a>';
                                                                }
                                                            }

                                                            ?>
                                                            <?php if ($lead_current_status == 321) {
                                                                echo "<br><br>
                                                                <a href='/leads/lead/golden_cube_lead/" . $lead_details["id"] . "' class='btn btn-primary'>Convert To GoldenCube Order</a>
                                                                <br>";
                                                            }
                                                            ?>

                                                        </td>
                                                    </tr>
                                                    <?php
                                                    if (isset($lead_attachments) && $lead_attachments != null) {
                                                    ?>
                                                        <tr>
                                                            <td colspan="2"><strong>Attachments</strong></td>
                                                        </tr>
                                                        <?php
                                                        foreach ($lead_attachments as $key => $value) {
                                                        ?>
                                                            <tr>
                                                                <td><?php echo strtoupper($value['attachment_name']); ?></td>
                                                                <td>
                                                                    <?php

                                                                    $file_url = $value['attachment_url'];
                                                                    $file_name = str_replace(getenv('CRM_URL')."uploads/leads/", "", $file_url);
                                                                    ?>

                                                                    <a target="blank"
                                                                        href="<?php echo $value['attachment_url']; ?>"> <span
                                                                            class="fiv-viv fiv-icon-doc"></span>&nbsp;&nbsp;View
                                                                        file</a>
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
                                                                <span id="o_ord_sub_category2"
                                                                    class="text-ontime"><?php echo $lead_details['order_receipt']; ?></span>
                                                                <!-- <span id="o_ord_sub_category2" class="text-ontime btn btn-primary" onclick="location.href='/leads/lead/getinvoice?so=<?php echo $lead_details['order_receipt']; ?>'"><?php echo $lead_details['order_receipt']; ?></span> -->
                                                            </td>
                                                        </tr>
                                                    <?php
                                                    }
                                                    ?>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row justify-content-center">
                            <div class="col-lg-11">
                                <?php
                                if ($this->session->flashdata('alert_complete')) {
                                ?>
                                    <div class="alert alert-<?php echo $this->session->flashdata('alert_complete'); ?>">
                                        <?php echo $this->session->flashdata('alert_complete_message'); ?>
                                    </div>
                                <?php
                                }
                                ?>
                                <div id="accordion" class="accordion-wrapper mb-3">
                                    <?php

                                    $dld_complete_flag = 0;
                                    $immigration_flag = 0;
                                    $emirates_flag = 0;
                                    $os_flag = 0;

                                    $convert_invoice_count = 0;

                                    if ($sub_leads) {
                                        foreach ($sub_leads as $key => $value) {
                                            $iter = $key + 1;
                                            $sub_lead_id = $value->id;

                                            if ($value->msd_key == 64 && $value->lead_status == 305) {
                                                $dld_complete_flag = 1;
                                                $immigration_flag = 1;
                                                $emirates_flag = 0;
                                                $os_flag = 0;
                                            }
                                            if ($value->msd_key == 66 && $value->lead_status == 305) {
                                                $dld_complete_flag = 1;
                                                $immigration_flag = 1;
                                                $emirates_flag = 1;
                                                $os_flag = 0;
                                            }
                                            if ($value->msd_key == 67 && $value->lead_status == 305) {
                                                $dld_complete_flag = 1;
                                                $immigration_flag = 1;
                                                $emirates_flag = 1;
                                                $os_flag = 1;
                                            }
                                            if ($value->msd_key == 68 && $value->lead_status == 305) {
                                                $dld_complete_flag = 1;
                                                $immigration_flag = 1;
                                                $emirates_flag = 1;
                                                $os_flag = 1;
                                            }

                                            if ($value->msd_key != 69 && $value->lead_status == 305) {
                                                $convert_invoice_count = (int)$convert_invoice_count + 1;
                                            }
                                    ?>
                                            <div class="">

                                                <div id="heading<?php echo $key + 1; ?>"
                                                    <?php if ($value->lead_status == 305) { ?> class="bg-success d-flex"
                                                    <?php } else if ($value->lead_status == 306) { ?> class="bg-midnight-bloom d-flex" <?php } else if ($value->msd_key == 66 && $value->lead_status == 301 && $immigration_flag == 0 && $value->branch_id == 106) { ?> class="bg-secondary d-flex" <?php } else if ($value->msd_key == 67 && $value->lead_status == 301 && $emirates_flag == 0 && $value->branch_id == 106) { ?> class="bg-secondary d-flex" <?php } else if ($value->msd_key == 68 && $value->lead_status == 301 && $os_flag == 0 && $value->branch_id == 106) { ?> class="bg-secondary d-flex" <?php } else { ?>
                                                    class="bg-night-sky  d-flex" <?php } ?>>
                                                    <button type="button" data-toggle="collapse"
                                                        data-target="#collapseOne<?php echo $key + 1; ?>"
                                                        <?php if ($key == 0) { ?> aria-expanded="true" <?php } else { ?>
                                                        aria-expanded="false" <?php } ?>
                                                        aria-controls="collapseOne<?php echo $key + 1; ?>"
                                                        class="text-left m-0 btn btn-link text-white btn-link btn-block p-3"
                                                        >
                                                        <!-- <?php if (($value->task_assigned != $this->auth_user_id) && ($lead_details["assigned_to_user"] != $this->auth_user_id)) { ?> disabled <?php } ?> -->
                                                        <h5 class="m-0 p-0">Sub Lead - <?php echo $key + 1; ?>
                                                            #<?php echo $sub_lead_id; ?> - <?php echo $value->service; ?>
                                                        </h5>
                                                    </button>
                                                    <?php // if ($value->lead_status != 305) { ?>
                                                        <button
                                                            class="bg-white btn btn-group btn-outline-primary d-block h-75 lead_preview mb-auto mr-3 mt-auto text-center text-primary"
                                                            data-href="<?php echo getenv('CRM_URL'); ?>leads/lead/preview/<?php echo $sub_lead_id; ?>"
                                                            style="min-width: 150px;" >
                                                            <?php if ($value->reassigned_to != "") { ?>
                                                                ReAssign To
                                                            <?php } else { ?>
                                                                Assign To
                                                            <?php } ?>

                                                            <i class="fa fa-forward ml-2"></i></button>
                                                    <?php // } ?>
                                                </div>
                                                <div data-parent="#accordion" id="collapseOne<?php echo $key + 1; ?>"
                                                    aria-labelledby="heading<?php echo $key + 1; ?>" <?php if ($key == 0) { ?>
                                                    class="collapse show" <?php } else { ?> class="collapse" <?php } ?>
                                                    style="">
                                                    <div class="card-body">
                                                        <table class="table">

                                                            <tr>
                                                                <td>
                                                                    <strong>Category:</strong><br />
                                                                    <span id="o_ord_category"
                                                                        class="text-ontime"><?php echo $value->category; ?></span>
                                                                </td>
                                                                <td>
                                                                    <strong>Service Name:</strong><br />
                                                                    <span id="o_ord_sub_category"
                                                                        class="text-ontime"><?php echo $value->service; ?></span>
                                                                </td>
                                                                <td>
                                                                    <strong>Gov Fee</strong><br />
                                                                    <span id="o_ord_sub_category" class="text-ontime">AED
                                                                        <?php echo $value->govt_fee ? $value->govt_fee : 0; ?></span>
                                                                </td>
                                                                <td>
                                                                    <strong>Typing Fee</strong><br />
                                                                    <span id="o_ord_sub_category" class="text-ontime">AED
                                                                        <?php echo $value->typing_fee ? $value->typing_fee : 0; ?></span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <?php // if ($value->reassigned_to != "") { 
                                                                ?>

                                                                <?php // } else { 
                                                                ?>
                                                                <td>
                                                                    <?php // } 
                                                                    ?>
                                                                    <strong>Description:</strong><br>
                                                                    <span>
                                                                        <?php echo $value->remarks; ?>

                                                                    </span>
                                                                </td>

                                                                <td>
                                                                    <strong>Additional Govt Fee</strong><br />
                                                                    <span id="o_ord_sub_category" class="text-ontime">AED
                                                                        <?php echo $value->additional_govt_fee ? $value->additional_govt_fee : 0; ?></span>
                                                                </td>
                                                                <td>
                                                                    <strong>Total</strong><br />
                                                                    <span id="o_ord_sub_category" class="text-ontime">AED
                                                                        <?php echo $value->govt_fee + $value->typing_fee + $value->additional_govt_fee; ?></span>
                                                                </td>
                                                                <?php if ($value->reassigned_to != "") { ?>
                                                                    <td>
                                                                        <strong>Assigned To:</strong><br>
                                                                        <span>
                                                                            <?php echo $value->reassigned_to; ?>
                                                                        </span>
                                                                    </td>
                                                                <?php } ?>

                                                            </tr>
                                                            <?php
                                                            if (($value->is_direct_invoice && $value->gc_service_id == 94) && $value->lead_status != 305) {
                                                            ?>
                                                                <tr>
                                                                    <td>
                                                                        <?php
                                                                        if ($value->is_direct_invoice >= 1 && $value->gc_service_id == 94) {
                                                                            echo '<a href="#" data-href="/leads/lead/subleadcomplete?code=' . $sub_lead_id . '&inovice_id=' . $value->is_direct_invoice . '" class="btn btn-primary convertInv">Convert To Invoice</a>';
                                                                            //if($lead_details["branch_id"] == 106 && $lead_details["no_of_closed_subleads"] < 4){  //$convert_invoice_count < ((int)$lead_details["total_no_subleads"] - 1)){
                                                                            /* if($lead_details["branch_id"] == 106 && $sub_leads_convert_invoice_count < ((int)$lead_details["total_no_subleads"] - 1)){
                                                                                echo '<a href="#" data-href="/leads/lead/subleadcomplete?code=' . $sub_lead_id . '&inovice_id='.$value->is_direct_invoice.'" class="btn btn-primary convertInvAlert">Convert To Invoice</a>';
                                                                            } else {
                                                                                echo '<a href="#" data-href="/leads/lead/subleadcomplete?code=' . $sub_lead_id . '&inovice_id='.$value->is_direct_invoice.'" class="btn btn-primary convertInv">Convert To Invoice</a>';
                                                                            }  */
                                                                        }
                                                                        ?>


                                                                    </td>
                                                                </tr>
                                                                <?php
                                                            }
                                                            // $lead_attachements = $this->db->select("*")->from("lead_attachments")->get()->result_array();
                                                            $lead_attachments = sub_lead_attachment($sub_lead_id);
                                                            if (count($lead_attachments) > 0) {
                                                                foreach ($lead_attachments as $key => $attach) {
                                                                ?>
                                                                    <tr>
                                                                        <td><?php echo strtoupper($attach['attachment_name']); ?></td>
                                                                        <td>
                                                                            <?php

                                                                            $file_url = $attach['attachment_url'];
                                                                            $file_name = str_replace(getenv('CRM_URL')."uploads/leads/", "", $file_url);
                                                                            ?>

                                                                            <a target="blank"
                                                                                href="<?php echo $attach['attachment_url']; ?>"> <span
                                                                                    class="fiv-viv fiv-icon-doc"></span>&nbsp;&nbsp;View
                                                                                file</a>
                                                                        </td>
                                                                    </tr>
                                                                <?php
                                                                }
                                                            }
                                                            if ($value->lead_status != 305 && $value->task_created != 1  && $value->lead_status != 306 && !in_array($value->gc_service_id, [2304, 1516 ,566, 701, 2309,2310])) {
                                                                ?>
                                                                <tr>
                                                                    <td colspan="2">
                                                                        <div class="card-body p-0">
                                                                            <div class="row">
                                                                                <div class="col-md-12">
                                                                                    <div class="form-group mb-0">
                                                                                        <label>Select the Closing Activity
                                                                                            &nbsp;<span
                                                                                                class="text-danger required">*</span></label>
                                                                                        <select class="form-control"
                                                                                            name="action_id" id="action_id"
                                                                                            data-id="<?php echo $sub_lead_id; ?>"
                                                                                            onchange="javascript:choose_action(this);">
                                                                                            <option value="">-- Choose action --
                                                                                            </option>
                                                                                            <?php
                                                                                            foreach ($complete_actions as $key => $value) {
                                                                                                if ($lead_details["branch_id"] == 106 or $value["id"] != 410) {
                                                                                                    continue;
                                                                                                }

                                                                                            ?>
                                                                                                <option
                                                                                                    value="<?php echo $value['id']; ?>">
                                                                                                    <?php echo $value['action_name']; ?>
                                                                                                </option>
                                                                                            <?php
                                                                                            }
                                                                                            ?>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                        </div>

                                                                    </td>
                                                                </tr>
                                                            <?php
                                                            }
                                                            ?>
                                                            <?php
                                                            if ((isset($value->order_receipt) || in_array($value->gc_service_id, [2304, 1516 ,566,701,2309,2310])) && $lead_details['lead_status'] != 306) {
                                                                if (($value->order_receipt == "0" && $value->task_created != 1) && !in_array($value->gc_service_id, [2304, 1516 ,566,701,2309,2310])) {

                                                            ?>
                                                                    <tr>
                                                                        <td colspan="2">
                                                                            <strong>Remark</strong><br />
                                                                            <span id="o_ord_sub_category2" class="text-ontime">-- Lead
                                                                                Closed --</span>
                                                                        </td>
                                                                    </tr>
                                                                <?php
                                                                } else {
                                                                ?>
                                                                    <tr>
                                                                        <td colspan="0">
                                                                            <strong>ORDER ID#:</strong><br />
                                                                            <span id="o_ord_sub_category2"
                                                                                class="text-ontime"><?php echo $value->order_receipt; ?></span>
                                                                        </td>
                                                                    <!-- DLD task process -->
                                                                    <?php if ($value->gc_service_id == 95 && $value->msd_key == 64 && $lead_details["branch_id"] == 106 && $value->task_created == 1) { ?>
                                                                        <tr>
                                                                            <form action="<?php echo getenv('CRM_URL'); ?>leads/lead/action_dld_submit/<?php echo $sub_lead_id;?>" method="post" enctype="multipart/form-data">
                                                                                <input type="hidden" name="lead_parent_id" value="<?php echo $lead_details['id']; ?>">
                                                                                <td colspan="3">
                                                                                    <div class="card-body p-0">
                                                                                        <div class="row">
                                                                                            <div class="col-md-6">
                                                                                                <div class="form-group mb-0">
                                                                                                    <label for="country_options"><strong>Country Options:</strong></label>
                                                                                                    <select class="form-control country_options" id="country_options" name="country_options" data-leadid="<?php echo $sub_lead_id; ?>" required <?php echo ($value->task_status == 'completed') ? 'disabled' : '' ?>>
                                                                                                        <option value="">-- Choose -- </option>
                                                                                                        <option value="insideCountry" <?php echo ($lead_details['country_options'] == 'insideCountry') ? 'selected' : '' ?>><?php echo "Inside Country"; ?> </option>
                                                                                                        <option value="outsideCountry" <?php echo ($lead_details['country_options'] == 'outsideCountry') ? 'selected' : '' ?>><?php echo "Outside Country"; ?> </option>
                                                                                                    </select>
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="col-md-6">
                                                                                                <div class="form-group mb-0">
                                                                                                    <label><strong>Task Status:</strong></label>
                                                                                                    <select class="form-control task_status_dld" name="task_status" data-leadid="<?php echo $sub_lead_id; ?>" <?php echo ($value->task_status == 'completed') ? 'disabled' : '' ?> >
                                                                                                        <option value="">-- Choose -- </option>
                                                                                                        <option value="open" <?php echo ($value->task_status == 'open') ? 'selected' : '' ?>><?php echo "Open"; ?> </option>
                                                                                                        <option value="hold" <?php echo ($value->task_status == 'hold') ? 'selected' : '' ?>><?php echo "On Hold"; ?> </option>
                                                                                                        <option value="completed" <?php echo ($value->task_status == 'completed') ? 'selected' : '' ?>><?php echo "Completed"; ?> </option>
                                                                                                    </select>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                                <td colspan="2">
                                                                                    <div class="card-body p-0">
                                                                                        <div class="row">
                                                                                            <div class="col-md-8">
                                                                                                <div class="form-group mb-0">
                                                                                                    <label for="document_upload"><strong>Document Upload: <span class="text-danger required <?php echo ($lead_details['country_options'] == 'insideCountry') ? '' : 'd-none' ?>">*</span></strong></label>
                                                                                                    <input type="file" id="document_upload" class="form-control document_upload" name="attachments_lead[]" accept=".jpg,.jpeg,.png,.gif,.pdf" multiple <?php echo ($value->task_status == 'completed') ? 'disabled' : '' ?>>
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="col-md-4 align-content-end">
                                                                                                <button type="submit" name="action_uploadattachments" class="btn btn-primary mt-2 upload-documents" data-lead-id="<?php echo $sub_lead_id; ?>" <?php echo ($value->task_status == 'completed') ? 'disabled' : '' ?>>Submit</button>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                            </form>
                                                                        </tr>
                                                                    <?php } ?>
                                                                    <!-- VISA task process -->
                                                                    <?php if ($value->gc_service_id == 93 && $value->msd_key == 66 && $lead_details["branch_id"] == 106  && $value->task_created == 1) { ?>
                                                                    <form action="<?php echo getenv('CRM_URL'); ?>leads/lead/action_visa_submit/<?php echo $sub_lead_id; ?>" id="visaProcessForm" method="post" enctype="multipart/form-data">
                                                                        <input type="hidden" name="lead_parent_id" value="<?php echo $lead_details['id']; ?>">
                                                                        <tr>
                                                                            <td colspan="5">
                                                                                <div class="card-body p-0">
                                                                                    <div class="row">
                                                                                        <div class="col-md-3">
                                                                                            <div class="form-group mb-0">
                                                                                                <label for="visa_status"><strong>VISA Status: <span class="text-danger required">*</span></strong></label>
                                                                                                <select id="visa_status" name="visa_status" class="form-control visa_status" required <?php echo ($value->task_status == 'completed') ? 'disabled' : ''; ?>>
                                                                                                    <option value=""> -- Choose -- </option>
                                                                                                    <?php foreach ($task_status as $status) { 
                                                                                                        if($status['service_id'] == 2 && $value->lead_status == 305) {
                                                                                                        ?>
                                                                                                        <option value="<?php echo $status['id']; ?>" <?php echo ($value->visa_status == $status['id']) ? 'selected' : '' ?>><?php echo $status['status_name']; ?> </option>
                                                                                                    <?php } else if($status['id'] == 8){
                                                                                                        ?>
                                                                                                        <option value="<?php echo $status['id']; ?>" <?php echo ($value->visa_status == $status['id']) ? 'selected' : '' ?>><?php echo $status['status_name']; ?> </option>
                                                                                                    <?php }
                                                                                                    } ?>
                                                                                                </select>
                                                                                            </div>
                                                                                        </div>
                                                                                        <!-- <div class="col-md-3">
                                                                                            <div class="form-group">
                                                                                                <label><strong>Applicant:</strong></label>
                                                                                                <select class="form-control applicant" name="applicant"  <?php echo ($value->task_status == 'completed') ? 'disabled' : '' ?>>
                                                                                                    <option value="">-- Choose -- </option>
                                                                                                    <option value="investor" <?php echo ($value->visa_applicant == 'investor') ? 'selected' : '' ?>>Investor</option>
                                                                                                    <option value="depended" <?php echo ($value->visa_applicant == 'depended') ? 'selected' : '' ?>>Depended + If the Client was Outside and receive the Outside Email from DLD stage</option>
                                                                                                </select>
                                                                                            </div>
                                                                                        </div> -->
                                                                                        <div class="col-md-3">
                                                                                            <div class="form-group">
                                                                                                <label><strong>Task Status: <span class="text-danger required">*</span></strong></label>
                                                                                                <select class="form-control task_status" name="task_status" data-leadid="<?php echo $sub_lead_id; ?>" required <?php echo ($value->task_status == 'completed' || $value->lead_status != 305) ? 'disabled' : '' ?>>
                                                                                                    <option value="">-- Choose -- </option>
                                                                                                    <option value="open" <?php echo ($value->task_status == 'open') ? 'selected' : '' ?>><?php echo "Open"; ?> </option>
                                                                                                    <option value="hold" <?php echo ($value->task_status == 'hold') ? 'selected' : '' ?>><?php echo "On Hold"; ?> </option>
                                                                                                    <option value="completed" <?php echo ($value->task_status == 'completed') ? 'selected' : '' ?>><?php echo "Completed"; ?> </option>
                                                                                                </select>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-md-3">
                                                                                            <div class="form-group">
                                                                                                <label for="document_upload"><strong>Document Upload: <span class="text-danger required d-none">*</span></strong></label>
                                                                                                <input type="file" id="document_upload_visa" class="form-control document_upload" name="attachments_lead[]" multiple <?php echo ( in_array($value->task_status, ['5', '7']) ) ? 'required' : ''; ?> <?php echo ($value->task_status == 'completed' || $value->lead_status != 305) ? 'disabled' : ''; ?>>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td colspan="5">
                                                                                <div class="card-body p-0">
                                                                                    <div class="row">
                                                                                        <div class="col-md-4">
                                                                                            <div class="form-group mb-0">
                                                                                                <label><strong>Visa Remarks: <span class="text-danger required d-none">*</span></strong></label>
                                                                                                <textarea name="task_remarks" class="form-control visa_remarks" rows="3" data-leadid="<?php echo $sub_lead_id; ?>" <?php echo ( in_array($value->visa_status, ['5', '4', '7']) ) ? 'required' : ''; ?> <?php echo ($value->task_status == 'completed' || $value->lead_status != 305) ? 'disabled' : ''; ?>><?php echo $value->task_remarks; ?></textarea>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-md-4 preApproval_status_div <?php echo ($value->visa_status == '6') ? '' : 'd-none'; ?>">
                                                                                            <div class="form-group mb-0">
                                                                                                <label><strong>Pre-approval Status: <span class="text-danger required">*</span></strong></label>
                                                                                                <select name="pre_approval_status" class="form-control pre_approval_status" data-leadid="<?php echo $sub_lead_id; ?>" <?php echo ($value->visa_status == '6') ? 'required' : ''; ?> <?php echo ($value->task_status == 'completed') ? 'disabled' : ''; ?>>
                                                                                                    <option value="">-- Choose -- </option>
                                                                                                    <!-- <option value="cancellation_medical" <?php echo ($value->pre_approval_status == 'cancellation_medical') ? 'selected' : '' ?>>Cancellation and Medical</option> -->
                                                                                                    <option value="medical" <?php echo ($value->pre_approval_status == 'medical') ? 'selected' : '' ?>>Medical</option>
                                                                                                    <!-- <option value="cancellation_medical_outside" <?php echo ($value->pre_approval_status == 'cancellation_medical_outside') ? 'selected' : '' ?>>Cancellation with medical outside</option> -->
                                                                                                    <option value="medical_outside" <?php echo ($value->pre_approval_status == 'medical_outside') ? 'selected' : '' ?>>Medical outside</option>
                                                                                                </select>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-md-2 align-content-end">
                                                                                            <div class="form-group mb-0 text-right">
                                                                                                <button type="submit" class="btn btn-success visaTaskBtn" data-leadid="<?php echo $sub_lead_id; ?>" <?php echo ($value->task_status == 'completed') ? 'disabled' : ''; ?>>Submit</button>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                    </form>
                                                                    <!-- EID task process -->
                                                                    <?php } 
                                                                    if ($value->gc_service_id == 2 && $value->msd_key == 67 && $lead_details["branch_id"] == 106 && $value->task_created == 1) { ?>
                                                                        <form action="<?php echo getenv('CRM_URL'); ?>leads/lead/action_eid_submit/<?php echo $sub_lead_id;?>" method="post" enctype="multipart/form-data" id="eidProcessForm">
                                                                            <input type="hidden" name="lead_parent_id" value="<?php echo $lead_details['id']; ?>">
                                                                        <tr>
                                                                            <td colspan="2">
                                                                                <div class="card-body p-0">
                                                                                    <div class="row">
                                                                                        <div class="col-md-12">
                                                                                            <div class="form-group mb-0">
                                                                                                <label><strong>EID status</strong></label>
                                                                                                <select name="eid_status" id="eid_status" class="form-control eid_status" data-leadid="<?php echo $sub_lead_id; ?>" required <?php echo ($value->task_status == 'completed') ? 'disabled' : ''; ?>>
                                                                                                    <option value="">-- Choose -- </option>
                                                                                                    <?php foreach ($task_status as $status) { 
                                                                                                        if($status['service_id'] == 4) {?>
                                                                                                        <option value="<?php echo $status['id']; ?>" <?php echo ($value->eid_status == $status['id']) ? 'selected' : '' ?>><?php echo $status['status_name']; ?> </option>
                                                                                                    <?php }} ?>
                                                                                                </select>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </td>
                                                                            <td colspan="2">
                                                                                <div class="card-body p-0">
                                                                                    <div class="row">
                                                                                        <div class="col-md-12">
                                                                                            <div class="form-group mb-0">
                                                                                                <label><strong>Task Status:</strong></label>
                                                                                                <select class="form-control task_status" name="task_status" data-leadid="<?php echo $sub_lead_id; ?>" <?php echo ($value->task_status == 'completed') ? 'disabled' : '' ?>>
                                                                                                    <option value="open" <?php echo ($value->task_status == 'open') ? 'selected' : '' ?>><?php echo "Open"; ?> </option>
                                                                                                    <option value="hold" <?php echo ($value->task_status == 'hold') ? 'selected' : '' ?>><?php echo "On Hold"; ?> </option>
                                                                                                    <option value="completed" <?php echo ($value->task_status == 'completed') ? 'selected' : '' ?>><?php echo "Completed"; ?> </option>
                                                                                                </select>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </td>
                                                                            <td colspan="2">
                                                                                <div class="card-body p-0">
                                                                                    <div class="row">
                                                                                        <div class="col-md-8">
                                                                                            <div class="form-group">
                                                                                                <label for="document_upload_eid"><strong>Document Upload: <span class="text-danger required d-none">*</span></strong></label>
                                                                                                <input type="file" id="document_upload_eid" class="form-control document_upload" name="attachments_lead[]" multiple <?php echo ($value->task_status == 'completed') ? 'disabled' : '';?>>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-md-4 align-content-center">
                                                                                            <button type="submit" class="btn btn-success mt-2" data-lead-id="<?php echo $sub_lead_id; ?>" <?php echo ($value->task_status == 'completed') ? 'disabled' : ''; ?>>Submit</button>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                        </form>
                                                                    <?php } ?>
                                                                    <!-- Block and unblock Task -->
                                                                    <?php if(in_array($value->lead_status, [305, 302, 301]) && $lead_details["branch_id"] == 106 && $value->gc_service_id == 2303  && $value->task_created == 1) { ?>
                                                                        <tr>
                                                                            <form action="<?php echo getenv('CRM_URL'); ?>leads/lead/action_task_block/<?php echo $sub_lead_id;?>" method="post">
                                                                            <td colspan="2">
                                                                                <input type="hidden" name="lead_parent_id" value="<?php echo $lead_details['id']; ?>">
                                                                                <div class="card-body p-0">
                                                                                    <div class="row">
                                                                                        <div class="col-md-6">
                                                                                            <div class="form-group mb-0">
                                                                                                <label for="block_category"><strong>Category:</strong></label>
                                                                                                <select class="form-control block_category" id="block_category" name="block_category" data-leadid="<?php echo $sub_lead_id; ?>" required <?php echo ($value->task_status == 'completed') ? 'disabled' : '' ?>>
                                                                                                    <option value="">-- Choose -- </option>
                                                                                                    <option value="transferProperty" <?php echo ($value->block_category == 'transferProperty') ? 'selected' : '' ?>>Transfer Property</option>
                                                                                                    <option value="newProperty" <?php echo ($value->block_category == 'newProperty') ? 'selected' : '' ?>>Unblock property and provide new property</option>
                                                                                                    <option value="unblock" <?php echo ($value->block_category == 'unblock') ? 'selected' : '' ?>>Unblock</option>
                                                                                                </select>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-md-6">
                                                                                            <div class="form-group mb-0">
                                                                                                <label><strong>Task Status:</strong></label>
                                                                                                <select class="form-control task_status_block" name="task_status" data-leadid="<?php echo $sub_lead_id; ?>" <?php echo ($value->task_status == 'completed') ? 'disabled' : '' ?> >
                                                                                                    <option value="">-- Choose -- </option>
                                                                                                    <option value="open" <?php echo ($value->task_status == 'open') ? 'selected' : '' ?>><?php echo "Open"; ?> </option>
                                                                                                    <option value="hold" <?php echo ($value->task_status == 'hold') ? 'selected' : '' ?>><?php echo "On Hold"; ?> </option>
                                                                                                    <option value="completed" <?php echo ($value->task_status == 'completed') ? 'selected' : '' ?>><?php echo "Completed"; ?> </option>
                                                                                                </select>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </td>
                                                                            <td colspan="2">
                                                                                <div class="card-body p-0">
                                                                                    <div class="row">
                                                                                        <div class="col-md-4 align-content-center">
                                                                                            <button type="submit" class="btn btn-success mt-4" data-lead-id="<?php echo $sub_lead_id; ?>" <?php echo ($value->task_status == 'completed') ? 'disabled' : ''; ?>>Submit</button>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </td>
                                                                            </form>
                                                                        </tr>
                                                                    <?php } ?>
                                                                    <!-- Insurance Task -->
                                                                    <?php if($value->gc_service_id == 1516 && $lead_details["branch_id"] == 106 && ((in_array($value->lead_status, [301,302,305]) || ($value->lead_status == '301')))) { ?>
                                                                        <tr>
                                                                            <form action="<?php echo getenv('CRM_URL'); ?>leads/lead/action_task_insurance/<?php echo $sub_lead_id;?>" method="post" enctype="multipart/form-data">
                                                                            <td colspan="4">
                                                                                <input type="hidden" name="lead_parent_id" value="<?php echo $lead_details['id']; ?>">
                                                                                <input type="hidden" name="status" value="<?php echo $value->lead_status; ?>">
                                                                                <input type="hidden" name="service_id" value="<?php echo $value->service_id; ?>">
                                                                                <input type="hidden" name="customer_id" value="<?php echo $lead_details['customer_id']; ?>">
                                                                                <div class="card-body p-0">
                                                                                    <div class="row">
                                                                                        <div class="col-md-3">
                                                                                            <div class="form-group mb-0">
                                                                                                <label for="insurance_category"><strong>Category:</strong></label>
                                                                                                <?php if(!empty($value->block_category)) { ?>
                                                                                                    <input type="text" class="form-control" name="insurance_category" value="<?php echo $value->block_category; ?>" readOnly>
                                                                                                <?php } else { ?>
                                                                                                    <select class="form-control insurance_category" id="insurance_category" name="insurance_category" data-leadid="<?php echo $sub_lead_id; ?>" required>
                                                                                                        <option value="">-- Choose -- </option>
                                                                                                        <option value="basic" <?php echo ($value->block_category == 'basic') ? 'selected' : '' ?>>Basic Insurance</option>
                                                                                                        <option value="comprehensive" <?php echo ($value->block_category == 'comprehensive') ? 'selected' : '' ?>>Comprehensive</option>
                                                                                                    </select>
                                                                                                <?php } ?>
                                                                                            </div>
                                                                                        </div>
                                                                                        <?php // if($value->task_assigned == $this->auth_user_id){ ?>
                                                                                        <div class="col-md-3">
                                                                                            <div class="form-group mb-0">
                                                                                                <label><strong>Task Status:</strong></label>
                                                                                                <select class="form-control task_status_ins" name="task_status" data-leadid="<?php echo $sub_lead_id; ?>" <?php echo ($value->task_status == 'completed') ? 'disabled' : '' ?>>
                                                                                                    <option value="">-- Choose -- </option>
                                                                                                    <option value="open" <?php echo ($value->task_status == 'open' || !empty($value->block_category)) ? 'selected' : '' ?>><?php echo "Open"; ?> </option>
                                                                                                    <!-- <option value="hold" <?php echo ($value->task_status == 'hold') ? 'selected' : '' ?>><?php echo "On Hold"; ?> </option> -->
                                                                                                    <option value="completed" <?php echo ($value->task_status == 'completed') ? 'selected' : '' ?>><?php echo "Completed"; ?> </option>
                                                                                                </select>
                                                                                            </div>
                                                                                        </div>
                                                                                        <?php if($value->block_category == 'comprehensive' && $value->ins_status == 'sent_form') { ?>
                                                                                        <div class="col-md-3">
                                                                                            <div class="form-group">
                                                                                                <label for="quotation_amount"><strong>Amount(typing fee): <span class="text-danger required ">*</span></strong></label>
                                                                                                <input type="number" id="quotation_amount" class="form-control quotation_amount" name="quotation_amount" step="0.01" min="0" required>
                                                                                            </div>
                                                                                        </div>
                                                                                        <?php } else {?>
                                                                                            <div class="col-md-3">
                                                                                                <div class="form-group">
                                                                                                    <label for="document_upload_ins"><strong>Document Upload: <span class="text-danger required <?php echo $value->block_category == 'comprehensive'?'':'d-none'; ?>">*</span></strong></label>
                                                                                                    <input type="file" id="document_upload_ins" class="form-control ins_doc document_upload" name="attachments_lead[]" multiple <?php echo ($value->task_status == 'completed') ? 'disabled' : '' ?> <?php echo $value->block_category == 'comprehensive'?'required':''; ?>>
                                                                                                </div>
                                                                                            </div>
                                                                                        <?php } 
                                                                                    // } ?>
                                                                                        <div class="col-md-1 align-content-center">
                                                                                            <button type="submit" class="btn btn-success mt-4" data-lead-id="<?php echo $sub_lead_id; ?>" <?php echo ($value->task_status == 'completed') ? 'disabled' : '' ?>>Submit</button>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </td>
                                                                            </form>
                                                                        </tr>
                                                                    <?php } ?>
                                                                    <!-- valuation Task -->
                                                                    <?php if($value->gc_service_id == 2304 &&  (in_array($value->lead_status, [305, 302, 301]) && $lead_details["branch_id"] == 106 )) { ?>
                                                                        <tr>
                                                                            <form id="valuation_form" action="<?php echo getenv('CRM_URL'); ?>leads/lead/action_task_valuation/<?php echo $sub_lead_id;?>" method="post" enctype="multipart/form-data">
                                                                            <td colspan="5">
                                                                                <input type="hidden" name="lead_parent_id" value="<?php echo $lead_details['id']; ?>">
                                                                                <input type="hidden" name="task_service" value="<?php echo $value->gc_service_id; ?>">
                                                                                <div class="card-body p-0">
                                                                                    <div class="row">
                                                                                        <div class="col-md-3">
                                                                                            <div class="form-group mb-0">
                                                                                                <label><strong>Task Status:</strong></label>
                                                                                                <select class="form-control task_status_valuation" name="task_status" data-leadid="<?php echo $sub_lead_id; ?>" <?php echo ($value->task_status == 'completed') ? 'disabled' : '' ?> >
                                                                                                    <option value="">-- Choose -- </option>
                                                                                                    <option value="valueCheck" <?php echo ($value->task_status == 'valueCheck') ? 'selected' : '' ?>><?php echo "Value Check"; ?> </option>
                                                                                                    <option value="open" <?php echo ($value->task_status == 'open') ? 'selected' : '' ?>><?php echo "Open"; ?> </option>
                                                                                                    <option value="completed" <?php echo ($value->task_status == 'completed') ? 'selected' : '' ?>><?php echo "Completed"; ?> </option>
                                                                                                </select>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-md-2">
                                                                                            <div class="form-group">
                                                                                                <label for="amount_check"><strong>Amount: <span class="text-danger required d-none">*</span></strong></label>
                                                                                                <input type="number" id="amount_check" class="form-control amount_check" name="amount_check" step="0.01" min="0" value ="<?php echo $value->property_value; ?>"  <?php echo ($value->task_status == 'valueCheck') ? 'required' : '' ?>>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-md-3">
                                                                                            <div class="form-group mb-0">
                                                                                                <label><strong>Remarks: <span class="text-danger required <?php echo ($value->task_status != 'valueCheck') ? 'd-none' : '' ?>">*</span></strong></label>
                                                                                                <textarea name="valuation_remarks" class="form-control valuation_remarks" rows="3" data-leadid="<?php echo $sub_lead_id; ?>" <?php echo ($value->task_status == 'valueCheck') ? 'required' : ''; ?> <?php echo ($value->task_status == 'completed') ? 'disabled' : ''; ?>><?php echo $value->task_remarks; ?></textarea>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-md-3">
                                                                                            <div class="form-group">
                                                                                                <label for="document_upload_val"><strong>Document Upload: <span class="text-danger required d-none" >*</span></strong></label>
                                                                                                <input type="file" id="document_upload_val" class="form-control document_upload" name="attachments_lead[]" multiple <?php echo ($value->task_status == 'completed') ? 'disabled' : '';?>>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-md-1 align-content-center">
                                                                                            <button type="submit" class="btn btn-success mt-2" data-lead-id="<?php echo $sub_lead_id; ?>" <?php echo ($value->task_status == 'completed') ? 'disabled' : ''; ?>>Submit</button>
                                                                                        </div>
                                                                                        
                                                                                    </div>
                                                                                </div>
                                                                            </td>
                                                                            </form>
                                                                        </tr>
                                                                    <?php } ?>
                                                                    <!-- attestation Task -->
                                                                     <?php if($value->gc_service_id == 2309 && (in_array($value->lead_status, [305, 302, 301]) && $lead_details["branch_id"] == 106 )) { ?>
                                                                        <tr>
                                                                            <form action="<?php echo getenv('CRM_URL'); ?>leads/lead/action_task_attestation/<?php echo $sub_lead_id;?>" method="post" enctype="multipart/form-data">
                                                                            <td colspan="5">
                                                                                <input type="hidden" name="lead_parent_id" value="<?php echo $lead_details['id']; ?>">
                                                                                <input type="hidden" name="task_service" value="<?php echo $value->gc_service_id; ?>">
                                                                                <input type="hidden" name="service_id" value="<?php echo $value->service_id; ?>">
                                                                                <input type="hidden" name="customer_id" value="<?php echo $lead_details['customer_id']; ?>">
                                                                                <div class="card-body p-0">
                                                                                    <div class="row">
                                                                                        <div class="col-md-2">
                                                                                            <div class="form-group mb-0">
                                                                                                <label><strong>Task Status:</strong></label>
                                                                                                <select class="form-control task_status_attest" name="task_status" data-leadid="<?php echo $sub_lead_id; ?>" <?php echo ($value->task_status == 'completed') ? 'disabled' : '' ?> >
                                                                                                    <option value="">-- Choose -- </option>
                                                                                                    <option value="open" <?php echo ($value->task_status == 'open') ? 'selected' : '' ?>><?php echo "Open"; ?> </option>
                                                                                                    <option value="hold" <?php echo ($value->task_status == 'hold') ? 'selected' : '' ?>><?php echo "On Hold"; ?> </option>
                                                                                                    <option value="completed" <?php echo ($value->task_status == 'completed') ? 'selected' : '' ?>><?php echo "Completed"; ?> </option>
                                                                                                </select>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-md-3">
                                                                                            <div class="form-group mb-0">
                                                                                                <label for="attestation_category"><strong>Category: <span class="text-danger required d-none" >*</span></strong></label>
                                                                                                <?php if(!empty($value->block_category)) { ?>
                                                                                                    <input type="text" class="form-control" name="attestation_category" value="<?php echo $value->block_category; ?>" readOnly>
                                                                                                <?php } else { ?>
                                                                                                    <select class="form-control attestation_category" id="attestation_category" name="attestation_category" data-leadid="<?php echo $sub_lead_id; ?>" required>
                                                                                                        <option value="">-- Choose -- </option>
                                                                                                        <option value="physical_avaiable" <?php echo ($value->block_category == 'physical_avaiable') ? 'selected' : '' ?>>Physical documents available</option>
                                                                                                        <option value="physical_not_avaiable" <?php echo ($value->block_category == 'physical_not_avaiable') ? 'selected' : '' ?>>Physical documents not available</option>
                                                                                                        <option value="digital" <?php echo ($value->block_category == 'digital') ? 'selected' : '' ?>>Digital</option>
                                                                                                    </select>
                                                                                                <?php } ?>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-md-2 status_open_div">
                                                                                            <div class="form-group">
                                                                                                <label for="quotation_amount"><strong>Amount(typing fee): <span class="text-danger required ">*</span></strong></label>
                                                                                                <input type="number" id="quotation_amount_attest" class="form-control quotation_amount" name="quotation_amount" step="0.01" min="0" value="<?php echo $value->typing_fee; ?>" required <?php echo ($value->task_status == 'completed') ? 'disabled' : '';?>>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-md-2 status_open_div">
                                                                                            <div class="form-group">
                                                                                                <label for="process_time"><strong>Processing Time: <span class="text-danger required ">*</span></strong></label>
                                                                                                <input type="number" id="process_time_attest" class="form-control process_time" name="process_time" value="<?php echo $value->Process_time; ?>" required <?php echo ($value->task_status == 'completed') ? 'disabled' : '';?>>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-md-3 document_type_div d-none">
                                                                                            <div class="form-group">
                                                                                                <label for="document_type"><strong>Document Type: <span class="text-danger required" >*</span></strong></label>
                                                                                                <select class="form-control document_type" name="document_type" require>
                                                                                                    <option value="">-- Choose --</option>
                                                                                                    <option value="physical" <?php echo ($value->document_type == 'physical') ? 'selected' : '' ?>>Physical</option>
                                                                                                    <option value="digital" <?php echo ($value->document_type == 'digital') ? 'selected' : '' ?>>Digital</option>
                                                                                                </select>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-md-3">
                                                                                            <div class="form-group">
                                                                                                <label for="document_upload_attest"><strong>Document Upload: <span class="text-danger required d-none" >*</span></strong></label>
                                                                                                <input type="file" id="document_upload_attest" class="form-control document_upload" name="attachments_lead[]" multiple <?php echo ($value->task_status == 'completed') ? 'disabled' : '';?>>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-md-3">
                                                                                            <div class="form-group">
                                                                                                <label for="task_remarks"><strong>Remarks:</label>
                                                                                                <textarea name="task_remarks" class="form-control task_remarks" rows="3" data-leadid="<?php echo $sub_lead_id; ?>" <?php echo ($value->task_status == 'completed') ? 'disabled' : ''; ?>><?php echo $value->task_remarks; ?></textarea>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-md-1 align-content-center">
                                                                                            <button type="submit" class="btn btn-success mt-2" data-lead-id="<?php echo $sub_lead_id; ?>" <?php echo ($value->task_status == 'completed') ? 'disabled' : ''; ?>>Submit</button>
                                                                                        </div>
                                                                                        
                                                                                    </div>
                                                                                </div>
                                                                            </td>
                                                                            </form>
                                                                        </tr>
                                                                    <?php } ?>
                                                                    <!-- Translation Task -->
                                                                    <?php if(in_array($value->gc_service_id, [566, 701]) && (in_array($value->lead_status, [305, 302, 301]) && $lead_details["branch_id"] == 106 )) { ?>
                                                                        <tr>
                                                                            <form action="<?php echo getenv('CRM_URL'); ?>leads/lead/action_task_translation/<?php echo $sub_lead_id;?>" method="post" enctype="multipart/form-data">
                                                                            <td colspan="5">
                                                                                <input type="hidden" name="lead_parent_id" value="<?php echo $lead_details['id']; ?>">
                                                                                <input type="hidden" name="task_service" value="<?php echo $value->gc_service_id; ?>">
                                                                                <input type="hidden" name="service_id" value="<?php echo $value->service_id; ?>">
                                                                                <input type="hidden" name="customer_id" value="<?php echo $lead_details['customer_id']; ?>">
                                                                                <div class="card-body p-0">
                                                                                    <div class="row">
                                                                                        <div class="col-md-2">
                                                                                            <div class="form-group mb-0">
                                                                                                <label><strong>Task Status:</strong></label>
                                                                                                <select class="form-control task_status_translation" name="task_status" data-leadid="<?php echo $sub_lead_id; ?>" <?php echo ($value->task_status == 'completed') ? 'disabled' : '' ?> >
                                                                                                    <option value="">-- Choose -- </option>
                                                                                                    <option value="open" <?php echo ($value->task_status == 'open') ? 'selected' : '' ?>><?php echo "Open"; ?> </option>
                                                                                                    <option value="hold" <?php echo ($value->task_status == 'hold') ? 'selected' : '' ?>><?php echo "On Hold"; ?> </option>
                                                                                                    <option value="completed" <?php echo ($value->task_status == 'completed') ? 'selected' : '' ?>><?php echo "Completed"; ?> </option>
                                                                                                </select>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-md-2">
                                                                                            <div class="form-group translation_open_div">
                                                                                                <label for="quotation_amount_translation"><strong>Amount(typing fee): <span class="text-danger required ">*</span></strong></label>
                                                                                                <input type="number" id="quotation_amount_translation" class="form-control quotation_amount" name="quotation_amount" step="0.01" min="0" value="<?php echo $value->typing_fee; ?>" required <?php echo ($value->task_status == 'completed') ? 'disabled' : '';?>>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-md-3">
                                                                                            <div class="form-group">
                                                                                                <label for="document_upload_translation"><strong>Document Upload: <span class="text-danger required d-none" >*</span></strong></label>
                                                                                                <input type="file" id="document_upload_translation" class="form-control document_upload" name="attachments_lead[]" multiple <?php echo ($value->task_status == 'completed') ? 'disabled' : '';?>>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-md-1 align-content-center">
                                                                                            <button type="submit" class="btn btn-success mt-2" data-lead-id="<?php echo $sub_lead_id; ?>" <?php echo ($value->task_status == 'completed') ? 'disabled' : ''; ?>>Submit</button>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </td>
                                                                            </form>
                                                                        </tr>
                                                                    <?php } ?>
                                                                    <!-- shipping Task -->
                                                                    <?php if($value->gc_service_id == 2310 && $lead_details["branch_id"] == 106 && (in_array($value->lead_status, [305, 302, 301]) )) { ?>
                                                                        <tr>
                                                                            <form action="<?php echo getenv('CRM_URL'); ?>leads/lead/action_task_shipping/<?php echo $sub_lead_id;?>" method="post" enctype="multipart/form-data">
                                                                            <td colspan="5">
                                                                                <input type="hidden" name="lead_parent_id" value="<?php echo $lead_details['id']; ?>">
                                                                                <input type="hidden" name="task_service" value="<?php echo $value->gc_service_id; ?>">
                                                                                <input type="hidden" name="service_id" value="<?php echo $value->service_id; ?>">
                                                                                <input type="hidden" name="customer_id" value="<?php echo $lead_details['customer_id']; ?>">
                                                                                <div class="card-body p-0">
                                                                                    <div class="row">
                                                                                        <div class="col-md-2">
                                                                                            <div class="form-group mb-0">
                                                                                                <label><strong>Task Status:</strong></label>
                                                                                                <select class="form-control task_status_shipping" name="task_status" data-leadid="<?php echo $sub_lead_id; ?>" <?php echo ($value->task_status == 'completed') ? 'disabled' : '' ?> >
                                                                                                    <option value="">-- Choose -- </option>
                                                                                                    <option value="open" <?php echo ($value->task_status == 'open') ? 'selected' : '' ?>><?php echo "Open"; ?> </option>
                                                                                                    <option value="hold" <?php echo ($value->task_status == 'hold') ? 'selected' : '' ?>><?php echo "On Hold"; ?> </option>
                                                                                                    <option value="completed" <?php echo ($value->task_status == 'completed') ? 'selected' : '' ?>><?php echo "Completed"; ?> </option>
                                                                                                </select>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-md-2">
                                                                                            <div class="form-group shipping_open_div">
                                                                                                <label for="quotation_amount"><strong>Amount(typing fee): <span class="text-danger required ">*</span></strong></label>
                                                                                                <input type="number" id="quotation_amount_shipping" class="form-control quotation_amount" name="quotation_amount" step="0.01" min="0" value="<?php echo $value->typing_fee; ?>" required <?php echo ($value->task_status == 'completed' || $is_customer_paid == 1) ? 'disabled' : '';?>>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-md-3 shipping_open_div">
                                                                                            <div class="form-group">
                                                                                                <label for="estimate_date"><strong>Estimated Date:<span class="text-danger required ">*</span></strong></label>
                                                                                                <input type="date" id="estimate_date" class="form-control estimate_date" name="estimate_date" value="<?php echo date('Y-m-d', strtotime($value->estimated_date)); ?>" min="<?php echo date('Y-m-d'); ?>" required <?php echo ($value->task_status == 'completed' || $is_customer_paid == 1) ? 'disabled' : '';?>>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-md-2 process_time_div d-none">
                                                                                            <div class="form-group">
                                                                                                <label for="process_time"><strong>Processing Time: <span class="text-danger required ">*</span></strong></label>
                                                                                                <input type="number" id="process_time_shipping" class="form-control process_time" name="process_time" value="<?php echo $value->Process_time; ?>" <?php echo ($value->task_status == 'completed') ? 'disabled' : '';?>>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-md-1 align-content-center">
                                                                                            <button type="submit" class="btn btn-success mt-2" data-lead-id="<?php echo $sub_lead_id; ?>" <?php echo ($value->task_status == 'completed') ? 'disabled' : ''; ?>>Submit</button>
                                                                                        </div>
                                                                                        
                                                                                    </div>
                                                                                </div>
                                                                            </td>
                                                                            </form>
                                                                        </tr>
                                                                    <?php } ?>
                                                                    <!-- Passport Update, new Title deed, replace EID and update EID  Tasks -->
                                                                    <?php if(in_array($value->lead_status, [301,302,305]) && $lead_details["branch_id"] == 106 && in_array($value->gc_service_id, [2305,1136,114,2308,2307,179]) && $value->task_created == 1) { ?>
                                                                        <tr>
                                                                            <form action="<?php echo getenv('CRM_URL'); ?>leads/lead/action_task_pass_deed/<?php echo $sub_lead_id;?>" method="post" enctype="multipart/form-data">
                                                                            <td colspan="5">
                                                                                <input type="hidden" name="lead_parent_id" value="<?php echo $lead_details['id']; ?>">
                                                                                <input type="hidden" name="task_service" value="<?php echo $value->gc_service_id; ?>">
                                                                                <div class="card-body p-0">
                                                                                    <div class="row">
                                                                                        <div class="col-md-3">
                                                                                            <div class="form-group mb-0">
                                                                                                <label><strong>Task Status:</strong></label>
                                                                                                <select class="form-control task_status_pass" name="task_status" data-leadid="<?php echo $sub_lead_id; ?>" <?php echo ($value->task_status == 'completed') ? 'disabled' : '' ?> >
                                                                                                    <option value="">-- Choose -- </option>
                                                                                                    <option value="open" <?php echo ($value->task_status == 'open') ? 'selected' : '' ?>><?php echo "Open"; ?> </option>
                                                                                                    <option value="completed" <?php echo ($value->task_status == 'completed') ? 'selected' : '' ?>><?php echo "Completed"; ?> </option>
                                                                                                </select>
                                                                                            </div>
                                                                                        </div>
                                                                                        <?php if(in_array($value->gc_service_id,[2305,701,566,114,1136])){ // new title deed, translation, update EID?>
                                                                                            <div class="col-md-3">
                                                                                                <div class="form-group">
                                                                                                    <label for="document_upload_title"><strong>Document Upload: <span class="text-danger required">*</span></strong></label>
                                                                                                    <input type="file" id="document_upload_title" class="form-control document_upload" name="attachments_lead[]" multiple required <?php echo ($value->task_status == 'completed') ? 'disabled' : '';?>>
                                                                                                </div>
                                                                                            </div>
                                                                                        <?php } ?>
                                                                                        <div class="col-md-1 align-content-center">
                                                                                            <button type="submit" class="btn btn-success mt-2" data-lead-id="<?php echo $sub_lead_id; ?>" <?php echo ($value->task_status == 'completed') ? 'disabled' : ''; ?>>Submit</button>
                                                                                        </div>
                                                                                        
                                                                                    </div>
                                                                                </div>
                                                                            </td>
                                                                            </form>
                                                                        </tr>
                                                                    <?php } ?>
                                                                    <!-- visa cancellation and update GDRFA task -->
                                                                    <?php if(in_array($value->lead_status, [301,302,305]) && $lead_details["branch_id"] == 106 && ($value->gc_service_id == 165 || $value->gc_service_id == 166 || $value->gc_service_id == 2306) && $value->task_created == 1) { ?>
                                                                        <tr>
                                                                            <form action="<?php echo getenv('CRM_URL'); ?>leads/lead/action_task_visa_cancel/<?php echo $sub_lead_id;?>" method="post" enctype="multipart/form-data">
                                                                            <td colspan="1">
                                                                                <input type="hidden" name="lead_parent_id" value="<?php echo $lead_details['id']; ?>">
                                                                                <input type="hidden" name="task_service" value="<?php echo $value->gc_service_id; ?>">
                                                                                <div class="card-body p-0">
                                                                                    <div class="row">
                                                                                        <div class="col-md-12">
                                                                                            <div class="form-group mb-0">
                                                                                                <label><strong>Task Status:</strong></label>
                                                                                                <select class="form-control task_status_visaCancel" name="task_status" data-leadid="<?php echo $sub_lead_id; ?>" <?php echo ($value->task_status == 'completed') ? 'disabled' : '' ?> >
                                                                                                    <option value="">-- Choose -- </option>
                                                                                                    <option value="submitted" <?php echo ($value->task_status == 'submitted') ? 'selected' : '' ?>><?php echo "Submitted"; ?> </option>
                                                                                                    <option value="open" <?php echo ($value->task_status == 'open') ? 'selected' : '' ?>><?php echo "Open"; ?> </option>
                                                                                                    <option value="completed" <?php echo ($value->task_status == 'completed') ? 'selected' : '' ?>><?php echo "Completed"; ?> </option>
                                                                                                    <option value="visitRequired" <?php echo ($value->task_status == 'visitRequired') ? 'selected' : '' ?>><?php echo "Visit Required"; ?> </option>
                                                                                                    <option value="hold" <?php echo ($value->task_status == 'hold') ? 'selected' : '' ?>><?php echo "Hold"; ?> </option>
                                                                                                </select>
                                                                                            </div>
                                                                                        </div>
                                                                                        
                                                                                    </div>
                                                                                </div>
                                                                            </td>
                                                                            <td colspan="4">
                                                                                <div class="card-body p-0">
                                                                                    <div class="row">
                                                                                        <div class="col-md-6">
                                                                                            <div class="form-group">
                                                                                                <label for="document_upload_visaCancel"><strong>Document Upload: <span class="text-danger required d-none">*</span></strong></label>
                                                                                                <input type="file" id="document_upload_visaCancel" class="form-control document_upload" name="attachments_lead[]" multiple <?php echo ($value->task_status == 'completed') ? 'disabled' : '';?>>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-md-4 align-content-center">
                                                                                            <button type="submit" class="btn btn-success mt-2" data-lead-id="<?php echo $sub_lead_id; ?>" <?php echo ($value->task_status == 'completed') ? 'disabled' : ''; ?>>Submit</button>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </td>
                                                                            </form>
                                                                        </tr>
                                                                    <?php } ?>
                                                                    <!-- Visa Entry Permit -->
                                                                    <?php if(($value->gc_service_id == 2311) && $value->task_created == 1) { ?>
                                                                        <tr>
                                                                            <form action="<?php echo getenv('CRM_URL'); ?>leads/lead/action_task_visa_permit/<?php echo $sub_lead_id;?>" method="post" enctype="multipart/form-data">
                                                                            <td colspan="5">
                                                                                <input type="hidden" name="lead_parent_id" value="<?php echo $lead_details['id']; ?>">
                                                                                <input type="hidden" name="task_service" value="<?php echo $value->gc_service_id; ?>">
                                                                                <div class="card-body p-0">
                                                                                    <div class="row">
                                                                                        <div class="col-md-3">
                                                                                            <div class="form-group mb-0">
                                                                                                <label><strong>Task Status:</strong></label>
                                                                                                <select class="form-control task_status_visaPermit" name="task_status" data-leadid="<?php echo $sub_lead_id; ?>" <?php echo ($value->task_status == 'completed') ? 'disabled' : '' ?> >
                                                                                                    <option value="">-- Choose -- </option>
                                                                                                    <?php if($value->lead_status == 305) { ?>
                                                                                                        <option value="submitted" <?php echo ($value->task_status == 'submitted') ? 'selected' : '' ?>>Submitted</option>
                                                                                                        <option value="completed" <?php echo ($value->task_status == 'completed') ? 'selected' : '' ?>>Completed</option>
                                                                                                        <!-- <option value="ADR On Hold" <?php echo ($value->task_status == 'ADR On Hold') ? 'selected' : '' ?>>ADR On Hold </option> -->
                                                                                                        <option value="rejected" <?php echo ($value->task_status == 'rejected') ? 'selected' : '' ?>>Rejected</option>
                                                                                                    <?php } ?>
                                                                                                    <option value="nooption" <?php echo ($value->task_status == 'nooption') ? 'selected' : '' ?>>No option</option>
                                                                                                </select>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-md-3">
                                                                                            <div class="form-group">
                                                                                                <label for="document_upload_visaPermit"><strong>Document Upload: <span class="text-danger required d-none">*</span></strong></label>
                                                                                                <input type="file" id="document_upload_visaPermit" class="form-control document_upload" name="attachments_lead[]" multiple <?php echo ($value->task_status == 'completed') ? 'disabled' : '';?>>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-md-3">
                                                                                            <div class="form-group">
                                                                                                <label for="task_remarks"><strong>Remarks:</strong></label>
                                                                                                <textarea class="form-control" name="task_remarks" id="task_remarks" rows="3"><?php echo $value->task_remarks; ?></textarea>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-md-3 align-content-center">
                                                                                            <button type="submit" class="btn btn-success mt-2" data-lead-id="<?php echo $sub_lead_id; ?>" <?php echo ($value->task_status == 'completed') ? 'disabled' : ''; ?>>Submit</button>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </td>
                                                                            </form>
                                                                        </tr>
                                                                    <?php } ?>
                                                                    <!-- Reopen Task -->
                                                                    <?php if($value->lead_status == 305 && ($value->task_status == 'completed' || $value->task_status == 'hold') && $lead_details["branch_id"] == 106 && $this->auth_user_role == 7  && $value->task_created == 1) { ?>
                                                                        <tr>
                                                                            <form action="<?php echo getenv('CRM_URL'); ?>leads/lead/action_task_reopen/<?php echo $sub_lead_id;?>" method="post">
                                                                                <input type="hidden" name="lead_parent_id" value="<?php echo $lead_details['id']; ?>">
                                                                                <td colspan="5">
                                                                                    <div class="card-body p-0">
                                                                                        <div class="row">
                                                                                            <div class="col-md-4">
                                                                                                <div class="form-group mb-0">
                                                                                                    <label><strong>Remarks:</strong></label>
                                                                                                    <textarea name="reopen_remarks" class="form-control reopen_remarks" rows="3" required></textarea>
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="col-md-4 align-content-end">
                                                                                                <button class="btn btn-primary reopen" data-subleadid="<?php echo $sub_lead_id;?>">Reopen</button>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                            </form>
                                                                        </tr>
                                                                    <?php } ?>
                                                            <?php
                                                                }
                                                            }
                                                            ?>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                    <?php
                                        }
                                    }
                                    ?>



                                </div>
                            </div>
                        </div>

                        <?php
                        if ($this->session->flashdata('alert')) {
                        ?>
                            <div class="alert alert-<?php echo $this->session->flashdata('alert_complete'); ?>">
                                <?php echo $this->session->flashdata('alert_complete_message'); ?>
                            </div>
                        <?php
                        }
                        ?>

                        <div class="row justify-content-center">
                            <!-- Follow-ups -->
                            <div class="col-lg-8 col-12 mt-4" id="follow-up-id">
                                <?php
                                if ($this->session->flashdata('alert')) {
                                ?>
                                    <div class="alert alert-<?php echo $this->session->flashdata('alert'); ?>">
                                        <?php echo $this->session->flashdata('alert_message'); ?>
                                    </div>
                                <?php
                                }
                                ?>
                                <?php if ($lead_current_status != 305 && $lead_current_status != 306 && $lead_current_status != 309 && $is_biz_lead != 1) { ?>
                                    <div class="app-inner-layout__header text-white bg-night-sky br-tr br-tl"
                                        <?php echo $is_biz_lead; ?>>
                                        <div class="app-page-title app-page-title-simple">
                                            <div class="page-title-wrapper">
                                                <div class="page-title-heading">
                                                    <div>Follow-ups
                                                        <div class="page-title-subheading">Manage the enquiry</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="app-inner-layout__wrapper row-fluid no-gutters" id="followups">
                                        <div class="app-inner-layout__sidebar bg-transparent card" style="">
                                            <div class="p-3 stick-to-parent" style="">
                                                <div
                                                    class="dropdown-menu nav p-0 dropdown-menu-inline dropdown-menu-rounded dropdown-menu-hover-primary">
                                                    <h6 tabindex="-1" class="pt-0 dropdown-header">Menu</h6>
                                                    <a href="#req_followup" data-toggle="tab" tabindex="0"
                                                        class="mb-1 dropdown-item <?php echo $auth_user_privilege['request_followup'] == 'disable' ? 'disabled' : ''; ?> <?php
                                                                                                                                                                    if ($lead_details["lead_created_by"] != 2906815795 && $auth_user_privilege['followup_through_email'] == "true") {
                                                                                                                                                                        echo "show active";
                                                                                                                                                                    }  ?>" aria-expanded="true" id="request-followup-tab">Request Followup </a>

                                                    <a href="#req_sts_upt" data-toggle="tab" tabindex="0"
                                                        class="mb-1 dropdown-item <?php echo $auth_user_privilege['request_status_update'] == 'disable' ? 'disabled' : ''; ?>" aria-expanded="true">Request Status Update </a>
                                                    <!--                                                 
                                                    <?php if ($lead_details["branch_id"] == 106 || $lead_details['branch_name'] == 'Golden Cube') { ?>
                                                        <a href="#tab-faq-10" data-toggle="tab" tabindex="0"
                                                        class="mb-1 dropdown-item" aria-expanded="true">Eligibility
                                                        Check</a>
                                                    <?php } ?>
                                                <a href="#tab-faq-0" data-toggle="tab" tabindex="0"
                                                    class="mb-1 dropdown-item" aria-expanded="true">Setup Meeting </a>
                                                <a href="#tab-faq-1" data-toggle="tab" tabindex="0"
                                                    class="mb-1 dropdown-item <?php
                                                                                if ($lead_details["lead_created_by"] != 2906815795) {
                                                                                    echo "show active";
                                                                                }

                                                                                ?>" aria-expanded="true">Through
                                                    Email </a>
                                                <?php
                                                if ($lead_details["lead_created_by"] == 2906815795) {
                                                ?>
                                                <a href="#tab-faq-9" data-toggle="tab" tabindex="0"
                                                    class="mb-1 dropdown-item show active" aria-expanded="true">Status
                                                    Updation </a>

                                                <?php
                                                }
                                                ?>
                                                <a href="#tab-faq-2" data-toggle="tab" tabindex="0"
                                                    class="mb-1 dropdown-item show" aria-expanded="true">Through
                                                    Call</a>
                                                <?php if ($payst == 1) { ?>
                                                <a href="#tab-faq-6" data-toggle="tab" tabindex="0"
                                                    class="mb-1 dropdown-item show payment_form_toggle"
                                                    aria-expanded="true">Through Payment
                                                    Link</a>
                                                <?php } ?>
                                                <a href="#tab-faq-3" data-toggle="tab" tabindex="0"
                                                    class="mb-1 dropdown-item show" aria-expanded="true">Custom</a>
                                                <?php if (count($payment_histories) == 0) { ?>
                                                <a href="#tab-faq-4" data-toggle="tab" tabindex="0"
                                                    class="mb-1 dropdown-item show" aria-expanded="true">Order
                                                    Confirm</a>
                                                <?php } ?>
                                                <a href="#tab-faq-5" data-toggle="tab" tabindex="0"
                                                    class="mb-1 dropdown-item show" aria-expanded="true">Close the
                                                    Lead</a>
                                                <?php
                                                // }
                                                ?>  -->

                                                <?php if (!empty($sub_leads) && !empty($lead_package_details) && in_array($lead_package_details[0]->package_id, [199,204,205,258,259,260])) { // valuation?>
                                                        <a href="#send_quot_tab" data-toggle="tab" tabindex="0" class="mb-1 dropdown-item" aria-expanded="true" id='create-quota-tab'>
                                                            Send Quotation
                                                        </a>
                                                <?php } ?>
                                                <?php

                                                $before_payment_packages = [199,204,205,258,259,260, 200,201,206,207,208,209,261,262,263, 222,223,224, 225,226,227, 228,229,230];
                                                // valuation, insurance, translation, attestation, shipping
                                                
                                                if ($lead_details['branch_id'] == 106 && empty($lead_package_details) && !in_array($lead_details['package_id'], $before_payment_packages) ) { ?>
                                                    <a href="#create_quot_tab" data-toggle="tab" tabindex="0"
                                                        class="mb-1 dropdown-item quotation_form_toggle"
                                                        aria-expanded="true">
                                                        Create Quotation
                                                    </a>
                                                    <?php } ?>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 app-inner-layout__content card">
                                            <div class="pb-5 pl-5 pr-5 pt-3">
                                                <div class="mobile-app-menu-btn mb-3">
                                                    <button type="button" class="hamburger hamburger--elastic">
                                                        <span class="hamburger-box">
                                                            <span class="hamburger-inner"></span>
                                                        </span>
                                                    </button>
                                                </div>
                                                <div class="tab-content">



                                                    <!-- setup_meeting, followup_through_email, followup_through_call, custom, through_payment_link, 
                                            request_status_update, eligibility_check, enquiry, missing_documents, order_confirm, disqualified -->

                                                    <div class="tab-pane <?php echo ($lead_details["lead_created_by"] != 2906815795 && $auth_user_privilege['followup_through_email'] == "true") ? 'show active' : ''; ?>" id="req_followup">
                                                        <select class="form-control mb-2" name="req_followup" onchange="$('#req_followup .tab-pane').removeClass('d-block').addClass('d-none');$('#'+this.value).addClass('d-block').removeClass('d-none');" required>
                                                            <option value="" disabled selected>--Options--</option>
                                                            <option value="tab-faq-0" class="<?php echo $auth_user_privilege['setup_meeting'] == 'false' ? 'd-none' : ''; ?>">Setup Meeting</option>
                                                            <option value="tab-faq-1" class="<?php echo $auth_user_privilege['followup_through_email'] == 'false' ? 'd-none' : ''; ?>" <?php if ($lead_details["lead_created_by"] != 2906815795 && $auth_user_privilege['followup_through_email'] == "true") {
                                                                                                                                                                                        echo "selected";
                                                                                                                                                                                    } ?>>Followup through Email</option>
                                                            <option value="tab-faq-2" class="<?php echo $auth_user_privilege['followup_through_call'] == 'false' ? 'd-none' : ''; ?>">Followup through Call</option>
                                                            <option value="tab-faq-3" class="<?php echo $auth_user_privilege['custom'] == 'false' ? 'd-none' : ''; ?>">Custom</option>
                                                            <?php if ($payst == 1) { ?>
                                                                <option value="tab-faq-6" class="<?php echo $auth_user_privilege['through_payment_link'] == 'false' ? 'd-none' : ''; ?>">Through payment Link</option>
                                                            <?php } ?>
                                                            <?php if(isset( $lead_details["lead_zoho_conversation_id"]) && $lead_details["lead_zoho_conversation_id"] != ""){ ?>
                                                                <option value="tab-faq-13">Redirect Zoho Conversation</option>
                                                            <?php } ?>
                                                        </select>
                                                        <hr>
                                                        <div class="tab-pane d-none" id="tab-faq-0">
                                                            <?php
                                                            if ($fake_domain == 'yes') {
                                                            ?>
                                                                <center class="pt-4">
                                                                    CUSTOMER EMAIL IS INVALID/NOT PROVIDED. PLEASE UPDATE THE
                                                                    CUSTOMER EMAIL TO USE THIS OPTION.<br /><br /><br />
                                                                    <button type="button" class="btn btn-rounded btn-warning"
                                                                        data-name="<?php echo $lead_details['customer_name']; ?>"
                                                                        data-email="<?php echo $lead_details['customer_email']; ?>"
                                                                        data-countrycode="<?php echo $lead_details['customer_country_code']; ?>"
                                                                        data-mobile="<?php echo $lead_details['customer_mobile']; ?>"
                                                                        data-toggle="modal" data-target="#modelId"><span
                                                                            class="btn-icon-left text-warning"><i
                                                                                class="fa fa-pencil color-warning"></i></span>Update
                                                                        customer information</button>

                                                                </center>
                                                            <?php
                                                            } else {
                                                            ?>
                                                                <form action="<?php echo getenv('CRM_URL'); ?>leads/lead/action_meeting/<?php echo $this->uri->segment(4); ?>" method="post">
                                                                    <div class="row">
                                                                        <div class="col-md-12">
                                                                            <div class="form-group">
                                                                                <label>Meeting Date &amp; Time&nbsp;<span
                                                                                        class="text-danger required">*</span></label>

                                                                                <input type="text" id="meeting_date"
                                                                                    name="contactable_date" class="form-control"
                                                                                    placeholder=""
                                                                                    value="<?php echo date('Y-m-d H:i:s', time() + 86400); ?>"
                                                                                    name="daterange-centered">
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label>Remarks&nbsp;<span
                                                                                        class="text-danger required">*</span></label>
                                                                                <textarea rows="3" class="form-control"
                                                                                    name="meeting_remarks"
                                                                                    id="meeting_remarks"></textarea>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <input type="submit" name="action_meeting"
                                                                                    class="btn btn-primary btn-block"
                                                                                    value="SETUP MEETING">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            <?php
                                                            }
                                                            ?>
                                                        </div>

                                                        <div class="tab-pane <?php if ($lead_details["lead_created_by"] != 2906815795 && $auth_user_privilege['followup_through_email'] == "true") {
                                                                                    echo "d-block";
                                                                                } else {
                                                                                    echo "d-none";
                                                                                }
                                                                                ?>" id="tab-faq-1">
                                                            <?php
                                                            if ($fake_domain == 'yes') {
                                                            ?>
                                                                <center class="pt-4">
                                                                    CUSTOMER EMAIL IS INVALID/NOT PROVIDED. PLEASE UPDATE THE
                                                                    CUSTOMER EMAIL TO USE THIS OPTION.<br /><br /><br />
                                                                    <button type="button" class="btn btn-rounded btn-warning"
                                                                        data-name="<?php echo $lead_details['customer_name']; ?>"
                                                                        data-email="<?php echo $lead_details['customer_email']; ?>"
                                                                        data-countrycode="<?php echo $lead_details['customer_country_code']; ?>"
                                                                        data-mobile="<?php echo $lead_details['customer_mobile']; ?>"
                                                                        data-toggle="modal" data-target="#modelId"><span
                                                                            class="btn-icon-left text-warning"><i
                                                                                class="fa fa-pencil color-warning"></i></span>Update
                                                                        customer information</button>

                                                                </center>
                                                            <?php
                                                            } else {
                                                            ?>
                                                                <form
                                                                    action="<?php echo getenv('CRM_URL'); ?>leads/lead/action_email/<?php echo $this->uri->segment(4); ?>" method="post" enctype="multipart/form-data">
                                                                    <div class="row">
                                                                        <div class="col-md-12">
                                                                            <div class="form-group">
                                                                                <label>From Email&nbsp;<span
                                                                                        class="text-danger required">*</span></label>
                                                                                <input type="email" class="form-control"
                                                                                    placeholder="from" readonly="" required
                                                                                    value="<?php echo $this->auth_email; ?>"
                                                                                    name="from_email">
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label>To Email (Customer)&nbsp;<span
                                                                                        class="text-danger required">*</span></label>
                                                                                <input type="email" class="form-control"
                                                                                    placeholder="To Email Address"
                                                                                    value="<?php echo $lead_details['customer_email']; ?>"
                                                                                    name="customer_email" required
                                                                                    pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$"
                                                                                    title="Please enter a valid email address.">
                                                                                <input type="hidden" name="agent_email"
                                                                                    value="<?php echo $this->auth_email; ?>">
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label>Subject&nbsp;<span
                                                                                        class="text-danger required">*</span></label>
                                                                                <?php
                                                                                if ($lead_details["branch_id"] == 106) {
                                                                                ?>
                                                                                    <input type="text" class="form-control"
                                                                                        placeholder="from" required
                                                                                        value="GOLDENCUBE LEADID - <?php echo $lead_details['id']; ?> - Followup regarding <?php echo $lead_details['category_name']; ?> - <?php echo $lead_details['service_name']; ?> - ##RE-<?php echo trim($lead_details['email_request_id']); ?>##"
                                                                                        name="email_subject">
                                                                                <?php
                                                                                } else {
                                                                                ?>
                                                                                    <input type="text" class="form-control"
                                                                                        placeholder="from" required
                                                                                        value="ONTIME LEADID - <?php echo $lead_details['id']; ?> - Followup regarding <?php echo $lead_details['category_name']; ?> - <?php echo $lead_details['service_name']; ?> -##RE-<?php echo trim($lead_details['email_request_id']); ?>##"
                                                                                        name="email_subject">

                                                                                <?php
                                                                                }
                                                                                ?>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label>Choose email template</label>
                                                                                <select class="form-control" name="template_id"
                                                                                    id="template_id"
                                                                                    onchange="javascript:apply_email_template(this.value);">
                                                                                    <option value="">-- Choose template --</option>
                                                                                    <?php
                                                                                    foreach ($email_templates as $key => $value) {
                                                                                    ?>
                                                                                        <option value="<?php echo $value['id']; ?>">
                                                                                            <?php echo $value['template_name']; ?>
                                                                                        </option>
                                                                                    <?php
                                                                                    }
                                                                                    ?>
                                                                                </select>
                                                                            </div>

                                                                            <div class="form-group">
                                                                                <label>Body&nbsp;<span
                                                                                        class="text-danger required">*</span></label>
                                                                                <textarea rows="5" class="form-control ckeditor"
                                                                                    name="email_message" required
                                                                                    id="email_editor"></textarea>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label>Attachments (Optional)</label>
                                                                                <input class="form-control" type="file"
                                                                                    name="email_attachments[]" multiple>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label>Remarks for your reference (Optional)</label>
                                                                                <textarea rows="3" class="form-control"
                                                                                    name="email_remarks" id="editor"></textarea>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label>Next Contactable Date&nbsp;<span
                                                                                        class="text-danger required">*</span></label>

                                                                                <input type="datetime-local" id="email_date"
                                                                                    name="contactable_date" class="form-control"
                                                                                    placeholder="" min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>" required
                                                                                    value="<?php echo date('Y-m-d\TH:i', time() + 86400); ?>">
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <input type="submit" name="action_email"
                                                                                    class="btn btn-primary btn-block"
                                                                                    value="SEND EMAIL">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            <?php
                                                            }
                                                            ?>
                                                        </div>

                                                        <div class="tab-pane show d-none" id="tab-faq-2">
                                                            <form
                                                                action="<?php echo getenv('CRM_URL'); ?>leads/lead/action_call/<?php echo $this->uri->segment(4); ?>"
                                                                method="post">
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <label>Remarks&nbsp;<span
                                                                                    class="text-danger required">*</span></label>
                                                                            <textarea rows="5" class="form-control" required
                                                                                name="call_remarks" id="call_remarks"></textarea>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label>Next Contactable Date&nbsp;<span
                                                                                    class="text-danger required">*</span></label>

                                                                            <input type="datetime-local" id="call_date"
                                                                                name="contactable_date" class="form-control"
                                                                                placeholder="" required
                                                                                value="<?php echo date('Y-m-d\TH:i', time() + 86400); ?>">
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <input type="submit" name="action_call"
                                                                                class="btn btn-primary btn-block"
                                                                                value="UPDATE CALL STATUS">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>

                                                        <div class="tab-pane show d-none" id="tab-faq-3">
                                                            <form
                                                                action="<?php echo getenv('CRM_URL'); ?>leads/lead/action_custom/<?php echo $this->uri->segment(4); ?>"
                                                                method="post">
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <label>Remarks&nbsp;<span
                                                                                    class="text-danger required">*</span></label>
                                                                            <textarea rows="5" class="form-control"
                                                                                name="custom_remarks" required
                                                                                id="custom_remarks"></textarea>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label>Next Contactable Date&nbsp;<span
                                                                                    class="text-danger required">*</span></label>

                                                                            <input type="datetime-local" id="custom_date"
                                                                                name="contactable_date" class="form-control"
                                                                                placeholder="" required
                                                                                value="<?php echo date('Y-m-d\TH:i', time() + 86400); ?>">
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <input type="submit" name="action_custom"
                                                                                class="btn btn-primary btn-block"
                                                                                value="UPDATE TIMELINE">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>

                                                        <?php if ($payst == 1) { ?>
                                                            <div class="tab-pane show d-none" id="tab-faq-6">
                                                                <?php
                                                                if ($fake_domain == 'yes') {
                                                                ?>
                                                                    <center class="pt-4">
                                                                        CUSTOMER EMAIL IS INVALID/NOT PROVIDED. PLEASE UPDATE THE
                                                                        CUSTOMER EMAIL TO USE THIS OPTION.<br /><br /><br />
                                                                        <button type="button" class="btn btn-rounded btn-warning"
                                                                            data-name="<?php echo $lead_details['customer_name']; ?>"
                                                                            data-email="<?php echo $lead_details['customer_email']; ?>"
                                                                            data-countrycode="<?php echo $lead_details['customer_country_code']; ?>"
                                                                            data-mobile="<?php echo $lead_details['customer_mobile']; ?>"
                                                                            data-toggle="modal" data-target="#modelId"><span
                                                                                class="btn-icon-left text-warning"><i
                                                                                    class="fa fa-pencil color-warning"></i></span>Update
                                                                            customer information</button>

                                                                    </center>
                                                                    <?php
                                                                } else {
                                                                    if ($lead_details["lead_parent_id"] == NULL) {
                                                                        if ($lead_details["branch_id"] == 106 && $lead_details["service_id"] == 10009) {
                                                                    ?>
                                                                            <h4 class="text-center mt-5 pt-5 mb-3">Payment Already Requested or
                                                                                Done</h4>
                                                                        <?php
                                                                        } else if ($lead_details["category_id"] != 125 && $lead_details["service_id"] != 103) {

                                                                        ?>
                                                                            <form
                                                                                action="<?php echo getenv('CRM_URL'); ?>leads/lead/action_payment/<?php echo $this->uri->segment(4); ?>"
                                                                                method="post">
                                                                                <div class="row">
                                                                                    <div class="col-md-12">
                                                                                        <div class="form-group">
                                                                                            <label>From Email&nbsp;<span
                                                                                                    class="text-danger required">*</span></label>
                                                                                            <input type="email" required class="form-control"
                                                                                                placeholder="from" readonly=""
                                                                                                value="<?php echo $this->auth_email; ?>"
                                                                                                name="from_email">
                                                                                        </div>
                                                                                        <div class="form-group">
                                                                                            <label>To Email (Customer)&nbsp;<span
                                                                                                    class="text-danger required">*</span></label>
                                                                                            <input type="email" required class="form-control"
                                                                                                placeholder="from" readonly=""
                                                                                                value="<?php echo $lead_details['customer_email']; ?>"
                                                                                                name="customer_email">
                                                                                            <input type="hidden" name="agent_email"
                                                                                                value="<?php echo $this->auth_email; ?>">
                                                                                        </div>
                                                                                        <div class="form-group">
                                                                                            <label>Subject&nbsp;<span
                                                                                                    class="text-danger required">*</span></label>
                                                                                            <input type="text" required class="form-control"
                                                                                                placeholder="from"
                                                                                                value="ONTIME - Followup regarding <?php echo $lead_details['category_name']; ?> - <?php echo $lead_details['service_name'] . ' - Payment Link'; ?>"
                                                                                                name="email_subject">
                                                                                        </div>

                                                                                        <div class="form-group">
                                                                                            <label>Body&nbsp;<span
                                                                                                    class="text-danger required">*</span></label>
                                                                                            <textarea rows="5" class="form-control ckeditor"
                                                                                                name="email_message"
                                                                                                id="email_editor2"></textarea>
                                                                                        </div>
                                                                                        <div class="form-group">
                                                                                            <label>Amount&nbsp;<span
                                                                                                    class="text-danger required">*</span></label>
                                                                                            <input type="number" required class="form-control"
                                                                                                placeholder="AED" step="0.01"
                                                                                                name="amount_payment">
                                                                                        </div>
                                                                                        <div class="form-group">
                                                                                            <label>Remarks for your reference (Optional)</label>
                                                                                            <textarea rows="3" class="form-control"
                                                                                                name="email_remarks" id="editor2"></textarea>
                                                                                        </div>
                                                                                        <div class="form-group">
                                                                                            <label>Next Contactable Date&nbsp;<span
                                                                                                    class="text-danger required">*</span></label>
                                                                                            <input type="datetime-local" id="email_date"
                                                                                                name="contactable_date" class="form-control"
                                                                                                placeholder="" required
                                                                                                value="<?php echo date('Y-m-d\TH:i', time() + 86400); ?>">
                                                                                        </div>
                                                                                        <div class="form-group">
                                                                                            <input type="submit" name="action_payment"
                                                                                                class="btn btn-primary btn-block"
                                                                                                value="SEND EMAIL">
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </form>
                                                                        <?php
                                                                        } else {
                                                                        ?>

                                                                            <div class="m-auto row pt-5">
                                                                                <!-- Button trigger modal -->
                                                                                <button type="button" class="btn btn-primary btn-lg m-auto mt-4"
                                                                                    data-toggle="modal" data-target="#payment_form">
                                                                                    Payment Form
                                                                                </button>
                                                                            </div>

                                                                            <!-- Modal -->

                                                                        <?php
                                                                        }
                                                                    } else {
                                                                        // Additional Payment
                                                                        ?>
                                                                        <form id="addPaymentForm"
                                                                            action="<?php echo getenv('CRM_URL'); ?>leads/lead/action_addpayment/<?php echo $this->uri->segment(4); ?>"
                                                                            method="post">
                                                                                <input type="hidden" name="customer_otp" value="">
                                                                                <input type="hidden" name="user_email" value="<?php echo $auth_user_data['email']; ?>">
                                                                                <input type="hidden" name="user_pos_id" value="<?php echo $auth_user_data['employee_id']; ?>">
                                                                                <input type="hidden" name="customer_id" value="<?php echo $lead_customer->user_id; ?>">
                                                                                <input type="hidden" name="lead_country_code" value="<?php echo $lead_customer->country_code; ?>">
                                                                                <input type="hidden" name="lead_contact" value="<?php echo $lead_customer->mobile; ?>">
                                                                                <input type="hidden" name="lead_name" value="<?php echo $lead_customer->first_name; ?>">
                                                                            <div class="row">
                                                                                <div class="col-md-12">
                                                                                    <div class="form-group">
                                                                                        <label>From Email&nbsp;<span
                                                                                                class="text-danger required">*</span></label>
                                                                                        <input type="email" required class="form-control"
                                                                                            placeholder="from" readonly=""
                                                                                            value="<?php echo $this->auth_email; ?>"
                                                                                            name="from_email">
                                                                                    </div>
                                                                                    <div class="form-group">
                                                                                        <label>To Email (Customer)&nbsp;<span
                                                                                                class="text-danger required">*</span></label>
                                                                                        <input type="email" required class="form-control"
                                                                                            placeholder="from" readonly=""
                                                                                            value="<?php echo $lead_details['customer_email']; ?>"
                                                                                            name="customer_email">
                                                                                        <input type="hidden" name="agent_email"
                                                                                            value="<?php echo $this->auth_email; ?>">
                                                                                    </div>
                                                                                    <div class="form-group">
                                                                                        <label>Subject&nbsp;<span
                                                                                                class="text-danger required">*</span></label>
                                                                                        <input type="text" required class="form-control"
                                                                                            placeholder="from"
                                                                                            value="ONTIME - Followup regarding <?php echo $lead_details['category_name']; ?> - <?php echo $lead_details['service_name'] . ' - Payment Link'; ?>"
                                                                                            name="email_subject">
                                                                                    </div>

                                                                                    <div class="form-group">
                                                                                        <label>Body&nbsp;<span
                                                                                                class="text-danger required">*</span></label>
                                                                                        <textarea rows="5" class="form-control ckeditor"
                                                                                            name="email_message"
                                                                                            id="email_editor2"></textarea>
                                                                                    </div>
                                                                                    <div class="form-group">
                                                                                        <label>Select Sub Lead</label>
                                                                                        <select class="form-control" name="sub_lead_id"
                                                                                            id="sub_lead_id">
                                                                                            <option value="">-- Sub Lead --</option>
                                                                                            <?php
                                                                                            foreach ($sub_leads as $key => $value) {
                                                                                            ?>
                                                                                                <option value="<?php echo $value->id; ?>">
                                                                                                    <?php echo $value->remarks; ?>
                                                                                                </option>
                                                                                            <?php
                                                                                            }
                                                                                            ?>
                                                                                        </select>
                                                                                    </div>
                                                                                    <?php if($lead_details["branch_id"] == 106) { ?>
                                                                                    <div class="form-group">
                                                                                        <label>Main Service Type&nbsp;<span
                                                                                                class="text-danger required">*</span></label>
                                                                                        <select class="form-control required" name="main_service_type"
                                                                                            id="main_service_type" required>
                                                                                            <option value="">-- Select Main Service Type --</option>
                                                                                            <option value="23">Insurance</option>
                                                                                            <option value="others">Others</option>

                                                                                        </select>
                                                                                    </div>
                                                                                    <?php } ?>
                                                                                    <?php if ($logged_primary_group_id == 41) { ?>
                                                                                        <div class="form-group">
                                                                                            <label>Cost Centers</label>
                                                                                            <select class="form-control" name="cc_cost_center"
                                                                                                id="cc_cost_center">
                                                                                                <option value="">-- Select Cost Center --</option>
                                                                                                <?php foreach ($company_cost_centers as $value) { 
                                                                                                    if($value['is_active'] == 1) { ?>
                                                                                                    <option value="<?php echo $value['cc_key']; ?>">
                                                                                                        <?php echo $value['cc_name']; ?>
                                                                                                    </option>
                                                                                                <?php }} ?>

                                                                                            </select>
                                                                                        </div>
                                                                                        <div class="form-group">
                                                                                            <label>Company</label>
                                                                                            <input type="text" class="form-control" id="cc_company_name" name="cc_company_name" value="" readonly>
                                                                                            <input type="hidden" class="form-control" id="cc_company" name="cc_company" value="" readonly>
                                                                                            <input type="hidden" class="form-control" id="cc_company_outlet" name="cc_company_outlet" value="" readonly>
                                                                                        </div>
                                                                                        <!-- <div class="form-group">
                                                                                            <label>Company Name</label>
                                                                                            <select class="form-control" name="cc_company"
                                                                                                id="cc_company">
                                                                                                <option value="">-- Select Company Name --</option>
                                                                                                <?php foreach ($company_outlets as $value) { ?>
                                                                                                    <option value="<?php echo $value['company_key']; ?>">
                                                                                                        <?php echo $value['company_name']; ?>
                                                                                                    </option>
                                                                                                <?php } ?>

                                                                                            </select>
                                                                                        </div> -->
                                                                                    <?php } ?>
                                                                                    <div class="form-group">
                                                                                        <label for="">Payment Type&nbsp;<span
                                                                                                class="text-danger required">*</span></label>
                                                                                        <div class="form-check form-check-inline w-100">
                                                                                            <label class="form-check-label">
                                                                                                <input class="form-check-input fabs" type="radio"
                                                                                                    name="payment_type" id="" value="online"
                                                                                                    checked onclick="hide_code()">Payment
                                                                                                Link
                                                                                            </label>
                                                                                            <label class="form-check-label">
                                                                                                <input class="form-check-input ml-3 fabs"
                                                                                                    type="radio" name="payment_type" id=""
                                                                                                    value="cash" onclick="show_popup()">Cash
                                                                                            </label>
                                                                                            <label class="form-check-label">
                                                                                                <input class="form-check-input ml-3 fabs"
                                                                                                    type="radio" name="payment_type"
                                                                                                    id="cash_type" value="card"
                                                                                                    onclick="show_code()">Card
                                                                                            </label>

                                                                                        </div>
                                                                                    </div>
                                                                                    <?php if(($lead_details["branch_id"] == 119 && $lead_details["lead_from"] == 'OntimeGOV' &&
                                                                                    $lead_details['otg_paylater'] == 1) || ($lead_details["branch_id"] == 113 && $lead_details["lead_from"] == 'DLD')) { ?>
                                                                                    <div class="form-group">
                                                                                        <?php  echo "<a href='#' id='fetch_service_amount' class='text-white badge badge-primary text-capitalize' data-id ='".$lead_details["otg_order_id"]."' >Fetch Service Amount</a>";   
                                                                                        ?>
                                                                                    </div>
                                                                                    <?php } ?>
                                                                                    <div class="row" style="border-style:solid;border-width:1px;border-color:#00f;border-radius:15px;padding-top:10px;">
                                                                                        <div class="col-md-4">
                                                                                            <div class="form-group">
                                                                                                <label>Government Fees&nbsp;</label>
                                                                                                <input type="text" required class="form-control" placeholder="AED" name="ad_gov_fee" id="ad_gov_fee" onkeyup="get_vat_amount();">
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-md-4">
                                                                                            <div class="form-group">
                                                                                                <label>Typing Fees&nbsp;</label>
                                                                                                <input type="text" required class="form-control" placeholder="AED" name="ad_typing_fees" onkeyup="get_vat_amount();" id="ad_typing_fees">
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-md-4">
                                                                                            <div class="form-group">
                                                                                                <label>Vendor Commission&nbsp;</label>
                                                                                                <input type="text" required class="form-control" placeholder="AED" name="ad_vendor_com" id="ad_vendor_com" onkeyup="gettot();">
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-md-4">
                                                                                            <div class="form-group">
                                                                                                <label>Online Transaction Charge&nbsp;</label>
                                                                                                <input type="text" required class="form-control" placeholder="AED" name="ad_online_charge" id="ad_online_charge" onkeyup="gettot();" readonly>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-md-4">
                                                                                            <div class="form-group">
                                                                                                <label>Tax Applicable&nbsp;</label><br />
                                                                                                <input type="checkbox" id="toggle" onchange="get_vat_amount();" checked />
                                                                                                <label for="toggle" class="labeled"></label>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-md-4">
                                                                                            <div class="form-group">
                                                                                                <label>VAT&nbsp;</label>
                                                                                                <input type="text" required class="form-control" placeholder="AED" name="ad_tax" id="ad_tax" onkeyup="gettot();" readonly>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <script>
                                                                                        $('.fabs').change(function() {
                                                                                            get_vat_amount();
                                                                                        });

                                                                                        function get_vat_amount() {
                                                                                            var isChecked = $('#toggle').prop('checked');
                                                                                            if (isChecked) {
                                                                                                var ad_typing_fees = parseFloat($('#ad_typing_fees').val());
                                                                                                if (!isNaN(ad_typing_fees) && ad_typing_fees > 0) {
                                                                                                    var at = (ad_typing_fees / 100) * 5;
                                                                                                    $('#ad_tax').val(at);
                                                                                                } else {
                                                                                                    $('#ad_tax').val(0);
                                                                                                }
                                                                                            } else {
                                                                                                $('#ad_tax').val(0);
                                                                                            }
                                                                                            var checkedValue = $('input[name="payment_type"]:checked').val();
                                                                                            if (checkedValue == 'online') {
                                                                                                var ad_gov_fee = parseFloat($('#ad_gov_fee').val());
                                                                                                if (!isNaN(ad_gov_fee) && ad_gov_fee > 0) {
                                                                                                    var at = (ad_gov_fee / 100) * 2.25;
                                                                                                    $('#ad_online_charge').val(at);
                                                                                                } else {
                                                                                                    $('#ad_online_charge').val(0);
                                                                                                }
                                                                                            } else if (checkedValue == 'card') {
                                                                                                var ad_gov_fee = parseFloat($('#ad_gov_fee').val());
                                                                                                if (!isNaN(ad_gov_fee) && ad_gov_fee > 0) {
                                                                                                    var at = (ad_gov_fee / 100) * 1;
                                                                                                    $('#ad_online_charge').val(at);
                                                                                                }
                                                                                            } else if (checkedValue == 'cash') {
                                                                                                $('#ad_online_charge').val(0);

                                                                                            }
                                                                                            gettot();
                                                                                        }

                                                                                        function gettot() {
                                                                                            var a = parseFloat($('#ad_gov_fee').val()) || 0;
                                                                                            var b = parseFloat($('#ad_typing_fees').val()) || 0;
                                                                                            var c = parseFloat($('#ad_vendor_com').val()) || 0;
                                                                                            var d = parseFloat($('#ad_online_charge').val()) || 0;
                                                                                            var e = parseFloat($('#ad_tax').val()) || 0;
                                                                                            var amtpmttot = a + b + c + d + e;
                                                                                            $('#amtpmttot').val(amtpmttot);
                                                                                        }
                                                                                    </script>
                                                                                    <!-- <div class="form-group" id="app_code">
                                                                                        <label>Approval Code&nbsp;<span
                                                                                                class="text-danger required">*</span></label>
                                                                                        <input type="text" class="form-control"
                                                                                            placeholder="Approval Code" step="0.01"
                                                                                            id="approval_code" name="approval_code">
                                                                                    </div> -->
                                                                                    <div class="form-group">
                                                                                        <label>Amount&nbsp;<span
                                                                                                class="text-danger required">*</span></label>
                                                                                        <input type="number" required class="form-control"
                                                                                            placeholder="AED" step="0.01"
                                                                                            name="amount_payment" id="amtpmttot" readonly>
                                                                                    </div>
                                                                                    <div class="form-group">
                                                                                        <label>Remarks for your reference (Optional)</label>
                                                                                        <textarea rows="3" class="form-control"
                                                                                            name="email_remarks" id="editor2"></textarea>
                                                                                    </div>
                                                                                    <input type="hidden" id="email_date"
                                                                                        name="contactable_date" class="form-control"
                                                                                        placeholder=""
                                                                                        value="<?php echo date('Y-m-d H:i:s', time() + 86400); ?>">

                                                                                    <div class="form-group">
                                                                                        <input type="submit" name="action_payment"
                                                                                            class="btn btn-primary btn-block"
                                                                                            value="SEND EMAIL">
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </form>
                                                                <?php
                                                                    }
                                                                }
                                                                ?>
                                                            </div>
                                                        <?php }
                                                        // if ($lead_current_status != 305 && $lead_current_status != 306 && $lead_current_status != 309) {
                                                        ?>
                                                        <div class="tab-pane show d-none" id="tab-faq-13">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="form-group" style="justify-items: center;">
                                                                        <?php $zoho_conv_url = "https://salesiq.zoho.com/ontimegroup/allchats/" . $lead_details["lead_zoho_conversation_id"];
                                                                        $zoho_conv_message = "<br /><a target='_blank' href=". $zoho_conv_url ." class='p-2 pl-4 pr-4 btn btn-primary'>Zoho Conversation</a>";
                                                                        echo "<pre>" . $zoho_conv_message . "</pre>";
                                                                        ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <?php
                                                        // }
                                                        ?>
                                                    </div>

                                                    <div class="tab-pane" id="req_sts_upt">
                                                        <select class="form-control mb-2" name="req_sts_upt" onchange="$('#req_sts_upt .tab-pane').removeClass('d-block').addClass('d-none');$('#'+this.value).addClass('d-block').removeClass('d-none');" required>
                                                            <option value="" disabled selected>--Options--</option>

                                                            <?php if (
                                                                $lead_details["branch_id"] == 106 || $lead_details['branch_name'] == 'Golden Cube' || $lead_details['assigned_group_id'] == 114 || $lead_details['assigned_group_id'] == 74 ||
                                                                $lead_details['lead_created_by'] == '2504392213' || $lead_details['lead_created_by'] == '3773720372' || $lead_details['lead_created_by'] == '2270324950'
                                                            ) { ?>
                                                                <option value="tab-faq-10" class="<?php echo $auth_user_privilege['eligibility_check'] == 'false' ? 'd-none' : ''; ?>">Eligibility Check</option>
                                                            <?php } ?>
                                                            <option value="tab-faq-7" class="<?php echo $auth_user_privilege['enquiry'] == 'false' ? 'd-none' : ''; ?>">Enquiry</option>
                                                            <option value="tab-faq-7" class="<?php echo $auth_user_privilege['missing_documents'] == 'false' ? 'd-none' : ''; ?>">Missing Documents</option>
                                                            <?php if (count($payment_histories) == 0) { ?>
                                                                <option value="tab-faq-4" class="<?php echo $auth_user_privilege['order_confirm'] == 'false' ? 'd-none' : ''; ?>">Order Confirm</option>
                                                            <?php } ?>
                                                            <!-- <?php if (count($payment_histories) == 0) { ?>
                                                                <option value="tab-faq-12" class="<?php echo $auth_user_privilege['order_confirm'] == 'false' ? 'd-none' : ''; ?>">Update Invoice</option>
                                                            <?php } ?> -->
                                                            <option value="tab-faq-5" class="<?php echo $auth_user_privilege['disqualified'] == 'false' ? 'd-none' : ''; ?>">Disqualified</option>
                                                            <option value="tab-faq-11" class="<?php echo $auth_user_privilege['lead_hold'] == 'false' ? 'd-none' : ''; ?>">Hold the Lead</option>
                                                        </select>
                                                        <hr>
                                                        <?php if ($lead_details["lead_created_by"] == 2906815795) { ?>
                                                            <div class="tab-pane d-none<?php if ($lead_details["lead_created_by"] == 2906815795) {
                                                                                            echo "show active";
                                                                                        }
                                                                                        ?>" id="tab-faq-9">

                                                                <form
                                                                    action="<?php echo getenv('CRM_URL'); ?>leads/lead/action_status/<?php echo $this->uri->segment(4); ?>"
                                                                    method="post" id="action_status_form">
                                                                    <div class="row">
                                                                        <div class="col-md-12">
                                                                            <div class="form-group">
                                                                                <label for="">Lead Status</label>
                                                                                <input type="hidden" name="action_status"
                                                                                    value="setup_status">
                                                                                <select name="status_id" class="form-control"
                                                                                    autofocus required>
                                                                                    <option value="" disabled selected default> --
                                                                                        Select Status --</option>
                                                                                    <?php
                                                                                    foreach ($lead_dld_status as $dld_stat) {
                                                                                    ?>
                                                                                        <option value="<?php echo $dld_stat["id"]; ?>">
                                                                                            <?php echo $dld_stat["status_name"]; ?>
                                                                                        </option>
                                                                                    <?php
                                                                                    }
                                                                                    ?>
                                                                                </select>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <input type="submit"
                                                                                    class="btn btn-primary btn-block"
                                                                                    value="SETUP STATUS">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </form>

                                                            </div>
                                                        <?php } ?>

                                                        <?php if (count($payment_histories) == 0) { ?>
                                                            <style>
                                                                .invoice-inputs .input-group:nth-child(1) .minus {
                                                                    display: none;
                                                                }

                                                                .invoice-inputs .input-group:not(:nth-last-child(1)) .plus {
                                                                    display: none;
                                                                }
                                                            </style>

                                                            <div class="tab-pane show d-none" id="tab-faq-4">
                                                                <form
                                                                    action="<?php echo getenv('CRM_URL'); ?>leads/lead/action_order/<?php echo $this->uri->segment(4); ?>"
                                                                    method="post" id="order_confirm_form" enctype="multipart/form-data">
                                                                    <input type="hidden" name="pos_updated" value="0">
                                                                    <input type="hidden" name="pos_govt_fee" value="">
                                                                    <input type="hidden" name="pos_typing_fee" value="">
                                                                    <input type="hidden" name="pos_Card_Amnt" value="">
                                                                    <input type="hidden" name="pos_Disc_Amnt" value="">
                                                                    <input type="hidden" name="pos_Tax_Amnt" value="">
                                                                    <input type="hidden" name="pos_Tot_Revn" value="">

                                                                    <input type="hidden" name="pos_ref1" value="">
                                                                    <input type="hidden" name="pos_ref2" value="">
                                                                    <input type="hidden" name="pos_Tot_Amt" value="">
                                                                    <input type="hidden" name="pos_username" value="">
                                                                    <div class="row">
                                                                        <div class="col-md-12">
                                                                            <div class="form-group">
                                                                                <label>ORDER REFERENCE NUMBER (INVOICE REFERENCE
                                                                                    NUMBER)&nbsp;<span
                                                                                        class="text-danger required">*</span></label>
                                                                                <div class="invoice-inputs">
                                                                                    <div class="input-group mb-3">
                                                                                        <div class="input-group-prepend minus">
                                                                                            <span
                                                                                                class="input-group-text add-invoice-input btn-danger"
                                                                                                id="basic-addon1"><i
                                                                                                    class="fas fa-minus"></i></span>
                                                                                        </div>
                                                                                        <input type="text" name="order_id[]"
                                                                                            class="order_ids form-control"
                                                                                            placeholder="Invoice #"
                                                                                            aria-label="Username"
                                                                                            aria-describedby="basic-addon1">
                                                                                        <div class="input-group-prepend plus">
                                                                                            <span
                                                                                                class="input-group-text add-invoice-input btn-primary"
                                                                                                id="basic-addon1"><i
                                                                                                    class="fas fa-plus"></i></span>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                    <input type="hidden" name="is_alpha_pro_invoice" id="is_alpha_pro_invoice" value="<?php echo $is_alpha_pro_invoice; ?>">
                                                                    <?php if($is_alpha_pro_invoice == 'enable') { ?>
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <label>Total LPO Amount</label>
                                                                            <input type="number" name="lpo_amount"  id="lpo_amount" class="form-control" placeholder="" value="">
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label>Approx. Profit</label>
                                                                            <input type="number" name="approx_profit" id="approx_profit" class="form-control" placeholder="" value="">
                                                                        </div>
                                                                    </div>
                                                                    <?php } ?>

                                                                                <div class="col-md-12">
                                                                                    <div class="form-group">
                                                                                        <label>Lead Attachments&nbsp;&nbsp;&nbsp;&nbsp; <button id="btnAddNewAttachmentOrder"
                                                                                                class="btn btn-sm btn-primary">Add new</button></label>
                                                                                        <div class="" id="attachments">
                                                                                        </div>
                                                                                    </div>
                                                                                </div>

                                                                                <!-- <input type="text" name="order_id" id="order_id"
                                                                            required="" class="form-control"> -->
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <input type="submit" name="action_order"
                                                                                    class="btn btn-primary btn-block"
                                                                                    value="COMPLETE THE LEAD">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>

                                                            <div class="tab-pane show d-none" id="tab-faq-12">
                                                                <form
                                                                    action="<?php echo getenv('CRM_URL'); ?>leads/lead/action_update_invoice/<?php echo $this->uri->segment(4); ?>"
                                                                    method="post" id="" enctype="multipart/form-data">
                                                                    <div class="row">
                                                                        <div class="col-md-12">
                                                                            <div class="form-group">
                                                                                <label>ORDER REFERENCE NUMBER (INVOICE REFERENCE
                                                                                    NUMBER)&nbsp;<span
                                                                                        class="text-danger required">*</span></label>
                                                                                <div class="invoice-inputs">
                                                                                    <div class="input-group mb-3">
                                                                                        <div class="input-group-prepend minus">
                                                                                            <span
                                                                                                class="input-group-text add-invoice-input btn-danger"
                                                                                                id="basic-addon1"><i
                                                                                                    class="fas fa-minus"></i></span>
                                                                                        </div>
                                                                                        <input type="text" name="order_id[]"
                                                                                            class="order_ids form-control"
                                                                                            placeholder="Invoice #"
                                                                                            aria-label="Username"
                                                                                            aria-describedby="basic-addon1">
                                                                                        <div class="input-group-prepend plus">
                                                                                            <span
                                                                                                class="input-group-text add-invoice-input btn-primary"
                                                                                                id="basic-addon1"><i
                                                                                                    class="fas fa-plus"></i></span>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <input type="submit" name="action_order"
                                                                                    class="btn btn-primary btn-block"
                                                                                    value="Update Invoice">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>

                                                        <?php  } ?>
                                                        <div class="tab-pane show d-none" id="tab-faq-5">
                                                            <form
                                                                action="<?php echo getenv('CRM_URL'); ?>leads/lead/action_close/<?php echo $this->uri->segment(4); ?>"
                                                                method="post">
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <label>Remarks&nbsp;<span
                                                                                    class="text-danger required">*</span></label>
                                                                            <textarea rows="5" class="form-control"
                                                                                name="close_remarks"
                                                                                id="close_remarks" required></textarea>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <input type="submit" name="action_close"
                                                                                class="btn btn-primary btn-block"
                                                                                value="CLOSE ENQUIRY">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>

                                                        <div class="tab-pane show d-none" id="tab-faq-7">
                                                            <form
                                                                action="<?php echo getenv('CRM_URL'); ?>leads/lead/action_enquiry/<?php echo $this->uri->segment(4); ?>"
                                                                method="post">
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <label>Remarks&nbsp;<span
                                                                                    class="text-danger required">*</span></label>
                                                                            <textarea rows="5" class="form-control"
                                                                                name="close_remarks"
                                                                                id="close_remarks" required></textarea>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label>Next Contactable Date&nbsp;<span
                                                                                    class="text-danger required">*</span></label>
                                                                            <input type="datetime-local" id="email_date"
                                                                                name="contactable_date" class="form-control" required
                                                                                placeholder=""
                                                                                min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>"
                                                                                value="<?php echo date('Y-m-d\TH:i', time() + 86400); ?>">
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <input type="submit" name="action_close"
                                                                                class="btn btn-primary btn-block"
                                                                                value="Submit">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>

                                                        <div class="tab-pane show d-none" id="tab-faq-11">
                                                            <form
                                                                action="<?php echo getenv('CRM_URL'); ?>leads/lead/action_lead_hold/<?php echo $this->uri->segment(4); ?>"
                                                                method="post">
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <label>Remarks&nbsp;<span
                                                                                    class="text-danger required">*</span></label>
                                                                            <textarea rows="5" class="form-control"
                                                                                name="close_remarks"
                                                                                id="close_remarks"></textarea>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <input type="submit" name="action_close"
                                                                                class="btn btn-primary btn-block"
                                                                                value="Submit">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>


                                                        <div class="tab-pane show d-none" id="tab-faq-10">
                                                            <form id="eligible-form">
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <label>Eligibility Status&nbsp;<span
                                                                                    class="text-danger required">*</span></label>
                                                                            <select class="form-control" name="is_eligible"
                                                                                id="is_eligible" required>
                                                                                <option value="" disabled selected>
                                                                                    --Eligibility--</option>
                                                                                <option value="1">Eligible</option>
                                                                                <option value="0">Not Eligible</option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label>Remarks&nbsp;<span
                                                                                    class="text-danger required">*</span></label>
                                                                            <textarea rows="5" class="form-control"
                                                                                name="custom_remarks" id="custom_remarks"
                                                                                required></textarea>
                                                                        </div>
                                                                        <input type="hidden" name="action_custom"
                                                                            value="UPDATE TIMELINE">
                                                                        <div class="form-group">
                                                                            <input type="submit" name="action_custom"
                                                                                class="btn btn-primary btn-block"
                                                                                value="UPDATE TIMELINE">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>

                                                    </div>
                                                    <?php if(!empty($sub_leads) && !empty($lead_package_details) && in_array($lead_package_details[0]->package_id, [199,204,205,258,259,260])){ ?>
                                                    <div class="tab-pane" id="send_quot_tab">
                                                        <div class="m-auto row pt-5">
                                                            <button type="button" class="btn btn-primary btn-lg m-auto mt-4"
                                                                data-toggle="modal" data-target="#send_quotation">
                                                                Send Quotation Form
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <?php } ?>
                                                    <?php if ($lead_details['branch_id'] == 106 && in_array($lead_details['lead_status'],[649]) && empty($lead_package_details) && !in_array($lead_details['package_id'], $before_payment_packages) ) { ?>
                                                    <div class="tab-pane" id="create_quot_tab">
                                                        <div class="m-auto row pt-5">
                                                            <button type="button" class="btn btn-primary btn-lg m-auto mt-4"
                                                                data-toggle="modal" data-target="#quotation_form_modal">
                                                                Create Quotation
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <?php } ?>

                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                <?php } ?>
                                <div class="main-card mb-3 card" id="timeline">
                                    <div class="card-body">
                                        <h5 class="card-title">Timeline</h5>
                                        <div
                                            class="vertical-timeline vertical-timeline--animate vertical-timeline--one-column">
                                            <?php
                                            $end_date = NULL;
                                            $found418 = false;
                                            
                                            foreach ($timeline as $key => $value) {
                                                if($value['action_id'] == 443)
                                                {
                                                    $createdTimestamp = new DateTime($value['updated_at']);
                                                    $createdTimestamp->setTimezone(new DateTimeZone('Asia/Dubai'));
                                                    $currentDateTime = new DateTime();
                                                    $diff = $currentDateTime->diff($createdTimestamp);
                                                    $totalDays = ($diff->y * 365) + ($diff->m * 30) + $diff->d;
                                                    $diffHours = ($totalDays * 24) + $diff->h;
                                                    $quotation_expiry =  ($diffHours > 48) ? true :false;
                                                }
                                                $start_date = $value['action_on'];
                                                $diff = time_difference($lead_details['lead_added_on'], $start_date);
                                                // print_r($value);
                                                $class = "success";
                                                if ($value["action_id"] == 404 
                                                    || $value["action_id"] == 445 || $value["action_id"] == 426) {
                                                    $class = "info";
                                                }

                                                if ($value["action_id"] == 405) {
                                                    $class = "alternate";
                                                }

                                                if ($value["action_id"] == 406) {
                                                    $class = "secondary";
                                                }

                                                if ($value["action_id"] == 407 || $value["action_id"] == 446) {
                                                    $class = "warning";
                                                }

                                                if ($value["action_id"] == 408) {
                                                    $class = "primary";
                                                }

                                                if ($value["action_id"] == 411) {
                                                    $class = "danger";
                                                }

                                                if($value['action_id'] == 412)
                                                {
                                                    $createdTimestamp = new DateTime($value['action_on']);
                                                    $currentDateTime = new DateTime();
                                                    $diff_date = $currentDateTime->diff($createdTimestamp);
                                                    $diffHours = ($diff_date->days * 24) + $diff_date->h;
                                                    $pay_link_expiry =  ($diffHours > 48) ? true :false;
                                                }

                                                // if (in_array(418, $value)) {
                                                //     $found418 = true;
                                                // }

                                            ?>
                                                <div class="vertical-timeline-item vertical-timeline-element">
                                                    <div>
                                                        <span class="vertical-timeline-element-icon bounce-in">
                                                            <i
                                                                class="badge badge-dot badge-dot-xl badge-<?php echo $class; ?>">
                                                            </i>
                                                        </span>
                                                        <div class="vertical-timeline-element-content bounce-in">
                                                            <a class="collapsed" data-toggle="collapse"
                                                                href="#collapsabl<?php echo $key; ?>" role="button"
                                                                aria-expanded="false" aria-controls="collapsable">
                                                                <h4 class="timeline-title">
                                                                <?php
                                                                $val_action = [415, 418];
                                                                $val_action_cash = [417];
                                                                $remarks = $value['remarks'];
                                                                $actionName = $value['action_name'];
                                                                $posPmtResponse = $value['pos_pmt_response'];
                                                                $showButtons = false;

                                                                // Check if 'RCT-' exists in remarks
                                                                if (strpos($remarks, 'RCT-') !== false) {
                                                                    $showButtons = true;
                                                                }

                                                                // Modify action_name only if RCT- is NOT present and action_id is in val_action_cash
                                                                if (!$showButtons && in_array($value["action_id"], $val_action_cash)) {
                                                                    $actionName = str_replace("Customer Paid", "Receipt Not Generated", $actionName);
                                                                } else {

                                                                    $actionName = $value['action_name'];
                                                                }


                                                                echo $actionName;
                                                                if ($diff != "0") {
                                                                    echo " - ( After " . $diff . " )";
                                                                }
                                                                $permit = [678064846, 1167483351, 1564456050, 3212920395, 3402994919, 3001413024, 732508183, 1668733300, 3644347224, 1378964183, 1575752556, 4184195673, 2813192886, 3573695398, 468166214, 1037006738, 1143711453];
                                                                $permit1 = [4294967295, 4184195673, 4140252872, 3935045924, 3573695398, 3317794190, 2854190512, 2826791041, 2644571391, 2441782906, 2371658098, 2354638858, 2259615390, 2204201215, 2113278237, 2002168679, 1896774416, 1740908607, 1341021925, 1027034203, 250197221, 96171022, 93401187];
                                                                if ($value['payment_link'] != null && $auth_user_data['paylink_option'] == 'enable') {  // && in_array($this->auth_user_id, $permit)
                                                                    echo " - <button class='bg-primary border-0 btn btn-xs p-2 text-white cpy-data' data-data='" . $value['payment_link'] . "' style='font-size: 87%;padding: 7px 17px !important;border-radius: 60px;'>Copy Link for AED " . $value["action_amount"] . "/-</button>";
                                                                
                                                                    if($pay_link_expiry) {
                                                                        echo "<a href='#' id='activate_pay_link' class='text-white badge badge-primary ml-3 text-capitalize' data-id ='".$value["id"]."' >Active Payment Link</a>";
                                                                    }
                                                                } else if ($value['payment_link'] != null && $auth_user_data['paylink_option'] == 'enable' ) {    // && in_array($this->auth_user_id, $permit1)
                                                                    echo " - <button class='bg-primary border-0 btn btn-xs p-2 text-white cpy-data' data-data='" . $value['payment_link'] . "' style='font-size: 87%;padding: 7px 17px !important;border-radius: 60px;'>Copy Link for AED " . $value["action_amount"] . "/-</button>";
                                                                    
                                                                    if($pay_link_expiry) {
                                                                        echo "<a href='#' id='activate_pay_link' class='text-white badge badge-primary ml-3 text-capitalize' data-id ='".$value["id"]."' >Active Payment Link</a>";
                                                                    }
                                                                }

                                                                // Determine which value to display
                                                                if (in_array($value["action_id"], $val_action_cash)) {
                                                                    if ($showButtons) {
                                                                        $displayText = $remarks;
                                                                    } else {
                                                                        $pos = strpos($posPmtResponse, '{"ResponseCode"');
                                                                        if ($pos !== false) {
                                                                            $secondJsonString = substr($posPmtResponse, $pos);
                                                                            $secondJson = json_decode($secondJsonString, true);
                                                                        
                                                                            $displayText = (!empty($secondJson['ResponseMsg']))
                                                                                ? $secondJson['ResponseMsg']
                                                                                : "The user role not assigned for cash payment";
                                                                        
                                                                        } else {
                                                                            $displayText = "The user role not assigned for cash payment";
                                                                        }
                                                                        /*$posPmtData = json_decode($posPmtResponse, true);
                                                                        $displayText = isset($posPmtData['ResponseMsg']) ? $posPmtData['ResponseMsg'] : "The user role not assigned for cash payment"; */
                                                                    }
                                                                } else {
                                                                    $displayText = $remarks;
                                                                }

                                                                if ($showButtons && in_array($value["action_id"], $val_action_cash)) {
                                                                    echo " - <a href='".getenv('CRM_URL')."leads/lead/resendReceipt/" . $value["id"] . "'><button class='bg-primary border-0 btn btn-xs p-2 ml-2 text-white' data-data='" . $value['payment_link'] . "' style='font-size: 87%;padding: 7px 17px !important;border-radius: 60px;'>Resend Receipt</button></a>";
                                                                    echo " - <a href='".getenv('CRM_URL')."leads/lead/reprintReceipt/" . $value["id"] . "'><button class='bg-primary border-0 btn btn-xs p-2 ml-2 text-white' data-data='" . $value['payment_link'] . "' style='font-size: 87%;padding: 7px 17px !important;border-radius: 60px;'>Reprint Receipt</button></a>";
                                                                }

                                                                if (in_array($value["action_id"], $val_action)) {
                                                                    echo " - <a href='".getenv('CRM_URL')."leads/lead/resendReceipt/" . $value["id"] . "'><button class='bg-primary border-0 btn btn-xs p-2 ml-2 text-white' data-data='" . $value['payment_link'] . "' style='font-size: 87%;padding: 7px 17px !important;border-radius: 60px;'>Resend Receipt</button></a>";
                                                                    echo " - <a href='".getenv('CRM_URL')."leads/lead/reprintReceipt/" . $value["id"] . "'><button class='bg-primary border-0 btn btn-xs p-2 ml-2 text-white' data-data='" . $value['payment_link'] . "' style='font-size: 87%;padding: 7px 17px !important;border-radius: 60px;'>Reprint Receipt</button></a>";
                                                                } 
                                                                
                                                                if ($value["action_id"] == 442) {
                                                                    if ($value['status_id'] != 310) {
                                                                        echo "<a href='javascript:void(0);' onclick=\"checkPaymentStatus('" . $value['lead_id'] . "','" . $value['payment_ref'] . "')\">
                                                                            <button class='bg-primary border-0 btn btn-xs p-2 ml-2 text-white' 
                                                                                    style='font-size: 87%; padding: 7px 17px !important; border-radius: 60px;'>
                                                                                Check Payment
                                                                            </button>
                                                                        </a>";
                                                                        // echo " - ".$reponse_card ;
                                                                    }
                                                                }

                                                                if ($value["action_id"] == 444 && $lead_package_details[0]->payment_type == "cash" && empty($lead_details['pos_pmt_number'])) {
                                                                    if ($value['status_id'] != 310) {
                                                                        echo "<a href='javascript:void(0);' id='cash_payment' data-href='".getenv('CRM_URL')."leads/lead/quotation_accept_cash/".$value['lead_id']."/".$value['action_amount']."'>
                                                                            <button class='bg-primary border-0 btn btn-xs p-2 ml-2 text-white' 
                                                                                    style='font-size: 87%; padding: 7px 17px !important; border-radius: 60px;'>
                                                                                Receive Payment
                                                                            </button>
                                                                        </a>";
                                                                    }
                                                                }
                                                                if ($value["action_id"] == 444 && $lead_package_details[0]->payment_type == "card" && empty($lead_details['pos_pmt_number'])) {
                                                                    if ($value['status_id'] != 310) {
                                                                        echo "<a href='".getenv('CRM_URL')."leads/lead/card_payment_internal/".$value['lead_id']."/".$value['action_amount']."'>
                                                                            <button class='bg-primary border-0 btn btn-xs p-2 ml-2 text-white' 
                                                                                    style='font-size: 87%; padding: 7px 17px !important; border-radius: 60px;'>
                                                                                Card Payment
                                                                            </button>
                                                                        </a>";
                                                                    }
                                                                }
                                                                if ($value["action_id"] == 443) {
                                                                    if ($value['status_id'] == 642) {
                                                                        $quotation_link = getenv('CRM_URL') . "/leads/lead/quotation?token=" . encrypt_decrypt(md5($lead_details["id"])) . "&action=" . encrypt_decrypt($value["id"]);
                                                                        echo "<a target='_blank' class='badge badge-primary ml-3 text-capitalize cpy-data' href='" . $quotation_link . "' data-data='" . $quotation_link . "'>Quotation Link</a>";

                                                                        if($quotation_expiry && $value['link_active'] == null) {
                                                                            echo "<a href='#' id='activate_quotation' class='text-white badge badge-primary ml-3 text-capitalize' data-id ='".$value["id"]."' >Active quotation</a>";
                                                                        }
                                                                    }
                                                                }
                                                                ?>
                                                                </h4>
                                                            </a>
                                                            <div class="collapse" id="collapsabl<?php echo $key; ?>">
                                                                <?php
                                                                if ($value["action_id"] == 426 && $value['pg_callback'] == NULL) {
                                                                    // && !$found418
                                                                    // $lead_details["branch_id"] == 106
                                                                    $fetch_pay_url = getenv('CRM_URL')."payment/payment_process?code=".trim($value['lead_id'])."&order_id=".trim($value['lead_id'])."&act=".trim($value["id"])."&attempt_action=".trim($value["id"])."&email=".trim($lead_details['customer_email']);
                                                                    $display_message = $displayText. "<br /><a target='_blank' href=". $fetch_pay_url ."' class='p-2 pl-4 pr-4 btn btn-primary'>Fetch PaymentGateway Status</a>";
                                                                    echo "<pre class='text-wrap text-break'>" . $display_message . "</pre>";
                                                                } else if ($value['is_fetch_pay_status'] == 1 ) {
                                                                    echo "<pre class='text-wrap text-break'>" . $displayText . "</pre>";
                                                                } else {
                                                                    echo "<pre class='text-wrap text-break'>" . quoted_printable_decode($displayText) . "</pre>";
                                                                }
                                                                ?>
                                                            </div>
                                                            <span
                                                                class="vertical-timeline-element-date"><?php echo date('H:i A', strtotime($value['action_on'])) . "<br>" . date('d M Y', strtotime($value['action_on'])); ?></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php
                                                $end_date = $value['action_on'];
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-lg-4 mt-4 order-first order-lg-last">
                                <div class="stick-to-parent">
                                    <div class="app-inner-layout__header text-white bg-night-sky br-tr br-tl">
                                        <div class="app-page-title app-page-title-simple">
                                            <div class="page-title-wrapper">
                                                <div class="page-title-heading">
                                                    <div>Meetings
                                                        <div class="page-title-subheading">Upcoiming Meeting(s)</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-shadow-primary card-border text-white mb-3 pl-2 card">
                                        <!-- <div class="text-left d-block card-footer">
                                            <?php
                                            echo $enquiry_details['subject'];
                                            ?>
                                            <br>
                                            <div class="text-center mt-3">
                                                <button class="btn-shadow-dark btn-wider btn btn-dark"><?php echo $enquiry_details['created_date']; ?></button>
                                            </div>
                                        </div> -->


                                        <div
                                            class="vertical-time-icons vertical-timeline vertical-timeline--animate vertical-timeline--one-column <?php if (empty($upcoming_meetings)) { ?> pt-0 <?php } ?>">

                                            <div class="card-body">
                                                <?php
                                                if (empty($upcoming_meetings)) {
                                                ?>
                                                    <center class="text-body">You have no meetings scheduled with this
                                                        customer.</center>
                                                    <?php
                                                } else {
                                                    foreach ($upcoming_meetings as $key => $value) {
                                                    ?>

                                                        <div class="vertical-timeline-item vertical-timeline-element">
                                                            <div>
                                                                <div class="vertical-timeline-element-icon bounce-in">
                                                                    <div class="timeline-icon border-success bg-success">
                                                                        <i class="fa fa-user-friends text-white"></i>
                                                                    </div>
                                                                </div>
                                                                <div class="vertical-timeline-element-content bounce-in">
                                                                    <h4 class="timeline-title text-success">Meeting with
                                                                        <?php echo $lead_details['customer_name']; ?> <a
                                                                            href="#meetingModal" class="open-meetingDialog"
                                                                            data-toggle="modal"
                                                                            data-meetingid="<?php echo $value['id']; ?>"
                                                                            data-leadid="<?php echo $lead_details['id']; ?>">
                                                                            <i class="fa fa-edit pl-2"></i>
                                                                        </a></h4>
                                                                    <p><?php echo date('d M Y H:i A', strtotime($value['meeting_date_time'])); ?>
                                                                    </p>.

                                                                </div>
                                                            </div>
                                                        </div>
                                                <?php
                                                    }
                                                }
                                                ?>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="stick-to-parent">
                                    <div class="app-inner-layout__header text-white bg-night-sky br-tr br-tl">
                                        <div class="app-page-title app-page-title-simple">
                                            <div class="page-title-wrapper">
                                                <div class="page-title-heading">
                                                    <div>Documents
                                                        <div class="page-title-subheading">Upload Document(s)</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-shadow-primary card-border text-white mb-3 pl-2 card">
                                        <form action="<?php echo getenv('CRM_URL'); ?>leads/lead/action_uploadattachments/<?php echo $this->uri->segment(4); ?>" method="post" enctype="multipart/form-data">
                                            <div class="row">
                                                <div class="col-md-12 mb-3">
                                                    <div class="form-group">
                                                        <label class="font-weight-bold">Attachments</label>
                                                        <input class="form-control" type="file" name="attachments_lead[]" multiple>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 text-center">
                                                    <div class="form-group">
                                                        <input type="hidden" name="lead_created_by" value="<?php echo $this->auth_user_id; ?>">
                                                        <button type="submit" name="action_uploadattachments"
                                                            class="btn btn-primary btn-lg px-5 py-2">
                                                            Upload
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php if (!empty($this->session->flashdata('alert_reply'))) { ?>
                            <div class="alert alert-<?php echo $this->session->flashdata('alert_reply'); ?>">
                                <?php echo $this->session->flashdata('alert_message'); ?>
                            </div>
                        <?php } ?>

                        <?php if (!empty(trim($lead_details['email_request_id']))) { ?>
                            <div class="row justify-content-center">
                                <div class="col-12 mt-4" id="conversation-mails">
                                    <div class="app-inner-layout__header text-white bg-night-sky br-tr br-tl">
                                        <div class="app-page-title app-page-title-simple">
                                            <div class="page-title-wrapper">
                                                <div class="page-title-heading">
                                                    Conversations
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php if (!empty($conversations)) { ?>
                                        <div class="app-inner-layout__wrapper row-fluid no-gutters" id="followups">
                                            <div id="conversationAccordion" class="accordion mb-3 mx-5 w-100">
                                                <?php foreach ($conversations as $key => $conversation) { ?>
                                                    <div class="item border">
                                                        <div id="heading<?php echo $key; ?>" class="bg-midnight-bloom d-flex">
                                                            <button class="btnAcct text-left m-0 btn btn-link text-white btn-link btn-block p-3 collapsed" type="button" data-toggle="collapse" data-target="#collapse<?php echo $key; ?>" data-contenturl="<?= $conversation->content_url ?>" aria-expanded="false" aria-controls="collapse<?php echo $key; ?>">
                                                                <h5 class="m-0 p-0">
                                                                    <?= $conversation->from->name ?>
                                                                    <span class="float-right"><?= $conversation->sent_time->display_value ?></span>
                                                                </h5>
                                                            </button>
                                                            <button class="bg-white btn btn-group btn-outline-primary d-block h-75 mb-auto mr-3 mt-auto text-center text-primary replyBtn" title="reply" data-tomail="<?= $conversation->from->email_id ?>" data-contenturl="<?= $conversation->content_url ?>"><i class="fa fa-reply"></i></button>
                                                            <button class="bg-white btn btn-group btn-outline-primary d-block h-75 mb-auto mr-3 mt-auto text-center text-primary forwardBtn" title="forward" data-tomail="<?= $conversation->from->email_id ?>" data-contenturl="<?= $conversation->content_url ?>"><i class="fa fa-share"></i></button>
                                                        </div>
                                                        <div id="collapse<?php echo $key; ?>" class="collapse" aria-labelledby="heading<?php echo $key; ?>" data-parent="#conversationAccordion">
                                                            <div class="card-body">
                                                                <div class="subject"></div>
                                                                <hr>
                                                                <div class="attachments" style="font-size: large; font-weight: 500;"></div>
                                                                <div class="description"></div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    <?php } else { ?>
                                        <div class="app-inner-layout__wrapper row-fluid no-gutters" id="followups">
                                            <div id="conversationAccordion" class="accordion m-3 mx-5 w-100 text-center">
                                                <span> No Conversations</span>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        <?php } ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $('#modelId').on('show.bs.modal', function(e) {
        var customerName = "<?php echo $lead_details['customer_name']; ?>";
        var customerEmail = "<?php echo $lead_details['customer_email']; ?>";
        var customerMobile = "<?php echo $lead_details['customer_mobile']; ?>";
        $('#uc_customer_name').val(customerName);
        $('input[name="customer_email"]').val(customerEmail);
        $('input[name="customer_mobile"]').val(customerMobile);
    });

    $(document).on("click", ".open-meetingDialog", function() {
        var meeting_id = $(this).data('meetingid');
        var lead_id = $(this).data('leadid');
        $(".modal-body #lead_id").val(lead_id);
        $(".modal-body #meeting_id").val(meeting_id);
        $("#meetingModal").modal('show');
    });
</script>

<!-- Payment Status Modal -->
<div class="modal fade" id="paymentStatusModal" tabindex="-1" role="dialog" aria-labelledby="paymentStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document"> <!-- Use modal-lg for larger content -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentStatusModalLabel">Payment Status</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="modalMessage" style="color: red; display: none;"></p>
                <div id="modalBody">
                    <!-- The iframe will be injected here -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="meetingModal" tabindex="-1" role="dialog" aria-labelledby="meetingModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="meetingModalLabel">Update Minutes of Meeting</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="<?php echo getenv('CRM_URL'); ?>leads/lead/action_meeting_minutes" method="post">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Remarks&nbsp;<span class="text-danger required">*</span></label>
                                <textarea rows="5" class="form-control" name="meeting_update_remarks"
                                    id="meeting_update_remarks"></textarea>
                            </div>
                            <div class="form-group">
                                <label>Next Contactable Date&nbsp;<span
                                        class="text-danger required">*</span></label>

                                <input type="datetime-local" id="meeting_contactable_date" name="meeting_contactable_date"
                                    class="form-control" placeholder="" required
                                    value="<?php echo date('Y-m-d\TH:i', time() + 86400); ?>">
                            </div>
                            <div class="form-group">
                                <input type="hidden" name="lead_id" id="lead_id" value="">
                                <input type="hidden" name="meeting_id" id="meeting_id" value="">
                                <input type="submit" name="action_meeting_minutes"
                                    class="btn btn-primary btn-block" value="UPDATE MINUTES OF MEETING ">
                            </div>
                        </div>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- eligible model -->
<div class="modal fade" id="send_quotation" role="dialog" aria-labelledby="modelTitleId"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document" style="max-width: 80%; width: 80%;">
        <div class="modal-content">
            <div class="modal-header">
                <h4>Send Quotation <?php echo $lead_details['id']; ?> </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="fiori-container container">
                    <div class="app-inner-layout chat-layout justify-content-center">
                        <div class="card">
                            <div class="card-body">
                                <form id="send_quotation" action="<?php echo getenv('CRM_URL') . 'leads/lead/task_send_quotation/' . $lead_details['id'] ?> " method="post" enctype="multipart/form-data">
                                    <input type="hidden" name="branch_id" value="106">
                                    <input type="hidden" name="lead_type" value="goldencube_package">
                                    <input type="hidden" name="assign_group" value="GoldenCube">
                                    <input type="hidden" name="assign_to" value="<?php echo $this->auth_user_id; ?>">
                                    <input type="hidden" name="lead_id" id="lead_id" value="<?php echo $lead_details['id']; ?>">
                                    <input type="hidden" name="customer_otp" value="">
                                    <input type="hidden" name="user_email" value="<?php echo $auth_user_data['email']; ?>">
                                    <input type="hidden" name="user_pos_id" value="<?php echo $auth_user_data['employee_id']; ?>">
                                    <input type="hidden" name="customer_id" value="<?php echo $lead_customer->user_id; ?>">
                                    <input type="hidden" name="package_id" value="<?php echo $lead_details['package_id']; ?>">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label>Payment Type&nbsp;<span class="text-danger required">*</span></label>
                                                <?php
                                                $options = array(
                                                    '' => 'Select payment type',
                                                    'card' => 'Card',
                                                    'cash' => 'Cash',
                                                    'online' => 'Online',
                                                );
                                                echo form_dropdown('payment_type3', $options, $lead_package_details[0]->payment_type, array('class' => 'form-control', 'id' => 'payment_type3q', 'required' => 'required', 'disabled' => 'disabled'));
                                                ?>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <?php //echo "<pre>"; print_r($packages);
                                                ?>
                                                <label>Select Package &nbsp;<span class="text-danger required">*</span></label>
                                                <select class="form-control" name="package_id1q" id="package_id1" disabled required autofocus onchange="get_dataset(this.value);">
                                                    <option value="">-- Select Package --</option>
                                                    <?php
                                                    foreach ($packages as $key => $value) {
                                                    ?>
                                                        <option data-amount="<?php echo $value["package_amount"]; ?>" data-payment-type="<?php echo $value["payment_type"]; ?>" value="<?php echo $value['package_id']; ?>" class="package-option1" <?php echo $lead_package_details[0]->package_id == $value['package_id'] ? "selected" : ""; ?>>
                                                            <?php echo $value['package_name'] . " - " . $value["package_amount"] . "AED - " . $value["payment_type"]; ?>
                                                        </option>
                                                    <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>

                                        <style>
                                            .service-row-gc {
                                                counter-increment: sno;
                                            }

                                            .s-no {
                                                width: 20px;
                                            }

                                            .s-no:after {
                                                content: counter(sno)".";
                                            }

                                            .serv-desc {
                                                width: calc(100% - 20px)
                                            }

                                            .service-row-gc:not(:nth-child(1)) label {
                                                display: none;
                                            }

                                            input[name='service_qty[]']::-webkit-outer-spin-button,
                                            input[name='service_qty[]']::-webkit-inner-spin-button {
                                                -webkit-appearance: none;
                                                margin: 0;
                                            }

                                            /* Firefox */
                                            input[name='service_qty[]'][type=number] {
                                                -moz-appearance: textfield;
                                            }

                                            .half-col-lg-2 {
                                                width: 12.33%;
                                                float: left;
                                                padding-left: 15px;
                                                padding-right: 15px;
                                            }

                                            .big-checkbox {
                                                width: 20px;
                                                height: 20px;
                                                transform: scale(1.5);
                                                /* Makes it larger */
                                                cursor: pointer;
                                            }
                                        </style>
                                        <div class="col-lg-12" id="servicessq">
                                            <fieldset class="border mb-3">
                                                <legend class="bg-plum-plate font-weight-lighter ml-1 p-1 pl-3 pr-3 text-white w-auto">Package Services</legend>
                                                <div class="serices-content-gcs">

                                                    <?php $i = 1;$card_amount=0;$total=0;
                                                      foreach ($lead_package_details as $key => $value) { ?>

                                                    <div class="row m-0 service-row-gcq" id="service-row-gcqs">
                                                        <input type="hidden" name="service_id" value="<?php echo $value->service_id; ?>">
                                                        <input type="hidden" name="is_meeting_contain[]">
                                                        <div class="col-lg-3 d-flex">
                                                            <div class="m-auto"><?php echo $i; ?></div>
                                                            <div class="form-group serv-desc">
                                                                <label for="">Service Description</label>
                                                                <input type="text" name="service_name[]" id="" class="form-control" placeholder="" aria-describedby="helpId" value="<?php echo $value->service_name; ?>" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-1 pl-0 pr-0 text-center">
                                                            <div class="form-group">
                                                                <label for="" class="w-100">Qty</label>
                                                                <input type="number" name="service_qty[]" min="1" id="" class="form-control text-center" placeholder="" aria-describedby="helpId" required readonly value="<?php echo $value->qty; ?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-2">
                                                            <div class="form-group">
                                                                <label for="">Govt Fee</label>
                                                                <input type="number" name="govt_fee[]" id="" class="form-control" placeholder="" aria-describedby="helpId" readonly value="<?php echo $value->govt_fee; ?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-2">
                                                            <div class="form-group">
                                                                <label for="">Typing Fee (Incl Vat)</label>
                                                                <input type="number" name="typing_fee[]" id="" class="form-control" placeholder="" aria-describedby="helpId" readonly value="<?php echo $value->typing_fee; ?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-2">
                                                            <div class="form-group">
                                                                <label for="">Total</label>
                                                                <input type="hidden" name="is_direct_invoice[]">
                                                                <input type="hidden" name="msd_key[]">
                                                                <input type="hidden" name="is_pos_typing_fee[]">
                                                                <input type="number" name="sub_total[]" id="" class="form-control" placeholder="" aria-describedby="helpId" value="<?php echo $value->sub_total; ?>" readonly required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <?php 
                                                        $i++;
                                                        $card_amount += $value->card_amount;
                                                        $total += $value->sub_total;
                                                    } ?>
                                                </div>
                                            </fieldset>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">Subtotal</label>
                                                <input type="number" name="amount_payment" id="amount_paymentsq" class="form-control" placeholder="Subtotal Amount" readonly aria-describedby="helpId"  value="<?php echo $total; ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-6 card-amount">
                                            <div class="form-group">
                                                <label for="">Card Amount</label>
                                                <input type="number" name="card_amount" id="card_amountsq" class="form-control" placeholder="Card Amount" readonly aria-describedby="helpId"  value="<?php echo $card_amount; ?>">
                                            </div>
                                        </div><br>
                                        <div class="col-md-6 total_amount">
                                            <div class="form-group">
                                                <label for="">Total Amount</label>
                                                <input type="number" name="quotation_amount" id="quotation_amount" class="form-control" placeholder="Total Amount" readonly aria-describedby="helpId" value="<?php echo $total+$card_amount; ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group text-right">
                                                <input type="hidden" name="lead_created_by" value="<?php echo $this->auth_user_id; ?>">
                                                <input type="submit" class="btn btn-lg btn-primary btn-square p-3 pl-5 pr-5" name="submitForm" value="SEND" />
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Customer Info Update</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="<?php echo getenv('CRM_URL'); ?>leads/lead/view/<?php echo $lead_details['id']; ?>"
                    method="post">
                    <div class="form-group">
                        <label>Customer Name</label>
                        <input id="uc_customer_name" type="text" name="customer_name" class="form-control"
                            placeholder="" pattern="[^0-9]+" title="Numbers not allowed"
                            value="<?php echo $lead_details['customer_name']; ?>">
                    </div>

                    <div class="form-group">
                        <label>Customer Email
                            Address<?php echo ($fake_domain == 'yes') ? '&nbsp;&nbsp;<span class="text-danger required">*</span>' : ''; ?></label>
                        <input type="email" name="customer_email" class="form-control" placeholder=""
                            <?php echo ($fake_domain == 'yes') ? 'required="required"' : ''; ?>
                            value="<?php echo ($fake_domain == 'yes') ? '' : $lead_details['customer_email']; ?>">

                    </div>
                    <div class="form-group">
                        <label>Customer Mobile Number <span class="text-danger required">*</span></label>
                        <div class="row">
                            <div class="col-md-2 pr-0">
                                <input type="text" name="customer_country_code" class="form-control" placeholder=""
                                    value="<?php echo $lead_details['customer_country_code']; ?>">
                            </div>
                            <div class="col-md-10">
                                <input type="text" name="customer_mobile" class="form-control" placeholder=""
                                    value="<?php echo $lead_details['customer_mobile']; ?>"
                                    required pattern="[0-9]{9,10}" title="Mobile number must be 10 digits">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="hidden" name="lead_id" id="lead_id" value="<?php echo $lead_details['id']; ?>">
                        <input type="submit" name="update_customer_info" class="btn btn-primary btn-block"
                            value="UPDATE CUSTOMER RECORD">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="subLead_modal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4>New Sub Lead on #<?php echo $lead_details['id']; ?> </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <script type="text/javascript">
                    $(document).ready(function() {
                        $('#category_block').show();
                        $('#service_block').show();
                        $('#package_block').hide();

                        if (location.hash != "#" && location.hash != "") {
                            if (location.hash.slice(1) == "timeline") {
                                $(".collapse").collapse()
                            }
                            document.getElementById("timeline").scrollIntoView();
                        }

                        var invoice_input = $(".invoice-inputs").html();

                        function addInput() {
                            $(".invoice-inputs .plus,.invoice-inputs .minus").off();
                            $(".invoice-inputs .plus").on("click", function() {
                                $(".invoice-inputs").append(invoice_input);
                                addInput();
                            });
                            $(".invoice-inputs .minus").on("click", function() {
                                $(this).closest(".input-group").remove();
                                addInput();
                            });
                        }
                        addInput();
                        //add new
                        $('#btnAddNewAttachment').click(function(e) {
                            e.preventDefault();
                            var newDiv = $(
                                '<div class="row mt-3"><div class="col-md-6"><input type="text" class="form-control" required="" name="attachment_name[]" placeholder="Attachment Name" /></div><div class="col-md-5"><input type="file" class="form-control" required="" name="files[]" placeholder="" /></div><div class="col-md-1 float-right"><a href="#" class="text-danger close-div"><i class="fa fa-trash"></i></a></div></div>'
                            );
                            // $('body').animate({
                            //     scrollTop: eval($('#attachment_area').offset().top - 70)
                            // }, 1000);
                            $('#attachments').append(newDiv);

                            $('.close-div').click(function(e) {
                                e.preventDefault();
                                $(this).parent().parent().remove();
                                // $('body').animate({
                                //     scrollTop: eval($('#attachment_area').offset().top - 70)
                                // }, 1000);
                            });
                        });
                    });
                </script>

                <div class="mb-0">
                    <div class="card-body p-0">
                        <!-- <?php if ($this->session->flashdata('alert')) { ?>
                                <div class="alert alert-<?php echo $this->session->flashdata('alert'); ?> alert-dismissible fade show" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <strong>
                                        <?php echo $this->session->flashdata('alert_message'); ?>
                                    </strong>
                                </div>
                                <script>
                                    $(".alert").alert();
                                </script>
                            <?php } ?> -->
                        <form action="<?php echo getenv('CRM_URL'); ?>leads/lead/new_child" method="post" id="add_sub_lead"
                            enctype="multipart/form-data">
                            <input type="hidden" name="lead_parent_id" value="<?php echo $lead_details['id']; ?>">
                            <input type="hidden" class="form-control" required="" name="lead_name"
                                value="<?php echo $lead_customer->first_name . ' ' . $lead_customer->last_name; ?>">
                            <input type="hidden" class="form-control" required="" name="lead_country_code" value="+971"
                                value="<?php echo $lead_customer->country_code; ?>">
                            <input type="hidden" class="form-control" required="" name="lead_contact"
                                value="<?php echo $lead_customer->mobile; ?>">
                            <input type="hidden" class="form-control" name="lead_email"
                                value="<?php echo $lead_customer->email; ?>">

                            <div class="row">

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="lead_type" id="normal"
                                                value="normal" onclick="javascript:normal_lead();" checked="">
                                            <label class="form-check-label" for="normal">Normal Lead</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="lead_type" id="package"
                                                value="package" onclick="javascript:package_lead();">
                                            <label class="form-check-label" for="package">Package Lead</label>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Select Branch &nbsp;<span class="text-danger required">*</span></label>
                                        <select class="form-control" name="branch_id" id="branch_id">
                                            <option value="">-- Select Branch --</option>
                                            <?php
                                            $branch_id = getUserBranch($this->auth_user_id);
                                            foreach ($branches as $key => $value) {


                                                // Check if the branch code exists in the branch_id array
                                                if (in_array($value['id'], $branch_id)) {
                                            ?>
                                                    <option value="<?php echo $value['branch_code']; ?>">
                                                        <?php echo $value['branch_name']; ?></option>
                                            <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                        <div id="branch_error" class="text-danger"></div>
                                    </div>
                                </div>
                                <div class="col-md-12" id="package_block">
                                    <div class="form-group">
                                        <label>Select Package &nbsp;<span class="text-danger required">*</span></label>
                                        <select class="form-control" name="package_id" id="package_id" autofocus>
                                            <option value="">-- Select Package --</option>
                                            <?php
                                            foreach ($packages as $key => $value) {
                                            ?>
                                                <option data-branch="<?php echo $value['package_branch']; ?>"
                                                    value="<?php echo $value['package_id']; ?>">
                                                    <?php echo $value['package_name']; ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                        <span id="package_error" class="text-danger"></span>
                                    </div>

                                    <div class="form-group serices-content">


                                    </div>
                                </div>





                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Remarks(in English/Arabic)&nbsp;<span
                                                class="text-danger required">*</span></label>
                                        <textarea rows="7" class="form-control" id="text_remarks"
                                            name="lead_remarks"></textarea>
                                        <div id="remarks_error" class="text-danger"></div>
                                    </div>
                                </div>
                                <a id="attachment_area"></a>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Lead Attachments&nbsp;&nbsp;&nbsp;&nbsp; <button id="btnAddNewAttachmentSublead"
                                                class="btn btn-sm btn-primary">Add new</button></label>
                                        <div class="" id="attachmentsSublead">
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
                                    <div class="form-group float-right">
                                        <input type="hidden" name="lead_created_by"
                                            value="<?php echo $this->auth_user_id; ?>">
                                        <input type="submit" class="btn btn-primary btn-wide p-2 pl-5 pr-5"
                                            name="submit" value="CREATE LEAD" />
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <script type="text/javascript">
                    function normal_lead() {
                        $('#category_block').show();
                        $('#service_block').show();
                        $('#package_block').hide();
                    }

                    function package_lead() {
                        $('#category_block').hide();
                        $('#service_block').hide();
                        $('#package_block').show();
                    }

                    $(document).ready(function() {
                        $("#add_sub_lead").on("submit", function(e) {
                            var leadType = $("input[name='lead_type']:checked").val();
                            var packageField = $("#package_id");
                            var packageError = $("#package_error");
                            var branchField = $("#branch_id");
                            var branchError = $("#branch_error");
                            var remarksField = $("#text_remarks");
                            var remarksError = $("#remarks_error");
                            var isValid = true;

                            // Clear previous error messages
                            packageError.text("");
                            branchError.text("");
                            remarksError.text("");

                            // Validate package field if leadType is 'package'
                            if (leadType === "package") {
                                if (packageField.val() === "") {
                                    packageError.text("This field is required.");
                                    isValid = false;
                                }
                            }

                            // Validate branch field
                            if (branchField.val() === "") {
                                branchError.text("Please select a branch.");
                                isValid = false;
                            }

                            // Validate remarks field
                            if (remarksField.val().trim() === "") {
                                remarksError.text("Please enter remarks.");
                                isValid = false;
                            }

                            // If any validation fails, prevent form submission
                            if (!isValid) {
                                e.preventDefault();
                            }
                        });

                        // Remove error messages when user selects a valid package
                        $("#package_id").on("change", function() {
                            if ($(this).val() !== "") {
                                $("#package_error").text("");
                            }
                        });

                        // Remove error messages when user selects a valid branch
                        $("#branch_id").on("change", function() {
                            if ($(this).val() !== "") {
                                $("#branch_error").text("");
                            }
                        });

                        // Remove error messages when user enters remarks
                        $("#text_remarks").on("input", function() {
                            if ($(this).val().trim() !== "") {
                                $("#remarks_error").text("");
                            }
                        });
                    });

                    function select_services(category_id) {
                        $.ajax({
                            url: "<?php echo getenv('CRM_URL'); ?>leads/lead/get_services?category_id=" + category_id,
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
                </script>
            </div>
            <!-- <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save</button>
                </div> -->
        </div>
    </div>
</div>
<script>
     $(document).ready(function () {
        $('#is_corporate').on('change', function () {
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
<!-- eligible model -->
<div class="modal fade" id="payment_model" role="dialog" aria-labelledby="modelTitleId"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4>Payment Process <?php echo $lead_details['id']; ?> </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
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
                                <a class="btn btn-primary waves-effect waves-light float-right" href="<?php echo getenv('CRM_URL'); ?>leads/lead/manage"> VIEW LEADS
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
                                <form id="eligibilityCheck" action="<?php echo getenv('CRM_URL') . 'leads/lead/golden_cube_lead/' . $lead_details['id'] ?> " method="post" enctype="multipart/form-data">
                                    <input type="hidden" name="branch_id" value="106">
                                    <input type="hidden" name="lead_type" value="goldencube_package">
                                    <input type="hidden" name="assign_group" value="GoldenCube">
                                    <input type="hidden" name="assign_to" value="<?php echo $this->auth_user_id; ?>">
                                    <input type="hidden" name="lead_id" id="lead_id" value="<?php echo $lead_details['id']; ?>">
                                    <input type="hidden" name="customer_otp" value="">
                                    <input type="hidden" name="user_email" value="<?php echo $auth_user_data['email']; ?>">
                                    <input type="hidden" name="user_pos_id" value="<?php echo $auth_user_data['employee_id']; ?>">
                                    <input type="hidden" name="customer_id" value="<?php echo $lead_customer->user_id; ?>">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label>Payment Type&nbsp;<span class="text-danger required">*</span></label>
                                                <?php
                                                $options = array(
                                                    '' => 'Select payment type',
                                                    'card' => 'Card',
                                                    'cash' => 'Cash',
                                                    'online' => 'Online',
                                                );
                                                echo form_dropdown('payment_type3', $options, "", array('class' => 'form-control', 'id' => 'payment_type3', 'required' => 'required'));
                                                ?>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <?php //echo "<pre>"; print_r($packages);
                                                ?>
                                                <label>Select Package &nbsp;<span class="text-danger required">*</span></label>
                                                <select class="form-control" name="package_id1" id="package_id1" required autofocus onchange="get_dataset(this.value);">
                                                    <option value="">-- Select Package --</option>
                                                    <?php
                                                    foreach ($packages as $key => $value) {
                                                    ?>
                                                        <option data-amount="<?php echo $value["package_amount"]; ?>" data-payment-type="<?php echo $value["payment_type"]; ?>" value="<?php echo $value['package_id']; ?>" class="package-option1">
                                                            <?php echo $value['package_name'] . " - " . $value["package_amount"] . "AED - " . $value["payment_type"]; ?>
                                                        </option>
                                                    <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>

                                        <style>
                                            .service-row-gc {
                                                counter-increment: sno;
                                            }

                                            .s-no {
                                                width: 20px;
                                            }

                                            .s-no:after {
                                                content: counter(sno)".";
                                            }

                                            .serv-desc {
                                                width: calc(100% - 20px)
                                            }

                                            .service-row-gc:not(:nth-child(1)) label {
                                                display: none;
                                            }

                                            input[name='service_qty[]']::-webkit-outer-spin-button,
                                            input[name='service_qty[]']::-webkit-inner-spin-button {
                                                -webkit-appearance: none;
                                                margin: 0;
                                            }

                                            /* Firefox */
                                            input[name='service_qty[]'][type=number] {
                                                -moz-appearance: textfield;
                                            }
                                        </style>
                                        <div class="col-lg-12 d-none" id="services">
                                            <fieldset class="border mb-3">
                                                <legend class="bg-plum-plate font-weight-lighter ml-1 p-1 pl-3 pr-3 text-white w-auto">Package Services</legend>
                                                <div class="serices-content-gc">
                                                    <div class="row m-0 service-row-gc" id="service-row-gc">
                                                        <input type="hidden" name="service_id[]">
                                                        <input type="hidden" name="is_meeting_contain[]">
                                                        <div class="col-lg-4 d-flex">
                                                            <div class="s-no m-auto"></div>
                                                            <div class="form-group serv-desc">
                                                                <label for="">Service Description</label>
                                                                <input type="text" name="service_name[]" id="" class="form-control" placeholder="" aria-describedby="helpId" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-1 pl-0 pr-0 text-center">
                                                            <div class="form-group">
                                                                <label for="" class="w-100">Qty</label>
                                                                <input type="number" name="service_qty[]" min="1" id="" class="form-control text-center" placeholder="" aria-describedby="helpId" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-2">
                                                            <div class="form-group">
                                                                <label for="">Govt Fee</label>
                                                                <input type="number" name="govt_fee[]" id="" class="form-control" placeholder="" aria-describedby="helpId" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-2">
                                                            <div class="form-group">
                                                                <label for="">Typing Fee (Incl Vat)</label>
                                                                <input type="number" name="typing_fee[]" id="" class="form-control" placeholder="" aria-describedby="helpId" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-2">
                                                            <div class="form-group">
                                                                <label for="">Total</label>
                                                                <input type="hidden" name="is_direct_invoice[]">
                                                                <input type="hidden" name="msd_key[]">
                                                                <input type="hidden" name="is_pos_typing_fee[]">
                                                                <input type="number" name="sub_total[]" id="" class="form-control" placeholder="" aria-describedby="helpId" readonly required>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-1 text-center">
                                                            <div class="form-group">
                                                                <label for="">Action</label>
                                                                <button class="btn btn-primary action-btn form-control" type="button">x</button>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-12 meeting-user d-none">
                                                            <div class="row justify-content-center">
                                                                <div class="col-lg-4">
                                                                    <div class="form-group">
                                                                        <label>User &nbsp;<span class="text-danger required">*</span></label>
                                                                        <select class="form-control slot_user_id" name="slot_user_id[]" id="slot_user_id" data-id="" required autofocus>
                                                                            <option value="">-- Select User --</option>
                                                                            <?php
                                                                            // print_r($slot_users);
                                                                            foreach ($slot_users as $value) {
                                                                            ?>
                                                                                <option value="<?php echo $value['user_id']; ?>">
                                                                                    <?php echo $value['first_name'] . " " . $value["last_name"] . " [ " . $value['employee_id'] . "]"; ?></option>
                                                                            <?php
                                                                            }
                                                                            ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-3">
                                                                    <div class="form-group">
                                                                        <label>Date &nbsp;<span class="text-danger required">*</span></label>
                                                                        <input type="text" name="slot_date[]" id="slot_date" class="form-control slot_date" disabled required>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-3">
                                                                    <div class="form-group">
                                                                        <label>Slot &nbsp;<span class="text-danger required">*</span></label>
                                                                        <select class="form-control slot" name="slot[]" id="slot" disabled required>
                                                                            <option value="">-- Select Slot --</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="divider"></div>
                                                <div class="row m-0">
                                                    <div class="col-lg-6">
                                                        <div class="form-group">
                                                            <label for="service_addition">Add Service</label>
                                                            <select id="service_addition" class="custom-select" name="">
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </fieldset>
                                        </div>
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
                                                <label>Customer Name&nbsp;<span class="text-danger required">*</span></label>
                                                <input type="text" class="form-control" value="<?php echo $lead_details['customer_name']; ?>" required="" name="lead_name" autofocus>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>Country Code&nbsp;<span class="text-danger required">*</span></label>
                                                <input type="text" pattern="+[0-9]" class="form-control" title="Country Code like +971" required="" name="lead_country_code" value="+971">
                                            </div>
                                        </div>
                                        <div class="col-md-10">
                                            <div class="form-group">
                                                <label>Customer contact number without country code&nbsp;<span class="text-danger required">*</span></label>
                                                <input type="number" class="form-control" required="" value="<?php echo $lead_details['customer_mobile']; ?>" pattern="[5|6][0-9]{8}" name="lead_contact">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Customer Email Address</label>
                                                <input type="email" value="<?php echo $lead_details['customer_email']; ?>" class="form-control" name="lead_email">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Remarks(in English/Arabic)&nbsp;<span class="text-danger required">*</span></label>
                                                <textarea rows="7" class="form-control" required="" name="lead_remarks" required></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">Subtotal</label>
                                                <input type="number" name="amount_payment" id="amount_payment" class="form-control" placeholder="Subtotal Amount" readonly aria-describedby="helpId">
                                            </div>
                                        </div>
                                        <div class="col-md-6 card-amount">
                                            <div class="form-group">
                                                <label for="">Card Amount</label>
                                                <input type="number" name="card_amount" id="card_amount" class="form-control" placeholder="Card Amount" readonly aria-describedby="helpId">
                                            </div>
                                        </div>
                                        <div class="col-md-6 total_amount">
                                            <div class="form-group">
                                                <label for="">Total Amount</label>
                                                <input type="number" name="total_amount" id="total_amount" class="form-control" placeholder="Total Amount" readonly aria-describedby="helpId">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="">Payment Type</label><br>
                                                <div class="form-check form-check-inline">
                                                    <label class="form-check-label">
                                                        <input class="form-check-input" type="radio" name="payment_type3" id="" value="online" checked>Payment Link
                                                    </label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <label class="form-check-label">
                                                        <input class="form-check-input" type="radio" name="payment_type3" id="" value="cash" onclick="show_eligible_popup()">Pay by Cash
                                                    </label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <label class="form-check-label">
                                                        <input class="form-check-input" type="radio" name="payment_type3" id="" value="card">Pay by Card
                                                    </label>
                                                </div>
                                                <div class="helper-text payment-desc mt-2 d-none">
                                                    <small>Payment Receipt will be sent to the customer</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group online-approval">
                                                <label>Email Body&nbsp;<span class="text-danger required">*</span></label>
                                                <textarea rows="5" class="form-control ckeditor" name="email_message" id="email_editor2"></textarea>
                                            </div>
                                        </div>
                                        <!-- <div class="col-md-12">
                                            <div class="payment-approval form-group d-none">
                                                <label>Approval Code&nbsp;<span class="text-danger required">*</span></label>
                                                <input type="text" class="form-control" placeholder="Card Payment Approval Code" name="approval_code">
                                            </div>
                                        </div> -->
                                        <div class="col-md-12">
                                            <div class="form-group text-right">
                                                <input type="hidden" name="lead_created_by" value="<?php echo $this->auth_user_id; ?>">
                                                <input type="submit" class="btn btn-lg btn-primary btn-square p-3 pl-5 pr-5" name="submitForm" value="CREATE" />
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <script src="/assets_new/richtext/jquery.richtext.js"></script>
                    <link rel="stylesheet" href="/assets_new/richtext/richtext.min.css">
                    <script src="<?php echo getenv('CRM_URL'); ?>global/node_modules/select2/dist/js/select2.min.js"></script>
                    <!-- <script src="/public/assets/js/jquery-ui.min.js"></script> -->
                    <link rel="stylesheet" href="<?php echo getenv('CRM_URL'); ?>global/node_modules/select2/dist/css/select2.min.css">
                    <link rel="stylesheet" href="/global/bs-datepicker/css/bootstrap-datepicker.min.css">
                    <script src="/global/bs-datepicker/js/bootstrap-datepicker.min.js"></script>
                    <script>
                        var is_payment_type = '';
                        var is_branch = $("input[name='branch_id']").val();
                        $('.package-option1').hide();

                        $('#payment_type3').change(function() {
                            $('#package_id1').val('');
                            get_dataset("");

                            var selectedPaymentType = $(this).val();
                            if (selectedPaymentType !== '') {
                                $("[name='payment_type3'][value='" + selectedPaymentType + "']").prop("checked", true);
                                payment_typeCheckList(selectedPaymentType);
                                is_payment_type = selectedPaymentType;
                                $('.package-option1').hide();
                                $('.package-option1[data-payment-type="' + selectedPaymentType + '"]').show();


                                // Update the ajax URL with the new payment_type value
                                $('#service_addition').select2({
                                    placeholder: "Please Select the Service",
                                    ajax: {
                                        url: '<?php echo getenv('CRM_URL'); ?>leads/package/new_getPackageDetails?payment_type=' + is_payment_type + '&branch=' + is_branch,
                                        dataType: 'json'
                                    }
                                });

                            } else {
                                $('.package-option1').show();
                            }
                        });




                        //$(".ckeditor").richText();

                        var package_service = $("#service-row-gc").html();
                        $("#service-row-gc").remove();


                        function meeting_slot(service_id) {
                            var today = new Date(today);
                            var tom = today.setDate(today.getDate());
                            // var tom = today.setDate(today.getDate());
                            var today_date = new Date(tom);
                            $('[name="slot_date[' + service_id + ']"]').removeAttr("disabled");
                            $('[name="slot_date[' + service_id + ']"]').attr("data-id", service_id);
                            $.get("/admin/exceptiondate/dates", function(res) {
                                // console.log("Res==> ", res);
                                var dates = JSON.parse(res);

                                $('[name="slot_date[' + service_id + ']"]').datepicker({
                                    format: "yyyy-mm-dd",
                                    uiLibrary: 'bootstrap',
                                    weekStart: 0,
                                    autoclose: true,
                                    // daysOfWeekDisabled: [6],
                                    // daysOfWeekHighlighted: [6],
                                    startDate: "<?php echo date('Y-m-d') ?>",
                                    datesDisabled: dates,
                                });
                            });

                            $('[name="slot_date[' + service_id + ']"]').off();
                            $('[name="slot_date[' + service_id + ']"]').on("change", function() {
                                var s_id = $(this).data("id");
                                $("[name='slot[" + s_id + "]']").removeAttr("disabled");
                                // console.log("SlotDD==> ",$(this).val());
                                $.get("/admin/usertimeslot/slots?user_id=" + $("[name='slot_user_id[" + service_id + "]']").val() + "&day=" + $("[name='slot_date[" + service_id + "]']").val(), function(dd) {
                                    console.log("DD==> ", dd);
                                    var dus = JSON.parse(dd);
                                    $("[name='slot[" + service_id + "]']").html("");
                                    dus.forEach(function(i) {
                                        $("[name='slot[" + service_id + "]']").append("<option value='" + i.user_timeslot_slot_id + "'>" + i.timeslot_name + "</option>");
                                    });
                                });
                            });

                        }




                        function amount_calc() {
                            var pay_type = $("[name='payment_type3']:checked").val();
                            var total = 0;
                            var total_govt_fee = 0;
                            $('.service-row-gc').each(function() {
                                var qty = parseInt($(this).find('[name="service_qty[]"]').val());
                                var govt_fee = parseFloat($(this).find('[name="govt_fee[]"]').val());
                                var typing_fee = parseFloat($(this).find('[name="typing_fee[]"]').val());
                                var subtotal = (govt_fee + typing_fee) * qty;
                                total = total + subtotal;
                                total_govt_fee = total_govt_fee + (govt_fee * qty);
                                $(this).find('[name="sub_total[]"]').val(parseFloat(subtotal).toFixed(2));
                            });
                            $("#amount_payment").val(parseFloat(total).toFixed(2));
                            var card_per = 0;
                            //if (pay_type == "online") card_per = 0;

                            if ($("#payment_type3").val() == 'cash') {
                                card_per = 0;
                            } else if ($("#payment_type3").val() == 'online') {
                                card_per = 2.25;
                            } else {
                                card_per = 1;
                            }
                            var card_amount = parseFloat(total_govt_fee * (card_per / 100));
                            $("#card_amount").val(card_amount.toFixed(2));
                            var total_amount = total + card_amount;
                            $('#total_amount').val(total_amount.toFixed(2));


                        }

                        function action_init() {
                            $(".action-btn").off();
                            $(".action-btn").on("click", function(i) {
                                var serv_name = $(this).closest(".service-row-gc").find('[name="service_name[]"]').val();
                                swal.fire({
                                    icon: "info",
                                    title: "Are you sure to remove ?",
                                    text: serv_name,
                                    confirmButtonText: "Yes",
                                    showCancelButton: true,
                                    cancelButtonText: "Cancel"
                                }).then((val) => {
                                    if (val.isConfirmed) {
                                        $(this).closest(".service-row-gc").remove();
                                        amount_calc();

                                    }
                                    amount_calc();

                                });


                                amount_calc();
                            });

                            $("[name='service_qty[]']").off();
                            $("[name='service_qty[]']").on("focus", function(e) {
                                $(this).select();
                            });

                            $("[name='service_qty[]']").on("keyup keydown keypress", function(e) {
                                var qty = parseInt($(this).val());
                                // console.log("Qty ==> ",qty);
                                var govt_fee = parseFloat($(this).closest('.service-row-gc').find('[name="govt_fee[]"]').val());
                                var typing_fee = parseFloat($(this).closest('.service-row-gc').find('[name="typing_fee[]"]').val());
                                var total = (govt_fee + typing_fee) * qty;

                                $(this).closest('.service-row-gc').find('[name="sub_total[]"]').val(total);
                                amount_calc();
                            });
                            amount_calc();
                        }

                        //$("#package_id").change(get_dataset(this));

                        function get_dataset(package_id) {
                            var pay_type = $("[name='payment_type3']:checked").val();
                            var package_pay_type = $("#package_id1 option:selected").data("payment-type");
                            if (pay_type != undefined && package_pay_type != undefined && pay_type != package_pay_type) {
                                swal.fire({
                                    icon: "info",
                                    text: "Selected Package is not available for this payment type",
                                });
                                $("#package_id1").val("").trigger("change");
                                return;
                            }
                            //console.log(e);
                            //e.preventDefault();
                            // var amount = $("#package_id option:selected").data("amount");
                            // $('[name="amount_payment"]').val(amount);
                            //var package_id = $(e).val();
                            if (package_id == "" || package_id == null) {
                                $("#services").addClass("d-none")
                                $(".serices-content-gc").html("");
                                return;
                            }
                            $.ajax({
                                url: "<?php echo getenv('CRM_URL'); ?>leads/package/getPackageDetails?package_id=" + package_id,
                                beforeSend: function() {
                                    $("#package_id").attr('disabled', 'disabled');
                                },
                                success: function(data) {
                                    $("#package_id").removeAttr('disabled');
                                    var package = JSON.parse(data);
                                    // console.log(package);

                                    if (package.data.length > 0) {
                                        $(".serices-content-gc").html("");
                                        package.data.forEach(function(i) {
                                            $(".serices-content-gc").append("<div class='row m-0 service-row-gc'>" + package_service + "</div>");
                                            var qty = 1;
                                            var govt_fee = parseFloat(i.govt_fee);
                                            var typing_fee = parseFloat(i.typing_fee);
                                            var total = govt_fee + typing_fee;
                                            // console.log("Total ==> "+total);
                                            $('.service-row-gc:nth-last-child(1) [name="service_id[]"]').val(i.service_id);
                                            $('.service-row-gc:nth-last-child(1) [name="is_meeting_contain[]"]').val(i.is_meeting_contain);
                                            $('.service-row-gc:nth-last-child(1) [name="service_name[]"]').val(i.service_name);
                                            $('.service-row-gc:nth-last-child(1) [name="service_qty[]"]').val(qty);
                                            $('.service-row-gc:nth-last-child(1) [name="govt_fee[]"]').val(govt_fee);
                                            $('.service-row-gc:nth-last-child(1) [name="typing_fee[]"]').val(typing_fee);
                                            $('.service-row-gc:nth-last-child(1) [name="sub_total[]"]').val(total);
                                            $('.service-row-gc:nth-last-child(1) [name="is_direct_invoice[]"]').val(i.is_direct_invoice);
                                            $('.service-row-gc:nth-last-child(1) [name="msd_key[]"]').val(i.msd_key);
                                            $('.service-row-gc:nth-last-child(1) [name="is_pos_typing_fee[]"]').val(i.is_pos_typing_fee);
                                            if (i.is_meeting_contain == 1) {
                                                $('.service-row-gc:nth-last-child(1) .meeting-user').removeClass("d-none");
                                                $('.service-row-gc:nth-last-child(1) .meeting-user [name="slot_user_id[]"]').attr("name", "slot_user_id[" + i.service_id + "]");
                                                $('.service-row-gc:nth-last-child(1) .meeting-user #slot_user_id').attr("data-id", i.service_id);
                                                $('.service-row-gc:nth-last-child(1) .meeting-user [name="slot_date[]"]').attr("name", "slot_date[" + i.service_id + "]");
                                                $('.service-row-gc:nth-last-child(1) .meeting-user [name="slot[]"]').attr("name", "slot[" + i.service_id + "]");

                                                $(".slot_user_id").off();
                                                $(".slot_user_id").on("change", function(e) {
                                                    e.preventDefault();
                                                    var d = $(this).data("id");
                                                    console.log("DD => ", d);
                                                    meeting_slot(d);
                                                });
                                            } else {
                                                $('.service-row-gc:nth-last-child(1) .meeting-user').remove();
                                            }
                                        });
                                        action_init();
                                        $("#services").removeClass("d-none")
                                        amount_calc();
                                    }
                                }
                            })
                        };



                        $("#service_addition").change(function() {
                            var service_id = $(this).val();
                            if (service_id == "" || service_id == null) return false;
                            $.ajax({
                                url: "<?php echo getenv('CRM_URL'); ?>leads/package/getPackageDetail?service_id=" + service_id,
                                beforeSend: function() {
                                    Swal.fire({
                                        allowOutsideClick: false,
                                        allowEscapeKey: false,
                                        didOpen: function() {
                                            swal.enableLoading();
                                        }
                                    });
                                },
                                success: function(data) {
                                    Swal.close();
                                    var data = JSON.parse(data);

                                    if (data != "false") {
                                        // $(".serices-content-gc").html("");
                                        $(".serices-content-gc").append("<div class='row m-0 service-row-gc'>" + package_service + "</div>");
                                        var qty = 1;
                                        var govt_fee = parseFloat(data.govt_fee);
                                        var typing_fee = parseFloat(data.typing_fee);
                                        var total = govt_fee + typing_fee;
                                        // console.log("Total ==> "+total);
                                        $('.service-row-gc:nth-last-child(1) [name="service_id[]"]').val(data.service_id);
                                        $('.service-row-gc:nth-last-child(1) [name="service_name[]"]').val(data.service_name);
                                        $('.service-row-gc:nth-last-child(1) [name="service_qty[]"]').val(qty);
                                        $('.service-row-gc:nth-last-child(1) [name="govt_fee[]"]').val(govt_fee);
                                        $('.service-row-gc:nth-last-child(1) [name="typing_fee[]"]').val(typing_fee);
                                        $('.service-row-gc:nth-last-child(1) [name="sub_total[]"]').val(total);
                                        $('.service-row-gc:nth-last-child(1) [name="is_direct_invoice[]"]').val(data.is_direct_invoice);
                                        $('.service-row-gc:nth-last-child(1) [name="msd_key[]"]').val(data.msd_key);
                                        $('.service-row-gc:nth-last-child(1) [name="is_pos_typing_fee[]"]').val(data.is_pos_typing_fee);
                                        if (data.is_meeting_contain == 1) {
                                            $('.service-row-gc:nth-last-child(1) .meeting-user').removeClass("d-none");
                                            $('.service-row-gc:nth-last-child(1) .meeting-user [name="slot_user_id[]"]').attr("name", "slot_user_id[" + data.service_id + "]");
                                            $('.service-row-gc:nth-last-child(1) .meeting-user #slot_user_id').attr("data-id", data.service_id);
                                            $('.service-row-gc:nth-last-child(1) .meeting-user [name="slot_date[]"]').attr("name", "slot_date[" + data.service_id + "]");
                                            $('.service-row-gc:nth-last-child(1) .meeting-user [name="slot[]"]').attr("name", "slot[" + data.service_id + "]");

                                            $(".slot_user_id").off();
                                            $(".slot_user_id").on("change", function(e) {
                                                e.preventDefault();
                                                var d = $(this).data("id");
                                                console.log("DD => ", d);
                                                meeting_slot(d);
                                            });
                                        } else {
                                            $('.service-row-gc:nth-last-child(1) .meeting-user').remove();
                                        }
                                        action_init();
                                        $("#services").removeClass("d-none")
                                        amount_calc();
                                    }
                                    $("#service_addition").val("").trigger("change");
                                }
                            })
                        });




                        function payment_typeCheckList(val) {
                            if (val == "online") {
                                $(".online-approval").removeClass("d-none");
                                $(".payment-approval").addClass("d-none");
                                $(".payment-desc").addClass("d-none");
                                $(".card-amount").removeClass("d-none");
                            }
                            if (val == "card") {
                                $(".online-approval").addClass("d-none");
                                $(".payment-approval").removeClass("d-none");
                                $(".payment-desc").removeClass("d-none");
                                $(".card-amount").removeClass("d-none");
                            }
                            if (val == "cash") {
                                show_popup();
                                $(".online-approval").addClass("d-none");
                                $(".payment-approval").addClass("d-none");
                                $(".payment-desc").removeClass("d-none");
                                $(".card-amount").addClass("d-none");
                            }
                        }


                        $('input[type="radio"][name="payment_type3"]').prop('disabled', true);

                        $("[name='payment_type3']").change(function() {
                            var val = $(this).val();
                            payment_typeCheckList(val);
                        });

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
                            // console.log("There=> ", val);
                            // alert(val);
                            // if (biz.indexOf(val) != -1) {

                            // // location.href = "/leads/lead/biznew";
                            // swal.fire({
                            // icon: "info",
                            // text: "Redirecting to Business Setup Leads Page",
                            // didOpen: function() {
                            // swal.enableLoading();
                            // setTimeout(() => {
                            // location.href = "/leads/lead/biznew";
                            // }, 1000);
                            // }
                            // })
                            // }

                            if (attest.indexOf(val) != -1) {
                                swal.fire({
                                    icon: "info",
                                    text: "Redirecting to Attestation Leads Page",
                                    didOpen: function() {
                                        swal.enableLoading();
                                        setTimeout(() => {
                                            location.href = "<?php echo getenv('CRM_URL'); ?>leads/lead/attestationnew";
                                        }, 1000);
                                    }
                                })
                            }
                        });

                        function select_services(category_id) {
                            $.ajax({
                                url: "<?php echo getenv('CRM_URL'); ?>leads/lead/get_services?category_id=" + category_id,
                                method: "GET",
                                type: 'ajax',
                                success: function(data) {
                                    var result = JSON.parse(data);
                                    $('#service_id').html("");
                                    if (result.length == 0) {
                                        //$('#existing_items').append('<span class="badge light badge-danger">There are no services existing in this workflow</span>');
                                    } else {
                                        for (var i = 0; i < result.length; i++) {
                                            if (result[i]['service_code'] == "" || result[i]['service_code'] == null) {
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
                                    // console.log(err);
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
                                            location.href = "<?php echo getenv('CRM_URL'); ?>leads/lead/attestationnew";
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
</div>


<!-- Order Completion Modal -->
<div class="modal fade" id="order_completion_modal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Lead Converted - Order Confirm - <span class="order_modal_id"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="<?php echo getenv('CRM_URL'); ?>leads/lead/action_order" enctype="multipart/form-data"
                    method="post">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>ORDER REFERENCE NUMBER&nbsp;<span class="text-danger required">*</span></label>
                                <input type="text" name="order_id" id="order_id" required="" class="form-control omref">
                            </div>
                            <div class="form-group">
                                <input type="submit" name="action_order" class="btn btn-primary btn-block"
                                    value="COMPLETE ORDER">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save</button>
            </div>
        </div>
    </div>
</div>


<!-- Order Close Modal -->
<div class="modal fade" id="order_close_modal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Close Lead - <span class="order_modal_id"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="<?php echo getenv('CRM_URL'); ?>leads/lead/action_close" method="post">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Remarks&nbsp;<span class="text-danger required">*</span></label>
                                <textarea rows="5" class="form-control" name="close_remarks"
                                    id="close_remarks"></textarea>
                            </div>
                            <div class="form-group">
                                <input type="submit" name="action_close" class="btn btn-primary btn-block"
                                    value="CLOSE LEAD">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Conversation reply modal -->
<div class="modal fade" id="reply_modal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
    aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Send Notification</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="<?php echo getenv('CRM_URL'); ?>leads/lead/send_reply/<?= $request_id ?>/<?php echo $this->uri->segment(4); ?>" method="post" enctype="multipart/form-data" onsubmit="">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>To&nbsp;<span class="text-danger required">*</span></label>
                                <input type="text" class="form-control" placeholder="from" value="" name="to_mail">
                            </div>
                            <div class="form-group">
                                <label>CC&nbsp;</label>
                                <input type="text" class="form-control" placeholder="cc" value="" name="cc_mail">
                            </div>
                            <div class="form-group">
                                <label>Subject&nbsp;<span class="text-danger required">*</span></label>
                                <input type="text" class="form-control" placeholder="subject" readonly="true" value="" name="subject">
                            </div>
                            <div class="form-group">
                                <label>Email Body&nbsp;<span class="text-danger required">*</span></label>
                                <textarea rows="5" class="form-control" name="email_message" id="reply_editor"></textarea>
                                <div id="forwardContent" class="border p-2" style="height: 300px; overflow: auto;"></div>
                            </div>
                            <div class="form-group">
                                <label>Attachments (Optional)</label>
                                <input class="form-control" type="file" name="conv_email_attachments[]" multiple>
                            </div>
                            <div class="form-group">
                                <input type="submit" name="reply_btn" class="btn btn-primary btn-block"
                                    value="SEND">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="quotation_form_modal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">OnTime CRM Quotation -
                    <?php echo $lead_details['customer_name'] . " - <small><a href='mailto:" . $lead_details['customer_email'] . "'>" . $lead_details['customer_email'] . "</a> - <a href='tel:" . $lead_details['customer_country_code'] . $lead_details['customer_mobile'] . "'>" . $lead_details['customer_country_code'] . ' ' . $lead_details['customer_mobile'] . "</a></small>"; ?>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php
                if ($lead_details['branch_id'] == 106 && empty($lead_package_details) && !in_array($lead_details['package_id'], $before_payment_packages) ) {
                    ?>
                    <form id="create_quot_form" action="<?php echo getenv('CRM_URL') . 'leads/lead/create_quotation_new/' . $lead_details['id'] ?> " method="post" enctype="multipart/form-data">
                        <input type="hidden" name="branch_id" value="106">
                        <input type="hidden" name="lead_type" value="goldencube_package">
                        <input type="hidden" name="assign_group" value="GoldenCube">
                        <input type="hidden" name="assign_to" value="<?php echo $this->auth_user_id; ?>">
                        <input type="hidden" name="lead_id" id="lead_id" value="<?php echo $lead_details['id']; ?>">
                        <input type="hidden" name="customer_otp" value="">
                        <input type="hidden" name="user_email" value="<?php echo $auth_user_data['email']; ?>">
                        <input type="hidden" name="user_pos_id" value="<?php echo $auth_user_data['employee_id']; ?>">
                        <input type="hidden" name="customer_id" value="<?php echo $lead_customer->user_id; ?>">
                        <input type="hidden" value="<?php echo $lead_details['customer_email']; ?>" name="lead_email">
                        <input type="hidden" class="form-control" value="<?php echo $lead_details['customer_name']; ?>" name="lead_name">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Payment Type&nbsp;<span class="text-danger required">*</span></label>
                                    <?php
                                    $options = array(
                                        '' => 'Select payment type',
                                        'card' => 'Card',
                                        'cash' => 'Cash',
                                        'online' => 'Online',
                                    );
                                    echo form_dropdown('payment_typeq', $options, "", array('class' => 'form-control', 'id' => 'payment_typeq', 'required' => 'required'));
                                    ?>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Select Package &nbsp;<span class="text-danger required">*</span></label>
                                    <select class="form-control" name="package_idq" id="package_idq" required autofocus onchange="get_datasetq(this.value);">
                                        <option value="">-- Select Package --</option>
                                        <?php
                                        foreach ($packages as $key => $value) {
                                        ?>
                                            <option data-amount="<?php echo $value["package_amount"]; ?>" data-payment-type="<?php echo $value["payment_type"]; ?>" value="<?php echo $value['package_id']; ?>" class="package-option1q">
                                                <?php echo $value['package_name'] . " - " . $value["package_amount"] . "AED - " . $value["payment_type"]; ?>
                                            </option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Is Corporate <?php echo $lead_details['is_corporate']; ?></label>
                                    <select class="form-control" name="is_corporate" id="is_corporateq"
                                        required>
                                        <option value="" selected default disabled>-- Select -</option>
                                        <option value="Corporate" <?php if ($lead_details['is_corporate'] == 'Corporate') { echo 'selected';} ?>>Corporate</option>
                                        <option value="Others" <?php if ($lead_details['is_corporate'] == 'Others') { echo 'selected';} ?>>Others</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6" id="applicant_name_groupq" style="<?php echo $lead_details['is_corporate'] == 'Corporate'? '':'display: none;'; ?>">
                                <div class="form-group">
                                    <label>Applicant Name&nbsp;<span class="text-danger required">*</span></label>
                                    <input type="text" class="form-control selected_field" required="" id="applicant_nameq" name="applicant_name" title="Numbers are not allowed" <?php echo $lead_details['is_corporate'] == 'Corporate'? '':'disabled'; ?> value="<?php echo $lead_details['applicant_name']; ?>">
                                </div>
                            </div>

                            <style>
                                .service-row-gcq {
                                    counter-increment: sno;
                                }

                                .s-no {
                                    width: 20px;
                                }

                                .s-no:after {
                                    content: counter(sno)".";
                                }

                                .serv-desc {
                                    width: calc(100% - 20px)
                                }

                                .service-row-gcq:not(:nth-child(1)) label {
                                    display: none;
                                }

                                input[name='service_qty[]']::-webkit-outer-spin-button,
                                input[name='service_qty[]']::-webkit-inner-spin-button {
                                    -webkit-appearance: none;
                                    margin: 0;
                                }

                                /* Firefox */
                                input[name='service_qty[]'][type=number] {
                                    -moz-appearance: textfield;
                                }

                                .half-col-lg-2 {
                                    width: 12.33%;
                                    float: left;
                                    padding-left: 15px;
                                    padding-right: 15px;
                                }

                                .big-checkbox {
                                    width: 20px;
                                    height: 20px;
                                    transform: scale(1.5);
                                    /* Makes it larger */
                                    cursor: pointer;
                                }
                            </style>
                            <div class="col-lg-12 d-none" id="servicesq">
                                <fieldset class="border mb-3">
                                    <legend class="bg-plum-plate font-weight-lighter ml-1 p-1 pl-3 pr-3 text-white w-auto">Package Services</legend>
                                    <div class="serices-content-gcq">
                                        <div class="row m-0 service-row-gcq" id="service-row-gcq">
                                            <input type="hidden" name="service_id[]">
                                            <input type="hidden" name="is_meeting_contain[]">
                                            <div class="col-lg-3 d-flex">
                                                <div class="s-no m-auto"></div>
                                                <div class="form-group serv-desc">
                                                    <label for="">Service Description</label>
                                                    <input type="text" name="service_name[]" class="form-control" placeholder="" aria-describedby="helpId" readonly>
                                                </div>
                                            </div>
                                            <div class="col-lg-1 pl-0 pr-0 text-center">
                                                <div class="form-group">
                                                    <label for="" class="w-100">Qty</label>
                                                    <input type="number" name="service_qty[]" min="1" class="form-control text-center" placeholder="" aria-describedby="helpId" required>
                                                </div>
                                            </div>
                                            <div class="col-lg-2">
                                                <div class="form-group">
                                                    <label for="">Govt Fee</label>
                                                    <input type="number" name="govt_fee[]" class="form-control" placeholder="" aria-describedby="helpId" readonly>
                                                </div>
                                            </div>
                                            <div class="col-lg-2">
                                                <div class="form-group">
                                                    <label for="">Typing Fee (Incl Vat)</label>
                                                    <input type="number" name="typing_fee[]" class="form-control" placeholder="" aria-describedby="helpId" readonly>
                                                </div>
                                            </div>
                                            <div class="half-col-lg-2" hidden>
                                                <div class="form-group">
                                                    <label for="">Vat on Card</label>
                                                    <input type="number" name="vat_on_card[]" class="form-control" placeholder="" aria-describedby="helpId" readonly>
                                                </div>
                                            </div>
                                            <div class="col-lg-2">
                                                <div class="form-group">
                                                    <label for="">Total</label>
                                                    <input type="hidden" name="is_direct_invoice[]">
                                                    <input type="hidden" name="msd_key[]">
                                                    <input type="hidden" name="is_pos_typing_fee[]">
                                                    <input type="number" name="sub_total[]" class="form-control" placeholder="" aria-describedby="helpId" readonly required>
                                                </div>
                                            </div>
                                            <div class="col-lg-1 text-center">
                                                <div class="form-group">
                                                    <label for="">Action</label>
                                                    <button class="btn btn-primary action-btnq form-control" type="button">x</button>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 meeting-userq d-none">
                                                <div class="row justify-content-center">
                                                    <div class="col-lg-4">
                                                        <div class="form-group">
                                                            <label>User &nbsp;<span class="text-danger required">*</span></label>
                                                            <select class="form-control slot_user_idq" name="slot_user_id[]" id="slot_user_idq" data-id="" required autofocus>
                                                                <option value="">-- Select User --</option>
                                                                <?php
                                                                foreach ($slot_users as $value) {
                                                                ?>
                                                                    <option value="<?php echo $value['user_id']; ?>">
                                                                        <?php echo $value['first_name'] . " " . $value["last_name"] . " [ " . $value['employee_id'] . "]"; ?></option>
                                                                <?php
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <label>Date &nbsp;<span class="text-danger required">*</span></label>
                                                            <input type="text" name="slot_date[]" id="slot_dateq" class="form-control slot_dateq" disabled required>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <label>Slot &nbsp;<span class="text-danger required">*</span></label>
                                                            <select class="form-control slot" name="slot[]" id="slot" disabled required>
                                                                <option value="">-- Select Slot --</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="divider"></div>
                                    <div class="row m-0">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="service_additionq">Add Service</label>
                                                <select id="service_additionq" class="custom-select" name="">
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Subtotal</label>
                                    <input type="number" name="amount_payment" id="amount_paymentq" class="form-control" placeholder="Subtotal Amount" readonly aria-describedby="helpId">
                                </div>
                            </div>
                            <div class="col-md-3 card-amountq">
                                <div class="form-group">
                                    <label for="">Card Amount</label>
                                    <input type="number" name="card_amount" id="card_amountq" class="form-control" placeholder="Card Amount" readonly aria-describedby="helpId">
                                </div>
                            </div><br>
                            <div class="col-md-3 total_amountq">
                                <div class="form-group">
                                    <label for="">Total Amount</label>
                                    <input type="number" name="total_amount" id="total_amountq" class="form-control" placeholder="Total Amount" readonly aria-describedby="helpId">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group online-approvalq">
                                    <label>Email Body&nbsp;<span class="text-danger required">*</span></label>
                                    <textarea rows="5" class="form-control ckeditor" name="email_message" id="email_editorq"></textarea>
                                </div>
                            </div>
                            <!-- <div class="col-md-12">
                                <div class="payment-approvalq form-group d-none">
                                    <label>Approval Code&nbsp;<span class="text-danger required">*</span></label>
                                    <input type="text" class="form-control" placeholder="Card Payment Approval Code" name="approval_code">
                                </div>
                            </div> -->
                            <div class="col-md-12">
                                <div class="form-group text-right">
                                    <input type="hidden" name="lead_created_by" value="<?php echo $this->auth_user_id; ?>">
                                    <input type="submit" class="btn btn-lg btn-primary btn-square p-3 pl-5 pr-5" name="submitForm" value="CREATE" />
                                </div>
                            </div>
                        </div>
                    </form>
                    <?php
                } else {
                    ?>
                        <h4>Waiting for Customer's action on the Quotation</h4>
                    <?php
                }
                ?>
                <script>
                    var is_payment_typeq = '';
                    var is_branch = $("input[name='branch_id']").val();
                    $('.package-option1q').hide();
                    // Update the ajax URL with the new payment_type value
                    // $('#service_additionq').select2({
                    //     placeholder: "Please Select the Service",
                    //     ajax: {
                    //         url: '<?php echo base_url() ?>/leads/package/new_getPackageDetails?payment_type=' + is_payment_typeq + '&branch=' + is_branch,
                    //         dataType: 'json'
                    //     }
                    // });

                    $('#is_corporateq').on('change', function() {
                        if ($(this).val() === 'Corporate') {
                            $('#applicant_name_groupq').show();
                            $('#applicant_nameq').attr('required', true);
                            $('#applicant_nameq').prop('disabled', false);
                        } else {
                            $('#applicant_name_groupq').hide();
                            $('#applicant_nameq').val('').removeAttr('required');
                            $('#applicant_nameq').prop('disabled', true);
                        }
                    });

                    $('#payment_typeq').change(function() {
                        $('#package_idq').val('');
                        get_datasetq("");

                        amount_calcq();
                        var selectedPaymentType = $(this).val();
                        if (selectedPaymentType !== '') {
                            $("[name='payment_typeq'][value='" + selectedPaymentType + "']").prop("checked", true);
                            payment_typeCheckListq(selectedPaymentType);
                            is_payment_typeq = selectedPaymentType;
                            $('.package-option1q').hide();
                            $('.package-option1q[data-payment-type="' + selectedPaymentType + '"]').show();

                            $('#service_additionq').select2({
                                placeholder: "Please Select the Service",
                                ajax: {
                                    url: '<?php echo base_url() ?>/leads/package/new_getPackageDetails?payment_type=' + is_payment_typeq + '&branch=' + is_branch,
                                    dataType: 'json'
                                }
                            });
                        } else {
                            $('.package-option1q').show();
                        }
                    });

                    var package_service = $("#service-row-gcq").html();
                    $("#service-row-gcq").remove();
                    
                    function meeting_slotq(service_id) {
                        var today = new Date(today);
                        var tom = today.setDate(today.getDate());
                        // var tom = today.setDate(today.getDate());
                        var today_date = new Date(tom);
                        $('[name="slot_date[' + service_id + ']"]').removeAttr("disabled");
                        $('[name="slot_date[' + service_id + ']"]').attr("data-id", service_id);
                        $.get("/admin/exceptiondate/dates", function(res) {
                            var dates = JSON.parse(res);
                            $('[name="slot_date[' + service_id + ']"]').datepicker({
                                format: "yyyy-mm-dd",
                                uiLibrary: 'bootstrap',
                                weekStart: 0,
                                autoclose: true,
                                // daysOfWeekDisabled: [6],
                                // daysOfWeekHighlighted: [6],
                                startDate: "<?php echo date('Y-m-d') ?>",
                                datesDisabled: dates,
                            });
                        });

                        $('[name="slot_date[' + service_id + ']"]').off();
                        $('[name="slot_date[' + service_id + ']"]').on("change", function() {
                            var s_id = $(this).data("id");
                            $("[name='slot[" + s_id + "]']").removeAttr("disabled");
                            $.get("/admin/usertimeslot/slots?user_id=" + $("[name='slot_user_id[" + service_id + "]']").val() + "&day=" + $("[name='slot_date[" + service_id + "]']").val(), function(dd) {
                                console.log("DD==> ", dd);
                                var dus = JSON.parse(dd);
                                $("[name='slot[" + service_id + "]']").html("");
                                dus.forEach(function(i) {
                                    $("[name='slot[" + service_id + "]']").append("<option value='" + i.user_timeslot_slot_id + "'>" + i.timeslot_name + "</option>");
                                });
                            });
                        });

                    }

                    function amount_calcq() {
                        var pay_type = $("[name='payment_typeq']:checked").val();
                        var total = 0;
                        var total_govt_fee = 0;
                        $('.service-row-gcq').each(function() {
                            var qty = parseInt($(this).find('[name="service_qty[]"]').val());
                            var govt_fee = parseFloat($(this).find('[name="govt_fee[]"]').val());
                            var typing_fee = parseFloat($(this).find('[name="typing_fee[]"]').val());
                            var vat_on_card = 0; //parseFloat($(this).find('[name="vat_on_card[]"]').val());
                            var subtotal = (govt_fee + typing_fee + vat_on_card) * qty;
                            total = total + subtotal;
                            total_govt_fee = total_govt_fee + (govt_fee * qty);
                            $(this).find('[name="sub_total[]"]').val(parseFloat(subtotal).toFixed(2));
                        });
                        $("#amount_paymentq").val(parseFloat(total).toFixed(2));
                        var card_per = 0;
                        //if (pay_type == "online") card_per = 0;

                        if ($("#payment_typeq").val() == 'cash') {
                            card_per = 0;
                        } else if ($("#payment_typeq").val() == 'online') {
                            card_per = 2.25;
                        } else {
                            card_per = 1;
                        }
                        var card_amount = parseFloat(total_govt_fee * (card_per / 100));
                        $("#card_amountq").val(card_amount.toFixed(2));
                        var total_amount = total + card_amount;
                        $('#total_amountq').val(total_amount.toFixed(2));


                    }

                    function action_initq() {
                        $(".action-btnq").off();
                        $(".action-btnq").on("click", function(i) {
                            var serv_name = $(this).closest(".service-row-gcq").find('[name="service_name[]"]').val();
                            swal.fire({
                                icon: "info",
                                title: "Are you sure to remove ?",
                                text: serv_name,
                                confirmButtonText: "Yes",
                                showCancelButton: true,
                                cancelButtonText: "Cancel"
                            }).then((val) => {
                                if (val.isConfirmed) {
                                    $(this).closest(".service-row-gcq").remove();
                                    amount_calcq();

                                }
                                amount_calcq();

                            });


                            amount_calcq();
                        });

                        $("[name='service_qty[]']").off();
                        $("[name='service_qty[]']").on("focus", function(e) {
                            $(this).select();
                        });

                        $("[name='service_qty[]']").on("keyup keydown keypress", function(e) {
                            var qty = parseInt($(this).val());
                            var govt_fee = parseFloat($(this).closest('.service-row-gcq').find('[name="govt_fee[]"]').val());
                            var typing_fee = parseFloat($(this).closest('.service-row-gcq').find('[name="typing_fee[]"]').val());
                            var vat_on_card = parseFloat($(this).closest('.service-row-gcq').find('[name="vat_on_card[]"]').val());
                            var total = (govt_fee + typing_fee + vat_on_card) * qty;

                            $(this).closest('.service-row-gcq').find('[name="sub_total[]"]').val(total);
                            amount_calcq();
                        });
                        amount_calcq();
                    }

                    function get_datasetq(package_id) {
                        var pay_type = $("[name='payment_typeq']:checked").val();
                        var package_pay_type = $("#package_idq option:selected").data("payment-type");
                        var payment_type = $('#payment_typeq').val();
                        if(payment_type == ''){
                            payment_type = package_pay_type;
                            $('#payment_typeq').val(package_pay_type);
                            $("[name='payment_type'][value="+package_pay_type+"]").prop("checked", true);
                        }
                        if (package_id == "" || package_id == null) {
                            $("#servicesq").addClass("d-none")
                            $(".serices-content-gcq").html("");
                            return;
                        }
                        $.ajax({
                            url: "<?php echo base_url() ?>/leads/package/getPackageDetails?package_id=" + package_id,
                            beforeSend: function() {
                                $("#package_id").attr('disabled', 'disabled');
                            },
                            success: function(data) {
                                $("#package_id").removeAttr('disabled');
                                var package = JSON.parse(data);

                                if (package.data.length > 0) {
                                    $(".serices-content-gcq").html("");
                                    package.data.forEach(function(i) {
                                        $(".serices-content-gcq").append("<div class='row m-0 service-row-gcq'>" + package_service + "</div>");
                                        var qty = 1;
                                        var govt_fee = parseFloat(i.govt_fee);
                                        var typing_fee = parseFloat(i.typing_fee);
                                        var vat_on_card = parseFloat(i.vat_on_card);
                                        var total = govt_fee + typing_fee + vat_on_card;
                                        // console.log("Total ==> "+total);
                                        $('.service-row-gcq:nth-last-child(1) [name="service_id[]"]').val(i.service_id);
                                        $('.service-row-gcq:nth-last-child(1) [name="is_meeting_contain[]"]').val(i.is_meeting_contain);
                                        $('.service-row-gcq:nth-last-child(1) [name="service_name[]"]').val(i.service_name);
                                        $('.service-row-gcq:nth-last-child(1) [name="service_qty[]"]').val(qty);
                                        $('.service-row-gcq:nth-last-child(1) [name="govt_fee[]"]').val(govt_fee);
                                        $('.service-row-gcq:nth-last-child(1) [name="typing_fee[]"]').val(typing_fee);
                                        $('.service-row-gcq:nth-last-child(1) [name="vat_on_card[]"]').val(vat_on_card);
                                        $('.service-row-gcq:nth-last-child(1) [name="sub_total[]"]').val(total);
                                        $('.service-row-gcq:nth-last-child(1) [name="is_direct_invoice[]"]').val(i.is_direct_invoice);
                                        $('.service-row-gcq:nth-last-child(1) [name="msd_key[]"]').val(i.msd_key);
                                        $('.service-row-gcq:nth-last-child(1) [name="is_pos_typing_fee[]"]').val(i.is_pos_typing_fee);
                                        if (i.is_meeting_contain == 1) {
                                            $('.service-row-gcq:nth-last-child(1) .meeting-userq').removeClass("d-none");
                                            $('.service-row-gcq:nth-last-child(1) .meeting-userq [name="slot_user_id[]"]').attr("name", "slot_user_id[" + i.service_id + "]");
                                            $('.service-row-gcq:nth-last-child(1) .meeting-userq #slot_user_idq').attr("data-id", i.service_id);
                                            $('.service-row-gcq:nth-last-child(1) .meeting-userq [name="slot_date[]"]').attr("name", "slot_date[" + i.service_id + "]");
                                            $('.service-row-gcq:nth-last-child(1) .meeting-userq [name="slot[]"]').attr("name", "slot[" + i.service_id + "]");

                                            $(".slot_user_idq").off();
                                            $(".slot_user_idq").on("change", function(e) {
                                                e.preventDefault();
                                                var d = $(this).data("id");
                                                meeting_slotq(d);
                                            });
                                        } else {
                                            $('.service-row-gcq:nth-last-child(1) .meeting-userq').remove();
                                        }
                                    });
                                    action_initq();
                                    $("#servicesq").removeClass("d-none")
                                }
                            }
                        })
                    };

                    $("#service_additionq").change(function() {
                        var service_id = $(this).val();
                        if (service_id == "" || service_id == null) return false;
                        $.ajax({
                            url: "<?php echo base_url() ?>/leads/package/getPackageDetail?service_id=" + service_id,
                            beforeSend: function() {
                                Swal.fire({
                                    allowOutsideClick: false,
                                    allowEscapeKey: false,
                                    didOpen: function() {
                                        swal.enableLoading();
                                    }
                                });
                            },
                            success: function(data) {
                                Swal.close();
                                var data = JSON.parse(data);

                                if (data != "false") {
                                    $(".serices-content-gcq").append("<div class='row m-0 service-row-gcq'>" + package_service + "</div>");
                                    var qty = 1;
                                    var govt_fee = parseFloat(data.govt_fee);
                                    var typing_fee = parseFloat(data.typing_fee);
                                    var vat_on_card = parseFloat(data.vat_on_card);
                                    var total = govt_fee + typing_fee + vat_on_card;
                                    // console.log("Total ==> "+total);
                                    $('.service-row-gcq:nth-last-child(1) [name="service_id[]"]').val(data.service_id);
                                    $('.service-row-gcq:nth-last-child(1) [name="service_name[]"]').val(data.service_name);
                                    $('.service-row-gcq:nth-last-child(1) [name="service_qty[]"]').val(qty);
                                    $('.service-row-gcq:nth-last-child(1) [name="govt_fee[]"]').val(govt_fee);
                                    $('.service-row-gcq:nth-last-child(1) [name="typing_fee[]"]').val(typing_fee);
                                    $('.service-row-gcq:nth-last-child(1) [name="vat_on_card[]"]').val(vat_on_card);
                                    $('.service-row-gcq:nth-last-child(1) [name="sub_total[]"]').val(total);
                                    $('.service-row-gcq:nth-last-child(1) [name="is_direct_invoice[]"]').val(data.is_direct_invoice);
                                    $('.service-row-gcq:nth-last-child(1) [name="msd_key[]"]').val(data.msd_key);
                                    $('.service-row-gcq:nth-last-child(1) [name="is_pos_typing_fee[]"]').val(data.is_pos_typing_fee);
                                    if (data.is_meeting_contain == 1) {
                                        $('.service-row-gcq:nth-last-child(1) .meeting-userq').removeClass("d-none");
                                        $('.service-row-gcq:nth-last-child(1) .meeting-userq [name="slot_user_id[]"]').attr("name", "slot_user_id[" + data.service_id + "]");
                                        $('.service-row-gcq:nth-last-child(1) .meeting-userq #slot_user_id').attr("data-id", data.service_id);
                                        $('.service-row-gcq:nth-last-child(1) .meeting-userq [name="slot_date[]"]').attr("name", "slot_date[" + data.service_id + "]");
                                        $('.service-row-gcq:nth-last-child(1) .meeting-userq [name="slot[]"]').attr("name", "slot[" + data.service_id + "]");

                                        $(".slot_user_id").off();
                                        $(".slot_user_id").on("change", function(e) {
                                            e.preventDefault();
                                            var d = $(this).data("id");
                                            console.log("DD => ", d);
                                            meeting_slotq(d);
                                        });
                                    } else {
                                        $('.service-row-gcq:nth-last-child(1) .meeting-userq').remove();
                                    }
                                    action_initq();
                                    $("#servicesq").removeClass("d-none")
                                }
                                $("#service_additionq").val("").trigger("change");
                            }
                        })
                    });

                    function payment_typeCheckListq(val) {
                        if (val == "online") {
                            $(".online-approvalq").removeClass("d-none");
                            $(".payment-approvalq").addClass("d-none");
                            $(".card-amount").removeClass("d-none");
                        }
                        if (val == "card") {
                            $(".online-approvalq").addClass("d-none");
                            $(".payment-approvalq").removeClass("d-none");
                            $(".card-amount").removeClass("d-none");
                        }
                        if (val == "cash") {
                            show_popup();
                            $(".online-approvalq").addClass("d-none");
                            $(".payment-approvalq").addClass("d-none");
                            $(".card-amount").addClass("d-none");
                        }
                    }
                    $('input[type="radio"][name="payment_typeq"]').prop('disabled', true);

                    $("[name='payment_typeq']").change(function() {
                        var val = $(this).val();
                        payment_typeCheckListq(val);
                    });

                </script>
            </div>
        </div>
    </div>
</div>

<?php if (!($lead_details["branch_id"] == 106 && $lead_details["service_id"] == 10009) && !($lead_details["category_id"] != 125 && $lead_details["service_id"] != 103) && $fake_domain != 'yes') { ?>
    <div class="modal fade" id="payment_form" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true"
        data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Attestation Customer Payment</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form
                        action="<?php echo getenv('CRM_URL'); ?>leads/lead/action_attest_payment/<?php echo $this->uri->segment(4); ?>"
                        method="post" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>From Email&nbsp;<span class="text-danger required">*</span></label>
                                    <input type="email" required class="form-control" placeholder="from" readonly=""
                                        value="<?php echo $this->auth_email; ?>" name="from_email">
                                </div>
                                <div class="form-group">
                                    <label>To Email (Customer)&nbsp;<span class="text-danger required">*</span></label>
                                    <input type="email" required class="form-control" placeholder="from" readonly=""
                                        value="<?php echo $lead_details['customer_email']; ?>" name="customer_email">
                                    <input type="hidden" name="agent_email" value="<?php echo $this->auth_email; ?>">
                                </div>
                                <div class="form-group">
                                    <label>Subject&nbsp;<span class="text-danger required">*</span></label>
                                    <input type="text" required class="form-control" placeholder="from"
                                        value="ONTIME - Followup regarding <?php echo $lead_details['category_name']; ?> - <?php echo ' Payment Link'; ?>"
                                        name="email_subject">
                                </div>

                                <div class="form-group">
                                    <label>Body&nbsp;<span class="text-danger required">*</span></label>
                                    <textarea rows="5" class="form-control ckeditor" name="email_message" id="email_editor2"
                                        required></textarea>
                                </div>
                                <div class="services">
                                    <div class="form-group row m-0 service-row" id="service_row">
                                        <!-- <div class="col-3 pl-0">
                                            <div class="form-group">
                                                <label for="">Category</label>
                                                <select class="form-control" name="attest_category[]"
                                                    id="attest_category_id" required>
                                                    <option value="" disabled default selected>
                                                        -- Category --
                                                    </option>
                                                    <?php
                                                    // foreach ($attestation_services->data as $serve) {
                                                    //     $fee = $serve->typingFee + $serve->govtFee;
                                                    //     echo "<option data-id='" . $serve->id . "' data-amount='$fee' value='$serve->serviceName'>" . $serve->serviceName . " - AED " . $fee . "/-</option>";
                                                    // }
                                                    // echo "<option data-id='" . $serve->id . "' data-amount='$fee' value='$serve->serviceName'>" . $serve->serviceName . " - AED " . $fee . "/-</option>";
                                                    ?>
                                                </select>
                                            </div>
                                        </div> -->
                                        <div class="col-5 pl-0">
                                            <div class="form-group">
                                                <label for="">Attestation Service</label>
                                                <select class="form-control" name="attest_service[]" id="attest_service_id"
                                                    required>
                                                    <option value="" disabled default selected>
                                                        -- Select the service --
                                                    </option>
                                                    <?php
                                                    foreach ($attestation_services->Data as $serve) {
                                                        $fee = $serve->TypingFee + $serve->GovtFee;
                                                        echo "<option data-id='" . $serve->Id . "' data-amount='$fee' value='$serve->ServiceName'>" . $serve->ServiceName . " - AED " . $fee . "/-</option>";
                                                    }
                                                    // echo "<option data-id='" . $serve->id . "' data-amount='$fee' value='$serve->serviceName'>" . $serve->serviceName . " - AED " . $fee . "/-</option>";
                                                    ?>
                                                </select>
                                                <input type="hidden" name="bot_id[]" value="0">
                                                <input type="hidden" name="service_amount[]" value="1">
                                            </div>
                                        </div>
                                        <div class="col-6 p-0">
                                            <div class="row m-0">
                                                <div class="col-4 p-0">
                                                    <div class="form-group">
                                                        <label for="">ThirdParty Fee</label>
                                                        <input type="number" name="tp_fee[]" id=""
                                                            class="form-control tp_fee" step="0.01" min="0" value="0"
                                                            placeholder="" aria-describedby="helpId" required>
                                                    </div>
                                                </div>
                                                <div class="col-4 pr-0">
                                                    <div class="form-group">
                                                        <label for="">Typing Fee</label>
                                                        <input type="number" name="typing_fee[]" id=""
                                                            class="form-control typing_fee" step="0.01" min="0" value="0"
                                                            placeholder="" aria-describedby="helpId" required>
                                                    </div>
                                                </div>
                                                <div class="col-4 pr-0">
                                                    <div class="form-group">
                                                        <label for="">Discount</label>
                                                        <input type="number" name="discount[]" id=""
                                                            class="form-control discount" step="0.01" min="0" value="0"
                                                            placeholder="" aria-describedby="helpId" required disabled>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-1 pr-0 action-column">
                                            <div class="form-group text-center">
                                                <label for="">Action</label>
                                                <button type="button" class="btn btn-primary form-control service-action">
                                                    <span></span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                if ($lead_details["lead_type"] == "cross sales") { ?>
                                    <div class="form-group">
                                        <label>Cross Sale Receipt Number&nbsp;<span
                                                class="text-danger required">*</span></label>
                                        <input type="text" required readonly class="form-control"
                                            value="<?php echo $lead_details["lead_cross_sale_pmt"]; ?>"
                                            name="lead_cross_sale_pmt" readonly>
                                    </div>
                                <?php } ?>
                                <div class="form-group">
                                    <label>Total Service Amount&nbsp;<span class="text-danger required">*</span></label>
                                    <input type="number" required class="form-control" placeholder="AED" step="0.01"
                                        value="1" name="amount_payment" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="">Payment Type</label><br>
                                    <?php if ($lead_details["lead_type"] != "cross sales") { ?>
                                        <div class="form-check form-check-inline">
                                            <label class="form-check-label">
                                                <input class="form-check-input" type="radio" name="payment_type" id=""
                                                    value="online" checked> Payment Link
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <label class="form-check-label">
                                                <input class="form-check-input" type="radio" name="payment_type" id=""
                                                    value="cash"> Pay by Cash
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <label class="form-check-label">
                                                <input class="form-check-input" type="radio" name="payment_type" id=""
                                                    value="BANKTRNSFR"> Bank Transfer
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <label class="form-check-label">
                                                <input class="form-check-input" type="radio" name="payment_type" id=""
                                                    value="CHEQUE"> Cheque Payment
                                            </label>
                                        </div>

                                    <?php
                                    } else {
                                    ?>
                                        <div class="form-check form-check-inline">
                                            <label class="form-check-label">
                                                <input class="form-check-input" type="radio" name="payment_type" id=""
                                                    value="cross" checked> Cross Sale
                                            </label>
                                        </div>
                                    <?php
                                    }
                                    ?>
                                </div>

                                <div class="form-group">
                                    <input type="submit" name="action_payment" class="btn btn-primary btn-block"
                                        value="CREATE PAYMENT">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(".payment_form_toggle").click(function(e) {
            $("#payment_form").modal("show");
        });

        $("#payment_form [name='payment_type']").change(function(e) {
            e.preventDefault();
            console.log($(this).val());
            if ($(this).val() == "bank_transfer" || $(this).val() == "cheque_payment") {
                $("#payment_form .approvables").removeClass("d-none");
                $("#payment_form .approvables input").attr("required", "true");
            } else {
                $("#payment_form .approvables").addClass("d-none");
                $("#payment_form .approvables input").removeAttr("required");

            }
        });
    </script>
<?php } ?>

<div class="modal fade" id="reassign_modal" tabindex="-1" role="dialog" aria-labelledby="editReassignModelLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Lead Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="lead_preview">
                Body
            </div>
            <div class="modal-footer">
                <button type="button" class="bg-ontime btn p-2 pl-4 pr-4" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="editOrderModal" tabindex="-1" role="dialog" aria-labelledby="editSubleadModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editSubleadModalLabel">Edit Order Information</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="#" method="post" id="UpdateLeadOrder">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table" id="orderTable">
                                <thead>
                                    <tr>
                                        <th>#ID</th>
                                        <th>Service Name</th>
                                        <th>Gov Fee</th>
                                        <th>Typing Fee</th>
                                        <th>Total</th>

                                    </tr>
                                </thead>
                                <tbody>

                                    <?php
                                    $OverallTotal = 0;
                                    if ($sub_leads) {
                                        foreach ($sub_leads as $key => $value) {
                                            $iter = $key + 1;
                                            $sub_lead_id = $value->id;
                                            $OverallTotal += $value->govt_fee + $value->typing_fee;
                                    ?>


                                            <tr>
                                                <td>
                                                    <?php echo $sub_lead_id ?>
                                                    <input type="hidden" value="<?php echo $sub_lead_id; ?>"
                                                        name="input_sublead_id[]"
                                                        <?php if ($value->lead_status == 305) {
                                                            echo "readonly";
                                                        } ?>>

                                                </td>
                                                <td>
                                                    <?php echo $value->service; ?>
                                                </td>
                                                <td>
                                                    <input type="number" step="0.01"
                                                        value="<?php echo $value->govt_fee ? $value->govt_fee : 0; ?>"
                                                        name="input_govt_fee[]"
                                                        <?php if ($value->lead_status == 305) {
                                                            echo "readonly";
                                                        } ?>>
                                                </td>
                                                <td>
                                                    <input type="number" step="0.01"
                                                        value="<?php echo $value->typing_fee ? $value->typing_fee : 0; ?>"
                                                        name="input_typing_fee[]"
                                                        <?php if ($value->lead_status == 305) {
                                                            echo "readonly";
                                                        } ?>>

                                                </td>
                                                <td>
                                                    <input type="number" step="0.01"
                                                        value="<?php echo $value->govt_fee + $value->typing_fee; ?>"
                                                        name="input_total[]" readonly>

                                                </td>
                                            </tr>

                                    <?php

                                        }
                                    } ?>


                                </tbody>
                            </table>
                        </div>
                        <div class="col-lg-12">
                            <div class="text-left">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary" id="saveleadOrder">Save Changes</button>
                            </div>
                            <div class="totalamount_container text-right">
                                <input type="hidden" value="<?php echo $lead_details['id']; ?>" name="parent_lead_id">
                                <input type="hidden" value="<?php echo $OverallTotal; ?>" name="previousOverallTotal">
                                <input type="hidden" value="<?php echo $OverallTotal; ?>" name="totalamount"
                                    id="totalamountAED">
                                <div class="text-right">Overall Prev Total: AED <b
                                        id="previousOverallTotal"><?php echo $OverallTotal; ?></b></div>
                                <div class="text-right">
                                    <h4>Overall New Total: AED <b class="totalamount"><?php echo $OverallTotal; ?></b>
                                    </h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalOTP" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="modelTitleId" >
    <div class="modal-dialog" >
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


        <style>
            #toggle {
                display: none;
            }

            .labeled {
                display: inline-block;
                ;
                width: 40px;
                height: 20px;
                background-color: #ccc;
                border-radius: 10px;
                position: relative;
                cursor: pointer;
                margin-left: 32px;
            }

            .labeled::before {
                content: "";
                position: absolute;
                top: 2px;
                left: 2px;
                width: 16px;
                height: 16px;
                background-color: white;
                border-radius: 50%;
                transition: transform 0.3s;
            }

            #toggle:checked+.labeled {
                background-color: #2196F3;
            }

            #toggle:checked+.labeled::before {
                transform: translateX(20px);
            }
        </style>




        <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script> -->
        <script src="/assets_new/richtext/jquery.richtext.js"></script>
        <link rel="stylesheet" href="/assets_new/richtext/richtext.min.css">
        <script src="/assets_new/node_modules/clipboard/dist/clipboard.min.js"></script>
        <script src="<?php echo getenv('CRM_URL'); ?>global/node_modules/select2/dist/js/select2.min.js"></script>
        <link rel="stylesheet" href="<?php echo getenv('CRM_URL'); ?>global/node_modules/select2/dist/css/select2.min.css">


        <script type="text/javascript">
            var script = document.createElement('script');
            script.src = 'https://ontimesmartpos.net/api/ApiPos/ValidateInvoiceForCRM?callback=handleResponse';
            document.body.appendChild(script);

            $(document).ready(function() {

                /* $('#btnAddNewAttachmentLead').click(function(e) {
                    e.preventDefault();
                    var newDiv = $(
                        '<div class="row mt-3"><div class="col-md-6"><input type="text" class="form-control" required="" name="attachment_name[]" placeholder="Attachment Name" /></div><div class="col-md-5"><input type="file" class="form-control-file" required="" name="files[]" placeholder="" /></div><div class="col-md-1 float-right"><a href="#" class="text-danger close-div"><i class="fa fa-trash"></i></a></div></div>'
                    );

                    $('#attachments_lead').append(newDiv);

                    $('.close-div').click(function(e) {
                        e.preventDefault();
                        $(this).parent().parent().remove();
                    });
                }); */

                $('#activate_quotation').click(function(e){
                    e.preventDefault();
                    let action_id = $(this).data('id');
                    $.ajax({
                        url: "<?php echo getenv('CRM_URL'); ?>/leads/lead/activateQuotation/?action_id="+action_id,
                        type: 'get',
                        success: function (res) {
                            swal.fire({
                                icon: "success",
                                text: "Activated Successfully"
                            }).then((value) => {
                                location.reload();
                            });
                        },
                        error: function (e) {
                            swal.fire(
                                'Something went wrong!',
                                e.responseText,
                                'error'
                            )
                        }
                    });
                });

                        $('#visa_status').on('change',function(){
            let value = $(this).val();

            $('.preApproval_status_div').addClass('d-none');
            $('#document_upload_visa, .visa_remarks, .pre_approval_status').removeAttr('required');
            $('.visa_remarks, #document_upload_visa').siblings('label').find('.required').addClass('d-none');
            if(value == '6') {
                $('.preApproval_status_div').removeClass('d-none');
                $('.pre_approval_status').attr('required', 'required');
                $(this).parents('.row').find('.task_status').val('hold');
            } else if(value == '4' || value == '8') {
                $('.visa_remarks').attr('required', 'required');
                $('.visa_remarks').siblings('label').find('.required').removeClass('d-none');
                $(this).parents('.row').find('.task_status').val('hold');
            } else if(value == '5' || value == '7') {
                $('.visa_remarks, #document_upload_visa').attr('required', 'required');
                $('.visa_remarks, #document_upload_visa').siblings('label').find('.required').removeClass('d-none');
                $(this).parents('.row').find('.task_status').val('completed');
            } else if(value == '3') {
                $(this).parents('.row').find('.task_status').val('open');
            }
        })
        $('#eid_status').on('change',function(){
            let value = $(this).val();
            $('#document_upload_eid').removeAttr('required');
            if(value== '10' || value == '11') {
                $(this).parents('.row').find('.task_status').val('completed');
                $('#document_upload_eid').siblings('label').find('.required').removeClass('d-none');
                $('#document_upload_eid').attr('required', 'required');
            } else if (value == '9') {
                $('#document_upload_eid').siblings('label').find('.required').addClass('d-none');
                $(this).parents('.row').find('.task_status').val('hold');
            }
        })
        $('.task_status_visaCancel').on('change', function() {
            if($(this).val() == 'completed' || $(this).val() == 'submitted') {
                $('#document_upload_visaCancel').prop('required', true);
                $('#document_upload_visaCancel').siblings('label').find('.required').removeClass('d-none');
            } else {
                $('#document_upload_visaCancel').siblings('label').find('.required').addClass('d-none');
                $('#document_upload_visaCancel').prop('required', false);
            }
        });

        $('#block_category').on('change', function() {
            if($(this).val() == 'transferProperty' || $(this).val() == 'unblock') {
                $('.task_status_block').val('completed');
                $('.task_status_block option[value="hold"]').hide();
            } else {
                $('.task_status_block').val('completed');
                $('.task_status_block option[value="hold"]').show();
            }
        })

        $(".document_upload").on("change", function(){
            let allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'pdf'];
            let invalidFiles = [];

            if($(this).hasClass('ins_doc')) {
                allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'xls', 'xlsx', 'csv'];
            }

            $.each(this.files, function(index, file){
                let ext = file.name.split('.').pop().toLowerCase();
                if ($.inArray(ext, allowedExtensions) === -1) {
                    invalidFiles.push(file.name);
                }
            });

            if(invalidFiles.length > 0){
                swal.fire({
                    icon: "error",
                    text: "These files are not allowed:\n" + invalidFiles.join("\n")
                });
                $(this)[0].value = '';// reset file input
            }

            const maxSize = 15 * 1024 * 1024; // 15 MB
            const files = $(this)[0].files;
            let error = '';
            if (files.length !== 0) {
                for (let i = 0; i < files.length; i++) {
                    if (files[i].size > maxSize) {
                        error = `File "${files[i].name}" exceeds 15 MB.`;
                        break;
                    }
                }
            }
            if (error) {
                swal.fire(
                    'Something went wrong!',
                    error,
                    'error'
                )
                $(this)[0].value = '';
            }
        });

        $('.task_status_valuation').on('change', function() {
            $('#document_upload_val,.amount_check,.valuation_remarks').prop('required', false);
            $('#document_upload_val,.amount_check,.valuation_remarks').siblings('label').find('.required').addClass('d-none');
            if($(this).val() == 'valueCheck') {
                $('.amount_check,.valuation_remarks').prop('required', true);
                $('.amount_check,.valuation_remarks').siblings('label').find('.required').removeClass('d-none');
            } else if($(this).val() == 'completed') {
                $('#document_upload_val').prop('required', true);
                $('#document_upload_val').siblings('label').find('.required').removeClass('d-none');
            }
        })
        $('.task_status_attest').on('change', function() {
            $('#quotation_amount_attest, #process_time_attest,#document_upload_attest').find('.required').addClass('d-none');
            $('#quotation_amount_attest, #process_time_attest,#document_upload_attest').prop('required', false);
            if($(this).val() == 'completed') {
                $('#document_upload_attest').prop('required', true);
                $('#document_upload_attest').siblings('label').find('.required').removeClass('d-none');
                $('.document_type_div').removeClass('d-none');
                $('.status_open_div').addClass('d-none');
            } else if($(this).val() == 'open'){
                $('#quotation_amount_attest, #process_time_attest').find('.required').removeClass('d-none');
                $('#quotation_amount_attest, #process_time_attest').prop('required', true);
                $('.status_open_div').removeClass('d-none');
            }
        });

        $('.task_status_translation').on('change', function() {
            $('#quotation_amount_translation, #process_time_attest,#document_upload_translation').find('.required').addClass('d-none');
            $('#quotation_amount_translation, #process_time_attest,#document_upload_translation').prop('required', false);
            if($(this).val() == 'completed') {
                $('#document_upload_translation').prop('required', true);
                $('#document_upload_translation').siblings('label').find('.required').removeClass('d-none');
                $('.document_type_div').removeClass('d-none');
                $('.status_open_div').addClass('d-none');
            } else if($(this).val() == 'open'){
                $('#quotation_amount_translation, #process_time_attest').find('.required').removeClass('d-none');
                $('#quotation_amount_translation, #process_time_attest').prop('required', true);
                $('.status_open_div').removeClass('d-none');
            }
        });

        $('.task_status_shipping').on('change', function() {
            $('#process_time_shipping').siblings('label').find('.required').addClass('d-none');
            $('#process_time_shipping').prop('required', false);
            $('.process_time_div').addClass('d-none');
            $('.shipping_open_div').removeClass('d-none');
            $('#estimate_date').prop('disabled', false); 
            if($(this).val() == 'completed') {
                $('#estimate_date').prop('required', false);
                $('#estimate_date').prop('disabled', true); 
                $('#estimate_date').siblings('label').find('.required').addClass('d-none');
                $('#process_time_shipping').prop('required', true);
                $('#process_time_shipping').siblings('label').find('.required').removeClass('d-none');
                $('.process_time_div').removeClass('d-none');
                $('.shipping_open_div').addClass('d-none');
            } else if($(this).val() == 'hold'){
                $('#estimate_date').prop('disabled', true); 
                $('.shipping_open_div').addClass('d-none');
            }
        });

        $('#document_type').on('change', function() {
            if($(this).val() == 'physical') {
                $('#document_upload_attest').prop('required', false);
                $('#document_upload_attest').siblings('label').find('.required').addClass('d-none');
                $('.document_type_div').addClass('d-none');
            } else {
                $('#document_upload_attest').prop('required', true);
                $('#document_upload_attest').siblings('label').find('.required').removeClass('d-none');
                $('.document_type_div').removeClass('d-none');
            }
        });

        $('.task_status_ins').on('change', function() {
            if($(this).val() == 'completed') {
                $('#document_upload_ins').prop('required', true);
                $('#document_upload_ins').siblings('label').find('.required').removeClass('d-none');
            } else {
                $('#document_upload_ins').siblings('label').find('.required').addClass('d-none');
                $('#document_upload_ins').prop('required', false);
            }
        });

        $('#insurance_category').on('change', function() {
            $('.task_status_ins').val('open');
            if($(this).val() == 'basic') {
                $('#document_upload_ins').prop('required', false);
                $('#document_upload_ins').siblings('label').find('.required').addClass('d-none');
            } else {
                $('#document_upload_ins').prop('required', true);
                $('#document_upload_ins').siblings('label').find('.required').removeClass('d-none');
            }
        });

                let payment_type = '<?php echo $lead_package_details[0]->payment_type; ?>';
                let customer_name = '<?php echo addslashes($lead_details['customer_name']); ?>';
                let customer_email = '<?php echo $lead_details['customer_email']; ?>';
                let customer_mobile = '<?php echo $lead_details['customer_mobile']; ?>';
                let user_id = '<?php echo $auth_user_data['employee_id']; ?>';
                let user_email = '<?php echo $auth_user_data['email']; ?>';
                // user_email = "Gowtham@egovllc.com";

                $('a#cash_payment').click(function(e) {
                    if(payment_type == "cash"){
                        e.preventDefault();
                        $.ajax({
                            "url": "https://ontimesmartpos.net/api/ApiPos/CrmPaymentOtpVerfication",
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
                                    $("#modalOTP").find('#submitOTP').addClass('cashPayment');
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

                $(".quotation_form_toggle").click(function (e) {
                    $("#quotation_form_modal").modal("show");
                });
                
                $('#btnAddNewAttachmentOrder').click(function(e) {
                    e.preventDefault();
                    var newDiv = $(
                        '<div class="row mt-3"><div class="col-md-6"><input type="text" class="form-control" required="" name="attachment_name[]" placeholder="Attachment Name" /></div><div class="col-md-5"><input type="file" class="form-control-file" required="" name="files[]" placeholder="" /></div><div class="col-md-1 float-right"><a href="#" class="text-danger close-div"><i class="fa fa-trash"></i></a></div></div>'
                    );

                    $('#attachments').append(newDiv);

                    $('.close-div').click(function(e) {
                        e.preventDefault();
                        $(this).parent().parent().remove();
                    });
                });

                $('#btnAddNewAttachmentSublead').click(function(e) {
                    e.preventDefault();
                    var newDiv = $(
                        '<div class="row mt-3"><div class="col-md-6"><input type="text" class="form-control" required="" name="attachment_name[]" placeholder="Attachment Name" /></div><div class="col-md-5"><input type="file" class="form-control-file" required="" name="files[]" placeholder="" /></div><div class="col-md-1 float-right"><a href="#" class="text-danger close-div"><i class="fa fa-trash"></i></a></div></div>'
                    );

                    $('#attachmentsSublead').append(newDiv);

                    $('.close-div').click(function(e) {
                        e.preventDefault();
                        $(this).parent().parent().remove();
                    });
                });

                $('#conversationAccordion .btnAcct').on('click', function() {
                    let content_url = $(this).data('contenturl');
                    let card_body_sub = $(this).parents('.item').find('.card-body .subject');
                    let card_body_desc = $(this).parents('.item').find('.card-body .description');
                    let card_body_attach = $(this).parents('.item').find('.card-body .attachments');

                    if (content_url.length && !card_body_desc.text().length) {
                        $.ajax({
                            url: '<?php echo getenv('CRM_URL'); ?>leads/lead/conversation_description',
                            type: 'GET',
                            data: {
                                "content_url": content_url,
                            },
                            dataType: 'json', // Add this line to parse the response as JSON
                            success: function(response) {
                                let resp = JSON.parse(response);
                                let desc = resp.notification.description;
                                let subject = resp.notification.subject;
                                let to = resp.notification.to.map(mail => mail.email_id).join(", ");

                                subject_content = `<div class="mb-2"><b>To: </b>${to}</div><div><b>subject: </b>${subject}</div>`
                                desc = desc.replaceAll("/api/v3/requests", "https://94.200.55.118:5000/api/v3/requests");

                                if (resp.notification.has_attachments == true) {
                                    let attachment_links = '<span>Attachments : </span><br><br>';
                                    let attachments = resp.notification.attachments;

                                    attachments.forEach(attachment => {
                                        attachment_url = attachment.content_url;
                                        attachment_type = attachment.content_type;
                                        // attachment_links += `<a href='${attachment_url}' target="_blank">${attachment.name}</a><br>`;
                                        attachment_links += `<a href="#" tabindex="-1" aria-disabled="true" class="attachmentLink" data-url='${attachment_url}' data-name='${attachment.name}' data-type='${attachment_type}'>${attachment.name}</a><br>`;
                                    });
                                    attachment_links += '<br><br>';
                                    card_body_attach.html(attachment_links);
                                }

                                card_body_sub.attr('data-sub', subject);
                                card_body_sub.html(subject_content);
                                card_body_desc.html(desc);
                            },
                            error: function(xhr, status, error) {
                                // Handle error response
                                console.log(error);
                            }
                        });
                    }
                })

                $('#conversationAccordion').on("click", ".attachmentLink", function(e) {
                    e.preventDefault();
                    let content_url = $(this).data('url');
                    let image_name = $(this).data('name');
                    let contentType = $(this).data('type');
                    $.ajax({
                        url: '<?php echo getenv('CRM_URL'); ?>leads/lead/download_attachment',
                        type: 'GET',
                        data: {
                            "content_url": content_url,
                            "content_type": contentType,
                            "image_name": image_name,
                        },
                        xhrFields: {
                            responseType: 'blob'
                        }, // Ensure response is treated as a binary file
                        success: function(response, status, xhr) {
                            let fileExtension = '';

                            if (contentType.startsWith('image/')) {
                                fileExtension = contentType.split('/')[1]; // Extract image extension
                            } else if (contentType === 'application/pdf') {
                                fileExtension = 'pdf';
                            } else if (contentType === 'application/msword') {
                                fileExtension = 'doc';
                            } else if (contentType === 'application/vnd.openxmlformats-officedocument.wordprocessingml.document') {
                                fileExtension = 'docx';
                            } else {
                                fileExtension = 'bin';
                            }

                            let fileName = image_name;
                            let a = document.createElement('a');
                            a.href = window.URL.createObjectURL(response); // Convert binary data to downloadable URL
                            a.download = fileName; // Set dynamic file name
                            document.body.appendChild(a);
                            a.click();
                            document.body.removeChild(a);
                        },
                        error: function(xhr, status, error) {
                            console.log(error);
                        }
                    });
                })

                $('#reply_editor').richText();

                $(".replyBtn").on('click', function() {
                    let content_url = $(this).data('contenturl');
                    let toMail = $(this).data('tomail');
                    let card_body_sub = $(this).parents('.item').find('.card-body .subject');
                    let card_body_desc = $(this).parents('.item').find('.card-body .description');
                    let subject = $(this).parents('.item').find('.card-body .subject').data('sub');
                    $('.richText').show();
                    $('#forwardContent').hide();
                    if (subject === undefined && content_url.length) {
                        $.ajax({
                            url: '<?php echo getenv('CRM_URL'); ?>leads/lead/conversation_description',
                            type: 'GET',
                            data: {
                                "content_url": content_url,
                            },
                            dataType: 'json', // Add this line to parse the response as JSON
                            success: function(response) {
                                let resp = JSON.parse(response);
                                let desc = resp.notification.description;
                                subject = resp.notification.subject;
                                let to = resp.notification.to.map(mail => mail.email_id).join(", ");

                                subject_content = `<div class="mb-2"><b>To: </b>${to}</div><div><b>subject: </b>${subject}</div>`
                                desc = desc.replaceAll("/api/v3/requests", "https://94.200.55.118:5000/api/v3/requests");
                                card_body_sub.attr('data-sub', subject);
                                card_body_sub.html(subject_content);
                                card_body_desc.html(desc);
                                $('#reply_modal form input[name="subject"]').val(subject);
                                $("#reply_modal").modal('show');
                            },
                            error: function(xhr, status, error) {
                                // Handle error response
                                console.log(error);
                            }
                        });
                    } else {
                        $('#reply_modal form input[name="subject"]').val(subject);
                        $("#reply_modal").modal('show');
                    }
                    $('#reply_modal form input[name="to_mail"]').val(toMail);
                    $('#reply_modal form #reply_editor').val('');
                    $('#reply_modal form #reply_editor').trigger('change');
                })


                $(".forwardBtn").on('click', function() {
                    let content_url = $(this).data('contenturl');
                    let toMail = $(this).data('tomail');
                    let card_body_sub = $(this).parents('.item').find('.card-body .subject');
                    let card_body_desc = $(this).parents('.item').find('.card-body .description');
                    let subject = $(this).parents('.item').find('.card-body .subject').data('sub');
                    $('.richText').hide();
                    $('#forwardContent').show();
                    if (subject === undefined && content_url.length) {
                        $.ajax({
                            url: '<?php echo getenv('CRM_URL'); ?>leads/lead/conversation_description',
                            type: 'GET',
                            data: {
                                "content_url": content_url,
                            },
                            dataType: 'json', // Add this line to parse the response as JSON
                            success: function(response) {
                                let resp = JSON.parse(response);
                                let desc = resp.notification.description;
                                subject = resp.notification.subject;
                                let to = resp.notification.to.map(mail => mail.email_id).join(", ");

                                subject_content = `<div class="mb-2"><b>To: </b>${to}</div><div><b>subject: </b>${subject}</div>`
                                desc = desc.replaceAll("/api/v3/requests", "https://94.200.55.118:5000/api/v3/requests");
                                card_body_sub.attr('data-sub', subject);
                                card_body_sub.html(subject_content);
                                card_body_desc.html(desc);
                                $('#reply_modal form input[name="subject"]').val(subject);
                                $('#forwardContent').html(desc);
                                $('#reply_modal form #reply_editor').val(desc);
                                $('#reply_modal form #reply_editor').trigger('change');
                                $("#reply_modal").modal('show');
                            },
                            error: function(xhr, status, error) {
                                // Handle error response
                                console.log(error);
                            }
                        });
                    } else {
                        $('#reply_modal form input[name="subject"]').val(subject);
                        $('#reply_modal form #reply_editor').val(card_body_desc.html());
                        $('#reply_modal form #reply_editor').trigger('change');
                        $('#forwardContent').html(card_body_desc.html());
                        $("#reply_modal").modal('show');
                    }
                    $('#reply_modal form input[name="to_mail"]').val(toMail);
                    // $('#reply_modal form #reply_editor').val('');
                })

                $('#editOrderButton').click(function() {
                    $('#editOrderModal').modal('show');
                });


                var previousOverallTotal = parseFloat($('#previousOverallTotal').text());



                // Add event listener to "Save Changes" button
                $('#saveleadOrder').click(function() {
                    // Get form data
                    var formData = $('#UpdateLeadOrder').serialize();

                    // Get the new overall total
                    var newOverallTotal = parseFloat($('.totalamount').text());


                    // Check if the new overall total matches the previous overall total
                    if (newOverallTotal !== previousOverallTotal) {
                        alert('Error: Total amount does not match the previous total amount. Prev Total=' +
                            previousOverallTotal + ', New Total=' + newOverallTotal
                        ); // Show error message
                        return false; // Stop the form from submitting
                    }

                    // Send AJAX request to server
                    $.ajax({
                        url: '<?php echo getenv('CRM_URL'); ?>leads/package/save_lead_order',
                        type: 'POST',
                        data: formData,
                        dataType: 'json', // Add this line to parse the response as JSON
                        success: function(response) {
                            console.log(response.status);
                            if (response.status == 'success') {
                                alert('Order Updated Successfully');
                                location.reload();

                            }
                            // Handle success response
                            console.log(response);
                        },
                        error: function(xhr, status, error) {
                            // Handle error response
                            console.log(error);
                        }
                    });
                });

                // Update overall total in real-time
                $('#orderTable').on('input', 'input[name^="input_"]', function() {
                    var total = 0;

                    // Calculate total
                    $('#orderTable tbody tr').each(function() {
                        var govtFee = parseFloat($(this).find('input[name^="input_govt_fee"]')
                            .val()) || 0;
                        var typingFee = parseFloat($(this).find(
                            'input[name^="input_typing_fee"]').val()) || 0;
                        var additionalGovtFee = parseFloat($(this).find(
                            'input[name^="input_additional_govt_fee"]').val()) || 0;
                        var rowTotal = govtFee + typingFee + additionalGovtFee;
                        $(this).find('input[name^="input_total"]').val(rowTotal);
                        total += rowTotal;
                    });

                    var minval = total - previousOverallTotal;
                    // Update overall total
                    $('.totalamount').text(total + '(' + minval + ')');
                    $('#totalamountAED').val(total);
                });


                $("#package_id").change(function(e) {
                    e.preventDefault();
                    // var amount = $("#package_id option:selected").data("amount");
                    // $('[name="amount_payment"]').val(amount);
                    var package_id = $(this).val();
                    if (package_id == "" || package_id == null) {
                        $("#services").addClass("d-none")
                        $(".serices-content").html("");
                        return;
                    }
                    $.ajax({
                        url: "<?php echo getenv('CRM_URL'); ?>leads/package/getPackageDetails?package_id=" +
                            package_id,
                        beforeSend: function() {
                            $("#package_id").attr('disabled', 'disabled');
                        },
                        success: function(data) {
                            $("#package_id").removeAttr('disabled');
                            var package = JSON.parse(data);
                            // console.log(package);

                            if (package.data && package.data.length > 0) {
                                $(".serices-content").empty();
                                // Create a select element
                                var select = $(
                                    "<select class='form-control' name=package_service_id>"
                                ).appendTo(".serices-content");
                                var label = $("<label>").text("Select a service*")
                                    .insertBefore(select);

                                // Loop through the response data and append options to the select element
                                package.data.forEach(function(i) {
                                    var govt_fee = parseFloat(i.govt_fee);
                                    var typing_fee = parseFloat(i.typing_fee);
                                    var total = govt_fee + typing_fee;

                                    // Create an option element
                                    var option = $("<option>")
                                        .val(i
                                            .service_id
                                        ) // set the value to service_id
                                        .text("Service: " + i.service_name +
                                            " | Gov Fee: " + govt_fee +
                                            " | Typing Fee: " + typing_fee +
                                            " | Total: " + total
                                        ) // set the text to service_name | gov_fee | typing_fee | Total
                                        .appendTo(
                                            select
                                        ); // append the option to the select element
                                });
                                $("#services").removeClass("d-none")
                            } else {
                                // If no services are returned, clear the content and hide the section
                                $(".serices-content").html("");
                                $("#services").addClass("d-none");
                            }
                        }
                    })
                });




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
                $('[name="package_id"] option').addClass("d-none");


                $("#branch_id").change(function() {
                    var biz = [6, 13, 14, 20, 21];
                    var attest = [103];
                    var val = parseInt($(this).val());
                    // console.log("There=> ", val);
                    // alert(val);
                    // if (biz.indexOf(val) != -1) {

                    // // location.href = "/leads/lead/biznew";
                    // swal.fire({
                    // icon: "info",
                    // text: "Redirecting to Business Setup Leads Page",
                    // didOpen: function() {
                    // swal.enableLoading();
                    // setTimeout(() => {
                    // location.href = "/leads/lead/biznew";
                    // }, 1000);
                    // }
                    // })
                    // }
                    $(".serices-content").html("");
                    $('[name="package_id"] option').addClass("d-none");
                    $('[name="package_id"] option[data-branch="' + val + '"]').removeClass("d-none");
                    $("#package_id").val("").trigger("change");

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

                $("#addPaymentForm, #eligibilityCheck").submit(function(e){
                    let formID = $(this).attr("id");
                    let paymentType = formID == 'addPaymentForm'?$(this).find('input[name=payment_type]:checked').val():$(this).find('[name=payment_type3]').val();
                    let country_code = $(this).find("input[name=lead_country_code]").val();
                    let type = country_code == '+971'?'mobile':'email';
                    let customer_mobile = $(this).find("input[name=lead_contact]").val();
                    let customer_email = formID == 'addPaymentForm'?$(this).find("input[name=customer_email]").val():$(this).find("input[name=lead_email]").val();
                    let customer_name = $(this).find("input[name=lead_name]").val();
                    let customer_id = $(this).find("input[name=customer_id]").val();
                    let user_id = $(this).find("input[name=user_pos_id]").val();
                    let user_email = $(this).find("input[name=user_email]").val();
                    
                    let amount_payment_val = $(this).find("input[name=amount_payment]").val();
                    if(amount_payment_val == 0 || amount_payment_val == ''){
                        e.preventDefault();
                        Swal.fire('Error!', 'Amount should be greater than 0', 'error');
                        return false;
                    }

                    if(paymentType == 'cash'){
                        e.preventDefault();
                        $.ajax({
                            "url": "https://ontimesmartpos.net/api/ApiPos/CrmPaymentOtpVerfication",
                            "method": "POST",
                            beforeSend: function() {
                                swal.fire({
                                    // text: "Sending OTP to customer Mobile or Email",
                                    didOpen: function() {
                                        swal.enableLoading();
                                    }
                                })
                            },
                            "data": {
                                "type": type,
                                "customer_email": customer_email,
                                "customer_mobile": customer_mobile,
                                "customer_name": customer_name,
                                "customer_id": customer_id,
                                "user_id": user_id,
                                "user_email": user_email
                            },
                            success: function(response) {
                                let res = response;
                                if(res.ResponseCode == 0){
                                    Swal.close();
                                    $("#modalOTP").modal();
                                    $('#submitOTP').data('formid',formID)
                                } else if(res.ResponseCode == 1) {
                                    Swal.fire('Error!', res.ResponseMsg, 'error');
                                }
                            },
                            error: function(xhr, status, error) {
                                Swal.close();
                                Swal.fire('Error!', 'Something went wrong with the request.', 'error');
                            }
                        });
                    }
                })
            
                $('.otp-input').on('input', function () {
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
                    let formID = $(this).data('formid');
                    let cashPayment = $(this).hasClass('cashPayment');
                    let customer_email = formID=='addPaymentForm'?$("#addPaymentForm").find("input[name=customer_email]").val():$("#eligibilityCheck").find("input[name=lead_email]").val();
                    for (var i = 1; i <= 6; i++) {
                        otp += $('#otp' + i).val();
                    }
                    if (otp.length === 6) {
                        if(formID == 'addPaymentForm') {
                            $("#addPaymentForm input[name=customer_otp]").val(otp);
                        } else {
                            $("#eligibilityCheck input[name=customer_otp]").val(otp);
                        }
                        $.ajax({
                            "url": "https://ontimesmartpos.net/api/ApiPos/ValidateCrmOTP",
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
                                if(res.ResponseCode == 0){
                                    $('#modalOTP').modal('hide');
                                    $('#submitOTP').removeClass('cashPayment');
                                    if(cashPayment){
                                        let link = $('a#cash_payment').data("href");
                                        link = link + "/"+otp;
                                        location.href = link;
                                    } else {
                                        if (formID == 'addPaymentForm') {
                                            var newInput = $('<input>', {
                                                type: 'hidden',
                                                name: 'action_payment',
                                                value: 'SEND EMAIL'
                                            });
                                        } else {
                                            var newInput = $('<input>', {
                                                type: 'hidden',
                                                name: 'submitForm',
                                                value: 'CREATE'
                                            });
                                        }
                                        $("#" + formID).append(newInput);
                                        $("#" + formID)[0].submit();
                                    }
                                } else if(res.ResponseCode == 1) {
                                    $('.otp-input').val('');
                                    Swal.close();
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
                        Swal.fire('Error!',"Enter Valid 6-digit OTP.<br>'"+pastedData+"' is not allowed.", 'error');
                        return;
                    }
                    var inputs = $('.otp-input');

                    $.each(pastedData.split(''), function(index, value) {
                        if (index < inputs.length) {
                            $(inputs[index]).val(value).focus();
                        }
                    });
                });




            });
        </script>



        <?php
        if ($lead_details["lead_type"] == "emp" && $lead_details["lead_emp_discount_per"] > 0) {
        ?>
            <script>
                $('[name="tp_fee[]"]').attr("readonly", "true");
            </script>
        <?php
        }
        ?>

        <script>
            if ($(".convertInv").length > 0) {
                $(".convertInv").click(function(e) {
                    var href = $(this).data("href");
                    e.preventDefault();
                    Swal.fire({
                        icon: "info",
                        text: "Please confirm to proceed to convert to invoice.",
                        allowEscapeKey: false,
                        outsideClick: false,
                        showCancelButton: true
                    }).then((val) => {
                        if (val.isConfirmed) {
                            location.href = href;
                        }
                    });
                })
            }

            if ($(".convertInvAlert").length > 0) {
                $(".convertInvAlert").click(function(e) {
                    e.preventDefault();
                    Swal.fire({
                        type: 'error',
                        icon: "error",
                        text: "Admin fee invoice cannot be generated until other subleads are completed",
                        allowEscapeKey: false,
                        outsideClick: false,
                        showCancelButton: true,
                        showConfirmButton: false
                    }).then((val) => {});
                })
            }

            if ($("#service_row").length) {
                var service_row = $("#service_row").html();
            }

            if ($('[name="payment_type"]').length > 0) {
                $('[name="payment_type"]').change(function() {
                    var val = $(this).val();
                    if (val == "card") {
                        // $(".payment-approval").removeClass("d-none");
                        // $('[name="approval_code"]').attr("required", "true");
                    } else {
                        $(".payment-approval").addClass("d-none");
                        // $('[name="approval_code"]').removeAttr("required");
                    }

                    if (val != "online") {
                        $(".payment-desc").removeClass("d-none");
                    } else {
                        $(".payment-desc").addClass("d-none");
                    }

                });
            }

            function service_row_amount() {
                var total = 0;
                var typing_total = 0;
                $('select[name="attest_service[]"]').each(function(e) {
                    var amount = parseFloat($(this).find("option:selected").data("amount"));
                    var typing = parseFloat($(this).closest(".service-row").find(".typing_fee").val());

                    var id = parseFloat($(this).find("option:selected").data("id"));

                    <?php
                    if ($lead_details["lead_type"] == "emp" && $lead_details["lead_emp_discount_per"] > 0) {
                    ?>
                        var dis_per = <?php echo $lead_details["lead_emp_discount_per"]; ?>;
                        var act_dis = (amount * (dis_per)) / 100;
                        $(this).closest(".service-row").find("[name='discount[]']").val(parseFloat(act_dis));
                    <?php
                    } ?>

                    if (!isNaN(amount)) {
                        var dis = $(this).closest(".service-row").find("[name='discount[]']").val();
                        $(this).closest(".service-row").find("[name='bot_id[]']").val(id);
                        $(this).closest(".service-row").find("[name='service_amount[]']").val(amount);
                        console.log("AmountDis ==> " + amount, dis);
                        amount = amount - dis;
                        total = parseFloat(total) + parseFloat(amount);
                    }
                    typing_total = typing_total + typing;
                });
                total = parseFloat(total) + ((parseFloat(total) + parseFloat(typing_total)) * 0.05) + typing_total;
                console.log("Totalsd ==> " + total);
                console.log("Total ==> " + total.toFixed(2));
                $("[name='amount_payment']").val(total.toFixed(2));
            }

            function service_row_appends() {
                if ($("#service_row").length) {
                    $(".service-row:nth-child(1) .service-action,.service-row:not(:nth-child(1)) .service-action")
                        .off();
                    $(".service-row:nth-child(1) .service-action").click(function(e) {
                        e.preventDefault();
                        $(".services").append("<div class='form-group service-row row m-0'>" + service_row +
                            "</div>");
                        service_row_appends();
                        service_row_amount();
                    });

                    $(".service-row:not(:nth-child(1)) .service-action").click(function(e) {
                        e.preventDefault();
                        $(this).closest(".service-row").remove();
                        // $(".services").append("<div class='form-group service-row row m-0'>" + service_row + "</div>");
                        service_row_appends();
                        service_row_amount();
                    });

                    $(".tp_fee,.discount,select[name='attest_service'],.typing_fee").off();
                    $(".tp_fee,.discount,.typing_fee").on("focus", function() {
                        $(this).select();
                    });

                    $(".tp_fee,.discount,.typing_fee").on("keyup keydown", function() {
                        // alert("There is");
                        var tp = $(this).closest(".service-row").find(".tp_fee").val();
                        if (tp > 0) {
                            var option = $(this).closest(".service-row").find("select").find("option:selected")
                                .data(
                                    "amount");

                            var bot_id = $(this).closest(".service-row").find("select").find("option:selected")
                                .data("id");
                            console.log("OptionID", option, bot_id);
                            var maximum = option - tp;
                            var max = 0;
                            if (maximum > tp) max = tp;
                            else max = maximum;
                            $(this).closest(".service-row").find(".discount").removeAttr("disabled");
                            $(this).closest(".service-row").find(".discount").attr("max", max);
                        }
                        service_row_amount();
                    })

                    $('select[name="attest_service[]"]').on("change", function() {
                        service_row_amount();
                    })

                    service_row_amount();

                    $('[name="attest_service[]"]').each(function(i) {
                        console.log(i + ' ==> ' + $(this).attr("id"));
                        $(this).attr("id", $(this).attr("id") + "_" + i);
                        if (!$(this).hasClass("select2-hidden-accessible")) {
                            $(this).select2({
                                dropdownParent: $("#payment_form")
                            });
                        }
                    });

                }


            }

            $(document).on('select2:open', () => {
                document.querySelector('.select2-search__field').focus();
            });

            <?php
            if ($this->auth_user_role == 7 && in_array(77, $groups)) {
            ?>
                $(".convert-btn").click(function(e) {
                    e.preventDefault();
                    swal.fire({
                        icon: "info",
                        text: "You want to convert this lead ?",
                        showCancelButton: true,
                        showConfirmButton: true
                    }).then((val) => {
                        if (val.isConfirmed) {
                            location.href =
                                "/leads/lead/attestconvert/<?php echo $lead_details['id']; ?>";
                        }
                    });
                });
            <?php
            } ?>
        </script>


        <script>
            service_row_appends();


            $(".invoice-convert").click(function(e) {
                e.preventDefault();
                Swal.fire({
                    icon: "info",
                    text: "Please confirm to proceed to convert to invoice.",
                    showConfirmButton: true,
                    showCancelButton: true
                }).then((val) => {
                    if (val.isConfirmed) {
                        location.href = $(this).data("href");
                    }
                });
            });

            $('.cpy-data').click(function(e) {
                var cc = new ClipboardJS.copy($(this).data("data"));
                if (cc) {

                    Swal.fire({
                        icon: "success",
                        title: "Payment Link Successfully Copied.",
                        toast: true,
                        timer: 3000,
                        position: "top-end",
                        showConfirmButton: false
                    })
                }
            });

            $("form#action_email").submit(function(e) {
                var body = $("form#action_email textarea").val();
                body = removeTags(body);
                if (body.trim() == '') {
                    swal.fire({
                        icon: "info",
                        text: "Please enter the Email Body"
                    });
                    e.preventDefault();
                }
            });

            function apply_email_template(template_id) {
                $.ajax({
                    url: "<?php echo getenv('CRM_URL'); ?>leads/lead/get_template?template_id=" + template_id,
                    method: "GET",
                    type: 'ajax',
                    dataType: 'html',
                    success: function(data) {
                        $("#email_editor").val(data).trigger("change");
                    },
                    error: function(err) {
                        $("#email_editor").val("").trigger("change");
                    }
                });
            }

            function apply_sms_template(template_id) {
                $.ajax({
                    url: "<?php echo getenv('CRM_URL'); ?>leads/lead/get_template?template_id=" + template_id,
                    method: "GET",
                    type: 'ajax',
                    dataType: 'html',
                    success: function(data) {
                        $('#sms_editor').html("");
                        $('#sms_editor').html(data);
                    },
                    error: function(err) {
                        $('#sms_editor').html("");
                    }
                });
            }
        </script>
        <script>
            function choose_action(data) {
                var lead_id = $(data).data("id");
                console.log($(data).val());
                $(".order_modal_id").html("#" + lead_id);
                if ($(data).val() == 410) {
                    var form = $("#order_completion_modal form").attr("action");
                    $("#order_completion_modal form").attr("action", form + "/" + lead_id)
                    $("#order_completion_modal").modal();
                } else {
                    var form = $("#order_close_modal form").attr("action");
                    $("#order_close_modal form").attr("action", form + "/" + lead_id)
                    $("#order_close_modal").modal();
                }
            }

            function apply_email_template(template_id) {
                $.ajax({
                    url: "<?php echo getenv('CRM_URL'); ?>leads/lead/get_template?template_id=" + template_id,
                    method: "GET",
                    type: 'ajax',
                    dataType: 'html',
                    success: function(data) {
                        $('#email_editor').html("");
                        $('#email_editor').val(data).trigger("change");
                    },
                    error: function(err) {
                        $('#email_editor').html("");
                    }
                });
            }

            function apply_sms_template(template_id) {
                $.ajax({
                    url: "<?php echo getenv('CRM_URL'); ?>leads/lead/get_template?template_id=" + template_id,
                    method: "GET",
                    type: 'ajax',
                    dataType: 'html',
                    success: function(data) {
                        $('#sms_editor').html("");
                        $('#sms_editor').html(data);
                    },
                    error: function(err) {
                        $('#sms_editor').html("");
                    }
                });
            }



            //

            $('#order_completion_modal,#order_close_modal').on('hidden.bs.modal', function() {
                $('[name="action_id"]').val("");
            });

            function invoice_check(input, is_last = 0) {
                var order_ref = $(input).val();
                if (order_ref == "" || order_ref == null) {
                    $(input).removeClass("bg-success");
                    $(input).addClass("bg-danger");
                    $(input).addClass("text-white");
                    return;
                }
                let is_alphapro = order_ref.match("^RV-");
                if (is_alphapro) {
                    $(input).removeClass("bg-danger");
                    $(input).addClass("bg-success");
                    $(input).addClass("text-white");

                    if (is_last == 1) {
                        swal.fire({
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            allowEnterKey: false,
                            didOpen: () => {
                                swal.enableLoading();
                                setTimeout(function() {
                                    if ($("#order_confirm_form .order_ids.bg-danger").length == 0) {
                                        $("#order_confirm_form").off();
                                        $("#order_confirm_form").submit();
                                    }
                                }, 3000);
                            }
                        });
                    }
                    return true;
                }

                $.ajax({
                    "url": "https://ontimesmartpos.net/api/ApiPos/ValidateInvoiceForCRM",
                    "method": "POST",
                    "beforeSend": function() {
                        swal.fire({
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            allowEnterKey: false,
                            didOpen: () => {
                                swal.enableLoading();
                                // setTimeout(function(){
                                //     if($("#order_confirm_form .order_ids.bg-danger").length == 0){
                                //         $("#order_confirm_form").off();
                                //         $("#order_confirm_form").submit();
                                //     }
                                //     swal.disableLoading();
                                // },3000);
                            }
                        });
                    },
                    "data": {
                        "lead_id": <?php echo $lead_details['id']; ?>,
                        "lead_custmobile": "<?php echo $lead_details['customer_mobile']; ?>",
                        "invoice_no": order_ref
                    },
                    success: function(data) {
                        console.log("ViewV2: ", data);
                        if (data.ResponseCode == 0) {
                            // $("#order_confirm_form").off();
                            // $("#order_confirm_form").submit();
                            $(input).removeClass("bg-danger");
                            $(input).addClass("bg-success");
                            $(input).addClass("text-white");
                            $("#order_confirm_form [name='pos_updated']").val(1);
                            $("#order_confirm_form [name='pos_govt_fee']").val(data.Data.govt_fee);
                            $("#order_confirm_form [name='pos_typing_fee']").val(data.Data.typing_fee);
                            $("#order_confirm_form [name='pos_Card_Amnt']").val(data.Data.Card_Amnt);
                            $("#order_confirm_form [name='pos_Disc_Amnt']").val(data.Data.Disc_Amnt);
                            $("#order_confirm_form [name='pos_Tax_Amnt']").val(data.Data.Tax_Amnt);
                            $("#order_confirm_form [name='pos_Tot_Revn']").val(data.Data.Tot_Revn);
                            $("#order_confirm_form [name='pos_ref1']").val(data.Data.Ref1);
                            $("#order_confirm_form [name='pos_ref2']").val(data.Data.Ref2);
                            $("#order_confirm_form [name='pos_Tot_Amt']").val(data.Data.Tot_Amt);
                            $("#order_confirm_form [name='pos_username']").val(data.Data.pos_username);
                        } else {
                            $(input).removeClass("bg-success");
                            $(input).addClass("bg-danger");
                            $(input).addClass("text-white");
                            swal.fire({
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                allowEnterKey: false,
                                icon: "warning",
                                title: "Something went wrong.",
                                text: data.ResponseMsg
                            });
                        }

                        if (is_last == 1) {
                            setTimeout(function() {
                                if ($("#order_confirm_form .order_ids.bg-danger").length == 0) {
                                    $("#order_confirm_form").off();
                                    $("#order_confirm_form").submit();
                                }
                            }, 3000);
                        }
                    }
                });
            }

            $("#order_confirm_form").on("submit", function(e) {
                e.preventDefault();
                var len = $("#order_confirm_form .order_ids").length - 1;
                $("#order_confirm_form .order_ids").each(function(i) {
                    invoice_check(this, (len == i) ? 1 : 0);
                });

                // let is_alphapro = order_ref.match("^RV-");
                // if (is_alphapro) {
                //     $("#order_confirm_form").off();
                //     $("#order_confirm_form").submit();
                // }                
            });

            // $("#order_completion_modal form").on("submit", function (e) {
            //     e.preventDefault();
            //     var order_ref = $(".omref").val();
            //     var lead_custmobile=<?php echo $lead_details['customer_mobile']; ?>;
            //     var lead_id=<?php echo $lead_details['id']; ?>;
            //     //alert(lead_id);
            //     let is_alphapro = order_ref.match("^RV-");
            //     if (is_alphapro) {
            //         $("#order_confirm_form").off();
            //         $("#order_confirm_form").submit();
            //     }

            //     $.ajax({
            //         "url": "https://ontimesmartpos.net/api/ApiPos/ValidateInvoiceForCRM",
            //         "method": "POST",
            //         "beforeSend": function () {
            //             swal.fire({
            //                 allowOutsideClick: false,
            //                 allowEscapeKey: false,
            //                 allowEnterKey: false,
            //                 didOpen: () => {
            //                     swal.enableLoading();
            //                 }
            //             });
            //         },
            //         "data": JSON.stringify({
            //             "lead_id": lead_id,
            //             "lead_custmobile": lead_custmobile,
            //             "invoice_no": order_ref
            //         }),
            //         success: function (data) {
            //             console.log("ViewV2: ",data);
            //             if (data.ResponseCode == 0) {
            //                 $("#order_confirm_form").off();
            //                 $("#order_confirm_form").submit();
            //             } else {
            //                 swal.fire({
            //                     allowOutsideClick: false,
            //                     allowEscapeKey: false,
            //                     allowEnterKey: false,
            //                     icon: "warning",
            //                     title: "Something went wrong.",
            //                     text: data.ResponseMsg
            //                 });
            //                 return;
            //             }
            //         }
            //     });

            // });


            $("#order_completion_modal form").on("submit", function(e) {
                e.preventDefault();

                var order_ref = $(".omref").val();
                var lead_custmobile = <?php echo $lead_details['customer_mobile']; ?>;
                var lead_id = <?php echo $lead_details['id']; ?>;

                console.log("order_ref:", order_ref);
                console.log("lead_custmobile:", lead_custmobile);
                console.log("lead_id:", lead_id);

                let is_alphapro = order_ref.match("^RV-");

                if (is_alphapro) {
                    $("#order_confirm_form").off();
                    $("#order_confirm_form").submit();
                }

                $.ajax({
                    "url": "https://ontimesmartpos.net/api/ApiPos/ValidateInvoiceForCRM",
                    "method": "POST",
                    "beforeSend": function() {
                        swal.fire({
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            allowEnterKey: false,
                            didOpen: () => {
                                swal.enableLoading();
                            }
                        });
                    },
                    "data": {
                        "lead_id": lead_id,
                        "lead_custmobile": lead_custmobile,
                        "invoice_no": order_ref
                    },
                    success: function(data) {
                        console.log("ViewV2: ", data);
                        if (data.ResponseCode == 0) {
                            $("#order_confirm_form").off();
                            $("#order_confirm_form").submit();
                        } else {
                            swal.fire({
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                allowEnterKey: false,
                                icon: "warning",
                                title: "Something went wrong.",
                                text: data.ResponseMsg
                            });
                            return;
                        }
                    }
                });
            });

            $("document").ready(function() {
                $("blockquote").remove();
            });
        </script>



        <script>
            function onChangeEves() {
                $('[name="assign_group"] option').addClass("d-none");
                $('[name="assign_to"] option:not([value=""])').addClass("d-none");
            }

            function branch_change(branch) {
                var biz = [107];
                var attest = [103];
                var val = parseInt(branch);


                $('[name="assign_group"]').val("").trigger("change");
                $('[name="assign_group"] option').addClass("d-none");
                $('[name="assign_group"] option[data-branch="' + val + '"]').removeClass("d-none");

                if ($('[name="assign_group"] option:not(.d-none)').length == 1) {
                    var val = $('[name="assign_group"] option:not(.d-none)').val();
                    $('[name="assign_group"]').val(val).trigger("change");
                }
            }

            function group_change(group) {
                $('[name="assign_to"]').val("").trigger("change");
                // alert($(this).val());
                //if ($(this).val() == "") {
                // $('[name="assign_to"] option').removeClass("d-none");
                // return;
                // }
                $('[name="assign_to"] option:not([value=""])').addClass("d-none");
                $('[name="assign_to"] option:not([value=""])[data-filter*="' + group + '"]')
                    .removeClass("d-none");

                //$('[name="assign_to"] option:not([value="3771749283"])[data-filter*="BusinessSetup"]')
                //    .addClass("d-none");
            }
            $(document).ready(function() {
                $("#eligible-form").on("submit", function(event) {
                    let eligible_val = $("#is_eligible").val();
                    event.preventDefault();
                    if (eligible_val == 1) {
                        // $('#payment_model').modal('show');
                        $('#quotation_form_modal').modal('show');
                        var lead_details_is_corporate = <?php echo json_encode($lead_details['is_corporate']); ?>;
                        if(lead_details_is_corporate == "Corporate"){
                            $('#is_corporate').val("Corporate");
                            $('#applicant_name_group').show();
                            $('#applicant_name').attr('required', true);
                            var lead_details_applicant_name = <?php echo json_encode($lead_details['applicant_name']); ?>;
                            if(lead_details_applicant_name != null){
                                $('#applicant_name').val(lead_details_applicant_name);
                            }
                        }
                        // request = $.ajax({
                        // url: "<?php echo getenv('CRM_URL'); ?>leads/lead/action_eligible/<?php echo $this->uri->segment(4); ?>",
                        // type: "post",
                        // data: $(this).serialize(),
                        // // dataType:"json"
                        // });

                        // // Callback handler that will be called on success
                        // request.done(function (response, textStatus){
                        // if (response == true ) {
                        //     console.log('true');

                        // } else {
                        //     console.log('not workss..');
                        //     // $('#fail-modal').modal('show');
                        // }
                        // });
                    } else {
                        request = $.ajax({
                            url: "<?php echo getenv('CRM_URL'); ?>leads/lead/action_eligible/<?php echo $this->uri->segment(4); ?>",
                            type: "post",
                            data: $(this).serialize(),
                            // dataType:"json"
                        });

                        // Callback handler that will be called on success
                        request.done(function(response, textStatus) {
                            console.log(textStatus);
                            console.log(response);

                            if (textStatus == 'success') {
                                location.reload();
                                // console.log('true');

                            } else {
                                console.log('not workss..');
                                // $('#fail-modal').modal('show');
                            }
                        });

                        // Callback handler that will be called on failure
                        request.fail(function(textStatus, errorThrown) {
                            // Log the error to the console
                            console.error(
                                "The following error occurred: " + textStatus, errorThrown
                            );
                        });
                    }
                });
            });

            function assign_csa(item_id, assigned_to, assigned_by) {
                if (assigned_to == "" || assigned_to == null) return false;

                console.log("item_id: " + item_id + " assigned_to: " + assigned_to + " assigned_by: " + assigned_by);
                Swal.fire({
                    title: 'Please confirm',
                    text: "If lead is assigned to CSA, it cannot be reverted.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Assign!'
                }).then((result) => {
                    if (result.isConfirmed) {

                        $.ajax({
                            url: "<?php echo getenv('CRM_URL'); ?>api/v1/assign/lead",
                            type: 'POST',
                            data: {
                                assigned_to: assigned_to,
                                lead_id: item_id,
                                assigned_by: assigned_by
                            },
                            success: function(res) {
                                console.log(res);
                                $('#o' + item_id).hide();
                                Swal.fire(
                                    'Assigned!',
                                    res,
                                    'success'
                                ).then((value) => {
                                    location.reload();
                                });
                            },
                            error: function(e) {
                                Swal.fire(
                                    'Something went wrong!',
                                    e,
                                    'error'
                                )
                            }
                        });

                    }
                });
            }

            function checkPaymentStatus(leadId, uniqueId) {
                $.ajax({
                    url: "<?php echo getenv('CRM_URL'); ?>leads/lead/card_payment_internal",
                    type: "POST",
                    contentType: "application/json",
                    data: JSON.stringify({
                        unique_id: uniqueId,
                        lead_id: leadId
                    }),
                    success: function(response) {
                        try {
                            var jsonResponse = JSON.parse(response);
                            if (jsonResponse.error) {
                                // Display error message
                                $('#modalMessage').text(jsonResponse.error).show();
                                $('#modalBody').html(""); // Clear iframe if error
                            } else {
                                // Load response into an iframe
                                var iframeHtml = `<iframe id="paymentFrame" style="width:100%; height:500px; border:none;"></iframe>`;
                                $('#modalBody').html(iframeHtml);

                                var iframe = document.getElementById('paymentFrame');
                                var iframeDoc = iframe.contentWindow.document;
                                iframeDoc.open();
                                iframeDoc.write(response);
                                iframeDoc.close();

                                $('#modalMessage').hide(); // Hide error if content loads

                                // Listen for iframe reload
                                iframe.onload = function() {
                                    console.log("Iframe reloaded. Closing modal...");
                                    $('#paymentStatusModal').modal('hide'); // Close modal
                                    location.reload(); // Reload the current page
                                };
                            }
                        } catch (e) {
                            // Handle non-JSON responses as HTML
                            var iframeHtml = `<iframe id="paymentFrame" style="width:100%; height:500px; border:none;"></iframe>`;
                            $('#modalBody').html(iframeHtml);

                            var iframe = document.getElementById('paymentFrame');
                            var iframeDoc = iframe.contentWindow.document;
                            iframeDoc.open();
                            iframeDoc.write(response);
                            iframeDoc.close();

                            $('#modalMessage').hide(); // Hide error if content loads

                            // Listen for iframe reload
                            iframe.onload = function() {
                                console.log("Iframe reloaded. Closing modal...");
                                $('#paymentStatusModal').modal('hide'); // Close modal
                                // location.reload(); // Reload the current page
                            };
                        }

                        // Show the modal
                        $('#paymentStatusModal').modal('show');
                    },
                    error: function(xhr) {
                        var errorMessage = "Error occurred while fetching payment status.";
                        if (xhr.responseText) {
                            try {
                                var errorResponse = JSON.parse(xhr.responseText);
                                if (errorResponse.error) {
                                    errorMessage = errorResponse.error;
                                }
                            } catch (e) {
                                errorMessage = "Unexpected error occurred.";
                            }
                        }
                        $('#modalMessage').text(errorMessage).show();
                        $('#modalBody').html(""); // Clear iframe if error
                        $('#paymentStatusModal').modal('show');
                    }
                });
            }

            $('#activate_pay_link').click(function(e){
                e.preventDefault();
                let action_id = $(this).data('id');
                $.ajax({
                    url: "<?php echo getenv('CRM_URL'); ?>leads/lead/activatePaylink/?action_id="+action_id,
                    type: 'get',
                    success: function (res) {
                        Swal.fire({
                            icon: "success",
                            text: "Activated Successfully"
                        }).then((value) => {
                            location.reload();
                        });
                    },
                    error: function (e) {
                        Swal.fire(
                            'Something went wrong!',
                            e.responseText,
                            'error'
                        )
                    }
                });
            });

            $('#fetch_service_amount').click(function(e){
                e.preventDefault();
                let order_id = $(this).data('id');
                $.ajax({
                    url: "https://ontimegov.com/api/v1/web/order/fetch_service_details?order_id="+order_id,
                    type: 'get',
                    success: function (res) {
                        let gov_govt_fee = res.govt_fee;
                        let gov_typing_fee = res.typing_fee;
                        let gov_other_fee = res.other_service_fee;
                        gov_typing_fee = gov_other_fee != null ?
                            parseFloat(parseFloat(gov_typing_fee) +parseFloat(gov_other_fee)) : parseFloat(gov_typing_fee);
                        let is_vat_checked = res.vat_check;
                        let is_trans_checked = res.transaction_check;

                        $('#ad_gov_fee').val(gov_govt_fee);
                        if(is_trans_checked == 1){
                            get_vat_amount();
                        }
                        $('#ad_typing_fees').val(gov_typing_fee);
                        if(is_vat_checked == 1){
                            $('#toggle').prop('checked', true);
                            get_vat_amount();
                        } else {
                            $('#toggle').prop('checked', false);
                        }
                        // $('#ad_vendor_com').val(res.other_service_fee);
                        gettot();
                        // console.log(res, 'Service Details');
                    },
                    error: function (e) {
                        Swal.fire( 'Something went wrong!', e.responseText, 'error')
                    }
                });
            });

            //$(".lead_preview").on('click', function(e) {

            $('.lead_preview').click(function(e) {
                // $('#editOrderModal').modal('show');


                // alert("Hi");
                e.preventDefault();
                var link = $(this).data("href");
                $.get(link, function(response) {
                    console.log(response);

                    $("#reassign_modal").modal('show');
                    $("#lead_preview").html(response);
                    onChangeEves();
                });
            });
            $("#email_editor").richText();

            // $("#attest_service_id").change(function(e) {
            //     e.preventDefault();
            //     var service_amount = $("#attest_service_id option:selected").data("amount");
            //     // var service_amount = 1;
            //     var bot_id = $("#attest_service_id option:selected").data("id");
            //     var serv = $(this).val();
            //     var service = $(this).val();
            //     $("[name='amount_payment']").val(service_amount);
            //     if (service_amount == 0) {
            //         $("[name='amount_payment']").removeAttr("readonly");
            //     } else $("[name='amount_payment']").attr("readonly");
            //     $(".attest_remark").html('This payment for service "' + serv + '"');
            //     $("[name='bot_id']").val(bot_id);
            //     $("[name='at_service_name']").val(service);
            // });
        </script>
        <script type="text/javascript">
            function show_popup() {
                $("#app_code").hide();
                Swal.fire({
                    title: 'Please confirm you have received cash to proceed.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#DCDCDC',
                    confirmButtonText: 'OK'
                })
            }

            function show_code() {
                var type = $("#cash_type").val();
                if (type == 'card') {
                    $("#app_code").show();
                } else {
                    $("#app_code").hide();
                }

            }

            function hide_code() {
                $("#app_code").hide();
            }
        </script>
        <script>
            $(function() {
                $("#app_code").hide();

            });

            <?php
            if ($lead_details["lead_created_by"] == 2906815795) {
            ?>
                $(document).ready(function(e) {
                    var target = $('#follow-up-id');
                    if (target.length) {
                        setTimeout(function() {
                            $('html,body').animate({
                                scrollTop: target.offset().top
                            }, 1);
                        }, 100);
                    }
                });

                $("[name='status_id']").change(function() {
                    Swal.fire({
                        icon: "info",
                        title: $("[name='status_id'] option:selected").text(),
                        text: "Please confirm to proceed to change status",
                        allowEscapeKey: false,
                        outsideClick: false,
                        showCancelButton: true
                    }).then((val) => {
                        if (val.isConfirmed) {
                            $("#action_status_form").submit();
                        }
                        $("[name='status_id']").val("");
                    });
                });
            <?php } ?>
        </script>

        <script src="<?php echo getenv('CRM_URL'); ?>global/node_modules/select2/dist/js/select2.min.js"></script>
        <link rel="stylesheet" href="<?php echo getenv('CRM_URL'); ?>global/node_modules/select2/dist/css/select2.min.css">
        <script>
            $("#branch_id").select2();

            $(document).on('select2:open', () => {
                document.querySelector('.select2-search__field').focus();
            });
        </script>
        <script type="text/javascript">
            function show_eligible_popup() {
                Swal.fire({
                    title: 'Please confirm you have received cash to proceed.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#DCDCDC',
                    confirmButtonText: 'OK'
                })
            }

            $(".reopen_leads").on("click", function(i) {
                var lead_id = <?php echo $lead_details['id']; ?>;
                swal.fire({
                    icon: "info",
                    title: "Are you sure to reopen this lead ?",
                    text: "Lead Id: " + lead_id,
                    confirmButtonText: "Yes",
                    showCancelButton: true,
                    cancelButtonText: "Cancel"
                }).then((val) => {
                    if (val.isConfirmed) {
                        $.ajax({
                            url: '<?php echo getenv('CRM_URL'); ?>leads/lead/action_leadreopen',
                            type: 'POST',
                            data: {
                                lead_id: lead_id
                            },
                            dataType: 'json', // Add this line to parse the response as JSON
                            success: function(response) {
                                console.log("Response : ", response.status);
                                if (response.status == 'success') {
                                    alert('Lead reopened Successfully, You can see the progress in timeline.');
                                    location.reload();

                                }
                                // Handle success response
                                console.log(response);
                            },
                            error: function(xhr, status, error) {
                                // Handle error response
                                console.log(error);
                            }
                        });
                    }
                });
            });

            $(".convert_leads").on("click", function(i) {
                var lead_id = <?php echo $lead_details['id']; ?>;
                swal.fire({
                    icon: "info",
                    title: "Are you sure to convert this lead ?",
                    text: "Lead Id: " + lead_id,
                    confirmButtonText: "Yes",
                    showCancelButton: true,
                    cancelButtonText: "Cancel"
                }).then((val) => {
                    if (val.isConfirmed) {
                        $.ajax({
                            url: '<?php echo base_url(); ?>leads/lead/action_leadconvert',
                            type: 'POST',
                            data: {
                                lead_id: lead_id
                            },
                            dataType: 'json', // Add this line to parse the response as JSON
                            success: function(response) {
                                console.log("Response : ", response.status);
                                if (response.status == 'success') {
                                    alert('Lead Converted Successfully, You can see the progress in timeline.');
                                    location.reload();
                                }
                                // Handle success response
                                console.log(response);
                            },
                            error: function(xhr, status, error) {
                                // Handle error response
                                console.log(error);
                            }
                        });
                    }
                });
            });

            var country_opt = $("#country_options").val();
            if(country_opt == "outsideCountry"){
                $('.task_status_dld option[value="open"], .task_status_dld option[value="completed"]').hide();
            }

            $(".country_options").on("change", function(i) {
                var value = $(this).val();
                var lead_id = <?php echo $lead_details['id']; ?>;

                $('.task_status_dld option[value="open"], .task_status_dld option[value="completed"]').show();
                $('#document_upload').removeAttr('required');
                $('#document_upload').siblings('label').find('.required').addClass('d-none');
                $('.task_status_dld').val('');

                if (value == "insideCountry") {
                    $('#document_upload').attr('required', 'required');
                    $('.task_status_dld').val('completed');
                    $('#document_upload').siblings('label').find('.required').removeClass('d-none');
                } else {
                    if(value == "outsideCountry"){
                        $('.task_status_dld').val('hold');
                        $('.task_status_dld option[value="open"], .task_status_dld option[value="completed"]').hide();
                    }
                }
                /* if (value != "") {
                    swal.fire({
                        icon: "info",
                        title: "Are you sure to confirm ?",
                        text: "Country Option is " + value,
                        confirmButtonText: "Yes",
                        showCancelButton: true,
                        cancelButtonText: "Cancel"
                    }).then((val) => {
                        if (val.isConfirmed) {
                            $.ajax({
                                url: '<?php echo getenv('CRM_URL'); ?>leads/lead/action_countryoptions',
                                type: 'POST',
                                data: {
                                    lead_id: lead_id,
                                    country_options: value,
                                },
                                dataType: 'json', // Add this line to parse the response as JSON
                                success: function(response) {
                                    console.log(response.status);
                                    if (response.status == 'success') {
                                        alert('Country Options updated Successfully, You can see the progress in timeline.');
                                        location.reload();

                                    }
                                    // Handle success response
                                    console.log(response);
                                },
                                error: function(xhr, status, error) {
                                    // Handle error response
                                    console.log(error);
                                }
                            });
                        }
                    });
                } */

            });

            $(document).ready(function() {
                $('select[name="req_sts_upt"]').on('change', function() {
                    var selectedTab = $(this).val();
                    $('#req_sts_upt .tab-pane').removeClass('d-block').addClass('d-none');
                    $('#' + selectedTab).addClass('d-block').removeClass('d-none');
                    $('#' + selectedTab + ' textarea[name="close_remarks"]').val('');

                    if (selectedTab == 'tab-faq-4') {
                        console.log('tss')
                        $('.invoice-inputs .input-group:gt(0)').remove();
                        $('.invoice-inputs .input-group input').val('');
                        $('.invoice-inputs .order_ids').val('').removeClass('bg-danger');
                    }
                });

                $('#request-status-update-tab').on('click', function() {
                    $('#req_sts_upt textarea, #req_sts_upt .form-control').val('').removeClass('bg-danger');
                    $('#req_sts_upt .tab-pane').removeClass('d-block').addClass('d-none');
                    $('select[name="req_sts_upt"]').val('');
                });

            });
        </script>
<script>
    const companyOutlets = <?= json_encode($company_outlets); ?>;
    const companyCostCenters = <?= json_encode($company_cost_centers); ?>;
    document.getElementById('cc_cost_center').addEventListener('change', function () {
        let costCenterKey = this.value;

        document.getElementById('cc_company_name').value = '';
        document.getElementById('cc_company_outlet').value = '';
        if (!costCenterKey) return;
        let costCenter = companyCostCenters.find(cc => cc.cc_key === costCenterKey);

        if (!costCenter) return;
        let companyKey = costCenter.pos_cmp_key;

        let company = companyOutlets.find(c => c.company_key === companyKey);

        if (company) {
            document.getElementById('cc_company').value = company.company_key;
            document.getElementById('cc_company_name').value = company.company_name;
            document.getElementById('cc_company_outlet').value = company.outlet_ref;
        }
    });
</script>