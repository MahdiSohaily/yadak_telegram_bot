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
                    <th class="text-right py-2 px-3">ردیف</th>
                    <th class="text-right py-2 px-3">اسم</th>
                    <th class="text-right py-2 px-3">نام کاربری</th>
                    <th class="text-right py-2 px-3">دسته بندی</th>
                    <th class="text-right py-2 px-3">نمایه</th>
                    <th class="text-right py-2 px-3">عملیات</th>
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
                            <td class="py-2 px-3"><?= $item['profile'] ?></td>
                            <td class="py-2 px-3 cursor-pointer" onclick="deleteContact('<?= $item['id'] ?>')">
                                <img src="./public/img/del.svg" alt="delete icon">
                            </td>
                        </tr>
                    <?php endforeach;
                else : ?>
                    <tr class="bg-rose-400 ">
                        <td class="py-2 px-3 text-white text-center" colspan="6">موردی برای نمایش وجود ندارد.</td>
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
                    <th class="text-right py-2 px-3 text-sm">نمایه</th>
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
        },
        {
            "id": 222597014,
            "type": "user",
            "first_name": "xxx kiamotors 112",
            "username": "KIAMOTORS112hosseini",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -3832594272863549400,
            "phone": "989302360096",
            "bot_nochats": false
        },
        {
            "id": 1075068299,
            "type": "user",
            "first_name": "Xxx Pooria",
            "last_name": "Mosavi",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 3515577371286386700,
            "phone": "989126072657",
            "bot_nochats": false
        },
        {
            "id": 311143797,
            "type": "user",
            "first_name": "حماد رجبعلیان بازار Hemad Rajabalian",
            "username": "Hemad_Store",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 1706890876297419300,
            "phone": "989128464202",
            "bot_nochats": false
        },
        {
            "id": 1142705272,
            "type": "user",
            "first_name": "888 محمد رضا پورحسین پاساژ سپهر",
            "username": "rezapo_h",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 3896046749580646400,
            "phone": "989129722671",
            "bot_nochats": false
        },
        {
            "id": 770434004,
            "type": "user",
            "first_name": "888 amirhosien rafiei",
            "username": "hyundai_Amir",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 3027838231150042600,
            "phone": "989365029300",
            "bot_nochats": false
        },
        {
            "id": 17678770,
            "type": "user",
            "first_name": "Xxx Shayan Salehi",
            "last_name": "Cerato",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 1284881009286071800,
            "phone": "989123704563",
            "bot_nochats": false
        },
        {
            "id": 839351240,
            "type": "user",
            "first_name": "xxx sedjavadi",
            "username": "Part_hyundai",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 682955871926585700,
            "phone": "989123475618",
            "bot_nochats": false
        },
        {
            "id": 313166642,
            "type": "user",
            "first_name": "00 Iman",
            "last_name": "Shams",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 7491617878081870000,
            "phone": "989128507732",
            "bot_nochats": false
        },
        {
            "id": 113234273,
            "type": "user",
            "first_name": "00",
            "last_name": "اسکندریان",
            "username": "Yadakchi",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 1196103972105257500,
            "phone": "989192261809",
            "bot_nochats": false
        },
        {
            "id": 120737980,
            "type": "user",
            "first_name": "XXX Payande Stock Shosh",
            "username": "payandeh666",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -1911431118210517200,
            "phone": "989126652664",
            "bot_nochats": false
        },
        {
            "id": 135760446,
            "type": "user",
            "first_name": "xxx ebrahim khodarahmi sharik saeed fooladi",
            "username": "yadakhub_support",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 1778757670477846500,
            "phone": "989368034474",
            "bot_nochats": false
        },
        {
            "id": 37293091,
            "type": "user",
            "first_name": "00 Saeed Khaje",
            "last_name": "Smaeil",
            "username": "Saeedshopkia",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -3859361639185051600,
            "phone": "989121386791",
            "bot_nochats": false
        },
        {
            "id": 68040686,
            "type": "user",
            "first_name": "888 hosein kermani",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -3551994639905670700,
            "phone": "989371070156",
            "bot_nochats": false
        },
        {
            "id": 868417312,
            "type": "user",
            "first_name": "888 arsalan mir",
            "username": "arsalanmir",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -205606306928852030,
            "phone": "989122443153",
            "bot_nochats": false
        },
        {
            "id": 532654564,
            "type": "user",
            "first_name": "00 میلاد",
            "last_name": "عباسی",
            "username": "Mld_KIA",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 5070062883419572000,
            "phone": "989125791755",
            "bot_nochats": false
        },
        {
            "id": 67231794,
            "type": "user",
            "first_name": "xxx asef",
            "username": "Asefmr",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 7123955059095743000,
            "phone": "989122230797",
            "bot_nochats": false
        },
        {
            "id": 137050152,
            "type": "user",
            "first_name": "888 mostafa rozbeh",
            "username": "Mostafarozbeh",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 6474864726390254000,
            "phone": "989122467346",
            "bot_nochats": false
        },
        {
            "id": 107375849,
            "type": "user",
            "first_name": "00 محسن",
            "last_name": "خانقلی",
            "username": "mohsen_khangholi",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -1323825188762097200,
            "phone": "989126452953",
            "bot_nochats": false
        },
        {
            "id": 144475548,
            "type": "user",
            "first_name": "00 مهدی",
            "last_name": "فرهادی",
            "username": "mehdi_farhadi001",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 5407628177266028000,
            "phone": "989385048593",
            "bot_nochats": false
        },
        {
            "id": 668904556,
            "type": "user",
            "first_name": "XXX Hamid",
            "last_name": "ChehreGosha",
            "username": "Hamidchehrehgosha_hyundai_kia",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 4805850838312050000,
            "phone": "989124546458",
            "bot_nochats": false
        },
        {
            "id": 688351147,
            "type": "user",
            "first_name": "888 saeed rostami",
            "username": "M_S_ROSTAMI233",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 5237625323178920000,
            "phone": "989124986037",
            "bot_nochats": false
        },
        {
            "id": 375669009,
            "type": "user",
            "first_name": "00 محمد علی",
            "last_name": "پور",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 4860037317505642000,
            "phone": "989363333491",
            "bot_nochats": false
        },
        {
            "id": 414982398,
            "type": "user",
            "first_name": "00 محمد",
            "last_name": "محبی",
            "username": "generalhyundaie",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 3774001868234833400,
            "phone": "989039406643",
            "bot_nochats": false
        },
        {
            "id": 83940900,
            "type": "user",
            "first_name": "00 Alireza",
            "last_name": "Nematollah",
            "username": "alirezahyundai",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -3490574732802297300,
            "phone": "989126192617",
            "bot_nochats": false
        },
        {
            "id": 6440824590,
            "type": "user",
            "first_name": "Milad Hatami",
            "last_name": "Hyundai",
            "username": "hamimotor",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 5208759892927579000,
            "phone": "989121251620",
            "bot_nochats": false
        },
        {
            "id": 83990082,
            "type": "user",
            "first_name": "00 Ali",
            "last_name": "Salehi",
            "username": "Ali_reza63",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -1975362572640386600,
            "phone": "989124367079",
            "bot_nochats": false
        },
        {
            "id": 111818536,
            "type": "user",
            "first_name": "888 امیرحسین شفاعی",
            "username": "SHAFAEEAMIR",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -9004868805862537000,
            "phone": "989194221914",
            "bot_nochats": false
        },
        {
            "id": 123380823,
            "type": "user",
            "first_name": "Xxx",
            "last_name": "Shahriar",
            "username": "Shahriar_1362",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -8623606096811622000,
            "phone": "989216086702",
            "bot_nochats": false
        },
        {
            "id": 1855653622,
            "type": "user",
            "first_name": "Tehran Seoul hamiz zarrabi",
            "username": "Tehran_soeul",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 9172621484853298000,
            "phone": "989925371017",
            "bot_nochats": false
        },
        {
            "id": 138595891,
            "type": "user",
            "first_name": "999 mahdi salamat",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 644719865485932400,
            "phone": "989122719964",
            "bot_nochats": false
        },
        {
            "id": 1324336471,
            "type": "user",
            "first_name": "xxx khodro part abedi",
            "username": "kiahyundaiii",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -2744870213471531000,
            "phone": "989332480902",
            "bot_nochats": false
        },
        {
            "id": 158656831,
            "type": "user",
            "first_name": "00 Hosein",
            "last_name": "Khaki",
            "username": "Gspshop_official",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 1066424091195216300,
            "phone": "989369574540",
            "bot_nochats": false
        },
        {
            "id": 5780342718,
            "type": "user",
            "first_name": "00 مهدی",
            "last_name": "منصوری",
            "username": "Mahdimansoriiii",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -2286977003797053200,
            "phone": "989124504420",
            "bot_nochats": false
        },
        {
            "id": 71887425,
            "type": "user",
            "first_name": "00 مرتضی",
            "last_name": "امینی",
            "username": "Morteza0amini",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -6006870404458613000,
            "phone": "989126216486",
            "bot_nochats": false
        },
        {
            "id": 6569704848,
            "type": "user",
            "first_name": "888 siros heidari starparts",
            "username": "Sirous_heydari",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -8278423391623524000,
            "phone": "989191656619",
            "bot_nochats": false
        },
        {
            "id": 1182204419,
            "type": "user",
            "first_name": "888 mohammadreza shafiei",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -7341030729764615000,
            "phone": "989120727861",
            "bot_nochats": false
        },
        {
            "id": 98037639,
            "type": "user",
            "first_name": "00 Alireza",
            "last_name": "Ghanbili",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 996252584176371700,
            "phone": "989121183237",
            "bot_nochats": false
        },
        {
            "id": 94890702,
            "type": "user",
            "first_name": "888 moradian yaftabad",
            "username": "hosien_moradian2014",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -5472398839861669000,
            "phone": "989124053952",
            "bot_nochats": false
        },
        {
            "id": 333985502,
            "type": "user",
            "first_name": "XXX Kiavash Telegram",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 1923077512757912800,
            "phone": "989373114133",
            "bot_nochats": false
        },
        {
            "id": 1430254647,
            "type": "user",
            "first_name": "xxx danial namadi",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -7957459226974167000,
            "phone": "989124468120",
            "bot_nochats": false
        },
        {
            "id": 232959307,
            "type": "user",
            "first_name": "888 modanlo",
            "username": "Hyukia_spare",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -803508813480542000,
            "phone": "989013164229",
            "bot_nochats": false
        },
        {
            "id": 1073742610,
            "type": "user",
            "first_name": "فروشگاه هیوندای افق",
            "username": "HyundaiOFOGH",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusOffline",
                "was_online": 1709890155
            },
            "access_hash": -5389938028995841000,
            "phone": "989929596929",
            "bot_nochats": false
        },
        {
            "id": 517456841,
            "type": "user",
            "first_name": "Xxx",
            "last_name": "Abedini",
            "username": "AA09128794270",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 1799872938677048600,
            "phone": "989128794270",
            "bot_nochats": false
        },
        {
            "id": 69633690,
            "type": "user",
            "first_name": "00 Hamid",
            "last_name": "Eslami",
            "username": "hamidrezaeslami7750",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 6383298123046212000,
            "phone": "989123047750",
            "bot_nochats": false
        },
        {
            "id": 1192531116,
            "type": "user",
            "first_name": "Xxx Arash Daftar",
            "last_name": "Hosseini",
            "username": "Arashsjam",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -936224897334665900,
            "phone": "989124027753",
            "bot_nochats": false
        },
        {
            "id": 89253175,
            "type": "user",
            "first_name": "sssSasan",
            "last_name": "Yadakcenter",
            "username": "sasan_h9756",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 6459731423824990000,
            "phone": "989126981491",
            "bot_nochats": false
        },
        {
            "id": 164549161,
            "type": "user",
            "first_name": "xxx Hamid Zarrabi",
            "username": "Hamid_zarrabii",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -6098010638218170000,
            "phone": "989125371017",
            "bot_nochats": false
        },
        {
            "id": 175012689,
            "type": "user",
            "first_name": "00 محمد",
            "last_name": "رضایی",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 6327836420664610000,
            "phone": "989120855844",
            "bot_nochats": false
        },
        {
            "id": 587827104,
            "type": "user",
            "first_name": "888 reyhani",
            "username": "reyhanipart",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 1148209576891233900,
            "phone": "989191834924",
            "bot_nochats": false
        },
        {
            "id": 638486692,
            "type": "user",
            "first_name": "999 hosein khazali",
            "username": "Hoseinparts",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -8852470094520635000,
            "phone": "989129323006",
            "bot_nochats": false
        },
        {
            "id": 138683199,
            "type": "user",
            "first_name": "xxx naghashzade",
            "username": "Aminnnz",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -8268246632454957000,
            "phone": "989123348593",
            "bot_nochats": false
        },
        {
            "id": 641837508,
            "type": "user",
            "first_name": "999 bazargani akbari",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 370676271853326660,
            "phone": "989124041642",
            "bot_nochats": false
        },
        {
            "id": 5386688767,
            "type": "user",
            "first_name": "xxx nazari whatsapp",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -8604054453732531000,
            "phone": "989923334819",
            "bot_nochats": false
        },
        {
            "id": 32391928,
            "type": "user",
            "first_name": "888 amirsalar chobdaran almas part",
            "username": "Hadimahlooji",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 35916545122553932,
            "phone": "989125882838",
            "bot_nochats": false
        },
        {
            "id": 139970422,
            "type": "user",
            "first_name": "xxx mostafa azimi khodesh",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -145046766712703800,
            "phone": "989122834164",
            "bot_nochats": false
        },
        {
            "id": 94481159,
            "type": "user",
            "first_name": "999 reza khosravi",
            "username": "rezakhosravi1",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 3600688094134246000,
            "phone": "989367004413",
            "bot_nochats": false
        },
        {
            "id": 334971523,
            "type": "user",
            "first_name": "Zzz",
            "last_name": "Mohajer",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -958993338436601200,
            "phone": "989121027390",
            "bot_nochats": false
        },
        {
            "id": 99497229,
            "type": "user",
            "first_name": "888 ahmadreza seyedjavdi",
            "username": "Sjah7590",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 1527028265442379300,
            "phone": "989122068632",
            "bot_nochats": false
        },
        {
            "id": 199180432,
            "type": "user",
            "first_name": "00 آرش",
            "last_name": "غلامی",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -6356044130028792000,
            "phone": "989122957241",
            "bot_nochats": false
        },
        {
            "id": 6342661245,
            "type": "user",
            "first_name": "xxx aghayari whatsapp maghazs",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -819356835351775200,
            "phone": "989053353630",
            "bot_nochats": false
        },
        {
            "id": 97202551,
            "type": "user",
            "first_name": "999 mohammad salehi",
            "username": "mohammad_shoop",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -6091078187281669000,
            "phone": "989122426121",
            "bot_nochats": false
        },
        {
            "id": 417844745,
            "type": "user",
            "first_name": "00 Alireza",
            "last_name": "Mahosh",
            "username": "Dfgvck12ert62343",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -8568850979483762000,
            "phone": "989128346588",
            "bot_nochats": false
        },
        {
            "id": 105964482,
            "type": "user",
            "first_name": "XXX Afshin Asadi Yadakcenter",
            "username": "kia_af",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 7919951893979777000,
            "phone": "989355454932",
            "bot_nochats": false
        },
        {
            "id": 602070418,
            "type": "user",
            "first_name": "xxx sina karmand ashkan masahi",
            "username": "sina_al13",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -7831586449351320000,
            "phone": "989227329588",
            "bot_nochats": false
        },
        {
            "id": 437739989,
            "type": "user",
            "first_name": "XXX Ali",
            "last_name": "MirSane",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -3956717937724774000,
            "phone": "989128444589",
            "bot_nochats": false
        },
        {
            "id": 108255048,
            "type": "user",
            "first_name": "hoseini kurosh",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 4107804297122695700,
            "phone": "989123707890",
            "bot_nochats": false
        },
        {
            "id": 106264400,
            "type": "user",
            "first_name": "XXX Amirhossein",
            "last_name": "Khanjari",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -2880080013824809500,
            "phone": "989126133084",
            "bot_nochats": false
        },
        {
            "id": 988938769,
            "type": "user",
            "first_name": "xxx mz new",
            "username": "Part_star_original",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 8671192892182106000,
            "phone": "989218265287",
            "bot_nochats": false
        },
        {
            "id": 95761996,
            "type": "user",
            "first_name": "00 محمد مهدی",
            "last_name": "موسوی",
            "username": "Fastpart",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 6456039878191350000,
            "phone": "989122801004",
            "bot_nochats": false
        },
        {
            "id": 1201010435,
            "type": "user",
            "first_name": "karmand nima bakhshayesh",
            "username": "vafa_hiundai",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 3768692994171489000,
            "phone": "989129558132",
            "bot_nochats": false
        },
        {
            "id": 97343957,
            "type": "user",
            "first_name": "Morteza",
            "last_name": "Shams",
            "username": "morteza_shams204",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -1065834700735549600,
            "phone": "989362260930",
            "bot_nochats": false
        },
        {
            "id": 88808777,
            "type": "user",
            "first_name": "888 ashkan masahi",
            "username": "Carseen",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -2031422946787165700,
            "phone": "989122806788",
            "bot_nochats": false
        },
        {
            "id": 76731870,
            "type": "user",
            "first_name": "00 علی",
            "last_name": "کاشانی",
            "username": "ali_miri77",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 7073718050976103000,
            "phone": "989127146366",
            "bot_nochats": false
        },
        {
            "id": 414732911,
            "type": "user",
            "first_name": "00 Mostafa",
            "last_name": "Arash",
            "username": "Arash_Hyundai_Kia",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 1469455028266435600,
            "phone": "989128445460",
            "bot_nochats": false
        },
        {
            "id": 220117051,
            "type": "user",
            "first_name": "888 foad",
            "username": "autogenuine_foad",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -2628477440138915000,
            "phone": "989129596007",
            "bot_nochats": false
        },
        {
            "id": 99236219,
            "type": "user",
            "first_name": "Xxx Pouria",
            "last_name": "Yaghma",
            "username": "Djpouri",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 3721037017253416400,
            "phone": "989377766761",
            "bot_nochats": false
        },
        {
            "id": 130141039,
            "type": "user",
            "first_name": "888 amir zaderayat",
            "username": "zadehraya",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 3741673590226534000,
            "phone": "989121671264",
            "bot_nochats": false
        },
        {
            "id": 104220250,
            "type": "user",
            "first_name": "Fariborz Megan Yadakcenter",
            "username": "Fariborz6771",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -706405514691302300,
            "phone": "989123952851",
            "bot_nochats": false
        },
        {
            "id": 645977156,
            "type": "user",
            "first_name": "00 Orginal Part",
            "last_name": "Heidari",
            "username": "orginalpart_heydari",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -1341032200829002000,
            "phone": "989374444725",
            "bot_nochats": false
        },
        {
            "id": 425211545,
            "type": "user",
            "first_name": "00 علی",
            "last_name": "خاقانی",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 8819474910021116000,
            "phone": "989127095231",
            "bot_nochats": false
        },
        {
            "id": 75145591,
            "type": "user",
            "first_name": "Sharifi XXX",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -940473515564958500,
            "phone": "989125473811",
            "bot_nochats": false
        },
        {
            "id": 757844430,
            "type": "user",
            "first_name": "999 amin taghavi",
            "username": "genuinepart_hyundai",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -5228364158680936000,
            "phone": "989120045088",
            "bot_nochats": false
        },
        {
            "id": 91169005,
            "type": "user",
            "first_name": "XXX Mohammad Ahmadi",
            "username": "M_ahmadi11152",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 5127643380556715000,
            "phone": "989122142545",
            "bot_nochats": false
        },
        {
            "id": 215971844,
            "type": "user",
            "first_name": "00 مهرداد",
            "last_name": "محتوی",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -5430260652961921000,
            "phone": "989197051073",
            "bot_nochats": false
        },
        {
            "id": 5027909316,
            "type": "user",
            "first_name": "Foroshgah Nor",
            "last_name": "Yadakcenter",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -8215723112575698000,
            "phone": "989125940150",
            "bot_nochats": false
        },
        {
            "id": 132412494,
            "type": "user",
            "first_name": "00 امیر شیرال",
            "last_name": "محمد",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 3046055361028242400,
            "phone": "989122076603",
            "bot_nochats": false
        },
        {
            "id": 536996669,
            "type": "user",
            "first_name": "888 محمد محمدی تهرانپارس",
            "username": "HYUNDAKIAYADAKI",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -4968274627687590000,
            "phone": "989121729362",
            "bot_nochats": false
        },
        {
            "id": 18592886,
            "type": "user",
            "first_name": "00 ناصر",
            "last_name": "مهرشاد",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 5955245123195269000,
            "phone": "989122219246",
            "bot_nochats": false
        },
        {
            "id": 484488861,
            "type": "user",
            "first_name": "999 sajjad kosha khodro",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 2733595680518911000,
            "phone": "989124219266",
            "bot_nochats": false
        },
        {
            "id": 763348002,
            "type": "user",
            "first_name": "00 امیر",
            "last_name": "نقدی",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -8204081353217293000,
            "phone": "989195727274",
            "bot_nochats": false
        },
        {
            "id": 116573609,
            "type": "user",
            "first_name": "00 مهرشاد",
            "last_name": "رحمتی",
            "username": "Mehrshad_Rahmati_etminan",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 6654820819782512000,
            "phone": "989109003519",
            "bot_nochats": false
        },
        {
            "id": 454627845,
            "type": "user",
            "first_name": "XXX Behzad",
            "last_name": "Shahbandi",
            "username": "shahr_yaadak",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -2127327143777776600,
            "phone": "989362142222",
            "bot_nochats": false
        },
        {
            "id": 5436406344,
            "type": "user",
            "first_name": "Ebrahim Aligoli Karmand Ghadim",
            "last_name": "Azimi",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 6952646176458799000,
            "phone": "989120038969",
            "bot_nochats": false
        },
        {
            "id": 660568597,
            "type": "user",
            "first_name": "ArmannnN",
            "username": "Arman_rhmm",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 2737649230099400700,
            "phone": "989125997146",
            "bot_nochats": false
        },
        {
            "id": 87563313,
            "type": "user",
            "first_name": "00 Mehrdad Shah",
            "last_name": "Nazari",
            "username": "MD_SH1191",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 2647552148873266700,
            "phone": "989121931191",
            "bot_nochats": false
        },
        {
            "id": 105758746,
            "type": "user",
            "first_name": "00 حامد رحیم",
            "last_name": "زاده",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 3374336072696961500,
            "phone": "989123623932",
            "bot_nochats": false
        },
        {
            "id": 98235567,
            "type": "user",
            "first_name": "Xxx Ali",
            "last_name": "Rahimi",
            "username": "ALIRAHIMI7",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -3281497466782355000,
            "phone": "989123904719",
            "bot_nochats": false
        },
        {
            "id": 1080628131,
            "type": "user",
            "first_name": "00 میثاق",
            "last_name": "رعیت",
            "username": "misagh_rayat",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -6451116443153297000,
            "phone": "989121076453",
            "bot_nochats": false
        },
        {
            "id": 753198821,
            "type": "user",
            "first_name": "00 pouria nilchi karmand behnam",
            "username": "itspouriachamp",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 861065968530991200,
            "phone": "989191170200",
            "bot_nochats": false
        },
        {
            "id": 757238495,
            "type": "user",
            "first_name": "00 مصطفی",
            "last_name": "صادقی",
            "username": "ryiapart",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 7733940617303877000,
            "phone": "989032350313",
            "bot_nochats": false
        },
        {
            "id": 135792954,
            "type": "user",
            "first_name": "Xxx Vahid",
            "last_name": "Shahram",
            "username": "vahidhyundai",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -9012213028575090000,
            "phone": "989122105063",
            "bot_nochats": false
        },
        {
            "id": 260670913,
            "type": "user",
            "first_name": "Valadkhani Aria Yadakcenter",
            "username": "Aryaparts",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 721485026467327900,
            "phone": "989107656612",
            "bot_nochats": false
        },
        {
            "id": 55731130,
            "type": "user",
            "first_name": "کارمند مصطفی عظیمی ( حسن",
            "last_name": "رجبی)",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 5173045968022445000,
            "phone": "989373009092",
            "bot_nochats": false
        },
        {
            "id": 6228967927,
            "type": "user",
            "first_name": "XXX Farid",
            "last_name": "Shekari",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 2464620239685784000,
            "phone": "989125265928",
            "bot_nochats": false
        },
        {
            "id": 97195200,
            "type": "user",
            "first_name": "Xxx Foroshgah",
            "last_name": "Nor",
            "username": "HAD9045",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 9206337579674917000,
            "phone": "989124768447",
            "bot_nochats": false
        },
        {
            "id": 95412424,
            "type": "user",
            "first_name": "00 Farnam",
            "last_name": "Davar",
            "username": "Tehranyadak_Davar",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -8016443698560642000,
            "phone": "989122490854",
            "bot_nochats": false
        },
        {
            "id": 180323765,
            "type": "user",
            "first_name": "karmand ali ghasemi",
            "username": "alirreza_78_rahimi",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 7484689938630593000,
            "phone": "989037532010",
            "bot_nochats": false
        },
        {
            "id": 391693707,
            "type": "user",
            "first_name": "00 Chobdaran",
            "last_name": "Danial",
            "username": "Daniyal_AtlasPart",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 3617972408938018300,
            "phone": "989129498612",
            "bot_nochats": false
        },
        {
            "id": 812107825,
            "type": "user",
            "first_name": "00 امیررضا",
            "last_name": "موسوی",
            "username": "cruiseparts",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -2759312308327456300,
            "phone": "989122722791",
            "bot_nochats": false
        },
        {
            "id": 73273798,
            "type": "user",
            "first_name": "888 behzad gholami",
            "username": "Behzadgholami",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 310652596349788800,
            "phone": "989122451318",
            "bot_nochats": false
        },
        {
            "id": 1592705607,
            "type": "user",
            "first_name": "888 aliakbar kashani",
            "username": "Hyundai_kashani",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -2064762918657631500,
            "phone": "989025002615",
            "bot_nochats": false
        },
        {
            "id": 379631832,
            "type": "user",
            "first_name": "آقای فرزین",
            "last_name": "جورمحمد",
            "username": "Farzinhosseinii",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 8857103587484409000,
            "phone": "989125442380",
            "bot_nochats": false
        },
        {
            "id": 613950339,
            "type": "user",
            "first_name": "00 ارسلان",
            "last_name": "عابدی",
            "username": "omid_aabedi",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -2025682295653489000,
            "phone": "989013134139",
            "bot_nochats": false
        },
        {
            "id": 1886396428,
            "type": "user",
            "first_name": "Ali",
            "last_name": "Almas part۲",
            "username": "almaspart2",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 3034189181232422400,
            "phone": "989125880838",
            "bot_nochats": false
        },
        {
            "id": 5481145355,
            "type": "user",
            "first_name": "هیوندا تابان",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -5864666364306975000,
            "phone": "989039408518",
            "bot_nochats": false
        },
        {
            "id": 189552839,
            "type": "user",
            "first_name": "Saeed",
            "last_name": "Fooladi",
            "username": "saeedfooladi77",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -2556745387101646000,
            "phone": "989126004085",
            "bot_nochats": false
        },
        {
            "id": 104417882,
            "type": "user",
            "first_name": "00 Sam",
            "last_name": "Alamtalab",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 865080948239463700,
            "phone": "989126709446",
            "bot_nochats": false
        },
        {
            "id": 63880540,
            "type": "user",
            "first_name": "Xxx Amir",
            "last_name": "Ashjari",
            "username": "AAB74",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -6521722013220233000,
            "phone": "989127244736",
            "bot_nochats": false
        },
        {
            "id": 109854803,
            "type": "user",
            "first_name": "888 مصطفی افشاری",
            "verified": false,
            "restricted": false,
            "access_hash": 3297830188933591000,
            "phone": "989126842139",
            "bot_nochats": false
        },
        {
            "id": 282591536,
            "type": "user",
            "first_name": "00 Reza",
            "last_name": "Sadeghi",
            "username": "REZADAYOU",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -7716142035330937000,
            "phone": "989123011592",
            "bot_nochats": false
        },
        {
            "id": 315133766,
            "type": "user",
            "first_name": "00 Mehran",
            "last_name": "Salimi",
            "username": "smehran",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -4688711817162364000,
            "phone": "989121890622",
            "bot_nochats": false
        },
        {
            "id": 102290412,
            "type": "user",
            "first_name": "Abramian",
            "last_name": "Yadakcenter",
            "username": "partcenter_abramian",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 119942642152292700,
            "phone": "989127609441",
            "bot_nochats": false
        },
        {
            "id": 5711567722,
            "type": "user",
            "first_name": "xxx parseh maghaze",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 9136693509863757000,
            "phone": "989924926761",
            "bot_nochats": false
        },
        {
            "id": 92568457,
            "type": "user",
            "first_name": "آقای دارابی بازار اوانته ورنا",
            "last_name": "کار",
            "username": "Hamid_darabi",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -2817201184774168000,
            "phone": "989121204505",
            "bot_nochats": false
        },
        {
            "id": 56735286,
            "type": "user",
            "first_name": "888 davoud kashani",
            "username": "davoud28",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 889723805061282800,
            "phone": "989121503975",
            "bot_nochats": false
        },
        {
            "id": 1141639630,
            "type": "user",
            "first_name": "888 ali ghasmi maghazs",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -2026765090272054500,
            "phone": "989371068086",
            "bot_nochats": false
        },
        {
            "id": 128976713,
            "type": "user",
            "first_name": "00 احسان",
            "last_name": "احدی",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -5883618841700593000,
            "phone": "989124200719",
            "bot_nochats": false
        },
        {
            "id": 220840158,
            "type": "user",
            "first_name": "888 hosein adibi",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 1760713201630042600,
            "phone": "989123099830",
            "bot_nochats": false
        },
        {
            "id": 111727900,
            "type": "user",
            "first_name": "00 فرزان",
            "last_name": "امیدی",
            "username": "Far_zaan",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 5894281268489003000,
            "phone": "989122882548",
            "bot_nochats": false
        },
        {
            "id": 64938363,
            "type": "user",
            "first_name": "XXX Samiei",
            "last_name": "Zafarghnadi",
            "username": "SameiZafarghandi",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -3215398682909310500,
            "phone": "989352167300",
            "bot_nochats": false
        },
        {
            "id": 1955756241,
            "type": "user",
            "first_name": "amirhoseine shayan",
            "username": "Amirhossein_Alireza",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 3278150234938877000,
            "phone": "989127245033",
            "bot_nochats": false
        },
        {
            "id": 1118709971,
            "type": "user",
            "first_name": "XXX Farshad Bahrami Autopart",
            "username": "farshadbahrami60",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -6565669949269112000,
            "phone": "989128909141",
            "bot_nochats": false
        },
        {
            "id": 6349875649,
            "type": "user",
            "first_name": "00 مهدی",
            "last_name": "صمدی",
            "username": "Karen1040",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -7751784840711792000,
            "phone": "989121040856",
            "bot_nochats": false
        },
        {
            "id": 80027007,
            "type": "user",
            "first_name": "00 Mohamad",
            "last_name": "Mozaffari",
            "username": "mohammadmozaffari74",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -743531967404117600,
            "phone": "989372406641",
            "bot_nochats": false
        },
        {
            "id": 120351217,
            "type": "user",
            "first_name": "00 Alireza",
            "last_name": "Mofarrah",
            "username": "hyundai_kia_mofarah",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 6603942551016433000,
            "phone": "989127905480",
            "bot_nochats": false
        },
        {
            "id": 152232139,
            "type": "user",
            "first_name": "00 محمدرضا",
            "last_name": "براتی",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -7731152013816587000,
            "phone": "989128081174",
            "bot_nochats": false
        },
        {
            "id": 143813676,
            "type": "user",
            "first_name": "999 mohammad reza kashani",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -7753440480510264000,
            "phone": "989128651843",
            "bot_nochats": false
        },
        {
            "id": 92647204,
            "type": "user",
            "first_name": "00 محمدرضا گرجی",
            "last_name": "دوز",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -444604180756683970,
            "phone": "989121068465",
            "bot_nochats": false
        },
        {
            "id": 157722670,
            "type": "user",
            "first_name": "999 javad tarkhorani",
            "username": "javadtarkhoraico",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 1360250919889564400,
            "phone": "989122882825",
            "bot_nochats": false
        },
        {
            "id": 556110047,
            "type": "user",
            "first_name": "999 mohammad amin enayat pour",
            "username": "mohamadamin_en",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -4388825851558340600,
            "phone": "989365725824",
            "bot_nochats": false
        },
        {
            "id": 107463150,
            "type": "user",
            "first_name": "XXX IranShahi Mehdi",
            "username": "Mehdiiranshahi",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -1108360064553543000,
            "phone": "989126400736",
            "bot_nochats": false
        },
        {
            "id": 355735966,
            "type": "user",
            "first_name": "00 سهند",
            "last_name": "زمانی",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 3205118058213646000,
            "phone": "989217966967",
            "bot_nochats": false
        },
        {
            "id": 118220892,
            "type": "user",
            "first_name": "xxx mojtaba rashtpar",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -5269726081097367000,
            "phone": "989121323205",
            "bot_nochats": false
        },
        {
            "id": 748330048,
            "type": "user",
            "first_name": "xxx mohsen bakhtiari",
            "username": "baradaran_hyundai",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 7609173460825441000,
            "phone": "989354111730",
            "bot_nochats": false
        },
        {
            "id": 111236173,
            "type": "user",
            "first_name": "999 vahid gazori",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -8514449607776066000,
            "phone": "989121026776",
            "bot_nochats": false
        },
        {
            "id": 181282713,
            "type": "user",
            "first_name": "00 حسین",
            "last_name": "عربی",
            "username": "arabistoreparts",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 5932313155728472000,
            "phone": "989129150684",
            "bot_nochats": false
        },
        {
            "id": 609176537,
            "type": "user",
            "first_name": "00 Kolbr",
            "last_name": "Hyundai",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 6899855574621770000,
            "phone": "989129727393",
            "bot_nochats": false
        },
        {
            "id": 119996621,
            "type": "user",
            "first_name": "00 مرتضی",
            "last_name": "نامی",
            "username": "Morinami",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 4259983989040484000,
            "phone": "989123172901",
            "bot_nochats": false
        },
        {
            "id": 147268099,
            "type": "user",
            "first_name": "xxx ebrahim rezvani",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 2707003649177130500,
            "phone": "989125804955",
            "bot_nochats": false
        },
        {
            "id": 256751420,
            "type": "user",
            "first_name": "00 رضا",
            "last_name": "خاقانی",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -5547007564325717000,
            "phone": "989125884189",
            "bot_nochats": false
        },
        {
            "id": 171904428,
            "type": "user",
            "first_name": "00 سپهر",
            "last_name": "نجفی",
            "username": "sepehr_najafi",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -3901306871673523000,
            "phone": "989128494668",
            "bot_nochats": false
        },
        {
            "id": 461280851,
            "type": "user",
            "first_name": "Xxx Bahram",
            "last_name": "Hoseini",
            "username": "spareparts_sinabasijed",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 7187069245743562000,
            "phone": "989124109952",
            "bot_nochats": false
        },
        {
            "id": 111967646,
            "type": "user",
            "first_name": "Whatsapp Web",
            "last_name": "00041",
            "username": "samarimostafa",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 7726278088778631000,
            "phone": "989127193258",
            "bot_nochats": false
        },
        {
            "id": 1370197834,
            "type": "user",
            "first_name": "888 mohammad hosein shahdadi",
            "username": "shahdadpart45",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -6112706582456863000,
            "phone": "989368440439",
            "bot_nochats": false
        },
        {
            "id": 102364027,
            "type": "user",
            "first_name": "00 مهدی دارابی پاساژ",
            "last_name": "کیان",
            "username": "Me13da56",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 2356889993338913000,
            "phone": "989123484643",
            "bot_nochats": false
        },
        {
            "id": 73300479,
            "type": "user",
            "first_name": "999 afshin arabi",
            "username": "Arabi_spc",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 4952421621425177000,
            "phone": "989121067462",
            "bot_nochats": false
        },
        {
            "id": 163116142,
            "type": "user",
            "first_name": "888 همون جلالی بازرگانی مهرداد",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -8406102508039100000,
            "phone": "989121872781",
            "bot_nochats": false
        },
        {
            "id": 100676997,
            "type": "user",
            "first_name": "xxx pesar hamid abbasali pour",
            "username": "Tahaalamdari",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 3698320206382725000,
            "phone": "989379390808",
            "bot_nochats": false
        },
        {
            "id": 81867859,
            "type": "user",
            "first_name": "00 علیرضا",
            "last_name": "دارابی",
            "username": "Hyundai_Part_shop",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 1737754369296080400,
            "phone": "989122309187",
            "bot_nochats": false
        },
        {
            "id": 704530313,
            "type": "user",
            "first_name": "00 Hamid",
            "last_name": "Salamt",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 8166222554368584000,
            "phone": "989125974584",
            "bot_nochats": false
        },
        {
            "id": 129003097,
            "type": "user",
            "first_name": "xxx rahman zaderayt",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 2190621803085346300,
            "phone": "989122107355",
            "bot_nochats": false
        },
        {
            "id": 89042879,
            "type": "user",
            "first_name": "999 mehdi sharidi foroshgah shayan",
            "username": "mehdi57i",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 2947322393258551300,
            "phone": "989125547832",
            "bot_nochats": false
        },
        {
            "id": 5496151651,
            "type": "user",
            "first_name": "فروشگاه سئول",
            "username": "foroshgah_seoul",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 4371472102008711000,
            "phone": "989352115751",
            "bot_nochats": false
        },
        {
            "id": 5296221228,
            "type": "user",
            "first_name": "00 Meraj",
            "last_name": "Minai",
            "username": "meraj_miin",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 545724237445776830,
            "phone": "989128160452",
            "bot_nochats": false
        },
        {
            "id": 155221209,
            "type": "user",
            "first_name": "Xxx Hadi",
            "last_name": "Abassali",
            "username": "Hadi_orouji",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -1765993970467808300,
            "phone": "989122349816",
            "bot_nochats": false
        },
        {
            "id": 85083517,
            "type": "user",
            "first_name": "888 alireza tarokh",
            "username": "AlirezaTarokh",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -675351440720302600,
            "phone": "989129478949",
            "bot_nochats": false
        },
        {
            "id": 5725379172,
            "type": "user",
            "first_name": "Iranyadak -",
            "last_name": "Navid",
            "username": "Iranyadak_khalili1",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -5734178644301824000,
            "phone": "989331057669",
            "bot_nochats": false
        },
        {
            "id": 108071812,
            "type": "user",
            "first_name": "00 Kamal",
            "last_name": "Dadgar",
            "username": "kia_yadak",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 271796646996300400,
            "phone": "989125196352",
            "bot_nochats": false
        },
        {
            "id": 71114790,
            "type": "user",
            "first_name": "00 محمد صادق",
            "last_name": "فولادی",
            "username": "sadeghf2",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -3022875543855099000,
            "phone": "989128380035",
            "bot_nochats": false
        },
        {
            "id": 143060591,
            "type": "user",
            "first_name": "Xxx Zolfkhni",
            "last_name": "Alireza",
            "username": "alireza_zkh",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 1714567419979643400,
            "phone": "989120186381",
            "bot_nochats": false
        },
        {
            "id": 6535836245,
            "type": "user",
            "first_name": "888 vahab sajadi",
            "username": "mrpartsshop",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -2647872421995814400,
            "phone": "989355089100",
            "bot_nochats": false
        },
        {
            "id": 120882109,
            "type": "user",
            "first_name": "XXX Htm Baran bolorian",
            "username": "Htm_baran",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 6312428524450480000,
            "phone": "989122803721",
            "bot_nochats": false
        },
        {
            "id": 99791618,
            "type": "user",
            "first_name": "xxx kamyar kojori",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 4439888689585132500,
            "phone": "989121508890",
            "bot_nochats": false
        },
        {
            "id": 254246942,
            "type": "user",
            "first_name": "xxx shayan oskoyi",
            "username": "shayanoskooi",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 8218547448583443000,
            "phone": "989123942471",
            "bot_nochats": false
        },
        {
            "id": 111233087,
            "type": "user",
            "first_name": "xxx hameeeed chehregosha",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -5063803357916073000,
            "phone": "989122142700",
            "bot_nochats": false
        },
        {
            "id": 393006978,
            "type": "user",
            "first_name": "xxx arsalan abedi (yadakkar+ dadash ashkan)",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 6736829754126269000,
            "phone": "989124274762",
            "bot_nochats": false
        },
        {
            "id": 97811715,
            "type": "user",
            "first_name": "00 مهدی",
            "last_name": "احمدوند",
            "username": "MehdiAhmadvand61",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 2043260495448518100,
            "phone": "989198919963",
            "bot_nochats": false
        },
        {
            "id": 93593818,
            "type": "user",
            "first_name": "XXX Alireza",
            "last_name": "Karimi",
            "username": "Alirezakarimi1371",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 3366146594995537400,
            "phone": "989123347514",
            "bot_nochats": false
        },
        {
            "id": 379496107,
            "type": "user",
            "first_name": "Akbar Shams",
            "last_name": "Cheraghbargh",
            "username": "Shamsakbar",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 3004580597861703000,
            "phone": "989122837542",
            "bot_nochats": false
        },
        {
            "id": 136094169,
            "type": "user",
            "first_name": "00 آرش",
            "last_name": "وصالی",
            "username": "Akavianpart",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -7653199129773915000,
            "phone": "989021720421",
            "bot_nochats": false
        },
        {
            "id": 661419431,
            "type": "user",
            "first_name": "Bakhtiari",
            "last_name": "Yadakcenter",
            "username": "shopbakhtiariRb2",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 6696016393335243000,
            "phone": "989122877577",
            "bot_nochats": false
        },
        {
            "id": 552266828,
            "type": "user",
            "first_name": "eini",
            "last_name": "ali salehi",
            "username": "Hyundaicheraghbargh",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -6069128788653537000,
            "phone": "989125473916",
            "bot_nochats": false
        },
        {
            "id": 561330133,
            "type": "user",
            "first_name": "علیرضا باقری ایران",
            "last_name": "اتوپارت",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -3222630772389963300,
            "phone": "989354361316",
            "bot_nochats": false
        },
        {
            "id": 895807269,
            "type": "user",
            "first_name": "Amirhasan",
            "last_name": "Amini",
            "username": "Amirhasanw",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -7718360456874223000,
            "phone": "989057655685",
            "bot_nochats": false
        },
        {
            "id": 1869176823,
            "type": "user",
            "first_name": "xxx bazargani",
            "last_name": "akbari",
            "username": "Bazargani_Ahmadi_2021",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -6032505210417422000,
            "phone": "989396225818",
            "bot_nochats": false
        },
        {
            "id": 78412560,
            "type": "user",
            "first_name": "888 mohsen rahmani",
            "username": "Mohsen001001",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -8525823089216482000,
            "phone": "989122360846",
            "bot_nochats": false
        },
        {
            "id": 109157168,
            "type": "user",
            "first_name": "00",
            "last_name": "Dadvar",
            "username": "karandadvar",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -4352044547385508400,
            "phone": "989123114212",
            "bot_nochats": false
        },
        {
            "id": 209345684,
            "type": "user",
            "first_name": "Ali Tehran",
            "last_name": "Fabric",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -8777612999725535000,
            "phone": "989123967202",
            "bot_nochats": false
        },
        {
            "id": 1150046792,
            "type": "user",
            "first_name": "Xxx Saeed Cheragh",
            "last_name": "Sport",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 1310879453970868700,
            "phone": "989122843948",
            "bot_nochats": false
        },
        {
            "id": 1428504544,
            "type": "user",
            "first_name": "xxx gorji daftar",
            "username": "PERSIAN_PARTS_TRADING",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 1157858858733833200,
            "phone": "989378285685",
            "bot_nochats": false
        },
        {
            "id": 134810924,
            "type": "user",
            "first_name": "XXX Nima",
            "last_name": "Bakhshayesh",
            "username": "ariyapartorg",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 2331210534749396500,
            "phone": "989127660564",
            "bot_nochats": false
        },
        {
            "id": 1147397507,
            "type": "user",
            "first_name": "888 حسین ودادی ستارخان",
            "username": "KoreaYadakMohsen",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 5118527128000534000,
            "phone": "989386443552",
            "bot_nochats": false
        },
        {
            "id": 90176875,
            "type": "user",
            "first_name": "xxx ebrahim jourmohammadi",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 2915274882523901000,
            "phone": "989121857493",
            "bot_nochats": false
        },
        {
            "id": 80251850,
            "type": "user",
            "first_name": "Xxx Siavash",
            "last_name": "Amiri",
            "username": "KiaRun",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 483226325864988700,
            "phone": "989126851867",
            "bot_nochats": false
        },
        {
            "id": 108614515,
            "type": "user",
            "first_name": "Behnam Sampart",
            "last_name": "Yadakcentet",
            "username": "BEHNAMSOHRABIAMIN",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -5775650460025222000,
            "phone": "989125181587",
            "bot_nochats": false
        },
        {
            "id": 246553131,
            "type": "user",
            "first_name": "xxx mirvakili",
            "username": "mvcoparts1",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -9105848735539281000,
            "phone": "989025029290",
            "bot_nochats": false
        },
        {
            "id": 92999843,
            "type": "user",
            "first_name": "Hamkar",
            "last_name": "Yadakcenter",
            "username": "EHSAN_ASGARI7",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 3206969417898083300,
            "phone": "989124895768",
            "bot_nochats": false
        },
        {
            "id": 493458106,
            "type": "user",
            "first_name": "Xxx Mahdi",
            "last_name": "Parpanji",
            "username": "Enginpart",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -8344495580150021000,
            "phone": "989120220806",
            "bot_nochats": false
        },
        {
            "id": 96598946,
            "type": "user",
            "first_name": "888 saeed jafari",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 4285111357857692000,
            "phone": "989124898504",
            "bot_nochats": false
        },
        {
            "id": 1100089663,
            "type": "user",
            "first_name": "XXX",
            "last_name": "Razavi",
            "username": "Tehran_partss",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 4134665620216691700,
            "phone": "989303106309",
            "bot_nochats": false
        },
        {
            "id": 95050550,
            "type": "user",
            "first_name": "Xxx SafarZade Ctr",
            "last_name": "List",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 6719015732785985000,
            "phone": "989121474526",
            "bot_nochats": false
        },
        {
            "id": 109620538,
            "type": "user",
            "first_name": "00 Alireza Mokhtar",
            "last_name": "Parse",
            "username": "Ali_R_M1987",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -1748207275413641000,
            "phone": "989123496232",
            "bot_nochats": false
        },
        {
            "id": 201154154,
            "type": "user",
            "first_name": "Javad",
            "last_name": "Kiavash",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -5497366489309202000,
            "phone": "989133826155",
            "bot_nochats": false
        },
        {
            "id": 102670151,
            "type": "user",
            "first_name": "XXX iman",
            "last_name": "Gholami",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -2661416446470838300,
            "phone": "989121464366",
            "bot_nochats": false
        },
        {
            "id": 118030629,
            "type": "user",
            "first_name": "xxx haji mosalreza",
            "username": "javad_m_1361",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 6622572230181930000,
            "phone": "989121445993",
            "bot_nochats": false
        },
        {
            "id": 5555724076,
            "type": "user",
            "first_name": "xxx amir bakhtiari",
            "username": "Gensis_savaran",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 7370630212788303000,
            "phone": "989129200604",
            "bot_nochats": false
        },
        {
            "id": 67284925,
            "type": "user",
            "first_name": "00 Reza",
            "last_name": "Norian",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -8266868833250949000,
            "phone": "989125006203",
            "bot_nochats": false
        },
        {
            "id": 114664652,
            "type": "user",
            "first_name": "xxx Masoud Khademi",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -2715741067353081000,
            "phone": "989123543866",
            "bot_nochats": false
        },
        {
            "id": 382147570,
            "type": "user",
            "first_name": "888 foroshagh hamed",
            "username": "hhyundaiyadak",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -8061888086226198000,
            "phone": "989197928309",
            "bot_nochats": false
        },
        {
            "id": 1171560646,
            "type": "user",
            "first_name": "888 reza vabaste",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusLastWeek"
            },
            "access_hash": -8319331946984918000,
            "phone": "989127470160",
            "bot_nochats": false
        },
        {
            "id": 75509123,
            "type": "user",
            "first_name": "XXX Ashkan Rahbari Kia",
            "username": "AshkanRahbari68",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 291093427230793200,
            "phone": "989121788301",
            "bot_nochats": false
        },
        {
            "id": 107914006,
            "type": "user",
            "first_name": "Abozari",
            "last_name": "Yadakcenter",
            "username": "mehdi293",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -1283955944017205500,
            "phone": "989126235158",
            "bot_nochats": false
        },
        {
            "id": 90565491,
            "type": "user",
            "first_name": "Xxx Amir Ahadi",
            "last_name": "Sebghat",
            "username": "sebghathyundai",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 1744901500331784000,
            "phone": "989122333493",
            "bot_nochats": false
        },
        {
            "id": 304403172,
            "type": "user",
            "first_name": "00 محمدرضا",
            "last_name": "شفیعی",
            "username": "mohamadreza30912",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 4700463393038335000,
            "phone": "989126147497",
            "bot_nochats": false
        },
        {
            "id": 447111485,
            "type": "user",
            "first_name": "888 afshin karimi automobilran",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -1603474494995661600,
            "phone": "989123906994",
            "bot_nochats": false
        },
        {
            "id": 75495983,
            "type": "user",
            "first_name": "888 amir nor mohammadi",
            "username": "amirnm123",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -5725835283941426000,
            "phone": "989199090989",
            "bot_nochats": false
        },
        {
            "id": 120047319,
            "type": "user",
            "first_name": "پرشین پارت علی عطایی همکار",
            "username": "ALIATAEE991",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -3351087024634511000,
            "phone": "989123655320",
            "bot_nochats": false
        },
        {
            "id": 111826149,
            "type": "user",
            "first_name": "xxx arash aidin",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 64026610849052350,
            "phone": "989127981385",
            "bot_nochats": false
        },
        {
            "id": 386095253,
            "type": "user",
            "first_name": "00 بابک",
            "last_name": "آقایاری",
            "username": "Babak_aghayari",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 2262106996557211600,
            "phone": "989121386521",
            "bot_nochats": false
        },
        {
            "id": 394558004,
            "type": "user",
            "first_name": "888 mahdi samadi",
            "username": "Karen_mst",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -3816295740974856000,
            "phone": "989121371326",
            "bot_nochats": false
        },
        {
            "id": 2096313114,
            "type": "user",
            "first_name": "xxx ezzat nejad",
            "username": "Tolopart",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -1999292707067811600,
            "phone": "989120893249",
            "bot_nochats": false
        },
        {
            "id": 118842898,
            "type": "user",
            "first_name": "00 آرش",
            "last_name": "کریمی",
            "username": "Arash_karimi17",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 520833256057299100,
            "phone": "989126903470",
            "bot_nochats": false
        },
        {
            "id": 862790280,
            "type": "user",
            "first_name": "Parsa",
            "last_name": "Fake",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusLastWeek"
            },
            "access_hash": 1731528407186993200,
            "phone": "989129627967",
            "bot_nochats": false
        },
        {
            "id": 109155586,
            "type": "user",
            "first_name": "888 alvand niki",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 5506728409510003000,
            "phone": "989128477871",
            "bot_nochats": false
        },
        {
            "id": 129578963,
            "type": "user",
            "first_name": "Babak",
            "username": "Babak66safari",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 5742330225640069000,
            "phone": "989127204134",
            "bot_nochats": false
        },
        {
            "id": 107977504,
            "type": "user",
            "first_name": "xxx mohammad gohari",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 2602975879887466500,
            "phone": "989121273609",
            "bot_nochats": false
        },
        {
            "id": 415998475,
            "type": "user",
            "first_name": "xxx vahid kiavash",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 4541753250314119700,
            "phone": "989354009767",
            "bot_nochats": false
        },
        {
            "id": 1552701353,
            "type": "user",
            "first_name": "xxx khalil whats mahdi",
            "username": "IRANYADAKKHALILI",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusLastWeek"
            },
            "access_hash": -8495616783720606000,
            "phone": "989031704452",
            "bot_nochats": false
        },
        {
            "id": 739586526,
            "type": "user",
            "first_name": "XXX Afshin Asadi Yadakcenter",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 6961748588171656000,
            "phone": "989195738462",
            "bot_nochats": false
        },
        {
            "id": 108272985,
            "type": "user",
            "first_name": "xxx farid razavi",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -7518571553286461000,
            "phone": "989121195845",
            "bot_nochats": false
        },
        {
            "id": 61705819,
            "type": "user",
            "first_name": "Xxx Mansor",
            "last_name": "Hatami",
            "username": "Mansoor_hatami",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -1462698760402361900,
            "phone": "989122309410",
            "bot_nochats": false
        },
        {
            "id": 527643553,
            "type": "user",
            "first_name": "888 amir rezayi dost",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -4840593509140185000,
            "phone": "989126543168",
            "bot_nochats": false
        },
        {
            "id": 791405646,
            "type": "user",
            "first_name": "00 محمدرضا جهان",
            "last_name": "تاب",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 8154518262072241000,
            "phone": "989129725264",
            "bot_nochats": false
        },
        {
            "id": 246683290,
            "type": "user",
            "first_name": "xxx farhad kolahdoz",
            "username": "farhadkolahdooz",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -6289674182766054000,
            "phone": "989125408461",
            "bot_nochats": false
        },
        {
            "id": 123242858,
            "type": "user",
            "first_name": "00 Amiran",
            "last_name": "Zandi",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -7968962166669492000,
            "phone": "989122966967",
            "bot_nochats": false
        },
        {
            "id": 5692423034,
            "type": "user",
            "first_name": "XXX Darabi Verna Avante",
            "username": "Mahdi13_51db",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -2613200668877220000,
            "phone": "989121125795",
            "bot_nochats": false
        },
        {
            "id": 88594208,
            "type": "user",
            "first_name": "XXX Shayan Kia",
            "username": "shgh84",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -9123417636526573000,
            "phone": "989121480778",
            "bot_nochats": false
        },
        {
            "id": 5287317939,
            "type": "user",
            "first_name": "xxx mohsen",
            "last_name": "bakhtiari",
            "username": "Mohsen_bakh",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -3054003354597845500,
            "phone": "989124111730",
            "bot_nochats": false
        },
        {
            "id": 679086978,
            "type": "user",
            "first_name": "Xxx Mahdi",
            "last_name": "Bakhtiari",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 2871624268779912700,
            "phone": "989129200570",
            "bot_nochats": false
        },
        {
            "id": 64224212,
            "type": "user",
            "first_name": "00 Mohammad",
            "last_name": "Timaj",
            "username": "MohammadTimajchi",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -5625968363014687000,
            "phone": "989124351684",
            "bot_nochats": false
        },
        {
            "id": 75394869,
            "type": "user",
            "first_name": "00 Mohsen",
            "last_name": "Barati",
            "username": "Mohsenbarati56",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -4243627721922918400,
            "phone": "989126175662",
            "bot_nochats": false
        },
        {
            "id": 157167019,
            "type": "user",
            "first_name": "milad ghomashchi gorji",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -3062550947153092000,
            "phone": "989129230627",
            "bot_nochats": false
        },
        {
            "id": 80819267,
            "type": "user",
            "first_name": "00 Hamed Yaghot",
            "last_name": "Rang",
            "username": "hamedyr",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -6519442097051965000,
            "phone": "989127222588",
            "bot_nochats": false
        },
        {
            "id": 106771776,
            "type": "user",
            "first_name": "xxx mamad jormamadi",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -4340913305601378000,
            "phone": "989121052434",
            "bot_nochats": false
        },
        {
            "id": 123897934,
            "type": "user",
            "first_name": "888 javad azimi",
            "username": "Javadazimi580",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 7867380182306888000,
            "phone": "989123123423",
            "bot_nochats": false
        },
        {
            "id": 1898545571,
            "type": "user",
            "first_name": "Amirhossein mirzaee",
            "username": "Amiiiirhossein37",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 1185956294289643800,
            "bot_nochats": false
        },
        {
            "id": 27635146,
            "type": "user",
            "first_name": "ⓢⓐⓙⓐⓓ",
            "username": "sajadperfume",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -8797048620469975000,
            "bot_nochats": false
        },
        {
            "id": 114694836,
            "type": "user",
            "first_name": "مرتضی",
            "username": "mortezakazemi55",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 9085586448762341000,
            "phone": "989128882821",
            "bot_nochats": false
        },
        {
            "id": 1354776739,
            "type": "user",
            "first_name": "Hassani",
            "username": "hassaniyadak",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -6798169907476655000,
            "bot_nochats": false
        },
        {
            "id": 1111633692,
            "type": "user",
            "first_name": "Hosein",
            "last_name": "Hoshiyari",
            "username": "Hhhoseunnbg",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -5686217453926789000,
            "bot_nochats": false
        },
        {
            "id": 106068568,
            "type": "user",
            "first_name": "Erfan",
            "username": "Erfan_khoshhal",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -3766896486734943700,
            "phone": "989102661303",
            "bot_nochats": false
        },
        {
            "id": 53371081,
            "type": "user",
            "first_name": "Mohammad",
            "last_name": "Esmaili",
            "username": "M_Reza_599",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -4457424263924971500,
            "bot_nochats": false
        },
        {
            "id": 6662537570,
            "type": "user",
            "first_name": "فروشگاه کاشانی",
            "last_name": "هیوندایی",
            "username": "Reza_shahrokhi",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -7969303991279300000,
            "bot_nochats": false
        },
        {
            "id": 5265257413,
            "type": "user",
            "first_name": "Alireza Mardi",
            "username": "Alireza_Mardi",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 5740463172998474000,
            "phone": "989126783100",
            "bot_nochats": false
        },
        {
            "id": 1008674510,
            "type": "user",
            "first_name": "فروشگاه قطعات اصلی",
            "username": "heravipart",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 2762562884809989000,
            "bot_nochats": false
        },
        {
            "id": 118114176,
            "type": "user",
            "first_name": "Mehdi",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 2606328764710960000,
            "bot_nochats": false
        },
        {
            "id": 5416488842,
            "type": "user",
            "first_name": "Naser",
            "last_name": "Sales",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 852868890407804500,
            "bot_nochats": false
        },
        {
            "id": 354753504,
            "type": "user",
            "first_name": "Seoul part",
            "username": "Seoulpart1",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 1343501141074004000,
            "bot_nochats": false
        },
        {
            "id": 947108568,
            "type": "user",
            "first_name": "فروشگاه سعید",
            "last_name": "( سعید فقیر )",
            "username": "Saeedfghshop",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 1787778987042582800,
            "bot_nochats": false
        },
        {
            "id": 1303677205,
            "type": "user",
            "first_name": "فروشگاه پارت سنتر (علی شفیعی)",
            "last_name": "Part Center Store (Ali Shafiei)",
            "username": "Part_center_iran1",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -1760605801701952800,
            "phone": "989129234642",
            "bot_nochats": false
        },
        {
            "id": 263857572,
            "type": "user",
            "first_name": "IMPERIAL",
            "username": "ZarinParsis",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -2785494593207314400,
            "bot_nochats": false
        },
        {
            "id": 164515760,
            "type": "user",
            "first_name": "Mohammadreza",
            "last_name": "Mahdifard",
            "username": "mahdifard1",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 3467014178235710500,
            "phone": "989125098876",
            "bot_nochats": false
        },
        {
            "id": 960595525,
            "type": "user",
            "first_name": "Hyundai",
            "last_name": "Mozaffari",
            "username": "hyundaimozaffari",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 2895177643487908000,
            "bot_nochats": false
        },
        {
            "id": 106256131,
            "type": "user",
            "first_name": "Vahab",
            "last_name": "Sajadi",
            "username": "vahabsajadi",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 4642692136897004000,
            "bot_nochats": false
        },
        {
            "id": 602119238,
            "type": "user",
            "first_name": "Moein",
            "last_name": "Torabi",
            "username": "moein_torabii",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -4184169390711187000,
            "bot_nochats": false
        },
        {
            "id": 5859933101,
            "type": "user",
            "first_name": "Amin Store",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 248137170045173700,
            "bot_nochats": false
        },
        {
            "id": 978486320,
            "type": "user",
            "first_name": "Aran yadak",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 836117620071935600,
            "bot_nochats": false
        },
        {
            "id": 1057811815,
            "type": "user",
            "first_name": "Payam niloofar",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -4665705495109035000,
            "bot_nochats": false
        },
        {
            "id": 1971522747,
            "type": "user",
            "first_name": "vahid",
            "last_name": "tabrizi",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -1201495667492433400,
            "bot_nochats": false
        },
        {
            "id": 171436873,
            "type": "user",
            "first_name": "Hamed",
            "last_name": "Sharbaf",
            "username": "HamedSherbaf",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -2946996967781280000,
            "bot_nochats": false
        },
        {
            "id": 5684446360,
            "type": "user",
            "first_name": "Azimi Store",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 2247146567599312600,
            "bot_nochats": false
        },
        {
            "id": 370163101,
            "type": "user",
            "first_name": "Ali . R E Z A A L I",
            "username": "Rezaalishop",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -3443932943651887600,
            "bot_nochats": false
        },
        {
            "id": 5035781935,
            "type": "user",
            "first_name": "آریا پارت امیر",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 7925460510178443000,
            "bot_nochats": false
        },
        {
            "id": 6213276073,
            "type": "user",
            "first_name": "IRAN YADAK",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 5213882118572429000,
            "bot_nochats": false
        },
        {
            "id": 117284981,
            "type": "user",
            "first_name": "Mehran",
            "username": "mehranslami",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -6960837540869053000,
            "bot_nochats": false
        },
        {
            "id": 1039531534,
            "type": "user",
            "first_name": "Hyundai Kia Shop",
            "last_name": "مهران احمدی",
            "username": "hyundai_kia_shop1",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 7192057016478990000,
            "bot_nochats": false
        },
        {
            "id": 625070429,
            "type": "user",
            "first_name": "M",
            "last_name": "M",
            "username": "mozafari33111380",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -6443406337753155000,
            "bot_nochats": false
        },
        {
            "id": 1321717554,
            "type": "user",
            "first_name": "PARTESTAN",
            "username": "partestan_co_ltd",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 620582272066348800,
            "bot_nochats": false
        },
        {
            "id": 1611107629,
            "type": "user",
            "first_name": "فروشگاه هایپرپارت",
            "username": "Hyperpartt",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 991040189530568700,
            "phone": "989217621165",
            "bot_nochats": false
        },
        {
            "id": 1516130408,
            "type": "user",
            "first_name": "Mohammad",
            "last_name": "Ezzatnejad",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 7474406578004753000,
            "bot_nochats": false
        },
        {
            "id": 5521648121,
            "type": "user",
            "first_name": "Ali fathabadi",
            "username": "ali33946119",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -4909469568575096000,
            "bot_nochats": false
        },
        {
            "id": 545837469,
            "type": "user",
            "first_name": "مجتبی براتی",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 6548773312591714000,
            "bot_nochats": false
        },
        {
            "id": 452827342,
            "type": "user",
            "first_name": "AmirSalar",
            "last_name": "Choobdaran",
            "username": "HyundaiAdminn",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 3159546929287545300,
            "phone": "989194441944",
            "bot_nochats": false
        },
        {
            "id": 97835555,
            "type": "user",
            "first_name": "Arash-sj",
            "username": "Arash_sedaghat92",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -1314760804994730500,
            "bot_nochats": false
        },
        {
            "id": 83996941,
            "type": "user",
            "first_name": "حسین",
            "last_name": "بیگی",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -94110842411086180,
            "bot_nochats": false
        },
        {
            "id": 1101371979,
            "type": "user",
            "first_name": "Amirali",
            "last_name": "S.T",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 8554769136733170000,
            "bot_nochats": false
        },
        {
            "id": 1044249443,
            "type": "user",
            "first_name": "فروشگاه کره پارت فلاح",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -5941838248216615000,
            "bot_nochats": false
        },
        {
            "id": 353234699,
            "type": "user",
            "first_name": "Daniyal",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -1228333476392017200,
            "bot_nochats": false
        },
        {
            "id": 1624883768,
            "type": "user",
            "first_name": "Hyundai",
            "last_name": "Mortezaei",
            "username": "partiran_hyundai",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 7683540936569150000,
            "bot_nochats": false
        },
        {
            "id": 675706217,
            "type": "user",
            "first_name": "sajad",
            "last_name": "sohrabi",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -3757446385958130700,
            "bot_nochats": false
        },
        {
            "id": 483892514,
            "type": "user",
            "first_name": "Afshin",
            "last_name": "Afshar",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 6134791828272232000,
            "bot_nochats": false
        },
        {
            "id": 113396179,
            "type": "user",
            "first_name": "Majid",
            "last_name": "09194509413",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -7985141237110560000,
            "bot_nochats": false
        },
        {
            "id": 373316796,
            "type": "user",
            "first_name": "Mohsen",
            "last_name": "Forouzandeh",
            "username": "MoonParts_Admin",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -9198160714976949000,
            "bot_nochats": false
        },
        {
            "id": 5347790338,
            "type": "user",
            "first_name": "reza",
            "last_name": "sedighian",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -5417575401486339000,
            "bot_nochats": false
        },
        {
            "id": 161305574,
            "type": "user",
            "first_name": "Ali Amirani",
            "username": "Ali_Amirani40",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -4888624915062437000,
            "bot_nochats": false
        },
        {
            "id": 5692153961,
            "type": "user",
            "first_name": "سامپارت یدک",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -2596673742787462000,
            "bot_nochats": false
        },
        {
            "id": 5736583095,
            "type": "user",
            "first_name": "ali vafaii",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -6219605295424537000,
            "phone": "989192582990",
            "bot_nochats": false
        },
        {
            "id": 253973824,
            "type": "user",
            "first_name": "Amir",
            "last_name": "Mocevand",
            "username": "amirmocevand",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 1270928659330866700,
            "bot_nochats": false
        },
        {
            "id": 456937514,
            "type": "user",
            "first_name": "MGMPART",
            "last_name": "33939279/33976298",
            "username": "mgm_part",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 1215713245339122200,
            "bot_nochats": false
        },
        {
            "id": 326623271,
            "type": "user",
            "first_name": "Mohammad",
            "last_name": "Reza",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -5814845345483843000,
            "bot_nochats": false
        },
        {
            "id": 110187686,
            "type": "user",
            "first_name": "ali",
            "last_name": "n.m",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 4238246196557332500,
            "bot_nochats": false
        },
        {
            "id": 406507291,
            "type": "user",
            "first_name": "Automobilran",
            "username": "Automobilran",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -5428857910873036000,
            "bot_nochats": false
        },
        {
            "id": 174152348,
            "type": "user",
            "first_name": "Hadi",
            "username": "Hadi6696",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 6366784024404772000,
            "bot_nochats": false
        },
        {
            "id": 766198659,
            "type": "user",
            "first_name": "NAVID",
            "last_name": "GOODARZIYAN",
            "username": "Keyno_part",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 2488336818873486000,
            "bot_nochats": false
        },
        {
            "id": 433688450,
            "type": "user",
            "first_name": "Soroush",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 2880970466058163700,
            "phone": "989127600907",
            "bot_nochats": false
        },
        {
            "id": 92473262,
            "type": "user",
            "first_name": "Amir",
            "last_name": "Afshar",
            "username": "amir31930",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -3018616648916348400,
            "bot_nochats": false
        },
        {
            "id": 759481750,
            "type": "user",
            "first_name": "RABO YADAK H&K",
            "last_name": "103",
            "username": "RABOYADAK103",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -733816516094464000,
            "phone": "989056499176",
            "bot_nochats": false
        },
        {
            "id": 6235789853,
            "type": "user",
            "first_name": "مهان پارت (مظفری)",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 8043315241658108000,
            "bot_nochats": false
        },
        {
            "id": 1509157548,
            "type": "user",
            "first_name": "Saeedi",
            "last_name": "Parts",
            "username": "SaeediPart",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -1786957925322095400,
            "bot_nochats": false
        },
        {
            "id": 82768979,
            "type": "user",
            "first_name": "امیر",
            "last_name": "برهانی",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -8048792074304793000,
            "bot_nochats": false
        },
        {
            "id": 5006201968,
            "type": "user",
            "first_name": "Mohamdhosein",
            "username": "hosein_7505",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -474160692405028740,
            "bot_nochats": false
        },
        {
            "id": 5441434276,
            "type": "user",
            "first_name": "M2PART",
            "username": "m2parttt",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -6787639429410681000,
            "bot_nochats": false
        },
        {
            "id": 1567615647,
            "type": "user",
            "first_name": "Hasan",
            "last_name": "Karamlou",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -7589917140925729000,
            "bot_nochats": false
        },
        {
            "id": 6687910398,
            "type": "user",
            "first_name": "استار یدک",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 2144592934290948000,
            "bot_nochats": false
        },
        {
            "id": 110044296,
            "type": "user",
            "first_name": "Reza",
            "last_name": "Modanloo",
            "username": "RezaModanloo",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 4885892514822413000,
            "bot_nochats": false
        },
        {
            "id": 6457622109,
            "type": "user",
            "first_name": "فروشگاه",
            "last_name": "پوریا",
            "username": "Kia_poria",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 3526858906625107500,
            "bot_nochats": false
        },
        {
            "id": 899280953,
            "type": "user",
            "first_name": "SOBHAN",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -8816087312966040000,
            "bot_nochats": false
        },
        {
            "id": 5136314191,
            "type": "user",
            "first_name": "بازرگانی دیاکو پارت",
            "last_name": "33980653",
            "username": "diiako_part",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -5115585386901121000,
            "bot_nochats": false
        },
        {
            "id": 5311986949,
            "type": "user",
            "first_name": "MOHAMADREZA BAYATI",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 7319805815218736000,
            "bot_nochats": false
        },
        {
            "id": 1456443868,
            "type": "user",
            "first_name": "Auto Kala",
            "last_name": "@autokalaco",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -7722068507214124000,
            "bot_nochats": false
        },
        {
            "id": 188327918,
            "type": "user",
            "first_name": "فروشگاه ورنا",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -8403262486251272000,
            "bot_nochats": false
        },
        {
            "id": 108869115,
            "type": "user",
            "first_name": "Amin",
            "last_name": "Khoshharf",
            "username": "Aminmotlagh_h",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -8201851132966596000,
            "bot_nochats": false
        },
        {
            "id": 1784021295,
            "type": "user",
            "first_name": "Milad",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 8043489985169274000,
            "bot_nochats": false
        },
        {
            "id": 895555280,
            "type": "user",
            "first_name": "فروشگاه",
            "last_name": "صفرزاده",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 369243684103232,
            "bot_nochats": false
        },
        {
            "id": 109521233,
            "type": "user",
            "first_name": "Auto Kala",
            "username": "autokalaco",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 6616264043926104000,
            "bot_nochats": false
        },
        {
            "id": 357113448,
            "type": "user",
            "first_name": "AMiN",
            "last_name": "MAHDAVi",
            "username": "amf15572",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -7229575352883079000,
            "phone": "989126130929",
            "bot_nochats": false
        },
        {
            "id": 197540543,
            "type": "user",
            "first_name": "فروشگاه پاینده",
            "username": "payandeh_group",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 6831400608505681000,
            "phone": "989039689004",
            "bot_nochats": false
        },
        {
            "id": 695236662,
            "type": "user",
            "first_name": "Hamid(arianshop)",
            "username": "Hamidreza_ar",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 6795015623509782000,
            "phone": "989352186403",
            "bot_nochats": false
        },
        {
            "id": 741828263,
            "type": "user",
            "first_name": "فروشگاه تهران فابریک",
            "username": "Tehranfabric",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -5644999062589596000,
            "bot_nochats": false
        },
        {
            "id": 5629347666,
            "type": "user",
            "first_name": "فروشگاه عنایت پور",
            "username": "Enayatpour_yadak",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -5019526724124428000,
            "bot_nochats": false
        },
        {
            "id": 114701135,
            "type": "user",
            "first_name": "Arash",
            "last_name": "Faraji",
            "username": "Arash_masouleh",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -1402402436042596400,
            "bot_nochats": false
        },
        {
            "id": 1763139331,
            "type": "user",
            "first_name": "mohammad",
            "last_name": "esfehani",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 4154834818097821700,
            "bot_nochats": false
        },
        {
            "id": 47182441,
            "type": "user",
            "first_name": "Peyman",
            "last_name": "Elami",
            "username": "psp_car",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -3166981357991718400,
            "bot_nochats": false
        },
        {
            "id": 100573882,
            "type": "user",
            "first_name": "BAHRAM",
            "last_name": "khanjari",
            "username": "Bahramkhanjarii",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 7711050710154713000,
            "bot_nochats": false
        },
        {
            "id": 1227138607,
            "type": "user",
            "first_name": "Amiran Yadak",
            "username": "Amiranyadak",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 5463514669831745000,
            "bot_nochats": false
        },
        {
            "id": 205664509,
            "type": "user",
            "first_name": "Abozar",
            "last_name": "Gharibi",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -449498656973644540,
            "bot_nochats": false
        },
        {
            "id": 746323655,
            "type": "user",
            "first_name": "RABO YADAK",
            "last_name": "101",
            "username": "RABOYADAK101",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 7085511549705815000,
            "bot_nochats": false
        },
        {
            "id": 5620807089,
            "type": "user",
            "first_name": "Piaropart/ایرانشاهی",
            "username": "piaro_part",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -7573749501992364000,
            "bot_nochats": false
        },
        {
            "id": 249742384,
            "type": "user",
            "first_name": "فروشگاه مهرداد",
            "username": "MEHRDAD_TRADING",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 6197607717131809000,
            "bot_nochats": false
        },
        {
            "id": 96022966,
            "type": "user",
            "first_name": "Ehsan",
            "last_name": "Emami",
            "username": "ehsan_emami88",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -1710877035218813700,
            "phone": "989124066994",
            "bot_nochats": false
        },
        {
            "id": 5915812099,
            "type": "user",
            "first_name": "فروشگاه ارشیا",
            "username": "Arshiastore93",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -8195996873035588000,
            "bot_nochats": false
        },
        {
            "id": 340191703,
            "type": "user",
            "first_name": "Mohammadreza",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -1559073465646053400,
            "bot_nochats": false
        },
        {
            "id": 1865593012,
            "type": "user",
            "first_name": "asef tavakoli",
            "username": "aseftavakoli_09127627915",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -4464553717905646600,
            "phone": "989127627915",
            "bot_nochats": false
        },
        {
            "id": 5176858151,
            "type": "user",
            "first_name": "Erfan",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 8393518616392024000,
            "phone": "989125557116",
            "bot_nochats": false
        },
        {
            "id": 504493292,
            "type": "user",
            "first_name": "Kianpart(jamshidi)",
            "last_name": "40331380",
            "username": "alireza_jamm",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 5795735129834636000,
            "bot_nochats": false
        },
        {
            "id": 1707050004,
            "type": "user",
            "first_name": "سامان",
            "last_name": "محمدی",
            "username": "Saman_mohammadi",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -1918399763387376600,
            "bot_nochats": false
        },
        {
            "id": 608330943,
            "type": "user",
            "first_name": "B.M",
            "username": "Autospare_rb2",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 8528151892405331000,
            "bot_nochats": false
        },
        {
            "id": 111344420,
            "type": "user",
            "first_name": "Majid",
            "last_name": "Samari",
            "username": "MAJIDS1983",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 2478295588844018000,
            "bot_nochats": false
        },
        {
            "id": 125726606,
            "type": "user",
            "first_name": "Ali",
            "last_name": "Sadeghi",
            "username": "AlirezaSadeghiJadid",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 1377014871652089600,
            "bot_nochats": false
        },
        {
            "id": 5682432111,
            "type": "user",
            "first_name": "Pars",
            "last_name": "Store ( بهرام کریم )",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 3724522828497660400,
            "bot_nochats": false
        },
        {
            "id": 5504285853,
            "type": "user",
            "first_name": "Hamid",
            "username": "Tehran_Yadakoriginal",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 9008109897083893000,
            "bot_nochats": false
        },
        {
            "id": 119389332,
            "type": "user",
            "first_name": "فروشگاه يزدان",
            "last_name": "(هادى)",
            "username": "alihadi267",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 340216998818299400,
            "bot_nochats": false
        },
        {
            "id": 1306383247,
            "type": "user",
            "first_name": "HYUNDAI",
            "last_name": "MOSAAL REZA🔩⚙️",
            "username": "mosalrezaaa",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 5326669661067607000,
            "bot_nochats": false
        },
        {
            "id": 248050655,
            "type": "user",
            "first_name": "Hadi",
            "last_name": "Zakhar",
            "username": "Atlaskia",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -5829576274428637000,
            "bot_nochats": false
        },
        {
            "id": 5577137822,
            "type": "user",
            "first_name": "PoorAmini",
            "username": "Pooramine00",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 7791856782315563000,
            "bot_nochats": false
        },
        {
            "id": 5797410349,
            "type": "user",
            "first_name": "عباس",
            "last_name": "محمدی",
            "username": "AbbasMohammadiH",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -2808002654571034600,
            "bot_nochats": false
        },
        {
            "id": 100695866,
            "type": "user",
            "first_name": "Mohsen 33960423🌀33114334",
            "username": "hiwano",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -5275858923283760000,
            "phone": "989124332749",
            "bot_nochats": false
        },
        {
            "id": 99045419,
            "type": "user",
            "first_name": "mojtaba",
            "last_name": "akbar",
            "username": "MojtabAkbar",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 53542943595329950,
            "bot_nochats": false
        },
        {
            "id": 94134574,
            "type": "user",
            "first_name": "Farshad_khoob",
            "username": "farshad9898",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -5465105314626663000,
            "bot_nochats": false
        },
        {
            "id": 5540360999,
            "type": "user",
            "first_name": "Ali Zare (Federal Part)",
            "username": "Ali_Zare_Hyundai",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 5170577384248276000,
            "bot_nochats": false
        },
        {
            "id": 907359360,
            "type": "user",
            "first_name": "Vahiid",
            "last_name": "Abbasian",
            "username": "h_k_1368",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -5887048121020814000,
            "phone": "989121772585",
            "bot_nochats": false
        },
        {
            "id": 21022859,
            "type": "user",
            "first_name": "Hyundai_part",
            "username": "Hyundai_part2",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -7951011445462904000,
            "bot_nochats": false
        },
        {
            "id": 68606335,
            "type": "user",
            "first_name": "Aria",
            "last_name": "Ezzatnejad",
            "username": "ariaezzatnejad",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 8260454371458501000,
            "bot_nochats": false
        },
        {
            "id": 116279362,
            "type": "user",
            "first_name": "Amir nasimi",
            "username": "amirnasimii",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -6462908796416197000,
            "bot_nochats": false
        },
        {
            "id": 118432607,
            "type": "user",
            "first_name": "Amin",
            "last_name": "Babaie 09122034911",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -7751061072799738000,
            "bot_nochats": false
        },
        {
            "id": 390781563,
            "type": "user",
            "first_name": "Mohamad",
            "username": "mohamdhoseini",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -1764948083188166700,
            "bot_nochats": false
        },
        {
            "id": 143667710,
            "type": "user",
            "first_name": "Atlaspart(گودرزی)",
            "username": "AtlasPartshop",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 7939622479202224000,
            "bot_nochats": false
        },
        {
            "id": 833383323,
            "type": "user",
            "first_name": "Ali Bagheri",
            "username": "ako_part",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 3570364531592378400,
            "bot_nochats": false
        },
        {
            "id": 5217562759,
            "type": "user",
            "first_name": "‌Azimi Store[javad azimi]",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -5836007415614285000,
            "phone": "989913123423",
            "bot_nochats": false
        },
        {
            "id": 1517449059,
            "type": "user",
            "first_name": "Hesam",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 3800963680242795500,
            "bot_nochats": false
        },
        {
            "id": 231556590,
            "type": "user",
            "first_name": "Mahdi",
            "last_name": "R",
            "username": "Mehdi_Rostamiyan",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 4691107382949899000,
            "bot_nochats": false
        },
        {
            "id": 93298179,
            "type": "user",
            "first_name": "Ramin",
            "last_name": "Sadeghi",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -1700793403046527200,
            "bot_nochats": false
        },
        {
            "id": 1310670940,
            "type": "user",
            "first_name": "Azizi -Diakopar",
            "username": "Azizi_diakopart",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -6845916721411049000,
            "bot_nochats": false
        },
        {
            "id": 5294296583,
            "type": "user",
            "first_name": "Hamed",
            "last_name": "Shabani",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -6505915045242264000,
            "bot_nochats": false
        },
        {
            "id": 47597606,
            "type": "user",
            "first_name": "Reza",
            "username": "Reza83657",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 2935825591655607000,
            "bot_nochats": false
        },
        {
            "id": 5583398676,
            "type": "user",
            "first_name": "Auto",
            "last_name": "Korea",
            "username": "Auto_korea2",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -5826546249212762000,
            "bot_nochats": false
        },
        {
            "id": 6091917729,
            "type": "user",
            "first_name": "نور پارت",
            "last_name": "33954786",
            "username": "Noorparts",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 1473850903197066500,
            "bot_nochats": false
        },
        {
            "id": 5014998023,
            "type": "user",
            "first_name": "آژند نيك پاسارگاد",
            "last_name": "(كلاهدوز)",
            "username": "ajandnikPASARGADkolahdooz",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 3269473640988514300,
            "bot_nochats": false
        },
        {
            "id": 325547354,
            "type": "user",
            "first_name": "Bey_Rey_parts",
            "username": "saeedshbz",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 3563589227754356000,
            "bot_nochats": false
        },
        {
            "id": 101329606,
            "type": "user",
            "first_name": "alireza",
            "last_name": "taebnia",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -8657305204689665000,
            "bot_nochats": false
        },
        {
            "id": 1181579162,
            "type": "user",
            "first_name": "hyundai",
            "last_name": "yadak",
            "username": "hyundai_yadak_mehrabian",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 8193701324577384000,
            "bot_nochats": false
        },
        {
            "id": 91787860,
            "type": "user",
            "first_name": "Vahid",
            "last_name": "Rahmani",
            "username": "Hyonda_kia",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -7600862819582418000,
            "bot_nochats": false
        },
        {
            "id": 98360465,
            "type": "user",
            "first_name": "Amir",
            "last_name": "Golshiri",
            "username": "amg2479",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -7793557565768840000,
            "bot_nochats": false
        },
        {
            "id": 91965856,
            "type": "user",
            "first_name": "Alireza",
            "last_name": "Alishoar",
            "username": "AlirezaAlishoar",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 5614558689797627000,
            "bot_nochats": false
        },
        {
            "id": 6822547166,
            "type": "user",
            "first_name": "Delta",
            "last_name": "Part",
            "username": "hooseintohidifar",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -1665937826093265400,
            "bot_nochats": false
        },
        {
            "id": 5086731631,
            "type": "user",
            "first_name": "Shahab dabiri",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -7338555214366102000,
            "bot_nochats": false
        },
        {
            "id": 1293796810,
            "type": "user",
            "first_name": "Auto-part7",
            "last_name": "H.p.",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 8099739773308092000,
            "bot_nochats": false
        },
        {
            "id": 6030377452,
            "type": "user",
            "first_name": "Hasan,",
            "last_name": "Fatemi",
            "username": "FTMPART",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -2584134831973266000,
            "bot_nochats": false
        },
        {
            "id": 362991245,
            "type": "user",
            "first_name": "قطعات یدکی",
            "last_name": "هیوندا.کیا",
            "username": "tyautopart",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 7173480073970498000,
            "phone": "989129303229",
            "bot_nochats": false
        },
        {
            "id": 6277979006,
            "type": "user",
            "first_name": "Part store",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 2609267842176480000,
            "bot_nochats": false
        },
        {
            "id": 413673330,
            "type": "user",
            "first_name": "فروشگاه رضوانى",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -3481104912954750000,
            "bot_nochats": false
        },
        {
            "id": 1838635606,
            "type": "user",
            "first_name": "Milad",
            "last_name": "Shahidi",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 4432370843826507300,
            "bot_nochats": false
        },
        {
            "id": 5775609464,
            "type": "user",
            "first_name": "کلبه هیوندای",
            "last_name": "حسین عبدی",
            "username": "Hosein_abdii1",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 3542243084572666400,
            "bot_nochats": false
        },
        {
            "id": 653940117,
            "type": "user",
            "first_name": "Tehran yadak",
            "last_name": "Mojtaba",
            "username": "mojtaba_fathabadi",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 2610513619640935400,
            "phone": "989125182896",
            "bot_nochats": false
        },
        {
            "id": 1781461046,
            "type": "user",
            "first_name": "Sina",
            "last_name": "Naseri",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 8035685864847748000,
            "bot_nochats": false
        },
        {
            "id": 501342050,
            "type": "user",
            "first_name": "ابراهیم",
            "last_name": "شریفی نیک نفس",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -2284959718912260400,
            "phone": "989123038306",
            "bot_nochats": false
        },
        {
            "id": 1024252603,
            "type": "user",
            "first_name": "فروشگاه 110",
            "username": "hyundaipartgholami",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 1246899799822074400,
            "bot_nochats": false
        },
        {
            "id": 642884920,
            "type": "user",
            "first_name": "Sepehr",
            "last_name": "Sepehr",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 4342613115035525600,
            "bot_nochats": false
        },
        {
            "id": 418773192,
            "type": "user",
            "first_name": "سعید",
            "last_name": "عباسی",
            "username": "Auto_part_abbassi",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 1046100242356953000,
            "bot_nochats": false
        },
        {
            "id": 25220876,
            "type": "user",
            "first_name": "Rokhsari",
            "last_name": "Javad",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -497158182332045400,
            "bot_nochats": false
        },
        {
            "id": 5183455258,
            "type": "user",
            "first_name": "مصطفی",
            "last_name": "افشار",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 193723244786986560,
            "bot_nochats": false
        },
        {
            "id": 1362829379,
            "type": "user",
            "first_name": "Ramin",
            "last_name": "Fathalian",
            "username": "fathalianramin",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 6967448149603535000,
            "bot_nochats": false
        },
        {
            "id": 5318454855,
            "type": "user",
            "first_name": "Heydar",
            "last_name": "Araz part",
            "username": "ARAZPART",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -3592663283341548000,
            "bot_nochats": false
        },
        {
            "id": 6592932826,
            "type": "user",
            "first_name": "🔹️فروشگاه کوریا اتو پارت 🔹️",
            "username": "Rafiei_H1",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 3431325184792505300,
            "bot_nochats": false
        },
        {
            "id": 6281956406,
            "type": "user",
            "first_name": "Erfan",
            "last_name": "Sobhanipour",
            "username": "sahebzaman_yadak",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 1997506938617681200,
            "bot_nochats": false
        },
        {
            "id": 1715332065,
            "type": "user",
            "first_name": "Toraj",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -6853828266297349000,
            "bot_nochats": false
        },
        {
            "id": 5563026029,
            "type": "user",
            "first_name": "Ali",
            "last_name": "Mohammadi",
            "username": "Berozyadak",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -5650564173311824000,
            "phone": "989052805389",
            "bot_nochats": false
        },
        {
            "id": 33531744,
            "type": "user",
            "first_name": "🇮🇷H@mze",
            "last_name": "Sh@rbaf🇰🇷",
            "username": "Sharbafh",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -3177020493314158600,
            "phone": "989122242847",
            "bot_nochats": false
        },
        {
            "id": 43416835,
            "type": "user",
            "first_name": "Morteza",
            "last_name": "Foroghi",
            "username": "mortezaforoghi1",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 2301778644154913500,
            "bot_nochats": false
        },
        {
            "id": 6674604860,
            "type": "user",
            "first_name": "فروشگاه پارتاک یدک",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -4441619211514269000,
            "bot_nochats": false
        },
        {
            "id": 2021845792,
            "type": "user",
            "first_name": "hykia",
            "username": "Hykiaa_part",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 5983229050166711000,
            "bot_nochats": false
        },
        {
            "id": 279120713,
            "type": "user",
            "first_name": "پارت یدک",
            "username": "partyadak066",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 1374271133828912000,
            "bot_nochats": false
        },
        {
            "id": 396257025,
            "type": "user",
            "first_name": "فروشگاه آذریدک",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -3684205824894517000,
            "phone": "989023932515",
            "bot_nochats": false
        },
        {
            "id": 887623937,
            "type": "user",
            "first_name": "RABO",
            "last_name": "YADAK 104",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 2844381934475550000,
            "bot_nochats": false
        },
        {
            "id": 1012148175,
            "type": "user",
            "first_name": "Majid",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -249583625568721000,
            "bot_nochats": false
        },
        {
            "id": 275635379,
            "type": "user",
            "first_name": "MOHAMADREZABAYATI",
            "username": "Mohamadrzabayati",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 7963726060199718000,
            "bot_nochats": false
        },
        {
            "id": 100762530,
            "type": "user",
            "first_name": "Amir",
            "last_name": "N",
            "username": "Amirnazari48",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -1943240908371106600,
            "bot_nochats": false
        },
        {
            "id": 72138907,
            "type": "user",
            "first_name": "Mehrdad",
            "username": "mehrdad_Eslm",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -7178979357958840000,
            "bot_nochats": false
        },
        {
            "id": 81871175,
            "type": "user",
            "first_name": "Yousefkhani",
            "last_name": "AshkaN",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -7771354385094355000,
            "bot_nochats": false
        },
        {
            "id": 260351508,
            "type": "user",
            "first_name": "M.mahdi.nosrati",
            "username": "m1988mn",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -4138874799478694000,
            "phone": "989120635010",
            "bot_nochats": false
        },
        {
            "id": 1122050493,
            "type": "user",
            "first_name": "GRAND PARTS",
            "username": "Grand_part",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 3689236612881267000,
            "bot_nochats": false
        },
        {
            "id": 64892671,
            "type": "user",
            "first_name": "Hossein",
            "last_name": "samsami",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 8501340759188875000,
            "bot_nochats": false
        },
        {
            "id": 986150808,
            "type": "user",
            "first_name": "Ashkan",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -8093592176938109000,
            "bot_nochats": false
        },
        {
            "id": 61680814,
            "type": "user",
            "first_name": "A.",
            "last_name": "Karimi",
            "username": "alikarimi2564",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -4163810242030094000,
            "bot_nochats": false
        },
        {
            "id": 266039561,
            "type": "user",
            "first_name": "hossein",
            "last_name": "Akbari",
            "username": "Hanaparts",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -2043349645636016400,
            "bot_nochats": false
        },
        {
            "id": 89571171,
            "type": "user",
            "first_name": "Shahrokh",
            "username": "Shahrokh_hasim",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 5738364323683531000,
            "bot_nochats": false
        },
        {
            "id": 5230618934,
            "type": "user",
            "first_name": "AlI",
            "username": "Mcpartz",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -6081836851487080000,
            "phone": "989125728663",
            "bot_nochats": false
        },
        {
            "id": 6150743605,
            "type": "user",
            "first_name": "∙∙∙∙∙·▫▫ᵒᴼᵒ Nik2023ᵒᴼᵒ▫▫·∙∙∙∙∙",
            "username": "Shnik2023",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 4597881910219746000,
            "bot_nochats": false
        },
        {
            "id": 434810962,
            "type": "user",
            "first_name": "Reza",
            "last_name": "Rezazadeh",
            "username": "rezaarezazadeh",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -128685280040779700,
            "phone": "989121004224",
            "bot_nochats": false
        },
        {
            "id": 96350309,
            "type": "user",
            "first_name": "هیوندا کیا",
            "username": "Kia_hyundai_ms",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 3260411319118624300,
            "bot_nochats": false
        },
        {
            "id": 925537076,
            "type": "user",
            "first_name": "Ali reza",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -899587189519214300,
            "bot_nochats": false
        },
        {
            "id": 957557649,
            "type": "user",
            "first_name": "فروشگاه ميثاق",
            "last_name": "قطعات هيونداي و كيا",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -5344991137795784000,
            "bot_nochats": false
        },
        {
            "id": 94468006,
            "type": "user",
            "first_name": "@.heydari",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -4987397370375481000,
            "bot_nochats": false
        },
        {
            "id": 47641777,
            "type": "user",
            "first_name": "KIA & HYUNDAI PARTS",
            "username": "HYUNDAI_KIA_BAGHERI",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 9070853467121019000,
            "bot_nochats": false
        },
        {
            "id": 5943783595,
            "type": "user",
            "first_name": "Ahmad",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusLastMonth"
            },
            "access_hash": -2158048625693620700,
            "bot_nochats": false
        },
        {
            "id": 234380169,
            "type": "user",
            "first_name": "RAADYDK",
            "username": "car_shop_parts",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 30588861962318030,
            "bot_nochats": false
        },
        {
            "id": 103549240,
            "type": "user",
            "first_name": "حسین",
            "last_name": "هاشمی",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 2788846136595133000,
            "bot_nochats": false
        },
        {
            "id": 62270053,
            "type": "user",
            "first_name": "Mojtaba",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -8921621531391608000,
            "bot_nochats": false
        },
        {
            "id": 235624720,
            "type": "user",
            "first_name": "Mehrdad",
            "last_name": "Akef",
            "username": "Mehrdadakef",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 8676437396585851000,
            "bot_nochats": false
        },
        {
            "id": 67422229,
            "type": "user",
            "first_name": "AUTO PART AZIMI",
            "username": "Auto_part_azimi",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 4760095728077165000,
            "phone": "989361360500",
            "bot_nochats": false
        },
        {
            "id": 317107105,
            "type": "user",
            "first_name": "B",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 4917901949631538000,
            "bot_nochats": false
        },
        {
            "id": 106709908,
            "type": "user",
            "first_name": "mahdi",
            "last_name": "safarzadeh",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -3440687439005083600,
            "bot_nochats": false
        },
        {
            "id": 1468585626,
            "type": "user",
            "first_name": "یدک اتوماتیو",
            "last_name": "Yadak automotive",
            "username": "Yadak_automotive",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -947890708023673000,
            "bot_nochats": false
        },
        {
            "id": 849351235,
            "type": "user",
            "first_name": "hyundai",
            "last_name": "kia",
            "username": "koreaautoparts1",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusLastWeek"
            },
            "access_hash": 968068340312808800,
            "phone": "989129252232",
            "bot_nochats": false
        },
        {
            "id": 200025075,
            "type": "user",
            "first_name": "Alireza",
            "username": "Akopart",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -1704699859003422000,
            "bot_nochats": false
        },
        {
            "id": 222025797,
            "type": "user",
            "first_name": "Mohsen09122309220rafi",
            "last_name": "02136611800",
            "username": "myadk",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 2339692796647490000,
            "bot_nochats": false
        },
        {
            "id": 5078930338,
            "type": "user",
            "first_name": "بازرگانی",
            "last_name": "رحیمیان",
            "username": "Bazarganirahimiyan",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 1094423637935193600,
            "phone": "989128595446",
            "bot_nochats": false
        },
        {
            "id": 64172096,
            "type": "user",
            "first_name": "REZA",
            "last_name": "GORJI",
            "username": "Rgk1986",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -8040192965771572000,
            "bot_nochats": false
        },
        {
            "id": 82991625,
            "type": "user",
            "first_name": "Alireza",
            "last_name": "Abbasi",
            "username": "aliz_abbasi",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 6241786193845352000,
            "bot_nochats": false
        },
        {
            "id": 331571180,
            "type": "user",
            "first_name": "mojtaba sane",
            "username": "Mojinikan",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusLastMonth"
            },
            "access_hash": -239848704719651840,
            "bot_nochats": false
        },
        {
            "id": 125004720,
            "type": "user",
            "first_name": "TAVOOS COMPANY🇰🇷🇰🇷",
            "username": "TAVOOS_COMPANY",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 5078739106030189000,
            "bot_nochats": false
        },
        {
            "id": 75328386,
            "type": "user",
            "first_name": "Amin",
            "last_name": "Bolourian",
            "username": "HTMbaran",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -629663778442209200,
            "bot_nochats": false
        },
        {
            "id": 189918071,
            "type": "user",
            "first_name": "Yadakkar Mahdi - Mosalreza",
            "last_name": "09127778775",
            "username": "Yadakkarmahdi",
            "verified": false,
            "restricted": false,
            "access_hash": -2218485348416138200,
            "bot_nochats": false
        },
        {
            "id": 96585494,
            "type": "user",
            "first_name": "Amir",
            "last_name": "Sharifi",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusLastMonth"
            },
            "access_hash": 5649822876142952000,
            "bot_nochats": false
        },
        {
            "id": 105991190,
            "type": "user",
            "first_name": "saeed",
            "last_name": "faghir",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 8839342796958591000,
            "bot_nochats": false
        },
        {
            "id": 689881150,
            "type": "user",
            "first_name": "KIA - HYUNDAI",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusLastMonth"
            },
            "access_hash": 5295127873283009000,
            "bot_nochats": false
        },
        {
            "id": 636250031,
            "type": "user",
            "first_name": "Reza",
            "last_name": "Rezazadeh",
            "username": "rezaarezazade",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -8474511585635604000,
            "bot_nochats": false
        },
        {
            "id": 657145482,
            "type": "user",
            "first_name": "Bahador Enayatpour",
            "username": "Bahador_en",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 6718550013884314000,
            "bot_nochats": false
        },
        {
            "id": 287513242,
            "type": "user",
            "first_name": "ali",
            "last_name": "shahidii",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -9188939362471434000,
            "bot_nochats": false
        },
        {
            "id": 102139237,
            "type": "user",
            "first_name": "Kia",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 2687197638307177500,
            "bot_nochats": false
        },
        {
            "id": 123021554,
            "type": "user",
            "first_name": "Jaafar",
            "last_name": "Salmanzadeh",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": 3955682034174449000,
            "bot_nochats": false
        },
        {
            "id": 105581678,
            "type": "user",
            "first_name": "Armin",
            "last_name": "Bagheri",
            "username": "armin_bagheri",
            "verified": false,
            "restricted": false,
            "status": {
                "_": "userStatusRecently"
            },
            "access_hash": -1840310550545483800,
            "bot_nochats": false
        }
    ];
    let selectedPartNumber = null;

    console.log(existingContacts);

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

    function addAllContacts() {
        var params = new URLSearchParams();
        params.append('addAllContact', 'addAllContact');
        params.append('contacts', NewContacts);

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
                            <td class="py-5 px-3 text-sm text-center" colspan="6">
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
                    NewContacts = contacts;
                    console.log(NewContacts);
                    let template = ``;
                    let counter = 1;
                    for (contact of contacts) {
                        if (!existingContacts.includes(contact.id + "")) {
                            template += `
                        <tr class="even:bg-gray-200">
                            <td class="py-2 px-3 text-sm">${counter}</td>
                            <td class="py-2 px-3 text-sm">${contact.first_name ?? ''}</td>
                            <td class="py-2 px-3 text-sm">${contact.username ?? ''}</td>
                            <td class="py-2 px-3 text-sm">category</td>
                            <td class="py-2 px-3 text-sm">profile</td>
                            <td class="py-2 px-3 text-sm cursor-pointer" 
                                onclick="addContact(
                                    '${contact.first_name ?? ''}',
                                    '${contact.username ?? ''}',
                                    '${contact.id ?? ''}',
                                    'rezaei.jpeg'
                                )">
                                <img src="./public/img/del.svg" alt="delete icon">
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
