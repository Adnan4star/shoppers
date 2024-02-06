<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\category;
use App\Models\TempImage;
use Illuminate\Support\Facades\File;

// use Intervention\Image\ImageManager;
// use Intervention\Image\Drivers\Gd\Driver;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = category::latest();

        if(!empty($request->get('keyword'))){
            $categories = $categories->where('name','like','%'.$request->get('keyword').'%'); //search by keyword on list category
        }

        $categories = $categories->paginate(10); 
        return view('admin.category.list',compact('categories')); //displaying categories
    }

    public function create()
    {
        return view('admin.category.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[   //validating and inserting data
            'name' => 'required',
            'slug' => 'required|unique:categories',
        ]);

        if($validator->passes()){

            //Save Image Here
            $inputArray = $request->all();
            $filename = '';
            if($request->hasFile('image'))
            {
                $image = $request->file('image');
                $filename = $image->getClientOriginalName(); 
                $image->move('uploads',$filename);
            }

            category::create([
                'name' =>  $inputArray['name'],
                'slug' =>  $inputArray['slug'],
                'status' =>  $inputArray['status'],
                'showHome' =>  $inputArray['showHome'],
                'image' =>  $filename,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Category added successfully'
            ]);

            }else{
                return response()->json([
                    'status' => false,
                    'errors' => $validator->errors()
                ]);
            }
        }


    public function edit($categoryId, Request $request)
    {
        $category = category::find($categoryId);
        if(empty($category))
        {
            return redirect()->route('categories.index');
        }


        return view('admin.category.edit',compact('category'));
    }

    public function update(Request $request,$categoryId)
    {
       
        $category = category::find($categoryId);

        if(empty($category))
            {
                // $request->session()->forget('category');

                return response()->json([
                    'status' => false,
                    'notFound' => true,
                    'message' => 'category not found'
                ]);
            }

        $validator = Validator::make($request->all(),[   //validating and inserting data
            'name' => 'required',
            'slug' => 'required|unique:categories,slug,' . $categoryId, // Ensure slug uniqueness except for the current category
        ]);

        if($validator->passes()){

            // Update the image if a new one is provided
            if ($request->hasFile('image')) {
                    $image = $request->file('image');
                    $filename = $image->getClientOriginalName(); 
                    $image->move('uploads', $filename);

                    $category->image = $filename;
            }

                // Update category data
                $category->name = $request->input('name');
                $category->slug = $request->input('slug');
                $category->status = $request->input('status');
                $category->showHome = $request->input('showHome');
                $category->save();
                
                // $request->session()->forget('category');

            return response()->json([
                'status' => true,
                'message' => 'Category updated successfully'
            ]);

        }else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function destroy($categoryId, Request $request)
    {
        $category = category::find($categoryId);
        if(empty($category))
        {
            $request->session()->forget('category');
            return response()->json([
                'status' => true,
                'message' => 'Category not found',
            ]); 
        }
        File::delete(public_path().'/uploads/category/'.$category->image);
        $category->delete();

        $request->session()->forget('category');
        return response()->json([
            'status' => true,
            'message' => 'Category Deleted Successfully'
        ]);
    }
}
