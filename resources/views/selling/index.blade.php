@extends('layouts.app')

@section('content-header')
Penjualan
@endsection

@section('content')
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
<!-- Body Copy -->
<div class="card">
    <div class="card-body">
        <div class="dropdown d-inline">
            <button class="btn btn-primary" type="button" id="dropdownMenuButton2" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false"><i class="fas fa-th-large"></i></button>
            <div class="dropdown-menu">
                <a href="{{ route('transaction.new')}}" class="dropdown-item has-icon"><i
                        class="fas fa-plus"></i>Transaksi
                    Baru</a>
                <a href="{{ route('transaction.index') }}" class="dropdown-item has-icon"><i
                        class="fas fa-hand-holding-usd"></i>Transaksi Aktif</a>
                <a href="#" id="selling_pdf_export" class="dropdown-item has-icon"><i class="fas fa-file-pdf"></i>Export PDF</a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-stripped table-selling">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Divisi</th>
                            <th>Member</th>
                            <th>Detail Produk</th>
                            <th>Total Item</th>
                            <th>Total Harga</th>
                            <th>Diskon</th>
                            <th>Total Bayar</th>
                            <th>Jenis Pembayaran</th>
                            <th>Kasir</th>
                            <th>Kelola Data</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
@include('selling.detail')
<script type="text/javascript">
    var table, save_method, table1;
    $(function () {
        table = $('.table-selling').DataTable({
            "language": {
                "url": "{{asset('tables_indo.json')}}",
            },
			"processing": true,
			"serverside": true,
            "ajax": {
                "url": "{{url('selling/data')}}/{{$begin}}/{{$end}}/{{$division}}",
                "type": "GET"
            }
        });

        table1 = $('.table-detail').DataTable({
            "dom": 'Brt',
            "bSort": false,
            "processing": true
        });

        $('.table-supplier').DataTable();
    });

    $('#button_filter').click(function () {
        let begin = $("#begin").val();
        let end = $("#end").val();
        let division = $("#division").val();
        table.ajax.url("{{url('selling/data')}}/"+begin+"/"+end+"/"+division);
        table.ajax.reload();
    });

    $("#selling_pdf_export").click(function () {
        let begin = $("#begin").val();
        let end = $("#end").val();
        let division = $("#division").val();
        let url = "{{url('selling/pdf')}}/"+begin+"/"+end+"/"+division;
        window.open(url, "_blank");
    });

    function addForm() {
        $('#modal-supplier').modal('show');
    }

    function showDetail(id) {
        $('#modal-detail').modal('show');
        table1.ajax.url("selling/" + id + "/show");
        table1.ajax.reload();
    }


    function deleteData(id) {
        if (confirm("Apakah yakin data akan dihapus?")) {
            $.ajax({
                url: "selling/" + id,
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

</script>

@endsection