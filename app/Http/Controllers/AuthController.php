<?php

namespace App\Http\Controllers;

use App\Mail\ResetPasswordEmail;
use App\Models\Country;
use App\Models\CustomerAddress;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

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
        $user_id = Auth::user()->id;
        $user = User::where('id', $user_id )->first();

        // $user = User::with('permissions')->find($user_id); // Defined permissions in database user_permission

        $countries = Country::orderBy('name','ASC')->get();
        $customerAddress = CustomerAddress::where('user_id', $user_id)->first();

        $data['user'] = $user;
        $data['countries'] = $countries;
        $data['customerAddress'] = $customerAddress;
        return view('front.account.profile',$data);
    }

    public function updateProfile(Request $request)
    {
        $userId = Auth::user()->id;

        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$userId.',id',
            'phone' => 'required',
        ]);

        if ($validator->passes()) {

            $user = User::find($userId);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->save();

            $message = 'Profile updated successfully';
            session()->flash('success', $message);
            return response()->json([
                'status' => true,
                'message' => $message
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }
    }

    public function updateAddress(Request $request)
    {
        $userId = Auth::user()->id;
        $validator = Validator::make($request->all(),[
            'country_id' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'address' => 'required|min:15',
            'city' => 'required',
            'state' => 'required',
            'zip' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
        ]);

        if ($validator->passes()) {

            // $user = User::find($userId);
            // $user->name = $request->name;
            // $user->email = $request->email;
            // $user->phone = $request->phone;
            // $user->save();

            CustomerAddress::updateOrCreate(
                ['user_id' => $userId], // 1st array checks does data already exits in db.
                [                         // 2nd array (If record doesnt exist it creates new record otherwise it will update.
                    'user_id' => $userId,
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'country_id' => $request->country_id,
                    'city' => $request->city,
                    'state' => $request->state,
                    'zip' => $request->zip,
                    'address' => $request->address,
                    'apartment' => $request->apartment
                ]
            );

            $message = 'Address updated successfully';
            session()->flash('success', $message);
            return response()->json([
                'status' => true,
                'message' => $message
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('account.login')
        ->with('success','You successfully logged out!');
    }

    public function orders(Request $request) 
    {
        $permission = data_get($request->all(), 'permissions') ?? []; // Retrieving granted permissions from RolePermission Middleware

        if (in_array('view_orders', $permission, true)) {
            $user = Auth::user(); // Getting logged in user id
            $orders = Order::where('user_id',$user->id)->orderBy('created_at','DESC')->get();
            $data['orders'] = $orders;
            return view('front.account.order',$data);
        } else {
            abort(401);
        }
    }

    public function orderdetail(Request $request, $id)
    {
        $permission = data_get($request->all(), 'permissions') ?? []; // Retrieving granted permissions from RolePermission Middleware

        if (in_array('view_order_details', $permission, true)) {

            $user = Auth::user(); // Getting logged in user id
            $order = Order::where('user_id',$user->id)->where('id',$id)->first();
            $orderItems = OrderItem::where('order_id', $id)->get();
            //counting total orders
            $orderItemsCount = OrderItem::where('order_id', $id)->count();
        
            $data['order'] = $order;
            $data['orderItems'] = $orderItems;
            $data['orderItemsCount'] = $orderItemsCount;
            return view('front.account.order-detail',$data);
        } else {
            abort(401);
        }
        
    }

    // Frontend show change password form
    public function showChangePasswordForm()
    {
        return view('front.account.change_password');
    }

    // Frontend show change password form submit
    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'old_password' => 'required',
            'new_password' => 'required|min:5',
            'confirm_password' => 'required|same:new_password'
        ]);

        if($validator->passes()) {

            $user = User::select('id','password')->where('id',Auth::user()->id)->first();
            
            if (!Hash::check($request->old_password,$user->password)){

                session()->flash('error','Your old password is incorrect!');
                return response()->json([
                    'status' => true,
                ]);
            }

            User::where('id',$user->id)->update([
                'password' => Hash::make($request->new_password)
            ]);

            session()->flash('success','You have successfully changed your password.');
                return response()->json([
                    'status' => true,
                ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }
    
    // Forgot password
    public function forgotPassword()
    {
        return view('front.account.forgot-password');
    }

    // Submission through route
    public function processForgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'email' => 'required|email|exists:users,email', //Checking email if exists in users
        ]);

        if($validator->fails()){
            return redirect()->route('front.forgotPassword')->withInput()->withErrors($validator); // not returning response through ajax bcs not submited fom through ajax
        }

        // Using str to generate random string
        $token = Str::random(60);

        // Deleting old entry stored in password_reset_tokens(Bcs laravel doesnt allow duplicate entry)
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        // Using DB table to insert new reset token 
        DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => now()
        ]);

        // Send email here
        $user = User::where('email', $request->email)->first();

        $formData = [
            'token' => $token,
            'user' => $user,
            'mailSubject' => 'You have requested to reset your password'
        ];

        Mail::to($request->email)->send(new ResetPasswordEmail($formData));

        // redirecting to forgotPassword with success message to check email inbox
        return redirect()->route('front.forgotPassword')->with('success','Please check your inbox to reset your password!');
    }

    // Route for reset password link reset-password.blade
    public function resetPassword($token)
    {
        // First check if token is valid
        $tokenExist = DB::table('password_reset_tokens')->where('token', $token)->first();

        if ($tokenExist == null) {
            return redirect()->route('front.forgotPassword')->with('error','Invalid Request!');
        }

        return view('front.account.reset-password', ['token' => $token]);
    }

    // Route for process reset-password
    public function processResetPassword(Request $request)
    {
        $token = $request->token;
        // First check if token is valid
        $tokenObj = DB::table('password_reset_tokens')->where('token', $token)->first();

        if ($tokenObj == null) {
            return redirect()->route('front.forgotPassword')->with('error','Invalid Request!');
        }

        // fetching user id to update user password
        $user = User::where('email', $tokenObj->email)->first();

        $validator = Validator::make($request->all(),[
            'new_password' => 'required|min:5',
            'confirm_password' => 'required|same:new_password'
        ]);

        if($validator->fails()){
            return redirect()->route('front.resetPassword', $token)->withErrors($validator);
        }

        User::where('id', $user->id)->update([
            'password' => Hash::make($request->new_password)
        ]);

        // Deleting record after updating password from password_reset_token. so that next time user resets it doesnt throw error regarding password reset token cannot be duplicate
        DB::table('password_reset_tokens')->where('email', $user->email)->delete();

        return redirect()->route('account.login')->with('success','You have successfully updated your password');
    }
}
