<style>
    .widget-content .widget-content-left .widget-heading {
        opacity: 1;
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
                                    <span class="d-inline-block">Dashboard</span>
                                </div>
                                <div class="page-title-subheading opacity-10">
                                    <nav class="" aria-label="breadcrumb">
                                        <ol class="breadcrumb">
                                            <li class="breadcrumb-item">
                                                <a href="javascript:void(0);">
                                                    <i aria-hidden="true" class="fa fa-home"></i>
                                                </a>
                                            </li>
                                            <li class="active breadcrumb-item">
                                                <a href="javascript:void(0);">Dashboard</a>
                                            </li>
                                        </ol>
                                    </nav>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="page-title-actions">
                                    <div class="d-inline-block pr-3">
                                        <select id="custom-inp-top" type="select" class="custom-select">
                                            <option>Select period...</option>
                                            <option>Last Week</option>
                                            <option>Last Month</option>
                                            <option>Last Year</option>
                                        </select>
                                    </div>
                                    <button type="button" data-toggle="tooltip" data-placement="left" class="btn btn-dark" title="Show a Toastr Notification!">
                                        <i class="fa fa-battery-three-quarters"></i>
                                    </button>
                                </div>
                            </div> -->
                    </div>
                </div>
                <div class="app-inner-layout app-inner-layout-page">
                    <div class="app-inner-bar">
                        <div class="container fiori-container">
                            <div class="inner-bar-center">
                                <ul class="nav">
                                    <li class="nav-item">
                                        <a role="tab" data-toggle="tab" class="nav-link active" href="#tab-content-0">
                                            <span>Overview</span>
                                        </a>
                                    </li>
                                    <!-- <li class="nav-item">
										<a role="tab" data-toggle="tab" class="nav-link" href="#tab-content-1">
											<span>Leads</span>
										</a>
									</li>
									<li class="nav-item">
										<a role="tab" data-toggle="tab" class="nav-link" href="#tab-content-1">
											<span>Enquiries</span>
										</a>
									</li>
									<li class="nav-item">
										<a role="tab" data-toggle="tab" class="nav-link" href="#tab-content-2">
											<span>Orders</span>
										</a>
									</li> -->
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="app-inner-layout__wrapper">
                        <div class="app-inner-layout__content">
                            <div class="tab-content">
                                <div class="container fiori-container">

                                    <div class="card no-shadow bg-transparent no-border rm-borders mb-3">
                                        <div class="card">
                                            <div class="no-gutters row">
                                                <div class="col-md-12 col-lg-6 col-xl-3">
                                                    <a href="/leads/lead/manage">
                                                        <div class="pt-0 pb-0 card-body">
                                                            <ul class="list-group list-group-flush">
                                                                <li class="list-group-item">
                                                                    <div class="widget-content p-0">
                                                                        <div class="widget-content-outer">
                                                                            <div class="widget-content-wrapper">
                                                                                <div class="widget-content-left">
                                                                                    <div class="widget-heading">Total
                                                                                        Leads</div>
                                                                                    <div class="widget-subheading">
                                                                                        Received Leads</div>
                                                                                </div>
                                                                                <div class="widget-content-right">
                                                                                    <div
                                                                                        class="widget-numbers text-primary">
                                                                                        <?php
                                                                                        if ($this->auth_user_role == 2) {
                                                                                            $total = count($accepted_leads) + count($converted_leads) + count($disqualified_leads);
                                                                                        } else {
                                                                                            // $total = count($unassigned_leads) + count($assigned_leads)  + count($converted_leads) + count($disqualified_leads);
                                                                                            $total = count($assigned_leads)  + count($converted_leads) + count($disqualified_leads) + count($pos_leads);
                                                                                        }
                                                                                        echo $total;
                                                                                        ?>

                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="widget-progress-wrapper">
                                                                                <div
                                                                                    class="progress-bar-sm progress-bar-animated-alt progress">
                                                                                    <div class="progress-bar bg-primary"
                                                                                        role="progressbar"
                                                                                        aria-valuenow="100"
                                                                                        aria-valuemin="0"
                                                                                        aria-valuemax="100"
                                                                                        style="width: 100%;"></div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </a>
                                                </div>
                                                <div class="col-md-12 col-lg-6 col-xl-3">
                                                    <a href="/leads/lead/manage#">
                                                        <div class="pt-0 pb-0 card-body">
                                                            <ul class="list-group list-group-flush">
                                                                <li class="list-group-item">
                                                                    <div class="widget-content p-0">
                                                                        <div class="widget-content-outer">
                                                                            <div class="widget-content-wrapper">
                                                                                <div class="widget-content-left">
                                                                                    <div class="widget-heading">Opened
                                                                                        Leads</div>
                                                                                    <div class="widget-subheading">
                                                                                        Follow-ups</div>
                                                                                </div>
                                                                                <div class="widget-content-right">
                                                                                    <div
                                                                                        class="widget-numbers text-success">
                                                                                        <?php
                                                                                        $open = count($accepted_leads)+count($pos_leads);
                                                                                        echo $open;
                                                                                        ?>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="widget-progress-wrapper">
                                                                                <div
                                                                                    class="progress-bar-sm progress-bar-animated-alt progress">
                                                                                    <div class="progress-bar bg-success"
                                                                                        role="progressbar"
                                                                                        aria-valuenow="<?php if ($total  == 0 || $total == '0') {
                                                                                                                                                                echo '0';
                                                                                                                                                            } else {
                                                                                                                                                                echo (int)($open * 100 / $total);
                                                                                                                                                            } ?>"
                                                                                        aria-valuemin="0"
                                                                                        aria-valuemax="100"
                                                                                        style="width: <?php if ($total  == 0 || $total == '0') {
                                                                                                                                                                                                                            echo '0';
                                                                                                                                                                                                                        } else {
                                                                                                                                                                                                                            echo (int)($open * 100 / $total);
                                                                                                                                                                                                                        } ?>%;">
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </a>
                                                </div>
                                                <div class="col-md-12 col-lg-6 col-xl-3">
                                                    <a href="/leads/lead/manage#converted">
                                                        <div class="pt-0 pb-0 card-body">
                                                            <ul class="list-group list-group-flush">
                                                                <li class="list-group-item">
                                                                    <div class="widget-content p-0">
                                                                        <div class="widget-content-outer">
                                                                            <div class="widget-content-wrapper">
                                                                                <div class="widget-content-left">
                                                                                    <div class="widget-heading">Closed
                                                                                        Leads</div>
                                                                                    <div class="widget-subheading">
                                                                                        Converted to Orders</div>
                                                                                </div>
                                                                                <div class="widget-content-right">
                                                                                    <div
                                                                                        class="widget-numbers text-danger">
                                                                                        <!-- <small>$</small> -->
                                                                                        <?php
                                                                                        $converted = count($converted_leads);
                                                                                        echo $converted;
                                                                                        ?>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="widget-progress-wrapper">
                                                                                <div
                                                                                    class="progress-bar-sm progress-bar-animated-alt progress">
                                                                                    <div class="progress-bar bg-danger"
                                                                                        role="progressbar"
                                                                                        aria-valuenow="<?php if ($total  == 0 || $total == '0') {
                                                                                                                                                                echo '0';
                                                                                                                                                            } else {
                                                                                                                                                                echo (int)($converted * 100 / $total);
                                                                                                                                                            } ?>"
                                                                                        aria-valuemin="0"
                                                                                        aria-valuemax="100"
                                                                                        style="width: <?php if ($total  == 0 || $total == '0') {
                                                                                                                                                                                                                            echo '0';
                                                                                                                                                                                                                        } else {
                                                                                                                                                                                                                            echo (int)($converted * 100 / $total);
                                                                                                                                                                                                                        } ?>%;">
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </a>
                                                </div>
                                                <div class="col-md-12 col-lg-6 col-xl-3">
                                                    <a href="/leads/lead/manage#disqualified">
                                                        <div class="pt-0 pb-0 card-body">
                                                            <ul class="list-group list-group-flush">
                                                                <li class="list-group-item">
                                                                    <div class="widget-content p-0">
                                                                        <div class="widget-content-outer">
                                                                            <div class="widget-content-wrapper">
                                                                                <div class="widget-content-left">
                                                                                    <div class="widget-heading">
                                                                                        Disqualified Leads</div>
                                                                                    <div class="widget-subheading">
                                                                                        Invalid or Irrelevents</div>
                                                                                </div>
                                                                                <div class="widget-content-right">
                                                                                    <div
                                                                                        class="widget-numbers text-focus">
                                                                                        <?php
                                                                                        $disq = count($disqualified_leads);
                                                                                        echo $disq;
                                                                                        ?>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="widget-progress-wrapper">
                                                                                <div
                                                                                    class="progress-bar-sm progress-bar-animated-alt progress">
                                                                                    <div class="progress-bar bg-focus"
                                                                                        role="progressbar"
                                                                                        aria-valuenow="<?php if ($total  == 0 || $total == '0') {
                                                                                                                                                                echo '0';
                                                                                                                                                            } else {
                                                                                                                                                                echo (int)($disq * 100 / $total);
                                                                                                                                                            } ?>"
                                                                                        aria-valuemin="0"
                                                                                        aria-valuemax="100"
                                                                                        style="width: <?php if ($total  == 0 || $total == '0') {
                                                                                                                                                                                                                            echo '0';
                                                                                                                                                                                                                        } else {
                                                                                                                                                                                                                            echo (int)($disq * 100 / $total);
                                                                                                                                                                                                                        } ?>%;">
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>



                                <div class="container fiori-container">
                                    <div class="row">
                                        <?php
                                            if ($this->auth_user_role == 7 || $this->auth_user_role == 89) {
                                        ?>
                                        <div class="col-lg-12 col-xl-12 p-0">
                                            <div class="mb-3 card">
                                                <div class="card-header-tab card-header d-flex justify-content-between">
                                                    <div class="card-header-title">
                                                        <i
                                                            class="header-icon lnr-users icon-gradient bg-tempting-azure"></i>
                                                        Group Analytics
                                                    </div>
                                                    <div>
                                                        <form action="/dashboard/index" method="get" id=dashboard_form>
                                                            <input type="date" value="<?php echo $date;?>" required name="date" onchange="$('#dashboard_form').submit()" class="form-control" id="">
                                                        </form>
                                                        
                                                    </div>
                                                </div>
                                            <!-- // Total leads created today.
                                                // Total leads received today.
                                                // Pending leads to be actioned
                                                // Total number of qualified leads this month.
                                                // Total number of disqualified leads this month -->
                                                <div class="pt-2 pb-0 card-body">
                                                    <div class="row">
                                                        <div class="col-md-6 col-xl-4">
                                                            <div class="card mb-3 border-0 widget-content">
                                                                <div class="widget-content-outer">
                                                                    <div class="widget-content-wrapper">
                                                                        <div class="widget-content-left">
                                                                            <div class="widget-heading">Total Leads
                                                                            </div>
                                                                            <div class="widget-subheading">Today Group Created</div>
                                                                        </div>
                                                                        <div class="widget-content-right">
                                                                            <div class="widget-numbers text-success">
                                                                                <?php echo $group_stat["today_created"]; ?>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="widget-progress-wrapper">
                                                                        <div class="progress-bar-sm progress">
                                                                            <div class="progress-bar bg-primary"
                                                                                role="progressbar" aria-valuenow="71"
                                                                                aria-valuemin="0" aria-valuemax="100"
                                                                                style="width: 71%;"></div>
                                                                        </div>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 col-xl-4">
                                                            <div class="card mb-3 widget-content  border-0">
                                                                <div class="widget-content-outer">
                                                                    <div class="widget-content-wrapper">
                                                                        <div class="widget-content-left">
                                                                            <div class="widget-heading">Received Leads
                                                                            </div>
                                                                            <div class="widget-subheading">Today Group Recieved 
                                                                            </div>
                                                                        </div>
                                                                        <div class="widget-content-right">
                                                                            <div class="widget-numbers text-warning">
                                                                            <?php echo $group_stat["today_received"]; ?>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="widget-progress-wrapper">
                                                                        <div
                                                                            class="progress-bar-sm progress-bar-animated-alt progress">
                                                                            <div class="progress-bar bg-danger"
                                                                                role="progressbar" aria-valuenow="85"
                                                                                aria-valuemin="0" aria-valuemax="100"
                                                                                style="width: 85%;"></div>
                                                                        </div>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 col-xl-4">
                                                            <div class="card mb-3 widget-content  border-0">
                                                                <div class="widget-content-outer">
                                                                    <div class="widget-content-wrapper">
                                                                        <div class="widget-content-left">
                                                                            <div class="widget-heading">Opened Leads</div>
                                                                            <div class="widget-subheading">Group Not Processed
                                                                            </div>
                                                                        </div>
                                                                        <div class="widget-content-right">
                                                                            <div class="widget-numbers text-danger">
                                                                            <?php echo $group_stat["opened"]; ?>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="widget-progress-wrapper">
                                                                        <div
                                                                            class="progress-bar-sm progress-bar-animated-alt progress">
                                                                            <div class="progress-bar bg-success"
                                                                                role="progressbar" aria-valuenow="46"
                                                                                aria-valuemin="0" aria-valuemax="100"
                                                                                style="width: 46%;"></div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 col-xl-6">
                                                            <div class="card mb-3 widget-content  border-0">
                                                                <div class="widget-content-outer">
                                                                    <div class="widget-content-wrapper">
                                                                        <div class="widget-content-left">
                                                                            <div class="widget-heading">Qualified Lead</div>
                                                                            <div class="widget-subheading">This Month
                                                                            </div>
                                                                        </div>
                                                                        <div class="widget-content-right">
                                                                            <div class="widget-numbers text-danger">

                                                                                <?php 
                                                                                if(isset($group_stat["qualified"]->total)) {
                                                                                    echo $group_stat["qualified"]->total;
                                                                                } else {
                                                                                    echo "0";} ?>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="widget-progress-wrapper">
                                                                        <div
                                                                            class="progress-bar-sm progress-bar-animated-alt progress">
                                                                            <div class="progress-bar bg-success"
                                                                                role="progressbar" aria-valuenow="46"
                                                                                aria-valuemin="0" aria-valuemax="100"
                                                                                style="width: 46%;"></div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 col-xl-6">
                                                            <div class="card mb-3 widget-content  border-0">
                                                                <div class="widget-content-outer">
                                                                    <div class="widget-content-wrapper">
                                                                        <div class="widget-content-left">
                                                                            <div class="widget-heading">Disqualified Leads</div>
                                                                            <div class="widget-subheading">This Month
                                                                            </div>
                                                                        </div>
                                                                        <div class="widget-content-right">
                                                                            <div class="widget-numbers text-danger">
                                                                            <?php 
                                                                                if(isset($group_stat["qualified"]->disqualified)) {
                                                                                    echo $group_stat["qualified"]->disqualified;
                                                                                } else {
                                                                                    echo "0";} ?>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="widget-progress-wrapper">
                                                                        <div
                                                                            class="progress-bar-sm progress-bar-animated-alt progress">
                                                                            <div class="progress-bar bg-success"
                                                                                role="progressbar" aria-valuenow="46"
                                                                                aria-valuemin="0" aria-valuemax="100"
                                                                                style="width: 46%;"></div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php } ?>

                                        <div class="col-lg-12 col-xl-12 p-0">
                                            <div class="mb-3 card">
                                                <div class="card-header-tab card-header">
                                                    <div class="card-header-title">
                                                        <i
                                                            class="header-icon lnr-rocket icon-gradient bg-tempting-azure">
                                                        </i>
                                                        Lead - Data Analysis Report
                                                    </div>
                                                </div>
                                                <div class="pt-2 pb-0 card-body">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="widget-content mt-2">
                                                                <div class="widget-content-outer">
                                                                    <div class="widget-content-wrapper">
                                                                        <div class="widget-content-left pr-2 fsize-1">
                                                                            <?php
                                                                        if ($lead_counts["salesql"] == 0 || $lead_counts["total"] == 0)
                                                                            $l_count = 0;
                                                                        else
                                                                            $l_count = number_format(($lead_counts["salesql"] / $lead_counts["total"]) * 100, 2);
                                                                        if ($lead_counts["salesql"] == 0 || $lead_counts["total"] == 0)
                                                                            $m_count = 0;
                                                                        else
                                                                            $m_count = number_format(($lead_counts["marketingql"] / $lead_counts["total"]) * 100, 2);
                                                                        ?>
                                                                            <div
                                                                                class="widget-numbers fsize-3 text-alternate">
                                                                                <?php echo $m_count; ?>%</div>
                                                                        </div>
                                                                        <div class="widget-content-right w-100">
                                                                            <div class="progress-bar-xs progress">
                                                                                <div class="progress-bar bg-info"
                                                                                    role="progressbar"
                                                                                    aria-valuenow="<?php echo $m_count; ?>"
                                                                                    aria-valuemin="0"
                                                                                    aria-valuemax="100"
                                                                                    style="width: <?php echo $m_count; ?>%">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="widget-content-left fsize-1">
                                                                        <div class="text-muted opacity-6">Marketing
                                                                            Qualified Leads</div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="widget-content mt-2">
                                                                <div class="widget-content-outer">
                                                                    <div class="widget-content-wrapper">
                                                                        <div class="widget-content-left pr-2 fsize-1">
                                                                            <div
                                                                                class="widget-numbers fsize-3 text-success">
                                                                                <?php echo $l_count; ?>%</div>
                                                                        </div>
                                                                        <div class="widget-content-right w-100">
                                                                            <div class="progress-bar-xs progress">
                                                                                <div class="progress-bar bg-success"
                                                                                    role="progressbar"
                                                                                    aria-valuenow="<?php echo $l_count; ?>"
                                                                                    aria-valuemin="0"
                                                                                    aria-valuemax="100"
                                                                                    style="width: <?php echo $l_count; ?>%">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="widget-content-left fsize-1">
                                                                        <div class="text-muted opacity-6">Sales
                                                                            Qualified
                                                                            Leads</div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- <div class="widget-chart p-0">
												<div id="dashboard-sparklines-primary"></div>
											</div> -->
                                            </div>
                                        </div>

                                        <div class="col-sm-12 col-lg-12 pl-0">
                                            <div class="card-hover-shadow-2x mb-3 card">
                                                <div class="card-header">
                                                    <div class="card-header-title">
                                                        Last Lead Activity
                                                    </div>
                                                    <div class="btn-actions-pane-right text-capitalize">
                                                        <button
                                                            class="btn-wide btn-outline-2x btn btn-outline-primary btn-sm"
                                                            onclick="location.href='/leads/lead/manage'">View
                                                            All</button>
                                                    </div>
                                                </div>
                                                <div class="">
                                                    <div class="scrollbar-container">
                                                        <div class="scroll-area-lg p-2">
                                                            <ul class="todo-list-wrapper list-group list-group-flush">
                                                                <?php
                                                            foreach ($latest_lead_action as $log) {
                                                            ?>
                                                                <li class="list-group-item">
                                                                    <div class="todo-indicator bg-info"></div>
                                                                    <div class="widget-content p-0">
                                                                        <div class="widget-content-wrapper">
                                                                            <div class="widget-content-left mr-2">
                                                                            </div>
                                                                            <div class="widget-content-left">
                                                                                <a
                                                                                    href="/leads/lead/view/<?php echo $log["lead_id"]; ?>">
                                                                                    <div class="widget-heading">
                                                                                        <?php echo $log["action_name"] . "-#" . $log["lead_id"]; ?>
                                                                                    </div>
                                                                                </a>
                                                                                <div class="widget-subheading">
                                                                                    <div>
                                                                                        <?php echo $log["log_remarks"]; ?>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="widget-content-right">
                                                                                <a
                                                                                    href="/leads/lead/view/<?php echo $log["lead_id"]; ?>">
                                                                                    <button
                                                                                        class="border-0 btn-transition btn btn-outline-info">
                                                                                        <i class="fa fa-eye"></i>
                                                                                    </button>
                                                                                </a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                                <?php
                                                            }
                                                            ?>
                                                            </ul>
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
    </div>
</div>