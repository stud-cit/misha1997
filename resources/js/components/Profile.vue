<template>

    <div class="container">
        <h1 class="blue-page-title">Профіль - Бабій Євгеній Андрійович</h1>
        <div class="page-content">
            <ul class=" list-view">


                <li class="row">
                    <div class="col-lg-3 list-item list-title">Прізвище, ім’я, по-батькові:</div>
                    <div class="col-lg-9 list-item list-text">
                        <div class="input-container">
                            <input class="item-value" type="text" v-model="data.name">
                            <div class="hint" ><span>Прізвище, ім’я, по-батькові:</span></div>
                        </div>
                    </div>

                </li>
                <li class="row">
                    <div class="col-lg-3 list-item list-title">Роль:</div>
                    <div class="col-lg-9 list-item list-text">
                        <div class="input-container">
                            <select  v-model="data.roles_id">
                                <option
                                    v-for="(item, index) in roles"
                                    :key="index"
                                    :value="item.id"
                                >{{item.name}}</option>

                            </select>
                            <div class="hint" ><span>title</span></div>
                        </div>

                    </div>
                </li>
                <li class="row">
                    <div class="col-lg-3 list-item list-title">Рік народження:</div>
                    <div class="col-lg-9 list-item list-text">
                        <div class="input-container">
                            <input class="item-value" type="text" v-model="data.name">
                            <div class="hint" ><span>title</span></div>
                        </div>
                    </div>
                </li>
                <li class="row">
                    <div class="col-lg-3 list-item list-title">Посада в СумДУ:</div>
                    <div class="col-lg-9 list-item list-text">
                        <div class="input-container">
                            <input class="item-value" type="text" v-model="data.job">
                            <div class="hint" ><span>title</span></div>
                        </div>
                    </div>
                </li>
                <li class="row">
                    <div class="col-lg-3 list-item list-title">Інститут/факультет:</div>
                    <div class="col-lg-9 list-item list-text">
                        <div class="input-container">
                            <input class="item-value" type="text" v-model="data.faculty">
                            <div class="hint" ><span>title</span></div>
                        </div>
                    </div>
                </li>
                <li class="row">
                    <div class="col-lg-3 list-item list-title">Кафедра:</div>
                    <div class="col-lg-9 list-item list-text">
                        <div class="input-container">
                            <input class="item-value" type="text" v-model="data.department">
                            <div class="hint" ><span>title</span></div>
                        </div>
                    </div>
                </li>
                <li class="row">
                    <div class="col-lg-3 list-item list-title">Індекс Гірша:</div>
                    <div class="col-lg-9  list-item list-text d-flex">
                        <div class="col-lg-6 two-col pr-2">
                            <label for="">БД Scopus</label>
                            <div class=" input-container">
                                <input class="item-value" type="text" v-model="data.scopus_autor_id">
                                <div class="hint" ><span>title</span></div>
                            </div>
                        </div>
                        <div class="col-lg-6 two-col">
                            <label for="">БД WoS</label>
                            <div class=" input-container">
                                <input class="item-value" type="text" v-model="data.h_index">
                                <div class="hint" ><span>title</span></div>
                            </div>
                        </div>

                    </div>
                </li>
                <li class="row">
                    <div class="col-lg-3 list-item list-title">Research ID:</div>
                    <div class="col-lg-9 list-item list-text">
                        <div class="input-container">
                            <input class="item-value" type="text" v-model="data.scopus_researcher_id">
                            <div class="hint" ><span>title</span></div>
                        </div>

                    </div>
                </li>
                <li class="row">
                    <div class="col-lg-3 list-item list-title">ORCID:</div>
                    <div class="col-lg-9 list-item list-text">
                        <div class="input-container">
                            <input class="item-value" type="text" v-model="data.orcid">
                            <div class="hint" ><span>title</span></div>
                        </div>
                    </div>
                </li>



            </ul>






            <div class="text-center">
                <button class="btn save-btn" @click="save()">Зберегти</button>
            </div>
        </div>


    </div>




</template>

<script>

    export default {
        data() {
            return {
                access: true,
                data: {
                    name: "",
                    roles_id: "",
                    country: "",
                    h_index: "",
                    scopus_researcher_id: "",
                    academic_code: "",
                    orcid: "",
                    email: "",
                    job: "",
                    alias: null,
                    role: {
                        name: ""
                    }
                },
                country: [],
                roles: []
            };
        },
        components: {

        },
        mounted () {
            this.getData();
            this.getCountry();
            this.getRoles();
        },
        methods: {
            getData() {
                axios.get('/api/author/22e6106c-c580-e711-8194-001a4be6d04a').then(response => {
                // axios.get('/api/author/'+localStorage.getItem('userId')).then(response => {
                    this.data = response.data;
                })
            },
            getCountry() {
                axios.get('/api/country').then(response => {
                    this.country = response.data;
                })
            },
            getRoles() {
                axios.get('/api/roles').then(response => {
                    this.roles = response.data;
                })
            },
            save() {

            }
        },

    }
</script>
<style lang="scss" scoped>



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


        .save-btn{
            margin-top: 25px;
            padding: 15px 40px;
            font-size: 20px;
            line-height: 24px;
            border-radius: 6px;

        }

    }


</style>

