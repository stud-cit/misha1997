<template>
<div>



        <button class="export-button" @click="exportRating"><img src="/img/download.png" alt=""> Експорт рейтингових показників</button>

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


            };
        },
        components: {

        },

        methods: {

            exportRating(){

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


</style>
