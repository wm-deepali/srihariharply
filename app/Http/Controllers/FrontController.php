<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Brand;
use App\Models\Category;
use App\Models\ContactBranch;
use App\Models\ContactEnquiry;
use App\Models\DynamicPage;
use App\Models\Faq;
use App\Models\GiftingOccasion;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Team;
use App\Models\GeneralEnquiry;
use App\Models\SupplierEnquiry;
use Illuminate\Support\Facades\Validator;
use App\Models\HeroSection;
use App\Models\BannerSection;
use App\Models\TestimonialSection;
use App\Models\AudioSection;
use App\Models\Collection;
use App\Models\Setting;
use App\Models\Attribute;
use App\Models\AttributeValue;
use Illuminate\Support\Str;

class FrontController extends Controller
{

    public function home()
    {
        $hero = HeroSection::firstOrCreate([], [
            'heading_line1' => "LUXURY ISN'T LOUDER.",
            'heading_line2' => "IT'S BETTER MADE",
            'btn1_label' => 'Shop All',
            'btn1_url' => '/shop',
            'btn2_label' => 'New Arrivals',
            'btn2_url' => '/shop',
        ]);

        $banner = BannerSection::firstOrCreate([], [
            'heading' => 'Rooted in culture. Designed for today.',
        ]);

        $testimonial = TestimonialSection::firstOrCreate([], [
            'quote_line1' => 'A very beautiful way',
            'quote_line2' => 'to start the day!',
            'author' => 'Bilal Khilji',
        ]);

        $audio = AudioSection::firstOrCreate([], [
            'heading' => 'THE FRAGRANCE OF RESTRAINT.',
        ]);

        $featuredCategories = Category::where('is_featured', 1)
            ->whereNull('parent_id')
            ->where('status', 1)
            ->orderBy('sort_order')
            ->take(2)
            ->get();

        $featuredProducts = Product::with([
            'images' => fn($q) => $q->whereIn('image_type', ['default', 'hover'])
        ])
            ->where('status', 1)
            ->latest()
            ->take(8)
            ->get();

        return view('front-pages.home', compact(
            'hero',
            'banner',
            'testimonial',
            'audio',
            'featuredCategories',
            'featuredProducts'
        ));
    }

