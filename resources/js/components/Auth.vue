<template>

    <div class=" auth-block">
        <div class="container page-content">
            <h1 class="title">Виконайте авторизацію</h1>
            <p class="description">Щоб почати працювати на веб-сервісі SCIPUB, авторизуйтесь через особистий кабінет СумДУ.</p>
            <div class="text-center"><a href="#" class="open_auth" @click="open_auth">Авторизуватись через кабінет СумДУ</a></div>
        </div>
    </div>


</template>

<script>

    export default {
        data() {
            return {
                auth_window: '',

            };
        },
        components: {

        },
        created () {

        },
        methods: {
            conditional_reload() {
                let location = '';
                try {
                    location = this.auth_window.location.toString()
                } catch (err) {
                    console.log(err);
                }
                if (location.indexOf("jv.") > 0) {
                    this.auth_window.close();
                    window.location.href = '/home';
                }
            },
            open_auth() {
                const strWindowFeatures = "menubar=no,location=no,resizable=no,scrollbars=yes,status=no,width=600,height=600";
                this.auth_window = window.open("http://cabinet.sumdu.edu.ua/index/service/7B4DIDiV", "Вход/Реєстрація через Кабинет", strWindowFeatures);
                setInterval(this.conditional_reload, 250);
                return false;
            }
        }
    }
</script>
<style lang="scss" scoped>
    .auth-block{
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        min-height: 100vh;
        background: #fff;
        display: flex;
        align-items: center;
        .container{
            display: grid;
            align-content: center;
            min-height: 0;
            background: #FFFFFF;
            box-shadow: none;
            border-radius: 0;
            padding: 0;
        }
    }
    .title{
        font-family: Arial;
        font-style: normal;
        font-weight: normal;
        font-size: 40px;
        line-height: 60px;
        color: #212529;
        text-align: center;

    }
    .description{
        font-family: Arial;
        font-style: normal;
        font-weight: normal;
        font-size: 28px;
        line-height: 40px;
        text-align: center;
        color: rgba(33, 37, 41, 0.75);
        margin-top: 30px;
    }
    .open_auth{
        padding: 20px 45px;
        display: inline-block;
        background: #007BFF;
        border-radius: 6px;
        font-family: Arial;
        font-style: normal;
        font-weight: normal;
        font-size: 20px;
        line-height: 24px;
        color: #FFFFFF;
        margin-top: 60px;
        &:hover{
            text-decoration: none;
            color: #fff;
        }
    }

    @media (max-width: 575px )  {
        .auth-block{
            .container{
                padding: 0 15px;
            }
        }
        .title{
            font-size: 24px;
            line-height: 32px;

        }
        .description{

            font-size: 18px;
            line-height: 22px;
            margin-top: 20px;
        }
        .open_auth{
            padding: 12px 20px;
            font-size: 16px;
            line-height: 20px;
            margin-top: 40px;

        }
    }
</style>

