<?php
$seg1 = $this->uri->segment(1);
$seg2 = $this->uri->segment(2);
$seg3 = $this->uri->segment(3);
$seg4 = $this->uri->segment(4);
$accessible_websites = get_my_access($this->auth_user_id);

?>
<!--**********************************
    Sidebar start
***********************************-->
<div class="deznav">
    <div class="deznav-scroll">
        <ul class="metismenu" id="menu">

            <?php
            if ($this->auth_user_role == 2 || $this->auth_user_role == 6) {
                ?>
                    <li><a class="ai-icon" href="<?php echo base_url(); ?>" aria-expanded="false">
                            <i class="flaticon-144-layout"></i>
                            <span class="nav-text">Dashboard</span>
                        </a>
                    </li>
                    <li <?php echo ($seg1 == 'orders') ? 'class="mm-active"' : ''; ?>><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
                            <i class="flaticon-127-like"></i>
                            <span class="nav-text">Orders</span>
                        </a>
                        <ul aria-expanded="false" <?php echo ($seg1 == 'orders') ? 'class="mm-collapse mm-show"' : ''; ?>>
                            <?php
                            if (in_array(501, $accessible_websites)) {
                                ?>
                                    <li <?php echo ($seg1 == 'orders' && $seg2 == 'baraha') ? 'class="mm-active"' : ''; ?>><a class="has-arrow" href="javascript:void()" aria-expanded="false">BARAHA</a>
                                        <ul aria-expanded="false" <?php echo ($seg1 == 'orders' && $seg2 == 'baraha') ? 'class="mm-collapse mm-show"' : ''; ?>>
                                            <!-- <li <?php echo ($seg1 == 'orders' && $seg2 == 'baraha' && $seg3 == 'freepcr') ? 'class="mm-active"' : ''; ?>><a href="<?php echo base_url(); ?>orders/baraha/freepcr" <?php echo ($seg1 == 'orders' && $seg2 == 'baraha' && $seg3 == 'freepcr') ? 'class="mm-active"' : ''; ?>>FREE PCR Orders</a></li> -->

                                            <li <?php echo ($seg1 == 'orders' && $seg2 == 'baraha' && $seg3 == 'vaccine') ? 'class="mm-active"' : ''; ?>><a href="<?php echo base_url(); ?>orders/baraha/vaccine" <?php echo ($seg1 == 'orders' && $seg2 == 'baraha' && $seg3 == 'vaccine') ? 'class="mm-active"' : ''; ?>>FREE Vaccine Orders</a></li>


                                            <li <?php echo ($seg1 == 'orders' && $seg2 == 'baraha' && $seg3 == 'index') ? 'class="mm-active"' : ''; ?>><a href="<?php echo base_url(); ?>orders/baraha/index" <?php echo ($seg1 == 'orders' && $seg2 == 'baraha' && $seg3 == 'index') ? 'class="mm-active"' : ''; ?>>Manage Orders</a></li>

                                            <li <?php echo ($seg1 == 'orders' && $seg2 == 'baraha' && $seg3 == 'pcr_index') ? 'class="mm-active"' : ''; ?>><a href="<?php echo base_url(); ?>orders/baraha/pcr_index" <?php echo ($seg1 == 'orders' && $seg2 == 'baraha' && $seg3 == 'pcr_index') ? 'class="mm-active"' : ''; ?>>Manage PCR Orders</a></li>

                                            <?php

                                            if ($this->auth_user_role == 1 || $this->auth_user_role == 6) {
                                                ?>

                                                    <li <?php echo ($seg1 == 'orders' && $seg2 == 'baraha' && $seg3 == 'assign') ? 'class="mm-active"' : ''; ?>><a href="<?php echo base_url(); ?>orders/baraha/assign" <?php echo ($seg1 == 'orders' && $seg2 == 'baraha' && $seg3 == 'assign') ? 'class="mm-active"' : ''; ?>>Assign Orders</a></li>

                                                    <li <?php echo ($seg1 == 'orders' && $seg2 == 'baraha' && $seg3 == 'pcr_assign') ? 'class="mm-active"' : ''; ?>><a href="<?php echo base_url(); ?>orders/baraha/pcr_assign" <?php echo ($seg1 == 'orders' && $seg2 == 'baraha' && $seg3 == 'pcr_assign') ? 'class="mm-active"' : ''; ?>>Assign PCR Orders</a></li>
                                                <?php
                                            }
                                            ?>
                                        </ul>
                                    </li>
                                <?php
                            }

                            if (in_array(502, $accessible_websites)) {
                                ?>
                                    <li <?php echo ($seg1 == 'orders' && $seg2 == 'ontimegov') ? 'class="mm-active"' : ''; ?>><a class="has-arrow" href="javascript:void()" aria-expanded="false">ONTIME GOV</a>
                                        <ul aria-expanded="false" <?php echo ($seg1 == 'orders' && $seg2 == 'ontimegov') ? 'class="mm-collapse mm-show"' : ''; ?>>

                                            <li <?php echo ($seg1 == 'orders' && $seg2 == 'ontimegov' && $seg3 == 'index') ? 'class="mm-active"' : ''; ?>><a href="<?php echo base_url(); ?>orders/ontimegov/index" <?php echo ($seg1 == 'orders' && $seg2 == 'ontimegov' && $seg3 == 'index') ? 'class="mm-active"' : ''; ?>>Manage Orders</a></li>

                                            <?php
                                            if ($this->auth_user_role == 1 || $this->auth_user_role == 6) {
                                                ?>
                                                    <li <?php echo ($seg1 == 'orders' && $seg2 == 'ontimegov' && $seg3 == 'assign') ? 'class="mm-active"' : ''; ?>><a href="<?php echo base_url(); ?>orders/ontimegov/assign" <?php echo ($seg1 == 'orders' && $seg2 == 'ontimegov' && $seg3 == 'assign') ? 'class="mm-active"' : ''; ?>>Assign Orders</a></li>
                                                <?php
                                            }
                                            ?>

                                        </ul>
                                    </li>

                                <?php
                            }

                            if (in_array(507, $accessible_websites)) {
                                ?>
                                    <li <?php echo ($seg1 == 'orders' && $seg2 == 'aladheed') ? 'class="mm-active"' : ''; ?>><a class="has-arrow" href="javascript:void()" aria-expanded="false">AL ADHEED</a>
                                        <ul aria-expanded="false" <?php echo ($seg1 == 'orders' && $seg2 == 'aladheed') ? 'class="mm-collapse mm-show"' : ''; ?>>

                                            <li <?php echo ($seg1 == 'orders' && $seg2 == 'aladheed' && $seg3 == 'index') ? 'class="mm-active"' : ''; ?>><a href="<?php echo base_url(); ?>orders/aladheed/index" <?php echo ($seg1 == 'orders' && $seg2 == 'aladheed' && $seg3 == 'index') ? 'class="mm-active"' : ''; ?>>Manage Orders</a></li>

                                        </ul>
                                    </li>

                                <?php
                            }
                            if (in_array(508, $accessible_websites)) {
                                ?>
                                        <li <?php echo ($seg1 == 'orders' && $seg2 == 'smartejari') ? 'class="mm-active"' : ''; ?>><a class="has-arrow" href="javascript:void()" aria-expanded="false">Smart Ejari</a>
                                            <ul aria-expanded="false" <?php echo ($seg1 == 'orders' && $seg2 == 'smartejari') ? 'class="mm-collapse mm-show"' : ''; ?>>
    
                                                <li <?php echo ($seg1 == 'orders' && $seg2 == 'smartejari' && $seg3 == 'index') ? 'class="mm-active"' : ''; ?>><a href="<?php echo base_url(); ?>orders/smartejari/index" <?php echo ($seg1 == 'orders' && $seg2 == 'smartejari' && $seg3 == 'index') ? 'class="mm-active"' : ''; ?>>Manage Orders</a></li>
    
                                            </ul>
                                        </li>
    
                                    <?php
                            }
                            ?>

                        </ul>
                    </li>


                    <li <?php echo ($seg1 == 'enquiries') ? 'class="mm-active"' : ''; ?>><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
                            <i class="flaticon-159-email"></i>
                            <span class="nav-text">Enquiries</span>
                        </a>
                    
                        <ul aria-expanded="false" <?php echo ($seg1 == 'enquiries') ? 'class="mm-collapse mm-show"' : ''; ?>>
                            
                    
                                <!-- <li <?php echo ($seg1 == 'enquiries' && $seg3 == 'index') ? 'class="mm-active"' : ''; ?>><a <?php echo ($seg1 == 'enquiries' && $seg3 == 'index') ? 'class="mm-active has-arrow"' : 'class="has-arrow"'; ?>  href="/enquiries/all/index" aria-expanded="false">Manage Enquiries</a></li>
                                <li <?php echo ($seg1 == 'enquiries' && $seg2 == 'all') ? 'class="mm-active"' : ''; ?>><a class="has-arrow" href="javascript:void()" aria-expanded="false">Assign Enquiries</a></li> -->


                            <?php
                            if (in_array(501, $accessible_websites)) {
                                ?>
                                    <li <?php echo ($seg1 == 'enquiries' && $seg2 == 'baraha') ? 'class="mm-active"' : ''; ?>><a class="has-arrow" href="javascript:void()" aria-expanded="false">BARAHA</a>
                                        <ul aria-expanded="false" <?php echo ($seg1 == 'enquiries' && $seg2 == 'baraha') ? 'class="mm-collapse mm-show"' : ''; ?>>

                                            <li <?php echo ($seg1 == 'enquiries' && $seg2 == 'baraha' && $seg3 == 'index') ? 'class="mm-active"' : ''; ?>><a href="<?php echo base_url(); ?>enquiries/baraha/index" <?php echo ($seg1 == 'enquiries' && $seg2 == 'baraha' && $seg3 == 'index') ? 'class="mm-active"' : ''; ?>>Manage Enquiries</a></li>
                                            <?php

                                            if ($this->auth_user_role == 1 || $this->auth_user_role == 6) {
                                                ?>
                                                    <li <?php echo ($seg1 == 'enquiries' && $seg2 == 'baraha' && $seg3 == 'assign') ? 'class="mm-active"' : ''; ?>><a href="<?php echo base_url(); ?>enquiries/baraha/assign" <?php echo ($seg1 == 'enquiries' && $seg2 == 'baraha' && $seg3 == 'assign') ? 'class="mm-active"' : ''; ?>>Assign Enquiries</a></li>
                                                <?php
                                            }
                                            ?>
                                        </ul>
                                    </li>
                                <?php
                            }

                            if (in_array(502, $accessible_websites)) {
                                ?>
                                    <li <?php echo ($seg1 == 'enquiries' && $seg2 == 'ontimegov') ? 'class="mm-active"' : ''; ?>><a class="has-arrow" href="javascript:void()" aria-expanded="false">ONTIMEGOV</a>
                                        <ul aria-expanded="false" <?php echo ($seg1 == 'enquiries' && $seg2 == 'ontimegov') ? 'class="mm-collapse mm-show"' : ''; ?>>

                                            <li <?php echo ($seg1 == 'enquiries' && $seg2 == 'ontimegov' && $seg3 == 'index') ? 'class="mm-active"' : ''; ?>><a href="<?php echo base_url(); ?>enquiries/ontimegov/index" <?php echo ($seg1 == 'enquiries' && $seg2 == 'ontimegov' && $seg3 == 'index') ? 'class="mm-active"' : ''; ?>>Manage Enquiries</a></li>
                                            <?php

                                            if ($this->auth_user_role == 1 || $this->auth_user_role == 6) {
                                                ?>
                                                    <li <?php echo ($seg1 == 'enquiries' && $seg2 == 'ontimegov' && $seg3 == 'assign') ? 'class="mm-active"' : ''; ?>><a href="<?php echo base_url(); ?>enquiries/ontimegov/assign" <?php echo ($seg1 == 'enquiries' && $seg2 == 'ontimegov' && $seg3 == 'assign') ? 'class="mm-active"' : ''; ?>>Assign Enquiries</a></li>
                                                <?php
                                            }
                                            ?>
                                        </ul>
                                    </li>
                                <?php
                            }

                            if (in_array(503, $accessible_websites)) {
                                ?>
                                    <li <?php echo ($seg1 == 'enquiries' && $seg2 == 'ontimevisa') ? 'class="mm-active"' : ''; ?>><a class="has-arrow" href="javascript:void()" aria-expanded="false">ONTIME VISA</a>
                                        <ul aria-expanded="false" <?php echo ($seg1 == 'enquiries' && $seg2 == 'ontimevisa') ? 'class="mm-collapse mm-show"' : ''; ?>>

                                            <li <?php echo ($seg1 == 'enquiries' && $seg2 == 'ontimevisa' && $seg3 == 'index') ? 'class="mm-active"' : ''; ?>><a href="<?php echo base_url(); ?>enquiries/ontimevisa/index" <?php echo ($seg1 == 'enquiries' && $seg2 == 'ontimevisa' && $seg3 == 'index') ? 'class="mm-active"' : ''; ?>>Manage Enquiries</a></li>
                                            <?php

                                            if ($this->auth_user_role == 1 || $this->auth_user_role == 6) {
                                                ?>
                                                    <li <?php echo ($seg1 == 'enquiries' && $seg2 == 'ontimevisa' && $seg3 == 'assign') ? 'class="mm-active"' : ''; ?>><a href="<?php echo base_url(); ?>enquiries/ontimevisa/assign" <?php echo ($seg1 == 'enquiries' && $seg2 == 'ontimevisa' && $seg3 == 'assign') ? 'class="mm-active"' : ''; ?>>Assign Enquiries</a></li>
                                                <?php
                                            }
                                            ?>
                                        </ul>
                                    </li>
                                <?php
                            }

                            if (in_array(504, $accessible_websites)) {
                                ?>
                                    <li <?php echo ($seg1 == 'enquiries' && $seg2 == 'business') ? 'class="mm-active"' : ''; ?>><a class="has-arrow" href="javascript:void()" aria-expanded="false">BUSINESS</a>
                                        <ul aria-expanded="false" <?php echo ($seg1 == 'enquiries' && $seg2 == 'business') ? 'class="mm-collapse mm-show"' : ''; ?>>

                                            <li <?php echo ($seg1 == 'enquiries' && $seg2 == 'business' && $seg3 == 'index') ? 'class="mm-active"' : ''; ?>><a href="<?php echo base_url(); ?>enquiries/business/index" <?php echo ($seg1 == 'enquiries' && $seg2 == 'business' && $seg3 == 'index') ? 'class="mm-active"' : ''; ?>>Manage Enquiries</a></li>
                                            <?php

                                            if ($this->auth_user_role == 1 || $this->auth_user_role == 6) {
                                                ?>
                                                    <li <?php echo ($seg1 == 'enquiries' && $seg2 == 'business' && $seg3 == 'assign') ? 'class="mm-active"' : ''; ?>><a href="<?php echo base_url(); ?>enquiries/business/assign" <?php echo ($seg1 == 'enquiries' && $seg2 == 'business' && $seg3 == 'assign') ? 'class="mm-active"' : ''; ?>>Assign Enquiries</a></li>
                                                <?php
                                            }
                                            ?>
                                        </ul>
                                    </li>
                                <?php
                            }

                            if (in_array(505, $accessible_websites)) {
                                ?>
                                    <li <?php echo ($seg1 == 'enquiries' && $seg2 == 'trustee') ? 'class="mm-active"' : ''; ?>><a class="has-arrow" href="javascript:void()" aria-expanded="false">TRUSTEE</a>
                                        <ul aria-expanded="false" <?php echo ($seg1 == 'enquiries' && $seg2 == 'trustee') ? 'class="mm-collapse mm-show"' : ''; ?>>

                                            <li <?php echo ($seg1 == 'enquiries' && $seg2 == 'trustee' && $seg3 == 'index') ? 'class="mm-active"' : ''; ?>><a href="<?php echo base_url(); ?>enquiries/trustee/index" <?php echo ($seg1 == 'enquiries' && $seg2 == 'trustee' && $seg3 == 'index') ? 'class="mm-active"' : ''; ?>>Manage Enquiries</a></li>
                                            <?php

                                            if ($this->auth_user_role == 1 || $this->auth_user_role == 6) {
                                                ?>
                                                    <li <?php echo ($seg1 == 'enquiries' && $seg2 == 'trustee' && $seg3 == 'assign') ? 'class="mm-active"' : ''; ?>><a href="<?php echo base_url(); ?>enquiries/trustee/assign" <?php echo ($seg1 == 'enquiries' && $seg2 == 'trustee' && $seg3 == 'assign') ? 'class="mm-active"' : ''; ?>>Assign Enquiries</a></li>
                                                <?php
                                            }
                                            ?>
                                        </ul>
                                    </li>
                                <?php
                            }

                            if (in_array(506, $accessible_websites)) {
                                ?>
                                    <li <?php echo ($seg1 == 'enquiries' && $seg2 == 'healthcare') ? 'class="mm-active"' : ''; ?>><a class="has-arrow" href="javascript:void()" aria-expanded="false">HEALTHCARE</a>
                                        <ul aria-expanded="false" <?php echo ($seg1 == 'enquiries' && $seg2 == 'healthcare') ? 'class="mm-collapse mm-show"' : ''; ?>>

                                            <li <?php echo ($seg1 == 'enquiries' && $seg2 == 'healthcare' && $seg3 == 'index') ? 'class="mm-active"' : ''; ?>><a href="<?php echo base_url(); ?>enquiries/healthcare/index" <?php echo ($seg1 == 'enquiries' && $seg2 == 'healthcare' && $seg3 == 'index') ? 'class="mm-active"' : ''; ?>>Manage Enquiries</a></li>
                                            <?php

                                            if ($this->auth_user_role == 1 || $this->auth_user_role == 6) {
                                                ?>
                                                    <li <?php echo ($seg1 == 'enquiries' && $seg2 == 'healthcare' && $seg3 == 'assign') ? 'class="mm-active"' : ''; ?>><a href="<?php echo base_url(); ?>enquiries/healthcare/assign" <?php echo ($seg1 == 'enquiries' && $seg2 == 'healthcare' && $seg3 == 'assign') ? 'class="mm-active"' : ''; ?>>Assign Enquiries</a></li>
                                                <?php
                                            }
                                            ?>
                                        </ul>
                                    </li>
                                <?php
                            }

                            if (in_array(507, $accessible_websites)) {
                                ?>
                                    <li <?php echo ($seg1 == 'enquiries' && $seg2 == 'aladheed') ? 'class="mm-active"' : ''; ?>><a class="has-arrow" href="javascript:void()" aria-expanded="false">Al Adheed</a>
                                        <ul aria-expanded="false" <?php echo ($seg1 == 'enquiries' && $seg2 == 'aladheed') ? 'class="mm-collapse mm-show"' : ''; ?>>

                                            <li <?php echo ($seg1 == 'enquiries' && $seg2 == 'aladheed' && $seg3 == 'index') ? 'class="mm-active"' : ''; ?>><a href="<?php echo base_url(); ?>enquiries/aladheed/index" <?php echo ($seg1 == 'enquiries' && $seg2 == 'aladheed' && $seg3 == 'index') ? 'class="mm-active"' : ''; ?>>Manage Enquiries</a></li>
                                            <?php

                                            if ($this->auth_user_role == 1 || $this->auth_user_role == 6) {
                                                ?>
                                                    <li <?php echo ($seg1 == 'enquiries' && $seg2 == 'aladheed' && $seg3 == 'assign') ? 'class="mm-active"' : ''; ?>><a href="<?php echo base_url(); ?>enquiries/aladheed/assign" <?php echo ($seg1 == 'enquiries' && $seg2 == 'aladheed' && $seg3 == 'assign') ? 'class="mm-active"' : ''; ?>>Assign Enquiries</a></li>
                                                <?php
                                            }
                                            ?>
                                        </ul>
                                    </li>
                                <?php
                            }
                            ?>
                        </ul>
                    </li>


                    <li <?php echo ($seg1 == 'meetings') ? 'class="mm-active"' : ''; ?>><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
                            <i class="flaticon-163-calendar"></i>
                            <span class="nav-text">Meetings</span>
                        </a>
                        <ul aria-expanded="false" <?php echo ($seg1 == 'meetings') ? 'class="mm-collapse mm-show"' : ''; ?>>
                            <?php
                            if (in_array(501, $accessible_websites)) {
                                ?>
                                    <li <?php echo ($seg1 == 'meetings' && $seg2 == 'baraha') ? 'class="mm-active"' : ''; ?>><a class="has-arrow" href="javascript:void()" aria-expanded="false">BARAHA</a>
                                        <ul aria-expanded="false" <?php echo ($seg1 == 'meetings' && $seg2 == 'baraha') ? 'class="mm-collapse mm-show"' : ''; ?>>

                                            <li <?php echo ($seg1 == 'meetings' && $seg2 == 'baraha' && $seg3 == 'index') ? 'class="mm-active"' : ''; ?>><a href="<?php echo base_url(); ?>meetings/baraha/index" <?php echo ($seg1 == 'meetings' && $seg2 == 'baraha' && $seg3 == 'index') ? 'class="mm-active"' : ''; ?>>Manage Meetings</a></li>
                                            <?php

                                            if ($this->auth_user_role == 1 || $this->auth_user_role == 6) {
                                                ?>
                                                    <li <?php echo ($seg1 == 'meetings' && $seg2 == 'baraha' && $seg3 == 'assign') ? 'class="mm-active"' : ''; ?>><a href="<?php echo base_url(); ?>meetings/baraha/assign" <?php echo ($seg1 == 'meetings' && $seg2 == 'baraha' && $seg3 == 'assign') ? 'class="mm-active"' : ''; ?>>Assign Meetings</a></li>
                                                <?php
                                            }
                                            ?>
                                        </ul>
                                    </li>
                                <?php
                            }

                            if (in_array(502, $accessible_websites)) {
                                ?>
                                    <li <?php echo ($seg1 == 'meetings' && $seg2 == 'ontimegov') ? 'class="mm-active"' : ''; ?>><a class="has-arrow" href="javascript:void()" aria-expanded="false">ONTIMEGOV</a>
                                        <ul aria-expanded="false" <?php echo ($seg1 == 'meetings' && $seg2 == 'ontimegov') ? 'class="mm-collapse mm-show"' : ''; ?>>

                                            <li <?php echo ($seg1 == 'meetings' && $seg2 == 'ontimegov' && $seg3 == 'index') ? 'class="mm-active"' : ''; ?>><a href="<?php echo base_url(); ?>meetings/ontimegov/index" <?php echo ($seg1 == 'meetings' && $seg2 == 'ontimegov' && $seg3 == 'index') ? 'class="mm-active"' : ''; ?>>Manage Meetings</a></li>
                                            <?php

                                            if ($this->auth_user_role == 1 || $this->auth_user_role == 6) {
                                                ?>
                                                    <li <?php echo ($seg1 == 'meetings' && $seg2 == 'ontimegov' && $seg3 == 'assign') ? 'class="mm-active"' : ''; ?>><a href="<?php echo base_url(); ?>meetings/ontimegov/assign" <?php echo ($seg1 == 'meetings' && $seg2 == 'ontimegov' && $seg3 == 'assign') ? 'class="mm-active"' : ''; ?>>Assign Meetings</a></li>
                                                <?php
                                            }
                                            ?>
                                        </ul>
                                    </li>
                                <?php
                            }

                            if (in_array(503, $accessible_websites)) {
                                ?>
                                    <li <?php echo ($seg1 == 'meetings' && $seg2 == 'ontimevisa') ? 'class="mm-active"' : ''; ?>><a class="has-arrow" href="javascript:void()" aria-expanded="false">ONTIME VISA</a>
                                        <ul aria-expanded="false" <?php echo ($seg1 == 'meetings' && $seg2 == 'baraha') ? 'class="mm-collapse mm-show"' : ''; ?>>

                                            <li <?php echo ($seg1 == 'meetings' && $seg2 == 'ontimevisa' && $seg3 == 'index') ? 'class="mm-active"' : ''; ?>><a href="<?php echo base_url(); ?>meetings/ontimevisa/index" <?php echo ($seg1 == 'meetings' && $seg2 == 'ontimevisa' && $seg3 == 'index') ? 'class="mm-active"' : ''; ?>>Manage Meetings</a></li>
                                            <?php

                                            if ($this->auth_user_role == 1 || $this->auth_user_role == 6) {
                                                ?>
                                                    <li <?php echo ($seg1 == 'meetings' && $seg2 == 'ontimevisa' && $seg3 == 'assign') ? 'class="mm-active"' : ''; ?>><a href="<?php echo base_url(); ?>meetings/ontimevisa/assign" <?php echo ($seg1 == 'meetings' && $seg2 == 'ontimevisa' && $seg3 == 'assign') ? 'class="mm-active"' : ''; ?>>Assign Meetings</a></li>
                                                <?php
                                            }
                                            ?>
                                        </ul>
                                    </li>
                                <?php
                            }

                            if (in_array(504, $accessible_websites)) {
                                ?>
                                    <li <?php echo ($seg1 == 'meetings' && $seg2 == 'business') ? 'class="mm-active"' : ''; ?>><a class="has-arrow" href="javascript:void()" aria-expanded="false">BUSINESS</a>
                                        <ul aria-expanded="false" <?php echo ($seg1 == 'meetings' && $seg2 == 'baraha') ? 'class="mm-collapse mm-show"' : ''; ?>>

                                            <li <?php echo ($seg1 == 'meetings' && $seg2 == 'business' && $seg3 == 'index') ? 'class="mm-active"' : ''; ?>><a href="<?php echo base_url(); ?>meetings/business/index" <?php echo ($seg1 == 'meetings' && $seg2 == 'business' && $seg3 == 'index') ? 'class="mm-active"' : ''; ?>>Manage Meetings</a></li>
                                            <?php

                                            if ($this->auth_user_role == 1 || $this->auth_user_role == 6) {
                                                ?>
                                                    <li <?php echo ($seg1 == 'meetings' && $seg2 == 'business' && $seg3 == 'assign') ? 'class="mm-active"' : ''; ?>><a href="<?php echo base_url(); ?>meetings/business/assign" <?php echo ($seg1 == 'meetings' && $seg2 == 'business' && $seg3 == 'assign') ? 'class="mm-active"' : ''; ?>>Assign Meetings</a></li>
                                                <?php
                                            }
                                            ?>
                                        </ul>
                                    </li>
                                <?php
                            }

                            if (in_array(505, $accessible_websites)) {
                                ?>
                                    <li <?php echo ($seg1 == 'meetings' && $seg2 == 'trustee') ? 'class="mm-active"' : ''; ?>><a class="has-arrow" href="javascript:void()" aria-expanded="false">TRUSTEE</a>
                                        <ul aria-expanded="false" <?php echo ($seg1 == 'meetings' && $seg2 == 'baraha') ? 'class="mm-collapse mm-show"' : ''; ?>>

                                            <li <?php echo ($seg1 == 'meetings' && $seg2 == 'trustee' && $seg3 == 'index') ? 'class="mm-active"' : ''; ?>><a href="<?php echo base_url(); ?>meetings/trustee/index" <?php echo ($seg1 == 'meetings' && $seg2 == 'trustee' && $seg3 == 'index') ? 'class="mm-active"' : ''; ?>>Manage Meetings</a></li>
                                            <?php

                                            if ($this->auth_user_role == 1 || $this->auth_user_role == 6) {
                                                ?>
                                                    <li <?php echo ($seg1 == 'meetings' && $seg2 == 'trustee' && $seg3 == 'assign') ? 'class="mm-active"' : ''; ?>><a href="<?php echo base_url(); ?>meetings/trustee/assign" <?php echo ($seg1 == 'meetings' && $seg2 == 'trustee' && $seg3 == 'assign') ? 'class="mm-active"' : ''; ?>>Assign Meetings</a></li>
                                                <?php
                                            }
                                            ?>
                                        </ul>
                                    </li>
                                <?php
                            }

                            if (in_array(506, $accessible_websites)) {
                                ?>
                                    <li <?php echo ($seg1 == 'meetings' && $seg2 == 'healthcare') ? 'class="mm-active"' : ''; ?>><a class="has-arrow" href="javascript:void()" aria-expanded="false">HEALTHCARE</a>
                                        <ul aria-expanded="false" <?php echo ($seg1 == 'meetings' && $seg2 == 'baraha') ? 'class="mm-collapse mm-show"' : ''; ?>>

                                            <li <?php echo ($seg1 == 'meetings' && $seg2 == 'healthcare' && $seg3 == 'index') ? 'class="mm-active"' : ''; ?>><a href="<?php echo base_url(); ?>meetings/healthcare/index" <?php echo ($seg1 == 'meetings' && $seg2 == 'healthcare' && $seg3 == 'index') ? 'class="mm-active"' : ''; ?>>Manage Meetings</a></li>
                                            <?php

                                            if ($this->auth_user_role == 1 || $this->auth_user_role == 6) {
                                                ?>
                                                    <li <?php echo ($seg1 == 'meetings' && $seg2 == 'healthcare' && $seg3 == 'assign') ? 'class="mm-active"' : ''; ?>><a href="<?php echo base_url(); ?>meetings/healthcare/assign" <?php echo ($seg1 == 'meetings' && $seg2 == 'healthcare' && $seg3 == 'assign') ? 'class="mm-active"' : ''; ?>>Assign Meetings</a></li>
                                                <?php
                                            }
                                            ?>
                                        </ul>
                                    </li>
                                <?php
                            }

                            if (in_array(507, $accessible_websites)) {
                                ?>
                                    <li <?php echo ($seg1 == 'meetings' && $seg2 == 'aladheed') ? 'class="mm-active"' : ''; ?>><a class="has-arrow" href="javascript:void()" aria-expanded="false">Al Adheed</a>
                                        <ul aria-expanded="false" <?php echo ($seg1 == 'meetings' && $seg2 == 'aladheed') ? 'class="mm-collapse mm-show"' : ''; ?>>

                                            <li <?php echo ($seg1 == 'meetings' && $seg2 == 'aladheed' && $seg3 == 'index') ? 'class="mm-active"' : ''; ?>><a href="<?php echo base_url(); ?>meetings/aladheed/index" <?php echo ($seg1 == 'meetings' && $seg2 == 'aladheed' && $seg3 == 'index') ? 'class="mm-active"' : ''; ?>>Manage Meetings</a></li>
                                            <?php

                                            if ($this->auth_user_role == 1 || $this->auth_user_role == 6) {
                                                ?>
                                                    <li <?php echo ($seg1 == 'meetings' && $seg2 == 'aladheed' && $seg3 == 'assign') ? 'class="mm-active"' : ''; ?>><a href="<?php echo base_url(); ?>meetings/aladheed/assign" <?php echo ($seg1 == 'meetings' && $seg2 == 'aladheed' && $seg3 == 'assign') ? 'class="mm-active"' : ''; ?>>Assign Meetings</a></li>
                                                <?php
                                            }
                                            ?>
                                        </ul>
                                    </li>
                                <?php
                            }
                            ?>
                        </ul>
                    </li>

                    <li <?php echo ($seg1 == 'complaints') ? 'class="mm-active"' : ''; ?>><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
                            <i class="flaticon-161-alarm"></i>
                            <span class="nav-text">Complaints</span>
                        </a>
                        <ul aria-expanded="false" <?php echo ($seg1 == 'complaints') ? 'class="mm-collapse mm-show"' : ''; ?>>
                            <?php
                            if (in_array(501, $accessible_websites)) {
                                ?>
                                    <li <?php echo ($seg1 == 'complaints' && $seg2 == 'baraha') ? 'class="mm-active"' : ''; ?>><a class="has-arrow" href="javascript:void()" aria-expanded="false">BARAHA</a>
                                        <ul aria-expanded="false" <?php echo ($seg1 == 'complaints' && $seg2 == 'baraha') ? 'class="mm-collapse mm-show"' : ''; ?>>

                                            <li <?php echo ($seg1 == 'complaints' && $seg2 == 'baraha' && $seg3 == 'index') ? 'class="mm-active"' : ''; ?>><a href="<?php echo base_url(); ?>complaints/baraha" <?php echo ($seg1 == 'complaints' && $seg2 == 'baraha' && $seg3 == 'index') ? 'class="mm-active"' : ''; ?>>Manage Complaints</a></li>
                                            <?php

                                            if ($this->auth_user_role == 1 || $this->auth_user_role == 6) {
                                                ?>
                                                    <li <?php echo ($seg1 == 'complaints' && $seg2 == 'baraha' && $seg3 == 'assign') ? 'class="mm-active"' : ''; ?>><a href="<?php echo base_url(); ?>complaints/baraha/assign" <?php echo ($seg1 == 'complaints' && $seg2 == 'baraha' && $seg3 == 'assign') ? 'class="mm-active"' : ''; ?>>Assign Complaints</a></li>
                                                <?php
                                            }
                                            ?>
                                        </ul>
                                    </li>
                                <?php
                            }

                            if (in_array(502, $accessible_websites)) {
                                ?>
                                    <li <?php echo ($seg1 == 'complaints' && $seg2 == 'ontimegov') ? 'class="mm-active"' : ''; ?>><a class="has-arrow" href="javascript:void()" aria-expanded="false">ONTIMEGOV</a>
                                        <ul aria-expanded="false" <?php echo ($seg1 == 'complaints' && $seg2 == 'ontimegov') ? 'class="mm-collapse mm-show"' : ''; ?>>

                                            <li <?php echo ($seg1 == 'complaints' && $seg2 == 'ontimegov' && $seg3 == 'index') ? 'class="mm-active"' : ''; ?>><a href="<?php echo base_url(); ?>complaints/ontimegov" <?php echo ($seg1 == 'complaints' && $seg2 == 'ontimegov' && $seg3 == 'index') ? 'class="mm-active"' : ''; ?>>Manage Complaints</a></li>
                                            <?php

                                            if ($this->auth_user_role == 1 || $this->auth_user_role == 6) {
                                                ?>
                                                    <li <?php echo ($seg1 == 'complaints' && $seg2 == 'ontimegov' && $seg3 == 'assign') ? 'class="mm-active"' : ''; ?>><a href="<?php echo base_url(); ?>complaints/ontimegov/assign" <?php echo ($seg1 == 'complaints' && $seg2 == 'ontimegov' && $seg3 == 'assign') ? 'class="mm-active"' : ''; ?>>Assign Complaints</a></li>
                                                <?php
                                            }
                                            ?>
                                        </ul>
                                    </li>
                                <?php
                            }

                            if (in_array(503, $accessible_websites)) {
                                ?>
                                    <li <?php echo ($seg1 == 'complaints' && $seg2 == 'ontimevisa') ? 'class="mm-active"' : ''; ?>><a class="has-arrow" href="javascript:void()" aria-expanded="false">ONTIME VISA</a>
                                        <ul aria-expanded="false" <?php echo ($seg1 == 'complaints' && $seg2 == 'ontimevisa') ? 'class="mm-collapse mm-show"' : ''; ?>>

                                            <li <?php echo ($seg1 == 'complaints' && $seg2 == 'ontimevisa' && $seg3 == 'index') ? 'class="mm-active"' : ''; ?>><a href="<?php echo base_url(); ?>complaints/ontimevisa" <?php echo ($seg1 == 'complaints' && $seg2 == 'ontimevisa' && $seg3 == 'index') ? 'class="mm-active"' : ''; ?>>Manage Complaints</a></li>
                                            <?php

                                            if ($this->auth_user_role == 1 || $this->auth_user_role == 6) {
                                                ?>
                                                    <li <?php echo ($seg1 == 'complaints' && $seg2 == 'baraha' && $seg3 == 'assign') ? 'class="mm-active"' : ''; ?>><a href="<?php echo base_url(); ?>complaints/ontimevisa/assign" <?php echo ($seg1 == 'complaints' && $seg2 == 'ontimevisa' && $seg3 == 'assign') ? 'class="mm-active"' : ''; ?>>Assign Complaints</a></li>
                                                <?php
                                            }
                                            ?>
                                        </ul>
                                    </li>
                                <?php
                            }

                            if (in_array(504, $accessible_websites)) {
                                ?>
                                    <li <?php echo ($seg1 == 'complaints' && $seg2 == 'business') ? 'class="mm-active"' : ''; ?>><a class="has-arrow" href="javascript:void()" aria-expanded="false">BUSINESS</a>
                                        <ul aria-expanded="false" <?php echo ($seg1 == 'complaints' && $seg2 == 'business') ? 'class="mm-collapse mm-show"' : ''; ?>>

                                            <li <?php echo ($seg1 == 'complaints' && $seg2 == 'ontimevisa' && $seg3 == 'index') ? 'class="mm-active"' : ''; ?>><a href="<?php echo base_url(); ?>complaints/business" <?php echo ($seg1 == 'complaints' && $seg2 == 'business' && $seg3 == 'index') ? 'class="mm-active"' : ''; ?>>Manage Complaints</a></li>
                                            <?php

                                            if ($this->auth_user_role == 1 || $this->auth_user_role == 6) {
                                                ?>
                                                    <li <?php echo ($seg1 == 'complaints' && $seg2 == 'baraha' && $seg3 == 'assign') ? 'class="mm-active"' : ''; ?>><a href="<?php echo base_url(); ?>complaints/business/assign" <?php echo ($seg1 == 'complaints' && $seg2 == 'business' && $seg3 == 'assign') ? 'class="mm-active"' : ''; ?>>Assign Complaints</a></li>
                                                <?php
                                            }
                                            ?>
                                        </ul>
                                    </li>
                                <?php
                            }

                            if (in_array(505, $accessible_websites)) {
                                ?>
                                    <li <?php echo ($seg1 == 'complaints' && $seg2 == 'trustee') ? 'class="mm-active"' : ''; ?>><a class="has-arrow" href="javascript:void()" aria-expanded="false">TRUSTEE</a>
                                        <ul aria-expanded="false" <?php echo ($seg1 == 'complaints' && $seg2 == 'trustee') ? 'class="mm-collapse mm-show"' : ''; ?>>

                                            <li <?php echo ($seg1 == 'complaints' && $seg2 == 'ontimevisa' && $seg3 == 'index') ? 'class="mm-active"' : ''; ?>><a href="<?php echo base_url(); ?>complaints/trustee" <?php echo ($seg1 == 'complaints' && $seg2 == 'trustee' && $seg3 == 'index') ? 'class="mm-active"' : ''; ?>>Manage Complaints</a></li>
                                            <?php

                                            if ($this->auth_user_role == 1 || $this->auth_user_role == 6) {
                                                ?>
                                                    <li <?php echo ($seg1 == 'complaints' && $seg2 == 'baraha' && $seg3 == 'assign') ? 'class="mm-active"' : ''; ?>><a href="<?php echo base_url(); ?>complaints/trustee/assign" <?php echo ($seg1 == 'complaints' && $seg2 == 'trustee' && $seg3 == 'assign') ? 'class="mm-active"' : ''; ?>>Assign Complaints</a></li>
                                                <?php
                                            }
                                            ?>
                                        </ul>
                                    </li>
                                <?php
                            }

                            if (in_array(506, $accessible_websites)) {
                                ?>
                                    <li <?php echo ($seg1 == 'complaints' && $seg2 == 'healthcare') ? 'class="mm-active"' : ''; ?>><a class="has-arrow" href="javascript:void()" aria-expanded="false">HEALTHCARE</a>
                                        <ul aria-expanded="false" <?php echo ($seg1 == 'complaints' && $seg2 == 'healthcare') ? 'class="mm-collapse mm-show"' : ''; ?>>

                                            <li <?php echo ($seg1 == 'complaints' && $seg2 == 'ontimevisa' && $seg3 == 'index') ? 'class="mm-active"' : ''; ?>><a href="<?php echo base_url(); ?>complaints/healthcare" <?php echo ($seg1 == 'complaints' && $seg2 == 'healthcare' && $seg3 == 'index') ? 'class="mm-active"' : ''; ?>>Manage Complaints</a></li>
                                            <?php

                                            if ($this->auth_user_role == 1 || $this->auth_user_role == 6) {
                                                ?>
                                                    <li <?php echo ($seg1 == 'complaints' && $seg2 == 'baraha' && $seg3 == 'assign') ? 'class="mm-active"' : ''; ?>><a href="<?php echo base_url(); ?>complaints/healthcare/assign" <?php echo ($seg1 == 'complaints' && $seg2 == 'healthcare' && $seg3 == 'assign') ? 'class="mm-active"' : ''; ?>>Assign Complaints</a></li>
                                                <?php
                                            }
                                            ?>
                                        </ul>
                                    </li>
                                <?php
                            }

                            if (in_array(507, $accessible_websites)) {
                                ?>
                                    <li <?php echo ($seg1 == 'complaints' && $seg2 == 'aladheed') ? 'class="mm-active"' : ''; ?>><a class="has-arrow" href="javascript:void()" aria-expanded="false">Al Adheed</a>
                                        <ul aria-expanded="false" <?php echo ($seg1 == 'complaints' && $seg2 == 'aladheed') ? 'class="mm-collapse mm-show"' : ''; ?>>

                                            <li <?php echo ($seg1 == 'complaints' && $seg2 == 'aladheed' && $seg3 == 'index') ? 'class="mm-active"' : ''; ?>><a href="<?php echo base_url(); ?>complaints/aladheed" <?php echo ($seg1 == 'complaints' && $seg2 == 'aladheed' && $seg3 == 'index') ? 'class="mm-active"' : ''; ?>>Manage Complaints</a></li>
                                            <?php

                                            if ($this->auth_user_role == 1 || $this->auth_user_role == 6) {
                                                ?>
                                                    <li <?php echo ($seg1 == 'complaints' && $seg2 == 'aladheed' && $seg3 == 'assign') ? 'class="mm-active"' : ''; ?>><a href="<?php echo base_url(); ?>complaints/aladheed/assign" <?php echo ($seg1 == 'complaints' && $seg2 == 'aladheed' && $seg3 == 'assign') ? 'class="mm-active"' : ''; ?>>Assign Complaints</a></li>
                                                <?php
                                            }
                                            ?>
                                        </ul>
                                    </li>
                                <?php
                            }
                            ?>
                        </ul>
                    </li>

                    <li <?php echo ($seg1 == 'leads') ? 'class="mm-active"' : ''; ?>><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
                            <i class="flaticon-106-target"></i>
                            <span class="nav-text">Leads</span>
                        </a>

                        <ul aria-expanded="false" <?php echo ($seg1 == 'leads') ? 'class="mm-collapse mm-show"' : ''; ?>>
                            <?php
                            // if($this->auth_user_role==1 || $this->auth_user_role==6)
                            if ($this->auth_user_role == 1) {
                                ?>
                                    <li <?php echo ($seg1 == 'leads' && $seg2 == 'settings') ? 'class="mm-active"' : ''; ?>><a class="has-arrow" href="javascript:void()" aria-expanded="false">Settings</a>
                                        <ul aria-expanded="false" <?php echo ($seg1 == 'leads' && $seg2 == 'settings') ? 'class="mm-collapse mm-show"' : ''; ?>>

                                            <li <?php echo ($seg1 == 'leads' && $seg2 == 'settings' && $seg3 == 'packages') ? 'class="mm-active"' : ''; ?>><a href="<?php echo base_url(); ?>leads/settings/packages" <?php echo ($seg1 == 'leads' && $seg2 == 'settings' && $seg3 == 'packages') ? 'class="mm-active"' : ''; ?>>Packages</a></li>

                                            <li <?php echo ($seg1 == 'leads' && $seg2 == 'settings' && $seg3 == 'pservices') ? 'class="mm-active"' : ''; ?>><a href="<?php echo base_url(); ?>leads/settings/pservices" <?php echo ($seg1 == 'leads' && $seg2 == 'settings' && $seg3 == 'pservices') ? 'class="mm-active"' : ''; ?>>Package Services</a></li>

                                            <li <?php echo ($seg1 == 'leads' && $seg2 == 'settings' && $seg3 == 'status') ? 'class="mm-active"' : ''; ?>><a href="<?php echo base_url(); ?>leads/settings/lstatus" <?php echo ($seg1 == 'leads' && $seg2 == 'settings' && $seg3 == 'status') ? 'class="mm-active"' : ''; ?>>Status</a></li>

                                            <li <?php echo ($seg1 == 'leads' && $seg2 == 'settings' && $seg3 == 'actions') ? 'class="mm-active"' : ''; ?>><a href="<?php echo base_url(); ?>leads/settings/actions" <?php echo ($seg1 == 'leads' && $seg2 == 'settings' && $seg3 == 'actions') ? 'class="mm-active"' : ''; ?>>Actions</a></li>

                                            <li <?php echo ($seg1 == 'leads' && $seg2 == 'settings' && $seg3 == 'workflows') ? 'class="mm-active"' : ''; ?>><a href="<?php echo base_url(); ?>leads/settings/workflows" <?php echo ($seg1 == 'leads' && $seg2 == 'settings' && $seg3 == 'workflows') ? 'class="mm-active"' : ''; ?>>Workflows</a></li>

                                            <li <?php echo ($seg1 == 'leads' && $seg2 == 'settings' && $seg3 == 'wentries') ? 'class="mm-active"' : ''; ?>><a href="<?php echo base_url(); ?>leads/settings/wentries" <?php echo ($seg1 == 'leads' && $seg2 == 'settings' && $seg3 == 'wentries') ? 'class="mm-active"' : ''; ?>>Workflow Definition</a></li>

                                        </ul>
                                    </li>
                                <?php
                            }
                            ?>

                            <li <?php echo ($seg1 == 'leads' && $seg2 == 'lead' && $seg3 == 'new') ? 'class="mm-active"' : ''; ?>><a href="<?php echo base_url(); ?>leads/lead/new" aria-expanded="false">Create Lead</a></li>

                            <li <?php echo ($seg1 == 'leads' && $seg2 == 'lead' && $seg3 == 'manage') ? 'class="mm-active"' : ''; ?>><a href="<?php echo base_url(); ?>leads/lead/manage" aria-expanded="false">Manage/Accept Leads</a></li>

                            <li <?php echo ($seg1 == 'leads' && $seg2 == 'meetings' && $seg3 == 'manage') ? 'class="mm-active"' : ''; ?>><a href="<?php echo base_url(); ?>leads/meeting/manage" aria-expanded="false">Meetings</a></li>

                            <li <?php echo ($seg1 == 'leads' && $seg2 == 'templates' && $seg3 == 'manage') ? 'class="mm-active"' : ''; ?>><a href="<?php echo base_url(); ?>leads/templates/manage" aria-expanded="false">My Templates</a></li>

                        </ul>

                    </li>
                <?php
            }
            ?>
            <?php
            if ($this->auth_user_role == 1) {
                ?>
                    <li <?php echo ($seg2 != 'timeslot' && $seg2 != 'exceptiondate' && $seg2 != 'usertimeslot') ? 'class="mm-active"' : ""; ?>><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
                            <i class="flaticon-381-settings-2"></i>
                            <span class="nav-text">Masters</span>
                        </a>
                        <ul aria-expanded="false" <?php echo ($seg1 == 'admin' && $seg2 != 'timeslot' && $seg2 != 'exceptiondate' && $seg2 != 'usertimeslot') ? 'class="mm-collapse mm-show"' : ""; ?>>
                            <?php
                            if ($this->auth_user_role == 1) {
                                ?>
                                    <li <?php echo ($seg1 == 'admin' && $seg2 == 'categories') ? 'class="mm-active"' : ""; ?>><a href="<?php echo base_url(); ?>admin/categories/" <?php echo ($seg1 == 'admin' && $seg2 == 'categories') ? 'class="mm-active"' : ""; ?>>CRM Categories</a></li>

                                    <li <?php echo ($seg1 == 'admin' && $seg2 == 'services') ? 'class="mm-active"' : ""; ?>><a href="<?php echo base_url(); ?>admin/services/" <?php echo ($seg1 == 'admin' && $seg2 == 'services') ? 'class="mm-active"' : ""; ?>>CRM Services</a></li>

                                    <li <?php echo ($seg1 == 'admin' && $seg2 == 'websites') ? 'class="mm-active"' : ""; ?>><a href="<?php echo base_url(); ?>admin/websites/" <?php echo ($seg1 == 'admin' && $seg2 == 'websites') ? 'class="mm-active"' : ""; ?>>ONTIME Websites</a></li>
                                    <li <?php echo (($seg1 == 'admin' && $seg2 == 'branch')) ? 'class="mm-active"' : ""; ?>><a href="<?php echo base_url(); ?>admin/branch/" <?php echo ($seg1 == 'admin' && $seg2 == 'branch') ? 'class="mm-active"' : ""; ?>>ONTIME Branches</a></li>

                                    <li <?php echo ($seg1 == 'admin' && $seg2 == 'wcategories') ? 'class="mm-active"' : ""; ?>><a href="<?php echo base_url(); ?>admin/wcategories/" <?php echo ($seg1 == 'admin' && $seg2 == 'wcategories') ? 'class="mm-active"' : ""; ?>>Website Categories</a></li>

                                    <li <?php echo ($seg1 == 'admin' && $seg2 == 'group') ? 'class="mm-active"' : ""; ?>><a href="<?php echo base_url(); ?>admin/group/" <?php echo ($seg1 == 'admin' && $seg2 == 'group') ? 'class="mm-active"' : ""; ?>>Manage Groups</a></li>

                                    <li <?php echo ($seg1 == 'admin' && $seg2 == 'user') ? 'class="mm-active"' : ""; ?>><a href="<?php echo base_url(); ?>admin/user/" <?php echo ($seg1 == 'admin' && $seg2 == 'user') ? 'class="mm-active"' : ""; ?>>Manage Users</a></li>

                                    <li <?php echo ($seg1 == 'admin' && $seg2 == 'ucategories') ? 'class="mm-active"' : ""; ?>><a href="<?php echo base_url(); ?>admin/ucategories/" <?php echo ($seg1 == 'admin' && $seg2 == 'ucategories') ? 'class="mm-active"' : ""; ?>>CRM User Categories</a></li>

                                <?php
                            }
                            ?>
                        </ul>
                    </li>
                    <li <?php if($seg2 == "timeslot") { echo 'class="mm-active"'; } ?>><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
                            <i class="flaticon-381-settings-2"></i>
                            <span class="nav-text">TimeSlots</span>
                        </a>
                        <ul aria-expanded="false" <?php echo ($seg2 == 'timeslot' || $seg2 != 'exceptiondate' || $seg2 != 'usertimeslot') ? 'class="mm-collapse mm-show"' : ""; ?>>
                            <?php
                            if ($this->auth_user_role == 1) {
                                ?>
                                    <li <?php echo ($seg2 == 'timeslot') ? 'class="mm-active"' : ""; ?>><a href="<?php echo base_url(); ?>admin/timeslot" <?php echo ($seg2 == 'timeslot') ? 'class="mm-active"' : ""; ?>>Time Slots</a></li>
                                    <li <?php echo ($seg2 == 'exceptiondate') ? 'class="mm-active"' : ""; ?>><a href="<?php echo base_url(); ?>admin/exceptiondate" <?php echo ($seg2 == 'exceptiondate') ? 'class="mm-active"' : ""; ?>>Exception Date</a></li>
                                    <li <?php echo ($seg2 == 'usertimeslot') ? 'class="mm-active"' : ""; ?>><a href="<?php echo base_url(); ?>admin/usertimeslot" <?php echo ($seg2 == 'usertimeslot') ? 'class="mm-active"' : ""; ?>>User's Timeslot</a></li>

                                  
                                <?php
                            }
                            ?>
                        </ul>
                    </li>
                    <li <?php if($seg2 == "calendar") { echo 'class="mm-active"'; } ?>><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
                            <i class="flaticon-381-settings-2"></i>
                            <span class="nav-text">Calendar</span>
                        </a>
                        <ul aria-expanded="false" <?php echo ($seg2 == 'notifications' || $seg2 != 'preferences' || $seg2 != 'booking_notice' || $seg2 != 'cancellation_and_reschedule') ? 'class="mm-collapse mm-show"' : ""; ?>>
                            <?php
                            if ($this->auth_user_role == 1) {
                                ?>
                                    <li <?php echo (($seg1 == 'admin' && $seg2 == 'branch')) ? 'class="mm-active"' : ""; ?>><a href="<?php echo base_url(); ?>admin/calendar/workspace/" <?php echo ($seg1 == 'admin' && $seg2 == 'branch') ? 'class="mm-active"' : ""; ?>>Workspace</a></li>
                                    <li <?php echo ($seg2 == 'notifications') ? 'class="mm-active"' : ""; ?>><a href="<?php echo base_url(); ?>admin/calendar/notifications" <?php echo ($seg2 == 'notifications') ? 'class="mm-active"' : ""; ?>>Calendar Notification </a></li>
                                    <li <?php echo ($seg2 == 'preferences') ? 'class="mm-active"' : ""; ?>><a href="<?php echo base_url(); ?>admin/calendar/preferences" <?php echo ($seg2 == 'preferences') ? 'class="mm-active"' : ""; ?>>Calendar Preferences</a></li>
                                    <!-- <li <?php echo ($seg2 == 'booking_notice') ? 'class="mm-active"' : ""; ?>><a href="<?php echo base_url(); ?>admin/calendar/booking_notice" <?php echo ($seg2 == 'booking_notice') ? 'class="mm-active"' : ""; ?>>Booking Notice</a></li>
                                    <li <?php echo ($seg2 == 'cancellation_and_reschedule') ? 'class="mm-active"' : ""; ?>><a href="<?php echo base_url(); ?>admin/calendar/cancellation_and_reschedule" <?php echo ($seg2 == 'cancellation_and_reschedule') ? 'class="mm-active"' : ""; ?>>Cancellation and Reschedule</a></li> -->
                                  
                                <?php
                            }
                            ?>
                        </ul>
                    </li>
                <?php
            }
            ?>

        </ul>
    </div>
</div>
<!--**********************************
    Sidebar end
***********************************-->