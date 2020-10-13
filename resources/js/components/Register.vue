<template>
    <div class="container page-content">
        <h1 class="page-title">Реєстрація</h1>
        <form class="search-block">
            <div class="form-group">
                <label >Країна *</label>
                <div class="input-container">
                    <select class="item-value" v-model="data.country">
                        <option
                            v-for="(item, index) in country"
                            :key="index"
                            :value="item.name"
                        >{{item.name}}</option>
                    </select>
                    <div class="hint" ><span>Країна:</span></div>
                </div>
            </div>

            <div class="form-group">
                <label >Інститут/факультет *</label>
                <div class="input-container">
                    <select v-model="data.faculty_code">
                        <option value=""></option>
                        <option
                            v-for="(item, index) in divisions.institute"
                            :key="index"
                            :value="item.ID_DIV"
                        >{{item.NAME_DIV}}</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label >Кафедра *</label>
                <div class="input-container">
                    <select v-model="data.department_code">
                        <option value=""></option>
                        <option
                            v-for="(item, index) in divisions.department"
                            :key="index"
                            :value="item.ID_DIV"
                        >{{item.NAME_DIV}}</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label >Індекс Гірша Scopus</label>
                <div class="input-container">
                    <input class="item-value" type="text" v-model="data.scopus_autor_id">

                    <div class="hint" ><span>Індекс Гірша Scopus:</span></div>
                </div>
            </div>
            <div class="form-group">
                <label >Індекс Гірша WoS</label>
                <div class="input-container">
                    <input class="item-value" type="text" v-model="data.h_index">

                    <div class="hint" ><span>Індекс Гірша WoS:</span></div>
                </div>
            </div>
            <div class="form-group">
                <label >Research ID</label>
                <div class="input-container">
                    <input class="item-value" type="text" v-model="data.scopus_researcher_id">

                    <div class="hint" ><span>Research ID:</span></div>
                </div>
            </div>
            <div class="form-group">
                <label>ORCID</label>
                <div class="input-container">
                    <input class="item-value" type="text" v-model="data.orcid">

                    <div class="hint" ><span>ORCID:</span></div>
                </div>
            </div>


        </form>


        <div class="text-center">
            <button class="btn save-btn" @click="save()">Зберегти</button>
        </div>

    </div>



</template>

<script>
    export default {
        data() {
            return {
                departments: [],
                divisions: {
                    institute: [],
                    department: []
                },
                data: {
                    country: "",
                    h_index: "",
                    scopus_autor_id: "",
                    scopus_researcher_id: "",
                    orcid: "",
                },
                country: []
            };
        },
        components: {
        },
        mounted () {
            this.getCountry();
            this.getDivisions();
        },
        methods: {
            getDivisions() {
                axios.get('/api/divisions').then(response => {
                    this.divisions = response.data;
                })
            },
            getCountry() {
                axios.get('/api/country').then(response => {
                    this.country = response.data;
                })
            },
            save() {
                axios.post('/api/register', this.data)
                    .then((response) => {
                        this.$store.dispatch('setUser', response.data)
                        this.$router.push('/home');
                    })
            }
        },
    }
</script>
<style lang="scss" scoped>
        form{
            margin-top: 60px;
        }

        .save-btn{
            margin-top: 50px;
            padding: 27px 85px;
            font-family: Arial;
            font-style: normal;
            font-weight: normal;
            font-size: 30px;
            line-height: 34px;
            text-align: center;
            color: #FFFFFF;
            background: #7EF583;
            border-radius: 6px;

        }

        @media (max-width: 575px) {

            form{
                margin-top: 30px;
            }

            .save-btn{
                margin-top: 25px;
                padding: 15px 40px;
                font-size: 20px;
                line-height: 24px;
                border-radius: 6px;

            }

        }


</style>
