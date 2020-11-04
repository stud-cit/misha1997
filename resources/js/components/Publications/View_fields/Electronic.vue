<template>
    <ul class=" list-view">
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
            <div class="col-lg-9 list-item list-text"><a :href="data.url" target="_blank">{{data.url}}</a></div>
        </li>
        <li class="row">
            <div class="col-lg-3 list-item list-title">Режим доступу:</div>
            <div class="col-lg-9 list-item list-text">{{ data.access_mode ? "Відкритий" : "Закритий" }}</div>
        </li>
    </ul>
</template>

<script>
    export default {
        props: {
            data: Object
        }
    }
</script>