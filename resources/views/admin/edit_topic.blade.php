@extends('layouts.dashboard')

@section('page-css')
<link href="{{ asset('dashui/dist/css/pages/data-table.css') }}" rel="stylesheet">
@endsection
@section('page-js')
  <!-- This page plugin js -->
    <!-- ============================================================== -->
    <script src="{{ asset('dashui/assets/extra-libs/DataTables/datatables.min.js') }}"></script>
@endsection
@section('content')
<div class="page-titles">
                <div class="d-flex align-items-center">
                    <h5 class="font-medium m-b-0">Topics</h5>
                    <div class="custom-breadcrumb ml-auto">
                        <a href="#!" class="breadcrumb">Home</a>
                        <a href="#!" class="breadcrumb">Topic details</a>
                    </div>
                </div>
            </div>
<div class="container-fluid">

<div class="row">
                    <div class="col l8 s12">
                        <div class="card">
                            <div class="card-content">
                                <div class="d-flex no-block align-items-center">
                                    <h5 class="card-title">All Challenges</h5>
                                    <div class="ml-auto">
                                        <a class="waves-effect waves-light btn blue-grey darken-4 modal-trigger" href="#modal1">Create New Challenge</a>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table id="file_export" class="table table-bordered nowrap display">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Description</th>
                                                <th>points</th>
                                                <th>Level</th>
                                                <th>Views</th>
                                                <th>Challengers</th>
                                                <th>Category</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($challenges as $challenge)
                                        @if($challenge->topic_id == $topic->id)
                                            <tr>
                                                <td>
                                                    <a href="{{ route('edit_challenges', $challenge->id) }}" ><img src="/storage/uploads/images/challenges/{{ $challenge->challenge_image }}" class="circle" width="30px" /> {{$challenge->challenge_name}}</a>
                                                    <br>
                                                    <span><a href="{{ route('challenges_quiz', $challenge->id) }}" >View Quiz</a></span>
                                                </td>
                                                <td>{{$challenge->challenge_des}}</td>
                                                <td>{{$challenge->challenge_points}}</td>
                                                <td>{{$challenge->challenge_level}}</td>
                                                <td>{{$challenge->challenge_views}}</td>
                                                <td>{{$challenge->total_challengers}}</td>
                                                <td><span class="label label-danger">Challenge</span> </td>
                                            </tr>
                                            @endif
                                        @endforeach
                                       
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col l4 s12">
                        <div class="card">
                        <div class="ml-auto">
                                        <a class="waves-effect waves-light btn blue-grey darken-4 modal-trigger" href="#modal3">Edit Topic</a>
                                    </div>
                            <div class="card-content">
                                <div class="row el-element-overlay">
                                    <div class="col  l12">
                                        <div class="card">
                                            <div class="card-image">
                                                <div class="el-card-item">
                                                    <div class="el-card-avatar el-overlay-1"> <img src="/storage/uploads/images/topics/{{ $topic->topic_image }}" alt="topic image" />
                                                       
                                                    </div>
                                                    <div class="el-card-content">
                                                        <h5 class="m-b-0">{{ $topic->topic_name }}</h5>
                                                        <small>{{$topic->topic_des}}</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="ml-auto">
                                        <a class="waves-effect waves-light btn blue-grey darken-4 modal-trigger" href="#modal2">Create New Fact</a>
                                    </div>
                                <div class="collection">
                                    <a href="#!" class="collection-item active indigo"><i class="ti-layers m-r-10"></i>View All Facts</a>
                                    @foreach($facts as $fact)
                                    @if($fact->topic_id == $topic->id)
                                        <a href="{{ route('edit_facts', $fact->id) }}" class="collection-item"><i class="ti-star m-r-10"></i>{{$fact->fact_des}}</a>
                                    @endif
                                                    <!-- <a href="{{ route('edit_facts', $fact->id) }}" ><img src="/storage/uploads/images/facts/{{ $fact->fact_image }}" class="circle" width="30px" /> </a>                                        -->
                                        @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Modal 1 Structure -->
