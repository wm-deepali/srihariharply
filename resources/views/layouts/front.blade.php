<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>@yield('title', 'Sri Harihar Ply & Hardware')</title>
<link href="{{ asset('front/css/bootstrap.css') }}" rel="stylesheet">
<link href="{{ asset('front/css/style.css') }}" rel="stylesheet">
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<link href="https://fonts.googleapis.com/css?family=Arimo" rel="stylesheet">
<link href="{{ asset('front/css/responsive.css') }}" rel="stylesheet">
</head>
<body class="theme-brown">

    <div class="preloader"></div>
    <header class="main-header header-style-one clearfix">

        <div class="header-mainbox">
            <div class="auto-container">
                <div class="clearfix">

                    <div class="pull-left logo-outer">
                        <div class="logo"><a href="{{ route('home') }}"><img src="{{ $siteLogo?->image_url ?? asset('front/img/logo.png') }}" alt="" title="Sri Harihar"></a></div>
                    </div>

                    <div class="pull-left outer-box clearfix">
                    <div class="mainmenu-bg clearfix">
                    <div class="main-menu-area clearfix">
                        <nav class="main-menu">
                            <div class="navbar-header">
                                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                </button>
                            </div>

                            <div class="navbar-collapse collapse clearfix">
                                <ul class="navigation clearfix">
                                    <li class="{{ request()->routeIs('home') ? 'current' : '' }}"><a href="{{ route('home') }}">Home</a></li>
                                    <li class="{{ request()->routeIs('about') ? 'current' : '' }}"><a href="{{ route('about') }}">About us</a></li>
                                    <li class="{{ request()->routeIs('gallery') ? 'current' : '' }}"><a href="{{ route('gallery') }}">Gallery</a></li>
                                    <li class="{{ request()->routeIs('products') ? 'current' : '' }}"><a href="{{ route('products') }}">Products</a></li>
                                    <li class="{{ request()->routeIs('enquiry') ? 'current' : '' }}"><a href="{{ route('enquiry') }}">Send Enquiry</a></li>
                                    <li class="{{ request()->routeIs('locate-us') ? 'current' : '' }}"><a href="{{ route('locate-us') }}">Contact</a></li>
                                </ul>
                            </div>
                        </nav>

                        <ul class="actions-outer pull-right clearfix">
                           <li><div class="sidenav-toggler hidden-bar-opener"><a href="#" class="theme-btn catalogue-btn">MENU</a></div></li>
                        </ul>

                    </div>
                    </div>

            </div>
        </div>
        </div>

        <section class="hidden-bar right-align">
            <div class="hidden-bar-closer">
                <button class="btn"><i class="fa fa-arrows" aria-hidden="true"></i></button>
            </div>
            <div class="hidden-bar-wrapper">
                <div class="logo text-center">
                    <a href="{{ route('home') }}"><img src="{{ $siteLogo?->image_url ?? asset('front/img/logo.png') }}" alt=""></a>
                </div>
                <div class="side-menu">
                    <ul class="navigation clearfix">
                        <li><a href="{{ route('home') }}">Home</a></li>
                        <li><a href="{{ route('about') }}">About us</a></li>
                        <li><a href="{{ route('products') }}">Products</a></li>
                        <li><a href="{{ route('gallery') }}">Gallery</a></li>
                        <li><a href="{{ route('enquiry') }}">Send Enquiry</a></li>
                        <li><a href="{{ route('locate-us') }}">Contact</a></li>
                    </ul>
                </div>
            </div>
        </section>
        </div>
    </header>

@yield('content')

