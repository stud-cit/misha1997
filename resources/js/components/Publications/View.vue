<template>


    <div class="container">
        <h1 class="blue-page-title">{{data.title}}</h1>
        <div class="page-content">
            <articles :data="data" v-if="data.publication_type.type == 'article'"></articles>
            <book :data="data" v-if="data.publication_type.type == 'book'"></book>
            <div class="edit-block">
                <button class="mr-2 edit">Редагувати</button>
                <button class="delete">Видалити</button>
            </div>
        </div>

    </div>



</template>

<script>
    import Articles from './View_fields/Articles'
    import Book from './View_fields/Book'

    export default {
        data() {
            return {
                data: {},
            }
        },

        created () {
            this.getPublicationData();
        },
        computed: {

        },
        methods: {
            getPublicationData(){
                console.log(this.$route.params.id);
                axios.get(`/api/publication/${this.$route.params.id}`).then(response => {
                    this.data = response.data;
                }).catch(error => {
                    console.log(error);
                });
            }
        },
        components: {
            Articles,
            Book
        }

    }
</script>
<style scoped lang="scss">



</style>
