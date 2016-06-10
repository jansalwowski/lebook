<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" value="{{ csrf_token() }}"/>

    <title>LeBook</title>

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/animate.min.css') }}" rel="stylesheet">

</head>
<body id="app-layout">
@include('nav.navbar')



@include('partials.flash')

@yield('content')

@include('partials.modals')

<script>
    var baseUrl = '{{ url('/') }}';
</script>
<script src="{{ asset('js/main.js') }}"></script>
</body>
</html>
