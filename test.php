<?php
// API endpoint URL
$apiUrl = 'http://telegram.om-dienstleistungen.de/';

// Data to send in the POST request (example)
$postData = [
    'getMessagesAuto' => 'getMessagesAuto'
];

// Initialize curl
$curl = curl_init();

// Set the curl options
curl_setopt_array($curl, [
    CURLOPT_URL => $apiUrl,
    CURLOPT_RETURNTRANSFER => true, // Return response as a string instead of outputting it
    CURLOPT_FOLLOWLOCATION => true, // Follow redirects
    CURLOPT_MAXREDIRS => 10, // Maximum number of redirects to follow
    CURLOPT_TIMEOUT => 300, // Timeout in seconds
    CURLOPT_POST => true, // Set as POST request
    CURLOPT_POSTFIELDS => http_build_query($postData), // Encode data as URL-encoded format
    CURLOPT_HTTPHEADER => [ // Optional headers
        'Content-Type: application/x-www-form-urlencoded',
    ],
]);

// Execute the request
$response = curl_exec($curl);

// Check for errors
if (curl_errno($curl)) {
    $errorMessage = curl_error($curl);
    // Handle the error
    echo "cURL error: $errorMessage";
} else {
    // Handle the response` 
    $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE); // Get HTTP status code
    if ($statusCode >= 200 && $statusCode < 300) {
        // Request was successful
        $response = json_decode($response, true);
        print_r(array_values($response));
    } else {
        // Request failed
        echo "Request failed with status code $statusCode";
        // You can handle different status codes here
    }
}

// Close curl
curl_close($curl);
