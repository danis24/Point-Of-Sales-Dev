<?php

namespace App\Http\Controllers;
use App\Spending;
use Illuminate\Http\Request;
use DataTables;
use App\Division;
use App\Payment;

class SpendingController extends Controller
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
        return view('spending.index', compact('divisions', 'payments'));
    }

    public function listData(){
        $spending = Spending::orderBy('spending_id', 'asc')->get();
        $no = 0;
        $data = array();
        foreach ($spending as $list) {
            $no ++;
            $row = array();
            $row[] = $no;
            $row[] = indo_date(substr($list->created_at, 0, 10), false);
            $row[] = $list->division->name;
            $row[] = $list->spending_type;
            $row[] = "Rp. " . currency_format($list->nominal);
            if($list->payment->type == "cash"){
                $row[] = "CASH";
            }else{
                $row[] = $list->payment->bank_name." - ".$list->payment->account_number." - ".$list->payment->account_name;
            }
            $row[] = '<tr>
                     <div class="dropdown d-inline">
                      <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Aksi
                      </button>
                      <div class="dropdown-menu">
                        <a onclick="editForm('.$list->spending_id.')" class="dropdown-item has-icon"><i class="fas fa-edit"></i>Edit Data</a>
                        <a onclick="deleteData('.$list->spending_id.')" class="dropdown-item has-icon"><i class="fas fa-trash"></i>Hapus Data</a>
                      </div></tr>';
            $data[] = $row;
        }
        return DataTables::of($data)->escapeColumns([])->make(true);
    }
    public function store(Request $request){
        $spending = new Spending;
        $spending->spending_type = $request['spending_type'];
        $spending->nominal = $request['nominal'];
        $spending->division_id = $request['division_id'];
        $spending->payment_id = $request['payment_id'];
        $spending->save();
    }
    public function edit($id){
        $spending = Spending::find($id);
        echo json_encode($spending);
    }
    public function update(Request $request, $id){
        $spending = Spending::find($id);
        $spending->spending_type = $request['spending_type'];
        $spending->nominal = $request['nominal'];
        $spending->division_id = $request['division_id'];
        $spending->payment_id = $request['payment_id'];
        $spending->update();
    }
    public function destroy($id){
        $spending = Spending::find($id);
        $spending->delete();
    }
}
