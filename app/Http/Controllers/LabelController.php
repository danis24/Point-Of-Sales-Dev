<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use DNS1D;
use PDF;

class LabelController extends Controller
{
    protected $product;

    public function __construct()
    {
        $this->product = new Product;
    }

    public function index()
    {
        $products = $this->product->all();
        return view("label.index", compact("products"));
    }

    public function printBarcode(Request $request){
        $data_product = array();
        for($i = 0; $i < count($request->product_id); $i++){
            for($j = 0; $j < $request->count_label[$i]; $j++){
				$product = $this->product->find($request->product_id[$i]);
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
    	$pdf->setPaper('A4', 'portrait');
    	return $pdf->stream();
	}
}
