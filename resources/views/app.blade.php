<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
{{--        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">--}}
{{--        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">--}}
        <link href="/css/app.css" rel="stylesheet">
        <link href="/css/site.css" rel="stylesheet">

        <!-- Styles -->

    </head>
    <body>
    <div id="app">
        <header-component></header-component>
        <router-view></router-view>

    </div>
    </body>
    <script src="/js/app.js"></script>
{{--    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>--}}
{{--    <script src="/js/xl.js"></script>--}}
{{--    <script src="/js/fileSaver.js"></script>--}}
{{--    <script src="/js/tableExport.js"></script>--}}







</html>
