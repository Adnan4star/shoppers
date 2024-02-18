<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Shipping;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PHPUnit\Framework\Constraint\Count;

use function Laravel\Prompts\select;

class ShippingController extends Controller
{
    public function create()
    {
        $countries = Country::get();
        $data['countries'] = $countries;
        
        return view('admin.shipping.create',$data);
    }

    public function index(Request $request)
    {
        $shippings = Shipping::select('shippings.*','countries.name')
                            ->leftJoin('countries','countries.id','shippings.country_id')
                            ->get();

        $data['shippings'] = $shippings;

        return view('admin.shipping.list',$data);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'country' => 'required',
            'amount' => 'required|numeric'
        ]);

        if ($validator->passes()){
            // Checking if record already exists
            $count = Shipping::where('country_id',$request->country)->count();
            
            if ($count > 0) {
                session()->flash('error','Shipping already exists');
                return response()->json([
                    'status' => true,
                ]);
            }

            // storing new shipping
            $shipping = new Shipping;

            $shipping->country_id = $request->country;
            $shipping->amount = $request->amount;
            $shipping->save();

            session()->flash('success','Shipping saved successfully');
            return response()->json([
                'status' => true,
                'success' => 'Shipping saved successfully',
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }
    }

    public function edit($id)
    {
        $shipping = Shipping::find($id);
        $countries = Country::get();

        $data['countries'] = $countries;
        $data['shipping'] = $shipping;

        return view('admin.shipping.edit',$data);
    }

    public function update($id, Request $request)
    {
        $validator = Validator::make($request->all(),[
            'country' => 'required',
            'amount' => 'required|numeric'
        ]);

        if ($validator->passes()){
            $shipping = Shipping::find($id);

            $shipping->country_id = $request->country;
            $shipping->amount = $request->amount;
            $shipping->save();

            session()->flash('success','Shipping Updated successfully');
            return response()->json([
                'status' => true,
                'success' => 'Shipping saved successfully',
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }
    }

    public function destroy($id)
    {
        $shipping = Shipping::find($id);

        if ($shipping == null) {
            session()->flash('error','Shipping not found.');
            return response()->json([
                'status' => true,
            ]);
        }

        $shipping->delete();

        session()->flash('success','Shipping deleted successfully.');
            return response()->json([
                'status' => true,
            ]);
    }
}
