<template>
  <div>
    <b-overlay :show="loading" rounded="sm">
      <b-container class="wrapper">
        <Back page=""></Back>
        <h1>{{ data.title }}<b-icon v-if="!loading && data.scopus_id && data.verification == 1" v-b-popover.hover.top="'Верифіковано'" icon="check"></b-icon></h1>
        <hr>
        <b-alert v-if="!loading && data.scopus_id && data.verification == 0" show variant="danger">Публікацію не верифіковано! Щоб верифікувати публікацію <a :href="'/publication/edit/'+data.id">відредагуйте</a> та заповніть всі необхідні поля.</b-alert>
        <a v-if="checkAccessPublication" :href="'/publication/edit/'+data.id" class="button white-green">Редагувати</a>
        <button 
          v-if="data.status_id == 1 && checkAccessPublication" 
          class="button grey" 
          @click="deletePublication()"
        >Видалити</button>
        <button 
          v-if="data.status_id == 2 && checkAccessPublication" 
          class="button white-green"
          @click="restorePublications()"
        >Відновити</button>
        <!--  -->
        <b-row class="my-2 line mb-2 py-1 mx-0">
          <b-col md="4" lg="4" xl="4" xs="12" class="px-1">
            Автори:
          </b-col>
          <b-col md="8" lg="8" xl="8" xs="12" class="px-1">
            <span 
              v-for="(author, index) in data.authors" 
              :key="index"
            >
              <a :href="'/user/'+author.author.id">{{ author.author.name }} </a>
            </span>
          </b-col>
        </b-row>
        <!--  -->
        <b-row class="line mb-2 py-1  mx-0">
          <b-col md="4" lg="4" xl="4" xs="12" class="px-1">
            Вид публікації:
          </b-col>
          <b-col class="px-1">
            {{ data.publication_type.title }}
          </b-col>
        </b-row>
        <!--  -->
        <b-row class="line mb-2 py-1  mx-0" v-if="data.supervisor">
          <b-col md="4" lg="4" xl="4" xs="12" class="px-1">
            Керівник:
          </b-col>
          <b-col class="px-1">
            <a :href="'/user/'+data.supervisor.author.id">{{ data.supervisor.author.name }}</a>
          </b-col>
        </b-row>
        <!--  -->
        <b-row class="line mb-2 py-1  mx-0" v-if="data.science_type">
          <b-col md="4" lg="4" xl="4" xs="12" class="px-1">
            БД Scopus/WoS:
          </b-col>
          <b-col class="px-1">
            {{ data.science_type.type }}
          </b-col>
        </b-row>
        <!--  -->
        <b-row class="line mb-2 py-1  mx-0" v-show="data.snip">
          <b-col md="4" lg="4" xl="4" xs="12" class="px-1">
            SNIP журналу (БД Scopus):
          </b-col>
          <b-col class="px-1">
            {{ data.snip }}
          </b-col>
        </b-row>
        <!--  -->
        <b-row class="line mb-2 py-1  mx-0" v-show="data.quartil_scopus">
          <b-col md="4" lg="4" xl="4" xs="12" class="px-1">
            Квартиль журналу (БД Scopus):
          </b-col>
          <b-col class="px-1">
            {{ data.quartil_scopus }}
          </b-col>
        </b-row>
        <!--  -->
        <b-row class="line mb-2 py-1  mx-0" v-show="data.impact_factor">
          <b-col md="4" lg="4" xl="4" xs="12" class="px-1">
            Імпакт-фактор (БД WoS):
          </b-col>
          <b-col class="px-1">
            {{ data.impact_factor }}
          </b-col>
        </b-row>
        <!--  -->
        <b-row class="line mb-2 py-1  mx-0" v-show="data.quartil_wos">
          <b-col md="4" lg="4" xl="4" xs="12" class="px-1">
            Квартиль журналу (БД WoS):
          </b-col>
          <b-col class="px-1">
            {{ data.quartil_wos }}
          </b-col>
        </b-row>
        <!--  -->
        <b-row class="line mb-2 py-1  mx-0" v-if="!(data.sub_db_scie==0 && data.sub_db_ssci==0)">
          <b-col md="4" lg="4" xl="4" xs="12" class="px-1">
            Підбаза WoS:
          </b-col>
          <b-col class="px-1">
            <span v-if="data.sub_db_scie==1 && data.sub_db_ssci==0">Science Citation Index Expanded (SCIE)</span>
            <span v-if="data.sub_db_ssci==1 && data.sub_db_scie==0">Social Science Citation Index (SSCI)</span>
            <span v-if="data.sub_db_scie==1 && data.sub_db_ssci==1">Science Citation Index Expanded (SCIE), Social Science Citation Index (SSCI)</span>
          </b-col>
        </b-row>
        <!--  -->
        <b-row class="line mb-2 py-1  mx-0" v-if="data.science_type_id">
          <b-col md="4" lg="4" xl="4" xs="12" class="px-1">
            Обліковується рентингом Nature Index:
          </b-col>
          <b-col class="px-1">
            {{ data.nature_index == 1 ? "Так" : "Ні" }}
          </b-col>
        </b-row>
        <!--  -->
        <b-row class="line mb-2 py-1  mx-0" v-show="data.nature_science">
          <b-col md="4" lg="4" xl="4" xs="12" class="px-1">
            У журналах:
          </b-col>
          <b-col class="px-1">
            {{data.nature_science}}
          </b-col>
        </b-row>
        <!--  -->
        <b-row class="line mb-2 py-1  mx-0" v-show="data.number_certificate">
          <b-col md="4" lg="4" xl="4" xs="12" class="px-1">
            <span v-if="data.publication_type.type == 'certificate'">№ свідоцтва/рішення:</span>
            <span v-if="data.publication_type.type == 'patent'">№ патенту:</span>
          </b-col>
          <b-col class="px-1">
            {{data.number_certificate}}
          </b-col>
        </b-row>
        <!--  -->
        <b-row class="line mb-2 py-1  mx-0" v-show="data.mpk">
          <b-col md="4" lg="4" xl="4" xs="12" class="px-1">
            МПК:
          </b-col>
          <b-col class="px-1">
            {{data.mpk}}
          </b-col>
        </b-row>
        <!--  -->
        <b-row class="line mb-2 py-1  mx-0" v-show="data.name_magazine">
          <b-col md="4" lg="4" xl="4" xs="12" class="px-1">
            Назва журналу:
          </b-col>
          <b-col class="px-1">
            {{data.name_magazine}}
          </b-col>
        </b-row>
        <!--  -->
        <b-row class="line mb-2 py-1  mx-0" v-show="data.name_conference">
          <b-col md="4" lg="4" xl="4" xs="12" class="px-1">
            Назва конференції:
          </b-col>
          <b-col class="px-1">
            {{data.name_conference}}
          </b-col>
        </b-row>
        <!--  -->
        <b-row class="line mb-2 py-1  mx-0" v-show="data.name_monograph">
          <b-col md="4" lg="4" xl="4" xs="12" class="px-1">
            Назва монографії/книги:
          </b-col>
          <b-col class="px-1">
            {{data.name_monograph}}
          </b-col>
        </b-row>
        <!--  -->
        <b-row class="line mb-2 py-1  mx-0" v-show="data.year">
          <b-col md="4" lg="4" xl="4" xs="12" class="px-1">
            Рік видання:
          </b-col>
          <b-col class="px-1">
            {{data.year}}
          </b-col>
        </b-row>
        <!--  -->
        <b-row class="line mb-2 py-1  mx-0" v-show="data.number">
          <b-col md="4" lg="4" xl="4" xs="12" class="px-1">
            Номер (том):
          </b-col>
          <b-col class="px-1">
            {{data.number}}
          </b-col>
        </b-row>
        <!--  -->
        <b-row class="line mb-2 py-1  mx-0" v-show="data.number_volumes">
          <b-col md="4" lg="4" xl="4" xs="12" class="px-1">
            Кількість томів:
          </b-col>
          <b-col class="px-1">
            {{data.number_volumes}}
          </b-col>
        </b-row>
        <!--  -->
        <b-row class="line mb-2 py-1  mx-0" v-show="data.pages">
          <b-col md="4" lg="4" xl="4" xs="12" class="px-1">
            Сторінки:
          </b-col>
          <b-col class="px-1">
            {{data.pages}}
          </b-col>
        </b-row>
        <!--  -->
        <b-row class="line mb-2 py-1  mx-0" v-show="data.country">
          <b-col md="4" lg="4" xl="4" xs="12" class="px-1">
            Країна:
          </b-col>
          <b-col class="px-1">
            {{data.country}}
          </b-col>
        </b-row>
        <!--  -->
        <b-row class="line mb-2 py-1  mx-0" v-show="data.city">
          <b-col md="4" lg="4" xl="4" xs="12" class="px-1">
            Місто видання:
          </b-col>
          <b-col class="px-1">
            {{data.city}}
          </b-col>
        </b-row>
        <!--  -->
        <b-row class="line mb-2 py-1  mx-0" v-show="data.by_editing">
          <b-col md="4" lg="4" xl="4" xs="12" class="px-1">
            За редакцією:
          </b-col>
          <b-col class="px-1">
            {{data.by_editing}}
          </b-col>
        </b-row>
        <!--  -->
        <b-row class="line mb-2 py-1  mx-0" v-show="data.editor_name">
          <b-col md="4" lg="4" xl="4" xs="12" class="px-1">
            Назва редакції:
          </b-col>
          <b-col class="px-1">
            {{data.editor_name}}
          </b-col>
        </b-row>
        <!--  -->
        <b-row class="line mb-2 py-1  mx-0" v-show="data.applicant">
          <b-col md="4" lg="4" xl="4" xs="12" class="px-1">
            Власник майнових прав:
          </b-col>
          <b-col class="px-1">
            {{data.applicant}}
          </b-col>
        </b-row>
        <!--  -->
        <b-row class="line mb-2 py-1  mx-0" v-show="data.application_number">
          <b-col md="4" lg="4" xl="4" xs="12" class="px-1">
            № заявки:
          </b-col>
          <b-col class="px-1">
            {{data.application_number}}
          </b-col>
        </b-row>
        <!--  -->
        <b-row class="line mb-2 py-1  mx-0" v-show="data.newsletter_number">
          <b-col md="4" lg="4" xl="4" xs="12" class="px-1">
            № бюлетеня:
          </b-col>
          <b-col class="px-1">
            {{data.newsletter_number}}
          </b-col>
        </b-row>
        <!--  -->
        <b-row class="line mb-2 py-1  mx-0" v-show="data.date_application">
          <b-col md="4" lg="4" xl="4" xs="12" class="px-1">
            Дата подачі заявки:
          </b-col>
          <b-col class="px-1">
            {{data.date_application}}
          </b-col>
        </b-row>
        <!--  -->
        <b-row class="line mb-2 py-1  mx-0" v-show="data.date_publication">
          <b-col md="4" lg="4" xl="4" xs="12" class="px-1">
            Дата публікації про видачу свідоцтва/рішення:
          </b-col>
          <b-col class="px-1">
            {{data.date_publication}}
          </b-col>
        </b-row>
        <!--  -->
        <b-row class="line mb-2 py-1  mx-0" v-if="data.publication_type.type == 'patent' && data.patent_type">
          <b-col md="4" lg="4" xl="4" xs="12" class="px-1">
            Вид патенту:
          </b-col>
          <b-col class="px-1">
            {{ data.patent_type.title }}
          </b-col>
        </b-row>
        <!--  -->
        <b-row class="line mb-2 py-1  mx-0" v-show="data.publication_type.type == 'certificate' || data.publication_type.type == 'patent'">
          <b-col md="4" lg="4" xl="4" xs="12" class="px-1">
            Комерціалізовано університетом:
          </b-col>
          <b-col class="px-1">
            {{ data.commerc_university ? "Так" : "Ні" }}
          </b-col>
        </b-row>
        <!--  -->
        <b-row class="line mb-2 py-1  mx-0" v-show="data.publication_type.type == 'certificate' || data.publication_type.type == 'patent'">
          <b-col md="4" lg="4" xl="4" xs="12" class="px-1">
            Комерціалізовано штатними співробітниками університету:
          </b-col>
          <b-col class="px-1">
            {{ data.commerc_employees ? "Так" : "Ні" }}
          </b-col>
        </b-row>
        <!--  -->
        <b-row class="line mb-2 py-1  mx-0" v-show="data.url">
          <b-col md="4" lg="4" xl="4" xs="12" class="px-1">
            Електронна адреса (url):
          </b-col>
          <b-col class="px-1">
            <a :href="data.url" target="_blank">{{data.url}}</a>
          </b-col>
        </b-row>
        <!--  -->
        <b-row class="line mb-2 py-1  mx-0" v-if="data.publication_type.type == 'electronic'">
          <b-col md="4" lg="4" xl="4" xs="12" class="px-1">
            Режим доступу:
          </b-col>
          <b-col class="px-1">
            {{ data.access_mode ? "Відкритий" : "Закритий" }}
          </b-col>
        </b-row>
        <!--  -->
        <b-row class="line mb-2 py-1  mx-0" v-show="data.doi">
          <b-col md="4" lg="4" xl="4" xs="12" class="px-1">
            DOI:
          </b-col>
          <b-col class="px-1">
            {{data.doi}}
          </b-col>
        </b-row>
        <!--  -->
        <b-row class="line mb-2 py-1  mx-0" v-if="data.scopus_id">
          <b-col md="4" lg="4" xl="4" xs="12" class="px-1">
            ID публікації в Scopus:
          </b-col>
          <b-col class="px-1">
            {{ data.scopus_id.replace("SCOPUS_ID:", "") }}
          </b-col>
        </b-row>
        <!--  -->
        <b-row class="line mb-2 py-1  mx-0" v-if="data.science_type_id == 1 || data.science_type_id == 3">
          <b-col md="4" lg="4" xl="4" xs="12" class="px-1">
            Увійшли до 10% за БД Scopus найбільш цитованих публікацій для своєї предметної галузі:
          </b-col>
          <b-col class="px-1">
            {{data.db_scopus_percent ? "Так" : "Ні"}}
          </b-col>
        </b-row>
        <!--  -->
        <b-row class="line mb-2 py-1  mx-0" v-if="data.science_type_id == 2 || data.science_type_id == 3">
          <b-col md="4" lg="4" xl="4" xs="12" class="px-1">
            Увійшли до 1% за БД WoS найбільш цитованих публікацій для своєї предметної галузі:
          </b-col>
          <b-col class="px-1">
            {{data.db_wos_percent ? "Так" : "Ні"}}
          </b-col>
        </b-row>
        <!--  -->
        <b-row class="line mb-2 py-1  mx-0" v-if="data.science_type_id">
          <b-col md="4" lg="4" xl="4" xs="12" class="px-1">
            Процитована у міжнародних патентах:
          </b-col>
          <b-col class="px-1">
            {{data.cited_international_patents ? "Так" : "Ні"}}
          </b-col>
        </b-row>
        <!--  -->
        <b-row class="line mb-2 py-1  mx-0">
          <b-col md="4" lg="4" xl="4" xs="12" class="px-1">
            Публікація врахована в рейтингу попереднього року:
          </b-col>
          <b-col class="px-1">
            {{ data.not_previous_year ? "Так" : "Ні" }}
          </b-col>
        </b-row>
        <!--  -->
        <b-row class="line mb-2 py-1  mx-0">
          <b-col md="4" lg="4" xl="4" xs="12" class="px-1">
            Публікація не врахована в рейтингу цього року:
          </b-col>
          <b-col class="px-1">
            {{ data.not_this_year ? "Так" : "Ні" }}
          </b-col>
        </b-row>
        <!--  -->
        <b-row class="line mb-2 py-1  mx-0" v-if="data.publication_add">
          <b-col md="4" lg="4" xl="4" xs="12" class="px-1">
            Додано:
          </b-col>
          <b-col class="px-1">
            <a :href="'/user/'+data.publication_add.id">{{data.publication_add.name}}</a>
          </b-col>
        </b-row>
        <!--  -->
        <b-row class="line mb-2 py-1  mx-0" v-if="data.publication_edit">
          <b-col md="4" lg="4" xl="4" xs="12" class="px-1">
            Редаговано:
          </b-col>
          <b-col class="px-1">
            <a :href="'/user/'+data.publication_edit.id">{{data.publication_edit.name}}</a>
          </b-col>
        </b-row>
      </b-container>
    </b-overlay>
  </div>
