@extends('layouts.frontend')

@section('pageTitle', 'Shopping Cart | NAMA')
@section('content')

    <!-- DESKTOP START-->
    <section id="shopcart" class="shop shop-cart bg-white hidden-sm hidden-xs">
        <div class="container" style="color: black;">
            <form method="POST" action="{{ route('submit.cart') }}">
                @csrf

                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <h1>Your Cart</h1>
                        <span style="font-size:16px; color: red;"> Transactions between 23 December 2019- 5 January 2020 <br> will be processed on 6 January 2020 <br></span>
                        <br>
                        <div class="cart-table table-responsive">
                            <table class="table">
                                <thead>
                                <tr class="cart-product" style="background-color: white;border-top: 2px solid black;border-bottom: 2px solid black;">
                                    <th class="cart-product-item">Item</th>
                                    <th class="cart-product-item">Description</th>
                                    <th class="cart-product-item">Color</th>
                                    <th class="cart-product-quantity">Quantity</th>
                                    <th class="cart-product-item">Customization</th>
                                    <th class="cart-product-total">Total</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @if($carts != null && $flag == 1)
                                    @foreach($carts as $cart)
                                        <tr class="cart-product">
                                            <td class="cart-product-item center pb-40 pt-40">
{{--                                                @php($productImage = \App\libs\Utilities::GetProductMainImage($cart->product_id))--}}
                                                @php($productImage = \App\Models\ProductImage::where('product_id', $cart->product_id)->where('is_main_image', 1)->first())
                                                <img src="{{ asset('storage/products/'.$productImage->path) }}" alt="product" style="width: 100px"/>
                                            </td>
                                            <td class="cart-product-item center pb-40 pt-40">
                                                {{ $cart->product->name }}
                                                <input type="hidden" name="id[]" value="{{ $cart->id }}"/>
                                            </td>
                                            <td class="cart-product-item center pb-40 pt-40">{{ $cart->product->colour }}</td>
                                            <td class="cart-product-quantity pb-40 pt-40">
                                                <div class="product-quantity">
                                                    <a><i class="fa fa-minus" onclick="updateQty('{{ $cart->id }}', 'min')"></i></a>
                                                    <input type="text" value="{{ $cart->qty }}" id="qty{{ $cart->id }}" name="qty[{{ $cart->id }}]" readonly>
                                                    <a><i class="fa fa-plus" onclick="updateQty('{{ $cart->id }}', 'plus')"></i></a>
                                                </div>
                                            </td>
                                            <td class="cart-product-item pb-40 pt-40">{!! $cart->description  !!} </td>
                                            <td class="cart-product-total pb-40 pt-40">
                                                {{env('KURS_IDR')}} <span id="total_price{{ $cart->id }}">{{ number_format($cart->total_price, 0, ",", ".") }}</span>
                                                <input id="total_price_span{{$cart->id}}" type="hidden" class="priceForTotal" value="{{$cart->total_price}}">
                                            </td>
                                            <td class="pb-40 pt-40">
                                                <i class="fa fa-close delete" data-toggle="modal" data-id="{{ $cart->id }}" data-target="#myModal"></i>
                                                <input type="hidden" value="{{ $cart->price }}" id="price{{ $cart->id }}">
                                            </td>
                                        </tr>
                                    @endforeach
                                @elseif($carts != null && $flag == 2)
                                    @foreach($carts as $cart)
                                        <tr class="cart-product">
                                            <td class="cart-product-item center pb-40 pt-40">
                                                @php($productImage = \App\Models\ProductImage::where('product_id', $cart['product_id'])->where('is_main_image', 1)->first())
                                                <img src="{{ asset('storage/products/'.$productImage->path) }}" alt="product" style="width: 100px"/>
                                            </td>
                                            <td class="cart-product-item center pb-40 pt-40">
                                                {{ $cart['item']['product']['name'] }}
                                                <input type="hidden" name="id[]" value="{{ $cart['product_id'] }}"/>
                                            </td>
                                            <td class="cart-product-item center pb-40 pt-40">{{ $cart['item']['product']['colour'] }}</td>
                                            <td class="cart-product-quantity pb-40 pt-40">
                                                <div class="product-quantity">
                                                    <a><i class="fa fa-minus" onclick="updateQty('{{ $cart['product_id'] }}', 'min')"></i></a>
                                                    <input type="text" value="{{ $cart['qty'] }}" id="qty{{ $cart['product_id'] }}" name="qty[{{ $cart['product_id'] }}]" readonly>
                                                    <a><i class="fa fa-plus" onclick="updateQty('{{ $cart['product_id'] }}', 'plus')"></i></a>
                                                </div>
                                            </td>
                                            <td class="cart-product-item pb-40 pt-40">{!! $cart['item']['description'] !!}</td>
                                            <td class="cart-product-total pb-40 pt-40">
                                                {{env('KURS_IDR')}} <span id="total_price{{ $cart['product_id'] }}">{{ number_format($cart['item']['price'], 0, ",", ".") }}</span>
                                                <input id="total_price_span{{$cart['product_id']}}" type="hidden" class="priceForTotal" value="{{$cart['item']['price']}}">
                                            </td>
                                            {{--<td class="cart-product-total" id="total_price{{ $cart['product_id'] }}">{{ $cart['item']['price'] }}</td>--}}
                                            <td class="pb-40 pt-40">
                                                <i class="fa fa-close delete" data-toggle="modal" data-id="{{ $cart['product_id'] }}" data-target="#myModal"></i>
                                                <input type="hidden" value="{{ $cart['item']['price'] }}" id="price{{ $cart['product_id'] }}">
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr class="cart-product">
                                        <td colspan="6">Sorry you haven't put anything in the cart yet!</td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div><!-- .row end -->

                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-8">
                        <div class="col-md-12">
                            @if($errors->count() > 0)
                                @foreach($errors->all() as $error)
                                    <span class="form-message">
                                        <strong> {{ $error }} </strong>
                                        <br/>
                                    </span>
                                @endforeach
                            @endif
                        </div>
                    </div>

                    <!-- Subtotal Side -->
                    <div class="col-xs-12 col-sm-12 col-md-4" style="margin-left: -1%; font-weight: 500;">
                        <div class="col-xs-6 col-sm-12 col-md-6">
                            SUBTOTAL
                        </div>
                        <div class="col-xs-6 col-sm-12 col-md-6" style="text-align: right;">
                            {{env('KURS_IDR')}} <span id="subtotal_span">{{number_format($totalPrice, 0, ",", ".") }}</span>
                            <input type="hidden" id="subtotal" value="{{$totalPrice}}" >
                        </div>
                        <div class="col-xs-6 col-sm-12 col-md-6">
                            VOUCHER
                        </div>
                        <div class="col-xs-6 col-sm-12 col-md-6" style="text-align: right;">
                            {{env('KURS_IDR')}} <span id="voucher_amount_span">0</span>
                            <input type="hidden" name="subtotal" id="voucher_amount" value="{{$totalPrice}}" >
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 hidden-xs" style="margin-top: -1%;">
                            <hr style="height:1px;border:none;color:#eee;background-color:#eee;" />
                        </div>
                        <div class="col-xs-6 col-sm-12 col-md-6">
                            TOTAL
                        </div>
                        <div class="col-xs-6 col-sm-12 col-md-6" style="font-size: 14px; text-align: right;" >
                            {{env('KURS_IDR')}} <span id="grand_total_span">{{number_format($totalPrice, 0, ",", ".") }}</span>
                            <input type="hidden" id="grand_total" value="{{$totalPrice}}" >
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 hidden-xs" style="margin-top: -3px;">
                            <hr style="height:1px;border:none;color:black;background-color:#333;" />
                        </div>
                        <div class="col-xs-6 col-sm-12 col-md-6">
                            <a href="{{ route('home') }}"><button type="button" class="btn btn--secondary btn--bordered" style="font-size: 11px; height: 31.5px; width: 130px;line-height: 0px; border: 1px solid #282828;">CONTINUE SHOPPING</button></a>
                        </div>
                        <div class="col-xs-6 col-sm-12 col-md-6" style="text-align: right;">
                            @if($totalPrice == 0)
                                @php($disabled = 'disabled')
                            @else
                                @php($disabled = '')
                            @endif
                            <button type="submit" class="btn btn--secondary btn--bordered" style="font-size: 11px; height: 31.5px; width: 120px;line-height: 0px; border: 1px solid #282828;" {{$disabled}}>PROCEED</button>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12" style="margin-top: 10px; text-align: justify;">
                            * Note: Shipping and taxes will be updated during checkout
                            based on your billing and shipping information.
                        </div>
                    </div>
                </div>
            </form>
        </div><!-- .container end -->
    </section>
    <!-- DESKTOP END-->

    <!-- MOBILE START-->
    <section id="shopcart" class="shop shop-cart bg-white hidden-md hidden-lg">
        <div class="container" style="color: black;">
            <form method="POST" action="{{ route('submit.cart') }}">
                @csrf

                <div class="row">
                    <h1 style="padding-left: 7%;">Your Cart</h1>
                        @if($carts != null && $flag == 1)
                            @foreach($carts as $cart)
                            <div class="col-xs-12 col-sm-12 col-md-12 border-b" style="padding-bottom: 6%;">
                                    <div class="col-xs-6 col-sm-6">
                                        @php($productImage = \App\Models\ProductImage::where('product_id', $cart->product_id)->where('is_main_image', 1)->first())
                                        <img src="{{ asset('storage/products/'.$productImage->path) }}" alt="product" style="width: 100%"/>
                                        <br>
                                        <i class="fa fa-close delete_mobile font-16" onclick="deleteItem('{{ $cart->id }}')"></i>
                                        {{--<i class="fa fa-close delete font-16 pt-20" data-toggle="modal" data-id="{{ $cart->id }}" data-target="#myModal"></i>--}}
                                        <input type="hidden" value="{{ $cart->price }}" id="price_mobile{{ $cart->id }}">
                                    </div>
                                    <div class="col-xs-6 col-sm-6">
                                        {{ $cart->product->name }}, {{ $cart->product->colour }}
                                        <input type="hidden" name="id[]" value="{{ $cart->id }}"/>
                                        <br>
                                        {!! $cart->description  !!}

                                        <br>
                                        TOTAL<br>
                                        {{env('KURS_IDR')}} <span id="total_price_mobile{{ $cart->id }}">{{ number_format($cart->total_price, 0, ",", ".") }} </span>
                                        <input id="total_price_mobile_span{{$cart->id}}" type="hidden" class="priceForTotalMobile" value="{{$cart->total_price}}">

                                        <div class="product-quantity">
                                            <a><i class="fa fa-minus font-12 pb-10 pt-10" onclick="updateQty('{{ $cart->id }}', 'min')"></i></a>
                                            <input type="text" value="{{ $cart->qty }}" id="qty_mobile{{ $cart->id }}" name="qty[{{ $cart->id }}]" readonly style="margin:6% 0 6% 0">
                                            <a><i class="fa fa-plus font-12 pb-10 pt-10" onclick="updateQty('{{ $cart->id }}', 'plus')"></i></a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @elseif($carts != null && $flag == 2)
                            @foreach($carts as $cart)
                            <div class="col-xs-12 col-sm-12 col-md-12 border-b" style="padding-bottom: 6%;">
                                    <div class="col-xs-6 col-sm-6" style="font-size: 20px; text-align: center;">
                                        @php($productImage = \App\Models\ProductImage::where('product_id', $cart['product_id'])->where('is_main_image', 1)->first())
                                        <img src="{{ asset('storage/products/'.$productImage->path) }}" alt="product" style="width: 100%"/>
                                        <br>
                                        {{--<i class="fa fa-close delete font-16" data-toggle="modal" data-id="{{ $cart['product_id'] }}" data-target="#myModal"></i>--}}
                                        <i class="fa fa-close delete_mobile font-16" onclick="deleteItem('{{ $cart['product_id'] }}')"></i>
                                        <input type="hidden" value="{{ $cart['item']['price'] }}" id="price_mobile{{ $cart['product_id'] }}">
                                    </div>
                                    <div class="col-xs-6 col-sm-6">
                                        {{ $cart['item']['product']['name'] }}, {{ $cart['item']['product']['colour'] }}
                                        <input type="hidden" name="id[]" value="{{ $cart['product_id'] }}"/>
                                        <br>
                                        {!! $cart['item']['description'] !!}

                                        <br>
                                        TOTAL<br>
                                        {{env('KURS_IDR')}} <span id="total_price_mobile{{ $cart['product_id'] }}">{{ number_format($cart['item']['price'], 0, ",", ".") }} </span>
                                        <input id="total_price_mobile_span{{ $cart['product_id'] }}" type="hidden" class="priceForTotalMobile" value="{{$cart['item']['price']}}">

                                        <div class="product-quantity">
                                            <a><i class="fa fa-minus font-12" onclick="updateQty('{{ $cart['product_id'] }}', 'min')"></i></a>
                                            <input type="text" value="{{ $cart['qty'] }}" id="qty_mobile{{ $cart['product_id'] }}" name="qty[{{ $cart['product_id'] }}]" readonly style="margin:6% 0 6% 0">
                                            <a><i class="fa fa-plus font-12" onclick="updateQty('{{ $cart['product_id'] }}', 'plus')"></i></a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <h3>Sorry you haven't put anything in the cart yet!</h3>
                            </div>
                            <tr class="cart-product">
                                <td colspan="6"></td>
                            </tr>
                        @endif
                </div><!-- .row end -->

                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-8">
                        <div class="col-md-12">
                            @if($errors->count() > 0)
                                @foreach($errors->all() as $error)
                                    <span class="form-message">
                                        <strong> {{ $error }} </strong>
                                        <br/>
                                    </span>
                                @endforeach
                            @endif
                        </div>
                    </div>

                    <!-- Subtotal Side -->
                    <div class="col-xs-12 col-sm-12 col-md-4" style="margin-left: -1%; font-weight: 500;">
                        <div class="col-xs-6 col-sm-12 col-md-6">
                            SUBTOTAL
                        </div>
                        <div class="col-xs-6 col-sm-12 col-md-6" style="text-align: right;">
                            {{env('KURS_IDR')}} <span id="subtotal_mobile_span">{{number_format($totalPrice, 0, ",", ".") }}</span>
                            <input type="hidden" id="subtotal_mobile" value="{{$totalPrice}}" >
                        </div>
                        <div class="col-xs-6 col-sm-12 col-md-6">
                            VOUCHER
                        </div>
                        <div class="col-xs-6 col-sm-12 col-md-6" style="text-align: right;">
                            {{env('KURS_IDR')}} <span id="voucher_amount_span">0</span>
                            <input type="hidden" name="subtotal" id="voucher_amount" value="{{$totalPrice}}" >
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 hidden-xs" style="margin-top: -1%;">
                            <hr style="height:1px;border:none;color:#eee;background-color:#eee;" />
                        </div>
                        <div class="col-xs-6 col-sm-12 col-md-6">
                            TOTAL
                        </div>
                        <div class="col-xs-6 col-sm-12 col-md-6" style="font-size: 14px; text-align: right;" >
                            {{env('KURS_IDR')}} <span id="grand_total_mobile_span">{{number_format($totalPrice, 0, ",", ".") }}</span>
                            <input type="hidden" id="grand_total_mobile" value="{{$totalPrice}}" >
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 hidden-xs" style="margin-top: -3px;">
                            <hr style="height:1px;border:none;color:black;background-color:#333;" />
                        </div>
                        <div class="col-xs-6 col-sm-12 col-md-6" style="padding-top: 4%;">
                            <a href="{{ route('home') }}"><button type="button" class="btn btn--secondary btn--bordered" style="font-size: 11px; height: 31.5px; width: 130px;line-height: 0px; border: 1px solid #282828;">CONTINUE SHOPPING</button></a>
                        </div>
                        <div class="col-xs-6 col-sm-12 col-md-6" style="text-align: right;padding-top: 4%;">
                            @if($totalPrice == 0)
                                @php($disabled = 'disabled')
                            @else
                                @php($disabled = '')
                            @endif
                            <button type="submit" class="btn btn--secondary btn--bordered" style="font-size: 11px; height: 31.5px; width: 120px;line-height: 0px; border: 1px solid #282828;" {{$disabled}}>PROCEED</button>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12" style="margin-top: 10px; text-align: justify;">
{{--                            * Note: Shipping and taxes will be updated during checkout--}}
{{--                            based on your billing and shipping information.--}}
                        </div>
                    </div>
                </div>
            </form>
        </div><!-- .container end -->
    </section>
    <!-- MOBILE END-->

    <!-- Modal -->
    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <form method="POST" action="{{ route('delete.cart') }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Delete Item</h4>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure want to delete this?</p>
                        <input type="hidden" id="cartId" name="cartId"/>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger">Yes</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        .bg-white{
            padding-bottom:0 !important;
        }
    </style>
