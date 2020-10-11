<template>

    <div class="container page-content">
        <h1 class="page-title">Список працівників</h1>
        <div class="main-content">
            <form class="search-block">
                <div class="form-group">
                    <label >Прізвище, ім’я та по батькові користувача</label>
                    <div class="input-container">
                        <input type="text" v-model="filters.name" >
                        <div class="hint" ><span>Прізвище, ім’я, по-батькові:</span></div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-lg-6">
                        <label >Інститут / факультет</label>
                        <div class="input-container">
                            <select v-model="filters.faculty_code" @change="getDepartments">
                                <option value=""></option>
                                <option
                                    v-for="(item, index) in divisions"
                                    :key="index"
                                    :value="item.ID_DIV"
                                >{{item.NAME_DIV}}</option>
                            </select>
                            <div class="hint"><span>Прізвище, ім’я, по-батькові:</span></div>
                        </div>
                    </div>
                    <div class="form-group col-lg-6">
                        <label >Кафедра</label>
                        <div class="input-container">
                            <select v-model="filters.department_code">
                                <option value=""></option>
                                <option
                                    v-for="(item, index) in departments"
                                    :key="index"
                                    :value="item.ID_DIV"
                                >{{item.NAME_DIV}}</option>
                            </select>
                            <div class="hint" ><span>Прізвище, ім’я, по-батькові:</span></div>
                        </div>
                    </div>
                    <!-- <div class="form-group col-lg-4">
                        <label >Рік народження</label>
                        <div class="input-container">
                            <select v-model="filters.birthday">
                                <option value=""></option>
                                <option value="1">2019</option>
                                <option value="1">2020</option>
                            </select>
                            <div class="hint" ><span>Прізвище, ім’я, по-батькові:</span></div>
                        </div>
                    </div> -->
                </div>


            </form>
            <div class="table-responsive text-center table-list">

                <table class="table table-bordered ">
                    <thead>
                    <tr>
                        <th scope="col">№</th>
<!--                        <th scope="col">Роль</th>-->
                        <th scope="col">ПІБ користувача</th>
                        <th scope="col">Посада</th>
                        <th scope="col">Кафедра</th>
                        <th scope="col">Інститут/
                            факультет</th>
                        <th scope="col">E-mail</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(item, index) in filteredList" :key="item.id">
                        <td scope="row">{{ index+1+(currentPage-1)*perPage}}</td>
<!--                        <td>{{ item.role.name }}</td>-->
                        <td><router-link :to="{path: `/user/${item.id}`}">{{ item.name }}</router-link></td>
                        <td v-if="item.categ_1 == 1">Студент</td>
                        <td v-else-if="item.categ_1 == 2">Аспірант</td>
                        <td v-else-if="item.categ_2 == 1">Співробітник</td>
                        <td v-else-if="item.categ_2 == 2">Викладач</td>
                        <td v-else></td>
                        <td>{{ item.department }}</td>
                        <td>{{ item.faculty }}</td>
                        <td>{{ item.email }}</td>
                    </tr>
                    </tbody>
                </table>
                <div class="spinner-border mt-4" role="status" v-if="loading">
                    <span class="sr-only">Loading...</span>
                </div>
                <paginate
                    v-model="currentPage"
                    :page-count="numPage"

                    :prev-text="'<'"
                    :next-text="'>'"

                    :container-class="'pagination'"
                    page-class="page-item"
                    page-link-class="page-link"
                    prev-class="page-link"
                    next-class="page-link">
                </paginate>
                <div class="step-button-group">
            <router-link :to="'/home'" tag="button" class="next">Назад</router-link>
        </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {required, requiredIf} from "vuelidate/lib/validators";

    export default {
        data() {
            return {
                loading: true,
                departments: [],
                divisions: [],
                data: [],
                filters: {
                    name: '',
                    faculty_code: '',
                    department_code: '',
                    birthday: ''
                },
                currentPage: 1,
                perPage: 10,
                numPage: 1,

            };
        },
        computed: {
            filteredList() {
                let list = this.data.filter(users => {
                    let result = 1;
                    let keys = Object.keys(this.filters);
                    let values = Object.values(this.filters);
                    for(let i = 0; i < keys.length; i++){
                        if(values[i]) {
                            if (users[keys[i]]) {
                                result = result && users[keys[i]].toLowerCase().includes(values[i].toLowerCase());
                            } else {
                                result = 0;
                            }
                        }
                    }
                    return result

                });
                this.numPage = Math.ceil(list.length/this.perPage);

                return list.slice((this.currentPage-1)*this.perPage, this.currentPage*this.perPage);
            }
        },

        created() {
            this.getData();
            this.getDivisions();
        },
        methods: {
            getDepartments() {
                this.departments = this.divisions.find(item => {
                    return this.filters.faculty_code == item.ID_DIV
                }).departments;
            },

            getDivisions() {
                axios.get('/api/sort-divisions').then(response => {
                    this.divisions = response.data;
                })
            },
            getData() {
                axios.get('/api/authors').then(response => {
                    console.log(response.data)
                    this.data = response.data;
                    this.loading = false;
                }).catch(() => {
                    this.loading = false;
                })
            },
            clean(){
                this.searchAuthor = '';
            }
        },

    }
</script>

<style lang="scss" scoped>
    .search-block{
        margin-top: 60px;
    }
    @media(max-width: 575px){
        .search-block{
            margin-top: 30px;
        }
    }
</style>
