<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DiscountCodeController extends Controller
{
    public function index()
    {
        return view('admin.coupons.index'); // Assuming you have a view file named 'index.blade.php' inside 'resources/views/admin/coupons' folder
    }

    public function create()
    {
        return view('admin.coupons.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
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

    public function edit($id)
    {
        // Your code to fetch and edit the discount code with the given ID
        return view('admin.coupons.edit', compact('id'));
    }
}