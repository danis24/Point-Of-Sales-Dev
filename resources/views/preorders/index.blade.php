@extends('layouts.app')

@section('content-header')
Pre Order
@endsection

@section('content')
<!-- Body Copy -->
<div class="card">
	<div class="card-header">
		Filter
	</div>

	<div class="card-body">
		<form action="" method="POST">
			@csrf
			<div class="row">
				<div class="col-4">
					<div class="form-group">
						<label for="">Dari Tanggal</label>
						<input type="date" class="form-control" name="begin" id="begin" required value="{{ $begin }}">
					</div>
				</div>
				<div class="col-4">
					<div class="form-group">
						<label for="">Sampai Tanggal</label>
						<input type="date" class="form-control" name="end" id="end" required value="{{ $end }}">
					</div>
				</div>
				<div class="col-4">
					<div class="form-group">
						<label for="">Divisi</label>
						<select name="division" id="division" class="form-control">
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
				<div class="col-12">
					<div class="form-group">
						<label for=""></label>
						<button type="button" id="button_filter" class="btn btn-success">FILTER</button>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>
<div class="card">
	<div class="card-body">
		<div class="dropdown d-inline">
			<button class="btn btn-primary" type="button" id="dropdownMenuButton2" data-toggle="dropdown"
				aria-haspopup="true" aria-expanded="false"><i class="fas fa-th-large"></i></button>
			<div class="dropdown-menu">
				<a class="dropdown-item has-icon" onclick="addForm()"><i class="fas fa-plus"></i>Tambah Pre Order</a>
				<a id="exportPDF" class="dropdown-item has-icon"><i class="fas fa-file-pdf"></i>Export PDF</a>
			</div>
		</div>
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-striped table-preorder">
					<thead>
						<tr>
							<th width="5%">No</th>
							<th>Tanggal</th>
							<th>Divisi</th>
							<th>Nama</th>
							<th>Rincian</th>
							<th>Qty</th>
							<th>Harga</th>
							<th>Total Harga</th>
							<th>Pelunasan</th>
							<th>Sisa</th>
							<th>Status</th>
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
	@include('preorders.form')
	@include('preorders.repayment')
	@include('preorders.repaymentdetail')

	<script type="text/javascript">
		var table, table1, save_method;
		$(function () {
			table = $('.table-preorder').DataTable({
				"language": {
					"url": "{{asset('tables_indo.json')}}",
				},
				"bSort": false,
				"bPaginate": false,
				"processing": true,
				"serverside": true,
				"ajax": {
					"url": "{{url('preorders/data')}}/{{$begin}}/{{$end}}/{{$division}}",
					"type": "GET"
				}
			});

			table1 = $('.table-detail').DataTable({
				"dom": 'Brt',
				"bSort": false,
				"processing": true
			});

			$('#modal-form form').validator().on('submit', function (e) {
				if (!e.isDefaultPrevented()) {
					var id = $('#id').val();
					if (save_method == "add") url = "{{route('preorders.store')}}";
					else url = "{{url('preorders')}}/" + id;

					$.ajax({
						url: url,
						type: "POST",
						data: $('#modal-form form').serialize(),
						success: function (data) {
							$('#modal-form').modal('hide');
							table.ajax.reload();
						},
						error: function () {
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
				table.ajax.url("{{url('preorders/data')}}/"+begin+"/"+end+"/"+division+"");
				table.ajax.reload();
			});

			$("#exportPDF").click(function () {
				let begin = $("#begin").val();
				let end = $("#end").val();
				let division = $("#division").val();
				window.open('{{url("preorders/report")}}/'+begin+'/'+end+'/'+division+'', '_blank')
			});

			$('#modal-repayment form').validator().on('submit', function (e) {
				if (!e.isDefaultPrevented()) {
					var id = $('#id').val();
					if (save_method == "add") url = "{{route('repayments.store')}}";
					else url = "{{url('repayments')}}/" + id;

					$.ajax({
						url: url,
						type: "POST",
						data: $('#modal-repayment form').serialize(),
						success: function (data) {
							$('#modal-repayment').modal('hide');
							table.ajax.reload();
						},
						error: function () {
							alert("Tidak dapat menyimpan data");
						}
					});
					return false;
				}
			});

		});

		function showDetail(id) {
			$('#modal-detail').modal('show');

			table1.ajax.url("{{url('repayments')}}/" + id + "/show");
			table1.ajax.reload();
			table.ajax.reload();
		}

		function addForm() {
			save_method = "add";
			$('input[name=_method]').val('POST');
			$('#modal-form').modal('show');
			$('#modal-form form')[0].reset();
			$('.modal-title').text('Tambah Pre Order');
		}

		function payForm(id) {
			save_method = "add";
			$('#pre_order_id').val(id);
			$('input[name=_method]').val('POST');
			$('#modal-repayment').modal('show');
			$('#modal-repayment form')[0].reset();
			$('.modal-title').text('Lakukan Pembayaran Pre Order');
		}

		function editForm(id) {
			save_method = "edit";
			$('input[name=_method]').val('PATCH');
			$('#modal-form form')[0].reset();
			$.ajax({
				url: "{{url('preorders')}}/" + id + "/edit",
				type: "GET",
				dataType: "JSON",
				success: function (data) {
					$('#modal-form').modal('show');
					$('.modal-title').text('Edit Pre Order');

					$('#id').val(data.id);
					$('#division_id').val(data.division_id);
					$('#date').val(data.date);
					$('#member_id').val(data.member_id);
					$('#details').val(data.details);
					$('#qty').val(data.qty);
					$('#price').val(data.price);
				},
				error: function () {
					alert("Tidak dapat menampilkan data!");
				}
			});
		}

		function deleteData(id) {
			if (confirm("Apakah yakin data akan dihapus?")) {
				$.ajax({
					url: "{{url('preorders')}}/" + id,
					type: "POST",
					data: { '_method': 'DELETE', '_token': $('meta[name=csrf-token]').attr('content') },
					success: function (data) {
						table.ajax.reload();
					},
					error: function () {
						alert("Tidak dapat menghapus data");
					}
				});
			}
		}

		function deleteItem(id) {
			if (confirm("Apakah yakin data akan dihapus?")) {
				$.ajax({
					url: "{{url('repayments')}}/" + id,
					type: "POST",
					data: { '_method': 'DELETE', '_token': $('meta[name=csrf-token]').attr('content') },
					success: function (data) {
						table1.ajax.reload();
					},
					error: function () {
						alert("Tidak dapat menghapus data");
					}
				});
			}
		}
	</script>

	@endsection