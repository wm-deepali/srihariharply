@extends('layouts.front')

@section('title', 'Our Products')

@section('content')

<section class="slider">
     <div class="container">
         <div class="wrapper">
             <h2>Our Products</h2>
         </div>
    </div>
</section>

<section class="news_area">
    <div class="container">
        <div class="row">
            <div class="col-md-3 col-sm-4 col-xs-12">
                <div class="categories">
                    <h2>Categories</h2>
                    <ul>
                        @foreach($categories as $category)
                        <li>
                            <a href="{{ route('products', ['cat_id' => $category->id]) }}" class="{{ request('cat_id') == $category->id ? 'active' : '' }}">
                                {{ $category->title }}
                                <span>{{ $category->details_count }}</span>
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="col-md-9 col-sm-8 col-xs-12">
                <div class="row">
                    <div class="col-md-12 col-xs-12">
                        <div class="form-group row">
                            <div class="col-md-6 pull-right">
                                <form method="get" action="{{ route('products') }}" name="myform">
                                    @if(request('cat_id'))
                                        <input type="hidden" name="cat_id" value="{{ request('cat_id') }}">
                                    @endif
                                    <select class="form-control1" name="brand_id" onchange="myform.submit();">
                                        <option value="">Select Brand</option>
                                        @foreach($brands as $brand)
                                        <option value="{{ $brand->id }}" {{ request('brand_id') == $brand->id ? 'selected' : '' }}>{{ $brand->title }}</option>
                                        @endforeach
                                    </select>
                                </form>
                            </div>
                        </div>
                    </div>

                    @forelse($products as $product)
                    <div class="col-sm-4 col-xs-6">
                        <article class="blog-item bg-gray">
                            <div class="blog-image">
                                <a href="#"><img src="{{ $product->thumb_url ?? $product->image_url ?? '' }}" alt=""></a>
                            </div>
                            <div class="blog-info">
                                <div class="post-title-time">
                                    <h5><a href="#">{{ $product->title }}</a></h5>
                                    <p>Brand : {{ $product->content }}</p>
                                    <p>Product Code : {{ $product->brand->title ?? '' }}</p>
                                </div>
                                <a class="read-more" href="{{ route('enquiry') }}">Enquiry Now</a>
                            </div>
                        </article>
                    </div>
                    @empty
                    <div class="col-xs-12">
                        <p style="padding:20px 0;">No products found.</p>
                    </div>
                    @endforelse

                </div>

                <div style="padding:20px 0;">
                    {{ $products->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
</section>

@endsection