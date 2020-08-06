<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();

Route::get('/', 'HomeController@index')->name('home');

Route::group(['middleware' => ['web', 'usercheck:1']], function(){
	Route::get('category/data', 'CategoryController@listData')->name('category.data');
	Route::resource('category', 'CategoryController');

	Route::get('divisions/data', 'DivisionController@listData')->name('divisions.data');
	Route::resource('divisions', 'DivisionController');

	Route::get('units/data', 'UnitController@listData')->name('units.data');
	Route::resource('units', 'UnitController');

	Route::get('payments/data', 'PaymentController@listData')->name('payments.data');
	Route::resource('payments', 'PaymentController');


	Route::get('stockin/data', 'StockController@listDataStockIn')->name('stockin.data');
	Route::get('stockin', 'StockController@indexStockIn')->name('stockin.index');
	Route::post('stockin', 'StockController@store')->name('stockin.store');
	Route::get('stockin/{id}/edit', 'StockController@edit')->name('stockin.edit');
	Route::patch('stockin/{id}', 'StockController@update')->name('stockin.update');
	Route::delete('stockin/{id}', 'StockController@destroy')->name('stockin.destroy');

	Route::get('stockout/data', 'StockController@listDataStockOut')->name('stockout.data');
	Route::get('stockout', 'StockController@indexStockOut')->name('stockout.index');
	Route::post('stockout', 'StockController@store')->name('stockout.store');
	Route::get('stockout/{id}/edit', 'StockController@edit')->name('stockout.edit');
	Route::patch('stockout/{id}', 'StockController@update')->name('stockout.update');
	Route::delete('stockout/{id}', 'StockController@destroy')->name('stockout.destroy');


	Route::get('product/data', 'ProductController@listData')->name('product.data');
	Route::post('product/delete', 'ProductController@deleteSelected');
	Route::post('product/print', 'ProductController@printBarcode');
	Route::post('product/print_stock', 'ProductController@printProductStock');
	Route::resource('product', 'ProductController');

	Route::get('supplier/data', 'SupplierController@listData')->name('supplier.data');
	Route::resource('supplier', 'SupplierController');

	Route::get('preorders/data/{begin}/{end}/{division}', 'PreOrderController@listData')->name('preorders.data');
	Route::get('preorders/report/{begin}/{end}/{division}', 'PreOrderController@report')->name('preorders.report');
	Route::post('preoders/send', 'PreOrderController@sendWhatsApp')->name('preorders.send');
	Route::resource('preorders', 'PreOrderController');
	Route::get('preorders/{id}/show', 'PreOrderController@show')->name('preorders.show');

	Route::resource('repayments', 'RepaymentController')->except([
		"index"
	]);
	Route::get('repayments/{id}/show', 'RepaymentController@show')->name('repayments.show');

	Route::get('supplier_products/data/{id}', 'SupplierProductController@listData')->name('supplier_product.data');
	Route::get('supplier_products/{id}', 'SupplierProductController@index')->name('supplier_product.index');
	Route::post('supplier_products', 'SupplierProductController@store')->name('supplier_product.store');
	Route::get('supplier_products/{id}/edit', 'SupplierProductController@edit')->name('supplier_product.edit');
	Route::patch('supplier_products/{id}', 'SupplierProductController@update')->name('supplier_product.update');
	Route::delete('supplier_products/{id}', 'SupplierProductController@destroy')->name('supplier_product.destroy');

	Route::get('member/data', 'MemberController@listData')->name('member.data');
	Route::post('member/print', 'MemberController@printCard');
	Route::resource('member', 'MemberController');

	Route::get('spending/data/{begin}/{end}/{division}/{payment}', 'SpendingController@listData')->name('spending.data');
	Route::resource('spending', 'SpendingController');

	Route::get('credits/data/{begin}/{end}/{division}/{payment}', 'CreditController@listData')->name('credits.data');
	Route::resource('credits', 'CreditController');

	Route::get('user/data', 'UserController@listData')->name('user.data');
	Route::resource('user', 'UserController');

	Route::get('purchase/data', 'PurchaseController@listData')->name('purchase.data');
	Route::get('purchase/{id}/add', 'PurchaseController@create');
	Route::get('purchase/{id}/show', 'PurchaseController@show');
	Route::resource('purchase', 'PurchaseController');

	Route::get('purchase_details/{id}/data', 'PurchaseDetailsController@listData')->name('purchase_details.data');
	Route::get('purchase_details/loadform/{discount}/{total}', 'PurchaseDetailsController@loadForm');
	Route::resource('purchase_details', 'PurchaseDetailsController');

	Route::get('selling/data', 'SellingController@listData')->name('selling.data');
	Route::get('selling/{id}/show', 'SellingController@show');
	Route::resource('selling', 'SellingController');

	Route::get('report', 'ReportController@index')->name('report.index');
	Route::post('report', 'ReportController@refresh')->name('report.refresh');
	Route::get('report/data/{begin}/{end}', 'ReportController@listData')->name('report.data');
	Route::get('report/pdf/{begin}/{end}', 'ReportController@exportPDF');

	Route::get('accounting-report', 'ReportController@reportAccounting')->name('accountingreports.index');
	Route::post('accounting-report', 'ReportController@refreshAccounting')->name('accountingreports.refresh');
	Route::get('accounting-report/data/{begin}/{end}/{division}/{payment}', 'ReportController@reportAccountingList')->name('accountingreports.data');
	Route::get('accounting-report/pdf/{begin}/{end}/{division}/{payment}', 'ReportController@exportAccountingPDF')->name('accountingreports.pdf');
	
	Route::get('debit-report', 'ReportController@reportdebit')->name('debitreports.index');
	Route::get('debit-report/data/{begin}/{end}/{division}/{member}', 'ReportController@reportDebitData')->name('debitreports.data');
	Route::get('debit-report/pdf/{begin}/{end}/{division}/{member}', 'ReportController@exportPDFDebit')->name('debitreports.pdf');

	Route::resource('setting', 'SettingController');

	Route::get('transaction/new', 'SellingDetailsController@newSession')->name('transaction.new');
	Route::get('transaction/{id}/data', 'SellingDetailsController@listData')->name('transaction.data');
	Route::get('transaction/printnote', 'SellingDetailsController@printNote')->name('transaction.print');
	Route::get('transaction/notepdf', 'SellingDetailsController@notePDF')->name('transaction.pdf');
	Route::post('transaction/save', 'SellingDetailsController@saveData');
	Route::get('transaction/loadform/{discount}/{total}/{received}', 'SellingDetailsController@loadForm');
	Route::resource('transaction', 'SellingDetailsController');

});

