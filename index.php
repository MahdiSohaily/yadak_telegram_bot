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
<div class="rtl flex px-5 gap-5 h-screen grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-5">
    <section class="p-5 border col-span-2 border-dotted border-2 rounded-md">
        <h2 class="text-xl font-bold "> مخاطبین</h2>
        <table class="w-full mt-3">
            <thead>
                <tr class="bg-gray-900">
                    <th class="text-right py-2 px-3 text-sm">ردیف</th>
                    <th class="text-right py-2 px-3 text-sm">اسم</th>
                    <th class="text-right py-2 px-3 text-sm">نام کاربری</th>
                    <th class="text-right py-2 px-3 text-sm">دسته بندی</th>
                    <th class="text-right py-2 px-3 text-sm">عملیات</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($contacts !== null && count($contacts) > 0) :
                    foreach ($contacts as $key => $item) : ?>
                        <tr class="even:bg-gray-200">
                            <td class="py-2 px-3"><?= $key + 1 ?></td>
                            <td class="py-2 px-3"><?= $item['name'] ?></td>
                            <td class="py-2 px-3"><?= $item['username'] ?></td>
                            <td class="py-2 px-3">category</td>
                            <td class="py-2 px-3 cursor-pointer" onclick="deleteContact('<?= $item['id'] ?>')">
                                <img src="./public/img/del.svg" alt="delete icon">
                            </td>
                        </tr>
                    <?php endforeach;
                else : ?>
                    <tr class="bg-rose-400 ">
                        <td class="py-2 px-3 text-white text-center" colspan="5">موردی برای نمایش وجود ندارد.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </section>
    <section class="p-5 border col-span-2 border-dotted border-2 rounded-md">
        <div class="flex justify-between">
            <h2 class="text-xl font-bold ">مخاطبین جدید</h2>
            <button onclick="addAllContacts()" class="bg-blue-500 text-sm text-white py-2 px-5 rounded-sm">افزودن همه</button>
        </div>
        <table class="w-full mt-3">
            <thead>
                <tr class="bg-gray-900">
                    <th class="text-right py-2 px-3 text-sm">ردیف</th>
                    <th class="text-right py-2 px-3 text-sm">اسم</th>
                    <th class="text-right py-2 px-3 text-sm">نام کاربری</th>
                    <th class="text-right py-2 px-3 text-sm">دسته بندی</th>
                    <th class="text-right py-2 px-3 text-sm">عملیات</th>
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
                    <th class="text-right py-2 px-3">ردیف</th>
                    <th class="text-right py-2 px-3">کدفنی</th>
                    <th class="text-right py-2 px-3">
                        <img src="./public/img/setting.svg" alt="setting icon" />
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($selectedGoods !== null && count($selectedGoods) > 0) :
                    foreach ($selectedGoods as $key => $item) : ?>
                        <tr class="even:bg-gray-200">
                            <td class="py-2 px-3"><?= $key + 1 ?></td>
                            <td class="py-2 px-3"><?= $item['partNumber'] ?></td>
                            <td class="py-2 px-3" onclick="deleteGood('<?= $item['id'] ?>')">
                                <img src="./public/img/del.svg" alt="delete icon" class="cursor-pointer" />
                            </td>
                        </tr>
                    <?php endforeach;
                else : ?>
                    <tr>
                        <td class="py-2 px-3 bg-rose-400 text-white text-center" colspan="3">موردی برای نمایش وجود ندارد</td>
                    </tr>
                <?php endif; ?>
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
    const existingContacts = <?= json_encode(array_column($contacts, 'chat_id')); ?>;
    const modal_container = document.getElementById('modal_container');
    const partNumber = document.getElementById('partNumber');
    const search_container = document.getElementById('search_container');
    const message = document.getElementById('message');

    let NewContacts = [{
            "id": 260351262,
            "type": "bot",
            "first_name": "تِله پرو | هوش مصنوعی گروه",
            "username": "TLProBot",
            "verified": false,
            "restricted": false,
            "access_hash": 3196894755595393500,
            "bot_nochats": false
        },
        {
            "id": 5549797218,
            "type": "user",
            "first_name": "admin",
            "last_name": "admin",
            "username": "aaadddmmmiinnn",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 6625906565215299000,
            "bot_nochats": false
        },
        {
            "id": 84502638,
            "type": "user",
            "first_name": "XXX Mohsen Ahmadi",
            "username": "HKparts20",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 7764717535922488000,
            "phone": "989124384722",
            "bot_nochats": false
        },
        {
            "id": 1909650544,
            "type": "user",
            "first_name": "mehran",
            "last_name": "mardani",
            "username": "mardaaaan",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 1707814536854456600,
            "phone": "989125958611",
            "bot_nochats": false
        },
        {
            "id": 98632830,
            "type": "user",
            "first_name": "XXX Mohsen",
            "last_name": "Khalili",
            "username": "Mosiii62",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -5172743929422422000,
            "phone": "989123756884",
            "bot_nochats": false
        },
        {
            "id": 110659793,
            "type": "user",
            "first_name": "Xxx",
            "last_name": "Emad",
            "username": "Emaddehsarayi",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -7748865615744558000,
            "phone": "989128882716",
            "bot_nochats": false
        },
        {
            "id": 405403994,
            "type": "user",
            "first_name": "00 Amir",
            "last_name": "Bakhtiari",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 4244941529294015500,
            "phone": "989196025741",
            "bot_nochats": false
        },
        {
            "id": 98199194,
            "type": "user",
            "first_name": "XXX Saeed Kia Yadak",
            "username": "yadakkia",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -8371627747124907000,
            "phone": "989127231384",
            "bot_nochats": false
        },
        {
            "id": 555135705,
            "type": "user",
            "first_name": "Private",
            "last_name": "Sardar",
            "username": "part_star",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 4383896638161524700,
            "phone": "989381067335",
            "bot_nochats": false
        },
        {
            "id": 104949184,
            "type": "user",
            "first_name": "Amin",
            "last_name": "Berenji",
            "username": "ammmin",
            "verified": false,
            "restricted": false,
            "access_hash": -3428513308641874400,
            "bot_nochats": false
        },
        {
            "id": 748713344,
            "type": "user",
            "first_name": "CheraghBargh",
            "last_name": "ADMIN",
            "username": "CheraghBarghAdmin",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 6492978197765966000,
            "phone": "989305533681",
            "bot_nochats": false
        },
        {
            "id": 169785118,
            "type": "user",
            "first_name": "Niyayesh",
            "last_name": "Rahimi",
            "username": "Niyayesh96",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -5358933001506705000,
            "phone": "989123612779",
            "bot_nochats": false
        },
        {
            "id": 73713372,
            "type": "user",
            "first_name": "999 pouya ebrahimi",
            "username": "pouyaa_eb",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -8002050112466909000,
            "phone": "989120231643",
            "bot_nochats": false
        },
        {
            "id": 231223759,
            "type": "user",
            "first_name": "00 Hasan",
            "last_name": "Torabi",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -5000478606913323000,
            "phone": "989121084067",
            "bot_nochats": false
        },
        {
            "id": 149114794,
            "type": "user",
            "first_name": "999 sed mehdi javadi",
            "username": "Sjmahdi93",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 6962201656038640000,
            "phone": "989031002033",
            "bot_nochats": false
        },
        {
            "id": 1037729069,
            "type": "user",
            "first_name": "Alireza Enayatpur XXX",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 6340903958618721000,
            "phone": "989161129364",
            "bot_nochats": false
        },
        {
            "id": 255192499,
            "type": "user",
            "first_name": "00 Meisam Karmand",
            "last_name": "Chehre",
            "username": "Korepart",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 7878972939921108000,
            "phone": "989128999423",
            "bot_nochats": false
        }
    ];
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
                    console.log(data);
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
                            <td class="py-5 px-3 text-sm text-center" colspan="5">
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
                            template += `
                        <tr class="even:bg-gray-200">
                            <td class="py-2 px-3 text-sm">${counter}</td>
                            <td class="py-2 px-3 text-sm">${contact.first_name ?? ''}</td>
                            <td class="py-2 px-3 text-sm">${contact.username ?? ''}</td>
                            <td class="py-2 px-3 text-sm">category</td>
                            <td class="py-2 px-3 text-sm cursor-pointer" 
                                onclick="addContact(
                                    '${contact.first_name ?? ''}',
                                    '${contact.username ?? ''}',
                                    '${contact.id ?? ''}',
                                    'rezaei.jpeg'
                                )">
                                <img src="./public/img/add.svg" alt="plus icon">
                            </td>
                        </tr>`;
                            counter++;
                        }
                    }
                    container.innerHTML = template;
                }
            })
            .catch(function(error) {
                console.log(error);
            });
    }
    connect();
</script>
<?php require_once './layouts/footer.php';
