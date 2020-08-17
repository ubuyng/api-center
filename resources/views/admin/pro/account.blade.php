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

				<h3>My Account</h3>



				<!-- Breadcrumbs -->

				<nav id="breadcrumbs" class="dark">

					<ul>

						<li><a href="{{route('dashboard')}}">Dashboard</a></li>

						<li>My Account</li>

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

							<h3><i class="icon-material-outline-account-circle"></i> My Account</h3>

						</div>



						<div class="content with-padding padding-bottom-0">
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

							<form method="post" action="{{route('update_user')}}" enctype="multipart/form-data">
								@csrf
							<div class="row">

								<div class="col-auto">

									<div class="avatar-wrapper" data-tippy-placement="bottom" title="Change Avatar">

                                        @if (Auth::user()->image == null)

                                        <img class="profile-pic" src="images/user-avatar-placeholder.png" alt="" />
										<div class="upload-button"></div>

										<input class="file-upload" name="image" type="file" accept="image/*"/>
                                        @else

                                        <img class="profile-pic" src="{{ url('/'). env('PROFILE_IMAGES_PATH').Auth::user()->image}}" alt="" />
										<div class="upload-button"></div>

										<input class="file-upload" name="image" type="file" accept="image/*"/>
                                        @endif

										

									</div>

								</div>



								<div class="col">

									<div class="row">



										<div class="col-xl-6">

											<div class="submit-field">

												<h5>First Name</h5>

                                            <input type="text" name="first_name" class="with-border" value="{{Auth::user()->first_name}}">

											</div>

										</div>



										<div class="col-xl-6">

											<div class="submit-field">

												<h5>Last Name</h5>

												<input type="text" name="last_name" class="with-border" value="{{Auth::user()->last_name}}">

											</div>

										</div>



										<div class="col-xl-6">

											<!-- Account Type -->

											<div class="submit-field">

												<h5>Account Type</h5>

												<div class="account-type">

													@if ( Auth::user()->user_role == 'pro')

													<div>

														<input type="radio" disabled name="account-type-radio" id="freelancer-radio" class="account-type-radio" checked/>

														<label for="freelancer-radio" class="ripple-effect-dark"><i class="icon-material-outline-account-circle"></i> Pro</label>

                                                    <span class="help-text"></span>

                                                    </div>

														@elseif( Auth::user()->user_role == 'customer')

														<div>

																<input type="radio" disabled name="account-type-radio" id="freelancer-radio" class="account-type-radio" checked/>

																<label for="freelancer-radio" class="ripple-effect-dark"><i class="icon-material-outline-account-circle"></i> Customer</label>

															<span class="help-text"></span>

															</div>

													@endif



													<div>

														<a href="#" class="button gray">Change Account</a>
														
													</div>

												</div>

											</div>

										</div>



										<div class="col-xl-6">

											<div class="submit-field">

												<h5>Email</h5>

												<input name="email" disabled type="text" class="with-border" placeholder="{{Auth::user()->email}}">
												<a href="{{route('edit_email')}}"><mark class="color">Change Your email here</mark></a>

											</div>
											<div class="submit-field">

												<h5>Number</h5>

												<input name="number" disabled type="text" class="with-border" placeholder="{{Auth::user()->number}}">
												<a href="{{route('edit_number')}}"><mark class="color">Change Your number here</mark></a>
											</div>

										</div>
 


									</div>

								</div>

								<div class="col-xl-12">
									<div class="row">
										<div class="col-xl-5">

										
										<div class="account-type">
											<div>

												<button type="submit" class="button ripple-effect big margin-top-30">Save Changes</button>
											</div>
											<div>
												<a href="{{route('edit_credentials')}}" class="button ripple-effectd dark big margin-top-30">Credentials/Licence</a>
											</div>
										</div>
									</div>
										</div>
									</div>
							</div>

						</form>


						</div>

					</div>

				</div>





				{{-- <!-- password change Box -->

				<div class="col-xl-12">

					<div id="test1" class="dashboard-box">



						<!-- Headline -->

						<div class="headline">

							<h3><i class="icon-material-outline-lock"></i> Password & Security</h3>

						</div>



						<div class="content with-padding">

							<div class="row">

								<div class="col-xl-4">

									<div class="submit-field">

										<h5>Current Password</h5>

										<input type="password" class="with-border">

									</div>

								</div>



								<div class="col-xl-4">

									<div class="submit-field">

										<h5>New Password</h5>

										<input type="password" class="with-border">

									</div>

								</div>



								<div class="col-xl-4">

									<div class="submit-field">

										<h5>Repeat New Password</h5>

										<input type="password" class="with-border">

									</div>

								</div>



								<div class="col-xl-12">

									<div class="checkbox">

										<input type="checkbox" id="two-step" checked>

										<label for="two-step"><span class="checkbox-icon"></span> Enable Two-Step Verification via Email</label>

									</div>

								</div>

							</div>

						</div>

					</div>

				</div> --}}

				

				<!-- Button -->

			
			</div>

			<!-- Row / End -->





@section('page-js')   

@endsection



@endsection