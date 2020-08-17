@extends('layouts.mvp_dash')

@section('page-css')
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">


    <style>
   .modal-content {
    padding: 30px;
}
    </style>
@endsection

@section('content')
 
@if ( Auth::user()->user_role == 'customer')

                
                <!-- Dashboard Headline -->
                <div class="dashboard-headline">
                    <h3>{{$project->sub_category_name}} Bids</h3>
    
                    <!-- Breadcrumbs -->
                    <nav id="breadcrumbs" class="dark">
                        <ul>
                            <li><a href="{{route('dashboard')}}">Dashboard</a></li>
                            <li><a href="{{route('dash_projects')}}">Projects</a></li>
                            <li>Projects Bid</li>
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
                                <h3><i class="icon-material-outline-supervisor-account"></i> {{$bidders->count()}} Bidders</h3>
                                {{-- <div class="sort-by">
                                    <select class="selectpicker hide-tick">
                                        <option>Highest First</option>
                                        <option>Lowest First</option>
                                        <option>Fastest First</option>
                                    </select>
                                </div> --}}
                            </div>
                            
    
                            <div class="content">
                                <ul class="dashboard-box-list">
                                    @forelse ($bidders as $bid)
                                        <li>
                                            <!-- Overview -->
                                            <div class="freelancer-overview manage-candidates">
                                                <div class="freelancer-overview-inner">
        
                                                    <!-- Avatar -->
                                                    <div class="freelancer-avatar">
                                                        <a href="{{route ('pro_p_profile', base64_encode($bid->pro_id*786)) }}"><img src="{{ url('/'). env('PROFILE_IMAGES_PATH') .$bid->profile_photo }}" alt=""></a>
                                                    </div>
        
                                                    <!-- Name -->
                                                    <div class="freelancer-name">
                                                        <h4><a href="{{route ('pro_p_profile', base64_encode($bid->pro_id*786)) }}">{{$bid->business_name}}</a></h4>      
                                                        <!-- Bid Details -->
                                                        <ul class="dashboard-task-info bid-info">
                                                            <li><strong>Bid Amount</strong><span>₦{{number_format($bid->bid_amount)}}</span></li>
                                                            <li><strong>Pro's message</strong><span>{{$bid->bid_message}}</span></li>
                                                        </ul>
        
                                                        <!-- Buttons -->
                                                        <div class="buttons-to-right always-visible margin-top-25 margin-bottom-0">
                                                            <a href="{{route ('pro_p_profile', base64_encode($bid->pro_id*786)) }}"  class="dark button ripple-effect"><i class="icon-material-outline-check"></i> View Profile</a>
                                                            @if ($bid->bid_status == 0)
                                                            <a data-toggle="modal" data-target="#bid_dialog{{$bid->bid_id}}" href="#" class=" button  ripple-effect"><i class="icon-feather-mail"></i> Send Message</a>
                                                                @else
                                                                <a href="{{route ('cus_project_chat', base64_encode($bid->bid_id*786)) }}" class="button  ripple-effect"><i class="icon-feather-mail"></i> Continue Conversation</a>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>  
        
                                    @empty
                                        
                                    @endforelse
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Row / End -->

             

            @elseif ( Auth::user()->user_role == 'pro')

@endif
			
@section('modal_content')
    
@forelse ($bidders as $bid)

    <!-- Modal -->
        
 <div id="bid_dialog{{$bid->bid_id}}" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                    <div class="modal-header">
                            <button type="button" class="close button" data-dismiss="modal"><i class="icon-line-awesome-close"></i></button>
                        </div>
                  <!-- Welcome Text -->
                  <div class="welcome-text">
                        <h3>Direct Message To {{$bid->business_name}}</h3>
                    </div>
                        
                    <!-- Form -->
                <form method="post" action="{{route('cus_responed_bid')}}" id="send-pm">
                        @csrf
                        <textarea name="message" cols="10" placeholder="Respond to {{$bid->business_name}}'s offer" class="with-border" required></textarea>
                        <input type="hidden" name="bid_id" value="{{$bid->bid_id}}">
                        <input type="hidden" name="project_id" value="{{$bid->proj_id}}">
                        <input type="hidden" name="receiver" value="{{$bid->pro_user_id}}">
                        <input type="hidden" name="sender" value="{{Auth::user()->id}}">

                        
                        <!-- Button -->
                        <button class="button full-width  ripple-effect" type="submit" form="send-pm">Send </button>
                    </form>
                </div>
            </div>
        </div>
<!-- Modal -->

    @empty

@endforelse
@endsection

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