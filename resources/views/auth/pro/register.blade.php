@extends('layouts.mvp_ui')

@section('content')

@section('page-css')
  <style>
    .pro-reg-bg{
        margin-top: 5%;
    }
    .dashboard-box .headline h3 {
    font-size: 20px;
}
.invalid-feedback strong {
    color: #E91E63;
    font-size: 12px;
    margin-top: 14px;
}
input.with-border {
    margin-bottom: 0px;
}
  </style>
@endsection

<div class="pro_bg">
    <div class="container-fluid">
            <div class="col-xl-5 offset-xl-6 pro-reg-bg ">
					<div class="dashboard-box margin-top-0">

						<!-- Headline -->
						<div class="headline">
                            <h3> Meet more customers, Increase your Earnings</h3>
                            <p>Create a free ubuy account</p>
						</div>

						<div class="content with-padding padding-bottom-10">
                            <form action="" method="post">
                                @csrf
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
                        @if(Session::has('cou_code'))
                        <div class="notification error closeable">
                                <ul>
                                        <li>{{ Session::get('cou_code') }} </li>
                                </ul>
                                <a class="close" href="#"></a>
                            </div>
                            @endif
                            <div class="col-xl-6">
									<div class="submit-field">

                                        <input type="text"
                                        @if(isset($_GET['first_name']))
                                        value="{{ $_GET['first_name']}}"
                                        @else 
                                        value="{{ old('first_name') }}"
                                        @endif
                                          name="first_name" placeholder="First Name" class="with-border">
                                        @if ($errors->has('first_name'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('first_name') }}</strong>
                                        </span>
                                         @endif
                                    </div>
                                  
								</div>
								<div class="col-xl-6">
									<div class="submit-field">
                                        <input type="text"
                                        @if(isset($_GET['last_name']))
                                        value="{{ $_GET['last_name']}}"
                                        @else 
                                        value="{{ old('last_name') }}"
                                        @endif
                                           name="last_name" placeholder="last Name" class="with-border">
                                        @if ($errors->has('last_name'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('last_name') }}</strong>
                                        </span>
                                         @endif
                                    </div>
								</div>
								<div class="col-xl-6">
									<div class="submit-field">
										<input type="text"  @if(isset($_GET['email']))
                                        value="{{ $_GET['email']}}"
                                        @else 
                                        value="{{ old('email') }}"
                                        @endif    type="email" name="email" placeholder="Valid Email Address" class="with-border">
                                        @if ($errors->has('email'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                         @endif
                                    </div>
								</div>
								<div class="col-xl-6">
									<div class="submit-field">
										<input type="text"   @if(isset($_GET['phone_number']))
                                        value="{{ $_GET['phone_number']}}"
                                        @else 
                                        value="{{ old('number') }}"
                                        @endif   name="number" placeholder="Valid Phone Number" class="with-border">
                                        @if ($errors->has('number'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('number') }}</strong>
                                        </span>
                                         @endif
                                        
									</div>
								</div>
								<div class="col-xl-6">
									<div class="submit-field">
										<input type="password" type="password" name="password" placeholder="Password" class="with-border">
                                        @if ($errors->has('password'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                         @endif
                                    </div>
								</div>
								<div class="col-xl-6">
									<div class="submit-field">
										<input type="password" type="password" name="password_confirmation" placeholder="Confirm Password" class="with-border">
                                        @if ($errors->has('password_confirmation'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('password_confirmation') }}</strong>
                                        </span>
                                         @endif
                                    </div>
                                </div>
                                
                                <div class="checkbox">
                                        <input type="checkbox" id="terms" name="accept_terms" value="1">
                                        <label for="terms"><span class="checkbox-icon"></span> Accept ubuy terms & conditions</label>
                                        @if ($errors->has('terms'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('accept_terms') }}</strong>
                                        </span>
                                    @endif
                                    </div>     

                                <button class="button full-width button-sliding-icon ripple-effect margin-top-10" type="submit"
                                >Join Ubuy <i class="icon-material-outline-arrow-right-alt"></i></button>
                                <p>By signing up, I agress to Ubuy's <a href="{{route('terms_of_use')}}">Terms of Use</a>, <a href="{{route('privacy_policy')}}">Privacy Policy</a> 
                                and <a href="{{route('pro_guide')}}">Community Guidelines</a>.</p>
							</div>
                        </form> 
						</div>
					</div>
				</div>
   
    </div>

    <div class="miniDivider"></div>
</div>


@endsection
