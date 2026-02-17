<style>
    a[role="tab"].active.nav-link::after {
        top: 38px;
    }

    span.select2.select2-container.select2-container--default {
        border: 1px solid #ced4da;
        border-radius: 3px;
        padding: 10px 5px 4px 5px;
    }

    .zoho-pill {
        font-size: 58%;
        letter-spacing: 0.3px;
        font-weight: 500;
        float: right;
    }

    /* sidemenu */
    /* ========== CSS (add to your stylesheet) ========== */
    .content-row {
        display: flex;
        gap: 12px;
        align-items: flex-start;
        position: relative;
    }

    .sideMenuWrap {
        position: relative;
        display: flex;
        align-items: stretch;
    }

    /* toggle button — bigger, visible background */
    .toggleMenu {
        position: absolute;
        z-index: 3000;
        padding: 0;
        position: absolute;
        top: 12px;
        /* adjust as needed */
        right: -18px;
        /* sits just outside the menu */
        width: 24px;
        height: 48px;
        background: linear-gradient(180deg, #00287c, #3641b6);
        color: #fff;
        border: none;
        font-size: 18px;
        line-height: 48px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 6px 18px rgba(16, 24, 40, .12);
        cursor: pointer;
    }

    /* side menu takes fixed space when open */
    .sideMenu {
        flex: 0 0 320px;
        /* reserve width in layout */
        width: 320px;
        background: #fff;
        padding: 12px;
        border-radius: 8px;
        box-shadow: 0 6px 18px rgba(16, 24, 40, .06);
        overflow: auto;
        transition: flex-basis .22s ease, width .22s ease, padding .18s ease, opacity .18s ease;
        z-index: 2000;
    }

    /* Collapsed state - release layout space so table expands */
    .sideMenu.collapsed {
        flex-basis: 0 !important;
        width: 0 !important;
        padding: 0 !important;
        margin: 0 !important;
        border-radius: 0 !important;
        opacity: 0;
        pointer-events: none;
        box-shadow: none;
    }

    /* default: button hugs the open menu */
    .sideMenu+.toggleMenu {
        right: -18px;
        /* offset just outside the menu edge */
        transform: rotate(0deg);
        transition: right .22s ease, transform .22s ease;
    }

    /* collapsed: button shifts to collapsed edge + flips arrow */
    .sideMenu.collapsed+.toggleMenu {
        right: -24px;
        /* adjust so button sits snug on collapsed edge */
        transform: rotate(180deg);
    }

    /* keep overlay rules the same... */
    .sideMenuOverlay {
        position: fixed;
        inset: 0;
        display: none;
        /* hidden by default */
        background: rgba(0, 0, 0, 0.35);
        z-index: 1800;
    }

    /* table area should take remaining space */
    .tableWrapper {
        flex: 1 1 0%;
        min-width: 0;
        transition: margin .18s ease;
    }

    /* responsive tweaks */
    @media (max-width: 900px) {
        .sideMenu {
            position: fixed;
            left: 12px;
            top: 80px;
            height: 70vh;
            width: calc(100% - 48px);
            max-width: none;
            transform: none;
            flex: 0 0 auto;
        }

        .toggleMenu {
            right: auto;
            left: 12px;
            top: 18px;
        }

        /* keep the collapsed transform for mobile if you prefer sliding animation */
        .sideMenu.collapsed {
            transform: translateX(-110%);
            opacity: 0;
            pointer-events: none;
        }
    }


    .sideMenuOverlay.show {
        display: block;
    }

    /* small internal scroll containers used in your script */
    .scroll-box {
        max-height: calc(100% - 56px);
        /* free space for search/title */
        overflow: auto;
    }

    /* table area should take remaining space */
    .tableWrapper {
        flex: 1 1 0%;
        min-width: 0;
        /* important so DataTables can shrink on small widths */
    }

    /* overlay for mobile */
    .sideMenuOverlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.35);
        z-index: 1000;
        display: none;
    }

    .sideMenuOverlay.show {
        display: block;
    }

    /* responsive tweaks */
    @media (max-width: 900px) {
        .sideMenu {
            position: fixed;
            left: 12px;
            top: 80px;
            height: 70vh;
            width: calc(100% - 48px);
            max-width: none;
            transform: translateY(0);
            /* use left slide but keep transform to collapse */
            border-radius: 8px;
        }

        .toggleMenu {
            right: auto;
            left: 12px;
            top: 18px;
        }

        .sideMenu.collapsed {
            transform: translateX(-110%);
        }
    }



    /* --- FLEX LAYOUT --- */
    .content-row {
        display: flex;
        flex-direction: row;
        align-items: flex-start;
        gap: 20px;
        padding: 15px;
        background: #f4f6f9;
    }

    /* --- INPUTS --- */
    .global-search,
    .filter-search {
        width: 100%;
        padding: 6px 8px;
        margin-bottom: 8px;
        border: 1px solid #ccc;
        border-radius: 6px;
        font-size: 13px;
    }

    /* --- SCROLLABLE BOX INSIDE MENU --- */
    .scroll-box {
        max-height: 180px;
        overflow-y: auto;
        border: 1px solid #ddd;
        padding: 4px;
        margin-bottom: 10px;
        background: #fff;
        border-radius: 6px;
    }

    /* --- THIN SCROLLBARS (ALL) --- */
    .sideMenu,
    .tableWrapper,
    .scroll-box {
        scrollbar-width: thin;
        scrollbar-color: #aaa #f1f1f1;
    }

    .sideMenu::-webkit-scrollbar,
    .tableWrapper::-webkit-scrollbar,
    .scroll-box::-webkit-scrollbar {
        width: 6px;
        height: 6px;
    }

    .sideMenu::-webkit-scrollbar-track,
    .tableWrapper::-webkit-scrollbar-track,
    .scroll-box::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    .sideMenu::-webkit-scrollbar-thumb,
    .tableWrapper::-webkit-scrollbar-thumb,
    .scroll-box::-webkit-scrollbar-thumb {
        background-color: #999;
        border-radius: 4px;
    }

    /* --- LABEL FIX --- */
    .option-item label {
        margin-bottom: 0px !important;
        display: block;
    }

    /* Bottom line input style */

    .customer_filter .info_field {
        border: none;
        border-bottom: 1px solid #ccc;
        border-radius: 0;
        padding: 4px 2px;
        font-size: 0.875rem;
        width: 100%;
        outline: none;
    }

    .customer_filter .info_field:focus {
        border-bottom: 1px solid #007bff;
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
                                    <span class="d-inline-block">Leads</span>
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
                                            <li class="breadcrumb-item">
                                                <a href="#">Leads</a>
                                            </li>
                                            <li class="active breadcrumb-item">
                                                <a href="javascript:void(0);">Manage</a>
                                            </li>
                                        </ol>
                                    </nav>
                                </div>
                            </div>
                        </div>
                        <div class="page-title-actions">
                            <div class="d-inline-block dropdown">
                                <a href="<?php echo base_url(); ?>leads/lead/new">
                                    <button type="button" class="btn btn-gradient-primary btn-shadow btn-wide">
                                        <span class="btn-icon-wrapper pr-1 opacity-7">
                                            <i class="fa fa-plus"></i>
                                        </span>
                                        Create Lead
                                    </button>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="app-inner-bar">
                    <div class="fiori-container" style="width:100%;align-content:center;">
                        <div class="inner-bar-center">

                            <ul class="nav nav_tabs">
                                <li class="nav-item">
                                    <a role="tab" data-toggle="tab" class="nav-link" href="#unassigned">
                                        <button class="btn">
                                            Unassigned Leads <span
                                                class="badge badge-primary"><?php echo count($unassigned_leads); ?></span>
                                        </button>
                                    </a>
                                </li>


                                <?php if (in_array(74, $group_ids) && $this->auth_user_role == 84) { ?>
                                    <li class="nav-item">
                                        <a role="tab" data-toggle="tab" class="nav-link" href="#created">
                                            <button class="btn">
                                                Group Created Leads <span
                                                    class="badge badge-primary"><?php echo $created_leads_count; ?></span>
                                            </button>
                                        </a>
                                    </li>

                                    <li class="nav-item">
                                        <a role="tab" data-toggle="tab" class="nav-link" href="#assigned">
                                            <button class="btn">
                                                Assigned Leads <span
                                                    class="badge badge-primary"><?php echo $assigned_leads_count; ?></span>
                                            </button>
                                        </a>
                                    </li>
                                <?php } else if ($this->auth_user_role != 84) { ?>
                                    <li class="nav-item">
                                        <a role="tab" data-toggle="tab" class="nav-link <?php if ($this->auth_user_role == 7 || $this->auth_user_role == 89) { ?> active<?php } ?>" href="#created">
                                            <button class="btn">
                                                <?php if ($this->auth_user_role == 7 || $this->auth_user_role == 89) {
                                                    echo 'Group';
                                                } ?> Created Leads <span
                                                    class="badge badge-primary"><?php echo $created_leads_count; ?></span>
                                            </button>
                                        </a>
                                    </li>

                                    <li class="nav-item">
                                        <a role="tab" data-toggle="tab" class="nav-link" href="#assigned">
                                            <button class="btn">
                                                Assigned Leads <span
                                                    class="badge badge-primary"><?php echo $assigned_leads_count; ?></span>
                                            </button>
                                        </a>
                                    </li>
                                <?php
                                }
                                ?>
                                <!-- <li class="nav-item">
                                    <a role="tab" data-toggle="tab" class="nav-link" href="#receipt">
                                        <button class="btn">
                                            Receipts Not Generated <span
                                                class="badge badge-primary"><?php echo $receipt_not_count; ?></span>
                                        </button>
                                    </a>
                                </li> -->
                                <?php
                                if ($this->auth_user_role == 7 || $this->auth_user_role == 89) {
                                ?>
                                    <li class="nav-item">
                                        <a role="tab" data-toggle="tab" class="nav-link" href="#reassigned">
                                            <button class="btn">
                                                Reassigned Leads <span
                                                    class="badge badge-primary"><?php echo $reassigned_leads_count; ?></span>
                                            </button>
                                        </a>
                                    </li>
                                <?php
                                }
                                ?>
                                <li class="nav-item">
                                    <a role="tab" data-toggle="tab" class="nav-link" href="#pos_leads">
                                        <button class="btn">
                                            POS Leads <span class="badge badge-primary" id="pos_leads_counts"></span>
                                        </button>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a role="tab" data-toggle="tab"
                                        class="nav-link <?php if ($this->auth_user_role < 6) { ?> active<?php } ?>"
                                        href="#manage">
                                        <button class="btn">
                                            Your Leads <span
                                                class="badge badge-primary"><?php echo $accepted_leads_count; ?></span>
                                        </button>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <!-- <a class="nav-link" data-toggle="tab" href="#converted">Converted</a> -->
                                    <a role="tab" data-toggle="tab" class="nav-link" href="#converted">
                                        <button class="btn">
                                            Converted Leads<span
                                                class="badge badge-primary"><?php echo $converted_leads_count; ?></span>
                                        </button>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <!-- <a class="nav-link" data-toggle="tab" href="#disqualified">Disqualified</a> -->
                                    <a role="tab" data-toggle="tab" class="nav-link" href="#disqualified">
                                        <button class="btn">
                                            Disqualified Leads<span
                                                class="badge badge-primary"><?php echo $disqualified_leads_count; ?></span>
                                        </button>
                                    </a>
                                </li>



                            </ul>
                        </div>
                    </div>
                </div>
                <div class="app-inner-layout app-inner-layout-page">
                    <div class="app-inner-layout__wrapper">
                        <div class="app-inner-layout__content">
                            <div class="tab-content container-fluid">
                                <?php if ($this->auth_user_role > 2) { ?>
                                    <div class="tab-pane tabs-animation fade show active" id="created" role="tabpanel">
                                    <?php } else {  ?>
                                        <div class="tab-pane tabs-animation fade show" id="created" role="tabpanel">
                                        <?php } ?>

                                        <div class="content-row">
                                            <div class="sideMenuWrap">
                                                <div class="sideMenu" id="sideMenu-created" role="region" aria-label="Table controls">
                                                    <div class="p-2 mb-2">
                                                        <form action="" method="post" id="pcr_date_form_created" class="w-100">
                                                            <div class="mb-3">
                                                                <label for="created_from_date" class="form-label small mb-1">From Date</label>
                                                                <input type="date" name="created_from_date" id="created_from_date" class="form-control"
                                                                    value="<?php echo isset($request['created_from_date']) ? $request['created_from_date'] : ''; ?>">
                                                            </div>

                                                            <div class="mb-3">
                                                                <label for="created_to_date" class="form-label small mb-1">To Date</label>
                                                                <input type="date" name="created_to_date" id="created_to_date" class="form-control"
                                                                    value="<?php echo isset($request['created_to_date']) ? $request['created_to_date'] : ''; ?>">
                                                            </div>

                                                            <div class="mb-0">
                                                                <button type="submit" class="btn btn-primary w-100" name="action" value="created">Apply</button>
                                                            </div>
                                                        </form>
                                                    </div>

                                                    <hr>

                                                    <h6 class="text-center">Column Visibility</h6>
                                                    <div class="columnToggles" id="createdColumnToggle"></div>
                                                    <hr>
                                                    <h6 class="text-center">Customer Filter</h6>
                                                    <div class="card-body active  customer_filter" id="created_customer_filter">
                                                        <div class="d-flex flex-column gap-2">

                                                            <!-- Lead ID -->
                                                            <div class="form-check">
                                                                <input class="form-check-input check_customer" type="checkbox" id="check_created_id">
                                                                <label class="form-check-label" for="check_created_id">Lead Id</label>
                                                                <input type="text" class="info_field d-none" id="created_lead_id" placeholder="Enter Lead Id">
                                                            </div>

                                                            <!-- Lead Name -->
                                                            <div class="form-check">
                                                                <input class="form-check-input check_customer" type="checkbox" id="check_created_name">
                                                                <label class="form-check-label" for="check_created_name">Lead Name</label>
                                                                <input type="text" class="info_field d-none" id="created_lead_name" placeholder="Enter Name">
                                                            </div>

                                                            <!-- Mobile -->
                                                            <div class="form-check">
                                                                <input class="form-check-input check_customer" type="checkbox" id="check_created_mobile">
                                                                <label class="form-check-label" for="check_created_mobile">Mobile</label>
                                                                <input type="text" class="info_field d-none" id="created_lead_mobile" placeholder="Enter Mobile">
                                                            </div>

                                                            <!-- Email -->
                                                            <div class="form-check">
                                                                <input class="form-check-input check_customer" type="checkbox" id="check_created_email">
                                                                <label class="form-check-label" for="check_created_email">Email</label>
                                                                <input type="email" class="info_field d-none" id="created_lead_email" placeholder="Enter Email">
                                                            </div>

                                                            <div id="created_error" class="text-danger small mt-1"></div>

                                                            <button type="button" class="btn btn-sm btn-primary w-100 mt-2" onclick="applyCustomerFilters('created_dTtable','created')">
                                                                <i class="fas fa-check"></i> Apply Filters
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <h6 class="text-center">Column Filters</h6>
                                                    <div class="columnFilters" id="createdColumnFilter"></div>
                                                </div>
                                                <button class="toggleMenu btn btn-sm" aria-expanded="true" aria-controls="sideMenu-created">⏴</button>

                                            </div>


                                            <div class="card-body table-responsive">
                                                <table style="width: 100%;"
                                                    class="table table-hover table-striped table-bordered"
                                                    id="created_dTtable">
                                                    <thead>
                                                        <tr>
                                                            <th>ID</th>
                                                            <th>Customer</th>
                                                            <th>Created By</th>
                                                            <th>Created Group</th>
                                                            <th>Created Time</th>
                                                            <th>Assigned to</th>
                                                            <th>Assigned Group</th>
                                                            <th>Assigned Time</th>
                                                            <th>Status</th>
                                                            <th>Applicant Name</th>
                                                            <th>Open Requests</th>
                                                            <th>Closed Requests</th>
                                                            <th>Country Options</th>
                                                            <th>Actions</th>
                                                            <!-- creator_group -->
                                                            <!-- assigned_group -->
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        foreach ($created_leads as $key => $value) {
                                                            if ($this->auth_user_role > 5 && $value["lead_parent_id"] != 0) continue;

                                                        ?>
                                                            <tr id="o<?php echo $value['id']; ?>">
                                                                <td data-href="<?php echo base_url(); ?>leads/lead/view/<?php echo $value['id']; ?>"
                                                                    class="lead_view_popup"><?php echo $value['id']; ?>
                                                                </td>
                                                                <td data-href="<?php echo base_url(); ?>leads/lead/view/<?php echo $value['id']; ?>" class="lead_view">
                                                                    <?php
                                                                    if ($value['lead_zoho_id'] == NULL) {
                                                                        if (in_array($value['lead_from'], ['OntimeGOV', 'GoldenCube', 'Baraha Van', 'DLD'])) {
                                                                            echo '<strong>' . $value['customer_name'] . ' <span class="badge-pill zoho-pill badge badge-info">WEBSITE</span></strong><br />' . $value['customer_mobile'] . '<br />' . $value['customer_email'];
                                                                        } else {
                                                                            echo '<strong>' . $value['customer_name'] . '</strong><br />' . $value['customer_mobile'] . '<br />' . $value['customer_email'];
                                                                        }
                                                                    } else {
                                                                        // $zoho_value = $value['lead_from'] != NULL && $value['lead_from'] != '' ? $value['lead_from'] : 'ZOHO';
                                                                        echo '<strong>' . $value['customer_name'] . ' <span class="badge-pill zoho-pill badge badge-primary">zoho</span></strong><br />' . $value['customer_mobile'] . '<br />' . $value['customer_email'];
                                                                    } ?></a>
                                                                </td>
                                                                <td data-href="<?php echo base_url(); ?>leads/lead/view/<?php echo $value['id']; ?>"
                                                                    class="lead_view">
                                                                    <?php echo $value['creator']; ?></td>

                                                                <td data-href="<?php echo base_url(); ?>leads/lead/view/<?php echo $value['id']; ?>"
                                                                    class="lead_view">
                                                                    <?php echo $value['creator_group']; ?></td>
                                                                <td data-href="<?php echo base_url(); ?>leads/lead/view/<?php echo $value['id']; ?>"
                                                                    class="lead_view">
                                                                    <?php echo date('d M Y H:i A', strtotime($value['lead_added_on'])); ?>
                                                                </td>
                                                                <td data-href="<?php echo base_url(); ?>leads/lead/preview/<?php echo $value['id']; ?>"
                                                                    class="<?php if (in_array($this->auth_user_role, [6, 7, 84, 86, 87])) {
                                                                                echo "lead_preview";
                                                                            } else {
                                                                                echo "";
                                                                            } ?>">
                                                                    <?php
                                                                    if ($value['assigned_to'] == $this->auth_user_id) {
                                                                        echo 'Self';
                                                                    } else {
                                                                        $assigned_user_data = get_user_display_data($value['assigned_to']);
                                                                        echo '<strong>' . $assigned_user_data['first_name'] . ' ' . $assigned_user_data['last_name'] . '</strong><br />' . $assigned_user_data['mobile'] . '<br />' . $assigned_user_data['email'];
                                                                    }
                                                                    ?>
                                                                </td>
                                                                <td data-href="<?php echo base_url(); ?>leads/lead/view/<?php echo $value['id']; ?>"
                                                                    class="lead_view">
                                                                    <?php echo $value['assigned_group']; ?>
                                                                </td>
                                                                <td data-href="<?php echo base_url(); ?>leads/lead/view/<?php echo $value['id']; ?>"
                                                                    class="lead_view">
                                                                    <?php echo date('d M Y H:i A', strtotime($value['assigned_on'])); ?>
                                                                </td>

                                                                <td <?php if ($value["lead_created_by"] == 2906815795) {
                                                                    ?> class="lead_status_update" data-href="<?php echo base_url(); ?>leads/lead/statusUpdate/<?php echo $value['id']; ?>" <?php   }   ?>>
                                                                    <?php echo $value['current_status']; ?>
                                                                </td>
                                                                <td data-href="<?php echo base_url(); ?>leads/lead/view/<?php echo $value['id']; ?>"
                                                                    class="lead_view">
                                                                    <?php echo $value['applicant_name']; ?>
                                                                </td>
                                                                <td><?php if ($value["total_no_subleads"] > 0) {
                                                                        echo $value['no_of_open_subleads'] . "/" . $value["total_no_subleads"];
                                                                    } else {
                                                                        echo "-";
                                                                    } ?></td>
                                                                <td><?php if ($value["total_no_subleads"] > 0) {
                                                                        echo $value['no_of_closed_subleads'] . "/" . $value["total_no_subleads"];
                                                                    } else {
                                                                        echo "-";
                                                                    } ?></td>
                                                                <td data-href="<?php echo base_url(); ?>leads/lead/view/<?php echo $value['id']; ?>"
                                                                    class="lead_view">
                                                                    <?php echo $value['country_options']; ?></td>
                                                                <td><a href="<?php echo base_url(); ?>leads/lead/view/<?php echo $value['id']; ?>"
                                                                        class="btn btn-sm btn-primary">View</a></td>
                                                            </tr>
                                                        <?php
                                                        }
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </div>

                                        </div>
                                        </div>

                                        <div class="tab-pane tabs-animation fade show" id="unassigned" role="tabpanel">
                                            <div class="content-row">
                                                <div class="sideMenuWrap">
                                                    <div class="sideMenu" id="sideMenu-unassigned" role="region" aria-label="Table controls">
                                                        <div class="p-2 mb-2">
                                                            <form action="" method="post" id="pcr_date_form_unassigned" class="w-100">
                                                                <div class="mb-3">
                                                                    <label for="unassigned_from_date" class="form-label small mb-1">From Date</label>
                                                                    <input type="date" name="unassigned_from_date" id="unassigned_from_date" class="form-control"
                                                                        value="<?php echo isset($request['unassigned_from_date']) ? $request['unassigned_from_date'] : ''; ?>">
                                                                </div>

                                                                <div class="mb-3">
                                                                    <label for="unassigned_to_date" class="form-label small mb-1">To Date</label>
                                                                    <input type="date" name="unassigned_to_date" id="unassigned_to_date" class="form-control"
                                                                        value="<?php echo isset($request['unassigned_to_date']) ? $request['unassigned_to_date'] : ''; ?>">
                                                                </div>

                                                                <div class="mb-0">
                                                                    <button type="submit" class="btn btn-primary w-100" name="action" value="unassigned">Apply</button>
                                                                </div>
                                                            </form>
                                                        </div>


                                                        <hr>


                                                        <h6 class="text-center">Column Visibility</h6>
                                                        <div class="columnToggles" id="unassignedColumnToggle"></div>
                                                        <hr>
                                                        <h6 class="text-center">Customer Filter</h6>
                                                        <div class="card-body active  customer_filter" id="unassigned_customer_filter">
                                                            <div class="d-flex flex-column gap-2">

                                                                <!-- Lead ID -->
                                                                <div class="form-check">
                                                                    <input class="form-check-input check_customer" type="checkbox" id="check_unassigned_id">
                                                                    <label class="form-check-label" for="check_unassigned_id">Lead Id</label>
                                                                    <input type="text" class="info_field d-none" id="unassigned_lead_id" placeholder="Enter Lead Id">
                                                                </div>

                                                                <!-- Lead Name -->
                                                                <div class="form-check">
                                                                    <input class="form-check-input check_customer" type="checkbox" id="check_unassigned_name">
                                                                    <label class="form-check-label" for="check_unassigned_name">Lead Name</label>
                                                                    <input type="text" class="info_field d-none" id="unassigned_lead_name" placeholder="Enter Name">
                                                                </div>

                                                                <!-- Mobile -->
                                                                <div class="form-check">
                                                                    <input class="form-check-input check_customer" type="checkbox" id="check_unassigned_mobile">
                                                                    <label class="form-check-label" for="check_unassigned_mobile">Mobile</label>
                                                                    <input type="text" class="info_field d-none" id="unassigned_lead_mobile" placeholder="Enter Mobile">
                                                                </div>

                                                                <!-- Email -->
                                                                <div class="form-check">
                                                                    <input class="form-check-input check_customer" type="checkbox" id="check_unassigned_email">
                                                                    <label class="form-check-label" for="check_unassigned_email">Email</label>
                                                                    <input type="email" class="info_field d-none" id="unassigned_lead_email" placeholder="Enter Email">
                                                                </div>

                                                                <div id="unassigned_error" class="text-danger small mt-1"></div>

                                                                <button class="btn btn-sm btn-primary w-100 mt-2" onclick="applyCustomerFilters('unassigned_dTtable','unassigned')">
                                                                    <i class="fas fa-check"></i> Apply Filters
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <hr>

                                                        <h6 class="text-center">Column Filters</h6>
                                                        <div class="columnFilters" id="unassignedColumnFilter"></div>
                                                    </div>
                                                    <button class="toggleMenu btn btn-sm" aria-expanded="true" aria-controls="sideMenu-unassigned">⏴</button>

                                                </div>

                                                <div class="card-body table-responsive">
                                                    <table style="width: 100%;"
                                                        class="table table-hover table-striped table-bordered"
                                                        id="unassigned_dTtable">
                                                        <thead>
                                                            <tr>
                                                                <th>ID</th>
                                                                <th>Customer</th>
                                                                <th>Created By</th>
                                                                <th>Created Group</th>
                                                                <th>Created Time</th>
                                                                <th>Status</th>
                                                                <th>Actions</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            foreach ($unassigned_leads as $key => $value) { ?>
                                                                <tr id="o<?php echo $value['id']; ?>">
                                                                    <td data-href="/leads/lead/view/<?php echo $value['id']; ?>"
                                                                        class="lead_view_popup"><?php echo $value['id']; ?>
                                                                    </td>
                                                                    <td data-href="/leads/lead/view/<?php echo $value['id']; ?>"
                                                                        class="lead_view">
                                                                        <?php
                                                                        if ($value['lead_zoho_id'] == NULL) {
                                                                            if (in_array($value['lead_from'], ['OntimeGOV', 'GoldenCube', 'Baraha Van', 'DLD'])) {
                                                                                echo '<strong>' . $value['customer_name'] . ' <span class="badge-pill zoho-pill badge badge-info">WEBSITE</span></strong><br />' . $value['customer_mobile'] . '<br />' . $value['customer_email'];
                                                                            } else {
                                                                                echo '<strong>' . $value['customer_name'] . '</strong><br />' . $value['customer_mobile'] . '<br />' . $value['customer_email'];
                                                                            }
                                                                        } else {
                                                                            // $zoho_value = $value['lead_from'] != NULL && $value['lead_from'] != '' ? $value['lead_from'] : 'ZOHO';
                                                                            echo '<strong>' . $value['customer_name'] . ' <span class="badge-pill zoho-pill badge badge-primary">zoho</span></strong><br />' . $value['customer_mobile'] . '<br />' . $value['customer_email'];
                                                                        } ?></a>
                                                                    </td>
                                                                    <td data-href="/leads/lead/view/<?php echo $value['id']; ?>"
                                                                        class="lead_view">
                                                                        <?php echo $value['creator']; ?></td>

                                                                    <td data-href="/leads/lead/view/<?php echo $value['id']; ?>"
                                                                        class="lead_view">
                                                                        <?php echo $value['creator_group']; ?></td>
                                                                    <td data-href="/leads/lead/view/<?php echo $value['id']; ?>"
                                                                        class="lead_view">
                                                                        <?php echo date('d M Y H:i A', strtotime($value['lead_added_on'])); ?>
                                                                    </td>
                                                                    <td> <?php echo $value['current_status']; ?></td>
                                                                    <td><a href="<?php echo base_url(); ?>leads/lead/view/<?php echo $value['id']; ?>" class="btn btn-sm btn-primary">View</a></td>
                                                                </tr>
                                                            <?php
                                                            }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                </div>

                                            </div>
                                        </div>

                                        <?php
                                        if ($this->auth_user_role > 5) {
                                            if (count($unassigned_leads) > 0) {
                                        ?>
                                                <!-- <div class="tab-pane tabs-animation fade show active" id="new" role="tabpanel">
                                            <div class="row justify-content-center">
                                                <div class="col-xl-10">
                                                    <div class="main-card mb-3 card mt-4">
                                                        <div class="card-body">
                                                            <table style="width: 100%;" id="enq_dTable" class="table table-hover table-striped table-bordered">
                                                                <thead>
                                                                    <tr>
                                                                        <th>#</th>
                                                                        <th>Customer</th>
                                                                        <th>Category</th>
                                                                        <th>Service</th>
                                                                        <th>Lead Group</th>
                                                                        <th>Created Date</th>
                                                                        <th>Actions</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                    foreach ($unassigned_leads as $key => $value) {
                                                                        if ($this->auth_user_role > 5 && $value["lead_parent_id"] != 0) continue;

                                                                    ?>
                                                                        <tr>
                                                                            <td class="lead_preview" data-href="<?php echo base_url(); ?>leads/lead/preview/<?php echo $value['id']; ?>">
                                                                                <?php echo $value['id']; ?></td>
                                                                            <td class="lead_preview" data-href="<?php echo base_url(); ?>leads/lead/preview/<?php echo $value['id']; ?>">
                                                                                <?php echo '<strong>' . $value['customer_name'] . '</strong><br />' . $value['customer_mobile'] . '<br />' . $value['customer_email']; ?>
                                                                            </td>
                                                                            <td class="lead_preview" data-href="<?php echo base_url(); ?>leads/lead/preview/<?php echo $value['id']; ?>">
                                                                                <?php echo $value['category_name']; ?></td>
                                                                            <td class="lead_preview" data-href="<?php echo base_url(); ?>leads/lead/preview/<?php echo $value['id']; ?>">
                                                                                <?php echo $value['service_name']; ?></td>
                                                                            <td class="lead_preview" data-href="<?php echo base_url(); ?>leads/lead/preview/<?php echo $value['id']; ?>">
                                                                                <?php echo $value['group_name']; ?></td>
                                                                            <td class="lead_preview" data-href="<?php echo base_url(); ?>leads/lead/preview/<?php echo $value['id']; ?>">
                                                                                <?php echo date('d M Y H:i A', strtotime($value['lead_added_on'])); ?>
                                                                            </td>
                                                                            <td>
                                                                                <a href="<?php echo base_url(); ?>leads/lead/accept/<?php echo $value['id']; ?>" class="btn btn-block btn-sm btn-success">Accept</a>
                                                                                <a href="#meetingModal" class="btn btn-danger btn-block btn-sm open-meetingDialog" data-toggle="modal" data-leadid="<?php echo $value['id']; ?>">
                                                                                    Reject
                                                                                </a>
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
                                        </div> -->
                                            <?php
                                            }
                                            ?>



                                            <div class="tab-pane tabs-animation" id="reassigned" role="tabpanel">
                                                <div class="content-row">
                                                    <div class="sideMenuWrap">
                                                        <div class="sideMenu" id="sideMenu-reassigned" role="region" aria-label="Table controls">
                                                            <div class="p-2 mb-2">
                                                                <form action="" method="post" id="pcr_date_form_reassigned" class="w-100">
                                                                    <div class="mb-3">
                                                                        <label for="reassigned_from_date" class="form-label small mb-1">From Date</label>
                                                                        <input type="date" name="reassigned_from_date" id="reassigned_from_date" class="form-control"
                                                                            value="<?php echo isset($request['reassigned_from_date']) ? $request['reassigned_from_date'] : ''; ?>">
                                                                    </div>

                                                                    <div class="mb-3">
                                                                        <label for="reassigned_to_date" class="form-label small mb-1">To Date</label>
                                                                        <input type="date" name="reassigned_to_date" id="reassigned_to_date" class="form-control"
                                                                            value="<?php echo isset($request['reassigned_to_date']) ? $request['reassigned_to_date'] : ''; ?>">
                                                                    </div>

                                                                    <div class="mb-0">
                                                                        <button type="submit" class="btn btn-primary w-100" name="action" value="reassigned">Apply</button>
                                                                    </div>
                                                                </form>
                                                            </div>


                                                            <hr>


                                                            <h6 class="text-center">Column Visibility</h6>
                                                            <div class="columnToggles" id="reassignedColumnToggle"></div>
                                                            <hr>
                                                            <h6 class="text-center">Customer Filter</h6>
                                                            <div class="card-body active  customer_filter" id="reassigned_customer_filter">
                                                                <div class="d-flex flex-column gap-2">

                                                                    <!-- Lead ID -->
                                                                    <div class="form-check">
                                                                        <input class="form-check-input check_customer" type="checkbox" id="check_reassigned_id">
                                                                        <label class="form-check-label" for="check_reassigned_id">Lead Id</label>
                                                                        <input type="text" class="info_field d-none" id="reassigned_lead_id" placeholder="Enter Lead Id">
                                                                    </div>

                                                                    <!-- Lead Name -->
                                                                    <div class="form-check">
                                                                        <input class="form-check-input check_customer" type="checkbox" id="check_reassigned_name">
                                                                        <label class="form-check-label" for="check_reassigned_name">Lead Name</label>
                                                                        <input type="text" class="info_field d-none" id="reassigned_lead_name" placeholder="Enter Name">
                                                                    </div>

                                                                    <!-- Mobile -->
                                                                    <div class="form-check">
                                                                        <input class="form-check-input check_customer" type="checkbox" id="check_reassigned_mobile">
                                                                        <label class="form-check-label" for="check_reassigned_mobile">Mobile</label>
                                                                        <input type="text" class="info_field d-none" id="reassigned_lead_mobile" placeholder="Enter Mobile">
                                                                    </div>

                                                                    <!-- Email -->
                                                                    <div class="form-check">
                                                                        <input class="form-check-input check_customer" type="checkbox" id="check_reassigned_email">
                                                                        <label class="form-check-label" for="check_reassigned_email">Email</label>
                                                                        <input type="email" class="info_field d-none" id="reassigned_lead_email" placeholder="Enter Email">
                                                                    </div>

                                                                    <div id="reassigned_error" class="text-danger small mt-1"></div>

                                                                    <button class="btn btn-sm btn-primary w-100 mt-2" onclick="applyCustomerFilters('reassigned_dTtable','reassigned')">
                                                                        <i class="fas fa-check"></i> Apply Filters
                                                                    </button>
                                                                </div>
                                                            </div>
                                                            <hr>
                                                            <h6 class="text-center">Column Filters</h6>
                                                            <div class="columnFilters" id="reassignedColumnFilter"></div>
                                                        </div>
                                                        <button class="toggleMenu btn btn-sm" aria-expanded="true" aria-controls="sideMenu-reassigned">⏴</button>

                                                    </div>

                                                    <div class="card-body table-responsive">
                                                        <table style="width: 100%;"
                                                            class="table dTtable table-hover table-striped table-bordered"
                                                            id="reassigned_dTtable">
                                                            <thead>
                                                                <tr>
                                                                    <th>ID</th>
                                                                    <th>Customer</th>
                                                                    <th>Created By</th>
                                                                    <th>Created Group</th>
                                                                    <th>Created Time</th>
                                                                    <th>Assigned to</th>
                                                                    <th>Assigned Group</th>
                                                                    <th>Assigned Time</th>
                                                                    <th>Status</th>
                                                                    <th>Applicant Name</th>
                                                                    <th>Open Requests</th>
                                                                    <th>Closed Requests</th>
                                                                    <th>Actions</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                foreach ($reassigned_leads as $key => $value) {
                                                                    if ($this->auth_user_role > 5 && $value["lead_parent_id"] != 0) continue;

                                                                ?>
                                                                    <tr id="o<?php echo $value['id']; ?>">
                                                                        <td data-href="<?php echo base_url(); ?>leads/lead/view/<?php echo $value['id']; ?>"
                                                                            class="lead_view_popup"><?php echo $value['id']; ?>
                                                                        </td>
                                                                        <td data-href="<?php echo base_url(); ?>leads/lead/view/<?php echo $value['id']; ?>"
                                                                            class="lead_view">
                                                                            <?php
                                                                            if ($value['lead_zoho_id'] == NULL) {
                                                                                if (in_array($value['lead_from'], ['OntimeGOV', 'GoldenCube', 'Baraha Van', 'DLD'])) {
                                                                                    echo '<strong>' . $value['customer_name'] . ' <span class="badge-pill zoho-pill badge badge-info">WEBSITE</span></strong><br />' . $value['customer_mobile'] . '<br />' . $value['customer_email'];
                                                                                } else {
                                                                                    echo '<strong>' . $value['customer_name'] . '</strong><br />' . $value['customer_mobile'] . '<br />' . $value['customer_email'];
                                                                                }
                                                                            } else {
                                                                                // $zoho_value = $value['lead_from'] != NULL && $value['lead_from'] != '' ? $value['lead_from'] : 'ZOHO';
                                                                                echo '<strong>' . $value['customer_name'] . ' <span class="badge-pill zoho-pill badge badge-primary">zoho</span></strong><br />' . $value['customer_mobile'] . '<br />' . $value['customer_email'];
                                                                            } ?></a>
                                                                        </td>
                                                                        <td data-href="<?php echo base_url(); ?>leads/lead/view/<?php echo $value['id']; ?>"
                                                                            class="lead_view">
                                                                            <?php echo $value['creator']; ?></td>

                                                                        <td data-href="<?php echo base_url(); ?>leads/lead/view/<?php echo $value['id']; ?>"
                                                                            class="lead_view">
                                                                            <?php echo $value['creator_group']; ?></td>
                                                                        <td data-href="<?php echo base_url(); ?>leads/lead/view/<?php echo $value['id']; ?>"
                                                                            class="lead_view">
                                                                            <?php echo date('d M Y H:i A', strtotime($value['lead_added_on'])); ?>
                                                                        </td>
                                                                        <td data-href="<?php echo base_url(); ?>leads/lead/preview/<?php echo $value['id']; ?>"
                                                                            class="lead_preview">
                                                                            <?php
                                                                            if ($value['assigned_to'] == $this->auth_user_id) {
                                                                                echo 'Self';
                                                                            } else {
                                                                                $assigned_user_data = get_user_display_data($value['assigned_to']);
                                                                                echo '<strong>' . $assigned_user_data['first_name'] . ' ' . $assigned_user_data['last_name'] . '</strong><br />' . $assigned_user_data['mobile'] . '<br />' . $assigned_user_data['email'];
                                                                            }
                                                                            ?>
                                                                        </td>
                                                                        <td data-href="<?php echo base_url(); ?>leads/lead/view/<?php echo $value['id']; ?>"
                                                                            class="lead_view">
                                                                            <?php echo $value['assigned_group']; ?></td>
                                                                        <td data-href="<?php echo base_url(); ?>leads/lead/view/<?php echo $value['id']; ?>"
                                                                            class="lead_view">
                                                                            <?php echo date('d M Y H:i A', strtotime($value['assigned_on'])); ?>
                                                                        </td>

                                                                        <td <?php if ($value["lead_created_by"] == 2906815795) {
                                                                            ?> class="lead_status_update" data-href="<?php echo base_url(); ?>leads/lead/statusUpdate/<?php echo $value['id']; ?>" <?php } ?>>
                                                                            <?php echo $value['current_status']; ?>
                                                                        </td>
                                                                        <td data-href="<?php echo base_url(); ?>leads/lead/view/<?php echo $value['id']; ?>"
                                                                            class="lead_view">
                                                                            <?php echo $value['applicant_name']; ?>
                                                                        </td>
                                                                        <td><?php if ($value["total_no_subleads"] > 0) {
                                                                                echo $value['no_of_open_subleads'] . "/" . $value["total_no_subleads"];
                                                                            } else {
                                                                                echo "-";
                                                                            } ?></td>
                                                                        <td><?php if ($value["total_no_subleads"] > 0) {
                                                                                echo $value['no_of_closed_subleads'] . "/" . $value["total_no_subleads"];
                                                                            } else {
                                                                                echo "-";
                                                                            } ?></td>
                                                                        <td><a href="<?php echo base_url(); ?>leads/lead/view/<?php echo $value['id']; ?>"
                                                                                class="btn btn-sm btn-primary">View</a></td>
                                                                    </tr>
                                                                <?php
                                                                }
                                                                ?>
                                                            </tbody>
                                                        </table>
                                                    </div>

                                                </div>
                                            </div>


                                            <div class="tab-pane tabs-animation" id="assigned" role="tabpanel">
                                                <div class="content-row">
                                                    <div class="sideMenuWrap">
                                                        <div class="sideMenu" id="sideMenu-assigned" role="region" aria-label="Table controls">
                                                            <div class="p-2 mb-2">
                                                                <form action="" method="post" id="pcr_date_form_assigned" class="w-100">
                                                                    <div class="mb-3">
                                                                        <label for="assigned_from_date" class="form-label small mb-1">From Date</label>
                                                                        <input type="date" name="assigned_from_date" id="assigned_from_date" class="form-control"
                                                                            value="<?php echo isset($request['assigned_from_date']) ? $request['assigned_from_date'] : ''; ?>">
                                                                    </div>

                                                                    <div class="mb-3">
                                                                        <label for="assigned_to_date" class="form-label small mb-1">To Date</label>
                                                                        <input type="date" name="assigned_to_date" id="assigned_to_date" class="form-control"
                                                                            value="<?php echo isset($request['assigned_to_date']) ? $request['assigned_to_date'] : ''; ?>">
                                                                    </div>

                                                                    <div class="mb-0">
                                                                        <button type="submit" class="btn btn-primary w-100" name="action" value="assigned">Apply</button>
                                                                    </div>
                                                                </form>
                                                            </div>


                                                            <hr>


                                                            <h6 class="text-center">Column Visibility</h6>
                                                            <div class="columnToggles" id="assignedColumnToggle"></div>
                                                            <hr>
                                                            <h6 class="text-center">Customer Filter</h6>
                                                            <div class="card-body active  customer_filter" id="assigned_customer_filter">
                                                                <div class="d-flex flex-column gap-2">

                                                                    <!-- Lead ID -->
                                                                    <div class="form-check">
                                                                        <input class="form-check-input check_customer" type="checkbox" id="check_assigned_id">
                                                                        <label class="form-check-label" for="check_assigned_id">Lead Id</label>
                                                                        <input type="text" class="info_field d-none" id="assigned_lead_id" placeholder="Enter Lead Id">
                                                                    </div>

                                                                    <!-- Lead Name -->
                                                                    <div class="form-check">
                                                                        <input class="form-check-input check_customer" type="checkbox" id="check_assigned_name">
                                                                        <label class="form-check-label" for="check_assigned_name">Lead Name</label>
                                                                        <input type="text" class="info_field d-none" id="assigned_lead_name" placeholder="Enter Name">
                                                                    </div>

                                                                    <!-- Mobile -->
                                                                    <div class="form-check">
                                                                        <input class="form-check-input check_customer" type="checkbox" id="check_assigned_mobile">
                                                                        <label class="form-check-label" for="check_assigned_mobile">Mobile</label>
                                                                        <input type="text" class="info_field d-none" id="assigned_lead_mobile" placeholder="Enter Mobile">
                                                                    </div>

                                                                    <!-- Email -->
                                                                    <div class="form-check">
                                                                        <input class="form-check-input check_customer" type="checkbox" id="check_assigned_email">
                                                                        <label class="form-check-label" for="check_assigned_email">Email</label>
                                                                        <input type="email" class="info_field d-none" id="assigned_lead_email" placeholder="Enter Email">
                                                                    </div>

                                                                    <div id="assigned_error" class="text-danger small mt-1"></div>

                                                                    <button class="btn btn-sm btn-primary w-100 mt-2" onclick="applyCustomerFilters('assigned_dTtable','assigned')">
                                                                        <i class="fas fa-check"></i> Apply Filters
                                                                    </button>
                                                                </div>
                                                            </div>
                                                            <hr>

                                                            <h6 class="text-center">Column Filters</h6>
                                                            <div class="columnFilters" id="assignedColumnFilter"></div>
                                                        </div>
                                                        <button class="toggleMenu btn btn-sm" aria-expanded="true" aria-controls="sideMenu-assigned">⏴</button>

                                                    </div>

                                                    <div class="card-body table-responsive">
                                                        <table style="width: 100%;"
                                                            class="table dTtable table-hover table-striped table-bordered"
                                                            id="assigned_dTtable">
                                                            <thead>
                                                                <tr>
                                                                    <th>ID</th>
                                                                    <th>Customer</th>
                                                                    <th>Created By</th>
                                                                    <th>Created Group</th>
                                                                    <th>Created Time</th>
                                                                    <th>Assigned to</th>
                                                                    <th>Assigned Group</th>
                                                                    <th>Assigned Time</th>
                                                                    <th>Status</th>
                                                                    <th>Applicant Name</th>
                                                                    <th>Open Requests</th>
                                                                    <th>Closed Requests</th>
                                                                    <th class="d-none">Country Options</th>
                                                                    <th>Actions</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                foreach ($assigned_leads as $key => $value) {
                                                                    if ($this->auth_user_role > 5 && $value["lead_parent_id"] != 0) continue;

                                                                ?>
                                                                    <tr id="o<?php echo $value['id']; ?>">
                                                                        <td data-href="<?php echo base_url(); ?>leads/lead/view/<?php echo $value['id']; ?>"
                                                                            class="lead_view_popup"><?php echo $value['id']; ?>
                                                                        </td>
                                                                        <td data-href="<?php echo base_url(); ?>leads/lead/view/<?php echo $value['id']; ?>" class="lead_view">
                                                                            <?php
                                                                            if ($value['lead_zoho_id'] == NULL) {
                                                                                if (in_array($value['lead_from'], ['OntimeGOV', 'GoldenCube', 'Baraha Van', 'DLD'])) {
                                                                                    echo '<strong>' . $value['customer_name'] . ' <span class="badge-pill zoho-pill badge badge-info">WEBSITE</span></strong><br />' . $value['customer_mobile'] . '<br />' . $value['customer_email'];
                                                                                } else {
                                                                                    echo '<strong>' . $value['customer_name'] . '</strong><br />' . $value['customer_mobile'] . '<br />' . $value['customer_email'];
                                                                                }
                                                                            } else {
                                                                                // $zoho_value = $value['lead_from'] != NULL && $value['lead_from'] != '' ? $value['lead_from'] : 'ZOHO';
                                                                                echo '<strong>' . $value['customer_name'] . ' <span class="badge-pill zoho-pill badge badge-primary">zoho</span></strong><br />' . $value['customer_mobile'] . '<br />' . $value['customer_email'];
                                                                            } ?></a>
                                                                        </td>
                                                                        <td data-href="<?php echo base_url(); ?>leads/lead/view/<?php echo $value['id']; ?>"
                                                                            class="lead_view">
                                                                            <?php echo $value['creator']; ?></td>

                                                                        <td data-href="<?php echo base_url(); ?>leads/lead/view/<?php echo $value['id']; ?>"
                                                                            class="lead_view">
                                                                            <?php echo $value['creator_group']; ?></td>
                                                                        <td data-href="<?php echo base_url(); ?>leads/lead/view/<?php echo $value['id']; ?>"
                                                                            class="lead_view">
                                                                            <?php echo date('d M Y H:i A', strtotime($value['lead_added_on'])); ?>
                                                                        </td>
                                                                        <td data-href="<?php echo base_url(); ?>leads/lead/preview/<?php echo $value['id']; ?>"
                                                                            class="<?php if (in_array($this->auth_user_role, [6, 7, 84, 86, 87])) {
                                                                                        echo "lead_preview";
                                                                                    } else {
                                                                                        echo "";
                                                                                    } ?>">
                                                                            <?php
                                                                            if ($value['assigned_to'] == $this->auth_user_id) {
                                                                                echo 'Self';
                                                                            } else {
                                                                                $assigned_user_data = get_user_display_data($value['assigned_to']);
                                                                                echo '<strong>' . $assigned_user_data['first_name'] . ' ' . $assigned_user_data['last_name'] . '</strong><br />' . $assigned_user_data['mobile'] . '<br />' . $assigned_user_data['email'];
                                                                            }
                                                                            ?>
                                                                        </td>
                                                                        <td data-href="<?php echo base_url(); ?>leads/lead/view/<?php echo $value['id']; ?>"
                                                                            class="lead_view">
                                                                            <?php echo $value['assigned_group']; ?></td>
                                                                        <td data-href="<?php echo base_url(); ?>leads/lead/view/<?php echo $value['id']; ?>"
                                                                            class="lead_view">
                                                                            <?php echo date('d M Y H:i A', strtotime($value['assigned_on'])); ?>
                                                                        </td>

                                                                        <td <?php if ($value["lead_created_by"] == 2906815795) {
                                                                            ?> class="lead_status_update"
                                                                            data-href="<?php echo base_url(); ?>leads/lead/statusUpdate/<?php echo $value['id']; ?>"
                                                                            <?php } ?>> <?php echo $value['current_status']; ?>
                                                                        </td>
                                                                        <td data-href="<?php echo base_url(); ?>leads/lead/view/<?php echo $value['id']; ?>"
                                                                            class="lead_view">
                                                                            <?php echo $value['applicant_name']; ?>
                                                                        </td>
                                                                        <td><?php if ($value["total_no_subleads"] > 0) {
                                                                                echo $value['no_of_open_subleads'] . "/" . $value["total_no_subleads"];
                                                                            } else {
                                                                                echo "-";
                                                                            } ?></td>
                                                                        <td><?php if ($value["total_no_subleads"] > 0) {
                                                                                echo $value['no_of_closed_subleads'] . "/" . $value["total_no_subleads"];
                                                                            } else {
                                                                                echo "-";
                                                                            } ?></td>
                                                                        <td data-href="<?php echo base_url(); ?>leads/lead/view/<?php echo $value['id']; ?>"
                                                                            class="lead_view d-none">
                                                                            <?php echo $value['country_options']; ?></td>
                                                                        <td><a href="<?php echo base_url(); ?>leads/lead/view/<?php echo $value['id']; ?>"
                                                                                class="btn btn-sm btn-primary">View</a></td>
                                                                    </tr>
                                                                <?php
                                                                }
                                                                ?>
                                                            </tbody>
                                                        </table>
                                                    </div>

                                                </div>
                                            </div>
                                        <?php
                                        }
                                        ?>
                                        <div class="tab-pane tabs-animation" id="receipt" role="tabpanel">
                                            <div class="content-row">
                                                <div class="sideMenuWrap">
                                                    <div class="sideMenu" id="sideMenu-receipt" role="region" aria-label="Table controls">
                                                        <div class="p-2 mb-2">
                                                            <form action="" method="post" id="pcr_date_form_assigned" class="w-100">
                                                                <div class="mb-3">
                                                                    <label for="receipt_from_date" class="form-label small mb-1">From Date</label>
                                                                    <input type="date" name="receipt_from_date" id="receipt_from_date" class="form-control"
                                                                        value="<?php echo isset($request['receipt_from_date']) ? $request['receipt_from_date'] : ''; ?>">
                                                                </div>

                                                                <div class="mb-3">
                                                                    <label for="receipt_to_date" class="form-label small mb-1">To Date</label>
                                                                    <input type="date" name="receipt_to_date" id="receipt_to_date" class="form-control"
                                                                        value="<?php echo isset($request['receipt_to_date']) ? $request['receipt_to_date'] : ''; ?>">
                                                                </div>

                                                                <div class="mb-0">
                                                                    <button type="submit" class="btn btn-primary w-100" name="action" value="assigned">Apply</button>
                                                                </div>
                                                            </form>
                                                        </div>


                                                        <hr>


                                                        <h6 class="text-center">Column Visibility</h6>
                                                        <div class="columnToggles" id="receiptColumnToggle"></div>
                                                        <hr>
                                                        <h6 class="text-center">Customer Filter</h6>
                                                        <div class="card-body active  customer_filter" id="receipt_customer_filter">
                                                            <div class="d-flex flex-column gap-2">

                                                                <!-- Lead ID -->
                                                                <div class="form-check">
                                                                    <input class="form-check-input check_customer" type="checkbox" id="check_receipt_id">
                                                                    <label class="form-check-label" for="check_receipt_id">Lead Id</label>
                                                                    <input type="text" class="info_field d-none" id="receipt_lead_id" placeholder="Enter Lead Id">
                                                                </div>

                                                                <!-- Lead Name -->
                                                                <div class="form-check">
                                                                    <input class="form-check-input check_customer" type="checkbox" id="check_receipt_name">
                                                                    <label class="form-check-label" for="check_receipt_name">Lead Name</label>
                                                                    <input type="text" class="info_field d-none" id="receipt_lead_name" placeholder="Enter Name">
                                                                </div>

                                                                <!-- Mobile -->
                                                                <div class="form-check">
                                                                    <input class="form-check-input check_customer" type="checkbox" id="check_receipt_mobile">
                                                                    <label class="form-check-label" for="check_receipt_mobile">Mobile</label>
                                                                    <input type="text" class="info_field d-none" id="receipt_lead_mobile" placeholder="Enter Mobile">
                                                                </div>

                                                                <!-- Email -->
                                                                <div class="form-check">
                                                                    <input class="form-check-input check_customer" type="checkbox" id="check_receipt_email">
                                                                    <label class="form-check-label" for="check_receipt_email">Email</label>
                                                                    <input type="email" class="info_field d-none" id="receipt_lead_email" placeholder="Enter Email">
                                                                </div>

                                                                <div id="receipt_error" class="text-danger small mt-1"></div>

                                                                <button class="btn btn-sm btn-primary w-100 mt-2" onclick="applyCustomerFilters('receipt_dTtable','receipt')">
                                                                    <i class="fas fa-check"></i> Apply Filters
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <hr>

                                                        <h6 class="text-center">Column Filters</h6>
                                                        <div class="columnFilters" id="receiptColumnFilter"></div>
                                                    </div>
                                                    <button class="toggleMenu btn btn-sm" aria-expanded="true" aria-controls="sideMenu-receipt">⏴</button>
                                                </div>
                                                <div class="card-body table-responsive">
                                                    <table style="width: 100%;"
                                                        class="table dTtable table-hover table-striped table-bordered"
                                                        id="receipt_dTtable">
                                                        <thead>
                                                            <tr>
                                                                <th>ID</th>
                                                                <th>Customer</th>
                                                                <th>Created By</th>
                                                                <th>Created Group</th>
                                                                <th>Created Time</th>
                                                                <th>Assigned to</th>
                                                                <th>Assigned Group</th>
                                                                <th>Assigned Time</th>
                                                                <th>Status</th>
                                                                <th>Applicant Name</th>
                                                                <!-- <th>Open Requests</th>
                                                                <th>Closed Requests</th> -->
                                                                <th>Remarks</th>
                                                                <th class="d-none">Country Options</th>
                                                                <th>Actions</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            foreach ($receipt_not as $key => $value) {
                                                                if ($this->auth_user_role > 5 && $value["lead_parent_id"] != 0) continue;

                                                            ?>
                                                                <tr id="o<?php echo $value['id']; ?>">
                                                                    <td data-href="<?php echo base_url(); ?>leads/lead/view/<?php echo $value['id']; ?>"
                                                                        class="lead_view_popup"><?php echo $value['id']; ?>
                                                                    </td>
                                                                    <td data-href="<?php echo base_url(); ?>leads/lead/view/<?php echo $value['id']; ?>" class="lead_view">
                                                                        <?php
                                                                        if ($value['lead_zoho_id'] == NULL) {
                                                                            if (in_array($value['lead_from'], ['OntimeGOV', 'GoldenCube', 'Baraha Van', 'DLD'])) {
                                                                                echo '<strong>' . $value['customer_name'] . ' <span class="badge-pill zoho-pill badge badge-info">WEBSITE</span></strong><br />' . $value['customer_mobile'] . '<br />' . $value['customer_email'];
                                                                            } else {
                                                                                echo '<strong>' . $value['customer_name'] . '</strong><br />' . $value['customer_mobile'] . '<br />' . $value['customer_email'];
                                                                            }
                                                                        } else {
                                                                            // $zoho_value = $value['lead_from'] != NULL && $value['lead_from'] != '' ? $value['lead_from'] : 'ZOHO';
                                                                            echo '<strong>' . $value['customer_name'] . ' <span class="badge-pill zoho-pill badge badge-primary">zoho</span></strong><br />' . $value['customer_mobile'] . '<br />' . $value['customer_email'];
                                                                        } ?></a>
                                                                    </td>
                                                                    <td data-href="<?php echo base_url(); ?>leads/lead/view/<?php echo $value['id']; ?>"
                                                                        class="lead_view">
                                                                        <?php echo $value['creator']; ?></td>

                                                                    <td data-href="<?php echo base_url(); ?>leads/lead/view/<?php echo $value['id']; ?>"
                                                                        class="lead_view">
                                                                        <?php echo $value['creator_group']; ?></td>
                                                                    <td data-href="<?php echo base_url(); ?>leads/lead/view/<?php echo $value['id']; ?>"
                                                                        class="lead_view">
                                                                        <?php echo date('d M Y H:i A', strtotime($value['lead_added_on'])); ?>
                                                                    </td>
                                                                    <td data-href="<?php echo base_url(); ?>leads/lead/preview/<?php echo $value['id']; ?>"
                                                                        class="<?php if (in_array($this->auth_user_role, [6, 7, 84, 86, 87])) {
                                                                                    echo "lead_preview";
                                                                                } else {
                                                                                    echo "";
                                                                                } ?>">
                                                                        <?php
                                                                        if ($value['assigned_to'] == $this->auth_user_id) {
                                                                            echo 'Self';
                                                                        } else {
                                                                            $assigned_user_data = get_user_display_data($value['assigned_to']);
                                                                            echo '<strong>' . $assigned_user_data['first_name'] . ' ' . $assigned_user_data['last_name'] . '</strong><br />' . $assigned_user_data['mobile'] . '<br />' . $assigned_user_data['email'];
                                                                        }
                                                                        ?>
                                                                    </td>
                                                                    <td data-href="<?php echo base_url(); ?>leads/lead/view/<?php echo $value['id']; ?>"
                                                                        class="lead_view">
                                                                        <?php echo $value['assigned_group']; ?></td>
                                                                    <td data-href="<?php echo base_url(); ?>leads/lead/view/<?php echo $value['id']; ?>"
                                                                        class="lead_view">
                                                                        <?php echo date('d M Y H:i A', strtotime($value['assigned_on'])); ?>
                                                                    </td>

                                                                    <td <?php if ($value["lead_created_by"] == 2906815795) {
                                                                        ?> class="lead_status_update"
                                                                        data-href="<?php echo base_url(); ?>leads/lead/statusUpdate/<?php echo $value['id']; ?>"
                                                                        <?php } ?>> <?php echo $value['current_status']; ?>
                                                                    </td>
                                                                    <td data-href="<?php echo base_url(); ?>leads/lead/view/<?php echo $value['id']; ?>"
                                                                        class="lead_view">
                                                                        <?php echo $value['applicant_name']; ?>
                                                                    </td>
                                                                    <!-- <td><?php if ($value["total_no_subleads"] > 0) {
                                                                            echo $value['no_of_open_subleads'] . "/" . $value["total_no_subleads"];
                                                                        } else {
                                                                            echo "-";
                                                                        } ?></td>
                                                                    <td><?php if ($value["total_no_subleads"] > 0) {
                                                                            echo $value['no_of_closed_subleads'] . "/" . $value["total_no_subleads"];
                                                                        } else {
                                                                            echo "-";
                                                                        } ?></td> -->
                                                                    <td data-href="<?php echo base_url(); ?>leads/lead/view/<?php echo $value['id']; ?>"
                                                                        class="lead_view d-none">
                                                                        <?php echo $value['country_options']; ?></td>
                                                                    <td>
                                                                        <?php echo $value['last_446_remarks']; ?>
                                                                    </td>
                                                                    <td><a href="<?php echo base_url(); ?>leads/lead/view/<?php echo $value['id']; ?>"
                                                                            class="btn btn-sm btn-primary">View</a>
                                                                            <!-- <br>
                                                                            <?php if (!empty($value['last_446_remarks'])) {
                                                                                $str = $value['last_446_remarks'];
                                                                                preg_match('/href=([\'"]?)([^\'"\s>]+)\1/i', $str, $matches);
                                                                                $url = $matches[2] ?? null;
                                                                                if (!empty($url)) {
                                                                                    echo '<a href="' . $url . '" target="_blank" class="btn mt-1 btn-sm btn-primary">Fetch Payment Status</a>';
                                                                                }
                                                                            }?> -->
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
                                        <?php if ($this->auth_user_role == 2) { ?>
                                            <div class="tab-pane tabs-animation show active fade" id="manage" role="tabpanel">
                                            <?php } else {  ?>
                                                <div class="tab-pane tabs-animation fade" id="manage" role="tabpanel">
                                                <?php } ?>
                                                <div class="content-row">
                                                    <div class="sideMenuWrap">
                                                        <div class="sideMenu" id="sideMenu-yourlead" role="region" aria-label="Table controls">
                                                            <div class="p-2 mb-2">
                                                                <form action="" method="post" id="pcr_date_form_yourlead" class="w-100">
                                                                    <div class="mb-3">
                                                                        <label for="your_lead_from_date" class="form-label small mb-1">From Date</label>
                                                                        <input type="date" name="your_lead_from_date" id="your_lead_from_date" class="form-control"
                                                                            value="<?php echo isset($request['your_lead_from_date']) ? $request['your_lead_from_date'] : ''; ?>">
                                                                    </div>

                                                                    <div class="mb-3">
                                                                        <label for="your_lead_to_date" class="form-label small mb-1">To Date</label>
                                                                        <input type="date" name="your_lead_to_date" id="your_lead_to_date" class="form-control"
                                                                            value="<?php echo isset($request['your_lead_to_date']) ? $request['your_lead_to_date'] : ''; ?>">
                                                                    </div>

                                                                    <div class="mb-0">
                                                                        <button type="submit" class="btn btn-primary w-100" name="action" value="your_lead">Apply</button>
                                                                    </div>
                                                                </form>
                                                            </div>


                                                            <hr>


                                                            <h6 class="text-center">Column Visibility</h6>
                                                            <div class="columnToggles" id="yourleadColumnToggle"></div>
                                                            <hr>
                                                            <h6 class="text-center">Customer Filter</h6>
                                                            <div class="card-body active  customer_filter" id="your_lead_customer_filter">
                                                                <div class="d-flex flex-column gap-2">

                                                                    <!-- Lead ID -->
                                                                    <div class="form-check">
                                                                        <input class="form-check-input check_customer" type="checkbox" id="check_your_leadd_id">
                                                                        <label class="form-check-label" for="check_your_lead_id">Lead Id</label>
                                                                        <input type="text" class="info_field d-none" id="your_lead_lead_id" placeholder="Enter Lead Id">
                                                                    </div>

                                                                    <!-- Lead Name -->
                                                                    <div class="form-check">
                                                                        <input class="form-check-input check_customer" type="checkbox" id="check_your_lead_name">
                                                                        <label class="form-check-label" for="check_your_lead_name">Lead Name</label>
                                                                        <input type="text" class="info_field d-none" id="your_lead_lead_name" placeholder="Enter Name">
                                                                    </div>

                                                                    <!-- Mobile -->
                                                                    <div class="form-check">
                                                                        <input class="form-check-input check_customer" type="checkbox" id="check_your_lead_mobile">
                                                                        <label class="form-check-label" for="check_your_lead_mobile">Mobile</label>
                                                                        <input type="text" class="info_field d-none" id="your_lead_lead_mobile" placeholder="Enter Mobile">
                                                                    </div>

                                                                    <!-- Email -->
                                                                    <div class="form-check">
                                                                        <input class="form-check-input check_customer" type="checkbox" id="check_your_lead_email">
                                                                        <label class="form-check-label" for="check_your_lead_email">Email</label>
                                                                        <input type="email" class="info_field d-none" id="your_lead_lead_email" placeholder="Enter Email">
                                                                    </div>

                                                                    <div id="your_lead_error" class="text-danger small mt-1"></div>

                                                                    <button class="btn btn-sm btn-primary w-100 mt-2" onclick="applyCustomerFilters('your_lead','your_lead')">
                                                                        <i class="fas fa-check"></i> Apply Filters
                                                                    </button>
                                                                </div>
                                                            </div>
                                                            <hr>

                                                            <h6 class="text-center">Column Filters</h6>
                                                            <div class="columnFilters" id="yourleadColumnFilter"></div>
                                                        </div>
                                                        <button class="toggleMenu btn btn-sm" aria-expanded="true" aria-controls="sideMenu-yourlead">⏴</button>

                                                    </div>

                                                    <div class="card-body table-responsive">
                                                        <table style="width: 100%;"
                                                            class="table dTtable-your-leads table-hover table-striped table-bordered" id="your_lead">
                                                            <thead>
                                                                <tr>
                                                                    <th>ID</th>
                                                                    <th>Customer</th>
                                                                    <th>Created By</th>
                                                                    <th>Created Groups</th>
                                                                    <th>Created Time</th>
                                                                    <th>Assigned to</th>
                                                                    <th>Assigned Group</th>
                                                                    <th>Assigned Time</th>
                                                                    <th>Status</th>
                                                                    <th>Applicant Name</th>
                                                                    <th>Open Requests</th>
                                                                    <th>Closed Requests</th>
                                                                    <th>Actions</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                foreach ($accepted_leads as $key => $value) {
                                                                    // if($this->auth_user_role > 5 && $value["lead_parent_id"] != 0) continue;

                                                                    // if ($this->auth_user_role > 5 && $value["lead_parent_id"] != 0) continue;

                                                                ?>
                                                                    <tr id="o<?php echo $value['id']; ?>">
                                                                        <td data-href="<?php echo base_url(); ?>leads/lead/view/<?php echo $value['id']; ?>"
                                                                            class="lead_view_popup"><?php echo $value['id']; ?>
                                                                        </td>
                                                                        <td data-href="<?php echo base_url(); ?>leads/lead/view/<?php echo $value['id']; ?>"
                                                                            class="lead_view">
                                                                            <?php if ($value['lead_zoho_id'] == NULL) {
                                                                                if (in_array($value['lead_from'], ['OntimeGOV', 'GoldenCube', 'Baraha Van', 'DLD'])) {
                                                                                    echo '<strong>' . $value['customer_name'] . ' <span class="badge-pill zoho-pill badge badge-info">WEBSITE</span></strong><br />' . $value['customer_mobile'] . '<br />' . $value['customer_email'];
                                                                                } else {
                                                                                    echo '<strong>' . $value['customer_name'] . '</strong><br />' . $value['customer_mobile'] . '<br />' . $value['customer_email'];
                                                                                }
                                                                            } else {
                                                                                // $zoho_value = $value['lead_from'] != NULL && $value['lead_from'] != '' ? $value['lead_from'] : 'ZOHO';
                                                                                echo '<strong>' . $value['customer_name'] . ' <span class="badge-pill zoho-pill badge badge-primary">zoho</span></strong><br />' . $value['customer_mobile'] . '<br />' . $value['customer_email'];
                                                                            } ?></a>
                                                                        </td>
                                                                        <td data-href="<?php echo base_url(); ?>leads/lead/view/<?php echo $value['id']; ?>"
                                                                            class="lead_view">
                                                                            <?php echo $value['creator']; ?></td>

                                                                        <td data-href="<?php echo base_url(); ?>leads/lead/view/<?php echo $value['id']; ?>"
                                                                            class="lead_view">
                                                                            <?php echo $value['creator_group']; ?></td>
                                                                        <td data-href="<?php echo base_url(); ?>leads/lead/view/<?php echo $value['id']; ?>"
                                                                            class="lead_view">
                                                                            <?php echo date('d M Y H:i A', strtotime($value['lead_added_on'])); ?>
                                                                        </td>

                                                                        <td data-href="<?php echo base_url(); ?>leads/lead/preview/<?php echo $value['id']; ?>"
                                                                            class="<?php if (in_array($this->auth_user_role, [6, 7, 84, 86, 87])) {
                                                                                        echo "lead_preview";
                                                                                    } else {
                                                                                        echo "";
                                                                                    } ?>">
                                                                            Self
                                                                        </td>
                                                                        <td data-href="<?php echo base_url(); ?>leads/lead/view/<?php echo $value['id']; ?>"
                                                                            class="lead_view">
                                                                            <?php echo $value['assigned_group']; ?>
                                                                        </td>
                                                                        <td data-href="<?php echo base_url(); ?>leads/lead/view/<?php echo $value['id']; ?>"
                                                                            class="lead_view">
                                                                            <?php echo date('d M Y H:i A', strtotime($value['assigned_on'])); ?>
                                                                        </td>
                                                                        <td <?php if ($value["lead_created_by"] == 2906815795) {
                                                                            ?> class="lead_status_update"
                                                                            data-href="<?php echo base_url(); ?>leads/lead/statusUpdate/<?php echo $value['id']; ?>"
                                                                            <?php } ?>> <?php echo $value['current_status']; ?>
                                                                        </td>
                                                                        <td data-href="<?php echo base_url(); ?>leads/lead/view/<?php echo $value['id']; ?>"
                                                                            class="lead_view">
                                                                            <?php echo $value['applicant_name']; ?>
                                                                        </td>
                                                                        <td><?php if ($value["total_no_subleads"] > 0) {
                                                                                echo $value['no_of_open_subleads'] . "/" . $value["total_no_subleads"];
                                                                            } else {
                                                                                echo "-";
                                                                            } ?></td>
                                                                        <td><?php if ($value["total_no_subleads"] > 0) {
                                                                                echo $value['no_of_closed_subleads'] . "/" . $value["total_no_subleads"];
                                                                            } else {
                                                                                echo "-";
                                                                            } ?></td>
                                                                        <td><a href="<?php echo base_url(); ?>leads/lead/view/<?php echo $value['id']; ?>"
                                                                                class="btn btn-sm btn-primary">View</a></td>
                                                                    </tr>
                                                                <?php
                                                                }
                                                                ?>
                                                            </tbody>
                                                        </table>
                                                    </div>

                                                </div>
                                                </div>
                                                <div class="tab-pane tabs-animation fade" id="converted" role="tabpanel">
                                                    <div class="content-row">
                                                        <div class="sideMenuWrap">
                                                            <div class="sideMenu" id="sideMenu-converted" role="region" aria-label="Table controls">
                                                                <div class="p-2 mb-2">
                                                                    <form action="" method="post" id="pcr_date_form_converted" class="w-100">
                                                                        <div class="mb-3">
                                                                            <label for="converted_from_date" class="form-label small mb-1">From Date</label>
                                                                            <input type="date" name="converted_from_date" id="converted_from_date" class="form-control"
                                                                                value="<?php echo isset($request['converted_from_date']) ? $request['converted_from_date'] : ''; ?>">
                                                                        </div>

                                                                        <div class="mb-3">
                                                                            <label for="converted_to_date" class="form-label small mb-1">To Date</label>
                                                                            <input type="date" name="converted_to_date" id="converted_to_date" class="form-control"
                                                                                value="<?php echo isset($request['converted_to_date']) ? $request['converted_to_date'] : ''; ?>">
                                                                        </div>

                                                                        <div class="mb-0">
                                                                            <button type="submit" class="btn btn-primary w-100" name="action" value="converted">Apply</button>
                                                                        </div>
                                                                    </form>
                                                                </div>


                                                                <hr>


                                                                <h6 class="text-center">Column Visibility</h6>
                                                                <div class="columnToggles" id="convertedColumnToggle"></div>
                                                                <hr>
                                                                <h6 class="text-center">Customer Filter</h6>
                                                                <div class="card-body active  customer_filter" id="converted_customer_filter">
                                                                    <div class="d-flex flex-column gap-2">

                                                                        <!-- Lead ID -->
                                                                        <div class="form-check">
                                                                            <input class="form-check-input check_customer" type="checkbox" id="check_converted_id">
                                                                            <label class="form-check-label" for="check_converted_id">Lead Id</label>
                                                                            <input type="text" class="info_field d-none" id="converted_lead_id" placeholder="Enter Lead Id">
                                                                        </div>

                                                                        <!-- Lead Name -->
                                                                        <div class="form-check">
                                                                            <input class="form-check-input check_customer" type="checkbox" id="check_converted_name">
                                                                            <label class="form-check-label" for="check_converted_name">Lead Name</label>
                                                                            <input type="text" class="info_field d-none" id="converted_lead_name" placeholder="Enter Name">
                                                                        </div>

                                                                        <!-- Mobile -->
                                                                        <div class="form-check">
                                                                            <input class="form-check-input check_customer" type="checkbox" id="check_converted_mobile">
                                                                            <label class="form-check-label" for="check_converted_mobile">Mobile</label>
                                                                            <input type="text" class="info_field d-none" id="converted_lead_mobile" placeholder="Enter Mobile">
                                                                        </div>

                                                                        <!-- Email -->
                                                                        <div class="form-check">
                                                                            <input class="form-check-input check_customer" type="checkbox" id="check_converted_email">
                                                                            <label class="form-check-label" for="check_converted_email">Email</label>
                                                                            <input type="email" class="info_field d-none" id="converted_lead_email" placeholder="Enter Email">
                                                                        </div>

                                                                        <div id="converted_error" class="text-danger small mt-1"></div>

                                                                        <button class="btn btn-sm btn-primary w-100 mt-2" onclick="applyCustomerFilters('converted_dTable','converted')">
                                                                            <i class="fas fa-check"></i> Apply Filters
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                                <hr>

                                                                <h6 class="text-center">Column Filters</h6>
                                                                <div class="columnFilters" id="convertedColumnFilter"></div>
                                                            </div>
                                                            <button class="toggleMenu btn btn-sm" aria-expanded="true" aria-controls="sideMenu-converted">⏴</button>

                                                        </div>

                                                        <div class="card-body table-responsive">
                                                            <table style="width: 100%;"
                                                                class="table dTtable table-hover table-striped table-bordered"
                                                                id="converted_dTable">
                                                                <thead>
                                                                    <tr>
                                                                        <th>ID</th>
                                                                        <th>Customer</th>
                                                                        <th>Category</th>
                                                                        <th>Service</th>
                                                                        <th>Lead Group</th>
                                                                        <th>Created By</th>
                                                                        <th>Created Group</th>
                                                                        <th>Created Time</th>
                                                                        <th>Applicant Name</th>
                                                                        <th>Assigned to</th>
                                                                        <th>Assigned Group</th>
                                                                        <th>Assigned Time</th>
                                                                        <th>Order No#</th>
                                                                        <!-- <th>Open Requests</th>
                                                                    <th>Closed Requests</th> -->
                                                                        <th>Actions</th>
                                                                        <!-- <th>Assigned Group</th> -->
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                    foreach ($converted_leads as $key => $value) {
                                                                    ?>
                                                                        <tr>
                                                                            <td><?php echo $value['id']; ?></td>
                                                                            <td><?php
                                                                                if ($value['lead_zoho_id'] == NULL) {
                                                                                    if (in_array($value['lead_from'], ['OntimeGOV', 'GoldenCube', 'Baraha Van', 'DLD'])) {
                                                                                        echo '<strong>' . $value['customer_name'] . ' <span class="badge-pill zoho-pill badge badge-info">WEBSITE</span></strong><br />' . $value['customer_mobile'] . '<br />' . $value['customer_email'];
                                                                                    } else {
                                                                                        echo '<strong>' . $value['customer_name'] . '</strong><br />' . $value['customer_mobile'] . '<br />' . $value['customer_email'];
                                                                                    }
                                                                                } else {
                                                                                    // $zoho_value = $value['lead_from'] != NULL && $value['lead_from'] != '' ? $value['lead_from'] : 'ZOHO';
                                                                                    echo '<strong>' . $value['customer_name'] . ' <span class="badge-pill zoho-pill badge badge-primary">zoho</span></strong><br />' . $value['customer_mobile'] . '<br />' . $value['customer_email'];
                                                                                } ?>
                                                                            </td>
                                                                            <td><?php echo $value['category_name']; ?></td>
                                                                            <td><?php echo $value['service_name']; ?></td>
                                                                            <td><?php echo $value['group_name']; ?></td>
                                                                            <td><?php echo $value['creator']; ?></td>

                                                                            <td><?php echo $value['creator_group']; ?></td>
                                                                            <td><?php echo date('d M Y H:i A', strtotime($value['lead_added_on'])); ?></td>
                                                                            <td data-href="<?php echo base_url(); ?>leads/lead/view/<?php echo $value['id']; ?>"
                                                                                class="lead_view">
                                                                                <?php echo $value['applicant_name']; ?>
                                                                            </td>

                                                                            <!-- <td data-href="<?php echo base_url(); ?>leads/lead/preview/<?php echo $value['id']; ?>"
                                                                        class="<?php if (in_array($this->auth_user_role, [6, 7, 84, 86, 87])) {
                                                                                    echo "lead_preview";
                                                                                } else {
                                                                                    echo "";
                                                                                } ?>">
                                                                        Self
                                                                    </td> -->
                                                                            <td>
                                                                                <?php
                                                                                if ($value['assigned_to'] == $this->auth_user_id) {
                                                                                    echo 'Self';
                                                                                } else {
                                                                                    $assigned_user_data = get_user_display_data($value['assigned_to']);
                                                                                    echo '<strong>' . $assigned_user_data['first_name'] . ' ' . $assigned_user_data['last_name'] . '</strong><br />' . $assigned_user_data['mobile'] . '<br />' . $assigned_user_data['email'];
                                                                                }
                                                                                ?>
                                                                            </td>
                                                                            <td><?php echo $value['assigned_group']; ?></td>
                                                                            <td><?php echo date('d M Y H:i A', strtotime($value['assigned_on'])); ?></td>

                                                                            <td><?php echo $value['order_receipt']; ?></td>
                                                                            <!-- <td><?php if ($value["total_no_subleads"] > 0) {
                                                                                            echo $value['no_of_open_subleads'] . "/" . $value["total_no_subleads"];
                                                                                        } else {
                                                                                            echo "-";
                                                                                        } ?></td>
                                                                    <td><?php if ($value["total_no_subleads"] > 0) {
                                                                            echo $value['no_of_closed_subleads'] . "/" . $value["total_no_subleads"];
                                                                        } else {
                                                                            echo "-";
                                                                        } ?></td> -->

                                                                            <td><a href="<?php echo base_url(); ?>leads/lead/view/<?php echo $value['id']; ?>"
                                                                                    class="btn btn-sm btn-primary">View</a></td>
                                                                            <!-- <td><?php echo $value['assigned_group']; ?></td> -->
                                                                        </tr>
                                                                    <?php
                                                                    }
                                                                    ?>
                                                                </tbody>
                                                            </table>
                                                        </div>

                                                    </div>
                                                </div>
                                                <div class="tab-pane tabs-animation fade" id="disqualified" role="tabpanel">
                                                    <div class="content-row">
                                                        <div class="sideMenuWrap">
                                                            <div class="sideMenu" id="sideMenu-disqualified" role="region" aria-label="Table controls">
                                                                <div class="p-2 mb-2">
                                                                    <form action="" method="post" id="pcr_date_form_disqualified" class="w-100">
                                                                        <div class="mb-3">
                                                                            <label for="disqualified_from_date" class="form-label small mb-1">From Date</label>
                                                                            <input type="date" name="disqualified_from_date" id="disqualified_from_date" class="form-control"
                                                                                value="<?php echo isset($request['disqualified_from_date']) ? $request['disqualified_from_date'] : ''; ?>">
                                                                        </div>

                                                                        <div class="mb-3">
                                                                            <label for="disqualified_to_date" class="form-label small mb-1">To Date</label>
                                                                            <input type="date" name="disqualified_to_date" id="disqualified_to_date" class="form-control"
                                                                                value="<?php echo isset($request['disqualified_to_date']) ? $request['disqualified_to_date'] : ''; ?>">
                                                                        </div>

                                                                        <div class="mb-0">
                                                                            <button type="submit" class="btn btn-primary w-100" name="action" value="disqualified">Apply</button>
                                                                        </div>
                                                                    </form>
                                                                </div>


                                                                <hr>


                                                                <h6 class="text-center">Column Visibility</h6>
                                                                <div class="columnToggles" id="disqualifiedColumnToggle"></div>
                                                                <hr>
                                                                <h6 class="text-center">Customer Filter</h6>
                                                                <div class="card-body active  customer_filter" id="disqualified_customer_filter">
                                                                    <div class="d-flex flex-column gap-2">

                                                                        <!-- Lead ID -->
                                                                        <div class="form-check">
                                                                            <input class="form-check-input check_customer" type="checkbox" id="check_disqualified_id">
                                                                            <label class="form-check-label" for="check_disqualified_id">Lead Id</label>
                                                                            <input type="text" class="info_field d-none" id="disqualified_lead_id" placeholder="Enter Lead Id">
                                                                        </div>

                                                                        <!-- Lead Name -->
                                                                        <div class="form-check">
                                                                            <input class="form-check-input check_customer" type="checkbox" id="check_disqualified_name">
                                                                            <label class="form-check-label" for="check_disqualified_name">Lead Name</label>
                                                                            <input type="text" class="info_field d-none" id="disqualified_lead_name" placeholder="Enter Name">
                                                                        </div>

                                                                        <!-- Mobile -->
                                                                        <div class="form-check">
                                                                            <input class="form-check-input check_customer" type="checkbox" id="check_disqualified_mobile">
                                                                            <label class="form-check-label" for="check_disqualified_mobile">Mobile</label>
                                                                            <input type="text" class="info_field d-none" id="disqualified_lead_mobile" placeholder="Enter Mobile">
                                                                        </div>

                                                                        <!-- Email -->
                                                                        <div class="form-check">
                                                                            <input class="form-check-input check_customer" type="checkbox" id="check_disqualified_email">
                                                                            <label class="form-check-label" for="check_disqualified_email">Email</label>
                                                                            <input type="email" class="info_field d-none" id="disqualified_lead_email" placeholder="Enter Email">
                                                                        </div>

                                                                        <div id="disqualified_error" class="text-danger small mt-1"></div>

                                                                        <button class="btn btn-sm btn-primary w-100 mt-2" onclick="applyCustomerFilters('disqualified_dTable','disqualified')">
                                                                            <i class="fas fa-check"></i> Apply Filters
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                                <hr>


                                                                <h6 class="text-center">Column Filters</h6>
                                                                <div class="columnFilters" id="disqualifiedColumnFilter"></div>
                                                            </div>
                                                            <button class="toggleMenu btn btn-sm" aria-expanded="true" aria-controls="sideMenu-disqualified">⏴</button>

                                                        </div>


                                                        <div class="card-body table-responsive">
                                                            <table style="width: 100%;"
                                                                class="table dTtable table-hover table-striped table-bordered"
                                                                id="disqualified_dTable">
                                                                <thead>
                                                                    <tr>
                                                                        <th>ID</th>
                                                                        <th>Customer</th>
                                                                        <th>Category</th>
                                                                        <th>Service</th>
                                                                        <th>Lead Group</th>
                                                                        <th>Created By</th>
                                                                        <th>Created Group</th>
                                                                        <th>Created Time</th>
                                                                        <th>Applicant Name</th>
                                                                        <th>Assigned to</th>
                                                                        <th>Assigned Group</th>
                                                                        <th>Assigned Time</th>
                                                                        <th>Remarks</th>
                                                                        <th>Contact Date</th>
                                                                        <!-- <th>Open Requests</th>
                                                                    <th>Closed Requests</th> -->
                                                                        <th>Actions</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                    foreach ($disqualified_leads as $key => $value) {
                                                                    ?>
                                                                        <tr>
                                                                            <td><?php echo $value['id']; ?></td>
                                                                            <td><?php
                                                                                if ($value['lead_zoho_id'] == NULL) {
                                                                                    if (in_array($value['lead_from'], ['OntimeGOV', 'GoldenCube', 'Baraha Van', 'DLD'])) {
                                                                                        echo '<strong>' . $value['customer_name'] . ' <span class="badge-pill zoho-pill badge badge-info">WEBSITE</span></strong><br />' . $value['customer_mobile'] . '<br />' . $value['customer_email'];
                                                                                    } else {
                                                                                        echo '<strong>' . $value['customer_name'] . '</strong><br />' . $value['customer_mobile'] . '<br />' . $value['customer_email'];
                                                                                    }
                                                                                } else {
                                                                                    // $zoho_value = $value['lead_from'] != NULL && $value['lead_from'] != '' ? $value['lead_from'] : 'ZOHO';
                                                                                    echo '<strong>' . $value['customer_name'] . ' <span class="badge-pill zoho-pill badge badge-primary">zoho</span></strong><br />' . $value['customer_mobile'] . '<br />' . $value['customer_email'];
                                                                                } ?>
                                                                            </td>
                                                                            <td><?php echo $value['category_name']; ?></td>
                                                                            <td><?php echo $value['service_name']; ?></td>
                                                                            <td><?php echo $value['group_name']; ?></td>
                                                                            <td><?php echo $value['creator']; ?></td>
                                                                            <td><?php echo $value['creator_group']; ?></td>
                                                                            <td> <?php echo date('d M Y H:i A', strtotime($value['lead_added_on'])); ?></td>
                                                                            <td data-href="<?php echo base_url(); ?>leads/lead/view/<?php echo $value['id']; ?>"
                                                                                class="lead_view">
                                                                                <?php echo $value['applicant_name']; ?>
                                                                            </td>

                                                                            <!-- <td data-href="<?php echo base_url(); ?>leads/lead/preview/<?php echo $value['id']; ?>"
                                                                        class="<?php if (in_array($this->auth_user_role, [6, 7, 84, 86, 87])) {
                                                                                    echo "lead_preview";
                                                                                } else {
                                                                                    echo "";
                                                                                } ?>">
                                                                        Self
                                                                    </td> -->
                                                                            <td>
                                                                                <?php
                                                                                if ($value['assigned_to'] == $this->auth_user_id) {
                                                                                    echo 'Self';
                                                                                } else {
                                                                                    $assigned_user_data = get_user_display_data($value['assigned_to']);
                                                                                    echo '<strong>' . $assigned_user_data['first_name'] . ' ' . $assigned_user_data['last_name'] . '</strong><br />' . $assigned_user_data['mobile'] . '<br />' . $assigned_user_data['email'];
                                                                                }
                                                                                ?>
                                                                            </td>
                                                                            <td><?php echo $value['assigned_group']; ?></td>
                                                                            <td><?php echo date('d M Y H:i A', strtotime($value['assigned_on'])); ?></td>
                                                                            <td><?php echo $value['close_remarks']; ?></td>
                                                                            <td><?php echo date('d M Y H:i A', strtotime($value['contactable_date'])); ?>
                                                                            </td>
                                                                            <!-- <td><?php if ($value["total_no_subleads"] > 0) {
                                                                                            echo $value['no_of_open_subleads'] . "/" . $value["total_no_subleads"];
                                                                                        } else {
                                                                                            echo "-";
                                                                                        } ?></td>
                                                                    <td><?php if ($value["total_no_subleads"] > 0) {
                                                                            echo $value['no_of_closed_subleads'] . "/" . $value["total_no_subleads"];
                                                                        } else {
                                                                            echo "-";
                                                                        } ?></td> -->

                                                                            <td><a href="<?php echo base_url(); ?>leads/lead/view/<?php echo $value['id']; ?>"
                                                                                    class="btn btn-sm btn-primary">View</a></td>
                                                                        </tr>
                                                                    <?php
                                                                    }
                                                                    ?>
                                                                </tbody>
                                                            </table>
                                                        </div>

                                                    </div>
                                                </div>
                                                <div class="tab-pane tabs-animation fade" id="pos_leads" role="tabpanel">
                                                    <div class="row justify-content-center">
                                                        <div class="col-lg-10">
                                                            <div class="main-card mb-3 card mt-4">
                                                                <div class="card-body table-responsive">
                                                                    <table style="width: 100%;" id="pos_leads_table"
                                                                        class="table table-hover table-striped table-bordered">
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
    </div>
