@extends('layouts.admin')

@section('content')

<header class="blue accent-3 relative">
    <div class="container-fluid text-white">
        <div class="row p-t-b-10 ">
            <div class="col">
                <h4>
                    <i class="icon-package"></i>
                    Create New Category
                </h4>
            </div>
        </div>
    </div>
</header>

{{ Form::open(['route'=>['admin.categories.store'],'method' => 'post','id' => 'general-form']) }}
{{--<form method="POST" action="{{ route('admin-users.store') }}">--}}
    {{--{{ csrf_field() }}--}}
    <div class="container-fluid relative animatedParent animateOnce">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body b-b">
                        <div class="tab-content pb-3" id="v-pills-tabContent">
                            <div class="tab-pane animated fadeInUpShort show active" id="v-pills-1">
                            @include('partials.admin._messages')
                                @foreach($errors->all() as $error)
                                    <ul>
                                        <li>
                                            <span class="help-block">
                                                <strong style="color: #ff3d00;"> {{ $error }} </strong>
                                            </span>
                                        </li>
                                    </ul>
                                @endforeach
                                <!-- Input -->
                                <div class="body">

                                    <div class="col-md-12">
                                        <div class="form-group form-float form-group-lg">
                                            <div class="form-line">
                                                <label class="form-label" for="name">Name *</label>
                                                <input id="name" type="text" class="form-control"
                                                       name="name" value="{{ old('name') }}" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group form-float form-group-lg">
                                            <div class="form-line">
                                                <label class="form-label" for="slug">Slug *</label>
                                                <input id="slug" type="text" class="form-control"
                                                       name="slug" value="{{ old('slug') }}" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group form-float form-group-lg">
                                            <div class="form-line">
                                                <label class="form-label" for="meta_title">Meta Title</label>
                                                <input id="meta_title" type="text" class="form-control"
                                                       name="meta_title" value="{{ old('meta_title') }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group form-float form-group-lg">
                                            <div class="form-line">
                                                <label class="form-label" for="meta_description">Meta Description</label>
                                                <input id="meta_description" type="text" class="form-control"
                                                       name="meta_description" value="{{ old('meta_description') }}">
                                            </div>
                                        </div>
                                    </div>

                                    <input id="parent" type="hidden" class="form-control"
                                           name="parent">
                                    {{--<div class="col-md-12">--}}
                                        {{--<div class="form-group">--}}
                                            {{--<label for="parent">Parent</label>--}}
                                            {{--<select id="parent" name="parent" class="form-control">--}}
                                                {{--<option value="0">-</option>--}}
                                            {{--</select>--}}
                                        {{--</div>--}}
                                    {{--</div>--}}

                                </div>
                                <div class="col-md-11 col-sm-11 col-xs-12" style="margin: 3% 0 3% 0;">
                                    <a href="{{ route('admin.categories.index') }}" class="btn btn-danger">Exit</a>
                                    <input type="submit" class="btn btn-success" value="Save">
                                </div>
                                <!-- #END# Input -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
{{--</form>--}}
{{ Form::close() }}
@endsection

@section('styles')
    <link href="{{ asset('css/select2-bootstrap4.min.css') }}" rel="stylesheet"/>
    <style>
        .select2-container--default .select2-search--dropdown::before {
            content: "";
        }
    </style>
@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
    <script type="text/javascript">

        $('#parent').select2({
            placeholder: {
                id: '-1',
                text: 'Pilih Parent Category...'
            },
            width: '100%',
            minimumInputLength: 0,
            ajax: {
                url: '{{ route('select.categories') }}',
                dataType: 'json',
                data: function (params) {
                    return {
                        q: $.trim(params.term),
                        id: '0'
                    };
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                }
            }
        });
    </script>
@endsection