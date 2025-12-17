<?php
namespace App\Http\Controllers;


use App\Models\Blog;
use App\Models\Product;
use App\Models\Service;
use App\Models\Career;

class SitemapController extends Controller
{
    public function index()
    {
        $blogs = Blog::all();
        $products = Product::all();
        $services = Service::all();
        $careers = Career::all();

        $content = view('sitemap', compact('blogs', 'products', 'services', 'careers'));

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n" . $content;
        return response($xml, 200)
                 ->header('Content-Type', 'application/xml');
    }
}
