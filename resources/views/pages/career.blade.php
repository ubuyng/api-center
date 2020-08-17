@extends('layouts.mvp_ui')

@section('page-css')
    <style>
    .contact-location-info .contact-address {
    border: 1px solid #fff !important;
    border-right: 0;
    border-radius: 17px 0 0 17px;
    text-align: center;
    padding: 40px;
}
.banner-headline {
    max-width: 100%;
    text-align: center;
}
.about1{
  padding: 5%;
}
.img-banner {
    /* position: absolute; */
    height: 100%;
    width: 100%;
  }
  .blog-compact-item:before {
    content: "";
    top: 0;
    position: absolute;
    height: 100%;
    width: 100%;
    z-index: 9;
    border-radius: 4px;
    background:none;
    transition: .4s;
}
.blog-compact-item {
    background: #fff;
    box-shadow: 0 3px 10px rgba(0, 0, 0, .2);
    border-radius: 4px;
    height: 100%;
    display: block;
    position: relative;
    background-size: cover;
    background-repeat: no-repeat;
    background-position: 50%;
    height: 100%;
    z-index: 100;
    cursor: default;
    transition: .4s;
}
.blog-compact-item-content h3 {
    color: #333;
    font-size: 20px;
    padding: 5px 0;
    font-weight: 500;
    margin: 2px 0 0;
    line-height: 30px;
    text-align: center;
}
.blog-compact-item-content p {
    font-size: 16px;
    font-weight: 300;
    display: inline-block;
    color: #333;
    margin: 7px 0 0;
    text-align: center;

}
.blog-compact-item-content {
    position: unset;
    bottom: 32px;
    left: 0;
    padding: 0 34px;
    width: 100%;
    z-index: 50;
    box-sizing: border-box;
    text-align: center;
}
.blog-compact-item img {
    object-fit: cover;
    height: 100%;
    width: 33%;
    border-radius: 4px;
    margin-top: 21px;
}
img.no_job {
    width: 30%;
    margin-bottom: 27px;
}
    </style>
@endsection

@section('content')
  <!-- Titlebar
