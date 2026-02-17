<?php

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://crm.ontimegroup.com/api/v1/category/enquiry',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => array('name' => 'Ganesh','phone' => '+919962528288','email' => 'ganesh@alphasoftz.com','enquiry' => '1','subject' => 'Testing the ontimevisa integration','website_id' => '503'),
));

$response = curl_exec($curl);

curl_close($curl);
echo $response;
