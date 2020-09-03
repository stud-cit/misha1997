<template>


    <div class="container page-content">

        <div class="page-list">
            <router-link :to="{name: 'publications'}" class="page-link">Переглянути список публікацій</router-link>
            <router-link :to="{name: 'publications-add'}" class="page-link">Додати нову публікацію</router-link>
            <router-link :to="{name: 'users'}" class="page-link">Список усіх користувачів</router-link>
            <router-link :to="{name: 'publications'}" class="page-link success" @click="">Перевести сервіс в звичайний режим</router-link>
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
        grid-gap: 25px;
        .page-link{
            padding: 15px 25px;
            max-width: 750px;
            font-family: Arial;
            font-style: normal;
            font-weight: normal;
            font-size: 25px;
            line-height: 29px;
            color: #FFFFFF;
            background: #007BFF;
            border-radius: 25px;
            text-align: center;
        }
        .success{

            background-color: #7EF583;
            color: #FFFFFF;
        }
        .danger{

            background-color: #FF6A6A;
            color: #FFFFFF;

            /*text-transform: uppercase;*/
        }
    }
    @media (max-width: 575px )  {

        .page-list{

            grid-gap: 20px;
            .page-link{
                padding: 12px 20px;
                max-width: 100%;
                font-size: 16px;
                line-height: 20px;
                border-radius: 15px;

            }
            .danger, .success{
                padding: 12px 20px;


                /*text-transform: uppercase;*/
            }
        }

    }
</style>

