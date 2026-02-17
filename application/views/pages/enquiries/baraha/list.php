<style type="text/css">
  .text-ontime {
    color: #3d4465 !important;
  }

  .dataTables_wrapper .dataTables_paginate .paginate_button.previous.disabled,
  .dataTables_wrapper .dataTables_paginate .paginate_button.next.disabled {
    color: #969ba0 !important;
  }
</style>
<script type="text/javascript">
  //hide elements at first
  $(document).ready(function() {
    $('#order_details_block').hide();
    $('#no_card').show();
    //    $('#main-wrapper').attr('class','show menu-toggle');
    $('#mrn_block').hide();
  });
</script>
<div class="row page-titles mx-0">
  <div class="col-sm-6 p-md-0">
    <div class="welcome-text">
      <h4><?php echo $website_details['website_name'] . ' ENQUIRIES'; ?></h4>
      <span>Manage <?php echo $website_details['website_name'] . ' Enquiries'; ?></span>
    </div>
  </div>
  <!--div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
        <a class="btn btn-primary waves-effect waves-light float-right"
            href="<?php echo base_url(); ?>enquiries/baraha/new/">
            <i class="mdi mdi-plus mr-2"></i> NEW ENQUIRY
        </a>
    </div-->
</div>
<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-body">
        <table id="enquiry" cellpadding="2" cellspacing="1" class="table table-responsive table-striped">
          <thead>
            <tr>
              <th>#</th>
              <th>Name</th>
              <th>Contact</th>
              <th>Website</th>
              <th>Last Follow-up</th>
              <!-- <th>Last Follow-up on</th> -->
              <th>Status</th>
              <th>Enquiry Date</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php
            foreach ($enquiries as $key => $value) {
            ?>
              <tr>
                <?php $last_folowup = last_action_details($value['id']); ?>
                <td><?php echo $value['id']; ?></td>
                <td><?php echo $value['name']; ?></td>
                <td><?php echo $value['phone']; ?><br /><?php echo $value['email']; ?></td>
                <td><?php echo $value['website_name']; ?></td>
                <td><?php echo isset($last_folowup[1]) ? $last_folowup[1] : '-';echo isset($last_folowup[2]) ? '<br>'.$last_folowup[2] : '-'; ?></td>
                <!-- <td><?php echo isset($last_folowup[2]) ? $last_folowup[2] : '-'; ?></td> -->
                <td><?php echo $value['status_name']; ?></td>
                <td><?php echo date("F jS, Y", strtotime($value['created_date'])); ?></td>
                <td><a href="<?php echo site_url('enquiries/' . $value["short_name"] . '/view?id=' . $value['id']) ?>"><button type="button" class="btn btn-sm btn-primary">View</button></a></td>
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
<script>
  $("document").ready(function() {
    $("#enquiry").dataTable({
      initComplete: function() {
        this.api().columns().every(function() {
          var column = this;
          console.log(column.index());
          if (column.index() == 3 || column.index() == 5) { //skip if column 0
            $(column.header()).append("<br>")
            var select = $(
                '<select style="width: 120px;"><option value=""></option></select>')
              .appendTo($(column.header()))
              .on('change', function() {
                var val = $.fn.dataTable.util.escapeRegex(
                  $(this).val()
                );

                column
                  .search(val ? '^' + val + '$' : '', true,
                    false)
                  .draw();
              });
            column.data().unique().sort().each(function(d, j) {
              select.append('<option value="' + d + '">' + d +
                '</option>')
            });
          } //end of if

        });
      }
    });
  });
</script>