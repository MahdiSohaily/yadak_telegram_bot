<?php
require_once '../../config/constants.php';
require_once '../../database/connect.php';
require_once '../Middlewares/AuthMiddleware.php';

if (isset($_POST['deleteContact'])) {
    $id = $_POST['id'];
    echo deleteContact($id);
}

function deleteContact($id)
{
    $sql = "DELETE FROM telegram.receiver WHERE id = $id";
    $result = CONN->query($sql);
    if ($result) {
        return true;
    } else {
        return false;
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
    $sql = "SELECT COUNT(chat_id) AS total FROM telegram.receiver WHERE chat_id = '$chat_id'";
    $result = CONN->query($sql);
    $result = $result->fetch_assoc()['total'];

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

if (isset($_POST['addAllContact'])) {

    $contacts = json_decode($_POST['contacts']);

    foreach ($contacts as $contact) {
        addAllContacts($contact);
    }

    echo true;
}

function addAllContacts($contact)
{
    $chat_id = $contact->id;
    $name = $contact->first_name;
    $username = $contact->username ?? '';
    $profile = '$contact->profile';

    $sql = "SELECT COUNT(chat_id) AS total FROM telegram.receiver WHERE chat_id = '$chat_id'";
    $result = CONN->query($sql);
    $result = $result->fetch_assoc()['total'];

    if (!$result) {
        $addSql = "INSERT INTO telegram.receiver (cat_id, chat_id, name, username, profile) VALUES 
                    ('1', '$chat_id', '$name', '$username', '$profile')";
        $status = CONN->query($addSql);
        if ($status) {
            return true;
        } else {
            return false;
        }
    }
}

if (isset($_POST['getPartialContacts'])) {
    $page = $_POST['page'];

    header('Content-Type: application/json');
    echo getPartialContacts($page);
}

function getPartialContacts($page)
{
    $sql = "SELECT * FROM telegram.receiver LIMIT 50 OFFSET $page";
    $result = CONN->query($sql);
    $contacts = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $contacts[] = $row;
        }
    }
    return json_encode($contacts);
}
