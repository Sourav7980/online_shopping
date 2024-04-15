<?php

namespace App\Http\Controllers;

use App\Mail\ContactEmail;
use App\Models\Page;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;

class FrontController extends Controller
{

    public function index(){

        $products = Product::where('is_featured','Yes')->orderBy('id','DESC')->where('status',1)->take(8)->get();
        $data['featuredProducts'] = $products;

        $latestProducts = Product::orderBy('id','DESC')->where('status',1)->take(8)->get();
        $data['latestProducts'] = $latestProducts;
        return view('front.home',$data);
    }


    public function page($slug){
        $page = Page::where('slug',$slug)->first();
        if($page == null){
            abort(404);
        }
        return view('front.page',[
            'page' => $page
        ]);
        //dd($page);
    }

    public function sendContentEmail(Request $request) {
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|email',
           'subject' => 'required|min:10',
        ]);

        if ($validator->passes()) {
            
            //send email here

            $mailData = [
                'name' => $request->name,
                'email' => $request->email,
               'subject' => $request->subject,
               'message' => $request->message,
               'mail_subject' => 'You have recived a contact email'
            ];

            $admin = User::where('id',1)->first();

            Mail::to($admin->email)->send(new ContactEmail($mailData));

            session()->flash('success','Thanks for contacting us, we wii get back to you soon.');

            return response()->json([
                'status' => true,
            ]);

        }else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

}
