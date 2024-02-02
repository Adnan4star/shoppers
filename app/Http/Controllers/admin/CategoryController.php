<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\category;
use App\Models\TempImage;
use Illuminate\Support\Facades\File;
use Intervention\Image\Laravel\Facades\Image;

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
            $category = new Category();
            $category->name = $request->name;
            $category->slug = $request->slug;
            $category->status = $request->status;
            $category->save();

            //Save Image Here
            if(!empty($request->image_id))
            {
                $tempImage = TempImage::find($request->image_id); //finding id of temo image stored in Db
                $extArray = explode('.',$tempImage->name);
                $ext = last($extArray);

                $newImageName = $category->id.'.'.$ext; //combining last saved category with image name
                $spath = public_path().'/temp/'.$tempImage->name; //temp path with actual image name
                $dpath = public_path().'/uploads/category/'.$newImageName; //copying image with new destination and with new image name
                File::copy($spath,$dpath);

                //Generate image thumbnail
                $dpath = public_path().'/uploads/category/thumb'.$newImageName;
                $img = Image::make($spath); 
                $img -> resize(450,600); //resize method of image intervention
                $img -> save($dpath);


                $category->image = $newImageName; //saving image name on category's image column
                $category->save();
            
            }

            $request->session()->forget('category');

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

    public function update($categoryId, Request $request)
    {
        $category = category::find($categoryId);

        if(empty($category))
            {
                $request->session()->forget('category');

                return response()->json([
                    'status' => false,
                    'notFound' => true,
                    'message' => 'category not found'
                ]);
            }

        $validator = Validator::make($request->all(),[   //validating and inserting data
            'name' => 'required',
            'slug' => 'required|unique:categories,slug,'.$category->id.',id',
        ]);
        if($validator->passes()){

            $category->name = $request->name;
            $category->slug = $request->slug;
            $category->status = $request->status;
            $category->save();

            $oldImage = $category->image;

            //Save Image Here
            if(!empty($request->image_id))
            {
                $tempImage = TempImage::find($request->image_id);
                $extArray = explode('.',$tempImage->name);
                $ext = last($extArray);

                $newImageName = $category->id.'-'.time().'.'.$ext;
                $spath = public_path().'/temp/'.$tempImage->name;
                $dpath = public_path().'/uploads/category/'.$newImageName;
                File::copy($spath,$dpath);

                //Generate image thumbnail
                // $img = Image::make('$spath');

                $category->image = $newImageName;
                $category->save();
                
                //Delete old images
                File::delete(public_path().'/uploads/category/'.$oldImage);
            }

            $request->session()->forget('category');

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
