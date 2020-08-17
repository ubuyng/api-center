@extends('layouts.mvp_ui')

@section('content')

@section('page-css')



  <style>
.col-xl-8>h2{
    text-align: center;
}
  </style>
@endsection
<div class="container margin-top-65 margin-bottom-25">
	<!-- Row -->
    <div class="row">
            <!-- Dashboard Box --> 
            <div class="col-xl-8 offset-xl-2">
                <h2 class="margin-bottom-10"><strong>Connect your Facebook account to Ubuy</strong></h2>
                <form method="POST"action="" id="register-account-form">
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


                                    @csrf
                <div class="input-with-icon-left">
                    <i class="icon-material-outline-account-circle"></i>
                    <input type="text" class="input-text with-border" name="first_name" id="first_name" placeholder="First name" 
                    @if (isset($_GET['first_name']))
                   value="{{$_GET['first_name']}}"
                   @endif

                      required/>
                </div>
                 @if ($errors->has('first_name'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('first_name') }}</strong>
                        </span>
                    @endif
                <div class="input-with-icon-left">
                    <i class="icon-material-outline-account-circle"></i>
                    <input type="text" class="input-text with-border" name="last_name" id="last_name" placeholder="Last name"
                    @if (isset($_GET['last_name']))
                    value="{{$_GET['last_name']}}"
                    @endif
                      required/>
                </div>
                 @if ($errors->has('last_name'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('last_name') }}</strong>
                        </span>
                    @endif
                <div class="input-with-icon-left">
                    <i class="icon-material-baseline-mail-outline"></i>
                    <input type="text" class="input-text with-border" name="email" id="email" placeholder="Email Address" 
                    @if (isset($_GET['email']))
                    value="{{$_GET['email']}}"
                    @endif
                    required/>
                </div>
                @if ($errors->has('email'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
            @endif
            <div class="input-with-icon-left">
                    <i class="icon-material-baseline-mail-outline"></i>
                    <input type="text" class="input-text with-border" name="number" id="phone_number" placeholder="Phone number: Do not enter +234" value="{{ old('phone_number') }}" required/>
                </div>
                @if ($errors->has('phone_number'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('phone_number') }}</strong>
                </span>
            @endif
                <div class="input-with-icon-left" title="Should be at least 6 characters long" data-tippy-placement="bottom">
                    <i class="icon-material-outline-lock"></i>
                    <input type="password" class="input-text with-border" name="password" id="password" placeholder="Password" required/>
                </div>
                @if ($errors->has('password'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('password') }}</strong>
                </span>
            @endif
                <div class="input-with-icon-left">
                    <i class="icon-material-outline-lock"></i>
                    <input type="password" class="input-text with-border" name="password_confirmation" id="password-repeat-register" placeholder="Repeat Password" required/>
                </div>
                @if ($errors->has('password_confirmation'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('password_confirmation') }}</strong>
                        </span>
                    @endif

                    <div class="checkbox">
                        <input type="checkbox" id="terms" name="accept_terms" value="1">
                        <label for="terms"><span class="checkbox-icon"></span> Accept ubuy terms & conditions</label>
                    </div>     
                    @if ($errors->has('terms'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('accept_terms') }}</strong>
                    </span>
                @endif
                    <!-- Button -->
            <button class="button full-width button-sliding-icon ripple-effect margin-top-10" type="submit"
                >Register <i class="icon-material-outline-arrow-right-alt"></i></button>
                
            </form>
            </div>
            </div>

        </div>
        <!-- Row / End -->
  

    {{-- <div class="page_help">
        <button disabled="disabled">Help</button>
    </div> --}}
    @section('page-js')

    @endsection

        @endsection
