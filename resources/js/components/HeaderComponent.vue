<template>

    <header :class="{shadow: navbar, header: true}">

        <div class="container">

            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <a class="navbar-brand" href="/home"><img src="/img/logo.svg" alt=""></a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation" v-if="navbar">
                    <span class="navbar-toggler-icon" ></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav" v-if="navbar">
                    <ul class="navbar-nav">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle"  href="#" data-toggle="dropdown" >Мої публікації
                                <img class="ml-1 pb2" src="/img/arrow-down.svg" alt=""></a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <router-link :to="{name: 'publications'}" class="dropdown-item">Переглянути список публікацій</router-link>
                                <router-link :to="{name: 'publications-add'}" class="dropdown-item">Додати нову публікацію</router-link>
                                <router-link :to="{name: 'users'}" class="dropdown-item">Список усіх користувачів</router-link>
                                <router-link :to="{name: 'publications'}" class="dropdown-item success" @click="">Перевести сервіс в звичайний режим</router-link>
                                <router-link :to="{name: 'publications'}" class="dropdown-item danger" @click="">Перевести сервіс в режим  з обмеженим доступом</router-link>
                            </div>

                        </li>
                        <li class="nav-item">
                            <a class="nav-link" >Підтримка</a>
                        </li>
                        <li class="nav-item">
                            <router-link class="nav-link" :to="{path: '/profile/1'}" >Профіль</router-link>
                        </li>
                        <li class="nav-item">

<!--                            <a class="nav-link note" exist :to="{name: 'notifications'}" active-class="active">Повідомлення&nbsp;<span class="number">2</span></a>-->
                            <router-link class="nav-link" :to="{path: '/notifications'}" >Повідомлення</router-link>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </header>

</template>

<script>

    export default {
        data() {
            return {
                navbar: true,
            };
        },
        components: {

        },
        created () {
            this.checkAuth();
        },
        methods: {
            checkAuth(){
                if(this.$route.name == "auth"){
                    this.navbar = false;
                }
            }

        },
        watch: {
            '$route' () {
                $('.navbar-collapse').collapse('hide');
            }
        }

    }
</script>
<style scoped lang="scss">
    .bg-light{
        background-color: #fff !important;
    }
    .pb2{
        padding-bottom: 2px;
    }
    .header{
        position: relative;
        padding-top: 25px;
        min-height: 100px;
        left: 0px;
        top: 0px;
        background: #FFFFFF;

        z-index: 10;
    }
    .shadow{
        box-shadow: 0px 5px 5px rgba(0, 0, 0, 0.25);
    }
    .navbar-toggler-icon{
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='30' height='30' viewBox='0 0 30 30'%3e%3cpath stroke='rgba(21, 73, 150, 0.9)' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
    }
    .navbar-toggler{
        border-color: rgba(21, 73, 150, 0.9);
        outline: none;
    }
    .navbar-brand{
        padding: 0;
        margin: 0;
        position: absolute;
        left: 35px;
        top: 50%;
        transform: translateY(-50%);
        max-width: 18vw;
    }
    .navbar-expand-lg{
        /*padding: 0 50px;*/
        position: static;
        justify-content: flex-end;
        .navbar-nav{
            width: 100%;
            display: flex;
            justify-content: center;

            li{
                font-family: Arial;
                font-style: normal;
                font-weight: normal;
                font-size: 20px;
                text-align: center;
                color: #465E82;
                margin: 0 50px;
                a{
                    color: #465E82;
                }
                .success{

                    background-color: #7EF583;
                    color: #FFFFFF;
                    &:hover{
                        opacity: 0.85;
                    }
                }
                .danger{

                    background-color: #FF6A6A;
                    color: #FFFFFF;
                    &:hover{
                        opacity: 0.85;
                    }
                }
            /*    .number{*/

            /*        border-radius: 50%;*/
            /*        background: #6293DB;*/
            /*        padding: 2px 7px;*/
            /*        font-family: Montserrat;*/
            /*        color: #fff;*/
            /*        font-style: normal;*/
            /*        font-weight: normal;*/
            /*        font-size: 18px;*/
            /*    }*/

            }
            /*.note{*/
            /*    color: #6293DB;*/
            /*}*/
        }
    }
    .dropdown-menu{
        padding: 0
    }
    .dropdown-item {
        max-width: 220px;
        white-space: normal;
        font-family: Arial;
        font-style: normal;
        font-weight: normal;
        font-size: 20px;
        line-height: 1.2;
        text-align: center;
        color: #465E82;
        padding: 10px 15px;
        &:active{
            color: #fff !important;
        }
    }
    .dropdown-toggle::after{
        display: none;
    }

    @media (max-width: 1365px ) and (min-width: 992px) {


        .navbar-brand {

            left: 20px;
        }
        .navbar-expand-lg {
            padding-left: 125px;


            .navbar-nav {

                li {

                    margin: 0 30px;


                }
            }
        }

    }
    @media (max-width: 991px )  {
        .header {
            padding: 15px 0;
            min-height: 85px;
        }

        .navbar-brand {
            padding-top: 10px;
            position: static;
            left: 0;
            top: 0;
            transform: translateY(0);
            max-width: 165px;
        }
        .navbar-expand-lg {
            padding: 0;
            justify-content: space-between;

            .navbar-nav {


                li {

                    margin: 0 25px;

                }
            }
        }
        /*.dropdown-menu{*/
        /*    border: none;*/
        /*}*/
        .dropdown-item {
            max-width: 100%;

        }
    }
    @media (max-width: 575px )  {
        .header {
            padding: 10px 0;
            min-height: auto;
        }


        .navbar-expand-lg {

            .navbar-nav {
                padding-top: 10px;

                li {
                    font-family: Arial;
                    font-style: normal;
                    font-weight: normal;
                    font-size: 16px;
                    text-align: center;
                    color: #465E82;
                    margin: 0 25px;

                }
            }
        }
        .dropdown-item {


            font-size: 16px;

        }
    }
</style>

