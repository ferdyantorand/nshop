@extends('layouts.admin')

@section('content')

    <header class="blue accent-3 relative nav-sticky">
        <div class="container-fluid text-white">
            <div class="row p-t-b-10 ">
                <div class="col">
                    <h4> <i class="icon-table"></i> New Product</h4>
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
                                    Step 2 (Customize Option)
                                </h3>
                                <h3 class="my-3">
                                    Product = {{$productPosition->product->name}}
                                </h3>

                                <div class="container-fluid animatedParent animateOnce my-3">
                                    <div class="animated fadeInUpShort">
                                        <!-- Input -->
                                        {{ Form::open(['route'=>['admin.product.update.customize', $productPosition->id],'method' => 'post','id' => 'general-form']) }}
                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="row">
                                                    <div class="col-md-12 mb-3">
                                                        <canvas id="myCanvas" width="600" height="600"></canvas>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="row">
                                                    <div class="col-md-12 mb-3">
                                                        <label class="form-label">Position Name</label>
                                                        <input id="position_name" name="position_name" type="text" value="{{$productPosition->name}}" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">X</label>
                                                        <input id="position_x" name="position_x" type="number" value="{{$productPosition->pos_x}}" onkeyup="ChangePosition()" class="form-control">
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Y</label>
                                                        <input id="position_y" name="position_y" type="number" value="{{$productPosition->pos_y}}" onkeyup="ChangePosition()" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-md-11 col-sm-11 col-xs-12" style="margin: 3% 0 3% 0;">
                                                    <a href="{{route('admin.product.show', ['item' => $productPosition->product_id])}}" class="btn btn-danger">Back</a>
                                                    <input type="submit" class="btn btn-success" value="Save">
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
                showPreview: false
            });

        window.onload = function(){
            var posX = $('#position_x').val();
            var posY = $('#position_y').val();
            var canvas = document.getElementById("myCanvas");
            var context = canvas.getContext("2d");
            var imageObj = new Image();
            imageObj.onload = function(){
                context.drawImage(imageObj, 10, 10);
                context.textAlign = 'center';
                context.font = "16px Bodoni";
                context.fillStyle = "red";
                context.fillText("TEST1234", posX, posY);
            };

            imageObj.src = "{{ asset('storage/products/'.$mainImage->path) }}";
        };

        function ChangePosition(){
            var posX = $('#position_x').val();
            var posY = $('#position_y').val();

            var canvas = document.getElementById("myCanvas");
            var context = canvas.getContext("2d");
            var imageObj = new Image();
            imageObj.onload = function(){
                context.drawImage(imageObj, 10, 10);
                context.textAlign = 'center';
                context.font = "16px Bodoni";
                context.fillStyle = "red";
                context.fillText("TEST1234", posX, posY);
            };

            imageObj.src = "{{ asset('storage/products/'.$mainImage->path) }}";
        }

        // CANVAS JAVASCRIPT

        // canvas related variables
        var canvas = document.getElementById("myCanvas");
        var ctx = canvas.getContext("2d");

        // variables used to get mouse position on the canvas
        var $canvas = $("#myCanvas");
        var canvasOffset = $canvas.offset();
        var offsetX = canvasOffset.left;
        var offsetY = canvasOffset.top;
        var scrollX = $canvas.scrollLeft();
        var scrollY = $canvas.scrollTop();

        // variables to save last mouse position
        // used to see how far the user dragged the mouse
        // and then move the text by that distance
        var startX;
        var startY;

        // an array to hold text objects
        var texts = [];

        // this var will hold the index of the hit-selected text
        var selectedText = -1;

        // clear the canvas & redraw all texts
        function draw() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            for (var i = 0; i < texts.length; i++) {
                var text = texts[i];
                ctx.fillText(text.text, text.x, text.y);
            }
        }

        // test if x,y is inside the bounding box of texts[textIndex]
        function textHittest(x, y, textIndex) {
            var text = texts[textIndex];
            return (x >= text.x && x <= text.x + text.width && y >= text.y - text.height && y <= text.y);
        }

        // handle mousedown events
        // iterate through texts[] and see if the user
        // mousedown'ed on one of them
        // If yes, set the selectedText to the index of that text
        function handleMouseDown(e) {
            e.preventDefault();
            startX = parseInt(e.clientX - offsetX);
            startY = parseInt(e.clientY - offsetY);
            // Put your mousedown stuff here
            for (var i = 0; i < texts.length; i++) {
                if (textHittest(startX, startY, i)) {
                    selectedText = i;
                }
            }
        }

        // done dragging
        function handleMouseUp(e) {
            e.preventDefault();
            selectedText = -1;
        }

        // also done dragging
        function handleMouseOut(e) {
            e.preventDefault();
            selectedText = -1;
        }

        // handle mousemove events
        // calc how far the mouse has been dragged since
        // the last mousemove event and move the selected text
        // by that distance
        function handleMouseMove(e) {
            if (selectedText < 0) {
                return;
            }
            e.preventDefault();
            mouseX = parseInt(e.clientX - offsetX);
            mouseY = parseInt(e.clientY - offsetY);

            // Put your mousemove stuff here
            var dx = mouseX - startX;
            var dy = mouseY - startY;
            startX = mouseX;
            startY = mouseY;

            var text = texts[selectedText];
            text.x += dx;
            text.y += dy;
            draw();
        }

        // listen for mouse events
        $("#myCanvas").mousedown(function (e) {
            handleMouseDown(e);
        });
        $("#myCanvas").mousemove(function (e) {
            handleMouseMove(e);
        });
        $("#myCanvas").mouseup(function (e) {
            handleMouseUp(e);
        });
        $("#myCanvas").mouseout(function (e) {
            handleMouseOut(e);
        });

        $("#new_position").click(function () {

            // calc the y coordinate for this text on the canvas
            var y = texts.length * 20 + 20;

            // get the text from the input element
            var text = {
                text: 'New Position',
                x: 20,
                y: y
            };

            // calc the size of this text for hit-testing purposes
            ctx.font = "16px verdana";
            text.width = ctx.measureText(text.text).width;
            text.height = 16;

            // put this new text in the texts array
            texts.push(text);

            // redraw everything
            draw();

        });
        // CANVAS JAVASCRIPT END
    </script>

@endsection