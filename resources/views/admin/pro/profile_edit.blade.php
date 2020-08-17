@extends('layouts.mvp_dash')

@section('page-css')
<style>
.avatar-wrapper {
    width: 100%;
}
</style>
@endsection

@section('content')
	<!-- Dashboard Headline -->
			<div class="dashboard-headline">
				<h3>My Profile</h3>

				<!-- Breadcrumbs -->
				<nav id="breadcrumbs" class="dark">
					<ul>
						<li><a href="{{route('dashboard')}}">Dashboard</a></li>
						<li>My Profile</li>
					</ul>
				</nav>
			</div>
	
			<!-- Row -->
			<div class="row">

					<form action="{{route('update_pro_profile')}}" method="post" enctype="multipart/form-data">
							
						@csrf
				<!-- Dashboard Box -->
				<div class="col-xl-12">
					<div class="dashboard-box">
						
						<!-- Headline -->
						<div class="headline">
							<h3><i class="icon-material-outline-face"></i> My Profile</h3>
						</div>

						<div class="content">
							
							<ul class="fields-ul">
							<li>
								<div class="row">
										@if ($errors->any())
										<div class="notification error closeable">
											<ul>
												@foreach ($errors->all() as $error)
													<li>{{ $error }}</li>
												@endforeach
											</ul>
											<a class="close" href="#"></a>
										</div>
										
									@endif
			
										<div class="col-xl-6">
											<div class="submit-field">
												<h5>Business Name</h5>
											<input type="text" name="business_name" placeholder="Business / brand name" class="with-border" value="{{$profile->business_name}}">
											</div>
										</div>
										

										<div class="col-xl-6">
											@if ($profile->cover_photo == null)
											<div class="avatar-wrapper" data-tippy-placement="bottom" title="Change Cover Photo">
												<img class="profile-pic" src="/mvp_ui/images/single-freelancer.png" alt="" />
											<div class="upload-button"></div>
											<input class="file-upload" name="thumbnail" type="file" accept="image/*"/>
										</div>
											<span class="help_text">click to upload a cover photo for your profile</span>

											@else
											<div class="avatar-wrapper" data-tippy-placement="bottom" title="Change Cover Photo">
												<img src="{{ url('/'). env('COVER_IMAGES_PATH').$profile->cover_photo}}" alt="" />
												<div class="upload-button"></div>
											<input class="file-upload" name="thumbnail" type="file" accept="image/*"/>
										</div>
											<span class="help_text">click to upload a cover photo for your profile</span>

											@endif
													
										</div>
									

										
							</li>
							<li>
								<div class="row">
									<div class="col-xl-4">
										<div class="submit-field">
											<h5>Skills <i class="help-icon" data-tippy-placement="right" title="Add up to 10 skills"></i></h5>

											<!-- Skills List -->
											<div class="keywords-container skills_con">
												<div class="keyword-input-container">
													<input type="text" class="keyword-input with-border" placeholder="e.g. Angular, Plumber"/>
													<button class="keyword-input-button ripple-effect"><i class="icon-material-outline-add"></i></button>
												</div>
												<div class="keywords-list">
													@forelse ($skills as $skill)
													@if ($skill->skill_type == 'skills')
														<span class="keyword"><span class="keyword-remove"></span><span class="keyword-text">{{$skill->skill_title}}</span></span>
													@endif
													@empty
														<Span>Tell customers what you are skilled at</Span>
													@endforelse
												</div>
												<div class="clearfix"></div>
											</div>
										</div>
									</div>

									<div class="col-xl-4">
											<div class="submit-field">
												<h5>Language <i class="help-icon" data-tippy-placement="right" title="Let customers know your language"></i></h5>
	
												<!-- Skills List -->
												<div class="keywords-container lang_con">
													<div class="keyword-input-container">
														<input type="text" class="keyword-input with-border" placeholder="e.g. English, Hausa, Igbo, Yoruba e.t.c"/>
														<button class="keyword-input-button ripple-effect"><i class="icon-material-outline-add"></i></button>
													</div>
													<div class="keywords-list">
															@forelse ($skills as $language)
															@if ($language->skill_type == 'language')
															<span class="keyword"><span class="keyword-remove"></span><span class="keyword-text">{{$language->skill_title}}</span></span>
																
															@endif
															@empty
															<Span>No Language Added</Span>

															@endforelse
													</div>
													<div class="clearfix"></div>
												</div>
											</div>
										</div>

										<div class="col-xl-4">
												<div class="submit-field">
													<h5>Education <i class="help-icon" data-tippy-placement="right" title="Add your education background"></i></h5>
		
													<!-- Skills List -->
													<div class="keywords-container edu_con">
														<div class="keyword-input-container">
															<input type="text" class="keyword-input with-border" placeholder="e.g. Bsc in Mass Communication"/>
															<button class="keyword-input-button ripple-effect"><i class="icon-material-outline-add"></i></button>
														</div>
														<div class="keywords-list">
																@forelse ($skills as $education)
																@if ($education->skill_type == 'education')
																<span class="keyword"><span class="keyword-remove"></span><span class="keyword-text">{{$education->skill_title}}</span></span>																	
																@endif
																@empty
																	<span>Add your education background</span>
																@endforelse
															</div>
														<div class="clearfix"></div>
													</div>
												</div>
											</div>
									</div>
							</li>
							<li>
									<div class="row">

									<div class="col-xl-2">
										<div class="submit-field">
											<h5>Number of staffs</h5>
											<input type="number" class="with-border" name="number_of_empolyees" value="{{$profile->number_of_empolyees}}">
										</div>
									</div>
									<div class="col-xl-6">
											<div class="submit-field">
												<h5>Year Founded</h5>
											<input type="text" class="with-border" placeholder="Year Founded" name="founded_year" value="{{$profile->founded_year}}">
											</div>
										</div>

									<div class="col-xl-12">
										<div class="submit-field">
											<h5>Introduce Yourself</h5>
											<textarea cols="30" rows="5" name="about_profile" class="with-border">{{$profile->about_profile}}</textarea>
										</div>
									</div>
								</div>

							</li>
						</ul>
						</div>
					</div>
				</div>

				<!-- Dashboard Box -->
				<div class="col-xl-12">
					<div id="test1" class="dashboard-box">

						<!-- Headline -->
						<div class="headline">
							<h3><i class="icon-material-outline-lock"></i>Website & social accounts</h3>
						</div>

						<div class="content with-padding">
							<div class="row">
								<div class="col-xl-4">
									<div class="submit-field">
										<h5>Website</h5>
										<input type="text" name="website" value="{{$profile->website}}" placeholder="website url" class="with-border">
									</div>
								</div>

								<div class="col-xl-4">
									<div class="submit-field">
										<h5>Facebook</h5>
										<input type="text" value="{{$profile->facebook_url}}" name="facebook_url" placeholder="Facebook url" class="with-border">
									</div>
								</div>

								<div class="col-xl-4">
									<div class="submit-field">
										<h5>Twitter</h5>
										<input type="text"  value="{{$profile->twitter_url}}" name="twitter_url" placeholder="Twitter url" class="with-border">
									</div>
								</div>
								<div class="col-xl-4">
									<div class="submit-field">
										<h5>Instagram</h5>
										<input type="text"  value="{{$profile->instagram_url}}" name="instagram_url"  placeholder="Instagram url" class="with-border">
									</div>
								</div>

								<div class="col-xl-4">
									<div class="submit-field">
										<h5>Linkedin</h5>
										<input type="text" value="{{$profile->linkedin_url}}" name="linkedin_url" placeholder="Linkedin Url" class="with-border">
									</div>
								</div>
			
							</div>
						</div>
					</div>
				</div>
				
				<!-- Button -->
				<div class="col-xl-12">
					<button class="button ripple-effect big margin-top-30">Save Changes</button>
				</div>
					</form>
			</div>
			<!-- Row / End -->


