<?php

$contacts = null;
$selectedGoods = getSelectedGoods();
$newContacts = null;



function getSelectedGoods()
{
    $sql = "SELECT * FROM telegram.goods_for_sell";
    $result = CONN->query($sql);
    $selectedGoods = mysqli_fetch_all($result, MYSQLI_ASSOC);
    return $selectedGoods;
}
