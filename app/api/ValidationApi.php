<?php
require_once '../../config/constants.php';
require_once '../../database/connect.php';
require_once '../Middlewares/AuthMiddleware.php';



if (isset($_POST['checkGood'])) {
    $partnumber = $_POST['partnumber'];
    echo checkGoodPartNumber($partnumber);;
}


function checkGoodPartNumber($partnumber)
{
    // Use prepared statements to prevent SQL injection
    $sql = "SELECT COUNT(partNumber) AS total FROM telegram.goods_for_sell WHERE partNumber LIKE ?";
    $stmt = CONN->prepare($sql);

    // Bind parameter and execute statement
    $partnumberParam = '%' . $partnumber . '%';
    $stmt->bind_param("s", $partnumberParam);
    $stmt->execute();

    // Get result
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $total = $row['total'];

    // Check if partnumber exists
    if ($total > 0) {
        return true;
    } else {
        return false;
    }
}
