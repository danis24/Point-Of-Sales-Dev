@extends('layouts.app')

@section('content-header')
	Produk
@endsection

@section('content')
<!-- Body Copy -->
<div class="card">
  <div class="card-body">
  	<div class="dropdown d-inline">
      <button class="btn btn-primary" type="button" id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-th-large"></i></button>
      <div class="dropdown-menu">
      	<a class="dropdown-item has-icon" onclick="addForm()"><i class="fas fa-plus"></i>Tambah Produk</a>
	  	<a class="dropdown-item has-icon" onclick="printBarcode()"><i class="fas fa-print"></i>Print Barcode Produk</a>
	  	<a class="dropdown-item has-icon" onclick="printStockProduct()"><i class="fas fa-print"></i>Print Stock Product</a>
	  	<a class="dropdown-item has-icon" onclick="deleteAll()"><i class="fas fa-trash"></i>Hapus Semua Data</a>
      </div>
  </div>
  <div class="card-body">
    <div class="table-responsive">
    	<form method="POST" id="form-product">
    		{{csrf_field()}}
    	<table class="table table-striped dataTable">
            <thead>
            <tr>
              <th>
                  <input type="checkbox" value="1" id="ig_checkbox">
                  <label for="ig_checkbox">&nbsp;</label>
              </th>
              <th>No</th>
	            <th>Kode</th>
	            <th>Nama</th>
	            <th>Kategori</th>
	            <th>Merek</th>
	            <th>Harga Beli</th>
	            <th>Harga Jual</th>
	            <th>Diskon</th>
	            <th>Stok</th>
	            <th>Kelola Data</th>
            </tr>
        </thead>
            <tbody>
            	<tr>
            	</tr>
            </tbody>
          </table>

    	</form>
    </div>
  </div>
</div>
@endsection

@section('script')
@include('product.form')
<script type="text/javascript">
	var table, save_method;
	$(function(){
		table = $('.table').DataTable({
			"language": {
				"url" : "{{asset('tables_indo.json')}}",
			},
			"processing" : true,
			"serverside" : true,
			"ajax" : {
				"url"  : "{{route('product.data')}}",
				"type" : "GET"
			},
			"columnDefs" : [{
				'targets' : 0,
				'searchable': false,
				'orderable' : false
			}],
			"order":[1, 'asc']
		});

		$('#ig_checkbox').click(function(){
			$('input[type="checkbox"]').prop('checked', this.checked);
		});

		$('#modal-form form').validator().on('submit', function(e){
			if(!e.isDefaultPrevented()){
				var id = $('#id').val();
				if(save_method == "add") url = "{{route('product.store')}}";
				else url = "product/"+id;

				$.ajax({
					url  	: url,
					type 	: "POST",
					data 	: $('#modal-form form').serialize(),
					dataType : "JSON",
					success : function(data){
						if(data.msg=="error"){
							alert('Kode produk sudah terpakai');
							$('#product_code').focus().select();
						}else{
							$('#modal-form').modal('hide');
							table.ajax.reload();
						}
					},
					error : function(){
						alert("Tidak dapat menyimpan data");
					}
				});
				return false;
			}
		});
	});
	function addForm(){
		save_method = "add";
		$('input[name=_method]').val('POST');
		$('#modal-form').modal('show');
		$('#modal-form form')[0].reset();
		$('.modal-title').text('Tambah Produk');
		$('#product_code').attr('readonly', false);
	}
	function editForm(id){
		save_method = "edit";
		$('input[name=_method]').val('PATCH');
		$('#modal-form form')[0].reset();
		$.ajax({
			url			: "product/"+id+"/edit",
			type 		: "GET",
			dataType	: "JSON",
			success		: function(data){
				$('#modal-form').modal('show');
				$('.modal-title').text('Edit Produk');

				$('#id').val(data.product_id);
				$('#product_code').val(data.product_code).attr('readonly', true);
				$('#product_name').val(data.product_name);
				$('#category').val(data.category_id);
				$('#unit_id').val(data.unit_id);
				$('#product_brand').val(data.product_brand);
				$('#purchase_price').val(data.purchase_price);
				$('#discount').val(data.discount);
				$('#selling_price').val(data.selling_price);
				$('#product_stock').val(data.product_stock);
			},
			error		: function(){
				alert("Tidak dapat menampilkan data!");
			}
		});
	}

	function deleteData(id){
		if(confirm("Apakah yakin data akan dihapus?")){
			$.ajax({
				url		: "product/"+id,
				type 	: "POST",
				data 	: {'_method' : 'DELETE', '_token' : $('meta[name=csrf-token]').attr('content')},
				success : function(data){
					table.ajax.reload();
				},
				error	: function(){
					alert("Tidak dapat menghapus data");
				}
			});
		}
	}

	function deleteAll(){
		if ($('input:checked').length < 1) {
			alert('Pilih data yang akan dihapus!');
		}else if(confirm("Apakah yakin akan menghapus semua data terpilih?")){
			$.ajax({
				url		: "product/delete",
				type 	: "POST",
				data 	: $('#form-product').serialize(),
				success	: function(data){
					table.ajax.reload();
				},
				error	: function(){
					alert("Tidak dapat menghapus data!");
				}
			});
		}
	}

	function printBarcode(){
		if ($('input:checked').length < 1) {
			alert('Pilih data yang akan dicetak!');
		}else{
			$('#form-product').attr('target', '_blank').attr('action', "product/print").submit();
		}
	}

	function printStockProduct(){
		if ($('input:checked').length < 1) {
			alert('Pilih data yang akan dicetak!');
		}else{
			$('#form-product').attr('target', '_blank').attr('action', "product/print_stock").submit();
		}
	}
</script>

@endsection