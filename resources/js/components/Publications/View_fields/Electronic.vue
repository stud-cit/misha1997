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
            <div class="col-lg-3 list-item list-title">Рік видання:</div>
            <div class="col-lg-9 list-item list-text">{{data.year}}</div>
        </li>
        <li class="row">
            <div class="col-lg-3 list-item list-title">Кількість томів:</div>
            <div class="col-lg-9 list-item list-text">{{data.number_volumes}}</div>
        </li>
        <li class="row">
            <div class="col-lg-3 list-item list-title">Том:</div>
            <div class="col-lg-9 list-item list-text">{{data.number}}</div>
        </li>
        <li class="row">
            <div class="col-lg-3 list-item list-title">За редакцією:</div>
            <div class="col-lg-9 list-item list-text">{{data.by_editing}}</div>
        </li>
        <li class="row">
            <div class="col-lg-3 list-item list-title">Країна видання:</div>
            <div class="col-lg-9 list-item list-text">{{data.country}}</div>
        </li>
         <li class="row">
            <div class="col-lg-3 list-item list-title">Місто видання:</div>
            <div class="col-lg-9 list-item list-text">{{data.city}}</div>
        </li>
        <li class="row">
            <div class="col-lg-3 list-item list-title">Видавництво:</div>
            <div class="col-lg-9 list-item list-text">{{data.editor_name}}</div>
        </li>
        <li class="row">
            <div class="col-lg-3 list-item list-title">Кількість сторінок:</div>
            <div class="col-lg-9 list-item list-text">{{data.pages}}</div>
        </li>
        <li class="row">
            <div class="col-lg-3 list-item list-title">Електронна адреса (url):</div>
            <div class="col-lg-9 list-item list-text">{{data.url}}</div>
        </li>

        <li class="row">
            <div class="col-lg-3 list-item list-title">Режим доступу:</div>
            <div class="col-lg-9 list-item list-text" v-if="data.access_mode==0">Закритий</div>
            <div class="col-lg-9 list-item list-text" v-if="data.access_mode==1">Відкритий</div>
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
