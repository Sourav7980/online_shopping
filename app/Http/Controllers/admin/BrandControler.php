<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Brand;


class BrandControler extends Controller
{
    public function index(Request $request){
        $brands = Brand::latest('id');

        if($request->get('keyword')){
            $brands = $brands->where('name','like','%'.$request->keyword.'%');
        }

        $brands = $brands->paginate(10);
        return view('admin.brands.list',compact('brands'));
    }
    
    public function create(){
        return view('admin.brands.create');
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'slug' => 'required|unique:brands',
        ]);

        if($validator->passes()) {
            $brand = new Brand();
            $brand->name = $request->name;
            $brand->slug = $request->slug;
            $brand->status = $request->status;
            $brand->save();

            return response()->json([
                'status' => true,
                'message' => 'Brand added successfully.'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function edit($id, Request $request){
        $brand = Brand::find($id);
        if(empty($brand)){
            //$request->session()->flash('error','Record not found');
            return redirect()->route('brands.index');
        }

        //$data['brand'] = $brand;
        return view('admin.brands.edit', compact('brand'));
    }

    public function update($id, Request $request){
        $brand = Brand::find($id);

        if(empty($brand)){
            $request->session()->flash('error','Record not found');
            
            return response()->json([
                'status' => false,
                'notFound' => true,
                'message' => 'Brand not Found'
            ]);
        }
        
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'slug' => 'required|unique:brands,slug,'.$brand->id.',id',

        ]);

        if($validator->passes()) {
            //$brand = new Brand();
            $brand->name = $request->name;
            $brand->slug = $request->slug;
            $brand->status = $request->status;
            $brand->save();

            $request->session()->flash('success','Brand Updated successfully');
            return response()->json([
                'status' => true,
                'message' => 'Brand Updated successfully.'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function destory($id, Request $request) {
        $brand = Brand::find($id);
        if(empty($brand)){

            $request->session()->flash('error','Category not found');
            return response()->json([
                'status' => true,
                'message' => 'Category not found'
            ]);
            //return redirect()->route('categories.index');
        }
        
        $brand->delete();

        $request->session()->flash('success','Category deleted successfully');
        return response()->json([
            'status' => true,
            'message' => 'Category deleted successfully'
        ]);
    
    }
}
