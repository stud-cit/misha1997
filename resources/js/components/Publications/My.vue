<template>
    <div class="container page-content general-block">
        <h1 class="page-title">Мої публікації</h1>
        <div class="main-content">
            <form class="search-block">
                <div class="form-group">
                    <label>Назва публікації</label>
                    <div class="input-container">
                        <input v-model="filters.title" type="text" list="names" @input="findNames">
                        <datalist id="names">
                            <option v-for="(item, index) in names" :key="index" :value="item">{{item}}</option>
                        </datalist>
                    </div>
                </div>
                <div class="form-group">
                    <label >Прізвище та ініціали співавтора</label>
                    <div class="input-container hint-container">
                        <input type="text" v-model="filters.initials">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-lg-4">
                        <label>БД Scopus\WoS</label>
                        <div class="input-container">
                            <select  v-model="filters.science_type_id">
                                <option value=""></option>
                                <option value="1">Scopus</option>
                                <option value="2">WoS</option>
                                <option value="3">Scopus та WoS</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group col-lg-4">
                        <label>Рік видання</label>
                        <div class="input-container">
                            <select  v-model="filters.year">
                                <option value=""></option>
                                <option v-for="(item, index) in years" :key="index" :value="item">{{item}}</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group col-lg-4">
                        <label>Країна видання</label>
                        <div class="input-container">
                            <select v-model="filters.country">
                                <option value=""></option>
                                <option v-for="(item, index) in countries" :value="item.name" :key="index">{{item.name}}</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label>Вид публікації</label>
                    <div class="input-container">
                        <select  v-model="filters.publication_type_id">
                            <option value=""></option>
                            <option v-for="(item, index) in publicationTypes" :value="item.id" :key="index">{{item.title}}</option>
                        </select>
                    </div>
                </div>
            </form>
            <Table 
                @select="selectItem"
                :publications="filteredList" 
                :authUser="authUser" 
                :loading="loading"
                :selectPublications="selectPublications"
            ></Table>
            <div class="step-button-group">
                <back-button></back-button>
                <delete-button @click="deletePublications" :disabled="selectPublications.length == 0"></delete-button>
            </div>
        </div>
    </div>
</template>

<script>
    import Table from "../Tables/Publications";
    import BackButton from "../Buttons/Back";
    import DeleteButton from "../Buttons/Delete";
    export default {
        data() {
            return {
                names: [],
                publicationNames: [],
                selectPublications: [],
                loading: true,
                data: [],
                countries: [],
                publicationTypes: [],
                filters: {
                    title: '',
                    initials: '',
                    science_type_id: '',
                    year: '',
                    country: '',
                    publication_type_id: ''
                }
            };
        },
        components: {
            Table,
            BackButton,
            DeleteButton
        },
        mounted () {
            this.getData();
            this.getCountry();
            this.getTypePublications();
            this.getNamesPublications();
        },
        methods: {
            findNames() {
                this.names = this.publicationNames.filter(item => {
                    return item.indexOf(this.filters.title) + 1
                })
            },
            selectItem(item) {
                if(this.selectPublications.indexOf(item) == -1) {
                    this.selectPublications.push(item);
                } else {
                    this.selectPublications.splice(this.selectPublications.indexOf(item), 1);
                }
            },
            getData() {
                axios.get('/api/my-publications').then(response => {
                    this.data = response.data;
                    this.loading = false;
                }).catch(() => {
                    this.loading = false;
                })
            },
            getCountry() {
                axios.get('/api/country').then(response => {
                    this.countries = response.data;
                })
            },
            getTypePublications() {
                axios.get(`/api/type-publications`).then(response => {
                    this.publicationTypes = response.data;
                })
            },
            getNamesPublications() {
                axios.get(`/api/publications-names`).then(response => {
                    this.publicationNames = response.data.map(n => this.parseString(n.title));

                })
            },
            deletePublications() {
				swal({
					title: "Бажаєте видалити?",
					text: "Після видалення ви не зможете відновити дані!",
					icon: "warning",
					buttons: true,
					dangerMode: true,
				}).then((willDelete) => {
					if (willDelete) {
						axios.post('/api/delete-publications', {
                            publications: this.selectPublications,
                            user: this.authUser
						})
                        .then(() => {
                            this.selectPublications = [];
                            this.getData();
                            swal("Публікація успішно видалена", {
                                icon: "success",
                            });
                        });
					}
				});
            },
            parseString(s) {
                const punctuation = s.replace(/[.,\/\[\]#!$%\^&\*;:{}=\-_`~()]/g,"");
                return punctuation.replace(/\s+/g,' ' ).trim().toLowerCase();
            }
        },
        computed: {
            authUser() {
                return this.$store.getters.authUser
            },
            filteredList() {
                this.exportPublication = this.data.filter(item => {
                    let result = 1;
                    let keys = Object.keys(this.filters);
                    let values = Object.values(this.filters);
                    for(let i = 0; i < keys.length; i++){
                        if(values[i]) {
                            if (item[keys[i]] && keys[i] != 'publication_type_id') {
                                result = result && item[keys[i]].toString().toLowerCase().includes(values[i].toString().toLowerCase());
                            } else if(keys[i] == 'publication_type_id') {
                                result = result && item[keys[i]].toString() == values[i].toString();
                            } else {
                                result = 0;
                            }
                        }
                    }
                    return result;
                });
                return this.exportPublication;
            },
            years() {
                const year = new Date().getFullYear();
                return Array.from({length: 10}, (value, index) => year - index);
            }
        }
    }
</script>

<style lang="scss" scoped>
    .fa-edit {
        cursor: pointer;
    }
    .search-block{
        margin-top: 60px;
    }
    .table-list{
        margin-top: 70px;
    }
    .exports{
        display: grid;
        justify-content: center;
        margin-top: 50px;
        .export-block{
            display: grid;
            margin-bottom: 20px;
        }
    }
    @media (max-width: 575px){
        .search-block{
            margin-top: 15px;
        }
        .table-list{
            margin-top: 35px;
        }
        .exports{
            margin-top: 25px;
        }
    }
</style>

