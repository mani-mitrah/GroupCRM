<?php
$seg1 = $this->uri->segment(1);
$seg2 = $this->uri->segment(2);
$seg3 = $this->uri->segment(3);
$seg4 = $this->uri->segment(4);
$accessible_websites = get_my_access($this->auth_user_id);

$websites = get_my_access_sites($this->auth_user_id);
$orderable_websites = get_my_orderable_access_sites($this->auth_user_id);
$groups = get_groups($this->auth_user_id);
// print_r($orderable_websites);
$is_contain_otg = is_contain_otg();
$get_user_availability = get_user_availability($this->auth_user_id);
?>
<style>
    span.select2.select2-container.select2-container--default {
        width: 100% !important;
    }

     #avail_toggle {
        display: none;
    }

    .avail_labeled {
        display: inline-block;
        width: 60px;
        height: 28px;
        background: #ccc;
        /* background: #4CAF50; */
        border-radius: 50px;
        position: relative;
        cursor: pointer;
        transition: background 0.3s;
        vertical-align: middle;
        transform: scale(0.75);
        margin-top: 5px;
    }

    .avail_labeled::after {
        content: "";
        width: 24px;
        height: 24px;
        background: white;
        position: absolute;
        top: 2px;
        left: 2px;
        border-radius: 50%;
        transition: 0.3s;
    }
    #avail_toggle:checked + .avail_labeled {
        background: #4CAF50;
    }
    #avail_toggle:checked + .avail_labeled::after {
        left: 34px;
    }
