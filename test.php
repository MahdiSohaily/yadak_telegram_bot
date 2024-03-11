<?php
require_once './config/constants.php';
require_once './database/connect.php';
require_once './utilities/PriceHelpers.php';
require_once './app/Controllers/MessageController.php';

// API endpoint URL
$apiUrl = 'http://telegram.om-dienstleistungen.de/';

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
        $response = array_values($response);

        validateMessages($response);
    } else {
        // Request failed
        echo "Request failed with status code $statusCode";
        // You can handle different status codes here
    }
}

validateMessages($response);

function validateMessages($messages)
{
    foreach ($messages as $message) {
        $sender = $message['userName'][0];

        if (!checkIfValidSender($sender)) {
            echo "Sender $sender is not valid";
            continue;
        }

        $messageContent = $message['info'];

        $selectedGoods = getSelectedGoods();

        foreach ($messageContent as $item) {
            $codes = explode("\n", $item['code']);

            $codes = array_filter($codes, function ($line) use ($selectedGoods) {
                return $line !== "" && in_array($line, $selectedGoods);
            });
            // Now $codes contains the filtered codes

            if (count($codes) > 0) {
                try {
                    $data = getPrice($codes);
                    $data = getFinalPrice($data);

                    $template = '';

                    foreach ($data as $item) {
                        $template .= $item['partnumber'] . ' : ' . $item['price'] . "\n";
                    }

                    echo $template;
                    // saveConversation($sender, implode(' ', $codes), $template);
                    // sendMessageWithTemplate($sender, $template);
                } catch (Exception $error) {
                    echo 'Error fetching price: ' . $error->getMessage();
                }
            } else {
                echo "No valid codes found";
            }
        }
    }
}

function getFinalPrice($prices)
{
    $explodedCodes = $prices['explodedCodes'];
    $existing = $prices['existing'];
    $displayPrices = [];

    foreach ($explodedCodes as $code) {
        $existingCodes = array_values($existing[$code]);
        $max = 0;

        foreach ($existingCodes as $item) {
            $max += max(array_values($item['relation']['sorted']));
        }

        if ($max <= 0) {
            return false;
        }

        $givenPrice = $existingCodes[0]['givenPrice'];

        if (!is_array($givenPrice)) {
            $givenPrice = array_values($givenPrice);
        }

        if (count($givenPrice) > 0) {
            $displayPrices[] = [
                'partnumber' => $givenPrice[0]['partnumber'],
                'price' => $givenPrice[0]['price']
            ];
        } else {
            return false;
        }
    }

    return $displayPrices;
}


// Close curl
curl_close($curl);
