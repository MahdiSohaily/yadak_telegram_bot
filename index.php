<?php
require_once './bootstrap/init.php';
require_once './layouts/header.php';
require_once './app/Controllers/TelegramController.php';
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
<div class="rtl flex px-5 gap-5 h-screen grid grid-cols-1 gap-6 lg:grid-cols-5">
    <section class="p-5 border col-span-2 border-dotted border-2 rounded-md">
        <div class="flex justify-between">
            <h2 class="text-xl font-bold ">مخاطبین</h2>
            <div>
                <a href="./messages.php" class="bg-blue-500 text-sm text-white py-2 px-5 rounded-sm">پیام های ارسالی</a>
            </div>
        </div>
        <table class="w-full mt-3">
            <thead>
                <tr class="bg-gray-900">
                    <th class="text-right py-2 px-3 text-sm">#</th>
                    <th class="text-right py-2 px-3 text-sm">اسم</th>
                    <th class="text-right py-2 px-3 text-sm">نام کاربری</th>
                    <th class="text-right py-2 px-3 text-sm">
                        <img src="./public/img/setting.svg" />
                    </th>
                </tr>
            </thead>
            <tbody id="existingContacts">
                <!-- Partial contacts will be appended here -->
            </tbody>
            <tfoot>
                <tr class="py-2">
                    <td class="py-2 px-3" colspan="4">
                        <div class="flex flex-wrap justify-center items-center">
                            <?php
                            $pages = ceil(count($contacts) / 50);
                            if ($pages > 1)
                                for ($page = 1; $page <= $pages; $page++) {
                                    echo "<span class='flex justify-center items-center w-8 p-2 m-1 text-sm cursor-pointer bg-gray-900 text-white' onclick='getPartialContacts($page)'>$page</span>";
                                }
                            ?>
                        </div>
                    </td>
                </tr>
            </tfoot>
        </table>
    </section>
    <section class="p-5 border col-span-2 border-dotted border-2 rounded-md">
        <div class="flex justify-between">
            <h2 class="text-xl font-bold ">مخاطبین جدید</h2>
            <div>
                <button onclick="connect()" class="bg-blue-500 text-sm text-white py-2 px-5 rounded-sm">بارگیری</button>
                <button onclick="addAllContacts()" class="bg-blue-500 text-sm text-white py-2 px-5 rounded-sm">افزودن همه</button>
            </div>
        </div>
        <table class="w-full mt-3">
            <thead>
                <tr class="bg-gray-900">
                    <th class="text-right py-2 px-3 text-sm">#</th>
                    <th class="text-right py-2 px-3 text-sm">اسم</th>
                    <th class="text-right py-2 px-3 text-sm">نام کاربری</th>
                    <th class="text-right py-2 px-3 text-sm">
                        <img src="./public/img/setting.svg" />
                    </th>
                </tr>
            </thead>
            <tbody id="newContacts">
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
                    <th class="text-right py-2 px-3 text-sm">ردیف</th>
                    <th class="text-right py-2 px-3 text-sm">کدفنی</th>
                    <th class="text-right py-2 px-3 text-sm">
                        <img src="./public/img/setting.svg" alt="setting icon" />
                    </th>
                </tr>
            </thead>
            <tbody id="partialSelectedGoods">
                <!-- Partial selected Goods will be placed here -->
            </tbody>
            <tfoot>
                <tr class="py-2">
                    <td class="py-2 px-3" colspan="4">
                        <div class="flex flex-wrap justify-center items-center">
                            <?php
                            $pages = ceil(count($selectedGoods) / 50);
                            if ($pages > 1)
                                for ($page = 1; $page <= $pages; $page++) {
                                    echo "<span class='flex justify-center items-center w-8 p-2 m-1 text-sm cursor-pointer bg-gray-900 text-white' onclick='getPartialContacts($page)'>$page</span>";
                                }
                            ?>
                        </div>
                    </td>
                </tr>

            </tfoot>
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
    const existingContacts = <?= json_encode(array_column($contacts, 'chat_id')); ?>;
    const definedCodes = <?= json_encode(array_column($selectedGoods, 'partNumber')); ?>;

    const modal_container = document.getElementById('modal_container');
    const partNumber = document.getElementById('partNumber');
    const search_container = document.getElementById('search_container');
    const message = document.getElementById('message');

    let NewContacts = [];
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
                        window.location.reload();
                    }, 1000);
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

    function deleteGood(id) {
        var params = new URLSearchParams();
        params.append('deleteGood', 'deleteGood');
        params.append('id', id);

        axios.post("./app/api/partNumberApi.php", params)
            .then(function(response) {
                const data = response.data;
                if (data == 'true') {
                    window.location.reload();
                }
            })
            .catch(function(error) {
                console.log(error);
            });
    }

    function deleteContact(id) {
        var params = new URLSearchParams();
        params.append('deleteContact', 'deleteContact');
        params.append('id', id);

        axios.post("./app/api/ContactsApi.php", params)
            .then(function(response) {
                const data = response.data;
                if (data == true) {
                    window.location.reload();
                }
            })
            .catch(function(error) {
                console.log(error);
            });
    }

    function addContact(name, username, chat_id, profile) {
        var params = new URLSearchParams();
        params.append('addContact', 'addContact');
        params.append('name', name);
        params.append('username', username);
        params.append('chat_id', chat_id);
        params.append('profile', profile);

        axios.post("./app/api/ContactsApi.php", params)
            .then(function(response) {
                const data = response.data;
                if (data == 'exist') {
                    alert('مخاطب از قبل در سیستم موجود است.');
                } else if (data == true) {
                    window.location.reload();
                } else {
                    alert('مشکلی  در هنگام اضافه کردن مخاطب رخ داده است.');
                }
            })
            .catch(function(error) {
                console.log(error);
            });

    }

    function addAllContacts() {
        var params = new URLSearchParams();
        params.append('addAllContact', 'addAllContact');
        params.append('contacts', JSON.stringify(NewContacts));

        if (NewContacts.length > 0) {
            axios.post("./app/api/ContactsApi.php", params)
                .then(function(response) {
                    const data = response.data;
                    if (data == 'exist') {
                        alert('مخاطب از قبل در سیستم موجود است.');
                    } else if (data == true) {
                        window.location.reload();
                    } else {
                        alert('مشکلی  در هنگام اضافه کردن مخاطب رخ داده است.');
                    }
                })
                .catch(function(error) {
                    console.log(error);
                });
        }
    }

    function connect() {
        var params = new URLSearchParams();
        params.append('getContacts', 'getContacts');
        const container = document.getElementById('newContacts');
        container.innerHTML = `
                        <tr class="even:bg-gray-200">
                            <td class="py-5 px-3 text-sm text-center" colspan="4">
                                <img class="w-12 h-12 mx-auto" src="./public/img/loading.png" />
                                <br />
                                <p class="text-sm">لطفا صبور باشید</p>
                            </td>
                        </tr>`;

        axios
            .post("http://telegram.om-dienstleistungen.de/", params)
            .then(function(response) {
                const contacts = response.data;
                if (contacts.length > 0) {
                    let template = ``;
                    let counter = 1;
                    for (contact of contacts) {
                        if (!existingContacts.includes(contact.id + "") && contact.type == "user") {
                            NewContacts.push(contact);
                            const firstName = contact.first_name ?? '';
                            const lastName = contact.last_name ?? '';

                            const clientName = firstName + " " + lastName;
                            template += `
                        <tr class="even:bg-gray-200 odd:bg-white">
                            <td class="py-2 px-3 text-sm">${counter}</td>
                            <td class="py-2 px-3 text-sm">${clientName ?? ''}</td>
                            <td class="py-2 px-3 text-sm">${contact.username ?? ''}</td>
                            <td class="py-2 px-3 text-sm cursor-pointer" 
                                onclick="addContact(
                                    '${clientName ?? ''}',
                                    '${contact.username ?? ''}',
                                    '${contact.id ?? ''}',
                                    'rezaei.jpeg'
                                )">
                                <img src="./public/img/add.svg" alt="plus icon">
                            </td>
                        </tr>`;
                            counter++;
                        } else {
                            container.innerHTML = `
                                <tr class="even:bg-gray-200">
                                    <td class="py-5 px-3 text-sm text-center" colspan="4">
                                        <p class="text-sm">هیچ مخاطب جدیدی یافت نشد</p>
                                    </td>
                                </tr>`;
                        }
                    }
                    container.innerHTML = template;
                }
            })
            .catch(function(error) {
                console.log(error);
            });
    }

    function getPartialContacts(page = 1) {
        const existingContacts = document.getElementById('existingContacts');
        var params = new URLSearchParams();
        params.append('getPartialContacts', 'getPartialContacts');
        params.append('page', page);

        axios
            .post("./app/api/ContactsApi.php", params)
            .then(function(response) {
                const contacts = response.data;
                if (contacts.length > 0) {
                    let template = ``;
                    let counter = null;

                    if (page == 1) {
                        counter = 1;
                    } else {
                        counter = (Number(page) - 1) * 50 + 1;
                    }
                    for (contact of contacts) {
                        template += `
                        <tr class="even:bg-gray-200 odd:bg-white">
                            <td class="py-2 px-3 text-sm">${counter}</td>
                            <td class="py-2 px-3 text-sm">${contact.name}</td>
                            <td class="py-2 px-3 text-sm">${contact.username}</td>
                            <td class="py-2 px-3 text-sm cursor-pointer" 
                                onclick="deleteContact('${contact.id}')">
                                <img src="./public/img/del.svg" alt="plus icon">
                            </td>
                        </tr>`;
                        counter++;
                    }
                    existingContacts.innerHTML = template;
                }
            })
            .catch(function(error) {
                console.log(error);
            });
    }

    function getPartialsSelectedGoods(page = 1) {
        const partialSelectedGoods = document.getElementById('partialSelectedGoods');

        var params = new URLSearchParams();
        params.append('getPartialsSelectedGoods', 'getPartialsSelectedGoods');
        params.append('page', page);

        axios
            .post("./app/api/ContactsApi.php", params)
            .then(function(response) {
                const goods = response.data;
                if (goods.length > 0) {
                    let template = ``;
                    let counter = null;

                    if (page == 1) {
                        counter = 1;
                    } else {
                        counter = (Number(page) - 1) * 50 + 1;
                    }
                    for (good of goods) {
                        template += `
                        <tr class="even:bg-gray-200 odd:bg-white">
                            <td class="py-2 px-3 text-sm">${counter}</td>
                            <td class="py-2 px-3 text-sm">${good.partNumber}</td>
                            <td class="py-2 px-3 text-sm cursor-pointer" 
                                onclick="deleteGood('${good.id}')">
                                <img src="./public/img/del.svg" alt="plus icon">
                            </td>
                        </tr>`;
                        counter++;
                    }
                    partialSelectedGoods.innerHTML = template;
                }
            })
            .catch(function(error) {
                console.log(error);
            });
    }

    getPartialContacts();
    getPartialsSelectedGoods();
</script>
<?php require_once './layouts/footer.php';
