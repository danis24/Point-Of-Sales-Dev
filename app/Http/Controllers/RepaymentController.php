<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repayment;

class RepaymentController extends Controller
{
	protected $model;

    public function __construct()
    {
        $this->model = new Repayment;
        $this->middleware("auth");
    }

    public function store(Request $request)
    {
        $repayment = $this->model->create($request->all());
        if($repayment){
            return response()->json([
                "status" => "success"
            ]);
        }
        return response()->json([
            "status" => "failed"
        ]);
    }

    public function show($id)
    {
        $detail = $this->model->where("pre_order_id", $id)->get();
        $no = 0;
        $data = array();
        foreach ($detail as $list) {
            $no ++;
            $row = array();
            $row[] = $no;
            $row[] = indo_date($list->date, false);
            $row[] = $list->division->name;
            if($list->payment->type == "cash"){
                $row[] = "CASH";
            }else{
                $row[] = $list->payment->bank_name." - ".$list->payment->account_number." - ".$list->payment->account_name;
            }
            $row[] = "Rp. ".currency_format($list->nominal);
            $row[] = $list->details;
            $row[] = "<a onclick='deleteItem(".$list->id.")' class='btn btn-primary'><i class='fa fa-trash'></i></a> | <a href='https://api.whatsapp.com/send?phone=".$list->preOrder->member->member_phone_number."&text=".$this->repaymentWhatsappText($list)."' target='_blank' class='btn btn-warning'><i class='fab fa-whatsapp'></i></a>";
            $data[] = $row;
        }
        $output = array("data" => $data);
        return response()->json($output);
    }

    protected function repaymentWhatsappText($data)
    {
        $text = "Salam Bpk/Ibu *".$data->preOrder->member->member_name."* %0a%0a Terimakasi sudah melakukan pembayaran sebesar *Rp.".currency_format($data->nominal)."* dengan rincian sebagai berikut : %0a%0a Rincian : ".$data->preOrder->details."%0a Qty : ".$data->preOrder->qty."%0a Harga Satuan : Rp.".currency_format($data->preOrder->price)."%0a Total Harga : Rp.".currency_format($data->preOrder->qty*$data->preOrder->price)."%0a ================== %0a%0a Regard %0a ERSO PRIDATAMA (DIVISI ".$data->preOrder->division->name.")";
        return $text;
    }

    public function edit($id)
    {
        $repayment = $this->model->findOrFail($id);
        return response()->json($repayment);
    }

    public function destroy($id)
    {
        $repayment = $this->model->destroy($id);
        if($repayment){
            return response()->json([
                "status" => "success"
            ]);
        }
        return response()->json([
            "status" => "failed"
        ]);
    }
}
