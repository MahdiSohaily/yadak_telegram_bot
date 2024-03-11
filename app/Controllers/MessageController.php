<?php

$messages = getMessages();

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
