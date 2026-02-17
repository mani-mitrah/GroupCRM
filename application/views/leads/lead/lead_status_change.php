<style type="text/css">
    .dtp div.dtp-date,
    .dtp div.dtp-time {
        background: #2196f3 !important;
    }

    .dtp table.dtp-picker-days tr>td>a.selected {
        background: #2196f3 !important;
    }

    .dtp>.dtp-content>.dtp-date-view>header.dtp-header {
        background: #3f51b5 !important;
    }

    .dtp .p10>a {
        color: #fff !important;
    }

    .year-picker-item.active {
        color: #3f51b5 !important;
    }

    .bg-secondary {
        background-color: #051469 !important;
    }
</style>

<?php
$fake_domain = "no";
$email = $lead_details['customer_email'];
$domain = substr(strrchr($email, "@"), 1);
if ($domain == 'ontimecustomer.com') {
    $fake_domain = "yes";
}
?>
<!-- <div class="row page-titles mx-0">
    <div class="col-sm-6 p-md-0">
        <div class="welcome-text">
            <h4>Lead information</h4>
            <span>View lead details</span>
        </div>
    </div>
    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
        <a class="btn btn-primary waves-effect waves-light float-right" href="<?php echo base_url(); ?>leads/lead/manage">
            <i class="fa fa-back mr-2"></i> Back to leads
        </a>
    </div>
</div> -->
<?php
if ($this->session->flashdata('alert_cu')) {
?>
    <div class="alert alert-<?php echo $this->session->flashdata('alert_cu'); ?>">
        <?php echo $this->session->flashdata('alert_message_cu'); ?>
    </div>
<?php
}
?>
<div class="row">
    <div class="col-lg-12">
        <div class="card" id="order_details_block">
            <!-- <div class="card-header bg-secondary">
                <h5 class="card-title text-white">LEAD DETAILS</h5>
            </div> -->
            <div class="card-body pb-0">
                <div class="customer_block">
                    <form action="<?php echo base_url(); ?>leads/lead/action_dld_status/<?php echo $lead_details["id"]; ?>" method="post" id="action_status_form">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Lead Status of <b>#<?php echo $lead_details["id"]; ?></b></label>
                                    <input type="hidden" name="action_status" value="setup_status">
                                    <select name="status_id" class="form-control" autofocus required>
                                        <option value="" disabled selected default> -- Select Status --</option>
                                        <?php
                                        foreach ($lead_dld_status as $dld_stat) {
                                        ?>
                                            <option value="<?php echo $dld_stat["id"]; ?>"><?php echo $dld_stat["status_name"]; ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <input type="submit" class="btn btn-primary btn-block" value="SETUP STATUS">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>