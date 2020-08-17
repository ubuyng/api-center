@extends('layouts.dashboard')

@section('content')
<div class="page-titles">
                <div class="d-flex align-items-center">
                    <h5 class="font-medium m-b-0">Ecommerce Products</h5>
                    <div class="custom-breadcrumb ml-auto">
                        <a href="#!" class="breadcrumb">Home</a>
                        <a href="#!" class="breadcrumb">Edit Product</a>
                    </div>
                </div>
            </div>
<div class="container-fluid">

<div class="row">
                    <div class="col s12">

                    <img src="/storage/uploads/images/products/{{ $product->product_image }}" >
            <form class="col s12" method="POST" action="" enctype="multipart/form-data">
                    @csrf
        <div class="row">
            <div class="input-field col s6">
            <input type="text" id="product_name" value="{{$product->product_name}}" name="product_name">
                {!! e_form_error('product_name', $errors) !!}
                <label for="product_name">Product Name</label>
            </div>
            <div class="input-field col s6">
            <input type="text" id="product_points" value="{{$product->product_points}}" name="product_points" placeholder="Product Points">
            {!! e_form_error('product_points', $errors) !!}
            <label for="product_points">Product Points</label>
        </div>
    </div>
    <div class="row">
        <div class="input-field col s12">
            <textarea id="des" class="materialize-textarea"  name="product_des" placeholder="Product Description">{{$product->product_des}}</textarea>
            {!! e_form_error('product_des', $errors) !!}
            <label for="des">Product Description</label>
        </div>
    </div>
    <div class="row">
        <div class="input-field col s12">
        <input type="text" id="product_stocks" value="{{$product->product_stocks}}" name="product_stocks" placeholder="Product Stock">
            {!! e_form_error('product_stocks', $errors) !!}
            <label for="product_stocks">Product Stock</label>
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
    <div class="row">
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

        <button class="waves-effect waves-light btn red" type="submit"> Update</button>

    </form>

    </div>
    </div>
    </div>
@endsection
