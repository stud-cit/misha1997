<template>
<div>
        <button class="export-button" @click="openFiltersPopup"><img src="/img/download.png"> Експорт рейтингових показників</button>

        <div class="other-author" v-if="showFilters">
            <div class="popup-layout">

                <h2 class="popup-title">Фільтри рейтингових показників</h2>

                <form class="search-form">
                    <div class="form-row" v-show="authUser.roles_id != 2">
                        <div class="form-group col-lg" v-show="authUser.roles_id != 3">
                            <label>Інститут / факультет </label>
                            <div class="input-container">
                                <select v-model="filters.faculty_code" @change="getDepartments">
                                    <option value=""></option>
                                    <option
                                        v-for="(item, index) in divisions"
                                        :key="index"
                                        :value="item.ID_DIV"
                                    >{{item.NAME_DIV}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-lg">
                            <label >Кафедра</label>
                            <div class="input-container">
                                <select v-model="filters.department_code">
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
                                    selectLabel="Натисніть для вибору"
                                    selectedLabel="Вибрано"
                                    deselectLabel="Натисніть для видалення"
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
                            <label>Рік видання</label>
                            <div class="input-container multiselect">
                                <multiselect
                                    v-model="filters.years"
                                    placeholder=""
                                    selectLabel="Натисніть для вибору"
                                    selectedLabel="Вибрано"
                                    deselectLabel="Натисніть для видалення"
                                    :options="years"
                                    :multiple="true"
                                    :taggable="true"
                                ></multiselect>
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
                                <select  v-model="filters.ssci">
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
                                    <option value="СумДУ">Отримані на ім'я СумДУ</option>
                                    <option value="не СумДУ">Отримані не на ім'я СумДУ</option>
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
                                    <option value="10">В тому числі мають індекс Гірша за Scopus та WoS не нижче 10</option>
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
                                    selectLabel="Натисніть для вибору"
                                    selectedLabel="Вибрано"
                                    deselectLabel="Натисніть для видалення"
                                    :multiple="true"
                                    :taggable="true"
                                ></multiselect>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-lg-6">
                            <label>Рік занесення до бази даних</label>
                            <div class="input-container multiselect">
                                <multiselect
                                    v-model="filters.year_db"
                                    placeholder=""
                                    selectLabel="Натисніть для вибору"
                                    selectedLabel="Вибрано"
                                    deselectLabel="Натисніть для видалення"
                                    :options="years"
                                    :multiple="true"
                                    :taggable="true"
                                ></multiselect>
                            </div>
                        </div>
                        <div class="form-group checkbox col-lg-6">
                            <div class="input-container">
                                <input v-model="filters.not_previous_year" type="checkbox" class="form-check-input" id="notPreviousYear">
                                <label class="form-check-label" for="notPreviousYear">Публікації які не враховані в рейтингу попереднього року</label>
                            </div>
                        </div>
                        <div class="form-group checkbox col-lg-6">
                            <div class="input-container">
                                <input v-model="filters.not_this_year" type="checkbox" class="form-check-input" id="notThisYear">
                                <label class="form-check-label" for="notThisYear">Публікації які не враховані в рейтингу цього року</label>
                            </div>
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
                <td colspan="3">
                    Показники, які визначають рейтинг інститутів, факультетів та кафедр СумДУ
                </td>
                <td>
                    Кількісний показник
                </td>
                <td>
                    Рейтинговий показник
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    Кількість статей за авторством та співавторством студентів
                </td>
                <td>{{ ratingData.studentPublications }}</td>
            </tr>
            <tr>
                <td colspan="3">
                    Кількість статей та монографій (розділів) у співавторстві з іноземними партнерами, які мають індекс Гірша за БД Scopus або WoS не нижче 10
                </td>
                <td>{{ ratingData.foreignPublications }}</td>
            </tr>
            <tr>
                <td colspan="3">
                    Кількість публікацій всього у тому числі:
                </td>
                <td>{{ ratingData.publications }}</td>
            </tr>
            <tr>
                <td colspan="3">
                    -  монографії проіндексовані БД Scopus або WoS за належності до профілю СумДУ
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
                <td>{{ ratingData.publicationsScopusWoSProfileSSU.countReportingYear.ScopusAndWoS.count }}</td>
                <td>{{ ratingData.publicationsScopusWoSProfileSSU.countReportingYear.ScopusAndWoS.rating }}</td>
            </tr>
            <tr>
                <td>
                    за БД Scopus або WoS:
                </td>
                <td>{{ ratingData.publicationsScopusWoSProfileSSU.countReportingYear.ScopusOrWoS.count }}</td>
                <td>{{ ratingData.publicationsScopusWoSProfileSSU.countReportingYear.ScopusOrWoS.rating }}</td>
            </tr>
            <tr>
                <td colspan="2">
                    у т. ч. у виданні, яке відноситься до квартиля Q3
                </td>
                <td>{{ ratingData.publicationsScopusWoSProfileSSU.quartile3.count }}</td>
                <td>{{ ratingData.publicationsScopusWoSProfileSSU.quartile3.rating }}</td>
            </tr>
            <tr>
                <td colspan="2">
                    у т. ч. у виданні, яке відноситься до квартиля Q2
                </td>
                <td>{{ ratingData.publicationsScopusWoSProfileSSU.quartile2.count }}</td>
                <td>{{ ratingData.publicationsScopusWoSProfileSSU.quartile2.rating }}</td>
            </tr>
            <tr>
                <td colspan="2">
                    у т. ч. у виданні, яке відноситься до квартиля Q1
                </td>
                <td>{{ ratingData.publicationsScopusWoSProfileSSU.quartile1.count }}</td>
                <td>{{ ratingData.publicationsScopusWoSProfileSSU.quartile1.rating }}</td>
            </tr>
            <tr>
                <td>
                    - у т.ч. статті у виданнях, які входять до SCIE
                </td>
                <td rowspan="2">
                    - за БД WoS
                </td>
                <td>{{ ratingData.publicationsScopusWoSProfileSSU.articleWoS.scie.count }}</td>
                <td>{{ ratingData.publicationsScopusWoSProfileSSU.articleWoS.scie.rating }}</td>
            </tr>
            <tr>
                <td>
                    - у т.ч. статті у виданнях, які входять до SSCI
                </td>
                <td>{{ ratingData.publicationsScopusWoSProfileSSU.articleWoS.ssci.count }}</td>
                <td>{{ ratingData.publicationsScopusWoSProfileSSU.articleWoS.ssci.rating }}</td>
            </tr>
            <tr>
                <td colspan="2">
                    - у т.ч. які обліковуються рейтингом Nature Index
                </td>
                <td>{{ ratingData.publicationsScopusWoSProfileSSU.accountedNatureIndex.count }}</td>
                <td>{{ ratingData.publicationsScopusWoSProfileSSU.accountedNatureIndex.rating }}</td>
            </tr>
            <tr>
                <td colspan="2">
                    - у т.ч. у журналах Nature або Scince
                </td>
                <td>{{ ratingData.publicationsScopusWoSProfileSSU.journalsNatureOrScience.count }}</td>
                <td>{{ ratingData.publicationsScopusWoSProfileSSU.journalsNatureOrScience.rating }}</td>
            </tr>
            <tr>
                <td colspan="2">
                    - у т.ч. за співавторством з представниками інших організацій
                </td>
                <td>{{ ratingData.publicationsScopusWoSProfileSSU.authorsOtherOrganizations.count }}</td>
                <td>{{ ratingData.publicationsScopusWoSProfileSSU.authorsOtherOrganizations.rating }}</td>
            </tr>
            <tr>
                <td colspan="2">
                    - у т.ч., що входять до списків Forbes та Fortune
                </td>
                <td>{{ ratingData.publicationsScopusWoSProfileSSU.authorsInForbesFortune.count }}</td>
                <td>{{ ratingData.publicationsScopusWoSProfileSSU.authorsInForbesFortune.rating }}</td>
            </tr>
            <tr>
                <td rowspan="2">
                    - у т.ч. які увійшли до найбільш цитованих для своєї предметної галузі
                </td>
                <td>
                    - до 10% за БД Scopus
                </td>
                <td>{{ ratingData.publicationsScopusWoSProfileSSU.enteredMostCitedSubjectArea.scopus.count }}</td>
                <td>{{ ratingData.publicationsScopusWoSProfileSSU.enteredMostCitedSubjectArea.scopus.rating }}</td>
            </tr>
            <tr>
                <td>
                    - До 1% за БД WoS
                </td>
                <td>{{ ratingData.publicationsScopusWoSProfileSSU.enteredMostCitedSubjectArea.wos.count }}</td>
                <td>{{ ratingData.publicationsScopusWoSProfileSSU.enteredMostCitedSubjectArea.wos.rating }}</td>
            </tr>
            <tr>
                <td colspan="2">
                    - у т.ч. з цифровим ідентифікатором DOI
                </td>
                <td>{{ ratingData.publicationsScopusWoSProfileSSU.countDOI.count }}</td>
                <td>{{ ratingData.publicationsScopusWoSProfileSSU.countDOI.rating }}</td>
            </tr>
            <tr>
                <td colspan="2">
                    - які процитовані у міжнародних патентах
                </td>
                <td>{{ ratingData.publicationsScopusWoSProfileSSU.citedInternationalPatents.count }}</td>
                <td>{{ ratingData.publicationsScopusWoSProfileSSU.citedInternationalPatents.rating }}</td>
            </tr>
            <tr>
                <td colspan="2">
                    - всього за 5 років за БД Scopus
                </td>
                <td>{{ ratingData.publicationsScopusWoSProfileSSU.countScopusFiveYear.count }}</td>
                <td>{{ ratingData.publicationsScopusWoSProfileSSU.countScopusFiveYear.rating }}</td>
            </tr>
            <tr>
                <td rowspan="5">
                    Кількість охоронних документів щодо об'єктів права інтелектуальної власності, які
                </td>
                <td colspan="2">
                    - отримано за звітний рік на ім'я СумДУ
                </td>
                <td>{{ ratingData.numberSecurityDocuments.receivedReportingNameSSU.count }}</td>
                <td>{{ ratingData.numberSecurityDocuments.receivedReportingNameSSU.rating }}</td>
            </tr>
            <tr>
                <td colspan="2">
                    - з них за сумісним авторством з представниками бізнесу
                </td>
                <td>{{ ratingData.numberSecurityDocuments.authorshipBusinessRepresentatives.count }}</td>
                <td>{{ ratingData.numberSecurityDocuments.authorshipBusinessRepresentatives.rating }}</td>
            </tr>
            <tr>
                <td colspan="2">
                    - отримано за звітний рік штатними співробітниками не на ім'я СумДУ
                </td>
                <td>{{ ratingData.numberSecurityDocuments.receivedReportingEmployeesNotSSU.count }}</td>
                <td>{{ ratingData.numberSecurityDocuments.receivedReportingEmployeesNotSSU.rating }}</td>
            </tr>
            <tr>
                <td rowspan="2">
                    - комерціалізовано у звітному році
                </td>
                <td>
                    - університетом
                </td>
                <td>{{ ratingData.numberSecurityDocuments.commercializedReportingYear.university.count }}</td>
                <td>{{ ratingData.numberSecurityDocuments.commercializedReportingYear.university.rating }}</td>
            </tr>
            <tr>
                <td>
                    - штатним співробітником
                </td>
                <td>{{ ratingData.numberSecurityDocuments.commercializedReportingYear.employee.count }}</td>
                <td>{{ ratingData.numberSecurityDocuments.commercializedReportingYear.employee.rating }}</td>
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
                    Загальне значення індексів Гірша без самоцитувань (за БД Scopus  або БД WoS) штатних працівників та докторантів
                </td>
                <td>{{ ratingData.countHirschIndexWithoutCitations }}</td>
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
                <td>{{ ratingData.publicationsScopusWoSProfileSSU.quartile4.count }}</td>
                <td>{{ ratingData.publicationsScopusWoSProfileSSU.quartile4.rating }}</td>
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
                <th>Студент</th>
                <th>Випускник</th>
                <th>Прізвище, ім'я </th>
                <th>Керівник </th>
                <th>Максимальний Індекс Гірша</th>
                <th>Індекс Гірша іноземця більше 10</th>
                <th>Факультет/країна(для співавторів - громадян інших країн) </th>
                <th>Рейтинг</th>
                <th>Кафедра(для співавторів з інших кафедр)/місце роботи(для співавторів не з СумДУ) </th>
                <th>Інші організацій</th>
                <th>Рейтинг</th>
                <th>Іноземець</th>
                <th>Іноземець</th>
                <th>Рік</th>
                <th>Квартиль Scopus</th>
                <th>Квартиль WoS</th>
                <th>Квартиль</th>
                <th>SNIP</th>
                <th>Імпакт-фактор (БД WoS)</th>
                <th>Підбаза WoS</th>
                <th>Nature Index</th>
                <th>DOI</th>
                <th>Охоронні документи</th>
                <th>У журналах</th>
                <th>Дата занесення до бази даних</th>
            </tr>
            <template v-for="(item, ind) in publicationsData">
            <tr v-for="(a, i) in item.authors" :key="'publication_' + ind + '_author_' + i">
                <td v-if="i == 0" :rowspan="item.authors.length">{{ind + 1}}</td>
                <td v-if="i == 0" :rowspan="item.authors.length">{{item.title}}</td>
                <td>{{ item.publication_type.title }}</td>
                <td>{{ (item.science_type_id && item.science_type_id != 2) ? 'Так' : 'Ні' }}</td>
                <td>{{ (item.science_type_id && item.science_type_id != 1) ? 'Так' : 'Ні' }}</td>
                <td>{{item.country}}</td>
                <td v-if="i == 0" :rowspan="item.authors.length">{{item.out_data}}</td>
                <td v-if="i == 0" :rowspan="item.authors.length">'{{item.pages}}'</td>
                <td v-if="i == 0" :rowspan="item.authors.length">'{{item.number}}'</td>
                <td v-if="a.author.categ_1 == 1">Студент</td>
                <td v-else-if="a.author.categ_1 == 2">Аспірант</td>
                <td v-else-if="a.author.categ_1 == 3">Випускник</td>
                <td v-else-if="a.author.categ_2 == 1">Співробітник</td>
                <td v-else-if="a.author.categ_2 == 2">Викладач</td>
                <td v-else-if="a.author.categ_2 == 3">Менеджер</td>
                <td v-else></td>
                <td>{{ item.authors.find(user => user.author.categ_1 == 1) ? "Студент" : "" }}</td>
                <td>{{ item.authors.find(user => user.author.categ_1 == 3) ? "Випускник" : "" }}</td>
                <td>{{ a.author.name }}</td>
                <td>{{ a.supervisor ? 'Так' : 'Ні' }}</td>

                <td>{{ indexHirsha(a.author) }}</td>

                <td>{{ item.authors.map(user => (user.author.scopus_autor_id >= 10 && user.author.country != "Україна") ? +user.author.scopus_autor_id : '').reduce((a, b) => a > b ? a : b) }}</td>

                <td>{{ a.author.faculty ? a.author.faculty : (a.author.categ_1 != 1 ? a.author.country : "") }}</td>
                <td>{{ a.rating_faculty }}</td>
                <td>{{a.author.department ? a.author.department : a.author.job}}</td>

                <td>{{ item.authors.find(user => (user.author.job != 'СумДУ' && user.author.job != 'СумДУ (Не працює)' && user.author.job != 'Не працює' && user.author.guid == null)) ? "Так" : "Ні" }}</td>

                <td>{{ a.rating_department }}</td>
                <td>{{a.author.country == 'Україна' ? 'Ні' : a.author.country ? 'Так' : 'Не вказано'}}</td>
                <td>{{ item.authors.find(user => user.author.country != 'Україна') ? "Так" : "Ні" }}</td>
                <td v-if="i == 0" :rowspan="item.authors.length">{{item.year}}</td>
                <td v-if="i == 0" :rowspan="item.authors.length">{{item.quartil_scopus}}</td>
                <td v-if="i == 0" :rowspan="item.authors.length">{{item.quartil_wos}}</td>

                <td v-if="i == 0" :rowspan="item.authors.length">{{ quartil(item) }}</td>

                <td>{{item.snip}}</td>
                <td v-if="i == 0" :rowspan="item.authors.length">{{item.impact_factor}}</td>
                <td v-if="i == 0" :rowspan="item.authors.length">{{
                    item.sub_db_scie == 1 && item.sub_db_ssci == 0 ? "SCIE" : "" ||
                    item.sub_db_scie == 0 && item.sub_db_ssci == 1 ? "SSCI" : "" ||
                    item.sub_db_scie == 1 && item.sub_db_ssci == 1 ? "SCIE, SSCI" : "" }}
                </td>
                <td>{{ item.nature_index == 1 ? "Так" : "Ні" }}</td>
                <td v-if="i == 0" :rowspan="item.authors.length">{{ item.doi }}</td>
                <td v-if="i == 0" :rowspan="item.authors.length">{{ item.applicant }}</td>
                <td v-if="i == 0" :rowspan="item.authors.length">{{ item.nature_science }}</td>
                <td v-if="i == 0" :rowspan="item.authors.length">{{ item.created_at }}</td>
            </tr>
            </template>
        </table>

    </div>
</template>

<script>
    import divisions from '../../mixins/divisions';
    import Multiselect from 'vue-multiselect';
    import Country from "../../Forms/Country";
    import XLSX from 'xlsx';
    export default {
        mixins: [divisions],
        data() {
            return {
                loading: false,
                showFilters: false,
                publicationsData: [],
                publicationTypes: [],
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
                    studentPublications: 0,
                    foreignPublications: 0,
                    publications: 0,
                    monographsIndexedScopusOrWoSNotSSU: 0,
                    articleProfessionalPublicationsUkraine: 0,
                    publicationsScopusWoSProfileSSU: {
                        countReportingYear: {
                            ScopusAndWoS: {
                                rating: 0,
                                count: 0
                            },
                            ScopusOrWoS: {
                                rating: 0,
                                count: 0
                            }
                        },
                        quartile1: {
                            rating: 0,
                            count: 0
                        },
                        quartile2: {
                            rating: 0,
                            count: 0
                        },
                        quartile3: {
                            rating: 0,
                            count: 0
                        },
                        quartile4: {
                            rating: 0,
                            count: 0
                        },
                        articleWoS: {
                            scie: {
                                rating: 0,
                                count: 0
                            },
                            ssci: {
                                rating: 0,
                                count: 0
                            }
                        },
                        accountedNatureIndex: {
                            rating: 0,
                            count: 0
                        },
                        journalsNatureOrScience: {
                            rating: 0,
                            count: 0
                        },
                        authorsOtherOrganizations: {
                            rating: 0,
                            count: 0
                        },
                        authorsInForbesFortune: {
                            rating: 0,
                            count: 0
                        },
                        enteredMostCitedSubjectArea: {
                            scopus: {
                                rating: 0,
                                count: 0
                            },
                            wos: {
                                rating: 0,
                                count: 0
                            }
                        },
                        countDOI: {
                            rating: 0,
                            count: 0
                        },
                        citedInternationalPatents: {
                            rating: 0,
                            count: 0
                        },
                        countScopusFiveYear: {
                            rating: 0,
                            count: 0
                        }
                    },
                    numberSecurityDocuments: {
                        receivedReportingNameSSU: {
                            rating: 0,
                            count: 0
                        },
                        authorshipBusinessRepresentatives: {
                            rating: 0,
                            count: 0
                        },
                        receivedReportingEmployeesNotSSU: {
                            rating: 0,
                            count: 0
                        },
                        commercializedReportingYear: {
                            university: {
                                rating: 0,
                                count: 0
                            },
                            employee: {
                                rating: 0,
                                count: 0
                            }
                        },
                    },
                    countSnipScopus: 0,
                    countHirschIndex: 0,
                    countHirschIndexWithoutCitations: 0,
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
                    authorsHasfivePublications: 0
                },

                filters: {
                    faculty_code: '',
                    department_code: '',
                    withStudents: false,
                    withForeigners: '',
                    science_types: [],
                    years: [],
                    year_db: [new Date().getFullYear()],
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
                    commercial_applicant: '',
                    not_previous_year: false
                }
            };
        },
        components: {
            Country,
            Multiselect
        },
        props:{
            years: Array,
        },
        created() {
            this.getDivisions();
            this.getPublicationTypes();
        },
        computed: {
            authUser() {
                return this.$store.getters.authUser
            },
        },
        methods: {
            getPublicationTypes() {
                axios.get('/api/type-publications').then(response => {
                    this.publicationTypes = response.data;
                })
            },
            quartil(item) {
                var result = Math.min(...[item.quartil_scopus, item.quartil_wos].filter(i => i != null));
                return isFinite(result) ? result : "";
            },
            indexHirsha(item) {
                var result = Math.max(...[item.h_index, item.scopus_autor_id].filter(i => i != null));
                return isFinite(result) ? result : "";
            },
            getExportData() {
                axios.post('/api/export', this.filters).then(response => {
                    this.publicationsData = Object.values(response.data.publications);
                    this.ratingData = Object.assign(this.ratingData, response.data.rating);
                }).then(() => {
                    this.exportRating();
                })
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
                        { wch: 10 }, // Студент
                        { wch: 10 }, // Випускник
                        { wch: 15 }, // Прізвище, ім'я
                        { wch: 5 }, // Керівник
                        { wch: 5 }, // Індекс Гірша WoS
                        { wch: 5 }, // Індекс Гірша WoS
                        { wch: 15 }, // Факультет/країна(для співавторів - громадян інших країн)
                        { wch: 5 },
                        { wch: 15 }, // Кафедра(для співавторів з інших кафедр)/місце роботи(для співавторів не з СумДУ)
                        { wch: 5 },
                        { wch: 5 },
                        { wch: 4 }, // Іноземець
                        { wch: 4 }, // Іноземець
                        { wch: 5 },  // Рік
                        { wch: 3 }, // Квартиль Scopus
                        { wch: 3 }, // Квартиль WoS
                        { wch: 3 }, // Квартиль
                        { wch: 3 },  // SNIP
                        { wch: 3 }, // Імпакт-фактор (БД WoS)
                        { wch: 5 }, // Підбаза WoS
                        { wch: 5 }, // Nature Index
                        { wch: 3 }, // DOI
                        { wch: 7 }, // DOI
                        { wch: 7 }, // DOI
                        { wch: 10 }, // Дата занесення до бази даних
                    ];
                    var name = "Рейтинг ";
                    var division = "";
                    if(this.authUser.roles_id == 3) {
                        division = this.authUser.faculty;
                    }
                    if(this.authUser.roles_id == 2) {
                        division = this.authUser.department;
                    }
                    if(this.filters.faculty_code) {
                        division = this.divisions.find(item => item.ID_DIV == this.filters.faculty_code).NAME_DIV;
                    }
                    if(this.filters.department_code) {
                        division = this.departments.find(item => item.ID_DIV == this.filters.department_code).NAME_DIV;
                    }

                    if(division) {
                        name += division;
                    } else {
                        name += "Університету";
                    }

                    var options = {
                        year: 'numeric',
                        month: 'numeric',
                        day: 'numeric',
                        timezone: 'UTC'
                    };

                    name += " " + new Date().toLocaleString("ru", options);
                    XLSX.writeFile(wb, name+'.xlsx');
                    this.loading = false;
                }, 0);
            }
        },

    }
</script>

<style lang="scss" scoped>
    .step-button-group {
        margin-top: 0;
    }
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
