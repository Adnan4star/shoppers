<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    // Add to wishlist
    public function addToWishlist(Request $request)
    {
        // Checking if user is not logged in!
        if (Auth::check() == false) {

            session(['url.intended' => url()->previous()]); // saving intended url for users to redirect them back after logging in 

            return response()->json([
                'status' => false,
            ]);
        }
        
        // Product query to fetch product name to return to response 
        $product = Product::where('id',$request->id)->first();
        if ($product == null) {
            return response()->json([
                'status' => true,
                'message' => '<div class="alert alert-danger">Product not found!</div>',
            ]);
        }
        
        // First array checks if record already exists it skips and updates in 2nd array
        Wishlist::updateOrCreate(
            [
                'user_id' => Auth::user()->id,
                'product_id' => $request->id,
            ],
            [
                'user_id' => Auth::user()->id,
                'product_id' => $request->id,
            ]
        );

        return response()->json([
            'status' => true,
            'message' => '<div class="alert alert-success"><strong>"'.$product->title.'"</strong> added in your wishlist</div>',
        ]);
    }
}