</style>
<div class="app-header header-shadow">
    <div class="container fiori-container">
        <div class="app-header__mobile-menu">
            <div>
                <button type="button" class="hamburger hamburger--elastic mobile-toggle-nav">
                    <span class="hamburger-box">
                        <span class="hamburger-inner"></span>
                    </span>
                </button>
            </div>
        </div>
        <div class="app-header__logo">
            <a href="/" data-toggle="tooltip" data-placement="bottom" title="OnTime Digital CRM" class="logo-src"></a>
        </div>
        <ul class="horizontal-nav-menu">
            <li>
                <a href="/" class="<?php if ($seg1 == "" || $seg2 == "dashboard") {
                                        echo 'active';
                                    } ?>">
                    <i class="nav-icon-big typcn typcn-directions"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <li class="dropdown">
                <a data-toggle="dropdown" data-offset="10" data-display="static" aria-expanded="true">
                    <i class="nav-icon-big typcn typcn-book"></i>
                    <span>Orders</span>
                    <i class="nav-icon-pointer icon ion-ios-arrow-down"></i>
                </a>
                <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu dropdown-menu-rounded">
                    <?php
                    if (in_array(501, $accessible_websites)) {
                    ?>
                        <div class="dropdown-mega-menu-sm p-0">
                        <?php
                    } else {
                        ?>
                            <div class="p-0">
                            <?php
                        }
                            ?>
                            <div class="grid-menu grid-menu-2col">

                                <div class="no-gutters row">
                                    <?php
                                    if (in_array(501, $accessible_websites)) {
                                        if (in_array(501, $accessible_websites) && count($accessible_websites) > 1) {
                                    ?>
                                            <div class="col-sm-4">
                                            <?php
                                        } else {
                                            ?>
                                                <div class="col-sm-12">
                                                <?php
                                            }
                                                ?>
                                                <!-- <div class="col-sm-4"> -->
                                                <div class="nav flex-column">
                                                    <div class="nav-item-header text-primary nav-item font-size-md">
                                                        Baraha
                                                    </div>
                                                    <a class="dropdown-item" href="<?php echo base_url(); ?>orders/baraha/vaccine">
                                                        Free Vaccine Orders
                                                    </a>
                                                    <a class="dropdown-item" href="<?php echo base_url(); ?>orders/baraha/index">
                                                        Manage Orders
                                                    </a>
                                                    <a class="dropdown-item" href="<?php echo base_url(); ?>orders/baraha/pcr_index">
                                                        Manage PCR Orders
                                                    </a>
                                                </div>
                                                </div>
                                            <?php
                                        }
                                        if (in_array(501, $accessible_websites)) {

                                            ?>
                                                <div class="col-sm-8 pl-lg-0 pb-lg-0 pt-lg-0">
                                                <?php
                                            } else {
                                                ?>
                                                    <div class="col pl-lg-0 pb-lg-0 pt-lg-0">
                                                    <?php
                                                }
                                                    ?>

                                                    <div class="nav flex-column">

                                                        <?php
                                                        $otg = 0;
                                                        foreach ($orderable_websites as $webs) {
                                                            if ($webs["id"] == 501) continue;
                                                            if ($webs["id"] == 502) {
                                                                $otg = 1;
                                                                continue;
                                                            };
                                                            if ($webs["is_orders_contains"] == 0) continue;
                                                            if ($webs['short_name'] == "property") {
                                                        ?>

                                                                <a href="<?php echo base_url(); ?>orders/<?php echo $webs['short_name']; ?>/list" class="dropdown-item-desc dropdown-item">
                                                                    <span style="font-weight: 500;"><?php echo $webs["website_name"]; ?></span>
                                                                </a>
                                                            <?php
                                                            } else {
                                                            ?>
                                                                <a href="<?php echo base_url(); ?>orders/<?php echo $webs['short_name']; ?>" class="dropdown-item-desc dropdown-item">
                                                                    <span style="font-weight: 500;"><?php echo $webs["website_name"]; ?></span>
                                                                </a>
                                                            <?php
                                                            }
                                                            ?>

                                                            <div class="divider mt-2 mb-2"></div>
                                                        <?php
                                                        }

                                                        if ($otg == 1 || $is_contain_otg > 0) { ?>
                                                            <a href="<?php echo base_url(); ?>orders/ontimegov" class="dropdown-item-desc dropdown-item">
                                                                <span style="font-weight: 500;">OnTime Gov</span>
                                                            </a>
                                                            <div class="divider mt-2 mb-2"></div>
                                                        <?php
                                                        }
                                                        if (in_array(510, $accessible_websites)) {
                                                        ?>
                                                            <a href="<?php echo base_url(); ?>leads/lead/translator" class="dropdown-item-desc dropdown-item">
                                                                <span style="font-weight: 500;">Translation</span>
                                                            </a>
                                                            <div class="divider mt-2 mb-2"></div>
                                                        <?php
                                                        }
                                                        ?>

                                                        <?php
                                                        if (in_array(74, $groups)) {
                                                        ?>
                                                            <a href="<?php echo base_url(); ?>leads/lead/golden_cube_new" class="dropdown-item-desc dropdown-item">
                                                                <span style="font-weight: 500;">Golden Cube</span>
                                                            </a>
                                                            <div class="divider mt-2 mb-2"></div>
                                                        <?php
                                                        }
                                                        ?>

                                                        <?php
                                                        if (in_array(82, $groups)) {
                                                        ?>
                                                            <a href="<?php echo base_url(); ?>leads/lead/lab_new" class="dropdown-item-desc dropdown-item">
                                                                <span style="font-weight: 500;">Tanfeeth</span>
                                                            </a>
                                                            <div class="divider mt-2 mb-2"></div>
                                                        <?php
                                                        }
                                                        ?>
                                                        <?php
                                                        if (in_array(98, $groups)) {
                                                        ?>
                                                            <a href="<?php echo base_url(); ?>leads/lead/lab_new_enbd" class="dropdown-item-desc dropdown-item">
                                                                <span style="font-weight: 500;">Tanfeeth - ENBD</span>
                                                            </a>
                                                            <div class="divider mt-2 mb-2"></div>
                                                        <?php
                                                        }
                                                        ?>
                                                        <?php
                                                        if (in_array(71, $groups) || in_array(81, $groups) || in_array(52, $groups) || in_array(54, $groups) ) {
                                                        ?>

                                                            <a href="<?php echo base_url(); ?>leads/lead/general_pack_sale_new" class="dropdown-item-desc dropdown-item">
                                                                <span style="font-weight: 500;">General - Package Sale</span>
                                                            </a>
                                                        <?php
                                                        }
                                                        ?>

                                                    </div>
                                                    </div>
                                                </div>
                                            </div>
                                </div>
                            </div>
            </li>
            <li>
                <a href="/enquiries/all/index" class="<?php if ($seg1 == "enquiries") {
                                                            echo 'active';
                                                        } ?>">
                    <i class="nav-icon-big typcn typcn-messages"></i>
                    <span>Enquiries</span>
                </a>
            </li>
            <li class="dropdown">
                <a data-toggle="dropdown" data-offset="10" data-display="static" aria-expanded="false">
                    <i class="nav-icon-big typcn typcn-lightbulb"></i>
                    <span>Leads</span>
                    <i class="nav-icon-pointer icon ion-ios-arrow-down"></i>
                </a>
                <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu dropdown-menu-rounded p-0">
                    <div class="dropdown pt-2 pb-3">
                        <div class="grid-menu grid-menu-3col">
                            <div class="nav flex-column">
                                <div class="row no-gutters">
                                    <div class="col">
                                        <a href="<?php echo base_url(); ?>leads/lead/new" class="dropdown-item">
                                            Create Leads
                                        </a>
                                        <?php if (in_array(77, $groups)) { ?>
                                            <a href="<?php echo base_url(); ?>leads/lead/attestationnew" class="dropdown-item">
                                                Create Attestation Leads
                                            </a>
                                        <?php } ?>

                                        <a href="<?php echo base_url(); ?>leads/lead/manage" class="dropdown-item">
                                            Manage Leads
                                        </a>
                                        <a href="<?php echo base_url(); ?>leads/lead/rct_leads" class="dropdown-item">
                                            RCT Not Generated Leads
                                        </a>
                                        <?php if (in_array(41, $groups)) { ?>
                                            <a href="<?php echo base_url(); ?>leads/lead/ccmanage" class="dropdown-item">
                                                Call Center Contacts
                                            </a>
                                        <?php } ?>
                                        <a href="<?php echo base_url(); ?>leads/lead/manage_tasks" class="dropdown-item">
                                            Manage Tasks
                                        </a>
                                        <?php if($this->auth_user_id == 2061684168 ||$this->auth_user_id == 1143711453 ||$this->auth_user_id == 2845782377 ||$this->auth_user_id == 2411946200) {?>
                                        <a href="<?php echo base_url(); ?>leads/kanban/kanban_leads" class="dropdown-item">
                                            Kanban Leads
                                        </a>
                                        <a href="<?php echo base_url(); ?>leads/kanban/kanban_gc" class="dropdown-item">
                                            GC - Kanban Leads
                                        </a>
                                        <?php } ?>
                                        <a href="<?php echo base_url(); ?>leads/lead/bizlist" class="dropdown-item">
                                            Business Setup Leads
                                        </a>
                                        <a href="<?php echo base_url(); ?>leads/meeting/manage" class="dropdown-item">
                                            Meetings
                                        </a>
                                        <a href="<?php echo base_url(); ?>leads/templates/manage" class="dropdown-item">
                                            My Templates
                                        </a>
                                        <?php
                                        // if (in_array(520, $accessible_websites) && ($this->auth_user_role == 7 || $this->auth_user_role == 89)) {
                                        if ($this->auth_user_role == 87){
                                        ?>
                                            <a href="<?php echo base_url(); ?>leads/package/direct_invoice" class="dropdown-item">
                                                POS Direct Invoice Entry
                                            </a>
                                        <?php
                                        }
                                        ?>
                                        <?php
                                        // if (in_array(520, $accessible_websites) && ($this->auth_user_role == 7 || $this->auth_user_role == 89)) {
                                        if ($this->auth_user_role == 87){
                                        ?>
                                            <a href="<?php echo base_url(); ?>leads/package" class="dropdown-item">
                                                Lead Packages - GoldenCube
                                            </a>
                                            <a href="<?php echo base_url(); ?>leads/service" class="dropdown-item">
                                                Services - GoldenCube
                                            </a>
                                        <?php
                                        }
                                        ?>
                                        <?php
                                        // if (in_array(520, $accessible_websites) && ($this->auth_user_role == 7 || $this->auth_user_role == 89)) {
                                        if ($this->auth_user_role == 87){
                                        ?>
                                            <a href="<?php echo base_url(); ?>leads/package/besmart_packages" class="dropdown-item">
                                                Lead Packages - Be Smart
                                            </a>
                                        <?php
                                        }
                                        ?>

                                        <?php
                                        // if (in_array(520, $accessible_websites) && ($this->auth_user_role == 7 || $this->auth_user_role == 89)) {
                                        if ($this->auth_user_role == 87){
                                        ?>
                                            <a href="<?php echo base_url(); ?>leads/package/barahavan_packages" class="dropdown-item">
                                                Lead Packages - Baraha Van
                                            </a>
                                        <?php
                                        }
                                        ?>

                                        <?php
                                        if (in_array(71, $groups)) {
                                        ?>
                                            <a href="<?php echo base_url(); ?>leads/package/general" class="dropdown-item">
                                                Lead Packages - General
                                            </a>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </li>
            <?php //if ($this->auth_user_role > 5 || $this->auth_user_role == 1) { ?>
                <li class="dropdown">
                    <a data-toggle="dropdown" data-offset="10" data-display="static" aria-expanded="false">
                        <i class="nav-icon-big typcn typcn-clipboard"></i>
                        <span>Reports</span>
                        <i class="nav-icon-pointer icon ion-ios-arrow-down"></i>
                    </a>
                    <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu dropdown-menu-rounded p-0">
                        <div class="dropdown pt-2 pb-3">
                            <div class="grid-menu grid-menu-3col">
                                <div class="nav flex-column">
                                    <div class="row no-gutters">
                                        <div class="col">
                                            <!-- <a href="<?php echo base_url(); ?>reports/prepayments" class="dropdown-item">
                                                PrePayment Requests
                                            </a> -->
                                            <!-- <a href="<?php echo base_url(); ?>reports/lead_complete" class="dropdown-item">
                                                Leads - Completed
                                            </a> -->
                                            <!-- <?php
                                            //if (in_array(88, $groups) OR in_array(71, $groups) OR in_array(73, $groups)) {
                                            // if (($this->auth_user_role == 7) || (in_array(88, $groups) || in_array(71, $groups) || in_array(73, $groups))) {
                                            ?> -->
                                                <a href="<?php echo base_url(); ?>reports/final_reports" class="dropdown-item">
                                                    Final - Report
                                                </a>
                                            <?php //} ?>
                                            
                                            <?php
                                            if($this->auth_user_role == 7 || $this->auth_user_role == 87 || $this->auth_user_role == 89) {
                                            ?>
                                                <a href="<?php echo base_url(); ?>reports/statreport" class="dropdown-item">
                                                    Lead - Report
                                                </a>
                                            <?php } ?>

                                            <!-- <a href="/leads/lead/manage" class="dropdown-item">
                                            Manage Leads
                                        </a>
                                        <a href="/leads/meeting/manage" class="dropdown-item">
                                            Meetings
                                        </a>
                                        <a href="/leads/templates/manage" class="dropdown-item">
                                            My Templates
                                        </a> -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
            <?php //} ?>

            <?php
              if ($this->auth_user_role == 87){
            // if (in_array(82, $groups) && ($this->auth_user_role == 7 || $this->auth_user_role == 89)) {
            ?>
                <li>
                    <a href="<?php echo base_url(); ?>leads/package/labindex" class="<?php if ($seg1 == "" || $seg2 == "dashboard") {
                                                                                        echo 'active';
                                                                                    } ?>">
                        <i class="nav-icon-big typcn typcn-briefcase"></i>
                        <span>Tanfeeth Packages</span>
                    </a>
                </li>
            <?php
            }
            ?>
            <li>
                <a href="<?php echo $calendar_url; ?>"  target="_blank" class="<?php if ( $seg1 == "" ) {
                                                            ;
                                                        } ?>">
                    <i class="nav-icon-big typcn typcn-calendar-outline"></i>
                    <span>Calendar</span>
                </a>
            </li>

        </ul>
        <div class="app-header-right">
            <div class="header-btn-lg pr-0">
                <div class="widget-content p-0">
                    <div class="widget-content-wrapper">
                        <div class="widget-content-left">
                            <div class="btn-group">
                                <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="p-0 btn">
                                    <img width="42" class="rounded" src="<?php echo base_url(); ?>assets_new/images/avatars/3.jpg" alt="">
                                    <i class="fa fa-angle-down ml-2 opacity-8"></i>
                                </a>
                                <div tabindex="-1" role="menu" aria-hidden="true" class="rm-pointers dropdown-menu-lg dropdown-menu dropdown-menu-right">
                                    <div class="dropdown-menu-header">
                                        <div class="dropdown-menu-header-inner bg-plum-plate">
                                            <div class="menu-header-image opacity-2"></div>
                                            <!-- <div class="menu-header-image opacity-2" style="background-image: url('/assets_new/images/dropdown-header/city1.jpg');"></div> -->
                                            <div class="menu-header-content text-left">
                                                <div class="widget-content p-0">
                                                    <div class="widget-content-wrapper">
                                                        <div class="widget-content-left mr-3">
                                                            <img width="42" class="rounded-circle" src="/assets_new/images/avatars/3.jpg" alt="">
                                                        </div>
                                                        <div class="widget-content-left">
                                                            <div class="widget-heading"><?php echo $this->auth_first_name . " " . $this->auth_last_name;
                                                                                        ?>
                                                            </div>
                                                            <div class="widget-subheading opacity-8">
                                                                <?php echo $this->auth_email; ?>
                                                            </div>
                                                        </div>
                                                        <div class="widget-content-right mr-2">
                                                            <a href="<?php echo base_url(); ?>logout">
                                                                <button class="btn-pill btn-shadow btn-shine btn btn-focus">Logout
                                                                </button>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="scroll-area-xs" style="height: fit-content;">
                                        <div class="scrollbar-container ps">
                                            <ul class="nav flex-column">
                                                <li class="nav-item-header nav-item">Activity
                                                </li>
                                                <li class="nav-item">
                                                    <a href="<?php echo base_url(); ?>recover" class="nav-link">Recover Password
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <!-- <ul class="nav flex-column">
<li class="nav-item-divider mb-0 nav-item"></li>
</ul>
<div class="grid-menu grid-menu-2col">
<div class="no-gutters row">
<div class="col-sm-6">
<button class="btn-icon-vertical btn-transition btn-transition-alt pt-2 pb-2 btn btn-outline-warning">
<i class="pe-7s-chat icon-gradient bg-amy-crisp btn-icon-wrapper mb-2"></i>
Message Inbox
</button>
</div>
<div class="col-sm-6">
<button class="btn-icon-vertical btn-transition btn-transition-alt pt-2 pb-2 btn btn-outline-danger">
<i class="pe-7s-ticket icon-gradient bg-love-kiss btn-icon-wrapper mb-2"></i>
<b>Support Tickets</b>
</button>
</div>
</div>
</div> -->
                                    <!-- <ul class="nav flex-column">
