<template>
  <b-container class="wrapper">
    <b-alert class="w-100 text-center" variant="danger" :show="access == 'close'">
      <b-icon class="mr-1" icon="exclamation-octagon-fill"></b-icon> Функції сервісу тимчасово обежено!
    </b-alert>
    <!--  -->
    <a href="/add" class="nav-item" v-show="this.userRole == 4 || this.access == 'open'">
      <img src="/img/file-plus-outline.svg">
      Додати публікацію
    </a>
    <!--  -->
    <a href="/my-publications" class="nav-item">
      <img src="/img/file-account-outline.svg">
      Мої публікації
    </a>
    <!--  -->
    <a href="/publications" class="nav-item" v-show="this.userRole != 1">
      <img src="/img/file-search-outline.svg">
      Всі публікації
    </a>
    <!--  -->
    <a href="/scopus" class="nav-item" v-show="this.userRole == 4">
      <img src="/img/scopus.png">
      Публікації SCOPUS
    </a>
    <!--  -->
    <a href="/archive" class="nav-item" v-show="this.userRole == 4">
      <img src="/img/archive.svg">
      Архів
    </a>
    <!--  -->
    <a href="/rating" class="nav-item" v-show="this.userRole == 4">
      <img src="/img/chart-timeline-variant-shimmer.svg">
      Рейтингові показники
    </a>
    <!--  -->
    <a href="/users" class="nav-item" v-show="this.userRole != 1">
      <img src="/img/account-multiple.svg">
      Автори
    </a>
    <!--  -->
    <a href="/profile" class="nav-item">
      <img src="/img/account.svg">
      Кабінет
    </a>
    <!--  -->
    <a href="/messages" class="nav-item">
      <img src="/img/email-outline.svg">
      Повідомлення
    </a>
    <!--  -->
    <a href="/audit" class="nav-item" v-show="this.userRole == 4">
      <img src="/img/math-log.svg">
      Аудит
    </a>
    <!--  -->
    <a href="/manual.pdf" class="nav-item" target="_blank">
      <img src="/img/manual.svg">
      Інструкція користувача
    </a>
    <!--  -->
    <div class="nav-item red" v-if="userRole == 4 && access == 'open'" @click="setAccess('close')">
      <img src="/img/lock.svg">
      Обмежити доступ до сервісу
    </div>
    <!--  -->
    <div class="nav-item green" v-if="userRole == 4 && access == 'close'" @click="setAccess('open')">
      <img src="/img/lock-open.svg">
      Відкрити повний доступ до сервісу
    </div>
  </b-container>
</template>
<script>
export default {
  computed: {
    userRole() {
      return this.$store.getters.authUser ? this.$store.getters.authUser.roles_id : null
    },
    access() {
      return this.$store.getters.accessMode
    }
  },
  methods: {
      setAccess(mode) {
          axios.post('/api/access', {
              mode
          }).then(() => {
            this.$store.dispatch('setAccess', mode)
          })
      }
  },
}
</script>
<style lang="css" scoped>
  .wrapper {
    background: #FFFFFF;
    border-radius: 8px;
    display: flex;
    flex-wrap: wrap;
    padding: 50px;
    justify-content: center;
  }
  .nav-item {
    background: #FCFCFC;
    box-shadow: 0px 0px 16px -5px rgba(0, 0, 0, 0.25);
    border-radius: 15px;
    text-align: center;
    width: 165px;
    height: 155px;
    display: flex;
    align-items: center;
    flex-direction: column;
    justify-content: center;
    margin: 34px;
    padding: 20px;
    font-size: 14px;
    line-height: 143.4%;
  }
  a.nav-item {
    color: #000;
    text-decoration: none;
  }
  a.nav-item:hover {
    color: #fff;
    background: rgba(0, 126, 216, 0.86);
  }
  .nav-item.red {
    cursor: pointer;
    background: #FF4747;
    color: #fff;
  }
  .nav-item.green {
    cursor: pointer;
    background: rgb(0, 179, 0);
    color: #fff;
  }
  .nav-item img {
    width: 72px;
    margin-bottom: 15px;
    opacity: 0.6;
  }
  .nav-item:hover img, .nav-item.red img, .nav-item.green img {
    opacity: 1;
    filter: brightness(0) invert(1);
  }
</style>