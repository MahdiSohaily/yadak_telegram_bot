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
}
