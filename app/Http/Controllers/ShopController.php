<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\category;
use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request, $categorySlug = null,)
    {
        $categories = category::orderBy('name','ASC')->with('sub_category')->where('status',1)->get();
        $brands = Brand::orderBy('name','ASC')->where('status',1)->get();
        
        $products = Product::where('status',1);
        //Apply filters here
        if(!empty($categorySlug)){
            $category = category::where('slug',$categorySlug)->first();
            $products = $products->where('category_id',$category->id);

        }
        


        $products = $products->orderBy('id','DESC');
        $products = $products->get();

        $data['products'] = $products;
        $data['categories'] = $categories;
        $data['brands'] = $brands;

        return view('front.shop',$data);
    }
}
