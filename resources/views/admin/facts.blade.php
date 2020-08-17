@extends('layouts.dashboard')

@section('content')
 <!-- ============================================================== -->
            <!-- Title and breadcrumb -->
            <!-- ============================================================== -->
            <div class="page-titles">
                <div class="d-flex align-items-center">
                    <h5 class="font-medium m-b-0">Facts</h5>
                    <div class="custom-breadcrumb ml-auto">
                        <a href="#!" class="breadcrumb">Home</a>
                        <a href="#!" class="breadcrumb">Facts</a>
                    </div>
                </div>
            </div>
            <!-- ============================================================== -->
            <!-- Container fluid scss in scafholding.scss -->
            <!-- ============================================================== -->
            <div class="container-fluid">
            <div class="input-field dl support-select">
                                        <a class="waves-effect waves-light btn modal-trigger" href="#modal1">Add Facts</a>
                                        </div>
            
                <div class="row">
                    <!-- Column -->
                    @foreach($facts as $fact)
                    <div class="col l4">
                        <div class="card">
                            <img class="card-img-top responsive-img" src="/storage/uploads/images/facts/{{ $fact->fact_image }}" alt="Card image cap">
                            <div class="card-content">
                                <h5>{{$fact->fact_des}}</h5>
                                <a href="{{route('edit_facts', $fact->id)}}" class="waves-effect waves-light btn btn-round indigo m-t-20">Edit0</a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Modal Structure -->
<div id="modal1" class="modal modal-fixed-footer">
    <div class="modal-content">
        <h4>Add Facts</h4>


        
        <div class="row">
            <form class="col s12" method="POST" action="" enctype="multipart/form-data">
                    @csrf
        <div class="row">
            <div class="input-field col s6">
            <input type="text" id="fact_des" value="{{ old('fact_des') }}" name="fact_des">
            {!! e_form_error('fact_des', $errors) !!}
            <label for="fact_des">Fact Des</label>
        </div>
        <div class="input-field col s6">
        <select  name="topic_id" class="browser-default">
                    <option value="" >Pick Topic Required *</option>
        @foreach($topics as $topic)
                    <option value="{{$topic->id}}" >{{$topic->topic_name}}</option>
                    @endforeach
                </select>
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