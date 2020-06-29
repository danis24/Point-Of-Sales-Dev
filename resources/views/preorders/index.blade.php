@extends('layouts.app')

@section('content-header')
Pre Order
@endsection

@section('content')
<!-- Body Copy -->
<div class="card">
	<div class="card-body">
		<div class="dropdown d-inline">
			<button class="btn btn-primary" type="button" id="dropdownMenuButton2" data-toggle="dropdown"
				aria-haspopup="true" aria-expanded="false"><i class="fas fa-th-large"></i></button>
			<div class="dropdown-menu">
				<a class="dropdown-item has-icon" onclick="addForm()"><i class="fas fa-plus"></i>Tambah Pre Order</a>
			</div>
		</div>
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-striped table-preorder">
					<thead>
						<tr>
							<th width="5%">No</th>
							<th>Tanggal</th>
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
				"processing": true,
				"ajax": {
					"url": "{{route('preorders.data')}}",
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
					else url = "preorders/" + id;

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

			$('#modal-repayment form').validator().on('submit', function (e) {
				if (!e.isDefaultPrevented()) {
					var id = $('#id').val();
					if (save_method == "add") url = "{{route('repayments.store')}}";
					else url = "repayments/" + id;

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

			table1.ajax.url("repayments/" + id + "/show");
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
				url: "preorders/" + id + "/edit",
				type: "GET",
				dataType: "JSON",
				success: function (data) {
					$('#modal-form').modal('show');
					$('.modal-title').text('Edit Pre Order');

					$('#id').val(data.id);
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
					url: "preorders/" + id,
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
					url: "repayments/" + id,
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