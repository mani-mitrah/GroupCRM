<?php
// zoho_cron.php for the Fetching the OntimeBiz Leads from the Zoho CRM

// Define the API endpoint
$url = base_url().'api/v1/lead/convert_next_contactable_leads_to_active';  // Replace with your API endpoint

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