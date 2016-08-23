<!DOCTYPE html>
<!--[if IE 9]><html class="lt-ie10" lang="en" > <![endif]-->
<html lang="en">
<head>
  <title>Viblo</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  {{ HTML::script('js/bootstrap.min.js') }}
  {{ HTML::style('css/bootstrap.min.css') }}
  {{ HTML::style('css/maintenace.css') }}
  {{ HTML::style('css/responsive.css') }}
  {{ HTML::style('css/common.css') }}
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,400,300,600,700&subset=latin,vietnamese' rel='stylesheet' type='text/css'>
</head>

<body>
<div class="container-fluid maintenace">
  <img src="\img\maintenace.png" class="img-responsive" alt="Cinque Terre">
  <p class="notice1">{{ trans('messages.maintenance.down') }}</p>
  <p class="notice2">{{ trans('messages.maintenance.back') }}</p>
</div>
</body>
</html>