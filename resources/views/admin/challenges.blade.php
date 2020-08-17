@extends('layouts.dashboard')

@section('content')
<div class="container-fluid">

<div class="row">
                    <div class="col s12">
                        <div class="card">
                            <div class="card-content">
                                <div class="d-flex align-items-center">
                                    <div>
                                        <h5 class="card-title">Challenges</h5>
                                        <h6 class="card-subtitle">Showing All challenges</h6>
                                    </div>
                                </div>
                                <div class="ml-auto">
                                        <div class="input-field dl support-select">
                                        <a class="waves-effect waves-light btn modal-trigger" href="#modal1">New Challenge</a>
                                        </div>
                                    </div>
                                <div class="table-responsive m-b-20">
                                    <table class="">
                                        <thead>
                                            <tr>
                                                <th>Challenge Name</th>
                                                <th>Description</th>
                                                <th>No of Quiz</th>
                                                <th>Times Completed</th>
                                                <th>Points</th>
                                                <th>Level</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($challenges as $challenge)
                                            <tr>
                                                <td>
                                                    <div class="d-flex no-block align-items-center">
                                                        <div class="m-r-10"><img src="/storage/uploads/images/challenges/{{ $challenge->challenge_image }}" alt="user" class="circle" width="45" /></div>
                                                        <div class="">
                                                            <h5 class="m-b-0 font-16 font-medium"> {{ $challenge->challenge_name }}   
                                                            <span><a href="{{ route('challenges_quiz', $challenge->id) }}" >Quizzes</a></span>
</h5>
                                                            <span><a href="{{ route('edit_challenges', $challenge->id) }}" >Edit</a></span>
                                                            </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <p class="">{{ $challenge->challenge_des }}</p>
                                                </td>
                                                <td class="blue-grey-text text-darken-4 font-medium">{{ $challenge->quiz_count }}</td>
                                                <td>{{ $challenge->total_challengeers}}</td>
                                                <td><span class="label label-info">{{ $challenge->challenge_points }}</span></td>
                                                <td><span class="label label-success">{{ $challenge->challenge_level }}</span></td>
                                                <td class="green-text"><i class="fa fa-arrow-up"></i>
                                                @if($challenge->challenge_status == 1)
                                                Active                  
                                            <br>
                                            <form class="col s12" method="POST" action="{{ route('challenge_status', $challenge->id) }}" >
                                                @csrf
                                                <input type="hidden" value="0" name="challenge_status">
                                            <button class="waves-effect waves-light btn btn-small  red" type="submit"><i class="material-icons">block</i></button>
                                               </form>
                                            @else
                                                Inactive
                                            <br>
                                            <form class="col s12" method="POST" action="{{ route('challenge_status', $challenge->id) }}" >
                                                @csrf
                                                <input type="hidden" value="1" name="challenge_status">
                                            <button class="waves-effect waves-light btn btn-small  blue" type="submit"><i class="material-icons">save</i></button>
                                               </form>
                                               <br>
                                               <form class="col s12" method="POST" action="{{ route('delete_challenges', $challenge->id) }}" >
                                               @csrf
                                                <input type="hidden" value="{{ $challenge->id }}" name="data_id">
                                            <button class="waves-effect waves-light btn btn-small  red" type="submit"><i class="material-icons">delete</i></button>
                                               </form>
                                                @endif
                                            </td>
                                               
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Trigger -->

<!-- Modal Structure -->
<div id="modal1" class="modal modal-fixed-footer">
    <div class="modal-content">
        <h4>Add Challenge</h4>


        
        <div class="row">
            <form class="col s12" method="POST" action="" enctype="multipart/form-data">
                    @csrf
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
        <div class="input-field col s6">
        <input type="text" id="challenge_timer" value="{{ old('challenge_timer') }}" name="challenge_timer" placeholder="Challenge Timer">
            {!! e_form_error('challenge_timer', $errors) !!}
            <label for="challenge_timer">Challenge Timer</label>
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

        <input class="waves-effect waves-light btn red" type="submit"/>

    </form>
</div>

    </div>
    <div class="modal-footer">
        <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat ">Close</a>
    </div>
</div>

   

</div>


@endsection