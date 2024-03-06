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
    <div class="bg-white rounded-lg w-1/2 lg:w-1/3 p-5 rtl border-b">
        <div class="flex justify-between">
            <h2 class="font-bold text-xl">افزودن کد جدید</h2>
            <img onclick="toggleModalDisplay()" class="cursor-pointer" src="./public/img/close.svg" alt="close icon">
        </div>
        <div class="py-5">
            <div class="relative">
                <input onkeyup="getPartNumbers(this.value)" type="text" name="partNumber" id="partNumber" placeholder="کد فنی محصول را وارد کنید" class="w-full p-2 border border-gray-300 rounded-md">
                <div id="search_container">
                    <!-- Matched  part numbers will be displayed here -->
                </div>
            </div>
            <div class="flex justify-between items-center">
                <button onclick="addPartNumber()" class="bg-blue-500 text-white text-sm py-2 px-5 rounded-md mt-5">افزودن</button>
                <p id="message" class="text-green-600 text-sm font-bold"></p>
            </div>
        </div>
    </div>
</div>
<script>
    const modal_container = document.getElementById('modal_container');
    const partNumber = document.getElementById('partNumber');
    const search_container = document.getElementById('search_container');
    const message = document.getElementById('message');

    let selectedPartNumber = null;

    function toggleModalDisplay() {
        modal_container.style.display = modal_container.style.display === 'none' ? 'flex' : 'none';
    }

    function addPartNumber() {
        if (selectedPartNumber === null) {
            message.innerHTML = "شما کد فنی ای تا به حال انتخاب نکرده اید.";
            return false;
        }

        var params = new URLSearchParams();
        params.append('addPartNumber', 'addPartNumber');

        params.append('selectedPartNumber', JSON.stringify(selectedPartNumber));

        axios.post("./app/api/partNumberApi.php", params)
            .then(function(response) {
                const data = response.data;
                if (data == 'true') {
                    message.innerHTML = "عملیلت موفقانه صورت گرفت";

                    setTimeout(() => {
                        toggleModalDisplay();
                    }, 2000);
                } else if (data == 'exists') {
                    message.innerHTML = "این کد فنی قبلا انتخاب شده است";
                } else {
                    message.innerHTML = "مشکلی در انجام عملیات پیش آمده است";
                }
            })
            .catch(function(error) {
                console.log(error);
            });
    }

    function getPartNumbers(pattern) {
        if (pattern.length > 6) {
            var params = new URLSearchParams();
            params.append('search', 'search');
            params.append('pattern', pattern);

            axios.post("./app/api/partNumberApi.php", params)
                .then(function(response) {
                    const data = response.data;
                    let template = ``;
                    search_container.innerHTML = template;
                    for (item of data) {
                        template += `
                            <div onclick="selectGood(${item.id}, '${item.partnumber}')" class="p-2 bg-gray-900 text-white mt-1 flex justify-between">
                                <p>${item.partnumber}</p>
                                <img src="./public/img/add.svg" alt="plus icon" class="cursor-pointer" />
                            </div>
                        `;
                    }
                    search_container.innerHTML = template;
                })
                .catch(function(error) {
                    console.log(error);
                });
            selectedPartNumber = null;
            message.innerHTML = "";
        } else {
            selectedPartNumber = null;
            message.innerHTML = "";
        }
    }

    function selectGood(id, partnumber) {
        partNumber.value = partnumber;
        selectedPartNumber = {
            "id": id,
            "partNumber": partnumber
        }
        search_container.innerHTML = '';
        message.innerHTML = partnumber;
    }

    
</script>
<?php require_once './layouts/footer.php';
