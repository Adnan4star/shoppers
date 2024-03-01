<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Product;
use Illuminate\Http\Request;

class FrontController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::where('is_featured','Yes')
                ->orderBy('id','DESC')
                ->where('status',1)
                ->get();
        $data['featuredProducts'] = $featuredProducts;
        //if using latest products section home page
        // $latestProducts = Product::orderBy('id','ASC')->where('status',1)->take(8)->get();
        // $data['latestProducts'] = $latestProducts;
        return view('front.home',$data);
    }

    public function page($slug)
    {
        $page = Page::where('slug',$slug)->first();
        
        $data['page'] = $page;

        return view('front.page',$data);
    }
}
