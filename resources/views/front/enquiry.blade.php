@extends('layouts.front')

@section('title', 'Send Enquiry')

@section('content')

<style type="text/css">
.subcat option{ display:none; }
.subcat option.label{ display:block; }
.profile-circle img { border-radius: 50%; width: 100px; height: 100px; }
</style>

<section class="slider">
     <div class="container">
         <div class="wrapper">
             <h2>Send Enquiry</h2>
         </div>
    </div>
</section>

<section class="contact-map-area">
    <div class="container">
        <div class="row">
            <div class="contact-map">
            <form method="post" action="{{ route('enquiry.send') }}" enctype="multipart/form-data">
              @csrf
              <p style="padding:5px 0; color: #36B3E2;" align="center"><strong>{{ session('enquiry_success') }}</strong></p>
            <div class="col-md-12 col-sm-12 col-xs-12">
               <div class="form-group row">
                 <div class="col-md-6">
                <label class="label-control" for="projectinput1">Full Name* </label>
                    <input type="text" id="projectinput1" class="form-control1" placeholder="Enter Full Name" name="name" required data-validation-required-message="This field is required" pattern="[a-zA-Z\s]+" value="{{ old('name') }}">
                 </div>
                <div class="col-md-6">
                <label class="label-control" for="projectinput1">Email Address* </label>
                    <input type="text" id="projectinput1" class="form-control1" placeholder="Enter Email Address" name="email" required data-validation-required-message="This field is required" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,5}$" value="{{ old('email') }}">
                </div>
               </div>

                <div class="form-group row">
                 <div class="col-md-6">
                <label class="label-control" for="projectinput1">Phone Number* </label>
                    <input type="text" id="projectinput1" class="form-control1" placeholder="mob" name="phn_no" required data-validation-required-message="This field is required" pattern="[789][0-9]{9}" data-toggle="tooltip" title="Enter 10 Digit Mobile Number!" value="{{ old('phn_no') }}">
                 </div>
                <div class="col-md-6">
                <label class="label-control" for="projectinput1">Product Category </label>
                    <select class="form-control1" name="category" id="category1">
                        <option value="">Select Product Category</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->title }}</option>
                        @endforeach
                    </select>
                </div>
               </div>

               <div class="form-group row">
                 <div class="col-md-6">
                <label class="label-control" for="projectinput1">Brand </label>
                   <select class="form-control1" name="brand">
                        <option value="">Select Brand</option>
                        @foreach($brands as $brand)
                        <option value="{{ $brand->title }}">{{ $brand->title }}</option>
                        @endforeach
                    </select>
                 </div>
                <div class="col-md-6">
                <label class="label-control" for="projectinput1">Product </label>
                    <select class="form-control1 subcat" name="product" id="category2">
                        <option value="">Select Product</option>
                        @foreach($products as $product)
                        <option rel="{{ $product->product_category_id }}">{{ $product->title }}</option>
                        @endforeach
                    </select>
                </div>
               </div>

                <div class="form-group row">
                 <div class="col-md-12">
                    <label class="label-control" for="projectinput1">Message </label>
                    <textarea class="form-control" rows="6" cols="30" name="msg">{{ old('msg') }}</textarea>
                 </div>
               </div>

                <div class="form-group row">
                 <div class="col-md-12 text-right">
                    <button type="submit" name="submit" class="btn btn-one mr">Send Enquiry</button>
                 </div>
                </div>
            </div>
            </form>
            </div>

        </div>
    </div>
</section>

<section class="contact-ser-area1">
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-sm-4 col-xs-12">
                <div class="contact-ser">
                   <i class="fa fa-mobile" aria-hidden="true"></i>
                    <h2>Phone Number</h2>
                    <p><a href="tel:9125776666" class="cont">+91-912-577-6666</a> <br> <img src="https://cdn0.iconfinder.com/data/icons/social-flat-rounded-rects/512/whatsapp-256.png" class="whatsapp"><a href="tel:9026187442" class="cont">+91-902-618-7442</a></p>
                </div>
            </div>
            <div class="col-md-4 col-sm-4 col-xs-12">
                <div class="contact-ser">
                   <i class="fa fa-envelope-o" aria-hidden="true"></i>
                    <h2>Email Us</h2>
                    <p>srihariharplyandhardware@gmail.com</p>
                </div>
            </div>
            <div class="col-md-4 col-sm-4 col-xs-12">
                <div class="contact-ser">
                   <i class="fa fa-map-marker" aria-hidden="true"></i>
                    <h2>Location</h2>
                    <p>Add: 598A/W58(03LA) Mausam Bagh, Triveni Nagar II
Sitapur Road, Opp Balaji Lawn Lucknow - 226020</p>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@section('scripts')
<script type="text/javascript">
$(function(){
    var $cat = $("#category1"),
        $subcat = $(".subcat");

    $cat.on("change",function(){
        var _rel = $(this).val();
        $subcat.find("option").attr("style","");
        $subcat.val("");
        if(!_rel) return $subcat.prop("disabled",true);
        $subcat.find("[rel="+_rel+"]").show();
        $subcat.prop("disabled",false);
    });
});
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();
});
</script>
@endsection