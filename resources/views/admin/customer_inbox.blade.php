@extends('layouts.mvp_dash')

@section('page-css')
    <style>
    .freelancer-overview .freelancer-avatar img {
    width: 48px;
    border-radius: 50%;
    cursor: pointer;
    height: 48px;
}

.status_icon:after {
    position: relative;
    content: "";
    height: 12px;
    width: 12px;
    background-color: silver;
    display: block;
    border: 2px solid #fff;
    box-shadow: 0 2px 3px rgba(0, 0, 0, .3);
    border-radius: 50%;
}
.freelancer-detail-item {
    margin-top: 10px;
}
.status_icon:after {
    background-color: #38b653;
}
ul.dashboard-box-list>li {
    padding: 4px 10px;
}
.dashboard-box .freelancer-overview {
    padding: 6px 0;
}
span.msg_date {
    color: green;
    background-color: aliceblue;
    padding: 2px;
    margin-top: 13px;
    border-radius: 5px;
}
    </style>
@endsection

@section('content')
 
@if ( Auth::user()->user_role == 'customer')

                
                <!-- Dashboard Headline -->
                <div class="dashboard-headline">
                    <h3> My Inbox</h3>
    
                    <!-- Breadcrumbs -->
                    <nav id="breadcrumbs" class="dark">
                        <ul>
                            <li><a href="{{route('home')}}">Home</a></li>
                            <li><a href="{{route('dashboard')}}">Dashboard</a></li>
                            <li>Inboxs</li>
                        </ul>
                    </nav>
                </div>
        
                <!-- Row -->
                <div class="row justify-content-center">
    
                    @if ($projects->isEmpty())
                    <!-- Dashboard Box -->
                    <div class="col-xl-12">

                        <!-- no projects -->
<div class="no_projects">
         <img src="images/icons/no_project_icon.svg" alt="">
     <h3>You don't have any active project</h3>
 </div> 

<div class="col-xl-12">
<div class="section-headline centered margin-top-0 margin-bottom-45">
<h3>Explore Ubuy</h3>
</div>
</div>

<div class="row explore_dash">
@forelse ($home_cat as $cat)
<div class="col-xl-3 col-md-6">
 <a href="{{ route('category', $cat->slug) }}" class="photo-box small" data-background-image="{{ url('/'). env('BACKEND_IMAGES_PATH') .$cat->image }}">
         <div class="photo-box-content">
             <h3>{{$cat->name}}</h3>
             <span>600</span>
         </div>
     </a>
</div>


@empty


@endforelse

</div>

</div>
@endif


	<!-- Dashboard Box -->
    <div class="col-md-8">
        @forelse ($projects as $project)
        <div class="dashboard-box margin-top-0">

                <!-- Headline -->
                <div class="headline">
                    <h3><i class="icon-material-outline-supervisor-account"></i> {{$project->sub_category_name}}</h3>
                </div>

                <div class="content">
                    <ul class="dashboard-box-list">
                       @forelse ($bids as $item)
                       @if ($item->id == $project->id)
                           
                       <li>
                           <!-- Overview -->
                           <div class="freelancer-overview manage-candidates">
                               <div class="freelancer-overview-inner">

                                   <!-- Avatar -->
                                   <div class="freelancer-avatar status-message">
                                        @if ($item->profile_photo == null)
                                        <a href="{{route ('cus_project_chat', base64_encode($item->bid_id*786)) }}"><img src="/mvp_ui/images/icons/user_icon.svg" alt=""></a>
                                        @else
                                        <a href="{{route ('cus_project_chat', base64_encode($item->bid_id*786)) }}"><img src="{{ url('/'). env('PROFILE_IMAGES_PATH') .$item->profile_photo }}" alt=""></a>
                                        @endif

                                   </div>

                                   <!-- Name -->
                                   <div class="freelancer-name">
                                       <h4><a href="{{route ('cus_project_chat', base64_encode($item->bid_id*786)) }}">{{$item->business_name}} </a> <span class="msg_date">₦{{$item->bid_amount}}</span></h4>

                                       <!-- Details -->
                                       <div class="freelancer-detail-item">
                                           <span class="msg_date">Bid Message</span>
                                           <span class="status-message">{{$item->bid_message}}</span>
                                       </div>

                                   </div>
                               </div>
                           </div>
                       </li>
                     
                       @endif
                        @empty
                        
                        @endforelse
                    </ul>
                </div>
            </div>
            <br>
        @empty
            
        @endforelse
         
        </div>
                </div>

@elseif ( Auth::user()->user_role == 'pros')

@endif
			

    @section('page-js')   
    @endsection

@endsection