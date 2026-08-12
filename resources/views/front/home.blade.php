@extends('layouts.front')

@section('content')

    <section class="rev_slider_wrapper">
        <div id="slider1" class="rev_slider" data-version="5.0">
            <ul>
                @foreach($sliders as $slide)
                    <li data-transition="fade" data-slotamount="1" data-masterspeed="1000" data-thumb="{{ $slide->image_url }}"
                        data-saveperformance="off" data-title="Awesome Title Here">
                        <img src="{{ $slide->image_url }}" alt="" data-bgposition="center top" data-bgfit="cover"
                            data-bgrepeat="no-repeat">
                        <div class="tp-caption tp-resizeme donation" data-x="center" data-hoffset="0" data-y="top"
                            data-voffset="370" data-transform_idle="o:1;"
                            data-transform_in="x:[-175%];y:0px;z:0;rX:0;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;opacity:0.01;s:3000;e:Power3.easeOut;"
                            data-transform_out="s:1000;e:Power3.easeInOut;s:1000;e:Power3.easeInOut;"
                            data-mask_in="x:[100%];y:0;s:inherit;e:inherit;" data-splitin="none" data-splitout="none"
                            data-start="500">
                            <h2>{{ $slide->title }} <span></span></h2>
                        </div>
                        <div class="tp-caption tp-resizeme donation" data-x="center" data-hoffset="0" data-y="top"
                            data-voffset="470" data-transform_idle="o:1;"
                            data-transform_in="x:[175%];y:0px;z:0;rX:0;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;opacity:0.01;s:3000;e:Power3.easeOut;"
                            data-transform_out="s:1000;e:Power3.easeInOut;s:1000;e:Power3.easeInOut;"
                            data-mask_in="x:[-100%];y:0;s:inherit;e:inherit;" data-splitin="none" data-splitout="none"
                            data-responsive_offset="on" data-start="1500">
                            <h4>{!! $slide->content !!}</h4>
                        </div>
                        <div class="tp-caption tp-resizeme donation" data-x="center" data-hoffset="0" data-y="top"
                            data-voffset="550" data-transform_idle="o:1;"
                            data-transform_in="y:[100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;opacity:0;s:2000;e:Power4.easeInOut;"
                            data-transform_out="s:1000;e:Power3.easeInOut;s:1000;e:Power3.easeInOut;" data-splitin="none"
                            data-splitout="none" data-responsive_offset="on" data-start="3000">
                            <button class="btn btn-danger">Explore now<i class="fa fa-long-arrow-right"
                                    aria-hidden="true"></i></button>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    </section>

    <section class="service_area services-section-two">
        <div class="container">
            <div class="sec-title">
                <div class="title">We Deals In</div>
                <h2>Plywood &amp; hardware products</h2>
            </div>

            <div class="row clearfix">
                @php $icons = ['fa-shopping-basket', 'fa-cutlery', 'fa-check']; @endphp
                @foreach($services as $i => $service)
                    <div class="service-block-two col-md-4 col-sm-6 col-xs-12">
                        <div class="inner-outer">
                            <div class="inner-box">
                                <div class="icon-box">
                                    <i class="fa {{ $icons[$i] ?? 'fa-check' }}"></i>
                                </div>
                                <h3><a href="javascript:void(0)">{{ $service->title }}</a></h3>
                                <div class="text">{!! $service->content !!}</div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <div class="clearfix"></div>

    <section class="portfolio_area">
        <div class="container">
            <div class="portfoli">
                <div class="portfolio">
                    <h2>Our Gallery</h2>
                    <ul class="gallery_filter text-center">
                        <li data-filter=".all" class="gallery_sorter active"><span>All</span></li>
                        @foreach($galleryCategories as $category)
                            <li data-filter=".{{ $category->id }}" class="gallery_sorter"><span>{{ $category->title }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="row">
                <div class="portfolio_image_gallery" data-filter-class="gallery_sorter">
                    @foreach($galleryCategories as $category)
                        @foreach($category->previewDetails as $item)
                            <div class="col-md-4 col-sm-4 col-xs-12 span-3 mix all {{ $category->id }}">
                                <div class="item_gallery">
                                    <img src="{{ $item->thumb_url ?? $item->image_url }}" alt="">
                                    <div class="overlay">
                                        <div class="box">
                                            <div class="image-view">
                                                <a class="fancybox" href="{{ $item->image_url }}"><i class="fa fa-eye"
                                                        aria-hidden="true"></i></a>
                                                <p>{{ $category->title }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <section class="dropex-section our-process-2">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="section-head b-align-center">
                        <span class="small-title">Our Process</span>
                        <h2 class="head-title">Process steps</h2>
                    </div>
                </div>
            </div>
            <div class="row row_process_2">
                <div class="col-lg-3 col-md-6 col-sm-6 col-12 col-process">
                    <div class="wrap_process_2 b-align-center"><span class="process_icon"><i
                                class="fa fa-lightbulb-o"></i></span>
                        <h3>Idea</h3>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12 col-process">
                    <div class="wrap_process_2 b-align-center"><span class="process_icon"><i
                                class="fa fa-pencil"></i></span>
                        <h3>Design</h3>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12 col-process">
                    <div class="wrap_process_2 b-align-center"><span class="process_icon"><i
                                class="fa fa-building"></i></span>
                        <h3>Construct</h3>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12 col-process">
                    <div class="wrap_process_2 b-align-center"><span class="process_icon"><i class="fa fa-home"></i></span>
                        <h3>Delivery</h3>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="counter_area">
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-sm-3 col-xs-12">
                    <div class="counter-text"><i class="fa fa-user"></i>
                        <h2 class="sF-counter" data-from="0" data-to="1200" data-speed="3000" data-refresh-interval="50">
                            1200</h2>
                        <p>Happy Clients</p>
                    </div>
                </div>
                <div class="col-md-3 col-sm-3 col-xs-12">
                    <div class="counter-text"><i class="fa fa-home"></i>
                        <h2 class="sF-counter" data-from="0" data-to="1600" data-speed="3000" data-refresh-interval="50">
                            1600</h2>
                        <p>Project Done</p>
                    </div>
                </div>
                <div class="col-md-3 col-sm-3 col-xs-12">
                    <div class="counter-text"><i class="fa fa-file-o"></i>
                        <h2 class="sF-counter" data-from="0" data-to="100" data-speed="3000" data-refresh-interval="50">100
                        </h2>
                        <p>Products</p>
                    </div>
                </div>
                <div class="col-md-3 col-sm-3 col-xs-12">
                    <div class="counter-text"><i class="fa fa-trophy"></i>
                        <h2 class="sF-counter" data-from="0" data-to="17" data-speed="3000" data-refresh-interval="50">17
                        </h2>
                        <p>years of experience</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section-gallery">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="section-heading text-center mb-75">
                        <h2 class="section-title"><span class="colored">Our Products</span></h2>
                        <div class="divider"><img src="{{ asset('front/img/slider/divider.png') }}" alt="Divider Image">
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                @foreach($productCategories as $category)
                    <div class="col-md-4 col-sm-6">
                        <div class="gallery-item img-wrapper">
                            <figure>
                                <img src="{{ asset('front/img/slider/promo1.jpg') }}" alt="">
                            </figure>
                            <div class="details">
                                <div class="border-frame">
                                    <img src="{{ asset('front/img/corner-left.png') }}" alt="image" class="corner-left">
                                    <img src="{{ asset('front/img/corner-right.png') }}" alt="image" class="corner-right">
                                </div>
                                <div class="inner">
                                    <span class="icon">
                                        <img src="{{ $category->image_url ?? asset('front/img/slider/promo1.jpg') }}"
                                            alt="Icon Image">
                                    </span>
                                    <p class="title">{{ $category->title }}</p>
                                    <a href="{{ route('products', ['cat_id' => $category->id]) }}"
                                        class="btn-primary btn-md btn-rad">Read more</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    
        <section class="testimonials-section">
            <div class="auto-container">
                <div class="ms-staff-carousel ms-round">

                    @if(session('testimonial_success'))
                        <div class="alert alert-success text-center">{{ session('testimonial_success') }}</div>
                    @endif
@if($testimonials->count() > 0)
                    <div class="master-slider slider" id="testimonials-one">
                        @foreach($testimonials as $testimonial)
                            <div class="ms-slide">
                                <img class="img-circle" src="{{ asset('front/css/style/blank.gif') }}"
                                    data-src="{{ $testimonial->image_url ?? asset('front/img/logo.png') }}" alt="Avatar">
                                <div class="ms-info">
                                    <div class="slide-content">{!! $testimonial->content !!}</div>
                                    <header class="slide-header">
                                        <h4>{{ $testimonial->title }}</h4>
                                        <p>Founder</p>
                                    </header>
                                </div>
                            </div>
                        @endforeach
                    </div>
                       @endif
                    <div class="ms-staff-info" id="staff-info"></div>
                    <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#feedback">Send
                        Feedback</button>
                </div>
            </div>
        </section>
 

    <div class="brand_area">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="owl-demo-brand">
                        @foreach($clients as $client)
                            <div class="single-item">
                                <a href="#"><img src="{{ $client->image_url }}" alt="Image"></a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="feedback" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('testimonial.submit') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Send Feedback</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="label-control" for="projectinput1">Name*</label>
                                <input type="text" class="form-control" placeholder="Enter Full Name" name="title" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="label-control" for="projectinput1">Email Address*</label>
                                <input type="email" class="form-control" placeholder="Enter Email Address" name="email"
                                    required pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,5}$">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="label-control">Upload Image*</label>
                                <input type="file" class="form-control" name="image">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="label-control">Message*</label>
                                <textarea class="form-control" name="content" rows="6" required></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection