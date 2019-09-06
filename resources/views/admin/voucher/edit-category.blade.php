@extends('layouts.admin')

@section('content')

    <header class="blue accent-3 relative">
        <div class="container-fluid text-white">
            <div class="row p-t-b-10 ">
                <div class="col">
                    <h4>
                        <i class="icon-package"></i>
                        Edit Voucher Category
                    </h4>
                </div>
            </div>
        </div>
    </header>

    {{ Form::open(['route'=>['admin.vouchers.update'],'method' => 'post','id' => 'general-form']) }}
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
                                        <div class="form-group">
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <input type="hidden" name="type" value="categories"/>
                                                @php($idx = 0)
                                                <table class="table">
                                                    <thead>
                                                    <tr>
                                                        <th colspan="3">
                                                            Categories
                                                        </th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            @foreach($categories as $category)
                                                                @php($idx++)
                                                                @php($checked="")
                                                                @php($disabled="disabled")
                                                                @if($choosenCategories->contains($category->id))
                                                                    @php($checked="checked")
                                                                    @php($disabled="")
                                                                @endif
                                                                <td>
                                                                    <label>
                                                                        <input type="checkbox" class="group1 flat" id="chk{{$category->id}}" name="chk[]" onclick="changeInput('{{ $category->id }}')" {{$checked}} /> {{ $category->name }}
                                                                        <input type="hidden" class="group2" value="{{ $category->id }}" id="{{ $category->id }}" name="ids[]" {{$disabled}}/>
                                                                    </label>
                                                                </td>
                                                                {{--@foreach($choosenCategories as $choosen)--}}
                                                                    {{--@if($choosen->id == $category->id)--}}
                                                                        {{--<td>--}}
                                                                            {{--<label>--}}
                                                                                {{--<input type="checkbox" class="group1 flat" id="chk{{$category->id}}" name="chk[]" onclick="changeInput('{{ $category->id }}')" checked/> {{ $category->name }}--}}
                                                                                {{--<input type="hidden" class="group2" value="{{ $category->id }}" id="{{ $category->id }}" name="ids[]"/>--}}
                                                                            {{--</label>--}}
                                                                        {{--</td>--}}
                                                                        {{--@break--}}
                                                                    {{--@else--}}
                                                                        {{--<td>--}}
                                                                            {{--<label>--}}
                                                                                {{--<input type="checkbox" class="group1 flat" id="chk{{$category->id}}" name="chk[]" onclick="changeInput('{{ $category->id }}')" /> {{ $category->name }}--}}
                                                                                {{--<input type="hidden" class="group2" value="{{ $category->id }}" id="{{ $category->id }}" name="ids[]" disabled/>--}}
                                                                            {{--</label>--}}
                                                                        {{--</td>--}}
                                                                        {{--@break--}}
                                                                    {{--@endif--}}
                                                                {{--@endforeach--}}

                                                                @if($idx == 3)
                                                                    <tr/><tr>
                                                                    @php($idx = 0)
                                                                @endif
                                                            @endforeach
                                                        </tr>
                                                        <tr>
                                                            <td colspan="3">
                                                                <label>
                                                                    <input type="checkbox" class="flat" id="selectAll"/> Select All
                                                                </label>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="status">Status *</label>
                                            <select id="status" name="status" class="form-control">
                                                @if($voucher->status_id == 1)
                                                    <option value="1" selected>Active</option>
                                                    <option value="2">Not Active</option>
                                                @else
                                                    <option value="1">Active</option>
                                                    <option value="2" selected>Not Active</option>
                                                @endif
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group form-float form-group-lg">
                                            <div class="form-line">
                                                <label class="form-label" for="code">Code *</label>
                                                <input id="code" type="text" class="form-control"
                                                       name="code" value="{{ $voucher->code }}">
                                                <input type="hidden" value="{{ $voucher->id }}" name="id">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group form-float form-group-lg">
                                            <div class="form-line">
                                                <label class="form-label" for="description">Description *</label>
                                                <textarea id="description" type="description" class="form-control"
                                                          name="description">{{ $voucher->description }}</textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <label class="form-label" for="voucher_amount">Voucher Amount </label>
                                        <input id="voucher_amount" type="number" class="form-control"
                                               name="voucher_amount" value="{{ $voucher->voucher_amount }}">
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label" for="voucher_percentage">Voucher Percentage </label>
                                        <input id="voucher_percentage" type="number" class="form-control"
                                               name="voucher_percentage" value="{{ $voucher->voucher_percentage }}">
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label" for="min_purchase">Minimal Purchase </label>
                                        <input id="min_purchase" type="number" class="form-control"
                                               name="min_purchase" value="{{ $voucher->min_purchase }}">
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label" for="stock">Stock </label>
                                        <input id="stock" type="number" class="form-control"
                                               name="stock" value="{{ $voucher->stock }}">
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group form-float form-group-lg">
                                            <div class="form-line">
                                                <label class="form-label" for="start_date">Start Date *</label>
                                                <input id="start_date" name="start_date" type="text" class="date-time-picker form-control" value="{{ $voucher->start_date }}"/>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group form-float form-group-lg">
                                            <div class="form-line">
                                                <label class="form-label" for="finish_date">Finish Date *</label>
                                                <input id="finish_date" type="text" class="date-time-picker form-control"
                                                       name="finish_date" value="{{ $voucher->finish_date }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-11 col-sm-11 col-xs-12" style="margin: 3% 0 3% 0;">
                                    <a href="{{ route('admin.vouchers.index') }}" class="btn btn-danger">Exit</a>
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
        $('#category').select2({
            placeholder: {
                id: '-1',
                text: 'Choose Category...'
            },
            width: '100%',
            minimumInputLength: 0,
            ajax: {
                url: '{{ route('select.categories') }}',
                dataType: 'json',
                data: function (params) {
                    return {
                        q: $.trim(params.term)
                    };
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                }
            }
        });

        $('#product').select2({
            placeholder: {
                id: '-1',
                text: 'Choose Product...'
            },
            width: '100%',
            minimumInputLength: 0,
            ajax: {
                url: '{{ route('select.products') }}',
                dataType: 'json',
                data: function (params) {
                    return {
                        q: $.trim(params.term)
                    };
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                }
            }
        });

        $("#selectAll").click(function(){
            $('input:checkbox').not(this).prop('checked', this.checked);
            if(this.checked){
                $('input.group1').prop("disabled", false);
                $('input.group2').prop("disabled", false);
            }
            else{
                $('input.group1').prop("disabled", true);
                $('input.group2').prop("disabled", true);
            }
        });

        function changeInput(id){
            if(document.getElementById("chk"+id).checked == true){
                document.getElementById(id).disabled = false;
            }
            else{
                document.getElementById(id).disabled = true;
            }
        }
    </script>
@endsection
