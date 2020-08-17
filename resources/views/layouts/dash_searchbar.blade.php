<div class="full-page-sidebar">
		<div class="full-page-sidebar-inner" data-simplebar>
			<div class="sidebar-container">
				
				<!-- Location -->
				<div class="sidebar-widget">
						@if ($distance > 20)
						<h3>Within {{$distance}} KM</h3>
							
						@else
						<h3>Within {{$city}}</h3>
						@endif

										<a href="#small-dialog" class=" button full-width gray popup-with-zoom-anim margin-bottom-50">Change Distance <i class="icon-material-outline-location-on"></i></a>

				</div>

				<!-- Category -->
				<div class="sidebar-widget">
					<h3>Category</h3>
					<select class="selectpicker" data-live-search="true" title="All Categories" >
					@forelse ($cats as $cat)
					<option>{{$cat->name}}</option>
						
					@empty
						
					@endforelse
					
					</select>
				</div>
				
			

				<!-- Keywords -->
				{{-- <div class="sidebar-widget">
					<h3>Keywords</h3>
					<div class="keywords-container">
						<div class="keyword-input-container">
							<input type="text" class="keyword-input" placeholder="e.g. task title"/>
							<button class="keyword-input-button ripple-effect"><i class="icon-material-outline-add"></i></button>
						</div>
						<div class="keywords-list"><!-- keywords go here --></div>
						<div class="clearfix"></div>
					</div>
				</div> --}}

				<!-- Budget -->
				{{-- <div class="sidebar-widget">
					<h3>Fixed Price</h3>
					<div class="margin-top-55"></div>

					<!-- Range Slider -->
					<input class="range-slider" type="text" value="" data-slider-currency="$" data-slider-min="10" data-slider-max="2500" data-slider-step="25" data-slider-value="[50,2500]"/>
				</div> --}}

				<!-- Hourly Rate -->
				{{-- <div class="sidebar-widget">
					<h3>Hourly Rate</h3>
					<div class="margin-top-55"></div>

					<!-- Range Slider -->
					<input class="range-slider" type="text" value="" data-slider-currency="$" data-slider-min="10" data-slider-max="150" data-slider-step="5" data-slider-value="[10,200]"/>
				</div> --}}

				
				<div class="clearfix"></div>

				<div class="margin-bottom-40"></div>

			</div>
			<!-- Sidebar Container / End -->

			<!-- Search Button -->
			<div class="sidebar-search-button-container">
				<button class="button ripple-effect">Search</button>
			</div>
			<!-- Search Button / End-->

		</div>
	</div>
	<!-- Full Page Sidebar / End -->
	