<li class="nav-item-divider nav-item">
</li>
<li class="nav-item-btn text-center nav-item">
<button class="btn-wide btn btn-primary btn-sm">
Open Messages
</button>
</li>
</ul> -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php if (in_array(58, $groups)) { ?>
            <div class="app-header-right ml-3">
                <span>Busy</span>
                <input type="checkbox" id="avail_toggle" <?php echo ($get_user_availability == 1) ? 'checked' : ''; ?>
                    onclick="change_useravailability(this)" />
                <label for="avail_toggle" class="avail_labeled"></label>
                <span>Available</span>
            </div>
        <?php } ?>
        <div class="app-header__menu">
            <span>
                <button type="button" class="btn-icon btn-icon-only btn btn-primary btn-sm mobile-toggle-header-nav">
                    <span class="btn-icon-wrapper">
                        <i class="fa fa-ellipsis-v fa-w-6"></i>
                    </span>
                </button>
            </span>
        </div>
    </div>
</div>

<script> 
    function change_useravailability(el) {
        var previousState = !el.checked; // store old state before confirmation
        var is_available = el.checked ? 1 : 0;
        var value = is_available === 1 ? "Available" : "Busy";

        Swal.fire({
            icon: "info",
            title: "Are you sure to confirm?",
            text: "User is " + value,
            confirmButtonText: "Yes",
            showCancelButton: true,
            cancelButtonText: "Cancel"
        }).then((val) => {
            if (val.isConfirmed) {
                $.ajax({
                    // url: "<?php echo base_url(); ?>leads/lead/change_user_availability",
                    url: "<?php echo base_url(); ?>admin/user/change_user_availability",
                    type: "POST",
                    data: { is_available: is_available },
                    success: function(response) {
                        console.log(response);
                        if (response.status === 'success') {
                            Swal.fire("Updated!", "User Availability updated successfully.", "success").then(() => {
                                // location.reload(); // optional reload
                            });
                        } else {
                            Swal.fire("Error!", "Failed to update availability.", "error");
                            el.checked = previousState; // revert if backend fails
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log(error);
                        Swal.fire("Error!", "Something went wrong.", "error");
                        el.checked = previousState; // revert on error
                    }
                });
            } else {
                // User clicked Cancel — revert checkbox
                el.checked = previousState;
            }
        });
    }

</script>