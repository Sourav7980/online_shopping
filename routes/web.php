<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\admin\AdminLoginController;
use App\Http\Controllers\admin\HomeControler;
use App\Http\Controllers\admin\BrandControler;
use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\SubCategoryController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\admin\TempImagesController;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\admin\ProductSubCategoryController;
use App\Http\Controllers\admin\SettingController;
use App\Http\Controllers\admin\ProductController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

/* Route::get('/', function () {
    return view('welcome');
}); */

    Route::get('/',[FrontController::class,'index'])->name('front.home');

    Route::get('/register',[AuthController::class,'register'])->name('account.register');
    Route::post('/process-register',[AuthController::class,'processRegister'])->name('account.processRegister');


    Route::group(['prefix' => 'admin'],function(){
    Route::group(['middleware' => 'admin.guest'],function(){

        Route::get('/login',[AdminLoginController::class,'index'])->name('admin.login');
        Route::post('/authenticate',[AdminLoginController::class,'authenticate'])->name('admin.authenticate');

    });

    Route::group(['middleware' => 'admin.auth'],function(){
    Route::get('/dashboard',[HomeControler::class,'index'])->name('admin.dashboard');
    Route::get('/logout',[HomeControler::class,'logout'])->name('admin.logout');


    //Category Routes
    Route::get('/categories',[CategoryController::class,'index'])->name('categories.index');
    Route::get('/categories/create',[CategoryController::class,'create'])->name('categories.create');
    Route::post('/categories',[CategoryController::class,'store'])->name('categories.store');
    Route::get('/categories/{create}/edit',[CategoryController::class,'edit'])->name('categories.edit');
    Route::put('/categories/{create}',[CategoryController::class,'update'])->name('categories.update');
    Route::delete('/categories/{create}',[CategoryController::class,'destory'])->name('categories.delete');

    //Sub Category Routes
    Route::get('/sub-categories/create',[SubCategoryController::class,'create'])->name('sub-categories.create');
    //temp-images.create
    Route::post('/upload-temp-image',[TempImagesController::class,'create'])->name('temp-images.create');

    //brands
    Route::get('/brands',[BrandControler::class,'index'])->name('brands.index');
    Route::get('/brands/create',[BrandControler::class,'create'])->name('brands.create');
    Route::post('/brands',[BrandControler::class,'store'])->name('brands.store');
    Route::get('/brands/{brand}/edit',[BrandControler::class,'edit'])->name('brands.edit');
    Route::put('/brands/{brand}',[BrandControler::class,'update'])->name('brands.update');
    Route::delete('/brands/{brand}',[BrandControler::class,'destory'])->name('brands.delete');

    //Prtoduct Routes
    Route::get('/products/create',[ProductController::class,'create'])->name('products.create');
    Route::post('/products',[ProductController::class,'store'])->name('products.store');

    Route::get('/product-subcategories',[ProductSubCategoryController::class,'index'])->name('product-subcategories.index');




    //setting-password
    Route::get('/change-password',[SettingController::class,'ShowChangePasswordForm'])->name('admin.ShowChangePasswordForm');
    Route::post('/process-change-password',[SettingController::class,'processChangePassword'])->name('admin.processChangePassword');

    Route::get('/getSlug',function(Request $request){
        $slug = '';
        if(!empty($request->title)){
            $slug = Str::slug($request->title);
        }
        return response()->json([
            'status' => true,
            'slug' => $slug
        ]);
    })->name('getSlug');


    });

});
