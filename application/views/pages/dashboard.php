<style>
    .widget-content .widget-content-left .widget-heading {
        opacity: 1;
    }

    .getDetails:hover {
        cursor: pointer;
    }

    .getSLAdetails:hover,
    .getcd:hover,
    .getad:hover {
        background: #85858529;
        cursor: pointer;
    }
    .w-14{
        width: 155px;   /* 134px; */
        margin: 0px 8px;
    }
    
    .widget-content .widget-content-left .widget-heading {
        opacity: 1;
    }
</style>
<?php 
    function get_country_code($country_name) {
        $countries = [
            "United States" => "us",
            "India" => "in",
            "Canada" => "ca",
            "United Kingdom" => "gb",
            "Germany" => "de",
            "France" => "fr",
            "Japan" => "jp",
            "China" => "cn",
            "Australia" => "au",
            "Brazil" => "br",
            "United Arab Emirates" => "ae" // Add UAE
        ];

        return $countries[$country_name] ?? 'unknown'; // Default to 'unknown' if not found
    }
    $groups = get_groups($this->auth_user_id);
?>
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
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="app-inner-layout__wrapper">
                        <div class="app-inner-layout__content">
                            <div class="tab-content">
                                <div class="container fiori-container">
                                    <div class="text-center mb-3">
                                        <div role="group" class="mb-2 btn-group-lg btn-group">
                                            <div style="margin-top: 36px; ">
                                                <a href="?all=true" class="btn-shadow <?php if (isset($_GET)) {
                                                    if ($_GET["all"] == "true") {
                                                        echo "active";
                                                    }
                                                } ?> btn btn-primary">All</a>
                                                <a href="?period=yesterday" class="btn-shadow <?php if (isset($_GET)) {
                                                    if ($_GET["period"] == "yesterday") {
                                                        echo "active";
                                                    }
                                                } ?>  btn btn-primary">Yesterday</a>
                                                <a href="?period=today" class="btn-shadow <?php  if (isset($_GET)) {
                                                    if ($_GET["period"] == "today") {
                                                        echo "active";
                                                    } else if (!$_GET) {
                                                        echo "active";
                                                    }
                                                } ?>  btn btn-primary">Today</a>
                                                <a href="?period=this_week" class="btn-shadow <?php if (isset($_GET)) {
                                                    if ($_GET["period"] == "this_week") {
                                                        echo "active";
                                                    }
                                                } ?>  btn btn-primary">Week</a>
                                                <a href="?period=this_month" class="btn-shadow <?php if (isset($_GET)) {
                                                    if ($_GET["period"] == "this_month") {
                                                        echo "active";
                                                    }
                                                } ?>  btn btn-primary">Month</a>
                                            </div>
                                            <div class="form-group m-1">
                                                <label for="">From Date:</label>
                                                <input type="date" name="from_date" class="form-control" id="from_date" value="<?php echo $startDate ?>">
                                            </div>
                                            <div class="form-group m-1">
                                                <label for="">To Date:</label>
                                                <input type="date" name="to_date" class="form-control" id="to_date" value="<?php echo $endDate ?>">
                                            </div>
                                            <button style="margin:5px; margin-top:35px;" id="date_filter" class="btn btn-primary">Apply</button>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-12 col-xl-12">
                                            <div class="row">
                                                <div class="col-md-6 col-lg-4 col-xl-4">
                                                    <div class="card mb-3 widget-chart widget-chart2 text-left card-btm-border card-shadow-primary border-primary leadsCount" data-start="<?= $startDate ?>"data-end="<?= $date ?>" data-type="<?php echo ($startDate != NULL) ? "date" :  "today" ;?>" data-id="all" data-details="leadsDetails" data-status="created">
                                                        <div class="widget-chat-wrapper-outer">
                                                            <div class="widget-chart-content pl-3 pb-1">
                                                                <div class="widget-chart-flex">
                                                                    <div class="widget-numbers">
                                                                        <div class="widget-chart-flex">
                                                                            <div class="fsize-4">
                                                                                <span class=""><?php echo $created_leads_count ? $created_leads_count : 0; ?></span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <h6 class="widget-subheading mb-0 opacity-5">Created Leads
                                                                </h6>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-lg-4 col-xl-4">
                                                    <div class="card mb-3 widget-chart widget-chart2 text-left card-btm-border card-shadow-success border-success leadsCount" data-start="<?= $startDate ?>"data-end="<?= $date ?>" data-type="<?php echo ($startDate != NULL) ? "date" :  "today" ;?>" data-id="all" data-details="leadsDetails" data-status="assigned">
                                                        <div class="widget-chat-wrapper-outer">
                                                            <div class="widget-chart-content pl-3 pb-1">
                                                                <div class="widget-chart-flex">
                                                                    <div class="widget-numbers">
                                                                        <div class="widget-chart-flex">
                                                                            <div class="fsize-4">
                                                                                <small class="fsize-1"></small>
                                                                                <span><?php echo $assigned_leads_count ? $assigned_leads_count : 0; ?></span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <h6 class="widget-subheading mb-0 opacity-5">Assigned 
                                                                    Leads</h6>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-lg-4 col-xl-4">
                                                    <div class="card mb-3 widget-chart widget-chart2 text-left card-btm-border card-shadow-info border-dark leadsCount" data-start="<?= $startDate ?>"data-end="<?= $date ?>" data-type="<?php echo ($startDate != NULL) ? "date" :  "today" ;?>" data-id="all" data-details="leadsDetails" data-status="accepted">
                                                        <div class="widget-chat-wrapper-outer">
                                                            <div class="widget-chart-content pl-3 pb-1">
                                                                <div class="widget-chart-flex">
                                                                    <div class="widget-numbers">
                                                                        <div class="widget-chart-flex">
                                                                            <div class="fsize-4">
                                                                                <span><?php echo $accepted_leads_count ? $accepted_leads_count : 0; ?></span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <h6 class="widget-subheading mb-0 opacity-5">Your Leads
                                                                </h6>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6 col-lg-3 col-xl-3">
                                                    <div class="card mb-3 widget-chart widget-chart2 text-left card-btm-border card-shadow-secondary border-secondary leadsCount" data-start="<?= $startDate ?>"data-end="<?= $date ?>" data-type="<?php echo ($startDate != NULL) ? "date" :  "today" ;?>" data-id="all" data-details="leadsDetails" data-status="unassigned">
                                                        <div class="widget-chat-wrapper-outer">
                                                            <div class="widget-chart-content pl-3 pb-1">
                                                                <div class="widget-chart-flex">
                                                                    <div class="widget-numbers">
                                                                        <div class="widget-chart-flex">
                                                                            <div class="fsize-4">
                                                                                <span><?php echo $unassigned_leads_count ? $unassigned_leads_count : 0; ?></span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <h6 class="widget-subheading mb-0 opacity-5">Unassigned Leads
                                                                </h6>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-lg-3 col-xl-3">
                                                    <div class="card mb-3 widget-chart widget-chart2 text-left card-btm-border card-shadow-info border-info leadsCount" data-start="<?= $startDate ?>"data-end="<?= $date ?>" data-type="<?php echo ($startDate != NULL) ? "date" :  "today" ;?>" data-id="all" data-details="leadsDetails" data-status="reassigned">
                                                        <div class="widget-chat-wrapper-outer">
                                                            <div class="widget-chart-content pl-3 pb-1">
                                                                <div class="widget-chart-flex">
                                                                    <div class="widget-numbers">
                                                                        <div class="widget-chart-flex">
                                                                            <div class="fsize-4">
                                                                                <span><?php echo $reassigned_leads_count ? $reassigned_leads_count : 0; ?></span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <h6 class="widget-subheading mb-0 opacity-5">Reassigned Leads
                                                                </h6>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-6 col-lg-3 col-xl-3">
                                                    <div class="card mb-3 widget-chart widget-chart2 text-left card-btm-border card-shadow-warning border-warning leadsCount" data-start="<?= $startDate ?>"data-end="<?= $date ?>" data-type="<?php echo ($startDate != NULL) ? "date" :  "today" ;?>" data-id="all" data-details="leadsDetails" data-status="converted">
                                                        <div class="widget-chat-wrapper-outer">
                                                            <div class="widget-chart-content pl-3 pb-1">
                                                                <div class="widget-chart-flex">
                                                                    <div class="widget-numbers">
                                                                        <div class="widget-chart-flex">
                                                                            <div class="fsize-4">
                                                                                <span><?php echo $converted_leads_count ? $converted_leads_count : 0; ?></span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <h6 class="widget-subheading mb-0 opacity-5">
                                                                    Converted</h6>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-lg-3 col-xl-3">
                                                    <div class="card mb-3 widget-chart widget-chart2 text-left card-btm-border card-shadow-danger border-danger leadsCount" data-start="<?= $startDate ?>"data-end="<?= $date ?>" data-type="<?php echo ($startDate != NULL) ? "date" :  "today" ;?>" data-id="all" data-details="leadsDetails" data-status="Disqualified">
                                                        <div class="widget-chat-wrapper-outer">
                                                            <div class="widget-chart-content pl-3 pb-1">
                                                                <div class="widget-chart-flex">
                                                                    <div class="widget-numbers">
                                                                        <div class="widget-chart-flex">
                                                                            <div class="fsize-4">
                                                                                <span class=""><?php echo $disqualified_leads_count ? $disqualified_leads_count : 0; ?></span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <h6 class="widget-subheading mb-0 opacity-5">
                                                                    Disqualified
                                                                </h6>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- <div class="col-sm-12 col-lg-12 pl-0">
                                            <div class="card-hover-shadow-2x mb-3 card">
                                                <div class="card-header">
                                                    <div class="card-header-title">
                                                        Last Lead Activity
                                                    </div>
                                                    <div class="btn-actions-pane-right text-capitalize">
                                                        <button class="btn-wide btn-outline-2x btn btn-outline-primary btn-sm"
                                                            onclick="location.href='/leads/lead/manage'">View All
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="">
                                                    <div class="scrollbar-container">
                                                        <div class="scroll-area-lg p-2">
                                                            <ul class="todo-list-wrapper list-group list-group-flush">
                                                            <?php foreach ($latest_lead_action as $log) { ?>
                                                                <li class="list-group-item">
                                                                    <div class="todo-indicator bg-info"></div>
                                                                    <div class="widget-content p-0">
                                                                        <div class="widget-content-wrapper">
                                                                            <div class="widget-content-left mr-2"> </div>
                                                                            <div class="widget-content-left">
                                                                                <a href="/leads/lead/view/<?php echo $log["lead_id"]; ?>">
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
                                                                                <a href="/leads/lead/view/<?php echo $log["lead_id"]; ?>">
                                                                                    <button class="border-0 btn-transition btn btn-outline-info"> <i class="fa fa-eye"></i>
                                                                                    </button>
                                                                                </a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                                <?php } ?>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> -->
                                            
                                        <div class="col-lg-12 col-xl-12">
                                            <div class="mb-3 card">
                                                <div class="card-header-tab card-header-tab-animation card-header bg-primary text-white">
                                                    <div class="card-header-title">
                                                        <i class="header-icon lnr-users icon-gradient bg-love-kiss"></i>
                                                        Online Leads
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <div class="tab-content">
                                                        <div class="tab-pane fade active show" id="tab-eg-11">                                                                
                                                            <div class="container">
                                                                <div class="d-flex flex-wrap">
                                                                    <div class="w-14">
                                                                        <div class="card mb-3 widget-chart widget-chart2 text-left card-btm-border card-shadow-success border-success">
                                                                            <div class="widget-chat-wrapper-outer">
                                                                                <div class="widget-chart-content pl-3 pb-1">
                                                                                    <div class="widget-chart-flex">
                                                                                        <div class="widget-numbers">
                                                                                            <div class="widget-chart-flex">
                                                                                                <div class="fsize-4">
                                                                                                    <span class=""><?php echo $lead_counts->online_leads; ?></span>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <h6 class="widget-subheading mb-0 opacity-5">Total Leads</h6>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="w-14">
                                                                        <div class="card mb-3 widget-chart widget-chart2 text-left card-btm-border card-shadow-dark border-dark leadsCount" data-start="<?= $startDate ?>"data-end="<?= $date ?>" data-type="<?php echo ($startDate != NULL) ? "date" :  "all" ;?>" data-id="online" data-details="leadsDetails" data-status="not_started">
                                                                            <div class="widget-chat-wrapper-outer">
                                                                                <div class="widget-chart-content pl-3 pb-1">
                                                                                    <div class="widget-chart-flex">
                                                                                        <div class="widget-numbers">
                                                                                            <div class="widget-chart-flex">
                                                                                                <div class="fsize-4">
                                                                                                    <span><?php echo $lead_counts->online_notstarted_leads; ?></span>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <h6 class="widget-subheading mb-0 opacity-5">Not Started</h6>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div> 
                                                                    <div class="w-14">
                                                                        <div class="card mb-3 widget-chart widget-chart2 text-left card-btm-border card-shadow-info border-info leadsCount" data-start="<?= $startDate ?>"data-end="<?= $date ?>" data-type="<?php echo ($startDate != NULL) ? "date" :  "all" ;?>" data-id="online" data-details="leadsDetails" data-status="hold">
                                                                            <div class="widget-chat-wrapper-outer">
                                                                                <div class="widget-chart-content pl-3 pb-1">
                                                                                    <div class="widget-chart-flex">
                                                                                        <div class="widget-numbers">
                                                                                            <div class="widget-chart-flex">
                                                                                                <div class="fsize-4">
                                                                                                    <span><?php echo $lead_counts->online_onhold_leads; ?></span>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <h6 class="widget-subheading mb-0 opacity-5">Hold</h6>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div> 
                                                                    <div class="w-14">
                                                                        <div class="card mb-3 widget-chart widget-chart2 text-left card-btm-border card-shadow-warning border-primary leadsCount" data-start="<?= $startDate ?>"data-end="<?= $date ?>" data-type="<?php echo ($startDate != NULL) ? "date" :  "all" ;?>" data-id="online" data-details="leadsDetails" data-status="Inprogress">
                                                                            <div class="widget-chat-wrapper-outer">
                                                                                <div class="widget-chart-content pl-3 pb-1">
                                                                                    <div class="widget-chart-flex">
                                                                                        <div class="widget-numbers">
                                                                                            <div class="widget-chart-flex">
                                                                                                <div class="fsize-4">
                                                                                                    <span class=""><?php echo $lead_counts->online_inprogress_leads; ?></span>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <h6 class="widget-subheading mb-0 opacity-5">In Progress</h6>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="w-14">
                                                                        <div class="card mb-3 widget-chart widget-chart2 text-left card-btm-border card-shadow-warning border-warning leadsCount" data-start="<?= $startDate ?>"data-end="<?= $date ?>" data-type="<?php echo ($startDate != NULL) ? "date" :  "all" ;?>" data-id="online" data-details="leadsDetails" data-status="Completed">
                                                                            <div class="widget-chat-wrapper-outer">
                                                                                <div class="widget-chart-content pl-3 pb-1">
                                                                                    <div class="widget-chart-flex">
                                                                                        <div class="widget-numbers">
                                                                                            <div class="widget-chart-flex">
                                                                                                <div class="fsize-4">
                                                                                                    <span><?php echo $lead_counts->online_completed_leads; ?></span>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <h6 class="widget-subheading mb-0 opacity-5">Completed</h6>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="w-14">
                                                                        <div class="card mb-3 widget-chart widget-chart2 text-left card-btm-border card-shadow-danger border-danger leadsCount" data-start="<?= $startDate ?>"data-end="<?= $date ?>" data-type="<?php echo ($startDate != NULL) ? "date" :  "all" ;?>" data-id="online" data-details="leadsDetails" data-status="Disqualified">
                                                                            <div class="widget-chat-wrapper-outer">
                                                                                <div class="widget-chart-content pl-3 pb-1">
                                                                                    <div class="widget-chart-flex">
                                                                                        <div class="widget-numbers">
                                                                                            <div class="widget-chart-flex">
                                                                                                <div class="fsize-4">
                                                                                                    <span class=""><?php echo $lead_counts->online_disqualified_leads; ?></span>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <h6 class="widget-subheading mb-0 opacity-5">
                                                                                        Disqualified
                                                                                    </h6>
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

                                        <div class="col-lg-12 col-xl-12">
                                            <div class="mb-3 card">
                                                <div class="card-header-tab card-header-tab-animation card-header bg-primary text-white">
                                                    <div class="card-header-title">
                                                        <i class="header-icon lnr-users icon-gradient bg-love-kiss"></i>
                                                        Walk In Leads
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <div class="tab-content">
                                                        <div class="tab-pane fade active show" id="tab-eg-11">                                                                
                                                            <div class="container">
                                                                <div class="d-flex flex-wrap">
                                                                    <div class="w-14">
                                                                        <div class="card mb-3 widget-chart widget-chart2 text-left card-btm-border card-shadow-success border-success">
                                                                            <div class="widget-chat-wrapper-outer">
                                                                                <div class="widget-chart-content pl-3 pb-1">
                                                                                    <div class="widget-chart-flex">
                                                                                        <div class="widget-numbers">
                                                                                            <div class="widget-chart-flex">
                                                                                                <div class="fsize-4">
                                                                                                    <span class=""><?php echo $lead_counts->walkin_leads; ?></span>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <h6 class="widget-subheading mb-0 opacity-5">Total Leads</h6>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="w-14">
                                                                        <div class="card mb-3 widget-chart widget-chart2 text-left card-btm-border card-shadow-dark border-dark leadsCount" data-start="<?= $startDate ?>"data-end="<?= $date ?>" data-type="<?php echo ($startDate != NULL) ? "date" :  "all" ;?>" data-id="walk_in" data-details="leadsDetails" data-status="not_started">
                                                                            <div class="widget-chat-wrapper-outer">
                                                                                <div class="widget-chart-content pl-3 pb-1">
                                                                                    <div class="widget-chart-flex">
                                                                                        <div class="widget-numbers">
                                                                                            <div class="widget-chart-flex">
                                                                                                <div class="fsize-4">
                                                                                                    <span><?php echo $lead_counts->walkin_notstarted_leads; ?></span>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <h6 class="widget-subheading mb-0 opacity-5">Not Started</h6>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="w-14">
                                                                        <div class="card mb-3 widget-chart widget-chart2 text-left card-btm-border card-shadow-info border-info leadsCount" data-start="<?= $startDate ?>"data-end="<?= $date ?>" data-type="<?php echo ($startDate != NULL) ? "date" :  "all" ;?>" data-id="walk_in" data-details="leadsDetails" data-status="hold">
                                                                            <div class="widget-chat-wrapper-outer">
                                                                                <div class="widget-chart-content pl-3 pb-1">
                                                                                    <div class="widget-chart-flex">
                                                                                        <div class="widget-numbers">
                                                                                            <div class="widget-chart-flex">
                                                                                                <div class="fsize-4">
                                                                                                    <span><?php echo $lead_counts->walkin_onhold_leads; ?></span>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <h6 class="widget-subheading mb-0 opacity-5">Hold</h6>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="w-14">
                                                                        <div class="card mb-3 widget-chart widget-chart2 text-left card-btm-border card-shadow-primary border-primary leadsCount" data-start="<?= $startDate ?>"data-end="<?= $date ?>" data-type="<?php echo ($startDate != NULL) ? "date" :  "all" ;?>" data-id="walk_in" data-details="leadsDetails" data-status="Inprogress">
                                                                            <div class="widget-chat-wrapper-outer">
                                                                                <div class="widget-chart-content pl-3 pb-1">
                                                                                    <div class="widget-chart-flex">
                                                                                        <div class="widget-numbers">
                                                                                            <div class="widget-chart-flex">
                                                                                                <div class="fsize-4">
                                                                                                    <span class=""><?php echo $lead_counts->walkin_inprogress_leads; ?></span>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <h6 class="widget-subheading mb-0 opacity-5">In Progress</h6>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="w-14">
                                                                        <div class="card mb-3 widget-chart widget-chart2 text-left card-btm-border card-shadow-warning border-warning leadsCount" data-start="<?= $startDate ?>"data-end="<?= $date ?>" data-type="<?php echo ($startDate != NULL) ? "date" :  "all" ;?>" data-id="walk_in" data-details="leadsDetails" data-status="Completed">
                                                                            <div class="widget-chat-wrapper-outer">
                                                                                <div class="widget-chart-content pl-3 pb-1">
                                                                                    <div class="widget-chart-flex">
                                                                                        <div class="widget-numbers">
                                                                                            <div class="widget-chart-flex">
                                                                                                <div class="fsize-4">
                                                                                                    <span><?php echo $lead_counts->walkin_completed_leads; ?></span>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <h6 class="widget-subheading mb-0 opacity-5">Completed</h6>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="w-14">
                                                                        <div class="card mb-3 widget-chart widget-chart2 text-left card-btm-border card-shadow-danger border-danger leadsCount" data-start="<?= $startDate ?>"data-end="<?= $date ?>" data-type="<?php echo ($startDate != NULL) ? "date" :  "all" ;?>" data-id="walk_in" data-details="leadsDetails" data-status="Disqualified">
                                                                            <div class="widget-chat-wrapper-outer">
                                                                                <div class="widget-chart-content pl-3 pb-1">
                                                                                    <div class="widget-chart-flex">
                                                                                        <div class="widget-numbers">
                                                                                            <div class="widget-chart-flex">
                                                                                                <div class="fsize-4">            
                                                                                                    <span class=""><?php echo $lead_counts->walkin_disqualified_leads; ?></span>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <h6 class="widget-subheading mb-0 opacity-5">
                                                                                        Disqualified
                                                                                    </h6>
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
                                        <?php if (in_array(58, $groups)) { ?>
                                        <div class="col-lg-12 col-xl-6">
                                            <div class="mb-3 card">
                                                <div class="card-header-tab card-header-tab-animation card-header bg-primary text-white">
                                                    <div class="card-header-title">
                                                        <i class="header-icon lnr-users icon-gradient bg-love-kiss"></i>
                                                        DLD - Users Availability
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <div class="tab-content">
                                                        <div class="tab-pane fade active show" id="tab-eg-11">
                                                            <div class="scroll-area-sm">
                                                                <div class="scrollbar-container">
                                                                    <ul class="rm-list-borders rm-list-borders-scroll list-group list-group-flush">
                                                                        <?php foreach ($dld_available_users as $user) { ?>
                                                                            <li class="list-group-item">
                                                                                <div class="widget-content p-0">
                                                                                    <div class="widget-content-wrapper getDLDdetails"
                                                                                        data-start="<?= $startDate; ?>"
                                                                                        data-end="<?= $date; ?>"
                                                                                        data-user="<?= $user['user_id'] ?>"
                                                                                        data-period="<?= $_GET["period"]?>"
                                                                                        data-name="<?= $user["first_name"] . " " . $user["last_name"]; ?>">
                                                                                        <div class="widget-content-left mr-3">
                                                                                            <img width="42" title="<?php echo $sla["first_name"] . " " . $sla["last_name"]; ?>"
                                                                                            class="rounded-circle profile_pic" src="/assets_new/images/avatars/3.jpg" alt>
                                                                                        </div>
                                                                                        <div
                                                                                            class="widget-content-left">
                                                                                            <div class="widget-heading">
                                                                                                <?php echo $user["first_name"] . " " . $user["last_name"]; ?>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="widget-content-right">
                                                                                            <div class="font-size-xlg text-muted">
                                                                                                <!-- <small class="opacity-5 pr-1">$</small> -->
                                                                                                <?php if($user['is_available'] == 1) { ?>
                                                                                                    <span class="text-success">Available</span> <?php } else { ?>
                                                                                                    <span class="text-danger">Busy</span> <?php } ?>
                                                                                                
                                                                                                <!-- <small class="text-danger pl-2">
                                                                                                <i  class="fa fa-angle-down"></i>
                                                                                            </small> -->
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </li>
                                                                        <?php } ?>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php } ?>
                                        <div class="col-lg-12 col-xl-6">
                                            <div class="mb-3 card">
                                                <div class="card-header-tab card-header-tab-animation card-header bg-primary text-white justify-content-between">
                                                    <div class="card-header-title">
                                                        <i class="header-icon lnr-funnel icon-gradient bg-love-kiss"></i>
                                                        Lead Pipeline
                                                    </div>
                                                    <!-- <div class="card-header-title">
                                                        Total: <?php echo $next_contactable_leads[0]['total_amount']; ?>
                                                    </div> -->
                                                </div>
                                                <div class="card-body p-0">
                                                    <div class="tab-content">
                                                        <div class="row p-3">
                                                            <div class="col-md-4 col-lg-3 col-xl-4">
                                                                <div class="card mb-3 widget-chart widget-chart2 text-left card-btm-border card-shadow-primary border-primary getDetails"
                                                                    data-details="nextContacble">
                                                                    <div class="widget-chat-wrapper-outer">
                                                                        <div class="widget-chart-content pl-3 pb-1">
                                                                            <div class="widget-chart-flex">
                                                                                <div class="widget-numbers">
                                                                                    <div class="widget-chart-flex">
                                                                                        <div class="fsize-4">
                                                                                            <span class=""><?php echo $next_contactable_leads[0]['next_contactable_leads']??0; ?></span>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <h6 class="widget-subheading mb-0 opacity-5">Next Contactable</h6>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!-- <div class="col-md-4 col-lg-3 col-xl-4">
                                                                <div class="card mb-3 widget-chart widget-chart2 text-left card-btm-border card-shadow-success border-success getDetails"
                                                                    data-details="qprogress">
                                                                    <div class="widget-chat-wrapper-outer">
                                                                        <div class="widget-chart-content pl-3 pb-1">
                                                                            <div class="widget-chart-flex">
                                                                                <div class="widget-numbers">
                                                                                    <div class="widget-chart-flex">
                                                                                        <div class="fsize-4">
                                                                                            <span class=""><?php echo $next_contactable_leads[0]['quotation_progress_total']??0; ?></span>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <h6 class="widget-subheading mb-0 opacity-5">Quotation Progress</h6>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4 col-lg-3 col-xl-4">
                                                                <div class="card mb-3 widget-chart widget-chart2 text-left card-btm-border card-shadow-danger border-danger getDetails"
                                                                    data-details="qaccepted">
                                                                    <div class="widget-chat-wrapper-outer">
                                                                        <div class="widget-chart-content pl-3 pb-1">
                                                                            <div class="widget-chart-flex">
                                                                                <div class="widget-numbers">
                                                                                    <div class="widget-chart-flex">
                                                                                        <div class="fsize-4">
                                                                                            <span class=""><?php echo $next_contactable_leads[0]['quotation_accepted_total']??0; ?></span>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <h6 class="widget-subheading mb-0 opacity-5">Quotation Accepted</h6>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div> -->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-12 col-xl-6">
                                            <div class="mb-3 card">
                                                <div class="card-header-tab card-header-tab-animation card-header bg-primary text-white justify-content-between">
                                                    <div class="card-header-title">
                                                        <i class="header-icon lnr-database icon-gradient bg-love-kiss"></i>
                                                        Lead Source
                                                    </div>
                                                </div>
                                                <div class="card-body p-0">
                                                    <div class="tab-content">
                                                        <div class="row p-3">
                                                            <div class="col-md-4 col-lg-3 col-xl-4">
                                                                <div class="card mb-3 widget-chart widget-chart2 text-left card-btm-border card-shadow-primary border-primary getLeadSourceDetails"
                                                                    data-start="<?= $startDate ?>"data-end="<?= $date ?>" 
                                                                    data-type="<?php echo ($startDate != NULL) ? "date" :  "all" ;?>"
                                                                    data-details="leadsourceDetails" data-status="walkin"
                                                                    data-id="walkin">
                                                                    <div class="widget-chat-wrapper-outer">
                                                                        <div class="widget-chart-content pl-3 pb-1">
                                                                            <div class="widget-chart-flex">
                                                                                <div class="widget-numbers">
                                                                                    <div class="widget-chart-flex">
                                                                                        <div class="fsize-4">
                                                                                            <span class=""><?php echo $gc_lead_source_leads->gc_walkin_leads; ?></span>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <h6 class="widget-subheading mb-0 opacity-5">Walk-in</h6>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4 col-lg-3 col-xl-4">
                                                                <div class="card mb-3 widget-chart widget-chart2 text-left card-btm-border card-shadow-success border-success getLeadSourceDetails"
                                                                    data-start="<?= $startDate ?>"data-end="<?= $date ?>"
                                                                    data-type="<?php echo ($startDate != NULL) ? "date" :  "all" ;?>"
                                                                    data-details="leadsourceDetails" data-status="whatsapp"
                                                                    data-id="whatsapp">
                                                                    <div class="widget-chat-wrapper-outer">
                                                                        <div class="widget-chart-content pl-3 pb-1">
                                                                            <div class="widget-chart-flex">
                                                                                <div class="widget-numbers">
                                                                                    <div class="widget-chart-flex">
                                                                                        <div class="fsize-4">
                                                                                            <span class=""><?php echo $gc_lead_source_leads->gc_whatsapp_leads; ?></span>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <h6 class="widget-subheading mb-0 opacity-5">WhatsApp</h6>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4 col-lg-3 col-xl-4">
                                                                <div class="card mb-3 widget-chart widget-chart2 text-left card-btm-border card-shadow-danger border-danger getLeadSourceDetails"
                                                                    data-start="<?= $startDate ?>"data-end="<?= $date ?>"
                                                                    data-type="<?php echo ($startDate != NULL) ? "date" :  "all" ;?>"
                                                                    data-details="leadsourceDetails" data-status="email"
                                                                    data-id="email">
                                                                    <div class="widget-chat-wrapper-outer">
                                                                        <div class="widget-chart-content pl-3 pb-1">
                                                                            <div class="widget-chart-flex">
                                                                                <div class="widget-numbers">
                                                                                    <div class="widget-chart-flex">
                                                                                        <div class="fsize-4">
                                                                                            <span class=""><?php echo $gc_lead_source_leads->gc_email_leads; ?></span>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <h6 class="widget-subheading mb-0 opacity-5">Email</h6>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div> 
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-12 col-xl-12">
                                            <div class="mb-3 card">
                                                <div class="card-header-tab card-header-tab-animation card-header bg-primary text-white justify-content-between">
                                                    <div class="card-header-title">
                                                        <i class="header-icon lnr-calendar-full icon-gradient bg-love-kiss"></i>
                                                        Tasks
                                                    </div>
                                                </div>
                                                <div class="card-body p-0">
                                                    <div class="tab-content">
                                                        <div class="row p-3">
                                                            <?php if (isset($tasks_count) && !empty($tasks_count)) { 
                                                                for ($i=0; $i < count($tasks_count); $i++) { 
                                                                    if (strlen($tasks_count[$i]['service_name'])) {
                                                                ?>
                                                            <div class="col-md-3 col-lg-3 col-xl-3">
                                                                <div class="card mb-3 widget-chart widget-chart2 text-left card-btm-border card-shadow-primary border-primary getDetails"
                                                                    data-details="tasksDld" data-serviceid = "<?php echo $tasks_count[$i]['id'] ?? ''; ?>">
                                                                    <div class="widget-chat-wrapper-outer">
                                                                        <div class="widget-chart-content pl-3 pb-1">
                                                                            <div class="widget-chart-flex">
                                                                                <div class="widget-numbers">
                                                                                    <div class="widget-chart-flex">
                                                                                        <div class="fsize-4">
                                                                                            <span class=""><?php echo $tasks_count[$i]['task_count'] ?? 0; ?></span>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <h6 class="widget-subheading mb-0 opacity-5"><?php echo $tasks_count[$i]['service_name'] ?? ''; ?></h6>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <?php }}} ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- <div class="row"> -->
                                            <div class="col-md-12">
                                                <div class="main-card mb-3 card">
                                                    <div class="card-header text-white bg-primary">Nationality - Lead Summary</div>
                                                    <div class="pt-4 pb-4 mx-3">
                                                        <table id="nationality_table_dTtable"
                                                            class="align-middle mb-0 table table-bordered table-striped table-hover nationality_table">
                                                            <thead>
                                                                <tr class="table-info">
                                                                    <th class="text-center">#</th>
                                                                    <th>Nationality</th>
                                                                    <th class="text-center">Not Started</th>
                                                                    <th class="text-center">Hold</th>
                                                                    <th class="text-center">InProgress</th>
                                                                    <th class="text-center">Completed</th>
                                                                    <th class="text-center">Disqualified</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php $i = 1;
                                                                foreach ($nationality_lead_counts as $nationality) { ?>
                                                                    <tr>
                                                                        <td class="text-center text-muted">
                                                                            #<?php echo $i; ?></td>
                                                                        <td>
                                                                            <div class="widget-content p-0">
                                                                                <div class="widget-content-wrapper">
                                                                                    <div class="widget-content-left mr-3">
                                                                                        <div class="widget-content-left">
                                                                                            <!-- <div
                                                                                                class="flag <?php echo $nationality['nationality_name']; ?> large mx-auto">
                                                                                            </div> -->
                                                                                            <img src="https://flagcdn.com/w40/<?php echo strtolower(get_country_code($nationality['country'])); ?>.png" width="30" height="20">
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="widget-content-left flex2">
                                                                                        <div class="widget-heading">
                                                                                            <?php echo $nationality['country']; ?>
                                                                                        </div>

                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                        <td class="text-center getcd"
                                                                            data-start="<?= $startDate ?>"
                                                                            data-end="<?= $date ?>"
                                                                            data-country="<?= $nationality['country'] ?>"
                                                                            data-type="<?php if ($startDate != NULL) {
                                                                                echo "date";
                                                                            } else {
                                                                                echo "all";
                                                                            } ?>" data-status="not started"><span><?php if (isset($nationality['not started'])) {
                                                                                echo $nationality['not started'];
                                                                            } else
                                                                                echo "0"; ?></span></td>
                                                                        <td class="text-center getcd"
                                                                            data-start="<?= $startDate ?>"
                                                                            data-end="<?= $date ?>"
                                                                            data-country="<?= $nationality['country'] ?>"
                                                                            data-type="<?php if ($startDate != NULL) {
                                                                                echo "date";
                                                                            } else {
                                                                                echo "all";
                                                                            } ?>" data-status="hold"><span><?php if (isset($nationality['onhold'])) {
                                                                                echo $nationality['onhold'];
                                                                            } else
                                                                                echo "0"; ?></span></td>
                                                                        <td class="text-center getcd"
                                                                            data-start="<?= $startDate ?>"
                                                                            data-end="<?= $date ?>"
                                                                            data-country="<?= $nationality['country'] ?>"
                                                                            data-type="<?php if ($startDate != NULL) {
                                                                                echo "date";
                                                                            } else {
                                                                                echo "all";
                                                                            } ?>" data-status="Inprogress"><span><?php if (isset($nationality['inprogress'])) {
                                                                                echo $nationality['inprogress'];
                                                                            } else
                                                                                echo "0"; ?></span></td>
                                                                        <td class="text-center getcd"
                                                                            data-start="<?= $startDate ?>"
                                                                            data-end="<?= $date ?>"
                                                                            data-country="<?= $nationality['country'] ?>"
                                                                            data-type="<?php if ($startDate != NULL) {
                                                                                echo "date";
                                                                            } else {
                                                                                echo "all";
                                                                            } ?>" data-status="Completed"><span><?php if (isset($nationality['completed'])) {
                                                                                echo $nationality['completed'];
                                                                            } else
                                                                                echo "0"; ?></span></td>
                                                                        <td class="text-center getcd"
                                                                            data-start="<?= $startDate ?>"
                                                                            data-end="<?= $date ?>"
                                                                            data-country="<?= $nationality['country'] ?>"
                                                                            data-type="<?php if ($startDate != NULL) {
                                                                                echo "date";
                                                                            } else {
                                                                                echo "all";
                                                                            } ?>" data-status="Disqualified"><span><?php if (isset($nationality['disqualified'])) {
                                                                                echo $nationality['disqualified'];
                                                                            } else
                                                                                echo "0"; ?></span></td>
                                                                    </tr>
                                                                    <?php $i = $i + 1;
                                                                } ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        <!-- </div> -->
   
                                        <!-- <div class="row"> -->
                                            <div class="col-md-12">
                                                <div class="main-card mb-3 card">
                                                    <div class="card-header text-white bg-primary">Assigned Users - Lead Summary</div>
                                                    <div class="pt-4 pb-4 mx-3">
                                                        <table id="nationality_table"
                                                            class="align-middle mb-0 table table-bordered table-striped table-hover nationality_table">
                                                            <thead>
                                                                <tr class="table-info">
                                                                    <th class="text-center">#</th>
                                                                    <th>User</th>
                                                                    <th class="text-center">Not Started</th>
                                                                    <th class="text-center">Hold</th>
                                                                    <th class="text-center">InProgress</th>
                                                                    <th class="text-center">Completed</th>
                                                                    <th class="text-center">Disqualified</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php $i = 1;
                                                                foreach ($assigned_lead_counts as $assigned_user) { ?>
                                                                    <tr>
                                                                        <td class="text-center text-muted">
                                                                            #<?php echo $i; ?></td>
                                                                        <td>
                                                                            <div class="widget-content p-0">
                                                                                <div class="widget-content-wrapper">
                                                                                    <div class="widget-content-left mr-3">
                                                                                        <div class="widget-content-left">
                                                                                            <img width="42"
                                                                                                title="<?php echo $assigned_user['first_name'] . " " . $assigned_user['last_name']; ?>"
                                                                                                class="rounded-circle profile_pic"
                                                                                                src="<?php echo ($assigned_user['profile_pic'] == '' || $assigned_user['profile_pic'] == NULL) ? "/assets_new/images/avatars/3.jpg" : "data:image/jpeg;base64," . $assigned_user['profile_pic']; ?>">
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="widget-content-left flex2">
                                                                                        <div class="widget-heading">
                                                                                            <?php echo $assigned_user['first_name'] . " " . $assigned_user['last_name']; ?>
                                                                                        </div>

                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                        <td class="text-center getad"
                                                                            data-start="<?= $startDate ?>"
                                                                            data-end="<?= $date ?>"
                                                                            data-user="<?= $assigned_user['user_id'] ?>"
                                                                            data-name="<?= $assigned_user['first_name'] . " " . $assigned_user['last_name'] ?>"
                                                                            data-type="<?php if ($startDate != NULL) {
                                                                                echo "date";
                                                                            } else {
                                                                                echo "all";
                                                                            } ?>" data-status="not started"><?php if (isset($assigned_user["not started"])) {
                                                                                echo $assigned_user["not started"];
                                                                            } else
                                                                                echo "0"; ?></td>
                                                                        <td class="text-center getad"
                                                                            data-start="<?= $startDate ?>"
                                                                            data-end="<?= $date ?>"
                                                                            data-user="<?= $assigned_user['user_id'] ?>"
                                                                            data-name="<?= $assigned_user['first_name'] . " " . $assigned_user['last_name'] ?>"
                                                                            data-type="<?php if ($startDate != NULL) {
                                                                                echo "date";
                                                                            } else {
                                                                                echo "all";
                                                                            } ?>" data-status="hold"><?php if (isset($assigned_user["onhold"])) {
                                                                                echo $assigned_user["onhold"];
                                                                            } else
                                                                                echo "0"; ?></td>
                                                                        <td class="text-center getad"
                                                                            data-start="<?= $startDate ?>"
                                                                            data-end="<?= $date ?>"
                                                                            data-user="<?= $assigned_user['user_id'] ?>"
                                                                            data-name="<?= $assigned_user['first_name'] . " " . $assigned_user['last_name'] ?>"
                                                                            data-type="<?php if ($startDate != NULL) {
                                                                                echo "date";
                                                                            } else {
                                                                                echo "all";
                                                                            } ?>" data-status="Inprogress"><?php if (isset($assigned_user["inprogress"])) {
                                                                                echo $assigned_user["inprogress"];
                                                                            } else
                                                                                echo "0"; ?></td>
                                                                        <td class="text-center getad"
                                                                            data-start="<?= $startDate ?>"
                                                                            data-end="<?= $date ?>"
                                                                            data-user="<?= $assigned_user['user_id'] ?>"
                                                                            data-name="<?= $assigned_user['first_name'] . " " . $assigned_user['last_name'] ?>"
                                                                            data-type="<?php if ($startDate != NULL) {
                                                                                echo "date";
                                                                            } else {
                                                                                echo "all";
                                                                            } ?>" data-status="Completed"><?php if (isset($assigned_user["completed"])) {
                                                                                echo $assigned_user["completed"];
                                                                            } else
                                                                                echo "0"; ?></td>
                                                                        <td class="text-center getad"
                                                                            data-start="<?= $startDate ?>"
                                                                            data-end="<?= $date ?>"
                                                                            data-user="<?= $assigned_user['user_id'] ?>"
                                                                            data-name="<?= $assigned_user['first_name'] . " " . $assigned_user['last_name'] ?>"
                                                                            data-type="<?php if ($startDate != NULL) {
                                                                                echo "date";
                                                                            } else {
                                                                                echo "all";
                                                                            } ?>" data-status="Disqualified"><?php if (isset($assigned_user['disqualified'])) {
                                                                                echo $assigned_user['disqualified'];
                                                                            } else
                                                                                echo "0"; ?></td>
                                                                    </tr>
                                                                    <?php $i = $i + 1;
                                                                } ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        <!-- </div> -->

                                        <!-- </div> -->
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