    public function searchSuggestions(Request $request)
    {
        $query = trim($request->q);

        if (!$query) {
            return response()->json([]);
        }

        $products = Product::with('images')
            ->visible()
            ->where('name', 'LIKE', "%{$query}%")
            ->take(5)
            ->get([
                'id',
                'name',
                'slug'
            ])
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'image' => $product->display_image, // accessor
                ];
            });

        // Parent Categories
        $categories = Category::whereNull('parent_id')
            ->where('status', 1)
            ->where('name', 'LIKE', "%{$query}%")
            ->take(5)
            ->get([
                'id',
                'name',
                'slug',
                'image'
            ]);

        // Sub Categories
        $subCategories = Category::with('parent')
            ->whereNotNull('parent_id')
            ->where('status', 1)
            ->where('name', 'LIKE', "%{$query}%")
            ->take(5)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'slug' => $item->slug,
                    'image' => $item->image,
                    'parent_slug' => $item->parent?->slug,
                ];
            });



        return response()->json([
            'products' => $products,
            'categories' => $categories,
            'subcategories' => $subCategories,
        ]);
    }

    public function shopAll(Request $request)
    {
        $subcategories = Category::where('status', 1)
            ->whereNull('parent_id')
            ->orderBy('name')
            ->get();

        $query = Product::with([
            'images' => function ($q) {
                $q->whereIn('image_type', ['default', 'hover']);
            }
        ])->where('status', 1);

        if ($request->filled('subcategory')) {
            $query->where('category_id', $request->subcategory);
        }

        match ($request->get('sort')) {
            'price-asc' => $query->orderBy('price', 'asc'),
            'price-desc' => $query->orderBy('price', 'desc'),
            default => $query->orderBy('id', 'desc'),
        };

        $products = $query->get();

        $category = (object) [
            'name' => 'Shop All',
            'description' => null,
            'horizontal_image' => null,
        ];

        $isShopAll = true; // ← flag

        return view('front-pages.products', compact(
            'category',
            'subcategories',
            'products',
            'isShopAll'
        ));
    }

    public function category(Category $category, Request $request)
    {
        $subcategories = Category::where('parent_id', $category->id)
            ->where('status', 1)
            ->orderBy('name')
            ->get();

        $query = Product::with([
            'images' => function ($q) {
                $q->whereIn('image_type', ['default', 'hover']);
            }
        ])
            ->where('category_id', $category->id)
            ->where('status', 1);

        // Apply subcategory filter if passed
        if ($request->filled('subcategory')) {
            $query->where('subcategory_id', $request->subcategory);
        }

        // Apply sort
        match ($request->get('sort')) {
            'price-asc' => $query->orderBy('price', 'asc'),
            'price-desc' => $query->orderBy('price', 'desc'),
            default => $query->orderBy('id', 'desc'),
        };

        $products = $query->get();
        $isShopAll = false;

        return view('front-pages.products', compact('category', 'subcategories', 'products', 'isShopAll'))
            ->with('activeSubcategory', $request->filled('subcategory') ? (string) $request->subcategory : null);
    }

    public function collection(Collection $collection, Request $request)
    {
        $query = $collection->products()
            ->with([
                'images' => function ($q) {
                    $q->whereIn('image_type', ['default', 'hover']);
                }
            ])
            ->where('status', 1);

        match ($request->get('sort')) {
            'price-asc' => $query->orderBy('price', 'asc'),
            'price-desc' => $query->orderBy('price', 'desc'),
            default => $query->orderBy('id', 'desc'),
        };

        $products = $query->get();

        $category = (object) [
            'name' => $collection->name,
            'description' => $collection->description ?? null,
            'horizontal_image' => $collection->image ?? null,
        ];

        $subcategories = collect();
        $isShopAll = false;

        return view('front-pages.products', compact('category', 'subcategories', 'products', 'isShopAll'))
            ->with('activeSubcategory', null);
    }


   public function productDetail(Product $product)
{
    // Abort if product is not active
    abort_if(!$product->status, 404);

    // Load all image types
    $product->load([
        'images',
        'category',
        'subcategory',
    ]);

    // Similar products: same category, excluding current
    $similarProducts = Product::with([
        'images' => function ($q) {
            $q->whereIn('image_type', ['default', 'hover']);
        }
    ])
        ->where('id', '!=', $product->id)
        ->where('status', 1)
        ->latest()
        ->take(8)
        ->get();

    $faqs = Faq::where('show_on_product_page', 1)
        ->where('status', 1)
        ->get();

    $cartItem = null;

    if (auth('customer')->check()) {
        $cart = \App\Models\Cart::where('user_id', auth('customer')->id())->first();

        if ($cart) {
            $cartItem = $cart->items()
                ->where('product_id', $product->id)
                ->first();
        }
    } else {
        $sessionCart = session('cart.items', []);

        $cartItem = collect($sessionCart)
            ->firstWhere('product_id', $product->id);
    }

    $viewItemScript = \App\Services\Tracking\PixelTracker::viewItemScript($product); // 👈 new

    return view('front-pages.product-detail', compact('product', 'similarProducts', 'faqs', 'cartItem', 'viewItemScript'));
}

    public function faqs(Request $request)
    {
        $faqs = Faq::where('status', 1)->get();

        return view('front-pages.faqs', compact('faqs'));
    }

    public function blogs(Request $request)
    {
        $lang = $request->get('lang', 'en');

        $blogs = Blog::where('status', 1)
            ->where('language', $lang)
            ->latest()
            ->get();

        return view('front-pages.blogs', compact('blogs', 'lang'));
    }

    public function blogDetails($slug)
    {
        $blog = Blog::where('slug', $slug)
            ->where('status', 1)
            ->firstOrFail();

        $relatedBlogs = Blog::where('status', 1)
            ->where('language', $blog->language)
            // ->where('id', '!=', $blog->id)
            ->latest()
            ->take(2)
            ->get();

        return view('front-pages.blog-details', compact('blog', 'relatedBlogs'));
    }

    public function contactUs()
    {
        $branches = ContactBranch::where('status', 1)->get();

        return view('front-pages.contact-us', compact('branches'));
    }

    public function dynamicPage($slug)
    {
        // match slug with page_name
        $page = DynamicPage::where('status', 1)
            ->get()
            ->first(function ($p) use ($slug) {
                return Str::slug($p->page_name) === $slug;
            });

        if (!$page) {
            abort(404);
        }

        return view(
            'front-pages.dynamic-page'
            ,
            compact('page')
        );
    }

    public function whyUs(Request $request)
    {
        $brands = Brand::where('status', 1)->get();
        return view('front-pages.why-us', compact('brands'));
    }



    public function gallery(Request $request)
    {
        return view('front-pages.gallery');
    }



    public function bulkEnquiry(Request $request)
    {
        $categories = Category::where('status', 1)->whereNull('parent_id')->get();

        return view('front-pages.bulk-enquiry', compact('categories'));
    }

    public function aboutUs(Request $request)
    {
        $teams = Team::where('status', 1)
            ->latest()
            ->get();

        return view('front-pages.about', compact('teams'));
    }

    public function submitContact(Request $request)
    {
        $request->validate([
            'first_name' => [
                'required',
                'string',
                'min:3',
                'max:100',
                'regex:/^[a-zA-Z\s]+$/'
            ],

            'last_name' => [
                'required',
                'string',
                'min:2',
                'max:100',
                'regex:/^[a-zA-Z\s]+$/'
            ],

            'email' => [
                'required',
                'email:rfc,dns',
                'max:255'
            ],

            'mobile' => [
                'nullable',
                'digits:10',
                'regex:/^[6-9]\d{9}$/'
            ],

            'message' => [
                'required',
                'string',
                'min:10',
                'max:1000'
            ],

            'g-recaptcha-response' => [
                'required'
            ]

        ], [

            'first_name.required' => 'Please enter your first name.',
            'first_name.min' => 'First name must be at least 3 characters.',
            'first_name.max' => 'First name cannot exceed 100 characters.',
            'first_name.regex' => 'First name should contain only letters and spaces.',

            'last_name.required' => 'Please enter your last name.',
            'last_name.min' => 'Last name must be at least 2 characters.',
            'last_name.max' => 'Last name cannot exceed 100 characters.',
            'last_name.regex' => 'Last name should contain only letters and spaces.',

            'email.required' => 'Email is required.',
            'email.email' => 'Please enter a valid email address.',

            'mobile.digits' => 'Mobile number must be 10 digits.',
            'mobile.regex' => 'Please enter a valid Indian mobile number.',

            'message.required' => 'Message cannot be empty.',
            'message.min' => 'Message must contain at least 10 characters.',
            'message.max' => 'Message cannot exceed 1000 characters.',

            'g-recaptcha-response.required' => 'Please verify captcha.',
        ]);

        // Verify reCAPTCHA
        $captchaResponse = Http::asForm()->post(
            'https://www.google.com/recaptcha/api/siteverify',
            [
                'secret' => env('RECAPTCHA_SECRET_KEY'),
                'response' => $request->input('g-recaptcha-response'),
                'remoteip' => $request->ip(),
            ]
        );

        if (!($captchaResponse->json()['success'] ?? false)) {
            return back()
                ->withErrors([
                    'g-recaptcha-response' => 'Captcha verification failed. Please try again.'
                ])
                ->withInput();
        }

       ContactEnquiry::create([
    'first_name' => $request->first_name,
    'last_name' => $request->last_name,
    'email' => $request->email,
    'mobile' => $request->mobile,
    'message' => $request->message,
]);

return redirect()->back()->with(
    'success',
    'Thank you! Your enquiry has been submitted successfully.'
)->with('fire_lead_event', 'Contact Us Form'); // 👈 new

    }


    public function submitGeneralEnquiry(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'company' => 'required|string|max:255',
            'email' => 'required|email:rfc,dns|max:255',
            'phone' => 'required|regex:/^[6-9]\d{9}$/',
            'g-recaptcha-response' => 'required',
        ], [
            'name.required' => 'Please enter your name',
            'company.required' => 'Company name is required',
            'email.email' => 'Enter valid email address',
            'phone.regex' => 'Enter valid 10-digit mobile number',
            'g-recaptcha-response.required' => 'Please verify captcha',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator, 'generalForm') // ✅ IMPORTANT
                ->withInput();
        }

        // CAPTCHA
        $response = Http::asForm()->post(
            'https://www.google.com/recaptcha/api/siteverify',
            [
                'secret' => env('RECAPTCHA_SECRET_KEY'),
                'response' => $request->input('g-recaptcha-response'),
                'remoteip' => $request->ip()
            ]
        );

        if (!($response->json()['success'] ?? false)) {
            return back()
                ->withErrors(['captcha' => 'Captcha verification failed'], 'generalForm')
                ->withInput();
        }

      GeneralEnquiry::create([
    'name' => $request->name,
    'company' => $request->company,
    'email' => $request->email,
    'phone' => $request->phone,
    'message' => $request->message,
    'source' => $request->source,
]);

