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

if (isset($_POST['addContact'])) {
    $name = $_POST['name'];
    $username = $_POST['username'];
    $profile = $_POST['profile'];
    $chat_id = $_POST['chat_id'];
    addContact($name, $username, $chat_id, $profile);
}
function addContact($name, $username, $chat_id, $profile)
{
    $sql = "SELECT COUNT(chat_id) FROM telegram.receiver WHERE chat_id = '$chat_id'";
    $result = CONN->query($sql);
    if (!$result) {
        $addSql = "INSERT INTO telegram.receiver (cat_id, chat_id, name, username, profile) VALUES 
                    ('1', '$chat_id', '$name', '$username', '$profile')";
        $status = CONN->query($addSql);
        if ($status) {
            echo 'true';
        } else {
            echo 'false';
        }
    } else {
        echo 'exist';
    }
}
