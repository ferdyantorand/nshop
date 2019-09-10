
<header id="navbar-spy" class="header header-13 header-topbar header-light header-fixed">
    <div id="top-bar" class="top-bar">
        <div class="container">
            <div class="bottom-bar-border">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 top--contact center color-black">
                        <ul class="list-inline mb-0">
                            <li>
                                {{--<span>FREE SHIPPING ON All ORDERS OVER XXX</span>--}}
                            </li>
                        </ul>
                    </div><!-- .col-md-6 end -->
                </div>
            </div>
        </div>
    </div>

    <!-- Menu section DESKTOP
    ============================================= -->
    <nav id="primary-menu" class="navbar navbar-fixed-top hidden-sm hidden-xs">
        <div class="container">
            <!-- Collect the nav links, forms, and other content for toggling -->

            <div class="collapse navbar-collapse pull-left" id="navbar-collapse-2">
                <!-- Module Shop -->
                <div class="module module-search2 pull-left">
                    <div class="module-icon search-icon color-black">
                        {{--<i class="fa fa-search"></i>--}}
                        {{--<span class="title">search</span>--}}
                        <a class="pointer">SHOP</a>
                    </div>
                    <div class="module-content module-fullscreen module--search2-box">
                        <div class="pos-vertical-center">
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12 center">
                                    <a href="{{route('product.list')}}"><H4 class="header-menu">SHOP ALL</H4></a>
                                    <a href="/product-list?category=1-2-3-4-14-15-17-20-21"><H4 class="header-menu">BAGS & TOTES</H4></a>
                                    <a href="/product-list?category=6-7-8"><H4 class="header-menu">WALLETS</H4></a>
                                    {{--<a href="/product-list?category=3"><H4 class="header-menu">CARD HOLDERS</H4></a>--}}
                                    <a href="/product-list?category=5-11-16"><H4 class="header-menu">POUCHES</H4></a>
                                    <a href="/product-list?category=10"><H4 class="header-menu">PHONE CASES</H4></a>
                                    <a href="/product-list?category=9-18-19"><H4 class="header-menu">ACCESSORIES</H4></a>
                                </div><!-- .col-md-8 end -->
                            </div><!-- .row end -->
                        </div>
                        <a class="module-cancel pointer"><i class="fa fa-close"></i></a>
                    </div>
                </div><!-- .module-Shop end -->

                <!-- Module About -->
                <div class="module module-search2 pull-left">
                    <div class="module-icon search-icon color-black">
                        {{--<i class="fa fa-search"></i>--}}
                        {{--<span class="title">search</span>--}}
                        <a href="{{route('about.us')}}">ABOUT</a>
                    </div>
                    {{--<div class="module-content module-fullscreen module--search2-box">--}}
                    {{--<div class="pos-vertical-center">--}}
                    {{--<div class="container">--}}
                    {{--<div class="row">--}}
                    {{--<div class="col-xs-6 col-sm-6 col-md-6">--}}
                    {{--<form class="form-search2">--}}
                    {{--<input type="text" class="form-control" placeholder="test">--}}
                    {{--<button class="btn" type="button"><i class="fa fa-search"></i></button>--}}
                    {{--</form><!-- .form-search end -->--}}
                    {{--</div><!-- .col-md-8 end -->--}}
                    {{--</div><!-- .row end -->--}}
                    {{--</div><!-- .container end -->--}}
                    {{--</div>--}}
                    {{--<a class="module-cancel" href="#"><i class="fa fa-close"></i></a>--}}
                    {{--</div>--}}
                </div><!-- .module-About end -->
                <!-- Module About -->
                <div class="module module-search2 pull-left">
                    <div class="module-icon search-icon color-black">
                        <a href="{{route('contact-form')}}">CONTACT</a>
                    </div>
                </div><!-- .module-About end -->

                {{--<!-- Module Contact -->--}}
                {{--<div class="module module-search2 pull-left">--}}
                    {{--<div class="module-icon search-icon color-black">--}}
                        {{--<a class="pointer">CONTACT OLD</a>--}}
                    {{--</div>--}}
                    {{--<div class="module-content module-fullscreen module--search2-box">--}}
                        {{--<div class="pos-vertical-center">--}}
                            {{--<div class="container">--}}
                                {{--<div class="row">--}}
                                    {{--<div class="col-xs-6 col-sm-6 col-md-6" style="padding: 0 1% 0 7%;">--}}
                                        {{--<h2>Contact</h2>--}}
                                        {{--<hr style="height:1px;border:none;color:#333;background-color:#333;" />--}}
                                        {{--<br/>--}}
                                        {{--{!! Form::open(array('action' => 'Frontend\HomeController@contact', 'id'=>'form-contact', 'class'=>'form-search2', 'method' => 'POST', 'role' => 'form', 'enctype' => 'multipart/form-data', 'novalidate')) !!}--}}

                                            {{--<input type="text" class="form-control" name="name" id="name" placeholder="NAME" required/>--}}
                                            {{--<input type="email" class="form-control" name="email" id="email" placeholder="EMAIL ADDRESS" required/>--}}
                                            {{--<input type="text" class="form-control" name="order" id="order" placeholder="ORDER NUMBER (IF APPLICABLE)"/>--}}
                                            {{--<textarea class="form-control" name="message" id="message" rows="2" placeholder="MESSAGE" required></textarea>--}}

                                            {{--<div style="text-align: center;">--}}
                                                {{--<button type="submit" class="btn btn--secondary btn--bordered">SEND</button>--}}
                                            {{--</div>--}}

                                        {{--{!! Form::close() !!}--}}
                                    {{--</div><!-- .col-md-8 end -->--}}
                                {{--</div><!-- .row end -->--}}
                            {{--</div><!-- .container end -->--}}
                        {{--</div>--}}
                        {{--<a class="module-cancel pointer"><i class="fa fa-close"></i></a>--}}
                    {{--</div>--}}
                {{--</div><!-- .module-Contact end -->--}}

            </div>
            <!-- .navbar-collapse -->
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="logo" href="{{route('home')}}">
                    {{--<img class="logo-light" src="{{ asset('images/icons/nama-brand-logo.svg') }}" alt="Nama Logo">--}}
                    {{--<img class="logo-dark" src="{{ asset('images/icons/nama-brand-logo.svg') }}" alt="Nama Logo">--}}
                    <img  src="{{ asset('images/icons/logo.jpg') }}" alt="Nama Logo" style="width: 25%;">
                    {{--<h1 style="font-size: 48px;">nama.</h1>--}}
                </a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse pull-right" id="navbar-collapse-1">
                <ul class="nav navbar-nav nav-pos-right navbar-left">

                    <!-- Home Menu -->
                    <li>
                        <a href="{{route('cart')}}"  class="menu-item">
                            CART <span class="badge">{{ \Illuminate\Support\Facades\Session::has('cartQty') ? \Illuminate\Support\Facades\Session::get('cartQty') : '0' }}</span>
                        </a>
                        <!-- .mega-dropdown-menu end -->
                    </li>
                    <!-- Home Menu -->

                    @if(auth()->guard('web')->check())
                        <li>
                            <a href="{{route('orders')}}"  class="menu-item">My ORDER</a>
                            <!-- .mega-dropdown-menu end -->
                        </li>
                        <li>
                            <a href="{{route('logout')}}"  class="menu-item">LOGOUT</a>
                            <!-- .mega-dropdown-menu end -->
                        </li>
                    @else
                        <li>
                            <a href="{{route('login')}}"  class="menu-item">LOGIN</a>
                            <!-- .mega-dropdown-menu end -->
                        </li>
                    @endif
                </ul>


                <!-- Module Search -->
                <div class="module module-search pull-left">
                    <div class="module-icon search-icon color-black">
                        {{--<i class="fa fa-search"></i>--}}
                        {{--<span class="title">search</span>--}}
                        <a class="pointer">SEARCH</a>
                    </div>
                    <div class="module-content module-fullscreen module--search-box">
                        <div class="pos-vertical-center">
                            <div class="container">
                                <div class="row">
                                    <div class="col-xs-6 col-sm-6 col-md-6 center">
                                        <h4>Search</h4>

                                        {!! Form::open(array('action' => 'Frontend\ProductController@search', 'id'=>'form-search', 'class'=>'form-search', 'method' => 'POST', 'role' => 'form', 'enctype' => 'multipart/form-data', 'novalidate')) !!}

                                        <input type="text" class="form-control" id="search-text" name="search-text">
                                        <button class="btn search-button contact-button" type="button" onClick="empty()" >
                                            <i class="fa fa-long-arrow-right"></i>
                                        </button>

                                        {!! Form::close() !!}
                                    </div><!-- .col-md-8 end -->
                                </div><!-- .row end -->
                            </div><!-- .container end -->
                        </div>
                        <a class="module-cancel pointer"><i class="fa fa-close"></i></a>
                    </div>
                </div><!-- .module-search end -->
            </div>
            <!-- .navbar-collapse -->
        </div>
        <!-- .container end  -->
    </nav>

    <!-- Menu section MOBILE
    ============================================= -->

    <div id="menu-fullscreen" class="menu-fullscreen" style="display:none;">
        <div class="col-xs-12 col-sm-12 center" style="padding-top: 32%;">

            <!-- Module Shop -->
            <div id="shop-search2" class="module module-search pull-left">
                <div class="module-icon search-icon color-black" style="margin-right: 5px !important;">
                    {{--<i class="fa fa-search"></i>--}}
                    {{--<span class="title">search</span>--}}
                    <H4 class="header-menu">SHOP</H4>
                </div>
                <div id="shop-fullscreen" class="module-content module-fullscreen module--search2-box">
                    <div class="pos-vertical-center">
                        <div class="container">
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 center">
                                    <a href="{{route('product.list')}}"><H4 class="header-menu">SHOP ALL</H4></a>
                                    <a href="/product-list?category=1-2-3-4-14-15-17-20-21"><H4 class="header-menu">BAGS & TOTES</H4></a>
                                    <a href="/product-list?category=6-7-8"><H4 class="header-menu">WALLETS</H4></a>
                                    {{--<a href="/product-list?category=3"><H4 class="header-menu">CARD HOLDERS</H4></a>--}}
                                    <a href="/product-list?category=5-11-16"><H4 class="header-menu">POUCHES</H4></a>
                                    <a href="/product-list?category=10"><H4 class="header-menu">PHONE CASES</H4></a>
                                    <a href="/product-list?category=9-18-19"><H4 class="header-menu">ACCESSORIES</H4></a>
                                </div><!-- .col-md-8 end -->
                            </div><!-- .row end -->
                        </div><!-- .container end -->
                    </div>
                    <a class="module-cancel pointer"><i class="fa fa-close"></i></a>
                </div>
            </div><!-- .module-Shop end -->

            <!-- Module About -->
            <div class="module module-search pull-left">
                <div class="module-icon search-icon color-black" style="margin-right: 5px !important;">
                    <a href="{{route('about.us')}}"><H4 class="header-menu">ABOUT</H4></a>
                </div>
            </div><!-- .module-About end -->

            <!-- Module Contact -->
            <div class="module module-search pull-left">
                <div class="module-icon search-icon color-black" style="margin-right: 5px !important;">
                    <a href="{{route('contact-form')}}"><H4 class="header-menu">CONTACT</H4></a>
                </div>
            </div><!-- .module-About end -->

            <!-- Module Search -->
            <div id="search-search2" class="module module-search pull-left">
                <div class="module-icon search-icon color-black">
                    <H4 class="header-menu">SEARCH</H4>
                </div>
                <div class="module-content module-fullscreen module--search-box">
                    <div class="pos-vertical-center">
                        <div class="container">
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 center">
                                    <h4>Search</h4>

                                    {!! Form::open(array('action' => 'Frontend\ProductController@search', 'id'=>'form-search', 'class'=>'form-search', 'method' => 'POST', 'role' => 'form', 'enctype' => 'multipart/form-data', 'novalidate')) !!}

                                    <input type="text" class="form-control" id="search-text" name="search-text">
                                    <button class="btn search-button contact-button" type="button" onClick="empty()" >
                                        <i class="fa fa-long-arrow-right"></i>
                                    </button>

                                    {!! Form::close() !!}
                                </div><!-- .col-md-8 end -->
                            </div><!-- .row end -->
                        </div><!-- .container end -->
                    </div>
                    <a class="module-cancel pointer"><i class="fa fa-close"></i></a>
                </div>
            </div><!-- .module-search end -->
            <a class="module-cancel-menu pointer" style="font-size: 16px;"><i class="fa fa-close"></i></a>
        </div><!-- .col-md-8 end -->
    </div>

    <!-- Menu section MOBILE
    ============================================= -->

    <nav id="primary-menu" class="navbar navbar-fixed-top hidden-md hidden-lg" >
        <div class="container">
            <!-- Collect the nav links, forms, and other content for toggling -->

            <!-- .navbar-collapse -->
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header" style="position: relative;">
                <button id="module-menu" type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                        data-target="#navbar-collapse-3" aria-expanded="false"
                        style="float: left;">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                @if(auth()->guard('web')->check())
                    <a href="{{route('logout')}}" class="navbar-toggle collapsed icon-header-responsive" style="float: right;margin-right: 10px;">
                        <span class="sr-only">Toggle navigation</span>
                        <img src="{{asset('/images/icons/logout-arrow.png')}}" style="width: 100%;">
                    </a>

                    <!-- Module myorder -->
                    <a href="{{route('orders')}}" class="navbar-toggle collapsed icon-header-responsive" style="float: right;margin-right: 15px;">
                        <span class="sr-only">Toggle navigation</span>
                        <img src="{{asset('/images/icons/order-list.png')}}" style="width: 100%;">
                    </a>
                @else
                    <a href="{{route('login')}}" class="navbar-toggle collapsed icon-header-responsive" style="float: right;">
                        <span class="sr-only">Toggle navigation</span>
                        <img src="{{asset('/images/icons/login-arrow.png')}}" style="width: 100%;">
                    </a>
                @endif
                <a href="{{route('cart')}}" class="navbar-toggle collapsed icon-header-responsive"
                        style="float: right;margin-right: 17px;">
                    <span class="sr-only">Toggle navigation</span>
                    <img src="{{asset('/images/icons/cart-image.png')}}" style="width: 100%;">
                </a>

                @if(auth()->guard('web')->check())
                    <span class="badge badge-cart" style="font-size: 10px;">
                        {{ \Illuminate\Support\Facades\Session::has('cartQty') ? \Illuminate\Support\Facades\Session::get('cartQty') : '' }}
                    </span>
                @endif
                <a class="logo center navbar-toggle collapsed" href="{{route('home')}}" style="border: none;line-height: 0;height: 0;width: 115px;">
                    <img  src="{{ asset('images/icons/logo.jpg') }}" alt="Nama Logo" style="height: 25px;">
                </a>
            </div>

            {{--<div class="collapse navbar-collapse pull-left" id="navbar-collapse-3">--}}
                {{--<!-- Module Shop -->--}}
                {{--<div id="shop-search2" class="module module-search2 pull-left">--}}
                    {{--<div class="module-icon search-icon color-black">--}}
                        {{--<i class="fa fa-search"></i>--}}
                        {{--<span class="title">search</span>--}}
                        {{--<a id="shop-click" class="pointer">SHOP</a>--}}
                    {{--</div>--}}
                    {{--<div id="shop-fullscreen" class="module-content module-fullscreen module--search2-box">--}}
                        {{--<div class="pos-vertical-center">--}}
                            {{--<div class="container">--}}
                                {{--<div class="row">--}}
                                    {{--<div class="col-xs-12 col-sm-12 center">--}}
                                        {{--<a href="{{route('product.list')}}"><H4 class="header-menu">SHOP ALL</H4></a>--}}
                                        {{--<a href="/product-list?category=1"><H4 class="header-menu">BAGS & TOTES</H4></a>--}}
                                        {{--<a href="/product-list?category=2"><H4 class="header-menu">WALLETS</H4></a>--}}
                                        {{--<a href="/product-list?category=3"><H4 class="header-menu">CARD HOLDERS</H4></a>--}}
                                        {{--<a href="/product-list?category=4"><H4 class="header-menu">POUCHES</H4></a>--}}
                                        {{--<a href="/product-list?category=5"><H4 class="header-menu">PHONE CASES</H4></a>--}}
                                    {{--</div><!-- .col-md-8 end -->--}}
                                {{--</div><!-- .row end -->--}}
                            {{--</div><!-- .container end -->--}}
                        {{--</div>--}}
                        {{--<a class="module-cancel pointer"><i class="fa fa-close"></i></a>--}}
                    {{--</div>--}}
                {{--</div><!-- .module-Shop end -->--}}

                {{--<!-- Module About -->--}}
                {{--<div class="module module-search2 pull-left">--}}
                    {{--<div class="module-icon search-icon color-black">--}}
                        {{--<a href="{{route('about.us')}}">ABOUT</a>--}}
                    {{--</div>--}}
                {{--</div><!-- .module-About end -->--}}

                {{--<!-- Module About -->--}}
                {{--<div class="module module-search2 pull-left">--}}
                    {{--<div class="module-icon search-icon color-black">--}}
                        {{--<a href="{{route('contact-form')}}">CONTACT</a>--}}
                    {{--</div>--}}
                {{--</div><!-- .module-About end -->--}}

                {{--<!-- Module Contact -->--}}
                {{--<div class="module module-search2 pull-left">--}}
                    {{--<div class="module-icon search-icon color-black">--}}
                        {{--<i class="fa fa-search"></i>--}}
                        {{--<span class="title">search</span>--}}
                        {{--<a href="{{route('contact-form')}}">CONTACT</a>--}}
                        {{--<a class="pointer">CONTACT</a>--}}
                    {{--</div>--}}
                    {{--<div class="module-content module-fullscreen module--search2-box">--}}
                        {{--<div class="pos-vertical-center">--}}
                            {{--<div class="container">--}}
                                {{--<div class="row">--}}
                                    {{--<div class="col-xs-12 col-sm-12" style="padding: 0 1% 0 7%;">--}}
                                        {{--<h2>Contact</h2>--}}
                                        {{--<hr style="height:1px;border:none;color:#333;background-color:#333;" />--}}
                                        {{--<br/>--}}
                                        {{--{!! Form::open(array('action' => 'Frontend\HomeController@contact', 'id'=>'form-contact', 'class'=>'form-search2', 'method' => 'POST', 'role' => 'form', 'enctype' => 'multipart/form-data', 'novalidate')) !!}--}}

                                        {{--<input type="text" class="form-control" name="name" id="name" placeholder="NAME" required/>--}}
                                        {{--<input type="email" class="form-control" name="email" id="email" placeholder="EMAIL ADDRESS" required/>--}}
                                        {{--<input type="text" class="form-control" name="order" id="order" placeholder="ORDER NUMBER (IF APPLICABLE)"/>--}}
                                        {{--<textarea class="form-control" name="message" id="message" rows="2" placeholder="MESSAGE" required></textarea>--}}

                                        {{--<div style="text-align: center;">--}}
                                            {{--<button type="submit" class="btn btn--secondary btn--bordered">SEND</button>--}}
                                        {{--</div>--}}

                                        {{--{!! Form::close() !!}--}}
                                    {{--</div><!-- .col-md-8 end -->--}}
                                {{--</div><!-- .row end -->--}}
                            {{--</div><!-- .container end -->--}}
                        {{--</div>--}}
                        {{--<a class="module-cancel pointer"><i class="fa fa-close"></i></a>--}}
                    {{--</div>--}}
                {{--</div><!-- .module-Contact end -->--}}

                {{--<!-- Module Search -->--}}
                {{--<div id="search-search2" class="module module-search pull-left">--}}
                    {{--<div class="module-icon search-icon color-black">--}}
                        {{--<i class="fa fa-search"></i>--}}
                        {{--<span class="title">search</span>--}}
                        {{--<a id="search-click" class="pointer">SEARCH</a>--}}
                    {{--</div>--}}
                    {{--<div class="module-content module-fullscreen module--search-box">--}}
                        {{--<div class="pos-vertical-center">--}}
                            {{--<div class="container">--}}
                                {{--<div class="row">--}}
                                    {{--<div class="col-xs-12 col-sm-12 center">--}}
                                        {{--<h4>Search</h4>--}}

                                        {{--{!! Form::open(array('action' => 'Frontend\ProductController@search', 'id'=>'form-search', 'class'=>'form-search', 'method' => 'POST', 'role' => 'form', 'enctype' => 'multipart/form-data', 'novalidate')) !!}--}}

                                        {{--<input type="text" class="form-control" id="search-text" name="search-text">--}}
                                        {{--<button class="btn search-button contact-button" type="button" onClick="empty()" >--}}
                                            {{--<i class="fa fa-long-arrow-right"></i>--}}
                                        {{--</button>--}}

                                        {{--{!! Form::close() !!}--}}
                                    {{--</div><!-- .col-md-8 end -->--}}
                                {{--</div><!-- .row end -->--}}
                            {{--</div><!-- .container end -->--}}
                        {{--</div>--}}
                        {{--<a class="module-cancel pointer"><i class="fa fa-close"></i></a>--}}
                    {{--</div>--}}
                {{--</div><!-- .module-search end -->--}}

            {{--</div>--}}
        </div>
        <!-- .container end  -->
    </nav>

