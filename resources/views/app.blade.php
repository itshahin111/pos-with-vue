<!DOCTYPE html>
{{-- <html lang="{{ str_replace('_', '-', app()->getLocale()) }}"> --}}
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
     <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="{{url('https://cdnjs.cloudflare.com/ajax/libs/nprogress/0.2.0/nprogress.min.css')}}" />

      <title inertia>Inventory</title>
    <!--For Development Env--->
    @vite('resources/js/app.js')
    @inertiaHead
</head>
<body>
@inertia
</body>
</html>
