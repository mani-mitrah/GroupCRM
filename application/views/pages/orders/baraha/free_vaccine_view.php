<div class="app-main">
  <div class="app-main__outer">
    <div class="app-main__inner p-0">
      <div class="app-page-title">
        <div class="container fiori-container">
          <div class="page-title-wrapper">
            <div class="page-title-heading">
              <div>
                Free Vaccine Order
                <div class="page-title-subheading">Details of Examinee and Appointment Information.</div>
              </div>
            </div>
            <div class="page-title-actions">
              <div class="d-inline-block dropdown">
                <a href="<?php echo base_url(); ?>orders/baraha/vaccine">
                  <button type="button" class="btn btn-gradient-primary btn-shadow btn-wide">
                    <span class="btn-icon-wrapper pr-1 opacity-7">
                      <i class="fa fa-list"></i>
                    </span>
                    All Vaccine Orders
                  </button>
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="fiori-container container">
        <div class="app-inner-layout chat-layout justify-content-center mt-5">
          <div class="card">
            <div class="card-body">
              <?php if ($this->session->flashdata('alert')) { ?>
                <div class="alert alert-<?php echo $this->session->flashdata('alert'); ?>">
                  <?php echo $this->session->flashdata('alert_message'); ?>
                </div>
              <?php } ?>
              <div class="customer_block">
                <h5><strong>EXAMINEE INFORMATION</strong></h5>
                <table class="table">
                  <tr>
                    <td  style="padding: 10px; width: 50%; vertical-align: top; text-align: left;" >
                      <strong>Examinee Name:</strong><br />
                      <?php echo $order_details[0]['name']; ?>
                    </td>
                    <td>
                      <strong>Mobile Number:</strong><br />
                      <?php echo $order_details[0]['mobile']; ?>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <strong>Email Address:</strong><br />
                      <?php echo $order_details[0]['email']; ?>
                    </td>
                    <td>
                      <strong>Residential Address:</strong><br />
                      <?php echo $order_details[0]['street']; ?>, <?php echo $order_details[0]['area']; ?>, <?php echo $order_details[0]['region']; ?>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <strong>Date of birth:</strong><br />
                      <?php echo date('d M Y', strtotime($order_details[0]['dob'])); ?>
                    </td>
                    <td>
                      <strong>Gender:</strong><br />
                      <?php echo $order_details[0]['gender']; ?>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <strong>Emirates Id:</strong><br />
                      <?php echo ($order_details[0]['emirates_id'] == '') ? '--' : $order_details[0]['emirates_id']; ?>
                    </td>
                    <td>
                      <strong>Passport Number:</strong><br />
                      <?php echo ($order_details[0]['passport_no'] == '') ? '--' : $order_details[0]['passport_no']; ?>
                    </td>
                  </tr>
                </table>
              </div>
              <div class="appt_block">
                <h5><strong>APPOINTMENT INFORMATION</strong></h5>
                <table class="table">
                  <tr>
                    <td>
                      <strong>Appointment Date:</strong><br />
                      <?php echo date('d M Y', strtotime($order_details[0]['booked_date'])); ?>
                    </td>
                    <td>
                      <strong>Slot Timings:</strong><br />
                      <?php echo $order_details[0]['slot_timings']; ?><br />
                      <?php echo ($order_details[0]['slot_timings'] == 1) ? 'Forenoon' : 'Afternoon'; ?>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <strong>Is report required?:</strong><br />
                      <?php echo ($order_details[0]['report'] == 0) ? 'NO' : 'YES'; ?>
                    </td>
                    <td>
                      <strong>Passport Number:</strong><br />
                      <?php echo ($order_details[0]['passport_no'] == '') ? '--' : $order_details[0]['passport_no']; ?>
                    </td>
                  </tr>
                </table>
              </div>
              <div class="order_block">
                <h5><strong>BOOKED BY</strong></h5>
                <table class="table">
                  <tr>
                    <td>
                      <strong>Name:</strong><br />
                      <?php echo $order_details[0]['customer_name']; ?>
                    </td>
                    <td>
                      <strong>Mobile Number:</strong><br />
                      <?php echo $order_details[0]['customer_mobile']; ?>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <strong>Email Address:</strong><br />
                      <?php echo $order_details[0]['customer_email']; ?>
                    </td>
                    <td>

                    </td>
                  </tr>
                </table>
              </div>

              <?php
              if ($order_data['pcr_status'] == 0) {
              ?>
                <div id="attachments_block">
                  <form action="<?php echo base_url(); ?>orders/baraha/pcr_view?code=<?php echo $order_data['']; ?>">

                  </form>
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