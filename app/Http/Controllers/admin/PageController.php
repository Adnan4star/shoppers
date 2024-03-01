<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PageController extends Controller
{
    public function index(Request $request)
    {
        $pages = Page::latest();

        if ($request->get('keyword') != '') {
            $pages = $pages->where('name','like','%'.$request->keyword.'%');
        }
        $pages = $pages->paginate(4);

        $data['pages'] = $pages;
        return view('admin.pages.list',$data);
    }

    public function create()
    {
        return view('admin.pages.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'slug' => 'required',
        ]);

        if ($validator->passes()) {

            $page = new Page();
            $page->name = $request->name;
            $page->slug = $request->slug;
            $page->content = $request->content;
            $page->save();

            $message = 'Page created successfully';
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

    public function edit($id)
    {
        $pages = Page::find($id);
        $data['pages'] = $pages;

        if(empty($pages)) {
            return redirect()->route('pages.index');
        }

        return view('admin.pages.edit',$data);
    }

    public function update(Request $request, $id)
    {
        $page = Page::find($id);

        if(empty($page)) {
            $message = 'Page not found';
            session()->flash('error',$message);
            return response()->json([
                'status' => true,
            ]);
        }

        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'slug' => 'required|unique:pages,slug,'.$page->id.',id',
        ]);

        if ($validator->passes()) {
            $page->name = $request->name;
            $page->slug = $request->slug;
            $page->content = $request->content;
            $page->save();

            $message = 'Page Updated successfully';
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

    public function destroy($id)
    {
        $page = Page::find($id);
        if (empty($page)) {
            $message = 'Page not found';
            session()->flash('error',$message);
            return response([
                'status' => true,
            ]);
        }

        $page->delete();
            $message = 'Page deleted successfully';
            session()->flash('success',$message);
            return response([
                'status' => true,
                'message' => $message
            ]);
    }
}