return back()
    ->with('success_general', 'Enquiry submitted successfully!')
    ->with('fire_lead_event', 'General Enquiry Form'); // 👈 new
    }


    public function submitSupplierEnquiry(Request $request)
    {
        $request->validate([
            'name' => 'required|string|min:3|max:255',
            'company' => 'required|string|min:2|max:255',
            'email' => 'required|email:rfc,dns|max:255',
            'phone' => 'required|digits:10|regex:/^[6-9]\d{9}$/',
            'category_id' => 'required|exists:categories,id',
            'quantity' => 'nullable|integer|min:1',
            'delivery_date' => 'nullable|date|after_or_equal:today',
            'city' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:1000',
            'catalogue' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',
            'g-recaptcha-response' => 'required',
        ], [
            'name.required' => 'Please enter your name',
            'company.required' => 'Company name is required',
            'email.email' => 'Enter valid email address',
            'phone.regex' => 'Enter valid 10-digit mobile number',
            'category_id.required' => 'Please select category',
            'catalogue.mimes' => 'File must be PDF, DOC, JPG or PNG',
            'catalogue.max' => 'File must be under 2MB',
            'g-recaptcha-response.required' => 'Please verify captcha',
        ]);

        // CAPTCHA
        $captcha = Http::asForm()->post(
            'https://www.google.com/recaptcha/api/siteverify',
            [
                'secret' => env('RECAPTCHA_SECRET_KEY'),
                'response' => $request->input('g-recaptcha-response'),
            ]
        );

        if (!$captcha->json('success')) {

            return back()
                ->withErrors([
                    'g-recaptcha-response' => 'Captcha failed'
                ])
                ->withInput();
        }

        // FILE UPLOAD
        $filePath = null;

        if ($request->hasFile('catalogue')) {

            $filePath = $request->file('catalogue')
                ->store('catalogues', 'public');
        }

       SupplierEnquiry::create([
    'name' => $request->name,
    'company' => $request->company,
    'email' => $request->email,
    'phone' => $request->phone,
    'category_id' => $request->category_id,
    'quantity' => $request->quantity,
    'delivery_date' => $request->delivery_date,
    'description' => $request->description,
    'city' => $request->city,
    'catalogue' => $filePath,
]);

return back()
    ->with('success', 'Bulk enquiry submitted successfully!')
    ->with('fire_lead_event', 'Bulk Enquiry Form'); // 👈 new
    
    }

    public function occasions(Request $request)
    {
        $occasions = GiftingOccasion::where('status', 1)
            ->latest()
            ->get();

        return view('front-pages.occasions', compact('occasions'));
    }


}