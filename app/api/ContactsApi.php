<?php
require_once '../../config/constants.php';
require_once '../../database/connect.php';
require_once '../Middlewares/AuthMiddleware.php';

if (isset($_POST['deleteContact'])) {
    $id = $_POST['id'];
    $sql = "DELETE FROM telegram.receiver WHERE id = $id";
    $result = CONN->query($sql);
    if ($result) {
        echo 'true';
    } else {
        echo 'false';
    }
}
