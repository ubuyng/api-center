@extends('layouts.mvp_dash')

@section('page-css')


    <style>
   .freelancer-avatar > a >img {
    width: 18%;
    border-radius: 50%;
}
.pro_profile_holder {
    margin: 9px;
    text-align: center;
}
    </style>
@endsection

@section('content')
 
@if ( Auth::user()->user_role == 'pro')

                
           		<!-- Dashboard Headline -->
			<div class="dashboard-headline">
                    <h3>Messages</h3>
    
                    <!-- Breadcrumbs -->
                    <nav id="breadcrumbs" class="dark">
                        <ul>
                            <li><a href="#">Home</a></li>
                            <li><a href="#">Dashboard</a></li>
                            <li>Messages</li>
                        </ul>
                    </nav>
                </div>

@elseif ( Auth::user()->user_role == 'customer')
		<!-- Dashboard Headline -->
        <div class="dashboard-headline">
				<h3>{{$project->sub_category_name}} Task</h3>

				<!-- Breadcrumbs -->
				<nav id="breadcrumbs" class="dark">
					<ul>
                    <li><a href="{{route('dashboard')}}">Dashboard</a></li>
						<li>Messages</li>
					</ul>
				</nav>
			</div>
	


            
				<div class="messages-container margin-top-0">

                        <div class="messages-container-inner">
    
                          
                            <!-- Message Content -->
                            <div class="message-content">
    
                                <div class="messages-headline">
                                    <h4>{{$pro->business_name}}</h4>
                                    <span><mark>{{$pro->pro_city}}</mark></span>                                
                                    @if ($bid != null)
                                         <a href="#" class="message-action"> Opening Bid: <mark class="color">₦{{$bid->bid_amount}} </mark> </a>
                                     @endif
                                </div>
                                
                              <!-- Message Content Inner -->
                              <div class="message-content-inner" id="scrolltoheight">


                                    <div id="chat-message"> 
                                            <span class="text-center">Loading messages...</span>

                                        </div>

                        </div>
                        <!-- Message Content Inner / End -->
                    <!-- Reply Area -->
                    
                    <form id="message-submit" action="{{URL::to('/dashboard/inbox/send-message')}}" method="post"> 
                                 
                        <input type="hidden" name="_token"id="token-message"value="{{csrf_token()}}">
                            <div class="message-reply">
                                    <textarea cols="1" rows="1" placeholder="Your Message" data-autoresize id="message" type="text"name="message"></textarea>
                                    <button  type="submit" class="button ripple-effect">Send</button>
                                </div>
                     
                     </form>
         
                               
                            </div>
                            <!-- Message Content -->
    
                              <!-- Messages -->
                              <div class="messages-inbox">
                                    <div class="messages-headline">
                                        <div class="input-with-icon">
                                            <h4 class="gray text-center"> Pro's Profile</h4>
                                            <i class="icon-feather-file-text"></i>
                                        </div>
                                    </div>

                                    <div class="pro_profile_holder">
                                        <div class="freelancer-avatar">
                                            <a href="{{route ('pro_p_profile', base64_encode($pro->id*786)) }}">
                                                <img src="{{ url('/'). env('PROFILE_IMAGES_PATH') .$pro->profile_photo }}" alt="">
                                                <h4>{{$pro->business_name}}</h4>
                                            </a>
                                           <h5 class="text-bold">
                                               <strong><a href="tel:+234{{$pro->number}}"><i class="icon-feather-phone-call"></i> {{$pro->number}} </a></strong>
                                               <strong><a href="mailto:{{$pro->email}}"><i class="icon-material-outline-email"></i> {{$pro->email}}</a></strong>
                                            </h5>

                                            <a href="{{route ('pro_p_profile', base64_encode($pro->id*786)) }}" class="button">profile</a>
                                            @if ($bid->bid_status == 2)
                                            <br>
                                            <h4><mark class="color">Pro Accepted</mark></h4>

                                            @else
                                            <a href="#small-dialog-1" class="popup-with-zoom-anim button dark button-sliding-icon">Accept bid <i class="icon-material-outline-arrow-right-alt"></i></a>
                                            @endif
                                           <br> 
                                           @if ($pro_rating == null)
                                           <a href="#small-dialog" class="popup-with-zoom-anim"> Leave a Review </a>
                                           @else
                                        <h4>You rated "{{$pro->business_name}}" {{$pro_rating->rating}} star</h4>
                                          <p>Your review:  <strong>{{$pro_rating->rate_title}}</strong></p> 
                                           @endif


                                        </div>                                  
                                      </div>
        
                                    
                                </div>
                                <!-- Messages / End -->
        
                        </div>
                </div>
                <!-- Messages Container / End -->
                <div id="small-dialog-1" class="zoom-anim-dialog mfp-hide dialog-with-tabs">

                        <!--Tabs -->
                        <div class="sign-in-form">
                    
                            <ul class="popup-tabs-nav">
                                <li><a href="#tab1">Accept Offer</a></li>
                            </ul>
                    
                            <div class="popup-tabs-container">
                    
                                <!-- Tab -->
                                <div class="popup-tab-content" id="tab">
                                    
                                    <!-- Welcome Text -->
                                    <div class="welcome-text">
                                        <h3>Accept Offer From {{$pro->business_name}}</h3>
                                        <div class="bid-acceptance margin-top-15">
                                                ₦{{$bid->bid_amount}}
                                        </div>
                    
                                    </div>
                    
                                <form id="terms" action="{{route('cus_accept_offer')}}" method="POST">
                                        @csrf
                                        <input type="hidden" name="project_name" value="{{$project->sub_category_name}}">
                                        <input type="hidden" name="cus_name" value="{{Auth::user()->first_name}} {{Auth::user()->last_name}}">
                                        <input type="hidden" name="user_id" value="{{$pro->user_id}}">
                                        <input type="hidden" name="bus_name" value="{{$pro->business_name}}">
                                        <input type="hidden" name="project_id" value="{{$project->id}}">
                                        <input type="hidden" name="bid_id" value="{{$bid->id}}">
                                        <div class="radio">
                                            <input id="radio-1" name="radio" type="radio" required>
                                            <label for="radio-1"><span class="radio-label"></span>  I have read and agree to the Terms and Conditions</label>
                                        </div>
                                    </form>
                    
                                    <!-- Button -->
                                    <button class="margin-top-15 button full-width button-sliding-icon ripple-effect" type="submit" form="terms">Accept <i class="icon-material-outline-arrow-right-alt"></i></button>
                    
                                </div>
                    
                            </div>
                        </div>
                    </div>
                    
                <div id="small-dialog" class="zoom-anim-dialog mfp-hide dialog-with-tabs">

                        <!--Tabs -->
                        <div class="sign-in-form">
                    
                            <ul class="popup-tabs-nav">
                                <li><a href="#tab">Leave a Review</a></li>
                            </ul>
                    
                            <div class="popup-tabs-container">
                    
                                <!-- Tab -->
                                <div class="popup-tab-content" id="tab">
                                    
                                    <!-- Welcome Text -->
                                    <div class="welcome-text">
                                    <h3>What is it like working with {{$pro->business_name}}?</h3>
                                        
                                    <!-- Form -->
                                    <form method="post" action="{{route('save_review')}}" id="leave-company-review-form">
                    
                                        @csrf

                                        <!-- Leave Rating -->
                                        <div class="clearfix"></div>
                                        <div class="leave-rating-container">
                                            <div class="leave-rating margin-bottom-5">
                                                <input type="radio" name="rating" id="rating-1" value="1" required>
                                                <label for="rating-1" class="icon-material-outline-star"></label>

                                                <input type="radio" name="rating" id="rating-2" value="2" required>
                                                <label for="rating-2" class="icon-material-outline-star"></label>

                                                <input type="radio" name="rating" id="rating-3" value="3" required>
                                                <label for="rating-3" class="icon-material-outline-star"></label>

                                                <input type="radio" name="rating" id="rating-4" value="4" required>
                                                <label for="rating-4" class="icon-material-outline-star"></label>

                                                <input type="radio" name="rating" id="rating-5" value="5" required>
                                                <label for="rating-5" class="icon-material-outline-star"></label>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <!-- Leave Rating / End-->
                    
                                    </div>
                    
                    
                                        <div class="row">
                                            <div class="col-xl-12">
                                                <div class="input-with-icon-left">
                                                    <i class="icon-material-outline-rate-review"></i>
                                                    <input type="text" class="input-text with-border" name="rate_title" id="reviewtitle" placeholder="Review Title"  required/>
                                                </div>
                                            </div>
                                        </div>
                                    <input type="hidden" name="project_id" value="{{$project->id}}">
                                    <input type="hidden" name="project_name" value="{{$project->sub_category_name}}">
                                    <input type="hidden" name="cus_name" value="{{Auth::user()->first_name}} {{Auth::user()->last_name}}">
                                    <input type="hidden" name="cus_id" value="{{$project->user_id}}">
                                    <input type="hidden" name="pro_id" value="{{$pro->user_id}}">
                    
                                        <textarea class="with-border" placeholder="Review" name="comment" id="message" cols="7"  required></textarea>
                    
                                        
                                        <!-- Button -->
                                        <button class="button margin-top-35 full-width button-sliding-icon ripple-effect" type="submit" form="leave-company-review-form">Leave a Review <i class="icon-material-outline-arrow-right-alt"></i></button>
                                    </form>
                                        
                                </div>
                    
                            </div>
                        </div>
                </div>
                
              
                
