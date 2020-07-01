<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Purchase;
use App\Selling;
use App\Spending;
use App\PreOrder;
use App\Repayment;
use App\Division;
use App\Payment;
use App\sellingDetails;
use App\PurchaseDetails;
use DB;
use PDF;

class ReportController extends Controller
{

    public function __construct()
    {
      $this->middleware("auth");
    }

   public function index()
   {
     $begin = date('Y-m-d', mktime(0,0,0, date('m'), 1, date('Y')));
     $end = date('Y-m-d');
     return view('report.index', compact('begin', 'end'));
   }

   public function reportAccounting()
   {
		$divisions = Division::all();
		$payments = Payment::all();
		$begin = date('Y-m-d', mktime(0,0,0, date('m'), 1, date('Y')));
		$end = date('Y-m-d');
		$division = 0;
		$payment = 0;
	   	return view("report.accountingreports", compact("divisions", "payments", "begin", "end", "division", "payment"));
   }

   public function refreshAccounting(Request $request)
   {
		$divisions = Division::all();
		$payments = Payment::all();
     	$begin = $request['begin'];
	 	$end = $request['end'];
	 	$division = $request['division'];
		$payment = $request['payment'];
		return view("report.accountingreports", compact("divisions", "payments", "begin", "end", "division", "payment"));
   }

   public function reportAccountingList($begin, $end, $division, $payment)
   {
		$data = $this->reportAccountingData($begin, $end, $division, $payment);
     	$output = array("data" => $data);
     	return response()->json($output);
   }

   protected function reportAccountingData($begin, $end, $division, $payment)
   {
		$selling = $this->sellingDetail($begin, $end, $division, $payment);
		$purchase = $this->purchaseDetail($begin, $end, $division, $payment);
		$spending = $this->spendingDetail($begin, $end, $division, $payment);
		$repayment = $this->repaymentDetail($begin, $end, $division, $payment);
		//Selling Data
		$selling_data = [];
		$total_income = 0;
		$total_spending = 0;
		if($selling->count() > 0){
			foreach($selling as $key => $value){
				if($value->division_id != null){
					$row = [];
					$row[] = indo_date($value->created_at, false);
					$row[] = $value->division->name;
					if($value->payment->type == "cash"){
						$row[] = "CASH";
					}else{
						$row[] = $value->payment->bank_name." - ".$value->payment->account_number." - ".$value->payment->account_name;
					}
					$selling_products = sellingDetails::where("selling_id", $value->selling_id)->join("product", "product.product_code", "=", "selling_details.product_code")->get();
					$product_lists = "";
					foreach($selling_products as $k => $v){
						$product_lists .= "<ul>";
						$product_lists .= "<li>".$v->product_name." [".$v->total." x ".currency_format($v->selling_price)." Discount : ".$v->discount."%]".
						$product_lists .= "</ul>";
					}
					$row[] = "Penjualan Produk : ".$product_lists;
					$row[] = $value->pay;
					$row[] = "";
					$selling_data[] = $row;
					$total_income += $value->pay;
				}
			}
		}
		//Purchase
		$purchase_data = [];
		if($purchase->count() > 0){
			foreach($purchase as $key => $value){
				$row = [];
				$row[] = indo_date($value->created_at, false);
				$row[] = $value->division->name;
				if($value->payment->type == "cash"){
					$row[] = "CASH";
				}else{
					$row[] = $value->payment->bank_name." - ".$value->payment->account_number." - ".$value->payment->account_name;
				}
				$purchase_details = PurchaseDetails::where("purchase_id", "=", $value->purchase_id)->join('supplier_products', 'supplier_products.id', '=', 'purchase_details.product_code')->get();
				$product_lists = "";
				if($purchase_details->count() > 0){
					foreach($purchase_details as $k => $v){
						$product_lists .= "<ul>";
						$product_lists .= "<li>".$v->product_name." [".$v->total." x ".currency_format($v->purchase_price)."]"."</li>" ;
						$product_lists .= "</ul>";
					}
				}
				$row[] = "Pembelian Barang di Supplier ".$value->supplier_name." dengan rincian : ".$product_lists;
				$row[] = "";
				$row[] = $value->pay;
				$purchase_data[] = $row;
				$total_spending += $value->pay;
			}
		}

		//Spending
		$spending_data = [];
		if($spending->count() > 0){
			foreach($spending as $key => $value){
				$row = [];
				$row[] = indo_date($value->created_at, false);
				$row[] = $value->division->name;
				if($value->payment->type == "cash"){
					$row[] = "CASH";
				}else{
					$row[] = $value->payment->bank_name." - ".$value->payment->account_number." - ".$value->payment->account_name;
				}
				$row[] = $value->spending_type;
				$row[] = "";
				$row[] = $value->nominal;
				$spending_data[] = $row;
				$total_spending += $value->nominal;
			}
		}

		//Repayment
		$repayment_data = [];
		if($repayment->count() > 0){
			foreach($repayment as $key => $value){
				$row = [];
				$row[] = indo_date($value->date, false);
				$row[] = $value->division->name;
				if($value->payment->type == "cash"){
					$row[] = "CASH";
				}else{
					$row[] = $value->payment->bank_name." - ".$value->payment->account_number." - ".$value->payment->account_name;
				}
				$row[] = "Pembayaran Pre Order Atas Nama ".$value->preOrder->member->member_name." Detail : ".$value->preOrder->details." [".$value->details."]";
				$row[] = $value->nominal;
				$row[] = "";
				$repayment_data[] = $row;
				$total_income += $value->nominal;
			}
		}
		$results = array_merge($selling_data, $purchase_data, $spending_data, $repayment_data);
		$result_array = [];
		$balance = 0;
		foreach($results as $key => $value){
			$balance += (int)$value[4];
			$balance -= (int)$value[5];
			$row = [];
			$row[] = $key+1;
			$row[] = $value[0];
			$row[] = $value[1];
			$row[] = $value[2];
			$row[] = $value[3];
			$row[] = currency_format((int)$value[4]);
			$row[] = currency_format((int)$value[5]);
			$row[] = "<b>".currency_format($balance)."</b>";
			$result_array[] = $row;
		}
		$result_array[] = ["", "", "", "", "", "<h5>".currency_format($total_income)."</h5>", "<h5>".currency_format($total_spending)."</h5>", "<h5>".currency_format($total_income-$total_spending)."</h5>"];
		return $result_array;
   }

