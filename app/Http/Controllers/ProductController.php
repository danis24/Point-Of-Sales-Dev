<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use App\Product;
use App\Unit;
use DataTables;
use PDF;
use DNS2D;
use DNS1D;
use App\Stock;

class ProductController extends Controller
{
	protected $stock;

	public function __construct()
	{
		$this->stock = new Stock;
		$this->middleware("auth");
	}

    public function index(){
		$category = Category::all();
		$units = Unit::all();
    	return view('product.index', compact('category', 'units'));
    }

    public function listData(){
    	$product = Product::leftJoin('category', 'category.category_id', '=', 'product.category_id')->orderBy('product.product_id', 'desc')->get();
    	$no = 0;
    	$data = array();
    	foreach ($product as $list) {
			$SumStockIn = $this->stock->where("product_id", "=", $list->product_id)->where("type", "=", "in")->sum("stocks");
			$SumStockOut = $this->stock->where("product_id", "=", $list->product_id)->where("type", "=", "out")->sum("stocks");
			$stocks = ($list->product_stock+$SumStockIn)-$SumStockOut;
    		$no ++;
            $row = array();
            $row[] = "
            <input type='checkbox' name='id[]'' id='ig_checkbox' value='".$list->product_id."'><label for='ig_checkbox'></label>";
            $row[] = $no;
            $row[] = $list->product_code;
            $row[] = $list->product_name;
            $row[] = $list->category_name;
            $row[] = $list->product_brand;
            $row[] = "Rp. ".currency_format($list->purchase_price);
            $row[] = "Rp. ".currency_format($list->selling_price);
            $row[] = $list->discount."%";
            $row[] = $stocks." ".$list->unit->name;
            $row[] = '
                    <div class="dropdown d-inline">
                      <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Aksi
                      </button>
                      <div class="dropdown-menu">
                        <a onclick="editForm('.$list->product_id.')" class="dropdown-item has-icon"><i class="fas fa-edit"></i>Edit Data</a>
                        <a onclick="deleteData('.$list->product_id.')" class="dropdown-item has-icon"><i class="fas fa-trash"></i>Hapus Data</a>
                      </div>';
            $data[] = $row;
    	}
    	return Datatables::of($data)->escapeColumns([])->make(true);
    }

    public function store(Request $request){
    	$total = Product::where('product_code', '=', $request['product_code'])->count();
    	if($total < 1){
    		$product = new Product;
    		$product->product_code = $request['product_code'];
    		$product->product_name = $request['product_name'];
    		$product->category_id = $request['category'];
    		$product->product_brand = $request['product_brand'];
    		$product->purchase_price = $request['purchase_price'];
    		$product->discount = $request['discount'];
    		$product->selling_price = $request['selling_price'];
    		$product->product_stock = $request['product_stock'];
    		$product->unit_id = $request['unit_id'];
    		$product->save();
    		echo json_encode(array('msg'=>'success'));
    	}else{
    		echo json_encode(array('msg'=>'error'));
    	}
    }

    public function edit($id){
    	$product = Product::find($id);
    	echo json_encode($product);
    }

    public function update(Request $request, $id){
    	$product = Product::find($id);
		$product->product_name = $request['product_name'];
		$product->category_id = $request['category'];
		$product->product_brand = $request['product_brand'];
		$product->purchase_price = $request['purchase_price'];
		$product->discount = $request['discount'];
		$product->selling_price = $request['selling_price'];
		$product->product_stock = $request['product_stock'];
		$product->unit_id = $request['unit_id'];
		$product->update();
		echo json_encode(array('msg'=>'success'));
    }

    public function destroy($id){
    	$product = Product::find($id);
    	$product->delete();
    }

    public function deleteSelected(Request $request){
    	foreach ($request['id'] as $id) {
    		$product = Product::find($id);
    		$product->delete();
    	}
    }

    public function printBarcode(Request $request){
		$data_product = array();
        if (is_array($request['id']) || is_object($request['id']))
        {
        	foreach ($request['id'] as $id) {
				$product = Product::find($id);
				$product_code = $product->product_code;
        		$data_product[] = [
					"product_name" => $product->product_name,
					"selling_price" => $product->selling_price,
					"product_code" => $product_code,
					"barcode" => DNS1D::getBarcodePNG($product_code, "C128", 1,30)
				];
        	}
		}
    	$no = 1;
    	$pdf = PDF::loadView('product.barcode', compact('data_product', 'no'));
    	$pdf->setPaper([0,0,575.433,470.551], 'portrait');
    	return $pdf->stream();
	}

	public function printProductStock(Request $request){
		return PDF::loadHTML($this->resultHtml($request))->setPaper('a4', 'potrait')->stream('download.pdf');
	}

	public function resultHtml($request)
  	{
		$data = array();
        if (is_array($request['id']) || is_object($request['id']))
        {
        	foreach ($request['id'] as $id) {
				$product = Product::find($id);

				$SumStockIn = $this->stock->where("product_id", "=", $product->product_id)->where("type", "=", "in")->sum("stocks");
				$SumStockOut = $this->stock->where("product_id", "=", $product->product_id)->where("type", "=", "out")->sum("stocks");
				$stocks = ($product->product_stock+$SumStockIn)-$SumStockOut;

				$data[] = [
					"product_code" => $product->product_code,
					"product_name" => $product->product_name,
					"stock" => $stocks." ".$product->unit->name
				];
        	}
		}

	   $output = "<h1>Laporan Stock Barang</h1>";
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
	   $output .= "<table class='vuln'><thead><tr><th width='5%'>No</th><th width='20%'>Kode Produk</th><th>Nama Produk</th><th width='20%'>Stok Produk</th></tr></thead><tbody>";
	   foreach ($data as $key => $value) {
		   $output .= "<tr><td>".($key+1)."</td>";
		   $output .= "<td>".$value['product_code']."</td>";
		   $output .= "<td>".$value['product_name']."</td>";
		   $output .= "<td align='center'>".$value['stock']."</td>";
		   $output .= "</tr>";
	   }

	   $output .= "</tbody></table>";
	   return $output;
  	}
}
