<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\category;
use App\Models\Product;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request, $categorySlug = null, $subCategorySlug = null)
    {
        $categorySelected = '';
        $subCategorySelected = '';

        $categories = category::orderBy('name','ASC')->with('sub_category')->where('status',1)->withCount('NoOfProducts')->get();
        // dd($categories);
        $brands = Brand::orderBy('name','ASC')->where('status',1)->get();
        
        $products = Product::where('status',1);
        //Apply filters here
        if(!empty($categorySlug)){
            $category = category::where('slug',$categorySlug)->first();
            $products = $products->where('category_id',$category->id);
            $categorySelected = $category->id;
        }
        if(!empty($subCategorySlug)){
            $subCategory = SubCategory::where('slug',$subCategorySlug)->first();
            $products = $products->where('sub_category_id',$subCategory->id);
            $subCategorySelected = $subCategory->id;
        }
        
        //Sorting filters
        if($request->get('sort') != ''){
            if($request->get('sort') == 'latest'){
                $products = $products->orderBy('id','DESC');
            } else if($request->get('sort') == 'price_asc'){
                $products = $products->orderBy('price','ASC');
            } else{
                $products = $products->orderBy('price','DESC');
            }
        }else{
            $products = $products->orderBy('id','DESC');
        }

        $products = $products->paginate(6);

        $data['products'] = $products;
        $data['categories'] = $categories;
        $data['brands'] = $brands;
        $data['sort'] = $request->get('sort');
        $data['categorySelected'] = $categorySelected;
        $data['subCategorySelected'] = $subCategorySelected;

        return view('front.shop',$data);
    }

    public function product($slug){
        $product = Product::where('slug',$slug)->first();
        
        // dd($product);
        if($product == null){
            abort(404);
        }

        $featuredProducts = Product::where('is_featured','Yes')->get();
        
        $data['featuredProducts'] = $featuredProducts;
        $data['product'] = $product;
        return view('front.product',$data);
    }

}
