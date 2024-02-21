<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login()
    {
        return view('front.account.login');
    }

    public function register()
    {
        return view('front.account.register');
    }

    public function processRegister(Request $request)
    {
        // validate form first
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:5|confirmed'
        ]);

        if ($validator->passes()) {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->password = Hash::make($request->password);
            $user->save();

            session()->flash('success','You have registered successfully.');
            return response()->json([
                'status' => true,
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }
    }

    public function authenticate(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->passes()) {
            // Authenicating login credentials
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->get('remember'))) 
            {
                // If user not logged in and clicks checkout page, then after logging in, he will be redirected back to checkout page
                if (session()->has('url.intended')) {
                    return redirect(session()->get('url.intended'));
                }

                return redirect()->route('account.profile');
                
            } else {
                return redirect()->route('account.login')
                ->withInput($request->only('email')) // with wrong entered email
                ->with('error','Either email/password is incorrect.');
            }

        } else {
            return redirect()->route('account.login')
            ->withErrors($validator)
            ->withInput($request->only('email'));
        }
    }

    public function profile()
    {
        return view('front.account.profile');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('account.login')
        ->with('success','You successfully logged out!');
    }

    public function orders() 
    {
        $user = Auth::user(); // Getting logged in user id

        $orders = Order::where('user_id',$user->id)->orderBy('created_at','DESC')->get();

        $data['orders'] = $orders;
        return view('front.account.order',$data);
    }

    public function orderdetail($id)
    {
        $user = Auth::user(); // Getting logged in user id

        $order = Order::where('user_id',$user->id)->where('id',$id)->first();
        $orderItems = OrderItem::where('order_id', $id)->get();

        $data['order'] = $order;
        $data['orderItems'] = $orderItems;
        return view('front.account.order-detail',$data);
    }
}
