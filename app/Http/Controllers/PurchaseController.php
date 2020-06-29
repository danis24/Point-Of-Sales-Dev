<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Redirect;
use App\Purchase;
use App\Supplier;
use App\PurchaseDetails;
use App\Product;
use App\SupplierProduct;

class PurchaseController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }

	public function index(){
		$supplier = Supplier::all();
        return view('purchase.index', compact('supplier'));
    }
    public function listData(){
        $purchase = Purchase::leftJoin('supplier', 'supplier.supplier_id', '=', 'purchase.supplier_id')->orderBy('purchase.purchase_id', 'desc')->get();
        $no = 0;
        $data = array();
        foreach ($purchase as $list) {
            $no ++;
            $row = array();
            $row[] = $no;
            $row[] = indo_date(substr($list->created_at, 0, 10), false);
            $row[] = $list->division->name;
            $row[] = $list->supplier_name;
            $row[] = $list->total_item;
            $row[] = "Rp. ".currency_format($list->total_price);
            $row[] = $list->discount."%";
            if($list->payment->type == "cash"){
                $row[] = "CASH";
            }else{
                $row[] = $list->payment->bank_name." - ".$list->payment->account_number." - ".$list->payment->account_name;
            }
            $row[] = "Rp. ".currency_format($list->pay);
            $row[] = '<div class="dropdown d-inline">
                      <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Aksi
                      </button>
                      <div class="dropdown-menu">
                        <a onclick="showDetail('.$list->purchase_id.')" class="dropdown-item has-icon"><i class="fas fa-eye"></i>Lihat Data</a>
                        <a onclick="deleteData('.$list->purchase_id.')" class="dropdown-item has-icon"><i class="fas fa-trash"></i>Hapus Data</a>
                      </div>';
            $data[] = $row;
        }
        $output = array("data" => $data);
        return response()->json($output);
    }
    public function show($id){
        $detail = PurchaseDetails::leftJoin('supplier_products', 'supplier_products.id', '=', 'purchase_details.product_code')->where('purchase_id', '=', $id)->get();
        $no = 0;
        $data = array();
        foreach ($detail as $list) {
            $no ++;
            $row = array();
            $row[] = $no;
            $row[] = $list->product_name;
            $row[] = "Rp. ".currency_format($list->price);
            $row[] = $list->total;
            $row[] = "Rp. ".currency_format($list->price * $list->total);
            $data[] = $row;
        }
        $output = array("data" => $data);
        return response()->json($output);
    }
    public function create($id){
        $purchase = new Purchase;
        $purchase->supplier_id = $id;
        $purchase->total_item = 0;
        $purchase->total_price = 0;
        $purchase->discount = 0;
        $purchase->pay = 0;
        $purchase->save();

        session(['purchase_id' => $purchase->purchase_id]);
        session(['supplier_id' => $id]);

        return Redirect::route('purchase_details.index');
    }
    public function store(Request $request){
        $purchase = Purchase::find($request['purchase_id']);
        $purchase->total_item = $request['total_item'];
        $purchase->total_price = $request['total'];
        $purchase->discount = $request['discount'];
        $purchase->pay = $request['pay'];
        $purchase->division_id = $request['division_id'];
        $purchase->payment_id = $request['payment_id'];
        $purchase->update();

        $detail = PurchaseDetails::where('purchase_id', '=', $request['purchase_id'])->get();
        foreach ($detail as $data) {
        	$product = SupplierProduct::where('id', '=', $data->product_code)->first();
        	$product->update();
        }
        return Redirect::route('purchase.index');
    }
    public function destroy($id){
        $purchase = Purchase::find($id);
        $purchase->delete();

        $detail = PurchaseDetails::where('purchase_id', '=', $id)->get();
        foreach ($detail as $data) {
        	$product = SupplierProduct::where('id', '=', $data->product_code)->first();
        	$product->update();
        	$data->delete();
        }
    }
}
