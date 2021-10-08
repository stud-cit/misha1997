<!DOCTYPE html>
<html lang="uk">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Наукові публікації Сумського державного університету</title>
        <link href="{{ mix('css/app.css') }}" rel="stylesheet">
        <!-- Global site tag (gtag.js) - Google Analytics -->
        <!-- <script async src="https://www.googletagmanager.com/gtag/js?id=G-M6K6J5KBET"></script>
        <script>
          window.dataLayer = window.dataLayer || [];
          function gtag(){dataLayer.push(arguments);}
          gtag('js', new Date());

          gtag('config', 'G-M6K6J5KBET');
        </script> -->
    </head>
    <body>
      <div id="app">
        <app-component 
          :user="{{ $user }}"
          status="{{ $status }}"
          access="{{ $access }}"
        ></app-component>
      </div>
    </body>
    <script src="{{ asset('js/app.js')}}"></script>
    <script data-services-id="cabinet_service" data-services-options='{"align":"right"}' src="https://cabinet.sumdu.edu.ua/public/js/cabinet.menu-services.min.js"></script>
</html>
