<?php

namespace App\Http\Controllers\admin;

use App\Models\Brand;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\TempImage;
use Illuminate\Support\Facades\File;


class ProductController extends Controller
{
    public function index(Request $request){
        $products = Product::latest('id')->with('product_images');
        if($request->get('keyword') != ""){
            $products = $products->where('title','like','%'.$request->get('keyword').'%');
        }
        $products = $products->paginate();

        $data['products'] = $products;
        return view('admin.products.list', $data);
    }

    public function create(){
        $data = [];
        $categories = Category::orderBy('name','ASC')->get();
        $brands = Brand::orderBy('name','ASC')->get();
        $data['categories'] = $categories;
        $data['brands'] = $brands;
        return view('admin.products.create',$data);
    }

    public function store(Request $request){

        /* dd($request->image_array);
        exit(); */
        $rules = [
            'title' => 'required',
            'slug' => 'required|unique:products',
            'price' => 'required|numeric',
            'sku' => 'required',
            'track_qty' => 'required|in:Yes,No',
            'category' => 'required|numeric',
            'is_featured' => 'required|in:Yes,No',
        ];

        if(!empty($request->track_qty) && $request->track_qty == 'Yes'){
            $rules['qty'] = 'required|numeric';
        }

        $validator = Validator::make($request->all(),$rules);

        if ($validator->passes()){

            $product= new Product;
            $product->title = $request->title;
            $product->slug = $request->slug;
            $product->description = $request->description;
            $product->price = $request->price;
            $product->compare_price = $request->compare_price;
            $product->sku = $request->sku;
            $product->barcode = $request->barcode;
            $product->track_qty = $request->track_qty;
            $product->qty = $request->qty;
            $product->status = $request->status;
            $product->category_id = $request->category;
            $product->sub_category_id = $request->sub_category_id;
            $product->brand_id = $request->brand;
            $product->is_featured = $request->is_featured;
            $product->save();

            // save gallery pics

             if(!empty($request->image_array)){
                foreach($request->image_array as $temp_image_id){

                    $tempImageInfo = TempImage::find($temp_image_id);
                    $extArray = explode('.',$tempImageInfo->name);
                    $ext = last($extArray); //like jpg,gif,png etc

                    $productImage = new ProductImage();
                    $productImage->product_id = $product->id;
                    $productImage->image = 'NULL';
                    $productImage->save();


                    $imageName = $product->id.'-'.$productImage->id.'-'.time().'.'.$ext;
                    $sPath = public_path().'/temp/'.$tempImageInfo->name;
                    $dPath = public_path().'/uploads/products/'.$imageName;
                    File::copy($sPath,$dPath);

                    $productImage ->image = $imageName;
                    $productImage ->save();

                    /* $imageName = $product->id.'-'.$productImage->id.'-'.time().'.'.$ext;
                    $productImage->image = $imageName;
                    $productImage->save() */;

                }

               // $oldImage= $product->image;

            /* if(!empty($request->image_array)){
                foreach($request->image_array as $temp_image_id){

                    $tempImage = TempImage::find($request->image_id);
                    $extArray = explode('.',$tempImageInfo->name);
                    $ext=last($extArray);

                    $productImage = new ProductImage();
                    $productImage->product_id = $product->id;
                    $productImage->image = 'NULL';
                    $productImage->save();

                    //$newImageName=$product->id.'-'.time().'.'.$ext;
                    $imageName = $product->id.'-'.$productImage->id.'-'.time().'.'.$ext;
                    $sPath = public_path().'/temp/'.$tempImage->name;
                    $dPath = public_path().'/uploads/products/'.$imageName;
                    File::copy($sPath,$dPath);

                    $productImage ->image = $imageName;
                    $productImage ->save();
                }

            } */

            $request->session()->flash('success','Product added succesfully');

            return response()->json([
                'status' => true,
                'message' => 'Product added succesfully'
            ]);


        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
            }
        }
    }
}
