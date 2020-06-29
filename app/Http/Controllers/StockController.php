<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Stock;
use App\Product;

class StockController extends Controller
{
    protected $model;
    protected $product;

    public function __construct()
    {
        $this->model = new Stock;
        $this->product = new Product;
    }

    public function indexStockIn()
    {
        $products = $this->product->get();
        return view('stockin.index', compact('products'));
    }

    public function indexStockOut()
    {
        $products = $this->product->get();
        return view('stockout.index', compact('products'));
    }

    public function listDataStockIn()
    {
        $stocks = $this->model->where('type', 'in')->orderBy('id', 'asc')->get();
        $no = 0;
        $data = array();
        foreach ($stocks as $key => $value) {
            $no ++;
            $row = array();
            $row[] = $no;
            $row[] = $value->product->product_name;
            $row[] = $value->stocks." ".$value->product->unit->name;
            $row[] = $value->keterangan;
            $row[] = indo_date(substr($value->created_at, 0, 10), false);
            $row[] = '<tr>
                    <div class="dropdown d-inline">
                      <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Aksi
                      </button>
                      <div class="dropdown-menu">
                        <a onclick="editForm('.$value->id.')" class="dropdown-item has-icon"><i class="fas fa-edit"></i>Edit Data</a>
                        <a onclick="deleteData('.$value->id.')" class="deleteData dropdown-item has-icon"><i class="fas fa-trash"></i>Hapus Data</a>
                      </div>
                     </tr>';
            $data[] = $row;
        }
        $output = array("data" => $data);
        return response()->json($output);
    }

    public function listDataStockOut()
    {
        $stocks = $this->model->where('type', 'out')->orderBy('id', 'asc')->get();
        $no = 0;
        $data = array();
        foreach ($stocks as $key => $value) {
            $no ++;
            $row = array();
            $row[] = $no;
            $row[] = $value->product->product_name;
            $row[] = $value->stocks." ".$value->product->unit->name;
            $row[] = $value->keterangan;
            $row[] = indo_date(substr($value->created_at, 0, 10), false);
            $row[] = '<tr>
                    <div class="dropdown d-inline">
                      <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Aksi
                      </button>
                      <div class="dropdown-menu">
                        <a onclick="editForm('.$value->id.')" class="dropdown-item has-icon"><i class="fas fa-edit"></i>Edit Data</a>
                        <a onclick="deleteData('.$value->id.')" class="deleteData dropdown-item has-icon"><i class="fas fa-trash"></i>Hapus Data</a>
                      </div>
                     </tr>';
            $data[] = $row;
        }
        $output = array("data" => $data);
        return response()->json($output);
    }

    public function store(Request $request)
    {
        $stock = $this->model->create($request->all());
        if($stock){
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
        $stock = $this->model->findOrFail($id);
        return response()->json($stock);
    }

    public function update($id, Request $request)
    {
        $stock = $this->model->findOrFail($id);
        $stock->update($request->all());
        if($stock){
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
        $stock = $this->model->destroy($id);
        if($stock){
            return response()->json([
                "status" => "success"
            ]);
        }
        return response()->json([
            "status" => "failed"
        ]);
    }
}