   public function spendingDetail($begin, $end, $division, $payment)
   {
		$spending = Spending::whereBetween('created_at', [$begin." 00:00:00", $end." 23:59:59"]);
		if($division != 0){
			$spending->where("division_id", $division);
		}
		if($payment != 0){
			$spending->where("payment_id", $payment);
		}
		return $spending->get();
   }

   public function repaymentDetail($begin, $end, $division, $payment)
   {
		$repayment = Repayment::whereBetween('date', [$begin, $end]);
		if($division != 0){
			$repayment->where("division_id", $division);
		}
		if($payment != 0){
			$repayment->where("payment_id", $payment);
		}
		return $repayment->get();
   }

   public function purchaseDetail($begin, $end, $division, $payment)
   {
		$purchase = Purchase::whereBetween('purchase.created_at', [$begin." 00:00:00", $end." 23:59:59"])->join('purchase_details', 'purchase_details.purchase_id', '=', 'purchase.purchase_id')->join('supplier', 'supplier.supplier_id', '=', 'purchase.supplier_id');
		if($division != 0){
			$purchase->where("division_id", $division);
		}
		if($payment != 0){
			$purchase->where("payment_id", $payment);
		}
		return $purchase->get();
   }

   public function sellingDetail($begin, $end, $division, $payment)
   {
		$selling = Selling::whereBetween('created_at', [$begin." 00:00:00", $end." 23:59:59"]);
		if($division != 0){
			$selling->where("division_id", $division);
		}
		if($payment != 0){
			$selling->where("payment_id", $payment);
		}
		return $selling->get();
   }

