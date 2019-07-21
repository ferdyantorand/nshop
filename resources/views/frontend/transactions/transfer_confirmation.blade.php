@extends('layouts.frontend')

@section('pageTitle', 'Transfer Confirmation | NAMA')
@section('content')
    <section class="bg-white">
            <div class="container">
                <div class="row" style="margin-bottom:5%;">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="col-md-12 hidden-xs hidden-sm">
                            <h1>{{$order->order_number}}</h1>
                            <hr style="height:1px;border:none;color:#333;background-color:#333;" />
                            <br/>
                        </div>
                        <div class="col-md-12 hidden-lg hidden-md text--center">
                            <h3>{{$order->order_number}}</h3>
                            <hr style="height:1px;border:none;color:#333;background-color:#333;" />
                            <br/>
                        </div>

                        <form method="POST" action="{{ route('order.bank_submit') }}">
                            @csrf

                            <div class="col-xs-12 col-sm-12 col-md-6 col-md-offset-3">
                                <div class="col-md-12 text--center">
                                    <p>
                                        We are pleased to tell you your order has been received.<br>
                                        Please make the payment to the account stated below.<br>
                                    </p>
                                    <h4>
                                        Bank Central Asia (BCA)<br>
                                        006 999 1515  a.n PT. Nama Karya Bersama
                                    </h4>
                                    <p>TOTAL PAYMENT = {{env('KURS_IDR')}} {{$order->grand_total_string}}</p>
                                </div>
                                <div class="col-md-12">
                                    <h1>Confirm Payment</h1>
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
                                <input type="hidden" name="order_number" value="{{$order->order_number}}">
                                <div class="col-md-12">
                                    <select name="bank_name" id="bank_name" class="form-control">
                                        <option value="Bank BCA">Bank BCA</option>
                                        <option value="Bank Mandiri">Bank Mandiri</option>
                                        <option value="Bank BNI">Bank BNI</option>
                                        <option value="Bank BRI">Bank BRI</option>
                                        <option value="Bank CIMB">Bank CIMB</option>
                                        <option value="Other Bank">Other Bank</option>
                                    </select>

                                    {{--<input type="text" class="form-control" name="bank_name" id="bank_name" value="{{old('bank_name')}}" placeholder="BANK NAME" required/>--}}
                                </div>

                                <div class="col-md-12">
                                    <input type="text" class="form-control" name="bank_acc_name" id="bank_acc_name" value="{{old('bank_acc_name')}}" placeholder="ACCOUNT NAME" required/>
                                </div>

                                <div class="col-md-12">
                                    <input type="text" class="form-control" name="bank_acc_no" id="bank_acc_no" value="{{old('bank_acc_no')}}" placeholder="ACCOUNT NUMBER" required/>
                                </div>

                                <div class="col-md-12">
                                    <input type="number" class="form-control" name="amount" id="amount" value="{{old('amount')}}" placeholder="TRANSFER AMOUNT" min="0" required/>
                                </div>

                                <div class="col-md-12 center">
                                    <button type="submit" class="btn btn--secondary btn--bordered">Submit</button>
                                </div>
                            </div>
                        </form>
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