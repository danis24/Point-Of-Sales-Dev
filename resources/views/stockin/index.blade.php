@extends('layouts.app')

@section('content-header')
	STOCK MASUK
@endsection

@section('content')
<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-header">
				Filter Laporan
			</div>
			<!-- /.card-header -->
			<div class="card-body">
				
				<div class="row">
					<div class="col-6">
						<div class="form-group">
							<label for="">Dari Tanggal</label>
							<input type="date" class="form-control" name="begin" id="begin" required value="{{ $begin }}">
						</div>
					</div>
					<div class="col-6">
						<div class="form-group">
							<label for="">Sampai Tanggal</label>
							<input type="date" class="form-control" name="end" id="end" required value="{{ $end }}">
						</div>
					</div>
					<div class="col-4">
						<button type="button" id="button_filter" class="btn btn-success">FILTER</button>
					</div>
				</div>
				
			</div>
			<!-- /.card-body -->
		</div>
		<!-- /.card -->
	</div>
</div>
<!-- Body Copy -->
<div class="card">
  <div class="card-body">
  	<div class="dropdown d-inline">
      <button class="btn btn-primary" type="button" id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-th-large"></i></button>
      <div class="dropdown-menu">
      	<a class="dropdown-item has-icon" onclick="addForm()"><i class="fas fa-plus"></i>Tambah Stok Masuk</a>
      </div>
</div>
  <div class="card-body">
    <div class="table-responsive">
        <table class="table table-striped">
        	<thead>
         <tr>
                <th width="5%">No</th>
                <th>Nama Produk</th>
                <th>Jumlah Stok Masuk</th>
                <th>Keterangan</th>
                <th>Tanggal</th>
                <th width="15%">Kelola Data</th>
         </tr>
         </thead>
         <tbody></tbody>
        </table>
    </div>
  </div>
</div>
@endsection

@section('script')
@include('stockin.form')

<script type="text/javascript">
	var table, save_method;
	$(function(){
		table = $('.table').DataTable({
			"language": {
				"url" : "{{asset('tables_indo.json')}}",
			},
			"bSort": false,
			"bPaginate": false,
			"processing": true,
			"serverside": true,
			"ajax" : {
				"url"  : "{{url('stockin/data')}}/{{$begin}}/{{$end}}",
				"type" : "GET"
			}
		});
		
		$('#button_filter').click(function () {
			let begin = $("#begin").val();
			let end = $("#end").val();
			table.ajax.url("{{url('stockin/data')}}/"+begin+"/"+end);
			table.ajax.reload();
		});

		$("#product_id").select2({
			width: '100%',
			dropdownParent: $("#modal-form")
		});

		$('#modal-form form').validator().on('submit', function(e){
			if(!e.isDefaultPrevented()){
				var id = $('#id').val();
				if(save_method == "add") url = "{{route('stockin.store')}}";
				else url = "stockin/"+id;

				$.ajax({
					url  	: url,
					type 	: "POST",
					data 	: $('#modal-form form').serialize(),
					success : function(data){
						$('#modal-form').modal('hide');
						table.ajax.reload();
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
		$('.modal-title').text('Tambah Stok Masuk');
	}
	function editForm(id){
		save_method = "edit";
		$('input[name=_method]').val('PATCH');
		$('#modal-form form')[0].reset();
		$.ajax({
			url			: "stockin/"+id+"/edit",
			type 		: "GET",
			dataType	: "JSON",
			success		: function(data){
				$('#modal-form').modal('show');
				$('.modal-title').text('Edit Stok Masuk');

				$('#id').val(data.id);
				$('#product_id').val(data.product_id);
				$('#keterangan').val(data.keterangan);
				$('#stocks').val(data.stocks);
			},
			error		: function(){
				alert("Tidak dapat menampilkan data!");
			}
		});
	}

	function deleteData(id){
		if(confirm("Apakah yakin data akan dihapus?")){
			$.ajax({
				url		: "stockin/"+id,
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
</script>

@endsection