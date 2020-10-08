<template>

    <div class="container">
        <h1 class="blue-page-title">Перегляд повідомлень</h1>
        <div class="page-content">
            <template v-if="notifications.length > 0">
                <h2 class="subtitle new" >Нові повідомлення</h2>
                <hr>
                <ul class=" notifications-list mb-75">
                    <li class="notifications-item cursor" v-for="item in notifications" :key="item.id" @click="watchedNotification(item)">
                        <p class="date">{{ item.date }}</p>
                        <p class="text">{{ item.text }}</p>
                    </li>
                </ul>
            </template>

            <h2 class="subtitle" >Попередні повідомлення</h2>
            <hr>
            <ul class=" notifications-list before" v-if="viewedNotifications.length > 0">
                <li class="notifications-item" v-for="item in viewedNotifications" :key="item.id">
                    <p class="date">{{ item.date }}</p>
                    <p class="text">{{ item.text }}</p>
                </li>
            </ul>
            <div class="step-button-group">
                <router-link :to="'/home'" tag="button" class="next">Назад</router-link>
            </div>
            
        </div>
    </div>



</template>

<script>
    export default {
        data() {
            return {
                notifications: [],
                viewedNotifications: []
            };
        },

        created () {
            this.getData();
        },
        methods: {
            getData() {
                this.notifications = [];
                this.viewedNotifications = [];
                axios.get('/api/notifications/'+this.$store.getters.authUser.id).then(response => {
                    response.data.map(item => {
                        if (item.status) {
                            this.viewedNotifications.push(item)
                        } else {
                            this.notifications.push(item)
                        }
                    });
                });
            },
            watchedNotification(item) {
                axios.post('/api/notifications/'+item.id+'/'+this.$store.getters.authUser.id, {
                    status: 1
                }).then(() => {
                    this.getData();
                });
            }
        },
    }
</script>
<style scoped lang="scss">
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
        .notifications-item{
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
                line-height: 25px;
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
