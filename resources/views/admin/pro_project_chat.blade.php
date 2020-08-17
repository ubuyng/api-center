@extends('layouts.mvp_dash')

@section('page-css')


    <style>
    a.file-button {
    background-color: #333;
    padding: 11px;
    border-radius: 50%;
    width: 50px;
    height: 50px;
    text-align: center;
    color: #fff;
    box-shadow: 0 4px 12px rgba(0, 0, 0, .1);
    font-size: 25px;
    margin-left: 5px;
}
    </style>
@endsection

@section('content')
 
@if ( Auth::user()->user_role == 'customer')

                
           		<!-- Dashboard Headline -->
			<div class="dashboard-headline">
                    <h3>Messages</h3>
    
                    <!-- Breadcrumbs -->
                    <nav id="breadcrumbs" class="dark">
                        <ul>
                            <li><a href="{{route('dashboard')}}">Dashboard</a></li>
                            <li>Messages</li>
                        </ul>
                    </nav>
                </div>

@elseif ( Auth::user()->user_role == 'pro')
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
                                    <h4>{{$customer->first_name}} {{$customer->last_name}}</h4>
                                    <span><mark>{{$project->city}}</mark></span>                                
                                    @if ($bid != null)
                                         <a href="#" class="message-action"> Opening Bid: <mark class="color">{{$bid->bid_amount}} </mark> </a>
                                     @endif
                                </div>
                                
                                @if ($project->status >= 2 && $project->pro_id != Auth::User()->id)
                                              <!-- Message Content Inner -->
                                              <div class="message-content-inner">
                                                
                                                <div class="no_projects">
                                                <h4>This project has been assigned to a pro</h4>
                                                    
                                                   
                                                </div> 

                                        </div>
                                @else
                                    {{-- bid --}}
                                @if ($bid == null)
                                <!-- Message Content Inner -->
                                <div class="message-content-inner">
                                        
                                        <div class="no_projects no_bid">
                                                <img src="/mvp_ui/images/icons/add_bid.svg" alt="">
                                            <h4>Tell the user how much you are willing to charge to complete this task.</h4>
                                            <p><mark class="color">     
                                                Your opening bid will be sent to the customer
                                            </mark>

                                            </p>
                                            <button class="button dark" id="b_bid_form">Place a Bid</button>
                                        </div> 

                                        <div class="bid_form">
                                            <div class="row">
                                         
                                                    <div class="col-xl-12">
                                                            <div class="submit-field">
                                                                <h5>Amount</h5>
                                                            <input type="number" name="bid_amount" class="with-border bid_amount" placeholder="How much do your charge for {{$project->sub_category_name}} services">
                                                            </div>
                                                        </div>
                                                    <div class="col-xl-12">
                                                            <div class="submit-field">
                                                                <h5>Bid Message</h5>
                                                                <textarea cols="30" name="bid_message" rows="5" class="with-border bid_message" placeholder="Start by telling the customer what makes you unique"></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="col-xl-12">

                                                        <button class="button send_bid_btn dark ladda-button" data-style="expand-right">Send Bid</button> <br>
                                                        <p>
                                                            <mark class="color">     
                                                                Sending of bids is 100% free. 
                                                            </mark>
                                                        </p>

                                                        </div>

                                            </div>
                                        </div>
                                    <div class="bid_sent no_projects" style="display:none;">
                                            <img src="/mvp_ui/images/icons/waiting_bid_accept.svg" alt="">
                                        <h4>Bid has been sent</h4>
                                        <p>  
                                            Customers will review your profile and will only accept your bids if you standout.      
                                        </p>
                                        <a href="#" class="button gray" >Edit profile</a>
                                        <a href="#" class="button dark" >View my profile</a>
                                    </div> 
                                </div>

                                @else
                                
                                    @if ($bid->bid_status == 0)
                                                <!-- Message Content Inner -->
                                        <div class="message-content-inner">
                                                
                                                <div class="no_projects">
                                                        <img src="/mvp_ui/images/icons/waiting_bid_accept.svg" alt="">
                                                    <h4>Bid has been sent</h4>
                                                    <p>  
                                                        Customers will review your profile and will only accept your bids if you standout.      
                                                    </p>
                                                    <a href="#" class="button gray" >Edit profile</a>
                                                    <a href="#" class="button dark" >View my profile</a>
                                                </div> 

                                        </div>
                                        <!-- Message Content Inner / End -->
                                
                                
                                    @elseif ($bid->bid_status >= 1)
                                                <!-- Message Content Inner -->
                                        <div class="message-content-inner" id="scrolltoheight">

                                            	<!-- Time Sign -->
									<div class="message-time-sign">
                                            <span>U Message</span>
                                        </div>
                                                
                                                <div class="message-bubble me">
                                                        <div class="message-bubble-inner ">
                                                            <div class="message-avatar"><img src="/mvp_ui/images/u_message.png" alt="" /></div>
                                                        <div class="message-text"><p>Congrants, {{$customer->first_name}} is willing to discuss further, also your contact details has been sent to {{$customer->first_name}}</p></div>
                                                        </div>
                                                        <div class="clearfix"></div>
                                                    </div>

                                                    <div id="chat-message"> 

                                                        <span class="text-center">Loading messages...</span>

                                                        </div>
                                                        @if ($bid->bid_status == 2)
                                                        <div class="message-time-sign">
                                                            <span>U Message</span>
                                                        </div>
                                                                
                                                                <div class="message-bubble me">
                                                                        <div class="message-bubble-inner ">
                                                                            <div class="message-avatar"><img src="/mvp_ui/images/u_message.png" alt="" /></div>
                                                                        <div class="message-text"><p>Congrants, {{$customer->first_name}} Project Accepted</p></div>
                                                                        </div>
                                                                        <div class="clearfix"></div>
                                                                    </div>

                                                                    @endif

                                        </div>
                                        <!-- Message Content Inner / End -->
                                    <!-- Reply Area -->
                                    
                                    <form id="message-submit" action="{{URL::to('/dashboard/inbox/send-message')}}" method="post"> 
                                                 
                                        <input type="hidden" name="_token"id="token-message"value="{{csrf_token()}}">
                                            <div class="message-reply">
                                                    <textarea cols="1" rows="1" placeholder="Your Message" data-autoresize id="message" type="text"name="message"></textarea>
                                                    <button  type="submit" class="button ripple-effect">Send</button>
                                                    <a href="{{route('project_files',base64_encode($project->id*786))}}" class="file-button ripple-effect gray"><i class="icon-line-awesome-paperclip"></i></a>

                                                </div>
                                     
                                     </form>
                         
    
                                        
                                    @endif

                                @endif
                               {{-- bid ends --}}
                                @endif
                           
                            </div>
                            <!-- Message Content -->
    
                              <!-- Messages -->
                              <div class="messages-inbox">
                                    <div class="messages-headline">
                                        <div class="input-with-icon">
                                            <h4 class="gray text-center"> Project details</h4>
                                            <i class="icon-feather-file-text"></i>
                                        </div>
                                    </div>
        
                                    <ul>
                                        <li>
                                                
        
                                                <div class="message-by">
                                                    <h4><strong>Customer Message:</strong><br> {{$project->project_message}}</h4>
                                                    <strong>Customer Demands:</strong>
                                                    @forelse ($details as $detail)
                                                    <div class="ques_holder">
                                                        <h4>{{$detail->ques_text}}</h4>
                                                        <strong>Answer: </strong><mark class="color">{{$detail->choice_text}}</mark>
                                                    </div>
                                                   
                                                    @empty
                                                        
                                                    @endforelse
                                                </div>
                                        </li>
        
        
                                    </ul>
                                </div>
                                <!-- Messages / End -->
        
                        </div>
                </div>
                <!-- Messages Container / End -->
    
    
