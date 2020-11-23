<template>
    <div class="container general-block">
        <h1 class="blue-page-title">Перегляд повідомлень</h1>
        <div class="page-content">
            <template>
                <h2 class="subtitle new" >Повідомлення</h2>
                <hr>
                <ul class="notifications-list mb-50">
                    <li class="notifications-item" v-for="(item, index) in filterList" :key="item.id">
                        <p class="date">{{ item.date }} 
                            <span v-if="item.autors_id">
                                <i v-if="item.status" class="fa fa-envelope-o fa-1x"></i>
                                <i v-else class="fa fa-envelope cursor fa-1x" @click="watchedNotification(item)"></i>
                            </span>
                        </p>
                        <p class="text" v-html="item.text"></p>
                    </li>
                </ul>
                <div v-if="notifications.length == 0" class="text-center no-notifications">Повідомлення відсутні</div>
            </template>
            <paginate
                v-model="pagination.currentPage"
                :page-count="pagination.numPage"

                :prev-text="'<'"
                :next-text="'>'"

                :container-class="'pagination'"
                page-class="page-item"
                page-link-class="page-link"
                prev-class="page-link"
                next-class="page-link">
            </paginate>
            <div class="step-button-group">
                <back-button></back-button>
            </div>
        </div>
    </div>
</template>

<script>
    import BackButton from "./Buttons/Back";
    import { mapMutations } from 'vuex';
    export default {
        data() {
            return {
                notifications: [],
                pagination: {
                    currentPage: 1,
                    perPage: 10,
                    numPage: 1
                },
            };
        },
         components: {
            BackButton
        },
        created() {
            this.getData();
        },
        watch: {
            publications: {
                deep: true,
                handler() {
                    this.pagination.currentPage = 1;
                    this.pagination.perPage = 10;
                    this.pagination.numPage = 1;
                }
            }
        },
        computed: {
            filterList() {
                this.pagination.numPage = Math.ceil(this.notifications.length / this.pagination.perPage);
                return this.notifications.slice((this.pagination.currentPage - 1) * this.pagination.perPage, this.pagination.currentPage * this.pagination.perPage);
            }
        },
        methods: {
            ...mapMutations([
                "updateNotifications"
            ]),
            getData() {
                axios.get('/api/notifications/'+this.$store.getters.authUser.id).then(response => {
                    this.notifications = response.data;
                });
            },
            watchedNotification(item) {
                axios.post('/api/notifications/'+item.id+'/'+this.$store.getters.authUser.id, {
                    status: 1
                }).then(() => {
                    this.updateNotifications(item);
                    this.getData();
                });
            }
        },
    }
</script>
<style scoped lang="scss">
    .no-notifications {
        font-size: 20px;
    }
    .cursor {
        cursor: pointer;
    }
    .subtitle{
        font-family: Arial;
        font-style: normal;
        font-weight: bold;
        font-size: 25px;
        line-height: 29px;
        color: #212529;
        border-bottom: none;
        margin-top: 0;

        &.new{
            color: #007BFF;

        }
    }
    hr{
        border-top: 1px solid #212529;
    }

    .notifications-list{
        margin-top: 35px;
        .notifications-item {
            margin-bottom: 35px;
            .date{
                margin-bottom: 5px;
                font-family: Arial;
                font-style: normal;
                font-weight: normal;
                font-size: 20px;
                line-height: 23px;
                color: rgba(33, 37, 41, 0.5);

            }
            .text{
                font-family: Arial;
                font-style: normal;
                font-weight: normal;
                font-size: 22px;
                line-height: 30px;
                color: #212529;


            }
        }
        &.mb-75{
            margin-bottom: 75px;
        }

    }

    @media (max-width: 575px) {
        .subtitle{

            font-size: 20px;
            line-height: 24px;



        }


        .notifications-list{
            margin-top: 35px;
            .notifications-item{
                margin-bottom: 15px;
                .date{

                    font-size: 16px;
                    line-height: 18px;

                }
                .text{
                    font-size: 18px;
                    line-height: 22px;



                }
            }
            &.mb-75{
                margin-bottom: 35px;
            }

        }
    }

</style>
