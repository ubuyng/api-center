@extends('layouts.mvp_dash')



@section('page-css')

<style>

.avatar-wrapper.licence {

width: 100%;

}

</style>

@endsection



@section('content')

	<!-- Dashboard Headline -->

			<div class="dashboard-headline">

				<h3>My Credentials</h3>



				<!-- Breadcrumbs -->

				<nav id="breadcrumbs" class="dark">

					<ul>

						<li><a href="{{route('dashboard')}}">Dashboard</a></li>

						<li>My Credentials</li>

					</ul>

				</nav>

			</div>

	

			<!-- Row -->

			<div class="row">

				@if ( Auth::user()->user_role == 'pro')
				<div class="col-xl-12">

					<div id="test1" class="dashboard-box">



						<!-- Headline -->

						<div class="headline">

							<h3><i class="icon-material-outline-lock"></i> Credentials</h3>

						</div>
					

						<div class="content with-padding">
							@if ($u_cred == null)
							<form action="{{route('pro_save_cre')}}" method="post" enctype="multipart/form-data">
								@csrf
							<div class="row">

								<div class="col-xl-4">

									<div class="submit-field">

										<h5>Name on Licence</h5>

										<input type="text"  class="input-text with-border" placeholder="Name on licence" required name="licence_username" >

									</div>

								</div>



								<div class="col-xl-4">
										<h5>Select Licence Type</h5>
								<div class="input-with-icon-left no-border">
									<select name="licence_type" class="selectpicker">
										<option value="National ID">National ID</option>
									<option value="Drivers Licence">Drivers Licence</option>
									<option value="Votters Card">Votters Card</option>
									<option value="International Passport">International Passport</option>
									   
										</select>
										<br>

								</div>

								</div>



								<div class="col-xl-4">

										<h5>Select State on Licence</h5>
								<div class="input-with-icon-left no-border">
									<select name="licence_state" class="selectpicker">
											@forelse ($states as $state)
											<option value="{{$state->name}}">{{$state->name}}</option>
													
												@empty
													
												@endforelse
										</select>
									
								</div>

								</div>


								<div class="col-xl-6">

									

										<div class="avatar-wrapper licence" data-tippy-placement="bottom" title="upload licence Photo">

											<img class="profile-pic" src="/mvp_ui/images/single-freelancer.png" alt="" />

										<div class="upload-button"></div>

										<input class="file-upload" name="licence_photo" type="file" accept="image/*"/>

									</div>

										<span class="help_text">click to upload a photo of your licence</span>												

									</div>
								<div class="col-xl-6">

									
										<div class="submit-field">

												<h5>Number on Licence</h5>
		
												<input type="text"  class="input-text with-border" placeholder="Number on licence" required name="licence_number"  >
		
											</div>
								</div>

							</div>
								<div class="col-xl-12">

								<button type="submit" class="button ripple-effect big margin-top-30">Save Changes</button>

							</div>
						</form>
							@else

							
						<form action="{{route('update_cred')}}" method="post" enctype="multipart/form-data">
								@csrf
							<div class="row">

								<div class="col-xl-4">

									<div class="submit-field">

										<h5>Name on Licence</h5>

										<input type="text"  class="input-text with-border" placeholder="Name on licence" required name="licence_username" value="{{ $u_cred->licence_username }}"  >

									</div>

								</div>



								<div class="col-xl-4">
										<h5>Select Licence Type</h5>
								<div class="input-with-icon-left no-border">
									<select name="licence_type" class="selectpicker">
										<option value="{{ $u_cred->licence_type }}"  selected>{{ $u_cred->licence_type }} selected</option>
										<option value="National ID">National ID</option>
									<option value="Drivers Licence">Drivers Licence</option>
									<option value="Votters Card">Votters Card</option>
									<option value="International Passport">International Passport</option>
									   
										</select>
										<br>

								</div>

								</div>



								<div class="col-xl-4">

										<h5>Select State on Licence</h5>
								<div class="input-with-icon-left no-border">
									<select name="licence_state" class="selectpicker">
										<option value="{{ $u_cred->licence_state }}" selected>{{ $u_cred->licence_state }} Selected</option>
											@forelse ($states as $state)
											<option value="{{$state->name}}">{{$state->name}}</option>
													
												@empty
													
												@endforelse
										</select>
									
								</div>

								</div>


								<div class="col-xl-6">

										@if ($u_cred->licence_photo == null)

										<div class="avatar-wrapper licence" data-tippy-placement="bottom" title="upload licence Photo">

											<img class="profile-pic" src="/mvp_ui/images/single-freelancer.png" alt="" />

										<div class="upload-button"></div>

										<input class="file-upload" name="licence_photo" type="file" accept="image/*"/>

									</div>

										<span class="help_text">click to upload a photo of your licence</span>



										@else

										<div class="avatar-wrapper licence" data-tippy-placement="bottom" title="Change licence Photo">

											<img src="/uploads/images/pro_licence/{{$u_cred->licence_photo}}" alt="" />

											<div class="upload-button"></div>

										<input class="file-upload" name="licence_photo" type="file" accept="image/*"/>

									</div>

										<span class="help_text">click to upload a photo of your licence</span>



										@endif

												

									</div>
								<div class="col-xl-6">

									
										<div class="submit-field">

												<h5>Number on Licence</h5>
		
												<input type="text"  class="input-text with-border" placeholder="Number on licence" required name="licence_number" value="{{ $u_cred->licence_number }}"  >
		
											</div>
								</div>

							</div>
								<div class="col-xl-12">

								<button type="submit" class="button ripple-effect big margin-top-30">Save Changes</button>

							</div>
						</form>
						@endif
						</div>

					</div>

				</div>
				@endif
			</div>

			<!-- Row / End -->





@section('page-js')   

@endsection



@endsection