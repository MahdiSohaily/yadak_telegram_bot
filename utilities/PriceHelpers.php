<?php
$appliedRate = 0;
$applyDate = null;
$additionRate = null;

$applyDateSmall = null;
$additionRateSmall = null;

$rateSpecification  = getDollarRateInfo();

if ($rateSpecification) {
    $applyDate = $rateSpecification[0]['created_at'];
    $additionRate = $rateSpecification[0]['rate'];

    $applyDateSmall = $rateSpecification[1]['created_at'];
    $additionRateSmall = $rateSpecification[1]['rate'];
}

function getDollarRateInfo()
{
    $statement = "SELECT rate, created_at FROM shop.dollarrate WHERE status = 1 ORDER BY created_at DESC LIMIT 2";
    $result = CONN->query($statement);
    $rate = [];

    while ($row = $result->fetch_assoc()) {
        $rate[] = $row;
    }
    return $rate;
}

function filterCode($message)
{
    if (empty($message)) {
        return '';
    }

    $codes = explode("\n", $message);

    $filteredCodes = array_map(function ($code) {
        $code = preg_replace('/\[[^\]]*\]/', '', $code);
        $parts = preg_split('/[:,]/', $code, 2);
        $rightSide = trim(preg_replace('/[^a-zA-Z0-9 ]/', '', $parts[1] ?? ''));
        return !empty($rightSide) ? $rightSide : trim(preg_replace('/[^a-zA-Z0-9 ]/', '', $code));
    }, array_filter($codes, 'trim'));

    $finalCodes = array_filter($filteredCodes, function ($item) {
        $data = explode(" ", $item);
        if (strlen($data[0]) > 4) {
            return $item;
        }
    });

    $finalCodes = array_map(function ($item) {
        $item = explode(' ', $item);
        if (count($item) >= 2) {
            $partOne = $item[0];
            $partTwo = $item[1];
            if (!preg_match('/[a-zA-Z]{4,}/i', $partOne) && !preg_match('/[a-zA-Z]{4,}/i', $partTwo)) {
                return $partOne . $partTwo;
            }
        }
        return $item[0];
    }, $finalCodes);

    $finalCodes = array_filter($finalCodes, function ($item) {
        $consecutiveChars = preg_match('/[a-zA-Z]{4,}/i', $item);
        return !$consecutiveChars;
    });

    return implode("\n", array_map(function ($item) {
        return explode(' ', $item)[0];
    }, $finalCodes)) . "\n";
}

function displayTimePassed($datetimeString)
{
    if ($datetimeString) {

        $now = new DateTime(); // current date time
        $date_time = new DateTime($datetimeString); // date time from string
        $interval = $now->diff($date_time); // difference between two date times

        $years = $interval->format('%y'); // difference in years
        $months = $interval->format('%m'); // difference in months
        $days = $interval->format('%d'); // difference in days

        $text = '';

        if ($years) {
            $text .= "$years سال ";
        }

        if ($months) {
            $text .= "$months ماه ";
        }

        if ($days) {
            $text .= " $days روز ";
        }

        if (empty($text)) {
            return "امروز"; // If the difference is less than a month
        }

        return $text . "قبل";
    }

    return 'تاریخ ورود موجود نیست';
}

function convertToPersian($number)
{
    $persianDigits = array('۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹');
    $persianNumber = '';

    while ($number > 0) {
        $digit = $number % 10;
        $persianNumber = $persianDigits[$digit] . $persianNumber;
        $number = (int)($number / 10);
    }

    return $persianNumber;
}

function applyDollarRate($price, $priceDate)
{
    $priceDate = date('Y-m-d', strtotime($priceDate));
    $rate = 0;

    if ($priceDate <= $GLOBALS['applyDate'] && $priceDate >= $GLOBALS['applyDateSmall']) {
        $rate = $GLOBALS['additionRate'];
    } elseif ($priceDate < $GLOBALS['applyDateSmall']) {
        $rate = $GLOBALS['additionRateSmall'];
    }

    $GLOBALS['appliedRate'] = $rate;
    // Split the input string into words using space as the delimiter
    $words = explode(' ', $price);

    // Iterate through the words and modify numbers with optional forward slashes
    foreach ($words as &$word) {
        // Define a regular expression pattern to match numbers with optional forward slashes
        $pattern = '/(\d+(?:\/\d+)?)/';

        // Check if the word matches the pattern
        if (preg_match($pattern, $word)) {
            // Extract the matched number, removing any forward slashes
            $number = preg_replace('/\//', '', $word);


            if (ctype_digit($number)) {
                // Increase the matched number by 2%
                $modifiedNumber = $number + (($number * $rate) / 100);

                if ($modifiedNumber >= 10) {
                    // Round the number to the nearest multiple of 10
                    $roundedNumber = ceil($modifiedNumber / 10) * 10;
                } else {
                    $roundedNumber = round($modifiedNumber);
                }

                // Replace the word with the modified number
                $word = str_replace($number, $roundedNumber, $word);
            }
        }
    }
    // Reconstruct the modified string by joining the words with spaces
    $modifiedString = implode(' ', $words);

    return $modifiedString;
}

function checkDateIfOkay($applyDate, $priceDate)
{
    $priceDate = date('Y-m-d', strtotime($priceDate));

    if ($priceDate <= $GLOBALS['applyDate'] && $priceDate >= $GLOBALS['applyDateSmall']) {
        return true;
    } elseif ($priceDate < $GLOBALS['applyDateSmall']) {
        return true;
    }

    return false;
}

function is_registered($partNumber, $connection)
{
    // Prepare the SQL statement
    $sql = "SELECT * FROM telegram.goods_for_sell WHERE partNumber = ?";

    // Prepare the statement
    $statement = $connection->prepare($sql);

    // Bind parameters and execute the statement
    $statement->bind_param("s", $partNumber);
    $statement->execute();

    // Store result
    $result = $statement->get_result();

    // Check if any rows were returned
    return $result->num_rows > 0;
}
