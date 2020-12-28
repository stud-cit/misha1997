<script>
    export default {
        data() {
            return {
                departments: [],
                divisions: [],
            }
        },
        created() {
            this.getDivisions();
        },
        methods: {
            getDivisions() {
                axios.get('/api/sort-divisions').then(response => {
                    this.divisions = response.data;
                    if(this.authUser.roles_id == 3 && this.authUser.faculty_code) {
                        this.departments = this.divisions.find(item => {
                            return this.authUser.faculty_code == item.ID_DIV;
                        });
                    }
                    if(this.departments) {
                        this.departments = this.departments.departments;
                    }
                })
            },
            getDepartments() {
                if(this.filters.faculty_code) {
                    this.departments = this.divisions.find(item => {
                        return this.filters.faculty_code == item.ID_DIV
                    }).departments;
                } else {
                    this.departments = [];
                    this.filters.department_code = "";
                }
            },
        }
    }
</script>
