<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PreOrder;
use App\Member;
use App\Repayment;
use App\Division;
use App\Payment;
use PDF;

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

		$begin = date('Y-m-d', mktime(0,0,0, date('m'), 1, date('Y')));
		$end = date('Y-m-d');
		$division = 0;
        return view("preorders.index", compact("members", "divisions", "payments", "begin", "end", "division"));
    }

    protected function getPreOrderFilter($begin, $end, $division)
    {
        $preorders = $this->model->whereBetween("date", [$begin, $end])->orderBy('id', 'asc');
        if($division != 0){
			$preorders->where("division_id", $division);
        }
        return $preorders->get();
    }

    public function report($begin, $end, $division)
    {
        return PDF::loadHTML($this->resultHtml($begin, $end, $division))->setPaper('a4', 'landscape')->stream('download.pdf');
    }

    public function resultHtml($begin, $end, $division)
    {
        $data = $this->getPreOrderFilter($begin, $end, $division);
        $output = "<h1>Laporan Pre Order</h1>";
        $output .= "<h3>Periode ".indo_date($begin, false)." s/d ".indo_date($end, false)."</h3><br><hr>";
        $output .= "<style>
            .vuln {
                font-size: 12px;
            font-family: 'Trebuchet MS', Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
            }

            .vuln td, .vuln th {
            border: 1px solid #ddd;
            padding: 8px;
            word-wrap:break-word
            }

            .vuln tr:nth-child(even){background-color: #f2f2f2;}

            .vuln tr:hover {background-color: #ddd;}

            .vuln th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: left;
            background-color: #131633;
            color: white;
            }
            table{
            table-layout: fixed;
            }
            </style>";
        $output .= "<table class='vuln'><thead><tr><th width='4%'>No</th><th width='10%'>Tanggal</th><th width='10%'>Divisi</th><th width='15%'>Nama</th><th width='18%'>Rincian</th><th width='6%'>QTY</th><th>Harga</th><th>Total Harga</th><th>Pelunasan</th><th>Sisa</th></tr></thead><tbody>";

        $count_qty = 0;
        $count_total_price = 0;
        $count_total_repayment = 0;
        $count_reminder = 0;

        foreach ($data as $key => $value) {
            $repayments = $this->repayment->where("pre_order_id", $value->id)->get();
            $repayment_count = 0;
            if($repayments->count() > 0){
                foreach($repayments as $k => $v){
                    $repayment_count += $v->nominal;
                }
            }
            $total_price = $value->price*$value->qty;
            $reminder = $total_price-$repayment_count;
            
            $count_total_price += $total_price;
            $count_total_repayment += $repayment_count;
            $count_reminder += $reminder;
            $count_qty += $value->qty;

            $output .= "<td>".($key+1)."</td>";
            $output .= "<tr><td>".indo_date($value->date, false)."</td>";
            $output .= "<td>".$value->division->name."</td>";
            $output .= "<td>".$value->member->member_name."</td>";
            $output .= "<td>".$value->details."</td>";
            $output .= "<td>".$value->qty."</td>";
            $output .= "<td align='center'>"."Rp.".currency_format($value->price)."</td>";
            $output .= "<td align='center'>"."Rp.".currency_format($total_price)."</td>";
            $output .= "<td align='center'>"."Rp.".currency_format($repayment_count)."</td>";
            $output .= "<td align='center'>"."Rp.".currency_format($reminder)."</td>";
            $output .= "</tr>";
        }
        $output .= "<tr>";
        $output .= "<td colspan='5'></td>";
        $output .= "<td>".$count_qty."</td>";
        $output .= "<td></td>";
        $output .= "<td><b>Rp.".currency_format($count_total_price)."</b></td>";
        $output .= "<td><b>Rp.".currency_format($count_total_repayment)."</b></td>";
        $output .= "<td><b>Rp.".currency_format($count_reminder)."</b></td>";
        $output .= "</tr>";
        $output .= "</tbody></table>";
        return $output;
    }


    public function listData($begin, $end, $division)
    {
        $preorders = $this->getPreOrderFilter($begin, $end, $division);   
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
            $row[] = $value->division->name;
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
        $data[] = array("","", "","","","","","<b>Rp.".currency_format($count_total_price)."</b>", "<b>Rp.".currency_format($count_total_repayment)."</b>", "<b>Rp.".currency_format($count_reminder)."</b>","","");
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
            $row[] = "Rp.".currency_format($value->price);
            $row[] = "Rp.".currency_format($total_price);
            $row[] = "Rp.".currency_format($repayment_count);
            $row[] = "Rp.".currency_format($reminder);
            if($reminder > 0){
                $row[] = "<label class='badge badge-danger'>Belum Lunas</label>";
            }else{
                $row[] = "<label class='badge badge-success'>Lunas</label>";
            }
            $row[] = "<a href='https://api.whatsapp.com/send?phone=".$value->member->member_phone_number."&text=".$this->whatsAppDebitText($value, $repayment_count,$reminder)."' target='_blank' class='btn btn-success'><i class='fab fa-whatsapp'></i> Tagih Piutang </a>";
            $data[] = $row;
            $total_price_count += $total_price;
            $repayment_count_result += $repayment_count;
            $reminder_count += $reminder;
        }
        $data[] = [
            "", "", "", "", "", "", "<b><h4>Rp. ".currency_format($total_price_count)."</h4></b>", "<b><h4>Rp. ".currency_format($repayment_count_result)."</h4></b>", "<b><h4>Rp. ".currency_format($reminder_count)."</h4></b>", "", ""
        ];
        $output = array("data" => $data);
        return response()->json($output);
    }

    protected function whatsAppDebitText($data, $repayment_count, $reminder)
    {
        $text = "Halo Bpk/Ibu *".$data->member->member_name."* %0a%0a Berikut merupakan tagihan dengan rincian : %0a===================%0aTanggal PO: ".indo_date($data->date)."%0aDetail PO : ".$data->details."%0aQty: ".$data->qty."%0aHarga Satuan : Rp.".currency_format($data->price)."%0aTotal Harga : Rp.".currency_format($data->qty*$data->price)."%0a===================%0aSudah Di Bayar : Rp.".currency_format($repayment_count)."%0aSisa : Rp.".currency_format($reminder)."%0a%0aMohon Untuk Segera Dilunasi Secepatnya,%0a%0aSalam, %0a ERSO PRIDATAMA (DIVISI ".$data->division->name.")";
        return $text;
    }

    public function store(Request $request)
    {
        $preorder = $this->model->create([
            "division_id" => $request->division_id,
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
