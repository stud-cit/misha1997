<template>
    <div>
        <multiselect
            v-model="data.country"
            :options="countries"
            placeholder=""
            :searchable="true"
            selectLabel="Натисніть щоб обрати"
            selectedLabel="Обрано"
            deselectLabel="Натисніть щоб видалити"
        >
            <span slot="noResult">По даному запиту результатів не знайдено</span>
        </multiselect>
    </div>
</template>
<script>
import Multiselect from 'vue-multiselect';
export default {
    data() {
        return {
            countries: [],
        }
    },
    components: {
        Multiselect
    },
    props: {
        data: Object
    },
    created() {
        this.getData();
    },
    methods: {
        getData() {
            axios.get('/api/country').then(response => {
                this.countries = response.data;
            })
        }
    },
}
</script>