Route::group(['middleware' => 'web'], function(){
	Route::get('accounting-report', 'ReportController@reportAccounting')->name('accountingreports.index');
	Route::post('accounting-report', 'ReportController@refreshAccounting')->name('accountingreports.refresh');
	Route::get('accounting-report/data/{begin}/{end}/{division}/{payment}', 'ReportController@reportAccountingList')->name('accountingreports.data');
	Route::get('accounting-report/pdf/{begin}/{end}/{division}/{payment}', 'ReportController@exportAccountingPDF')->name('accountingreports.pdf');


	Route::get('user/profile', 'UserController@show')->name('user.profile');
	Route::patch('user/{id}/change', 'UserController@changeProfile');

	Route::get('transaction/new', 'SellingDetailsController@newSession')->name('transaction.new');
	Route::get('transaction/{id}/data', 'SellingDetailsController@listData')->name('transaction.data');
	Route::get('transaction/printnote', 'SellingDetailsController@printNote')->name('transaction.print');
	Route::get('transaction/notepdf', 'SellingDetailsController@notePDF')->name('transaction.pdf');
	Route::post('transaction/save', 'SellingDetailsController@saveData');
	Route::get('transaction/loadform/{discount}/{total}/{received}', 'SellingDetailsController@loadForm');
	Route::resource('transaction', 'SellingDetailsController');

	Route::get('category/data', 'CategoryController@listData')->name('category.data');
	Route::resource('category', 'CategoryController');

	Route::get('units/data', 'UnitController@listData')->name('units.data');
	Route::resource('units', 'UnitController');

	Route::get('product/data', 'ProductController@listData')->name('product.data');
	Route::post('product/delete', 'ProductController@deleteSelected');
	Route::post('product/print', 'ProductController@printBarcode');
	Route::post('product/print_stock', 'ProductController@printProductStock');
	Route::resource('product', 'ProductController');

	Route::get('stockin/data', 'StockController@listDataStockIn')->name('stockin.data');
	Route::get('stockin', 'StockController@indexStockIn')->name('stockin.index');
	Route::post('stockin', 'StockController@store')->name('stockin.store');
	Route::get('stockin/{id}/edit', 'StockController@edit')->name('stockin.edit');
	Route::patch('stockin/{id}', 'StockController@update')->name('stockin.update');
	Route::delete('stockin/{id}', 'StockController@destroy')->name('stockin.destroy');

	Route::get('stockout/data', 'StockController@listDataStockOut')->name('stockout.data');
	Route::get('stockout', 'StockController@indexStockOut')->name('stockout.index');
	Route::post('stockout', 'StockController@store')->name('stockout.store');
	Route::get('stockout/{id}/edit', 'StockController@edit')->name('stockout.edit');
	Route::patch('stockout/{id}', 'StockController@update')->name('stockout.update');
	Route::delete('stockout/{id}', 'StockController@destroy')->name('stockout.destroy');

});