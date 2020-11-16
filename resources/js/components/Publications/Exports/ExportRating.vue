<template>
<div>
        <button class="export-button" @click="openFiltersPopup"><img src="/img/download.png"> Експорт рейтингових показників</button>

        <div class="other-author" v-if="showFilters">
            <div class="popup-layout">

                <h2 class="popup-title">Фільтри рейтингових показників</h2>

                <form class="search-form">
                    <div class="form-row">

                        <div class="form-group col-lg-6">
                            <label>Інститут / факультет </label>
                            <div class="input-container">
                                <select v-model="filters.faculty" @change="getDepartments">
                                    <option value=""></option>
                                    <option
                                        v-for="(item, index) in divisions"
                                        :key="index"
                                        :value="item.ID_DIV"
                                    >{{item.NAME_DIV}}</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group col-lg-6">
                            <label >Кафедра</label>
                            <div class="input-container">
                                <select v-model="filters.department">
                                    <option value=""></option>
                                    <option
                                        v-for="(item, index) in departments"
                                        :key="index"
                                        :value="item.ID_DIV"
                                    >{{item.NAME_DIV}}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-lg-6">
                            <label >Країна видання </label>
                            <Country :data="filters"></Country>
                        </div>
                        <div class="form-group col-lg-6">
                            <label >Квартиль журналу Scopus </label>
                            <div class="input-container ">
                                <select  v-model="filters.quartil_scopus">
                                    <option value=""></option>
                                    <option v-for="(item, index) in 4" :value="item" :key="index">{{item}}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-lg-6">
                            <label >Вид публікацій</label>
                            <div class="input-container multiselect">
                                <multiselect
                                    v-model="filters.publication_types"
                                    placeholder=""
                                    label="title"
                                    track-by="id"
                                    :options="publicationTypes"
                                    :multiple="true"
                                    :taggable="true"
                                ></multiselect>
                            </div>
                        </div>
                        <div class="form-group col-lg-6">
                            <label >Квартиль журналу WoS</label>
                            <div class="input-container ">
                                <select  v-model="filters.quartil_wos">
                                    <option value=""></option>
                                    <option v-for="(item, index) in 4" :value="item" :key="index">{{item}}</option>
                                </select>
                            </div>
                        </div>

                    </div>
                    <div class="form-row">
                        <div class="form-group col-lg-6">
                            <label >Рік видання</label>
                            <div class="input-container ">
                                <select  v-model="filters.year">
                                    <option value=""></option>
                                    <option v-for="(item, index) in years" :key="index" :value="item">{{item}}</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group col-lg-6">
                            <label >Публікації опубліковані у виданнях з показником SNIP більше ніж 1.0 </label>
                            <div class="input-container">
                                <select v-model="filters.snip">
                                    <option value=""></option>
                                    <option value="1">Так</option>
                                    <option value="0">Ні</option>
                                </select>
                            </div>
                        </div>

                    </div>
                    <div class="form-row">
                        <div class="form-group col-lg-6">
                            <label >Публікації з цифровим ідентифікатором DOI</label>
                            <div class="input-container ">
                                <select  v-model="filters.doi ">
                                    <option value=""></option>
                                    <option value="1">Так</option>
                                    <option value="0">Ні</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-lg-6">
                            <label >Статті у виданнях, які входять до підбази WoS - SCIE</label>
                            <div class="input-container ">
                                <select  v-model="filters.scie ">
                                    <option value=""></option>
                                    <option value="1">Так</option>
                                    <option value="0">Ні</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group checkbox col-lg-6">
                            <input v-model="filters.withStudents" type="checkbox" class="form-check-input" id="withStudents">
                            <label class="form-check-label" for="withStudents">Публікації за авторством та співавторством студентів</label>
                        </div>
                        <div class="form-group col-lg-6">
                            <label >Статті у виданнях, які входять до підбази WoS - SSCI </label>
                            <div class="input-container ">
                                <select  v-model="filters.ssci ">
                                    <option value=""></option>
                                    <option value="1">Так</option>
                                    <option value="0">Ні</option>
                                </select>
                            </div>
                        </div>


                    </div>
                    <div class="form-row">

                        <div class="form-group col-lg-6">
                            <label >Охоронні документи</label>
                            <div class="input-container ">
                                <select  v-model="filters.applicant">
                                    <option value=""></option>
                                    <option value="СумДУ">Отромані на ім'я СумДУ</option>
                                    <option value="не СумДУ">отримані не на ім'я СумДУ</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group checkbox col-lg-6">
                            <div class="input-container">
                                <input v-model="filters.other_organization" type="checkbox" class="form-check-input" id="otherOrganization">
                                <label class="form-check-label" for="otherOrganization">Публікації за співавторством з представниками інших організацій</label>
                            </div>
                        </div>

                    </div>
                    <div class="form-row">
                        <div class="form-group col-lg-6">
                            <label >Комерціалізовані охоронні документи</label>
                            <div class="input-container ">
                                <select  v-model="filters.commercial_applicant">
                                    <option value=""></option>
                                    <option value="1">Університетом</option>
                                    <option value="2">Штатними співробітниками</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-lg-6">
                            <label >Публікації опубліковані за кордоном</label>
                            <div class="input-container ">
                                <select  v-model="filters.abroad ">
                                    <option value=""></option>
                                    <option value="1">Так</option>
                                    <option value="0">Ні</option>
                                </select>
                            </div>
                        </div>

                    </div>
                    <div class="form-row">
                        <div class="form-group col-lg-6">
                            <label >Публікації у співавторстві з іноземними партнерами</label>
                            <div class="input-container ">
                                <select  v-model="filters.withForeigners">
                                    <option value=""></option>
                                    <option value="1">Так</option>
                                    <option value="0">Ні</option>
                                    <option value="10">В тому числі мають індекс гірша за Scopus та WoS не нижче 10</option>
                                </select>

                            </div>
                        </div>
                        <div class="form-group col-lg-6">
                            <label >Індексування БД Scopus\WoS</label>
                            <div class="input-container">
                                <multiselect
                                    v-model="filters.science_types"
                                    placeholder=""
                                    label="title"
                                    track-by="id"
                                    :options="scienceTypes"
                                    :multiple="true"
                                    :taggable="true"
                                ></multiselect>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-lg-6">
                            <label>Рік занесення до бази даних</label>
                            <div class="input-container">
                                <select v-model="filters.year_db">
                                    <option value=""></option>
                                    <option v-for="(item, index) in years" :key="index" :value="item">{{item}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-lg-6">
                        </div>
                    </div>

                </form>
                <div class="step-button-group">
                    <button class="prev" @click="showFilters = false">Назад</button>
                    <button class="next active" @click="getExportData">
                        <span class="spinner-border spinner-border-sm" v-show="loading" role="status" aria-hidden="true"></span> Експортувати дані
                    </button>
                </div>
            </div>
        </div>


        <table id="exportRating" v-show="false" ref="exportR">
            <tr>
                <td colspan="4">
                    Показники, які визначають рейтинг інститутів, факультетів та кафедр СумДУ
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    Кількість статей за авторством та співавторством студентів
                </td>
                <td>{{ ratingData.countStudentPublications }}</td>
            </tr>
            <tr>
                <td colspan="3">
                    Кількість статей та монографій (розділів) у співавторстві з іноземними партнерами, які мають індекс Гірша за БД Scopus або WoS не нижче 10
                </td>
                <td>{{ ratingData.countForeignPublications.count }}</td>
            </tr>
            <tr>
                <td colspan="3">
                    Кількість публікацій всього у тому числі:
                </td>
                <td>{{ ratingData.countPublications }}</td>
            </tr>
            <tr>
                <td colspan="3">
                    -  монографії проіндексовані  БД Scopus або WoS за належності до профілю СумДУ
                </td>
                <td>{{ ratingData.monographsIndexedScopusOrWoSNotSSU }}</td>
            </tr>
            <tr>
                <td colspan="3">
                    - статей у фахових за статусом виданнях
                </td>

                <td>{{ ratingData.articleProfessionalPublicationsUkraine }}</td>
            </tr>
            <tr>
                <td rowspan="16">
                    які опубліковані у виданнях, що індексуються БД Scopus та/або WoS за умови належності до профілю СумДУ
                </td>
                <td rowspan="2">
                    всього за звітний рік
                </td>
                <td>
                    за БД Scopus та WoS:
                </td>
                <td>{{ ratingData.publicationsScopusOrAndWoSNotSSU.countReportingYear.ScopusAndWoS }}</td>
            </tr>
            <tr>
                <td>
                    за БД Scopus або WoS:
                </td>
                <td>{{ ratingData.publicationsScopusOrAndWoSNotSSU.countReportingYear.ScopusOrWoS }}</td>
            </tr>
            <tr>
                <td colspan="2">
                    у т. ч. у виданні, яке відноситься до квартиля Q3
                </td>
                <td>{{ ratingData.publicationsScopusOrAndWoSNotSSU.quartile3 }}</td>
            </tr>
            <tr>
                <td colspan="2">
                    у т. ч. у виданні, яке відноситься до квартиля Q2
                </td>
                <td>{{ ratingData.publicationsScopusOrAndWoSNotSSU.quartile2 }}</td>
            </tr>
            <tr>
                <td colspan="2">
                    у т. ч. у виданні, яке відноситься до квартиля Q1
                </td>
                <td>{{ ratingData.publicationsScopusOrAndWoSNotSSU.quartile1 }}</td>
            </tr>
            <tr>
                <td>
                    - у т.ч. статті у виданнях, які входять до SCIE
                </td>
                <td rowspan="2">
                    - за БД WoS
                </td>
                <td>{{ ratingData.articleWoS.scie }}</td>
            </tr>
            <tr>
                <td>
                    - у т.ч. статті у виданнях, які входять до SSCI
                </td>
                <td>{{ ratingData.articleWoS.ssci }}</td>
            </tr>
            <tr>
                <td colspan="2">
                    - у т.ч. які обліковуються рейтингом Nature Index
                </td>
                <td>{{ ratingData.accountedNatureIndex }}</td>
            </tr>
            <tr>
                <td colspan="2">
                    - у т.ч. у журналах Nature або Scince
                </td>
                <td>{{ ratingData.journalsNatureOrScience }}</td>
            </tr>
            <tr>
                <td colspan="2">
                    - у т.ч. за співавторством з представниками інших організацій
                </td>
                <td>{{ ratingData.authorsOtherOrganizations }}</td>
            </tr>
            <tr>
                <td colspan="2">
                    - у т.ч., що входять до списків Forbes та Fortune
                </td>
                <td>{{ ratingData.authorsInForbesFortune }}</td>
            </tr>
            <tr>
                <td rowspan="2">
                    - у т.ч. які увійшли до найбільш цитованих для своєї предметної галузі
                </td>
                <td>
                    - до 10% за БД Scopus
                </td>
                <td>{{ ratingData.enteredMostCitedSubjectArea.scopus }}</td>
            </tr>
            <tr>
                <td>
                    - До 1% за БД WoS
                </td>
                <td>{{ ratingData.enteredMostCitedSubjectArea.wos }}</td>
            </tr>
            <tr>
                <td colspan="2">
                    - у т.ч. з цифровим ідентифікатором DOI
                </td>
                <td>{{ ratingData.countDOI }}</td>
            </tr>
            <tr>
                <td colspan="2">
                    - які процитовані у міжнародних патентах
                </td>
                <td>{{ ratingData.citedInternationalPatents }}</td>
            </tr>
            <tr>
                <td colspan="2">
                    - всього за 5 років за БД Scopus
                </td>
                <td>{{ ratingData.countScopusFiveYear }}</td>
            </tr>
            <tr>
                <td rowspan="5">
                    Кількість охоронних документів щодо об'єктів права інтелектуальної власності, які 
                </td>
                <td colspan="2">
                    - отримано за звітний рік на ім'я СумДУ
                </td>
                <td>
                    {{ ratingData.receivedReportingNameSSU }}
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    - з них за сумісним авторством з представниками бізнесу
                </td>
                <td>
                    {{ ratingData.authorshipBusinessRepresentatives }}
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    - отримано за звітний рік штатними співробітниками не на ім'я СумДУ
                </td>
                <td>
                    {{ ratingData.receivedReportingEmployeesNotSSU }}
                </td>
            </tr>
            <tr>
                <td rowspan="2">
                    - комерціалізовано у звітному році
                </td>
                <td>
                    - університетом
                </td>
                <td>
                    {{ ratingData.commercializedReportingYear.university }}
                </td>
            </tr>
            <tr>
                <td>
                    - штатним співробітником
                </td>
                <td>
                    {{ ratingData.commercializedReportingYear.employee }}
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    Показники для звітів
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    Кількість публікацій у виданнях з показником SNIP більше ніж 1,0 за БД Scopus
                </td>
                <td>{{ ratingData.countSnipScopus }}</td>
            </tr>
            <tr>
                <td colspan="3">
                    Загальне значення індексів Гірша (за БД Scopus  або БД WoS) штатних працівників та докторантів
                </td>
                <td>{{ ratingData.countHirschIndex }}</td>
            </tr>
            <tr>
                <td colspan="3">
                    Кількість тез всього
                </td>
                <td>{{ ratingData.these.count }}</td>
            </tr>
            <tr>
                <td colspan="3">
                    Тез опублікованих за кордоном
                </td>
                <td>{{ ratingData.these.publishedAbroad }}</td>
            </tr>
            <tr>
                <td colspan="3">
                    Тез опублікованих зі студентами
                </td>
                <td>{{ ratingData.these.publishedWithStudents }}</td>
            </tr>
            <tr>
                <td colspan="3">
                    Тез опублікованих з іноземними партнерами
                </td>
                <td>{{ ratingData.these.publishedWithForeignPartners }}</td>
            </tr>
            <tr>
                <td colspan="3" >
                    Кількість статей (всього), з них:
                </td>
                <td>{{ ratingData.articles.count }}</td>
            </tr>
            <tr>
                <td colspan="3">
                    - статей опублікованих за кордоном
                </td>
                <td>{{ ratingData.articles.publishedAbroad }}</td>
            </tr>
            <tr>
                <td colspan="3">
                    - статей з іноземними партнерами
                </td>
                <td>{{ ratingData.articles.publishedWithForeignPartners }}</td>
            </tr>
            <tr>
                <td colspan="3">
                    Чисельність штатних науково та науково-педагогічних працівників, які мають не менше 5-ти публікацій у періодичних виданнях, що індексуються БД Scopus та/або WoS.
                </td>
                <td>{{ ratingData.authorsHasfivePublications }}</td>
            </tr>
            <tr>
                <td colspan="3">
                    Публікації що відноситься до квартиля Q4
                </td>
                <td>{{ ratingData.publicationsScopusOrAndWoSNotSSU.quartile4 }}</td>
            </tr>
        </table>

        <table id="exportList" v-show="false">
            <tr>
                <th>№</th>
                <th>Назва роботи(мовою оригіналу)</th>
                <th>Вид публікації</th>
                <th>Scopus </th>
                <th>WoS </th>
                <th>Країна видання публікаціїї </th>
                <th>Вихідні дані  </th>
                <th>К-сть сторінок </th>
                <th>Номер (том) </th>
                <th>Посада</th>
                <th>Прізвище, ім'я </th>
                <th>Керівник </th>
                <th>Індекс Гірша WoS </th>
                <th>Індекс Гірша Scopus </th>
                <!-- <th>Прізвище, ініціали наукового керівника </th>
                <th>Індекс Гірша WoS керівника </th>
                <th>Індекс Гірша Scopus керівника </th> -->
                <th>Факультет/країна(для співавторів - громадян інших країн) </th>
                <th>Рейтинг</th>
                <th>Кафедра(для співавторів з інших кафедр)/місце роботи(для співавторів не з СумДУ) </th>
                <th>Рейтинг</th>
                <th>Іноземець</th>
                <th>Рік</th>
                <th>Квартиль журналу Scopus</th>
                <th>Квартиль журналу WoS</th>
                <th>SNIP</th>
                <th>Імпакт-фактор (БД WoS)</th>
                <th>Підбаза WoS</th>
                <th>Мова</th>
                <th>DOI</th>
                <th>Дата занесення до бази даних</th>
            </tr>
            <template v-for="(item, ind) in publicationsData">
            <tr v-for="(a, i) in item.authors" :key="'publication_' + ind + '_author_' + i">
                <td v-if="i == 0" :rowspan="item.authors.length">{{ind + 1}}</td>
                <td v-if="i == 0" :rowspan="item.authors.length">{{item.title}}</td>
                <td>{{ item.publication_type.title }}</td>
                <td v-if="i == 0" :rowspan="item.authors.length">{{ (item.science_type_id && item.science_type_id != 2) ? 'Так' : 'Ні' }}</td>
                <td v-if="i == 0" :rowspan="item.authors.length">{{ (item.science_type_id && item.science_type_id != 1) ? 'Так' : 'Ні' }}</td>
                <td>{{item.country}}</td>
                <td v-if="i == 0" :rowspan="item.authors.length">{{item.out_data}}</td>
                <td v-if="i == 0" :rowspan="item.authors.length">'{{item.pages}}'</td>
                <td v-if="i == 0" :rowspan="item.authors.length">'{{item.number}}'</td>
                <td v-if="a.categ_1 == 1">Студент</td>
                <td v-else-if="a.categ_1 == 2">Аспірант</td>
                <td v-else-if="a.categ_2 == 1">Співробітник</td>
                <td v-else-if="a.categ_2 == 2">Викладач</td>
                <td v-else></td>
                <td>{{ a.name }}</td>
                <td>{{ a.supervisor ? 'Так' : 'Ні' }}</td>
                <td>{{ a.h_index }}</td>
                <td>{{ a.scopus_autor_id }}</td>
                <td>{{ a.faculty ? a.faculty : '' }}</td>
                <td>0</td>
                <td>{{a.department ? a.department : a.job}}</td>
                <td>0</td>
                <td>{{a.country == 'Україна' ? 'Ні' :  a.country ? 'Так' : 'Не вказано'}}</td>
                <td v-if="i == 0" :rowspan="item.authors.length">{{item.year}}</td>
                <td v-if="i == 0" :rowspan="item.authors.length">{{item.quartil_scopus}}</td>
                <td v-if="i == 0" :rowspan="item.authors.length">{{item.quartil_wos}}</td>
                <td v-if="i == 0" :rowspan="item.authors.length">{{item.snip}}</td>
                <td v-if="i == 0" :rowspan="item.authors.length">{{item.impact_factor}}</td>
                <td v-if="i == 0" :rowspan="item.authors.length">{{ item.sub_db_index ? (item.sub_db_index == 1 ? "Science Citation Index Expanded (SCIE)" : "Social Science Citation Index") : "" }}</td>
                <td v-if="i == 0" :rowspan="item.authors.length">{{ item.languages }}</td>
                <td v-if="i == 0" :rowspan="item.authors.length">{{ item.doi }}</td>
                <td v-if="i == 0" :rowspan="item.authors.length">{{ item.date }}</td>
            </tr>
            </template>
        </table>

    </div>
</template>

<script>
    import Multiselect from 'vue-multiselect';
    import Country from "../../Forms/Country";
    import XLSX from 'xlsx';
    export default {
        data() {
            return {
                loading: false,
                showFilters: false,
                departments: [],
                divisions: [],
                publicationsData: [],
                scienceTypes: [
                    {
                        id: 1,
                        title: "Scopus"
                    },
                    {
                        id: 2,
                        title: "WoS"
                    },
                    {
                        id: 3,
                        title: "Scopus та WoS"
                    }
                ],

                ratingData: {
                    countStudentPublications: 0,
                    countForeignPublications: {
                        count: 0,
                        haveIndexScopusWoS: 0
                    },
                    monographsIndexedScopusOrWoSNotSSU: 0,
                    articleProfessionalPublicationsUkraine: 0,
                    publicationsScopusOrAndWoSNotSSU: {
                        countReportingYear: {
                            ScopusAndWoS: 0,
                            ScopusOrWoS: 0
                        },
                        quartile1: 0,
                        quartile2: 0,
                        quartile3: 0,
                        quartile4: 0,
                    },
                    articleWoS: {
                        scie: 0,
                        ssci: 0
                    },
                    accountedNatureIndex: 0,
                    journalsNatureOrScience: 0,
                    authorsOtherOrganizations: 0,
                    authorsInForbesFortune: 0,
                    enteredMostCitedSubjectArea: {
                        scopus: 0,
                        wos: 0
                    },
                    countDOI: 0,
                    citedInternationalPatents: 0,
                    countSnipScopus: 0,
                    countHirschIndex: 0,
                    countEmployees: 0,
                    these: {
                        count: 0,
                        publishedAbroad: 0,
                        publishedWithStudents: 0,
                        publishedWithForeignPartners: 0
                    },
                    articles: {
                        count: 0,
                        publishedAbroad: 0,
                        publishedWithForeignPartners: 0
                    },
                    authorsHasfivePublications: 0,
                    countScopusFiveYear: 0,
                    receivedReportingNameSSU: 0,
                    authorshipBusinessRepresentatives: 0,
                    receivedReportingEmployeesNotSSU: 0,
                    commercializedReportingYear: {
                        university: 0,
                        employee: 0
                    }
                },

                filters: {
                    faculty: '',
                    university: '',
                    department: '',
                    withStudents: false,
                    withForeigners: '',
                    science_types: [],
                    year: '',
                    year_db: new Date().getFullYear(),
                    country: '',
                    quartil_scopus: '',
                    quartil_wos: '',
                    publication_types: [],

                    snip: '',
                    scie: '',
                    ssci: '',

                    doi: '',
                    other_organization: false,
                    abroad: '',
                    applicant: '',
                    commercial_applicant: ''
                }
            };
        },
        components: {
            Country,
            Multiselect
        },
        props:{
            publicationTypes: Array,
            years: Array,
        },
        created() {
            this.getDivisions();
        },

        methods: {
            getDivisions() {
                axios.get('/api/sort-divisions').then(response => {
                    this.divisions = response.data;
                })
            },
            getExportData() {
                axios.post('/api/export', this.filters).then(response => {
                    this.publicationsData = response.data.publications;
                    this.publicationsData.map(publication => {
                        var authors = [];
                        publication.authors.map(author => {
                            authors.push(author.author);
                        })
                        publication.authors = authors;
                    });
                    this.ratingData = Object.assign(this.ratingData, response.data.rating);
                }).then(() => {
                    this.exportRating();
                })
            },
            getDepartments() {
                if(this.filters.faculty == "") {
                    this.filters.department = ''
                }
                let data = this.divisions.find(item => {
                    return this.filters.faculty == item.ID_DIV
                });
                this.departments = data ? data.departments : [];
            },
            openFiltersPopup() {
                this.showFilters = true;
            },
            exportRating() {
                this.loading = true;
                setTimeout(() => {
                    const rating = XLSX.utils.table_to_book(document.getElementById('exportRating'));
                    const publications = XLSX.utils.table_to_book(document.getElementById('exportList'));
                    const wb = XLSX.utils.book_new();
                    wb.SheetNames.push("Publications");
                    wb.SheetNames.push("Rating");
                    wb.Sheets.Publications = publications.Sheets.Sheet1;
                    wb.Sheets.Rating = rating.Sheets.Sheet1;

                    const wscols2 = [
                        {wch:30},
                        {wch:30},
                        {wch:20},
                        {wch:5}
                    ];

                    wb.Sheets.Rating['!cols'] = wscols2;
                    wb.Sheets.Publications['!cols'] = [
                        { wch: 3 },  // id
                        { wch: 10 }, // Назва роботи(мовою оригіналу)
                        { wch: 10 }, // Вид публікації
                        { wch: 5 }, // Scopus
                        { wch: 5 }, // WoS
                        { wch: 5 }, // Країна видання
                        { wch: 10 }, // Вихідні дані
                        { wch: 5 }, // К-сть сторінок
                        { wch: 5 }, // Номер (том)
                        { wch: 10 }, // Посада
                        { wch: 15 }, // Прізвище, ім'я
                        { wch: 5 }, // Керівник
                        { wch: 5 }, // Індекс Гірша WoS
                        { wch: 5 }, // Індекс Гірша Scopus
                        { wch: 15 }, // Факультет/країна(для співавторів - громадян інших країн)
                        { wch: 5 },
                        { wch: 15 }, // Кафедра(для співавторів з інших кафедр)/місце роботи(для співавторів не з СумДУ)
                        { wch: 5 },
                        { wch: 4 }, // Іноземець
                        { wch: 5 },  // Рік
                        { wch: 3 }, // Квартиль журналу Scopus
                        { wch: 3 }, // Квартиль журналу WoS
                        { wch: 3 },  // SNIP
                        { wch: 3 }, // Імпакт-фактор (БД WoS)
                        { wch: 5 }, // Підбаза WoS
                        { wch: 5 }, // Мова
                        { wch: 3 }, // DOI
                        { wch: 10 }, // Дата занесення до бази даних
                    ];
                    XLSX.writeFile(wb, 'Rating.xlsx');
                    this.loading = false;
                }, 0);
            }
        },

    }
</script>

<style lang="scss" scoped>
    .checkbox input[type=checkbox] {
        width: 30px; 
        height: 30px;
        margin: 0;
    }
    .checkbox label {
        margin-left: 40px;
    }
    .spinner-border {
        width: 25px;
        height: 25px;
    }
    .form-row{
        align-items: flex-end;
    }
    .other-author{
        position: absolute;
        top: 0;
        left: 0;
        z-index: 100;
        padding: 5% 10%;
        width: 100%;
        min-height: 100%;
        background: rgba(0,0,0,0.8);
        .popup-layout{
            padding: 60px 60px;
            background-color: #fff;
            border-radius: 10px;
            .popup-title{
                margin-bottom: 30px;
                font-family: Arial;
                font-style: normal;
                font-weight: normal;
                font-size: 30px;
                text-align: center;
                /*color: #18A0FB;*/
            }

        }
    }




    @media  (max-width: 575px) {
        .other-author{
            padding: 10% 10px;

            .popup-layout{
                padding: 15px;

                .popup-title{
                    margin-bottom: 20px;

                    font-size: 25px;

                }

            }
        }



    }

</style>