</template>
<script>
import Back from "../components/Back.vue";
import access from "../mixins/access";
export default {
  mixins: [access],
  components: {
    Back
  },
  data() {
    return {
      loading: true,
      data: {
        title: "",
        science_type_id: null,
        science_type: {
            type: "",
        },
        publication_type_id: null,
        publication_type: {
            scopus_wos: "",
            title: "",
            type: "",
        },
        snip: "",
        impact_factor: "",
        quartil_scopus: "",
        quartil_wos: "",
        sub_db_scie: "",
        sub_db_ssci: "",
        year: "",
        number: "",
        pages: "",
        initials: "",
        country: "",
        number_volumes: "",
        by_editing: "",
        city: "",
        editor_name: "",
        languages: "",
        number_certificate: "",
        applicant: "",
        date_application: "",
        date_publication: "",
        commerc_university: "",
        commerc_employees: "",
        access_mode: "",
        mpk: "",
        application_number: "",
        newsletter_number: "",
        name_conference: "",
        url: "",
        out_data: "",
        name_magazine: "",
        doi: "",
        nature_index: "",
        nature_science: "",
        db_scopus_percent: "",
        db_wos_percent: "",
        authors: [],
        supervisor: null,
        not_previous_year: false,
        verification: false,
        scopus_id: "",
        status_id: null
      },
    }
  },
  created() {
    this.getData();
  },
  methods: {
    getData() {
      axios.get(`/api/publication/${this.$route.params.id}`).then(response => {
          this.data = Object.assign(this.data, response.data);
          this.loading = false;
      })
    },
    deletePublication() {
      swal.fire({
          title: 'Бажаєте видалити?',
          text: "Після видалення ви не зможете відновити дані!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#3085d6',
          confirmButtonText: 'Видалити',
          cancelButtonText: 'Відміна',
      }).then((result) => {
          if (result.isConfirmed) {
              axios.post('/api/delete-publications', {
                  publications: [this.$route.params.id]
              })
              .then(() => {
                  this.$router.push({path: `/publications`});
              });
          }
      })
    },
    restorePublications() {
        swal.fire({
            title: 'Бажаєте відновити?',
            text: "Публікацію буде повернуто до списку публікацій",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Відновити',
            cancelButtonText: 'Відміна',
        }).then((result) => {
            if (result.isConfirmed) {
                axios.post('/api/restore-publications', {
                    publications: [this.$route.params.id]
                })
                .then(() => {
                    this.getData();
                    swal.fire("Публікацію видалено");
                });
            }
        })
    },
  }
}
</script>
<style lang="css" scoped>
  h1 {
    font-weight: 300;
    font-size: 32px;
    line-height: 37px;
  }
  .line {
    border-bottom: 1px solid rgb(236, 236, 236);
  }
</style>