@endif
			

    @section('page-js') 
    
    <script>
        
            $('.bid_form').hide();
    $('#b_bid_form').click(function() {
        $('.bid_form').fadeIn(2000);
        $('.no_projects').fadeOut(1000);
            });

            $('.send_bid_btn').click(function () { 
               var bid_amount =  $('.bid_amount').val();
                console.log(bid_amount)
               var bid_message = $('.bid_message').val();
                console.log(bid_message)

                Snackbar.show({
                        text: 'Sending Bid',
                        pos: 'top-center',
                        showAction: false,
                        actionText: "Dismiss",
                        duration:3000,
                        textColor: '#fff',
                        dismiss:false,
                        backgroundColor: '#383838'
                    });
                $.post("{{route('send_bid_pro')}}",
                {
                    "_token": "{{ csrf_token() }}",
                    project_id: '{{$project->id}}',
                    cus_id: '{{$project->user_id}}',
                    user_id: '{{Auth::user()->id}}',
                    bid_amount: bid_amount,
                    bid_message: bid_message,

                },
                function(data, status){
                   
                });
		    });

    </script>
 @if ($bid != null)
<script>  
        setInterval(ajaxCall,10000); 
       
        function ajaxCall() {
           
       
           var oldscrollHeight = $("#scrolltoheight").prop("scrollHeight");
            $.ajax({
                type:'get',
                url:'{{URL::to('/dashboard/inbox/cus/chat')}}/'+{{$bid->id}},
                datatype:'html',
                success:function(response){
                       $('#chat-message').html(response);
                       var newscrollHeight = $("#scrolltoheight").prop("scrollHeight"); //Scroll height after the request
                       var newMessage=$("#chat-message li").length;
                       if(newscrollHeight > oldscrollHeight){
                           $("#scrolltoheight").animate({ scrollTop: newscrollHeight }, 'normal'); //Autoscroll to bottom of div
       
                       }
                     
                   }
                });
        }

       function deleteMessage(id){
           $('#'+id).hide();
           var sender=id;
           $.ajax({
               type:'get',
               url:'{{URL::to('/dashboard/inbox/deletemessage')}}/'+sender,
               datatype:'html'
               });
       }
        $('#message-submit').on('submit',function(e){
           $('#message').focus();
        e.preventDefault();
        var message=$('#message').val();
        var token=$('#token-message').val();
            $.ajax({
                    type:'post',
                    url:'{{URL::to('/dashboard/inbox/send-message')}}',
                    data:{
                        message:message,
                        bid_id:{{$bid->id}},
                        project_id:{{$project->id}},
                        receiver:{{$pro->user_id}},
                        sender:{{Auth::user()->id}},
                        _token:token,
                        
                    }
                   
                    });
                    document.getElementById('message-submit').reset();
                  
        });
        $('#message').keypress(function (e) {
           $('#message').focus();
        // e.preventDefault();
        if (e.which == 13) {
 
        var message=$('#message').val();
        var token=$('#token-message').val();
            $.ajax({
                    type:'post',
                    url:'{{URL::to('/dashboard/inbox/send-message')}}',
                    data:{
                        message:message,
                        bid_id:{{$bid->id}},
                        project_id:{{$project->id}},
                        receiver:{{$pro->user_id}},
                        sender:{{Auth::user()->id}},
                        _token:token,
                        
                    }
                   
                    });
                    document.getElementById('message-submit').reset();
                }
        });
       
          </script>
          @endif
    @endsection

@endsection