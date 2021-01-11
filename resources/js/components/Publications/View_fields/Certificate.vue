<template>
    <ul class="list-view">
        <li class="row">
            <div class="col-lg-3 list-item list-title">№ свідоцтва/рішення:</div>
            <div class="col-lg-9 list-item list-text">{{data.number_certificate}}</div>
        </li>
        <li class="row">
            <div class="col-lg-3 list-item list-title">Рік видання:</div>
            <div class="col-lg-9 list-item list-text">{{data.year}}</div>
        </li>
        <li class="row">
            <div class="col-lg-3 list-item list-title">Країна, де отриманий патент:</div>
            <div class="col-lg-9 list-item list-text">{{data.country}}</div>
        </li>
        <li class="row">
            <div class="col-lg-3 list-item list-title">Власник майнових прав:</div>
            <div class="col-lg-9 list-item list-text">{{data.applicant}}</div>
        </li>
        <li class="row">
            <div class="col-lg-3 list-item list-title">Дата подачі заявки:</div>
            <div class="col-lg-9 list-item list-text">{{data.date_application}}</div>
        </li>
        <li class="row">
            <div class="col-lg-3 list-item list-title">Дата публікації про видачу свідоцтва/рішення:</div>
            <div class="col-lg-9 list-item list-text">{{data.date_publication}}</div>
        </li>
        <li class="row">
            <div class="col-lg-3 list-item list-title">Комерціалізовано університетом:</div>
            <div class="col-lg-9 list-item list-text">{{ data.commerc_university ? "Так" : "Ні" }}</div>
        </li>
        <li class="row">
            <div class="col-lg-3 list-item list-title">Комерціалізовано штатними співробітниками університету:</div>
            <div class="col-lg-9 list-item list-text">{{ data.commerc_employees ? "Так" : "Ні" }}</div>
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
