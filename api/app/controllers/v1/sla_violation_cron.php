<?php
// sla_violation_cron.php for the Send the Violation Mail for the Assigend to user in Ontime CRM

$url = 'https://crm.ontimegroup.com/api/v1/lead/resource_violation_leads'; 

$ch = curl_init($url);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);  

$headers = array(
    'Content-Type: application/x-www-form-urlencoded',
);

curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
$response = curl_exec($ch);

if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
} else {
    echo 'Response:' . $response;
}
curl_close($ch);

?>
