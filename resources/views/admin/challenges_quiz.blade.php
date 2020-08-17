@extends('layouts.dashboard')

@section('content')
<div class="container-fluid">

<div class="row">
                    <div class="col s12">
                        <div class="card">
                            <div class="card-content">
                                <div class="d-flex align-items-center">
                                    <div>
                                        <h5 class="card-title">{{$challenge->quiz_qus}} Quizzes</h5>
                                        <h6 class="card-subtitle">Showing All Quizzes</h6>
                                    </div>
                                </div>
                                <div class="ml-auto">
                                        <div class="input-field dl support-select">
                                        <a class="waves-effect waves-light btn modal-trigger" href="#modal1">Add Quiz</a>
                                        </div>
                                    </div>
                                <div class="table-responsive m-b-20">
                                    <table class="">
                                        <thead>
                                            <tr>
                                                <th>Question</th>
                                                <th>Answer</th>
                                                <th>Option 1</th>
                                                <th>Option 2</th>
                                                <th>Option 3</th>
                                                <th>Option 4</th>
                                                <!-- <th>Quiz Type</th> -->
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($quizzes as $quiz)
                                            @if($challenge->id == $quiz->challenge_id)
                                            <tr>
                                                <td>
                                                    <div class="d-flex no-block align-items-center">
                                                        <div class="">
                                                            <h5 class="m-b-0 font-16 font-medium"> {{ $quiz->quiz_qus }}</h5>
                                                            <span><a href="{{ route('edit_challenges', $quiz->id) }}" >Edit</a></span>
                                                            </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <p class="">{{ $quiz->answer }}</p>
                                                </td>
                                                <td class="blue-grey-text text-darken-4 font-medium">{{ $quiz->option_1 }}</td>
                                                <td>{{ $quiz->option_2}}</td>
                                                <td>{{ $quiz->option_3}}</td>
                                                <td>{{ $quiz->option_4}}</td>
                                                <!-- <td><span class="label label-info">{{ $quiz->quiz_type }}</span></td> -->
                                            </tr>
                                            @endif
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
        <h4>Add Quiz</h4>


        
        <div class="row">
            <form class="col s12" method="POST" action="{{route('post_quiz')}}" >
                    @csrf
                    <input type="hidden" value="{{$challenge->id}}" name="challenge_id">
        <div class="row">
            <div class="input-field col s6">
            <input type="text" id="quiz_qus" value="{{ old('quiz_qus') }}" name="quiz_qus" placeholder="Question">
                {!! e_form_error('quiz_qus', $errors) !!}
                <label for="quiz_qus">Question</label>
            </div>
            <div class="input-field col s6">
            <input type="text" id="answer" value="{{ old('answer') }}" name="answer" placeholder="Answer">
            {!! e_form_error('answer', $errors) !!}
            <label for="answer">Answers</label>
        </div>
    </div>
    <div class="row">
        <div class="input-field col s12">
            <textarea id="des" class="materialize-textarea" value="{{ old('option_1') }}" name="option_1" placeholder="option 1"></textarea>
            {!! e_form_error('option_1', $errors) !!}
            <label for="des">option 1</label>
        </div>
    </div>
    <div class="row">
        <div class="input-field col s12">
            <textarea id="des" class="materialize-textarea" value="{{ old('option_2') }}" name="option_2" placeholder="option 2"></textarea>
            {!! e_form_error('option_2', $errors) !!}
            <label for="des">option 2</label>
        </div>
    </div>
    <div class="row">
        <div class="input-field col s12">
            <textarea id="des" class="materialize-textarea" value="{{ old('option_3') }}" name="option_3" placeholder="option 3"></textarea>
            {!! e_form_error('option_3', $errors) !!}
            <label for="des">option 3</label>
        </div>
    </div>
    <div class="row">
        <div class="input-field col s12">
            <textarea id="des" class="materialize-textarea" value="{{ old('option_4') }}" name="option_4" placeholder="option 4"></textarea>
            {!! e_form_error('option_4', $errors) !!}
            <label for="des">option 4</label>
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