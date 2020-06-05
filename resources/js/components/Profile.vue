<template>


    <div class="container">
        <h1 class="page-title">Перегляд профілю</h1>
        <div class="profile-info">
            <div class="info-item">
                <h2 class="item-title">Прізвище, ім’я, по-батькові *</h2>

                <div class="value-block">
                    <input class="item-value" type="text" v-model="data.name">
                    <button class="alias-btn add-alias">Додати псевдонім</button>
                </div>
            </div>
            <div class="info-item">
                <h2 class="item-title">Псевдонім автора</h2>

                <div class="value-block">
                    <p class="item-value">Бабій Євгеній Андрійович</p>
                    <button class="alias-btn delete-alias">Видалити псевдонім</button>
                </div>
            </div>
            <div class="info-item">

                <div class="value-block">
                    <div class="w-40">
                        <h2 class="item-title">Роль *</h2>
                        <select class="item-value" v-model="data.roles_id">
                            <option
                                v-for="(item, index) in roles"
                                :key="index"
                                :value="item.id"
                            >{{item.name}}</option>
                        </select>
                    </div>
                    <div class="w-40">
                        <h2 class="item-title">Країна *</h2>
                        <select class="item-value" v-model="data.country">
                            <option
                                v-for="(item, index) in country"
                                :key="index"
                                :value="item.name"
                            >{{item.name}}</option>
                        </select>
                    </div>

                </div>

            </div>
            <div class="info-item">
                <h2 class="item-title">Індекс Хірша</h2>

                <div class="value-block">
                    <input class="item-value" type="text" v-model="data.h_index">
                </div>
            </div>
            <div class="info-item">
                <h2 class="item-title">Research ID</h2>

                <div class="value-block">
                    <input class="item-value" type="text" v-model="data.scopus_researcher_id">
                </div>
            </div>
            <div class="info-item">
                <h2 class="item-title">ORCID-посилання</h2>

                <div class="value-block">
                    <input class="item-value" type="text" v-model="data.orcid">
                </div>
            </div>
            <div class="info-item">
                <h2 class="item-title">Шифр групи студента * </h2>

                <div class="value-block">
                    <input class="item-value" type="text" v-model="data.academic_code">
                </div>
            </div>
            <div class="info-item">
                <h2 class="item-title">Робоча почта користувача *  </h2>

                <div class="value-block">
                    <input class="item-value" type="text" v-model="data.email">
                </div>
            </div>
            <div class="info-item">
                <h2 class="item-title">Научне звання *  </h2>

                <div class="value-block">
                    <input class="item-value" type="text" v-model="data.job">
                </div>
            </div>
            <div class="text-center">
                <button class="btn save-btn" @click="save()">Зберегти</button>
                <router-link :to="{name: 'home'}" class="back-link">Назад на головну</router-link>
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
                var id = 1;
                axios.get('/api/author/'+id).then(response => {
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

    .profile-info{
        margin-top: 90px;
        .info-item{
            margin-bottom: 30px;
            .item-title{
                width: 100%;
                margin-bottom: 15px;
                padding-left: 24px;
                font-family: Montserrat;
                font-style: normal;
                font-weight: normal;
                font-size: 25px;
                color: #A6A6A6;
            }
            .value-block{
                display: flex;
                align-items: center;
                /*flex-wrap: wrap;*/
                .w-40{
                    width: 40%;
                }
                .alias-btn{
                    width: 10%;
                    background-color: transparent;
                    border: none;
                    outline: none;
                    font-family: Montserrat;
                    font-style: normal;
                    font-weight: normal;
                    font-size: 18px;
                    text-align: center;
                    color: #A6A6A6;
                }
                .delete-alias{
                    color: #FF6A6A;
                }
                .item-value{
                    width: 90%;
                    /*background: #FFFFFF;*/
                    box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.25);
                    border-radius: 44.5px;
                    padding: 12px 24px;
                    text-align: center;
                    font-family: Montserrat;
                    font-style: normal;
                    font-weight: normal;
                    font-size: 20px;
                    color: #0E0E0E;
                    outline: none;
                    border: none;
                }
            }
        }
        .save-btn{
            margin-top: 120px;
            padding: 27px 85px;
            font-family: Montserrat;
            font-style: normal;
            font-weight: normal;
            font-size: 22px;
            text-align: center;
            color: #FFFFFF;
            background: #C4C4C4;
            position: relative;
            line-height: 1;
            &::before{
                content: "";
                position: absolute;
                background: url("/img/angle.png");
                width: 20px;
                height: 14px;
                right: 50px;
                top: 33px;
            }
        }
        .back-link{
            display: block;
            margin-top: 35px;
            font-family: Montserrat;
            font-style: normal;
            font-weight: 300;
            font-size: 22px;
            text-align: center;
            color: #A6A6A6;

        }
    }

</style>

