<template>
    <ul class="list-view">
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
        props: {
            data: Object
        }
    }
</script>