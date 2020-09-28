<template>
<div>



        <button class="export-button" @click="openFiltersPopup"><img src="/img/download.png" alt=""> Експорт рейтингових показників</button>

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
                                        v-for="(item, index) in divisions.institute"
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

                        <!-- <div class="form-group col-lg-6">
                            <label >Університет</label>
                            <div class="input-container ">
                                <select  v-model="filters.university">
                                    <option value=""></option>
                                    <option value="1">Scopus</option>
                                    <option value="2">Wos</option>
                                    <option value="3">Scopus та Wos</option>
                                </select>

                            </div>
                        </div> -->

                    </div>
                    <div class="form-row">
                        <div class="form-group col-lg-6">
                            <label >Країна видання </label>
                            <div class="input-container ">
                                <select  v-model="filters.country">
                                    <option value=""></option>
                                    <option v-for="(item, index) in countries" :value="item.name" :key="index">{{item.name}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-lg-6">
                            <label >Квартиль журналу SCOPUS </label>
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
                            <div class="input-container ">
                                <select  v-model="filters.publication_type_id">
                                    <option value=""></option>
                                    <option v-for="(item, index) in publicationTypes" :value="item.id" :key="index">{{item.title}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-lg-6">
                            <label >Квартиль журналу WOS</label>
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
                            <label >Рік
                                видання </label>
                            <div class="input-container ">
                                <select  v-model="filters.year">
                                    <option value=""></option>
                                    <option v-for="(item, index) in years" :key="index" :value="item">{{item}}</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group col-lg-6">
                            <label >Публікації опубліковані у виданнях з показником SNIP більше ніж 1,0 </label>
                            <div class="input-container ">
                                <select  v-model="filters.snip ">
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
                            <label >Статті у виданнях, які входять до підбази WOS - SCIE</label>
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
                        <div class="form-group col-lg-6">
                            <label >Публікації
                                за авторством та
                                співавторством студентів</label>
                            <div class="input-container ">
                                <select  v-model="filters.withStudents">
                                    <option value=""></option>
                                    <option value="1">Так</option>
                                    <option value="0">Ні</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-lg-6">
                            <label >Статті у виданнях, які входять до підбази WOS - SSCI </label>
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

                        <div class="form-group col-lg-6">
                            <label >Публікації за співавторством з представниками інших організацій</label>
                            <div class="input-container ">
                                <select  v-model="filters.other_organization ">
                                    <option value=""></option>
                                    <option value="1">Так</option>
                                    <option value="0">Ні</option>
                                </select>
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
                            <div class="input-container ">
                                <select  v-model="filters.science_type_id">
                                    <option value=""></option>
                                    <option value="1">Scopus</option>
                                    <option value="2">Wos</option>
                                    <option value="3">Scopus та Wos</option>
                                </select>
                            </div>
                        </div>
                    </div>

                </form>
                <div class="step-button-group">
                    <button class="prev" @click="showFilters = false">Назад</button>
                    <button class="next active" @click="exportRating">Експортувати дані </button>

                </div>

            </div>
        </div>


        <table id="exportRating" v-show="false" ref="exportR">
            <tr>
                <td colspan="3">
                    Кількість статей
                    за авторством та
                    співавторством студентів
                </td>

                <td>value</td>

            </tr>

            <tr>
                <td rowspan="2" >
                    Кількість публікацій у співавторстві
                    з іноземними партнерами
                </td>
                <td colspan="2">
                    Всього
                </td>

                <td>value</td>

            </tr>
            <tr>
                <td colspan="2">
                    Мають індекс Гірша за БД Scopus або WoS не нижче 10
                </td>

                <td>value</td>
            </tr>

            <tr>
                <td colspan="3">
                    Кількість публікацій всього
                    у тому числі:
                </td>


            </tr>

            <tr>
                <td colspan="3">
                    - підручників
                </td>

                <td>value</td>
            </tr>

            <tr>
                <td colspan="3">
                    - посібників
                </td>

                <td>value</td>
            </tr>

            <tr>
                <td colspan="3">
                    - монографій
                </td>

                <td>value</td>
            </tr>

            <tr>
                <td colspan="3">
                    - опублікованих за кордоном мовами ОЕСР та ЕС
                    проіндексовані БД Scopus або WoS
                    статей у фахових за статусом виданнях
                </td>

                <td>value</td>
            </tr>

            <tr>
                <td colspan="3">
                    - статей у фахових за статусом виданнях
                </td>

                <td>value</td>
            </tr>

            <tr>
                <td rowspan="15" >
                    -	які опубліковані у виданнях, що індексуються БД Scopus та/або WoS:
                </td>
                <td rowspan="2">
                    всього за звітний рік
                </td>
                <td >
                    за БД Scopus та WoS:
                </td>

                <td>value</td>

            </tr>
            <tr>
                <td >
                    за БД Scopus або WoS:
                </td>

                <td>value</td>
            </tr>
            <tr>
                <td colspan="2">
                    -	 у тому числі у виданнях з показником SNIP більше ніж 1,0 за БД Scopus
                </td>

                <td>value</td>
            </tr>
            <tr>
                <td >
                    -	у т.ч. статті у виданнях, які входять до SCIE
                </td>
                <td rowspan="3">
                    -	за БД WoS
                </td>

                <td>value</td>
            </tr>
            <tr>
                <td >
                    -	у т.ч. статті у виданнях, які входять до SSCI
                </td>


                <td>value</td>
            </tr>
            <tr>
                <td >
                    -	у т.ч. у виданнях з імпакт-фактором більше ніж 0&#8218;5
                </td>


                <td>value</td>
            </tr>
            <tr>
                <td colspan="2">
                    -	у т.ч. які обліковуються рейтингом Nature Index
                </td>


                <td>value</td>
            </tr>
            <tr>
                <td colspan="2">
                    -	у т.ч. у журналах Nature Scince
                </td>


                <td>value</td>
            </tr>
            <tr>
                <td colspan="2">
                    -	у т.ч. за співавторством з представниками інших організацій
                </td>


                <td>value</td>
            </tr>
            <tr>
                <td colspan="2">
                    -	у т.ч., що входять до списків Forbes та Fortune
                </td>


                <td>value</td>
            </tr>
            <tr>
                <td rowspan="2">
                    -	у т.ч. які увійшли до найбільш цитованих для своєї предметної галузі
                </td>
                <td>
                    -	до 10% за БД Scopus
                </td>


                <td>value</td>
            </tr>
            <tr>

                <td>
                    -	До 1% за БД WoS
                </td>


                <td>value</td>
            </tr>
            <tr>

                <td colspan="2">
                    -	у т.ч. з цифровим ідентифікатором DOI
                </td>


                <td>value</td>
            </tr>
            <tr>

                <td colspan="2">
                    -	які процитовані у міжнародних патентах
                </td>


                <td>value</td>
            </tr>
            <tr>

                <td colspan="2">
                    -	всього за 5 років за БД Scopus
                </td>


                <td>value</td>
            </tr>


            <tr>

                <td colspan="3">
                    Показники для звітів
                </td>


                <td></td>
            </tr>

            <tr>

                <td colspan="3">
                    Кількість тез всього
                </td>


                <td>value</td>
            </tr>

            <tr>

                <td colspan="3">
                    Тез опублікованих за кордоном
                </td>


                <td>value</td>
            </tr>

            <tr>

                <td colspan="3">
                    Тез опублікованих зі студентами
                </td>


                <td>value</td>
            </tr>

            <tr>

                <td colspan="3">
                    Тез опублікованих з іноземними партнерами
                </td>


                <td>value</td>
            </tr>

            <tr>

                <td colspan="2" rowspan="2">
                    Кількість статей (всього), з них:
                </td>
                <td>
                    -	статей опублікованих за кордоном
                </td>


                <td>value</td>
            </tr>

            <tr>

                <td >
                    -	статей з іноземними партнерами
                </td>


                <td>value</td>
            </tr>

            <tr>

                <td colspan="3">
                    Чисельність штатних науково та науково-педагогічних працівників, які мають не менше 5-ти публікацій у виданнях, що індексуються БД Scopus та/або WoS.
                </td>


                <td>value</td>
            </tr>





        </table>

</div>
</template>

<script>
    import XLSX from 'xlsx';
    export default {
        data() {
            return {
                showFilters: false,
                departments: [],
                divisions: {
                    institute: [],
                    department: []
                },
                filters: {
                    faculty: '',
                    university: '',
                    department: '',
                    withStudents: '',
                    withForeigners: '',
                    science_type_id: '',
                    year: '',
                    country: '',
                    quartil_scopus: '',
                    quartil_wos: '',
                    publication_type_id: '',

                    snip: '',
                    scie: '',
                    ssci: '',

                    doi: '',
                    other_organization: '',
                    abroad: '',
                    applicant: '',
                    commercial_applicant: ''
                }
            };
        },
        props:{
            publicationTypes: Array,
            countries: Array,
            years: Array,
        },
        created() {
            this.getDivisions();
        },

        methods: {
            getDivisions() {
                axios.get('/api/divisions').then(response => {
                    this.divisions = response.data;
                })
            },
            getDepartments() {
                this.departments = this.divisions.department.filter(item => {
                    return this.filters.faculty == item.ID_PAR
                })
            },
            openFiltersPopup() {
                this.showFilters = true;
            },
            exportRating() {
                const workbook = XLSX.utils.table_to_book(document.getElementById('exportRating'));
                console.log(workbook.Sheets.Sheet1);
                var wscols = [
                    {wch:65},
                    {wch:65},
                    {wch:40},
                    {wch:10}
                ];
                workbook.Sheets.Sheet1['!cols'] = wscols;

                XLSX.writeFile(workbook, 'filename.xlsx');
            }
        },

    }
</script>

<style lang="scss" scoped>
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
