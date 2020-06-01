<template>

    <header class="header">

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

                        <router-link class="nav-link note" exist :to="{name: 'notification'}" active-class="active">Сповіщення&nbsp;<span class="number">2</span></router-link>
                    </li>
                </ul>
            </div>
        </nav>
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
    .navbar-toggler-icon{
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='30' height='30' viewBox='0 0 30 30'%3e%3cpath stroke='rgba(21, 73, 150, 0.9)' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
    }
    .navbar-toggler{
        border-color: rgba(21, 73, 150, 0.9);
        outline: none;
    }
    .navbar-brand{
        margin-right: 20px;
    }
    .navbar-expand-lg{
        .navbar-nav{
            width: 100%;
            display: flex;
            justify-content: space-around;
            padding-right: 220px;
            li{
                font-family: Montserrat;
                font-style: normal;
                font-weight: normal;
                font-size: 20px;
                text-align: center;
                color: #A6A6A6;
                .number{

                    border-radius: 50%;
                    background: #6293DB;
                    padding: 2px 7px;
                    font-family: Montserrat;
                    color: #fff;
                    font-style: normal;
                    font-weight: normal;
                    font-size: 18px;
                }

            }
            .note{
                color: #6293DB;
            }
        }
    }
</style>

