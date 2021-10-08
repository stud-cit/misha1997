<template>
  <b-container class="wrapper">
    <div style="display:flex">
      <Back page=""></Back> 
      <div class="filler"></div> 
      <b-icon 
        class="cursor"
        scale="1.5" 
        variant="danger" 
        icon="exclamation-circle" 
        v-show="userRole != 1 && userRole != 5 && $route.name == 'edit'"
        @click="sendMessage"
        id="tooltip-target-1"
      ></b-icon>
      <b-tooltip target="tooltip-target-1" triggers="hover">
        Написати повідомлення авторам публікації
      </b-tooltip>
    </div>
    <div class="line">
      <div class="container2">
            <ul class="progressbar">
              <li :class="i < currentStep ? 'active' : ''" v-for="i in 4" :key="i">Крок {{ i }}</li>
            </ul>
        </div>
    </div>
    <b-alert v-model="showDismissibleAlert" variant="warning" dismissible>
      <b-icon class="mr-1" icon="exclamation-circle-fill"></b-icon> Поля з зірочками обов'язкові для заповнення.
    </b-alert>
    <transition name="component-fade" mode="out-in">
      <keep-alive>
      <Step1 
        v-if="currentStep==1" 
        :publicationData="publicationData" 
        @getData="getStepData"
      ></Step1>
      <Step2 
        v-if="currentStep==2" 
        :publicationData="publicationData" 
        @getData="getStepData" 
        @prevStep="prevStep"
      ></Step2>
      <Step3 
        v-if="currentStep==3" 
        :publicationData="publicationData" 
        @getData="getStepData" 
        @prevStep="prevStep"
      ></Step3>
      <Step4 
        v-if="currentStep==4" 
        :publicationData="publicationData" 
        @getData="getStepData" 
        @prevStep="prevStep"
        :publicationType="publicationData.publication_type.type"
      ></Step4>
      </keep-alive>
    </transition>
  </b-container>
</template>
<script>
import Back from "../components/Back.vue";

import Step1 from "../components/Steps/Step1.vue";
import Step2 from "../components/Steps/Step2.vue";
import Step3 from "../components/Steps/Step3.vue";
import Step4 from "../components/Steps/Step4.vue";

