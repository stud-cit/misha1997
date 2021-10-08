export default {
  computed: {
    authUser() {
      return this.$store.getters.authUser
    },
    checkAccessPublication() {
      if(this.authUser.roles_id == 4) {
        return true;
      } else if(this.$store.getters.accessMode == 'open') {
        return true;
      } else if((this.$store.getters.accessMode == 'open' && this.authUser.roles_id != 1) && this.authUser.roles_id != 5) {
        return true;
      } else {
        return false;
      }
    },
    checkAccess() {
      if(this.authUser.roles_id == 4) {
          return true;
      } else if((this.$store.getters.accessMode == 'open' && this.authUser.roles_id == 1) || this.authUser.roles_id != 5) {
          return true;
      } else {
          return false;
      }
    },
  },
}