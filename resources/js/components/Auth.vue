<template>

    <div class=" auth-block">
        <div class="container">
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


            conditional_reload(){
                let location = '';
                try {
                    location = this.auth_window.location.toString()
                } catch (err){
                    console.log(err);
                }
                console.log('auth_location:' + location);
                if (location.indexOf("jv.") > 0){
                    console.log("close stuff");
                    // this.auth_window.close();
                    window.location = '/cabinet';
                }
            },
            open_auth(){
                const strWindowFeatures = "menubar=no,location=no,resizable=no,scrollbars=yes,status=no,width=600,height=600";
                this.auth_window = window.open("http://cabinet.sumdu.edu.ua/index/service/7tvvT6CG", "Вход/Реєстрація через Кабинет",strWindowFeatures);
                console.log(this.auth_window);
                setInterval(this.conditional_reload, 250);
                return false;
            }

            // isOpenedRegistration() {
            //     axios
            //         .get('/is-opened-registration')
            //         .then( ( response ) => {
            //             this.isRegistration = response.data;
            //         })
            //         .catch( ( error ) => console.error(error) )
            // }
        },

    }
</script>
<style lang="scss">
    .auth-block{
        width: 100%;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }
    .title{
        font-family: Montserrat;
        font-style: normal;
        font-weight: normal;
        font-size: 48px;
        line-height: 72px;
        text-align: center;
        color: #000000;
        opacity: 0.5;
    }
    .description{
        font-family: Montserrat;
        font-style: normal;
        font-weight: normal;
        font-size: 36px;
        line-height: 50px;
        text-align: center;
        color: rgba(0, 0, 0, 0.8);
        opacity: 0.5;
        margin-top: 30px;
    }
    .open_auth{
        padding: 25px;
        display: inline-block;
        background: #18A0FB;
        font-family: Montserrat;
        font-style: normal;
        font-weight: 500;
        font-size: 25px;
        line-height: 30px;
        color: #fff;
        margin-top: 60px;
        &:hover{
            text-decoration: none;
            color: #fff;
        }
    }

</style>

