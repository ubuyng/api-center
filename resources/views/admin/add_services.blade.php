@extends('layouts.mvp_dash')

@section('page-css')
    <style>
    
    </style>
@endsection

@section('content')
 
@if ( Auth::user()->user_role == 'pro')

                
                <!-- Dashboard Headline -->
                <div class="dashboard-headline">
                    <h3> Add services</h3>
    
                    <!-- Breadcrumbs -->
                    <nav id="breadcrumbs" class="dark">
                        <ul>
                            <li><a href="{{route('dashboard')}}">Dashboard</a></li>
                            <li><a href="{{route('pro_services')}}">My Services</a></li>
                            <li>Add services</li>
                        </ul>
                    </nav>
                </div>
        	<!-- Row -->
			<div class="row">

				<!-- Dashboard Box -->
				<div class="col-xl-12">
					<div class="dashboard-box margin-top-0">

						<!-- Headline -->
						<div class="headline">
							<h3><i class="icon-feather-folder-plus"></i> Add a service you offer</h3>
						</div>

						<div class="content with-padding padding-bottom-10">
							<div class="row">

								<div class="col-xl-12">

                                <form action="{{route('save_services')}}" method="post">
                                        @csrf

									<div class="submit-field">
										<h5>What services do you provide</h5>
                                        <select name="sub_category_id" class="selectpicker" id="select_service" data-live-search="true">
                                                <option value="" disabled selected> search services e.g Plumber, Capenter, Tailor, Gardner, Web designer</option>
                                            @forelse ($subcats as $cat)
                                        <option value="{{$cat->id}}">{{$cat->name}}</option>
                                            @empty
                                            @endforelse
                                        </select>
                                        <input type="hidden" value="" id="select_text" name="service_name" >
                                    </div>

                                   
                                            <button type="submit" class="button ripple-effect big margin-top-30"><i class="icon-feather-plus"></i> Add Service</button>
                        
                                </form>

								</div>

								

							</div>
						</div>
					</div>
				</div>

			
			</div>
			<!-- Row / End -->
    
    
    
@elseif ( Auth::user()->user_role == 'customers')

@endif
			

    @section('page-js')   

    <script>
    $('#select_service').change(function(){ 
        console.log($(this).val());
        console.log($("#select_service option:selected").text());
        var select_text = $("#select_service option:selected").text();

        $("#select_text").val(select_text);
        // $(".user_city").text(city);
        
            });
    </script>
    @endsection

@endsection