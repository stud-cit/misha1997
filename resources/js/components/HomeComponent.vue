<template>

    <div>
        <div>content</div>
        <a href="#" @click="open_auth">login</a>
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
                        this.auth_window.close();
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
<style>

</style>

