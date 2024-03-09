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
