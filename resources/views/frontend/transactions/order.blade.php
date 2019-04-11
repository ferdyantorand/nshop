@extends('layouts.frontend')

@section('pageTitle', 'Order | NAMA')
@section('content')
    <section class="bg-white">
            <div class="container">
                <div class="row" style="margin-bottom:5%;">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="col-md-12">
                            <h1>Order {{$order->order_number}}</h1>
                            <hr style="height:1px;border:none;color:#333;background-color:#333;" />
                            <br/>
                        </div>
                        <div class="col-md-12 mb-20">
                            <div class="col-md-2"><h5>Date</h5></div>
                            <div class="col-md-10"><h5>: {{$order->created_at}}</h5> </div>
                        </div>
                        <div class="col-md-12 mb-20">
                            <div class="col-md-2"><h5>Shipping</h5></div>
                            <div class="col-md-10"><h5>: {{$order->shipping_option}}</h5> </div>
                        </div>
                        <div class="col-md-12 mb-20">
                            <div class="col-md-2"><h5>Status</h5></div>
                            <div class="col-md-10"><h5>: {{$order->order_status->name}}</h5> </div>
                        </div>
                        @if($order->payment_option == 2)
                            <div class="col-md-12 mb-20">
                                <a href="{{route('admin.orders.bank_confirmation')}}">Confirm Payment</a>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="row">
                    <table class="table">
                        <tr>
                            <td>
                                <div class="col-md-12">
                                    <div class="col-md-7 border-bottom-black mb-20">
                                        <div class="col-md-2"></div>
                                        <div class="col-md-4">PRODUCT</div>
                                        <div class="col-md-2">QUANTITY</div>
                                        <div class="col-md-4 right">TOTAL</div>
                                    </div>
                                    <div class="col-md-5 border-bottom-black mb-20 hidden-xs hidden-sm">
                                        SHIPPING TO
                                    </div>
                                    <hr class="hidden-xs hidden-sm" style="height:1px;border:none;color:#333;background-color:#333;" />
                                    <div class="col-md-7">
                                        @foreach($orderProduct as $product)
                                            <div class="col-md-12 mb-10">
                                                <div class="col-md-2 ">
                                                    @php($productImage = \App\Models\ProductImage::where('product_id', $product->product->id)->where('is_main_image', 1)->first())
                                                    <img src="{{ asset('storage/products/'.$productImage->path) }}" alt="product" style="width: 100%"/>
                                                </div>
                                                <div class="col-md-4">
                                                    {{$product->product->name}}
                                                    Customized :<br>
                                                    {!! $product->product_info !!}
                                                </div>
                                                <div class="col-md-2">
                                                    {{$product->qty}}
                                                </div>
                                                <div class="col-md-4 right">
                                                    {{env('KURS_IDR')}} {{$product->grand_total_string}}
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="col-md-5">
                                        <div class="col-md-12 border-bottom-black mb-20 hidden-md hidden-lg">
                                            SHIPPING TO
                                        </div>
                                        <div class="col-md-12 border-bottom-black mb-20">
                                            {{$order->address->description}}, {{$order->address->street}},
                                            {{$order->address->suburb}}, {{$order->address->postal_code}}, {{$order->address->state}}, {{$order->address->country->name}},
                                        </div>
                                        <div class="col-md-12 mb-20">
                                            <div class="col-md-6 bold">SUBTOTAL</div>
                                            <div class="col-md-6 right">{{env('KURS_IDR')}} {{$order->sub_total_string}}</div>
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
                                            <div class="col-md-6 right">({{env('KURS_IDR')}} <span id="voucher_amount_span">{{$order->voucher_amount_string}}</span>)</div>
                                            <input type="hidden" name="voucher_amount" id="voucher_amount" value="0">
                                        </div>
                                        <div class="col-md-12 mb-20">
                                            <div class="col-md-6 bold"><h5>TOTAL</h5></div>
                                            <div class="col-md-6 right bold"><h5>{{env('KURS_IDR')}} {{$order->grand_total_string}}</h5></div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>

                    </table>
                </div>
            </div>
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

@section('scripts')
    <script type="text/javascript">
        //Set input Restriction
        function setInputFilter(textbox, inputFilter) {
            ["input", "keydown", "keyup", "mousedown", "mouseup", "select", "contextmenu", "drop"].forEach(function(event) {
                textbox.addEventListener(event, function() {
                    if (inputFilter(this.value)) {
                        this.oldValue = this.value;
                        this.oldSelectionStart = this.selectionStart;
                        this.oldSelectionEnd = this.selectionEnd;
                    } else if (this.hasOwnProperty("oldValue")) {
                        this.value = this.oldValue;
                        this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
                    }
                });
            });
        }

        setInputFilter(document.getElementById("card_date"), function(value) {
            return /^-?\d*[/,]?\d*$/.test(value); });

        $('#card_date').on('input', function() {
            var tmp = $(this).val();
            if(tmp.length === 2){
                $(this).val(tmp + '/');
            }
        });
    </script>
@endsection