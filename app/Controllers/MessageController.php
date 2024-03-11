<?php
function getMessages()
{
    $sql = "SELECT messages.*, receiver.name, receiver.username FROM telegram.messages
    INNER JOIN telegram.receiver ON telegram.receiver.chat_id = telegram.messages.receiver";
    $result = CONN->query($sql);
    $allMessages = mysqli_fetch_all($result, MYSQLI_ASSOC);
    return $allMessages;
}

function checkIfValidSender($sender)
{
    $sql = "SELECT * FROM telegram.receiver WHERE chat_id = ?";
    $stmt = CONN->prepare($sql);
    $stmt->bind_param("s", $sender);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    if ($row) {
        return true;
    } else {
        return false;
    }
}

function getSelectedGoods()
{
    $conn = CONN; // Assuming CONN is your database connection object

    // Fetch data from the database
    $sql = "SELECT good_id, partNumber FROM telegram.goods_for_sell";
    $result = $conn->query($sql);

    // Check for errors
    if (!$result) {
        // Handle the error (e.g., log it, return an error message, etc.)
        return [];
    }

    // Fetch all rows as associative arrays
    $selectedGoods = $result->fetch_all(MYSQLI_ASSOC);

    // Extract partNumbers
    $partNumbers = array_column($selectedGoods, 'partNumber');

    // Extract all_ids
    $allIds = array_column($selectedGoods, 'good_id');

    // Iterate over all_ids to find related items
    foreach ($allIds as $id) {
        // Find relation for each id
        $nishaId = findRelation($id);

        // If relation not found, continue to the next id
        if (!$nishaId) {
            continue;
        }

        // Get related items and concatenate their partNumbers
        $relatedPartNumbers = getInRelationItems($nishaId);

        // Check for errors or empty result
        if (!$relatedPartNumbers) {
            continue;
        }

        // Merge the new partNumbers with existing partNumbers
        $partNumbers = array_merge($partNumbers, $relatedPartNumbers);
    }

    return $partNumbers;
}


function getInRelationItems($nisha_id)
{
    // Fetch similar items based on the provided nisha_id
    $sql = "SELECT nisha_id FROM shop.similars WHERE pattern_id = '$nisha_id'";
    $result = CONN->query($sql);
    $goods = mysqli_fetch_all($result, MYSQLI_ASSOC);
    $all_ids = array_column($goods, 'nisha_id');

    if (count($all_ids) == 0) {
        return false;
    }

    // Prepare the list of IDs to use in the IN clause of the next query
    $idList = implode(',', $all_ids);

    // Fetch part numbers of the related items
    $partNumberSQL = "SELECT partnumber FROM yadakshop1402.nisha WHERE id IN ($idList)";
    $partNumberResult = CONN->query($partNumberSQL);
    $partNumbers = mysqli_fetch_all($partNumberResult, MYSQLI_ASSOC);

    return array_column($partNumbers, 'partnumber');
}

function findRelation($id)
{
    // Prepare and execute the SQL query
    $sql = "SELECT pattern_id FROM shop.similars WHERE nisha_id = '$id' LIMIT 1";
    $result = CONN->query($sql);

    // Check if there are any rows returned
    if ($result && $result->num_rows > 0) {
        // Fetch the first row and return the pattern_id
        $row = $result->fetch_assoc();
        return (int) $row['pattern_id']; // Convert to integer and return
    } else {
        // No rows found, return false
        return false;
    }
}


function sendMessageWithTemplate($receiver, $template)
{
    // Prepare POST data
    $postData = http_build_query([
        'sendMessageWithTemplate' => 'sendMessageWithTemplate',
        'receiver' => $receiver,
        'message' => $template
    ]);

    // Initialize cURL session
    $ch = curl_init();

    // Set cURL options
    curl_setopt($ch, CURLOPT_URL, "http://telegram.om-dienstleistungen.de/");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Execute cURL session
    $response = curl_exec($ch);

    // Check for errors
    if (curl_errno($ch)) {
        // Handle cURL error
        $error = curl_error($ch);
        curl_close($ch);
        return "Error: " . $error;
    }

    // Close cURL session
    curl_close($ch);

    // Return response
    return $response;
}














