<?php
$contacts = getContacts();
$selectedGoods = getSelectedGoods();
$newContacts = null;
$status = getStatus();



function getSelectedGoods()
{
    $sql = "SELECT * FROM telegram.goods_for_sell";
    $result = CONN->query($sql);
    $selectedGoods = mysqli_fetch_all($result, MYSQLI_ASSOC);
    return $selectedGoods;
}

function getContacts()
{
    $sql = "SELECT * FROM telegram.receiver";
    $result = CONN->query($sql);
    $contacts = mysqli_fetch_all($result, MYSQLI_ASSOC);
    return $contacts;
}

function getStatus()
{
    $sql = "SELECT * FROM telegram.receiver_cat WHERE id = 1";
    $result = CONN->query($sql);
    $status = $result->fetch_assoc();
    $status = $status['status'];
    return $status;
}
