@extends('layouts.frontend')

@section('pageTitle', 'About Us | NAMA')
@section('content')


    <!-- Info #1
============================================= -->
    <section id="cover5" class="section cover-5 mtop-100 pt-0 pb-0">
        <div class="container-fluid">
            <div class="row comm-height">
                <div class="col-xs-12 col-sm-12 col-md-6 col-content-about-top bg-pastel center">
                    <h3 class="">Timeless essentials <br>with a twist of <br>refined luxury</h3>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-6 pr-0 pl-0">
                    <div class="bg-overlay">
                        <div class="bg-section">
                            <img src="{{asset('/images/Links/AHA12.jpg')}}" alt="Background" loading="lazy"/>
                        </div>
                    </div>
                </div><!-- .col-md-6 end-->
            </div>
            <!-- .row end -->
        </div>
        <!-- .container end -->
    </section>
    <!-- #Info end -->
    {{--<a href="#testimonial1" class="down-button"><i class="fa fa-long-arrow-down"></i></a>--}}

    <!-- Product #1
    ============================================= -->
    <section id="testimonial1" class="testimonial testimonial-boxed testimonial-1 bg-white pt-0 pb-0">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-offset-2 col-md-8" style="padding-top: 10%;padding-bottom: 10%;text-align: justify;">
                    <div class="center">
                        <h2>OUR STORY</h2>
                    </div>
                    <p class="font-16">
                        Nama is Indonesian for the word name; “a word or set of words by which a person, animal, place, or thing is known, addressed, or referred to.” We believe a name is not only a way to identify someone or something but it also what defines how others see us—our individuality.
                    </p>
                    <p class="font-16">
                        Nama was established by two best friends who are passionate about all things personalized. They met in Los Angeles and spent their days sipping coffee and brunching on Melrose Ave surrounded by the most coveted fashion in the world. They were not looking forward to return and leave this fashion heaven until they realized they could bring a slice of that heaven home. They are firm believers that everyone is special and their essential accessories should reflect that. Consequently, Nama was born. They want to make a line of staple everyday accessories made with exceptional quality leather, with a touch of personal customization.
                    </p>
                    <p class="font-16">
                        Nama’s designs are driven from minimalistic elements, with a twist of refined luxury. Our mission is to create timeless, elegant and functional pieces with exceptional quality that complements today’s demanding world.
                    </p>
                </div>
                {{--<div class="col-xs-12 col-sm-12 col-md-12">--}}
                    {{--<div id="testimonial-carousel" class="carousel carousel-dots" data-slide="3" data-slide-rs="1" data-autoplay="false" data-nav="true" data-dots="false" data-space="0" data-loop="true" data-speed="800" data-center="true">--}}
                        {{--<!-- Product -->--}}
                        {{--@foreach($products as $product)--}}
                            {{--@php($link = route('product.detail', ['product'=>$product->slug] ))--}}
                            {{--@php($productImage = $product->product_images->where('is_main_image', 1)->first())--}}
                            {{--<div class="testimonial-panel product-item">--}}
                                {{--<div class="">--}}
                                    {{--<img src="{{ asset('storage/products/'.$productImage->path) }}" alt="Product" style="max-height: 300px; width: auto"/>--}}
                                {{--</div><!-- .product-img end -->--}}
                            {{--</div>--}}
                        {{--@endforeach--}}
                    {{--</div>--}}
                {{--</div><!-- .col-md-12 end -->--}}
            </div><!-- .row end -->
        </div><!-- .container end -->
    </section>
    <!-- #Product end -->


    <!-- Info #5
    ============================================= -->
    <section id="cover5" class="section cover-5 mtop-100 pt-0 pb-0">
        <div class="container-fluid">
            <div class="row comm-height">
                <div class="col-xs-12 col-sm-12 col-md-6 pr-0 pl-0">
                    <div class="bg-overlay">
                        <div class="bg-section">
                            <img src="{{asset('/images/Links/home-3.jpg')}}" alt="Background" loading="lazy"/>
                        </div>
                    </div>
                </div><!-- .col-md-6 end-->
                <div class="col-xs-12 col-sm-12 col-md-6 col-content-about-bottom bg-windrift-blue center">
                    <h3 class="">Customized Monogramming</h3>
                    {{--<p class="font-16" style="text-transform: uppercase;">--}}
                        {{--At nama, we believe in the beauty of adding a personal touch that's distinctively yours.--}}
                        {{--<BR><BR>--}}
                        {{--With our top of the line hot stamp machine and 100% imported genuine leather, we will beautifully embellish your products with one of a kind customization.--}}
                    {{--</p>--}}
                </div>
            </div>
            <!-- .row end -->
        </div>
        <!-- .container end -->
    </section>
    <!-- #Info end -->

    <!-- Product #1
    ============================================= -->
    <section id="testimonial1" class="testimonial testimonial-boxed testimonial-1 bg-white pt-0 pb-0">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-offset-2 col-md-8" style="padding-top: 10%;padding-bottom: 10%;text-align: justify">
                    <div class="center">
                        <h2>Personalization</h2>
                    </div>
                    <div class="center">
                        <h4 style="margin: 0 0 20px;font-size: 20px;">Customized Monogramming</h4>
                        {{--<p class="font-16">CUSTOMIZED MONOGRAMMING</p>--}}
                        <p class="font-16">
                            At nama, we believe in the beauty of adding a personal touch that's distinctively yours.
                            <BR><BR>
                            With our top of the line hot stamp machine and 100% imported genuine leather, we will beautifully embellish your products with one of a kind customization.
                        </p>
                    </div>
                    {{--<div class="center">--}}
                        {{--<h4>Techniques & Materials</h4>--}}
                    {{--</div>--}}
                    {{--<p class="font-16">--}}
                        {{--Redefine simple everyday luxury with nama/s practical,--}}
                        {{--timeless and high distiction personalized leather goods.<BR>--}}
                        {{--Enjoy chic and bespoke accesories with the knowledge that they--}}
                        {{--have been made with utmost care and quality.--}}
                    {{--</p>--}}
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div id="testimonial-carousel" class="carousel carousel-dots" data-slide="3" data-slide-rs="1" data-autoplay="false" data-nav="true" data-dots="false" data-space="0" data-loop="true" data-speed="800" data-center="false">
                        <!-- Product -->
                        @foreach($products as $product)
                            @php($link = route('product.detail', ['product'=>$product->slug] ))
                            @php($productImage = $product->product_images->where('is_main_image', 1)->first())
                            <div class="testimonial-panel product-item">
                                <div class="product--img">
                                    <img src="{{ asset('storage/products/'.$productImage->path) }}" alt="Product" loading="lazy" style="max-height: 300px; width: 100%"/>
                                </div><!-- .product-img end -->
                            </div>
                        @endforeach
                    </div>
                </div><!-- .col-md-12 end -->
            </div><!-- .row end -->
        </div><!-- .container end -->
    </section>
    <!-- #Product end -->
@endsection