function getPrice($codes)
{
    $completeCode = $codes;
    $finalResult = (setup_loading($codes));

    $explodedCodes = &$finalResult['explodedCodes'];
    $not_exist = &$finalResult['not_exist'];
    $existing = &$finalResult['existing'];
    $completeCode = &$finalResult['completeCode'];
    $relation_ids = &$finalResult['relation_id'];

    foreach ($explodedCodes as $code_index => &$code) {
        if (array_key_exists($code, $existing)) {
            foreach ($existing[$code] as &$item) {
                if ($item['givenPrice'] !== null && count($item['givenPrice']) > 0) {
                    foreach ($item['givenPrice'] as &$price) {
                        $priceDate = $price['created_at'];

                        if (checkDateIfOkay($GLOBALS['applyDate'], $priceDate)) {
                            $rawGivenPrice = $price['price'];
                            $price['price'] = applyDollarRate($rawGivenPrice, $GLOBALS['applyDate']);
                        }
                    }
                    unset($price); // Unset the reference to avoid any unintended modifications outside the loop
                }
            }
            unset($item); // Unset the reference to avoid any unintended modifications outside the loop
        }
    }
    unset($code); // Unset the reference to avoid any unintended modifications outside the loop

    if (!empty($finalResult)) {
        return $finalResult;
    }
}

function setup_loading($completeCode)
{
    $completeCode = array("58101A7A00"); // Example array containing code(s)

    $conn = CONN; // Assuming CONN is your database connection

    foreach ($completeCode as $givenCode) {
        if (is_string($givenCode)) {
            $explodedCodes = explode("\n", $givenCode);
            // Process $explodedCodes as needed
        } else {
            // Handle non-string elements of $completeCode array, if any
        }
    }

    $results_array = [
        'not_exist' => [],
        'existing' => [],
    ];

    $explodedCodes = array_map(function ($code) {
        if (strlen($code) > 0) {
            return  preg_replace('/[^a-z0-9]/i', '', $code);
        }
    }, $explodedCodes);

    $explodedCodes = array_filter($explodedCodes, function ($code) {
        if (strlen($code) > 6) {
            return  $code;
        }
    });

    // Remove duplicate codes from results array
    $explodedCodes = array_unique($explodedCodes);

    $existing_code = []; // this array will hold the id and partNumber of the existing codes in DB
    foreach ($explodedCodes as $code) {
        $sql = "SELECT id, partnumber FROM yadakshop1402.nisha WHERE partnumber LIKE '" . $code . "%'";
        $result = mysqli_query($conn, $sql);

        $all_matched = [];
        if (mysqli_num_rows($result) > 0) {
            while ($item = mysqli_fetch_assoc($result)) {
                array_push($all_matched, $item);
            }

            $existing_code[$code] = $all_matched;
        } else {
            array_push($results_array['not_exist'], $code); //Adding nonexisting codes to the final result array's not_exist index Line NO: 34
        }
    }

    $itemDetails = [];
    $relation_id = [];
    $codeRelationId = [];
    foreach ($explodedCodes as $code) {
        if (!in_array($code, $results_array['not_exist'])) {
            $itemDetails[$code] = [];
            foreach ($existing_code[$code] as $item) {

                // Check every matched good's Id If they have relationship and,
                // avoid operation for items in the same relationship
                $relation_exist = isInRelation($conn, $item['id']);

                if ($relation_exist) {
                    $codeRelationId[$code] =  $relation_exist;
                    if (!in_array($relation_exist, $relation_id)) {

                        array_push($relation_id, $relation_exist); // if a new relation exists -> put it in the result array

                        $itemDetails[$code][$item['partnumber']]['information'] = info($conn, $relation_exist);
                        $itemDetails[$code][$item['partnumber']]['relation'] = relations($conn, $relation_exist, true);
                        $itemDetails[$code][$item['partnumber']]['givenPrice'] = givenPrice($conn, array_keys($itemDetails[$code][$item['partnumber']]['relation']['goods']), $relation_exist);
                    }
                } else {
                    $codeRelationId[$code] =  'not' . rand();
                    $itemDetails[$code][$item['partnumber']]['information'] = info($conn);
                    $itemDetails[$code][$item['partnumber']]['relation'] = relations($conn, $item['partnumber'], false);
                    $itemDetails[$code][$item['partnumber']]['givenPrice'] = givenPrice($conn, array_keys($itemDetails[$code][$item['partnumber']]['relation']['goods']));
                }
            }
        }
    }

    // Custom comparison function to sort inner arrays by values in descending order
    function customSort($a, $b)
    {
        $sumA = array_sum($a['relation']['sorted']); // Calculate the sum of values in $a
        $sumB = array_sum($b['relation']['sorted']); // Calculate the sum of values in $b

        // Compare the sums in descending order
        if ($sumA == $sumB) {
            return 0;
        }
        return ($sumA > $sumB) ? -1 : 1;
    }


    foreach ($itemDetails as &$record) {

        uasort($record, 'customSort'); // Sort the inner array by values
    }

    return ([
        'explodedCodes' => $explodedCodes,
        'not_exist' => $results_array['not_exist'],
        'existing' => $itemDetails,
        'completeCode' => $completeCode,
        'relation_id' => $codeRelationId
    ]);
}

