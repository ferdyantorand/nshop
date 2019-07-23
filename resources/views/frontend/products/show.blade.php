@extends('layouts.frontend')

@section('pageTitle', $product->name.' | NAMA')
@section('content')

    <!-- Cover #5
    ============================================= -->
    <section id="cover5" class="section mtop-100 pt-0 pb-0">
        <div class="container-fluid">
            <div class="row" style="background-color: white;">
                <div class="col-xs-12 col-sm-12 col-md-6 center slider-image-left" style="padding-left: 8%;">
                    <section id="slider-product" class="carousel slider slider-shop slider-dots"
                             data-slide="1" data-slide-rs="1" data-autoplay="false" data-nav="true" data-dots="true"
                             data-space="0" data-loop="true" data-speed="800"
                             style="height:500px;">
                        @php($productImages = $product->product_images->where('is_thumbnail', 0))
                        @php($productMainImages = $product->product_images->where('is_main_image', 1)->first())
                        @php($productThumbnailImage = $product->product_images->where('is_thumbnail', 1)->first())
                        @foreach($productImages as $images)
                            <!-- Slide -->
                                <div class="slide--item">
                                    {{--<div class="bg-section">--}}
                                    {{--<img src="{{ asset('images/sliders/slide-bg/banner-1.jpg') }}" alt="Background"/>--}}
                                    {{--</div>--}}
                                    <div class="pos-vertical-center">
                                        <div class="col-xs-12 col-sm-12 col-md-12 slider-home" style="background-image: url('{{ asset('storage/products/'.$images->path) }}');background-size: contain !important;height:500px;">
                                            {{--<div class="bg-section">--}}
                                            {{--<img src="{{ asset('images/sliders/slide-bg/banner-1.jpg') }}" alt="Background"/>--}}
                                            {{--</div>--}}
                                        </div>
                                    </div>
                                </div><!-- .slide-item end -->
                        @endforeach
                    </section>
                    <div id="custom-section" style="display: none; margin-left:-50px;padding-top:4%;">
                        <canvas id="myCanvas" width="600" height="600"></canvas>
                        <canvas id="myCanvasMobile" width="300" height="300" style="display:none;"></canvas>
                    </div>
                </div><!-- .col-md-8 end -->
                <div class="col-xs-12 col-sm-12 col-md-6 slider-image-right" style="padding: 5% 8% 0 8%;">
                    <div class="row" style="margin-bottom: 28px;">
                        <div class="col-md-12">
                            <h2 style="margin: 0 0 18px;">{{$product->name}}</h2>
                            <h5 style="text-transform: capitalize;">{{env('KURS_IDR')}} {{$product->price_string}}</h5>
                            <p class="font-16" style="text-align: justify">
                                {{$product->style_notes}}
                            </p>
                        </div>
                    </div>
                    {!! Form::open(array('action' => 'Frontend\CartController@addCart', 'id'=>'form-search', 'method' => 'POST', 'role' => 'form', 'onkeypress' => 'return event.keyCode != 13;', 'novalidate')) !!}

                    <div class="row">
                        <div class="col-md-12">
                            <input type="hidden" id="slug" name="slug" value="{{$product->slug}}">
                            <input type="hidden" id="position_x" value="{{$product->product_positions[0]->pos_x}}">
                            <input type="hidden" id="position_y" value="{{$product->product_positions[0]->pos_y}}">
                            <div class="row">
                                <div class="col-xs-6 col-sm-6 col-md-6 text--left">
                                    <H4>COLOR <span id="color-thumbnail" style="font-size: 12px">({{$thumbnailColour}})</span></H4>
                                </div>
                                <div class="col-xs-6 col-sm-6 col-md-6 text--right">
                                    {{--<select id="select-colour" class="minimal family-sans" data-width="auto"--}}
                                            {{--onchange="ChangeColour()" style="width: 75%;font-size: 14px !important;text-align: right;">--}}
                                        {{--@foreach($otherProductColour as $colour)--}}
                                            {{--@php($colorThumbnailImages = $colour->product_images->where('is_thumbnail', 1)->first())--}}

                                            {{--@if($productThumbnailImage->path == $colorThumbnailImages->path)--}}
                                                {{--<option class="family-sans" value="{{$colour->slug}}" selected style="font-size: 14px !important;">{{$colour->colour}}</option>--}}
                                            {{--@else--}}
                                                {{--<option class="family-sans" value="{{$colour->slug}}" style="font-size: 14px !important;">{{$colour->colour}}</option>--}}
                                            {{--@endif--}}
                                        {{--@endforeach--}}
                                    {{--</select>--}}

                                    <div id="category_list" class="field radio_field">
                                        @foreach($otherProductColour as $colour)
                                            @php($colorThumbnailImages = $colour->product_images->where('is_thumbnail', 1)->first())
                                            @if(!empty($colorThumbnailImages))
                                                @if($productThumbnailImage->path == $colorThumbnailImages->path)
                                                    <label style="padding-right: 3%;cursor: pointer;">
                                                        <input type="radio" name="color-thumbnail" onchange="ChangeColourThumbnail(this)" value="{{$colour->slug}}" checked/>
                                                        <img src="{{ asset('storage/products/'.$colorThumbnailImages->path) }}" style="width: 40px; height:40px">
                                                    </label>
                                                @else

                                                    <label style="padding-right: 3%;cursor: pointer;">
                                                        <input type="radio" name="color-thumbnail" onchange="ChangeColourThumbnail(this)" value="{{$colour->slug}}"/>
                                                        <img src="{{ asset('storage/products/'.$colorThumbnailImages->path) }}" style="width: 40px; height:40px">
                                                    </label>
                                                @endif
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @php($display='none')
                            @if($product->is_customize == 1)
                                @if($product->category_id != 9)
                                    @php($display='block')
                                    <div class="row">
                                        <div class="col-xs-6 col-sm-6 col-md-6 text--left">
                                            <H4>Customization</H4>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 text--right">
                                            <button type="button" class="btn btn-toggle active" data-toggle="button" aria-pressed="true" onclick="onToggleCustomize();">
                                                <div class="handle"></div>
                                            </button>
                                        </div>
                                    </div>
                                @endif
                            @endif
                            <input type="hidden" id="customize-toggle" name="customize-toggle" value="true" />

                            <div id="customize-section" class="row customize-section" style="display:{{$display}}">
                                <div class="col-md-12 bg-white bg-white-mobile" style="padding-bottom: 25px;">
                                    <p style="font-weight: bold">Enter Text (max 5 characters)

                                        {{--@if(auth()->guard('web')->check())--}}
                                            {{--@if(auth()->guard('web')->user()->email == "yansen626@gmail.com")--}}
                                                {{--<button id="emoji-picker">--}}
                                                    {{--<img src="{{ asset('images/emoji/smiley.png') }}" height="20" width="20">--}}
                                                {{--</button>--}}
                                            {{--@endif--}}
                                        {{--@endif--}}
                                        <button id="emoji-picker">
                                            <img src="{{ asset('images/emoji/smiley.png') }}" height="20" width="20">
                                        </button>
                                    </p>
                                    <input type="hidden" id="emoji_show" value="false">

                                    <form>
                                        {{--<input type="text" class="form-control auto-blur"--}}
                                               {{--name="custom-text" id="custom-text" placeholder="TEXT HERE" maxlength="3"--}}
                                               {{--onfocusout="ChangePosition()" style="text-transform:uppercase" />--}}
                                        <input type="hidden" class="form-control auto-blur"
                                               name="custom-text" id="custom-input-text"
                                               style="text-transform:uppercase" />
                                        <div id="custom-text" class="form-control"
                                             onfocusout="ChangeText()" style="text-transform:uppercase"
                                             contenteditable></div>
                                        <input type="hidden" id="src-list" value="">

                                        <div id="emoji_list" class="field radio_field" style="display: none;padding-bottom: 5%;">
                                            <div class="col-xs-12 col-sm-12 col-md-12 text--right" style="padding-bottom: 5%;">
                                                *Please hit refresh for emoji visualization
                                            </div>
                                            <label style="padding-right: 3%;cursor: pointer;">
                                                <input type="radio" name="emoji"
                                                       onchange="SelectEmoji(this)" value="{{ asset('IMAGES/EMOJI/BUTTERFLY.PNG') }}"/>
                                                <img src='{{ asset('IMAGES/EMOJI/BUTTERFLY.PNG') }}'>
                                            </label>
                                            <label style="padding-right: 3%;cursor: pointer;">
                                                <input type="radio" name="emoji"
                                                       onchange="SelectEmoji(this)" value="{{ asset('IMAGES/EMOJI/CAT.PNG') }}"/>
                                                <img src='{{ asset('IMAGES/EMOJI/CAT.PNG') }}'>
                                            </label>
                                            <label style="padding-right: 3%;cursor: pointer;">
                                                <input type="radio" name="emoji"
                                                       onchange="SelectEmoji(this)" value="{{ asset('IMAGES/EMOJI/CLOVER.PNG') }}"/>
                                                <img src='{{ asset('IMAGES/EMOJI/CLOVER.PNG') }}'>
                                            </label>
                                            <label style="padding-right: 3%;cursor: pointer;">
                                                <input type="radio" name="emoji"
                                                       onchange="SelectEmoji(this)" value="{{ asset('IMAGES/EMOJI/COFFEE.PNG') }}"/>
                                                <img src='{{ asset('IMAGES/EMOJI/COFFEE.PNG') }}'>
                                            </label>
                                            <label style="padding-right: 3%;cursor: pointer;">
                                                <input type="radio" name="emoji"
                                                       onchange="SelectEmoji(this)" value="{{ asset('IMAGES/EMOJI/CROWN.PNG') }}"/>
                                                <img src='{{ asset('IMAGES/EMOJI/CROWN.PNG') }}'>
                                            </label>
                                            <label style="padding-right: 3%;cursor: pointer;">
                                                <input type="radio" name="emoji"
                                                       onchange="SelectEmoji(this)" value="{{ asset('IMAGES/EMOJI/CUPCAKE.PNG') }}"/>
                                                <img src='{{ asset('IMAGES/EMOJI/CUPCAKE.PNG') }}'>
                                            </label>
                                            <label style="padding-right: 3%;cursor: pointer;">
                                                <input type="radio" name="emoji"
                                                       onchange="SelectEmoji(this)" value="{{ asset('IMAGES/EMOJI/DOG.PNG') }}"/>
                                                <img src='{{ asset('IMAGES/EMOJI/DOG.PNG') }}'>
                                            </label>
                                            <label style="padding-right: 3%;cursor: pointer;">
                                                <input type="radio" name="emoji"
                                                       onchange="SelectEmoji(this)" value="{{ asset('IMAGES/EMOJI/GIFT.PNG') }}"/>
                                                <img src='{{ asset('IMAGES/EMOJI/GIFT.PNG') }}'>
                                            </label>
                                            <label style="padding-right: 3%;cursor: pointer;">
                                                <input type="radio" name="emoji"
                                                       onchange="SelectEmoji(this)" value="{{ asset('IMAGES/EMOJI/ICECREAM.PNG') }}"/>
                                                <img src='{{ asset('IMAGES/EMOJI/ICECREAM.PNG') }}'>
                                            </label>
                                            <label style="padding-right: 3%;cursor: pointer;">
                                                <input type="radio" name="emoji"
                                                       onchange="SelectEmoji(this)" value="{{ asset('IMAGES/EMOJI/LIKE.PNG') }}"/>
                                                <img src='{{ asset('IMAGES/EMOJI/LIKE.PNG') }}'>
                                            </label>
                                            <label style="padding-right: 3%;cursor: pointer;">
                                                <input type="radio" name="emoji"
                                                       onchange="SelectEmoji(this)" value="{{ asset('IMAGES/EMOJI/LIPSTICK.PNG') }}"/>
                                                <img src='{{ asset('IMAGES/EMOJI/LIPSTICK.PNG') }}'>
                                            </label>
                                            <label style="padding-right: 3%;cursor: pointer;">
                                                <input type="radio" name="emoji"
                                                       onchange="SelectEmoji(this)" value="{{ asset('IMAGES/EMOJI/LOVESTRUCK.PNG') }}"/>
                                                <img src='{{ asset('IMAGES/EMOJI/LOVESTRUCK.PNG') }}'>
                                            </label>
                                            <label style="padding-right: 3%;cursor: pointer;">
                                                <input type="radio" name="emoji"
                                                       onchange="SelectEmoji(this)" value="{{ asset('IMAGES/EMOJI/MONEY.PNG') }}"/>
                                                <img src='{{ asset('IMAGES/EMOJI/MONEY.PNG') }}'>
                                            </label>
                                            <label style="padding-right: 3%;cursor: pointer;">
                                                <input type="radio" name="emoji"
                                                       onchange="SelectEmoji(this)" value="{{ asset('IMAGES/EMOJI/PALMT.PNG') }}"/>
                                                <img src='{{ asset('IMAGES/EMOJI/PALMT.PNG') }}'>
                                            </label>
                                            <label style="padding-right: 3%;cursor: pointer;">
                                                <input type="radio" name="emoji"
                                                       onchange="SelectEmoji(this)" value="{{ asset('IMAGES/EMOJI/PIZZA.PNG') }}"/>
                                                <img src='{{ asset('IMAGES/EMOJI/PIZZA.PNG') }}'>
                                            </label>
                                            <label style="padding-right: 3%;cursor: pointer;">
                                                <input type="radio" name="emoji"
                                                       onchange="SelectEmoji(this)" value="{{ asset('IMAGES/EMOJI/PLANE.PNG') }}"/>
                                                <img src='{{ asset('IMAGES/EMOJI/PLANE.PNG') }}'>
                                            </label>
                                            <label style="padding-right: 3%;cursor: pointer;">
                                                <input type="radio" name="emoji"
                                                       onchange="SelectEmoji(this)" value="{{ asset('IMAGES/EMOJI/RAINBOW.PNG') }}"/>
                                                <img src='{{ asset('IMAGES/EMOJI/RAINBOW.PNG') }}'>
                                            </label>
                                            <label style="padding-right: 3%;cursor: pointer;">
                                                <input type="radio" name="emoji"
                                                       onchange="SelectEmoji(this)" value="{{ asset('IMAGES/EMOJI/RIBBON.PNG') }}"/>
                                                <img src='{{ asset('IMAGES/EMOJI/RIBBON.PNG') }}'>
                                            </label>
                                            <label style="padding-right: 3%;cursor: pointer;">
                                                <input type="radio" name="emoji"
                                                       onchange="SelectEmoji(this)" value="{{ asset('IMAGES/EMOJI/STAR.PNG') }}"/>
                                                <img src='{{ asset('IMAGES/EMOJI/STAR.PNG') }}'>
                                            </label>
                                            <label style="padding-right: 3%;cursor: pointer;">
                                                <input type="radio" name="emoji"
                                                       onchange="SelectEmoji(this)" value="{{ asset('IMAGES/EMOJI/SUN.PNG') }}"/>
                                                <img src='{{ asset('IMAGES/EMOJI/SUN.PNG') }}'>
                                            </label>
                                            <label style="padding-right: 3%;cursor: pointer;">
                                                <input type="radio" name="emoji"
                                                       onchange="SelectEmoji(this)" value="{{ asset('IMAGES/EMOJI/SUSHI.PNG') }}"/>
                                                <img src='{{ asset('IMAGES/EMOJI/SUSHI.PNG') }}'>
                                            </label>
                                            <label style="padding-right: 3%;cursor: pointer;">
                                                <input type="radio" name="emoji"
                                                       onchange="SelectEmoji(this)" value="{{ asset('IMAGES/EMOJI/UNICORN.PNG') }}"/>
                                                <img src='{{ asset('IMAGES/EMOJI/UNICORN.PNG') }}'>
                                            </label>
                                            <label style="padding-right: 3%;cursor: pointer;">
                                                <input type="radio" name="emoji"
                                                       onchange="SelectEmoji(this)" value="{{ asset('IMAGES/EMOJI/WINE.PNG') }}"/>
                                                <img src='{{ asset('IMAGES/EMOJI/WINE.PNG') }}'>
                                            </label>
                                        </div>


                                        {{--<div class="col-xs-12 col-sm-12 col-md-4 text-center">--}}
                                        {{--<p style="margin-bottom: 0;margin-left: 11%;">Choose Position</p>--}}
                                        {{--<select class="minimal" data-width="auto"--}}
                                        {{--id="custom-position" name="custom-position"--}}
                                        {{--onchange="ChangeSelectedPosition()" style="width: 130px;">--}}
                                        {{--@foreach($product->product_positions as $position)--}}
                                        {{--@php($value=$position->pos_x."-".$position->pos_y)--}}
                                        {{--<option value="{{$value}}">{{$position->name}}</option>--}}
                                        {{--@endforeach--}}
                                        {{--</select>--}}
                                        {{--</div>--}}
                                        <div class="col-xs-12 col-sm-12 col-md-4 text-center">
                                            <div class="row">
                                                <div style="width: 100%">
                                                    <p style="margin-bottom: 0;font-weight: bold">Choose Position</p>
                                                </div>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-default text-customization-xl btn-color-customize" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <span class="fa fa-angle-down"></span>
                                                        <span id="custom-position-text">{{$product->product_positions[0]->name}}</span>
                                                    </button>
                                                    <input type="hidden" name="custom-position" id="custom-position" value="{{$product->product_positions[0]->pos_x}}-{{$product->product_positions[0]->pos_y}}">
                                                    <input type="hidden" name="custom-position-name" id="custom-position-name" value="{{$product->product_positions[0]->name}}">

                                                    <ul class="dropdown-menu">
                                                        @foreach($product->product_positions as $position)
                                                            @php($value=$position->pos_x."-".$position->pos_y)
                                                            <li style="height: 30px;width: 40px;cursor:pointer;">
                                                                <a onclick="ChangeCustom('{{$position->name}}-{{$value}}', 1)" style="text-transform: uppercase;">
                                                                    {{$position->name}}
                                                                </a>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        {{--<div class="col-xs-12 col-sm-12 col-md-3 text-center">--}}
                                        {{--<p style="margin-bottom: 0;margin-left: 11%;">Font</p>--}}
                                        {{--<select class="minimal" data-width="auto" id="custom-font" name="custom-font" onchange="ChangePosition()">--}}
                                        {{--<option value="Serif">SERIF</option>--}}
                                        {{--<option value="Sans-serif">SAN SERIF</option>--}}

                                        {{--</select>--}}
                                        {{--</div>--}}
                                        {{--<div class="col-xs-12 col-sm-12 col-md-4 text-center">--}}
                                        {{--<p style="margin-bottom: 0;margin-left: 11%;">Choose Color</p>--}}
                                        {{--<select class="selectpicker minimal" data-width="auto" id="custom-color" name="custom-color" onchange="ChangePosition()">--}}
                                        {{--<option value="Silver-C0C0C0">SILVER</option>--}}
                                        {{--<option value="Blind-ffffff">BLIND</option>--}}
                                        {{--</select>--}}
                                        {{--</div>--}}

                                        <div class="col-xs-12 col-sm-12 col-md-4 text-center">
                                            <div class="row">
                                                <div style="width: 100%">
                                                    <p style="margin-bottom: 0;font-weight: bold">
                                                        Choose Color <i class="fa fa-info-circle" data-toggle="modal" data-target="#colorInformation" style="cursor: pointer;"></i>
                                                    </p>
                                                </div>

                                                <div class="btn-group">
                                                    <button type="button" class="btn text-customization-xl btn-default btn-color-customize" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <span class="fa fa-angle-down"></span>
                                                        <span id="custom-color-text">Silver</span>
                                                    </button>
                                                    <input type="hidden" name="custom-color" id="custom-color" value="Silver-C0C0C0">

                                                    <ul class="dropdown-menu">
                                                        <li style="height: 40px;width: 40px;cursor:pointer;">
                                                            <a onclick="ChangeCustom('Silver-C0C0C0', 2)">
                                                                <img src="{{asset('images/icons/Silver.PNG')}}" style="width: 35px; height: 35px;"> SILVER
                                                            </a>
                                                        </li>
                                                        <li style="height: 40px;width: 40px;cursor:pointer;">
                                                            <a onclick="ChangeCustom('Gold-FFD700', 2)">
                                                                <img src="{{asset('images/icons/Gold.PNG')}}" style="width: 35px; height: 35px;"> GOLD
                                                            </a>
                                                        </li>
                                                        {{--<li style="height: 40px;width: 40px;cursor:pointer;">--}}
                                                            {{--<a onclick="ChangeCustom('Blind-ffffff', 2)">--}}
                                                                {{--<img src="{{asset('images/icons/Blind.PNG')}}" style="width: 35px; height: 35px;"> BLIND--}}
                                                            {{--</a>--}}
                                                        {{--</li>--}}
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        {{--<div class="col-xs-12 col-sm-12 col-md-4 text-center">--}}
                                        {{--<p style="margin-bottom: 0;margin-left: 11%;">Choose Size</p>--}}
                                        {{--<select class="selectpicker minimal" data-width="auto" id="custom-size" name="custom-size" onchange="ChangePosition()">--}}
                                        {{--<option value="24 pt-20">24 pt</option>--}}
                                        {{--<option value="36 pt-24">36 pt</option>--}}
                                        {{--</select>--}}
                                        {{--</div>--}}

                                        <div class="col-xs-12 col-sm-12 col-md-4 text-center">
                                            <div class="row">
                                                <div style="width: 100%">
                                                    <p style="margin-bottom: 0;font-weight: bold">Choose Size</p>
                                                </div>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-default text-customization-xl btn-color-customize" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <span class="fa fa-angle-down"></span>
                                                        <span id="custom-size-text">36 pt</span>
                                                    </button>
                                                    <input type="hidden" name="custom-size" id="custom-size" value="36 pt-16">

                                                    <ul class="dropdown-menu text--center">
                                                        <li style="height: 30px;width: 40px;cursor:pointer;">
                                                            <a onclick="ChangeCustom('36 pt-16', 3)">
                                                                36 PT
                                                            </a>
                                                        </li>
                                                        @if($product->category_id == 10)
                                                            <li style="height: 30px;width: 40px;cursor:pointer;">
                                                                <a onclick="ChangeCustom('24 pt-12', 3)">
                                                                    24 PT
                                                                </a>
                                                            </li>
                                                        @endif
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <!-- Accordion #1
                                ============================================= -->
                                    <div id="accordion1">
                                        <div class="accordion accordion-1" id="accordion01">
                                            <!-- Panel 01 -->
                                            <div class="panel">
                                                <div class="panel--heading">
                                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion01" href="#collapse01-1">
                                                        Product Details
                                                    </a>
                                                </div>
                                                <div id="collapse01-1" class="panel--body panel-collapse collapse in">
                                                    <p>{!! nl2br($product->description) !!}</p>

                                                </div>
                                            </div>

                                        {{--<!-- Panel 01 -->--}}
                                        {{--<div class="panel">--}}
                                        {{--<div class="panel--heading">--}}
                                        {{--<a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion01" href="#collapse01-2">--}}
                                        {{--Customization--}}
                                        {{--</a>--}}
                                        {{--</div>--}}
                                        {{--<div id="collapse01-2" class="panel--body panel-collapse collapse responsive-height">--}}
                                        {{--<span>Enter personalized text (max 8 characters)</span>--}}
                                        {{--<span>&nbsp;</span>--}}
                                        {{--<form>--}}
                                        {{--<input type="text" class="form-control auto-blur"--}}
                                        {{--name="custom-text" id="custom-text" placeholder="TEXT HERE" maxlength="8"--}}
                                        {{--onfocusout="ChangePosition()" style="text-transform:uppercase" />--}}
                                        {{--<div class="col-xs-12 col-sm-12 col-md-3 text-center">--}}
                                        {{--<p style="margin-bottom: 0;margin-left: 11%;">Position</p>--}}
                                        {{--<select class="minimal" data-width="auto"--}}
                                        {{--id="custom-position" name="custom-position"--}}
                                        {{--onchange="ChangeSelectedPosition()" style="width: 130px;">--}}
                                        {{--@foreach($product->product_positions as $position)--}}
                                        {{--@php($value=$position->pos_x."-".$position->pos_y)--}}
                                        {{--<option value="{{$value}}">{{$position->name}}</option>--}}
                                        {{--@endforeach--}}
                                        {{--</select>--}}
                                        {{--</div>--}}
                                        {{--<div class="col-xs-12 col-sm-12 col-md-3 text-center">--}}
                                        {{--<p style="margin-bottom: 0;margin-left: 11%;">Font</p>--}}
                                        {{--<select class="minimal" data-width="auto" id="custom-font" name="custom-font" onchange="ChangePosition()">--}}
                                        {{--<option value="Serif">SERIF</option>--}}
                                        {{--<option value="Sans-serif">SAN SERIF</option>--}}
                                        {{--</select>--}}
                                        {{--</div>--}}
                                        {{--<div class="col-xs-12 col-sm-12 col-md-3 text-center">--}}
                                        {{--<p style="margin-bottom: 0;margin-left: 11%;">Color</p>--}}
                                        {{--<select class="selectpicker minimal" data-width="auto" id="custom-color" name="custom-color" onchange="ChangePosition()">--}}
                                        {{--<option value="Gold-FFD700">GOLD</option>--}}
                                        {{--<option value="Silver-C0C0C0">SILVER</option>--}}
                                        {{--<option value="Bronze-CD7F32">BRONZE</option>--}}
                                        {{--</select>--}}
                                        {{--</div>--}}
                                        {{--<div class="col-xs-12 col-sm-12 col-md-3 text-center">--}}
                                        {{--<p style="margin-bottom: 0;margin-left: 11%;">Size</p>--}}
                                        {{--<select class="selectpicker minimal" data-width="auto" id="custom-size" name="custom-size" onchange="ChangePosition()">--}}
                                        {{--<option value="Large-24">LARGE</option>--}}
                                        {{--<option value="Medium-20">MEDIUM</option>--}}
                                        {{--<option value="Small-16">SMALL</option>--}}
                                        {{--</select>--}}
                                        {{--</div>--}}
                                        {{--</form>--}}
                                        {{--</div>--}}
                                        {{--</div>--}}

                                        <!-- Panel 03 -->
                                            {{--<div class="panel">--}}
                                                {{--<div class="panel--heading">--}}
                                                    {{--<a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion01" href="#collapse01-3">--}}
                                                        {{--Delivery & Returns--}}
                                                    {{--</a>--}}
                                                {{--</div>--}}
                                                {{--<div id="collapse01-3" class="panel--body panel-collapse collapse">--}}
                                                    {{--Delivery and Returns Delivery and Returns Delivery and Returns Delivery and Returns Delivery and Returns Delivery and Returns Delivery and Returns--}}
                                                {{--</div>--}}
                                            {{--</div>--}}
                                        </div>
                                        <!-- End .Accordion-->

                                        <div class="mobile-center" style="padding: 5% 0 5% 0">
                                            @if($product->qty <= 0)
                                                {{--<p style="color: red;">This product is sold Out, drop your email for next information</p>--}}
                                                <div class="col-md-12" style="background-color: #D3D3D3; padding: 5% 5% 5% 5%">
                                                    <p style="margin: 0;">This product is sold out, drop your email below and we will notify you when the product is available</p>
                                                </div>
                                                <input type="text" class="form-control" id="waiting_list_name" placeholder="NAME"/>
                                                <input type="email" class="form-control" id="waiting_list_email" placeholder="EMAIL"/>
                                                <a class="btn btn--secondary btn--bordered" id="waiting_list_button">Submit</a>

                                                <div id="waiting_list_success_message" class="row mb-3" style="display: none;padding-top:5%;">
                                                    <div class="col-12">
                                                        <h5 class="text--left">Thank you for registering</h5>
                                                    </div>
                                                </div>
                                                <div id="waiting_list_error_message" class="row mb-3" style="display: none;padding-top:5%;">
                                                    <div class="col-12">
                                                        <h5 class="text--left">Please fill Name and Email</h5>
                                                    </div>
                                                </div>
                                            @else
                                                {{--<p class="font-16" style="text-align: justify;">--}}
                                                    {{--This product will ship on 30 April - 7 May--}}
                                                {{--</p>--}}
                                                <button class="btn btn--secondary btn--bordered" type="submit">Add to Cart</button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div><!-- .col-md-6 end-->
            </div>
            <!-- .row end -->
        </div>
        <!-- .container end -->
    </section>
    <!-- #cover5 end -->
@endsection
@include('partials.frontend._color-information')

@section('scripts')
    <!-- JavaScript Section -->
    <script type="text/javascript">
        $(document).ready(function(){
            $('a[data-toggle=tooltip]').tooltip();
        });

        // Auto onfocusout when enter is pressed
        $('.auto-blur').keypress(function (e) {
            if (e.which == 13) {
                $(this).blur();
            }
        });

        // Toggle customize section
        function onToggleCustomize(){
            var toggleInput = $('#customize-toggle').val();
            if(toggleInput === 'true'){
                $('#customize-toggle').val('false');
                $('#customize-section').hide();
            }
            else{
                $('#customize-toggle').val('true');
                $('#customize-section').show();
            }
        }
        function ChangeColour(){
            var url = "/product-detail/" + $('#select-colour').val();

            window.location = url;
        }
        function ChangeColourThumbnail(myRadio){
            var url = "/product-detail/" + myRadio.value;

            window.location = url;
        }

        $('#waiting_list_button').click(function(e){
            e.preventDefault();
            var email = $('#waiting_list_email').val();
            var name = $('#waiting_list_name').val();
            var slug = $('#slug').val();

            if(email === "" || name === ""){
                $('#waiting_list_error_message').slideDown(500);
            }
            else{
                $.ajax({
                    type: 'POST',
                    url: '{{ route('waiting-list') }}',
                    datatype : "application/json",
                    data: {
                        '_token': '{{ csrf_token() }}',
                        'email': email,
                        'name': name,
                        'slug': slug,
                    }, // no need to stringify
                    success: function (result) {
                        $('#waiting_list_error_message').hide();
                        $('#waiting_list_success_message').slideDown(500);
                    }
                });
            }
        });

        $('#emoji-picker').click(function(e){
            e.preventDefault();
            var toggleEmoji = $('#emoji_show').val();

            if(toggleEmoji === 'true'){
                $('#emoji_show').val('false');
                $('#emoji_list').hide();
            }
            else{
                $('#emoji_show').val('true');
                $('#emoji_list').show();
            }
        });
    </script>
    <script>

        function ChangeCustom(value, option){
            var valueArr = value.split("-");
            //change position
            if(option === 1){
                var valuePosition = valueArr[1] + "-" + valueArr[2];
                $('#custom-position').val(valuePosition);
                $('#custom-position-text').text(valueArr[0]);
                $('#custom-position-name').text(valueArr[0]);
                $('#custom-position-name').val(valueArr[0]);
                ChangeSelectedPosition();
            }
            //change color
            else if(option === 2){
                $('#custom-color').val(value);
                $('#custom-color-text').text(valueArr[0]);
                $('#custom-position-name').text(valueArr[0]);
            }
            //change Size
            else{
                $('#custom-size').val(value);
                $('#custom-size-text').text(valueArr[0]);
                $('#custom-position-name').text(valueArr[0]);
            }

            ChangePosition();
            $('#custom-text').focus();
        }
        function ChangeSelectedPosition(){
            var selectedPosition = $('#custom-position').val();
            var positionArr = selectedPosition.split("-");
            $('#position_x').val(positionArr[0]);
            $('#position_y').val(positionArr[1]);
            // alert(positionArr[0] + " " + positionArr[1]);
            ChangePosition();
        }

        function SelectEmoji(myRadio){
            var text = $('#custom-text').html();
            var srcList = $('#src-list').val();
            var img = "<img src='" + myRadio.value + "'>";

            srcList = srcList + myRadio.value + '#';
            {{--if(myRadio.value === "1"){--}}
                {{--img = "<img src='{{ asset('IMAGES/EMOJI/10-SMILEY.PNG') }}'>";--}}
                {{--srcList = srcList + '{{ asset('IMAGES/EMOJI/10-SMILEY.PNG') }}' + '#';--}}
            {{--}--}}
            // alert(newText.length);
            var newText = text + img;
            $('#custom-text').empty();
            $('#custom-text').append(newText);
            // $('#custom-input-text').val(newText);

            $('#src-list').val(srcList);

            ChangeText();
        }

        function ChangeText(){
            var src = [];
            var indices = [];
            var oldText = $('#custom-text').html().toUpperCase();
            oldText = oldText.replace(/<BR>/g, "");
            var newText = oldText.replace(/>/g, "<");

            newText = newText.replace(/"/g, "'");
            newText = newText.replace(/<IMG SRC='/g, "#");
            newText = newText.replace(/'<<IMG SRC='/g, "#");
            newText = newText.replace(/'</g, "#");
            newText = newText.replace(/##/g, "#");
            newText = newText.replace(/<BR</g, "");

            //count character process (only 3 character)
            var srcList = newText.split("#");
            var newSrcList = "";
            for(var k=0; k<srcList.length;k++){
                if(srcList[k] !== "" ){
                    if(srcList[k].length > 1){
                        if(srcList[k].indexOf('EMOJI') >= 0){
                            newSrcList = newSrcList + srcList[k] + "#";
                        }
                        else{
                            for(var m=0; m<srcList[k].length;m++){
                                newSrcList = newSrcList + srcList[k].substr(m, 1) + "#";
                            }
                        }
                    }
                    else{
                        newSrcList = newSrcList + srcList[k] + "#";
                    }
                }
            }

            //if more than 3 character
            var newSrcListCount = newSrcList.split("#");
            if((newSrcListCount.length - 1) > 5){
                var oldInput = $('#custom-input-text').val();
                $('#custom-text').empty();
                $('#custom-text').append(oldInput);
            }
            else{
                // $('#custom-text').val(text);
                // $('#src-list').val(newText);
                $('#src-list').val(newSrcList);
                $('#custom-text').empty();
                $('#custom-text').append(oldText);
                $('#custom-input-text').val(oldText);

                ChangePosition();
            }
        }

        function ChangePosition(){
            //checking mobile view
            var isMobile = detectmob();
            var canvasType = 1;
            var canvasId = "myCanvas";
            if(isMobile){
                $('#myCanvas').hide();
                $('#myCanvasMobile').show();

                canvasType = 2;
                canvasId = "myCanvasMobile";
            }
            else{
                $('#myCanvas').show();
                $('#myCanvasMobile').hide();
            }

            $('#slider-product').hide();
            $('#custom-section').show();

            var text = $('#custom-text').html().toUpperCase();

            // $('#custom-text').val(text);
            $('#custom-input-text').val(text);
            var selectedPosition = $('#custom-position').val();
            // var selectedFont = $('#custom-font').val();
            var color = $('#custom-color').val();
            var size = $('#custom-size').val();
            var posX = ($('#position_x').val());
            var posY = ($('#position_y').val());

            var colorArr = color.split("-");
            var sizeArr = size.split("-");

            var font = sizeArr[1] + "px Bodoni";
            if(isMobile){
                if(sizeArr[1] === 16){
                    font = (sizeArr[1] - 8) + "px Bodoni";
                }
                else{
                    font = (sizeArr[1] - 2) + "px Bodoni";
                }
            }
            var fillStyle = "#"+colorArr[1];

            var canvas = document.getElementById(canvasId);
            var context = canvas.getContext("2d");

            var imageObj = new Image();

            var src = [];
            var imgs = [];

            var srcListInput = $('#src-list').val();
            var srcList = srcListInput.split("#");

            for(var k=0; k<20;k++){
                imgs[k] = new Image();
            }
            //emoji draw image
            if(srcList.length > 0){
                var ct = 0;
                for(var l=0; l<srcList.length;l++) {
                    if(srcList[l] !== "" && srcList[l].indexOf(".PNG") >= 0){
                        var imageColor = srcList[l];

                        if(fillStyle === '#C0C0C0') {
                            imageColor = imageColor.replace(/.PNG/g, "-SILVER.PNG");
                        }
                        else{
                            imageColor = imageColor.replace(/.PNG/g, "-GOLD.PNG");
                        }
                        imgs[ct].src = imageColor;
                        ct++;
                    }
                }
            }
            imageObj.onload = function(){
                context.restore();
                if(isMobile){
                    var hRatio = canvas.width  / imageObj.width;
                    posX = posX * (hRatio-0.025);
                    var vRatio =  canvas.height / imageObj.height;
                    posY = posY * (vRatio-0.025);

                    var ratio  = Math.min ( hRatio, vRatio );
                    var centerShift_x = ( canvas.width - imageObj.width*ratio )/2;
                    var centerShift_y = ( canvas.height - imageObj.height*ratio )/2;
                    // context.clearRect(0,0,canvas.width, canvas.height);
                    context.drawImage(imageObj, 0,0, imageObj.width, imageObj.height,
                        centerShift_x,centerShift_y,imageObj.width*ratio, imageObj.height*ratio);
                }
                else{
                    context.drawImage(imageObj, 10, 10);
                }
                context.textAlign = 'center';
                context.font = font;

                if(fillStyle === '#C0C0C0' || fillStyle === '#FFD700') {
                    context.fillStyle = fillStyle;``
                }else{
                    context.strokeStyle = 'black';
                    context.fillStyle = "rgba(255, 255, 255, 0.2)";
                }

                //emoji processing
                var newText = text.replace(/<BR>/g, "");
                if(text.indexOf('SRC=') >= 0){
                    newText = "";
                    var ct = 0;
                    var posXimg = Number(posX);
                    for(var l=0; l<srcList.length;l++) {
                        if(srcList[l] !== "" ){
                            if(srcList[l].indexOf(".PNG") >= 0){
                                if(l === 0){
                                    posXimg = posXimg - Number(30);
                                    newText = newText + "     ";
                                }
                                else if(l === 1){
                                    //cek jika gambar pertama
                                    if(ct === 0){
                                        posXimg = posXimg - Number(15);
                                        newText = newText + "    ";
                                    }
                                    else{
                                        posXimg = posXimg + Number(15);
                                        newText = newText + "     ";
                                    }
                                }
                                else if(l === 2){
                                    //cek jika gambar pertama
                                    if(ct === 0){
                                        posXimg = posXimg - Number(5);
                                        newText = newText + "    ";
                                    }
                                    else{
                                        posXimg = posXimg + Number(16);
                                        newText = newText + "     ";
                                    }
                                }
                                else if(l === 3){
                                    //cek jika gambar pertama
                                    if(ct === 0){
                                        posXimg = posXimg - Number(2);
                                        newText = newText + "    ";
                                    }
                                    else{
                                        posXimg = posXimg + Number(14);
                                        newText = newText + "     ";
                                    }
                                }
                                else{
                                    if(ct === 0){
                                        posXimg = posXimg + Number(9);
                                    }
                                    else{
                                        posXimg = posXimg + Number(17);
                                    }
                                }
                                var posYimg = posY - 13;
                                context.drawImage(imgs[ct], posXimg, posYimg, 15, 15);
                                ct++;
                            }
                            else{
                                // newText = newText + srcList[l];
                                // posXimg = posXimg + Number(8);
                                newText = newText + srcList[l];
                                if(l <= 1){
                                    if(ct !== 0){
                                        posXimg = posXimg + Number(12);
                                    }
                                }
                                else{
                                    if(ct !== 0){
                                        posXimg = posXimg + Number(13);
                                    }
                                    else{
                                        posXimg = posXimg + Number(5);
                                    }
                                }
                            }
                        }
                    }
                }
                context.fillText(newText, posX, posY);
            };
            imageObj.src = "{{ asset('storage/products/'.$productMainImages->path) }}";

        }

        function detectmob() {
            if(window.innerWidth <= 450) {
                return true;
            } else {
                return false;
            }
        }
    </script>

@endsection

@section('styles')
    <style>
        .header-13 .navbar-header > .logo {
            padding-top: 2%;

        }
        .customize-section{
            padding-bottom: 5%;
        }
        .btn-color-customize{
            font-size: 14px;
            border:none;
        }
        .btn-color-customize:hover{
            background-color: #ffffff;
            border:none;
        }
        body {
            font-family: "Montserrat", "Lato", "Open Sans", "Helvetica Neue", Helvetica, Calibri, Arial, sans-serif;
            color: #6b7381;
            background: #f2f2f2;
        }
        .jumbotron {
            background: #6b7381;
            color: #bdc1c8;
        }
        .jumbotron h1 {
            color: #fff;
        }
        .example {
            margin: 4rem auto;
        }
        .example > .row {
            margin-top: 2rem;
            height: 5rem;
            vertical-align: middle;
            text-align: center;
            border: 1px solid rgba(189, 193, 200, 0.5);
        }
        .example > .row:first-of-type {
            border: none;
            height: auto;
            text-align: left;
        }
        .example h3 {
            font-weight: 400;
        }
        .example h3 > small {
            font-weight: 200;
            font-size: 0.75em;
            color: #939aa5;
        }
        .example h6 {
            font-weight: 700;
            font-size: 0.65rem;
            letter-spacing: 3.32px;
            text-transform: uppercase;
            color: #bdc1c8;
            margin: 0;
            line-height: 5rem;
        }
        .example .btn-toggle {
            top: 50%;
            transform: translateY(-50%);
        }
        .btn-toggle {
            margin: 0 4rem;
            padding: 0;
            position: relative;
            border: none;
            height: 1.5rem;
            width: 3rem;
            border-radius: 1.5rem;
            color: #6b7381;
            background: #bdc1c8;
        }
        .btn-toggle:focus,
        .btn-toggle.focus,
        .btn-toggle:focus.active,
        .btn-toggle.focus.active {
            outline: none;
        }
        .btn-toggle:before,
        .btn-toggle:after {
            line-height: 1.5rem;
            width: 4rem;
            text-align: center;
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            position: absolute;
            bottom: 0;
            transition: opacity 0.25s;
        }
        .btn-toggle:before {
            content: "Off";
            left: -4rem;
        }
        .btn-toggle:after {
            content: "On";
            right: -4rem;
            opacity: 0.5;
        }
        .btn-toggle > .handle {
            position: absolute;
            top: 0.1875rem;
            left: 0.1875rem;
            width: 1.125rem;
            height: 1.125rem;
            border-radius: 1.125rem;
            background: #fff;
            transition: left 0.25s;
        }
        .btn-toggle.active {
            transition: background-color 0.25s;
        }
        .btn-toggle.active > .handle {
            left: 1.6875rem;
            transition: left 0.25s;
        }
        .btn-toggle.active:before {
            opacity: 0.5;
        }
        .btn-toggle.active:after {
            opacity: 1;
        }
        .btn-toggle.btn-sm:before,
        .btn-toggle.btn-sm:after {
            line-height: -0.5rem;
            color: #fff;
            letter-spacing: 0.75px;
            left: 0.4125rem;
            width: 2.325rem;
        }
        .btn-toggle.btn-sm:before {
            text-align: right;
        }
        .btn-toggle.btn-sm:after {
            text-align: left;
            opacity: 0;
        }
        .btn-toggle.btn-sm.active:before {
            opacity: 0;
        }
        .btn-toggle.btn-sm.active:after {
            opacity: 1;
        }
        .btn-toggle.btn-xs:before,
        .btn-toggle.btn-xs:after {
            display: none;
        }
        .btn-toggle:before,
        .btn-toggle:after {
            color: #6b7381;
        }
        .btn-toggle.active {
            background-color: #29b5a8;
        }
        .btn-toggle.btn-lg {
            margin: 0 5rem;
            padding: 0;
            position: relative;
            border: none;
            height: 2.5rem;
            width: 5rem;
            border-radius: 2.5rem;
        }
        .btn-toggle.btn-lg:focus,
        .btn-toggle.btn-lg.focus,
        .btn-toggle.btn-lg:focus.active,
        .btn-toggle.btn-lg.focus.active {
            outline: none;
        }
        .btn-toggle.btn-lg:before,
        .btn-toggle.btn-lg:after {
            line-height: 2.5rem;
            width: 5rem;
            text-align: center;
            font-weight: 600;
            font-size: 1rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            position: absolute;
            bottom: 0;
            transition: opacity 0.25s;
        }
        .btn-toggle.btn-lg:before {
            content: "Off";
            left: -5rem;
        }
        .btn-toggle.btn-lg:after {
            content: "On";
            right: -5rem;
            opacity: 0.5;
        }
        .btn-toggle.btn-lg > .handle {
            position: absolute;
            top: 0.3125rem;
            left: 0.3125rem;
            width: 1.875rem;
            height: 1.875rem;
            border-radius: 1.875rem;
            background: #fff;
            transition: left 0.25s;
        }
        .btn-toggle.btn-lg.active {
            transition: background-color 0.25s;
        }
        .btn-toggle.btn-lg.active > .handle {
            left: 2.8125rem;
            transition: left 0.25s;
        }
        .btn-toggle.btn-lg.active:before {
            opacity: 0.5;
        }
        .btn-toggle.btn-lg.active:after {
            opacity: 1;
        }
        .btn-toggle.btn-lg.btn-sm:before,
        .btn-toggle.btn-lg.btn-sm:after {
            line-height: 0.5rem;
            color: #fff;
            letter-spacing: 0.75px;
            left: 0.6875rem;
            width: 3.875rem;
        }
        .btn-toggle.btn-lg.btn-sm:before {
            text-align: right;
        }
        .btn-toggle.btn-lg.btn-sm:after {
            text-align: left;
            opacity: 0;
        }
        .btn-toggle.btn-lg.btn-sm.active:before {
            opacity: 0;
        }
        .btn-toggle.btn-lg.btn-sm.active:after {
            opacity: 1;
        }
        .btn-toggle.btn-lg.btn-xs:before,
        .btn-toggle.btn-lg.btn-xs:after {
            display: none;
        }
        .btn-toggle.btn-sm {
            margin: 0 0.5rem;
            padding: 0;
            position: relative;
            border: none;
            height: 1.5rem;
            width: 3rem;
            border-radius: 1.5rem;
        }
        .btn-toggle.btn-sm:focus,
        .btn-toggle.btn-sm.focus,
        .btn-toggle.btn-sm:focus.active,
        .btn-toggle.btn-sm.focus.active {
            outline: none;
        }
        .btn-toggle.btn-sm:before,
        .btn-toggle.btn-sm:after {
            line-height: 1.5rem;
            width: 0.5rem;
            text-align: center;
            font-weight: 600;
            font-size: 0.55rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            position: absolute;
            bottom: 0;
            transition: opacity 0.25s;
        }
        .btn-toggle.btn-sm:before {
            content: "Off";
            left: -0.5rem;
        }
        .btn-toggle.btn-sm:after {
            content: "On";
            right: -0.5rem;
            opacity: 0.5;
        }
        .btn-toggle.btn-sm > .handle {
            position: absolute;
            top: 0.1875rem;
            left: 0.1875rem;
            width: 1.125rem;
            height: 1.125rem;
            border-radius: 1.125rem;
            background: #fff;
            transition: left 0.25s;
        }
        .btn-toggle.btn-sm.active {
            transition: background-color 0.25s;
        }
        .btn-toggle.btn-sm.active > .handle {
            left: 1.6875rem;
            transition: left 0.25s;
        }
        .btn-toggle.btn-sm.active:before {
            opacity: 0.5;
        }
        .btn-toggle.btn-sm.active:after {
            opacity: 1;
        }
        .btn-toggle.btn-sm.btn-sm:before,
        .btn-toggle.btn-sm.btn-sm:after {
            line-height: -0.5rem;
            color: #fff;
            letter-spacing: 0.75px;
            left: 0.4125rem;
            width: 2.325rem;
        }
        .btn-toggle.btn-sm.btn-sm:before {
            text-align: right;
        }
        .btn-toggle.btn-sm.btn-sm:after {
            text-align: left;
            opacity: 0;
        }
        .btn-toggle.btn-sm.btn-sm.active:before {
            opacity: 0;
        }
        .btn-toggle.btn-sm.btn-sm.active:after {
            opacity: 1;
        }
        .btn-toggle.btn-sm.btn-xs:before,
        .btn-toggle.btn-sm.btn-xs:after {
            display: none;
        }
        .btn-toggle.btn-xs {
            margin: 0 0;
            padding: 0;
            position: relative;
            border: none;
            height: 1rem;
            width: 2rem;
            border-radius: 1rem;
        }
        .btn-toggle.btn-xs:focus,
        .btn-toggle.btn-xs.focus,
        .btn-toggle.btn-xs:focus.active,
        .btn-toggle.btn-xs.focus.active {
            outline: none;
        }
        .btn-toggle.btn-xs:before,
        .btn-toggle.btn-xs:after {
            line-height: 1rem;
            width: 0;
            text-align: center;
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            position: absolute;
            bottom: 0;
            transition: opacity 0.25s;
        }
        .btn-toggle.btn-xs:before {
            content: "Off";
            left: 0;
        }
        .btn-toggle.btn-xs:after {
            content: "On";
            right: 0;
            opacity: 0.5;
        }
        .btn-toggle.btn-xs > .handle {
            position: absolute;
            top: 0.125rem;
            left: 0.125rem;
            width: 0.75rem;
            height: 0.75rem;
            border-radius: 0.75rem;
            background: #fff;
            transition: left 0.25s;
        }
        .btn-toggle.btn-xs.active {
            transition: background-color 0.25s;
        }
        .btn-toggle.btn-xs.active > .handle {
            left: 1.125rem;
            transition: left 0.25s;
        }
        .btn-toggle.btn-xs.active:before {
            opacity: 0.5;
        }
        .btn-toggle.btn-xs.active:after {
            opacity: 1;
        }
        .btn-toggle.btn-xs.btn-sm:before,
        .btn-toggle.btn-xs.btn-sm:after {
            line-height: -1rem;
            color: #fff;
            letter-spacing: 0.75px;
            left: 0.275rem;
            width: 1.55rem;
        }
        .btn-toggle.btn-xs.btn-sm:before {
            text-align: right;
        }
        .btn-toggle.btn-xs.btn-sm:after {
            text-align: left;
            opacity: 0;
        }
        .btn-toggle.btn-xs.btn-sm.active:before {
            opacity: 0;
        }
        .btn-toggle.btn-xs.btn-sm.active:after {
            opacity: 1;
        }
        .btn-toggle.btn-xs.btn-xs:before,
        .btn-toggle.btn-xs.btn-xs:after {
            display: none;
        }
        .btn-toggle.btn-secondary {
            color: #6b7381;
            background: #bdc1c8;
        }
        .btn-toggle.btn-secondary:before,
        .btn-toggle.btn-secondary:after {
            color: #6b7381;
        }
        .btn-toggle.btn-secondary.active {
            background-color: #ff8300;
        }

    </style>
    <style>
        .btn-group img {
            margin-right: 10px;
        }
        .dropdown-toggle {
            padding-right: 50px;
        }
        .dropdown-toggle .glyphicon {
            margin-left: 20px;
            margin-right: -40px;
        }
        .dropdown-menu > li > a:hover {
            background: #f1f9fd;
        } /* $search-blue */
        .dropdown-header {
            background: #ccc;
            font-size: 14px;
            font-weight: 700;
            padding-top: 5px;
            padding-bottom: 5px;
            margin-top: 10px;
            margin-bottom: 5px;
        }
        /* HIDE RADIO */
        [type=radio] {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0;
        }

        /* IMAGE STYLES */
        [type=radio] + img {
            cursor: pointer;
        }

        /* CHECKED STYLES */
        [type=radio]:checked + img {
            outline: 2px solid #ffffff;
        }

        /* EMOJI PICKER */
        #emoji-picker{
            background-color: transparent;
            font-size: 30px;
            font-weight: 400;
            line-height: 20px;
            border: none;
        }
    </style>
@endsection