export default {
  components: {
    Back,
    Step1,
    Step2,
    Step3,
    Step4
  },
  data() {
    return {
      isScopus: false,
      currentStep: 1,
      showDismissibleAlert: true,
      publicationData: {
          whose_publication: "",
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
          sub_db_scie: 0,
          sub_db_ssci: 0,
          year: new Date().getFullYear(),
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
          commerc_university: "0",
          commerc_employees: "0",
          access_mode: "",
          mpk: "",
          application_number: "",
          newsletter_number: "",
          name_conference: "",
          url: "",
          out_data: "",
          name_magazine: "",
          doi: "",
          nature_index: 2,
          nature_science: "",
          db_scopus_percent: "",
          db_wos_percent: "",
          cited_international_patents: "",
          not_previous_year: false,
          not_this_year: false,
          name_monograph: "",
          authors: [],
          useSupervisor: false,
          supervisor: null,
          default_name_magazine: false,
          article_number: "",
          scopus_id: null,
          verification: true,
          old_supervisor: null,
          patent_type_id: null
      },
    }
  },
  created() {
    if(this.$route.name == 'edit') {
      this.getPublicationData();
    } else {
      this.publicationData.whose_publication = 'my';
    }
  },
  computed: {
      userRole() {
          return this.$store.getters.authUser ? this.$store.getters.authUser.roles_id : null
      }
  },
  methods: {
    prevStep() {
        if(this.currentStep !== 1){
            // этот код скрывает 3 шаг
            if(!this.isScopus && this.currentStep == 4){
                this.currentStep-=2;
            } else {
                this.currentStep--;
            }
        }
    },
    sendMessage() {
        swal.fire({
            title: 'Введіть текст повідомлення',
            input: 'textarea',
            inputAttributes: {
                autocapitalize: 'off'
            },
            showCancelButton: true,
            confirmButtonText: 'Надіслати',
            cancelButtonText: 'Відміна',
            inputValidator: (value) => {
                if (!value) {
                    return 'Поле не має бути пустим'
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                axios.post(`/api/notifications/${this.$route.params.id}`, {
                    comment: result.value
                })
                .then((response) => {
                    swal.fire({
                        icon: 'success',
                        title: "Повідомлення надіслано"
                    })
                }).catch(() => {
                    swal.fire({
                        icon: 'error',
                        title: 'Помилка'
                    })
                });
            }
        })
    },
    getPublicationData() {
      axios.get(`/api/publication/${this.$route.params.id}`).then(response => {
        this.publicationData = Object.assign(this.publicationData, response.data);
        if(this.publicationData.supervisor) {
          this.publicationData.supervisor = this.publicationData.supervisor.author;
          this.publicationData.useSupervisor = true;
        }
        this.publicationData.authors = this.publicationData.authors.map(item => {
          if(item.supervisor) {
            item.author.supervisor = item.supervisor;
          }
          return item.author;
        });
      })
    },
    getStepData(num = 4) {
        if(num !== 4) {
            // этот код скрывает 3 шаг
            if((!this.isScopus && num == 2) || (this.isScopus && [4, 5, 6, 7, 8].indexOf(this.publicationData.publication_type.id) > -1 && num == 2)) {
                this.currentStep = num + 2 ;
                const falseScinceType = {
                    snip: null,
                    impact_factor: null,
                    quartil_scopus: null,
                    quartil_wos: null,
                    sub_db_scie: 0,
                    sub_db_ssci: 0
                };
                this.publicationData = Object.assign(this.publicationData, falseScinceType);
            } else {
                this.currentStep = num + 1;
            }
        } else {
          if(this.$route.name == 'edit') {
            this.edit();
          } else {
            this.post();
          }
        }
    },
    post() {
      this.publicationData.out_data = this.outDataParser(this.publicationData);
      if(this.publicationData.science_type_id == 1) {
          this.publicationData.quartil_wos = null;
          this.publicationData.impact_factor = null;
          this.publicationData.sub_db_scie = 0;
          this.publicationData.sub_db_ssci = 0;
      }
      if(this.publicationData.science_type_id == 2) {
          this.publicationData.snip = null;
          this.publicationData.quartil_scopus = null;
      }
      axios.post('/api/publication', this.publicationData)
      .then(() => {
          swal.fire({
              icon: 'success',
              title: 'Публікацію успішно додано!',
              showConfirmButton: false,
              timer: 1500
          })
          setTimeout(() => {
              location.reload();
          }, 1500);
      })
      .catch(() => {
          swal.fire({
              icon: 'error',
              title: 'Помилка',
              showConfirmButton: false,
              timer: 1500
          })
      });
    },
    edit() {
      this.publicationData.out_data = this.outDataParser(this.publicationData);
      if(this.publicationData.science_type_id == 1) {
          this.publicationData.quartil_wos = null;
          this.publicationData.impact_factor = null;
          this.publicationData.sub_db_scie = 0;
          this.publicationData.sub_db_ssci = 0;
          this.publicationData.db_wos_percent = 0;
      }
      if(this.publicationData.science_type_id == 2) {
          this.publicationData.snip = null;
          this.publicationData.quartil_scopus = null;
          this.publicationData.db_scopus_percent = 0;
      }
      if(this.publicationData.science_type_id == '') {
          this.publicationData.snip = null;
          this.publicationData.quartil_scopus = null;
          this.publicationData.impact_factor = null;
          this.publicationData.quartil_wos = null;
          this.publicationData.sub_db_scie = 0;
          this.publicationData.sub_db_ssci = 0;
          this.publicationData.nature_index = 2;
          this.publicationData.nature_science = null;
          this.publicationData.db_scopus_percent = 0;
          this.publicationData.db_wos_percent = 0;
          this.publicationData.cited_international_patents = 0;
      }
      axios.post(`/api/update-publication/${this.$route.params.id}`, this.publicationData)
          .then((response) => {
              swal.fire({
                  icon: 'success',
                  title: 'Публікацію успішно змінено!',
                  showConfirmButton: false,
                  timer: 1500
              })
              setTimeout(() => {
                  if(this.userRole == 1) {
                      this.$router.push({path: '/my-publications'});
                  } else {
                      this.$router.push({path: '/publications'});
                  }
              }, 1500);
          })
          .catch(() => {
              swal.fire({
                  icon: 'error',
                  title: 'Помилка',
                  showConfirmButton: false,
                  timer: 1500
              })
          });
    },
    outDataParser (item) {
        let result = "";
        switch(item.publication_type.type){
            case "article":
                result = item.name_magazine + '. ' + item.year +  '. С. ' + item.pages + '.' + (item.doi ? ' DOI: ' + item.doi : '');
                // result = item.initials + ' ' + item.title + '. ' + item.name_magazine + '. ' + item.year + '. №' + item.number + '. С. ' + item.pages + '.' + (item.doi ? ' DOI: ' + item.doi : '');
                break;
            case "book":
                result = item.publication_type.title.toLowerCase() + (item.by_editing ? ' / за ред. ' + item.by_editing : '') + '. ' + item.year + '. ' + item.pages + ' с.' + (item.doi ? ' DOI: ' + item.doi : '');
                // result = item.initials + ' ' + item.title + ': ' + item.publication_type.title.toLowerCase() + (item.by_editing ? ' / за ред. ' + item.by_editing : '') + '. ' + item.city + ': ' + item.editor_name + ', ' + item.year + '. ' + item.pages + ' с.' + (item.doi ? ' DOI: ' + item.doi : '');
                break;
            case "book-part":
                result = item.publication_type.title.toLowerCase() + (item.by_editing ? ' / за ред. ' + item.by_editing : '') + '. ' + item.year + '. С. ' + item.pages + '.';
                // result = item.initials + ' ' + item.title + ': ' + item.publication_type.title.toLowerCase() + (item.by_editing ? ' / за ред. ' + item.by_editing : '') + '. ' + item.city + ': ' + item.editor_name + ', ' + item.year + '. С. ' + item.pages + '.';
                break;
            case "thesis":
                result = 'Матеріали ' + item.name_conference + '. ' + ', ' + item.year + '. С. ' + item.pages + '. ' + (item.doi ? ' DOI: ' + item.doi : '');
                // result = item.initials + ' ' + item.title + ': матеріали ' + item.name_conference + '. ' + item.city + ': ' + item.editor_name + ', ' + item.year + '. С. ' + item.pages + '. ' + (item.doi ? ' DOI: ' + item.doi : '');
                break;
            case "patent":
                result =  'Пат. ' + item.number_certificate + ' ' + item.country + ': МПК ' + item.mpk + '. № ' + item.application_number +  '; опубл. ' + item.date_publication + ', Бюл. № ' + item.newsletter_number + '.';
                break;
            case "certificate":
                result =  'Свідоцтво про реєстрацію авторського права на твір № ' +  item.number_certificate + '. ' + item.title + ' / ' + item.initials + ' ' + item.country + ';  опубл. ' + item.date_publication + '.';
                // result =  'Свідоцтво про реєстрацію авторського права на твір № ' +  item.number_certificate + '. ' + item.title + ' / ' + item.initials + ' ' + item.country + '; заявл. ' + item.date_application + '; опубл. ' + item.date_publication + '.';
                break;
            case "methodical":
                result =  item.year + '. ' + item.pages + ' с.';
                // result =  item.initials + ' ' + item.title + '. ' + item.city + ': ' + item.editor_name + ', ' + item.year + '. ' + item.pages + ' с.';
                break;
            case "electronic":
                result =  item.year + '. ' + item.pages + ' с. URL: ' + item.url;
                // result =  item.initials + ' ' + item.title + '. ' + item.city + ': ' + item.editor_name + ', ' + item.year + '. ' + item.pages + ' с. URL: ' + item.url;
                break;
        }
        return result;
    },
  }
}
</script>
<style lang="css" scoped>
  .line {
    height: 80px;
    position: relative;
  }
  .line ul {
    padding: 0;
    margin: 0;
  }
  .container2 {
    width: 100%;
    position: absolute;
    z-index: 1;
  }
  .progressbar li {
    list-style: none;
    float: left;
    width: 25%;
    position: relative;
    text-align: center;
    font-size: 16px;
    transition: all 0.5s ease-out;
  }
  .progressbar li:after {
    content: '';
    width: 15px;
    height: 15px;
    border: 2px solid #fff;
    display: block;
    margin: 0 auto 10px auto;
    border-radius: 50%;
    background: #1580CD;
    margin-top: 16px;
    transition: all 0.5s ease-out;
  }
  .progressbar li:before {
    content: '';
    position: absolute;
    width:100%;
    height: 3px;
    background: #1580CD;
    top: 30px;
    left: -50%;
    z-index: -1;
    margin-top: 16px;
    transition: all 0.5s ease-out;
  }
  .progressbar li.active {
    color: #24CF08;
  }
  .progressbar li.active:after {
    border: 2px solid #fff;
    background: #24CF08;
  }
  .progressbar li.active::before {
    background: #24CF08;
  }
  .progressbar li.active + li:before {
    background: #24CF08;
  }
  .progressbar li.active + li:after {
    border: 2px solid #fff;
    background: #24CF08;
  }
  .progressbar li:first-child:before {
    content: none;
  }
  .progressbar li:first-child {
    margin: 0;
  }
</style>