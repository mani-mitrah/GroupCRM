<style>
    a[role="tab"].active.nav-link::after {
        top: 38px;
    }

    span.select2.select2-container.select2-container--default {
        border: 1px solid #ced4da;
        border-radius: 3px;
        padding: 10px 5px 4px 5px;
    }
</style>
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
                                    <span class="d-inline-block">Tasks</span>
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
                                                <a href="<?php echo base_url(); ?>">Dashboard</a>
                                            </li>
                                            <li class="active breadcrumb-item">
                                                <a href="javascript:void(0);">Manage Tasks</a>
                                            </li>
                                        </ol>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="app-inner-bar">
                    <div class="fiori-container" style="width:100%;align-content:center;">
                        <div class="inner-bar-center">

                            <ul class="nav nav_tabs">
                                <li class="nav-item">
                                    <a role="tab" data-toggle="tab" class="nav-link active" href="#DLDTasks">
                                        <button class="btn"> DLD </button>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a role="tab" data-toggle="tab" class="nav-link" href="#visaTasks">
                                        <button class="btn"> Visa Processing </button>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a role="tab" data-toggle="tab" class="nav-link" href="#eidTasks">
                                        <button class="btn"> EID </button>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a role="tab" data-toggle="tab" class="nav-link" href="#blockTasks">
                                        <button class="btn"> Block and Unblock </button>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a role="tab" data-toggle="tab" class="nav-link" href="#insuranceTasks">
                                        <button class="btn"> Insurance </button>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a role="tab" data-toggle="tab" class="nav-link" href="#valuationTasks">
                                        <button class="btn"> Valuation </button>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a role="tab" data-toggle="tab" class="nav-link" href="#passportTasks">
                                        <button class="btn">Passport Update in Taskeen</button>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a role="tab" data-toggle="tab" class="nav-link" href="#titleTasks">
                                        <button class="btn">New Title Deed</button>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a role="tab" data-toggle="tab" class="nav-link" href="#translationTasks">
                                        <button class="btn">Translation</button>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a role="tab" data-toggle="tab" class="nav-link" href="#attestationTasks">
                                        <button class="btn">Attestation</button>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a role="tab" data-toggle="tab" class="nav-link" href="#shippingTasks">
                                        <button class="btn">Shipping</button>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a role="tab" data-toggle="tab" class="nav-link" href="#visaCancelTasks">
                                        <button class="btn">Visa cancellation</button>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a role="tab" data-toggle="tab" class="nav-link" href="#gdrfaTasks">
                                        <button class="btn">Update GDRFA</button>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a role="tab" data-toggle="tab" class="nav-link" href="#eidReplacementTasks">
                                        <button class="btn">Replacement EID</button>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a role="tab" data-toggle="tab" class="nav-link" href="#updateIcpTasks">
                                        <button class="btn">Update ICP</button>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a role="tab" data-toggle="tab" class="nav-link" href="#visaEntryPermitTasks">
                                        <button class="btn">Visa Entry Permit</button>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="mt-3 mr-3 ml-3">
                    <?php if ($this->session->flashdata('alert')) { ?>
                        <div class="p-2 alert alert-<?php echo $this->session->flashdata('alert'); ?>">
                            <?php echo $this->session->flashdata('alert_message'); ?>
                        </div>
                    <?php } ?>
                </div>
                <div class="app-inner-layout app-inner-layout-page">
                    <div class="app-inner-layout__wrapper">
                        <div class="app-inner-layout__content">
                            <div class="tab-content container-fluid">
                                <div class="tab-pane tabs-animation fade show active" id="DLDTasks" role="tabpanel">
                                    <div class="row justify-content-center">
                                        <div class="col-lg-12">
                                            <div class="main-card mb-3 card mt-4">
                                                <div class="card-body table-responsive">
                                                    <table class="table table-hover table-striped table-bordered w-100" id="created_dTtable">
                                                        <thead>
                                                            <tr>
                                                                <th>Sub Lead ID</th>
                                                                <th>Main Lead ID</th>
                                                                <th>Customer</th>
                                                                <th>Assigned To</th>
                                                                <th>Task Created At</th>
                                                                <th>service Name</th>
                                                                <!-- <th>Group</th> -->
                                                                <th>Lead Status</th>
                                                                <th>Task Status</th>
                                                                <th>SLA Violated</th>
                                                                <th>Actions</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php foreach ($tasks as $key => $value) {
                                                                if ($value['service_id'] == 95) { 
                                                                    $badge_clr = $value['task_status'] == 'completed'?'success':($value['task_status'] == 'open'?'primary':'secondary');
                                                                ?>
                                                                <tr id="o<?php echo $value['id']; ?>">
                                                                    <td><?php echo $value['lead_id']; ?></td>
                                                                    <td><?php echo $value['lead_parent_id']; ?></td>
                                                                    <td>
                                                                        <?php echo '<strong>' . $value['customer_name'] . '</strong><br />' . $value['customer_mobile'] . '<br />' . $value['customer_email']; ?></a>
                                                                    </td>
                                                                    <td><?php echo $value['assigned_user']; ?><br><?php echo $value['assigned_email']; ?></td>
                                                                    <td><?php echo date('d M Y H:i A', strtotime($value['task_created_at']));?></td>
                                                                    <td><?php echo $value['service_name']; ?></td>
                                                                    <!-- <td><?php echo $value['group_name']; ?></td> -->
                                                                    <td><?php echo $value['status_name']; ?></td>
                                                                    <td><span class="badge badge-<?php echo $badge_clr;?>"><?php echo $value['task_status']; ?></span></td>
                                                                    <td><span class="badge badge-<?php echo $value['sla_violated']=='Yes'?'danger':'secondary';?>"><?php echo $value['sla_violated']; ?></span></td>
                                                                    <td>
                                                                        <a href="<?php echo base_url(); ?>leads/lead/view/<?php echo $value['lead_parent_id']; ?>" class="btn btn-sm btn-primary">View</a>
                                                                    </td>
                                                                </tr>
                                                            <?php }} ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane tabs-animation" id="visaTasks" role="tabpanel">
                                    <div class="row justify-content-center">
                                        <div class="col-lg-12">
                                            <div class="main-card mb-3 card mt-4">
                                                <div class="card-body table-responsive">
                                                    <table class="table table-hover table-striped table-bordered w-100" id="created_dTtable">
                                                        <thead>
                                                            <tr>
                                                                <th>Sub Lead ID</th>
                                                                <th>Main Lead ID</th>
                                                                <th>Customer</th>
                                                                <th>Assigned To</th>
                                                                <th>Task Created At</th>
                                                                <th>service Name</th>
                                                                <th>Lead Status</th>
                                                                <th>visa Status</th>
                                                                <th>Pre-Approval Status</th>
                                                                <th>Task Status</th>
                                                                <th>SLA Violated</th>
                                                                <th>Actions</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php foreach ($tasks as $key => $value) {
                                                                if ($value['service_id'] == 93) { 
                                                                    $badge_clr = $value['task_status'] == 'completed'?'success':($value['task_status'] == 'open'?'primary':'secondary');
                                                                    ?>
                                                                <tr id="o<?php echo $value['id']; ?>">
                                                                    <td><?php echo $value['lead_id']; ?></td>
                                                                    <td><?php echo $value['lead_parent_id']; ?></td>
                                                                    <td>
                                                                        <?php echo '<strong>' . $value['customer_name'] . '</strong><br />' . $value['customer_mobile'] . '<br />' . $value['customer_email']; ?></a>
                                                                    </td>
                                                                    <td><?php echo $value['assigned_user']; ?></td>
                                                                    <td><?php echo date('d M Y H:i A', strtotime($value['task_created_at']));?></td>
                                                                    <td><?php echo $value['service_name']; ?></td>
                                                                    <td><?php echo $value['status_name']; ?></td>
                                                                    <td><?php echo $value['visa_status']; ?></td>
                                                                    <td><?php echo $value['visa_status'] == 6 ?$value['pre_approval_status']:''; ?></td>
                                                                    <td><span class="badge badge-<?php echo $badge_clr;?>"><?php echo $value['task_status']; ?></span></td>
                                                                    <td><span class="badge badge-<?php echo $value['sla_violated']=='Yes'?'danger':'secondary';?>"><?php echo $value['sla_violated']; ?></span></td>
                                                                    <td>
                                                                        <a href="<?php echo base_url(); ?>leads/lead/view/<?php echo $value['lead_parent_id']; ?>" class="btn btn-sm btn-primary">View</a>
                                                                    </td>
                                                                </tr>
                                                            <?php }} ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane tabs-animation" id="eidTasks" role="tabpanel">
                                    <div class="row justify-content-center">
                                        <div class="col-lg-12">
                                            <div class="main-card mb-3 card mt-4">
                                                <div class="card-body table-responsive">
                                                    <table class="table table-hover table-striped table-bordered w-100" id="created_dTtable">
                                                        <thead>
                                                            <tr>
                                                                <th>Sub Lead ID</th>
                                                                <th>Main Lead ID</th>
                                                                <th>Customer</th>
                                                                <th>Assigned To</th>
                                                                <th>Task Created At</th>
                                                                <th>service Name</th>
                                                                <th>Lead Status</th>
                                                                <th>EID Status</th>
                                                                <th>Task Status</th>
                                                                <th>SLA Violated</th>
                                                                <th>Actions</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php foreach ($tasks as $key => $value) {
                                                                if ($value['service_id'] == 2) { 
                                                                    $badge_clr = $value['task_status'] == 'completed'?'success':($value['task_status'] == 'open'?'primary':'secondary');
                                                                    ?>
                                                                <tr id="o<?php echo $value['id']; ?>">
                                                                    <td><?php echo $value['lead_id']; ?></td>
                                                                    <td><?php echo $value['lead_parent_id']; ?></td>
                                                                    <td>
                                                                        <?php echo '<strong>' . $value['customer_name'] . '</strong><br />' . $value['customer_mobile'] . '<br />' . $value['customer_email']; ?></a>
                                                                    </td>
                                                                    <td><?php echo $value['assigned_user']; ?></td>
                                                                    <td><?php echo date('d M Y H:i A', strtotime($value['task_created_at']));?></td>
                                                                    <td><?php echo $value['service_name']; ?></td>
                                                                    <td><?php echo $value['status_name']; ?></td>
                                                                    <td><?php echo $value['eid_status'] == 9?'Outside Country':($value['eid_status'] == 10?'Finger Print':'Submitted');?></td>
                                                                    <td><span class="badge badge-<?php echo $badge_clr;?>"><?php echo $value['task_status']; ?></span></td>
                                                                    <td><span class="badge badge-<?php echo $value['sla_violated']=='Yes'?'danger':'secondary';?>"><?php echo $value['sla_violated']; ?></span></td>
                                                                    <td>
                                                                        <a href="<?php echo base_url(); ?>leads/lead/view/<?php echo $value['lead_parent_id']; ?>" class="btn btn-sm btn-primary">View</a>
                                                                    </td>
                                                                </tr>
                                                            <?php }} ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane tabs-animation fade show" id="blockTasks" role="tabpanel">
                                    <div class="row justify-content-center">
                                        <div class="col-lg-12">
                                            <div class="main-card mb-3 card mt-4">
                                                <div class="card-body table-responsive">
                                                    <table class="table table-hover table-striped table-bordered w-100" id="created_dTtable">
                                                        <thead>
                                                            <tr>
                                                                <th>Sub Lead ID</th>
                                                                <th>Main Lead ID</th>
                                                                <th>Customer</th>
                                                                <th>Assigned To</th>
                                                                <th>Task Created At</th>
                                                                <th>service Name</th>
                                                                <!-- <th>Group</th> -->
                                                                <th>Lead Status</th>
                                                                <th>Task Status</th>
                                                                <th>Category</th>
                                                                <th>SLA Violated</th>
                                                                <th>Actions</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php foreach ($tasks as $key => $value) {
                                                                if ($value['service_id'] == 2303) { 
                                                                    $badge_clr = $value['task_status'] == 'completed'?'success':($value['task_status'] == 'open'?'primary':'secondary');
                                                                ?>
                                                                <tr id="o<?php echo $value['id']; ?>">
                                                                    <td><?php echo $value['lead_id']; ?></td>
                                                                    <td><?php echo $value['lead_parent_id']; ?></td>
                                                                    <td>
                                                                        <?php echo '<strong>' . $value['customer_name'] . '</strong><br />' . $value['customer_mobile'] . '<br />' . $value['customer_email']; ?></a>
                                                                    </td>
                                                                    <td><?php echo $value['assigned_user']; ?><br><?php echo $value['assigned_email']; ?></td>
                                                                    <td><?php echo date('d M Y H:i A', strtotime($value['task_created_at']));?></td>
                                                                    <td><?php echo $value['service_name']; ?></td>
                                                                    <!-- <td><?php echo $value['group_name']; ?></td> -->
                                                                    <td><?php echo $value['status_name']; ?></td>
                                                                    <td><span class="badge badge-<?php echo $badge_clr;?>"><?php echo $value['task_status']; ?></span></td>
                                                                    <td><span><?php echo $value['block_category']; ?></span></td>
                                                                    <td><span class="badge badge-<?php echo $value['sla_violated']=='Yes'?'danger':'secondary';?>"><?php echo $value['sla_violated']; ?></span></td>
                                                                    <td>
                                                                        <a href="<?php echo base_url(); ?>leads/lead/view/<?php echo $value['lead_parent_id']; ?>" class="btn btn-sm btn-primary">View</a>
                                                                    </td>
                                                                </tr>
                                                            <?php }} ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane tabs-animation fade show" id="insuranceTasks" role="tabpanel">
                                    <div class="row justify-content-center">
                                        <div class="col-lg-12">
                                            <div class="main-card mb-3 card mt-4">
                                                <div class="card-body table-responsive">
                                                    <table class="table table-hover table-striped table-bordered w-100" id="created_dTtable">
                                                        <thead>
                                                            <tr>
                                                                <th>Sub Lead ID</th>
                                                                <th>Main Lead ID</th>
                                                                <th>Customer</th>
                                                                <th>Assigned To</th>
                                                                <th>Task Created At</th>
                                                                <th>service Name</th>
                                                                <!-- <th>Group</th> -->
                                                                <th>Lead Status</th>
                                                                <th>Task Status</th>
                                                                <th>Category</th>
                                                                <th>SLA Violated</th>
                                                                <th>Actions</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php foreach ($tasks as $key => $value) {
                                                                if ($value['service_id'] == 1516) { 
                                                                    $badge_clr = $value['task_status'] == 'completed'?'success':($value['task_status'] == 'open'?'primary':'secondary');
                                                                ?>
                                                                <tr id="o<?php echo $value['id']; ?>">
                                                                    <td><?php echo $value['lead_id']; ?></td>
                                                                    <td><?php echo $value['lead_parent_id']; ?></td>
                                                                    <td>
                                                                        <?php echo '<strong>' . $value['customer_name'] . '</strong><br />' . $value['customer_mobile'] . '<br />' . $value['customer_email']; ?></a>
                                                                    </td>
                                                                    <td><?php echo $value['assigned_user']; ?><br><?php echo $value['assigned_email']; ?></td>
                                                                    <td><?php echo date('d M Y H:i A', strtotime($value['task_created_at']));?></td>
                                                                    <td><?php echo $value['service_name']; ?></td>
                                                                    <!-- <td><?php echo $value['group_name']; ?></td> -->
                                                                    <td><?php echo $value['status_name']; ?></td>
                                                                    <td><span class="badge badge-<?php echo $badge_clr;?>"><?php echo $value['task_status']; ?></span></td>
                                                                    <td><span><?php echo $value['block_category']; ?></span></td>
                                                                    <td><span class="badge badge-<?php echo $value['sla_violated']=='Yes'?'danger':'secondary';?>"><?php echo $value['sla_violated']; ?></span></td>
                                                                    <td>
                                                                        <a href="<?php echo base_url(); ?>leads/lead/view/<?php echo $value['lead_parent_id']; ?>" class="btn btn-sm btn-primary">View</a>
                                                                    </td>
                                                                </tr>
                                                            <?php }} ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane tabs-animation fade show" id="valuationTasks" role="tabpanel">
                                    <div class="row justify-content-center">
                                        <div class="col-lg-12">
                                            <div class="main-card mb-3 card mt-4">
                                                <div class="card-body table-responsive">
                                                    <table class="table table-hover table-striped table-bordered w-100" id="created_dTtable">
                                                        <thead>
                                                            <tr>
                                                                <th>Sub Lead ID</th>
                                                                <th>Main Lead ID</th>
                                                                <th>Customer</th>
                                                                <th>Assigned To</th>
                                                                <th>Task Created At</th>
                                                                <th>service Name</th>
                                                                <!-- <th>Group</th> -->
                                                                <th>Lead Status</th>
                                                                <th>Task Status</th>
                                                                <th>Property Value</th>
                                                                <th>SLA Violated</th>
                                                                <th>Actions</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php foreach ($tasks as $key => $value) {
                                                                if ($value['service_id'] == 2304) { 
                                                                    $badge_clr = $value['task_status'] == 'completed'?'success':($value['task_status'] == 'open'?'primary':'secondary');
                                                                ?>
                                                                <tr id="o<?php echo $value['id']; ?>">
                                                                    <td><?php echo $value['lead_id']; ?></td>
                                                                    <td><?php echo $value['lead_parent_id']; ?></td>
                                                                    <td>
                                                                        <?php echo '<strong>' . $value['customer_name'] . '</strong><br />' . $value['customer_mobile'] . '<br />' . $value['customer_email']; ?></a>
                                                                    </td>
                                                                    <td><?php echo $value['assigned_user']; ?><br><?php echo $value['assigned_email']; ?></td>
                                                                    <td><?php echo date('d M Y H:i A', strtotime($value['task_created_at']));?></td>
                                                                    <td><?php echo $value['service_name']; ?></td>
                                                                    <!-- <td><?php echo $value['group_name']; ?></td> -->
                                                                    <td><?php echo $value['status_name']; ?></td>
                                                                    <td><span class="badge badge-<?php echo $badge_clr;?>"><?php echo $value['task_status']; ?></span></td>
                                                                    <td><span><?php echo $value['property_value']; ?></span></td>
                                                                    <td><span class="badge badge-<?php echo $value['sla_violated']=='Yes'?'danger':'secondary';?>"><?php echo $value['sla_violated']; ?></span></td>
                                                                    <td>
                                                                        <a href="<?php echo base_url(); ?>leads/lead/view/<?php echo $value['lead_parent_id']; ?>" class="btn btn-sm btn-primary">View</a>
                                                                    </td>
                                                                </tr>
                                                            <?php }} ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane tabs-animation fade show" id="translationTasks" role="tabpanel">
                                    <div class="row justify-content-center">
                                        <div class="col-lg-12">
                                            <div class="main-card mb-3 card mt-4">
                                                <div class="card-body table-responsive">
                                                    <table class="table table-hover table-striped table-bordered w-100" id="created_dTtable">
                                                        <thead>
                                                            <tr>
                                                                <th>Sub Lead ID</th>
                                                                <th>Main Lead ID</th>
                                                                <th>Customer</th>
                                                                <th>Assigned To</th>
                                                                <th>Task Created At</th>
                                                                <th>service Name</th>
                                                                <th>Lead Status</th>
                                                                <th>Task Status</th>
                                                                <th>SLA Violated</th>
                                                                <th>Actions</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php foreach ($tasks as $key => $value) {
                                                                if ($value['service_id'] == 701 || $value['service_id'] == 566) { 
                                                                    $badge_clr = $value['task_status'] == 'completed'?'success':($value['task_status'] == 'open'?'primary':'secondary');
                                                                ?>
                                                                <tr id="o<?php echo $value['id']; ?>">
                                                                    <td><?php echo $value['lead_id']; ?></td>
                                                                    <td><?php echo $value['lead_parent_id']; ?></td>
                                                                    <td>
                                                                        <?php echo '<strong>' . $value['customer_name'] . '</strong><br />' . $value['customer_mobile'] . '<br />' . $value['customer_email']; ?></a>
                                                                    </td>
                                                                    <td><?php echo $value['assigned_user']; ?><br><?php echo $value['assigned_email']; ?></td>
                                                                    <td><?php echo date('d M Y H:i A', strtotime($value['task_created_at']));?></td>
                                                                    <td><?php echo $value['service_name']; ?></td>
                                                                    <!-- <td><?php echo $value['group_name']; ?></td> -->
                                                                    <td><?php echo $value['status_name']; ?></td>
                                                                    <td><span class="badge badge-<?php echo $badge_clr;?>"><?php echo $value['task_status']; ?></span></td>
                                                                    <td><span class="badge badge-<?php echo $value['sla_violated']=='Yes'?'danger':'secondary';?>"><?php echo $value['sla_violated']; ?></span></td>
                                                                    <td>
                                                                        <a href="<?php echo base_url(); ?>leads/lead/view/<?php echo $value['lead_parent_id']; ?>" class="btn btn-sm btn-primary">View</a>
                                                                    </td>
                                                                </tr>
                                                            <?php }} ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane tabs-animation fade show" id="attestationTasks" role="tabpanel">
                                    <div class="row justify-content-center">
                                        <div class="col-lg-12">
                                            <div class="main-card mb-3 card mt-4">
                                                <div class="card-body table-responsive">
                                                    <table class="table table-hover table-striped table-bordered w-100" id="created_dTtable">
                                                        <thead>
                                                            <tr>
                                                                <th>Sub Lead ID</th>
                                                                <th>Main Lead ID</th>
                                                                <th>Customer</th>
                                                                <th>Assigned To</th>
                                                                <th>Task Created At</th>
                                                                <th>service Name</th>
                                                                <!-- <th>Group</th> -->
                                                                <th>Lead Status</th>
                                                                <th>Task Status</th>
                                                                <th>Category</th>
                                                                <th>SLA Violated</th>
                                                                <th>Actions</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php foreach ($tasks as $key => $value) {
                                                                if ($value['service_id'] == 2309) { 
                                                                    $badge_clr = $value['task_status'] == 'completed'?'success':($value['task_status'] == 'open'?'primary':'secondary');
                                                                ?>
                                                                <tr id="o<?php echo $value['id']; ?>">
                                                                    <td><?php echo $value['lead_id']; ?></td>
                                                                    <td><?php echo $value['lead_parent_id']; ?></td>
                                                                    <td>
                                                                        <?php echo '<strong>' . $value['customer_name'] . '</strong><br />' . $value['customer_mobile'] . '<br />' . $value['customer_email']; ?></a>
                                                                    </td>
                                                                    <td><?php echo $value['assigned_user']; ?><br><?php echo $value['assigned_email']; ?></td>
                                                                    <td><?php echo date('d M Y H:i A', strtotime($value['task_created_at']));?></td>
                                                                    <td><?php echo $value['service_name']; ?></td>
                                                                    <!-- <td><?php echo $value['group_name']; ?></td> -->
                                                                    <td><?php echo $value['status_name']; ?></td>
                                                                    <td><span class="badge badge-<?php echo $badge_clr;?>"><?php echo $value['task_status']; ?></span></td>
                                                                    <td><span><?php echo $value['block_category']; ?></span></td>
                                                                    <td><span class="badge badge-<?php echo $value['sla_violated']=='Yes'?'danger':'secondary';?>"><?php echo $value['sla_violated']; ?></span></td>
                                                                    <td>
                                                                        <a href="<?php echo base_url(); ?>leads/lead/view/<?php echo $value['lead_parent_id']; ?>" class="btn btn-sm btn-primary">View</a>
                                                                    </td>
                                                                </tr>
                                                            <?php }} ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane tabs-animation fade show" id="shippingTasks" role="tabpanel">
                                    <div class="row justify-content-center">
                                        <div class="col-lg-12">
                                            <div class="main-card mb-3 card mt-4">
                                                <div class="card-body table-responsive">
                                                    <table class="table table-hover table-striped table-bordered w-100" id="created_dTtable">
                                                        <thead>
                                                            <tr>
                                                                <th>Sub Lead ID</th>
                                                                <th>Main Lead ID</th>
                                                                <th>Customer</th>
                                                                <th>Assigned To</th>
                                                                <th>Task Created At</th>
                                                                <th>service Name</th>
                                                                <!-- <th>Group</th> -->
                                                                <th>Lead Status</th>
                                                                <th>Task Status</th>
                                                                <th>Estimated Date</th>
                                                                <th>SLA Violated</th>
                                                                <th>Actions</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php foreach ($tasks as $key => $value) {
                                                                if ($value['service_id'] == 2310) { 
                                                                    $badge_clr = $value['task_status'] == 'completed'?'success':($value['task_status'] == 'open'?'primary':'secondary');
                                                                ?>
                                                                <tr id="o<?php echo $value['id']; ?>">
                                                                    <td><?php echo $value['lead_id']; ?></td>
                                                                    <td><?php echo $value['lead_parent_id']; ?></td>
                                                                    <td>
                                                                        <?php echo '<strong>' . $value['customer_name'] . '</strong><br />' . $value['customer_mobile'] . '<br />' . $value['customer_email']; ?></a>
                                                                    </td>
                                                                    <td><?php echo $value['assigned_user']; ?><br><?php echo $value['assigned_email']; ?></td>
                                                                    <td><?php echo date('d M Y H:i A', strtotime($value['task_created_at']));?></td>
                                                                    <td><?php echo $value['service_name']; ?></td>
                                                                    <!-- <td><?php echo $value['group_name']; ?></td> -->
                                                                    <td><?php echo $value['status_name']; ?></td>
                                                                    <td><span class="badge badge-<?php echo $badge_clr;?>"><?php echo $value['task_status']; ?></span></td>
                                                                    <td><span><?php echo empty($value['estimated_date'])?date('d M Y', strtotime($value['estimated_date'])):''; ?></span></td>
                                                                    <td><span class="badge badge-<?php echo $value['sla_violated']=='Yes'?'danger':'secondary';?>"><?php echo $value['sla_violated']; ?></span></td>
                                                                    <td>
                                                                        <a href="<?php echo base_url(); ?>leads/lead/view/<?php echo $value['lead_parent_id']; ?>" class="btn btn-sm btn-primary">View</a>
                                                                    </td>
                                                                </tr>
                                                            <?php }} ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane tabs-animation fade show" id="passportTasks" role="tabpanel">
                                    <div class="row justify-content-center">
                                        <div class="col-lg-12">
                                            <div class="main-card mb-3 card mt-4">
                                                <div class="card-body table-responsive">
                                                    <table class="table table-hover table-striped table-bordered w-100" id="created_dTtable">
                                                        <thead>
                                                            <tr>
                                                                <th>Sub Lead ID</th>
                                                                <th>Main Lead ID</th>
                                                                <th>Customer</th>
                                                                <th>Assigned To</th>
                                                                <th>Task Created At</th>
                                                                <th>service Name</th>
                                                                <!-- <th>Group</th> -->
                                                                <th>Lead Status</th>
                                                                <th>Task Status</th>
                                                                <th>SLA Violated</th>
                                                                <th>Actions</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php foreach ($tasks as $key => $value) {
                                                                if ($value['service_id'] == 2307 || $value['service_id'] == 179) { 
                                                                    $badge_clr = $value['task_status'] == 'completed'?'success':($value['task_status'] == 'open'?'primary':'secondary');
                                                                ?>
                                                                <tr id="o<?php echo $value['id']; ?>">
                                                                    <td><?php echo $value['lead_id']; ?></td>
                                                                    <td><?php echo $value['lead_parent_id']; ?></td>
                                                                    <td>
                                                                        <?php echo '<strong>' . $value['customer_name'] . '</strong><br />' . $value['customer_mobile'] . '<br />' . $value['customer_email']; ?></a>
                                                                    </td>
                                                                    <td><?php echo $value['assigned_user']; ?><br><?php echo $value['assigned_email']; ?></td>
                                                                    <td><?php echo date('d M Y H:i A', strtotime($value['task_created_at']));?></td>
                                                                    <td><?php echo $value['service_name']; ?></td>
                                                                    <!-- <td><?php echo $value['group_name']; ?></td> -->
                                                                    <td><?php echo $value['status_name']; ?></td>
                                                                    <td><span class="badge badge-<?php echo $badge_clr;?>"><?php echo $value['task_status']; ?></span></td>
                                                                    <td><span class="badge badge-<?php echo $value['sla_violated']=='Yes'?'danger':'secondary';?>"><?php echo $value['sla_violated']; ?></span></td>
                                                                    <td>
                                                                        <a href="<?php echo base_url(); ?>leads/lead/view/<?php echo $value['lead_parent_id']; ?>" class="btn btn-sm btn-primary">View</a>
                                                                    </td>
                                                                </tr>
                                                            <?php }} ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane tabs-animation fade show" id="titleTasks" role="tabpanel">
                                    <div class="row justify-content-center">
                                        <div class="col-lg-12">
                                            <div class="main-card mb-3 card mt-4">
                                                <div class="card-body table-responsive">
                                                    <table class="table table-hover table-striped table-bordered w-100" id="created_dTtable">
                                                        <thead>
                                                            <tr>
                                                                <th>Sub Lead ID</th>
                                                                <th>Main Lead ID</th>
                                                                <th>Customer</th>
                                                                <th>Assigned To</th>
                                                                <th>Task Created At</th>
                                                                <th>service Name</th>
                                                                <!-- <th>Group</th> -->
                                                                <th>Lead Status</th>
                                                                <th>Task Status</th>
                                                                <th>SLA Violated</th>
                                                                <th>Actions</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php foreach ($tasks as $key => $value) {
                                                                if ($value['service_id'] == 2305) { 
                                                                    $badge_clr = $value['task_status'] == 'completed'?'success':($value['task_status'] == 'open'?'primary':'secondary');
                                                                ?>
                                                                <tr id="o<?php echo $value['id']; ?>">
                                                                    <td><?php echo $value['lead_id']; ?></td>
                                                                    <td><?php echo $value['lead_parent_id']; ?></td>
                                                                    <td>
                                                                        <?php echo '<strong>' . $value['customer_name'] . '</strong><br />' . $value['customer_mobile'] . '<br />' . $value['customer_email']; ?></a>
                                                                    </td>
                                                                    <td><?php echo $value['assigned_user']; ?><br><?php echo $value['assigned_email']; ?></td>
                                                                    <td><?php echo date('d M Y H:i A', strtotime($value['task_created_at']));?></td>
                                                                    <td><?php echo $value['service_name']; ?></td>
                                                                    <!-- <td><?php echo $value['group_name']; ?></td> -->
                                                                    <td><?php echo $value['status_name']; ?></td>
                                                                    <td><span class="badge badge-<?php echo $badge_clr;?>"><?php echo $value['task_status']; ?></span></td>
                                                                    <td><span class="badge badge-<?php echo $value['sla_violated']=='Yes'?'danger':'secondary';?>"><?php echo $value['sla_violated']; ?></span></td>
                                                                    <td>
                                                                        <a href="<?php echo base_url(); ?>leads/lead/view/<?php echo $value['lead_parent_id']; ?>" class="btn btn-sm btn-primary">View</a>
                                                                    </td>
                                                                </tr>
                                                            <?php }} ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane tabs-animation fade show" id="visaCancelTasks" role="tabpanel">
                                    <div class="row justify-content-center">
                                        <div class="col-lg-12">
                                            <div class="main-card mb-3 card mt-4">
                                                <div class="card-body table-responsive">
                                                    <table class="table table-hover table-striped table-bordered w-100" id="created_dTtable">
                                                        <thead>
                                                            <tr>
                                                                <th>Sub Lead ID</th>
                                                                <th>Main Lead ID</th>
                                                                <th>Customer</th>
                                                                <th>Assigned To</th>
                                                                <th>Task Created At</th>
                                                                <th>service Name</th>
                                                                <!-- <th>Group</th> -->
                                                                <th>Lead Status</th>
                                                                <th>Task Status</th>
                                                                <th>SLA Violated</th>
                                                                <th>Actions</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php foreach ($tasks as $key => $value) {
                                                                if ($value['service_id'] == 165 || $value['service_id'] == 166) { 
                                                                    $badge_clr = $value['task_status'] == 'completed'?'success':($value['task_status'] == 'open'?'primary':'secondary');
                                                                ?>
                                                                <tr id="o<?php echo $value['id']; ?>">
                                                                    <td><?php echo $value['lead_id']; ?></td>
                                                                    <td><?php echo $value['lead_parent_id']; ?></td>
                                                                    <td>
                                                                        <?php echo '<strong>' . $value['customer_name'] . '</strong><br />' . $value['customer_mobile'] . '<br />' . $value['customer_email']; ?></a>
                                                                    </td>
                                                                    <td><?php echo $value['assigned_user']; ?><br><?php echo $value['assigned_email']; ?></td>
                                                                    <td><?php echo date('d M Y H:i A', strtotime($value['task_created_at']));?></td>
                                                                    <td><?php echo $value['service_name']; ?></td>
                                                                    <!-- <td><?php echo $value['group_name']; ?></td> -->
                                                                    <td><?php echo $value['status_name']; ?></td>
                                                                    <td><span class="badge badge-<?php echo $badge_clr;?>"><?php echo $value['task_status']; ?></span></td>
                                                                    <td><span class="badge badge-<?php echo $value['sla_violated']=='Yes'?'danger':'secondary';?>"><?php echo $value['sla_violated']; ?></span></td>
                                                                    <td>
                                                                        <a href="<?php echo base_url(); ?>leads/lead/view/<?php echo $value['lead_parent_id']; ?>" class="btn btn-sm btn-primary">View</a>
                                                                    </td>
                                                                </tr>
                                                            <?php }} ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane tabs-animation fade show" id="gdrfaTasks" role="tabpanel">
                                    <div class="row justify-content-center">
                                        <div class="col-lg-12">
                                            <div class="main-card mb-3 card mt-4">
                                                <div class="card-body table-responsive">
                                                    <table class="table table-hover table-striped table-bordered w-100" id="created_dTtable">
                                                        <thead>
                                                            <tr>
                                                                <th>Sub Lead ID</th>
                                                                <th>Main Lead ID</th>
                                                                <th>Customer</th>
                                                                <th>Assigned To</th>
                                                                <th>Task Created At</th>
                                                                <th>service Name</th>
                                                                <!-- <th>Group</th> -->
                                                                <th>Lead Status</th>
                                                                <th>Task Status</th>
                                                                <th>SLA Violated</th>
                                                                <th>Actions</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php foreach ($tasks as $key => $value) {
                                                                if ($value['service_id'] == 2306) { 
                                                                    $badge_clr = $value['task_status'] == 'completed'?'success':($value['task_status'] == 'open'?'primary':'secondary');
                                                                ?>
                                                                <tr id="o<?php echo $value['id']; ?>">
                                                                    <td><?php echo $value['lead_id']; ?></td>
                                                                    <td><?php echo $value['lead_parent_id']; ?></td>
                                                                    <td>
                                                                        <?php echo '<strong>' . $value['customer_name'] . '</strong><br />' . $value['customer_mobile'] . '<br />' . $value['customer_email']; ?></a>
                                                                    </td>
                                                                    <td><?php echo $value['assigned_user']; ?><br><?php echo $value['assigned_email']; ?></td>
                                                                    <td><?php echo date('d M Y H:i A', strtotime($value['task_created_at']));?></td>
                                                                    <td><?php echo $value['service_name']; ?></td>
                                                                    <!-- <td><?php echo $value['group_name']; ?></td> -->
                                                                    <td><?php echo $value['status_name']; ?></td>
                                                                    <td><span class="badge badge-<?php echo $badge_clr;?>"><?php echo $value['task_status']; ?></span></td>
                                                                    <td><span class="badge badge-<?php echo $value['sla_violated']=='Yes'?'danger':'secondary';?>"><?php echo $value['sla_violated']; ?></span></td>
                                                                    <td>
                                                                        <a href="<?php echo base_url(); ?>leads/lead/view/<?php echo $value['lead_parent_id']; ?>" class="btn btn-sm btn-primary">View</a>
                                                                    </td>
                                                                </tr>
                                                            <?php }} ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane tabs-animation fade show" id="eidReplacementTasks" role="tabpanel">
                                    <div class="row justify-content-center">
                                        <div class="col-lg-12">
                                            <div class="main-card mb-3 card mt-4">
                                                <div class="card-body table-responsive">
                                                    <table class="table table-hover table-striped table-bordered w-100" id="created_dTtable">
                                                        <thead>
                                                            <tr>
                                                                <th>Sub Lead ID</th>
                                                                <th>Main Lead ID</th>
                                                                <th>Customer</th>
                                                                <th>Assigned To</th>
                                                                <th>Task Created At</th>
                                                                <th>service Name</th>
                                                                <!-- <th>Group</th> -->
                                                                <th>Lead Status</th>
                                                                <th>Task Status</th>
                                                                <th>SLA Violated</th>
                                                                <th>Actions</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php foreach ($tasks as $key => $value) {
                                                                if ($value['service_id'] == 114 || $value['service_id'] == 1136) { 
                                                                    $badge_clr = $value['task_status'] == 'completed'?'success':($value['task_status'] == 'open'?'primary':'secondary');
                                                                ?>
                                                                <tr id="o<?php echo $value['id']; ?>">
                                                                    <td><?php echo $value['lead_id']; ?></td>
                                                                    <td><?php echo $value['lead_parent_id']; ?></td>
                                                                    <td>
                                                                        <?php echo '<strong>' . $value['customer_name'] . '</strong><br />' . $value['customer_mobile'] . '<br />' . $value['customer_email']; ?></a>
                                                                    </td>
                                                                    <td><?php echo $value['assigned_user']; ?><br><?php echo $value['assigned_email']; ?></td>
                                                                    <td><?php echo date('d M Y H:i A', strtotime($value['task_created_at']));?></td>
                                                                    <td><?php echo $value['service_name']; ?></td>
                                                                    <!-- <td><?php echo $value['group_name']; ?></td> -->
                                                                    <td><?php echo $value['status_name']; ?></td>
                                                                    <td><span class="badge badge-<?php echo $badge_clr;?>"><?php echo $value['task_status']; ?></span></td>
                                                                    <td><span class="badge badge-<?php echo $value['sla_violated']=='Yes'?'danger':'secondary';?>"><?php echo $value['sla_violated']; ?></span></td>
                                                                    <td>
                                                                        <a href="<?php echo base_url(); ?>leads/lead/view/<?php echo $value['lead_parent_id']; ?>" class="btn btn-sm btn-primary">View</a>
                                                                    </td>
                                                                </tr>
                                                            <?php }} ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane tabs-animation fade show" id="updateIcpTasks" role="tabpanel">
                                    <div class="row justify-content-center">
                                        <div class="col-lg-12">
                                            <div class="main-card mb-3 card mt-4">
                                                <div class="card-body table-responsive">
                                                    <table class="table table-hover table-striped table-bordered w-100" id="created_dTtable">
                                                        <thead>
                                                            <tr>
                                                                <th>Sub Lead ID</th>
                                                                <th>Main Lead ID</th>
                                                                <th>Customer</th>
                                                                <th>Assigned To</th>
                                                                <th>Task Created At</th>
                                                                <th>service Name</th>
                                                                <!-- <th>Group</th> -->
                                                                <th>Lead Status</th>
                                                                <th>Task Status</th>
                                                                <th>SLA Violated</th>
                                                                <th>Actions</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php foreach ($tasks as $key => $value) {
                                                                if ($value['service_id'] == 2308) { 
                                                                    $badge_clr = $value['task_status'] == 'completed'?'success':($value['task_status'] == 'open'?'primary':'secondary');
                                                                ?>
                                                                <tr id="o<?php echo $value['id']; ?>">
                                                                    <td><?php echo $value['lead_id']; ?></td>
                                                                    <td><?php echo $value['lead_parent_id']; ?></td>
                                                                    <td>
                                                                        <?php echo '<strong>' . $value['customer_name'] . '</strong><br />' . $value['customer_mobile'] . '<br />' . $value['customer_email']; ?></a>
                                                                    </td>
                                                                    <td><?php echo $value['assigned_user']; ?><br><?php echo $value['assigned_email']; ?></td>
                                                                    <td><?php echo date('d M Y H:i A', strtotime($value['task_created_at']));?></td>
                                                                    <td><?php echo $value['service_name']; ?></td>
                                                                    <!-- <td><?php echo $value['group_name']; ?></td> -->
                                                                    <td><?php echo $value['status_name']; ?></td>
                                                                    <td><span class="badge badge-<?php echo $badge_clr;?>"><?php echo $value['task_status']; ?></span></td>
                                                                    <td><span class="badge badge-<?php echo $value['sla_violated']=='Yes'?'danger':'secondary';?>"><?php echo $value['sla_violated']; ?></span></td>
                                                                    <td>
                                                                        <a href="<?php echo base_url(); ?>leads/lead/view/<?php echo $value['lead_parent_id']; ?>" class="btn btn-sm btn-primary">View</a>
                                                                    </td>
                                                                </tr>
                                                            <?php }} ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane tabs-animation fade show" id="visaEntryPermitTasks" role="tabpanel">
                                    <div class="row justify-content-center">
                                        <div class="col-lg-12">
                                            <div class="main-card mb-3 card mt-4">
                                                <div class="card-body table-responsive">
                                                    <table class="table table-hover table-striped table-bordered w-100" id="created_dTtable">
                                                        <thead>
                                                            <tr>
                                                                <th>Sub Lead ID</th>
                                                                <th>Main Lead ID</th>
                                                                <th>Customer</th>
                                                                <th>Assigned To</th>
                                                                <th>Task Created At</th>
                                                                <th>service Name</th>
                                                                <!-- <th>Group</th> -->
                                                                <th>Lead Status</th>
                                                                <th>Task Status</th>
                                                                <th>SLA Violated</th>
                                                                <th>Actions</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php foreach ($tasks as $key => $value) {
                                                                if ($value['service_id'] == 2311) { 
                                                                    $badge_clr = $value['task_status'] == 'completed'?'success':($value['task_status'] == 'open'?'primary':'secondary');
                                                                ?>
                                                                <tr id="o<?php echo $value['id']; ?>">
                                                                    <td><?php echo $value['lead_id']; ?></td>
                                                                    <td><?php echo $value['lead_parent_id']; ?></td>
                                                                    <td>
                                                                        <?php echo '<strong>' . $value['customer_name'] . '</strong><br />' . $value['customer_mobile'] . '<br />' . $value['customer_email']; ?></a>
                                                                    </td>
                                                                    <td><?php echo $value['assigned_user']; ?><br><?php echo $value['assigned_email']; ?></td>
                                                                    <td><?php echo date('d M Y H:i A', strtotime($value['task_created_at']));?></td>
                                                                    <td><?php echo $value['service_name']; ?></td>
                                                                    <!-- <td><?php echo $value['group_name']; ?></td> -->
                                                                    <td><?php echo $value['status_name']; ?></td>
                                                                    <td><span class="badge badge-<?php echo $badge_clr;?>"><?php echo $value['task_status']; ?></span></td>
                                                                    <td><span class="badge badge-<?php echo $value['sla_violated']=='Yes'?'danger':'secondary';?>"><?php echo $value['sla_violated']; ?></span></td>
                                                                    <td>
                                                                        <a href="<?php echo base_url(); ?>leads/lead/view/<?php echo $value['lead_parent_id']; ?>" class="btn btn-sm btn-primary">View</a>
                                                                    </td>
                                                                </tr>
                                                            <?php }} ?>
                                                        </tbody>
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


