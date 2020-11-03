<template>
    <div class="container general-block">
        <h1 class="blue-page-title">Ваш профіль</h1>
        <div class="page-content">
            <ul class="list-view">
                <li class="row">
                    <div class="col-lg-3 list-item list-title">Прізвище, ім’я, по-батькові:</div>
                    <div class="col-lg-9 list-item list-text">{{data.name}}</div>
                </li>
                <li class="row">
                    <div class="col-lg-3 list-item list-title">Роль:</div>
                    <div class="col-lg-9 list-item list-text">{{data.role.name}}</div>
                </li>
                <li class="row" v-if="data.job && data.job != 'СумДУ'">
                    <div class="col-lg-3 list-item list-title">Місце роботи:</div>
                    <div class="col-lg-9 list-item list-text">{{data.job}}</div>
                </li>
                <li class="row" v-if="data.position && data.job != 'СумДУ'">
                    <div class="col-lg-3 list-item list-title">Входить до списків Forbes та Fortune:</div>
                    <div class="col-lg-9 list-item list-text">{{data.forbes_fortune ? "Так" : "Ні"}}</div>
                </li>
                <li class="row" v-if="data.faculty">
                    <div class="col-lg-3 list-item list-title">Інститут/факультет:</div>
                    <div class="col-lg-9 list-item list-text">{{data.faculty}}</div>
                </li>
                <li class="row" v-if="data.department">
                    <div class="col-lg-3 list-item list-title">Кафедра:</div>
                    <div class="col-lg-9 list-item list-text">{{data.department}}</div>
                </li>
                <li class="row" v-if="data.academic_code">
                    <div class="col-lg-3 list-item list-title">Академічна група:</div>
                    <div class="col-lg-9 list-item list-text">{{data.academic_code}}</div>
                </li>
                <li class="row" v-if="data.job != 'СумДУ'">
                    <div class="col-lg-3 list-item list-title">Країна:</div>
                    <div class="col-lg-9 list-item list-text">{{data.country}}</div>
                </li>
                <li class="row" v-if="authUser.roles_id == 4">
                    <div class="col-lg-3 list-item list-title">5 або більше публікацій в Scopus та/або WoS:</div>
                    <div class="col-lg-9 list-item list-text">
                        <div class="input-container">
                            <select v-model="data.five_publications">
                                <option value=""></option>
                                <option value="1">Так</option>
                                <option value="0">Ні</option>
                            </select>
                        </div>
                    </div>
                </li>
                <li class="row">
                    <div class="col-lg-3 list-item list-title">Індекс Гірша:</div>
                    <div class="col-lg-9  list-item list-text d-flex">
                        <div class="col-lg-6 two-col pr-2">
                            <label>БД Scopus</label>
                            <div class=" input-container">
                                <input class="item-value" type="text" v-model="data.scopus_autor_id">
                            </div>
                        </div>
                        <div class="col-lg-6 two-col">
                            <label>БД WoS</label>
                            <div class=" input-container">
                                <input class="item-value" type="text" v-model="data.h_index">
                            </div>
                        </div>
                    </div>
                </li>
                <li class="row">
                    <div class="col-lg-3 list-item list-title">Research ID:</div>
                    <div class="col-lg-9 list-item list-text">
                        <div class="input-container">
                            <input class="item-value" type="text" v-model="data.scopus_researcher_id">
                        </div>
                    </div>
                </li>
                <li class="row">
                    <div class="col-lg-3 list-item list-title">ORCID:</div>
                    <div class="col-lg-9 list-item list-text">
                        <div class="input-container">
                            <input class="item-value" type="text" v-model="data.orcid">
                        </div>
                    </div>
                </li>
            </ul>
            <div class="step-button-group">
                <back-button></back-button>
                <save-button v-if="authUser.roles_id == 4" @click.native="save()"></save-button>
            </div>
        </div>
    </div>
</template>

<script>
    import BackButton from "./Buttons/Back";
    import SaveButton from "./Buttons/Save";
    export default {
        data() {
            return {
                data: {
                    name: "",
                    role: {
                        name: ""
                    },
                    job: "",
                    position: "",
                    faculty: "",
                    department: "",
                    academic_code: "",
                    country: "",
                    scopus_autor_id: "",
                    h_index: "",
                    scopus_researcher_id: "",
                    orcid: "",
                    forbes_fortune: "",
                    five_publications: ""
                }
            }
        },
        components: {
            BackButton,
            SaveButton
        },
        mounted() {
            this.getData();
        },
        computed: {
            authUser() {
                return this.$store.getters.authUser
            }
        },
        methods: {
            getData() {
                axios.get(`/api/profile`).then(response => {
                    this.data = response.data;
                })
            },
            save() {
                axios.post(`/api/profile`, this.data)
                    .then((response) => {
                        this.$store.dispatch('setUser', response.data)
                        swal("Інформацію оновлено", {
                            icon: "success",
                        });
                })
            }
        },

    }
</script>
<style lang="scss" scoped>
    .save-btn {
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

