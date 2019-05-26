@extends('layouts.admin')

@section('content')

    <header class="blue accent-3 relative nav-sticky">
        <div class="container-fluid text-white">
            <div class="row p-t-b-10 ">
                <div class="col">
                    <h4> <i class="icon-table"></i> Order Shipped</h4>
                </div>
            </div>
        </div>
    </header>

    <div class="content-wrapper animatedParent animateOnce">
        <div class="container">
            <section class="paper-card">
                <div class="row">
                    <div class="col-lg-12">
                        @include('partials.admin._messages')
                        <table id="user-admin" class="table table-striped table-bordered dt-responsive nowrap" width="100%" cellspacing="0">
                            <thead>
                            <tr>
                                <th>Date</th>
                                <th>Order Number</th>
                                <th>Customer</th>
                                <th>Customer Email</th>
                                <th>Shipping</th>
                                <th>Sub total</th>
                                <th>Payment</th>
                                <th>Grand Total</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection

@section('styles')
    <link href="{{ asset('css/datatables.css') }}" rel="stylesheet">
    <style>
        .text-uppercase{
            text-transform: uppercase;
        }
    </style>
@endsection

@section('scripts')
    <script src="{{ asset('js/datatables.js') }}"></script>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script>
        $('#user-admin').DataTable({
            processing: true,
            serverSide: true,
            pageLength: 25,
            ajax: '{!! route('datatables.order-shipped') !!}',
            order: [ [1, 'desc'], [0, 'desc'] ],
            columns: [
                { data: 'created_at', name: 'created_at', class: 'text-center', orderable: false, searchable: false,
                    render: function ( data, type, row ){
                        if ( type === 'display' || type === 'filter' ){
                            return moment(data).format('DD MMM YYYY');
                        }
                        return data;
                    }
                },
                { data: 'order_number', name: 'order_number' },
                { data: 'customer', name: 'customer' },
                { data: 'email', name: 'email', class: 'text-center'},
                { data: 'shipping', name: 'shipping', class: 'text-center text-uppercase'},
                { data: 'sub_total', name: 'sub_total', class: 'text-right'},
                { data: 'payment_option', name: 'payment_option', class: 'text-center'},
                { data: 'grand_total', name: 'grand_total', class: 'text-right'},
                { data: 'action', name: 'action', orderable: false, searchable: false, class: 'text-center'}
            ],
        });

        $(document).on('click', '.accept-modal', function(){
            $('#acceptModal').modal({
                backdrop: 'static',
                keyboard: false
            });

            $('#accept-id').val($(this).data('id'));
        });
    </script>
@endsection