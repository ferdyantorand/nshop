@extends('layouts.frontend')

@section('pageTitle', 'COVID UPDATE | NAMA')
@section('content')


    <!-- Product #1
    ============================================= -->
    <section id="testimonial1" class="testimonial testimonial-boxed testimonial-1 bg-white pt-0 pb-0">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-offset-2 col-md-8" style="padding-top: 10%;padding-bottom: 10%;text-align: justify;">
                    <div class="center">
                        <h2>COVID-19 UPDATE</h2>
                    </div>
                    <p class="font-16">
                        Following the new regulation from the Governor of Jakarta, we are implementing
                        large-scale social restrictions (PSBB) starting from April 10 to April 24.
                        We understand these steps are taken to further flatten the curve and curb the spread.
                    </p>
                    <p class="font-16">
                        However, that means that our workshop will also be closed for this period.
                        We are still taking orders but shipping will be delayed.
                        Therefore, orders starting from today will be processed and shipped once our workshop is up and running.
                    </p>
                    <p class="font-16">
                        We love and thank you for your understanding and again,
                        let’s do our part to help flatten the curve and staying home,
                        so we can soon rejoin with our loved ones ♡.
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
@endsection
