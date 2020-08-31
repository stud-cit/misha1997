<template>


    <div class="container">
        <h1 class="blue-page-title">{{data.title}}</h1>
        <div class="page-content">
            <articles :data="data" v-if="data.publication_type.type == 'article'"></articles>
            <book :data="data" v-if="data.publication_type.type == 'book'"></book>
            <book-part :data="data" v-if="data.publication_type.type == 'book-part'"></book-part>
            <thesis :data="data" v-if="data.publication_type.type == 'thesis'"></thesis>
            <patent :data="data" v-if="data.publication_type.type == 'patent'"></patent>
            <certificate :data="data" v-if="data.publication_type.type == 'certificate'"></certificate>
            <methodical :data="data" v-if="data.publication_type.type == 'methodical'"></methodical>
            <electronic :data="data" v-if="data.publication_type.type == 'electronic'"></electronic>
            <div class="edit-block">
                <button class="mr-2 edit">Редагувати</button>
                <button class="delete">Видалити</button>
            </div>
        </div>

    </div>



</template>

<script>
    import Articles from './View_fields/Articles';
    import Book from './View_fields/Book';
    import BookPart from "./View_fields/BookPart";
    import Thesis from "./View_fields/Thesis";
    import Patent from "./View_fields/Patent";
    import Certificate from "./View_fields/Certificate";
    import Methodical from "./View_fields/Methodical";
    import Electronic from "./View_fields/Electronic";

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
            Book,
            BookPart,
            Thesis,
            Patent,
            Certificate,
            Methodical,
            Electronic
        }

    }
</script>
<style scoped lang="scss">



</style>
