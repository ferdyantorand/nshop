@extends('layouts.admin')

@section('content')

<header class="blue accent-3 relative">
    <div class="container-fluid text-white">
        <div class="row p-t-b-10 ">
            <div class="col">
                <h4>
                    <i class="icon-package"></i>
                    Order Details
                </h4>
            </div>
        </div>
    </div>
</header>

<div class="container-fluid relative animatedParent animateOnce">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body b-b">
                    <div class="tab-content pb-3" id="v-pills-tabContent">
                        <div class="tab-pane animated fadeInUpShort show active" id="v-pills-1">
                            <!-- Input -->
                            <div class="body">
                                <div class="col-md-6">
                                    <div class="form-group form-float form-group-lg">
                                        <div class="form-line">
                                            <label class="form-label" for="created_at">Date</label>
                                            <input id="created_at" type="text" class="form-control"
                                                   name="created_at" value="{{ $order->created_at }}" readonly>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group form-float form-group-lg">
                                        <div class="form-line">
                                            <label class="form-label" for="customer_name">Customer</label>
                                            <input id="customer_name" type="text" class="form-control"
                                                   name="customer_name" value="{{ $order->user->first_name . ' ' . $order->user->last_name }}" readonly>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group form-float form-group-lg">
                                        <div class="form-line">
                                            <label class="form-label" for="customer_email">Customer Email</label>
                                            <input id="customer_email" type="text" class="form-control"
                                                   name="customer_email" value="{{ $order->user->email }}" readonly>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group form-float form-group-lg">
                                        <div class="form-line">
                                            <label class="form-label" for="customer_address">Customer Address</label>
                                            <textarea id="customer_address" type="text" class="form-control"
                                                   name="customer_address" readonly>{{ $order->user->addresses[0]->description }}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group form-float form-group-lg">
                                        <div class="form-line">
                                            <label class="form-label" for="customer_country">Country</label>
                                            <input id="customer_country" type="text" class="form-control"
                                                   name="customer_country" value="{{ $order->user->addresses[0]->country->name }}" readonly>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group form-float form-group-lg">
                                        <div class="form-line">
                                            <label class="form-label" for="customer_province">Province</label>
                                            <input id="customer_province" type="text" class="form-control"
                                                   name="customer_province" value="{{ $order->user->addresses[0]->province->name }}" readonly>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group form-float form-group-lg">
                                        <div class="form-line">
                                            <label class="form-label" for="customer_city">City</label>
                                            <input id="customer_city" type="text" class="form-control"
                                                      name="customer_city" value="{{ $order->user->addresses[0]->city->name }}" readonly>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group form-float form-group-lg">
                                        <div class="form-line">
                                            <label class="form-label" for="shipping">Shipping</label>
                                            <input id="shipping" type="text" class="form-control"
                                                   name="shipping" value="{{ $order->shipping_option }}" readonly style="text-transform: uppercase;">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group form-float form-group-lg">
                                        <div class="form-line">
                                            <label class="form-label" for="sub_total">Sub Total</label>
                                            <input id="sub_total" type="text" class="form-control"
                                                   name="sub_total" value="Rp{{ $order->sub_total }}" readonly>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group form-float form-group-lg">
                                        <div class="form-line">
                                            <label class="form-label" for="shipping_charge">Shipping Amount</label>
                                            <input id="shipping_charge" type="text" class="form-control"
                                                   name="shipping_charge" value="Rp{{ $order->shipping_charge }}" readonly>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group form-float form-group-lg">
                                        <div class="form-line">
                                            <label class="form-label" for="tax_amount">Tax Amount</label>
                                            <input id="tax_amount" type="text" class="form-control"
                                                   name="tax_amount" value="Rp{{ $order->tax_amount }}" readonly>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group form-float form-group-lg">
                                        <div class="form-line">
                                            <label class="form-label" for="payment_charge">Service Amount</label>
                                            <input id="payment_charge" type="text" class="form-control"
                                                   name="payment_charge" value="Rp{{ $order->payment_charge }}" readonly>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group form-float form-group-lg">
                                        <div class="form-line">
                                            <label class="form-label" for="grand_total">Grand Total</label>
                                            <input id="grand_total" type="text" class="form-control"
                                                   name="grand_total" value="Rp{{ $order->grand_total }}" readonly>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group form-float form-group-lg">
                                        <div class="form-line">
                                            <label class="form-label" for="status">Status</label>
                                            <input id="status" type="text" class="form-control"
                                                   name="status" value="{{ $order->order_status->name }}" readonly>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <table class="table table-striped table-bordered dt-responsive">
                                        <thead>
                                            <tr>
                                                <td>Product Name</td>
                                                <td>Product Info</td>
                                                <td>Qty</td>
                                                <td>Price</td>
                                                <td>Total</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($order->order_products as $product)
                                                <tr>
                                                    <td>{{ $product->product->name }}</td>
                                                    <td>{!! $product->product_info !!}</td>
                                                    <td>{{ $product->qty }}</td>
                                                    <td>{{ $product->price }}</td>
                                                    <td>{{ $product->grand_total }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group form-float form-group-lg">
                                        <a href="{{route('admin.orders.print_packing', ['id'=>$order->id])}}" target="_blank" class="btn btn-success">Print Packing Label</a>
                                    </div>
                                </div>
                                @if($order->order_status_id == 3)
                                    {{ Form::open(['route'=>['admin.orders.tracking'],'method' => 'post','id' => 'general-form', 'enctype' => 'multipart/form-data']) }}
                                    <input type="hidden" name="order-id" value="{{$order->id}}">
                                    <div class="col-md-6 text-center">
                                        <div class="form-group form-float form-group-lg">
                                            <div class="form-line">
                                                <label class="form-label" for="track_code">Tracking Code</label>
                                                <input id="track_code" type="text" class="form-control"
                                                       name="track_code" value="{{ $order->track_code }}" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 text-center">
                                        <div class="form-group form-float form-group-lg">
                                            <button class="btn btn-success">Submit</button>
                                        </div>
                                    </div>

                                    {{ Form::close() }}
                                @elseif ($order->order_status_id == 4)
                                    {{ Form::open(['route'=>['admin.orders.tracking'],'method' => 'post','id' => 'general-form', 'enctype' => 'multipart/form-data']) }}
                                    <input type="hidden" name="order-id" value="{{$order->id}}">
                                    <div class="col-md-6 text-center">
                                        <div class="form-group form-float form-group-lg">
                                            <div class="form-line">
                                                <label class="form-label" for="track_code">Tracking Code</label>
                                                <input id="track_code" type="text" class="form-control"
                                                       name="track_code" value="{{ $order->track_code }}" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 text-center">
                                        <div class="form-group form-float form-group-lg">
                                            <button class="btn btn-success">Change</button>
                                        </div>
                                    </div>

                                    {{ Form::close() }}
                                @endif
                            </div>
                            <hr>
                            <div class="col-md-11 col-sm-11 col-xs-12" style="margin: 3% 0 3% 0;">
                                <a href="{{ route('admin.orders.index') }}" class="btn btn-danger">Exit</a>
                            </div>
                            <!-- #END# Input -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection