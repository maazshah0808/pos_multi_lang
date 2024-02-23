<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReturnController;



Route::middleware(['auth:sanctum', config('jetstream.auth_session'),'verified'])->group(function () {

    //dashboard
    //Route::get('/', function () { return view('dashboard'); })->name('dashboard');
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');


        //brand
    Route::resource('brand',BrandController::class);
    Route::put('brandupdate',[BrandController::class, 'update']);


        // category controller
    Route::resource('category',CategoryController::class);
    Route::put('cat_update',[CategoryController::class, 'update']);


        // unit controller
    Route::resource('unit',UnitController::class);
    Route::put('unitupdate',[UnitController::class, 'update']);


      // product controller
    Route::resource('product',ProductController::class);
    Route::put('produtupdate',[ProductController::class, 'update']);

    //sales controller
    Route::resource('sales',SalesController::class);
    Route::get('getStockProduct', [SalesController::class, 'getstockproduct'])->name('getStockProduct');
    Route::get('addProductSaleList', [SalesController::class, 'addproductsalelist'])->name('addProductSaleList');


    //stock 
    Route::resource('stock', StockController::class);
    Route::get('getProduct', [StockController::class, 'getproduct'])->name('getProduct');
    Route::get('getProductUnit', [StockController::class, 'getproductunit'])->name('getProductUnit');

    //get purchase stock
    Route::get('getavlstock', [StockController::class, 'getavalstock'])->name('getavlstock');


    //stock listing 
    Route::get('getstocklist', [StockController::class, 'getstock'])->name('getstocklist');
    Route::get('getsview/{id}', [StockController::class, 'getstockview'])->name('getsview');


      //Vendor 
    Route::resource('vendor',VendorController::class);
    Route::put('vendorupdate',[VendorController::class, 'update']);
    Route::get('getLedgerList',[VendorController::class, 'getledgerList'])->name('getLedgerList');
    Route::post('savepayment',[VendorController::class, 'addpayment'])->name('savepayment');
    //General Ledger
    Route::get('GeneralLedger',[VendorController::class, 'getgl'])->name('GeneralLedger');



    //invoice listing
    Route::get('getinvlist',[SalesController::class, 'getinvlistin'])->name('getinvlist');
    Route::get('getprint/{id}',[SalesController::class, 'getprint'])->name('getprint');



     //return 
     Route::resource('salereturn',ReturnController::class);
     Route::get('getreturnlist',[ReturnController::class, 'getreturnlist'])->name('getreturnlist');

});
