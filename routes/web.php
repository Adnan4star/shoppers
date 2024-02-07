<?php

use App\Http\Controllers\admin\AdminLoginController;
use App\Http\Controllers\admin\BrandController;
use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\HomeController;
use App\Http\Controllers\admin\ProductController;
use App\Http\Controllers\admin\ProductImageController;
use App\Http\Controllers\admin\ProductSubCategoryController;
use App\Http\Controllers\admin\SubCategoryController;
use App\Http\Controllers\admin\TempImagesController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\ShopController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/',[FrontController::class,'index'])->name('front.home');
Route::get('/shop/{categorySlug?}/{subCategorySlug?}',[ShopController::class, 'index'])->name('front.shop');



Route::group(['prefix' => 'admin'],function(){
    Route::group(['middleware' => 'admin.guest'],function(){

        Route::get('/login',[AdminLoginController::class, 'index'])->name('admin.login'); //admin login route
        Route::post('/authenticate',[AdminLoginController::class, 'authenticate'])->name('admin.authenticate');

    });

    Route::group(['middleware' => 'admin.auth'],function(){
        Route::get('/dashboard',[HomeController::class, 'index'])->name('admin.dashboard');
        Route::get('/logout',[HomeController::class, 'logout'])->name('admin.logout');

        //category routes
        Route::get('/categories',[CategoryController::class, 'index'])->name('categories.index');
        Route::get('/categories/create',[CategoryController::class, 'create'])->name('categories.create');
        Route::post('/categories',[CategoryController::class, 'store'])->name('categories.store');
        Route::get('/categories/{category}/edit',[CategoryController::class, 'edit'])->name('categories.edit');
        Route::post('/categories/{category}',[CategoryController::class, 'update'])->name('categories.update'); //edit submit
        Route::delete('/categories/{category}',[CategoryController::class, 'destroy'])->name('categories.destroy');

        //sub_category routes
        Route::get('/sub-categories',[SubCategoryController::class, 'index'])->name('sub-categories.index');
        Route::get('/sub-categories/create',[SubCategoryController::class, 'create'])->name('sub-categories.create');
        Route::post('/sub-categories',[SubCategoryController::class, 'store'])->name('sub-categories.store');
        Route::get('/sub-categories/{subCategory}/edit',[SubCategoryController::class, 'edit'])->name('sub-categories.edit');
        Route::put('/sub-categories/{subCategory}',[SubCategoryController::class, 'update'])->name('sub-categories.update'); //edit submit
        Route::delete('/sub-categories/{subCategory}',[SubCategoryController::class, 'destroy'])->name('sub-categories.delete');

        //Brands route
        Route::get('/brands',[BrandController::class, 'index'])->name('brands.index');
        Route::get('/brands/create',[BrandController::class, 'create'])->name('brands.create');
        Route::post('/brands',[BrandController::class, 'store'])->name('brands.store');
        Route::get('/brands/{brand}/edit',[BrandController::class, 'edit'])->name('brands.edit');
        Route::put('/brands/{brand}',[BrandController::class, 'update'])->name('brands.update'); //edit submit
        Route::delete('/brands/{brand}',[BrandController::class, 'destroy'])->name('brands.destroy');

        //Product Route
        Route::get('/products',[ProductController::class, 'index'])->name('products.index');
        Route::get('/products/create',[ProductController::class, 'create'])->name('products.create');
        Route::post('/products',[ProductController::class, 'store'])->name('products.store');
        Route::get('/products/{product}/edit',[ProductController::class, 'edit'])->name('products.edit');
        Route::post('/products/{product}',[ProductController::class, 'update'])->name('products.update'); //edit submit
        Route::delete('/products/{product}',[ProductController::class, 'destroy'])->name('products.delete');


        Route::get('/product-subcategories',[ProductSubCategoryController::class, 'index'])->name('product-subcategories.index'); //Selecting subcategory auto when selecting category on create products 

        //product-Images in update 
        Route::post('/product-images/update',[ProductImageController::class, 'create'])->name('product-images.update');


        //temp-images.create
        Route::post('/upload-temp-image',[TempImagesController::class,'create'])->name('temp-images.create');

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