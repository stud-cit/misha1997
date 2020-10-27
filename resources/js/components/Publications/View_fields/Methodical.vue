<template>
    <ul class=" list-view">
        <li class="row">
            <div class="col-lg-3 list-item list-title">Рік видання:</div>
            <div class="col-lg-9 list-item list-text">{{data.year}}</div>
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
    </ul>
</template>

<script>
    export default {
        props: {
            data: Object
        }
    }
</script>