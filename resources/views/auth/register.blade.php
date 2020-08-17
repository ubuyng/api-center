@extends('layouts.mvp_ui')

@section('content')

	<div class="pro_bg">
	
		<div class="container-fluid">
			<div class="row">
				<div class="col-xl-4 offset-xl-4 form_bg">

					<div class="login-register-page">
						<!-- Welcome Text -->
						<div class="welcome-text">
							<h3 style="font-size: 26px;">Let's create your account!</h3>
							<span>Already have an account? <a href="pages-login.html">Log In!</a></span>
						</div>

						<!-- Account Type -->
						<div class="account-type">

								<div>
										<input type="radio" name="account-type-radio" id="customers-radio"
											class="account-type-radio" />
										<label for="customers-radio" class="ripple-effect-dark"><i
												class="icon-material-outline-business-center"></i> Customers</label>
									</div>

							<div>
								<input type="radio" name="account-type-radio" id="freelancer-radio"
									class="account-type-radio" checked />
								<label for="freelancer-radio" class="ripple-effect-dark"><i
										class="icon-material-outline-account-circle"></i> Pros</label>
							</div>
						</div>

                         <form method="POST" action="{{ route('register') }}"  id="register-account-form">
                        @csrf
							<div class="input-with-icon-left">
								<i class="icon-material-outline-account-circle"></i>
								<input type="text" class="input-text with-border" name="full_name" id="full_name" placeholder="Enter Fullname" required/>
							</div>
                             @if ($errors->has('name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
							<div class="input-with-icon-left">
								<i class="icon-material-baseline-mail-outline"></i>
								<input type="text" class="input-text with-border" name="email" id="email" placeholder="Email Address" required/>
							</div>
		
							<div class="input-with-icon-left" title="Should be at least 8 characters long" data-tippy-placement="bottom">
								<i class="icon-material-outline-lock"></i>
								<input type="password" class="input-text with-border" name="password" id="password" placeholder="Password" required/>
							</div>
		
							<div class="input-with-icon-left">
								<i class="icon-material-outline-lock"></i>
								<input type="password" class="input-text with-border" name="password-repeat-register" id="password-repeat-register" placeholder="Repeat Password" required/>
							</div>
						<!-- Button -->
						<button class="button full-width button-sliding-icon ripple-effect margin-top-10" type="submit"
							>Register <i class="icon-material-outline-arrow-right-alt"></i></button>
							
						</form>


						<!-- Social Login -->
						<div class="social-login-separator"><span>or</span></div>
						<div class="social-login-buttons">
							<button class="facebook-login ripple-effect"><i class="icon-brand-facebook-f"></i> Register
								via Facebook</button>
							<button class="google-login ripple-effect"><i class="icon-brand-google-plus-g"></i> Register
								via Google+</button>
						</div>
					</div>

				</div>
			</div>
		</div>

		<div class="miniDivider"></div>
	</div>



{{-- <div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Register') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" required autofocus>

                                @if ($errors->has('name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required>

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> --}}
@endsection
