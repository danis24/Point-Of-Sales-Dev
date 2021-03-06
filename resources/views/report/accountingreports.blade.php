@extends('layouts.app')

@section('content-header')
Laporan Keuangan
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
				<form action="" method="POST">
					@csrf
					<div class="row">
						<div class="col-3">
							<div class="form-group">
								<label for="">Dari Tanggal</label>
								<input type="date" class="form-control" name="begin" required value="{{ $begin }}">
							</div>
						</div>
						<div class="col-3">
							<div class="form-group">
								<label for="">Sampai Tanggal</label>
								<input type="date" class="form-control" name="end" required value="{{ $end }}">
							</div>
						</div>
						<div class="col-3">
							<div class="form-group">
								<label for="">Divisi</label>
								<select name="division" class="form-control">
									@if(Auth::user()->level != 3)
									<option value="0">Semua</option>
									@endif
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
								<select name="payment" class="form-control">
								<option value="0">Semua</option>
								@if(Auth::user()->level != 3)
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
								@endif
							</select>
							</div>
						</div>
						<div class="col-4">
							<button type="submit" class="btn btn-success">FILTER</button>
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
      <button class="btn btn-primary" type="button" id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true"
        aria-expanded="false"><i class="fas fa-th-large"></i></button>
      <div class="dropdown-menu">
	  <a href="accounting-report/pdf/{{$begin}}/{{$end}}/{{$division}}/{{$payment}}" target="_blank" class="dropdown-item has-icon"><i
            class="fas fa-file-pdf"></i>Export PDF</a>
      </div>
    </div>
    <div class="card-body">
      <div class="table-responsive">
          <table class="table table-bordered table-report-accounting">
            <thead>
              <tr>
                <th width="30">No</th>
                <th>Tanggal</th>
                <th>Divisi</th>
                <th>Jenis Pembayaran</th>
                <th>Keterangan</th>
                <th>Pemasukan</th>
                <th>Pengeluaran</th>
                <th>Saldo</th>
              </tr>
            </thead>
            <tbody>
              <tr>
              </tr>
            </tbody>
          </table>
      </div>
    </div>
  </div>
  @endsection

  @section('script')
  <script type="text/javascript">
    var table, begin, end;
    $(function () {
      table = $('.table-report-accounting').DataTable({
        "language": {
          "url": "{{asset('tables_indo.json')}}",
        },
        "dom": 'Brt',
        "bSort": false,
        "bPaginate": false,
        "processing": true,
        "serverside": true,
		"ajax": {
          "url": "accounting-report/data/{{ $begin }}/{{ $end }}/{{$division}}/{{$payment}}",
          "type": "GET"
        }
      });

    });
  </script>
  @endsection