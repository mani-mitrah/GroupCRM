<style>
    a[role="tab"].active.nav-link::after {
        top: 40px !important;
    }

    span.select2-selection.select2-selection--multiple {
        font-size: 15px;
    }

    span.select2.select2-container.select2-container--default {
        width: 100% !important;
    }
</style>

<div class="app-main">
    <div class="app-main__outer w-100">
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
                                    <span class="d-inline-block">Reports</span>
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
                                                <a href="/">Dashboard</a>
                                            </li>
                                            <li class="active breadcrumb-item">
                                                <a href="javascript:void(0);">Reports</a>
                                            </li>
                                        </ol>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="app-inner-layout app-inner-layout-page">
                <div class="app-inner-layout__wrapper">
                    <div class="app-inner-layout__content">
                        <div class="tab-content container-fluid">
                            <div class="tab-pane tabs-animation fade show active" id="manage" role="tabpanel">
                                <div class="row justify-content-center">
                                    <div class="col-lg-10">
                                        <div class="main-card mb-3 card mt-4">
                                            <div>
                                                <div class="row justify-content-end mt-3">
                                                    <div class="col-lg-12">
                                                        <form action="" method="post" id="pcr_date_form" class="d-inline-flex w-100">
                                                            <div class="col-lg-4">
                                                                <div class="form-group mr-2">
                                                                    <label for="" class="w-100">Report Type</label>
                                                                    <select class="form-control" name="report_type" id="report_type" required>
                                                                        <?php if (!isset($request)) { ?>
                                                                            <option value="" default disabled selected>-- Select Report Type --</option>
                                                                        <?php } ?>
                                                                        <option value="processing" <?php if (isset($request)) {
                                                                                                        if ($request["report_type"] == "processing") {
                                                                                                            echo "selected";
                                                                                                        }
                                                                                                    } ?>>Processing</option>
                                                                        <option value="completed" <?php if (isset($request)) {
                                                                                                        if ($request["report_type"] == "completed") {
                                                                                                            echo "selected";
                                                                                                        }
                                                                                                    } ?>>Completed</option>
                                                                        <option value="disqualified" <?php if (isset($request)) {
                                                                                                            if ($request["report_type"] == "disqualified") {
                                                                                                                echo "selected";
                                                                                                            }
                                                                                                        } ?>>Disqualified</option>

                                                                        <option value="all" <?php if (isset($request)) {
                                                                                                        if ($request["report_type"] == "all") {
                                                                                                            echo "selected";
                                                                                                        }
                                                                                                    } ?>>All</option>
                                                                         <!-- <option value="first_assigned" <?php if (isset($request)) {
                                                                                                        if ($request["report_type"] == "first_assigned") {
                                                                                                           // echo "selected";
                                                                                                        }
                                                                                                    } ?>>First Assigned</option>
                                                                        <option value="completed_other_leads" <?php if (isset($request)) {
                                                                                                        if ($request["report_type"] == "completed_other_leads") {
                                                                                                            //echo "selected";
                                                                                                        }
                                                                                                    } ?>>Completed other leads</option> -->
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-2">
                                                                <div class="form-group mr-2">
                                                                    <label for="" class="w-100">Groups</label>
                                                                    <input type="hidden" name="group_name" class="form-control" id="group_name" value="<?php echo $request['group_name']; ?>"> 
                                                                    <select class="form-control" name="branch_id" id="branches">
                                                                        <option value="">-- Select Group --</option>
                                                                        <?php 
                                                                        if($this->auth_user_id=='2233119808'||$this->auth_user_id=='1143711453' || $this->auth_user_id == '188880683' || $this->auth_user_id == '895881848'){ ?>
                                                                          <option value="all"
                                                                                <?php if (isset($request)) {
                                                                                    if (isset($request["group_name"])) {
                                                                                        if ($request["group_name"] == 'all') {
                                                                                            echo "selected";
                                                                                        }
                                                                                    }
                                                                                }?>
                                                                            >All</option>
                                                                        <?php }
                                                                        /* foreach ($branches as $branch) {
                                                                            $selected = "";
                                                                            if (isset($request)) {
                                                                                if (isset($request["branch_id"])) {
                                                                                    if ($request["branch_id"] == $branch["branch_name"] && $request["group_name"] == $branch["group_name"]) {
                                                                                        $selected = "selected";
                                                                                    }
                                                                                }
                                                                            }
                                                                            echo "<option value='" . $branch["group_name"] . "' " . $selected . ">" . $branch["branch_name"] ." - ".  $branch['group_name']. "</option>";
                                                                        } */
                                                                        foreach ($groups as $group) {
                                                                            $selected = "";
                                                                            if (isset($request)) {
                                                                                if (isset($request["group_name"])) {
                                                                                    if ($request["group_name"] == $group["group_name"] && $request["group_name"] == $group["group_name"]) {
                                                                                        $selected = "selected";
                                                                                    }
                                                                                }
                                                                            }
                                                                            echo "<option value='" . $group["group_name"] . "' " . $selected . ">" . $group["group_name"] . "</option>";
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-2">
                                                                <div class="form-group">
                                                                    <label for="">From Date:</label>
                                                                    <input type="date" name="from_date" class="form-control" id="from_date"     value="<?php if (isset($request)) { if (isset($request["from_date"])) { echo $request["from_date"]; } } ?>" aria-describedby="helpId" placeholder="" max="<?php echo date('Y-m-d'); ?>">
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-2">
                                                                <div class="form-group">
                                                                    <label for="">To Date:</label>
                                                                    <input type="date" name="to_date" class="form-control" id="to_date" value="<?php if (isset($request)) { if (isset($request["to_date"])) { 
                                                                        echo $request["to_date"]; } } ?>" aria-describedby="helpId" placeholder="" max="<?php echo date('Y-m-d'); ?>">
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-2">
                                                                <div class="form-group">
                                                                    <label for="">Action:</label>
                                                                    <button type="submit" class="btn btn-primary form-control" name="action" value="submit"
                                                                    style="background-image: linear-gradient(140deg, #3641b6 -30%, #00287c 90%) !important;
                                                                        background-color: #3641b6 !important;
                                                                        border-color: #3641b6 !important;
                                                                        color: white !important; box-shadow: none !important;
                                                                        transition: none !important;">Apply</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body pt-0">
                                                <table style="width: 100%;" id="dTtable" class="table dTtable table-hover table-striped table-bordered table-responsive">
                                                    <thead>
                                                        <tr>
                                                         
                                                            <th>Main Lead</th>
                                                            <th>Sub lead</th>
                                                            <th>Customer Name</th>
                                                            <th>Source</th>
                                                            <th>Created_by</th>
                                                            <th>Created Time</th>
                                                            <th>Created Dep</th>
                                                            <th>Created Branch</th>
                                                            <th>First Assigned to</th>
                                                            <th>First Assigned at</th>
                                                            <th>Assigned to</th>
                                                            <th>Assigned time</th>
                                                            <th>Assigned dep</th>
                                                            <th>Assigned branch</th>
                                                            <th>Assigned - Created</th>
                                                            <th>Completed by</th>
                                                            <th>Completed dep</th>
                                                            <th>Completed Branch</th>
                                                            <th>Completed - Assigned</th>
                                                            <th>Completed Time</th>
                                                            <th>Duration</th>
                                                            <th>Description</th>
                                                            
                                                            <th>Payment Recept No</th>
                                                            
                                                            <th>Invoice</th>
                                                            <th>Status</th>
                                                            <th>Remarks</th>
                                                            <th>Req type</th>
                                                            <th>Govt. Fee</th>
                                                            <th>Typing Fee</th>
                                                            
                                                            <th>Final Collected Amount</th>
                                                            
                                                            <th>POS_GovtFees</th>
                                                            <th>POS_TypeFee</th>
                                                            <th>POS_Card_Amnt</th>
                                                            <th>POS_Disc_Amnt</th>
                                                            <th>POS_Tax_Amnt</th>
                                                            <th>POS_Tot_Amt</th>
                                                            <th>POS_Tot_Revn</th>
                                                            <th>POS_CreatedBy</th>

                                                            <th>Disqualified Remarks</th>
                                                            <th>Disqualified Date</th>
                                                            <th>Disqualified By</th>
                                                            <th>Disqualified Dep</th>
                                                            <th>Disqualified Branch</th>
                                                            <th>Last action date</th>

                                                            <th>Zoho Lead Source</th>
                                                            <th>Zoho CRM Lead Id</th>
                                                            <th>Zoho Ad Campaign</th>
                                                            <th>Zoho Campaign Id</th>
                                                            <th>Zoho Lead Created By</th>
                                                            <th>Zoho Chat Id</th>
                                                            <th>Lead Channel</th>
                                                            <th>Lead From</th>

                                                            <th>Alpha Pro LPO Amount</th>
                                                            <th>Alpha Pro Approx. Profit</th>
                                                            <th>Is Corporate</th>
                                                            <th>Applicant Name</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>

                                                             <?php
                                                        if(!empty($reports)) {
                                                            foreach ($reports as $report) {
                                                                // echo "<pre>";
                                                                // print_r($report);
                                                                // echo "</pre>";
                                                                // exit();
                                                        ?>
                                                                <tr>
                                                                    <td><a target="_new" href=/leads/lead/view/<?php echo $report["main_lead"]; ?>><?php echo $report["main_lead"]; ?></a></td>
                                                                    <td>
                                                                        <?php 
                                                                        if($report["sub_lead"] != 0) {
                                                                            echo "SUB-".$report["sub_lead"]; 
                                                                        }
                                                                        else echo "-";
                                                                        ?>
                                                                    </td>
                                                                    <td><?php echo $report["customer_name"]; ?></td>
                                                                    <td><?php echo $report["SOURCE"]; ?></td>
                                                                    <td><?php echo $report["created_by"]; ?></td>
                                                                    <td><?php echo $report["created_time"]; ?></td>
                                                                    <td><?php echo $report["created_dep"]; ?></td>
                                                                    <td><?php echo $report["created_branch"]; ?></td>
                                                                    <td><?php echo $report["first_assigned_to"]; ?></td>
                                                                    <td><?php echo $report["first_assigned_at"]; ?></td>
                                                                    <td><?php echo $report["assigned_to"]; ?></td>
                                                                    <td><?php echo $report["Assigned_time"]; ?></td>
                                                                    <td><?php echo $report["assigned_dep"]; ?></td>
                                                                    <td><?php echo $report["assigned_branch"]; ?></td>
                                                                    <?php 
                                                                    $created_time = new DateTime($report["created_time"]);
                                                                    $assigned_time = new DateTime($report["Assigned_time"]);
                                                                    
                                                                    $date_diff = $created_time->diff($assigned_time);
                                                                    
                                                                    ?>
                                                                    <td><?php echo $date_diff->format('%a');?></td>
                                                                    <td><?php echo $report["completed_by"]; ?></td>
                                                                    <td><?php echo $report["completed_dep"]; ?></td>
                                                                    <td><?php echo $report["completed_branch"]; ?></td>
                                                                    <?php 
                                                                    $assigned_time = new DateTime($report["Assigned_time"]);
                                                                    $completed_time = new DateTime($report["completed_time"]);
                                                                    
                                                                    $date_diff1 = $assigned_time->diff($completed_time);
                                                                    
                                                                    
                                                                    ?>
                                                                    <td>
                                                                    <?php  echo $date_diff1->format('%a'); ?>
                                                                    </td>
                                                                    <td><?php echo $report["completed_time"]; ?></td>
                                                                    <?php 
                                                                    $created_time = new DateTime($report["created_time"]);
                                                                    $completed_time = new DateTime($report["completed_time"]);
                                                                    
                                                                    $date_diff2 = $created_time->diff($completed_time);
                                                                    
                                                                    
                                                                    ?>

                                                                    <td><?php //echo $report["Duration"]; ?><?php  echo $date_diff2->format('%a'); ?></td>
                                                                    <td><?php echo $report["Description"]; ?></td>
                                                                  
                                                                    <td><?php echo implode(',',array_unique(explode(',', $report["pmt_rec_no"]))); ?></td>
                                                                    
                                                                    <td><?php echo $report["invoice"]; ?></td>
                                                                    <td><?php echo $report["status"]; ?></td>
                                                                    <td><?php echo $report["remark"]; ?></td>
                                                                    <td><?php echo $report["Req_type"]; ?></td>
                                                                    <td><?php echo $report["govt_fee"]; ?></td>
                                                                    <td><?php echo $report["typing_fee"]; ?></td>

                                                                    <td><?php
                                                                        if($report["govt_fee"] && $report["typing_fee"]){

                                                                            echo ( $report["govt_fee"]+$report["typing_fee"] );

                                                                        }else{

                                                                             echo $report["final_collected_amnt"]? "AED ".$report["final_collected_amnt"]: "AED 0"; 

                                                                        }

                                                                    

                                                                 ?></td>
                                                                    

                                                                    <td><?php echo $report["pos_GovtFees"]; ?></td>
                                                                    <td><?php echo $report["pos_TypeFee"]; ?></td>
                                                                    <td><?php echo $report["pos_Card_Amnt"]; ?></td>
                                                                    <td><?php echo $report["pos_Disc_Amnt"]; ?></td>
                                                                    <td><?php echo $report["pos_Tax_Amnt"]; ?></td>
                                                                    <td><?php echo $report["pos_Tot_Amt"]; ?></td>
                                                                    <td><?php echo $report["pos_Tot_Revn"]; ?></td>
                                                                    <td><?php echo $report["pos_CreatedBy"]; ?></td>

                                                                    <td><?php echo $report["disqualified_remarks"]; ?></td>
                                                                    <td><?php echo $report["disqualified_date"]; ?></td>
                                                                    <td><?php echo $report["disqualified_by"]; ?></td>
                                                                    <td><?php echo $report["disqualified_dep"]; ?></td>
                                                                    <td><?php echo $report["disqualified_branch"]; ?></td>
                                                                    <td><?php echo $report["last_action_date"]; ?></td>

                                                                    <td><?php echo $report["lead_source"]; ?></td>
                                                                    <td><?php echo $report["lead_zoho_id"]; ?></td>
                                                                    <td><?php echo $report["lead_ad_campaign"]; ?></td>
                                                                    <td><?php echo $report["lead_ad_campaign_id"]; ?></td>
                                                                    <td><?php echo $report["lead_zoho_created_by"]; ?></td>
                                                                    <td><?php echo $report["lead_zoho_chat_id"]; ?></td>
                                                                    <td><?php echo $report["lead_zoho_channel"]; ?></td>
                                                                    <td><?php echo $report["lead_from"]; ?></td>
                                                                   
                                                                    <td><?php echo $report["alpha_pro_lpo_amount"]; ?></td>
                                                                    <td><?php echo $report["alpha_pro_approx_profit"]; ?></td>
                                                                    <td><?php echo $report["is_corporate"]; ?></td>
                                                                    <td><?php echo $report["applicant_name"]; ?></td>
                                                                </tr>
                                                        <?php }
                                                        } ?>
                                                         
                                                    </tbody>
                                                    <?php 
                                                     //   echo "<pre>";
                                                      //  print_r($reports)

                                                    ?>
                                                </table>
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
</div>

