@extends('layouts.frontend')
@section('pageTitle', 'NAMA | Personalized Leather Goods')
@section('content')


    <!-- Slider #1
============================================= -->
    {{--<section id="slider" class="carousel slider slider-shop slider-dots slider-navs" data-slide="1" data-slide-rs="1" data-autoplay="false" data-nav="false" data-dots="true" data-space="0" data-loop="true" data-speed="800">--}}
    <section id="slider" class="carousel slider slider-shop slider-dots slider-navs" data-slide="1" data-slide-rs="1" data-autoplay="false" data-nav="false" data-dots="false" data-space="0" data-loop="false" data-speed="800">

        <!-- Slide #1 -->
        <div class="slide--item">
            {{--<div class="bg-section">--}}
                {{--<img src="{{ asset('images/sliders/slide-bg/banner-1.jpg') }}" alt="Background"/>--}}
            {{--</div>--}}
            <div class="pos-vertical-center">
                <div class="col-xs-12 col-sm-12 col-md-6">
{{--                    <div class="slide--headline center"><span style="text-transform: lowercase !important;">--}}
{{--                            all items</span> <br> <span style="font-size:60px!important;">20% OFF</span>--}}
{{--                        <p class="font-16" style="margin-top:20px;margin-bottom:-2px;">--}}
{{--                            With Code--}}
{{--                        </p>--}}
{{--                        <p class="font-16">--}}
{{--                            <span style="font-weight: bold;text-transform: lowercase !important;">#Backtooffice</span>--}}
{{--                        </p>--}}
{{--                        <a class="btn btn--secondary btn--bordered" href="{{route('product.list')}}" style="width: 220px;">SHOP COLLECTION</a>--}}
{{--                    </div>--}}
                    <div class="slide--headline center" >
                        <span style="text-transform: lowercase !important;">new collection</span> <br> MINI REVERSIBLE TOTE<br><br>
                        <a class="btn btn--secondary btn--bordered" href="{{route('product.list')}}" style="width: 220px;text-transform: lowercase !important;">shop collection</a>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-6 slider-home slider-home-banner"
                     style="background-image: url('{{asset('images/Links/nama-home-16-jun-2020-1-rev-conv.jpg')}}'); margin-top: -100px;">
                    {{--<div class="bg-section">--}}
                    {{--<img src="{{ asset('images/sliders/slide-bg/banner-1.jpg') }}" alt="Background"/>--}}
                    {{--</div>--}}
                </div>
            </div>
        </div><!-- .slide-item end -->

        <!-- Slide #2 -->
        {{--<div class="slide--item" style="display:none;">--}}
            {{--<div class="bg-section">--}}
            {{--<img src="{{ asset('images/sliders/slide-bg/banner-1.jpg') }}" alt="Background"/>--}}
            {{--</div>--}}
            {{--<div class="pos-vertical-center">--}}
                {{--<div class="col-xs-12 col-sm-12 col-md-6">--}}
                    {{--<div class="slide--headline center">--}}
                        {{--Sustainable <br> Leather <br> Accessories<br>--}}
                        {{--<a class="btn btn--secondary btn--bordered" href="{{route('product.list')}}" style="width: 220px;">SHOP COLLECTION</a>--}}
                    {{--</div>--}}
                {{--</div>--}}
                {{--<div class="col-xs-12 col-sm-12 col-md-6 slider-home" style="background-image: url('{{asset('images/Links/banner-2.jpg')}}')">--}}
                    {{--<div class="bg-section">--}}
                    {{--<img src="{{ asset('images/sliders/slide-bg/banner-1.jpg') }}" alt="Background"/>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div><!-- .slide-item end -->--}}

        <!-- Slide #2 -->
        {{--<div class="slide--item">--}}
            {{--<div class="bg-section">--}}
            {{--<img src="{{ asset('images/sliders/slide-bg/banner-1.jpg') }}" alt="Background"/>--}}
            {{--</div>--}}
            {{--<div class="pos-vertical-center">--}}
                {{--<div class="col-xs-12 col-sm-12 col-md-6">--}}
                    {{--<div class="slide--headline center">--}}
                        {{--The Perfect Gift <br> or Everyday <br> Companion<br>--}}
                        {{--<a class="btn btn--secondary btn--bordered" href="{{route('product.list')}}" style="width: 220px;">LET'S SHOP</a>--}}
                    {{--</div>--}}
                {{--</div>--}}
                {{--<div class="col-xs-12 col-sm-12 col-md-6 slider-home" style="background-image: url('{{asset('images/Links/banner-3.jpg')}}')">--}}
                    {{--<div class="bg-section">--}}
                    {{--<img src="{{ asset('images/sliders/slide-bg/banner-1.jpg') }}" alt="Background"/>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div><!-- .slide-item end -->--}}

    </section>
    <a href="#testimonial1" class="down-button"><i class="fa fa-long-arrow-down"></i></a>
    <!-- Info #5
        ============================================= -->
{{--    <section id="cover5" class="section cover-5 mtop-100 pt-0 pb-0">--}}
{{--        <div class="container-fluid">--}}
{{--            <div class="row comm-height">--}}
{{--                <div class="col-xs-12 col-sm-12 col-md-12 bg-pastel center" style="padding:5%">--}}
{{--                    <h4>COVID-19 UPDATE</h4>--}}
{{--                    <a href="{{route('others', ['type'=>'Covid'])}}" class="font-16" style="width: 220px;">See More</a>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--            <!-- .row end -->--}}
{{--        </div>--}}
{{--        <!-- .container end -->--}}
{{--    </section>--}}
    <!-- #Info end -->

    <!-- Product #1
    ============================================= -->
    <section id="testimonial1" class="testimonial testimonial-boxed testimonial-1 bg-white">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 center">
                    {{--<h2>Spring Favorites</h2>--}}
                    <p class="font-16" style="margin-top:8%; margin-bottom:10%;">
                        REDEFINE SIMPLE, EVERYDAY LUXURY WITH NAMA'S PRACTICAL,<BR>
                        TIMELESS AND HIGH DISTINCTION LEATHER GOODS.
                    </p>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div id="testimonial-carousel" class="carousel carousel-dots" data-slide="3" data-slide-rs="1" data-autoplay="false" data-nav="true" data-dots="false" data-space="0" data-loop="true" data-speed="800" data-center="false">
                        <!-- Product -->
                        @foreach($products as $product)
                            @php($link = route('product.detail', ['product'=>$product->slug] ))
                            @php($productImage = $product->product_images->where('is_main_image', 1)->first())
                            <div class="testimonial-panel product-item" style="padding:0 !important;">
                                <div class="product--img">
                                    <img class="product-img-home" src="{{ asset('storage/products/'.$productImage->path) }}" loading="lazy" alt="Product" style="max-height: 300px; width: 100%;"/>
                                    <div class="product--hover">
                                        <div class="product--action">
                                            <a class="btn btn--secondary btn--bordered" href="{{$link}}">VIEW</a>
                                        </div>
                                    </div><!-- .product-overlay end -->
                                </div><!-- .product-img end -->
                                <div class="product--content">
                                    <div class="product--title" style="height: 50px;">
                                        <h3><a href="{{$link}}">{{$product->name}}</a></h3>
                                    </div><!-- .product-title end -->
                                    <div class="product--price" style="padding-bottom:30px !important;">
                                        <span class="family-sans" style="font-weight: 400;">{{env('KURS_IDR')}} {{$product->price_string}}</span>
                                    </div><!-- .product-price end -->
                                </div><!-- .product-bio end -->
                            </div>
                        @endforeach
                    </div>
                </div><!-- .col-md-12 end -->
            </div><!-- .row end -->
        </div><!-- .container end -->
    </section>
    <!-- #Product end -->


    <!-- Info #5
    ============================================= -->
    <section id="cover5" class="section cover-5 mtop-100 pt-0 pb-0">
        <div class="container-fluid">
            <div class="row comm-height" style="height:610px;">
                <div class="col-xs-12 col-sm-12 col-md-6 pr-0 pl-0 height-custom">
                    <div class="bg-overlay">
                        <div class="bg-section">
                            <img src="{{asset('/images/Links/home-2-nov.jpg')}}" loading="lazy" alt="Background"/>
                        </div>
                    </div>
                </div><!-- .col-md-6 end-->
                <div class="col-xs-12 col-sm-12 col-md-6 col-content bg-pastel center height-custom">
                    <h3 class="marg-top-min" style="font-size: 32px;">Customize For A Tailored & Timeless Accessory</h3>
                    <a class="btn btn--secondary btn--bordered" href="{{route('product.list')}}" style="width: 220px;">LET'S GO SHOPPING</a>
                </div>
            </div>
            <!-- .row end -->
        </div>
        <!-- .container end -->
    </section>
    <!-- #Info end -->

    <!-- SnapWidget -->
    <div class="hidden-sm hidden-xs" style="padding:3%;">
        <!-- SnapWidget -->
        <script src="https://snapwidget.com/js/snapwidget.js"></script>
        <iframe src="https://snapwidget.com/embed/690324" class="snapwidget-widget" allowtransparency="true" frameborder="0" scrolling="no" style="border:none; overflow:hidden; width:100%; "></iframe>
        {{--<div style="font:10px/14px 'Roboto','Helvetica Neue',Arial,Helvetica,sans-serif;font-weight:400;width:100%;text-align:right"><a href="https://snapwidget.com" style="color:#777;text-decoration:none;">SnapWidget · Instagram Widget</a></div>--}}
    </div>
    <div class="hidden-lg hidden-md" style="padding:3%;">
        <!-- SnapWidget -->
        <script src="https://snapwidget.com/js/snapwidget.js"></script>
        <iframe src="https://snapwidget.com/embed/682408" class="snapwidget-widget" allowtransparency="true" frameborder="0" scrolling="no" style="border:none; overflow:hidden; width:100%; "></iframe>
        {{--<div style="font:10px/14px 'Roboto','Helvetica Neue',Arial,Helvetica,sans-serif;font-weight:400;width:100%;text-align:right"><a href="https://snapwidget.com" style="color:#777;text-decoration:none;">SnapWidget · Instagram Widget</a></div>--}}
    </div>

    <div class="modal" id="first_modal">
        <div class="modal-dialog">
            <div class="modal-content bg_popup">

                <!-- Modal Header -->
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>


                <!-- Modal body -->
                <div class="modal-body">
                </div>
            </div>
        </div>
    </div>
@endsection
@section('styles')
    <style>
        {{--body{ height: auto; }--}}
        {{--* { cursor: url( '{{asset('/images/Links/cursor.cur')}}' ), default; }--}}

        /* pop up css */

        .modal-dialog {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) !important;
            /*height: auto;*/
            /*margin-top: 50%;*/
            /*display: flex!important;*/
            /*align-items: center!important;*/
            /*flex-grow: 1;*/
        }

        .modal-header{
            border-bottom: 0 !important;
        }

        .bg_popup{
            background-image: url('{{ asset('images/Links/PopUp_191128.jpg') }}');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            height: 240px;
            width: 95%;
        }
        @media (max-width: 578px) {
            .modal-dialog{
                width: 350px;
            }
        }
        @media (min-width: 576px) {
            .bg_popup{
                height: 370px;
                width: 600px;
            }
        }

        @media (min-width: 768px) {
            .height-custom{
                height:600px !important;
            }
            .marg-top-min{
                /*margin-top:-125px;*/
            }
            /*sup{*/
            /*    top:-1.0em;*/
            /*    font-size:40%;*/
            /*}*/

        }


        @media (min-width: 992px) {

        }


        @media (min-width: 1369px) {

        }

        @media (min-width: 1900px){
            .marg-top-min{
                margin-top:45px;
            }
        }
    </style>
@endsection

@section('scripts')
{{--    for christmas snow efect--}}
{{--    <script src="{{ asset('js/frontend/jquery.flurry.js')}}"></script>--}}
{{--    <script>--}}

{{--        $( document ).ready( function() {--}}
{{--            try {--}}
{{--                // $('body').flurry('destroy');--}}
{{--            }--}}
{{--            catch(err) {--}}
{{--            }--}}
{{--            finally {--}}
{{--                $('#second_modal').modal();--}}

{{--                // $('body').flurry({--}}
{{--                //     character: '❅❆',--}}
{{--                //     height: $( document ).height() + 600,--}}
{{--                //     speed: 40400,--}}
{{--                //     wind: 300,--}}
{{--                //     windVariance: 320,--}}
{{--                //     frequency: 40,--}}
{{--                //     large: 20,--}}
{{--                //     small: 2--}}
{{--                // });--}}
{{--            }--}}
{{--        });--}}
{{--    </script>--}}
@endsection