<script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script src="<?php echo base_url(); ?>global/vendor/datatables/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(); ?>assets_new/node_modules/datatables.net-buttons/js/dataTables.buttons.min.js">
</script>
<script src="<?php echo base_url(); ?>assets_new/node_modules/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js">
</script>
<script src="<?php echo base_url(); ?>assets_new/node_modules/datatables.net-buttons/js/buttons.html5.min.js"></script>
<script src="<?php echo base_url(); ?>assets_new/node_modules/datatables-export-document/dataTables.export.js"></script>
<script
    src="<?php echo base_url(); ?>assets_new/node_modules/datatables.net-responsive/js/dataTables.responsive.min.js">
</script>
<script
    src="<?php echo base_url(); ?>assets_new/node_modules/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js">
</script>
<script src="<?php echo base_url(); ?>global/node_modules/select2/dist/js/select2.min.js"></script>

<link rel="stylesheet"
    href="<?php echo base_url(); ?>assets_new/node_modules/datatables.net-responsive-bs4/css/responsive.bootstrap4.css">

<script src="https://cdn.datatables.net/fixedheader/3.3.1/js/dataTables.fixedHeader.min.js"></script>
<script src="https://cdn.datatables.net/colreorder/1.6.1/js/dataTables.colReorder.min.js"></script>

