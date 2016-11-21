<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="user-name" content="{{ Auth::user()->name }}">

  <title>{{ config('app.name', 'Laravel') }}</title>

  <!-- Styles -->
  <link href="/css/app.css" rel="stylesheet">

  <style>
    #logout-form {
      display: none;
    }
  </style>

</head>
<body>
<div>
  <div id="app"></div>
</div>

<!-- Scripts -->
<script>window.Laravel = {!! json_encode([ 'csrfToken' => csrf_token() ]) !!};</script>
<script src="/js/admin.app.js"></script>
</body>
</html>