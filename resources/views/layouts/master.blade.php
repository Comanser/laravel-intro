<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title></title>
        <link rel="stylesheet" href="{{ URL::secure('src/css/main.css') }}">
        @yield('styles')
    </head>
  
    <body>
        @include('includes.header')
        <div class="main">
            @yield('content')
        </div>
    </body>
</html>