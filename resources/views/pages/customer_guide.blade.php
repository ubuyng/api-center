@extends('layouts.mvp_ui')

@section('content')
  <!-- Titlebar
================================================== -->
<div id="titlebar" class="gradient">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
    
                    <h2>Customer Guidelines</h2>
    
                    <!-- Breadcrumbs -->
                    <nav id="breadcrumbs" class="dark">
                        <ul>
                            <li><a href="{{route('home')}}">Home</a></li>
                            <li>Customer Guidelines</li>
                        </ul>
                    </nav>
    
                </div>
            </div>
        </div>
    </div>
    
    <!-- Post Content -->
<div class="container-fluid">
	<div class="row">
		
		<!-- Inner Content -->
		<div class="col-xl-8 col-lg-8">
			<div class="blog-post single-post">
                
                <div class="blog-post-content">
					<h3 class="margin-bottom-10">Ubuy.ng Customer Guidelines</h3>

					<div class="blog-post-info-list margin-bottom-20">
						<a href="#"  class="blog-post-info"><strong>TREAT AS IMPORTANT:</strong><br></a>
                    </div>
					To ensure a safe, reliable and successful working environment for every UbuyNG user, we have set up rules and regulations that our community must follow. Any breech of these rules is a serious offence and the defaulter would be held accountable for it.
					
					<br><br><strong>Sharing private information publicly</strong><br>
					To maintain the integrity and authenticity of UbuyNG, our members can only share private contact information once an offer is accepted and they have an understanding with each other. To protect your safety and security, private contact details or 3rd party links are not allowed to be shared in any public areas of the site.
					
					<br><br><strong>Prohibited behaviours</strong><br>
					Community is at the heart of what we do at UbuyNG. Courtesy, mutual respect and seeing things from another person's perspective is essential. At UbuyNG, the following behaviours against a customer or a pro or a UbuyNG staff are prohibited; Violence, Discrimination, Harassment, illegal acts, hate speech, trolling and verbal abuse.
					
					<br><br><strong>Unsupported practices
					</strong><br>At UbuyNG, we're committed to linking customers to pros. However, this requires full cooperation from our users. UbuyNG do not support the following activities within our community, as it jeopardizes the creation of a fair, transparent and trustworthy environment:
					<ul>
						 <li>Fraudulent reviews</li>
					</ul>
					<ul>
						 <li>Artificially improving public profile</li>
						 <li>Harvesting member information</li>
						 <li>Lead generation</li>
						 <li>No alcohol related Tasks</li>
					</ul>
					<br><br><strong>Pricing and payments
					</strong><br>To ensure a safe and rewarding environment for all UbuyNG users, it's important to understand how pricing and payments work.
					UbuyNG does not offer any form of payment method. Means of payment would be discussed and agreed on between the customer and the professional. However, professionals are advised to provide accurate banking details to their customers to ensure seamless transition of funds if they are not receiving cash.
					
					
					<br><br><strong>Accountability and Reputation</strong><br>
					
					&nbsp;
					
					At UbuyNG, we have a zero tolerance policy for against acts that do not promote accountability and integrity. We are committed to creating a sustainable culture of accountability and integrity that will protect both our customers and pros. Any member of the UbuyNG community found in violation of this culture will be held accountable for his actions.
					
					&nbsp;
					
					&nbsp;
                    
				</div>

			</div>
		
			
		
		</div>
		<!-- Inner Content / End -->


		<div class="col-xl-4 col-lg-4 content-left-offset">
			<div class="sidebar-container">
				
			

				<!-- Widget -->
				<div class="sidebar-widget">

					<h3>Important Pages</h3>
					<ul class="widget-tabs">
							<li>
								<a href="{{route('pro_guide')}}" class="widget-content">
									<div class="widget-text">
										<h5>Pros Guidelines</h5>
									</div>
								</a>
							</li>
							<li>
								<a href="{{route('customer_guide')}}" class="widget-content">
									<div class="widget-text">
										<h5>Customers Guidelines</h5>
									</div>
								</a>
							</li>
							<li>
								<a href="{{route('privacy_policy')}}" class="widget-content">
									<div class="widget-text">
										<h5>Privacy Policy</h5>
									</div>
								</a>
							</li>
	
							<li>
								<a href="{{route('safety')}}" class="widget-content">
									<div class="widget-text">
										<h5>Safety And Precautions</h5>
									</div>
								</a>
							</li>
							{{-- <li>
								<a href="#" class="widget-content">
									<img src="images/blog-04a.jpg" alt="">
									<div class="widget-text">
										<h5>Guarantee</h5>
									</div>
								</a>
							</li> --}}
						</ul>

				</div>
				<!-- Widget / End-->
			</div>
		</div>

	</div>
</div>

<!-- Spacer -->
<div class="padding-top-40"></div>
<!-- Spacer -->



    @section('page-js')
        <!-- Google API & Maps -->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBhol0N_wyb0oZqcKjaU7afqPRFMfz7X80&libraries=places"></script>
<script src="/mvp_ui/js/infobox.min.js"></script>
<script src="/mvp_ui/js/markerclusterer.js"></script>
<script src="/mvp_ui/js/maps.js"></script>

    @endsection
@endsection