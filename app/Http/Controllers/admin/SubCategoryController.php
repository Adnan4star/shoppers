<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubCategoryController extends Controller
{
    public function index(Request $request)
    {
        $subCategories = SubCategory::select('sub_categories.*','categories.name as categoryName')
        ->latest('sub_categories.id')
        ->leftJoin('categories','categories.id','sub_categories.category_id'); //listing subCategory usig leftJoin to fetch category name from categories table

        if(!empty($request->get('keyword'))){
            $subCategories = $subCategories->where('sub_categories.name','like','%'.$request->get('keyword').'%'); //search by keyword on list subcategory
            $subCategories = $subCategories->orWhere('categories.name','like','%'.$request->get('keyword').'%'); //search by keyword on list category
        }

        $subCategories = $subCategories->paginate(10); 
        return view('admin.sub_category.list',compact('subCategories')); //displaying categories
    }

    public function create()
    {
        $categories = category::orderBy('name','ASC')->get(); //Getting all categories
        $data['categories'] = $categories;
        return view('admin.sub_category.create',$data); //appending to sub-category view
    }

    public function store(Request $request)
    {
      $validator = Validator::make($request->all(),[ //validating form fields
        'name' => 'required',
        'slug' => 'required|unique:sub_categories',
        'category' => 'required',
        'status' => 'required'
      ]);
      
      if($validator->passes()){
        $subCategory = new SubCategory(); //inserting form data into subCategory table via model

        $subCategory->name = $request->name;
        $subCategory->slug = $request->slug;
        $subCategory->category_id = $request->category;
        $subCategory->status = $request->status;
        $subCategory->showHome = $request->showHome;
        $subCategory->save();

        $request->session()->forget('subCategory');
        
        return response([
            'status' => true,
            'message' => 'SubCategory added successfully'
        ]);

      } else {
        return response([
            'status' => false,
            'errors' => $validator->errors()
        ]);
      }
    }

    public function edit($id, Request $request)
    {
        $subCategory = SubCategory::find($id); //fetch clicked id result
        if (empty($subCategory)){

            $request->session()->forget('subCategory');
            return redirect()->route('sub-categories.index');
            
        }

        $categories = category::orderBy('name','ASC')->get(); //Getting all categories
        $data['categories'] = $categories;
        $data['subCategory'] = $subCategory;
        return view('admin.sub_category.edit',$data); //appending to sub-category view

    }

    public function update($id, Request $request)
    {
        $subCategory = SubCategory::find($id); //fetch clicked id result
        if (empty($subCategory)){

            $request->session()->forget('subCategory');
            // return redirect()->route('sub-categories.index');
            return response([
                'status' => false,
                'notFound' => true
            ]);
            
        }

        $validator = Validator::make($request->all(),[ //validating form fields
            'name' => 'required',
            'slug' => 'required|unique:sub_categories,slug,'.$subCategory->id.',id',
            'category' => 'required',
            'status' => 'required'
          ]);
          
          if($validator->passes()){
    
            $subCategory->name = $request->name;
            $subCategory->slug = $request->slug;
            $subCategory->category_id = $request->category;
            $subCategory->status = $request->status;
            $subCategory->showHome = $request->showHome;
            $subCategory->save();
    
            $request->session()->forget('subCategory');
            
            return response([
                'status' => true,
                'message' => 'SubCategory Updated successfully'
            ]);
    
          } else {
            return response([
                'status' => false,
                'errors' => $validator->errors()
            ]);
          }
    }

    public function destroy($id, Request $request)
    {
        $subCategory = SubCategory::find($id); //fetch clicked id result
        if(empty($subCategory)) {
            $request->session()->forget('subCategory');
            return response([
                'status' => false,
                'notFound' => true
            ]);
        }

        $subCategory->delete();
        $request->session()->forget('subCategory');
        return response([
            'status' => true,
            'message' => 'Sub Category deleted successfully'
        ]);
    }
}
