<template>
    <div class="input-container authors">
        <multiselect
            v-model="data.country"
            :options="countries"
            placeholder=""
            :searchable="true"
            selectLabel="Натисніть для вибору"
            selectedLabel="Вибрано"
            deselectLabel="Натисніть для видалення"
        >
            <span slot="noResult">По даному запиту немає результатів</span>
        </multiselect>
    </div>
</template>
<script>
import Multiselect from 'vue-multiselect';
export default {
    data() {
        return {
            countries: []
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