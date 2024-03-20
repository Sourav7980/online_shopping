<?php

namespace App\Http\Controllers\admin;

use App\Models\ProductImage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductImageController extends Controller
{
    public function update(Request $request){

        $image = $request->image;
        $ext = $image->getClientOriginalExtension();
        $sPath =$image->getPathName();

        $productImage = new ProductImage();
        $productImage->product_id = $request->product_id;
        $productImage->image = 'NULL';
        $productImage->save();

        $imageName = $request->product_id.'-'.$productImage->id.'-'.time().'.'.$ext;
        //$sPath = public_path().'/temp/'.$tempImageInfo->name;
        $dPath = public_path().'/uploads/products/'.$imageName;
        File::copy($sPath,$dPath);

        $productImage ->image = $imageName;
        $productImage ->save();

        return resonse()->json([
            'status' => true,
            'image_id' => $productImage->id,
            'ImagePath' => asset('upload/products/'.$productImage->image),
            'message' => 'Image save successfully'
        ]);
    }
}
