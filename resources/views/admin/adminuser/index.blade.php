@extends('layouts.admin')

@section('content')

    <header class="blue accent-3 relative nav-sticky">
        <div class="container-fluid text-white">
            <div class="row p-t-b-10 ">
                <div class="col">
                    <h4> <i class="icon-table"></i> Admin Users</h4>
                </div>
            </div>
        </div>
    </header>

    <div class="content-wrapper animatedParent animateOnce">
        <div class="container">
            <section class="paper-card">
                <div class="row">
                    <table class="table cell-vertical-align-middle  table-responsive mb-4">
                        <tbody>
                        <tr class="no-b">
                            <td>
                                <a href="{{ route('admin.admin-users.create') }}" class="btn btn-outline-primary btn-lg btn-block">
                                <i class="icon icon-plus"></i> Add
                                </a>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <div class="col-lg-12">
                        @include('partials.admin._messages')
                        <table id="user-admin" class="table table-striped table-bordered dt-responsive nowrap" width="100%" cellspacing="0">
                            <thead>
                            <tr>
                                <th>Email</th>
                                <th>Name</th>
                                <th>Is Superadmin</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Created At</th>
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
    @include('partials._delete')
@endsection

@section('styles')
    <link href="{{ asset('css/datatables.css') }}" rel="stylesheet">
@endsection

@section('scripts')
    <script src="{{ asset('js/datatables.js') }}"></script>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script>
        $('#user-admin').DataTable({
            processing: true,
            serverSide: true,
            pageLength: 25,
            ajax: '{!! route('datatables.admin_users') !!}',
            order: [ [0, 'asc'] ],
            columns: [
                { data: 'email', name: 'email', class: 'text-center'},
                { data: 'name', name: 'name', class: 'text-center'},
                { data: 'superadmin', name: 'superadmin', class: 'text-center'},
                { data: 'role', name: 'role', class: 'text-center'},
                { data: 'status', name: 'status', class: 'text-center'},
                { data: 'created_at', name: 'created_at', class: 'text-center', orderable: false, searchable: false,
                    render: function ( data, type, row ){
                        if ( type === 'display' || type === 'filter' ){
                            return moment(data).format('DD MMM YYYY');
                        }
                        return data;
                    }
                },
                { data: 'action', name: 'action', orderable: false, searchable: false, class: 'text-center'}
            ],
        });

        $(document).on('click', '.delete-modal', function(){
            $('#deleteModal').modal({
                backdrop: 'static',
                keyboard: false
            });

            $('#deleted-id').val($(this).data('id'));
        });
    </script>
    @include('partials._deletejs', ['routeUrl' => 'admin.admin-users.destroy', 'redirectUrl' => 'admin.admin-users.index'])
@endsection