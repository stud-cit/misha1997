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
            <div class="col-lg-3 list-item list-title">Назва конференції:</div>
            <div class="col-lg-9 list-item list-text">{{data.name_conference}}</div>
        </li>
        <li class="row">
            <div class="col-lg-3 list-item list-title">Рік видання:</div>
            <div class="col-lg-9 list-item list-text">{{data.year}}</div>
        </li>
        <li class="row">
            <div class="col-lg-3 list-item list-title">Видавництво:</div>
            <div class="col-lg-9 list-item list-text">{{data.editor_name}}</div>
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
            <div class="col-lg-3 list-item list-title">Місто видання:</div>
            <div class="col-lg-9 list-item list-text">{{data.city}}</div>
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
