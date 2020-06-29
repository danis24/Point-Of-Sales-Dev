<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Unit;

class UnitController extends Controller
{
    protected $model;

    public function __construct()
    {
        $this->model = new Unit;
    }

    public function index()
    {
        return view('unit.index');
    }

    public function listData()
    {
        $units = $this->model->orderBy('id', 'asc')->get();
        $no = 0;
        $data = array();
        foreach ($units as $key => $value) {
            $no ++;
            $row = array();
            $row[] = $no;
            $row[] = $value->name;
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
        $division = $this->model->create($request->all());
        if($division){
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
        $division = $this->model->findOrFail($id);
        return response()->json($division);
    }

    public function update($id, Request $request)
    {
        $division = $this->model->findOrFail($id);
        $division->update($request->all());
        if($division){
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
        $division = $this->model->destroy($id);
        if($division){
            return response()->json([
                "status" => "success"
            ]);
        }
        return response()->json([
            "status" => "failed"
        ]);
    }
}
