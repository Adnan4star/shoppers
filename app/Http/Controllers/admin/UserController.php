<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::latest('id');

        if(!empty($request->get('keyword'))){
            $users = $users->where('name','like','%'.$request->get('keyword').'%'); //search by keyword on list category
            $users = $users->orWhere('email','like','%'.$request->get('keyword').'%');
        }
        $users = $users->paginate(4);
        return view('admin.users.list',compact('users'));
    }

    public function create()
    {
        $roles = Role::all();
        $permissions = Permission::all();
        
        $data['roles'] = $roles;
        $data['permissions'] = $permissions;

        return view('admin.users.create', $data);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:5|confirmed',
            'roles' => 'required|array',
            'permissions' => 'required|array',
        ]);

        if ($validator->passes()) {

            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->password = Hash::make($request->password);
            $user->status = $request->status;
            $user->save();
            
            
            
            foreach ($request->input('roles') as $role_id) {
                $role = Role::findOrFail($role_id);
                
                // Retrieve permissions selected for the current role
                $permissionsForRole = $request->input('permissions');
                
                // Synchronize permissions for the current role
                $role->permissions()->sync($permissionsForRole);
            }

            $user->roles()->attach($request->input('roles')); // Defined roles() relation in user model

            $message = 'User created successfully';
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

    public function edit(Request $request, $id) 
    {
        $user = User::find($id);
        $roles = Role::all();
        $permissions = Permission::all();
        
        if (empty($user)) {
            $request->session()->forget('user');
            return redirect()->route('users.index');
        }

        $data['user'] = $user;
        $data['roles'] = $roles;
        $data['permissions'] = $permissions;
        return view('admin.users.edit',$data); //appending to users.edit view

    }

    public function update(Request $request, $id)
    {
        $users = User::find($id);

        if (empty($users)){
            $message = 'User not found!';
            session()->flash('error',$message);
            return redirect()->route('users.index');

            return response([
                'status' => true,
                'message' => $message
            ]);
            
        }
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$id.',id',
            'roles' => 'required|array',
        ]);

        if ($validator->passes()) {
            $users->name = $request->name;
            $users->email = $request->email;
            $users->phone = $request->phone;

            if ($request->password != ''){
                $users->password = Hash::make($request->password);
            }

            $users->status = $request->status;
            $users->save();

            // attach = add ids
            // detach = delete ids

            // sync = detach + attach

            foreach ($request->input('roles') as $role_id) { // Updating permissions
                $role = Role::findOrFail($role_id);
                
                // Retrieve permissions selected for the current role
                $permissionsForRole = $request->input('permissions');
                
                // Synchronize permissions for the current role
                $role->permissions()->sync($permissionsForRole);
            }

            $users->roles()->sync($request->input('roles')); // Updating roles

            $message = 'User details updated successfully';
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

    public function destroy(Request $request,$id)
    {
        $user = User::find($id); //fetch clicked id result
        if(empty($user)) {

            $message = 'User not found!';
            session()->flash('error',$message);
            return response([
                'status' => false,
                'notFound' => true
            ]);
        }

        // First Delete permissions associated with the user's roles 
        foreach ($user->roles as $role) {
            $role->permissions()->detach();
        }

        $user->roles()->detach();
        $user->delete();

        $message = 'User deleted successfully';
        session()->flash('success',$message);
        return response([
            'status' => true,
            'message' => $message
        ]);
    }
}
