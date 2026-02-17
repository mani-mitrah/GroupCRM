<style>
    i {
        user-select: none;
        outline: none;
        display: inline-block;
    }

    #content {
        padding: 20px;
        height: 50vh;
        overflow-y: auto;
        background: #fff;
        position: relative;
        padding-bottom: 200px;
    }

    #tcontent {
        overflow: visible !important;
        /* Prevents scrollbars */
        max-height: none !important;
        /* Removes height limitations */
    }

    .head {
        cursor: pointer;
    }

    .over-view-section {
        margin-top: 20px;
        padding: 20px;
    }

    #sidebar {
        width: 250px;
        transition: all 0.3s ease-in-out;
    }

    .collapsed {
        width: 0 !important;
        padding: 0 !important;
        overflow: hidden;
    }

    #toggle-btn {
        position: absolute;
        transition: left 0.3s ease-in-out;
    }

    .collapsed+#toggle-btn {
        left: 10px;
    }

    .nav-link {
        color: black;
    }

    .nav-item :hover {
        background-color: skyblue;
        color: white !important;
    }

    .timeline {
        overflow: visible;
        list-style: none !important;
        position: relative;
        padding: 0;
        margin: 0;
    }

    .timeline:before {
        top: 0;
        bottom: 0;
        position: absolute;
        content: " ";
        width: 3px;
        list-style: none;
        background-color: #eeeeee;
        left: 115px;
    }

    li::marker {
        content: '';
    }

    .timeline>li {
        margin-bottom: 20px;
        position: relative;
    }

    .timeline>li:before {
        content: " ";
        display: table;
    }

    .timeline>li:after {
        content: " ";
        display: table;
        clear: both;
    }

    .timeline>li:before {
        content: " ";
        display: table;
    }

    .timeline>li:after {
        content: " ";
        display: table;
        clear: both;
    }

    .timeline>li>.timeline-panel {
        width: 95%;
        float: left;
        border: 1px solid #eeeeee;
        background: #ffffff;
        border-radius: 3px;
        padding: 20px;
        position: relative;
        -webkit-box-shadow: 0px 1px 20px 1px rgba(69, 65, 78, 0.06);
        -moz-box-shadow: 0px 1px 20px 1px rgba(69, 65, 78, 0.06);
        box-shadow: 0px 1px 20px 1px rgba(69, 65, 78, 0.06);
    }

    .timeline>li.timeline-inverted+li:not(.timeline-inverted) {
        margin-top: -60px;
    }

    .timeline>li:not(.timeline-inverted) {
        padding-right: 90px;
    }

    .timeline>li:not(.timeline-inverted)+li.timeline-inverted {
        margin-top: -60px;
    }

    .timeline>li.timeline-inverted {
        padding-left: 90px;
    }

    .timeline>li.timeline-inverted>.timeline-panel {
        float: right;
    }

    .timeline>li.timeline-inverted>.timeline-panel:before {
        border-left-width: 0;
        border-right-width: 15px;
        left: -15px;
        right: auto;
    }

    .timeline>li.timeline-inverted>.timeline-panel:after {
        border-left-width: 0;
        border-right-width: 14px;
        left: -14px;
        right: auto;
    }

    .timeline>li>.timeline-panel:before {
        position: absolute;
        top: 26px;
        right: -15px;
        display: inline-block;
        border-top: 15px solid transparent;
        border-left: 15px solid #eeeeee;
        border-right: 0 solid #eeeeee;
        border-bottom: 15px solid transparent;
        content: " ";
    }

    .timeline>li>.timeline-panel:after {
        position: absolute;
        top: 27px;
        right: -14px;
        display: inline-block;
        border-top: 14px solid transparent;
        border-left: 14px solid #ffffff;
        border-right: 0 solid #ffffff;
        border-bottom: 14px solid transparent;
        content: " ";
    }

    .timeline>li>.timeline-badge {
        color: #ffffff;
        width: 50px;
        height: 50px;
        line-height: 50px;
        font-size: 1.8em;
        text-align: center;
        position: absolute;
        top: 16px;
        background-color: #999999;
        z-index: 100;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .timeline>li>.timeline-badge i {
        color: #ffffff !important;
    }

    .timeline>li>.timeline-badge i.fa,
    .timeline>li>.timeline-badge i.fab,
    .timeline>li>.timeline-badge i.fal,
    .timeline>li>.timeline-badge i.far,
    .timeline>li>.timeline-badge i.fas {
        font-size: 0.8em;
    }

    .timeline-badge.black {
        background-color: #1a2035 !important;
    }

    .timeline-badge.primary {
        background-color: #1572E8 !important;
    }

    .timeline-badge.secondary {
        background-color: #6861CE !important;
    }

    .timeline-badge.success {
        background-color: #31CE36 !important;
    }

    .timeline-badge.warning {
        background-color: #FFAD46 !important;
    }

    .timeline-badge.danger {
        background-color: #F25961 !important;
    }

    .timeline-badge.info {
        background-color: #48ABF7 !important;
    }

    .timeline-title {
        font-size: 17px;
        margin-top: 0;
        color: inherit;
    }

    .timeline-heading i {
        font-size: 22px;
        display: inline-block;
        vertical-align: middle;
        margin-right: 5px;
    }

    .timeline-body>p,
    .timeline-body>ul {
        margin-bottom: 0;
    }

    .timeline-body>p+p {
        margin-top: 5px;
    }
</style>
<div class="app-main">
    <div class="app-main__outer">
        <div class="app-main__inner">
            <div class="app-page-title">
                <!-- Add this after your existing nav bars -->
                <div class="container-fluid">
                    <!-- Header Section -->
                    <div class="header-section d-flex justify-content-between align-items-center p-3 border-bottom bg-night-sky">
                        <div class="header-left d-flex align-items-center">
                            <button class="btn btn-secondary back-btn mr-3">
                                <i class="fas fa-arrow-left"></i>
                            </button>
                            <div class="profile-icon mr-3">
                                <img src="<?php echo base_url(); ?>assets_new/images/avatars/7.jpg" class="rounded-circle" width="70" height="70">
                            </div>
                            <div class="profile-details">
                                <h5 class="m-0 text-white"><?php echo $lead_details['customer_name']; ?></h5>
                                <small class="text-white"><?php echo $lead_details['customer_email']; ?></small><br>
                                <small class="text-white"><?php echo $lead_details['customer_country_code'] . ' ' . $lead_details['customer_mobile']; ?></small>
                            </div>
                        </div>
                        <div class="header-right">
                            <a href= "<?= base_url('leads/lead/view/'.$lead_details['id']) ?>" target="_blank">
                                <button class="btn btn-primary">View</button>
                            </a>
                        </div>
                    </div>
                    <!-- Main Container -->
                    <div class="main-container d-flex">
                        <!-- Sidebar -->
                        <div class="sidebar border-right p-3" style="width: 250px; cursor:pointer" id="sidebar">
                            <div class="mt-3">
                                <i class="fa fa-caret-left" style="margin:7px; font-size: x-large;!important" id="toggle-btn"></i>
                            </div>
                            <div class="collapse show mt-3">
                                <ul class="nav flex-column sidebar-menu">
                                    <li class="nav-item">
                                        <a class="nav-link" href="#"> &nbsp;&nbsp;Relative List</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#notes">Notes</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#attachments">Attachments</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#products">Services</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#open_activities">Open Activities</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#close_activities">Closed Activities</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#meetings">Meetings</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#emails">Emails</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <!-- Content Area -->
                        <div class="content-area flex-grow-1 p-4">
                            <div class="toggle-container">
                                <div class="highlight"></div>
                                <button class="toggle-btn active" id="overview">Overview</button>
                                <button class="toggle-btn " id="timeline">Timeline</button>
                            </div>
                            <div class="over-view-section" id="content">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="row">
                                                    <!-- Left Side: Created By -->
                                                    <div class="col-md-6">
                                                        <h5 class="mb-3">Created Details</h5>
                                                        <table class="table table-borderless">
                                                            <tbody>
                                                                <tr>
                                                                    <td><i class="fa fa-user"> Created by</i></td>
                                                                    <td><?php echo $lead_created_details['first_name'] . ' ' . $lead_created_details['last_name']; ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <td><i class="fa fa-map-marker"> Created Branch</i></td>
                                                                    <td><?php echo $lead_details['creator_branch']; ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <td><i class="fa fa-mobile"> Phone Number</i></td>
                                                                    <td><?php echo $lead_created_details['mobile']; ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <td><i class="fa fa-envelope"> Email</i></td>
                                                                    <td><?php echo $lead_created_details['email']; ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <td><i class="fa fa-check-circle"> Lead Status</i></td>
                                                                    <td><?php echo $lead_details["current_status"]; ?></td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>

                                                    <!-- Vertical Divider -->
                                                    <div class="col-md-1 d-none d-md-flex justify-content-center">
                                                        <div style="border-left: 1px solid #ccc; height: 100%;"></div>
                                                    </div>

                                                    <!-- Right Side: Assigned By -->
                                                    <div class="col-md-5">
                                                        <h5 class="mb-3">Assigned Details</h5>
                                                        <table class="table table-borderless">
                                                            <tbody>
                                                                <tr>
                                                                    <td><i class="fa fa-user"> Assigned to</i></td>
                                                                    <td><?php echo $lead_assigned_details['first_name'] . ' ' . $lead_assigned_details['last_name']; ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <td><i class="fa fa-map-marker"> Assigned Branch</i></td>
                                                                    <td><?php echo $lead_details['assigned_branch']; ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <td><i class="fa fa-mobile"> Phone Number</i></td>
                                                                    <td><?php echo $lead_assigned_details['mobile']; ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <td><i class="fa fa-envelope"> Email</i></td>
                                                                    <td><?php echo $lead_assigned_details['email']; ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <td><i class="fa fa-calendar-check"> Assigned On</i></td>
                                                                    <td><?php echo $lead_details['assigned_date']; ?></td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card mt-2">
                                            <div class="card-header mb-1 d-flex align-items-center justify-content-between" style="cursor: pointer;" id="toggle">
                                                <h6 class="m-0"><i class="fa fa-caret-down" id="icon"></i>&nbsp; <span id="toggleText">Hide Details</span></h6>
                                            </div>
                                            <div class="card-body" id="show_hide">
                                                <table class="table">
                                                    <tbody></tbody>
                                                    <tr>
                                                        <td>
                                                            <strong>Lead Additional Details:</strong><br />
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <strong>Cutomer Type:</strong><br />
                                                            <span class="text-ontime"><?php echo $lead_details["customer_type"]; ?></span>
                                                        </td>
                                                        <td>
                                                            <strong>Customer Country:</strong><br />
                                                            <span class="text-ontime"><?php echo $lead_details['customer_country']; ?></span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <strong>Category:</strong><br />
                                                            <span class="text-ontime"><?php echo $lead_details['category_name']; ?></span>
                                                        </td>
                                                        <td>
                                                            <strong>Service Name:</strong><br />
                                                            <span class="text-ontime"><?php echo $lead_details['service_name']; ?></span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <strong>Lead by:</strong><br />
                                                            <span class="text-ontime"> <?php echo $lead_details['branch_name']; ?></span>
                                                        </td>
                                                        <td>
                                                            <strong>Lead Type</strong><br>
                                                            <span style="text-transform: capitalize;"> <?php echo $lead_details["lead_type"]; ?></span>
                                                        </td>
                                                    </tr>
                                                    <?php if (!empty($view_data['lead_assigned_details_excess'])):
                                                        $excess = $view_data['lead_assigned_details_excess'];
                                                    ?>
                                                        <tr>
                                                            <td colspan="2">
                                                                <strong>Assigned Details:</strong><br><br>
                                                                <b><i class="fa fa-user"></i> Assigned by:</b> <?php echo $excess['first_name'] . ' ' . $excess['last_name']; ?><br>
                                                                <b><i class="fa fa-envelope"></i> Email:</b> <?php echo $excess['email']; ?><br>
                                                                <b><i class="fa fa-mobile"></i> Mobile:</b> <?php echo $excess['mobile']; ?>
                                                            </td>
                                                        </tr>
                                                    <?php endif; ?>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" id="notes">
                                    <div class="col-md-12">
                                        <div class="card mt-2">
                                            <div class="card-header">
                                                <span class="head" id="notes_header">Notes</span>
                                            </div>
                                            <div class="card-body active overview-content" id="notes_body">

                                                <?php
                                                $has_main_remark = !empty(trim($lead_details['remarks']));
                                                $has_old_remarks = !empty($view_data['lead_status_304_remarks']);
                                                ?>

                                                <?php if ($has_main_remark): ?>
                                                    <!-- Latest Lead Remark -->
                                                    <div>
                                                        <?php echo nl2br($lead_details['remarks']); ?>
                                                    </div>
                                                <?php endif; ?>

                                                <?php if ($has_old_remarks): ?>
                                                    <!-- Divider -->
                                                    <hr style="border-top: 1px solid #bbb; margin: 20px auto; width: 50%;">
                                                    <p class="text-center text-muted" style="margin-top: -15px;">Latest Remarks</p>

                                                    <!-- Old Remarks -->
                                                    <?php
                                                    // Sort by date if needed
                                                    usort($view_data['lead_status_304_remarks'], function ($a, $b) {
                                                        return strtotime($a['action_on']) - strtotime($b['action_on']);
                                                    });

                                                    foreach ($view_data['lead_status_304_remarks'] as $remark) {
                                                        echo '<div style="margin-bottom: 10px;">';
                                                        echo '<strong>' . date('d M Y, h:i A', strtotime($remark['action_on'])) . '</strong><br>';
                                                        echo nl2br(htmlspecialchars($remark['remarks']));
                                                        echo '</div>';
                                                    }
                                                    ?>
                                                <?php endif; ?>

                                                <?php if (!$has_main_remark && !$has_old_remarks): ?>
                                                    <!-- No Notes Message -->
                                                    <p class="text-muted text-center">No notes found.</p>
                                                <?php endif; ?>

                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="row" id="attachments">
                                    <div class="col-md-12">
                                        <div class="card mt-2">
                                            <div class="card-header">
                                                <span class="head" id="attachments_header">Attachments</span>
                                            </div>
                                            <!-- <div class="card-body active overview-content" id="attachments_body">
                                                <?php
                                                // Define branch configuration
                                                $branch = $lead_details['branch_name'] ?? '';
                                                $isGoldenCube = ($branch === "Golden Cube");
                                                $isBarahaVan = ($branch === "Baraha Van");

                                                // Set search keyword and base paths
                                                $searchKeyword = $isGoldenCube || $isBarahaVan ? 'Documents' : 'DOCUMENTS';
                                                $anc = $lead_details['remarks'];
                                                $basePaths = [
                                                    'Golden Cube' => 'assets/uploads/gc/',
                                                    'Baraha Van' => 'assets/uploads/bv/',
                                                    'default' => ['assets/uploads/', 'serve_filemain/', 'api/serve_file/']
                                                ];

                                                // Extract content after keyword
                                                $pos = strpos($anc, $searchKeyword);
                                                $bbn = ($pos !== false) ? trim(substr($anc, $pos + strlen($searchKeyword))) : '';

                                                // Process HTML content
                                                libxml_use_internal_errors(true);
                                                $doc = new DOMDocument();
                                                $doc->loadHTML('<?xml encoding="utf-8" ?>' . $bbn);
                                                libxml_clear_errors();

                                                // Process anchor tags
                                                $anchors = $doc->getElementsByTagName('a');
                                                $fileData = [];

                                                foreach ($anchors as $anchor) {
                                                    $textContent = trim($anchor->textContent);
                                                    $href = $anchor->getAttribute('href');

                                                    // Clean and parse URL
                                                    if (!$isGoldenCube && !$isBarahaVan) {
                                                        $parsedUrl = parse_url($href);
                                                        $href = ($parsedUrl['scheme'] ?? 'https') . '://'
                                                            . ($parsedUrl['host'] ?? '')
                                                            . ($parsedUrl['path'] ?? '');
                                                    }

                                                    // Extract file path
                                                    $filePath = $this->extractFilePath($href, $basePaths[$branch] ?? $basePaths['default']);
                                                    if ($filePath) {
                                                        $fileData[] = [
                                                            'text' => $textContent,
                                                            'path' => rawurlencode($filePath)
                                                        ];
                                                    }
                                                }

                                                // Process remarks
                                                $remarks = $lead_details['remarks'] ?? '';
                                                $keywordPos = strpos($remarks, $searchKeyword);

                                                if ($keywordPos !== false) {
                                                    $remarks = preg_replace(
                                                        '/(<a\b[^>]*>.*?<\/a>)(.*)$/is',
                                                        '$1',
                                                        substr($remarks, 0, $keywordPos + strlen($searchKeyword))
                                                    );
                                                }

                                                // Output processed data
                                                ?>
                                                <tr>
                                                    <td colspan="2"><strong>Documents</strong></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">
                                                        <?php foreach ($fileData as $file): ?>
                                                            <?php $url = $this->generateFileUrl($file['path'], $branch); ?>
                                                            <a href="<?= $url ?>" target="_blank" class="document-link">
                                                                <?= $file['text'] ?>
                                                            </a><br>
                                                        <?php endforeach; ?>

                                                        <?php if ($lead_current_status == 321): ?>
                                                            <br><br>
                                                            <a href="/leads/lead/golden_cube_lead/<?= $lead_details['id'] ?>"
                                                                class="btn btn-primary">
                                                                Convert To GoldenCube Order
                                                            </a>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>

                                                <?php if (!empty($lead_attachments)): ?>
                                                    <tr>
                                                        <td colspan="2"><strong>Attachments</strong></td>
                                                    </tr>
                                                    <?php foreach ($lead_attachments as $attachment): ?>
                                                        <tr>
                                                            <td><?= strtoupper($attachment['attachment_name']) ?></td>
                                                            <td>
                                                                <a href="<?= $attachment['attachment_url'] ?>" target="_blank">
                                                                    <span class="fiv-viv fiv-icon-doc"></span>
                                                                    View file
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>

                                                <?php if (!empty($lead_details['order_receipt'])): ?>
                                                    <tr>
                                                        <td colspan="2">
                                                            <strong>ORDER ID#:</strong><br>
                                                            <span class="text-ontime"><?= $lead_details['order_receipt'] ?></span>
                                                        </td>
                                                    </tr>
                                                <?php endif; ?>

                                                <?php
                                                // Helper methods in class context
                                                function extractFilePath($href, $basePaths)
                                                {
                                                    if (!is_array($basePaths)) {
                                                        $basePaths = [$basePaths];
                                                    }

                                                    foreach ($basePaths as $base) {
                                                        $pos = strpos($href, $base);
                                                        if ($pos !== false) {
                                                            $filePath = substr($href, $pos + strlen($base));
                                                            return str_replace(['?token=tdhi', 'tdhiOrATsvWorX'], '', $filePath);
                                                        }
                                                    }
                                                    return null;
                                                }

                                                function generateFileUrl($encodedPath, $branch)
                                                {
                                                    $baseUrl = 'https://ontimegov.com/digital/sapi/';
                                                    $endpoints = [
                                                        'Golden Cube' => 'serve_file_gc/',
                                                        'Baraha Van' => 'serve_file_bv/',
                                                        'default' => 'serve_file/'
                                                    ];

                                                    $endpoint = $endpoints[$branch] ?? $endpoints['default'];
                                                    return $baseUrl . $endpoint . $encodedPath . '?token=tdhi';
                                                }
                                                ?>
                                            </div> -->
                                            <div class="card-body active overview-content" id="attachments_body">
                                                <?php
                                                // Define branch configuration
                                                $branch = $lead_details['branch_name'] ?? '';
                                                $isGoldenCube = ($branch === "Golden Cube");
                                                $isBarahaVan = ($branch === "Baraha Van");

                                                // Set search keyword and base paths
                                                $searchKeyword = $isGoldenCube || $isBarahaVan ? 'Documents' : 'DOCUMENTS';
                                                $basePaths = [
                                                    'Golden Cube' => 'assets/uploads/gc/',
                                                    'Baraha Van' => 'assets/uploads/bv/',
                                                    'default' => ['assets/uploads/', 'serve_filemain/', 'api/serve_file/']
                                                ];

                                                // Extract content after keyword
                                                $pos = strpos($anc, $searchKeyword);
                                                $bbn = ($pos !== false) ? trim(substr($anc, $pos + strlen($searchKeyword))) : '';

                                                // Process HTML content
                                                libxml_use_internal_errors(true);
                                                $doc = new DOMDocument();
                                                $doc->loadHTML('<?xml encoding="utf-8" ?>' . $bbn);
                                                libxml_clear_errors();

                                                // Process anchor tags
                                                $anchors = $doc->getElementsByTagName('a');
                                                $fileData = [];

                                                foreach ($anchors as $anchor) {
                                                    $textContent = trim($anchor->textContent);
                                                    $href = $anchor->getAttribute('href');

                                                    if (!$isGoldenCube && !$isBarahaVan) {
                                                        $parsedUrl = parse_url($href);
                                                        $href = ($parsedUrl['scheme'] ?? 'https') . '://'
                                                            . ($parsedUrl['host'] ?? '')
                                                            . ($parsedUrl['path'] ?? '');
                                                    }

                                                    $filePath = $this->extractFilePath($href, $basePaths[$branch] ?? $basePaths['default']);
                                                    if ($filePath) {
                                                        $fileData[] = [
                                                            'text' => $textContent,
                                                            'path' => rawurlencode($filePath)
                                                        ];
                                                    }
                                                }

                                                // Check if all attachments data are empty
                                                $hasDocs = !empty($fileData);
                                                $hasAttachments = !empty($lead_attachments);
                                                $hasReceipt = !empty($lead_details['order_receipt']);
                                                ?>

                                                <?php if ($hasDocs || $hasAttachments || $hasReceipt): ?>
                                                    <table class="table">
                                                        <?php if ($hasDocs): ?>
                                                            <tr>
                                                                <td colspan="2"><strong>Documents</strong></td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2">
                                                                    <?php foreach ($fileData as $file): ?>
                                                                        <?php $url = $this->generateFileUrl($file['path'], $branch); ?>
                                                                        <a href="<?= $url ?>" target="_blank" class="document-link">
                                                                            <?= $file['text'] ?>
                                                                        </a><br>
                                                                    <?php endforeach; ?>

                                                                    <?php if ($lead_current_status == 321): ?>
                                                                        <br><br>
                                                                        <a href="/leads/lead/golden_cube_lead/<?= $lead_details['id'] ?>" class="btn btn-primary">
                                                                            Convert To GoldenCube Order
                                                                        </a>
                                                                    <?php endif; ?>
                                                                </td>
                                                            </tr>
                                                        <?php endif; ?>

                                                        <?php if ($hasAttachments): ?>
                                                            <tr>
                                                                <td colspan="2"><strong>Attachments</strong></td>
                                                            </tr>
                                                            <?php foreach ($lead_attachments as $attachment): ?>
                                                                <tr>
                                                                    <td><?= strtoupper($attachment['attachment_name']) ?></td>
                                                                    <td>
                                                                        <a href="<?= $attachment['attachment_url'] ?>" target="_blank">
                                                                            <span class="fiv-viv fiv-icon-doc"></span>
                                                                            View file
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                            <?php endforeach; ?>
                                                        <?php endif; ?>

                                                        <?php if ($hasReceipt): ?>
                                                            <tr>
                                                                <td colspan="2">
                                                                    <strong>ORDER ID#:</strong><br>
                                                                    <span class="text-ontime"><?= $lead_details['order_receipt'] ?></span>
                                                                </td>
                                                            </tr>
                                                        <?php endif; ?>
                                                    </table>
                                                <?php else: ?>
                                                    <div class="text-center py-3">
                                                        <span class="text-muted">No Email Request Associated</span>
                                                    </div>
                                                <?php endif; ?>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="row" id="products">
                                    <div class="col-md-12">
                                        <div class="card mt-2">
                                            <div class="card-header">
                                                <span class="head" id="products_header">Services</span>
                                            </div>
                                            <div class="card-body active overview-content" id="products_body">
                                                <?php
                                                if (!empty($lead_package_deatils)) {
                                                    foreach ($lead_package_deatils as $package) {
                                                        // Get the service name
                                                        $service_id = $package['service_id'];
                                                        $service_info = array_filter($lead_service_deatils, function ($service) use ($service_id) {
                                                            return $service['id'] == $service_id;
                                                        });

                                                        $service_name = !empty($service_info) ? array_values($service_info)[0]['service_name'] : 'Unknown Service';

                                                        // Get the created by user
                                                        $created_by = $package['created_by'];
                                                        $user_info = $this->mcommon->specific_row_fields('users', ['user_id' => $created_by], ['first_name', 'last_name']);
                                                        $created_by_name = !empty($user_info) ? trim($user_info['first_name'] . ' ' . $user_info['last_name']) : 'Unknown User';
                                                ?>
                                                        <div class="card mb-2 shadow-sm border">
                                                            <div class="card-body">
                                                                <h5 class="text-primary"><?php echo $service_name; ?></h5>
                                                                <ul class="mb-2">
                                                                    <li><strong>Gov. Fee:</strong> ₹<?php echo number_format($package['gov_fee'], 2); ?></li>
                                                                    <li><strong>Typing Fee:</strong> ₹<?php echo number_format($package['typing_fee'], 2); ?></li>
                                                                    <li><strong>Card Amount:</strong> ₹<?php echo number_format($package['card_amount'], 2); ?></li>
                                                                    <li><strong>Sub Total:</strong> ₹<?php echo number_format($package['sub_total'], 2); ?></li>
                                                                    <li><strong>Payment Type:</strong> <?php echo ucfirst($package['payment_type']); ?></li>
                                                                    <li><strong>Direct Invoice:</strong> <?php echo $package['is_direct_invoice'] ? 'Yes' : 'No'; ?></li>
                                                                </ul>
                                                                <p class="mb-0"><strong>Created By:</strong> <?php echo $created_by_name; ?></p>
                                                            </div>
                                                        </div>
                                                    <?php
                                                    }
                                                } else { ?>
                                                    <div class="text-center py-3">
                                                        <span class="text-muted">You have no meetings scheduled with this customer.</span>
                                                    </div>
                                                <?php } ?>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Open Activities -->
                                <div class="row" id="open_activities">
                                    <div class="col-md-12">
                                        <div class="card mt-2">
                                            <div class="card-header">
                                                <span class="head" id="open_activities_header">Open Activities</span>
                                            </div>
                                            <div class="card-body active overview-content" id="open_activities_body">
                                                <ul class="list-group">
                                                    <?php if (!empty($latest_lead_action_open)) {
                                                        foreach ($latest_lead_action_open as $log) { ?>
                                                            <li class="list-group-item">
                                                                <div class="todo-indicator bg-info"></div>
                                                                <div class="widget-content p-0">
                                                                    <div class="widget-content-wrapper">
                                                                        <div class="widget-content-left mr-2"></div>
                                                                        <div class="widget-content-left">
                                                                            <a href="<?php echo base_url('leads/lead/view/' . $log["lead_id"]); ?>">
                                                                                <div class="widget-heading">
                                                                                    <?php echo $log["action_name"] . " - #" . $log["lead_id"]; ?>
                                                                                </div>
                                                                            </a>
                                                                            <div class="widget-subheading">
                                                                                <div><?php echo $log["log_remarks"]; ?></div>
                                                                                <small class="text-muted">
                                                                                    <?php echo date("d M Y, h:i A", strtotime($log["action_on"])); ?>
                                                                                </small>
                                                                            </div>
                                                                        </div>
                                                                        <div class="widget-content-right">
                                                                            <a href="<?php echo base_url('leads/lead/view/' . $log["lead_id"]); ?>">
                                                                                <button class="border-0 btn-transition btn btn-outline-info">
                                                                                    <i class="fa fa-eye"></i>
                                                                                </button>
                                                                            </a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                        <?php }
                                                    } else { ?>
                                                        <div class="text-center py-3">
                                                            <span class="text-muted">No open activities found.</span>
                                                        </div>
                                                    <?php } ?>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Closed Activities -->
                                <div class="row" id="close_activities">
                                    <div class="col-md-12">
                                        <div class="card mt-2">
                                            <div class="card-header">
                                                <span class="head" id="close_activities_header">Closed Activities</span>
                                            </div>
                                            <div class="card-body active overview-content" id="close_activities_body">
                                                <ul class="list-group">
                                                    <?php if (!empty($latest_lead_action_closed)) {
                                                        foreach ($latest_lead_action_closed as $log) { ?>
                                                            <li class="list-group-item">
                                                                <div class="todo-indicator bg-success"></div>
                                                                <div class="widget-content p-0">
                                                                    <div class="widget-content-wrapper">
                                                                        <div class="widget-content-left mr-2"></div>
                                                                        <div class="widget-content-left">
                                                                            <a href="<?php echo base_url('leads/lead/view/' . $log["lead_id"]); ?>">
                                                                                <div class="widget-heading">
                                                                                    <?php echo $log["action_name"] . " - #" . $log["lead_id"]; ?>
                                                                                </div>
                                                                            </a>
                                                                            <div class="widget-subheading">
                                                                                <div><?php echo $log["log_remarks"]; ?></div>
                                                                                <small class="text-muted">
                                                                                    <?php echo date("d M Y, h:i A", strtotime($log["action_on"])); ?>
                                                                                </small>
                                                                            </div>
                                                                        </div>
                                                                        <div class="widget-content-right">
                                                                            <a href="<?php echo base_url('leads/lead/view/' . $log["lead_id"]); ?>">
                                                                                <button class="border-0 btn-transition btn btn-outline-success">
                                                                                    <i class="fa fa-eye"></i>
                                                                                </button>
                                                                            </a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                        <?php }
                                                    } else { ?>
                                                        <div class="text-center py-3">
                                                            <span class="text-muted">No closed activities found.</span>
                                                        </div>
                                                    <?php } ?>

                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row" id="meetings">
                                    <div class="col-md-12">
                                        <div class="card mt-2">
                                            <div class="card-header">
                                                <span class="head" id="meetings_header">Meetings</span>
                                            </div>
                                            <div class="card-body active overview-content" id="meetings_body">
                                                <?php if (empty($upcoming_meetings)) : ?>
                                                    <div class="text-center py-3">
                                                        <span class="text-muted">You have no meetings scheduled with this customer.</span>
                                                    </div>
                                                <?php else : ?>
                                                    <?php foreach ($upcoming_meetings as $meeting) : ?>
                                                        <div class="vertical-timeline-item vertical-timeline-element">
                                                            <div>
                                                                <div class="vertical-timeline-element-icon bounce-in">
                                                                    <div class="timeline-icon border-success bg-success">
                                                                        <i class="fa fa-user-friends text-white"></i>
                                                                    </div>
                                                                </div>
                                                                <div class="vertical-timeline-element-content bounce-in">
                                                                    <h4 class="timeline-title text-success">
                                                                        Meeting with <?php echo htmlspecialchars($lead_details['customer_name']); ?>
                                                                        <a href="#meetingModal"
                                                                            class="open-meetingDialog"
                                                                            data-toggle="modal"
                                                                            data-meetingid="<?php echo $meeting['id']; ?>"
                                                                            data-leadid="<?php echo $lead_details['id']; ?>">
                                                                            <i class="fa fa-edit pl-2"></i>
                                                                        </a>
                                                                    </h4>
                                                                    <p><?php echo date('d M Y h:i A', strtotime($meeting['meeting_date_time'])); ?></p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" id="emails">
                                    <div class="col-md-12">
                                        <div class="card mt-2">
                                            <div class="card-header">
                                                <span class="head" id="emails_header">Emails</span>
                                            </div>
                                            <div class="card-body active overview-content" id="emails_body">
                                                <?php if (!empty(trim($lead_details['email_request_id']))) { ?>
                                                    <div class="app-inner-layout__wrapper row-fluid no-gutters" id="followups">
                                                        <?php if (!empty($conversations)) { ?>
                                                            <div id="conversationAccordion" class="accordion mb-3 w-100">
                                                                <?php foreach ($conversations as $key => $conversation) { ?>
                                                                    <div class="item border">
                                                                        <div id="heading<?= $key ?>" class="bg-midnight-bloom d-flex">
                                                                            <button class="btnAcct text-left m-0 btn btn-link text-white btn-link btn-block p-3 collapsed"
                                                                                type="button"
                                                                                data-toggle="collapse"
                                                                                data-target="#collapse<?= $key ?>"
                                                                                data-contenturl="<?= $conversation->content_url ?>"
                                                                                aria-expanded="false"
                                                                                aria-controls="collapse<?= $key ?>">
                                                                                <h5 class="m-0 p-0">
                                                                                    <?= $conversation->from->name ?>
                                                                                    <span class="float-right"><?= $conversation->sent_time->display_value ?></span>
                                                                                </h5>
                                                                            </button>
                                                                            <div class="btn-group ml-auto">
                                                                                <button class="btn btn-outline-light replyBtn"
                                                                                    title="Reply"
                                                                                    data-tomail="<?= $conversation->from->email_id ?>"
                                                                                    data-contenturl="<?= $conversation->content_url ?>">
                                                                                    <i class="fa fa-reply"></i>
                                                                                </button>
                                                                                <button class="btn btn-outline-light forwardBtn"
                                                                                    title="Forward"
                                                                                    data-tomail="<?= $conversation->from->email_id ?>"
                                                                                    data-contenturl="<?= $conversation->content_url ?>">
                                                                                    <i class="fa fa-share"></i>
                                                                                </button>
                                                                            </div>
                                                                        </div>
                                                                        <div id="collapse<?= $key ?>" class="collapse"
                                                                            aria-labelledby="heading<?= $key ?>"
                                                                            data-parent="#conversationAccordion">
                                                                            <div class="card-body">
                                                                                <div class="subject"></div>
                                                                                <hr>
                                                                                <div class="attachments" style="font-size: large; font-weight: 500;"></div>
                                                                                <div class="description"></div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                <?php } ?>
                                                            </div>
                                                        <?php } else { ?>
                                                            <div class="text-center py-3">
                                                                <span class="text-muted">No Conversations Found</span>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                <?php } else { ?>
                                                    <div class="text-center py-3">
                                                        <span class="text-muted">No Email Request Associated</span>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="content-section timeline-content">
                                <div class="card mt-2">
                                    <div class="card-body ">
                                        <h3 class="fw-bold mb-3">Timeline
                                            <?php
                                            if ($lead_details["lead_amount"] > 0) {
                                                if ($paid_amount < $lead_details["lead_amount"]) {
                                                    echo "<span class='float-right badge badge-secondary'>Outstanding Payment</span>";
                                                }
                                            }
                                            ?>
                                        </h3>
                                        <div class="col-md-12">

                                            <ul class="timeline">
                                                <?php
                                                $is_quotation_shown = false;
                                                $is_need_quotation = false;
                                                $is_quotation_link = 0;
                                                 $class = "info";
                                                 $icon = "fa fa-info-circle";
                                                foreach ($timeline as $key => $value) {
                                                    $class = "black";
                                                    $icon = "fa fa-clipboard";
                                                    $rowClass = "timeline-inverted";
                                                    if ($value["action_id"] == 401 || $value["action_id"] == 407 || $value["action_id"] == 413 || $value["action_id"] == 419) {
                                                        $class = "secondary";
                                                        $icon = "fa fa-paper-plane";
                                                        $rowClass = "timeline-inverted";
                                                    }

                                                    if ($value["action_id"] == 402 || $value["action_id"] == 408 || $value["action_id"] == 414 || $value["action_id"] == 420) {
                                                        $class = "success";
                                                        $icon = "fa fa-check";
                                                        $rowClass = "timeline-inverted";
                                                    }

                                                    if ($value["action_id"] == 403 || $value["action_id"] == 409 || $value["action_id"] == 415 || $value["action_id"] == 421 || $value['action_id'] == 432) {
                                                        $class = "info";
                                                        $icon = "fa fa-info";
                                                        $rowClass = "timeline-inverted";
                                                    }

                                                    if ($value["action_id"] == 404 || $value["action_id"] == 410 || $value["action_id"] == 416 || $value["action_id"] == 422) {
                                                        $class = "warning";
                                                        $icon = "fa fa-bell";
                                                        $rowClass = "timeline-inverted";
                                                    }

                                                    if ($value["action_id"] == 405 || $value["action_id"] == 411 || $value["action_id"] == 417 || $value["action_id"] == 423) {
                                                        $class = "primary";
                                                        $icon = "fa fa-check";
                                                        $rowClass = "timeline-inverted";
                                                    }

                                                    if ($value["action_id"] == 439 || $value["action_id"] == 410 || $value["action_id"] == 423) {
                                                        // order confirmed 
                                                        $class = "success";
                                                        $icon = "fa fa-check";
                                                        $rowClass = "timeline-inverted";
                                                    } elseif ($value["action_id"] == 417) {
                                                        // Cash
                                                        $class = "success";
                                                        $icon = "fa fa-money-bill"; // You can also use 'fa fa-money'
                                                        $rowClass = "timeline-inverted";
                                                    } elseif ($value["action_id"] == 418) {
                                                        // Card
                                                        $class = "success";
                                                        $icon = "fa fa-credit-card";
                                                        $rowClass = "timeline-inverted";
                                                    } elseif ($value["action_id"] == 415 || $value["action_id"] == 434) {
                                                        // Online
                                                        $class = "success";
                                                        $icon = "fa fa-globe"; // You can also use 'fa fa-wifi' or 'fa fa-laptop'
                                                        $rowClass = "timeline-inverted";
                                                    } elseif ($value["action_id"] == 431) {
                                                        // hold
                                                        $class = "warning";
                                                        $icon = "fa fa-hourglass-half"; // or 'fa fa-clock' or 'fa fa-pause-circle'
                                                        $rowClass = "timeline-inverted";
                                                    } elseif ($value["action_id"] == 411 || $value["action_id"] == 424 || $value["action_id"] == 436 || $value["action_id"] == 440) {
                                                        // disqualified
                                                        $class = "danger";
                                                        $icon = "fa fa-ban"; // or 'fa fa-times-circle' or 'fa fa-user-times'
                                                        $rowClass = "timeline-inverted";
                                                         //follow ups
                                                    }elseif ($value["action_id"] == 442) {
                                                        // quotation process
                                                        $class = "info";
                                                        $icon = "fa fa-file-alt"; // or 'fa fa-clock' or 'fa fa-pause-circle'
                                                        $rowClass = "timeline-inverted";
                                                    }elseif($value["action_id"] == 443){
                                                        // quotation accepted
                                                        $class = "success";
                                                        $icon = "fa fa-file-alt"; 
                                                        $rowClass = "timeline-inverted";
                                                    }elseif($value["action_id"] == 444){
                                                        // quotation accepted
                                                        $class = "danger";
                                                        $icon = "fa fa-file-alt"; 
                                                        $rowClass = "timeline-inverted";
                                                    }elseif($value["action_id"] == 412){
                                                        // general payment
                                                        $class = "info";
                                                        $icon = "fa fa-money-check-alt";
                                                        $rowClass = "timeline-inverted";
                                                    }elseif($value["action_id"] == 426){
                                                        // general payment
                                                        $class = "info";
                                                        $icon = "fa fa-money-check-alt"; 
                                                        $rowClass = "timeline-inverted";
                                                    }elseif($value["action_id"] == 442){
                                                        // card payment
                                                        $class = "info";
                                                        $icon = "fa fa-credit-card"; 
                                                        $rowClass = "timeline-inverted";
                                                    }elseif($value["action_id"] == 430 || $value["action_id"] == 404 || $value["action_id"] == 413 ){
                                                        // email 
                                                        $class = "info";
                                                        $icon = "fa fa-envelope"; 
                                                        $rowClass = "timeline-inverted";
                                                    }elseif($value["action_id"] == 405){
                                                        // calls 
                                                        $class = "info";
                                                        $icon = "fa fa-phone"; 
                                                        $rowClass = "timeline-inverted";
                                                    }elseif($value["action_id"] == 407 || $value["action_id"] == 409){
                                                        // meetings 
                                                        $class = "info";
                                                        $icon = "fa fa-handshake"; 
                                                        $rowClass = "timeline-inverted";
                                                    }elseif($value["action_id"] == 408){
                                                        // custom 
                                                        $class = "info";
                                                        $icon = "fa fa-cogs"; 
                                                        $rowClass = "timeline-inverted";
                                                    }elseif($value["action_id"] == 414){
                                                        //zoho update  
                                                        $class = "info";
                                                        $icon = "fa fa-sync-alt"; 
                                                        $rowClass = "timeline-inverted";
                                                    }elseif($value["action_id"] == 416){
                                                        // salesorder 
                                                        $class = "info";
                                                        $icon = "fa fa-tasks"; 
                                                        $rowClass = "timeline-inverted";
                                                    }elseif($value["action_id"] == 418){
                                                        // Lead Type Conversion 
                                                        $class = "info";
                                                        $icon = "fa fa-random"; 
                                                        $rowClass = "timeline-inverted";
                                                    }elseif($value["action_id"] == 421){
                                                        // Status Update 
                                                        $class = "info";
                                                        $icon = "fa fa-flag"; 
                                                        $rowClass = "timeline-inverted";
                                                    }elseif($value["action_id"] == 433){
                                                        // Calendar - Booking Confirmed / Not Paid 
                                                        $class = "info";
                                                        $icon = "fa fa-calendar-check"; 
                                                        $rowClass = "timeline-inverted";
                                                    }elseif($value["action_id"] == 437){
                                                        // Calendar - No Show
                                                        $class = "warning";
                                                        $icon = "fa fa-user-slash"; 
                                                        $rowClass = "timeline-inverted";
                                                    }elseif($value["action_id"] == 425 || $value["action_id"] == 435 || $value["action_id"] == 441){
                                                        // retrun or reshedule
                                                        $class = "warning";
                                                        $icon = "fa fa-exchange-alt"; 
                                                        $rowClass = "timeline-inverted";
                                                    }elseif($value["action_id"] == 427){
                                                        // missing documents
                                                        $class = "warning";
                                                        $icon = "fa fa-folder-open"; 
                                                        $rowClass = "timeline-inverted";
                                                    }elseif($value["action_id"] == 428){
                                                        // reopen 
                                                        $class = "primary";
                                                        $icon = "fa fa-undo"; 
                                                        $rowClass = "timeline-inverted";
                                                    }elseif($value["action_id"] == 428){
                                                        // refund 
                                                        $class = "danger";
                                                        $icon = "fa fa-money-bill-wave"; 
                                                        $rowClass = "timeline-inverted";
                                                    }elseif($value["action_id"] == 401){
                                                        // created 
                                                        $class = "secondary";
                                                        $icon = "fa fa-user-plus"; 
                                                        $rowClass = "timeline-inverted";
                                                    }elseif($value["action_id"] == 402){
                                                        // Accepted 
                                                        $class = "secondary";
                                                        $icon = "fa fa-check"; 
                                                        $rowClass = "timeline-inverted";
                                                    }elseif($value["action_id"] == 403){
                                                        // Assigned 
                                                        $class = "secondary";
                                                        $icon = "fa fa-user-check"; 
                                                        $rowClass = "timeline-inverted";
                                                    }elseif($value["action_id"] == 420){
                                                        // Eligibility Check 
                                                        $class = "secondary";
                                                        $icon = "fa fa-clipboard-check"; 
                                                        $rowClass = "timeline-inverted";
                                                    }elseif($value["action_id"] == 432){
                                                        // Pending for Confirmation
                                                        $class = "secondary";
                                                        $icon = "fa fa-clock"; 
                                                        $rowClass = "timeline-inverted";
                                                    }

                                                ?>
                                                    <li class="<?php echo ($rowClass) ?? ''; ?>">
                                                        <div class="timeline-badge <?php echo $class; ?>">
                                                            <i class="<?php echo $icon; ?>"></i>
                                                        </div>
                                                        <div class="timeline-panel">
                                                            <div class="timeline-heading">
                                                                <h4 class="timeline-title"><?php echo $value['action_name'];
                                                                                            if ($value["action_amount"] > 0) {
                                                                                                echo " - ( " . $value["action_amount"] . " AED )";
                                                                                            }
                                                                                            ?>
                                                                </h4>
                                                                <p>
                                                                    <small class="text-muted">
                                                                        <i class="fa fa-clock"></i>
                                                                        <?php echo date('H:i A', strtotime($value['action_on'])) . " " . date('d M Y', strtotime($value['action_on'])); ?>
                                                                    </small>
                                                                </p>
                                                            </div>
                                                            <div class="timeline-body">
                                                                <p>
                                                                    <?php
                                                                    echo  quoted_printable_decode($value['remarks']);

                                                                    // if ($value['payment_link'] != null && $value["action_id"] != 426) { {
                                                                    //     }
                                                                    //     echo " - <button class='bg-primary border-0 btn btn-xs p-2 text-white cpy-data' data-data='" . $value['payment_link'] . "' style='font-size: 87%;padding: 7px 17px !important;border-radius: 60px;'>Copy Link for AED " . $value["action_amount"] . "/-</button>";
                                                                    // }
                                                                    // if ($value["action_id"] == 432) {

                                                                    //     $download_link = $_SERVER["HOST_NAME"] . "/leads/lead/generatePdf?token=" . encrypt_decrypt($lead_details["id"]) . "&action=download";
                                                                    //     echo "<a target='_blank' class='badge badge-primary ml-3 text-capitalize cpy-download' href='" . $download_link . "' data-data='" . $download_link . "'>Download Contract</a>";
                                                                    // }
                                                                    // if ($value["action_id"] == 413 || $value["action_id"] == 416) {

                                                                    // $download_link = $_SERVER["HOST_NAME"] . "/leads/lead/generatePdf?token=" . encrypt_decrypt($lead_details["id"]) . "&action=receipt";
                                                                    // echo "<a target='_blank' class='badge badge-primary ml-3 text-capitalize cpy-download' href='" . $download_link . "' data-data='" . $download_link . "'>Download Receipt</a>";
                                                                    // $download_link = $_SERVER["HOST_NAME"] . "/leads/lead/generatePdf?token=" . encrypt_decrypt($lead_details["id"]) . "&action=invoice";
                                                                    // echo "<a target='_blank' class='badge badge-primary ml-3 text-capitalize cpy-download' href='" . $download_link . "' data-data='" . $download_link . "'>Download Invoice</a>";
                                                                    // }
                                                                    ?>
                                                                </p>
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
<script>
    document.getElementById("toggle-btn").addEventListener("click", function() {
        let sidebar = document.getElementById("sidebar");
        sidebar.classList.toggle("collapsed");
        let icon = this;
        if (icon.classList.contains("fa-caret-left")) {
            icon.classList.remove("fa-caret-left");
            icon.classList.add("fa-caret-right");
        } else {
            icon.classList.remove("fa-caret-right");
            icon.classList.add("fa-caret-left");
        }
    });
</script>
<script>
    $(document).ready(function() {
        $('.toggle-container').each(function() {
            $(this).click(function() {
                if ($('#overview').hasClass('active')) {
                    $('#overview').removeClass('active');
                    $('#timeline').addClass('active');
                    $('.over-view-section').hide();
                    $('.timeline-content').show();
                    $('.highlight').css("left", "135px");
                    $('html, body').css('overflow-y', 'auto');


                } else {
                    $('#overview').addClass('active');
                    $('#timeline').removeClass('active');
                    $('.over-view-section').show();
                    $('.timeline-content').hide();
                    $('.highlight').css("left", "0")
                    $('html, body').css('overflow-y', 'hidden');
                }
            });
        });
        $(".sidebar-menu .nav-link").click(function(event) {
            if (!$('#overview').hasClass('active')) {
                $('#overview').addClass('active');
                $('#timeline').removeClass('active');
                $('.over-view-section').show();
                $('.timeline-content').hide();
                $('.highlight').css("left", "0")
                $('html, body').css('overflow-y', 'hidden');
            }

            $('.head').css('background-color', '');
            event.preventDefault();
            var targetId = $(this).attr("href");
            var targetElement = $(targetId);
            $(targetId + "_header").css('background-color', 'yellow');
            if (targetElement.length) {
                var targetPosition = targetElement.position().top + $("#content").scrollTop(); // Get position inside #content
                $("#content").animate({
                    scrollTop: targetPosition
                }, 600); // Smooth scroll inside #content
            }
        });
        $("#show_hide").show();
        $("#toggle").click(function() {
            $("#show_hide").toggle();
            if ($("#show_hide").is(":visible")) {
                $("#icon").removeClass("fa-caret-up").addClass("fa-caret-down");
                $('#toggle').css("border-bottom", "1px solid #ddd")
                $("#toggleText").text("Hide Details");
            } else {
                $("#icon").removeClass("fa-caret-down").addClass("fa-caret-up");
                $('#toggle').css("border-bottom", "none");
                $("#toggleText").text("Show Details");
            }
        });
        $('.overview-content').show();
        $(".head").click(function() {
            let id = $(this).attr('id');
            id = id.replace("header", "");
            console.log(id);
            if ($('#' + id + 'body').hasClass('active')) {
                $('#' + id + 'body').removeClass('active');
                $('#' + id + 'body').hide();
            } else {
                $('#' + id + 'body').addClass('active');
                $('#' + id + 'body').show();
            }
        })
    })
</script>