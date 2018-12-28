@extends('layouts.frontend')

@section('content')

    <section id="shopcart" class="shop shop-cart bg-white">
        <div class="container" style="color: black;">
            <form method="POST" action="{{ route('submit.cart') }}">
                @csrf

                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <h1>Your Cart</h1>
                        <div class="cart-table table-responsive">
                            <table class="table">
                                <thead>
                                <tr class="cart-product" style="background-color: white;border-top: 2px solid black;border-bottom: 2px solid black;">
                                    <th class="cart-product-item">Item</th>
                                    <th class="cart-product-item">Description</th>
                                    <th class="cart-product-item">Colour</th>
                                    <th class="cart-product-quantity">Quantity</th>
                                    <th class="cart-product-item">Customized</th>
                                    <th class="cart-product-total">Total</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @if($carts != null && $flag == 1)
                                    @foreach($carts as $cart)
                                        <tr class="cart-product">
                                            <td class="cart-product-item">
                                                <img src="{{ asset('/images/shop/thumb/1.jpg') }}" alt="product"/>
                                            </td>
                                            <td class="cart-product-item">
                                                {{ $cart->product->name }}
                                                <input type="hidden" name="id[]" value="{{ $cart->id }}"/>
                                            </td>
                                            <td class="cart-product-item">{{ $cart->product->colour }}</td>
                                            <td class="cart-product-quantity">
                                                <div class="product-quantity">
                                                    <a href="#"><i class="fa fa-minus" onclick="updateQty('{{ $cart->id }}', 'min')"></i></a>
                                                    <input type="text" value="{{ $cart->qty }}" id="qty{{ $cart->id }}" name="qty[{{ $cart->id }}]" readonly>
                                                    <a href="#"><i class="fa fa-plus" onclick="updateQty('{{ $cart->id }}', 'plus')"></i></a>
                                                </div>
                                            </td>
                                            <td class="cart-product-item">{!! $cart->description  !!} </td>
                                            <td class="cart-product-total" id="total_price{{ $cart->id }}">{{ $cart->total_price }}</td>
                                            <td>
                                                <i class="fa fa-close delete" data-toggle="modal" data-id="{{ $cart->id }}" data-target="#myModal"></i>
                                                <input type="hidden" value="{{ $cart->price }}" id="price{{ $cart->id }}">
                                            </td>
                                        </tr>
                                    @endforeach
                                @elseif($carts != null && $flag == 2)
                                    @foreach($carts as $cart)
                                        <tr class="cart-product">
                                            <td class="cart-product-item">
                                                <img src="{{ asset('/images/shop/thumb/1.jpg') }}" alt="product"/>
                                            </td>
                                            <td class="cart-product-item">
                                                {{ $cart['item']['product']['name'] }}
                                                <input type="hidden" name="id[]" value="{{ $cart['item']['product_id'] }}"/>
                                            </td>
                                            <td class="cart-product-item">{{ $cart['item']['product']['colour'] }}</td>
                                            <td class="cart-product-quantity">
                                                <div class="product-quantity">
                                                    <a href="#"><i class="fa fa-minus" onclick="updateQty('{{ $cart['item']['product_id'] }}', 'min')"></i></a>
                                                    <input type="text" value="{{ $cart['qty'] }}" id="qty{{ $cart['item']['product_id'] }}" name="qty[{{ $cart['item']['product_id'] }}]" readonly>
                                                    <a href="#"><i class="fa fa-plus" onclick="updateQty('{{ $cart['item']['product_id'] }}', 'plus')"></i></a>
                                                </div>
                                            </td>
                                            <td class="cart-product-item">{!! $cart['item']['description'] !!}</td>
                                            <td class="cart-product-total" id="total_price{{ $cart['item']['product_id'] }}">{{ $cart['item']['price'] }}</td>
                                            <td>
                                                <i class="fa fa-close delete" data-toggle="modal" data-id="{{ $cart['item']['product_id'] }}" data-target="#myModal"></i>
                                                <input type="hidden" value="{{ $cart['item']['price'] }}" id="price{{ $cart['item']['product_id'] }}">
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr class="cart-product">
                                        <td colspan="6">Sorry You haven't put anything in the cart yet!</td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div><!-- .row end -->
                
                <div class="row">
                    <!-- Coupon Side -->
                    <div class="col-xs-12 col-sm-12 col-md-8">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <span>Have a coupon code?</span>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-8">
                            <input type="text" class="form-control input-bordered" name="coupon" id="coupon" placeholder="TYPE CODE HERE" style="text-align: center"/>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-4">
                            <button type="submit" class="btn btn--secondary btn--bordered" style="font-size: 11px; height: 31.5px; width: 100%;line-height: 0px; border: 1px solid #282828;">APPLY CODE</button>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 hidden-xs" style="margin-top: -5%;">
                            <hr style="height:1px;border:none;color:#eee;background-color:#eee;" />
                        </div>
                    </div>

                    <!-- Subtotal Side -->
                    <div class="col-xs-12 col-sm-12 col-md-4" style="margin-left: -1%; font-weight: 500;">
                        <div class="col-xs-6 col-sm-12 col-md-6">
                            SUBTOTAL
                        </div>
                        <div class="col-xs-6 col-sm-12 col-md-6" style="text-align: right;">
                            ${{$totalPrice}} USD
                        </div>
                        <div class="col-xs-6 col-sm-12 col-md-6">
                            SHIPPING
                        </div>
                        <div class="col-xs-6 col-sm-12 col-md-6" style="text-align: right;">
                            $00.00 USD
                        </div>
                        <div class="col-xs-6 col-sm-12 col-md-6">
                            TAX
                        </div>
                        <div class="col-xs-6 col-sm-12 col-md-6" style="text-align: right;">
                            $00.00 USD
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 hidden-xs" style="margin-top: -1%;">
                            <hr style="height:1px;border:none;color:#eee;background-color:#eee;" />
                        </div>
                        <div class="col-xs-6 col-sm-12 col-md-6">
                            TOTAL
                        </div>
                        <div class="col-xs-6 col-sm-12 col-md-6" style="font-size: 14px; text-align: right;" >
                            $160.00 USD
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 hidden-xs" style="margin-top: -3px;">
                            <hr style="height:1px;border:none;color:black;background-color:#333;" />
                        </div>
                        <div class="col-xs-6 col-sm-12 col-md-6">
                            <a href="{{ route('home') }}"><button type="button" class="btn btn--secondary btn--bordered" style="font-size: 11px; height: 31.5px; width: 130px;line-height: 0px; border: 1px solid #282828;">CONTINUE SHOPPING</button></a>
                        </div>
                        <div class="col-xs-6 col-sm-12 col-md-6" style="text-align: right;">
                            <button type="submit" class="btn btn--secondary btn--bordered" style="font-size: 11px; height: 31.5px; width: 120px;line-height: 0px; border: 1px solid #282828;">PROCEED</button>
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
                        <input type="hidden" id="cartId" name="cartId" />
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

@section('scripts')
    <script type="text/javascript">
        function updateQty(identifier, state){
            //if it plus
            var tmpQty;
            if(state === 'plus'){
                var qty = 'qty' + identifier;
                tmpQty = parseInt($('#' + qty).val());
                tmpQty++;
                $('#' + qty).val(tmpQty);
            }
            else if(state === 'min'){
                var qty = 'qty' + identifier;
                tmpQty = parseInt($('#' + qty).val());
                if(tmpQty != 0){
                    tmpQty--;
                    $('#' + qty).val(tmpQty);
                }
            }

            //Update Price
            var price = 'price' + identifier;
            var totalPrice = 'total_price' + identifier;
            var tmpPrice = parseFloat($('#' + price).val());
            var tmpTotalPrice = tmpQty * tmpPrice;
            $('#' + totalPrice).html(tmpTotalPrice);
        }

        $(document).on("click", ".delete", function () {
            var cartId = $(this).data('id');
            $(".modal-body #cartId").val( cartId );
            // As pointed out in comments,
            // it is superfluous to have to manually call the modal.
            // $('#addBookDialog').modal('show');
        });
    </script>
@endsection
