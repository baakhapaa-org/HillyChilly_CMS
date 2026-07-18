<?php
namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\{Blog, Faq, Package, Setting, Testimonial};

class HomeController extends Controller
{
    public function index()
    {
        $featuredPackages = Package::active()->featured()->latest()->take(6)->get();
        $allPackages      = Package::active()->latest()->take(6)->get();
        $testimonials     = Testimonial::where('is_visible', true)->orderBy('sort_order')->take(6)->get();
        $blogs            = Blog::where('is_published', true)->latest()->take(3)->get();
        $faqs             = Faq::where('is_visible', true)->orderBy('sort_order')->take(8)->get();

        return view('pages.home', compact('featuredPackages', 'allPackages', 'testimonials', 'blogs', 'faqs'));
    }

    public function about()   { return view('pages.about'); }
    public function contact() { return view('pages.contact'); }
}