@section('page-js')   
<script>
	

$(".skills_con").each(function() {
	var keywordInput = $(this).find(".skills_input");

	function addKeyword() {
		$.post("{{route('pro_save_skill')}}",
		{
			"_token": "{{ csrf_token() }}",
			user_id: "{{Auth::user()->id}}",
			skill_title: keywordInput.val(),
			skill_type:'skills',
		},
		function(data, status){
			Snackbar.show({
				text: 'Skill saved',
				pos: 'top-center',
				showAction: false,
				actionText: "Dismiss",
				duration:2000,
				textColor: '#fff',
				dismiss:false,
				backgroundColor: '#383838'
			}); 
		});
		}
	keywordInput.on('keyup', function(e) {
		if ((e.keyCode == 13) && (keywordInput.val() !== "")) {
			addKeyword();
		}
	});
	$('.skills_input_container').on('click', function() {
		if ((keywordInput.val() !== "")) {
			addKeyword();
		}
	});
	$(document).on("click", ".keyword-remove", function() {
		$(this).parent().addClass('keyword-removed');
		var keyworddelete = $(this).parent().find(".keyword-text");

		$.post("{{route('skill_destroy')}}",
		{
			"_token": "{{ csrf_token() }}",
			user_id: "{{Auth::user()->id}}",
			skill_title: keyworddelete.text(),
		},
		function(data, status){
		   console.log('item removed');
		});
		function removeFromMarkup() {
			$(".keyword-removed").remove();

		}
		setTimeout(removeFromMarkup, 500);
		keywordsList.css({
			'height': 'auto'
		}).height();
	});
  
});

