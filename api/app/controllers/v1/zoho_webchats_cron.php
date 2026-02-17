<?php
// zoho_cron.php for the Fetching the Webchats Leads  for Mazaya,Business,MOH,RTA from the Zoho CRM

// Define the API endpoint
$url = 'https://crm.ontimegroup.com/api/v1/order/zoho_leads_webchats';  // Replace with your API endpoint

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