<link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.3.1/css/fixedHeader.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/colreorder/1.6.1/css/colReorder.dataTables.min.css">
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>

<link rel="stylesheet" href="<?php echo base_url(); ?>assets_new/node_modules/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css">




<script>
    function init() {
        $(".lead_preview,.lead_view,.lead_status_update").off();
        $(".lead_preview").on('click', function(e) {
            // alert("Hi");
            e.preventDefault();
            var link = $(this).data("href");
            $.get(link, function(response) {
                console.log(response);
                $("#lead_preview").html(response);
                $("#modelId").modal();

                onChangeEves();
            });
        });

        $(".lead_view").on('click', function(e) {
            e.preventDefault();
            var link = $(this).data("href");
            window.open(link, "_blank");
        });

        $(".lead_status_update").on('click', function(e) {
            e.preventDefault();
            var link = $(this).data("href");
            $.get(link, function(response) {
                console.log(response);
                $("#lead_preview").html(response);
                $("#modelId").modal();
            });
        });
    }
    window.datTable = "";

    $("document").ready(function() {

        function onChangeEves() {
            $('[name="assign_group"] option').addClass("d-none");
            $('[name="assign_to"] option:not([value=""])').addClass("d-none");
        }

        if (window.screen.width > 991) {
            var dbOptions = {
                order: [
                    [0, 'desc']
                ],
                dom: '<"row"<"col-1"l><"col"B><"col"f>>rt<"row"<"col"i><"col my-3"p>>',
                buttons: [{
                        extend: 'csv',
                        text: "Export as CSV",
                        exportOptions: {
                            columns: ':not(:last-child)',
                            format: {
                                header: function(data, column, row) {
                                    // Remove all <select>...</select> tags
                                    data = data.replace(/<select[^>]*>[\s\S]*?<\/select>/gi, '');
                                    // Remove all <br> tags (any form like <br>, <br/>, <br />)
                                    data = data.replace(/<br\s*\/?>/gi, '');
                                    return data.trim();
                                }
                            }
                        },
                        attr: {
                                style: 'margin-right: 10px; border-radius: 3px;' // Add inline margin
                            }
                    }
                ],
                responsive: true,
                columnDefs: [{
                    targets: 6,
                    render: function(data, type, row) {
                        if (type === 'sort') {

                            var timestamp = moment(data, "DD MMM YYYY HH:mm A").valueOf();
                            return timestamp;
                        }

                        return data;
                    }
                }],
                initComplete: function(settings) {
                    // Get current table DOM element
                    var currentTable = settings.nTable;

                    // Get index of this table
                    var tableIndex = $('table.dataTable').index(currentTable);
                    console.log(tableIndex)
                    this.api().columns().every(function() {
                        var column = this;
                        // console.log(column.index());
                        if ( (column.index() == 2 || column.index() == 3 || column.index() == 5 || column.index() == 6 || column.index() == 7 || (tableIndex!=0 && column.index() == 8) || (tableIndex==1 && column.index() == 9))) { //skip if column 0
                            $(column.header()).append("<br>")
                            var select = $(
                                    '<select  class="form-control"><option value=""></option></select>'
                                )
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
                                var plainText = $('<div>').html(d).text();
                                select.append('<option value="' + plainText + '">' + plainText +
                                    '</option>')
                            });
                        } //end of if
                        if (column.index() == 6) {
                            column.data().unique().sort().each(function(d, j) {

                                var timestamp = moment(d, "DD MMM YYYY HH:mm A").valueOf();
                                column.data()[j] = timestamp;
                            });
                        }
                    });
                }
            };
        } else {
            var dbOptions = {
                order: [
                    [0, 'desc']
                ],
                colReorder: {
                    order: [0, 1, 8, 3, 4, 2, 5, 6, 7, 9, 10, 11]
                },
                dom: '<"row"<"col"l><"col"f>>rt<"row"<"col"i><"col my-3"p>>',
                responsive: true,
                columnDefs: [{
                    targets: 6,
                    render: function(data, type, row) {
                        if (type === 'sort') {

                            var timestamp = moment(data, "DD MMM YYYY HH:mm A").valueOf();
                            return timestamp;
                        }

                        return data;
                    }
                }],
                initComplete: function() {
                    this.api().columns().every(function() {
                        var column = this;
                        // console.log(column.index());
                        if (column.index() == 2 || column.index() == 3 || column.index() == 5 || column.index() == 7 || column.index() == 10) { //skip if column 0
                            $(column.header()).append("<br>")
                            var select = $(
                                    '<select  class="form-control"><option value=""></option></select>'
                                )
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
                        if (column.index() == 6) {
                            column.data().unique().sort().each(function(d, j) {

                                var timestamp = moment(d, "DD MMM YYYY HH:mm A").valueOf();
                                column.data()[j] = timestamp;
                            });
                        }
                    });
                }
            };
        }

        window.dtTable = $("#assigned_dTtable,#created_dTtable").on("draw.dt", init).dataTable(dbOptions);

        var hash = document.location.hash;
        if (hash) {
            console.log(hash);
            $(".nav-tabs a[href=\\" + hash + "]").tab('show');
        }

        // Change hash for page-reload
        $('.nav-tabs a').on('shown.bs.tab', function(e) {
            window.location.hash = e.target.hash;
        });
    });
</script>

<script src="/global/js/plugins-init/select2-init.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        var hash = location.hash.replace(/^#/, ''); // ^ means starting, meaning only match the first hash
        if (hash) {
            $('.nav_tabs a[href="#' + hash + '"]').tab('show');
        }

        window.onhashchange = function(e) {
            $('.nav_tabs a[href="' + e.target.location.hash + '"]').tab('show');
        }

        $('.nav_tabs a').on('show.bs.tab', function(e) {
            // Change hash for page-reload
            window.location.hash = e.target.hash;
        });
        $('#main-wrapper').attr('class', 'show menu-toggle');
    })
</script>