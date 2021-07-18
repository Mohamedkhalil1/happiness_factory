<!DOCTYPE html>
<!--[if IE 8]>
<html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]>
<html lang="en" class="ie9 no-js"> <![endif]-->
<!--<![endif]-->
<!-- BEGIN HEAD -->

<head>
    <meta charset="utf-8"/>
    {{--    <title>{{$title ?? __('')}} {{ ($title ?? null) ?"|":""}} {{ config('app.name') }}</title>--}}
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport"/>
    <meta content="{{ config('app.name') }}" name="description"/>
    <meta content="Designfy" name="author"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    {{--    <link rel="shortcut icon" href="{{asset('assets/images/logo/logo.png')}}" type="image/x-icon">--}}
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href=" {{asset('assets/css/bootstrap.css')}}">
    <link rel="stylesheet" href=" {{asset('assets/vendors/iconly/bold.css')}}">
    <link rel="stylesheet" href=" {{asset('assets/vendors/perfect-scrollbar/perfect-scrollbar.css')}}">
    <link rel="stylesheet" href=" {{asset('assets/vendors/bootstrap-icons/bootstrap-icons.css')}}">
    <link rel="stylesheet" href=" {{asset('assets/css/app.css')}}">
    <link rel="stylesheet" href="{{asset('assets/vendors/toastify/toastify.css')}}">
    <link rel="stylesheet" href="{{asset('assets/vendors/choices.js/choices.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/vendors/fontawesome/all.min.css')}}">

    <title>
        @yield('title') - {{ config('app.name') }}
    </title>
    <!-- END GLOBAL MANDATORY STYLES -->
    @livewireStyles
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <style>
        .loading-bar {
            position: fixed;
            height: 2px;
            left: 0;
            top: 0;
            width: 100%;
            z-index: 10000;
        }
    </style>
@stack('head')
<!-- END PAGE LEVEL PLUGINS -->

</head>
<!-- END HEAD -->
{{--THE BODY--}}
<body>
<div id="app">
    <x-general.sidebar/>
    <div id="main" class='layout-navbar'>
        <x-general.navbar/>
        <header class="mb-3">
            <a href="#" class="burger-btn d-block d-xl-none">
                <i class="bi bi-justify fs-3"></i>
            </a>
        </header>
        <div id="main-content">
            @yield('content')
            <x-general.footer/>
        </div>
    </div>
</div>

{{--END BODY--}}

<!-- BEGIN CORE PLUGINS -->
{{--JS--}}
<!-- END THEME LAYOUT SCRIPTS -->
<script src="{{asset('assets/vendors/perfect-scrollbar/perfect-scrollbar.min.js')}}"></script>
<script src="{{asset('assets/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('assets/vendors/toastify/toastify.js')}}"></script>
<script src="{{asset('assets/js/extensions/toastify.js')}}"></script>
{{--<script src="{{asset('assets/vendors/apexcharts/apexcharts.js')}}"></script>--}}
{{--<script src="{{asset('assets/js/pages/dashboard.js')}}"></script>--}}
<script src="{{asset('assets/vendors/choices.js/choices.min.js')}}"></script>

<script src="{{asset('assets/js/main.js')}}"></script>


{{--<script>--}}
{{--    $.ajaxSetup({--}}
{{--        headers: {--}}
{{--            'X-CSRF-TOKEN': '{{csrf_token()}}'--}}
{{--        }--}}
{{--    });--}}
{{--</script>--}}
<script>
    window.addEventListener('notify', function (data) {
        Toastify({
            text           : data.detail.message,
            duration       : 3000,
            close          : true,
            gravity        : 'top',
            position       : 'right',
            backgroundColor: data.detail.color,
        }).showToast();
    })
</script>
@livewireScripts
<script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>


@stack('script')
</body>

</html>
