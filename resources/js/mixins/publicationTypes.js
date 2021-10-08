export default {
  data() {
    return {
      publicationTypes: [],
      loadingPublicationTypes: false
    }
  },
  methods: {
    getPublicationTypes() {
      if(this.publicationTypes.length == 0) {
        this.loadingPublicationTypes = true;
        axios.get('/api/type-publications').then(response => {
          this.publicationTypes = response.data;
          this.loadingPublicationTypes = false;
        }).catch(() => {
          this.loadingPublicationTypes = false;
        })
      }
    }
  }
}