<?php
require_once '../../config/constants.php';
require_once '../../database/connect.php';
require_once '../Middlewares/AuthMiddleware.php';


if (isset($_POST['search'])) {
    $pattern = $_POST['pattern'];
    // Allow requests from any origin
    header("Access-Control-Allow-Origin: *");

    // Allow specified HTTP methods
    header("Access-Control-Allow-Methods:POST");

    // Allow specified headers
    header("Access-Control-Allow-Headers: Content-Type");

    // Allow credentials (cookies, authorization headers, etc.)
    header("Access-Control-Allow-Credentials: true");

    // Set content type to JSON
    header("Content-Type: application/json"); // Allow requests from any origin

    echo json_encode(getMatchedPartNumbers($pattern));
}

function getMatchedPartNumbers($search)
{
    $sql = "SELECT * FROM yadakshop1402.nisha WHERE partnumber LIKE '%$search%'";
    $result = CONN->query($sql);
    $partNumbers = mysqli_fetch_all($result, MYSQLI_ASSOC);
    return $partNumbers;
}


if (isset($_POST['addPartNumber'])) {
    $addPartNumber = $_POST['addPartNumber'];
    $selectedPartNumber = json_decode($_POST['selectedPartNumber']);

    // Set content type to JSON
    header("Content-Type: application/json"); // Allow requests from any origin
    echo json_encode(addGoodsForSell($selectedPartNumber));
}


function addGoodsForSell($good)
{
    $count = 0;
    // Check if good_id already exists
    $check_sql = "SELECT COUNT(*) FROM goods_for_sell WHERE good_id = ?";
    $check_stmt = CONN->prepare($check_sql);
    $check_stmt->bind_param('i', $good->id);
    $check_stmt->execute();
    $check_stmt->bind_result($count);
    $check_stmt->fetch();
    $check_stmt->close();

    if ($count > 0) {
        return "exists";
    }
    $nishaId = findRelation($good->id);

    if (!$nishaId) {
        // If good_id does not exist, proceed with insertion
        $sql = "INSERT INTO goods_for_sell (good_id, partNumber) VALUES (?, ?)";
        $stmt = CONN->prepare($sql);
        $stmt->bind_param('is', $good->id, $good->partNumber);

        // Execute the prepared statement
        if ($stmt->execute()) {
            return 'true'; // Insertion successful
        } else {
            return 'false'; // Insertion failed
        }
    } else {
        $relatedItems = getInRelationItems($nishaId);
        if ($relatedItems) {
            foreach ($relatedItems as $item) {
                var_dump($item);
                $sql = "INSERT INTO goods_for_sell (good_id, partNumber) VALUES (?, ?)";
                $stmt = CONN->prepare($sql);
                $stmt->bind_param('ss', $item['id'], $item['partnumber']);
                $stmt->execute();
            }
            return 'true'; // Insertion successful
        }
    }
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
    $partNumberSQL = "SELECT id, partnumber FROM yadakshop1402.nisha WHERE id IN ($idList)";
    $partNumberResult = CONN->query($partNumberSQL);
    $partNumbers = mysqli_fetch_all($partNumberResult, MYSQLI_ASSOC);

    return ($partNumbers);
}

if (isset($_POST['deleteGood'])) {
    $deleteGood = $_POST['deleteGood'];
    $id = $_POST['id'];

    // Set content type to JSON
    header("Content-Type: application/json"); // Allow requests from any origin
    echo json_encode(deleteGoodsForSell($id));
}

function deleteGoodsForSell($id)
{
    $sql = "DELETE FROM goods_for_sell WHERE id = ?";
    $stmt = CONN->prepare($sql);
    $stmt->bind_param('i', $id);

    // Execute the prepared statement
    if ($stmt->execute()) {
        return 'true'; // Deletion successful
    } else {
        return 'false'; // Deletion failed
    }
}
