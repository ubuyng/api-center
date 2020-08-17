@extends('layouts.mvp_ui')

@section('content')
  <!-- Titlebar
================================================== -->
<div id="titlebar" class="gradient">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
    
                    <h2>Contact</h2>
    
                    <!-- Breadcrumbs -->
                    <nav id="breadcrumbs" class="dark">
                        <ul>
                            <li><a href="{{route('home')}}">Home</a></li>
                            <li>Contact Us</li>
                        </ul>
                    </nav>
    
                </div>
            </div>
        </div>
    </div>
    
    
    <!-- Content
    ================================================== -->
    
    
    <!-- Container -->
    <div class="container">
        <div class="row">
    
            <div class="col-xl-12">
                <div class="contact-location-info margin-bottom-50">
                    <div class="contact-address">
                        <ul>
                            <li class="contact-address-headline">Our Office</li>
                            <li>Plot 410/405 After Naval Staff Quarters, Jahi, FCT-Abuja, 900211, Nigeria</li>
                            <li>Phone 08091145425</li>
                            <li><a href="mailto:info@ubuy.ng"><span class="__cf_email__" data-cfemail="e885898184a88d90898598848dc68b8785">info@ubuy.ng</span></a></li>
                            <li>
                                <div class="freelancer-socials">
                                        <ul class="footer-social-links">

                                                <li>
        
                                                    <a href="https://www.facebook.com/UbuyNigeria" title="Facebook" data-tippy-placement="top" data-tippy-theme="light">
        
                                                        <i class="icon-brand-facebook-f"></i>
        
                                                    </a>
        
                                                </li>
        
                                                <li>
        
                                                    <a href="https://twitter.com/UbuyNg" title="Twitter" data-tippy-placement="top" data-tippy-theme="light">
        
                                                        <i class="icon-brand-twitter"></i>
        
                                                    </a>
        
                                                </li>
        
                                                <li>
        
                                                        <a href="https://www.instagram.com/ubuyng" title="Instagram" data-tippy-placement="top" data-tippy-theme="light">
        
                                                            <i class="icon-feather-instagram"></i>
                                            
                                                        </a>
        
                                                </li>
        
                                                <li>
        
                                                    <a href="https://www.linkedin.com/company/ubuy-nigeria/" title="LinkedIn" data-tippy-placement="top" data-tippy-theme="light">
        
                                                        <i class="icon-brand-linkedin-in"></i>
        
                                                    </a>
        
                                                </li>
                                                <li>
        
                                                    <a href="https://www.youtube.com/channel/UCsqN2Uu6Iv8Sr-uYbmrwo4A" title="Youtube" data-tippy-placement="top" data-tippy-theme="light">
        
                                                        <i class="icon-brand-youtube"></i>
        
                                                    </a>
        
                                                </li>
        
                                            </ul>
                                </div>
                            </li>
                        </ul>
    
                    </div>
                    <div id="single-job-map-container">
                        <div id="singleListingMap" data-latitude="9.0958845" data-longitude="7.424591899999999" data-map-icon="im im-icon-Hamburger"></div>
                        <a href="#" id="streetView">Street View</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                    <div href="#" class="job-listing">

                            <div class="job-listing-details">
                                    <div class="job-listing-description">
                                    <h3 class="job-listing-title" style="text-align:center">HELP / SUPPORT</h3>
                                    <p>For all things technical and app-related. Contact Us or reach us by email at <a href="mailto:hello@ubuy.ng"><mark class="color">hello@ubuy.ng</mark></a> </p>
                                </div>
                            </div>
                        </div>
            </div>
            <div class="col-md-4">
                    <div href="#" class="job-listing">

                            <div class="job-listing-details">
                                    <div class="job-listing-description">
                                    <h3 class="job-listing-title" style="text-align:center">PARTNERSHIPS</h3>
                                    <p>Interested in partnering with Ubuy?  <a href="mailto:partners@ubuy.ng"> <mark class="color">partners@ubuy.ng</mark></a> </p>

                                </div>
                            </div>
                        </div>
            </div>
            <div class="col-md-4">
                    <div href="#" class="job-listing">

                            <div class="job-listing-details">
                                    <div class="job-listing-description">
                                    <h3 class="job-listing-title" style="text-align:center">PRESS</h3>
                                    
                                    <p>Interested in incorporating Ubuy in your next article or blog? <a href="mailto:press@ubuy.ng"> <mark class="color">press@ubuy.ng</mark></a> </p>
                                </div>
                            </div>
                        </div>
            </div>
            <div class="col-md-4">
                    <div href="#" class="job-listing">

                            <div class="job-listing-details">
                                    <div class="job-listing-description">
                                    <h3 class="job-listing-title" style="text-align:center">AD SALES</h3>
                                    
                                    <p>Interested in advertising on Ubuy?  <a href="mailto:adsales@ubuy.ng"> <mark class="color">adsales@ubuy.ng</mark></a> </p>
                                </div>
                            </div>
                        </div>
            </div>
            <div class="col-md-4">
                    <div href="#" class="job-listing">

                            <div class="job-listing-details">
                                    <div class="job-listing-description">
                                    <h3 class="job-listing-title" style="text-align:center">SUCCESS STORIES</h3>
                                    <p>Did you meet the best service provider on Ubuy? Tell us about it.  <a href="mailto:ubuystories@ubuy.ng"> <mark class="color">ubuystories@ubuy.ng</mark></a> </p>
                                </div>
                            </div>
                        </div>
            </div>
            <div class="col-md-4">
                    <div href="#" class="job-listing">

                            <div class="job-listing-details">
                                    <div class="job-listing-description">
                                    <p>You may view Ubuy's Terms of Use here. To find out more about Ubuy's policy on the protection of the personal data of its users, please consult the Privacy Policy.</p>
                                </div>
                            </div>
                        </div>
            </div>

            <div class="col-xl-8 col-lg-8 offset-xl-2 offset-lg-2">
    
                <section id="contact" class="margin-bottom-60">
                    <h3 class="headline margin-top-15 margin-bottom-35">Any questions? Feel free to contact us!</h3>
    
                <form method="post"  id="contactform" autocomplete="on" action="">
                    @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-with-icon-left">
                                    <input class="with-border" name="name" type="text" id="name" placeholder="Your Name" required="required" />
                                    <i class="icon-material-outline-account-circle"></i>
                                </div>
                            </div>
    
                            <div class="col-md-6">
                                <div class="input-with-icon-left">
                                    <input class="with-border" name="email" type="email" id="email" placeholder="Email Address" pattern="^[A-Za-z0-9](([_\.\-]?[a-zA-Z0-9]+)*)@([A-Za-z0-9]+)(([\.\-]?[a-zA-Z0-9]+)*)\.([A-Za-z]{2,})$" required="required" />
                                    <i class="icon-material-outline-email"></i>
                                </div>
                            </div>
                        </div>
    
                        <div class="input-with-icon-left">
                            <input class="with-border" name="subject" type="text" id="subject" placeholder="Subject" required="required" />
                            <i class="icon-material-outline-assignment"></i>
                        </div>
    
                        <div>
                            <textarea class="with-border" name="message" cols="40" rows="5" id="comments" placeholder="Message" spellcheck="true" required="required"></textarea>
                        </div>
    
                        <input type="submit" class="submit button margin-top-15" id="submit" value="Submit Message" />
    
                    </form>
                </section>
    
            </div>
    
        </div>
    </div>
    <!-- Container / End -->
    
    @section('page-js')
        <!-- Google API & Maps -->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBhol0N_wyb0oZqcKjaU7afqPRFMfz7X80&libraries=places"></script>
<script src="/mvp_ui/js/infobox.min.js"></script>
<script src="/mvp_ui/js/markerclusterer.js"></script>
<script src="/mvp_ui/js/maps.js"></script>

    @endsection
@endsection