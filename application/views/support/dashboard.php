

<div class="row">
    <div class="col-lg-12">
        <div class="email-left-box px-0 mb-3">
            <div id="accordion-six" class="accordion accordion-primary accordion-bordered">
                <div class="accordion__item">
                    <div class="accordion__header" data-toggle="collapse" data-target="#with-icon_collapseOne">
                        <span class="accordion__header--text">Waiting Customers</span>
                        <span class="accordion__header--indicator indicator_bordered"></span>
                    </div>
                    <div id="with-icon_collapseOne" class="collapse accordion__body show" data-parent="#accordion-six">
                        <div class="accordion__body--text">
                            <div id="DZ_W_Todo1" class="widget-media dz-scroll height200 ps ps--active-y">
                                <ul class="timeline" id="paused_conversations"  data-chat-list="">

                                    <?php
                                    /*for ($i=0; $i <10 ; $i++) { 
                                    ?>
                                    <li>
                                        <div class="timeline-panel">
                                            <div class="media mr-2 media-danger">
                                                K<?php echo $i; ?>
                                            </div>
                                            <div class="media-body">
                                                <h5 class="mb-1">New Request</h5>
                                                <small class="d-block"><?php echo date('Y-m-d H:i A'); ?></small>
                                            </div>
                                            <div class="dropdown">
                                                <button type="button" class="btn btn-danger light sharp">
                                                    <i class="fa fa-check"></i>
                                                </button>
                                                
                                            </div>
                                        </div>
                                    </li>
                                    <?php
                                    }*/
                                    ?>
                                    
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="accordion__item">
                    <div class="accordion__header" data-toggle="collapse" data-target="#with-icon_collapseTwo">
                        <span class="accordion__header--text">Ongoing chats</span>
                        <span class="accordion__header--indicator indicator_bordered"></span>
                    </div>
                    <div id="with-icon_collapseTwo" class="collapse accordion__body show" data-parent="#accordion-six">
                        <div class="accordion__body--text">
                            <div id="DZ_W_Todo1" class="widget-media dz-scroll height150 ps ps--active-y">
                                <ul class="timeline" id="ongoing_conversations"  data-chat-list="">
                                    
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="accordion__item">
                    <div class="accordion__header collapsed" data-toggle="collapse" data-target="#with-icon_collapseThree">
                        <span class="accordion__header--text">Chat History</span>
                        <span class="accordion__header--indicator indicator_bordered"></span>
                    </div>
                    <div id="with-icon_collapseThree" class="collapse accordion__body" data-parent="#accordion-six">
                        <div class="accordion__body--text">
                            <div id="DZ_W_Todo1" class="widget-media dz-scroll height370 ps ps--active-y">
                                <ul class="timeline" id="all_conversations" data-chat-list="">
                                    
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="email-right-box ml-0 ml-sm-4 ml-sm-0">
            <div class="card" id="main_chat">
                <div class="card-header d-sm-flex d-block pb-2 border-0 bg-ontime">
                    
                    <div>
                        <h5 class="card-title text-white" id="customer_name">Customer Name</h5>
                        <p class="mb-0 fs-12"><strong><i class="fa fa-envelope"></i></strong>&nbsp;<span id="customer_email">Customer Email</span> &nbsp;&nbsp; <strong><i class="fa fa-phone"></i></strong>&nbsp;<span id="customer_mobile">+971 1234567890</span></p>
                    </div>
                
                    <div class="dropdown custom-dropdown d-block mt-3 mt-sm-0 mb-2">
                        <button class="btn btn-danger btn-sm" id="endchat" data-botid="" data-customerid="" data-conversationid="" data-pgid="" onclick="javascript:end_chat(this);">End Chat</button>
                    </div>
                </div>
                <div class="card-body p-0" id="history">
                    <div class="container">
                        <div class="dz-scroll height370 ps ps--active-y" id="DZ_W_Contacts_Body3">
                            <div id="user_conversation">
                            </div>
                        </div>
                    </div>
                    <div class="chat-finished" id="chat-finished"></div>
                </div>
                <div class="card-footer" id="chat_footer">
                    <div class="row">
                        <div class="col-md-10">
                            <textarea id="messageInput" name="agent_message" class="form-control text-dark d-inline" placeholder="Type something to send..."></textarea>
                        </div>
                        <div class="col-md-2 pt-2">
                            <button onclick="javascript:send_message(this);" data-agentname="<?php echo $this->auth_username; ?>" href="javascript:void(0);" class="btn btn-primary">SEND</button>
                        </div>
                    </div>

                    
                </div>
            </div>
            <div class="card" id="default_main">
                <div class="chats">
                    <div class="d-flex flex-column justify-content-center text-center h-100 w-100">
                        <div class="container">
                            <h5>Welcome, <?php echo $this->auth_username; ?>!</h5>
                            <h6 id="random_quote"></h6>
                            <p class="text-muted">Please select a chat to start supporting.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


