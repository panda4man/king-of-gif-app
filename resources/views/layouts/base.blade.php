<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{csrf_token()}}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name'))</title>

    <link rel="stylesheet" href="{{mix('css/app.css')}}">

    <script>
        window.Laravel = <?php echo json_encode([
            'siteName'   => config('app.name'),
            'socketPort' => env('SOCKET_PORT', '4240'),
            'socketUrl'  => env('SOCKET_URL')
        ]); ?>
    </script>
</head>
<body>
@yield('content')

<script src="{{mix('js/app.js')}}"></script>
</body>
</html>
