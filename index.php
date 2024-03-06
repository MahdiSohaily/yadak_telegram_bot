<?php
require_once './bootstrap/init.php';
require_once './layouts/header.php';
?>
<style>
    .modal_container {
        position: fixed;
        inset: 0;
        background-color: rgba(0, 0, 0, 0.7);
        z-index: 100000000000000000;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }
</style>
<div class="rtl flex px-5 gap-5 h-screen grid grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-3">
    <section class="p-5 border border-dotted border-2 rounded-md">
        <h2 class="text-xl font-bold "> مخاطبین</h2>
        <table class="w-full mt-3">
            <thead>
                <tr class="bg-gray-900">
                    <th class="text-right py-2 px-3">ردیف</th>
                    <th class="text-right py-2 px-3">اسم</th>
                    <th class="text-right py-2 px-3">نام کاربری</th>
                    <th class="text-right py-2 px-3">دسته بندی</th>
                    <th class="text-right py-2 px-3">نمایه</th>
                    <th class="text-right py-2 px-3">عملیات</th>
                </tr>
            </thead>
            <tbody>
                <tr class="even:bg-gray-200">
                    <td class="py-2 px-3">1</td>
                    <td class="py-2 px-3">name</td>
                    <td class="py-2 px-3">username</td>
                    <td class="py-2 px-3">category</td>
                    <td class="py-2 px-3">profile</td>
                    <td class="py-2 px-3">setting</td>
                </tr>
                <tr class="even:bg-gray-200">
                    <td class="py-2 px-3">1</td>
                    <td class="py-2 px-3">name</td>
                    <td class="py-2 px-3">username</td>
                    <td class="py-2 px-3">category</td>
                    <td class="py-2 px-3">profile</td>
                    <td class="py-2 px-3">setting</td>
                </tr>
                <tr class="even:bg-gray-200">
                    <td class="py-2 px-3">1</td>
                    <td class="py-2 px-3">name</td>
                    <td class="py-2 px-3">username</td>
                    <td class="py-2 px-3">category</td>
                    <td class="py-2 px-3">profile</td>
                    <td class="py-2 px-3">setting</td>
                </tr>
                <tr class="even:bg-gray-200">
                    <td class="py-2 px-3">1</td>
                    <td class="py-2 px-3">name</td>
                    <td class="py-2 px-3">username</td>
                    <td class="py-2 px-3">category</td>
                    <td class="py-2 px-3">profile</td>
                    <td class="py-2 px-3">setting</td>
                </tr>
                <tr class="even:bg-gray-200">
                    <td class="py-2 px-3">1</td>
                    <td class="py-2 px-3">name</td>
                    <td class="py-2 px-3">username</td>
                    <td class="py-2 px-3">category</td>
                    <td class="py-2 px-3">profile</td>
                    <td class="py-2 px-3">setting</td>
                </tr>
            </tbody>
        </table>
    </section>
    <section class="p-5 border border-dotted border-2 rounded-md">
        <div class="flex justify-between">
            <h2 class="text-xl font-bold ">مخاطبین جدید</h2>
            <button class="bg-blue-500 text-sm text-white py-2 px-5 rounded-sm">افزودن همه</button>
        </div>
        <table class="w-full mt-3">
            <thead>
                <tr class="bg-gray-900">
                    <th class="text-right py-2 px-3">ردیف</th>
                    <th class="text-right py-2 px-3">اسم</th>
                    <th class="text-right py-2 px-3">نام کاربری</th>
                    <th class="text-right py-2 px-3">دسته بندی</th>
                    <th class="text-right py-2 px-3">نمایه</th>
                    <th class="text-right py-2 px-3">عملیات</th>
                </tr>
            </thead>
            <tbody>
                <tr class="even:bg-gray-200">
                    <td class="py-2 px-3">1</td>
                    <td class="py-2 px-3">name</td>
                    <td class="py-2 px-3">username</td>
                    <td class="py-2 px-3">category</td>
                    <td class="py-2 px-3">profile</td>
                    <td class="py-2 px-3">setting</td>
                </tr>
                <tr class="even:bg-gray-200">
                    <td class="py-2 px-3">1</td>
                    <td class="py-2 px-3">name</td>
                    <td class="py-2 px-3">username</td>
                    <td class="py-2 px-3">category</td>
                    <td class="py-2 px-3">profile</td>
                    <td class="py-2 px-3">setting</td>
                </tr>
                <tr class="even:bg-gray-200">
                    <td class="py-2 px-3">1</td>
                    <td class="py-2 px-3">name</td>
                    <td class="py-2 px-3">username</td>
                    <td class="py-2 px-3">category</td>
                    <td class="py-2 px-3">profile</td>
                    <td class="py-2 px-3">setting</td>
                </tr>
                <tr class="even:bg-gray-200">
                    <td class="py-2 px-3">1</td>
                    <td class="py-2 px-3">name</td>
                    <td class="py-2 px-3">username</td>
                    <td class="py-2 px-3">category</td>
                    <td class="py-2 px-3">profile</td>
                    <td class="py-2 px-3">setting</td>
                </tr>
                <tr class="even:bg-gray-200">
                    <td class="py-2 px-3">1</td>
                    <td class="py-2 px-3">name</td>
                    <td class="py-2 px-3">username</td>
                    <td class="py-2 px-3">category</td>
                    <td class="py-2 px-3">profile</td>
                    <td class="py-2 px-3">setting</td>
                </tr>
            </tbody>
        </table>
    </section>
    <section class="p-5 border border-dotted border-2 rounded-md">
        <div class="flex justify-between">
            <h2 class="text-xl font-bold "> کد های انتخابی</h2>
            <button onclick="toggleModalDisplay()" class="bg-blue-500 text-sm text-white py-2 px-5 rounded-sm">افزودن کد</button>
        </div>
        <table class="w-full mt-3">
            <thead>
                <tr class="bg-gray-900">
                    <th class="text-right py-2 px-3">1</th>
                    <th class="text-right py-2 px-3">کدفنی</th>
                    <th class="text-right py-2 px-3">تعداد</th>
                    <th class="text-right py-2 px-3">setting</th>
                </tr>
            </thead>
            <tbody>
                <tr class="even:bg-gray-200">
                    <td class="py-2 px-3">1</td>
                    <td class="py-2 px-3">name</td>
                    <td class="py-2 px-3">username</td>
                    <td class="py-2 px-3">setting</td>
                </tr>
                <tr class="even:bg-gray-200">
                    <td class="py-2 px-3">1</td>
                    <td class="py-2 px-3">name</td>
                    <td class="py-2 px-3">profile</td>
                    <td class="py-2 px-3">setting</td>
                </tr>
                <tr class="even:bg-gray-200">
                    <td class="py-2 px-3">1</td>
                    <td class="py-2 px-3">name</td>
                    <td class="py-2 px-3">profile</td>
                    <td class="py-2 px-3">setting</td>
                </tr>
                <tr class="even:bg-gray-200">
                    <td class="py-2 px-3">1</td>
                    <td class="py-2 px-3">name</td>
                    <td class="py-2 px-3">profile</td>
                    <td class="py-2 px-3">setting</td>
                </tr>
                <tr class="even:bg-gray-200">
                    <td class="py-2 px-3">1</td>
                    <td class="py-2 px-3">name</td>
                    <td class="py-2 px-3">profile</td>
                    <td class="py-2 px-3">setting</td>
                </tr>
            </tbody>
        </table>
    </section>
</div>

<!-- MODAL TO ADD NEW PART NUMBER  -->
<div id="modal_container" class="modal_container" style=" display: none;">
    <div class="bg-white rounded-lg w-1/3 p-5 rtl border-b">
        <div class="flex justify-between">
            <h2 class="font-bold text-xl">افزودن کد جدید</h2>
            <img onclick="toggleModalDisplay()" class="cursor-pointer" src="./public/img/close.svg" alt="close icon">
        </div>
        <div class="py-5">
            <form action="#">
                <input type="text" name="partNumber" id="partNumber" placeholder="کد فنی محصول را وارد کنید" class="w-full p-2 border border-gray-300 rounded-md">
            </form>
        </div>
    </div>
</div>
<script>
    const modal_container = document.getElementById('modal_container');

    function toggleModalDisplay() {
        modal_container.style.display = modal_container.style.display === 'none' ? 'flex' : 'none';
    }
</script>
<?php require_once './layouts/footer.php';
