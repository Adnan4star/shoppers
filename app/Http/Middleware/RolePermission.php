<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RolePermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user_id = Auth::user()->id; // Fetching current id
        $user = User::with('roles.permissions')->where('id', $user_id)->first(); // permissions are associated with roles AND roles with users model
        $permissions =  $user->roles->flatMap->permissions->pluck('name')->toArray(); // Plucking permision name from associated models
        $request->merge(['permissions' => $permissions]); // Merging plucked permissions 'name' with request
        return $next($request);
    }
}
