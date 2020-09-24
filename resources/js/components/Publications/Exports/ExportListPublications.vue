<template>
    <div>



        <button class="export-button" @click="exportListPublications"><img src="/img/download.png" alt=""> Експорт публікацій excel</button>




        <table id="exportList" v-show="false" >
            <tr>
                <th>№</th>
                <th>Вид публікації</th>
                <th>Назва публікації</th>
                <th>Прізвище та ініціали автора\ співавторів</th>
                <th>Рік видання</th>
                <th>Науковий керівник</th>
                <th>БД Scopus\ WoS</th>
                <th>SNIP журналу (БД Scopus)</th>
                <th>Квартиль журналу (БД Scopus)</th>
                <th>Імпакт-фактор (БД WoS)</th>
                <th>Квартиль журналу (БД WoS)</th>
                <th>Підбаза WoS)</th>
                <th>Назва журналу</th>
                <th>Номер (том)</th>
                <th>Кількість томів</th>
                <th>Сторінки</th>
                <th>Країна видання</th>
                <th>Місто видання</th>
                <th>Видавництво</th>
                <th>За редакцією</th>
                <th>Кількість сторінок</th>
                <th>Опубліковано мовами ОЕСР </th>
                <th>Назва конференції </th>
                <th>№ патенту/свідоцтва/рішення </th>
                <th>МПК</th>
                <th>Власник майнових прав</th>
                <th>№ заявки</th>
                <th>Дата подачі заявки</th>
                <th>Дата реєстрації свідоцтва/рішення</th>
                <th>№ бюлетеня</th>
                <th>Комерціалізовано університетом</th>
                <th>Комерціалізовано штатними співробітниками університету </th>
                <th>Електронна адреса (url) </th>
                <th>Режим доступу  </th>

                <th>DOI</th>

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
        props:{
            exportList: Array,

        },
        components: {

        },

        methods: {



            exportListPublications(){

                const workbook = XLSX.utils.table_to_book(document.getElementById('exportList'));
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
