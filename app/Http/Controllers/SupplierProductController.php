<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SupplierProduct;

class SupplierProductController extends Controller
{
    protected $model;

    public function __construct()
    {
        $this->model = new SupplierProduct;
    }

    public function index($id)
    {
        $supplier_id = $id;
        return view('supplierproducts.index', compact('supplier_id'));
    }

    public function listData($id)
    {
        $supplierProducts = $this->model->where('supplier_id', $id)->orderBy('id', 'asc')->get();
        $no = 0;
        $data = array();
        foreach ($supplierProducts as $key => $value) {
            $no ++;
            $row = array();
            $row[] = $no;
            $row[] = $value->product_name;
            $row[] = $value->product_brand;
            $row[] = $value->price;
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
        $supplierProduct = $this->model->create($request->all());
        if($supplierProduct){
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
        $supplierProduct = $this->model->findOrFail($id);
        return response()->json($supplierProduct);
    }

    public function update($id, Request $request)
    {
        $supplierProduct = $this->model->findOrFail($id);
        $supplierProduct->update($request->all());
        if($supplierProduct){
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
        $supplierProduct = $this->model->destroy($id);
        if($supplierProduct){
            return response()->json([
                "status" => "success"
            ]);
        }
        return response()->json([
            "status" => "failed"
        ]);
    }

}