@endif
			

    @section('page-js') 
    <script src="{{ asset('mvp_ui/vendor/ladda/dist/ladda.min.js') }}"></script>

    <script>
        
            $('.bid_form').hide();
    $('#b_bid_form').click(function() {
       
        $('.bid_form').fadeIn();
        $('.no_bid').fadeOut();
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
                    $('.bid_sent').fadeIn();
                    $('.bid_form').fadeOut();

                Snackbar.show({
                        text: 'Bid sent to {{$customer->first_name}}',
                        pos: 'top-center',
                        showAction: false,
                        actionText: "Dismiss",
                        duration:2000,
                        textColor: '#fff',
                        dismiss:false,
                        backgroundColor: '#383838'
                    });
                });
		    });

    </script>
 @if ($bid != null)
<script>  
        setInterval(ajaxCall,1000);        
        function ajaxCall() {
           
       
           var oldMessage=$("#chat-message li").length;
           var oldscrollHeight = $("#scrolltoheight").prop("scrollHeight");
            $.ajax({
                type:'get',
                url:'{{URL::to('/dashboard/inbox/chat')}}/'+{{$bid->id}},
                datatype:'html',
                success:function(response){
                    if (resonse = null) {
                        console.log('no message');
                    }
                    else{
                        console.log('got message');
                        console.log(response);

                    }
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
                        receiver:{{$customer->id}},
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
                        receiver:{{$customer->id}},
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