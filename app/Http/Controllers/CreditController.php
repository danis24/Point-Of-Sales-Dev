<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DataTables;
use App\Division;
use App\Payment;
use App\Credit;

class CreditController extends Controller
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
        $divisions = $this->division->get();
        $payments = $this->payment->get();
        $begin = date('Y-m-d', mktime(0, 0, 0, date('m'), 1, date('Y')));
		$end = date('Y-m-d');
		$division = 0;
		$payment = 0;
        return view('credit.index', compact('divisions', 'payments', 'begin', 'end', 'division', 'payment'));
    }

    protected function getCreditDetail($begin, $end, $division = 0, $payment = 0)
    {
        $credits = Credit::whereBetween('created_at', [$begin . " 00:00:00", $end . " 23:59:59"])->orderBy('id', 'asc');
        if($division != 0){
            $credits->where("division_id", $division);
        }

        if($payment != 0){
            $credits->where("payment_id", $division);
        }
        return $credits->get();
    }

    public function listData($begin, $end, $division, $payment){
        $credits = $this->getCreditDetail($begin, $end, $division, $payment);
        $no = 0;
        $data = array();
        $nominal_count = 0;
        foreach ($credits as $list) {
            $no ++;
            $row = array();
            $row[] = $no;
            $row[] = indo_date(substr($list->created_at, 0, 10), false);
            $row[] = $list->division->name;
            if($list->payment->type == "cash"){
                $row[] = "CASH";
            }else{
                $row[] = $list->payment->bank_name." - ".$list->payment->account_number." - ".$list->payment->account_name;
            }
            $row[] = $list->description;
            $row[] = "Rp. " . currency_format($list->nominal);
            $row[] = '<tr>
                     <div class="dropdown d-inline">
                      <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Aksi
                      </button>
                      <div class="dropdown-menu">
                        <a onclick="editForm('.$list->id.')" class="dropdown-item has-icon"><i class="fas fa-edit"></i>Edit Data</a>
                        <a onclick="deleteData('.$list->id.')" class="dropdown-item has-icon"><i class="fas fa-trash"></i>Hapus Data</a>
                      </div></tr>';
            $data[] = $row;
            $nominal_count += $list->nominal;
        }
        $data[] = ["", "", "", "", "", "Rp.".currency_format($nominal_count), ""];
        return DataTables::of($data)->escapeColumns([])->make(true);
    }
    public function store(Request $request){
        $credit = new Credit;
        $credit->description = $request['description'];
        $credit->nominal = $request['nominal'];
        $credit->division_id = $request['division_id'];
        $credit->payment_id = $request['payment_id'];
        $credit->created_at = $request['created_at'];
        $credit->save();
    }
    public function edit($id){
        $credit = Credit::find($id);
        echo json_encode($credit);
    }
    public function update(Request $request, $id){
        $credit = Credit::find($id);
        $credit->description = $request['description'];
        $credit->nominal = $request['nominal'];
        $credit->division_id = $request['division_id'];
        $credit->payment_id = $request['payment_id'];
        $credit->created_at = $request['created_at'];
        $credit->update();
    }
    public function destroy($id){
        $spending = Credit::find($id);
        $spending->delete();
    }
}