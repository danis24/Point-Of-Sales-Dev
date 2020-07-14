<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Redirect;
use App\Selling;
use App\Product;
use App\Member;
use App\SellingDetails;
use App\Division;
use App\Payment;

class SellingController extends Controller
{
    protected $division;
    protected $payment;

    public function __construct()
    {
        $this->middleware("auth");
        $this->division = new Division;
        $this->payment = new Payment;
    }

    public function index(){
        return view('selling.index');
    }

    public function listData(){
        $selling = Selling::join('users', 'users.id', '=', 'selling.users_id')->join('divisions', 'divisions.id', '=', 'selling.division_id')->join('payments', 'payments.id', '=', 'selling.payment_id')->select('users.*', 'selling.*', 'divisions.name as division_name', 'payments.type as payment_type', 'payments.bank_name as payment_bank_name', 'payments.account_number as payment_account_number', 'payments.account_name as payment_account_name', 'selling.created_at as date')->orderBy('selling.selling_id', 'desc')->get();
        $no = 0;
        $data = array();
        foreach ($selling as $list) {
            $sellingDetails = $this->sellingDetail($list->selling_id);
            $products = "<ul>";
            foreach($sellingDetails as $key => $value){
                $products .= "<li>".$value[2]." (".$value[3]." = ".$value[4].") </li>";
            }
            $products .= "</ul>";
            $no ++;
            $row = array();
            $row[] = $no;
            $row[] = indo_date(substr($list->date, 0, 10), false);
            $row[] = $list->division_name;
            if($list->member_code == 0){
                $row[] = "UMUM";
            }else{
                $row[] = $list->member_code;
            }
            $row[] = $products;
            $row[] = $list->total_item;
            $row[] = "Rp. ".currency_format($list->total_price);
            $row[] = $list->discount."%";
            $row[] = "Rp. ".currency_format($list->pay);
            if($list->payment_type == "cash"){
                $row[] = "CASH";
            }else{
                $row[] = $list->payment_bank_name." - ".$list->payment_account_number." - ".$list->payment_account_name;
            }
            $row[] = $list->name;
            $row[] = '<tr>
                     <div class="dropdown d-inline">
                      <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Aksi
                      </button>
                      <div class="dropdown-menu">
                        <a onclick="showDetail('.$list->selling_id.')" class="dropdown-item has-icon"><i class="fas fa-eye"></i>Lihat Data</a>
                        <a onclick="deleteData('.$list->selling_id.')" class="dropdown-item has-icon"><i class="fas fa-trash"></i>Hapus Data</a>
                      </div></tr>';
            $data[] = $row;
        }
        $output = array("data" => $data);
        return response()->json($output);
    }

    public function sellingDetail($id)
    {
        $detail = SellingDetails::leftJoin('product', 'product.product_code', '=', 'selling_details.product_code')->where('selling_id', '=', $id)->get();
        $no = 0;
        $data = array();
        foreach ($detail as $list) {
            $no ++;
            $row = array();
            $row[] = $no;
            $row[] = $list->product_code;
            $row[] = $list->product_name;
            $row[] = "Rp. ".currency_format($list->selling_price);
            $row[] = $list->total;
            $row[] = "Rp. ".currency_format($list->sub_total);
            $data[] = $row;
        }
        return $data;
    }

    public function show($id){
        $data = $this->sellingDetail($id);
        $output = array("data" => $data);
        return response()->json($output);
    }
    
    public function destroy($id){
        $selling = Selling::find($id);
        $selling->delete();

        $detail = SellingDetails::where('selling_id', '=', $id)->get();
        foreach ($detail as $data) {
        	$product = Product::where('product_code', '=', $data->product_code)->first();
        	$product->stock += $data->total;
        	$data->update();
        	$data->delete();
        }
    }
}
