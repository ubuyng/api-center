@extends('layouts.mvp_dash2')

@section('content')

@section('page-css')
@endsection

<h3 class="page-title">Search Results</h3>

<div class="notify-box margin-top-15">
	<div class="switch-container">
		@if ($distance > 20)
		<span class="switch-text">Showing  tasks within {{$distance}} KM</span>
			
		@else
		<span class="switch-text">Showing  tasks within {{$city}}</span>
		@endif
	</div>

</div>

<!-- Tasks Container -->
<div class="tasks-list-container tasks-grid-layout margin-top-35">
	@forelse ($available_request as $item)
	@if ($item->user_id == Auth::User()->id)
	<a href="{{route('project_chat',base64_encode($item->id*786))}}" class="task-listing">

			<!-- Job Listing Details -->
			<div class="task-listing-details">
	
				<!-- Details -->
				<div class="task-listing-description">
					<h3 class="task-listing-title">{{$item->sub_category_name}}</h3>
					<ul class="task-icons">
						<li><i class="icon-material-outline-location-on"></i> {{$item->city}} - {{$item->state}}</li>
					</ul>
					<p class="task-listing-text">{{$item->project_message}}</p>

				</div>
	
			</div>
			<div class="task-listing-bid">
				<div class="task-listing-bid-inner">
					<div class="task-offers">
							<span><i class="icon-material-outline-access-time"></i>
								{{date('H-i', strtotime($item->created_at)) }}
							</span>
							<span><i class=" icon-material-outline-date-range"></i>
								{{date('d-m-Y', strtotime($item->created_at)) }}
							</span>
					</div>
					<span class="button button-sliding-icon ripple-effect">Bid Now <i class="icon-material-outline-arrow-right-alt"></i></span>
				</div>
			</div>
		</a>
	@endif
		@empty
		
	<div class="col-xl-12">

					<!-- no projects -->
			<div class="no_projects">
			<img src="/mvp_ui/images/icons/no_request.svg" alt="">
			<h3>No Request Found</h3>
			<p>Add services you offer and start getting thousands of request around you</p>
		<a href="{{route('add_services')}}" class="button dark ripple-effect">Add Services</a>	
		</div> 

			</div>


	@endforelse
	

</div>
<!-- Tasks Container / End -->
<div id="small-dialog" class="zoom-anim-dialog mfp-hide dialog-with-tabs">

		<!--Tabs -->
		<div class="sign-in-form">
	
			<ul class="popup-tabs-nav">
				<li><a href="#tab">Change Distance</a></li>
			</ul>
	
			<div class="popup-tabs-container">
	
				<!-- Tab -->
				<div class="popup-tab-content" id="tab">
					
					<!-- Welcome Text -->
					<div class="welcome-text">
						<h3>Set your default distance</h3>
					</div>
						
					<!-- Form -->
				<form method="post" action="{{route('update_pro_distance')}}" >
								@csrf
							<input name="distance" class="range-slider-single" type="text" data-slider-min="1" data-slider-max="1050" data-slider-step="10" data-slider-value="{{$distance}}"/>
	
						<div class="uploadButton margin-top-25">
						<span class="uploadButton-file-name">We would display requests within your selected distance</span>
						</div>
	
						
						<!-- Button -->
						<button class="button margin-top-35 full-width button-sliding-icon ripple-effect" type="submit">Update Distance<i class="icon-material-outline-arrow-right-alt"></i></button>
					</form>
	
				</div>
				
			</div>
		</div>
	</div>

<!-- Pagination -->
<div class="clearfix"></div>
{{-- <div class="pagination-container margin-top-20 margin-bottom-20">
	<nav class="pagination">
		<ul>
			<li class="pagination-arrow"><a href="#" class="ripple-effect"><i class="icon-material-outline-keyboard-arrow-left"></i></a></li>
			<li><a href="#" class="ripple-effect current-page">1</a></li>
			<li><a href="#" class="ripple-effect ">2</a></li>
			<li><a href="#" class="ripple-effect">3</a></li>
			<li><a href="#" class="ripple-effect">4</a></li>
			<li class="pagination-arrow"><a href="#" class="ripple-effect"><i class="icon-material-outline-keyboard-arrow-right"></i></a></li>
		</ul>
	</nav>
</div> --}}
<div class="clearfix"></div>
<!-- Pagination / End -->
@section('page-js')
        
<!-- Snackbar // documentation: https://www.polonel.com/snackbar/ -->
<script>
    // Snackbar for user status switcher
    $('#snackbar-user-status label').click(function() { 
        Snackbar.show({
            text: 'Your status has been changed!',
            pos: 'bottom-center',
            showAction: false,
            actionText: "Dismiss",
            duration: 3000,
            textColor: '#fff',
            backgroundColor: '#383838'
        }); 
    }); 
    </script>
    
    
    @endsection

@endsection
