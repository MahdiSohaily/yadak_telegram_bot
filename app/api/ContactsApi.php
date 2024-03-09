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
    $offset = ($page - 1) * 50;
    $sql = "SELECT * FROM telegram.receiver LIMIT 50 OFFSET $offset";
    $result = CONN->query($sql);
    $contacts = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $contacts[] = $row;
        }
    }
    return json_encode($contacts);
}

if (isset($_POST['getPartialsSelectedGoods'])) {
    $page = $_POST['page'];

    header('Content-Type: application/json');
    echo getPartialsSelectedGoods($page);
}

function getPartialsSelectedGoods($page)
{
    $offset = ($page - 1) * 50;
    $sql = "SELECT * FROM telegram.goods_for_sell LIMIT 50 OFFSET $offset";
    $result = CONN->query($sql);
    $goods = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $goods[] = $row;
        }
    }
    return json_encode($goods);
}


if (isset($_POST['saveConversation'])) {
    $receiver = $_POST['receiver'];
    $request = $_POST['request'];
    $response = $_POST['response'];

    header('Content-Type: application/json');
    echo saveConversation($receiver, $request, $response);
}


function saveConversation($receiver, $request, $response) {
    // Prepare the SQL statement
    $sql = "INSERT INTO telegram.messages (receiver, request, response) VALUES (?, ?, ?)";
    
    // Prepare the statement
    $statement = CONN->prepare($sql);
    
    // Bind parameters and execute the statement
    $statement->bind_param("iss", $receiver, $request, $response);
    $statement->execute();
    
    // Check if the insertion was successful
    if ($statement->affected_rows > 0) {
        return true; // Conversation saved successfully
    } else {
        return false; // Failed to save conversation
    }
}
