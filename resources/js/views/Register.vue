<template>
  <b-container class="wrapper">
    <h1 class="page-title">{{ $store.getters.authUser.name }}</h1>
    <h2 class="subtitle">Вітаємо Вас на сервісі "Наукові публікації". Заповніть наступні поля, у разі їх наявності, та натисніть кнопку "Продовжити".</h2>

    <b-row>
      <b-col cols="6">
        <label class="mt-2">Research ID:</label>
        <input 
          type="text" 
          class="input" 
          v-model="user.scopus_researcher_id" 
          @input="$v.$touch()"
          placeholder="A-0000-2021"
        >
        <div class="error" v-if="$v.user.scopus_researcher_id.$error">
          Не коректний формат. Приклад (A-0000-2021)
        </div>
      </b-col>
      <b-col cols="6">
        <label class="mt-2">ORCID:</label>
        <input 
          type="text" 
          class="input" 
          v-model="user.orcid" 
          @input="$v.$touch()"
          placeholder="0000-0000-0000-0000"
        >
        <div class="error" v-if="$v.user.orcid.$error">
          Не коректний формат. Приклад (0000-0000-0000-0000)
        </div>
      </b-col>
      <b-col cols="6">
        <label class="mt-2">Scopus ID:</label>
        <input 
          type="text" 
          class="input" 
          v-model="user.scopus_id" 
          @input="$v.$touch()"
          placeholder="00000000000"
        >
        <div class="error" v-if="$v.user.scopus_id.$error">
          Дозволено лише цифри. Приклад (00000000000)
        </div>
      </b-col>
      <b-col cols="6">

      </b-col>
      <b-col cols="6">
        <b-row>
          <b-col cols="12">
            <label class="mt-4">Індекс Гірша:</label>
          </b-col>
          <b-col cols="12">
              <!--  -->
              <label>БД Scopus:</label>
              <input type="number" min="0" class="input" v-model="user.scopus_autor_id">
              <!--  -->
              <label class="mt-2">БД WoS:</label>
              <input type="number" min="0" class="input" v-model="user.h_index">
          </b-col>
        </b-row>
      </b-col>
      <b-col cols="6">
        <b-row>
          <b-col cols="12">
            <label class="mt-4">Індекс Гірша без самоцитувань:</label>
          </b-col>
          <b-col cols="12">
            <!--  -->
            <label>БД Scopus:</label>
            <input type="number" class="input" min="0" v-model="user.without_self_citations_scopus">
            <!--  -->
            <label class="mt-2">БД WoS:</label>
            <input type="number" class="input" min="0" v-model="user.without_self_citations_wos">
          </b-col>
        </b-row>
      </b-col>
      <b-col cols="12" class="text-right mt-4">
        <b-overlay
          :show="loading"
          rounded
          opacity="0.6"
          spinner-small
          spinner-variant="primary"
          class="d-inline-block"
        >
          <button class="button blue save" @click="save()">Продовжити</button>
        </b-overlay>
      </b-col>
    </b-row>
  </b-container>
</template>
<script>
import {numeric} from "vuelidate/lib/validators";
export default {
  data() {
    return {
      loading: false,
      user: {
        scopus_autor_id: "",
        h_index: "",
        without_self_citations_scopus: "",
        without_self_citations_wos: "",
        scopus_researcher_id: "",
        orcid: "",
        scopus_id: ""
      }
    }
  },
  validations: {
    user: {
      orcid: {
        validFormat: val => val ? /(\d{4})\-(\d{4})\-(\d{4})\-(\d{4})/.test(val) : true, 
      },
      scopus_id: {
        numeric
      },
      scopus_researcher_id: {
        validFormat: val => val ? /(\w+)\-(\d{4})\-(\d{4})/.test(val) : true, 
      }
    },
  },
  methods: {
    save() {
      this.$v.$touch();
      if (this.$v.$invalid) {
        return
      }
      this.loading = true;
      axios.post('/api/register', this.user)
        .then((response) => {
          this.$store.dispatch('setUser', response.data)
          location.reload();
        }).catch(() => {
          this.loading = false;
        })
    }
  }
}
</script>
<style lang="css" scoped>
  .wrapper {
    padding: 70px !important;
  }
  .page-title, .subtitle {
    text-align: center;
  }
  h1.page-title {
    font-size: 2.1rem;
  }
  .subtitle {
    font-size: 1.4rem;
    margin: 30px 0 40px 0;
  }
  button.button.save {
    padding: 10px !important;
    font-size: 16px !important;
    width: 186px;
  }
</style>