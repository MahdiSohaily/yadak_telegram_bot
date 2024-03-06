<!DOCTYPE html>
<html lang="fe">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="This is a simple CMS for tracing goods based on thier serail or part number.">
    <meta name="author" content="Mahdi Rezaei">
    <?php
    $page = getPageDetails(basename($_SERVER['PHP_SELF']))
    ?>
    <title id="customer_factor"><?= $page['title'] ?></title>
    <link rel="shortcut icon" href="<?= $page['icon'] ?>">
    <link href="./public/css/material_icons.css?v=<?= rand() ?>" rel="stylesheet">
    <script src="./public/js/index.js?v=<?= rand() ?>"></script>
    <link rel="stylesheet" href="./public/css/index.css?v=<?= rand() ?>">
    <script src="./public/js/axios.js"></script>
    <script src="./public/js/helpers.js?v=<?= rand() ?>"></script>
    <script src="./public/js/jalaliMoment.js?v=<?= rand() ?>"></script>
</head>

<body class="min-h-screen bg-gray-50">
    <nav id="nav" ref="nav" class="main-nav bg-white shadow-lg flex flex-col justify-between">
        <i id="close" onclick="toggleNav()" class="material-icons absolute m-3 left-0 hover:cursor-pointer">close</i>
        <ul class="rtl flex flex-wrap flex-col pt-5 mt-5 ">
            <a class="cursor-pointer inline-flex items-center py-3 pr-6 text-sm font-medium 
                    leading-5 text-gray-500 hover:bg-indigo-500 hover:text-white focus:outline-none
                     transition duration-150 ease-in-out" href="../callcenter/index.php">
                <i class="px-2 material-icons hover:cursor-pointer">account_balance</i>
                صفحه اصلی
            </a>
            <a class="cursor-pointer inline-flex 
                    items-center py-3 pr-6 text-sm font-medium leading-5 
                    text-gray-500 hover:bg-indigo-500 hover:text-white focus:outline-none
                     transition duration-150 ease-in-out" href="../../1402/">
                <i class="px-2 material-icons hover:cursor-pointer">attach_money</i>
                سامانه قیمت
            </a>
            <a class="cursor-pointer inline-flex items-center py-3 
                    pr-6 text-sm font-medium leading-5 text-gray-500 hover:bg-indigo-500 
                    hover:text-white focus:outline-none transition duration-150 ease-in-out" href="../callcenter/cartable-personal.php">
                <i class="px-2 material-icons hover:cursor-pointer">assignment_ind</i>
                کارتابل شخصی
            </a>
            <a class="cursor-pointer inline-flex items-center py-3 pr-6 text-sm 
                    font-medium leading-5 text-gray-500 hover:bg-indigo-500 hover:text-white 
                    focus:outline-none transition duration-150 ease-in-out" href="../callcenter/customer-list.php">
                <i class="px-2 material-icons hover:cursor-pointer">assignment</i>
                لیست مشتریان
            </a>
            <a class="cursor-pointer inline-flex items-center py-3 pr-6 text-sm 
                    font-medium leading-5 text-gray-500 hover:bg-indigo-500 hover:text-white 
                    focus:outline-none transition duration-150 ease-in-out" href="../callcenter/last-calling-time.php">
                <i class="px-2 material-icons hover:cursor-pointer">call_end</i>
                آخرین مکالمات
            </a>

            <a class="cursor-pointer inline-flex items-center py-3 pr-6 text-sm 
                    font-medium leading-5 text-gray-500 hover:bg-indigo-500 hover:text-white 
                    focus:outline-none transition duration-150 ease-in-out" href="../callcenter/report/index.php">
                <i class="px-2 material-icons hover:cursor-pointer">search</i>
                جستجوی اجناس
            </a>
            <a class="cursor-pointer inline-flex items-center py-3 pr-6 text-sm 
                    font-medium leading-5 text-gray-500 hover:bg-indigo-500 hover:text-white 
                    focus:outline-none transition duration-150 ease-in-out" href="../callcenter/report/showGoods.php">
                <i class="px-2 material-icons hover:cursor-pointer">local_mall</i>
                اجناس
            </a>
            <a class="cursor-pointer inline-flex items-center py-3 pr-6 text-sm
                     font-medium leading-5 text-gray-500 hover:bg-indigo-500 hover:text-white 
                     focus:outline-none transition duration-150 ease-in-out" href="../callcenter/report/showRates.php">
                <i class="px-2 material-icons hover:cursor-pointer">show_chart</i>
                نرخ های ارز
            </a>
            <a class="cursor-pointer inline-flex items-center py-3 pr-6 text-sm 
                    font-medium leading-5 text-gray-500 hover:bg-indigo-500 hover:text-white
                     focus:outline-none transition duration-150 ease-in-out" href="../callcenter/report/relationships.php">
                <i class="px-2 material-icons hover:cursor-pointer">sync</i>
                تعریف رابطه اجناس
            </a>
            <a class="cursor-pointer inline-flex items-center py-3 pr-6 text-sm 
                    font-medium leading-5 text-gray-500 hover:bg-indigo-500 hover:text-white
                     focus:outline-none transition duration-150 ease-in-out" href="../callcenter/report/usersManagement.php">
                <i class="px-2 material-icons hover:cursor-pointer">verified_user</i>
                مدیریت کاربران
            </a>
            <a class="cursor-pointer inline-flex items-center py-3 pr-6 text-sm 
                    font-medium leading-5 text-gray-500 hover:bg-indigo-500 hover:text-white
                     focus:outline-none transition duration-150 ease-in-out" href="../callcenter/report/defineExchangeRate.php">
                <i class="px-2 material-icons hover:cursor-pointer">attach_money</i>
                تعریف دلار جدید
            </a>
            <a class="cursor-pointer inline-flex items-center py-3 pr-6 text-sm 
                    font-medium leading-5 text-gray-500 hover:bg-indigo-500 hover:text-white 
                    focus:outline-none transition duration-150 ease-in-out" href="../callcenter/report/price_check.php">
                <i class="px-2 material-icons hover:cursor-pointer">call_end</i>
                بررسی قیمت کدفنی
            </a>
        </ul>
        <!-- Authentication -->
        <a class="rtl cursor-pointer inline-flex items-center py-3 pr-6 text-sm font-medium leading-5 text-gray-500 hover:bg-indigo-500 hover:text-white focus:outline-none transition duration-150 ease-in-out" href="../1402/logout.php">
            <i class="px-2 material-icons hover:cursor-pointer">power_settings_new</i>
            خروج از حساب
        </a>
    </nav>
    <!-- Page Content -->
    <main class="pt-20 min-h-screen">
        <div id="side_nav" class="rtl flex justify-between bg-gray-200 fixed w-full shadow-lg top-0" style="z-index:100">
            <i class="p-2 right-0 material-icons hover:cursor-pointer fixed my-3" onclick="toggleNav()">menu</i>
            <ul class="flex flex-wrap mr-20 py-3">
                <li class="my-2">
                    <a class="px-4 py-2 bg-violet-600 ml-2 rounded-md text-white text-xs" href="../callcenter/cartable.php">
                        <i class="fas fa-layer-group"></i>
                        کارتابل
                    </a>
                </li>
                <li class="my-2">
                    <a class="px-4 py-2 bg-violet-600 ml-2 rounded-md text-white text-xs" href="../callcenter/bazar.php">
                        <i class="fas fa-phone-volume"></i>
                        تماس عمومی
                    </a>
                </li>
                <li class="my-2"><a class="px-4 py-2 bg-violet-600 ml-2 rounded-md text-white text-xs" href="../callcenter/bazar2.php">
                        <i class="fas fa-phone-volume"></i>
                        تماس با بازار
                    </a>
                </li>
                <li class="my-2"><a class="px-4 py-2 bg-violet-600 ml-2 rounded-md text-white text-xs" href="../callcenter/report/hamkarTelegram.php">
                        <i class="fas fa-phone-volume"></i>
                        همکار تلگرام
                    </a>
                </li>
                <li class="my-2">
                    <a class="px-4 py-2 bg-violet-600 ml-2 rounded-md text-white text-xs" href="../callcenter/estelam-list.php">
                        <i class="fas fa-arrow-down"></i>
                        <i class="fas fa-dollar-sign"></i>
                        قیمت های گرفته شده
                    </a>
                </li>
                <li class="my-2">
                    <a class="px-4 py-2 bg-violet-600 ml-2 rounded-md text-white text-xs" href="../callcenter/shomarefaktor.php">
                        <i class="fas fa-feather-alt"></i>
                        شماره فاکتور
                    </a>
                </li>
                <li class="my-2">
                    <a target="_blank" class="px-4 py-2 bg-violet-600 ml-2 rounded-md text-white text-xs" href="../callcenter/report/givePrice.php">
                        <i class="fas fa-feather-alt"></i>
                        قیمت دهی دستوری
                    </a>
                </li>
                <li class="my-2">
                    <a class="px-4 py-2 bg-violet-600 ml-2 rounded-md text-white text-xs" target="_blank" href="../callcenter/report/GivenPriceHistory.php">
                        <i class="fas fa-history"></i>
                        تاریخچه
                    </a>
                </li>
                <li class="my-2">
                    <a class="px-4 py-2 bg-violet-600 ml-2 rounded-md text-white text-xs" target="_blank" href="../callcenter/report/telegramProcess.php">
                        <i class="fas fa-history"></i>
                        تلگرام
                    </a>
                </li>
            </ul>

            <div class="my-2 flex flex-wrap items-top p-2">
                <i onclick="toggleTV()" class="material-icons hover:cursor-pointer text-gray-500">branding_watermark</i>
                <?php
                $profile = '../userimg/default.png';
                if (file_exists("../userimg/" . $_SESSION['id'] . ".jpg")) {
                    $profile = "../userimg/" . $_SESSION['id'] . ".jpg";
                }
                ?>
                <img class="userImage mx-2" src="<?= $profile ?>" alt="userimage">
                <a id="active" class="hidden" href="../callcenter/report/notification.php">
                    <i class="material-icons hover:cursor-pointer notify ">notifications_active</i>
                </a>
                <a id="deactive" class="" href="../callcenter/report/notification.php">
                    <i class="material-icons hover:cursor-pointer text-indigo-500">notifications</i>
                </a>
            </div>
        </div>