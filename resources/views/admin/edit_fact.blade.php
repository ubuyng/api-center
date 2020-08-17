@extends('layouts.dashboard')

@section('content')
<div class="page-titles">
                <div class="d-flex align-items-center">
                    <h5 class="font-medium m-b-0">Facts</h5>
                    <div class="custom-breadcrumb ml-auto">
                        <a href="#!" class="breadcrumb">Home</a>
                        <a href="#!" class="breadcrumb">Edit Fact</a>
                    </div>
                </div>
            </div>
<div class="container-fluid">

<div class="row">
                    <div class="col s12">
                    <div class="card">
                    
                    <img src="/storage/uploads/images/facts/{{ $fact->fact_image }}" >
            <form class="col s12" method="POST" action="" enctype="multipart/form-data">
                    @csrf
    <div class="row">
        <div class="input-field col s12">
            <textarea id="des" class="materialize-textarea"  name="fact_des" >{{$fact->fact_des}}</textarea>
            {!! e_form_error('fact_des', $errors) !!}
            <label for="des">Fact Description</label>
        </div>
    </div>
    <div class="row">
        <div class="input-field col s12">
        <select  name="topic_id" class="browser-default">
                    <option value="" >Pick Topic Required *</option>
        @foreach($topics as $topic)
                    <option value="{{$topic->id}}" >{{$topic->topic_name}}</option>
                    @endforeach
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
    </div>
@endsection
