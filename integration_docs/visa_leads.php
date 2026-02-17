<?php
//lead_type = visa or uae_visa
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://crm.ontimegroup.com/api/v1/lead/visa',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => array('lead_type' => 'visa','customer_name' => 'Ganesh Ananthan','country' => 'India','country_code' => '+91','customer_mobile' => '9962528200','customer_email' => 'info@thenanolab.com','description' => '<div><b>GENERAL INFORMATION<br><br><b>FROM:</b> Pakistan<br><br><b>TO:</b> Turkey<br><br><b>EMAIL:</b> adnangoldentulip@gmail.com<br><br><b>PHONE:</b> 00971506853629<br><br><b>NATIONALITY:</b> Pakistan<br><br><b>SERVICE :</b> Single Entry<br><br><b>ADD ON SERVICE:</b> Hotel Booking<br><br><b>Estimated Trip Start Date:</b> 07 20 2021<br><br><b>Estimated Trip End Date:</b> 07 31 2021<br><br><b>ADDRESS :</b> Flat#1203 , al meer tower, Barsha heights Dubai<br><br><b>APPLICANTS:</b><br><br><b>FIRST NAME :</b> Adnan<br><br><b>LAST NAME :</b> Kha<br><br><b>GENDER :</b> Male<br><br><b>NATIONALITY (as in passport) :</b> Pakistan<br><br><b>BIRTHDAY :</b> 07 12 1994<br><br><b>COUNTRY OF BIRTH :</b> Pakistan<br><br><b>COUNTRY OF RESIDENCE :</b> United Arab Emirates<br><br><b>PASSPORT NUMBER :</b> GM4128962<br><br><b>PASSPORT ISSUED ON :</b> 12 07 2016<br><br><b>PASSPORT EXPIRES ON :</b> 12 07 2026<br><br><b><br><br><b>ENQUIRY TYPE :</b> <br>Leads</b></b></div>'),
));

$response = curl_exec($curl);

curl_close($curl);
echo $response;
