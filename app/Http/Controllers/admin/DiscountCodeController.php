<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

class DiscountCodeController extends Controller
{
    public function index(){
        return view('admin.coupon.list');
    }

    public function create(){
        return view('admin.coupon.create');
    }
    

    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'code' => 'required',
            'type' => 'required',
            'amount' => 'required|numeric',
            'status' => 'required',
        ]);

        if ($validator->passes()) {
            
            //starting date must be greator than current date
            if(!empty($request->starts_at)){
                $now =  Carbon::now();
                $startsAt = Carbon::createFromFormat('Y-m-d H:i:s',$request->starts_at);

                if($starts_at->lte($now) == true) {
                    return response()->json([
                        'status' => false,
                        'errors' => ['starts_at' => 'Start date can not be less than current date time ']
                    ]);
                }
            }
            //End date must be greator than start date

            if(!empty($request->starts_at) && !empty($request->ends_at)){
                $endAt =  Carbon::createFromFormat('Y-m-d H:i:s',$request->end_at);
                $startsAt = Carbon::createFromFormat('Y-m-d H:i:s',$request->starts_at);

                if($endAt->gt($startsAt) == false) {
                    return response()->json([
                        'status' => false,
                        'errors' => ['end_at' => 'End date must be greater than start date']
                    ]);
                }
            }
            $discountCode = new DiscountCoupon();
            $discountCode ->code = $request->code;
            $discountCode ->name = $request->name;
            $discountCode ->name = $request->name;
            $discountCode ->description = $request->description;
            $discountCode ->max_uses = $request->max_uses;
            $discountCode ->max_uses_uses = $request->max_uses_uses;
            $discountCode ->type = $request->type;
            $discountCode ->amount = $request->amount;
            $discountCode ->min_amount = $request->min_amount;
            $discountCode ->status = $request->status;
            $discountCode ->starts_at = $request->starts_at;
            $discountCode ->ends_at = $request->ends_at;
            $discountCode ->created_at = $request->created_at;
            $discountCode ->updated_at = $request->updated_at;
            $discountCode ->save();

            $message = 'Discount coupon added successfully.';
            session()->flash('success',$message);

            return response()->json([
                'status' => true,
                'message' => 'Discount coupon added successfully.'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }
}