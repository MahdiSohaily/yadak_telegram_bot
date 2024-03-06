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
