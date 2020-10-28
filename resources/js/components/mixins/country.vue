<script>
    export default {
        data() {
            return {
                country: [],
            }
        },
        created() {
            this.getCountry();
        },
        methods: {
            getCountry() {
                axios.get('/api/country').then(response => {
                    this.country = response.data;
                })
            },
        }
    }
</script>