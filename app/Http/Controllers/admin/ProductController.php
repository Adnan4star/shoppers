<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\category;
use App\Models\Product;
use App\Models\ProductRating;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::latest('id')->with('product_images');
        
        if($request->get('keyword') != ""){
            $products = $products->where('title','like','%'.$request->keyword. '%');
        }
        
        $products = $products->paginate(4);
        // dd($products);

        $data['products'] = $products;
        return view('admin.products.list',$data);
    }

    public function create()
    {
        $data = [];
        //fetching categories,subCategories and brands
        $categories = category::orderBy('name','ASC')->get();
        $brands = Brand::orderBy('name','ASC')->get();
        $subCategories = SubCategory::orderBy('name','ASC')->get();
        $data['categories'] = $categories;
        $data['brands'] = $brands;
        $data['subCategories'] = $subCategories;

        return view('admin.products.create',$data);
    }

    public function store(Request $request)
    {

        $rules = [
            'title' => 'required',
            'slug' => 'required|unique:products',
            'pricing' => 'required|numeric',
            'sku' => 'required|unique:products',
            'track_qty' => 'required|in:Yes,No', //enum fields accepts two value yes and no
            'category' => 'required|numeric',
            'is_featured' => 'required|in:Yes,No',
        ];
        if(!empty($request->track_qty) && $request->track_qty == 'Yes'){
            $rules['qty'] = 'required|numeric';
        }

        $Validator = Validator::make($request->all(),$rules);
        if($Validator->passes()){

            //save gallery pics
            $request->all();
            $filename = '';
            if($request->hasFile('image'))
            {
                $image = $request->file('image');
                $filename = $image->getClientOriginalName(); 
                $image->move('uploads/products',$filename);
            }

            $product = new Product;
            $product->title = $request->title;
            $product->slug = $request->slug;
            $product->description = $request->description;
            $product->price = $request->pricing;
            $product->compare_price = $request->compare_price;
            $product->sku = $request->sku;
            $product->barcode = $request->barcode;
            $product->track_qty = $request->track_qty;
            $product->qty = $request->qty;
            $product->status = $request->status;
            $product->category_id = $request->category;
            $product->sub_category_id = $request->sub_category;
            $product->brand_id = $request->brand;
            $product->is_featured = $request->is_featured;
            $product->image = $filename;
            $product->save();

            

            $message = 'Product added successfully';
            session()->flash('success',$message);
            
            return response()->json([
                'status' => true,
                'message' => $message
            ]);

            

        }else{
            return response()->json([
                'status' => 'false',
                'errors' => $Validator->errors()
            ]);
        }
    }

    public function edit($id)
    {
        $product = Product::find($id); // finding id to edit products
        
        if(empty($product)){
            return redirect()->route('products.index')->with('error','Product not found');
        }

        //Fetch Product Images
        // $productImages = ProductImage::where('product_id',$product->id)->get();

        $subCategories = SubCategory::where('category_id',$product->category_id)->get();
        
        $data = [];
        //fetching categories,subCategories and brands
        $categories = category::orderBy('name','ASC')->get();
        $brands = Brand::orderBy('name','ASC')->get();
        $data['categories'] = $categories;
        $data['brands'] = $brands;
        $data['subCategories'] = $subCategories;
        $data['product'] = $product;
        // $data['productImages'] = $productImages;
        return view('admin.products.edit',$data);
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());
        $product = Product::find($id);
        
        $rules = [
            'title' => 'required',
            'slug' => 'required|unique:products,slug,'.$product->id.',id',
            'pricing' => 'required|numeric',
            'sku' => 'required|unique:products,sku,'.$product->id.',id',
            'track_qty' => 'required|in:Yes,No', //enum fields accepts two value yes and no
            'category' => 'required|numeric',
            'is_featured' => 'required|in:Yes,No',
        ];
        if(!empty($request->track_qty) && $request->track_qty == 'Yes'){
            $rules['qty'] = 'required|numeric';
        }

        $Validator = Validator::make($request->all(),$rules);
        if($Validator->passes()){

            $product->title = $request->title;
            $product->slug = $request->slug;
            $product->description = $request->description;
            $product->price = $request->pricing;
            $product->compare_price = $request->compare_price;
            $product->sku = $request->sku;
            $product->barcode = $request->barcode;
            $product->track_qty = $request->track_qty;
            $product->qty = $request->qty;
            $product->status = $request->status;
            $product->category_id = $request->category;
            $product->sub_category_id = $request->sub_category;
            $product->brand_id = $request->brand;
            $product->is_featured = $request->is_featured;
            $product->save();

            //save gallery pics
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $filename = $image->getClientOriginalName(); 
                $image->move('uploads/products', $filename);

                $product->image = $filename;
                $product->save();
            }

            $message = 'Product updated successfully';
            session()->flash('success',$message);
            
            return response()->json([
                'status' => true,
                'message' => $message
            ]);

            

        }else{
            return response()->json([
                'status' => 'false',
                'errors' => $Validator->errors()
            ]);
        }
    }

    public function destroy($id, Request $request)
    {
        $product = Product::find($id); //fetch clicked id result
        if(empty($product)) {

            $message = 'Product not found';
            session()->flash('error',$message);

            return response([
                'status' => true,
            ]);
        }

        $product->delete();

        $message = 'Product deleted successfully';
        session()->flash('success',$message);
        return response([
            'status' => true,
            'message' => $message
        ]);
    }

    // For product ratings backend 
    public function productRatings(Request $request)
    {
        $ratings = ProductRating::select('product_ratings.*','products.title as productTitle')
                                ->orderBy('product_ratings.created_at','DESC')
                                ->leftJoin('products','products.id','product_ratings.product_id');

        if($request->has('keyword') && $request->keyword != "") {
            $ratings->where('products.title','like','%'.$request->keyword. '%')
                    ->orWhere('product_ratings.username','like','%'.$request->keyword. '%');
        }
        
        $ratings = $ratings->paginate(4);
        $data['ratings'] = $ratings;
        return view('admin.products.ratings',$data);
    }

    // Change rating status backend
    public function changeRatingStatus(Request $request)
    {
        $productRating = ProductRating::find($request->id);
        $productRating->status = $request->status;
        $productRating->save();

        session()->flash('success','Status changed successfully');
        return response()->json([
            'status' => true,
        ]);
    }
}