<script src="<?php echo base_url(); ?>global/vendor/datatables/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(); ?>assets_new/node_modules/datatables.net-buttons/js/dataTables.buttons.min.js">
</script>
<script src="<?php echo base_url(); ?>assets_new/node_modules/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js">
</script>
<script src="<?php echo base_url(); ?>assets_new/node_modules/datatables.net-buttons/js/buttons.html5.min.js"></script>
<script src="<?php echo base_url(); ?>assets_new/node_modules/datatables-export-document/dataTables.export.js"></script>
<script src="<?php echo base_url(); ?>assets_new/node_modules/datatables.net-responsive/js/dataTables.responsive.min.js">
</script>
<script src="<?php echo base_url(); ?>assets_new/node_modules/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js">
</script>
<link rel="stylesheet" href="<?php echo base_url(); ?>assets_new/node_modules/datatables.net-responsive-bs4/css/responsive.bootstrap4.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>assets_new/node_modules/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css">
<script src="<?php echo base_url(); ?>global/vendor/select2/js/select2.full.min.js"></script>
<link rel="stylesheet" href="<?php echo base_url(); ?>global/vendor/select2/css/select2.min.css">
<script>
    $('#branches').on('change', function(){
        var branch_name = $("#branches option:selected").val();
        var branches = $("#branches option:selected").text();
        // var group_name = branches.replace(branch_name+' - ', '' );  //branches.split(' - ')[1];
        $('#group_name').val(branches);
    })

    window.dTtable = $("#dTtable").dataTable({

        order: [
            [0, 'asc']
        ],
        dom: '<"row"<"col"B><"col"l><"col"f>>rt<"row"<"col"i><"col my-3"p>>',
        // responsive: true,
        buttons: [{
                extend: 'csv',
                text: "Export as CSV",
                exportOptions: {
                    format: {
                        header: function(data, column, row) {
                            // console.log('header column :' + data);
                            return data.replace(/<br><select>.*lect>/gm, "").trim();
                        }
                    }
                },
                attr: {
                    style: 'margin-right: 10px; border-radius: 3px;' // Add inline margin
                }

            },
            {
                text: 'Clear',
                action: function(e, dt, node, config) {
                    location.href = "/reports/statreport";
                }
            }
        ],
        // initComplete: function() {
        //     this.api().columns().every(function() {
        //         var column = this;
        //         console.log(column.index());
        //         if (column.index() == 4 || column.index() == 5 || column.index() == 7) { //skip if column 0
        //             $(column.header()).append("<br>")
        //             var select = $(
        //                     '<select class="form-control"><option value=""></option></select>')
        //                 .appendTo($(column.header()))
        //                 .on('change', function() {
        //                     var val = $.fn.dataTable.util.escapeRegex(
        //                         $(this).val()
        //                     );

        //                     column
        //                         .search(val ? '^' + val + '$' : '', true,
        //                             false)
        //                         .draw();
        //                 });
        //             column.data().unique().sort().each(function(d, j) {
        //                 select.append('<option value="' + d + '">' + d +
        //                     '</option>')
        //             });
        //         } //end of if

        //     });
        // }
    });
</script>