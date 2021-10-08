<template>
  <div class="header">
    <b-container class="nav-bar">
      <a href="/">
        <img class="logo" src="/img/logo.svg" title="Наукові публікації Сумського державного університету">
      </a>
      <div class="filler"></div>
      <nav id="menu" :class="menu_btn ? 'active' : ''">
          <ul>
              <li><a href="/">Головна</a></li>
              <li>
                  <a href="#">
                      Публікації <b-icon class="ml-1" icon="chevron-down"></b-icon>
                  </a>
                  <ul>
                      <li v-show="this.userRole == 4 || this.access == 'open'"><a href="/add">Додати публікацію</a></li>
                      <li><a href="/my-publications">Мої публікації</a></li>
                      <li v-show="this.userRole != 1"><a href="/publications">Всі публікації</a></li>
                      <li v-show="this.userRole == 4"><a href="/scopus">Публікації SCOPUS</a></li>
                      <li v-show="this.userRole == 4"><a href="/archive">Архів</a></li>
                      <li v-show="this.userRole == 4"><a href="/rating">Рейтингові показники</a></li>
                  </ul>
              </li>
              <li v-show="this.userRole != 1"><a href="/users">Автори</a></li>
              <li><a href="/profile">Кабінет</a></li>
              <li><a href="/messages">Повідомлення <span v-if="authUser.notifications_count > 0" class="number">{{ authUser.notifications_count }}</span></a></li>
              <li v-show="this.userRole == 4"><a href="/audit">Аудит</a></li>
              <li><a href="#" @click="exit">Вихід</a></li>
          </ul>
      </nav>
      <b-icon @click="menu_btn = !menu_btn" class="menu-btn" icon="list"></b-icon>
      <nav>
        <ul>
          <li style="margin-right: 20px;">
            <div class="service">
              <div id="cabinet_service"></div>
            </div>
          </li>
        </ul>
      </nav>
    </b-container>
  </div>
</template>

<script>
    export default {
    data() {
      return {
        menu_btn: false
      }
    },
    computed: {
      userRole() {
        return this.$store.getters.authUser ? this.$store.getters.authUser.roles_id : null
      },
      authUser() {
        return this.$store.getters.authUser
      },
      access() {
        return this.$store.getters.accessMode
      }
    },
    methods: {
      exit() {
        axios.post('/api/logout').then(() => {
          // this.$router.push({path: '/'});
          this.$store.dispatch('setUser', null);
          window.location.href = "https://cabinet.sumdu.edu.ua/";
        })
      }
    }
  }
</script>

<style lang="css" scoped>
  .header {
    box-shadow: 0px 1px 8px rgba(0, 0, 0, 0.25);
    background: #FFFFFF;
    margin-bottom: 33px;
    font-family: inherit;
  }
  .menu-btn {
    display: none;
    cursor: pointer;
    font-size: 27px;
    margin-right: -20px;
    z-index: 99;
  }
  .nav-bar {
    height: 64px;
    display: flex;
    align-items: center;
  }
  .nav-bar .logo {
    height: 42px;
  }
  nav ul, nav li {
      list-style: none;
      padding: 0;
      margin: 0;
  }
  nav a {
      color: #fff;
      text-decoration: none;
      text-transform: uppercase;
      /* font-weight: 500; */
      font-size: 14px;
  }
  nav > ul > li {
      display: inline-block;
      position: relative;
      margin-left: 40px;
  }
  nav > ul > li > a {
      padding-bottom: 50px;
      color: #000;
  }
  nav > ul > li > ul {
      display: none;
      transition: 1s;
      position: absolute;
      background: #fff;
      top: 60px;
      padding: 10px 0;
      box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.25);
      border-radius: 5px;
      margin-left: -65px;
      min-width: 233px;
      z-index: 9999;
  }
  nav > ul > li > ul > li {
      white-space: nowrap;
      padding: 8px 15px;
      text-align: center;
  }
  nav > ul > li > ul > li > a {
      display: block;
      color: #000;
  }
  a:hover {
      text-decoration: none;
      color: #1580CD;
  }
  nav > ul > li > ul:after {
      position: absolute;
      left: 50%;
      margin-left: -20px;
      top: -8px;
      width: 0;
      height: 0;
      content:'';
      border-left: 20px solid transparent;
      border-right: 20px solid transparent;
      border-bottom: 20px solid #fff;
  }
  nav > ul > li:hover > ul {
    display: block;
  }
  .service {
    position: absolute;
    top: -26px;
  }
  .number {
    font-weight: 500;
    font-size: 9px;
    background: #1580CD;
    border-radius: 13px;
    padding: 1px 7px;
    color: #fff;
  }
  @media screen and (max-width: 1200px) {
    .nav-bar {
      position: relative;
    }
    #menu {
      display: none;
      position: absolute;
      background: #fff;
      top: 75px;
      padding: 10px 0;
      box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.25);
      border-radius: 5px;
      min-width: 233px;
      z-index: 9999;
      right: 0;
    }
    #menu > ul:after {
      position: absolute;
      right: 47px;
      margin-left: -20px;
      top: -8px;
      width: 0;
      height: 0;
      content:'';
      border-left: 20px solid transparent;
      border-right: 20px solid transparent;
      border-bottom: 20px solid #fff;
  }
    #menu > ul > li {
      display: block;
      position: relative;
      text-align: center;
      margin: 10px 0;
    }
    #menu > ul > li > ul {
      position: relative;
      top: 10px;
      margin-bottom: 20px;
      padding: 10px 0;
      box-shadow: 0px;
      border-radius: 0;
      width: 100%;
      margin-left: 0;
    }
    .menu-btn {
      display: block;
    }
    #menu.active {
      display: block;
    }
    .service {
      right: -30px;
    }
  }
</style>