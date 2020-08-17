@extends('layouts.dashboard')

@section('content')
@section('page-css')
<link href="{{ asset('dashui/dist/css/pages/user-card.css') }}" rel="stylesheet">
    <link href="{{ asset('dashui/assets/libs/magnific-popup/dist/magnific-popup.css') }}" rel="stylesheet">
@endsection
@section('page-js')
<script src="{{ asset('dashui/assets/libs/magnific-popup/dist/jquery.magnific-popup.min.js') }}"></script>
    <script src="{{ asset('dashui/assets/libs/magnific-popup/meg.init.js') }}"></script>
@endsection
 <!-- ============================================================== -->
            <!-- Title and breadcrumb -->
            <!-- ============================================================== -->
            <div class="page-titles">
                <div class="d-flex align-items-center">
                    <h5 class="font-medium m-b-0">Ecommerce Products</h5>
                    <div class="custom-breadcrumb ml-auto">
                        <a href="#!" class="breadcrumb">Home</a>
                        <a href="#!" class="breadcrumb">Ecommerce Products</a>
                    </div>
                </div>
            </div>
            <!-- ============================================================== -->
            <!-- Container fluid scss in scafholding.scss -->
            <!-- ============================================================== -->
            <div class="container-fluid">
            <div class="input-field dl support-select">
                                        <a class="waves-effect waves-light btn modal-trigger" href="#modal1">Add product</a>
                                        </div>
                <div class="row el-element-overlay">

                    @foreach($products as $product)
                    <div class="col m6 l3">
                        <div class="card">
                            <div class="card-image">
                                <div class="el-card-item">
                                    <div class="el-card-avatar el-overlay-1"> 
                                        <img src="/storage/uploads/images/products/{{ $product->product_image }}" alt="user" />
                                        <div class="el-overlay">
                                            <ul class="el-info">
                                                <li><a class="btn-floating image-popup-vertical-fit" href="/storage/uploads/images/products/{{ $product->product_image }}"><i class="material-icons">search</i></a></li>
                                                <li><a class="btn-floating" href="{{route('edit_product', $product->id)}}"><i class="material-icons">link</i></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="d-flex no-block align-items-center">
                                        <div class="m-l-15">
                                            <p>{{$product->product_type}}</p>
                                            @if($product->product_status == 1 )
                                        <small class="blue white-text">Active</small>
                                        @else
                                        <small class="red white-text">inactive</small>
                                        @endif
                                            <h5 class="m-b-0">{{$product->product_name}}</h5>
                                            <small>{{$product->product_des}}</small> <br>
                                            <small>Total orders: {{$product->product_orders}}</small>

                                            @if($product->product_status == 0)
                                                 <form class="col s12" method="POST" action="{{ route('product_status', $product->id) }}" >
                                                @csrf
                                                <input type="hidden" value="1" name="product_status">
                                            <button class="waves-effect waves-light btn btn-small  blue" type="submit"><i class="material-icons">save</i></button>
                                               </form>
                                               @else
                                               <form class="col s12" method="POST" action="{{ route('product_status', $product->id) }}" >
                                                @csrf
                                                <input type="hidden" value="0" name="product_status">
                                            <button class="waves-effect waves-light btn btn-small  red" type="submit"><i class="material-icons">block</i></button>
                                               </form>
                                               @endif
                                                            <span><a href="#" >edit</a></span>

                                        </div>
                                        <div class="ml-auto m-r-10">
                                            <a class="btn-floating btn-large waves-effect waves-light teal">{{$product->product_stocks}} left</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach

                </div>

                <!-- Modal Structure -->
<div id="modal1" class="modal modal-fixed-footer">
    <div class="modal-content">
        <h4>Add Product</h4>


        
        <div class="row">
            <form class="col s12" method="POST" action="" enctype="multipart/form-data">
                    @csrf
        <div class="row">
            <div class="input-field col s6">
            <input type="text" id="product_name" value="{{ old('product_name') }}" name="product_name" >
                {!! e_form_error('product_name', $errors) !!}
                <label for="product_name">Product Name</label>
            </div>
            <div class="input-field col s6">
            <input type="text" id="product_points" value="{{ old('product_points') }}" name="product_points">
            {!! e_form_error('product_points', $errors) !!}
            <label for="product_points">Points required</label>
        </div>
    </div>
        <div class="row">
            <div class="input-field col s6">
            <input type="text" id="product_stocks" value="{{ old('product_stocks') }}" name="product_stocks" >
                {!! e_form_error('product_stocks', $errors) !!}
                <label for="product_stocks">Total stock</label>
            </div>
            <div class="input-field col s6">
            <div class="file-field input-field">
        <div class="btn">
            <span>File</span>
            <input type="file" name="thumbnail">
        </div>
        <div class="file-path-wrapper">
            <input class="file-path validate" type="text">
        </div>
    </div>
        </div>
    </div>
    <div class="row">
        <div class="input-field col s6">
            <textarea id="des" class="materialize-textarea" value="{{ old('product_des') }}" name="product_des"></textarea>
            {!! e_form_error('product_des', $errors) !!}
            <label for="des">Product Description</label>
        </div>
        <div class="input-field col s6">
            <select  name="product_type" class="browser-default">
                <option value="featured">Featured Product</option>
                <option value="data">Data plan</option>
                <option value="fashion">Fashion Item</option>
                <option value="tech">Tech Item</option>
            </select>
        </div>
    </div>
    <div class="row">
        <div class="input-field col s12">
            <select  name="product_color" class="browser-default">
                <option value="normal">Normal Color</option>
                <option value="blue">Blue Color</option>
                <option value="green">green Color</option>
                <option value="yellow">Yellow Color</option>
                <option value="red">Red Color</option>
            </select>
        </div>
    </div>
    
        <button class="waves-effect waves-light btn red" type="submit">Submit</button>

    </form>
</div>

    </div>
    <div class="modal-footer">
        <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat ">Close</a>
    </div>
</div>


            </div>
            <!-- ============================================================== -->
            <!-- Container fluid scss in scafholding.scss -->
            <!-- ============================================================== -->
@endsection