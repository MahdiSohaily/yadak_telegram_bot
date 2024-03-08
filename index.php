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
                    <th class="text-right py-2 px-3 text-sm">#</th>
                    <th class="text-right py-2 px-3 text-sm">اسم</th>
                    <th class="text-right py-2 px-3 text-sm">نام کاربری</th>
                    <th class="text-right py-2 px-3 text-sm">دسته بندی</th>
                    <th class="text-right py-2 px-3 text-sm">
                        <img src="./public/img/setting.svg" />
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($contacts !== null && count($contacts) > 0) :
                    foreach ($contacts as $key => $item) : ?>
                        <tr class="even:bg-gray-200">
                            <td class="py-2 px-3 text-sm"><?= $key + 1 ?></td>
                            <td class="py-2 px-3 text-sm"><?= $item['name'] ?></td>
                            <td class="py-2 px-3 text-sm"><?= $item['username'] ?></td>
                            <td class="py-2 px-3 text-sm">ارسال پیام خودکار</td>
                            <td class="py-2 px-3 text-sm cursor-pointer" onclick="deleteContact('<?= $item['id'] ?>')">
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
                    <th class="text-right py-2 px-3 text-sm">#</th>
                    <th class="text-right py-2 px-3 text-sm">اسم</th>
                    <th class="text-right py-2 px-3 text-sm">نام کاربری</th>
                    <th class="text-right py-2 px-3 text-sm">دسته بندی</th>
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
    const definedCodes = <?= json_encode(array_column($selectedGoods, 'partNumber')); ?>;

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
                            <td class="py-2 px-3 text-sm">ارسال پیام خودکار</td>
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

    let AllMessages = {
        "17678770": {
            "info": [{
                "code": "\n",
                "message": "Kore",
                "date": 1709820810
            }],
            "name": [
                "Xxx Shayan Salehi Cerato"
            ],
            "userName": [
                17678770
            ],
            "profile": [
                "17678770_x_4.jpg"
            ]
        },
        "27635146": {
            "info": [{
                "code": "S581012SA51\n",
                "message": "98620-3L200\n\n\n\n\n\n\n\n\n\nPv please",
                "date": 1709820385
            }],
            "name": [
                "ⓢⓐⓙⓐⓓ"
            ],
            "userName": [
                27635146
            ],
            "profile": [
                "27635146_x_4.jpg"
            ]
        },
        "37293091": {
            "info": [{
                    "code": "",
                    "message": "",
                    "date": 1709815002
                },
                {
                    "code": "",
                    "message": "",
                    "date": 1709815002
                },
                {
                    "code": "\n",
                    "message": "کارنیوال",
                    "date": 1709815002
                }
            ],
            "name": [
                "00 Saeed Khaje Smaeil",
                "00 Saeed Khaje Smaeil",
                "00 Saeed Khaje Smaeil"
            ],
            "userName": [
                37293091,
                37293091,
                37293091
            ],
            "profile": [
                "37293091_x_4.jpg",
                "37293091_x_4.jpg",
                "37293091_x_4.jpg"
            ]
        },
        "53371081": {
            "info": [{
                    "code": "865103L220\n",
                    "message": "86510_3L220\n\n\n\n\n?",
                    "date": 1709813519
                },
                {
                    "code": "865103L240\n",
                    "message": "86510-3L240\n\n\n\n\n\n\n\n\n\n\n???????????",
                    "date": 1709811167
                },
                {
                    "code": "924033V050\n",
                    "message": "92403_3V050\n\n\n\n\n\n\n\n?????",
                    "date": 1709808599
                }
            ],
            "name": [
                "Mohammad Esmaili",
                "Mohammad Esmaili",
                "Mohammad Esmaili"
            ],
            "userName": [
                53371081,
                53371081,
                53371081
            ],
            "profile": [
                "53371081_x_4.jpg",
                "53371081_x_4.jpg",
                "53371081_x_4.jpg"
            ]
        },
        "67231794": {
            "info": [{
                    "code": "\n",
                    "message": "موجود",
                    "date": 1709810569
                },
                {
                    "code": "396503C000\n",
                    "message": "396503C000",
                    "date": 1709809444
                }
            ],
            "name": [
                "xxx asef",
                "xxx asef"
            ],
            "userName": [
                67231794,
                67231794
            ],
            "profile": [
                "67231794_x_4.jpg",
                "67231794_x_4.jpg"
            ]
        },
        "68040686": {
            "info": [{
                    "code": "\n",
                    "message": "M",
                    "date": 1709814730
                },
                {
                    "code": "396503c000\n",
                    "message": "396503c000",
                    "date": 1709809881
                },
                {
                    "code": "\n",
                    "message": "M",
                    "date": 1709808304
                }
            ],
            "name": [
                "888 hosein kermani",
                "888 hosein kermani",
                "888 hosein kermani"
            ],
            "userName": [
                68040686,
                68040686,
                68040686
            ],
            "profile": [
                "68040686_x_4.jpg",
                "68040686_x_4.jpg",
                "68040686_x_4.jpg"
            ]
        },
        "73713372": {
            "info": [{
                    "code": "392102E400\n",
                    "message": "39210 2E400",
                    "date": 1709890795
                },
                {
                    "code": "863102W000\n",
                    "message": "86310 2W000",
                    "date": 1709883929
                }
            ],
            "name": [
                "999 pouya ebrahimi",
                "999 pouya ebrahimi"
            ],
            "userName": [
                73713372,
                73713372
            ],
            "profile": [
                "73713372_x_4.jpg",
                "73713372_x_4.jpg"
            ]
        },
        "83940900": {
            "info": [{
                "code": "\n",
                "message": "م",
                "date": 1709808681
            }],
            "name": [
                "00 Alireza Nematollah"
            ],
            "userName": [
                83940900
            ],
            "profile": [
                "83940900_x_4.jpg"
            ]
        },
        "83990082": {
            "info": [{
                "code": "826512B210\n",
                "message": "826512B210\n\n\n\n???",
                "date": 1709808532
            }],
            "name": [
                "00 Ali Salehi"
            ],
            "userName": [
                83990082
            ],
            "profile": [
                "83990082_x_4.jpg"
            ]
        },
        "106068568": {
            "info": [{
                "code": "1884611070\n",
                "message": "18846-11070",
                "date": 1709813885
            }],
            "name": [
                "Erfan"
            ],
            "userName": [
                106068568
            ],
            "profile": [
                "106068568_x_4.jpg"
            ]
        },
        "106256131": {
            "info": [{
                "code": "921021W250\n663211W100\n865111W200\n986201W000\n",
                "message": "92102-1W250\n66321-1W100\n86511-1W200\n98620-1W000\n\n\n\n??????????",
                "date": 1709808615
            }],
            "name": [
                "Vahab Sajadi"
            ],
            "userName": [
                106256131
            ],
            "profile": [
                "images.png"
            ]
        },
        "107375849": {
            "info": [{
                    "code": "924021R030\n",
                    "message": "92402-1R030\n\n\n\n\n?",
                    "date": 1709810138
                },
                {
                    "code": "865111R000\n866111R000\n",
                    "message": "86511-1R000\n86611-1R000\n\n\n\n\n?",
                    "date": 1709810120
                },
                {
                    "code": "924023X210\n",
                    "message": "92402-3X210\n\n\n\n\n\n\n\n\n\n\n\n؟",
                    "date": 1709809678
                },
                {
                    "code": "935713X001RY\n",
                    "message": "93571-3X001RY\n\n\n\n\n\n\n\n\n?",
                    "date": 1709809351
                }
            ],
            "name": [
                "00 محسن خانقلی",
                "00 محسن خانقلی",
                "00 محسن خانقلی",
                "00 محسن خانقلی"
            ],
            "userName": [
                107375849,
                107375849,
                107375849,
                107375849
            ],
            "profile": [
                "107375849_x_4.jpg",
                "107375849_x_4.jpg",
                "107375849_x_4.jpg",
                "107375849_x_4.jpg"
            ]
        },
        "111818536": {
            "info": [{
                    "code": "552503Z000\n",
                    "message": "55250-3Z000\n\n\n?",
                    "date": 1709808489
                },
                {
                    "code": "553113V\n",
                    "message": "55311-3V\n\n\n?",
                    "date": 1709808478
                },
                {
                    "code": "218303V200\n",
                    "message": "21830-3V200\n\n?",
                    "date": 1709808468
                },
                {
                    "code": "218103V200\n",
                    "message": "21810-3V200\n\n?",
                    "date": 1709808454
                },
                {
                    "code": "546123S000\n",
                    "message": "54612-3S000\n\n\n?",
                    "date": 1709808425
                },
                {
                    "code": "546102T000\n",
                    "message": "54610-2T000\n\n\n?",
                    "date": 1709808375
                },
                {
                    "code": "546513V010\n546613V010\n",
                    "message": "54651-3V010\n\n54661-3V010\n\n\n?",
                    "date": 1709808342
                }
            ],
            "name": [
                "888 امیرحسین شفاعی",
                "888 امیرحسین شفاعی",
                "888 امیرحسین شفاعی",
                "888 امیرحسین شفاعی",
                "888 امیرحسین شفاعی",
                "888 امیرحسین شفاعی",
                "888 امیرحسین شفاعی"
            ],
            "userName": [
                111818536,
                111818536,
                111818536,
                111818536,
                111818536,
                111818536,
                111818536
            ],
            "profile": [
                "111818536_x_4.jpg",
                "111818536_x_4.jpg",
                "111818536_x_4.jpg",
                "111818536_x_4.jpg",
                "111818536_x_4.jpg",
                "111818536_x_4.jpg",
                "111818536_x_4.jpg"
            ]
        },
        "113234273": {
            "info": [{
                "code": "5463040\n",
                "message": "54630 40 3E300\nکمک جلو \n?????",
                "date": 1709816667
            }],
            "name": [
                "00 اسکندریان"
            ],
            "userName": [
                113234273
            ],
            "profile": [
                "113234273_x_4.jpg"
            ]
        },
        "114694836": {
            "info": [{
                "code": "581013XA20\n",
                "message": "58101-3XA20",
                "date": 1709819194
            }],
            "name": [
                "مرتضی"
            ],
            "userName": [
                114694836
            ],
            "profile": [
                "114694836_x_4.jpg"
            ]
        },
        "118114176": {
            "info": [{
                "code": "\n",
                "message": "توپی چرخ عقب سانتافه",
                "date": 1709811499
            }],
            "name": [
                "Mehdi"
            ],
            "userName": [
                118114176
            ],
            "profile": [
                "118114176_x_4.jpg"
            ]
        },
        "120737980": {
            "info": [{
                    "code": "922023j\n",
                    "message": "92202 3j\n\n\n\n\n\n!???",
                    "date": 1709898785
                },
                {
                    "code": "\n",
                    "message": "ورنا ۱۶۰۰\nشلنگ های بالا و پایین \nشلنگ بخاری\nشلنگ لوله فرعی\nدسته موتور پایین. جفت\nترموستات با واشر\nتسمه کولر\nتسمه هیدرولیک فرمان\nتسمه دینام",
                    "date": 1709815818
                }
            ],
            "name": [
                "XXX Payande Stock Shosh",
                "XXX Payande Stock Shosh"
            ],
            "userName": [
                120737980,
                120737980
            ],
            "profile": [
                "120737980_x_4.jpg",
                "120737980_x_4.jpg"
            ]
        },
        "135760446": {
            "info": [{
                "code": "2563137100\n",
                "message": "25631-37100",
                "date": 1709815615
            }],
            "name": [
                "xxx ebrahim khodarahmi sharik saeed fooladi"
            ],
            "userName": [
                135760446
            ],
            "profile": [
                "135760446_x_4.jpg"
            ]
        },
        "137050152": {
            "info": [{
                    "code": "213502E021\n",
                    "message": "21350 2E021\nاویل پمپ النترا",
                    "date": 1709810403
                },
                {
                    "code": "529603X300\n",
                    "message": "52960 3X300\nقالپاق النترا ۲۰۱۴",
                    "date": 1709810304
                }
            ],
            "name": [
                "888 mostafa rozbeh",
                "888 mostafa rozbeh"
            ],
            "userName": [
                137050152,
                137050152
            ],
            "profile": [
                "137050152_x_4.jpg",
                "137050152_x_4.jpg"
            ]
        },
        "144475548": {
            "info": [{
                "code": "546502B540\n546602B540\n",
                "message": "546502B540\n546602B540\n\n\n؟؟",
                "date": 1709810104
            }],
            "name": [
                "00 مهدی فرهادی"
            ],
            "userName": [
                144475548
            ],
            "profile": [
                "images.png"
            ]
        },
        "149114794": {
            "info": [{
                    "code": "971382D000\n",
                    "message": "971382D000",
                    "date": 1709885184
                },
                {
                    "code": "971382D000\n",
                    "message": "971382D000",
                    "date": 1709883911
                }
            ],
            "name": [
                "999 sed mehdi javadi",
                "999 sed mehdi javadi"
            ],
            "userName": [
                149114794,
                149114794
            ],
            "profile": [
                "149114794_x_4.jpg",
                "149114794_x_4.jpg"
            ]
        },
        "164515760": {
            "info": [{
                    "code": "254122W500\n254132W500\n",
                    "message": "254122W500\n254132W500",
                    "date": 1709808788
                },
                {
                    "code": "973122W100\n973112W100\n",
                    "message": "973122W100\n973112W100",
                    "date": 1709808749
                }
            ],
            "name": [
                "Mohammadreza Mahdifard",
                "Mohammadreza Mahdifard"
            ],
            "userName": [
                164515760,
                164515760
            ],
            "profile": [
                "164515760_x_4.jpg",
                "164515760_x_4.jpg"
            ]
        },
        "222597014": {
            "info": [{
                "code": "935701M100WK\n",
                "message": "93570-1M100WK",
                "date": 1709880217
            }],
            "name": [
                "xxx kiamotors 112"
            ],
            "userName": [
                222597014
            ],
            "profile": [
                "222597014_x_4.jpg"
            ]
        },
        "231223759": {
            "info": [{
                "code": "\n",
                "message": "M",
                "date": 1709885415
            }],
            "name": [
                "00 Hasan Torabi"
            ],
            "userName": [
                231223759
            ],
            "profile": [
                "231223759_x_4.jpg"
            ]
        },
        "255192499": {
            "info": [{
                    "code": "546611m\n546101M\n517602E\n",
                    "message": "54661 1m\n54610 1M\n51760 2E",
                    "date": 1709884000
                },
                {
                    "code": "\n",
                    "message": "پمپ ترمز اونته",
                    "date": 1709811812
                },
                {
                    "code": "865113L201\n",
                    "message": "865113L201",
                    "date": 1709811121
                },
                {
                    "code": "210202G130\n",
                    "message": "21020 2G130\n۳ تا سبز",
                    "date": 1709809934
                },
                {
                    "code": "2102025150\n",
                    "message": "21020 25150",
                    "date": 1709808580
                }
            ],
            "name": [
                "00 Meisam Karmand Chehre",
                "00 Meisam Karmand Chehre",
                "00 Meisam Karmand Chehre",
                "00 Meisam Karmand Chehre",
                "00 Meisam Karmand Chehre"
            ],
            "userName": [
                255192499,
                255192499,
                255192499,
                255192499,
                255192499
            ],
            "profile": [
                "255192499_x_4.jpg",
                "255192499_x_4.jpg",
                "255192499_x_4.jpg",
                "255192499_x_4.jpg",
                "255192499_x_4.jpg"
            ]
        },
        "263857572": {
            "info": [{
                    "code": "\n",
                    "message": "M",
                    "date": 1709808798
                },
                {
                    "code": "\n",
                    "message": "M",
                    "date": 1709808610
                }
            ],
            "name": [
                "IMPERIAL",
                "IMPERIAL"
            ],
            "userName": [
                263857572,
                263857572
            ],
            "profile": [
                "263857572_x_4.jpg",
                "263857572_x_4.jpg"
            ]
        },
        "311143797": {
            "info": [{
                "code": "\n",
                "message": "M",
                "date": 1709828396
            }],
            "name": [
                "حماد رجبعلیان بازار Hemad Rajabalian"
            ],
            "userName": [
                311143797
            ],
            "profile": [
                "311143797_x_4.jpg"
            ]
        },
        "313166642": {
            "info": [{
                "code": "819002j710\n",
                "message": "819002j710",
                "date": 1709818636
            }],
            "name": [
                "00 Iman Shams"
            ],
            "userName": [
                313166642
            ],
            "profile": [
                "313166642_x_4.jpg"
            ]
        },
        "354753504": {
            "info": [{
                "code": "256202G510\n256002G510\n",
                "message": "25620-2G510\n\n\n\n25600-2G510\n\n\n؟؟؟",
                "date": 1709809433
            }],
            "name": [
                "Seoul part"
            ],
            "userName": [
                354753504
            ],
            "profile": [
                "images.png"
            ]
        },
        "375669009": {
            "info": [{
                "code": "4362123600\n",
                "message": "43621. 23600\n\n??",
                "date": 1709809474
            }],
            "name": [
                "00 محمد علی پور"
            ],
            "userName": [
                375669009
            ],
            "profile": [
                "375669009_x_4.jpg"
            ]
        },
        "414982398": {
            "info": [{
                "code": "495982e000\n",
                "message": "49598-2e000\n\n\n\n\n\n????",
                "date": 1709808937
            }],
            "name": [
                "00 محمد محبی"
            ],
            "userName": [
                414982398
            ],
            "profile": [
                "414982398_x_4.jpg"
            ]
        },
        "456937514": {
            "info": [{
                "code": "254112w500\n254122w500\n",
                "message": "25411-2w500\n\n25412-2w500\n\n\nاصلی کره طرح\nامروز یا فردا",
                "date": 1709896648
            }],
            "name": [
                "MGMPART 33939279/33976298"
            ],
            "userName": [
                456937514
            ],
            "profile": [
                "456937514_x_4.jpg"
            ]
        },
        "532654564": {
            "info": [{
                "code": "\n",
                "message": "M",
                "date": 1709811114
            }],
            "name": [
                "00 میلاد عباسی"
            ],
            "userName": [
                532654564
            ],
            "profile": [
                "532654564_x_4.jpg"
            ]
        },
        "602119238": {
            "info": [{
                "code": "224412GGA0\n",
                "message": "22441-2GGA0\n\n\n\n\n\n🔴🔴🔴🔴🔴🔴🔴🔴",
                "date": 1709808491
            }],
            "name": [
                "Moein Torabi"
            ],
            "userName": [
                602119238
            ],
            "profile": [
                "602119238_x_4.jpg"
            ]
        },
        "668904556": {
            "info": [{
                "code": "25600\n",
                "message": "25600",
                "date": 1709809715
            }],
            "name": [
                "XXX Hamid ChehreGosha"
            ],
            "userName": [
                668904556
            ],
            "profile": [
                "668904556_x_4.jpg"
            ]
        },
        "688351147": {
            "info": [{
                "code": "292153C1503\n",
                "message": "29215-3C150 3 adad\n\n?",
                "date": 1709809711
            }],
            "name": [
                "888 saeed rostami"
            ],
            "userName": [
                688351147
            ],
            "profile": [
                "688351147_x_4.jpg"
            ]
        },
        "770434004": {
            "info": [{
                    "code": "\n",
                    "message": "GEN",
                    "date": 1709822094
                },
                {
                    "code": "\n",
                    "message": "m",
                    "date": 1709811264
                }
            ],
            "name": [
                "888 amirhosien rafiei",
                "888 amirhosien rafiei"
            ],
            "userName": [
                770434004,
                770434004
            ],
            "profile": [
                "770434004_x_4.jpg",
                "770434004_x_4.jpg"
            ]
        },
        "839351240": {
            "info": [{
                "code": "598304C000\n",
                "message": "59830 4C000\n\n?????",
                "date": 1709818897
            }],
            "name": [
                "xxx sedjavadi"
            ],
            "userName": [
                839351240
            ],
            "profile": [
                "839351240_x_4.jpg"
            ]
        },
        "868417312": {
            "info": [{
                    "code": "\n",
                    "message": "م",
                    "date": 1709814026
                },
                {
                    "code": "\n",
                    "message": "ماندو",
                    "date": 1709813452
                },
                {
                    "code": "\n",
                    "message": "م",
                    "date": 1709811850
                },
                {
                    "code": "\n",
                    "message": "م",
                    "date": 1709810203
                },
                {
                    "code": "\n",
                    "message": "M",
                    "date": 1709809201
                },
                {
                    "code": "\n",
                    "message": "M",
                    "date": 1709809195
                },
                {
                    "code": "\n",
                    "message": "M",
                    "date": 1709808322
                },
                {
                    "code": "\n",
                    "message": "Gen",
                    "date": 1709808305
                },
                {
                    "code": "\n",
                    "message": "M",
                    "date": 1709808290
                }
            ],
            "name": [
                "888 arsalan mir",
                "888 arsalan mir",
                "888 arsalan mir",
                "888 arsalan mir",
                "888 arsalan mir",
                "888 arsalan mir",
                "888 arsalan mir",
                "888 arsalan mir",
                "888 arsalan mir"
            ],
            "userName": [
                868417312,
                868417312,
                868417312,
                868417312,
                868417312,
                868417312,
                868417312,
                868417312,
                868417312
            ],
            "profile": [
                "868417312_x_4.jpg",
                "868417312_x_4.jpg",
                "868417312_x_4.jpg",
                "868417312_x_4.jpg",
                "868417312_x_4.jpg",
                "868417312_x_4.jpg",
                "868417312_x_4.jpg",
                "868417312_x_4.jpg",
                "868417312_x_4.jpg"
            ]
        },
        "947108568": {
            "info": [{
                "code": "866101W510\n866121W520\n924061W500\n866301W500\n865601W520\n921021W030\n865911W500\n865221W520\n865211W520\n865111W500\n865601W520\n865141W200\n865821W500\n868121W500\n925011W200\n865201W500\n",
                "message": "86610-1W510\n\n86612-1W520\n\n92406-1W500\n\n\n86630-1W500\n\n\n\n86560-1W520\n\n\n92102-1W030\n\n\n86591-1W500\n\n\n86522-1W520\n\n\n86521-1W520\n\n\n86511-1W500\n\n\n86560-1W520\n\n\n86514-1W200\n\n\n86582-1W500\n\n\n86812-1W500\n\n\n92501-1W200\n\n\n\n86520-1W500\n\n\n??????",
                "date": 1709809167
            }],
            "name": [
                "فروشگاه سعید ( سعید فقیر )"
            ],
            "userName": [
                947108568
            ],
            "profile": [
                "947108568_x_4.jpg"
            ]
        },
        "960595525": {
            "info": [{
                    "code": "552502s100fff\n",
                    "message": "55250-2s100 fff\n\n\n\nAsli",
                    "date": 1709808721
                },
                {
                    "code": "555133n200ya\n",
                    "message": "55513-3n200 ya 300\n\n\nAsli",
                    "date": 1709808721
                }
            ],
            "name": [
                "Hyundai Mozaffari",
                "Hyundai Mozaffari"
            ],
            "userName": [
                960595525,
                960595525
            ],
            "profile": [
                "960595525_x_4.jpg",
                "960595525_x_4.jpg"
            ]
        },
        "1008674510": {
            "info": [{
                    "code": "2528737101\n",
                    "message": "25287-37101\n\n\n?",
                    "date": 1709811805
                },
                {
                    "code": "2528637100\n",
                    "message": "25286-37100\n\n\n?",
                    "date": 1709811797
                },
                {
                    "code": "2521237181\n",
                    "message": "25212-37181\n\n\n\n?",
                    "date": 1709810412
                }
            ],
            "name": [
                "فروشگاه قطعات اصلی",
                "فروشگاه قطعات اصلی",
                "فروشگاه قطعات اصلی"
            ],
            "userName": [
                1008674510,
                1008674510,
                1008674510
            ],
            "profile": [
                "images.png",
                "images.png",
                "images.png"
            ]
        },
        "1037729069": {
            "info": [{
                    "code": "\n",
                    "message": "دسته موتور چپ اپیروس",
                    "date": 1709885104
                },
                {
                    "code": "218303F\n",
                    "message": "21830-3F",
                    "date": 1709884923
                }
            ],
            "name": [
                "Alireza Enayatpur XXX",
                "Alireza Enayatpur XXX"
            ],
            "userName": [
                1037729069,
                1037729069
            ],
            "profile": [
                "images.png",
                "images.png"
            ]
        },
        "1075068299": {
            "info": [{
                    "code": "\n",
                    "message": "سرپلوس \nالنترا\n\n\n24خار",
                    "date": 1709879558
                },
                {
                    "code": "\n",
                    "message": "سرپلوس النترا\n\n\n24خار\n\n\n؟؟",
                    "date": 1709808660
                },
                {
                    "code": "\n",
                    "message": "سرپلوس النترا\n\n\n24خار",
                    "date": 1709808453
                }
            ],
            "name": [
                "Xxx Pooria Mosavi",
                "Xxx Pooria Mosavi",
                "Xxx Pooria Mosavi"
            ],
            "userName": [
                1075068299,
                1075068299,
                1075068299
            ],
            "profile": [
                "1075068299_x_4.jpg",
                "1075068299_x_4.jpg",
                "1075068299_x_4.jpg"
            ]
        },
        "1111633692": {
            "info": [{
                    "code": "\n",
                    "message": "m\\",
                    "date": 1709813911
                },
                {
                    "code": "\n",
                    "message": "م",
                    "date": 1709812756
                },
                {
                    "code": "583502E\n",
                    "message": "58350_2E\n\n\n\n?????????????",
                    "date": 1709811643
                },
                {
                    "code": "\n",
                    "message": "m",
                    "date": 1709810215
                },
                {
                    "code": "\n",
                    "message": "m",
                    "date": 1709810196
                }
            ],
            "name": [
                "Hosein Hoshiyari",
                "Hosein Hoshiyari",
                "Hosein Hoshiyari",
                "Hosein Hoshiyari",
                "Hosein Hoshiyari"
            ],
            "userName": [
                1111633692,
                1111633692,
                1111633692,
                1111633692,
                1111633692
            ],
            "profile": [
                "1111633692_x_4.jpg",
                "1111633692_x_4.jpg",
                "1111633692_x_4.jpg",
                "1111633692_x_4.jpg",
                "1111633692_x_4.jpg"
            ]
        },
        "1142705272": {
            "info": [{
                    "code": "517123E500\n",
                    "message": "51712 3E500\n\n\nEmrooz\n\n\nKorea",
                    "date": 1709825463
                },
                {
                    "code": "973113J100\n",
                    "message": "97311 3J100\n\nEmrooz\n\n??????",
                    "date": 1709822060
                }
            ],
            "name": [
                "888 محمد رضا پورحسین پاساژ سپهر",
                "888 محمد رضا پورحسین پاساژ سپهر"
            ],
            "userName": [
                1142705272,
                1142705272
            ],
            "profile": [
                "images.png",
                "images.png"
            ]
        },
        "1303677205": {
            "info": [{
                "code": "233002G400\n",
                "message": "23300-2G400\n\n?",
                "date": 1709809119
            }],
            "name": [
                "فروشگاه پارت سنتر (علی شفیعی) Part Center Store (Ali Shafiei)"
            ],
            "userName": [
                1303677205
            ],
            "profile": [
                "1303677205_x_4.jpg"
            ]
        },
        "1354776739": {
            "info": [{
                "code": "31111c2500\n",
                "message": "31111_c2500\n\n\n\n?????\n\n\n\nبرای الان",
                "date": 1709816839
            }],
            "name": [
                "Hassani"
            ],
            "userName": [
                1354776739
            ],
            "profile": [
                "images.png"
            ]
        },
        "1898545571": {
            "info": [{
                    "code": "553113K040\nS581012SA51\n",
                    "message": "92101-1M\n92102-1M\n\n\n\n\nسراتو سایپایی ؟؟؟\n\n\nفوری نیاز",
                    "date": 1709884868
                },
                {
                    "code": "921011M\n921021M\n",
                    "message": "92101-1M\n92102-1M\n\n\n\n\nسراتو سایپایی ؟؟؟",
                    "date": 1709884590
                }
            ],
            "name": [
                "Amirhossein mirzaee",
                "Amirhossein mirzaee"
            ],
            "userName": [
                1898545571,
                1898545571
            ],
            "profile": [
                "1898545571_x_4.jpg",
                "1898545571_x_4.jpg"
            ]
        },
        "6662537570": {
            "info": [{
                "code": "\n",
                "message": "لنت جلو سوناتا قدیم hiQ",
                "date": 1709812732
            }],
            "name": [
                "فروشگاه کاشانی هیوندایی"
            ],
            "userName": [
                6662537570
            ],
            "profile": [
                "6662537570_x_4.jpg"
            ]
        },
        "5265257413": {
            "info": [{
                "code": "244713c100\n",
                "message": "24471-3c100\n\n\n\n???",
                "date": 1709812091
            }],
            "name": [
                "Alireza Mardi"
            ],
            "userName": [
                5265257413
            ],
            "profile": [
                "5265257413_x_4.jpg"
            ]
        },
        "5416488842": {
            "info": [{
                "code": "25600M\n",
                "message": "25600. M",
                "date": 1709810033
            }],
            "name": [
                "Naser Sales"
            ],
            "userName": [
                5416488842
            ],
            "profile": [
                "5416488842_x_4.jpg"
            ]
        },
        "6440824590": {
            "info": [{
                "code": "256312B051\n",
                "message": "25631-2B051\n\n\n\n\n????\n🔴🔴🔴🔴🔴🔴",
                "date": 1709808553
            }],
            "name": [
                "Milad Hatami Hyundai"
            ],
            "userName": [
                6440824590
            ],
            "profile": [
                "6440824590_x_4.jpg"
            ]
        }
    };

    // function getMessagesAuto() {
    //     var params = new URLSearchParams();
    //     params.append('getMessagesAuto', 'getMessagesAuto');
    //     axios
    //         .post("http://telegram.om-dienstleistungen.de/", params)
    //         .then(function(response) {
    //             const messages = JSON.stringify(response.data);

    //             console.log(JSON.parse(messages));
    //         })
    //         .catch(function(error) {
    //             console.log(error);
    //         });
    // }

    // getMessagesAuto();
    async function checkMessages() {
        for (messageInfo of Object.values(AllMessages)) {
            // const sender = '169785118';
            const sender = messageInfo.userName[0];
            let displayPrices = [];

            if (existingContacts.includes(sender + '')) {
                const messageContent = messageInfo.info;
                let template = ``;

                for (item of messageContent) {
                    let codes = item.code.split('\n');
                    codes = codes.filter(function(line) {
                        return line != "" && definedCodes.includes(line);
                    })
                    if (codes.length) {
                        await getPrice(codes).then(data => {
                            for (item of data) {
                                template += `${item.partnumber} : ${item.price} \n`;
                            }

                            sendMessageWithTemplate(sender, template);
                        });
                    }
                }
            }
        }
    }


    async function getPrice(codes) {
        var params = new URLSearchParams();
        params.append('code', codes.join('\n'));
        let result = await axios
            .post("../callcenter/report/lastPrice.php", params)
            .then(function(response) {
                let displayPrices = [];
                const data = response.data.data;
                const explodedCodes = data.explodedCodes;
                const existing = data.existing;
                for (const code of explodedCodes) {
                    const existingCodes = Object.values(existing[code]);
                    let max = 0;

                    for (const item of existingCodes) {
                        max += Math.max(...Object.values(item['relation']['sorted']))
                    }

                    if (max > 0) {
                        let givenPrice = existingCodes[0]['givenPrice'];
                        if (!Array.isArray(givenPrice)) {
                            givenPrice = [...Object.values(givenPrice)]
                        }

                        if (givenPrice.length > 0) {
                            displayPrices.push({
                                partnumber: givenPrice[0]['partnumber'],
                                price: givenPrice[0]['price']
                            });
                        } else {
                            displayPrices.push({
                                partnumber: givenPrice[0]['partnumber'],
                                price: 'موجود نیست'
                            });
                        }
                    }
                }
                return displayPrices;

            })
            .catch(function(error) {
                console.log(error);
            });
        return result;
    }

    function sendMessageWithTemplate(receiver, template) {
        var params = new URLSearchParams();
        params.append('sendMessageWithTemplate', 'sendMessageWithTemplate');
        params.append('receiver', receiver);
        params.append('message', template);

        axios
            .post("http://telegram.om-dienstleistungen.de/", params)
            .then(function(response) {

                console.log(response.data);
            })
            .catch(function(error) {
                console.log(error);
            });
    }

    checkMessages();
</script>
<?php require_once './layouts/footer.php';
