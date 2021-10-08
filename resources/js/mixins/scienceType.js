  export default {
    data() {
      return {
        scienceTypes: [],
        loadScienceTypes: false
      }
    },
    methods: {
      getScienceTypes() {
        if(this.scienceTypes.length == 0) {
          this.loadScienceTypes = true;
          axios.get('/api/science-types').then(response => {
            this.scienceTypes = response.data;
            this.loadScienceTypes = false;
          }).catch(() => {
            this.loadScienceTypes = false;
          })
        }
      }
    }
  }