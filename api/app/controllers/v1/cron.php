<?php
// cron.php for the Fetching the Goldencube Leads from the Ontime CRM which is not completed more than 7 days

// Define the API endpoint
$url = 'https://crm.ontimegroup.com/api/v1/Lead/goldencube_pending_leads';  // Replace with your API endpoint

// Initialize cURL
$ch = curl_init($url);

// Set cURL options
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);  // Set the request to POST method

$headers = array(
    'Content-Type: application/x-www-form-urlencoded',
    // Add headers if required, e.g., 'Authorization: Bearer YOUR_API_TOKEN'
    // 'Authorization: Bearer your_api_token'
);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

// Execute the request
$response = curl_exec($ch);

// Check for errors
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
} else {
    // Process the response if needed
    echo 'Response:' . $response;
}

// Close the cURL session
curl_close($ch);


$url = 'https://crm.ontimegroup.com/api/v1/Lead/goldencube_pending_sub_leads';  // Replace with your API endpoint

// Initialize cURL
$ch = curl_init($url);

// Set cURL options
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);  // Set the request to POST method

$headers = array(
    'Content-Type: application/x-www-form-urlencoded',
    // Add headers if required, e.g., 'Authorization: Bearer YOUR_API_TOKEN'
    // 'Authorization: Bearer your_api_token'
);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

// Execute the request
$response = curl_exec($ch);

// Check for errors
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
} else {
    // Process the response if needed
    echo 'Response:' . $response;
}

// Close the cURL session
curl_close($ch);
?>
