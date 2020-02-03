@extends('layouts.admin')

@section('content')

<header class="blue accent-3 relative">
    <div class="container-fluid text-white">
        <div class="row p-t-b-10 ">
            <div class="col">
                <h4>
                    <i class="icon-package"></i>
                    Create New Transaction
                </h4>
            </div>
        </div>
    </div>
</header>

{{ Form::open(['route'=>['admin.orders.store'],'method' => 'post','id' => 'general-form']) }}
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
                                                <label class="form-label" for="order_date">Order Date *</label>
                                                <input id="order_date" name="order_date" type="text" class="date-time-picker form-control" value="{{ old('order_date') }}"/>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group form-float form-group-lg">
                                            <div class="form-line">
                                                <label class="form-label" for="name">Buyer Name *</label>
                                                <input id="name" type="text" class="form-control"
                                                       name="name" value="{{ old('name') }}" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group form-float form-group-lg">
                                            <div class="form-line">
                                                <label class="form-label" for="email">Buyer Email</label>
                                                <input id="email" type="text" class="form-control"
                                                       name="email" value="{{ old('email') }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group form-float form-group-lg">
                                            <div class="form-line">
                                                <label class="form-label" for="phone">Buyer Phone *</label>
                                                <input id="phone" type="number" class="form-control"
                                                       name="phone" value="{{ old('phone') }}" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group form-float form-group-lg">
                                            <div class="form-line">
                                                <label class="form-label" for="address_description">Buyer Address Description *</label>

                                                <textarea class="form-control" id="address_description" name="address_description" rows="3" required>{{old('address_description')}}</textarea>
{{--                                                <input id="address_description" type="text" class="form-control"--}}
{{--                                                       name="address_description" value="{{ old('address_description') }}" required>--}}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group form-float form-group-lg">
                                            <div class="form-line">
                                                <label class="form-label" for="address_street">Buyer Address Street *</label>
                                                <textarea class="form-control" id="address_street" name="address_street" rows="3" required>{{old('address_street')}}</textarea>
{{--                                                <input id="address_street" type="text" class="form-control"--}}
{{--                                                       name="address_street" value="{{ old('address_street') }}" required>--}}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group form-float form-group-lg">
                                            <div class="form-line">
                                                <label class="form-label" for="address_province">Buyer Address Province *</label>
                                                <select name="address_province" id="address_province" class="form-control">
                                                    <option value="-1" selected>PROVINCE</option>
                                                    @foreach($provinces as $province)
                                                        <option value="{{ $province->id }}">{{ $province->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group form-float form-group-lg">
                                            <div class="form-line">
                                                <label class="form-label" for="address_city">Buyer Address City *</label>
                                                <select name="address_city" id="address_city" class="form-control">
                                                    <option value="-1" selected>CITY</option>
                                                    @foreach($cities as $city)
                                                        <option value="{{ $city->province_id . '-' . $city->id }}">{{ $city->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group form-float form-group-lg">
                                            <div class="form-line">
                                                <label class="form-label" for="address_postal_code">Buyer Address Postal Code</label>
                                                <input id="address_postal_code" type="number" class="form-control"
                                                       name="address_postal_code" value="{{ old('address_postal_code') }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group form-float form-group-lg">
                                            <div class="form-line">
                                                <label class="form-label" for="shipping_date">Shipping Date *</label>
                                                <input id="shipping_date" name="shipping_date" type="text" class="date-time-picker form-control" value="{{ old('shipping_date') }}"/>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group form-float form-group-lg">
                                            <div class="form-line">
                                                <label class="form-label" for="address_postal_code">Voucher Code</label>
                                                <input id="voucher" type="text" class="form-control"
                                                       name="voucher" value="{{ old('voucher') }}">
                                            </div>
                                        </div>
                                    </div>

                                    <br>
                                    <h5>Pilih Produk</h5>
                                    <br>

                                    <input type="hidden" id="weight" value="{{ $totalWeight }}">
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-hover" id="tab_logic">
                                                <thead>
                                                <tr>
                                                    <th class="text-center" width="35">
                                                        Produk
                                                    </th>
                                                    <th class="text-center"  width="5">
                                                        Quantity
                                                    </th>
                                                    <th class="text-center" width="30">
                                                        Customization Text
                                                    </th>
                                                    <th class="text-center" width="10">
                                                        Customization Position
                                                    </th>
                                                    <th class="text-center" width="10">
                                                        Customization Color
                                                    </th>
                                                    <th class="text-center" width="10">
                                                        Customization Size
                                                    </th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr id='sch0'>
                                                    <td>
                                                        <select id="product0" name="product[]" class='form-control'></select>
                                                        <input id='weight0' type="hidden" class='form-control weight-product'/>
                                                    </td>
                                                    <td>
                                                        <input id='quantity0' type="text" class='form-control' name='quantity[]' required/>
                                                    </td>
                                                    <td>
                                                        <input id='text0' type="text" class='form-control' name='text[]' maxlength="5" value="-"/>
                                                    </td>
                                                    <td>
{{--                                                        <select id="position0" name="position[]" class='form-control'></select>--}}
                                                        <select id="position0" name="position[]" class="form-control">
                                                            <option value="Top" selected>TOP</option>
                                                            <option value="Middle">MIDDLE</option>
                                                            <option value="Bottom">BOTTOM</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select id="color0" name="color[]" class="form-control">
                                                            <option value="Silver" selected>Silver</option>
                                                            <option value="Gold">Gold</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select id="size0" name="size[]" class="form-control">
                                                            <option value="36 pt" selected>36 pt</option>
                                                            <option value="24 pt">24 pt</option>
                                                        </select>
                                                    </td>
                                                </tr>
                                                <tr id='sch1'></tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <a id="add_row" class="btn btn-success" style="color: #fff;">Tambah</a>
                                        &nbsp;
                                        <a id='delete_row' class="btn btn-danger" style="color: #fff;">Hapus</a>
                                    </div>
                                    <br>

                                    <div class="col-md-12">
                                        <div class="form-group form-float form-group-lg">
                                            <div class="form-line">
                                                <label class="form-label" for="choose_shipping">Choose Shipping *</label>
                                                <select id="choose_shipping" name="choose_shipping" class="form-control">
                                                    <option value="jne-REG">JNE - REG</option>
                                                    <option value="jne-YES">JNE - YES</option>
                                                    <option value="gojek-grab">GOJEK / GRAB</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group form-float form-group-lg">
                                            <div class="form-line">
                                                <label class="form-label" for="delivery-fee">Delivery Fee</label>
                                                <a id="btn-delivery-fee" class="btn btn-primary btn-xs" onclick="refreshButton()">Refresh</a>
                                                <br><br>
                                                <span id="delivery-fee" class="" style="color:black; height: 31.5px; width: 120px; line-height: 0;font-size: 24px"></span>
                                                <input type="hidden" id="delivery_fee" name="delivery_fee">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-11 col-sm-11 col-xs-12" style="margin: 3% 0 3% 0;">
                                    <a href="{{ route('admin.orders.index') }}" class="btn btn-danger">Exit</a>
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
        var userCityId = parseInt("{{ $userCity }}");
        var weightInt = parseInt('{{ $totalWeight }}');
        var defaultCourier = 'jne-REG';

        $("#btn-delivery-fee").click(function(){
        // function refreshButton(){
            let i = 0;
            let totalWeight = 0;
            $('.weight-product').each(function() {
                let qty = $('#quantity' + i).val();
                if(qty == null){
                    qty = 1;
                }
                $(this).val();
                let totalWeightProduct = qty * ($(this).val());
                totalWeight = totalWeight + totalWeightProduct;

                i++;
            });

            let courierValue = $('#choose_shipping').val();
            let courierValueSplitted = courierValue.split('-');

            rajaongkirAjaxGetCost(userCityId, totalWeight, courierValueSplitted);
        });

        $('#product0').select2({
            placeholder: {
                id: '-1',
                text: 'Pilih Product'
            },
            width: '100%',
            minimumInputLength: 1,
            ajax: {
                url: '{{ route('select.product-weights') }}',
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
        $('#product0').on('select2:select', function(){
            var database = $('#product0').val();
            let weight = database.split("#");

            let id = "#weight0";
            $(id).val(weight[1]);
        });

        var i=1;
        $("#add_row").click(function(){
            var bufferID = i;
            $('#sch'+i).html(

                "<td>" +
                "<select id='product" + i + "' name='product[]' class='form-control'></select>" +
                "<input id='weight" + i + "' type='hidden' class='form-control weight-product'/>" +
                "</td>" +

                "<td>" +
                "<input id='quantity"+ i +"' type='text' class='form-control' name='quantity[]' required/>" +
                "</td>" +

                "<td>" +
                "<input id='text"+ i +"' type='text' class='form-control' name='text[]' maxlength='5' value='-'/>" +
                "</td>" +

                "<td>"+
                // "<input id='position"+ i +"' type='text' class='form-control' name='position[]' required/>" +
                "<select id='position"+ i +"' name='position[]' class='form-control'>" +
                "<option value='Top' selected>TOP</option>" +
                "<option value='Middle'>MIDDLE</option>" +
                "<option value='Bottom'>BOTTOM</option>" +
                "</select>" +
                "</td>" +

                "<td>" +
                "<select id='color"+ i +"' name='color[]' class='form-control'>" +
                "<option value='Silver' selected>Silver</option>" +
                "<option value='Gold'>Gold</option>" +
                "</select>" +
                "</td>" +

                "<td>" +
                "<select id='size"+ i +"' name='size[]' class='form-control'>" +
                "<option value='36 pt' selected>36 pt</option>" +
                "<option value='24 pt' >24 pt</option>" +
                "</select>" +
                "</td>"
            );
            $('#tab_logic').append('<tr id="sch'+(i+1)+'"></tr>');

            $('#product' + i).select2({
                placeholder: {
                    id: '-1',
                    text: ' - Pilih Product - '
                },
                width: '100%',
                minimumInputLength: 1,
                ajax: {
                    url: '{{ route('select.product-weights') }}',
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
                    },
                }
            });
            $('#product' + i).on('select2:select', function(){
                var database = $('#product' + i).val();
                let weight = database.split("#");

                let id = "#weight" + i;
                $(id).val(weight[1]);
            });

            {{--$('#position' + i).select2({--}}
            {{--    placeholder: {--}}
            {{--        id: '-1',--}}
            {{--        text: ' - Pilih Position - '--}}
            {{--    },--}}
            {{--    width: '100%',--}}
            {{--    minimumInputLength: 0,--}}
            {{--    ajax: {--}}
            {{--        url: '{{ route('select.product-positions') }}',--}}
            {{--        dataType: 'json',--}}
            {{--        data: function (params) {--}}
            {{--            return {--}}
            {{--                q: $.trim(params.term),--}}
            {{--                id: $('#position' + i).val()--}}
            {{--            };--}}
            {{--        },--}}
            {{--        processResults: function (data) {--}}
            {{--            return {--}}
            {{--                results: data--}}
            {{--            };--}}
            {{--        }--}}
            {{--    }--}}
            {{--});--}}
            i++;
        });

        $("#delete_row").click(function(){
            if(i>1){
                $("#sch"+(i-1)).html('');
                i--;
            }
        });


        @if($userCity !== -1)
            // Get default delivery fee for jne-REG
            var defaultCourierSplitted = defaultCourier.split('-');
            rajaongkirAjaxGetCost(userCityId, weightInt, defaultCourierSplitted);
        @endif

        // Get delivery fee for every courier selected
        $('select[name="choose_shipping"]').change(function(){
            let value = $(this).val();
            let splittedValue = value.split('-');

            weightInt = $('#weight').val();
            if(userCityId !== -1){
                rajaongkirAjaxGetCost(userCityId, weightInt, splittedValue);
            }
        });

        // Get delivery fee for every city selected
        $('#address_city').on('change', function() {
            //alert( this.value );
            let cityId = -1;
            if (this.value.indexOf('-') > -1)
            {
                let splittedValue = this.value.split('-');
                cityId = parseInt(splittedValue[1]);
            }
            else{
                cityId = parseInt(this.value);
            }

            userCityId = cityId;

            if(userCityId !== -1){
                // Get selected courier
                let courierValue = $('#choose_shipping').val();
                let courierValueSplitted = courierValue.split('-');

                rajaongkirAjaxGetCost(userCityId, weightInt, courierValueSplitted);
            }
        });

        // Ajax function to get rajaongkir delivery fee
        function rajaongkirAjaxGetCost(tmpCityId, tmpWeight, tmpCourier){
            // alert(tmpCourier);
            if(tmpCourier[0] === "gojek"){
                $('#delivery-fee').html("Rp 0");
                $('#delivery_fee').val(0);
            }
            else{
                $.ajax({
                    url: '{{ route('ajax.rajaongkir.cost') }}',
                    type: 'POST',
                    data: {
                        'destination_city_id': tmpCityId,
                        'weight': tmpWeight,
                        'courier': tmpCourier
                    },
                    success: function(data) {
                        //console.log(data);
                        if(data.code === 200){
                            let feeStr = rupiahFormat(data.fee);
                            $('#delivery-fee').html("Rp " + feeStr);
                            $('#delivery_fee').val(data.fee);
                        }
                        else{
                            $('#delivery-fee').html("Shipping Service Not Available");
                            $('#delivery_fee').val(0);
                        }
                    },
                    error: function(response){
                        console.log(response);
                    }
                });
            }
        }

        (function($){
            var province = $('#address_province');
            var city = $('#address_city');
            var cityOptions = city.children();

            province.on('change', function(){
                //remove the options
                cityOptions.detach();

                city.append("<option value='-1' selected>CITY</option>");

                //readd only the options for the country
                cityOptions.filter(function(){
                    return this.value.indexOf(province.val() + "-") === 0;
                }).appendTo(city);
                //clear out the value so it doesn't default to one it should not
                city.val('-1');
                $('#delivery-fee').html('');
            });
        }(jQuery));

        (function($){
            var province = $('#province_secondary');
            var city = $('#city_secondary');
            var cityOptions = city.children();

            province.on('change', function(){
                //remove the options
                cityOptions.detach();

                city.append("<option value='-1' selected>CITY</option>");

                //readd only the options for the country
                cityOptions.filter(function(){
                    return this.value.indexOf(province.val() + "-") === 0;
                }).appendTo(city);
                //clear out the value so it doesn't default to one it should not
                city.val('-1');
                $('#delivery-fee').html('');
            });
        }(jQuery));

        function rupiahFormat(nStr) {
            nStr += '';
            x = nStr.split(',');
            x1 = x[0];
            x2 = x.length > 1 ? ',' + x[1] : '';
            var rgx = /(\d+)(\d{3})/;
            while (rgx.test(x1)) {
                x1 = x1.replace(rgx, '$1' + '.' + '$2');
            }
            return x1 + x2;
        }
    </script>
@endsection
