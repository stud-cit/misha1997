<template>

    <div class="container">
        <h1 class="blue-page-title">Перегляд повідомлень</h1>
        <div class="page-content">
            <template v-if="notifications.length > 0">
                <h2 class="subtitle new" >Нові повідомлення</h2>
                <hr>
                <ul class=" notifications-list mb-75">
                    <li class="notifications-item" v-for="item in notifications" :key="item.id">
                        <p class="date">{{ item.created_at }}</p>
                        <p class="text">{{ item.text }}</p>
                    </li>
                </ul>
            </template>

            <h2 class="subtitle" >Попередні повідомлення</h2>
            <hr>
            <ul class=" notifications-list before" v-if="viewedNotifications.length > 0">
                <li class="notifications-item" v-for="item in viewedNotifications" :key="item.id">
                    <p class="date">{{ item.created_at }}</p>
                    <p class="text">{{ item.text }}</p>
                </li>
            </ul>
        </div>

    </div>



</template>

<script>
    export default {
        data() {
            return {
                notifications: [
                    {
                        created_at: '12.05.2020',
                        text: 'Автор Бабій Євгеній Андрійович редагував дані в публікації ”Modeling Business Process of Labor Intensity Calculating The Machine-Building Equipment’s Production”'
                    }
                ],
                viewedNotifications: [
                    {
                        created_at: '12.05.2020',
                        text: 'Автор Бабій Євгеній Андрійович редагував дані в публікації ”Modeling Business Process of Labor Intensity Calculating The Machine-Building Equipment’s Production”'
                    },
                    {
                        created_at: '12.05.2020',
                        text: 'Автор Бабій Євгеній Андрійович редагував дані в публікації ”Modeling Business Process of Labor Intensity Calculating The Machine-Building Equipment’s Production”'
                    }

                ]
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


</style>
