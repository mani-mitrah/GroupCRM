<div class="app-main">
    <div class="app-main__outer">
        <div class="app-main__inner">
            <div class="app-page-title">
                <div class="container fiori-container">
                    <div class="page-title-wrapper">
                        <div class="page-title-heading">
                            <div>
                                <div class="page-title-head center-elem">
                                    <span class="d-inline-block pr-2">
                                        <i class=""></i>
                                    </span>
                                    <span class="d-inline-block">Orders</span>
                                </div>
                                <div class="page-title-subheading opacity-10">
                                    <nav class="" aria-label="breadcrumb">
                                        <ol class="breadcrumb">
                                            <li class="breadcrumb-item">
                                                <a href="javascript:void(0);">
                                                    <i aria-hidden="true" class="fa fa-home"></i>
                                                </a>
                                            </li>
                                            <li class="breadcrumb-item">
                                                <a href="#">Dashboard</a>
                                            </li>
                                            <li class="breadcrumb-item">
                                                <a href="/orders/smartejari">SmartEjari</a>
                                            </li>
                                            <li class="active breadcrumb-item">
                                                <a href="javascript:void(0);">Orders</a>
                                            </li>
                                        </ol>
                                    </nav>
                                </div>
                            </div>
                        </div>
                        <div class="page-title-actions">
                            <button type="button" class="btn font-size-lg" onclick="location.hash='timeline';"
                                data-toggle="tooltip" data-placement="left" title="Timeline / History">
                                <i class="fa fa-history"></i>
                            </button>
                            <a href="/orders/smartejari">
                                <button type="button" data-toggle="tooltip" data-placement="Top" class="btn btn-dark"
                                    title="Direct to list of Orders">
                                    <i class="fa fa-list mr-2"></i> All Orders
                                </button>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="app-inner-layout chat-layout">
                <div class="app-inner-layout__wrapper chatbot">
                    <div class="app-inner-layout__content w-100">
                        <div class="tab-content container-fluid">
                            <div class="row justify-content-center mt-4">
                                <div class="col-lg-8">
                                    <div class="main-card mb-3 card element-block-example">
                                        <div class="card-header d-flex justify-content-between">
                                            <?php echo $page_title; ?> Details </div>
                                        <div class="card-body">
                                            <div class="card-body">
                                                <div class="customer_block">
                                                    <h5><strong>CUSTOMER INFORMATION</strong></h5>
                                                    <table class="table">
                                                        <tr>
                                                            <td>
                                                                <strong>Customer Name:</strong><br />
                                                                <span id="o_cus_name"
                                                                    class="text-ontime"><?php echo $order_details['first_name'] . ' ' . $order_details['last_name']; ?></span>
                                                            </td>
                                                            <td>
                                                                <strong>Mobile Number:</strong><br />
                                                                <span id="o_cus_mobile"
                                                                    class="text-ontime"><?php echo $order_details['country_code'] . ' ' . $order_details['mobile']; ?></span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <strong>Customer Email:</strong><br />
                                                                <span id="o_cus_email"
                                                                    class="text-ontime"><?php echo $order_details['email']; ?></span>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                                <div class="order_block">
                                                    <h5><strong>ORDER INFORMATION</strong></h5>
                                                    <?php
                          echo $order_details['details'];
                          ?>
                                                    <br>
                                                    <?php
                          $status = "info";
                          if ($order_details["order_status"] == 410 || $order_details["order_status"] == 106) $status = "success";
                          ?>
                                                    <span
                                                        class="text-ontime badge badge-<?php echo $status; ?> btn btn-wide mt-3"><?php echo $order_details["status_name"]; ?></span>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- <div class="d-block text-right card-footer">
                        <button class="mr-2 btn btn-link btn-sm">Cancel</button>
                        <button class="btn btn-success btn-lg">Save</button>
                      </div> -->
                                    </div>
                                </div>
                            </div>
                            <div class="row justify-content-center">
                                <!-- Follow-ups -->
                                <div class="col-lg-8 col-12 mt-4">
                                    <?php
                  if ($this->session->flashdata('alert')) {
                  ?>
                                    <div class="alert alert-<?php echo $this->session->flashdata('alert'); ?>">
                                        <?php echo $this->session->flashdata('alert_message');
                      unset($_SESSION["alert"]); ?>
                                    </div>
                                    <?php
                  }
                  ?>
                                    <?php if ($enquiry_current_status !=  305 && $enquiry_current_status !=  306) { ?>
                                    <div class="app-inner-layout__header text-white bg-night-sky br-tr br-tl">
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
                                    <?php
                    if ($order_details["order_status"] != 410 && $order_details["order_status"] != 106) {
                    ?>
                                    <div class="app-inner-layout__wrapper row-fluid no-gutters">
                                        <div class="app-inner-layout__sidebar bg-transparent card" style="">
                                            <div class="p-3 stick-to-parent" style="">
                                                <div
                                                    class="dropdown-menu nav p-0 dropdown-menu-inline dropdown-menu-rounded dropdown-menu-hover-primary">
                                                    <h6 tabindex="-1" class="pt-0 dropdown-header">Menu</h6>
                                                    <a href="#tab-faq-0" data-toggle="tab" tabindex="0"
                                                        class="mb-1 dropdown-item" aria-expanded="true">Setup Meeting
                                                    </a>
                                                    <a href="#tab-faq-1" data-toggle="tab" tabindex="0"
                                                        class="mb-1 dropdown-item show active"
                                                        aria-expanded="true">Through Email </a>
                                                    <a href="#tab-faq-2" data-toggle="tab" tabindex="0"
                                                        class="mb-1 dropdown-item show" aria-expanded="true">Through
                                                        Call</a>
                                                    <!-- <a href="#tab-faq-6" data-toggle="tab" tabindex="0" class="mb-1 dropdown-item show" aria-expanded="true">Through Payment Link</a> -->
                                                    <a href="#tab-faq-3" data-toggle="tab" tabindex="0"
                                                        class="mb-1 dropdown-item show" aria-expanded="true">Custom</a>
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
                                                    <div class="tab-pane" id="tab-faq-0">
                                                        <?php
                                if ($fake_domain == 'yes') {
                                ?>
                                                        <center class="pt-4">
                                                            CUSTOMER EMAIL IS INVALID/NOT PROVIDED. PLEASE UPDATE THE
                                                            CUSTOMER EMAIL TO USE THIS OPTION.<br /><br /><br />
                                                            <button type="button" class="btn btn-rounded btn-warning"
                                                                data-name="<?php echo $lead_details['customer_name']; ?>"
                                                                data-email="<?php echo $order_details['email']; ?>"
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
                                                            action="<?php echo base_url(); ?>orders/smartejari/action_meeting/<?php echo $_GET['code']; ?>"
                                                            method="post">
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
                                                    <div class="tab-pane show active" id="tab-faq-1">
                                                        <?php
                                if ($fake_domain == 'yes') {
                                ?>
                                                        <center class="pt-4">
                                                            CUSTOMER EMAIL IS INVALID/NOT PROVIDED. PLEASE UPDATE THE
                                                            CUSTOMER EMAIL TO USE THIS OPTION.<br /><br /><br />
                                                            <button type="button" class="btn btn-rounded btn-warning"
                                                                data-name="<?php echo $lead_details['customer_name']; ?>"
                                                                data-email="<?php echo $order_details['email']; ?>"
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
                                                            action="<?php echo base_url(); ?>orders/smartejari/action_email/<?php echo $_GET['code']; ?>"
                                                            method="post">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label>From Email&nbsp;<span
                                                                                class="text-danger required">*</span></label>
                                                                        <input type="email" class="form-control"
                                                                            placeholder="from" readonly=""
                                                                            value="<?php echo $this->auth_email; ?>"
                                                                            name="from_email">
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label>To Email (Customer)&nbsp;<span
                                                                                class="text-danger required">*</span></label>
                                                                        <input type="email" class="form-control"
                                                                            placeholder="To Email Address"
                                                                            value="<?php echo $order_details['email']; ?>"
                                                                            name="customer_email">
                                                                        <input type="hidden" name="agent_email"
                                                                            value="<?php echo $this->auth_email; ?>">
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label>Subject&nbsp;<span
                                                                                class="text-danger required">*</span></label>
                                                                        <input type="text" class="form-control"
                                                                            placeholder="from"
                                                                            value="ONTIME - Followup regarding <?php echo $lead_details['category_name']; ?> - <?php echo $lead_details['service_name']; ?>"
                                                                            name="email_subject">
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label>Choose email template</label>
                                                                        <select class="form-control" name="template_id"
                                                                            id="template_id"
                                                                            onchange="javascript:apply_email_template(this.value);">
                                                                            <option value="">-- Choose template --
                                                                            </option>
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
                                                                            name="email_message"
                                                                            id="email_editor"></textarea>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label>Remarks for your reference
                                                                            (Optional)</label>
                                                                        <textarea rows="3" class="form-control"
                                                                            name="email_remarks" id="editor"></textarea>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label>Next Contactable Date&nbsp;<span
                                                                                class="text-danger required">*</span></label>

                                                                        <input type="text" id="email_date"
                                                                            name="contactable_date" class="form-control"
                                                                            placeholder=""
                                                                            value="<?php echo date('Y-m-d H:i:s', time() + 86400); ?>">
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
                                                    <div class="tab-pane show" id="tab-faq-2">
                                                        <form
                                                            action="<?php echo base_url(); ?>orders/smartejari/action_call/<?php echo $_GET['code']; ?>"
                                                            method="post">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label>Remarks&nbsp;<span
                                                                                class="text-danger required">*</span></label>
                                                                        <textarea rows="5" class="form-control"
                                                                            name="call_remarks"
                                                                            id="call_remarks"></textarea>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label>Next Contactable Date&nbsp;<span
                                                                                class="text-danger required">*</span></label>

                                                                        <input type="text" id="call_date"
                                                                            name="contactable_date" class="form-control"
                                                                            placeholder=""
                                                                            value="<?php echo date('Y-m-d H:i:s', time() + 86400); ?>">
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
                                                    <div class="tab-pane show" id="tab-faq-6">
                                                        <?php
                                if ($fake_domain == 'yes') {
                                ?>
                                                        <center class="pt-4">
                                                            CUSTOMER EMAIL IS INVALID/NOT PROVIDED. PLEASE UPDATE THE
                                                            CUSTOMER EMAIL TO USE THIS OPTION.<br /><br /><br />
                                                            <button type="button" class="btn btn-rounded btn-warning"
                                                                data-name="<?php echo $lead_details['customer_name']; ?>"
                                                                data-email="<?php echo $order_details['email']; ?>"
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
                                                            action="<?php echo base_url(); ?>orders/smartejari/action_payment/<?php echo $_GET['code']; ?>"
                                                            method="post">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label>From Email&nbsp;<span
                                                                                class="text-danger required">*</span></label>
                                                                        <input type="email" required
                                                                            class="form-control" placeholder="from"
                                                                            readonly=""
                                                                            value="<?php echo $this->auth_email; ?>"
                                                                            name="from_email">
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label>To Email (Customer)&nbsp;<span
                                                                                class="text-danger required">*</span></label>
                                                                        <input type="email" required
                                                                            class="form-control" placeholder="from"
                                                                            readonly=""
                                                                            value="<?php echo $order_details['email']; ?>"
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
                                                                        <input type="number" required
                                                                            class="form-control" placeholder="AED"
                                                                            step="0.01" name="amount_payment">
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label>Remarks for your reference
                                                                            (Optional)</label>
                                                                        <textarea rows="3" class="form-control"
                                                                            name="email_remarks"
                                                                            id="editor2"></textarea>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label>Next Contactable Date&nbsp;<span
                                                                                class="text-danger required">*</span></label>
                                                                        <input type="text" id="email_date"
                                                                            name="contactable_date" class="form-control"
                                                                            placeholder=""
                                                                            value="<?php echo date('Y-m-d H:i:s', time() + 86400); ?>">
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
                                }
                                ?>
                                                    </div>


                                                    <div class="tab-pane show" id="tab-faq-5">
                                                        <form action="<?php echo $action_url; ?>" method="post">
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
                                                                            value="CLOSE ENQUIRY">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                    <div class="tab-pane show" id="tab-faq-3">
                                                        <form
                                                            action="<?php echo base_url(); ?>orders/smartejari/action_custom/<?php echo $_GET['code']; ?>"
                                                            method="post">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label>Remarks&nbsp;<span
                                                                                class="text-danger required">*</span></label>
                                                                        <textarea rows="5" class="form-control"
                                                                            name="custom_remarks"
                                                                            id="custom_remarks"></textarea>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label>Next Contactable Date&nbsp;<span
                                                                                class="text-danger required">*</span></label>

                                                                        <input type="text" id="custom_date"
                                                                            name="contactable_date" class="form-control"
                                                                            placeholder=""
                                                                            value="<?php echo date('Y-m-d H:i:s', time() + 86400); ?>">
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
                                                    <div class="tab-pane show" id="tab-faq-4">

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <?php
                    }
                  }
                  ?>
                                    <div class="main-card mb-3 card" id="timeline">
                                        <div class="card-body">
                                            <h5 class="card-title font-weight-bold">Timeline</h5>
                                            <div
                                                class="vertical-timeline vertical-timeline--animate vertical-timeline--one-column">
                                                <?php
                        foreach ($timeline as $key => $value) {
                          // print_r($value);
                          $class = "success";
                          if ($value["action_id"] == 104) $class = "info";
                          if ($value["action_id"] == 105) $class = "alternate";
                          if ($value["action_id"] == 106) $class = "secondary";
                          if ($value["action_id"] == 107) $class = "warning";
                          if ($value["action_id"] == 108) $class = "primary";
                          if ($value["action_id"] == 109) $class = "danger";

                        ?>
                                                <div class="vertical-timeline-item vertical-timeline-element">
                                                    <div>
                                                        <span class="vertical-timeline-element-icon bounce-in">
                                                            <i
                                                                class="badge badge-dot badge-dot-xl badge-<?php echo $class; ?>">
                                                            </i>
                                                        </span>
                                                        <div class="vertical-timeline-element-content bounce-in">
                                                            <h4 class="timeline-title">
                                                                <?php echo $value['action_name']; ?></h4>
                                                            <p><?php echo $value['remarks']; ?></p>
                                                            <span
                                                                class="vertical-timeline-element-date"><?php echo date('H:i A', strtotime($value['action_on'])) . "<br>" . date('d M Y', strtotime($value['action_on'])); ?></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php
                        }
                        ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$('#order_completion_modal,#order_close_modal').on('hidden.bs.modal', function() {
    $('[name="action_id"]').val("");
});

$("#order_confirm_form").on("submit", function(e) {
    e.preventDefault();
    var uri = "https://verify.ontime-pos.com/posinvoice/VerifyInvoice/" + $("#order_confirm_form #order_id")
        .val();
    console.log(uri);
    $.ajax({
        url: uri,
        type: "POST",
        beforeSend: function() {
            swal.fire({
                allowOutsideClick: false,
                allowEscapeKey: false,
                allowEnterKey: false,
                didOpen: () => {
                    swal.enableLoading();
                }
            });
        },
        success: function(data) {
            console.log(data);
            if (data.Status == 0) {
                swal.fire({
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    allowEnterKey: false,
                    icon: "warning",
                    title: "Invoice is not matched",
                    text: "Please put the exact Invoice number"
                });
                return;
            } else {
                $("#order_confirm_form").off();
                $("#order_confirm_form").submit();
            }
        }
    })
});

$("document").ready(function() {
    $("blockquote").remove();
});
</script>