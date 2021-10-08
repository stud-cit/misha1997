export default {
    data() {
        return {
            roles: [],
        }
    },
    created() {
        this.getRoles();
    },
    methods: {
        getRoles() {
            axios.get('/api/roles').then(response => {
                this.roles = response.data;
            })
        },
    }
}