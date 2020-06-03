<template>


    <div >
        <h1 class="page-title">Перегляд публікацій</h1>
        <ul class="container notifications-list">
            <li class="notifications-item" v-for="item in notifications" :key="item.id">
                <p class="date">{{ item.created_at }}</p>
                <p class="text">{{ item.text }}</p>
            </li>
        </ul>

        <h2 class="subtitle" v-if="viewedNotifications.length > 0">Попередні сповіщення</h2>

        <ul class="container notifications-list before">
            <li class="notifications-item" v-for="item in viewedNotifications" :key="item.id">
                <p class="date">{{ item.created_at }}</p>
                <p class="text">{{ item.text }}</p>
            </li>
        </ul>
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
        computed: {

        },
        methods: {
            getData() {
                var userid = 1;
                axios.get('/api/notifications/'+userid).then(response => {
                    response.data.map(item => {
                        if (item.status) {
                            this.viewedNotifications.push(item)
                        } else {
                            this.notifications.push(item)
                        }
                    });
                });
            }
        },

    }
</script>
<style scoped lang="scss">
    .subtitle{
        font-family: Montserrat;
        font-style: normal;
        font-weight: normal;
        font-size: 20px;
        color: #A6A6A6;
        text-align: center;
        padding-bottom: 10px;
        border-bottom: 1px solid #A6A6A6;
    }
    .notifications-list{
        margin-top: 90px;
        .notifications-item{
            margin-bottom: 90px;
            .date{
                margin-bottom: 15px;
                font-family: Montserrat;
                font-style: normal;
                font-weight: normal;
                font-size: 20px;
                color: #A6A6A6;
            }
            .text{
                font-family: Montserrat;
                font-style: normal;
                font-weight: 300;
                font-size: 25px;
                color: #18A0FB;

            }
        }
        &.before{
            .notifications-item{
                .text{
                    color: #A6A6A6;
                }
            }
        }
    }


</style>
