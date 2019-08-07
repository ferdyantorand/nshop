@extends('layouts.admin')

@section('content')

    <header class="blue accent-3 relative nav-sticky">
        <div class="container-fluid text-white">
            <div class="row p-t-b-10 ">
                <div class="col">
                    <h4> <i class="icon-table"></i> Detail Product</h4>
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
                                    Produk Detail
                                </h3>
                                <div class="container-fluid animatedParent animateOnce my-3">
                                    <div class="animated fadeInUpShort">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="row">
                                                    <div class="col-md-12 mb-3">
                                                        <label class="form-label">Image</label>
                                                    </div>
                                                    <div class="col-md-12 mb-3">
                                                        @foreach($images as $image)
                                                            <img src="{{ asset('storage/products/'.$image->path) }}" style="width: 200px;height: auto;">
                                                        @endforeach
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label for="validationCustom01">Product Name</label>
                                                        <input type="text" name="name" class="form-control" value="{{$product->name}}" disabled>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label for="sku">SKU</label>
                                                        <input type="text" class="form-control" id="sku" name="sku" value="{{$product->sku}}" disabled>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-3 mb-3">
                                                        <label for="category">Category</label>
                                                        <input type="text" class="form-control" id="sku" name="sku" value="{{$product->category->name}}" disabled>
                                                    </div>
                                                    <div class="col-md-3 mb-3">
                                                        <label for="validationCustom04">Color</label>
                                                        <input type="text" class="form-control" id="colour"  name="colour" value="{{$product->colour}}" disabled>
                                                    </div>
                                                    <div class="col-md-3 mb-3">
                                                        <label for="validationCustom04">Price</label>
                                                        <input type="number" class="form-control" id="price"  name="price" value="{{$product->price}}" disabled>
                                                    </div>
                                                    <div class="col-md-3 mb-3">
                                                        <label for="sku">Quantity</label>
                                                        <input type="number" class="form-control" id="qty" name="qty" value="{{$product->qty}}" disabled>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-3 mb-3">
                                                        <label>Weight</label>
                                                        <input type="number" class="form-control" id="weight" name="weight" value="{{$product->weight}}" disabled>
                                                    </div>
                                                    <div class="col-md-3 mb-3">
                                                        <label>Width</label>
                                                        <input type="number" class="form-control" id="width" name="width" value="{{$product->width}}" disabled>
                                                    </div>
                                                    <div class="col-md-3 mb-3">
                                                        <label>Height</label>
                                                        <input type="number" class="form-control" id="height" name="height" value="{{$product->height}}" disabled>
                                                    </div>
                                                    <div class="col-md-3 mb-3">
                                                        <label>Length</label>
                                                        <input type="number" class="form-control" id="length" name="length" value="{{$product->length}}" disabled>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="productDetails">Style Notes</label>
                                                    <textarea class="form-control p-t-40" id="style_notes" name="style_notes"
                                                              placeholder="Write Something..." rows="7" disabled>{{$product->style_notes}}</textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label for="productDetails">Product Details</label>
                                                    <textarea class="form-control p-t-40" id="description" name="description"
                                                              placeholder="Write Something..." rows="7" disabled>{{$product->description}}</textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label for="tags">Product Tags</label><br>
                                                    <input type="text" class="tags-input" id="tags" name="tags" disabled
                                                           value="{{$product->tag}}">
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
                                            </div>
                                        </div>
                                    <!-- #END# Input -->
                                    </div>
                                </div>
                                <hr>
                                <h3 class="my-3">
                                    Customize Position Detail
                                </h3>
                                <div class="container-fluid animatedParent animateOnce my-3">
                                    <div class="animated fadeInUpShort">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="row">
                                                    @foreach($productPosition as $position)
                                                        <div class="col-md-6">
                                                            <div class="row">
                                                                <div class="col-md-12 mb-3">
                                                                    <label class="form-label">Position Name</label>
                                                                    <input id="position_name" name="position_name" type="text" value="{{$position->name}}" class="form-control">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="row">
                                                                <div class="col-md-12 mb-3">
                                                                    <a href="{{route('admin.product.edit.customize',['item' => $position->id])}}" class="btn btn-primary">Edit Customize</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                                <a href="{{route('admin.product.create.customize',['item' => $product->id])}}" class="btn btn-success">Add New Customize</a>
                                            </div>
                                        </div>
                                    <!-- #END# Input -->
                                    </div>
                                </div>
                                <hr>
                                <div class="row center">
                                    <a href="{{ route('admin.product.index') }}" class="btn btn-success">Back</a>
                                    <a href="{{ route('admin.product.edit', ['item' => $product->id]) }}" class="btn btn-danger">Edit</a>
                                    <a href="{{ route('admin.product.create-copy', ['item' => $product->id]) }}" class="btn btn-primary">Duplicate Product</a>
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
        $("#detail_image")
            .fileinput({
                allowedFileExtensions: ["jpg", "jpeg", "png"],
                showUpload: false,
            });
    </script>
@endsection
