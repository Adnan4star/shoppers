<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\category;
use App\Models\Product;
use App\Models\ProductRating;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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

        // Home search filtering
        if (!empty($request->get('search'))) {
            $products = $products->where('title', 'like','%'.$request->get('search').'%');

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
        $product = Product::where('slug',$slug)  // defined relation in product model
                ->withCount('product_ratings') // defined relation in product model
                ->withSum('product_ratings','rating') // defined relation in product model
                ->with(['product_ratings']) // defined relation in product model
                ->first();
        // dd($product);
        if($product == null){
            abort(404);
        }

        // Rating calculation
        // For product reviews on product detail page
        $avgRating = '0.00';
        $avgRatingPercent = '0.00';

        if ($product->product_ratings_count > 0) {
            $avgRating = number_format($product->product_ratings_sum_rating / $product->product_ratings_count,2); // These dividents are coming from product query, as we defined relation of product_ratings with product model
            $avgRatingPercent = ($avgRating*100)/5;
        }

        $featuredProducts = Product::where('is_featured','Yes')->get();
        
        $data['featuredProducts'] = $featuredProducts;
        $data['product'] = $product;
        $data['avgRating'] = $avgRating;
        $data['avgRatingPercent'] = $avgRatingPercent;

        return view('front.product',$data);
    }

    // Rating Form submit
    public function saveRating(Request $request, $id)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|min:5',
            'email' => 'required|email',
            'comment' => 'required|min:10',
            'rating' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        } 

        $count = ProductRating::where('email',$request->email)->count();
        
        if ($count > 0) {
            session()->flash('error','You have already rated this product!');
            return response()->json([
                'status' => true,
            ]);
        }
        $productRating = new ProductRating;
        $productRating->product_id = $id;
        $productRating->username = $request->name;
        $productRating->email = $request->email;
        $productRating->comment = $request->comment;
        $productRating->rating = $request->rating;
        $productRating->status = 0;
        $productRating->save();

        session()->flash('success','Thanks for your rating');
        return response()->json([
            'status' => true,
            'message' => 'Thanks for your rating',
        ]);
    }
}
