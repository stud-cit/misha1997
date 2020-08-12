<template>


    <div class="container page-content">

        <div class="page-list">
            <router-link :to="{name: 'publications'}" class="page-link">Переглянути список публікацій</router-link>
            <router-link :to="{name: 'publications-add'}" class="page-link">Додати нову публікацію</router-link>
            <router-link :to="{name: 'users'}" class="page-link">Список усіх користувачів</router-link>

            <router-link :to="{name: 'publications'}" class="page-link danger" @click="">Перевести сервіс в режим  з обмеженим доступом</router-link>

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
    .container{
        display: grid;
        align-content: center;
    }
    .page-list{
        display: grid;
        align-content: center;
        justify-content: center;
        grid-gap: 40px;
        .page-link{
            padding: 22px 80px;
            max-width: 565px;
            font-family: Arial;
            font-style: normal;
            font-weight: normal;
            font-size: 28px;
            line-height: 32px;
            color: #FFFFFF;
            background: #007BFF;
            border-radius: 25px;
            text-align: center;
        }
        .danger{
            padding: 10px 80px;
            background-color: #FF6A6A;
            color: #FFFFFF;

            /*text-transform: uppercase;*/
        }
    }

</style>

