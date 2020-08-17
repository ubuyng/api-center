@extends('layouts.dashboard')

@section('content')
 <!-- ============================================================== -->
            <!-- Title and breadcrumb -->
            <!-- ============================================================== -->
            <div class="page-titles">
                <div class="d-flex align-items-center">
                    <h5 class="font-medium m-b-0">Sliders</h5>
                    <div class="custom-breadcrumb ml-auto">
                        <a href="#!" class="breadcrumb">Home</a>
                        <a href="#!" class="breadcrumb">Sliders</a>
                    </div>
                </div>
            </div>
            <!-- ============================================================== -->
            <!-- Container fluid scss in scafholding.scss -->
            <!-- ============================================================== -->
            <div class="container-fluid">
            <div class="input-field dl support-select">
                                        <a class="waves-effect waves-light btn modal-trigger" href="#modal1">Add Sliders</a>
                                        </div>
            
                <div class="row">
                    <!-- Column -->
                    @foreach($sliders as $slider)
                    <div class="col l4">
                        <div class="card">
                            <img class="card-img-top responsive-img" src="/storage/uploads/images/sliders/{{ $slider->slider_image }}" alt="Card image cap">
                            <div class="card-content">
                            @if($slider->challenge_id != null)
                                <h5>Challenge</h5>
                            @elseif($slider->topic_id != null)
                                <h5>Topic</h5>
                            @elseif($slider->fact_id != null)
                                <h5>Fact</h5>
                            @else($slider->slider_url != null)
                                <h5>{{$slider->slider_url}}</h5>
                            @endif
                                <a href="{{route('edit_sliders', $slider->id)}}" class="waves-effect waves-light btn btn-round indigo m-t-20">Edit</a>
                                <form class="col s12" method="POST" action="{{ route('delete_slider', $slider->id) }}" >
                                               @csrf
                                                <input type="hidden" value="{{ $slider->id }}" name="data_id">
                                            <button class="waves-effect waves-light btn btn-small  red" type="submit"><i class="material-icons">delete</i></button>
                                               </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Modal Structure -->
<div id="modal1" class="modal modal-fixed-footer">
    <div class="modal-content">
        <h4>Add Sliders</h4>


        
        <div class="row">
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
    <div class="modal-footer">
        <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat ">Close</a>
    </div>
</div>


            </div>
            <!-- ============================================================== -->
            <!-- Container fluid scss in scafholding.scss -->
            <!-- ============================================================== -->
@endsection