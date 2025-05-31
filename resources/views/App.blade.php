<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title')</title>
  <link rel="stylesheet" href="{{ asset('CSS/normalize.css') }}">
  <link rel="stylesheet" href="{{ asset('CSS/style.css') }}">
  @livewireStyles
</head>

<body>
  @yield('body')
  @livewireScripts
</body>

</html>