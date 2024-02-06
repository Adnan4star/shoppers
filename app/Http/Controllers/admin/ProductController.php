<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\SubCategory;
use App\Models\TempImage;
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
        
        $products = $products->paginate();
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

            

            // $request->session()->forget('product');
            
            return response()->json([
                'status' => true,
                'message' => 'Product added successfully'
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

            $request->session()->forget('product');
            
            return response()->json([
                'status' => true,
                'message' => 'Product updated successfully'
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
            $request->session()->forget('product');
            return response([
                'status' => false,
                'notFound' => true
            ]);
        }

        $product->delete();
        $request->session()->forget('product');
        return response([
            'status' => true,
            'message' => 'product deleted successfully'
        ]);
    }
}
