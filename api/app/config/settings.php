<?php

defined('BASEPATH') OR exit('No direct script access allowed');


$config['bearer_token'] = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJlbWFpbCI6InRoZW5hbm9sYWJlbWFpbEBnbWFpbC5jb20iLCJzdHJhdGVneSI6ImRlZmF1bHQiLCJpc1N1cGVyQWRtaW4iOnRydWUsImlhdCI6MTYxMDc5OTU2MywiZXhwIjoxNjEwODIxMTYzLCJhdWQiOiJjb2xsYWJvcmF0b3JzIn0.Ga9P2RL7VXZfXhecQav9A2Bigc0yRrBoK3GAIZXwWF4";

$config['bearer_token_url'] = "https://bots.siabot.com/api/v1/auth/login/basic/default";
$config['admin_user_name'] = "thenanolabemail@gmail.com";
$config['admin_password'] = "Smoke!23"; 

$config['session_pause_url'] = "https://bots.siabot.com/api/v1/bots/{bot_id}/mod/hitl/channel/web/user/{user_id}/pause";

$config['session_unpause_url'] = "https://bots.siabot.com/api/v1/bots/{bot_id}/mod/hitl/channel/web/user/{user_id}/unpause";

$config['send_message_url'] = "https://bots.siabot.com/api/v1/bots/{bot_id}/mod/channel-web/messages/{user_id}";

$config['default_welcome_message'] = "Hello, welcome to Ontime Group customer support. How can we help you today?";
$config['send_coordinator_emails'] = 1; //yes