<!-- leads count summary details-->
<div class="modal fade" id="leadsDetails" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title details-title"><span id="lead_title"> </span> <span id="lead_type"> </span> Leads Summary</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body table-responsive" id="leadsDTtable_container">
                <table class="table table-striped table-hoverable table-hover w-100" id="leadsDTtable"></table>
            </div>
        </div>
    </div>
</div>

<!-- sla Details -->
<div class="modal fade" id="slaDetails" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title details-title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body table-responsive" id="slaDetailsDTtable_container">
                <table class="table table-striped table-hoverable table-hover w-100" id="slaDetailsDTtable"></table>
            </div>

        </div>
    </div>
</div>

<!-- leads source details-->
<div class="modal fade" id="leadsSourceDetails" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title details-title"><span id="lead_title"> </span> <span id="lead_type"> </span> Leads Source</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body table-responsive" id="leadsSourceDTtable_container">
                <table class="table table-striped table-hoverable table-hover w-100" id="leadsSourceDTtable"></table>
            </div>
        </div>
    </div>
</div>

<!--Next Contactable leads-->
<div class="modal fade" id="nextContacble" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title details-title">Next Contactable Leads</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body table-responsive">
                <table class="table table-bordered leadPipelineTbl">
                    <thead>
                        <tr class="table-info">
                            <th scope="col">ID</th>
                            <th scope="col">Customer</th>
                            <th scope="col">Assigned To</th>
                            <th>status</th>
                            <th scope="col" class="text-right">Remaining Days</th>
                            <th scope="col" class="text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($next_contactable_leads as $lead) if($lead['lead_status'] == 630){ ?>
                            <tr>
                                <td><?php echo $lead["id"]; ?></td>
                                <td><?php echo $lead["customer_name"]; ?><br><?php echo $lead['customer_mobile']; ?></td>
                                <td><?php echo $lead['assigned_user']; ?></td>
                                <td><?php echo $lead['status_name']; ?></td>
                                <td class="text-center"><?php echo $lead["remain_days"]>=0?$lead["remain_days"]:'-'; ?></td>
                                <td class="text-center">
                                    <a target="_blank" href="/leads/lead/view/<?= $lead["id"];?>">
                                        <button class='btn btn-primary'>View</button>
                                    </a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="tasksDld" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title details-title">Tasks</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body table-responsive">
                <table class="table table-hover table-striped table-bordered w-100" id="created_dTtable">
                    <thead>
                        <tr class="table-info">
                            <th>Task Created At</th>
                            <th>Sub Lead ID</th>
                            <th>Main Lead ID</th>
                            <th>Customer</th>
                            <th>Assigned To</th>
                            <th>service Name</th>
                            <th class="t_visa_col">visa Status</th>
                            <th class="t_visa_col">Pre-Approval Status</th>
                            <th class="t_eid_col">EID Status</th>
                            <th>Task Status</th>
                            <th>SLA Violated</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tasks as $key => $value) { 
                            $badge_clr = $value['task_status'] == 'completed'?'success':($value['task_status'] == 'open'?'primary':'secondary');?>
                            <tr id="o<?php echo $value['id']; ?>" class="task_service_<?php echo $value['service_id']; ?>">
                                <td><?php echo date('d M Y H:i A', strtotime($value['task_created_at']));?></td>
                                <td><?php echo $value['lead_id']; ?></td>
                                <td><?php echo $value['lead_parent_id']; ?></td>
                                <td>
                                    <?php echo '<strong>' . $value['customer_name'] . '</strong><br />' . $value['customer_mobile'] . '<br />' . $value['customer_email']; ?></a>
                                </td>
                                <td><?php echo $value['assigned_user']; ?><br><?php echo $value['assigned_email']; ?></td>
                                <td><?php echo $value['service_name']; ?></td>
                                <td class="t_visa_col"><?php echo $value['visa_status']; ?></td>
                                <td class="t_visa_col"><?php echo $value['visa_status'] == 6 ?$value['pre_approval_status']:''; ?></td>
                                <td class="t_eid_col">
                                    <?php 
                                    $eid_status = '';
                                    if($value['eid_status'] == 9) {
                                        $eid_status = 'Outside Country';
                                    } else if ($value['eid_status'] == 10) {
                                        $eid_status = 'Finger Print';
                                    } else if ($value['eid_status'] == 11) {
                                        $eid_status = 'Submitted';
                                    }
                                    echo $eid_status;?>
                                </td>
                                <td><span class="badge badge-<?php echo $badge_clr;?>"><?php echo $value['task_status']; ?></span></td>
                                <td><span class="badge badge-<?php echo $value['sla_violated']=='Yes'?'danger':'secondary';?>"><?php echo $value['sla_violated']; ?></span></td>
                                <td>
                                    <a href="<?php echo base_url(); ?>leads/lead/view/<?php echo $value['lead_parent_id']; ?>" class="btn btn-sm btn-primary">View</a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo base_url(); ?>global/vendor/datatables/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">

<script>
    $("document").ready(function () {
        $('#date_filter').click(function(e){
            let fromDate = $('#from_date').val();
            let toDate = $('#to_date').val();
            if(fromDate == '' || toDate == '' || fromDate > toDate)
            {
                Swal.fire('Invalid Dates','You have selected invalid dates','error').then((value) => 
                {
                    window.location.href = "?all=true";
                });
            }else{
                window.location.href = "?period=date&from_date="+fromDate +"&to_date="+toDate;
            }
        });
        $('.leadsCount').click(function(e) {
            let start = $(this).data("start");
            let end = $(this).data("end");
            let type = $(this).data("type");
            let modal = $(this).data("details");
            let id = $(this).data("id");
            let status = $(this).data("status");
            let title = (id == 'online') ? 'Online - ' : (id == 'all' ? 'All' : 'Walk In');
            $('#lead_title').text(title);
            switch(status){
                case 'not_started' :
                    $('#lead_type').text('Not Started');
                break;
                case 'hold' :
                    $('#lead_type').text('Hold');
                break;
                case 'Inprogress' :
                    $('#lead_type').text('Inprogress');
                break;
                case 'Completed' :
                    $('#lead_type').text('Completed');
                break;
                case 'assigned' :
                    $('#lead_type').text('Assigned');
                break;
                case 'accepted' :
                    $('#lead_type').text('Accepted');
                break;
                case 'reassigned' :
                    $('#lead_type').text('Reassigned');
                break;
                case 'unassigned' :
                    $('#lead_type').text('Unassigned');
                break;
                case 'created' :
                    $('#lead_type').text('Created');
                break;
                case 'converted' :
                    $('#lead_type').text('Converted');
                break;
                default :
                    $('#lead_type').text('Disqualified');
                break;
            }   
            $("#leadsDTtable_container").html('<table class="table table-striped table-hoverable table-hover w-100" id="leadsDTtable"></table>');
            $("#leadsDTtable").dataTable({
                ajax: "/dashboard/fetch_leads_summary?startDate=" + start + "&endDate=" + end + "&type=" +
                    type + "&status=" + status + "&id=" + id + "&request=all",
                columns: [
                    {
                        "title": "Lead Id",
                        "data": "id",
                    },
                    {
                        "title": "Customer",
                        "data": "customer_name",
                    },
                    {
                        "title": "Created By",
                        "data": "creator"
                    },
                    {
                        "title": "Created Group",
                        "data": "creator_group"
                    },
                    {
                        "title": "Assigned To",
                        "data": "assigned_user_name"
                    },
                    {
                        "title": "Assigned Group",
                        "data": "assigned_group"
                    },
                    {
                        "title": "Status",
                        "data": "current_status"
                    },
                    {
                        "title": "Action",
                        "data": "id",
                        "render": function (data) {
                            return "<a href='/leads/lead/view/" + data +
                                "' target='_blank'><button class='btn btn-primary'>View</button></a>";
                        }
                    },
                ],
                destroy: true
            });
            $('#leadsDTtable thead').addClass('table-info');
            $("#leadsDetails").modal("show");
        });

        $('.getLeadSourceDetails').click(function(e) {
            let start = $(this).data("start");
            let end = $(this).data("end");
            let type = $(this).data("type");
            let id = $(this).data("id");
            let modal = $(this).data("details");
            let status = $(this).data("status");
            let title = (id == 'walkin') ? 'Walk-In - ' : (id == 'whatsapp' ? 'WhatsApp' : 'Email');
            $('#lead_title').text(title);
            switch(status){
                case 'walkin' :
                    $('#lead_type').text('Walk-In');
                break;
                case 'whatsapp' :
                    $('#lead_type').text('WhatsApp');
                break;
                case 'email' :
                    $('#lead_type').text('Email');
                break;
                default :
                    $('#lead_type').text('Walk-In');
                break;
            }   
            $("#leadsSourceDTtable_container").html('<table class="table table-striped table-hoverable table-hover w-100" id="leadsSourceDTtable"></table>');
            $("#leadsSourceDTtable").dataTable({
                ajax: "/dashboard/fetch_leads_source_details?startDate=" + start + "&endDate=" + end + "&type=" +
                    type + "&status=" + status + "&id=" + id + "&request=all",
                columns: [
                    {
                        "title": "Lead Id",
                        "data": "id",
                    },
                    {
                        "title": "Customer",
                        "data": "customer_name",
                    },
                    {
                        "title": "Created By",
                        "data": "creator"
                    },
                    {
                        "title": "Created Group",
                        "data": "creator_group"
                    },
                    {
                        "title": "Assigned To",
                        "data": "assigned_user_name"
                    },
                    {
                        "title": "Assigned Group",
                        "data": "assigned_group"
                    },
                    {
                        "title": "Status",
                        "data": "current_status"
                    },
                    {
                        "title": "Action",
                        "data": "id",
                        "render": function (data) {
                            return "<a href='/leads/lead/view/" + data +
                                "' target='_blank'><button class='btn btn-primary'>View</button></a>";
                        }
                    },
                ],
                destroy: true
            });
            $('#leadsSourceDTtable thead').addClass('table-info');
            $("#leadsSourceDetails").modal("show");
        });
        
        $(".getDetails").click(function () {
            var modal = $(this).data("details");
            if(modal == 'leadssummary'){
                if($(this).data("id") == 'Online'){
                    $('#online_leads_total_value').show();
                    $('#walk_in_leads_total_value').hide();                
                }else{
                    $('#walk_in_leads_total_value').show();
                    $('#online_leads_total_value').hide();                
                }
                $("#" + modal).modal("show");
            } else if(modal == 'nextContacble') {
                $("#" + modal).modal("show");
            } else if (modal == 'tasksDld') {
                let service_id = $(this).data("serviceid");
                $('#tasksDld tbody tr').hide();
                $("#" + modal).modal("show");
                $('.task_service_'+service_id).show();
                $('.t_visa_col,.t_eid_col').hide();
                if(service_id == 93){ // visa
                    $('.t_visa_col').show();
                } else if (service_id == 2){ //eid
                    $('.t_eid_col').show();
                }
            }
        });
    });

    function getNationalDetails() {
        $(".getcd").off();
        $(".getcd").click(function (e) {
            // alert("Clicked");
            // console.log("getContryDetails ==> ", $(this).data());
            // e.preventDefault();
            // /dashboard/get_leads_country_summary_details/United%20Arab%20Emirates/inprogress

            var start = $(this).data("start");
            var end = $(this).data("end");
            var country = $(this).data("country");
            var type = $(this).data("type");
            var status = $(this).data("status");

            // var date = $(this).data("date");
            // var user = $(this).data("user");
            // var name = $(this).data("name");

            var popup_name = $("#slaDetails .details-title").html(country + " - " + status);
            $("#slaDetailsDTtable_container").html('<table class="table table-striped table-hoverable table-hover w-100" id="slaDetailsDTtable"></table>');
            $("#slaDetailsDTtable").dataTable({
                ajax: "/dashboard/get_leads_country_summary_details?country=" + country + "&status=" + status + "&startDate=" +
                    start + "&endDate=" + end + "&type=" + type,
                columns: [{
                    "title": "id",
                    "data": "id"
                },
                {
                    "title": "Customer",
                    "data": "customer_name",
                    "render": function (data, row, row_data) {
                        // return data;
                        return "<strong>" + row_data.customer_name +
                            "</strong><br>" + row_data.customer_country_code +
                            row_data.customer_mobile + "<br>" + row_data.customer_email;
                    }
                },
                {
                    "title": "Status",
                    "data": "current_status"
                },
                {
                    "title": "Created At",
                    "data": "created_date"
                },
                {
                    "title": "Action",
                    "data": "id",
                    "render": function (data) {
                        return "<a href='/leads/lead/view/" + data +
                            "' target='_blank'><button class='btn btn-primary'>View</button></a>";
                    }
                },
                ],
                destroy: true
            });
            $("#slaDetails").modal("show");
        });
    }
    getNationalDetails();

    function getAssignedDetails() {
        $(".getad").off();
        $(".getad").click(function (e) {
            var start = $(this).data("start");
            var end = $(this).data("end");
            var user = $(this).data("user");
            var name = $(this).data("name");
            var type = $(this).data("type");
            var status = $(this).data("status");

            // var date = $(this).data("date");
            // var user = $(this).data("user");
            // var name = $(this).data("name");

            var popup_name = $("#slaDetails .details-title").html(name + " - " + status);
            $("#slaDetailsDTtable_container").html('<table class="table table-striped table-hoverable table-hover w-100" id="slaDetailsDTtable"></table>');

            $("#slaDetailsDTtable").dataTable({
                ajax: "/dashboard/get_leads_assigned_summary_details?user=" + user + "&status=" + status + "&startDate=" +
                    start + "&endDate=" + end + "&type=" + type,
                columns: [{
                    "title": "id",
                    "data": "id"
                },
                {
                    "title": "Customer",
                    "data": "customer_name",
                    "render": function (data, row, row_data) {
                        return "<strong>" + row_data.customer_name +
                            "</strong><br>" + row_data.customer_country_code +
                            row_data.customer_mobile + "<br>" + row_data.customer_email;
                    }
                },
                {
                    "title": "Status",
                    "data": "current_status"
                },
                {
                    "title": "Created At",
                    "data": "created_date"
                },
                {
                    "title": "Action",
                    "data": "id",
                    "render": function (data) {
                        return "<a href='/leads/lead/view/" + data +
                            "' target='_blank'><button class='btn btn-primary'>View</button></a>";
                    }
                },
                ],
                destroy: true
            });
            $("#slaDetails").modal("show");
        });
    }
    getAssignedDetails();

    function popupInit() {
        $("img.profile_pic").off();
        $("img.profile_pic").on("click", function (e) {
            e.preventDefault();
            var image = $(this).attr("src");
            var title = $(this).attr("title");
            // console.log('Image',image);
            // console.log("Logged");
            $("#profile_preview").attr("src", image);
            $(".image-title").html(title);
            $("#modelId").modal("show");
            // window.open(image,"_blank");
        })
        $(".lead_view").off();

        $(".lead_view").on('click', function (e) {
            e.preventDefault();
            var link = $(this).data("href");
            location.href = link;
        });
        getAssignedDetails();

    }
    popupInit();function popupInit() {
        $("img.profile_pic").off();
        $("img.profile_pic").on("click", function (e) {
            e.preventDefault();
            var image = $(this).attr("src");
            var title = $(this).attr("title");
            $("#profile_preview").attr("src", image);
            $(".image-title").html(title);
            $("#modelId").modal("show");
        })
        $(".lead_view").off();

        $(".lead_view").on('click', function (e) {
            e.preventDefault();
            var link = $(this).data("href");
            location.href = link;
        });
        getAssignedDetails();

    }
    popupInit();

    $("#nationality_table_dTtable").on("dt.draw", getNationalDetails).dataTable({ destroy: true });
    $("#nationality_table,#detailsDTtable").on("dt.draw", popupInit).dataTable({ destroy: true });

</script>