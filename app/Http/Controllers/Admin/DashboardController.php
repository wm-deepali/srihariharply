<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Client;
use App\Models\GalleryDetail;
use App\Models\OurService;
use App\Models\ProductCategory;
use App\Models\ProductDetail;
use App\Models\Slider;
use App\Models\Testimonial;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'products' => ProductDetail::count(),
            'categories' => ProductCategory::count(),
            'brands' => Brand::count(),
            'galleryImages' => GalleryDetail::count(),
            'clients' => Client::count(),
            'testimonials' => Testimonial::count(),
            'services' => OurService::count(),
            'sliders' => Slider::count(),
        ];

        $activeCounts = [
            'products' => ProductDetail::where('status', 'active')->count(),
            'categories' => ProductCategory::where('status', 'active')->count(),
            'gallery' => GalleryDetail::where('status', 'active')->count(),
            'testimonials' => Testimonial::where('status', 'active')->count(),
        ];

        $topCategories = ProductCategory::withCount('details')
            ->orderByDesc('details_count')
            ->take(5)
            ->get();

        $recentTestimonials = Testimonial::latest()->take(5)->get();

        $monthly = ProductDetail::selectRaw("DATE_FORMAT(created_at, '%Y-%m') as ym, DATE_FORMAT(created_at, '%b') as label, COUNT(*) as total")
            ->where('created_at', '>=', now()->subMonths(5)->startOfMonth())
            ->groupBy('ym', 'label')
            ->orderBy('ym')
            ->get();

        return view('admin.dashboard.index', compact(
            'stats',
            'activeCounts',
            'topCategories',
            'recentTestimonials',
            'monthly'
        ));
    }
}