<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Gallery;
use App\Models\OurService;
use App\Models\ProductCategory;
use App\Models\Slider;
use App\Models\Testimonial;
use App\Models\AboutUs;
use App\Models\Brand;
use App\Models\ProductDetail;
use Illuminate\Http\Request;
use App\Mail\EnquiryReceived;
use App\Services\ThumbnailService;
use Illuminate\Support\Facades\Mail;

class FrontController extends Controller
{
    public function home()
    {
        $sliders = Slider::where('status', 'active')->latest()->get();

        $services = OurService::where('status', 'active')->latest()->take(3)->get();

        $galleryCategories = Gallery::where('status', 'active')
            ->orderBy('id')
            ->get()
            ->map(function ($category) {
                $category->setRelation('previewDetails', $category->details()
                    ->where('status', 'active')
                    ->latest()
                    ->take(2)
                    ->get());
                return $category;
            });

        $productCategories = ProductCategory::where('status', 'active')->orderBy('id')->get();

        $testimonials = Testimonial::where('status', 'active')->latest()->take(10)->get();

        $clients = Client::where('status', 'active')->latest()->get();

        return view('front.home', compact(
            'sliders',
            'services',
            'galleryCategories',
            'productCategories',
            'testimonials',
            'clients'
        ));
    }

    public function about()
    {
        $about = AboutUs::where('status', 'active')->first();

        $productCategories = ProductCategory::where('status', 'active')
            ->orderBy('id')
            ->get();

        $clients = Client::where('status', 'active')->latest()->get();

        return view('front.about', compact('about', 'productCategories', 'clients'));
    }

    public function products(Request $request)
    {
        $categories = ProductCategory::where('status', 'active')
            ->orderBy('id')
            ->withCount([
                'details' => function ($q) {
                    $q->where('status', 'active');
                }
            ])
            ->get();

        $brands = Brand::where('status', 'active')->orderBy('title')->get();

        $query = ProductDetail::with('brand')->where('status', 'active');

        if ($request->filled('cat_id')) {
            $query->where('product_category_id', $request->cat_id);
        }

        if ($request->filled('brand_id')) {
            $query->where('brand_id', $request->brand_id);
        }

        $products = $query->orderBy('id')->paginate(18)->withQueryString();

        return view('front.products', compact('categories', 'brands', 'products'));
    }

    public function gallery()
    {
        $categories = Gallery::where('status', 'active')
            ->orderBy('id')
            ->get()
            ->map(function ($category) {
                $category->setRelation('activeDetails', $category->details()
                    ->where('status', 'active')
                    ->get());
                return $category;
            });

        return view('front.gallery', compact('categories'));
    }

    public function locateUs()
    {
        return view('front.locate-us');
    }

    public function enquiry()
    {
        $categories = ProductCategory::where('status', 'active')->orderBy('id')->get();
        $brands = Brand::where('status', 'active')->orderBy('title')->get();
        $products = ProductDetail::where('status', 'active')->get();

        return view('front.enquiry', compact('categories', 'brands', 'products'));
    }

    public function sendEnquiry(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phn_no' => 'required|digits:10',
            'category' => 'nullable|exists:product_categories,id',
            'brand' => 'nullable|string',
            'product' => 'nullable|string',
            'msg' => 'nullable|string',
        ]);

        $categoryName = $request->category
            ? ProductCategory::find($request->category)?->title
            : '';

        Mail::to('srihariharplyandhardware@gmail.com')->send(new EnquiryReceived([
            'name' => $request->name,
            'email' => $request->email,
            'phn_no' => $request->phn_no,
            'category_name' => $categoryName,
            'brand_name' => $request->brand,
            'product' => $request->product,
            'msg' => $request->msg,
        ]));

        return redirect()->route('enquiry')->with('enquiry_success', 'Thank you for contacting us. We will get back to you within 24 hours.');
    }

    public function submitTestimonial(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'email' => 'required|email',
            'content' => 'required|string',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = [
            'title' => $request->title,
            'email' => $request->email,
            'content' => $request->content,
            'status' => 'active',
        ];

        if ($request->hasFile('image')) {
            $stored = $request->file('image')->store('testimonial', 'public');
            ThumbnailService::make($stored, 100, 100);
            $data['image'] = $stored;
        }

        Testimonial::create($data);

        return redirect()->route('home')->with('testimonial_success', 'Thank you for your feedback!');
    }

}