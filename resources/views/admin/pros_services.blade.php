@extends('layouts.mvp_dash')

@section('page-css')
    <style>
    
    .status-icon.status-message, .status-message:after {
    background-color: #e53935;
}
.pros_holder {
    text-align: center;
}
.job-listing-details {
    text-align: center;
}
.grid-layout .job-listing-details {
    flex-grow: 1;
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    padding: 30px;
    padding-right: 40px;
    margin-bottom: 8px;
}
.grid-layout .job-listing {
    display: flex;
    flex-direction: column;
    justify-content: center;
    margin: 0 30px 30px 0;
    width: calc(85% * (1/2) - 30px);
    flex-direction: column;
}
.job_subtext{
    margin: 24px 0px;
}
    </style>
@endsection

@section('content')
 
@if ( Auth::user()->user_role == 'pro')

                
                <!-- Dashboard Headline -->
                <div class="dashboard-headline">
                    <h3> My services</h3>
    
                    <!-- Breadcrumbs -->
                    <nav id="breadcrumbs" class="dark">
                        <ul>
                            <li><a href="{{route('home')}}">Home</a></li>
                            <li><a href="{{route('dashboard')}}">Dashboard</a></li>
                            <li>My services</li>
                        </ul>
                    </nav>
                </div>
        
                <!-- Row -->
                <div class="row">
                          <!-- no projects -->
                          <div class="col-xl-12">
                          @if ($services->isEmpty())
                             <div class="no_projects">
                                <img src="/mvp_ui/images/icons/pro_services.svg" alt="">
                            <h3>Add services you offer and start getting projects </h3>
                        <a href="{{route('add_services')}}" class="button dark ripple-effect">Add Services</a>
                        </div> 

                        @else

                        <a href="{{route('add_services')}}" class="button dark ripple-effect">Add Services</a>
                    </div>
                        <!-- no projects ends here -->
                           
                          @endif
    @forelse ($services as $item)
                    <!-- Dashboard Box -->
                    <div class="col-xl-6">
                          <div class="listings-container margin-top-35">
                                       
                                <div class="job-listing service{{$item->id}}">
                        
                                            <!-- Job Listing Details -->
                                            <div class="job-listing-details">
                                               
                                                <!-- Details -->
                                                <div class="job-listing-description">
                                                    <h2 class="job-listing-company">{{$item->service_name}} </h2>
                                                </div>
                                            </div>
                        
                                            <!-- Job Listing Footer -->
                                            <div class="job-listing-footer">
                                                  
                                                <ul>
                                                <li><i class="icon-feather-bar-chart"></i> Views: {{$item->service_views}}</li>
                                                    <li><i class="icon-feather-users"></i> Leads: {{$item->service_leads}}</li>
                                                </ul>
                                                <button class="button deactivate{{$item->id}} gray ripple-effect">Deactivate</button> 

                                            </div>
                                        </div>	
                                            
                                    </div>
                                    
                                </div>
                                
                                @empty
                                    
                                @endforelse
                </div>
                
                <!-- Row / End -->
    
    
    
@elseif ( Auth::user()->user_role == 'customers')

@endif
			

    @section('page-js')   
            @forelse ($services as $ajax)
        <script>
         $('.deactivate{{$ajax->id}}').click(function () { //Close Button on Form Modal to trigger Warning Modal
				swal({
					title: "Deactivate {{$ajax->service_name}}",
					text: "You will no longer be able to recieve projects, this service will be permanently deleted",
					icon: "warning",
					buttons: true,
					dangerMode: true,
					})
					.then((willDelete) => {
					if (willDelete) {
                        send_delete{{$ajax->id}}()
                    } 
					});
		    });
            function send_delete{{$ajax->id}}(){
                Snackbar.show({
                        text: 'Removing service',
                        pos: 'top-center',
                        showAction: false,
                        actionText: "Dismiss",
                        duration:2000,
                        textColor: '#fff',
                        dismiss:false,
                        backgroundColor: '#383838'
                    });
                $.post("{{route('destroy_services')}}",
                {
                    "_token": "{{ csrf_token() }}",
                    service_id: '{{$ajax->id}}',
                },
                function(data, status){
                    $('.service{{$ajax->id}}').fadeOut();
                });
                }
       
    </script>
                @empty
                
                @endforelse
    @endsection

@endsection