/**
 * @param Connection to the database
 * @return array of rates selected to be used in the goods report table
 */
function getSelectedRates($conn)
{

    $sql = "SELECT amount, status FROM shop.rates WHERE selected = '1' ORDER BY amount ASC";
    $result = mysqli_query($conn, $sql);

    $rates = [];
    if (mysqli_num_rows($result) > 0) {
        while ($item = mysqli_fetch_assoc($result)) {
            array_push($rates, $item);
        }
    }

    return $rates;
}

/**
 * @param Connection to the database
 * @param int $id is the id of the good to check if it has a relationship
 * @return int if the good has a relationship return the id of the relationship
 */
function isInRelation($conn, $id)
{
    $sql = "SELECT pattern_id FROM shop.similars WHERE nisha_id = '$id'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        while ($item = mysqli_fetch_assoc($result)) {
            return $item['pattern_id'];
        }
    }
    return false;
}

/**
 * @param Connection to the database
 * @param int $id is the id of specified good
 * @return int $relation_exist
 * @return array of information about the good
 */
function info($conn, $relation_exist = null)
{
    $info = false;
    $cars = [];
    if ($relation_exist) {

        $sql = "SELECT * FROM shop.patterns WHERE id = '" . $relation_exist . "'";
        $result = mysqli_query($conn, $sql);

        $info = null;
        if (mysqli_num_rows($result) > 0) {
            $info = mysqli_fetch_assoc($result);
        }

        if ($info['status_id'] !== 0) {
            $sql = "SELECT shop.patterns.*, status.name AS  status_name 
                    FROM shop.patterns 
                    INNER JOIN shop.status ON status.id = patterns.status_id 
                    WHERE patterns.id = '" . $relation_exist . "'";

            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
                $info = mysqli_fetch_assoc($result);
            }
        }

        $sql = "SELECT cars.name 
                FROM shop.patterncars 
                INNER JOIN shop.cars ON cars.id = patterncars.car_id 
                WHERE patterncars.pattern_id = '" . $relation_exist . "'";

        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            while ($item = mysqli_fetch_assoc($result)) {
                array_push($cars, $item['name']);
            }
        }
    }

    return $info ? ['relationInfo' => $info, 'cars' => $cars] : false;
}

function relations($conn, $id, $condition)
{
    $relations = [];
    $limit_id = '';

    if ($condition) {

        $sql = "SELECT yadakshop1402.nisha.* 
                FROM yadakshop1402.nisha 
                INNER JOIN shop.similars ON similars.nisha_id = nisha.id 
                WHERE similars.pattern_id = '" . $id . "'";

        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            while ($info = mysqli_fetch_assoc($result)) {
                array_push($relations, $info);
            }
            $limit_id = $id . '-r';
        }
    } else {
        $sql = "SELECT * FROM yadakshop1402.nisha WHERE partnumber = '" . $id . "'";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            while ($info = mysqli_fetch_assoc($result)) {
                array_push($relations, $info);
            }
        }
        $limit_id = end($relations)['id'] . '-s';
    }


    $existing = [];
    $stockInfo = [];
    $sortedGoods = [];

    $unique_goods = [];

    foreach ($relations as $relation) {
        if (!array_key_exists($relation['partnumber'], $unique_goods)) {
            $unique_goods[$relation['partnumber']] = [$relation['id']];
        } else {
            $unique_goods[$relation['partnumber']][count($unique_goods[$relation['partnumber']])] = $relation['id'];
        }
        $sortedGoods[$relation['partnumber']] = $relation;
    }

    foreach ($unique_goods as $key => $relation) {
        $data = exist($conn, $relation);
        $existing[$key] = $data['brands_info'];
        $stockInfo[$key] = $data['stockInfo'];
    }

    arsort($existing);
    $sorted = [];

    $max = 0;
    foreach ($existing as $key => $value) {
        $sorted[$key] = getMax($value);
        $max += $sorted[$key];
    }

    arsort($sorted);

    return ((['goods' => $sortedGoods, 'existing' => $existing, 'sorted' => $sorted, 'stockInfo' => $stockInfo, 'limit_alert' => $limit_id]));
}

