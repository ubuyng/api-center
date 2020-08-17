@extends('layouts.mvp_dash3')



@section('page-css')


@endsection



@section('content')

	<!-- Dashboard Headline -->

			<div class="dashboard-headline">

				<h3>Change Your Number</h3>



				<!-- Breadcrumbs -->

				<nav id="breadcrumbs" class="dark">

					<ul>

						<li><a href="{{route('dashboard')}}">Dashboard</a></li>
						<li><a href="{{route('dashboard')}}">Account</a></li>
						<li>Change Number</li>
					</ul>

				</nav>

			</div>

	

			<!-- Row -->

			<div class="row">



				<!-- Dashboard Box -->

				<div class="col-xl-8 offset-xl-2">

					<div class="dashboard-box margin-top-0">



						<!-- Headline -->

						<div class="headline">

							<h3><i class="icon-material-outline-account-circle"></i> My Number</h3>

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

							<form method="post" action="" >
								@csrf
							<div class="row">

									<div class="col-xl-8">

											<div class="submit-field">

												<h5>number </h5>

											<input type="number" name="number" class="with-border" value="{{Auth::user()->number}}">
											<mark> You will have to confirm your new mobile number before accessing your dashboard</mark>
											</div>

										</div>

								<div class="col-xl-4">

										<button type="submit" class="button ripple-effect big margin-top-30">Save Changes</button>
					
									</div>
							</div>

						</form>


						</div>

					</div>

				</div>		


					<!-- password change Box -->

			</div>

			<!-- Row / End -->





@section('page-js')   

@endsection



@endsection