<section class="footer_area">
    <div class="container">
        <div class="row">
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="footer-about">
                    <h2>About Us</h2>
                    <p></p>
                   <h6>{!! Str::substr($footerAbout->content ?? '', 0, 300) !!}</h6>
                </div>
            </div>
            <div class="col-md-2 col-sm-6 col-xs-12">
                <div class="footer-useful">
                    <h2>Main Menu</h2>
                    <ul class="useful-left">
                        <li><a href="{{ route('products') }}">Products</a></li>
                        <li><a href="{{ route('gallery') }}">Gallery</a></li>
                        <li><a href="{{ route('enquiry') }}">Send Enquiry</a></li>
                        <li><a href="{{ route('about') }}">About Us</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="footer-resent">
                    <h2>Locate us</h2>
                    <div class="resent-1">
                     <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1779.3157655496882!2d80.93061195078947!3d26.883445495670173!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x399bfd81307fcfdf%3A0x8837ca9dec7ba2fc!2sSri+Harihar+Ply+%26+Hardware!5e0!3m2!1sen!2sin!4v1512115957510" width="100%" height="200px" frameborder="0" style="border:0" allowfullscreen></iframe>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-6 col-xs-12">
               <div class="footer-about">
                    <h2>Contact Us</h2>
                    <p></p>
                    <h6>Phone:  +91-912-577-6666</h6>
                   <h6><img src="https://cdn0.iconfinder.com/data/icons/social-flat-rounded-rects/512/whatsapp-256.png" class="whatsapp"> +91-902-618-7442</h6>
                    <h6>Email: srihariharplyandhardware@gmail.com</h6>
                    <h6>Add: 598A/W58(03LA) Mausam Bagh, Triveni Nagar II
Sitapur Road, Opp Balaji Lawn Lucknow - 226020
</h6>
                    <ul>
                        <li><a href="#"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
                        <li><a href="#"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
                        <li><a href="#"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>
                        <li><a href="#"><i class="fa fa-rss" aria-hidden="true"></i></a></li>
                        <li><a href="#"><i class="fa fa-google-plus" aria-hidden="true"></i></a></li>
                        <li><a href="#"><i class="fa fa-vimeo" aria-hidden="true"></i></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="footer-bottom_area">
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="footer-bottom">
                    <p>Copyright © {{ now()->year }} Sri Harihar Ply &amp; Hardware. All rights reserved.</p>
                </div>
            </div>
           <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="footer-bottom-right">
                    <p>Design &amp; Developed By <a href="http://www.webmingo.com/">Web Mingo It Solutions</a></p>
                </div>
            </div>
        </div>
    </div>
</section>
<div class="scroll-to-top scroll-to-target" data-target=".main-header"><span class="fa fa-long-arrow-up"></span></div>

	<script src="{{ asset('front/js/jquery.js') }}"></script>
	<script src="{{ asset('front/js/bootstrap.min.js') }}"></script>
	<script src="{{ asset('front/js/jquery.mCustomScrollbar.concat.min.js') }}"></script>
	<script src="{{ asset('front/js/jquery.countTo.js') }}"></script>
	<script src="{{ asset('front/js/jquery.appear.js') }}"></script>
	<script src="{{ asset('front/js/jquery.mixitup.min.js') }}"></script>
    <script src="{{ asset('front/js/jquery.fancybox.pack.js') }}"></script>
    <script src="{{ asset('front/js/masterslider.js') }}"></script>
	<script src="{{ asset('front/js/bxslider.js') }}"></script>
	<script src="{{ asset('front/js/owl.js') }}"></script>
	<script src="{{ asset('front/js/validate.js') }}"></script>
	<script src="{{ asset('front/js/wow.js') }}"></script>

	<script src="{{ asset('front/revolution/js/jquery.themepunch.tools.min.js') }}"></script>
	<script src="{{ asset('front/revolution/js/jquery.themepunch.revolution.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('front/revolution/js/extensions/revolution.extension.actions.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('front/revolution/js/extensions/revolution.extension.carousel.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('front/revolution/js/extensions/revolution.extension.kenburn.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('front/revolution/js/extensions/revolution.extension.layeranimation.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('front/revolution/js/extensions/revolution.extension.migration.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('front/revolution/js/extensions/revolution.extension.navigation.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('front/revolution/js/extensions/revolution.extension.parallax.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('front/revolution/js/extensions/revolution.extension.slideanims.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('front/revolution/js/extensions/revolution.extension.video.min.js') }}"></script>
	<script src="{{ asset('front/js/custom.js') }}"></script>
@yield('scripts')
</body>
</html>