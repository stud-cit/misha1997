<template>


    <div class="container">
        <div class="page-list">
            <router-link :to="{name: 'publications'}" class="page-link">Переглянути список публікацій</router-link>
            <router-link :to="{name: 'publications-add'}" class="page-link">Додати нову публікацію</router-link>
            <router-link :to="{name: 'users'}" class="page-link">Список усіх користувачів</router-link>
            <router-link :to="{name: 'publications'}" class="page-link">Експорт списку особистих публікацій у форматі .pdf</router-link>
            <router-link :to="{name: 'publications'}" class="page-link">Експорт списку усіх публікацій в форматі .pdf</router-link>
            <router-link :to="{name: 'publications'}" class="page-link">Експорт рейтингових показників в форматі .pdf</router-link>
            <router-link :to="{name: 'publications'}" class="page-link danger" @click="">Перевести сервіс в режим з обмеженим доступом</router-link>

        </div>
    </div>



</template>

<script>

    export default {
        data() {
            return {
                access: true,

            };
        },
        components: {

        },
        created () {

        },
        methods: {



        },

    }
</script>
<style lang="scss" scoped>
    .page-list{
        margin-top: 50px;
        display: flex;
        flex-direction: column;
        align-items: center;
        .page-link{
            width: 80%;
            margin: 20px 0;
            padding: 20px;
            font-family: Montserrat;
            font-style: normal;
            font-weight: normal;
            font-size: 26px;
            text-align: center;
            color: #18A0FB;
            border: 3px solid #18A0FB;
            box-sizing: border-box;
            border-radius: 20px;
        }
        .danger{
            background-color: #FF6A6A;
            color: #FFFFFF;
            border: none;
            text-transform: uppercase;
        }
    }

</style>

