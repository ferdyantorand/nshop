<!DOCTYPE html>
<html dir="ltr" lang="en-US">
<head>
    <!-- Document Meta
        ============================================= -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    {{-- gsuite meta tag --}}
    <meta name="google-site-verification" content="c2mtea-KKk-OAoXjQuoopCGSF0ILtZalyoC0HL0MB94" />
    <!--IE Compatibility Meta-->
    <meta name="author" content="zytheme"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="description" content="Customize for tailored & timeless accessories">
    <link rel="shortcut icon" href="{{ asset('/favicon.ico') }}" type="image/x-icon">
    <link rel="icon" href="{{ asset('/favicon.ico') }}" type="image/x-icon">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Fonts
        ============================================= -->
    {{--<link href="http://fonts.googleapis.com/css?family=Raleway:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i%7COpen+Sans:300,300i,400,400i,600,600i,700,700i,800,800i%7CPlayfair+Display:400,400i,700,700i,900,900i%7CDroid+Serif" rel="stylesheet" type="text/css">--}}
    <link href="https://fonts.googleapis.com/css?family=Abril+Fatface|Work+Sans:400,500" rel="stylesheet" type="text/css">

    <!-- Stylesheets
        ============================================= -->
    <link href="{{ asset('css/frontend/external.css') }}" rel="stylesheet">
    <link href="{{ asset('css/frontend/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/frontend/library/font-awesome.min.css') }}" rel="stylesheet">
    {{--<link href="{{ asset('css/frontend/style.css') }}" rel="stylesheet">--}}
    <link href="{{ asset('css/frontend/custom-style.css') }}" rel="stylesheet">
    <link href="{{ asset('css/frontend/custom.css') }}" rel="stylesheet">

    <!-- RS5.0 Main Stylesheet -->
    <link rel="stylesheet" type="text/css" href="{{ asset('revolution/css/settings.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('revolution/css/layers.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('revolution/css/navigation.css') }}">

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements. All other JS at the end of file. -->
    <!--[if lt IE 9]>
    <script src="{{ asset('js/frontend/html5shiv.js')}}"></script>
    <script src="{{ asset('js/frontend/respond.min.js')}}"></script>
    <![endif]-->

    <!-- Document Title
        ============================================= -->
    <title>@yield('pageTitle')</title>
    @yield('scripts-top')

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-142605978-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-142605978-1');
    </script>
    @yield('styles')
</head>
<body>
    {{--<div class="preloader">--}}
        {{--<div class="spinner">--}}
            {{--<div class="bounce1"></div>--}}
            {{--<div class="bounce2"></div>--}}
            {{--<div class="bounce3"></div>--}}
        {{--</div>--}}
    {{--</div><!-- Document Wrapper--}}
	{{--============================================= -->--}}
    <div id="wrapper" class="wrapper clearfix">

        @include('partials.frontend._header')

        @yield('content')
        <!-- Footer #1
        ============================================= -->
        @include('partials.frontend._footer')
    </div><!-- #wrapper end -->

    <!-- Footer Scripts
    ============================================= -->
    <script src="{{ asset('js/frontend/jquery-2.2.4.min.js')}}"></script>
    <script src="{{ asset('js/frontend/plugins.js')}} "></script>
    <script src=" {{ asset('js/frontend/functions.js')}}"></script>
    <!-- RS5.0 Core JS Files -->
    <script type="text/javascript" src="{{ asset('revolution/js/jquery.themepunch.tools.min.js?rev=5.0')}}"></script>
    <script type="text/javascript" src="{{ asset('revolution/js/jquery.themepunch.revolution.min.js?rev=5.0')}}"></script>
    <script type="text/javascript" src="{{ asset('revolution/js/extensions/revolution.extension.video.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('revolution/js/extensions/revolution.extension.slideanims.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('revolution/js/extensions/revolution.extension.actions.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('revolution/js/extensions/revolution.extension.layeranimation.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('revolution/js/extensions/revolution.extension.kenburn.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('revolution/js/extensions/revolution.extension.navigation.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('revolution/js/extensions/revolution.extension.migration.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('revolution/js/extensions/revolution.extension.parallax.min.js')}}"></script>
    <!-- RS Configration JS Files -->
    <script src=" {{ asset('js/frontend/rsconfig.js')}}"></script>
    <script src=" {{ asset('js/frontend/custom.js')}}"></script>
    @yield('scripts')
    @yield('scripts-footer-header')
</body>
</html>
