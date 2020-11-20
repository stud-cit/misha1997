<template>
    <div class="wrapper">
        <table id="exportRating" ref="exportR">
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
    </div>
</template>
<script>
export default {
    props: {
        filters: Object
    },
    data() {
        return {
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
        }
    },
    created() {
        console.log(this.filters)
        this.getExportData();
    },
    methods: {
        getExportData() {
            axios.post('/api/export', this.filters).then(response => {
                this.ratingData = Object.assign(this.ratingData, response.data.rating);
            })
        },
    },
}
</script>
<style lang="css" scoped>
    .wrapper {
        background: #fff;
    }
    table {
        width: 90%;
        margin: 0 auto !important;
    }
</style>