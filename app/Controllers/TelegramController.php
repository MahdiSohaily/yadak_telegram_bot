<?php

$contacts = getContacts();
$selectedGoods = getSelectedGoods();
$newContacts = null;



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
