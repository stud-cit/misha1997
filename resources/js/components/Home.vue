<template>
    <div class="container page-content">
        <div class="page-list">
            <router-link :to="{name: 'publications'}" class="page-link">Переглянути список публікацій</router-link>
            <router-link 
                v-if="access == 'open'"
                :to="{name: 'publications-add'}" 
                class="page-link"
            >Додати нову публікацію</router-link>
            <router-link 
                v-if="access == 'open' && (userRole == 4 || userRole == 3 || userRole == 2)"
                :to="{name: 'users'}" 
                class="page-link"
            >Список усіх користувачів</router-link>
            <button 
                v-if="userRole == 4 && access == 'close'"
                class="page-link success" 
                @click="setAccess('open')"
            >Перевести сервіс в звичайний режим</button>
            <button 
                v-if="userRole == 4 && access == 'open'"
                class="page-link danger" 
                @click="setAccess('close')"
            >Перевести сервіс в режим  з обмеженим доступом</button>
        </div>
    </div>
</template>

<script>
    export default {
        computed: {
            userRole() {
                return this.$store.getters.authUser ? this.$store.getters.authUser.roles_id : null
            },
            access() {
                return this.$store.getters.accessMode
            }
        },
        created() {
            this.getAccess();
        },
        methods: {
            getAccess() {
                this.$store.dispatch('getAccess')
            },
            setAccess(mode) {
                this.$store.dispatch('setAccess', mode)
            }
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
        .page-link, button.page-link {
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
        button.success{

            background-color: #7EF583;
            color: #FFFFFF;
        }
        button.danger{

            background-color: #FF6A6A;
            color: #FFFFFF;

            /*text-transform: uppercase;*/
        }
    }
    @media (max-width: 575px )  {

        .page-list{

            grid-gap: 20px;
            .page-link, button.page-link {
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

