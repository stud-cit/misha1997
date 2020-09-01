<template>
    <ul class=" list-view">
        <li class="row">
            <div class="col-lg-3 list-item list-title">Назва публікації:</div>
            <div class="col-lg-9 list-item list-text">{{data.title}}</div>
        </li>
        <li class="row">
            <div class="col-lg-3 list-item list-title">Автори публікації:</div>
            <div class="col-lg-9 list-item list-text">{{data.initials}}</div>
        </li>
        <li class="row">
            <div class="col-lg-3 list-item list-title">Вид публікації:</div>
            <div class="col-lg-9 list-item list-text">{{data.publication_type.title}}</div>
        </li>
        <template v-if="data.supervisor_id!=null">
            <li class="row">
                <div class="col-lg-3 list-item list-title">Під керівництвом:</div>
                <div class="col-lg-9 list-item list-text">Так</div>
            </li>
            <li class="row">
                <div class="col-lg-3 list-item list-title">Керівник:</div>
                <div class="col-lg-9 list-item list-text">{{data.supervisor.name}}</div>
            </li>
        </template>
        
        <template v-else>
            <li class="row">
                <div class="col-lg-3 list-item list-title">Під керівництвом:</div>
                <div class="col-lg-9 list-item list-text">Ні</div>
            </li>
        </template>

        

        <template v-if="data.science_type_id!=null">
            <li class="row">
                <div class="col-lg-3 list-item list-title">БД Scopus\WoS:</div>
                <div class="col-lg-9 list-item list-text">{{data.science_type.type}}</div>
            </li>
            <li class="row">
                <div class="col-lg-3 list-item list-title">SNIP журналу (БД Scopus):</div>
                <div class="col-lg-9 list-item list-text">{{data.snip}}</div>
            </li>
            <li class="row">
                <div class="col-lg-3 list-item list-title">Квартиль журналу (БД Scopus):</div>
                <div class="col-lg-9 list-item list-text">{{data.quartil_scopus}}</div>
            </li>
        
            <li class="row">
                <div class="col-lg-3 list-item list-title">Імпакт-фактор (БД WoS):</div>
                <div class="col-lg-9 list-item list-text">{{data.impact_factor}}</div>
            </li>
            <li class="row">
                <div class="col-lg-3 list-item list-title">Квартиль журналу (БД WoS):</div>
                <div class="col-lg-9 list-item list-text">{{data.quartil_wos}}</div>
            </li>
            <li class="row">
                <div class="col-lg-3 list-item list-title">Підбаза WoS:</div>
                <div class="col-lg-9 list-item list-text" v-if="data.sub_db_index==1">Science Citation Index Expanded (SCIE)</div>
                <div class="col-lg-9 list-item list-text" v-if="data.sub_db_index==2">Social Science Citation Index</div>
            </li>
        </template>


        <li class="row">
            <div class="col-lg-3 list-item list-title">Назва журналу:</div>
            <div class="col-lg-9 list-item list-text">{{data.name_magazine}}</div>
        </li>
        <li class="row">
            <div class="col-lg-3 list-item list-title">Рік видання:</div>
            <div class="col-lg-9 list-item list-text">{{data.year}}</div>
        </li>
        <li class="row">
            <div class="col-lg-3 list-item list-title">Номер (том):</div>
            <div class="col-lg-9 list-item list-text">{{data.number}}</div>
        </li>
        <li class="row">
            <div class="col-lg-3 list-item list-title">Сторінки:</div>
            <div class="col-lg-9 list-item list-text">{{data.pages}}</div>
        </li>
        <li class="row">
            <div class="col-lg-3 list-item list-title">Країна видання:</div>
            <div class="col-lg-9 list-item list-text">{{data.country}}</div>
        </li>
        <li class="row">
            <div class="col-lg-3 list-item list-title">DOI:</div>
            <div class="col-lg-9 list-item list-text">{{data.doi}}</div>
        </li>


    </ul>
</template>

<script>
    export default {
        data() {
            return {

            }
        },
        props: {
            data: Object
        }
    }
</script>

<style scoped>

</style>
