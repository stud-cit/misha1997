<template>

        <div class="error-block">

            <div class="container page-content">

                <h2 class="error-title ">
                    <p>404</p>
                    Сторінку не знайдено</h2>
            </div>
        </div>

</template>

<script>
    export default {
        data() {
            return {

            };
        },

        created () {

        },
        computed: {

        },
        methods: {

        },

    }
</script>
<style scoped lang="scss">
    .error-block{
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
   .error-title{
       /*margin-top: 15vh;*/
       text-align: center;
       font-family: Montserrat;
       font-style: normal;
       font-weight: 500;
       font-size: 50px;
       color: rgba(68, 68, 68, 0.5);
       opacity: 0.75;
       p{
           margin-bottom: 30px;
           font-family: Montserrat;
           font-style: normal;
           font-weight: normal;
           font-size: 250px;
           color: #A6A6A6;


       }
   }
    @media (max-width: 991px) {

    }


    @media (max-width: 414px) {

    }


</style>
