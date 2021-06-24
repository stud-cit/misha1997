<template>
    <div class="container page-content general-block">
        <div class="page-list">
            <a v-if="userRole == 4 || access == 'open'" href="/publications/add" class="page-link">Додати нову публікацію</a>
            <a href="/my-publications" class="page-link">Мої публікації</a>
            <a v-if="userRole != 1" href="/publications" class="page-link">Cписок усіх публікацій</a>
            <a v-if="userRole != 1" href="/users" class="page-link">Список усіх користувачів</a>
            <a href="/manual.pdf" class="page-link" target="_blank">Інструкція користувача</a>
            <a v-if="userRole == 4" href="/audit" class="page-link">Аудит</a>
            <a v-if="userRole == 4" href="/scopus" class="page-link">Публікації Scopus</a>
            <a v-if="userRole == 4" href="/archive" class="page-link">Архів публікацій</a>
            <!-- <a v-if="userRole == 4" href="#" class="page-link">Експорт рейтингових показників</a> -->
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
        display: block;
        width: 70%;
        margin: 0 auto;
        align-content: center;
        justify-content: center;
        grid-gap: 25px;
        .page-link{
            padding: 15px 25px;
            width: 100%;
            margin-bottom: 15px;
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
            width: 100%;
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
