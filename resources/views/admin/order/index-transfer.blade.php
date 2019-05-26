@extends('layouts.admin')

@section('content')

    <header class="blue accent-3 relative nav-sticky">
        <div class="container-fluid text-white">
            <div class="row p-t-b-10 ">
                <div class="col">
                    <h4> <i class="icon-table"></i> Order Bank Transfers</h4>
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
                                <th>Status</th>
                                <th>Customer</th>
                                <th>Bank Account Number</th>
                                <th>Bank Account Name</th>
                                <th>Bank Name</th>
                                <th>Amount</th>
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
    @include('partials._accept-bank-transfer')
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
            ajax: '{!! route('datatables.order-bank-trfs') !!}',
            order: [ [2, 'desc'], [1, 'desc'] ],
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
                { data: 'status', name: 'status', class: 'text-center'},
                { data: 'customer', name: 'customer' },
                { data: 'bank_acc_no', name: 'bank_acc_no', class: 'text-center'},
                { data: 'bank_acc_name', name: 'bank_acc_name', class: 'text-center'},
                { data: 'bank_name', name: 'bank_name', class: 'text-center'},
                { data: 'amount', name: 'amount', class: 'text-center'},
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
    @include('partials._accept-bank-transferjs', ['routeUrl' => 'admin.orders.bank_transfer_accept', 'redirectUrl' => 'admin.orders.bank_transfer'])
@endsection