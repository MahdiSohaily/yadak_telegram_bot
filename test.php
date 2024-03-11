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
// $response = curl_exec($curl);

$response = '{
    "169785118":{
        "info":[
            {"code":"58101A7A00\n","message":"58101-A7A00","date":1710145143}],
        "name":["Mahdi Rezaei"],
        "userName":[169785118],
        "profile":["169785118_x_4.jpg"]},
    "5684446360":{
        "info":[
            {"code":"86565C5010\n","message":"86565-C5010\n\n\n?????","date":1710145141}],
        "name":["Azimi Store"],
        "userName":[5684446360],
        "profile":["5684446360_x_4.jpg"]},
    "483892514":{
        "info":[
            {"code":"935702W050\n","message":"93570-2W050\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n?????????????","date":1710145139},{"code":"553003X110\n","message":"55300-3X110\n\n\n\n\nAsli\n\n\n\n\n\n??","date":1710145084},{"code":"553003X110\n","message":"55300-3X110","date":1710144956}],
        "name":["Afshin Afshar","Afshin Afshar","Afshin Afshar"],
        "userName":[483892514,483892514,483892514],
        "profile":["483892514_x_4.jpg","483892514_x_4.jpg","483892514_x_4.jpg"]},
    "370163101":{
        "info":[
            {"code":"865113L010\n","message":"86511-3L010\n\n\n\ud83c\udd70\ufe0f\ud83c\udd70\ufe0f\ud83c\udd70\ufe0f\ud83c\udd70\ufe0f\ud83c\udd70\ufe0f\ud83c\udd70\ufe0f\ud83c\udd70\ufe0f\ud83c\udd70\ufe0f\ud83c\udd70\ufe0f","date":1710145137},
            {"code":"865113L010\n","message":"86511-3L010\n\n\n\ud83c\udd70\ufe0f\ud83c\udd70\ufe0f\ud83c\udd70\ufe0f\ud83c\udd70\ufe0f\ud83c\udd70\ufe0f\ud83c\udd70\ufe0f\ud83c\udd70\ufe0f\ud83c\udd70\ufe0f\ud83c\udd70\ufe0f","date":1710145045}
        ],
        "name":["Ali . R E Z A A L I","Ali . R E Z A A L I"],
        "userName":[370163101,370163101],
        "profile":["370163101_x_4.jpg","370163101_x_4.jpg"]}
    }';

// // Check for errors
// if (curl_errno($curl)) {
//     $errorMessage = curl_error($curl);
//     // Handle the error
//     echo "cURL error: $errorMessage";
// } else {
//     // Handle the response` 
//     $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE); // Get HTTP status code
//     if ($statusCode >= 200 && $statusCode < 300) {
//         // Request was successful
//         $response = json_decode($response, true);
//         $response = array_values($response);

//         validateMessages($response);
//     } else {
//         // Request failed
//         echo "Request failed with status code $statusCode";
//         // You can handle different status codes here
//     }
// }

$response = json_decode($response, true);
$response = array_values($response);

validateMessages($response);

function validateMessages($messages)
{
    foreach ($messages as $message) {
        $sender = $message['userName'][0];

        if (!checkIfValidSender($sender)) {
            echo "Sender $sender is not valid";
            break;
        }

        $messageContent = $message['info'];

        $selectedGoods = array_column(getSelectedGoods(), 'partNumber');

        foreach ($messageContent as $item) {
            $codes = explode("\n", $item['code']);
            echo "Before processing";
            print_r($codes);

            $codes = array_filter($codes, function ($line) use ($selectedGoods) {
                return $line !== "" && in_array($line, $selectedGoods);
            });
            // Now $codes contains the filtered codes

            echo "After processing";
            print_r($codes);

            if (count($codes) > 0) {
                try {
                    $data = getPrice($codes);
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
            }
        }
    }
}




// Close curl
curl_close($curl);
