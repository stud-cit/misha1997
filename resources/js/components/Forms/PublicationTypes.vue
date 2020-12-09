<template>
    <div class="input-container">
        <select v-model="data.publication_type_id">
            <option value=""></option>
            <option v-for="(item, index) in publicationTypes" :value="item.id" :key="index">{{item.title}}</option>
        </select>
    </div>
</template>
<script>
export default {
    data() {
        return {
            publicationTypes: []
        }
    },
    props: {
        data: Object
    },
    created() {
        this.getData();
    },
    methods: {
        getData() {
            axios.get('/api/type-publications').then(response => {
                this.publicationTypes = response.data;
            })
        }
    },
}
</script>