@extends('layouts.mvp_dash')

@section('page-css')
    <style>
   
    </style>
@endsection

@section('content')
 
@if ( Auth::user()->user_role == 'customer')

                
                <!-- Dashboard Headline -->
                <div class="dashboard-headline">
                    <h3> Update Project</h3>
    
                    <!-- Breadcrumbs -->
                    <nav id="breadcrumbs" class="dark">
                        <ul>
                            <li><a href="{{route('dashboard')}}">Dashboard</a></li>
                            <li><a href="{{route('dash_projects')}}">Projects</a></li>
                            <li>Update Projects</li>
                        </ul>
                    </nav>
                </div>
        
           
			<!-- Row -->
			<div class="row">

                    <!-- Dashboard Box -->
                    <div class="col-xl-12">
                        <div class="dashboard-box margin-top-0">
    
                            <!-- Headline -->
                            <div class="headline">
                                <h3><i class="icon-feather-folder-plus"></i> Update project status</h3>
                            </div>
    
                            <div class="content with-padding padding-bottom-10">
                                <div class="row">
    
                                    <div class="col-xl-12">
                                        <div class="submit-field">
                                            <form action="" method="post">
                                                @csrf
                                        <h5>What's the current status of your {{$project->sub_category_name}}</h5>
                                        <div class="feedback-yes-no margin-top-0">
                                                <div class="radio">
                                                    <input id="radio-done" name="status" value="3" type="radio" checked>
                                                    <label for="radio-done"><span class="radio-label"></span> Project Completed</label>
                                                </div>
                                                <br>
                                                <div class="radio">
                                                    <input id="radio-progress" name="status" value="1" type="radio">
                                                    <label for="radio-progress"><span class="radio-label"></span> In progress</label>
                                                </div>
                                                <br>
                                                <div class="radio">
                                                    <input id="radio-deciding" name="status" value="2" type="radio">
                                                    <label for="radio-deciding"><span class="radio-label"></span> Selected a pro</label>
                                                </div>
                                                <br>
                                                <div class="radio">
                                                    <input id="radio-cancel" name="status" value="4" type="radio">
                                                    <label for="radio-cancel"><span class="radio-label"></span> On hold or canceled</label>
                                                </div>
                                                <p id="hold_text">Pros will no longer reach out to you about this project. <br> (You can always continue the project later by updating its status)</p>
                                            </div>
                                            <div class="col-xl-12">
                                                    <button type="submit" class="button ripple-effect big margin-top-30"><i class="icon-feather-plus"></i> Update Project</a>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
    
                                </div>
                            </div>
                        </div>
                    </div>
    

    
                </div>
                <!-- Row / End -->
    
    
    
    
@elseif ( Auth::user()->user_role == 'pros')

@endif
			

    @section('page-js') 
    
    <script>
            $('#hold_text').hide();
    $('#radio-deciding').click(function() {
        $('#hold_text').hide();
    });
    $('#radio-progress').click(function() {
        $('#hold_text').hide();
    });
    $('#radio-done').click(function() {
        $('#hold_text').hide();
    });
    $('#radio-cancel').click(function() {
        $('#hold_text').show();
    });
    </script>
    @endsection

@endsection