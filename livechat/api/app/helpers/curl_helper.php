<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//https://bots.siabot.com/api/v1/auth/login/basic/default
function get_bearer_token() {
   $curl = curl_init();
   curl_setopt_array($curl, array(
   CURLOPT_URL => config_item("bearer_token_url"),
     CURLOPT_RETURNTRANSFER => true,
     CURLOPT_ENCODING => "",
     CURLOPT_MAXREDIRS => 10,
     CURLOPT_TIMEOUT => 0,
     CURLOPT_FOLLOWLOCATION => true,
     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
     CURLOPT_CUSTOMREQUEST => "POST",
     CURLOPT_POSTFIELDS => "email=".config_item('admin_user_name')."&password=".config_item('admin_password'),
      CURLOPT_HTTPHEADER => array(
       "Content-Type: application/x-www-form-urlencoded"
     ),
   ));
   $response = curl_exec($curl);
   curl_close($curl);
   $response_json = json_decode($response);
   return $response_json->payload->token;
}

function send_post_request($url, $param_array='') {
   $ch = curl_init();
   $token = get_bearer_token();
   $headers = array(
      'Content-Type: application/json',
      'Authorization: Bearer '.$token
   );
   curl_setopt($ch, CURLOPT_URL, $url);
   curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
   curl_setopt($ch, CURLOPT_HEADER, false);
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
   curl_setopt($ch, CURLOPT_POST, 1);
   curl_setopt($ch, CURLOPT_TIMEOUT, 30);
   curl_setopt($ch, CURLOPT_FAILONERROR, true);
   $result = curl_exec($ch);
   if (curl_errno($ch)) {
      $error_msg = curl_error($ch);

      error_log("send_post_request curl error: ".$error_msg);
      error_log($url);
   }
   $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
   curl_close($ch);
   return $http_code;
}

function send_post_params($url, $param_array) {
   $ch = curl_init();
   $token = get_bearer_token();
   $headers = array(
      'Content-Type: application/json',
      'Authorization: Bearer '.$token
   );
   curl_setopt($ch, CURLOPT_URL, $url);
   curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
   curl_setopt($ch, CURLOPT_HEADER, true);
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
   curl_setopt($ch, CURLOPT_POST, 1);
   curl_setopt($ch, CURLOPT_POSTFIELDS, $param_array);
   curl_setopt($ch, CURLOPT_TIMEOUT, 30);
   curl_setopt($ch, CURLOPT_FAILONERROR, true);
   $result = curl_exec($ch);
   if (curl_errno($ch)) {
      $error_msg = curl_error($ch);
      echo("send_post_params curl error: ".$error_msg."<br />");
      echo($url);
   }
   //$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
   curl_close($ch);
   return json_decode($result);
}

function send_message_curl($url, $param_array) {
   $ch = curl_init();
   $token = get_bearer_token();
   $headers = array(
      'Content-Type: application/json',
      'Authorization: Bearer '.$token
   );
   curl_setopt($ch, CURLOPT_URL, $url);
   curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
   curl_setopt($ch, CURLOPT_HEADER, true);
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
   curl_setopt($ch, CURLOPT_POST, 1);
   curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($param_array));
   curl_setopt($ch, CURLOPT_TIMEOUT, 30);
   curl_setopt($ch, CURLOPT_FAILONERROR, true);
   $result = curl_exec($ch);
   if (curl_errno($ch)) {
      $error_msg = curl_error($ch);
      echo("send_post_params curl error: ".$error_msg."<br />");
      echo($url);
   }
   //$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
   curl_close($ch);
   return json_decode($result);
}