</header>


@section('scripts-footer-header')
    <script>
        $("#module-menu").on('click', (function() {
            // var windowHeight = $(window).height();
            var windowHeight = window.innerHeight ? window.innerHeight : $(window).height();
            // alert(windowHeight);
            $('#menu-fullscreen').css('height', windowHeight + 200);
            $('#menu-fullscreen').show();
            // $('#shop-fullscreen').css('height', windowHeight);
            // $('#navbar-collapse-2').height(windowHeight);
        }));
        $("#shop-click").on('click', (function() {
            // var windowHeight = $(window).height();
            var windowHeight = window.innerHeight ? window.innerHeight : $(window).height();
            // alert(windowHeight);
            $('#menu-fullscreen').css('height', windowHeight  + 200);
            $('#menu-fullscreen').show();
            // $('#shop-fullscreen').css('height', windowHeight);
            // $('#navbar-collapse-2').height(windowHeight);
        }));
        $("#search-click").on('click', (function() {
            var windowHeight = window.innerHeight ? window.innerHeight : $(window).height();
            $('#search-search2').css('height', windowHeight  + 200);
        }));
        $(".module-cancel-menu").on('click', (function() {
            $('#menu-fullscreen').hide();
        }));
        function empty() {
            var x;
            x = document.getElementById("search-text").value;
            if (x == "" || x == null) {
            }
            else{
                $('#form-search').submit();
            }
        }
        $('#subscription_form').on('submit', function(e) {
            e.preventDefault();
            var name = $('#subscribe_name').val();
            var email = $('#subscribe_email').val();
            // alert(name);

            $.ajax({
                type: 'POST',
                url: '{{ route('newsletter') }}',
                datatype : "application/json",
                data: {
                    '_token': '{{ csrf_token() }}',
                    'name': name,
                    'email': email
                }, // no need to stringify
                success: function (result) {
                    $('#subscribe_success_message').slideDown(500);
                }
            });
        });
    </script>
@endsection
