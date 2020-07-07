@extends('layouts.app')

@section('content-header')
	Pemasukan Umum
@endsection

@section('content')
<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-header">
				Filter
			</div>
			<!-- /.card-header -->
			<div class="card-body">
				<form action="" method="POST">
					@csrf
					<div class="row">
						<div class="col-3">
							<div class="form-group">
								<label for="">Dari Tanggal</label>
								<input type="date" class="form-control" name="begin" required value="{{ $begin }}" id="begin">
							</div>
						</div>
						<div class="col-3">
							<div class="form-group">
								<label for="">Sampai Tanggal</label>
								<input type="date" class="form-control" name="end" required value="{{ $end }}" id="end">
							</div>
						</div>
						<div class="col-3">
							<div class="form-group">
								<label for="">Divisi</label>
								<select name="division" class="form-control" id="division">
									<option value="0">Semua</option>
									@if($divisions->count() > 0)
									@foreach($divisions as $key => $value)
									@if($division == $value->id)
									<option value="{{$value->id}}" selected>{{$value->name}}</option>
									@else
									<option value="{{$value->id}}">{{$value->name}}</option>
									@endif
									@endforeach
									@endif
								</select>
							</div>
						</div>
						<div class="col-3">
							<div class="form-group">
								<label for="">Jenis Keuangan</label>
								<select name="payment" class="form-control" id="payment">
								<option value="0">Semua</option>
								@if($payments->count() > 0)
								@foreach($payments as $key => $value)
								@if($payment == $value->id)
								<option value="{{ $value->id }}" selected>
									@if($value->bank_name == "")
									CASH
									@else
									{{$value->bank_name}} - {{$value->account_number}} - {{$value->account_name}}
									@endif
								</option>
								@else
								<option value="{{ $value->id }}">
									@if($value->bank_name == "")
									CASH
									@else
									{{$value->bank_name}} - {{$value->account_number}} - {{$value->account_name}}
									@endif
								</option>
								@endif
								@endforeach
								@endif
							</select>
							</div>
						</div>
						<div class="col-4">
							<button type="button" id="button_filter" class="btn btn-success">FILTER</button>
						</div>
					</div>
				</form>
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
      	<a class="dropdown-item has-icon" onclick="addForm()"><i class="fas fa-plus"></i>Tambah Pemasukan</a>
      </div>
</div>
  <div class="card-body">
    <div class="table-responsive">
         <table class="table table-striped table-hover js-basic-example dataTable">
            <thead>
                <tr>
                    <th width="20">No</th>
                    <th>Tanggal</th>
                    <th>Divisi</th>
                    <th>Jenis Pembayaran</th>
                    <th>Deskripsi</th>
                    <th>Nominal</th>
                    <th>Kelola Data</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
  </div>
</div>
@endsection

@section('script')
@include('credit.form')
<script type="text/javascript">
	var table, save_method;
	$(function(){
		table = $('.table').DataTable({
			"language": {
            	"url" : "{{asset('tables_indo.json')}}",
         	},
			"dom": 'Brt',
			"bSort": false,
			"bPaginate": false,
			"serverside": true,
			"processing" : true,
			"ajax" : {
				"url"  : "{{url('credits/data')}}/{{$begin}}/{{$end}}/{{$division}}/{{$payment}}",
				"type" : "GET"
			}
		});
		$('#modal-form form').validator().on('submit', function(e){
			if(!e.isDefaultPrevented()){
				var id = $('#id').val();
				if(save_method == "add") url = "{{route('credits.store')}}";
				else url = "credits/"+id;

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

		$('#button_filter').click(function () {
			let begin = $("#begin").val();
			let end = $("#end").val();
			let division = $("#division").val();
			let payment = $("#payment").val();
			table.ajax.url("{{url('credits/data')}}/"+begin+"/"+end+"/"+division+"/"+payment);
			table.ajax.reload();
		});

	});
	function addForm(){
		save_method = "add";
		$('input[name=_method]').val('POST');
		$('#modal-form').modal('show');
		$('#modal-form form')[0].reset();
		$('.modal-title').text('Tambah Pemasukan');
	}
	function editForm(id){
		save_method = "edit";
		$('input[name=_method]').val('PATCH');
		$('#modal-form form')[0].reset();
		$.ajax({
			url			: "credits/"+id+"/edit",
			type 		: "GET",
			dataType	: "JSON",
			success		: function(data){
				$('#modal-form').modal('show');
				$('.modal-title').text('Edit Pemasukan');

				$('#id').val(data.id);
				$('#description').val(data.description);
				$('#nominal').val(data.nominal);
				$('#division_id').val(data.division_id);
				$('#payment_id').val(data.payment_id);
			},
			error		: function(){
				alert("Tidak dapat menampilkan data!");
			}
		});
	}

	function deleteData(id){
		if(confirm("Apakah yakin data akan dihapus?")){
			$.ajax({
				url		: "credits/"+id,
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