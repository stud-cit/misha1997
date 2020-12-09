<!DOCTYPE html>
<html lang="uk">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Наукові публікації Сумського державного університету</title>
        <link href="/css/app.css" rel="stylesheet">
        <link href="/css/site.css" rel="stylesheet">
    </head>
    <body>
    <div id="app">
        <header-component></header-component>
        <div class="wrapper">
            <transition name="component-fade" mode="out-in">
                <router-view></router-view>
            </transition>
        </div>
    </div>
    <!-- HotLog -->
    <div style="visibility: hidden">
        <span id="hotlog_counter"></span>
        <span id="hotlog_dyn"></span>
    </div>
    <script type="text/javascript"> var hot_s = document.createElement('script');
        hot_s.type = 'text/javascript'; hot_s.async = true;
        hot_s.src = 'https://js.hotlog.ru/dcounter/2592716.js';
        hot_d = document.getElementById('hotlog_dyn');
        hot_d.appendChild(hot_s);
    </script>
    <noscript>
        <a href="http://click.hotlog.ru/?2592716" target="_blank">
        <img src="http://hit5.hotlog.ru/cgi-bin/hotlog/count?s=2592716&im=357" border="0" title="HotLog" alt="HotLog"></a>
    </noscript>
    <!-- /HotLog -->
    </body>
    <script src="/js/app.js"></script>
    <script data-services-id="cabinet_service" data-services-options='{"align":"right"}' src="https://cabinet.sumdu.edu.ua/public/js/cabinet.menu-services.min.js"></script>
</html>