   protected function getData($begin, $end){
      $no = 0;
      $data = array();
      $income = 0;
      $total_income = 0;
      $result_selling = 0;
      $result_purchase = 0;
      $result_spending = 0;
      $result_preorder = 0;
     while(strtotime($begin) <= strtotime($end)){
        $date = $begin;
        $begin = date('Y-m-d', strtotime("+1 day", strtotime($begin)));

        $total_selling = Selling::where('created_at', 'LIKE', "$date%")->sum('pay');
        $total_purchase = Purchase::where('created_at', 'LIKE', "$date%")->sum('pay');
        $total_spending = Spending::where('created_at', 'LIKE', "$date%")->sum('nominal');
        $total_preorder = Repayment::where('date', 'LIKE', "$date%")->sum('nominal');
        $income = ($total_selling+$total_preorder) - $total_purchase - $total_spending;
        $total_income += $income;
        $result_selling += $total_selling;
        $result_purchase += $total_purchase;
        $result_spending += $total_spending;
        $result_preorder += $total_preorder;

        $no ++;
        $row = array();
        $row[] = $no;
        $row[] = indo_date($date, false);
        $row[] = currency_format($total_selling);
        $row[] = currency_format($total_preorder);
        $row[] = currency_format($total_purchase);
        $row[] = currency_format($total_spending);
        $row[] = currency_format($income);
        $data[] = $row;
     }
     $data[] = array("", "<b><h5>Jumlah<h5></b>", "<b>Rp. ".currency_format($result_selling)."</b>", "<b>Rp. ".currency_format($result_preorder)."</b>", "<b>Rp. ".currency_format($result_purchase)."</b>", "<b>Rp. ".currency_format($result_spending)."</b>", "<b><h5>Rp. ".currency_format($total_income)."<h5></b>");

     return $data;
   }

   public function listData($begin, $end)
   {
     $data = $this->getData($begin, $end);
     $output = array("data" => $data);
     return response()->json($output);
   }

   public function refresh(Request $request)
   {
     $begin = $request['begin'];
     $end = $request['end'];
     return view('report.index', compact('begin', 'end'));
   }

   public function exportPDF($begin, $end){
     $date_begin = $begin;
     $date_end = $end;
	 return PDF::loadHTML($this->resultHtml($begin, $end))->stream('download.pdf');
   }

   public function exportAccountingPDF($begin, $end, $division, $payment){
	$date_begin = $begin;
	$date_end = $end;
	return PDF::loadHTML($this->resultHtmlAccounting($begin, $end, $division, $payment))->setPaper('a4', 'landscape')->stream('download.pdf');
  }
  
  public function resultHtmlAccounting($begin, $end, $division, $payment)
  {
	   $data = $this->reportAccountingData($begin, $end, $division, $payment);
	   $output = "<h1>Laporan Keuangan</h1>";
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
	   $output .= "<table class='vuln'><thead><tr><th width='4%'>No</th><th width='10%'>Tanggal</th><th>Divisi</th><th width='20%'>Jenis Pembayaran</th><th width='30%'>Keterangan</th><th>Pemasukan</th><th>Pengeluaran</th><th>Saldo</th></tr></thead><tbody>";
	   foreach ($data as $key => $value) {
		   $output .= "<tr><td>".$value[0]."</td>";
		   $output .= "<td>".$value[1]."</td>";
		   $output .= "<td>".$value[2]."</td>";
		   $output .= "<td>".$value[3]."</td>";
		   $output .= "<td>".$value[4]."</td>";
		   $output .= "<td align='center'>".$value[5]."</td>";
		   $output .= "<td align='center'>".$value[6]."</td>";
		   $output .= "<td align='center'>".$value[7]."</td>";
		   $output .= "</tr>";
	   }

	   $output .= "</tbody></table>";
	   return $output;
  }

   public function resultHtml($begin, $end)
   {
		$data = $this->getData($begin, $end);
		$output = "<h1>Laporan Pendapatan</h1>";
        $output .= "<h3>Bulan ".indo_date($begin, false)." s/d ".indo_date($end, false)."</h3><br><hr>";
        $output .= "<style>
          .vuln {
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
        $output .= "<table class='vuln'><thead><tr><th width='8%'>No</th><th>Tanggal</th><th>Penjualan</th><th>Pre Order</th><th>Pembelian</th><th>Pengeluaran</th><th>Pendapatan</th></tr></thead><tbody>";
        foreach ($data as $key => $value) {
			$output .= "<tr><td>".$value[0]."</td>";
			$output .= "<td align='center'>".$value[1]."</td>";
			$output .= "<td align='center'>".$value[2]."</td>";
			$output .= "<td align='center'>".$value[3]."</td>";
			$output .= "<td align='center'>".$value[4]."</td>";
			$output .= "<td align='center'>".$value[5]."</td>";
			$output .= "<td align='center'>".$value[6]."</td>";
			$output .= "</tr>";
        }

        $output .= "</tbody></table>";
        return $output;
   }
}