function givenPrice($conn, $codes, $relation_exist = null)
{
    $codes = array_filter($codes, function ($item) {
        return strtolower($item);
    });
    $ordared_price = [];


    if ($relation_exist) {
        $out_sql = "SELECT patterns.price, patterns.created_at 
                    FROM shop.patterns WHERE id = '" . $relation_exist . "'";
        $out_result = mysqli_query($conn, $out_sql);

        if (mysqli_num_rows($out_result) > 0) {
            $ordared_price = mysqli_fetch_assoc($out_result);
        }
        $ordared_price['ordered'] = true;
    }

    $givenPrices = [];
    $sql = "SELECT  prices.id, prices.price, prices.partnumber, customer.name, customer.id AS customerID, customer.family, users.id AS userID, prices.created_at
            FROM ((shop.prices 
            INNER JOIN callcenter.customer ON customer.id = prices.customer_id)
            INNER JOIN yadakshop1402.users ON users.id = prices.user_id)
            WHERE partnumber IN ('" . implode("','", $codes) . "')
            ORDER BY created_at DESC LIMIT 7";

    $result = mysqli_query($conn, $sql);
    while ($item = mysqli_fetch_assoc($result))
        array_push($givenPrices, $item);

    $givenPrices = array_filter($givenPrices, function ($item) {

        if ($item !== null && count($item) > 0) {
            return $item;
        }
    });

    $unsortedData = [];
    foreach ($givenPrices as $item) {
        array_push($unsortedData, $item);
    }

    array_push($unsortedData, $ordared_price);

    if ($relation_exist) {
        usort($unsortedData, function ($a, $b) {
            return $a['created_at'] < $b['created_at'];
        });
    }
    $final_data = $relation_exist ? $unsortedData : $givenPrices;

    $filtered_data = array_filter($final_data, function ($item) {
        return is_array($item) && isset($item['price']) && $item['price'] !== '';
    });

    // Re-index the array sequentially starting from 0
    $filtered_data = array_values($filtered_data);

    return [...$filtered_data];
}

function estelam($conn, $code)
{
    $code = strtolower($code);
    $sql = "SELECT * FROM callcenter.estelam 
            INNER JOIN yadakshop1402.seller ON seller.id = estelam.seller 
            WHERE codename LIKE '" . $code . "%' ORDER BY time ASC LIMIT 7;";
    $result = mysqli_query($conn, $sql);


    $estelam = [];
    if (mysqli_num_rows($result) > 0) {
        while ($item = mysqli_fetch_assoc($result)) {
            array_push($estelam, $item);
        }
    }

    return $estelam;
}

function out($conn, $id)
{
    $out_sql = "SELECT qty FROM yadakshop1402.exitrecord WHERE qtyid = '" . $id . "'";
    $out_result = mysqli_query($conn, $out_sql);

    $result = null;
    if (mysqli_num_rows($out_result) > 0) {
        while ($row = mysqli_fetch_assoc($out_result)) {
            $result += $row['qty'];
        }
    }
    return $result;
}

function stockInfo($conn, $id, $brand)
{

    $stockInfo_sql = "SELECT id FROM yadakshop1402.brand WHERE brand.name = '" . $brand . "'";
    $out_result = mysqli_query($conn, $stockInfo_sql);

    $brand_id = null;
    if (mysqli_num_rows($out_result) > 0) {
        $brand_id = mysqli_fetch_assoc($out_result);
    }

    $qtybank_sql = "SELECT qtybank.id, qtybank.qty, seller.name 
                    FROM yadakshop1402.qtybank 
                    INNER JOIN yadakshop1402.seller ON qtybank.seller = seller.id 
                    WHERE codeid = '" . $id . "' AND brand= '" . $brand_id['id'] . "'";
    $qtybank_data = mysqli_query($conn, $qtybank_sql);

    $result = [];

    if (mysqli_num_rows($qtybank_data) > 0) {
        while ($item = mysqli_fetch_assoc($qtybank_data)) {
            array_push($result, $item);
        }
    }

    $existing_record = [];
    $customers = [];
    foreach ($result as $key => $item) {

        $out_data = out($conn, $item['id']);
        $out =  $out_data ? (int) $out_data : 0;

        $item['qty'] = (int)($item['qty']) - $out;

        array_push($existing_record, $item);
        array_push($customers, $item['name']);
    }

    $customers = array_unique($customers);

    $final_result = [];

    foreach ($customers as $customer) {
        $total = 0;
        foreach ($existing_record as $record) {
            if ($customer === $record['name']) {
                $total += $record['qty'];
            }
        }
        $final_result[$customer] = $total;
    }


    return $final_result;
}

