<?php

namespace App\Http\Controllers;

use DB;
use App\Models\User;
use App\Models\Order;
use App\Models\Country;
use App\Models\Wishlist;
use App\Models\OrderItem;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\CustomerAddress;
use App\Mail\ResetPasswordEmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(){
        return view('front.account.login');
    }

    public function register(){
        return view('front.account.register');
    }

    public function processRegister(Request $request){

         $validator = Validator::make($request->all(),[
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:5|confirmed'

        ]);

        if($validator->passes()){

            $user = new User;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->password = Hash::make($request->password);
            $user->save();

            session()->flash('success','You have been Registerd Successfully.');

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

    public function authenticate(Request $request){

        $validator = Validator::make($request->all(),[
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if($validator->passes()){

            if(Auth::attempt(['email' => $request->email, 'password' => $request->password],$request->get('remember'))){

                if(session()->has('url.intended')){
                    return redirect(session()->get('url.intended'));
                }

                /* session()->flash('success','You login successfully');

                return response()->json([
                    'status' => true,
                    'message' => 'Login successfully'
                ]); */

                return redirect()->route('account.profile')->with('success','You login successfully');

            }else{
                //session()->flash('error','Either email/password is incorrect.');
                return redirect()->route('account.login')->withInput($request->only('email'))->with('error','Either email/password is incorrect.');
            }

        }else{
            return redirect()->route('account.login')
            ->withErrors($validator)
            ->withInput($request->only('email'));
        }
    }

    public function profile(){

        $userId = Auth::user()->id;

        $countries = Country::orderBy('name','ASC')->get();

        $user = User::where('id',Auth::user()->id)->first();
        $address = CustomerAddress::where('user_id',$userId)->first();

        return view('front.account.profile',[
            'user' => $user,
            'countries' => $countries,
            'address' => $address
        ]);
    }

    public function updateProfile(Request $request){

        $userId = Auth::user()->id;
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$userId.',id',
            'phone' => 'required'
        ]);

        if($validator->passes()){

            $user = User::find($userId);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->save();

            session()->flash('success','Profile updated successfully');
            return response()->json([
                'status' => true,
                'message' => 'Profile updated successfully'
            ]);

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function updateAddress(Request $request){

        $userId = Auth::user()->id;

        $validator = Validator::make($request->all(),[

            'first_name' => 'required|min:5',
            'last_name' => 'required',
            'email' => 'required|email',
            'country_id' => 'required',
            'address' => 'required|min:30',
            'city' => 'required',
            'state' => 'required',
            'zip' => 'required',
            'mobile' =>'required'
        ]);

        if($validator->passes()){

            /* $user = User::find($userId);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->save(); */

            CustomerAddress::updateOrCreate(
            ['user_id' => $userId],
            [
                'user_id' => $userId,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'country_id' => $request->country_id,
                'address' => $request->address,
                'apartment' => $request->apartment,
                'city' => $request->city,
                'state' => $request->state,
                'zip' =>$request->zip,
            ]
        );

            session()->flash('success','Address updated successfully');

            return response()->json([
                'status' => true,
                'message' => 'Profile updated successfully'
            ]);

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }


    public function logout(){
        Auth::logout();
        return redirect()->route('account.login')->with('success','You successfully logged out!');
    }

    public function orders(){
        $data = [];
        $user = Auth::user();
        $orders = Order::where('user_id',$user->id)->orderBy('created_at','DESC')->get();
        $data['orders'] = $orders;

        return view('front.account.order',$data);
    }

    public function orderDetail($id){

        $data = [];
        $user = Auth::user();

        $order = Order::where('user_id',$user->id)->where('id',$id)->first();
        $data['order'] = $order;

        $orderItem = OrderItem::where('order_id',$id)->get();
        $data['orderItem'] = $orderItem;

        $orderItemCount = OrderItem::where('order_id',$id)->count();
        $data['orderItemCount'] = $orderItemCount;


        return view('front.account.order-detail',$data);
    }

    public function forgotPassword(){
        return view('front.account.forgot-password');
    }

    public function processForgotPassword(Request $request){
        $validator = Validator::make($request->all(),[
            'email' => 'required|email|exists:users,email'
        ]);

        if($validator->fails()){
            return redirect()->route('front.forgotPassword')
                ->withInput()->withErrors($validator);
        }

        $token = Str::random(60);

        \DB::table('password_reset_tokens')->where('email',$request->email)->delete();

        \DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => now()
        ]);

        //Send Email here

        $user = User::where('email',$request->email)->first();

        $formData = [
            'token' => $token,
            'user' => $user,
            'mailSubject' => 'You have to request to reset your password'
        ];

        Mail::to($request->email)->send(new ResetPasswordEmail($formData));
        return redirect()->route('front.forgotPassword')->with('success','Please check your inbox to reset your password');
    }

    public function resetPassword($token){

        $tokenExist = \DB::table('password_reset_tokens')->where('token',$token)->first();

        if($tokenExist == null){
            return redirect()->route('front.forgotPassword')->with('error','Invalid request');
        }
        return view('front.account.reset-password',[
            'token' => $token
        ]);
    }

    public function processResetPassword(Request $request){
        $token = $request->token;
        $tokenObj = \DB::table('password_reset_tokens')->where('token',$token)->first();

        if($tokenObj == null){
            return redirect()->route('front.forgotPassword')->with('error','Invalid request');
        }

        $user = User::where('email',$tokenObj->email)->first();

        $validator = Validator::make($request->all(),[
            'new_password' => 'required|min:3',
            'confirm_password' => 'required|same:new_password'
        ]);

        if($validator->fails()){
            return redirect()->route('front.resetPassword',$token)
                ->withInput()->withErrors($validator);
        }

        User::where('id',$user->id)->update([
            'password'=>Hash::make($request->new_password)
        ]);

        \DB::table('password_reset_tokens')->where('email',$user->email)->delete();

        return redirect()->route('account.login')->with('success','You have succesfully updated your password.');
    }

    public function showChangePasswordForm(){
        return view('front.account.change-password');
    }

    public function changePassword(Request $request){
        $validator = Validator::make($request->all(),[

            'old_password' => 'required',
            'new_password' => 'required|min:3',
            'confirm_password' => 'required|same:new_password'
        ]);

        if($validator->passes()){

            $user = User::select('id','password')->where('id',Auth::user()->id)->first();

            if(!Hash::check($request->old_password, $user->password)){
                session()->flash('error','Your old password is incorrect, please try again.');

                return response()->json([
                    'status' => true,
                ]);
            }

            User::where('id',$user->id)->update([
                'password' => Hash::make($request->new_password)
            ]);

            session()->flash('success','You have successfully change password.');

            return response()->json([
                'status' => true,
                'message' => 'You have successfully change password'
            ]);


        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function wishlist(){
        $wishlists = Wishlist::where('user_id', Auth::user()->id)->get();
        $data = [];
        $data['wishlists'] = $wishlists;
        return view('front.account.wishlist',$data);
    }

    public function removeProductWishlist(Request $request){
        $wishlist = Wishlist::where('user_id',Auth::user()->id)->where('product_id',$request->id)->first();

        if($wishlist == null){
            session()->flash('error','Product already remove');
            return response()->json([
                'status' => true,
            ]);
        } else {
            Wishlist::where('user_id',Auth::user()->id)->where('product_id',$request->id)->delete();
            session()->flash('success','Product remove successfully');
            return response()->json([
                'status' => true,
            ]);
        }
    }

}
