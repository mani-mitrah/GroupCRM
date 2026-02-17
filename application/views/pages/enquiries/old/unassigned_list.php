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
<div class="row page-titles mx-0">
    <div class="col-sm-6 p-md-0">
        <div class="welcome-text">
            <h4>Al-baraha Un-assigned Enquiries</h4>
            <span>Al-Baraha Un-assigned Enquiries</span>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <table id="datatable" cellpadding="2" cellspacing="1" class="table table-striped">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Customer</th>
                      <th>Contact</th>
                      <th>Subject</th>
                      <th>Enquiry Date</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    foreach ($enquiries as $key => $value) 
                    {
                    ?>
                      <tr id="o<?php echo $value['id'];?>">
                        <td><?php echo $value['id']; ?></td>
                        <td><?php echo $value['name']; ?></td>
                        <td><?php echo "&nbsp;".$value['phone']."&nbsp;/&nbsp;".$value['email']; ?></td>
                        <td><?php 
                        $out = strlen($value['subject']) > 50 ? substr($in,0,50)."..." : $value['subject'];
                        echo $out; 
                        ?></td>
                        <td><?php echo date("F jS, Y", strtotime($value['created_date'])); ?></td>
                        <td>
                          <select onchange="javascript:assign_csa(<?php echo $value['id']; ?>,this.value,<?php echo $this->auth_user_id; ?>);">
                            <option>-- Select CSA --</option>
                            <?php
                            foreach ($baraha_csas as $key => $value) 
                            {
                              ?>
                              <option value="<?php echo $value['user_id']; ?>"><?php echo $value['first_name']; ?></option>
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
  function assign_csa(item_id,assigned_to,assigned_by) 
    {

      Swal.fire({
        title: 'Please confirm',
        text: "If enquiry is assigned to CSA, it cannot be reverted.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, Assign!'
      }).then((result) => {
        if (result.isConfirmed) {

          $.ajax({
            url: "<?php echo base_url(); ?>api/v1/assign/enquiry",
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