</div>

                <!-- Modal -->
                <div class="modal fade" id="meetingModal" tabindex="-1" role="dialog" aria-labelledby="meetingModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="meetingModalLabel">Remarks (Mandatory)</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="<?php echo base_url(); ?>leads/lead/manage" method="post">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Please enter the reason for rejection&nbsp;<span
                                                        class="text-danger required">*</span></label>
                                                <textarea rows="5" class="form-control" name="rejection_remarks" required=""
                                                    id="rejection_remarks"></textarea>
                                            </div>
                                            <div class="form-group">
                                                <input type="hidden" name="lead_id" id="lead_id" value="">
                                                <input type="submit" name="rejection_submit" class="btn btn-primary btn-block"
                                                    value="Add remark and reject">
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal -->
                <div class="modal fade" id="meetingModal" tabindex="-1" role="dialog" aria-labelledby="meetingModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="meetingModalLabel">Remarks (Mandatory)</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="<?php echo base_url(); ?>leads/lead/manage" method="post">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Please enter the reason for rejection&nbsp;<span
                                                        class="text-danger required">*</span></label>
                                                <textarea rows="5" class="form-control" name="rejection_remarks" required=""
                                                    id="rejection_remarks"></textarea>
                                            </div>
                                            <div class="form-group">
                                                <input type="hidden" name="lead_id" id="lead_id" value="">
                                                <input type="submit" name="rejection_submit" class="btn btn-primary btn-block"
                                                    value="Add remark and reject">
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal -->
                <div class="modal fade" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Lead Details</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body" id="lead_preview">
                                Body
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="bg-ontime btn p-2 pl-4 pr-4" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>

                <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
                <script src="<?php echo base_url(); ?>global/vendor/datatables/js/jquery.dataTables.min.js"></script>
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


                <script>
                    // Apply filters dynamically
                    function applyCustomerFilters(tableId, key) {
                        let errorBox = $("#" + key + "_error");
                        errorBox.text(""); // clear errors

                        let table = $("#" + tableId).DataTable();

                        // Safe fallbacks
                        let leadId = ($("#" + key + "_lead_id:visible").val() || "").trim();
                        let leadName = ($("#" + key + "_lead_name:visible").val() || "").trim();
                        let leadMobile = ($("#" + key + "_lead_mobile:visible").val() || "").trim();
                        let leadEmail = ($("#" + key + "_lead_email:visible").val() || "").trim();

                        let valid = true;

                        // Validate Lead ID (number)
                        if (leadId && !/^\d+$/.test(leadId)) {
                            errorBox.text("Lead ID must be a number");
                            valid = false;
                        }

                        // Validate Mobile
                        if (leadMobile && !/^\d{7,15}$/.test(leadMobile)) {
                            errorBox.text("Mobile must be a valid number (7-15 digits)");
                            valid = false;
                        }

                        // Validate Email
                        if (leadEmail && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(leadEmail)) {
                            errorBox.text("Enter a valid email address");
                            valid = false;
                        }

                        if (!valid) return;

                        // Reset filters first
                        table.search("").columns().search("");

                        // Apply filters
                        if (leadId) {
                            table.column(0).search("^" + leadId + "$", true, false);
                        }

                        if (leadName || leadMobile || leadEmail) {
                            // Column 1 = Customer (name, mobile, email combined)
                            let regexParts = [];
                            if (leadName) regexParts.push("(?=.*" + leadName + ")");
                            if (leadMobile) regexParts.push("(?=.*" + leadMobile + ")");
                            if (leadEmail) regexParts.push("(?=.*" + leadEmail + ")");

                            let regex = regexParts.join("");
                            table.column(1).search(regex, true, false);
                        }

                        table.draw();
                    }


                    function init() {
                        //directInvoice();
                        // alert("Inititated");
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
                            //  location.href = link;
                        });

                        $(".lead_status_update").on('click', function(e) {
                            // alert("Hi");
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

                    function pos_leads_count() {
                        if (datTable.hasOwnProperty("data")) {
                            $("#pos_leads_counts").html(datTable.data().length);
                        }
                    }

                    function pos_init() {
                        //directInvoice();
                        // alert("Inititated");

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
                            //  location.href = link;
                        });

                        $(".lead_status_update").on('click', function(e) {
                            // alert("Hi");
                            e.preventDefault();
                            var link = $(this).data("href");
                            $.get(link, function(response) {
                                console.log(response);
                                $("#lead_preview").html(response);
                                $("#modelId").modal();
                            });
                        });
                        pos_leads_count();
                    }

                    $("document").ready(function() {

                        function onChangeEves() {
                            $('[name="assign_group"] option').addClass("d-none");
                            $('[name="assign_to"] option:not([value=""])').addClass("d-none");
                        }

                        $('#converted_from_date, #converted_to_date').change(function() {
                            table.ajax.reload();
                        });

                        function setDefaultDates() {
                            if (!$('#converted_from_date').val() || !$('#converted_to_date').val()) {
                                let max = new Date();
                                let min = new Date();
                                min.setMonth(max.getMonth() - 1);
                                $('#converted_from_date').val(min.toISOString().split('T')[0]);
                                $('#converted_to_date').val(max.toISOString().split('T')[0]);
                            }
                        }

                        $('#disqualified_from_date, #disqualified_to_date').change(function() {
                            table.ajax.reload();
                        });

                        function setDefaultDates() {
                            if (!$('#disqualified_from_date').val() || !$('#disqualified_to_date').val()) {
                                let max = new Date();
                                let min = new Date();
                                min.setMonth(max.getMonth() - 1);
                                $('#disqualified_from_date').val(min.toISOString().split('T')[0]);
                                $('#disqualified_to_date').val(max.toISOString().split('T')[0]);
                            }
                        }

                        setDefaultDates();

                        // $("#enq_dTable,.dTtable:not(#assigned_dTtable, #reassigned_dTtable)").on("draw.dt", init).dataTable({
                        //     order: [
                        //         [0, 'desc']
                        //     ],
                        //     dom: '<"row"<"col"l><"col"f>>rt<"row"<"col"i><"col my-3"p>>',
                        //     responsive: true,
                        //     columnDefs: [{
                        //         targets: 8,
                        //         render: function(data, type, row) {
                        //             if (type === 'sort' || type === 'filter') {
                        //                 var div = document.createElement("div");
                        //                 div.innerHTML = data;
                        //                 var strongElement = div.querySelector("strong");
                        //                 var fullName = strongElement ? strongElement.textContent : "";
                        //                 return fullName; // Return the extracted name or an empty string
                        //             }
                        //             return data; // Return the original HTML for display
                        //         }
                        //     }],
                        //     initComplete: function() {
                        //         this.api().columns().every(function() {
                        //             var column = this;
                        //             // console.log(column.index());
                        //             if (column.index() == 2 || column.index() == 3 || column.index() == 4 || column.index() == 5 || column.index() == 6 || column.index() == 8 || column.index() == 9) { //skip if column 0
                        //                 $(column.header()).append("<br>")
                        //                 var select = $('<select class="form-control"><option value=""></option></select>')
                        //                     .appendTo($(column.header()))
                        //                     .on('change', function() {
                        //                         var val = $.fn.dataTable.util.escapeRegex($(this).val());
                        //                         column.search(val ? '^' + val + '$' : '', true, false).draw();
                        //                     });
                        //                 column.data().unique().sort().each(function(d, j) {
                        //                     if (column.index() == 8) {
                        //                         var div = document.createElement("div");
                        //                         div.innerHTML = d; // Parse the HTML content
                        //                         var strongElement = div.querySelector("strong");
                        //                         var fullName = strongElement ? strongElement.textContent : "";
                        //                         select.append('<option value="' + fullName + '">' + fullName + '</option>');
                        //                     } else {
                        //                         select.append('<option value="' + d + '">' + d + '</option>');
                        //                     }
                        //                 });
                        //             } //end of if
                        //         });
                        //     }
                        // });

                        // Trigger filter on date change
                        $('#unassigned_from_date, #unassigned_to_date').change(function() {
                            table.ajax.reload();
                        });

                        function setDefaultDates() {
                            if (!$('#unassigned_from_date').val() || !$('#unassigned_to_date').val()) {
                                let max = new Date();
                                let min = new Date();
                                min.setMonth(max.getMonth() - 1);
                                $('#unassigned_from_date').val(min.toISOString().split('T')[0]);
                                $('#unassigned_to_date').val(max.toISOString().split('T')[0]);
                            }
                        }

                        // Trigger filter on date change
                        $('#created_from_date, #created_to_date').change(function() {
                            table.ajax.reload();
                        });

                        function setDefaultDates() {
                            if (!$('#created_from_date').val() || !$('#created_to_date').val()) {
                                let max = new Date();
                                let min = new Date();
                                min.setMonth(max.getMonth() - 1);
                                $('#created_from_date').val(min.toISOString().split('T')[0]);
                                $('#created_to_date').val(max.toISOString().split('T')[0]);
                            }
                        }

                        $('#assigned_from_date, #assigned_to_date').change(function() {
                            table.ajax.reload();
                        });

                        function setDefaultDates() {
                            if (!$('#assigned_from_date').val() || !$('#assigned_to_date').val()) {
                                let max = new Date();
                                let min = new Date();
                                min.setMonth(max.getMonth() - 1);
                                $('#assigned_from_date').val(min.toISOString().split('T')[0]);
                                $('#assigned_to_date').val(max.toISOString().split('T')[0]);
                            }
                        }

                        $('#receipt_from_date, #receipt_to_date').change(function() {
                            table.ajax.reload();
                        });

                        function setDefaultDates() {
                            if (!$('#receipt_from_date').val() || !$('#receipt_to_date').val()) {
                                let max = new Date();
                                let min = new Date();
                                min.setMonth(max.getMonth() - 1);
                                $('#receipt_from_date').val(min.toISOString().split('T')[0]);
                                $('#receipt_to_date').val(max.toISOString().split('T')[0]);
                            }
                        }

                        $('#reassigned_from_date, #reassigned_to_date').change(function() {
                            table.ajax.reload();
                        });

                        function setDefaultDates() {
                            if (!$('#reassigned_from_date').val() || !$('#reassigned_to_date').val()) {
                                let max = new Date();
                                let min = new Date();
                                min.setMonth(max.getMonth() - 1);
                                $('#reassigned_from_date').val(min.toISOString().split('T')[0]);
                                $('#reassigned_to_date').val(max.toISOString().split('T')[0]);
                            }
                        }


                        $('#your_lead_from_date, #your_lead_to_date').change(function() {
                            table.ajax.reload();
                        });

                        function setDefaultDates() {
                            if (!$('#your_lead_from_date').val() || !$('#your_lead_to_date').val()) {
                                let max = new Date();
                                let min = new Date();
                                min.setMonth(max.getMonth() - 1);
                                $('#your_lead_from_date').val(min.toISOString().split('T')[0]);
                                $('#your_lead_to_date').val(max.toISOString().split('T')[0]);
                            }
                        }

                        setDefaultDates();

                        if (window.screen.width > 991) {
                            var dbOptions = {
                                order: [
                                    [0, 'desc']
                                ],
                                dom: '<"row"<"col"l><"col"f>>rt<"row"<"col"i><"col my-3"p>>',
                                responsive: true,
                                columnDefs: [{
                                    targets: 5,
                                    render: function(data, type, row) {
                                        if (type === 'sort' || type === 'filter') {
                                            var div = document.createElement("div");
                                            div.innerHTML = data;
                                            var strongElement = div.querySelector("strong");
                                            var fullName = strongElement ? strongElement.textContent : "";
                                            return fullName; // Return the extracted name or an empty string
                                        }
                                        return data; // Return the original HTML for display
                                    }
                                }],
                                initComplete: function() {
                                    this.api().columns().every(function() {
                                        var column = this;
                                        // console.log(column.index());
                                        if (column.index() == 2 || column.index() == 3 || column.index() == 5 || column.index() == 6 || column.index() == 8) { //skip if column 0
                                            $(column.header()).append("<br>")
                                            var select = $('<select class="form-control"><option value=""></option></select>')
                                                .appendTo($(column.header()))
                                                .on('change', function() {
                                                    var val = $.fn.dataTable.util.escapeRegex(
                                                        $(this).val()
                                                    );
                                                    column.search(val ? '^' + val + '$' : '', true, false).draw();
                                                });
                                            column.data().unique().sort().each(function(d, j) {
                                                if (column.index() == 5) {
                                                    var div = document.createElement("div");
                                                    div.innerHTML = d; // Parse the HTML content
                                                    var strongElement = div.querySelector("strong");
                                                    var fullName = strongElement ? strongElement.textContent : "";
                                                    select.append('<option value="' + fullName + '">' + fullName + '</option>');
                                                } else {
                                                    select.append('<option value="' + d + '">' + d + '</option>');
                                                }
                                            });
                                        } //end of if

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
                                    targets: 5,
                                    render: function(data, type, row) {
                                        if (type === 'sort' || type === 'filter') {
                                            var div = document.createElement("div");
                                            div.innerHTML = data;
                                            var strongElement = div.querySelector("strong");
                                            var fullName = strongElement ? strongElement.textContent : "";
                                            return fullName; // Return the extracted name or an empty string
                                        }
                                        return data; // Return the original HTML for display
                                    }
                                }],
                                initComplete: function() {
                                    this.api().columns().every(function() {
                                        var column = this;
                                        // console.log(column.index());
                                        if (column.index() == 2 || column.index() == 3 || column.index() == 5 || column.index() == 6 || column.index() == 8) { //skip if column 0
                                            $(column.header()).append("<br>")
                                            var select = $('<select class="form-control"><option value=""></option></select>')
                                                .appendTo($(column.header()))
                                                .on('change', function() {
                                                    var val = $.fn.dataTable.util.escapeRegex(
                                                        $(this).val()
                                                    );
                                                    column.search(val ? '^' + val + '$' : '', true, false).draw();
                                                });
                                            column.data().unique().sort().each(function(d, j) {
                                                if (column.index() == 5) {
                                                    var div = document.createElement("div");
                                                    div.innerHTML = d; // Parse the HTML content
                                                    var strongElement = div.querySelector("strong");
                                                    var fullName = strongElement ? strongElement.textContent : "";
                                                    select.append('<option value="' + fullName + '">' + fullName + '</option>');
                                                } else {
                                                    select.append('<option value="' + d + '">' + d + '</option>');
                                                }
                                            });
                                        } //end of if

                                    });
                                }
                            };
                        }

                        // window.dtTable = $("#assigned_dTtable,#reassigned_dTtable, #created_dTtable").on("draw.dt", init).dataTable(dbOptions);
                        function initCustomDataTable(tableSelector, togglesSelector, filtersSelector, $colums_filter) {
                            var tableEl = $(tableSelector);

                            if (!tableEl.length) return; // Table not found

                            tableEl.DataTable({
                                order: [
                                    [0, 'desc']
                                ],
                                dom: '<"row"<"col"l><"col"f>>rt<"row"<"col"i><"col my-3"p>>',
                                responsive: true,
                                columnDefs: [{
                                    targets: 6,
                                    render: function(data, type) {
                                        if (type === 'sort') return moment(data, "DD MMM YYYY HH:mm A").valueOf();
                                        return data;
                                    }
                                }],
                                initComplete: function() {
                                    var api = this.api();
                                    var $togglesContainer = $(togglesSelector);
                                    var $filtersContainer = $(filtersSelector);

                                    $togglesContainer.empty();
                                    $filtersContainer.empty();

                                    // --- Column Visibility ---
                                    var $visSearch = $('<input type="text" class="global-search" placeholder="Search columns...">');
                                    var $visBox = $('<div class="scroll-box"></div>');
                                    $togglesContainer.append($visSearch).append($visBox);

                                    api.columns().every(function() {
                                        var column = this;
                                        var colIdx = column.index();
                                        var colName = $(column.header()).text().trim();
                                        var checked = column.visible() ? "checked" : "";

                                        var $toggle = $('<div class="option-item"><label><input type="checkbox" ' + checked + ' data-col="' + colIdx + '"> ' + colName + '</label></div>');
                                        $toggle.find("input").on("change", function() {
                                            api.column($(this).data("col")).visible(this.checked);
                                        });
                                        $visBox.append($toggle);
                                    });

                                    $visSearch.on("keyup", function() {
                                        var val = $(this).val().toLowerCase();
                                        $visBox.find(".option-item").each(function() {
                                            $(this).toggle($(this).text().toLowerCase().includes(val));
                                        });
                                    });

                                    // --- Column Filters ---
                                    api.columns().every(function() {
                                        var column = this;
                                        var colIdx = column.index();

                                        if ($colums_filter.includes(colIdx)) {
                                            var $filterBox = $('<div class="filter-box"></div>');
                                            $filterBox.append('<strong class="filter-title d-block mb-1">' + $(column.header()).text().trim() + '</strong>');

                                            var $search = $('<input type="text" class="filter-search" placeholder="Search">');
                                            var $options = $('<div class="scroll-box"></div>');
                                            $filterBox.append($search).append($options);

                                            var uniqueVals = {};
                                            column.data().each(function(d) {
                                                // For column 8, extract first name from <strong> (or fallback to first token)
                                                var firstToken = "";

                                                try {
                                                    var div = document.createElement('div');
                                                    div.innerHTML = d;
                                                    var strong = div.querySelector('strong');
                                                    if (colIdx === 9 && strong) {
                                                        // take only the first name from "First Last"
                                                        firstToken = strong.textContent.trim();
                                                    } else {
                                                        // fallback: take first token of plain text
                                                        firstToken = $('<div>').html(d).text().trim().split(/\s+/)[0] || '';
                                                    }
                                                } catch (err) {
                                                    // safe fallback
                                                    firstToken = $('<div>').html(d).text().trim().split(/\s+/)[0] || '';
                                                }

                                                if (firstToken) uniqueVals[firstToken] = true;
                                            });
                                            // var uniqueVals = {};
                                            // column.data().each(function(d) {
                                            //     var val = $('<div>').html(d).text().trim();
                                            //     if (val) uniqueVals[val] = true;
                                            // });

                                            function renderOptions(searchText = "") {
                                                $options.empty();
                                                Object.keys(uniqueVals).sort().forEach(function(val) {
                                                    if (val.toLowerCase().includes(searchText.toLowerCase())) {
                                                        var $chk = $('<div class="option-item"><label><input type="checkbox" value="' + val + '" data-col="' + colIdx + '"> ' + val + '</label></div>');
                                                        $chk.find('input').on('change', function() {
                                                            var selected = [];
                                                            $filtersContainer.find("input[data-col='" + colIdx + "']:checked").each(function() {
                                                                selected.push($.fn.dataTable.util.escapeRegex($(this).val()));
                                                            });
                                                            column.search(selected.length ? selected.join('|') : '', true, false).draw();
                                                        });
                                                        $options.append($chk);
                                                    }
                                                });
                                            }

                                            renderOptions();
                                            $search.on('keyup', function() {
                                                renderOptions($(this).val());
                                            });

                                            $filtersContainer.append($filterBox).append('<hr>');
                                        }
                                    });
                                }
                            });
                        }
                        // initialize your custom table(s)
                        initCustomDataTable('#created_dTtable', '#createdColumnToggle', '#createdColumnFilter', [2, 3, 5, 6, 8]);
                        initCustomDataTable('#unassigned_dTtable', '#unassignedColumnToggle', '#unassignedColumnFilter', [2, 3]);
                        initCustomDataTable('#reassigned_dTtable', '#reassignedColumnToggle', '#reassignedColumnFilter', [2, 3, 5, 6, 8]);
                        initCustomDataTable('#assigned_dTtable', '#assignedColumnToggle', '#assignedColumnFilter', [2, 3, 5, 6, 8]);
                        initCustomDataTable('#receipt_dTtable', '#receiptColumnToggle', '#receiptColumnFilter', [2, 3, 5, 6, 8]);
                        initCustomDataTable('#your_lead', '#yourleadColumnToggle', '#yourleadColumnFilter', [2, 3, 5, 6, 8, 9]);
                        initCustomDataTable('#converted_dTable', '#convertedColumnToggle', '#convertedColumnFilter', [2, 3, 4, 5, 6, 8, 9]);
                        initCustomDataTable('#disqualified_dTable', '#disqualifiedColumnToggle', '#disqualifiedColumnFilter', [2, 3, 4, 5, 6, 8, 9]);
                        // initCustomDataTable('#pos_leads_table', '#posleadsColumnToggle', '#posleadsColumnFilter', [2, 3, 5, 6, 8]);







                        // cache elements
                        var $contentRows = $('.content-row');

                        // toggle behavior for each content-row (supports multiple tables on same page)
                        $contentRows.each(function(i, row) {
                            var $row = $(row);
                            var $sideMenu = $row.find('.sideMenu');
                            var $toggleBtn = $row.find('.toggleMenu');
                            var $tableWrapper = $row.find('.tableWrapper');

                            // restore collapsed state per table (optional)
                            var storageKey = 'sideMenuCollapsed_' + (i || 0);
                            if (localStorage.getItem(storageKey) === '1') {
                                $sideMenu.addClass('collapsed');
                                $toggleBtn.attr('aria-expanded', 'false').text('⏵');
                            }

                            // helper: size the sideMenu's max-height to tableWrapper height
                            function syncSideMenuHeight() {
                                // prefer table inner height so both look aligned
                                var h = $tableWrapper.innerHeight();
                                // leave a small gap for padding
                                $sideMenu.css('max-height', 1023.22 + 'px');
                            }

                            // call initially and on window resize
                            syncSideMenuHeight();
                            $(window).on('resize.sideMenu' + i, syncSideMenuHeight);

                            // also sync after the datatable redraw (if present)
                            var dt = $row.find('table').DataTable ? $row.find('table').DataTable() : null;
                            if (dt && dt.on) {
                                dt.on('draw', function() {
                                    // small delay if DataTable changes size
                                    setTimeout(syncSideMenuHeight, 60);
                                });
                            }

                            // overlay handling for small screens
                            var $overlay = $('#sideMenuOverlay');

                            function showOverlay() {
                                $overlay.addClass('show').prop('hidden', false);
                            }

                            function hideOverlay() {
                                $overlay.removeClass('show').prop('hidden', true);
                            }

                            // toggle click
                            $toggleBtn.on('click', function(e) {
                                e.preventDefault();
                                var collapsed = $sideMenu.toggleClass('collapsed').hasClass('collapsed');

                                // update aria (keep this)
                                $toggleBtn.attr('aria-expanded', (!collapsed).toString());

                                // remove: $toggleBtn.text(collapsed ? '⏵' : '⏴');
                                //  let CSS transform rotate handle arrow direction

                                // store preference
                                localStorage.setItem(storageKey, collapsed ? '1' : '0');

                                // overlay logic stays the same
                                if ($(window).width() <= 900 && !collapsed) {
                                    showOverlay();
                                } else {
                                    hideOverlay();
                                }

                                // sync table width after animation
                                setTimeout(function() {
                                    syncSideMenuHeight();
                                    try {
                                        var dtTable = $row.find('table').DataTable ? $row.find('table').DataTable() : null;
                                        if (dtTable && typeof dtTable.columns === 'function') {
                                            dtTable.columns.adjust().draw(false);
                                        }
                                    } catch (err) {
                                        // ignore if DataTables not initialized yet
                                    }
                                }, 300);
                            });


                            // clicking overlay closes the menu on small screens
                            $overlay.on('click', function() {
                                $sideMenu.addClass('collapsed');
                                $toggleBtn.attr('aria-expanded', 'false').text('⏵');
                                localStorage.setItem(storageKey, '1');
                                hideOverlay();
                            });
                        });

                        // also hide overlay on escape
                        $(document).on('keydown', function(e) {
                            if (e.key === 'Escape') {
                                $('#sideMenuOverlay').trigger('click');
                            }
                        });

                        // ensure overlay is hidden on load
                        $('#sideMenuOverlay').prop('hidden', true);


                        var unassigned_dbOptions = {
                            order: [
                                [0, 'desc']
                            ],
                            colReorder: {
                                order: [0, 1, 8, 3, 4, 2, 5, 6, 7, 9, 10, 11]
                            },
                            dom: '<"row"<"col"l><"col"f>>rt<"row"<"col"i><"col my-3"p>>',
                            responsive: true,
                            columnDefs: [{
                                targets: 5,
                                render: function(data, type, row) {
                                    if (type === 'sort' || type === 'filter') {
                                        var div = document.createElement("div");
                                        div.innerHTML = data;
                                        var strongElement = div.querySelector("strong");
                                        var fullName = strongElement ? strongElement.textContent : "";
                                        return fullName; // Return the extracted name or an empty string
                                    }
                                    return data; // Return the original HTML for display
                                }
                            }],
                            initComplete: function() {
                                this.api().columns().every(function() {
                                    var column = this;
                                    // console.log(column.index());
                                    if (column.index() == 2 || column.index() == 3) { //skip if column 0
                                        $(column.header()).append("<br>")
                                        var select = $('<select class="form-control"><option value=""></option></select>')
                                            .appendTo($(column.header()))
                                            .on('change', function() {
                                                var val = $.fn.dataTable.util.escapeRegex(
                                                    $(this).val()
                                                );
                                                column.search(val ? '^' + val + '$' : '', true, false).draw();
                                            });
                                        column.data().unique().sort().each(function(d, j) {
                                            select.append('<option value="' + d + '">' + d + '</option>');
                                        });
                                    }
                                });
                            }
                        };

                        // window.dtTable = $("#unassigned_dTtable").on("draw.dt", init).dataTable(unassigned_dbOptions);

                        // $(".dTtable-your-leads").on("draw.dt", init).on("draw", pos_leads_count).dataTable({
                        //     order: [
                        //         [0, 'desc']
                        //     ],
                        //     dom: '<"row"<"col"l><"col"f>>rt<"row"<"col"i><"col my-3"p>>',
                        //     responsive: true,
                        //     initComplete: function() {
                        //         this.api().columns().every(function() {
                        //             var column = this;
                        //             // console.log(column.index());
                        //             if (column.index() == 2 || column.index() == 3 || column.index() == 5 || column.index() == 6 || column.index() == 8) { //skip if column 0
                        //                 $(column.header()).append("<br>")
                        //                 var select = $(
                        //                         '<select class="form-control"><option value=""></option></select>'
                        //                     )
                        //                     .appendTo($(column.header()))
                        //                     .on('change', function() {
                        //                         var val = $.fn.dataTable.util.escapeRegex(
                        //                             $(this).val()
                        //                         );

                        //                         column
                        //                             .search(val ? '^' + val + '$' : '', true,
                        //                                 false)
                        //                             .draw();
                        //                     });
                        //                 column.data().unique().sort().each(function(d, j) {
                        //                     select.append('<option value="' + d + '">' + d +
                        //                         '</option>')
                        //                 });
                        //             } //end of if

                        //         });
                        //     }
                        // });


                        // ID
                        // Customer
                        // Created By
                        // Created Group
                        // Assigned to
                        // Assigned Group
                        // Created Time
                        // Status
                        // Open Requests
                        // Closed Requests
                        // Actions

                        window.datTable = $("#pos_leads_table").on('draw.dt', pos_init).DataTable({
                            "ajax": "/leads/lead/pos_leads",
                            "columns": [{
                                    "data": "id",
                                    "title": "ID",
                                    "render": function(data, row, row_values) {
                                        return '<a data-href="<?php echo base_url(); ?>leads/lead/view/' + row_values.id +
                                            '" class="lead_view_popup">' + data + '</a>';
                                    }
                                },
                                {
                                    "title": "Customer",
                                    "data": "customer_name",
                                    "render": function(data, row, row_values) {
                                        return "<div data-href='<?php echo base_url(); ?>leads/lead/view/" + row_values.id +
                                            "' class='lead_view'><b>" + row_values.customer_name +
                                            "</b><br>" + row_values.customer_mobile + "<br>" + row_values
                                            .customer_email;
                                    }
                                },
                                {
                                    "title": "Created By",
                                    "data": "creator",
                                    "render": function(data, row, row_values) {
                                        return '<a data-href="<?php echo base_url(); ?>leads/lead/view/' + row_values.id +
                                            '" class="lead_view_popup">' + data + '</a>';
                                    }
                                },
                                {
                                    "title": "Created Group",
                                    "data": "creator_group",
                                    "render": function(data, row, row_values) {
                                        return '<a data-href="<?php echo base_url(); ?>leads/lead/view/' + row_values.id +
                                            '" class="lead_view_popup">' + data + '</a>';
                                    }
                                },
                                {
                                    "title": "Created Time",
                                    "data": "created_at",
                                    "render": function(data, type, row) {
                                        if (type === 'sort') {
                                            // Return UNIX timestamp for sorting
                                            var timestamp = new Date(row.created_at).getTime();
                                            return timestamp;
                                        }

                                        // Render the formatted date for display
                                        const createdAt = new Date(row.created_at);
                                        const options = {
                                            day: '2-digit',
                                            month: 'short',
                                            year: 'numeric',
                                            hour: '2-digit',
                                            minute: '2-digit',
                                            hour12: false
                                        };
                                        var formattedDate = new Intl.DateTimeFormat('en-GB', options).format(createdAt);
                                        formattedDate = formattedDate.replace(',', '');
                                        var hours = createdAt.getHours();
                                        var amPm = hours >= 12 ? 'PM' : 'AM';
                                        var finalFormattedDate = `${formattedDate} ${amPm}`;
                                        return "<span data-href='/leads/lead/view/" + row.id + "' class='lead_view'>" + finalFormattedDate + "</span>";
                                    }
                                },
                                {
                                    "title": "Assigned to",
                                    "data": "assigned_user",
                                    "render": function(data, row, row_values) {
                                        return "<span data-href='<?php echo base_url(); ?>leads/lead/preview/" + row_values.id +
                                            "' class='lead_preview'>" + row_values.assigned_user +
                                            "</span>";
                                    }
                                },
                                {
                                    "title": "Assigned Group",
                                    "data": "assigned_group",
                                    "render": function(data, row, row_values) {
                                        return "<span data-href='<?php echo base_url(); ?>leads/lead/view/" + row_values.id +
                                            "' class='lead_view'>" + data + "</span>";
                                    }
                                },
                                // {
                                //     "title": "Assigned Time",
                                //     "data": "assigned_on",
                                //     "render": function (data, row, row_values) {
                                //         return "<span data-href='<?php echo base_url(); ?>leads/lead/view/" + row_values.id +
                                //             "' class='lead_view'>" + row_values.assigned_on + "</span>";
                                //     }
                                // },
                                {
                                    "title": "Assigned Time",
                                    "data": "assigned_on",
                                    "render": function(data, type, row) {
                                        if (type === 'sort') {
                                            // Return UNIX timestamp for sorting
                                            var timestamp = new Date(row.assigned_on).getTime();
                                            return timestamp;
                                        }

                                        // Render the formatted date for display
                                        const assigned_on = new Date(row.assigned_on);
                                        const options = {
                                            day: '2-digit',
                                            month: 'short',
                                            year: 'numeric',
                                            hour: '2-digit',
                                            minute: '2-digit',
                                            hour12: false
                                        };
                                        var formattedDate = new Intl.DateTimeFormat('en-GB', options).format(assigned_on);
                                        formattedDate = formattedDate.replace(',', '');
                                        var hours = assigned_on.getHours();
                                        var amPm = hours >= 12 ? 'PM' : 'AM';
                                        var finalFormattedDate = `${formattedDate} ${amPm}`;
                                        return "<span data-href='/leads/lead/view/" + row.id + "' class='lead_view'>" + finalFormattedDate + "</span>";
                                    }
                                },
                                {
                                    "title": "Status",
                                    "data": "current_status",
                                    "render": function(data, row, row_values) {
                                        return "<span data-href='<?php echo base_url(); ?>leads/lead/view/" + row_values.id +
                                            "' class='lead_view'>" + row_values.current_status + "</span>";
                                    }
                                },
                                {
                                    "title": "Open Subleads",
                                    "data": "id",
                                    "render": function(data, row, row_values) {
                                        if (row_values.total_no_subleads > 0) {
                                            return "<span data-href='<?php echo base_url(); ?>leads/lead/view/" + row_values.id +
                                                "' class='lead_view'>" + row_values.no_of_open_subleads +
                                                "/" + row_values.total_no_subleads + "</span>";
                                        } else {
                                            return "-";
                                        }
                                    }
                                },
                                {
                                    "title": "Closed Subleads",
                                    "data": "id",
                                    "render": function(data, row, row_values) {
                                        if (row_values.total_no_subleads > 0) {
                                            return "<span data-href='<?php echo base_url(); ?>leads/lead/view/" + row_values.id +
                                                "' class='lead_view'>" + row_values.no_of_closed_subleads +
                                                "/" + row_values.total_no_subleads + "</span>";
                                        } else {
                                            return "-";
                                        }
                                    }
                                },
                                {
                                    "title": "Actions",
                                    "data": "id",
                                    "render": function(data, row, row_values) {
                                        return '<a href="<?php echo base_url(); ?>leads/lead/view/' + data +
                                            '" class="btn btn-sm btn-primary">View</a>';

                                    }
                                },
                            ],
                            order: [
                                [0, "desc"]
                            ],
                            "columnDefs": [{
                                targets: 4, // Created Time column
                                render: function(data, type, row) {
                                    if (type === 'sort') {
                                        var timestamp = new Date(row.created_at).getTime(); // Convert to timestamp
                                        return timestamp;
                                    }
                                    return data; // For display, return formatted date
                                }
                            }, {
                                targets: 7, // Created Time column
                                render: function(data, type, row) {
                                    if (type === 'sort') {
                                        var timestamp = new Date(row.assigned_on).getTime(); // Convert to timestamp
                                        return timestamp;
                                    }
                                    return data; // For display, return formatted date
                                }
                            }],
                            initComplete: function() {
                                this.api().columns().every(function() {
                                    var column = this;
                                    // console.log(column.index());
                                    if (column.index() == 2 || column.index() == 3 || column.index() == 5 || column.index() == 6 || column.index() == 8) { //skip if column 0
                                        $(column.header()).append("<br>")
                                        var select = $(
                                                '<select class="form-control"><option value=""></option></select>'
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
                                    if (column.index() == 4 || column.index() == 7) {
                                        // If you need a dropdown filter for the Created Time column
                                        column.data().unique().sort().each(function(d, j) {
                                            var timestamp = new Date(d).getTime();
                                            column.data()[j] = timestamp;
                                        });
                                    }

                                });
                            }
                        });


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

                    $(document).on("click", ".open-meetingDialog", function() {
                        var lead_id = $(this).data('leadid');
                        $(".modal-body #lead_id").val(lead_id);
                    });
                </script>

                <script type="text/javascript">
                    function branch_change(branch) {
                        var biz = [107];
                        var attest = [103];
                        var val = parseInt(branch);


                        $('[name="assign_group"]').val("").trigger("change");
                        $('[name="assign_group"] option').addClass("d-none");
                        $('[name="assign_group"] option[data-branch="' + val + '"]').removeClass("d-none");

                        if ($('[name="assign_group"] option:not(.d-none)').length == 1) {
                            var val = $('[name="assign_group"] option:not(.d-none)').val();
                            $('[name="assign_group"]').val(val).trigger("change");
                        }
                    }

                    function group_change(group) {
                        $('[name="assign_to"]').val("").trigger("change");
                        // alert($(this).val());
                        //if ($(this).val() == "") {
                        // $('[name="assign_to"] option').removeClass("d-none");
                        // return;
                        // }
                        $('[name="assign_to"] option:not([value=""])').addClass("d-none");
                        $('[name="assign_to"] option:not([value=""])[data-filter*="' + group + '"]')
                            .removeClass("d-none");

                        // $('[name="assign_to"] option:not([value="3771749283"])[data-filter*="BusinessSetup"]')
                        //   .addClass("d-none");
                        if (group === "DLD" || group === "MazayaDLD") {
                            // Check if "Unassigned" is not already in the options
                            if ($('[name="assign_to"] option[value="unassigned"]').length === 0) {
                                $('[name="assign_to"]').append('<option value="unassigned">Unassigned</option>');
                                // $('[name="assign_to"]').prepend('<option value="unassigned">Unassigned</option>');
                            }
                        } else {
                            // Remove "Unassigned" option if it exists and it's not "DLD"
                            $('[name="assign_to"] option[value="unassigned"]').remove();
                        }
                    }

                    function assign_csa(item_id, assigned_to, assigned_by) {
                        if (assigned_to == "" || assigned_to == null) return false;
                        console.log("item_id: " + item_id + " assigned_to: " + assigned_to + " assigned_by: " + assigned_by);
                        Swal.fire({
                            title: 'Please confirm',
                            text: "If lead is assigned to CSA, it cannot be reverted.",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Yes, Assign!'
                        }).then((result) => {
                            if (result.isConfirmed) {

                                $.ajax({
                                    url: "<?php echo base_url(); ?>api/v1/assign/lead",
                                    type: 'POST',
                                    data: {
                                        assigned_to: assigned_to,
                                        lead_id: item_id,
                                        assigned_by: assigned_by
                                    },
                                    success: function(res) {
                                        console.log(res);
                                        $('#o' + item_id).hide();
                                        Swal.fire(
                                            'Assigned!',
                                            res,
                                            'success'
                                        ).then((value) => {
                                            location.reload();
                                        });
                                    },
                                    error: function(e) {
                                        Swal.fire(
                                            'Something went wrong!',
                                            e,
                                            'error'
                                        )
                                    }
                                });

                            }
                        });
                    }
                </script>


                <script src="/global/js/plugins-init/select2-init.js"></script>
                <script type="text/javascript">
                    $(document).ready(function() {
                        var hash = location.hash.replace(/^#/, ''); // ^ means starting, meaning only match the first hash
                        if (hash) {
                            $('.nav_tabs a[href="#' + hash + '"]').tab('show');
                        }

                        window.onhashchange = function(e) {
                            // console.log(e);
                            // alert(e.target.location.hash);
                            $('.nav_tabs a[href="' + e.target.location.hash + '"]').tab('show');

                        }

                        $('.nav_tabs a').on('show.bs.tab', function(e) {
                            // Change hash for page-reload
                            window.location.hash = e.target.hash;
                        });
                        $('#main-wrapper').attr('class', 'show menu-toggle');

                        $(document).on('change', '.customer_filter .check_customer', function() {
                            let input = $(this).closest('.form-check').find('.info_field');
                            if (this.checked) {
                                input.removeClass('d-none').focus();
                            } else {
                                input.addClass('d-none').val('');
                            }
                        });




                    })
                </script>