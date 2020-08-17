@extends('layouts.mvp_dash3')

@section('page-css')
    <style>
    .switch_holder{
        text-align: center;
    }
    .switch_holder img {
    width: 20%;
    }
 
    </style>
@endsection

@section('content')
 
@if ( Auth::user()->user_role == 'pro')

                
               
                <!-- Row -->
                <div class="row">
    
                    <!-- Dashboard Box -->
                    <div class="col-xl-12">

                        <!-- no projects -->
                        <div class="switch_holder">
                        <form action="{{route('post_to_cus')}}" method="post">
                            @csrf
                                <img src="/mvp_ui/images/icons/htw/htw_customer1.svg" alt="">
                                <h3>Finding local professionals has never been easier.
                                    </h3>
                            
                                    <button  class="button button-sliding-icon ripple-effect big margin-top-20" type="submit">Switch to Customer <i class="icon-feather-user"></i></button>
                                    <br>
                                    <mark class="color"> you can always switch back to your Pro account</mark>
                               
                            </form>
                        </div>

                    </div>
    
                </div>
                <!-- Row / End -->
    
    
    
@elseif ( Auth::user()->user_role == 'pros')

@endif
			

    @section('page-js')   
    @endsection

@endsection