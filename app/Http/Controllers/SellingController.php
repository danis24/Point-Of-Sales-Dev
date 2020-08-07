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
use PDF;

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
        $begin = date('Y-m-d', mktime(0, 0, 0, date('m'), 1, date('Y')));
        $end = date('Y-m-d');
        $divisions = Division::all();
        $division = 1;
        return view('selling.index', compact('begin', 'end', 'divisions', 'division'));
    }

    public function sellingExportDetail($begin, $end, $division)
    {
        $division_name = Division::where("id", $division)->first()->name;
        $sellings = Selling::select("selling.selling_id as selling_id", "selling.created_at as created_at", "member.member_name as member_name", "selling.total_item as total_item", "selling.total_price as total_price")->whereBetween('selling.created_at', [$begin . " 00:00:00", $end . " 23:59:59"])->where('selling.division_id', $division)->join('member', 'member.member_code', '=', 'selling.member_code')->orderBy('selling.created_at', 'asc')->get();
        $data = [];
        $count_item = 0;
        $count_price = 0;
        if($sellings->count() > 0){
            foreach($sellings as $key => $value){
                $sellingDetails = $this->sellingDetail($value->selling_id);
                $selling_detail_data = [];
                if(count($sellingDetails) > 0){
                    foreach($sellingDetails as $k => $v){
                        $selling_detail_data[] = $v;
                    }
                }
                $data[] = [
                    "created_at" => $value->created_at,
                    "member_name" => $value->member_name,
                    "selling_detail_data" => $selling_detail_data,
                    "total_item" => $value->total_item,
                    "total_price" => $value->total_price
                ];
            $count_item += $value->total_item;
            $count_price += $value->total_price;
            }
        }
        $sellingDetailGroup = SellingDetails::whereBetween('selling.created_at', [$begin . " 00:00:00", $end . " 23:59:59"])->join('selling', 'selling.selling_id', '=', 'selling_details.selling_id')->join('product', 'product.product_code', '=', 'selling_details.product_code')->where('selling.division_id', $division)->get();
        $product_group = [];
        if($sellingDetailGroup->count() > 0){
            foreach($sellingDetailGroup as $key => $value){
                $product_group[] = [
                    "product_name" => $value->product_name,
                    "product_code" => $value->product_code,
                    "total" => $value->total
                ];
            }
        }

        $result_selling = array();
        $prev_value = array('product_name' => null, 'product_code' => null, 'total' => null);

        foreach ($product_group as $val) {
            if ($prev_value['product_code'] != $val['product_code']) {
                unset($prev_value);
                $prev_value = array('product_name' => $val['product_name'], 'product_code' => $val['product_code'], 'total' => 0);
                $result_selling[] =& $prev_value;
            }
            $prev_value['total'] += $val['total'];
        }
        $pdf = PDF::loadView('selling.pdf', compact('begin', 'end', 'division', 'sellings', 'division_name', 'data', 'count_item', 'count_price', 'result_selling'));
        $pdf->setPaper('a4', 'landscape');
        return $pdf->stream();
    }

    public function listData($begin, $end, $division){
        $selling = Selling::whereBetween('selling.created_at', [$begin . " 00:00:00", $end . " 23:59:59"])->join('users', 'users.id', '=', 'selling.users_id')->join('divisions', 'divisions.id', '=', 'selling.division_id')->join('payments', 'payments.id', '=', 'selling.payment_id')->select('users.*', 'selling.*', 'divisions.name as division_name', 'payments.type as payment_type', 'payments.bank_name as payment_bank_name', 'payments.account_number as payment_account_number', 'payments.account_name as payment_account_name', 'selling.created_at as date')->orderBy('selling.selling_id', 'desc')->where("selling.division_id", $division)->get();
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
