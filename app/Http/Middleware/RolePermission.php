<?php

namespace App\Http\Middleware;

use App\Models\User;
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
        // $user_id = Auth::user()->id;
        // $user = User::with('permissions')->where('id', $user_id )->first()->toArray();
        // $permissions = collect(data_get($user, 'permissions'))->pluck('name')->toArray(); //  data_get allows get a value from an array // collect() is an Array class method which invokes the argument block once for each element of the array(works like foreach but on array)
        // $request->merge(['permissions' => $permissions]); // Merging plucked permissions 'name' with request
        
        return $next($request);
    }
}
