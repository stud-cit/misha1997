<template>
  <div>
    <Header v-show="$route.name != 'auth' && $route.name != 'register'"></Header>
    <b-button class="setting" v-b-toggle.setting>
      <b-icon class="ml-1" icon="gear-fill"></b-icon>
    </b-button>
    <b-sidebar id="setting" title="Налаштування" right shadow backdrop>
      <div class="px-3 py-2">
        <Sidebar></Sidebar>
      </div>
    </b-sidebar>
    <router-view v-if="status == 'register'"></router-view>
    <Index v-if="status == 'unauthorized'"></Index>
    <Register v-if="status == 'login'"></Register>
  </div>
</template>
<script>
import Header from '../components/Header';
import Sidebar from '../components/Sidebar';

import Index from './Index.vue';
import Register from './Register.vue';

export default {
  components: {
    Header,
    Sidebar,
    Index,
    Register
  },
  props: {
    user: Object,
    status: String,
    access: String
  },
  created() {
    this.$store.dispatch('setAccess', this.access);
    this.$store.dispatch('setUser', this.user);
  },
}
</script>
<style lang="css" scoped>
  .setting {
    position: absolute;
    z-index: 999;
    right: 0;
    top: 100px;
    border-top-right-radius: 0;
    border-bottom-right-radius: 0;
  }
</style>