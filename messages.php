<?php
require_once './bootstrap/init.php';
require_once './layouts/header.php';
require_once './app/Controllers/MessageController.php';
?>
<div class="rtl flex px-5 gap-5 h-screen flex justify-center">
    <section class="p-5 border container lg:w-1/2 border-dotted border-2 rounded-md">
        <h2 class="text-xl font-bold ">پیام های ارسال شده</h2>
        <div id="messages_container">
            <?php foreach ($messages as $messages) ?>
            <div class="flex justify-end">
                <div class="request rounded-md bg-green-100 inline-block  w-80">
                    <p class="text-md font-bold p-3 border-b border-dashed border-green-300">مهدی رضایی</p>
                    <p class="p-3">Lorem ipsum dolor sit, amet consectetur adipisicing elit.
                        Aliquid maiores maxime nobis non. Iure rem dolorum voluptatem?
                        Iste, dignissimos adipisci impedit ad possimus eveniet! Consectetur
                        amet eum perferendis facilis error.</p>
                </div>
            </div>
            <div>
                <div class="response rounded-md bg-blue-100 inline-block p-3 w-80">
                    <p class="text-md font-bold">ربات</p>
                    <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit.
                        Aliquid maiores maxime nobis non. Iure rem dolorum voluptatem?
                        Iste, dignissimos adipisci impedit ad possimus eveniet! Consectetur
                        amet eum perferendis facilis error.</p>
                </div>
            </div>
        </div>
    </section>
</div>