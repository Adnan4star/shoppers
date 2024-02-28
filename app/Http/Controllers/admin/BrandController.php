<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BrandController extends Controller
{
    public function index(Request $request)
    {
        $brands = Brand::latest('id');

        if($request->get('keyword')){
            $brands = $brands->where('name','like','%'.$request->keyword.'%');
        }
        $brands = $brands->paginate(10);
        return view('admin.brands.list',compact('brands'));
    }

    public function create(Request $request)
    {
        // $permissions = data_get($request->all(), 'permissions') ?? [];
        
        // if (in_array('create_brand', $permissions, true)) {
            return view('admin.brands.create');
        // } else {
        //     abort(401);
        // }
        
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[   //validating and inserting data
            'name' => 'required',
            'slug' => 'required|unique:brands',
        ]);
        if($validator->passes()){
            $brand = new Brand();
            $brand->name = $request->name;
            $brand->slug = $request->slug;
            $brand->status = $request->status;
            $brand->save();

            $message = 'brand added successfully';
            session()->flash('success',$message);

            return response()->json([
                'status' => true,
                'message' => $message
            ]);

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function edit($id, Request $request)
    {
        $brand = Brand::find($id); //Getting all brands
        $data['brand'] = $brand;

        if(empty($brand)) {
            return redirect()->route('brands.index');
        }

        return view('admin.brands.edit',$data);
    }

    public function update($id, Request $request)
    {
        $brand = Brand::find($id);

        if(empty($brand)) {

            $message = 'Brand not found';
            session()->flash('error',$message);
            return response()->json([
                'status' => false,
                'notFound' => true
            ]);
        }

        $validator = Validator::make($request->all(),[   //validating and inserting data
            'name' => 'required',
            'slug' => 'required|unique:brands,slug,'.$brand->id.',id',
        ]);
        if($validator->passes()){
            $brand->name = $request->name;
            $brand->slug = $request->slug;
            $brand->status = $request->status;
            $brand->save();

            $message = 'Brand updated successfully';
            session()->flash('success',$message);

            return response()->json([
                'status' => true,
                'message' => $message
            ]);

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        } 
    }

    public function destroy($id, Request $request)
    {
        $brand = Brand::find($id); //fetch clicked id result
        if(empty($brand)) {

            $message = 'Brand not found';
            session()->flash('error',$message);
            return response([
                'status' => false,
                'notFound' => true
            ]);
        }
        $brand->delete();

        $message = 'Brand deleted successfully';
        session()->flash('success',$message);
        return response([
            'status' => true,
            'message' => $message
        ]);
    }
}
