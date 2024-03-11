<?php
function getPageDetails($page)
{
    switch ($page) {
        case 'index.php':
            return ['title' => "مدیریت تلگرام", 'icon' => './public/img/telegram.svg'];
            break;
        default:
            return ['title' => "مدیریت تلگرام", 'icon' => './public/img/telegram.svg'];
            break;
    }
}



