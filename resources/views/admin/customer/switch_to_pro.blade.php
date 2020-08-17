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
 
@if ( Auth::user()->user_role == 'customer')

                
               
                <!-- Row -->
                <div class="row">
    
                    <!-- Dashboard Box -->
                    <div class="col-xl-12">

                        <!-- no projects -->
                        <div class="switch_holder">
                        <form action="{{route('post_to_pro')}}" method="post">
                            @csrf
                                <img src="/mvp_ui/images/icons/htw/htw_pro1.svg" alt="">
                                <h3>Thousands of tasks at your finger tips</h3>
                                <p>Create a free professional profile now and strat getting offers
                                    <br>
                                    <button  class="button button-sliding-icon ripple-effect big margin-top-20"  type="submit">Switch to Pro <i class="icon-feather-user"></i></button>
                                    <br>
                                    <mark class="color"> you can always switch back to your customer account</mark>
                                </p>
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