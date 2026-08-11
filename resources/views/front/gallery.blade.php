@extends('layouts.front')

@section('title', 'Our Gallery')

@section('content')

<section class="slider">
     <div class="container">
         <div class="wrapper">
             <h2>Our Gallery</h2>
         </div>
    </div>
</section>

<div class="clearfix"></div>

<section>
    <div class="container">
        <div class="portfoli">
            <div class="portfolio">
                <h2>Our Gallery</h2>
                <ul class="gallery_filter text-center">
                    <li data-filter=".all" class="gallery_sorter active">
                        <span>All</span>
                    </li>
                    @foreach($categories as $category)
                    <li data-filter=".{{ $category->id }}" class="gallery_sorter">
                        <span>{{ $category->title }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
        <div class="row">
            <div class="portfolio_image_gallery" data-filter-class="gallery_sorter">
                @foreach($categories as $category)
                    @foreach($category->activeDetails as $item)
                    <div class="col-md-4 col-sm-4 col-xs-12 span-3 mix all {{ $category->id }}">
                        <div class="item_gallery">
                            <img src="{{ $item->thumb_url ?? $item->image_url }}" alt="">
                            <div class="overlay">
                                <div class="box">
                                    <div class="image-view">
                                     <a class="fancybox" href="{{ $item->image_url }}"><i class="fa fa-eye" aria-hidden="true"></i></a>
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

@endsection