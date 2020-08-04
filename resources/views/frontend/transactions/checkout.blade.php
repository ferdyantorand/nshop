@extends('layouts.frontend')

@section('pageTitle', 'Checkout | NAMA')
@section('content')
    <section class="bg-white">
        {{--<form method="POST" action="{{ route('submit.checkout', ["order"=>$order->id]) }}">--}}
            {{--@csrf--}}
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="col-md-12">
                            <h1>Checkout</h1>
{{--                            <span style="font-size:16px; color: red;"> Transactions between 23 December 2019- 5 January 2020 <br> will be processed on 6 January 2020 <br></span>--}}
{{--                            <br>--}}
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

                        @if(\Illuminate\Support\Facades\Auth::guard('web')->check())
                            {{--Check if logged in and there's payment Info--}}
                        @endif
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <span>Have a coupon code?</span>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <span id="voucher_response" style="display:none;color:red;"></span>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-8">
                            <input type="text" class="form-control input-bordered" name="voucher" id="voucher" value="" placeholder="TYPE CODE HERE" style="text-align: center"/>
                            <input type="hidden" id="voucher_applied" value=""/>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-4">
                            <button id="apply-voucher" type="button" class="btn btn--secondary btn--bordered" style="font-size: 11px; height: 31.5px; width: 100%;line-height: 0px; border: 1px solid #282828;">APPLY CODE</button>
                        </div>

                    </div>
                </div>

                <br/>

                <div class="row">
                    <table class="table">
                        <tr>
                            <td colspan="2">REVIEW YOUR ORDER</td>
                        </tr>
                        <tr>
                            <td>
                                <div class="col-md-12">
                                    <div class="col-md-7 border-bottom-black mb-20 hidden-xs hidden-sm">
                                        <div class="col-md-2"></div>
                                        <div class="col-md-4">PRODUCT</div>
                                        <div class="col-md-2">QUANTITY</div>
                                        <div class="col-md-4 right">TOTAL</div>
                                    </div>
                                    <div class="col-md-5 border-bottom-black mb-20 hidden-xs hidden-sm">
                                        SHIPPING <b>({{$order->shipping_option}})</b> TO
                                    </div>
                                    <hr class="hidden-xs hidden-sm" style="height:1px;border:none;color:#333;background-color:#333;" />
                                    <hr style="height:1px;border:none;color:#333;background-color:#333;" />
                                    <div class="col-md-7">
                                        @foreach($orderProduct as $product)
                                            <div class="col-md-12 mb-10">
                                                <div class="col-md-2">
                                                    @php($productImage = \App\Models\ProductImage::where('product_id', $product->product->id)->where('is_main_image', 1)->first())
                                                    <img src="{{ asset('storage/products/'.$productImage->path) }}" alt="product" style="width: 100%"/>
                                                </div>
                                                <div class="col-md-4">
                                                    {{$product->product->name}}<br>
                                                    @if(!empty($product->product_info))
                                                        Customization :<br>
                                                        {!! $product->product_info !!}
                                                    @endif
                                                </div>
                                                <div class="col-md-2 hidden-sm hidden-xs">
                                                    {{$product->qty}}
                                                </div>
                                                <div class="col-md-2 hidden-md hidden-lg">
                                                    Quantity : {{$product->qty}}
                                                </div>
                                                <div class="col-md-4 right">
                                                    {{env('KURS_IDR')}} {{$product->grand_total_string}}
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="col-md-5">
                                        <div class="col-md-12 border-bottom-black mb-20 hidden-md hidden-lg">
                                            SHIPPING <b>({{$order->shipping_option}})</b> TO
                                        </div>
                                        <div class="col-md-12 border-bottom-black mb-20">
                                            {{$order->address->description}}, {{$order->address->street}},
                                            {{$order->address->city->name}}, {{$order->address->province->name}}, {{$order->address->postal_code}},
                                            {{$order->address->country->name}},
                                        </div>
                                        <div class="col-md-12 mb-20">
                                            <div class="col-md-6 bold">SUBTOTAL</div>
                                            <div class="col-md-6 right">{{env('KURS_IDR')}} {{$order->sub_total_string}}</div>
                                            <input type="hidden" id="subtotal" value="{{$order->sub_total}}">
                                        </div>
                                        <div class="col-md-12 mb-20">
                                            <div class="col-md-6 bold">SERVICE</div>
                                            <div class="col-md-6 right">{{env('KURS_IDR')}} {{$order->payment_charge_string}}</div>
                                        </div>
                                        <div class="col-md-12 mb-20">
                                            <div class="col-md-6 bold">SHIPPING</div>
                                            <div class="col-md-6 right">{{env('KURS_IDR')}} {{$order->shipping_charge_string}}</div>
                                        </div>
                                        <div class="col-md-12 mb-20">
                                            <div class="col-md-6 bold">TAX</div>
                                            <div class="col-md-6 right">{{env('KURS_IDR')}} {{$order->tax_amount_string}}</div>
                                        </div>
                                        <div class="col-md-12 border-bottom-black mb-20">
                                            <div class="col-md-6 bold">VOUCHER</div>
                                            <div class="col-md-6 right">({{env('KURS_IDR')}} <span id="voucher_amount_span">0</span>)</div>
                                            <input type="hidden" name="voucher_amount" id="voucher_amount" value="0">
                                        </div>
                                        <div class="col-md-12 mb-20">
                                            <div class="col-md-6 bold"><h5>TOTAL</h5></div>
                                            <div class="col-md-6 right bold"><h5>{{env('KURS_IDR')}} <span id="grand_total_span">{{$order->grand_total_string}}</span></h5></div>
                                            <input type="hidden" name="grand_total" id="grand_total" value="{{$order->grand_total}}">
                                        </div>
                                        <div class="col-md-12 mb-20">
                                            <div class="row">
                                                <div class="col-sm-12 hidden-xs text--right">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="dustbag_option">
                                                        <label class="form-check-label bold" for="dustbag_option">
                                                            include dustbag
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12 hidden-md text--left">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="dustbag_option">
                                                        <label class="form-check-label bold" for="dustbag_option">
                                                            include dustbag
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>

                    </table>
                </div>

                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="col-md-12">
                            @if($isIndonesian)
                                <input type="radio" name="payment_method" class="payment_method" value="credit_card" checked/>
                                <span style="font-size: 16px;">Card Payment </span>
                                <img src="{{ asset('images/icons/checkout-visa.png') }}" class="width-50">
                                <img src="{{ asset('images/icons/checkout-mastercard.png') }}" class="width-50">
                                <img src="{{ asset('images/icons/checkout-jcb.png') }}" class="width-50">
                                <img src="{{ asset('images/icons/checkout-american.png') }}" class="width-50">
                                &nbsp;<br>
                                <input type="radio" name="payment_method" class="payment_method" value="bank_transfer"/>
                                <span style="font-size: 16px;">Bank Transfer</span>
                                <img src="{{ asset('images/icons/checkout-banktransfer.png') }}" class="width-50">
                                {{--Pay with Virtual Account--}}
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row border">
                    {{--<div class="col-xs-6 col-sm-12 col-md-6">--}}
                    {{--<div class="col-xs-6 col-sm-12 col-md-6">--}}
                    {{--<input type="checkbox" name="TC_Aggrement" value="ship" class=""/> I've read and accept the T&C's--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    <div class="col-lg-offset-6 col-xs-6 col-sm-12 col-md-6">
                        <div class="col-xs-12 col-sm-12 col-md-12" style="text-align: right;">
                            <a href="{{ route('orders') }}">
                                <button type="button" class="btn btn--secondary btn--bordered" style="font-size: 11px; height: 31.5px; width: 120px;line-height: 0px; border: 1px solid #282828;">
                                    CANCEL
                                </button>
                            </a>
                            <button type="button" id="pay-button" class="btn btn--secondary btn--bordered" style="font-size: 11px; height: 31.5px; width: 120px;line-height: 0px; border: 1px solid #282828;">PAY NOW</button>
                        </div>
                    </div>
                </div>
            </div>
        {{--</form>--}}
    </section>
@endsection

@section('styles')
    <style>
        .border-bottom-black {
            border-bottom: 1px solid #000000 !important;
            padding-bottom: 10px;
        }
        .right{
            text-align: right;
        }
    </style>
@endsection
@section ('scripts-top')
    <script type="text/javascript"
            src="{{$snapURL}}"
            data-client-key="{{$clientKey}}"></script>
@endsection
@section('scripts')
    <script type="text/javascript">
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
        // var payButton = document.getElementById('pay-button');
        // payButton.addEventListener('click', function () {
        //     snap.pay('<SNAP_TOKEN>');
        // });

        $(document).on('click', '#pay-button', function (e) {
            $('#pay-button').attr("disabled", true);

            var orderId = "{{$order->id}}";
            // var paymentMethod = $('.payment_method').val();
            var paymentMethod = $("input[name='payment_method']:checked").val();
            var voucher = $('#voucher_applied').val();
            var voucherAmount = $('#voucher_amount').val();
            var grandTotal = $('#grand_total').val();
            var dustbagOption = $('#dustbag_option').val();
            var snapToken;
            // Request get token to your server & save result to snapToken variable
            // alert(paymentMethod);
            if(paymentMethod==="credit_card"){
                snap.show();
                $.ajax({
                    type: 'POST',
                    url: '{{ route('submit.checkout') }}',
                    datatype : "application/json",
                    data: {
                        '_token': '{{ csrf_token() }}',
                        'order': orderId,
                        'payment_method': paymentMethod,
                        'voucher' : voucher,
                        'voucher_amount' : voucherAmount,
                        'dustbag_option' : dustbagOption,
                        'grand_total' : grandTotal
                    }, // no need to stringify
                    success: function (result) {
                        if (typeof result == "string")
                            result = JSON.parse(result);
                        if (result.success) {
                            snapToken = result.success;
                            // snap.pay(snapToken);

                            snap.pay(snapToken, {
                                onSuccess: function(result2){
                                    var order_id = result2.order_id;
                                    var new_order_id = order_id.replace(new RegExp('/', 'g'), '_');
                                    var url = "{{env('SERVER_HOST_URL')}}" + "checkout-success/" + new_order_id;
                                    // console.log('success');console.log(result2);
                                    window.location = url;
                                },
                                onPending: function(result) {
                                    console.log('pending');console.log(result);
                                },
                                onError: function(result2) {
                                    console.log('error');console.log(result2);
                                },
                                onClose: function() {
                                    $('#pay-button').attr("disabled", false);
                                }
                            });
                        } else {
                            snap.hide();
                            $('#pay-button').attr("disabled", false);
                        }
                    }
                });
            }
            else{
                $.ajax({
                    type: 'POST',
                    url: '{{ route('submit.checkout') }}',
                    datatype : "application/json",
                    data: {
                        '_token': '{{ csrf_token() }}',
                        'order': orderId,
                        'payment_method': paymentMethod,
                        'voucher' : voucher,
                        'voucher_amount' : voucherAmount,
                        'dustbag_option' : dustbagOption,
                        'grand_total' : grandTotal
                    }, // no need to stringify
                    success: function (result) {
                        if (typeof result == "string")
                            result = JSON.parse(result);
                        if (result.success) {
                            snapToken = result.success;
                            window.location = snapToken;
                            $('#pay-button').attr("disabled", false);
                        }
                    }
                });
            }
        });

    </script>
    <script type="text/javascript">
        //Set input Restriction
        // function setInputFilter(textbox, inputFilter) {
        //     ["input", "keydown", "keyup", "mousedown", "mouseup", "select", "contextmenu", "drop"].forEach(function(event) {
        //         textbox.addEventListener(event, function() {
        //             if (inputFilter(this.value)) {
        //                 this.oldValue = this.value;
        //                 this.oldSelectionStart = this.selectionStart;
        //                 this.oldSelectionEnd = this.selectionEnd;
        //             } else if (this.hasOwnProperty("oldValue")) {
        //                 this.value = this.oldValue;
        //                 this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
        //             }
        //         });
        //     });
        // }

        // setInputFilter(document.getElementById("card_date"), function(value) {
        //     return /^-?\d*[/,]?\d*$/.test(value); });

        $('#card_date').on('input', function() {
            var tmp = $(this).val();
            if(tmp.length === 2){
                $(this).val(tmp + '/');
            }
        });

        $('#apply-voucher').on("click", function () {
            var orderId = "{{$order->id}}";
            var voucherCode = $('#voucher').val();
            var voucherCodeApplied = $('#voucher_applied').val();
            // alert(name);
            if(voucherCode != ""){
                if(voucherCodeApplied.toUpperCase() == voucherCode.toUpperCase()){
                    $('#voucher_response').text("Voucher already applied");
                }
                else{
                    $.ajax({
                        type: 'POST',
                        url: '{{ route('check.voucher') }}',
                        datatype : "application/json",
                        data: {
                            '_token': '{{ csrf_token() }}',
                            'voucher-code': voucherCode,
                            'order_id': orderId
                        }, // no need to stringify
                        success: function (result) {
                            if (typeof result == "string")
                                result = JSON.parse(result);
                            if (result.success) {
                                $('#voucher_response').show();
                                $('#voucher_response').css('color', 'green');
                                $('#voucher_response').text("Enjoy Your Voucher!");
                                var voucherData = result.success;
                                var subtotal = $('#subtotal').val();
                                var grandTotal = $('#grand_total').val();
                                // alert(grandTotal);
                                if(voucherData != 0){
                                    var newTotal = grandTotal - voucherData;
                                    // $('#grand_total').val(newTotal);
                                    $('#grand_total_span').text(rupiahFormat(newTotal));
                                    $('#voucher_amount').val(voucherData);
                                    $('#voucher_amount_span').text(rupiahFormat(voucherData));
                                }
                                $('#voucher_applied').val(voucherCode);
                            } else {
                                $('#voucher_response').show();
                                $('#voucher_response').css('color', 'red');
                                $('#voucher_response').text(result.errors);
                            }
                        }
                    });
                }
            }
        });
    </script>
@endsection
