<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="html">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>{{ $title ?? 'Page Title' }}</title>
  @vite(['resources/css/app.css','resources/js/app.js'])
</head>

<body class="bg-azure font-geist" >
  <x-navbar />



  <main class="flex">
    @yield('sidebar')
    @yield('content')
  </main>

</body>

</html>
@php
session(['page' => request()->route()->getName()]);
@endphp