@endsection

@section('scripts')
    <script type="text/javascript">
        function deleteItem(id){
            $("#myModal").modal('show');
            $("#cartId").val(id);
        }

        function updateQty(identifier, state){
            //if it plus
            var tmpQty;
            if(state === 'plus'){
                var qty = 'qty' + identifier;
                tmpQty = parseInt($('#' + qty).val());
                tmpQty++;
                $('#' + qty).val(tmpQty);

                var qtyMobile = 'qty_mobile' + identifier;
                tmpQtyMobile = parseInt($('#' + qtyMobile).val());
                tmpQtyMobile++;
                $('#' + qtyMobile).val(tmpQtyMobile);
            }
            else if(state === 'min'){
                var qty = 'qty' + identifier;
                tmpQty = parseInt($('#' + qty).val());
                tmpQty--;

                var qtyMobile = 'qty_mobile' + identifier;
                tmpQtyMobile = parseInt($('#' + qtyMobile).val());
                tmpQtyMobile--;
                if(tmpQty > 0 || tmpQtyMobile > 0){
                    $('#' + qty).val(tmpQty);
                    $('#' + qtyMobile).val(tmpQtyMobile);
                }
            }
            if(tmpQty > 0){

                //Update Price
                var price = 'price' + identifier;
                var priceMobile = 'price_mobile' + identifier;
                var totalPrice = 'total_price' + identifier;
                var totalPriceMobile = 'total_price_mobile' + identifier;
                var tmpPrice = parseFloat($('#' + price).val());
                var tmpPriceMobile = parseFloat($('#' + priceMobile).val());
                var tmpTotalPrice = tmpQty * tmpPrice;
                var tmpTotalPriceMobile = tmpQty * tmpPriceMobile;
                var totalPriceSpan = 'total_price_span' + identifier;
                var totalPriceMobileSpan = 'total_price_mobile_span' + identifier;
                $('#' + totalPriceSpan).val(tmpTotalPrice);
                $('#' + totalPriceMobileSpan).val(tmpTotalPriceMobile);

                tmpTotalPrice = tmpTotalPrice.toLocaleString(
                    "de-DE"
                );
                tmpTotalPriceMobile = tmpTotalPriceMobile.toLocaleString(
                    "de-DE"
                );
                $('#' + totalPrice).html(tmpTotalPrice);
                $('#' + totalPriceMobile).html(tmpTotalPriceMobile);

                //Update Subtotal Price and grand total price
                var grandTotalTmp = 0;
                $(".priceForTotal").each(function () {
                    var subtotalTmp = $(this).val();
                    grandTotalTmp = parseFloat(grandTotalTmp) + parseFloat(subtotalTmp);
                });
                var grandTotalMobileTmp = 0;
                $(".priceForTotalMobile").each(function () {
                    var subtotalTmp = $(this).val();
                    grandTotalMobileTmp = parseFloat(grandTotalMobileTmp) + parseFloat(subtotalTmp);
                });

                $('#subtotal').val(grandTotalTmp);
                $('#subtotal_mobile').val(grandTotalMobileTmp);
                $('#grand_total').val(grandTotalTmp);
                $('#grand_total_mobile').val(grandTotalMobileTmp);
                grandTotalTmp = grandTotalTmp.toLocaleString(
                    "de-DE"
                );
                grandTotalMobileTmp = grandTotalMobileTmp.toLocaleString(
                    "de-DE"
                );
                $('#subtotal_span').text(grandTotalTmp);
                $('#subtotal_mobile_span').text(grandTotalMobileTmp);
                $('#grand_total_span').text(grandTotalTmp);
                $('#grand_total_mobile_span').text(grandTotalMobileTmp);
            }
        }

        $(document).on("click", ".delete", function () {
            var cartId = $(this).data('id');
            $(".modal-body #cartId").val( cartId );
            // As pointed out in comments,
            // it is superfluous to have to manually call the modal.
            // $('#addBookDialog').modal('show');
        });
        $('#apply-voucher').on("click", function () {
            var voucherCode = $('#voucher').val();
            var voucherCodeApplied = $('#voucher_applied').val();
            // alert(name);
            if(voucherCode != ""){
                if(voucherCodeApplied == voucherCode){
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
                        }, // no need to stringify
                        success: function (result) {
                            if (typeof result == "string")
                                result = JSON.parse(result);
                            if (result.success) {
                                $('#voucher_response').show();
                                $('#voucher_response').text("Voucher is applied");
                                var voucherData = result.success.split('#');
                                var subtotal = $('#subtotal').val();
                                var grandTotal = $('#grand_total').val();
                                // alert(grandTotal);
                                if(voucherData[0] != 0){
                                    var newTotal = grandTotal - voucherData[0];
                                    $('#grand_total').val(newTotal);
                                    $('#grand_total_span').text(newTotal);
                                    $('#voucher_amount').val(voucherData[0]);
                                    $('#voucher_amount_span').text(voucherData[0]);
                                }
                                if(voucherData[1] != 0){
                                    var voucherAmount = (subtotal * voucherData[1])/100;
                                    var newTotal = grandTotal - voucherAmount;
                                    $('#grand_total').val(newTotal);
                                    $('#grand_total_span').text(newTotal);
                                    $('#voucher_amount').val(voucherAmount);
                                    $('#voucher_amount_span').text(voucherAmount);
                                }
                                $('#voucher_applied').val(voucherCode);
                            } else {
                                $('#voucher_response').show();
                                $('#voucher_response').text("Voucher is not valid");
                            }
                        }
                    });
                }
            }
        });
    </script>
@endsection
