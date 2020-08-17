@extends('layouts.dashboard')

@section('content')
<div class="page-titles">
                <div class="d-flex align-items-center">
                    <h5 class="font-medium m-b-0">Sliders</h5>
                    <div class="custom-breadcrumb ml-auto">
                        <a href="#!" class="breadcrumb">Home</a>
                        <a href="#!" class="breadcrumb">Edit Sliders</a>
                    </div>
                </div>
            </div>
<div class="container-fluid">

<div class="row">
                    <div class="col s12">
                    <div class="card">
                    
                    <img src="/storage/uploads/images/sliders/{{ $slider->slider_image }}" >
                    @if($slider->challenge_id != null)
                                <h5>Challenge</h5>
                            @elseif($slider->topic_id != null)
                                <h5>Topic</h5>
                            @elseif($slider->fact_id != null)
                                <h5>Fact</h5>
                            @else($slider->slider_url != null)
                                <h5>{{$slider->slider_url}}</h5>
                            @endif
                    <form class="col s12" method="POST" action="" enctype="multipart/form-data">
                    @csrf
        <div class="row">
            <div class="input-field col s6">
            <select  name="challenge_id" class="browser-default">
                    <option value="" >Pick Challenge *</option>
        @foreach($challenges as $challenge)
                    <option value="{{$challenge->id}}" >{{$challenge->challenge_name}}</option>
                    @endforeach
                </select>
        </div>
        <div class="input-field col s6">
        <select  name="topic_id" class="browser-default">
                    <option value="" >Pick Topic *</option>
        @foreach($topics as $topic)
                    <option value="{{$topic->id}}" >{{$topic->topic_name}}</option>
                    @endforeach
                </select>
        </div>
        <div class="row">
            <div class="input-field col s6">
            <select  name="fact_id" class="browser-default">
                    <option value="" >Pick Fact *</option>
        @foreach($facts as $fact)
                    <option value="{{$fact->id}}" >{{$fact->fact_des}}</option>
                    @endforeach
                </select>
        </div>
        <div class="input-field col s6">
        <input type="text" id="slider_url" value="{{ old('slider_url') }}" name="slider_url" placeholder="slider url">
            {!! e_form_error('product_sslider_urltocks', $errors) !!}
            <label for="slider_url">Slider url</label>
        </div>
    </div>
        <div class="row">
            <div class="input-field col s12">
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
        <button class="waves-effect waves-light btn red" type="submit">Submit</button>

    </form>

    </div>
    </div>
    </div>
    </div>
@endsection
