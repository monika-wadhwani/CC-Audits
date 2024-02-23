{{-- header starts here --}}
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>

    @yield('css')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">
    <link rel="stylesheet" href="{{ url('assets/design/css/style.css') }}">
    <script src="{{ url('assets/vendors/base/vendors.bundle.js') }}" type="text/javascript"></script>
    <script src="{{ url('assets/demo/default/base/scripts.bundle.js') }}" type="text/javascript"></script>
    <script src="{{ url('assets/app/bundle/app.bundle.js') }}" type="text/javascript"></script>
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>

</head>

<body>

    <section class="dashBoard " id="app">
        @include('porter_design.shared.leftmenu')
        {{-- header ends here --}}
        @yield('main')
    </section>
    @include('porter_design.shared.footer')
	{{-- <script type="text/javascript" src="{{url('js/app.js')}}"></script> --}}

    @yield('js')

</body>

</html>
