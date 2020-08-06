<template>

    <header class="header">

        <div class="container">

            <nav class="navbar navbar-expand-lg navbar-light ">
                <a class="navbar-brand" href=""><img src="/img/logo.svg" alt=""></a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon" ></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav" v-if="navbar">
                    <ul class="navbar-nav">
                        <li class="nav-item ">
                            <router-link class="nav-link" exist :to="{name: 'home'}" active-class="active">Головна</router-link>

                        </li>
                        <li class="nav-item">
                            <router-link class="nav-link" to="">Підтримка</router-link>
                        </li>
                        <li class="nav-item">
                            <router-link class="nav-link" :to="{name: 'profile'}" active-class="active">Профіль</router-link>
                        </li>
                        <li class="nav-item">

<!--                            <router-link class="nav-link note" exist :to="{name: 'notifications'}" active-class="active">Повідомлення&nbsp;<span class="number">2</span></router-link>-->
                            <router-link class="nav-link" exist :to="{name: 'notifications'}" >Повідомлення</router-link>
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

    }
</script>
<style scoped lang="scss">
    .header{
        position: relative;
        /*display: flex;*/
        /*align-items: center;*/
        padding-top: 25px;
        min-height: 100px;
        left: 0px;
        top: 0px;
        background: #FFFFFF;
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
    @media (max-width: 1365px ) and (min-width: 992px) {
        .header {
            padding-top: 25px;
        }

        .navbar-brand {
            padding: 0;
            margin: 0;
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            max-width: 18vw;
        }
        .navbar-expand-lg {
            padding-left: 60px;


            .navbar-nav {
                width: 100%;
                display: flex;
                justify-content: center;

                li {
                    font-family: Arial;
                    font-style: normal;
                    font-weight: normal;
                    font-size: 20px;
                    text-align: center;
                    color: #465E82;
                    margin: 0 30px;

                }
            }
        }
    }
    @media (max-width: 991px )  {
        .header {
            padding-top: 25px;
        }

        .navbar-brand {

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
                    font-family: Arial;
                    font-style: normal;
                    font-weight: normal;
                    font-size: 20px;
                    text-align: center;
                    color: #465E82;
                    margin: 0 25px;

                }
            }
        }
    }
</style>

