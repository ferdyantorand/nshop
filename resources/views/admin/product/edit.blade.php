@extends('layouts.admin')

@section('content')

    <header class="blue accent-3 relative nav-sticky">
        <div class="container-fluid text-white">
            <div class="row p-t-b-10 ">
                <div class="col">
                    <h4> <i class="icon-table"></i> Edit Product</h4>
                </div>
            </div>
        </div>
    </header>


    <div class="content-wrapper animatedParent animateOnce">
        <div class="container">
            <section class="paper-card">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body b-b">
                                <h3 class="my-3">
                                    Step 1 (Product Information)
                                </h3>

                                <div class="container-fluid animatedParent animateOnce my-3">
                                    <div class="animated fadeInUpShort">
                                        <!-- Input -->
                                        {{ Form::open(['route'=>['admin.product.update'],'method' => 'post','id' => 'general-form', 'enctype' => 'multipart/form-data']) }}

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
                                        <div class="row">
                                            <div class="col-md-12">
                                                <input type="hidden" name="id" value="{{$product->id}}"/>
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Upload Main Image *</label>
                                                        @if(!empty($mainImage))
                                                            <img src="{{ asset('storage/products/'.$mainImage->path) }}" style="width: 200px;height: auto;">
                                                        @endif
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        {{--<input type="file" name="PhotoPosted" id="PhotoPosted" class="file-loading">--}}
                                                        {!! Form::file('main_image', array('id' => 'main_image', 'class' => 'file-loading', 'accept' => 'image/*,application/pdf')) !!}
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Upload Thumbnail Image *</label>
                                                        @if(!empty($thumbnailImage))
                                                            <img src="{{ asset('storage/products/'.$thumbnailImage->path) }}" style="width: 50px;height: auto;">
                                                        @endif
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        {{--<input type="file" name="PhotoPosted" id="PhotoPosted" class="file-loading">--}}
                                                        {!! Form::file('thumbnail_image', array('id' => 'thumbnail_image', 'class' => 'file-loading', 'accept' => 'image/*')) !!}
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Upload Detail Image *</label>
                                                        <br>
                                                        @foreach($detailImage as $image)
                                                            <img src="{{ asset('storage/products/'.$image->path) }}" style="width: 200px;height: auto;">
                                                        @endforeach
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        {!! Form::file('detail_image[]', array('id' => 'detail_image', 'class' => 'file-loading', 'multiple' => 'multiple')) !!}
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label for="validationCustom01">Product Name</label>
                                                        <input type="text" name="name" class="form-control" value="{{$product->name}}" readonly>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label for="sku">SKU</label>
                                                        <input type="text" class="form-control" id="sku" name="sku" value="{{$product->sku}}" required>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-3 mb-3">
                                                        <label for="category">Category</label>
                                                        <select id="category" name="category" class="custom-select form-control">
                                                            <option value="-1">Select Product Category</option>
                                                            @foreach($categories as $category)
                                                                <option value="{{ $category->id }}" @if($category->id == $product->category_id) selected @endif > {{ $category->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-3 mb-3">
                                                        <label>Color</label>
                                                        <input type="text" class="form-control" id="colour-edit"  name="colour" value="{{$product->colour}}"
                                                               style="text-transform: capitalize;" required>
                                                    </div>
                                                    <div class="col-md-3 mb-3">
                                                        <label>Price</label>
                                                        <input type="number" class="form-control" id="price-edit"  name="price" value="{{$product->price}}" required>
                                                    </div>
                                                    <div class="col-md-3 mb-3">
                                                        <label>Quantity</label>
                                                        <input type="number" class="form-control" id="qty" name="qty" value="{{$product->qty}}" required>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-3 mb-3">
                                                        <label>Weight</label>
                                                        <input type="number" class="form-control" id="weight" name="weight" value="{{$product->weight}}" required>
                                                    </div>
                                                    <div class="col-md-3 mb-3">
                                                        <label>Width</label>
                                                        <input type="number" class="form-control" id="width" name="width" value="{{$product->width}}">
                                                    </div>
                                                    <div class="col-md-3 mb-3">
                                                        <label>Height</label>
                                                        <input type="number" class="form-control" id="height" name="height" value="{{$product->height}}">
                                                    </div>
                                                    <div class="col-md-3 mb-3">
                                                        <label>Length</label>
                                                        <input type="number" class="form-control" id="length" name="length" value="{{$product->length}}">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="tags">Product Tags</label><br>
                                                    <input type="text" class="tags-input" id="tags" name="tags" placeholder="Add New"
                                                           value="{{$product->tag}}">
                                                </div>
                                                <div class="form-group">
                                                    <label for="productDetails">Product Details</label>
                                                    <textarea class="form-control p-t-40" id="description" name="description"
                                                              placeholder="Write Something..." rows="7" required>{{$product->description}}</textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label for="productDetails">Style Notes</label>
                                                    <textarea class="form-control p-t-40" id="style_notes" name="style_notes"
                                                              placeholder="Write Something..." rows="7" required>{{$product->style_notes}}</textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label for="status">Status *</label>
                                                    <select id="status" name="status" class="form-control">
                                                        @if($product->status == 1)
                                                            <option value="1" selected>Active</option>
                                                            <option value="2">Not Active</option>
                                                        @else
                                                            <option value="1">Active</option>
                                                            <option value="2" selected>Not Active</option>
                                                        @endif
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <input type="checkbox" id="is_customize" name="is_customize" class="form-check-input" checked/>
                                                    <label class="form-check-label" for="is_customize">
                                                        Customize
                                                    </label>
                                                </div>
                                                {{--<div class="row">--}}
                                                {{--<div class="col-md-12 mb-3">--}}
                                                {{--<label>Meta Title</label>--}}
                                                {{--<input type="text" name="meta_title" class="form-control">--}}
                                                {{--</div>--}}
                                                {{--<div class="col-md-12 mb-3">--}}
                                                {{--<label>Meta Description</label>--}}
                                                {{--<textarea id="meta_description" rows="2" class="form-control" name="meta_description"></textarea>--}}
                                                {{--</div>--}}
                                                {{--</div>--}}
                                                <div class="row">
                                                    <a href="{{ route('admin.product.index') }}" class="btn btn-danger">Exit</a>
                                                    <button class="btn btn-primary" type="submit">Publish</button>
                                                </div>
                                            </div>
                                        </div>
                                    {{ Form::close() }}
                                    <!-- #END# Input -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection


@section('styles')
    <link href="{{ URL::asset('css/fileinput.css') }}" rel="stylesheet">
@endsection

@section('scripts')
    <script type="text/javascript" src="{{ URL::asset('js/fileinput.js') }}"></script>

    <script>

        // FILEINPUT
        $("#main_image")
            .fileinput({
                allowedFileExtensions: ["jpg", "jpeg", "png"],
                showUpload: false,
            });
        $("#thumbnail_image")
            .fileinput({
                allowedFileExtensions: ["jpg", "jpeg", "png"],
                showUpload: false,
            });
        $("#detail_image")
            .fileinput({
                allowedFileExtensions: ["jpg", "jpeg", "png"],
                showUpload: false,
            });
    </script>
@endsection