{{-- language --}}
$(".lang_con").each(function() {
	var keywordInput = $(this).find(".lang_input");

	function addKeyword() {
		$.post("{{route('pro_save_skill')}}",
		{
			"_token": "{{ csrf_token() }}",
			user_id: "{{Auth::user()->id}}",
			skill_title: keywordInput.val(),
			skill_type:'language',
		},
		function(data, status){
			Snackbar.show({
				text: 'Language saved',
				pos: 'top-center',
				showAction: false,
				actionText: "Dismiss",
				duration: 1000,
				textColor: '#fff',
				dismiss:false,
				backgroundColor: '#383838'
			}); 
		});
		}
	keywordInput.on('keyup', function(e) {
		if ((e.keyCode == 13) && (keywordInput.val() !== "")) {
			addKeyword();
		}
	});
	$('.lang_input_container').on('click', function() {
		if ((keywordInput.val() !== "")) {
			addKeyword();
		}
	});
	$(document).on("click", ".keyword-remove", function() {
		$(this).parent().addClass('keyword-removed');
		var keyworddelete = $(this).parent().find(".keyword-text");

		$.post("{{route('skill_destroy')}}",
		{
			"_token": "{{ csrf_token() }}",
			user_id: "{{Auth::user()->id}}",
			skill_title: keyworddelete.text(),
		},
		function(data, status){
			console.log('item removed');
		});
		function removeFromMarkup() {
			$(".keyword-removed").remove();

		}
		setTimeout(removeFromMarkup, 500);
		keywordsList.css({
			'height': 'auto'
		}).height();
	});
  
});
{{-- education --}}
$(".edu_con").each(function() {
	var keywordInput = $(this).find(".edu_input");

	function addKeyword() {
		$.post("{{route('pro_save_skill')}}",
		{
			"_token": "{{ csrf_token() }}",
			user_id: "{{Auth::user()->id}}",
			skill_title: keywordInput.val(),
			skill_type:'education',
		},
		function(data, status){
			Snackbar.show({
				text: 'Record saved',
				pos: 'top-center',
				showAction: false,
				actionText: "Dismiss",
				duration: 1000,
				textColor: '#fff',
				dismiss:false,
				backgroundColor: '#383838'
			}); 
		});
		}
	keywordInput.on('keyup', function(e) {
		if ((e.keyCode == 13) && (keywordInput.val() !== "")) {
			addKeyword();
		}
	});
	$('.edu_input_container').on('click', function() {
		if ((keywordInput.val() !== "")) {
			addKeyword();
		}
	});
	$(document).on("click", ".keyword-remove", function() {
		$(this).parent().addClass('keyword-removed');
		var keyworddelete = $(this).parent().find(".keyword-text");

		$.post("{{route('skill_destroy')}}",
		{
			"_token": "{{ csrf_token() }}",
			user_id: "{{Auth::user()->id}}",
			skill_title: keyworddelete.text(),
		},
		function(data, status){
			console.log('item removed');
		});
		function removeFromMarkup() {
			$(".keyword-removed").remove();

		}
		setTimeout(removeFromMarkup, 500);
		keywordsList.css({
			'height': 'auto'
		}).height();
	});
  
});

</script>
@endsection

@endsection