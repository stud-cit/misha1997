export default {
    data() {
        return {
            job: [],
        }
    },
    created() {
        this.getJob();
    },
    methods: {
      getJob() {
          axios.get('/api/job-type').then(response => {
              this.job = response.data;
          })
      },
    }
}