<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PreOrder;
use App\Member;
use App\Repayment;
use App\Division;
use App\Payment;

class PreOrderController extends Controller
{
    protected $model;
    protected $member;
    protected $repayment;
    protected $division;
    protected $payment;

    public function __construct()
    {
        $this->middleware("auth");
        $this->model = new PreOrder;
        $this->member = new Member;
        $this->repayment = new Repayment;
        $this->division = new Division;
        $this->payment = new Payment;
    }

    public function index()
    {
        $members = $this->member->get();
        $divisions = $this->division->get();
        $payments = $this->payment->get();
        return view("preorders.index", compact("members", "divisions", "payments"));
    }

    public function listData()
    {
        $preorders = $this->model->orderBy('id', 'asc')->get();
        $no = 0;
        $data = array();
        $count_total_price = 0;
        $count_total_repayment = 0;
        $count_reminder = 0;

        foreach ($preorders as $key => $value) {
            $repayments = $this->repayment->where("pre_order_id", $value->id)->get();
            $repayment_count = 0;
            if($repayments->count() > 0){
                foreach($repayments as $k => $v){
                    $repayment_count += $v->nominal;
                }
            }
            $total_price = $value->price*$value->qty;
            $reminder = $total_price-$repayment_count;
            $no ++;
            $row = array();
            $row[] = $no;
            $row[] = indo_date($value->date, false);
            $row[] = $value->member->member_name;
            $row[] = $value->details;
            $row[] = $value->qty;
            $row[] = "Rp.".currency_format($value->price);
            $row[] = "Rp.".currency_format($total_price);
            $row[] = "Rp.".currency_format($repayment_count);
            $row[] = "Rp.".currency_format($reminder);
            $count_total_price += $total_price;
            $count_total_repayment += $repayment_count;
            $count_reminder += $reminder;
            if($reminder > 0){
                $row[] = "<label class='badge badge-danger'>Belum Lunas</label>";
            }else{
                $row[] = "<label class='badge badge-success'>Lunas</label>";
            }
            $bayar = "";
            if($reminder > 0){
                $bayar = '<a onclick="payForm('.$value->id.')" class="dropdown-item has-icon"><i class="fas fa-hand-holding-usd"></i>Bayar</a>';
            }
            $row[] = '<tr>
                    <div class="dropdown d-inline">
                      <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Aksi
                      </button>
                      <div class="dropdown-menu">
                        '.$bayar.'
                        <a onclick="showDetail('.$value->id.')" class="dropdown-item has-icon"><i class="fas fa-boxes"></i>Detail Pembayaran</a>
                        <a onclick="editForm('.$value->id.')" class="dropdown-item has-icon"><i class="fas fa-edit"></i>Edit Data</a>
                        <a onclick="deleteData('.$value->id.')" class="deleteData dropdown-item has-icon"><i class="fas fa-trash"></i>Hapus Data</a>
                      </div>
                     </tr>';
            $data[] = $row;
        }
        $data[] = array("","","","","","","<b>Rp.".currency_format($count_total_price)."</b>", "<b>Rp.".currency_format($count_total_repayment)."</b>", "<b>Rp.".currency_format($count_reminder)."</b>","","");
        $output = array("data" => $data);
        return response()->json($output);
    }

    public function show($id)
    {
        $preorders = $this->model->where("member_id", $id)->get();
        $no = 0;
        $data = array();
        $total_price_count = 0;
        $repayment_count_result = 0;
        $reminder_count = 0;
        foreach ($preorders as $key => $value) {
            $repayments = $this->repayment->where("pre_order_id", $value->id)->get();
            $repayment_count = 0;
            if($repayments->count() > 0){
                foreach($repayments as $k => $v){
                    $repayment_count += $v->nominal;
                }
            }
            $total_price = $value->price*$value->qty;
            $reminder = $total_price-$repayment_count;
            $no ++;
            $row = array();
            $row[] = $no;
            $row[] = indo_date($value->date, false);
            $row[] = $value->member->member_name;
            $row[] = $value->details;
            $row[] = $value->qty;
            $row[] = "Rp. ".currency_format($value->price);
            $row[] = "Rp. ".currency_format($total_price);
            $row[] = "Rp. ".currency_format($repayment_count);
            $row[] = "Rp. ".currency_format($reminder);
            if($reminder > 0){
                $row[] = "<label class='badge badge-danger'>Belum Lunas</label>";
            }else{
                $row[] = "<label class='badge badge-success'>Lunas</label>";
            }
            $data[] = $row;
            $total_price_count += $total_price;
            $repayment_count_result += $repayment_count;
            $reminder_count += $reminder;
        }
        $data[] = [
            "", "", "", "", "", "", "<b><h4>Rp. ".currency_format($total_price_count)."</h4></b>", "<b><h4>Rp. ".currency_format($repayment_count_result)."</h4></b>", "<b><h4>Rp. ".currency_format($reminder_count)."</h4></b>", ""
        ];
        $output = array("data" => $data);
        return response()->json($output);
    }

    public function store(Request $request)
    {
        $preorder = $this->model->create([
            "date" => $request->date,
            "member_id" => $request->member_id,
            "details" => $request->details,
            "qty" => $request->qty,
            "price" => $request->price
        ]);
        if($preorder){
            return response()->json([
                "status" => "sucess"
            ]);
        }
        return response()->json([
            "status" => "failed"
        ]);
    }

    public function edit($id)
    {
        $preorder = $this->model->findOrFail($id);
        return response()->json($preorder);
    }

    public function update($id, Request $request)
    {
        $preorder = $this->model->findOrFail($id);
        $preorder->update($request->all());
        if($preorder){
            return response()->json([
                "status" => "success"
            ]);
        }
        return response()->json([
            "status" => "failed"
        ]);
    }

    public function destroy($id)
    {
        $preorder = $this->model->destroy($id);
        if($preorder){
            return response()->json([
                "status" => "success"
            ]);
        }
        return response()->json([
            "status" => "failed"
        ]);
    }
}
