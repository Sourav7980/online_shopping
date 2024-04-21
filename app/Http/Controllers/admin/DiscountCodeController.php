<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DiscountCodeController extends Controller
{
    public function index(){
    
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
            // Your code to store the discount code
            return response()->json([
                'status' => true,
                'message' => 'Discount code created successfully',
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }
    }
}