================================================== -->
<div id="titlebar" class="gradient">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
    
                    <h2>Careers</h2>
    
                    <!-- Breadcrumbs -->
                    <nav id="breadcrumbs" class="dark">
                        <ul>
                            <li><a href="{{route('home')}}">Home</a></li>
                            <li>Careers</li>
                        </ul>
                    </nav>
    
                </div>
            </div>
        </div>
    </div>

    <!-- Container -->
    <div class="container">
        <div class="row">
    
            <div class="col-xl-12">
                <div class="contact-location-info margin-bottom-50">
                    <div class="contact-address">
                      <div class="row">
                        <div class="col-md-12">
                            <h4>“In Nigeria today, a lot of talks, seminars, shows, and programs are
                                being hosted daily, with hopes of imbibing the entrepreneurial spirit in citizens. 
                                Adeviation from reliance on government. Personally, I have come to the realization,
                                 through personal experience, that Nigerians have a strong entrepreneurial 
                                 spirit within them. The environment and circumstances, as inmost cases do 
                                 not accord them the opportunity to work and earn gainfully.That’s why 
                                 at Ubuy Nigeria, we have decided to create a platform to enable broad and quick
                                  access to these nation builders, the entrepreneurs. Taking them to levels
                                   beyond their imagination”</h4>
                        </div>

                        <div class="col-md-12">
                            <br>
                            <h3><mark class="color">-Jeremiah Ajilore, CEO.</mark></h3>
                        </div>
                      </div>
                    
                          
                          </div>
                        </div>
            </div>


        </div>
    </div>
    <div class="intro-banner dark-overlay" data-background-image="mvp_ui/images/career_bg.jpg">

        <!-- Transparent Header Spacer -->
      
        <div class="container">
          
          <!-- Intro Headline -->
          <div class="row">
            <div class="col-md-12">
              <div class="banner-headline">
                <h3>
                  <strong>Why work with us?</strong>
                  <br>
                  <span>The   welfare   of   our   employees   are   super   priorities;  
                        we   create   a   free environment for our employees to 
                        work and play hard within business hours.In good faith that 
                        it will keep them mentally and physically proactive, 
                        withoutboredom or stress taking a toll on them. And YES, 
                        they give us the best results!</span>
                </h3>
              </div>
            </div>
          </div>
          
        </div>
      </div>
   
      <div class="section padding-top-65 padding-bottom-50">
            <div class="container">
              <div class="row">
                <div class="col-xl-12">
               
                  <div class="row">
                    <!-- Blog Post Item -->
                    <div class="col-xl-4">
                        <div class="blog-compact-item-container">
                            <div class="blog-compact-item">
                          <div class="blog-compact-item-content">
                              <img src="/mvp_ui/images/icons/career_1.svg" alt="">
        
                              <h3>People-oriented mission</h3>
                              <p>Instead of creating a technological platform that takes away from skilled individuals; we offer an opportunity for all entrepreneurs to be seen andbetter still, accessed. We are the future of works in Nigeria.</p>
                            </div>
                        </div>
                      </div>
                    </div>
                    <!-- Blog post Item / End -->
          
                    <!-- Blog Post Item -->
                    <div class="col-xl-4">
                        <div class="blog-compact-item-container">
                            <div class="blog-compact-item">
                          <div class="blog-compact-item-content">
                              <img src="/mvp_ui/images/icons/career_2.svg" alt="">
                              <h3>Ever innovative</h3>
                              <p>We motivate our employees into taking that extra step to provide the results that exceed expectations. 
                                  This keeps us going; up and beyond, creating new ideas that steadily improves the state of UbuyNG.</p>
                              </div>
                            </div>
                          </div>
                        </div>
                        <!-- Blog post Item / End -->
                        
                        <!-- Blog Post Item -->
                        <div class="col-xl-4">
                          <div class="blog-compact-item-container">
                            <div class="blog-compact-item">
                              <div class="blog-compact-item-content">
                                  <img src="/mvp_ui/images/icons/career_3.svg" alt="">
        
                                <h3>Rich diversity for rich results</h3>
                           <p>We are not exclusive to any single individual or group of individuals. Hence,we look beyond ethnicity and religion to create a staff strength that is highlydiverse, and from all backgrounds</p>
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- Blog post Item / End -->

                       <!-- Blog Post Item -->
                       <div class="col-xl-4">
                            <div class="blog-compact-item-container">
                                <div class="blog-compact-item">
                              <div class="blog-compact-item-content">
                                  <img src="/mvp_ui/images/icons/career_4.svg" alt="">
            
                                  <h3>A happy office</h3>
                                  <p>We boast of the lovable conditions we provide in our offices. We ensure thatour employees are happy.</p>
                                </div>
                            </div>
                          </div>
                        </div>
                        <!-- Blog post Item / End -->
              
                        <!-- Blog Post Item -->
                        <div class="col-xl-4">
                            <div class="blog-compact-item-container">
                                <div class="blog-compact-item">
                              <div class="blog-compact-item-content">
                                  <img src="/mvp_ui/images/icons/career_5.svg" alt="">
                                  <h3>Healthy living. Healthy workers. Healthy results</h3>
                                  <p>We   provide   discounted  gym   membership   for   all  our  employees,   fitness sessions twice a week, paid holidays, and daily meals.</p>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <!-- Blog post Item / End -->
                            
                            <!-- Blog Post Item -->
                            <div class="col-xl-4">
                              <div class="blog-compact-item-container">
                                <div class="blog-compact-item">
                                  <div class="blog-compact-item-content">
                                      <img src="/mvp_ui/images/icons/career_6.svg" alt="">
            
                                    <h3>Amazing incentives</h3>
                               <p>In addition to other standard benefits, which you may expect, we give ouremployees special treats and incentives annually to spend on UbuyNG services.</p>
                              </div>
                            </div>
                          </div>
                        </div>
                  </div>

                 
                </div>
              </div>
            </div>
          </div>
          
          <div class="container">
                <div class="row">
            
                    <div class="col-md-7 offset-xl-3">
                        <div class="contact-location-info margin-bottom-50">
                            <div class="contact-address">
                              <div class="row">
                                <div class="col-md-12">
                                        <div class="blog-compact-item-content">
                                                <img class="no_job" src="/mvp_ui/images/icons/pro_services.svg" alt="">
                      
                                                <h2 style="text-align:center;">No Available Positions</h2>
                                         </div>
                                </div>
                              </div>
                            
                                  
                                  </div>
                                </div>
                    </div>
        
        
                </div>
            </div>
        
      @section('page-js')


    @endsection
@endsection