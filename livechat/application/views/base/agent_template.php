<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <!-- Viewport-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no" />
    <title><?php echo ($title=='')?'Unified Portal':$title; ?></title>
    <!-- Favicon and Touch Icons-->
    <link rel="apple-touch-icon" sizes="180x180" href="../apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../favicon-16x16.png">
    <link rel="shortcut icon" href="../favicon.ico" />
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">

    <link rel="stylesheet" href="<?php echo base_url(); ?>/assets/css/placeholder-loading.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>/assets/webfonts/inter/inter.css"> 
    <link rel="stylesheet" href="<?php echo base_url(); ?>/assets/css/app.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>/assets/css/uni.css">
    

</head>

<body class="chats-tab-open">

    <!-- Main Layout Start -->
    <div class="main-layout">
         <!-- Navigation Start -->
         <div class="navigation navbar navbar-light bg-primary">
            <!-- Logo Start -->
            <a class="d-none d-xl-block rounded p-1" href="<?php echo base_url(); ?>">
                <img src="<?php echo base_url(); ?>assets/media/logo.png" />
            </a>
            <!-- Logo End -->

            <!-- Main Nav Start -->
            <?php include('menu.php'); ?>
            <!-- Main Nav End -->
        </div>
        <!-- Navigation End -->

        <!-- Sidebar Start -->
        <aside class="sidebar">
            <!-- Tab Content Start -->
            <div class="tab-content">
                

                <!-- Profile Tab Content Start -->
                <div class="tab-pane active" id="profile-content">
                    <div class="d-flex flex-column h-100">
                        <div class="hide-scrollbar">
                            <!-- Sidebar Header Start -->
                            <div class="sidebar-header sticky-top p-2 mb-3">
                                <h5 class="font-weight-semibold">Welcome Infant Joseph!</h5>
                                <p class="text-muted mb-0">Keep logged in to receive chats</p>
                            </div>
                            <!-- Sidebar Header end -->

                            <div class="p-2 mb-3">
                                <h5 class="font-weight-semibold">Waiting Customers</h5>

                                <!-- Sidebar paused conversations start -->
                                <ul class="contacts-list" id="paused_conversations" data-chat-list="">
                                    <!-- Chat Item Start -->
                                    
                                    <!-- Chat Item End -->
                                </ul>
                                <!-- Sidebar paused conversation End -->
                            </div>
                            <hr />
                            <div class="p-2 mb-3">
                                <h5 class="font-weight-semibold">Ongoing Conversations</h5>

                                <!-- Sidebar paused conversations start -->
                                <ul class="contacts-list" id="ongoing_conversations" data-chat-list=""></ul>
                                <!-- Sidebar paused conversation End -->
                            </div>
                            <hr />
                            <div class="p-2 mb-3">
                                <h5 class="font-weight-semibold">History</h5>
                                <!-- Sidebar paused conversations start -->
                                <ul class="contacts-list" id="all_conversations" data-chat-list="">
                                    <!-- Chat Item Start -->
                                   
                                    <!-- Chat Item End -->
                                </ul>
                                <!-- Sidebar paused conversation End -->
                            </div>
                             
                        </div>
                    </div>
                </div>
                <!-- Profile Tab Content End -->
            </div>
            <!-- Tab Content End -->
        </aside>
        <!-- Sidebar End -->

        <!-- Main Start -->
        <main class="main main-visible" id="main_chat">

            <!-- Chats Page Start -->
            <div class="chats">
                <!-- Chat Body Start -->
                <div class="chat-body">

                    <!-- Chat Header Start-->
                    <div class="chat-header">
                        <!-- Chat Back Button (Visible only in Small Devices) -->
                        <button class="btn btn-secondary btn-icon btn-minimal btn-sm d-xl-none" type="button" data-close="">
                            <svg class="hw-20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            <!-- <img class="injectable hw-20" src="./<?php echo base_url(); ?>/assets/media/heroicons/outline/arrow-left.svg" alt=""> -->
                        </button>

                        <!-- Chat participant's Name -->
                        <div class="media chat-name align-items-center text-truncate">
                            <!-- <div class="avatar bg-success text-light d-none d-sm-inline-block mr-3">
                                <span>
                                    
                                </span>
                            </div> -->

                            <div class="media-body align-self-center ">
                                <h6 class="text-truncate mb-0" id="customer_name">
                                    <!-- <div class="ph-item">
                                        <div class="ph-row">
                                            <div class="ph-col-4"></div>
                                        </div>
                                    </div> -->
                                </h6>
                                <small class="text-muted" id="customer_email">
                                    <!-- <div class="ph-item">
                                        <div class="ph-row">
                                            <div class="ph-col-8"></div>
                                        </div>
                                    </div> -->
                                </small>&nbsp;&nbsp;
                                <small class="badge text-white badge-primary" id="customer_mobile">
                                </small>
                            </div>
                        </div>

                        <!-- Chat Options -->
                        <ul class="nav flex-nowrap">
                            <li class="nav-item list-inline-item d-none d-sm-block mr-1 mt-1">
                                <button class="btn btn-danger btn-sm" id="endchat" data-botid="" data-customerid="" data-conversationid="" data-pgid="" onclick="javascript:end_chat(this);">End Chat</button>
                            </li>

                            <li class="nav-item list-inline-item d-none d-sm-block mr-1">
                                <a class="nav-link text-muted px-1" href="#" title="Add People">
                                    <svg class="hw-20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                    <!-- <img src="./<?php echo base_url(); ?>/assets/media/heroicons/outline/phone.svg" alt="" class="injectable hw-20"> -->
                                </a>
                            </li>
                            <li class="nav-item list-inline-item d-none d-sm-block mr-0">
                                <div class="dropdown">
                                    <a class="nav-link text-muted px-1" href="#" role="button" title="Details" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <svg class="hw-20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                                        </svg>
                                        <!-- <img src="./<?php echo base_url(); ?>/assets/media/heroicons/outline/dots-vertical.svg" alt="" class="injectable hw-20"> -->
                                    </a>

                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a class="dropdown-item align-items-center d-flex" href="#" data-chat-info-toggle="">
                                            <svg class="hw-20 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                              
                                            <!-- <img src="./<?php echo base_url(); ?>/assets/media/heroicons/outline/information-circle.svg" alt="" class="injectable hw-20 mr-2"> -->
                                            <span>View Info</span>
                                        </a>

                                        <a class="dropdown-item align-items-center d-flex" href="#">
                                            <svg class="hw-20 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z" clip-rule="evenodd"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2"/>
                                              </svg>
                                            <!-- <img src="./<?php echo base_url(); ?>/assets/media/heroicons/outline/volume-off.svg" alt="" class="injectable hw-20 mr-2"> -->
                                            <span>Mute Notifications</span> 
                                        </a>
                                        <a class="dropdown-item align-items-center d-flex" href="#">
                                            <svg class="hw-20 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                              </svg>
                                            <!-- <img src="./<?php echo base_url(); ?>/assets/media/heroicons/outline/photograph.svg" alt="" class="injectable hw-20 mr-2"> -->
                                            <span>Wallpaper</span>
                                        </a>
                                        <a class="dropdown-item align-items-center d-flex" href="#">
                                            <svg class="hw-20 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                                              </svg>
                                              
                                            <!-- <img src="./<?php echo base_url(); ?>/assets/media/heroicons/outline/archive.svg" alt="" class="injectable hw-20 mr-2"> -->
                                            <span>Archive</span>
                                        </a>
                                        <a class="dropdown-item align-items-center d-flex" href="#">
                                            <svg class="hw-20 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                              </svg>
                                              
                                            <!-- <img src="./<?php echo base_url(); ?>/assets/media/heroicons/outline/trash.svg" alt="" class="injectable hw-20 mr-2"> -->
                                            <span>Delete</span>
                                        </a>
                                        <a class="dropdown-item align-items-center d-flex text-danger" href="#">
                                            <svg class="hw-20 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                              </svg>
                                              
                                            <!-- <img src="./<?php echo base_url(); ?>/assets/media/heroicons/outline/ban.svg" alt="" class="injectable hw-20 mr-2"> -->
                                            <span>Block</span>
                                        </a>
                                    </div>
                                </div>
                            </li>
                            <li class="nav-item list-inline-item d-sm-none mr-0">
                                <div class="dropdown">
                                    <a class="nav-link text-muted px-1" href="#" role="button" title="Details" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <svg class="hw-20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                                        </svg>
                                        <!-- <img src="./<?php echo base_url(); ?>/assets/media/heroicons/outline/dots-vertical.svg" alt="" class="injectable hw-20"> -->
                                    </a>

                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a class="dropdown-item align-items-center d-flex" href="#">
                                            <svg class="hw-20 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                              </svg>
                                            <!-- <img src="./<?php echo base_url(); ?>/assets/media/heroicons/outline/phone.svg" alt="" class="injectable hw-20 mr-2"> -->
                                            <span>Call</span>    
                                        </a>
                                        <a class="dropdown-item align-items-center d-flex" href="#" data-toggle="collapse" data-target="#searchCollapse" aria-expanded="false">
                                            <svg class="hw-20 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                            </svg>
                                            <!-- <img src="./<?php echo base_url(); ?>/assets/media/heroicons/outline/search.svg" alt="" class="injectable hw-20 mr-2"> -->
                                            <span>Search</span>    
                                        </a>
                                        
                                        <a class="dropdown-item align-items-center d-flex" href="#" data-chat-info-toggle="">
                                            <svg class="hw-20 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                              
                                            <!-- <img src="./<?php echo base_url(); ?>/assets/media/heroicons/outline/information-circle.svg" alt="" class="injectable hw-20 mr-2"> -->
                                            <span>View Info</span>
                                        </a>
                                        
                                        <a class="dropdown-item align-items-center d-flex" href="#">
                                            <svg class="hw-20 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z" clip-rule="evenodd"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2"/>
                                            </svg>
                                            <!-- <img src="./<?php echo base_url(); ?>/assets/media/heroicons/outline/volume-off.svg" alt="" class="injectable hw-20 mr-2"> -->
                                            <span>Mute Notifications</span> 
                                        </a>
                                        <a class="dropdown-item align-items-center d-flex" href="#">
                                            <svg class="hw-20 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            <!-- <img src="./<?php echo base_url(); ?>/assets/media/heroicons/outline/photograph.svg" alt="" class="injectable hw-20 mr-2"> -->
                                            <span>Wallpaper</span>
                                        </a>
                                        <a class="dropdown-item align-items-center d-flex" href="#">
                                            <svg class="hw-20 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                                            </svg>
                                            <!-- <img src="./<?php echo base_url(); ?>/assets/media/heroicons/outline/archive.svg" alt="" class="injectable hw-20 mr-2"> -->
                                            <span>Archive</span>
                                        </a>
                                        <a class="dropdown-item align-items-center d-flex" href="#">
                                            <svg class="hw-20 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                              
                                            <!-- <img src="./<?php echo base_url(); ?>/assets/media/heroicons/outline/trash.svg" alt="" class="injectable hw-20 mr-2"> -->
                                            <span>Delete</span>
                                        </a>
                                        <a class="dropdown-item align-items-center d-flex text-danger" href="#">
                                            <svg class="hw-20 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                            </svg>
                                              
                                            <!-- <img src="./<?php echo base_url(); ?>/assets/media/heroicons/outline/ban.svg" alt="" class="injectable hw-20 mr-2"> -->
                                            <span>Block</span>
                                        </a>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <!-- Chat Header End-->

                    <!-- Search Start -->
                    <div class="collapse border-bottom px-3" id="searchCollapse">
                        <div class="container-xl py-2 px-0 px-md-3">
                            <div class="input-group bg-light ">
                                <input type="text" class="form-control form-control-md border-right-0 bg-transparent pr-0" placeholder="Search">
                                <div class="input-group-append">
                                    <span class="input-group-text bg-transparent border-left-0">
                                        <svg class="hw-20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                          </svg>
                                          
                                        <!-- <img class="injectable hw-20" src="./<?php echo base_url(); ?>/assets/media/heroicons/outline/search.svg" alt="Search icon"> -->
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    <!-- Search End -->

                    <!-- Chat Content Start-->
                    <div class="chat-content p-2" id="history">
                        <div class="container">
                            <div class="message-day" id="user_conversation">


                                <!-- Received Message Start -->
                                
                                <!-- Received Message End -->

                            </div>
                        </div>

                        <!-- Scroll to finish -->
                        <div class="chat-finished" id="chat-finished"></div>
                    </div>
                    <!-- Chat Content End-->


                    <!-- Chat Footer Start-->
                    <div class="chat-footer" id="chat_footer">
                        <div class="attachment">
                        </div>
                        <textarea  class="form-control emojionearea-form-control" id="messageInput" name="agent_message" rows="1" placeholder="Type your message here..."></textarea>
                        <button onclick="javascript:send_message(this);" data-agentname="<?php echo $this->auth_username; ?>" id="send_message" class="btn btn-primary btn-icon send-icon rounded-circle text-light mb-1" role="button">
                            <svg class="hw-24" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </button>
                    </div>
                    <!-- Chat Footer End-->
                </div>
                <!-- Chat Body End -->

                <!-- Chat Info Start -->
                <div class="chat-info">
                    <div class="d-flex h-100 flex-column">

                        <!-- Chat Info Header Start -->
                        <div class="chat-info-header px-2">
                            <div class="container-fluid">
                                <ul class="nav justify-content-between align-items-center">
                                    <!-- Sidebar Title Start -->
                                    <li class="text-center">
                                        <h5 class="text-truncate mb-0">Profile Details</h5>
                                    </li>
                                    <!-- Sidebar Title End -->

                                    <!-- Close Sidebar Start -->
                                    <li class="nav-item list-inline-item">
                                        <a class="nav-link text-muted px-0" href="#" data-chat-info-close="">
                                             
                                            <svg class="hw-22" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                              
                                            <!-- <img class="injectable hw-22" src="./<?php echo base_url(); ?>/assets/media/heroicons/outline/x.svg" alt=""> -->
                                        
                                        </a>
                                    </li>
                                    <!-- Close Sidebar End -->
                                </ul>
                            </div>
                        </div>
                        <!-- Chat Info Header End  -->

                        <!-- Chat Info Body Start  -->
                        <div class="hide-scrollbar flex-fill">
                            <!-- User Profile Start -->
                            <div class="border-bottom text-center p-3">

                                <!-- User Profile Picture -->
                                <div class="avatar bg-success text-light avatar-xl mx-5 mb-3">
                                    <span>
                                        <svg class="hw-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                        <!-- <img class="injectable hw-50" src="./<?php echo base_url(); ?>/assets/media/heroicons/outline/user-group.svg" alt=""> -->
                                    </span>
                                </div>

                                <!-- User Info -->
                                <h5 class="mb-1">Themeforest Group</h5>
                                <p class="text-muted d-flex align-items-center justify-content-center">
                                    <svg class="mr-1 hw-18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                      
                                    <!-- <img class="injectable mr-1 hw-18" src="./<?php echo base_url(); ?>/assets/media/heroicons/outline/location-marker.svg" alt=""> -->
                                    <span>252 Participants</span>
                                </p>
                            </div>
                            <!-- User Profile End -->

                          
                            <!-- Participants Start -->
                            <div class="chat-info-group">
                                <a class="chat-info-group-header" data-toggle="collapse" href="#participants-list" role="button" aria-expanded="true" aria-controls="participants-list">
                                    <h6 class="mb-0">Group Participants</h6>
                                    
                                    <svg class="hw-20 text-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                    <!-- <img class="injectable text-muted hw-20" src="./<?php echo base_url(); ?>/assets/media/heroicons/outline/user-group.svg" alt=""> -->
                                  </a>

                                <div class="chat-info-group-body collapse show" id="participants-list">
                                    <div class="chat-info-group-content list-item-has-padding">
                                        <!-- List Group Start -->
                                        <ul class="list-group list-group-flush">

                                            <!-- List Group Item Start -->
                                            <li class="list-group-item">
                                                <div class="media align-items-center">
                                                    <div class="avatar mr-2">
                                                        <img src="<?php echo base_url(); ?>/assets/media/avatar/1.png" alt="">
                                                    </div>

                                                    <div class="media-body">
                                                        <h6 class="text-truncate">
                                                            <a href="#" class="text-reset">Catherine Richardson</a>
                                                        </h6>
                                                        <p class="text-muted mb-0">Product designer</p>
                                                    </div>

                                                    <div class="media-options ml-1">
                                                        <div class="dropdown">
                                                            <button class="btn btn-secondary btn-icon btn-minimal btn-sm text-muted" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                <svg class="hw-20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                                                                </svg>
                                                                <!-- <img class="injectable hw-20" src="./<?php echo base_url(); ?>/assets/media/heroicons/outline/dots-vertical.svg" alt=""> -->
                                                            </button>
                                                            <div class="dropdown-menu">
                                                                <a class="dropdown-item" href="#">Make admin</a>
                                                                <a class="dropdown-item" href="#">Remove from group</a>
                                                                <a class="dropdown-item" href="#">Block</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                            <!-- List Group Item End -->

                                            <!-- List Group Item Start -->
                                            <li class="list-group-item">
                                                <div class="media align-items-center">
                                                    <div class="avatar mr-2">
                                                        <img src="<?php echo base_url(); ?>/assets/media/avatar/2.png" alt="">
                                                    </div>

                                                    <div class="media-body">
                                                        <h6 class="text-truncate">
                                                            <a href="#" class="text-reset">Maizie Edwards</a>
                                                        </h6>
                                                        <p class="text-muted mb-0">Team leader</p>
                                                    </div>

                                                    <div class="media-options ml-1">
                                                        <div class="dropdown">
                                                            <button class="btn btn-secondary btn-icon btn-minimal btn-sm text-muted" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                <svg class="hw-20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                                                                </svg>
                                                                <!-- <img class="injectable hw-20" src="./<?php echo base_url(); ?>/assets/media/heroicons/outline/dots-vertical.svg" alt=""> -->
                                                            </button>
                                                            <div class="dropdown-menu">
                                                                <a class="dropdown-item" href="#">Make admin</a>
                                                                <a class="dropdown-item" href="#">Remove from group</a>
                                                                <a class="dropdown-item" href="#">Block</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                            <!-- List Group Item End -->

                                            <!-- List Group Item Start -->
                                            <li class="list-group-item">
                                                <div class="media align-items-center">
                                                    <div class="avatar mr-2">
                                                        <img src="<?php echo base_url(); ?>/assets/media/avatar/3.png" alt="">
                                                    </div>

                                                    <div class="media-body">
                                                        <h6 class="text-truncate">
                                                            <a href="#" class="text-reset">Brittany K. Williams</a>
                                                        </h6>
                                                        <p class="text-muted mb-0">UI/UX designer</p>
                                                    </div>

                                                    <div class="media-options ml-1">
                                                        <div class="dropdown">
                                                            <button class="btn btn-secondary btn-icon btn-minimal btn-sm text-muted" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                <svg class="hw-20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                                                                </svg>
                                                                <!-- <img class="injectable hw-20" src="./<?php echo base_url(); ?>/assets/media/heroicons/outline/dots-vertical.svg" alt=""> -->
                                                            </button>
                                                            <div class="dropdown-menu">
                                                                <a class="dropdown-item" href="#">Make admin</a>
                                                                <a class="dropdown-item" href="#">Remove from group</a>
                                                                <a class="dropdown-item" href="#">Block</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                            <!-- List Group Item End -->

                                            <!-- List Group Item Start -->
                                            <li class="list-group-item">
                                                <div class="media align-items-center">
                                                    <div class="avatar mr-2">
                                                        <img src="<?php echo base_url(); ?>/assets/media/avatar/4.png" alt="">
                                                    </div>

                                                    <div class="media-body">
                                                        <h6 class="text-truncate">
                                                            <a href="#" class="text-reset">Albert K. Johansen</a>
                                                        </h6>
                                                        <p class="text-muted mb-0">Sr. developer</p>
                                                    </div>

                                                    <div class="media-options ml-1">
                                                        <div class="dropdown">
                                                            <button class="btn btn-secondary btn-icon btn-minimal btn-sm text-muted" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                <svg class="hw-20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                                                                </svg>
                                                                <!-- <img class="injectable hw-20" src="./<?php echo base_url(); ?>/assets/media/heroicons/outline/dots-vertical.svg" alt=""> -->
                                                            </button>
                                                            <div class="dropdown-menu">
                                                                <a class="dropdown-item" href="#">Make admin</a>
                                                                <a class="dropdown-item" href="#">Remove from group</a>
                                                                <a class="dropdown-item" href="#">Block</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                            <!-- List Group Item End -->

                                            <!-- List Group Item Start -->
                                            <li class="list-group-item">
                                                <div class="media align-items-center">
                                                    <div class="avatar mr-2">
                                                        <img src="<?php echo base_url(); ?>/assets/media/avatar/5.png" alt="">
                                                    </div>

                                                    <div class="media-body">
                                                        <h6 class="text-truncate">
                                                            <a href="#" class="text-reset">Christopher Garcia</a>
                                                        </h6>
                                                        <p class="text-muted mb-0">Project manager</p>
                                                    </div>

                                                    <div class="media-options ml-1">
                                                        <div class="dropdown">
                                                            <button class="btn btn-secondary btn-icon btn-minimal btn-sm text-muted" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                <svg class="hw-20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                                                                </svg>
                                                                <!-- <img class="injectable hw-20" src="./<?php echo base_url(); ?>/assets/media/heroicons/outline/dots-vertical.svg" alt=""> -->
                                                            </button>
                                                            <div class="dropdown-menu">
                                                                <a class="dropdown-item" href="#">Make admin</a>
                                                                <a class="dropdown-item" href="#">Remove from group</a>
                                                                <a class="dropdown-item" href="#">Block</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                            <!-- List Group Item End -->

                                        </ul>
                                        <!-- List Group End -->
                                    </div>
                                </div>
                            </div>
                            <!-- Participants End -->
                            
                            <!-- Shared Media Start -->
                            <div class="chat-info-group">
                                <a class="chat-info-group-header" data-toggle="collapse" href="#shared-media" role="button" aria-expanded="true" aria-controls="shared-media">
                                    <h6 class="mb-0">Last Media</h6>
                                   
                                    <!-- Default :: Inline SVG -->
                                    <svg class="hw-20 text-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>

                                    <!-- Alternate :: External File link -->
                                    <!-- <img class="injectable text-muted hw-20" src="./<?php echo base_url(); ?>/assets/media/heroicons/outline/photograph.svg" alt=""> -->
                                    
                                  </a>

                                <div class="chat-info-group-body collapse show" id="shared-media">
                                    <div class="chat-info-group-content">
                                        <!-- Shared Media -->
                                        <div class="form-row">
                                            <div class="col-4 col-md-2 col-xl-4">
                                                <a href="#">
                                                    <img src="<?php echo base_url(); ?>/assets/media/shared-photos/01.jpg" class="img-fluid rounded border" alt="">
                                                </a>
                                            </div>
                                            <div class="col-4 col-md-2 col-xl-4">
                                                <a href="#">
                                                    <img src="<?php echo base_url(); ?>/assets/media/shared-photos/02.jpg" class="img-fluid rounded border" alt="">
                                                </a>
                                            </div>
                                            <div class="col-4 col-md-2 col-xl-4">
                                                <a href="#">
                                                    <img src="<?php echo base_url(); ?>/assets/media/shared-photos/03.jpg" class="img-fluid rounded border" alt="">
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Shared Media End -->

                            <!-- Shared Files Start -->
                            <div class="chat-info-group">
                                <a class="chat-info-group-header" data-toggle="collapse" href="#shared-files" role="button" aria-expanded="true" aria-controls="shared-files">
                                    <h6 class="mb-0">Documents</h6>
                                    <!-- Default :: Inline SVG -->
                                    <svg class="hw-20 text-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                    </svg>
                                  
                                    <!-- Alternate :: External File link -->
                                    <!-- <img class="injectable text-muted hw-20" src="./<?php echo base_url(); ?>/assets/media/heroicons/outline/document.svg" alt=""> -->
                                </a>

                                <div class="chat-info-group-body collapse show" id="shared-files">
                                    <div class="chat-info-group-content list-item-has-padding">
                                         <!-- List Group Start -->
                                         <ul class="list-group list-group-flush">

                                            <!-- List Group Item Start -->
                                            <li class="list-group-item">
                                                <div class="document">
                                                    <div class="btn btn-primary btn-icon rounded-circle text-light mr-2">
                                                        <!-- Default :: Inline SVG -->
                                                        <svg class="hw-24" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                                        </svg>
                                                          
                                                        <!-- Alternate :: External File link -->
                                                        <!-- <img class="injectable hw-24" src="./<?php echo base_url(); ?>/assets/media/heroicons/outline/document.svg" alt=""> -->
                                                    </div>

                                                    <div class="document-body">
                                                        <h6 class="text-truncate">
                                                            <a href="#" class="text-reset" title="effects-of-global-warming.docs">Effects-of-global-warming.docs</a>
                                                        </h6>

                                                        <ul class="list-inline small mb-0">
                                                            <li class="list-inline-item">
                                                                <span class="text-muted">79.2 KB</span>
                                                            </li>
                                                            <li class="list-inline-item">
                                                                <span class="text-muted text-uppercase">docs</span>
                                                            </li>
                                                        </ul>
                                                    </div>

                                                    <div class="document-options ml-1">
                                                        <div class="dropdown">
                                                            <button class="btn btn-secondary btn-icon btn-minimal btn-sm text-muted" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                <!-- Default :: Inline SVG -->
                                                                <svg class="hw-20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                                                                </svg>
                                                                
                                                                <!-- Alternate :: External File link -->
                                                                <!-- <img class="injectable hw-20" src="./<?php echo base_url(); ?>/assets/media/heroicons/outline/dots-vertical.svg" alt=""> -->
                                                            </button>
                                                            <div class="dropdown-menu">
                                                                <a class="dropdown-item" href="#">Download</a>
                                                                <a class="dropdown-item" href="#">Share</a>
                                                                <a class="dropdown-item" href="#">Delete</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                            <!-- List Group Item End -->

                                            <!-- List Group Item Start -->
                                            <li class="list-group-item">
                                                <div class="document">
                                                    <div class="btn btn-primary btn-icon rounded-circle text-light mr-2">
                                                        <!-- Default :: Inline SVG -->
                                                        <svg class="hw-24" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                                        </svg>

                                                        <!-- Alternate :: External File link -->
                                                        <!-- <img class="injectable hw-24" src="./<?php echo base_url(); ?>/assets/media/icons/excel-file.svg" alt=""> -->
                                                    </div>

                                                    <div class="document-body">
                                                        <h6 class="text-truncate">
                                                            <a href="#" class="text-reset" title="global-warming-data-2020.xlxs">Global-warming-data-2020.xlxs</a>
                                                        </h6>

                                                        <ul class="list-inline small mb-0">
                                                            <li class="list-inline-item">
                                                                <span class="text-muted">79.2 KB</span>
                                                            </li>
                                                            <li class="list-inline-item">
                                                                <span class="text-muted text-uppercase">xlxs</span>
                                                            </li>
                                                        </ul>
                                                    </div>

                                                    <div class="document-options ml-1">
                                                        <div class="dropdown">
                                                            <button class="btn btn-secondary btn-icon btn-minimal btn-sm text-muted" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                <!-- Default :: Inline SVG -->
                                                                <svg class="hw-20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                                                                </svg>
                                                                
                                                                <!-- Alternate :: External File link -->
                                                                <!-- <img class="injectable hw-20" src="./<?php echo base_url(); ?>/assets/media/heroicons/outline/dots-vertical.svg" alt=""> -->
                                                            </button>
                                                            <div class="dropdown-menu">
                                                                <a class="dropdown-item" href="#">View</a>
                                                                <a class="dropdown-item" href="#">Share</a>
                                                                <a class="dropdown-item" href="#">Delete</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                            <!-- List Group Item End -->

                                            <!-- List Group Item Start -->
                                            <li class="list-group-item">
                                                <div class="document">
                                                    <div class="btn btn-primary btn-icon rounded-circle text-light mr-2">
                                                        <!-- Default :: Inline SVG -->
                                                        <svg class="hw-24" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                                        </svg>

                                                        <!-- Alternate :: External File link -->
                                                        <!-- <img class="injectable hw-24" src="./<?php echo base_url(); ?>/assets/media/icons/powerpoint-file.svg" alt=""> -->
                                                    </div>

                                                    <div class="document-body">
                                                        <h6 class="text-truncate">
                                                            <a href="#" class="text-reset" title="great-presentation-on global-warming-2020.ppt">Great-presentation-on global-warming-2020.ppt</a>
                                                        </h6>

                                                        <ul class="list-inline small mb-0">
                                                            <li class="list-inline-item">
                                                                <span class="text-muted">79.2 KB</span>
                                                            </li>
                                                            <li class="list-inline-item">
                                                                <span class="text-muted text-uppercase">ppt</span>
                                                            </li>
                                                        </ul>
                                                    </div>

                                                    <div class="document-options ml-1">
                                                        <div class="dropdown">
                                                            <button class="btn btn-secondary btn-icon btn-minimal btn-sm text-muted" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                <!-- Default :: Inline SVG -->
                                                                <svg class="hw-20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                                                                </svg>
                                                                
                                                                <!-- Alternate :: External File link -->
                                                                <!-- <img class="injectable hw-20" src="./<?php echo base_url(); ?>/assets/media/heroicons/outline/dots-vertical.svg" alt=""> -->
                                                            </button>
                                                            <div class="dropdown-menu">
                                                                <a class="dropdown-item" href="#">Download</a>
                                                                <a class="dropdown-item" href="#">Share</a>
                                                                <a class="dropdown-item" href="#">Delete</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                            <!-- List Group Item End -->
                                        </ul>
                                        <!-- List Group End -->
                                    </div>
                                </div>
                            </div>
                            <!-- Shared Files End -->


                        </div>
                        <!-- Chat Info Body Start  -->

                    </div>
                </div>
                <!-- Chat Info End -->
            </div>
            <!-- Chats Page End -->
        </main>
        <!-- Main End -->

        <main class="main" id="default_main">
            <div class="chats">
                <div class="d-flex flex-column justify-content-center text-center h-100 w-100">
                    <div class="container">
                        <h5>Welcome, <?php echo $this->auth_username; ?>!</h5>
                        <h6 id="random_quote"></h6>
                        <p class="text-muted">Please select a chat to Start supporting.</p>
                    </div>
                </div>
            </div>
        </main>


        <div class="backdrop"></div>
 
    </div>
    <!-- Main Layout End -->
  

    <!-- Javascript Files -->
    <script src="<?php echo base_url(); ?>/assets/vendors/jquery/jquery-3.5.0.min.js"></script>
    <script src="<?php echo base_url(); ?>/assets/js/moment.min.js"></script>
    <script src="<?php echo base_url(); ?>/assets/vendors/bootstrap/bootstrap.bundle.min.js"></script>
    <script src="<?php echo base_url(); ?>/assets/vendors/magnific-popup/jquery.magnific-popup.min.js"></script>
    <script src="<?php echo base_url(); ?>/assets/vendors/svg-inject/svg-inject.min.js"></script>
    <script src="<?php echo base_url(); ?>/assets/vendors/modal-stepes/modal-steps.min.js"></script>
    <script src="<?php echo base_url(); ?>/assets/vendors/emojione/emojionearea.min.js"></script>
    <script src="<?php echo base_url(); ?>/assets/js/app.js"></script>
    <script src="<?php echo base_url(); ?>/assets/js/agent.js" id="include_js_id" data-agentid="<?php echo $this->auth_user_id; ?>"></script>
</body>
</html>