@extends('layouts.front')

@section('title', 'About Us')

@section('content')

<section class="slider">
     <div class="container">
         <div class="wrapper">
             <h2>About Us</h2>
         </div>
    </div>
</section>

<section class="wellcome_area">
    <div class="container">
        <div class="row">
			<div class="col-md-6 col-sm-6 col-xs-12">
                <div class="wellcome">
                    <h2>Welcome to<br><span>{{ $about->title ?? '' }}</span></h2>
                    {!! $about->content ?? '' !!}
                    @foreach($productCategories as $category)
                    <h6><i class="fa fa-check-circle-o" aria-hidden="true"></i> {{ $category->title }}</h6>
                    @endforeach

                    <a href="#" class="btn-one">View All Services</a>
                </div>
			</div>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="wellcome-2">
                   <img src="{{ $about->thumb_url ?? $about->image_url ?? '' }}" alt="Images">
                </div>
            </div>
		</div>
	</div>
</section>

<section class="counter_area">
    <div class="container">
        <div class="row">
			<div class="col-md-3 col-sm-3 col-xs-12">
                <div class="counter-text">
                   <h2 class="sF-counter" data-from="0" data-to="800" data-speed="3000" data-refresh-interval="50">800</h2>
                    <p>Happy Clients</p>
                </div>
            </div>
            <div class="col-md-3 col-sm-3 col-xs-12">
                <div class="counter-text">
                   <h2 class="sF-counter" data-from="0" data-to="1500" data-speed="3000" data-refresh-interval="50">1500</h2>
                    <p>Project Done</p>
                </div>
            </div>
            <div class="col-md-3 col-sm-3 col-xs-12">
                <div class="counter-text">
                   <h2 class="sF-counter" data-from="0" data-to="25" data-speed="3000" data-refresh-interval="50">25</h2>
                    <p>Products</p>
                </div>
            </div>
            <div class="col-md-3 col-sm-3 col-xs-12">
                <div class="counter-text">
                   <h2 class="sF-counter" data-from="0" data-to="16" data-speed="3000" data-refresh-interval="50">16</h2>
                    <p>years of experience</p>
                </div>
            </div>
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

@endsection