function exist($conn, $id)
{

    if (count($id) == 1) {
        $data_sql = "SELECT yadakshop1402.qtybank.id, codeid, brand.name, qty, create_time as invoice_date,seller.name As seller_name
                    FROM (( yadakshop1402.qtybank 
                    INNER JOIN yadakshop1402.brand ON brand.id = qtybank.brand )
                    INNER JOIN yadakshop1402.seller ON seller.id = qtybank.seller)
                    WHERE codeid = '" . current($id) . "'";
    } else {
        $data_sql = "SELECT yadakshop1402.qtybank.id, codeid, brand.name, qty, create_time as invoice_date,seller.name As seller_name
                    FROM (( yadakshop1402.qtybank 
                    INNER JOIN yadakshop1402.brand ON brand.id = qtybank.brand )
                    INNER JOIN yadakshop1402.seller ON seller.id = qtybank.seller)
                    WHERE codeid IN ('" . implode("','", $id) . "')";
    }

    $data_result = mysqli_query($conn, $data_sql);

    $incoming = [];
    if (mysqli_num_rows($data_result) > 0) {
        while ($item = mysqli_fetch_assoc($data_result)) {
            array_push($incoming, $item);
        }
    };

    $brands = [];
    $amount = [];
    $stockInfo = [];

    $modifiedResult = [];

    $incoming = array_map(function ($item) {
        global $conn;
        $out_data = out($conn, $item['id']);
        $out =  $out_data;
        $item['qty'] -= $out;

        if ($item['qty'] !== 0) return $item;
    }, $incoming);

    $incoming = array_filter($incoming, function ($item) {
        if ($item !== null) {
            return $item;
        }
    });

    foreach ($incoming as $item) {
        array_push($brands, $item['name']);
    }

    $brands = array_unique($brands);
    usort($incoming, function ($a, $b) {
        return $b['qty'] - $a['qty'];
    });

    $brands_info = [];
    foreach ($brands as $item) {
        $total = 0;
        foreach ($incoming as $value) {
            if ($item == $value['name']) {
                $total += $value['qty'];
            }
        }

        $brands_info[$item] = $total;
    }

    arsort($brands_info);
    return ['stockInfo' => $incoming, 'brands_info' => $brands_info];
}

function getMax($array)
{
    $max = 0;
    foreach ($array as $k => $v) {
        $max = $max < $v ? $v : $max;
    }
    return $max;
}

function sortArrayByNumericPropertyDescending($array, $property)
{
    usort($array, function ($a, $b) use ($property) {
        return $b->$property - $a->$property;
    });
    return $array;
}

function inventorySpecification($conn, $id, $type)
{
    $sql = '';
    switch ($type) {
        case 'r':
            $sql = "SELECT original, fake FROM shop.good_limit_inventory WHERE pattern_id = '" . $id . "'";
            break;
        case 's':
            $sql = "SELECT original, fake FROM shop.good_limit_inventory WHERE nisha_id = '" . $id . "'";
            break;
    }

    $limit = $conn->query($sql);
    $limit = $limit->fetch_assoc();
    $yadakLimit = !empty($limit) ? $limit : false;

    return $yadakLimit;
}

function overallSpecification($conn, $id, $type)
{
    $sql = '';
    switch ($type) {
        case 'r':
            $sql = "SELECT original AS original_all, fake As fake_all FROM shop.good_limit_all WHERE pattern_id = '" . $id . "'";
            break;
        case 's':
            $sql = "SELECT original AS original_all, fake As fake_all FROM shop.good_limit_all WHERE nisha_id = '" . $id . "'";
            break;
    }
    $limit_all = $conn->query($sql);
    $limit_all = $limit_all->fetch_assoc();
    $allLimit = !empty($limit_all) ? $limit_all : false;
    return $allLimit;
}
