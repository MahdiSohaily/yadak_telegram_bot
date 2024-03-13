<?php
require_once './config/constants.php';
require_once './database/connect.php';
require_once './utilities/PriceHelpers.php';
require_once './app/Controllers/MessageController.php';

// function boot()
// {
//     $now = date('Y-m-d H:i:s');
//     echo "\n\n Cron job started ( $now ) \n\n";
//     // API endpoint URL
//     $apiUrl = 'http://telegram.om-dienstleistungen.de/';

//     $postData = [
//         'getMessagesAuto' => 'getMessagesAuto'
//     ];

//     // Initialize curl
//     $curl = curl_init();

//     // Set the curl options
//     curl_setopt_array($curl, [
//         CURLOPT_URL => $apiUrl,
//         CURLOPT_RETURNTRANSFER => true, // Return response as a string instead of outputting it
//         CURLOPT_FOLLOWLOCATION => true, // Follow redirects
//         CURLOPT_MAXREDIRS => 10, // Maximum number of redirects to follow
//         CURLOPT_TIMEOUT => 600, // Timeout in seconds
//         CURLOPT_POST => true, // Set as POST request
//         CURLOPT_POSTFIELDS => http_build_query($postData), // Encode data as URL-encoded format
//         CURLOPT_HTTPHEADER => [ // Optional headers
//             'Content-Type: application/x-www-form-urlencoded',
//         ],
//     ]);

//     // Execute the request
//     $response = curl_exec($curl);

//     echo $response;

//     // Check for errors
//     if (curl_errno($curl)) {
//         $errorMessage = curl_error($curl);
//         // Handle the error
//         echo "cURL error: $errorMessage";
//     } else {
//         // Handle the response` 
//         $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE); // Get HTTP status code
//         if ($statusCode >= 200 && $statusCode < 300) {
//             // Request was successful
//             $response = json_decode($response, true);
//             $response = array_values($response);

//             validateMessages($response);
//         } else {
//             // Request failed
//             echo "Request failed with status code $statusCode";
//             // You can handle different status codes here
//         }
//     }

//     // Close curl
//     curl_close($curl);
// }

function validateMessages($messages)
{
    foreach ($messages as $message) {
        $sender = $message['userName'][0];
        if (!checkIfValidSender($sender)) {
            continue;
        }

        $messageContent = $message['info'];
        $selectedGoods = getSelectedGoods();

        foreach ($messageContent as $item) {
            $rawCodes = explode("\n", $item['code']);

            $rawCodes = array_filter($rawCodes, function ($line) use ($selectedGoods) {
                return $line !== "";
            });

            $codes = array_filter($rawCodes, function ($line) use ($selectedGoods) {
                return $line !== "" && in_array($line, $selectedGoods);
            });
            // Now $codes contains the filtered codes
            if (count($codes) > 0) {
                try {
                    $data = getPrice($codes);
                    print_r(json_encode($data) . "\n");
                    $data = getFinalPrice($data);

                    $template = '';

                    if ($data) {
                        foreach ($data as $item) {
                            if (trim($item['price']) == 'موجود نیست') {
                                continue;
                            }
                            $template .= $item['partnumber'] . ' ' . $item['price'] . "\n";
                        }
                    }

                    echo "\n" . $template . "\n";
                    saveConversation($sender, implode(' ', $codes), $template);
                    // if ($template !== '')
                    //     sendMessageWithTemplate($sender, $template);
                } catch (Exception $error) {
                    echo 'Error fetching price: ' . $error->getMessage();
                }
            } else {
                if (count($rawCodes) > 0) {
                    echo implode(', ', $rawCodes);
                    echo " کد مدنظر اضافه نشده " . "\n";
                }
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
                'partnumber' => $code,
                'price' => $givenPrice[0]['price']
            ];
        } else {
            return false;
        }
    }

    return $displayPrices;
}

// boot();

$response = '{
    "1310670940":
        {"info":[
            {"code":"581013SA26\n","message":"58101-3SA26","date":1710235467}],
        "name":["Azizi -Diakopar"],
        "userName":[1310670940],
        "profile":["1310670940_x_4.jpg"]},
    "104417882":
        {"info":[
            {"code":"577332b000\n","message":"577332b000","date":1710235466}
        ],
        "name":["00 Sam Alamtalab"],
        "userName":[104417882],
        "profile":["104417882_x_4.jpg"]},
    "102364027":
    {"info":[
        {"code":"866873L200\n866813L200\n866823L200\n","message":"86687-3L200\n86681-3L200\n86682-3L200\n\n\u06a9\u0631\u0647 \u06cc\u0627 \u0686\u06cc\u0646","date":1710235464},
        {"code":"866103K110\n","message":"86610-3K110\n\n\n\n\u0633\u0648\u0646\u0627\u062a\u0627 \u06f6\u0633\u06cc\u0644\u0646\u062f\u0631","date":1710235427}],
        "name":["00 \u0645\u0647\u062f\u06cc \u062f\u0627\u0631\u0627\u0628\u06cc \u067e\u0627\u0633\u0627\u0698 \u06a9\u06cc\u0627\u0646","00 \u0645\u0647\u062f\u06cc \u062f\u0627\u0631\u0627\u0628\u06cc \u067e\u0627\u0633\u0627\u0698 \u06a9\u06cc\u0627\u0646"],
        "userName":[102364027,102364027],
        "profile":["102364027_x_4.jpg","102364027_x_4.jpg"]},
        "138595891":
        {"info":[{"code":"281132W100\n","message":"28113-2W100       \u0641\u0642\u0637 \u0627\u0635\u0644\u06cc","date":1710235461}],
        "name":["999 mahdi salamat"],
        "userName":[138595891],
        "profile":["138595891_x_4.jpg"]},
    "32391928":
    {"info":[
        {"code":"986202G500\n","message":"986202G500\n\n\n??????","date":1710235454}],
        "name":["888 amirsalar chobdaran almas part"],
        "userName":[32391928],
        "profile":["32391928_x_4.jpg"]
    }
    }';
$response = json_decode($response, true);
$response = array_values($response);

validateMessages($response);