<div id="modal1" class="modal modal-fixed-footer">
    <div class="modal-content">
        <h4>Add Challenge</h4>


        
        <div class="row">
            <form class="col s12" method="POST" action="{{route('post_challenges')}}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="topic_id" value="{{$topic->id}}">
        <div class="row">
            <div class="input-field col s6">
            <input type="text" id="challenge_name" value="{{ old('challenge_name') }}" name="challenge_name" placeholder="Challenge Name">
                {!! e_form_error('challenge_name', $errors) !!}
                <label for="challenge_name">Challenge Name</label>
            </div>
            <div class="input-field col s6">
            <input type="text" id="challenge_points" value="{{ old('challenge_points') }}" name="challenge_points" placeholder="Challenge Points">
            {!! e_form_error('challenge_points', $errors) !!}
            <label for="challenge_points">Challenge Points</label>
        </div>
    </div>
    <div class="row">
        <div class="input-field col s6">
            <textarea id="des" class="materialize-textarea" value="{{ old('challenge_des') }}" name="challenge_des" placeholder="Challenge Description"></textarea>
            {!! e_form_error('challenge_des', $errors) !!}
            <label for="des">Challenge Description</label>
        </div>
        <div class="input-field col s6">
        <select  name="challenge_level" class="browser-default">
                    <option value="" >Pick Level</option>
                    <option value="easy">Easy</option>
                    <option value="medium">Medium</option>
                    <option value="hard">Hard</option>
                    <option value="Hell">Hell</option>
                </select>
        </div>
    </div>
    <div class="row">
        <div class="input-field col s12">
        <input type="text" id="challenge_timer" value="{{ old('challenge_timer') }}" name="challenge_timer" placeholder="Challenge Timer">
            {!! e_form_error('challenge_timer', $errors) !!}
            <label for="challenge_timer">Challenge Timer</label>
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

        <Button class="waves-effect waves-light btn red" type="submit">Submit</Button>

    </form>
</div>

    </div>
    <div class="modal-footer">
        <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat ">Close</a>
    </div>
</div>
                <!-- Modal 2 Structure -->
<div id="modal2" class="modal modal-fixed-footer">
    <div class="modal-content">
        <h4>Add Facts</h4>


        
        <div class="row">
            <form class="col s12" method="POST" action="{{route('post_facts')}}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="topic_id" value="{{$topic->id}}">
        <div class="row">
            <div class="input-field col s12">
            <input type="text" id="fact_des" value="{{ old('fact_des') }}" name="fact_des">
                {!! e_form_error('fact_des', $errors) !!}
                <label for="fact_des">Fact Desciption</label>
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

        <Button class="waves-effect waves-light btn red" type="submit">Submit</Button>

    </form>
</div>

    </div>
    <div class="modal-footer">
        <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat ">Close</a>
    </div>
</div>
                <!-- Modal 3 Structure -->
<div id="modal3" class="modal modal-fixed-footer">
    <div class="modal-content">
        <h4>Add Facts</h4>


        
        <div class="row">
        <form class="col s12" method="POST" action="" enctype="multipart/form-data">
                    @csrf
        <div class="row">
            <div class="input-field col s2">
            <input type="text" id="topic_name" value="{{$topic->topic_name}}" name="topic_name">
                {!! e_form_error('topic_name', $errors) !!}
                <label for="topic_name">Topic Name</label>
            </div>
    </div>
    <div class="row">
        <div class="input-field col s12">
            <textarea id="des" class="materialize-textarea"  name="topic_des" >{{$topic->topic_des}}</textarea>
            {!! e_form_error('topic_des', $errors) !!}
            <label for="des">Topic Description</label>
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
    <div class="modal-footer">
        <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat ">Close</a>
    </div>
</div>


@endsection
