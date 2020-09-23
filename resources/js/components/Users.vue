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
                    <div class="form-group col-lg-4">
                        <label >Інститут/факультет</label>
                        <div class="input-container">
                            <select  v-model="filters.faculty">
                                <option value=""></option>
                                <option value="1">факультет 1</option>
                                <option value="1">факультет 2</option>
                                <option value="Медичний інститут">Медичний інститут</option>
                            </select>
                            <div class="hint" ><span>Прізвище, ім’я, по-батькові:</span></div>
                        </div>
                    </div>
                    <div class="form-group col-lg-4">
                        <label >Кафедра</label>
                        <div class="input-container">
                            <select  v-model="filters.department">
                                <option value=""></option>
                                <option value="Кафедра X">Кафедра X</option>
                                <option value="1">факультет 1</option>
                                <option value="1">факультет 2</option>
                            </select>
                            <div class="hint" ><span>Прізвище, ім’я, по-батькові:</span></div>
                        </div>
                    </div>
                    <div class="form-group col-lg-4">
                        <label >Рік народження</label>
                        <div class="input-container">
                            <select v-model="filters.birthday">
                                <option value=""></option>
                                <option value="1">2019</option>
                                <option value="1">2020</option>
                            </select>
                            <div class="hint" ><span>Прізвище, ім’я, по-батькові:</span></div>
                        </div>
                    </div>
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
                        <th scope="col">Рік народження</th>

                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(item, index) in filteredList" :key="item.id">
                        <td scope="row">{{ index+1+(currentPage-1)*perPage}}</td>
<!--                        <td>{{ item.role.name }}</td>-->
                        <td><router-link :to="{path: `/user/${item.id}`}">{{ item.name }}</router-link></td>
                        <td></td>
                        <td>{{ item.department }}</td>
                        <td>{{ item.faculty }}</td>
                        <td>{{ item.email }}</td>
                        <td></td>
                    </tr>
                    </tbody>
                </table>
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
            </div>
        </div>
    </div>
</template>

<script>
    import {required, requiredIf} from "vuelidate/lib/validators";

    export default {
        data() {
            return {
                data: [],
                filters: {
                    name: '',
                    faculty: '',
                    department: '',
                    birthday: ''
                },
                currentPage: 1,
                perPage: 10,
                numPage: 1,

            };
        },
        components: {

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
        },
        methods: {
            getData() {
                axios.get('/api/authors').then(response => {
                    this.data = response.data;
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
