@extends('layouts.frontend')

@section('pageTitle', 'Billing and Shipment | NAMA')
@section('content')
    <section class="bg-white">
        <form method="POST" action="{{ route('submit.billing') }}" id="billingForm">
            @csrf
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="col-md-12">
                            <h1>Billing & Shipping</h1>
                            <hr style="height:1px;border:none;color:#333;background-color:#333;" />
                            <br/>
                        </div>
                        <div class="col-md-12">
                            @foreach($errors->all() as $error)
                                <span class="form-message">
                                    <strong> {{ $error }} </strong>
                                    <br/>
                                </span>
                            @endforeach
                        </div>
                        <input type="hidden" name="weight" value="{{$totalWeight}}">
                        {{-- guset or user don't have address --}}
                        @if($flag==0)
                            <div>
                                <div class="col-md-12">
                                    <select name="country" id="country" class="form-control">
                                        <option value="-1" selected>COUNTRY/REGION</option>
                                        @foreach($countries as $country)
                                            @if($country->id == 106)
                                                <option value="{{ $country->id }}" selected>{{ $country->name }}</option>
                                            @else
                                                <option value="{{ $country->id }}">{{ $country->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="address_detail" id="address_detail"
                                           placeholder="ADDRESS (LINE 1)" value="{{old('address_detail')}}" required/>
                                    @if ($errors->has('address_detail'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('address_detail') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="address_detail_2" id="address_detail_2"
                                           placeholder="ADDRESS (LINE 2)" value="{{old('address_detail_2')}}" />
                                    @if ($errors->has('address_detail_2'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('address_detail_2') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="col-md-12">
                                    <input type="text" class="form-control" name="street" id="street"
                                           placeholder="STREET" value="{{old('street')}}" required/>
                                    @if ($errors->has('street'))
                                        <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('street') }}</strong>
                                </span>
                                    @endif
                                </div>

                                <div class="col-md-12">
                                    <select name="province" id="province" class="form-control">
                                        <option value="-1" selected>PROVINCE</option>
                                        @foreach($provinces as $province)
                                            <option value="{{ $province->id }}">{{ $province->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-12">
                                    <select name="city" id="city" class="form-control">
                                        <option value="-1" selected>CITY</option>
                                        @foreach($cities as $city)
                                            <option value="{{ $city->province_id . '-' . $city->id }}">{{ $city->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                {{--<div class="col-md-12">--}}
                                    {{--@if ($errors->has('suburb'))--}}
                                        {{--<span class="invalid-feedback" role="alert">--}}
                                            {{--<strong>{{ $errors->first('suburb') }}</strong>--}}
                                        {{--</span>--}}
                                    {{--@endif--}}
                                {{--</div>--}}
                                <input type="hidden" class="form-control" name="suburb" id="suburb"
                                       placeholder="SUBURB" value="{{old('suburb')}}"/>

                                <div class="col-md-12">
                                    <input type="text" class="form-control" name="post_code" id="post_code"
                                           placeholder="POST CODE" value="{{old('post_code')}}" required/>
                                    @if ($errors->has('post_code'))
                                        <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('post_code') }}</strong>
                                </span>
                                    @endif
                                </div>

                                {{--<div class="col-md-6">--}}
                                    {{--<input type="text" class="form-control" name="state" id="state"--}}
                                           {{--placeholder="STATE" value="{{old('state')}}"/>--}}
                                    {{--@if ($errors->has('state'))--}}
                                        {{--<span class="invalid-feedback" role="alert">--}}
                                    {{--<strong>{{ $errors->first('state') }}</strong>--}}
                                {{--</span>--}}
                                    {{--@endif--}}
                                {{--</div>--}}
                                <input type="hidden" class="form-control" name="state" id="state"
                                       placeholder="STATE" value="{{old('suburb')}}"/>
                            </div>
                        {{-- guset or user already have address --}}
                        @else
                            <div class="col-md-12 bg-windrift-blue" style="padding-top:1%;padding-bottom:1%;">
                                <div class="col-md-12 padding-bottom-10">
                                    <span class="font-20 text-black">{{$address->description}}, {{$address->street}}</span>
                                    <input type="hidden" name="address_detail" value="{{$address->description}}">
                                    <input type="hidden" name="street" value="{{$address->street}}">
                                </div>

                                <div class="col-md-12 padding-bottom-10">
                                    <span class="font-20 text-black">City : {{$address->city->name}}</span>
                                    <input type="hidden" name="city" value="{{$address->city_id}}">
                                </div>

                                <div class="col-md-12 padding-bottom-10">
                                    <span class="font-20 text-black">Province : {{$address->province->name}}</span>
                                    <input type="hidden" name="province" value="{{$address->province_id}}">
                                </div>

                                <div class="col-md-6 padding-bottom-10">
                                    <span class="font-20 text-black">Post Code : {{$address->postal_code}}</span>
                                    <input type="hidden" name="post_code" value="{{$address->postal_code}}">
                                </div>

                                <div class="col-md-12 padding-bottom-10">
                                    <span class="font-20 text-black">Country : {{$address->country->name}}</span>
                                    <input type="hidden" name="country" value="{{$address->country_id}}">
                                </div>

                                {{--<div class="col-md-6 padding-bottom-10">--}}
                                    {{--<span class="font-20 text-black">Suburb : {{$address->suburb}}</span>--}}
                                    <input type="hidden" name="suburb" value="{{$address->suburb}}">
                                {{--</div>--}}

                                {{--<div class="col-md-6 padding-bottom-10">--}}
                                    {{--<span class="font-20 text-black">State : {{$address->state}}</span>--}}
                                    <input type="hidden" name="state" value="{{$address->state}}">
                                {{--</div>--}}
                            </div>
                        @endif

                    </div>
                </div>

                {{-- Input new Address --}}
                <div id="new-address" style="display:none;">
                    <div class="col-md-12">
                        <h1>New Shipping Address</h1>
                        <hr style="height:1px;border:none;color:#333;background-color:#333;" />
                        <br/>
                    </div>
                    <div class="col-md-12">
                        <select name="country_secondary" id="country" class="form-control">
                            <option value="-1" selected>COUNTRY/REGION</option>
                            @foreach($countries as $country)
                                @if($country->id == 106)
                                    <option value="{{ $country->id }}" selected>{{ $country->name }}</option>
                                @else
                                    <option value="{{ $country->id }}">{{ $country->name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <input type="text" class="form-control" name="address_detail_secondary" id="address_detail" placeholder="ADDRESS (LINE 1)" />
                    </div>

                    <div class="col-md-6">
                        <input type="text" class="form-control" name="address_detail_2_secondary" id="address_detail_2" placeholder="ADDRESS (LINE 2)" />
                    </div>

                    <div class="col-md-12">
                        <input type="text" class="form-control" name="street_secondary" id="street" placeholder="STREET" />
                    </div>

                    {{--<div class="col-md-12">--}}
                        {{--<input type="text" class="form-control" name="suburb_secondary" id="suburb" placeholder="SUBURB" />--}}
                    {{--</div>--}}

                    <input type="hidden" class="form-control" name="suburb_secondary"/>

                    <div class="col-md-12">
                        <select name="province_secondary" id="province" class="form-control">
                            <option value="-1" selected>PROVINCE</option>
                            @foreach($provinces as $province)
                                <option value="{{ $province->id }}">{{ $province->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-12">
                        <select name="city_secondary" id="city" class="form-control">
                            <option value="-1" selected>CITY</option>
                            @foreach($cities as $city)
                                <option value="{{ $city->province_id . '-' . $city->id }}">{{ $city->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-12">
                        <input type="text" class="form-control" name="post_code_secondary" id="post_code" placeholder="POST CODE" />
                    </div>

                    {{--<div class="col-md-6">--}}
                        {{--<input type="text" class="form-control" name="state_secondary" id="state" placeholder="STATE" />--}}
                    {{--</div>--}}
                    <input type="hidden" class="form-control" name="state_secondary" id="state" placeholder="STATE" />
                </div>

                <div class="row padding-top-3">
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="col-xs-12 col-sm-12 col-md-6">
                            @if($flag==0)
                                &nbsp;
                            @else
                                <input type="checkbox" name="another_shipment" id="another_shipment" class=""/> Ship to a different address
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-12 padding-top-3">
                    <h1>Shipping Service</h1>
                    <hr style="height:1px;border:none;color:#333;background-color:#333;" />
                    <br/>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        @if($isIndonesian)
                            <div class="col-md-12 padding-bottom-3">
                                <div class="col-md-3 col-sm-12">
                                    <img src="{{ asset('images/icons/jne.jpg') }}" class="width-100">
                                    <br>
                                    <input type="radio" name="courier" id="jne" value="jne-REG" checked/> REG <br>
                                    {{--<input type="radio" name="courier" id="jne" value="jne-OKE"/> OKE <br>--}}
                                    <input type="radio" name="courier" id="jne" value="jne-YES"/> YES <br>
                                </div>
                                {{--<div class="col-md-3">--}}
                                    {{--<img src="{{ asset('images/icons/tiki.png') }}" class="width-100">--}}
                                    {{--<br>--}}
                                    {{--<input type="radio" name="courier" id="jne" value="tiki-REG" /> REG (REGULER SERVICE) <br>--}}
                                    {{--<input type="radio" name="courier" id="jne" value="tiki-ONS" /> ONS (OVER NIGHT SERVICE) <br>--}}
                                    {{--<input type="radio" name="courier" id="jne" value="tiki-SDS" /> SDS (SAME DAY SERVICE) <br>--}}
                                {{--</div>--}}
                                <div class="col-md-5 col-sm-12">
                                    <img src="{{ asset('images/icons/gojek.png') }}" class="width-100">
                                    <img src="{{ asset('images/icons/grab.png') }}" class="width-100">
                                    <br>
                                    <input type="radio" name="courier" id="jne" value="gojek-grab" /> GOJEK / GRAB <br>
                                    <br>
                                    <div class="col-md-12" style="background-color: #D3D3D3; padding: 5% 5% 5% 5%">
                                        <p style="margin: 0;">For this option, delivery cost will be borne by customer</p>
                                    </div>

                                </div>
                                {{--<div class="col-md-3">--}}
                                    {{--<img src="{{ asset('images/icons/grab.png') }}" class="width-100">--}}
                                    {{--<br>--}}
                                    {{--<input type="radio" name="courier" id="jne" value="grab" /> GRAB <br>--}}
                                {{--</div>--}}
                            </div>
                            {{--<div class="col-md-12">--}}
                                {{--<div class="col-md-4">--}}
                                    {{--<img src="{{ asset('images/icons/sicepat.jpg') }}" class="width-100">--}}
                                    {{--<br>--}}
                                    {{--<input type="radio" name="courier" id="jne" value="sicepat-REG"/> REG (Layanan Reguler) <br>--}}
                                    {{--<input type="radio" name="courier" id="jne" value="sicepat-OKE"/> BEST (Besok Sampai Tujuan) <br>--}}
                                {{--</div>--}}
                                {{--<div class="col-md-4">--}}
                                    {{--<img src="{{ asset('images/icons/JNT.png') }}" class="width-100">--}}
                                    {{--<img src="{{ asset('images/icons/nama-brand-pinterest.svg') }}" class="width-50">--}}
                                    {{--<br>--}}
                                    {{--<input type="radio" name="courier" id="jne" value="J&T-EZ" /> EZ (Regular Service) <br>--}}
                                    {{--<input type="radio" name="courier" id="jne" value="J&T-JSD" /> JSD (Same Day Service) <br>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                            {{--jne, pos, tiki, rpx, esl, pcp, pandu, wahana, sicepat, jnt, pahala, cahaya, sap, jet, indah, dse, slis, first, ncs, star, ninja, lion, idl--}}
                            {{--<input type="radio" name="courier" id="jne" value="jne" />--}}
                            {{--<img src="{{ asset('images/icons/nama-brand-pinterest.svg') }}" class="width-50">--}}
                            {{--&nbsp;--}}
                            {{--<input type="radio" name="courier" id="tiki" value="tiki"/>--}}
                            {{--<img src="{{ asset('images/icons/nama-brand-instagram.svg') }}" class="width-50">--}}

                            {{--<input type="radio" name="courier" id="pos" value="pos"/>--}}
                            {{--<img src="{{ asset('images/icons/nama-brand-instagram.svg') }}" class="width-50">--}}

                            {{--<input type="radio" name="courier" id="sicepat" value="sicepat"/>--}}
                            {{--<img src="{{ asset('images/icons/nama-brand-instagram.svg') }}" class="width-50">--}}

                            {{--<input type="radio" name="courier" id="jnt" value="jnt"/>--}}
                            {{--<img src="{{ asset('images/icons/nama-brand-instagram.svg') }}" class="width-50">--}}

                            {{--<input type="radio" name="courier" id="jet" value="jet"/>--}}
                            {{--<img src="{{ asset('images/icons/nama-brand-instagram.svg') }}" class="width-50">--}}

                            {{--<input type="radio" name="courier" id="ninja" value="ninja"/>--}}
                            {{--<img src="{{ asset('images/icons/nama-brand-instagram.svg') }}" class="width-50">--}}
                        @endif
                    </div>
                </div>
                <div class="row padding-top-3">
                    <div class="col-xs-6 col-sm-6 col-md-10 text-right">
                        <span class="text-black" style="font-size: 11px; height: 31.5px; width: 120px;line-height: 0;"> DELIVERY FEE : </span>
                    </div>
                    <div class="col-xs-6 col-sm-6 col-md-2 text-center-xs" style="text-align: right;">
                        <span id="delivery-fee" class="text-black font-weight-bold" style="font-size: 11px; height: 31.5px; width: 120px; line-height: 0;"></span>
                    </div>
                </div>

                <div class="row padding-top-3">

                    <div class="col-xs-12 col-sm-12 col-md-12" style="text-align: right;">
                        <a href="{{ route('cart') }}">
                            <button type="button" class="btn btn--secondary btn--bordered" style="font-size: 11px; height: 31.5px; width: 120px;line-height: 0px; border: 1px solid #282828;">
                                BACK TO CART
                            </button>
                        </a>
                        <button type="submit" class="btn btn--secondary btn--bordered submitBtn" style="font-size: 11px; height: 31.5px; width: 120px;line-height: 0px; border: 1px solid #282828;" disabled>CONTINUE</button>
                    </div>
                </div>
            </div>
        </form>
    </section>
@endsection

@section('styles')
    <style>
       .padding-top-3{
           padding-top: 3% !important;
       }
       .padding-bottom-3{
           padding-bottom: 3% !important;
       }
       .padding-bottom-10{
           padding-bottom: 10px !important;
       }
        .bg-white{
            padding-bottom:0;
        }

    </style>
@endsection
@section('scripts')
    <script type="text/javascript">

        var userCityId = parseInt("{{ $userCity }}");
        var weightInt = parseInt('{{ $totalWeight }}');
        var defaultCourier = 'jne-REG';

        @if($userCity !== -1)
            // Get default delivery fee for jne-REG
            var defaultCourierSplitted = defaultCourier.split('-');
            rajaongkirAjaxGetCost(userCityId, weightInt, defaultCourierSplitted);
        @endif

        // Get delivery fee for every courier selected
        $("input:radio[name=courier]").click(function() {
            let value = $(this).val();
            let splittedValue = value.split('-');

            if(userCityId !== -1){
                rajaongkirAjaxGetCost(userCityId, weightInt, splittedValue);
            }
        });

        // Get delivery fee for every city selected
        $('#city').on('change', function() {
            //alert( this.value );
            let cityId = -1;
            if (this.value.indexOf('-') > -1)
            {
                let splittedValue = this.value.split('-');
                cityId = parseInt(splittedValue[1]);
            }
            else{
                cityId = parseInt(this.value);
            }

            userCityId = cityId;

            if(userCityId !== -1){
                // Get selected courier
                let courierValue = $('input[name=courier]:checked').val();
                let courierValueSplitted = courierValue.split('-');

                rajaongkirAjaxGetCost(userCityId, weightInt, courierValueSplitted);
            }
        });

        // Ajax function to get rajaongkir delivery fee
        function rajaongkirAjaxGetCost(tmpCityId, tmpWeight, tmpCourier){
            // alert(tmpCourier);
            if(tmpCourier[0] === "gojek"){
                $('#delivery-fee').html("Rp 0");
                $(".submitBtn").attr("disabled", false);
            }

            //promo free ongkir for jabodetabek
            // else if(tmpCityId === 54 || tmpCityId === 55 || tmpCityId === 78 || tmpCityId === 79 ||
            //         tmpCityId === 115 || tmpCityId === 151 || tmpCityId === 152 || tmpCityId === 153 ||
            //         tmpCityId === 154 || tmpCityId === 155 || tmpCityId === 455 || tmpCityId === 456 ||
            //         tmpCityId === 457)
            // {
            //     $('#delivery-fee').html("Rp 0");
            // }
            else{
                $.ajax({
                    url: '{{ route('ajax.rajaongkir.cost') }}',
                    type: 'POST',
                    data: {
                        'destination_city_id': tmpCityId,
                        'weight': tmpWeight,
                        'courier': tmpCourier
                    },
                    success: function(data) {
                        //console.log(data);
                        if(data.code === 200){
                            let feeStr = rupiahFormat(data.fee);
                            $('#delivery-fee').html("Rp " + feeStr);
                            $(".submitBtn").attr("disabled", false);
                        }
                        else{
                            $('#delivery-fee').html("Shipping service is currently unavailable, please try again in a few minutes");
                            $(".submitBtn").attr("disabled", true);
                        }
                    },
                    error: function(response){
                        console.log(response);
                    }
                });
            }
        }

        $("#another_shipment").change(function() {
            if(this.checked) {
                $('#new-address').show();
            }
            else{
                $('#new-address').hide();
            }
        });

        (function($){
            var province = $('#province');
            var city = $('#city');
            var cityOptions = city.children();

            province.on('change', function(){
                //remove the options
                cityOptions.detach();

                city.append("<option value='-1' selected>CITY</option>");

                //readd only the options for the country
                cityOptions.filter(function(){
                    return this.value.indexOf(province.val() + "-") === 0;
                }).appendTo(city);
                //clear out the value so it doesn't default to one it should not
                city.val('-1');
                $('#delivery-fee').html('');
            });
        }(jQuery));

        (function($){
            var province = $('#province_secondary');
            var city = $('#city_secondary');
            var cityOptions = city.children();

            province.on('change', function(){
                //remove the options
                cityOptions.detach();

                city.append("<option value='-1' selected>CITY</option>");

                //readd only the options for the country
                cityOptions.filter(function(){
                    return this.value.indexOf(province.val() + "-") === 0;
                }).appendTo(city);
                //clear out the value so it doesn't default to one it should not
                city.val('-1');
                $('#delivery-fee').html('');
            });
        }(jQuery));

        function rupiahFormat(nStr) {
            nStr += '';
            x = nStr.split(',');
            x1 = x[0];
            x2 = x.length > 1 ? ',' + x[1] : '';
            var rgx = /(\d+)(\d{3})/;
            while (rgx.test(x1)) {
                x1 = x1.replace(rgx, '$1' + '.' + '$2');
            }
            return x1 + x2;
        }

        $("#billingForm").submit(function () {
            $(".submitBtn").click(function () {
                $(".submitBtn").attr("disabled", true);
                return true;
            });
        });
    </script>
@endsection
