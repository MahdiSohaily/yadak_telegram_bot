<?php
function getPageDetails($page)
{
    switch ($page) {
        case 'index.php':
            return ['title' => "مدیریت تلگرام", 'icon' => './public/img/factor.svg'];
            break;
        case 'incomplete.php':
            return ['title' => "ویرایش پیش فاکتور", 'icon' => './public/img/factor.svg'];
            break;
        case 'complete.php':
            return ['title' => "ویرایش فاکتور", 'icon' => './public/img/factor.svg'];
            break;
        case 'yadakFactor.php':
            return ['title' => "فاکتور یدک شاپ", 'icon' => './public/img/factor.svg'];
            break;
        case 'insuranceFactor.php':
            return ['title' => "فاکتور بیمه", 'icon' => './public/img/factor.svg'];
            break;
        case 'partnerFactor.php':
            return ['title' => "فاکتور همکار", 'icon' => './public/img/factor.svg'];
            break;
        case 'koreaFactor.php':
            return ['title' => "فاکتور اتوپارت", 'icon' => './public/img/factor.svg'];
            break;
        default:
            return ['title' => "مدیریت فاکتور", 'icon' => './public/img/factor.svg'];
            break;
    }
}
