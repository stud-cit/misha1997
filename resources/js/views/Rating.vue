<template>
  <b-container class="wrapper">
    <Back page="Рейтингові показники"></Back>
    <b-row>
      <b-col md="3" lg="3" xl="3" xs="12">
        <FilterRating></FilterRating>
      </b-col>
      <b-col md="9" lg="9" xl="9" xs="12">
        <b-alert class="w-100 text-center" variant="danger" :show="true">
          <b-icon class="mr-1" icon="exclamation-octagon-fill"></b-icon> Функції обмежено. Сторінка в розробці.
        </b-alert>
        <b-row>
          <b-col cols="12">
            <button disabled class="button green mr-1"><b-icon class="mr-1" icon="file-earmark-excel-fill"></b-icon> Експорт Excel</button>
            <!-- <a href="/constructor" class="button blue">
              <b-icon class="mr-1" icon="gear-fill"></b-icon> Налаштувати показники
            </a> -->
          </b-col>
        </b-row>
        <table id="exportRating" ref="exportR">
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
                <td></td>
            </tr>
            <tr>
                <td colspan="3">
                    Кількість статей та монографій (розділів) у співавторстві з іноземними партнерами, які мають індекс Гірша за БД Scopus або WoS не нижче 10
                </td>
                <td>{{ ratingData.foreignPublications }}</td>
                <td></td>
            </tr>
            <tr>
                <td colspan="3">
                    Кількість публікацій всього у тому числі:
                </td>
                <td>{{ ratingData.publications }}</td>
                <td></td>
            </tr>
            <tr>
                <td colspan="3">
                    -  монографії проіндексовані БД Scopus або WoS за належності до профілю СумДУ
                </td>
                <td>{{ ratingData.monographsIndexedScopusOrWoSNotSSU }}</td>
                <td></td>
            </tr>
            <tr>
                <td colspan="3">
                    - статей у фахових за статусом виданнях
                </td>
                <td>{{ ratingData.articleProfessionalPublicationsUkraine }}</td>
                <td></td>
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
                <td colspan="5">
                    Показники для звітів
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    Кількість публікацій у виданнях з показником SNIP більше ніж 1,0 за БД Scopus
                </td>
                <td>{{ ratingData.countSnipScopus }}</td>
                <td></td>
            </tr>
            <tr>
                <td colspan="3">
                    Загальне значення індексів Гірша (за БД Scopus  або БД WoS) штатних працівників та докторантів
                </td>
                <td>{{ ratingData.countHirschIndex }}</td>
                <td></td>
            </tr>
            <tr>
                <td colspan="3">
                    Загальне значення індексів Гірша без самоцитувань (за БД Scopus  або БД WoS) штатних працівників та докторантів
                </td>
                <td>{{ ratingData.countHirschIndexWithoutCitations }}</td>
                <td></td>
            </tr>
            <tr>
                <td colspan="3">
                    Кількість тез всього
                </td>
                <td>{{ ratingData.these.count }}</td>
                <td></td>
            </tr>
            <tr>
                <td colspan="3">
                    Тез опублікованих за кордоном
                </td>
                <td>{{ ratingData.these.publishedAbroad }}</td>
                <td></td>
            </tr>
            <tr>
                <td colspan="3">
                    Тез опублікованих зі студентами
                </td>
                <td>{{ ratingData.these.publishedWithStudents }}</td>
                <td></td>
            </tr>
            <tr>
                <td colspan="3">
                    Тез опублікованих з іноземними партнерами
                </td>
                <td>{{ ratingData.these.publishedWithForeignPartners }}</td>
                <td></td>
            </tr>
            <tr>
                <td colspan="3" >
                    Кількість статей (всього), з них:
                </td>
                <td>{{ ratingData.articles.count }}</td>
                <td></td>
            </tr>
            <tr>
                <td colspan="3">
                    - статей опублікованих за кордоном
                </td>
                <td>{{ ratingData.articles.publishedAbroad }}</td>
                <td></td>
            </tr>
            <tr>
                <td colspan="3">
                    - статей з іноземними партнерами
                </td>
                <td>{{ ratingData.articles.publishedWithForeignPartners }}</td>
                <td></td>
            </tr>
            <tr>
                <td colspan="3">
                    Чисельність штатних науково та науково-педагогічних працівників, які мають не менше 5-ти публікацій у періодичних виданнях, що індексуються БД Scopus та/або WoS.
                </td>
                <td>{{ ratingData.authorsHasfivePublications }}</td>
                <td></td>
            </tr>
            <tr>
                <td colspan="3">
                    Публікації що відноситься до квартиля Q4
                </td>
                <td>{{ ratingData.publicationsScopusWoSProfileSSU.quartile4.count }}</td>
                <td>{{ ratingData.publicationsScopusWoSProfileSSU.quartile4.rating }}</td>
            </tr>
        </table>
      </b-col>
    </b-row>
  </b-container>
</template>
<script>
import Back from "../components/Back.vue";
import FilterRating from "../components/FilterRating.vue";

export default {
  components: {
    Back,
    FilterRating
  },
  data() {
    return {
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
    }
  }
}
</script>
<style lang="css" scoped>
    table#exportRating {
        margin-top: 20px;
        border: 1px solid #000 !important;
    }
    table#exportRating td {
      text-align: left;
      padding: 5px;
      vertical-align: center;
      border: 1px solid #000 !important;
    }
</style>