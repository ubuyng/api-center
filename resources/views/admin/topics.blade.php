@extends('layouts.dashboard')

@section('content')
 <!-- ============================================================== -->
            <!-- Title and breadcrumb -->
            <!-- ============================================================== -->
            <div class="page-titles">
                <div class="d-flex align-items-center">
                    <h5 class="font-medium m-b-0">Topics</h5>
                    <div class="custom-breadcrumb ml-auto">
                        <a href="#!" class="breadcrumb">Home</a>
                        <a href="#!" class="breadcrumb">Topics</a>
                    </div>
                </div>
            </div>
            <!-- ============================================================== -->
            <!-- Container fluid scss in scafholding.scss -->
            <!-- ============================================================== -->
            <div class="container-fluid">
            <div class="input-field dl support-select">
                                        <a class="waves-effect waves-light btn modal-trigger" href="#modal1">Add Topics</a>
                                        </div>
            
                <div class="row">
                    <!-- Column -->
                    @foreach($topics as $topic)
                    <div class="col l4">
                        <div class="card">
                            <img class="card-img-top responsive-img" src="/storage/uploads/images/topics/{{ $topic->topic_image }}" alt="Card image cap">
                            <div class="card-content">
                                <h5>{{$topic->topic_name}}</h5>
                                <p class="m-b-0 m-t-10">{{$topic->topic_des}}</p>
                                <a href="{{route('edit_topic', $topic->id)}}" class="waves-effect waves-light btn btn-round indigo m-t-20">Explore</a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Modal Structure -->
<div id="modal1" class="modal modal-fixed-footer">
    <div class="modal-content">
        <h4>Add Topics</h4>


        
        <div class="row">
            <form class="col s12" method="POST" action="" enctype="multipart/form-data">
                    @csrf
        <div class="row">
            <div class="input-field col s6">
            <input type="text" id="topic_name" value="{{ old('topic_name') }}" name="topic_name" >
                {!! e_form_error('topic_name', $errors) !!}
                <label for="topic_name">Topic Name</label>
            </div>
            <div class="input-field col s6">
            <input type="text" id="topic_des" value="{{ old('topic_des') }}" name="topic_des">
            {!! e_form_error('topic_des', $errors) !!}
            <label for="topic_des">Topic Des</label>
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