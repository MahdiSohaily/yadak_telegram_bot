<?php

function filterCode($message)
{
    if (empty($message)) {
        return "";
    }

    $codes = explode("\n", $message);

    $filteredCodes = array_map(function ($code) {
        $code = preg_replace('/\[[^\]]*\]/', '', $code);

        $parts = preg_split('/[:,]/', $code, 2);

        if (!empty($parts[1]) && strpos($parts[1], "/") !== false) {
            $parts[1] = explode("/", $parts[1])[0];
        }

        $rightSide = trim(preg_replace('/[^a-zA-Z0-9 ]/', '', $parts[1] ?? ''));

        return $rightSide ? $rightSide : trim(preg_replace('/[^a-zA-Z0-9 ]/', '', $code));
    }, $codes);

    $finalCodes = array_filter($filteredCodes, function ($item) {
        $data = explode(" ", $item);
        return strlen($data[0]) > 4;
    });

    $mappedFinalCodes = array_map(function ($item) {
        $parts = explode(" ", $item);
        if (count($parts) >= 2) {
            $partOne = $parts[0];
            $partTwo = $parts[1];
            if (!preg_match('/[a-zA-Z]{4,}/i', $partOne) && !preg_match('/[a-zA-Z]{4,}/i', $partTwo)) {
                return $partOne . $partTwo;
            }
        }
        return $parts[0];
    }, $finalCodes);

    $nonConsecutiveCodes = array_filter($mappedFinalCodes, function ($item) {
        return !preg_match('/[a-zA-Z]{4,}/i', $item);
    });

    return implode("\n", array_map(function ($item) {
        return explode(" ", $item)[0];
    }, $nonConsecutiveCodes)) . "\n";
}


echo filterCode(
    '
00 Orginal Part Heidari, [3/13/2024 12:45 PM]
23300-2G401

xxx nazari whatsapp, [3/13/2024 12:45 PM]
م

حماد رجبعلیان بازار Hemad Rajabalian, [3/13/2024 12:45 PM]
57724-2w500
Osung
کد خودش

reza sedighian, [3/13/2024 12:46 PM]
خطر رو گلگیر سراتو سایپایی


چپ؟؟؟

xxx nazari whatsapp, [3/13/2024 12:46 PM]
م

IMPERIAL, [3/13/2024 12:46 PM]
289103C100
.
.

..

.؟؟؟

00 امیر نقدی, [3/13/2024 12:46 PM]
92402 3w510




؟

xxx nazari whatsapp, [3/13/2024 12:46 PM]
م

ali vafaii, [3/13/2024 12:46 PM]
922012T630

92202

xxx nazari whatsapp, [3/13/2024 12:46 PM]
م

00 مهدی فرهادی, [3/13/2024 12:46 PM]
4735239300


؟؟

Afshin Afshar, [3/13/2024 12:46 PM]
95440-3V015

Auto Kala @autokalaco, [3/13/2024 12:46 PM]
57150-3K100

Milad, [3/13/2024 12:46 PM]
86350-2W000



??

00 سهند زمانی, [3/13/2024 12:47 PM]
82652
m

Majid, [3/13/2024 12:47 PM]
93555-2P000VA




?????

888 siros heidari starparts, [3/13/2024 12:47 PM]
92191-3E500








؟؟؟؟؟؟؟؟؟؟؟؟؟؟؟؟

Alireza Mardi, [3/13/2024 12:47 PM]
23300-2g401








???

888 davoud kashani, [3/13/2024 12:47 PM]
86822-1M000

888 hosein kermani, [3/13/2024 12:47 PM]
517123K050 
اصلی ؟؟؟؟

888 ali ghasmi maghazs, [3/13/2024 12:47 PM]
M

xxx nazari whatsapp, [3/13/2024 12:47 PM]
م

888 امیرحسین شفاعی, [3/13/2024 12:47 PM]
92403-D4280

mehran mardani, [3/13/2024 12:47 PM]
553001m






Asli

Xxx Vahid Shahram, [3/13/2024 12:47 PM]
86560-2baa0
    '
);
