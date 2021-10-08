export default {
  data() {
    return {
      country: [],
      loadCountry: false
    }
  },
  methods: {
    getCountry() {
      if(this.country.length == 0) {
        this.loadCountry = true;
        axios.get('/api/country').then(response => {
          this.country = response.data;
          this.loadCountry = false;
        }).catch(() => {
          this.loadCountry = false;
        })
      }
    }
  }
}