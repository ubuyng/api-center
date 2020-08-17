@extends('layouts.dashboard')

@section('content')

<div class="container-fluid">

<div class="row">
                    <div class="col s12">

                    <img src="/storage/uploads/images/challenges/{{ $challenge->challenge_image }}" >
            <form class="col s12" method="POST" action="" enctype="multipart/form-data">
                    @csrf
        <div class="row">
            <div class="input-field col s6">
            <input type="text" id="challenge_name" value="{{$challenge->challenge_name}}" name="challenge_name">
                {!! e_form_error('challenge_name', $errors) !!}
                <label for="challenge_name">Challenge Name</label>
            </div>
            <div class="input-field col s6">
            <input type="text" id="challenge_points" value="{{$challenge->challenge_points}}" name="challenge_points" placeholder="Challenge Points">
            {!! e_form_error('challenge_points', $errors) !!}
            <label for="challenge_points">Challenge Points</label>
        </div>
    </div>
    <div class="row">
        <div class="input-field col s12">
            <textarea id="des" class="materialize-textarea"  name="challenge_des" placeholder="Challenge Description">{{$challenge->challenge_des}}</textarea>
            {!! e_form_error('challenge_des', $errors) !!}
            <label for="des">Challenge Description</label>
        </div>
    </div>
    <div class="row">
        <div class="input-field col s12">
        <input type="text" id="challenge_timer" value="{{$challenge->challenge_timer}}" name="challenge_timer" placeholder="Challenge Timer">
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

        <button class="waves-effect waves-light btn red" type="submit"> Update</button>

    </form>

    </div>
    </div>
    </div>
@endsection
