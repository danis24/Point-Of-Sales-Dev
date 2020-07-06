@extends('layouts.app')

@section('content-header')
Laporan Piutang
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
								<input type="date" class="form-control" name="begin" id="begin" required value="{{ $begin }}">
							</div>
						</div>
						<div class="col-3">
							<div class="form-group">
								<label for="">Sampai Tanggal</label>
								<input type="date" class="form-control" name="end" id="end" required value="{{ $end }}">
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
								<label for="">Member</label>
								<select name="member_id" class="form-control select2" id="member_id">
								<option value="0">Semua</option>
								@if($members->count() > 0)
								@foreach($members as $key => $value)
								@if($member == $value->member_id)
								<option value="{{ $value->member_id }}" selected>{{$value->member_name}}</option>
								@else
								<option value="{{ $value->member_id }}">{{$value->member_name}}</option>
								@endif
								@endforeach
								@endif
							</select>
							</div>
						</div>
						<div class="col-4">
							<button type="button" id="filterButton" class="btn btn-success">FILTER</button>
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
	  <button class="dropdown-item has-icon" id="exportPDF"><i class="fas fa-file-pdf"></i>Export PDF</button>
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
                <th>Nama</th>
                <th>Rincian</th>
                <th>Qty</th>
                <th>Harga</th>
                <th>Total Harga</th>
                <th>Hutang</th>
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
          "url": "debit-report/data/{{ $begin }}/{{ $end }}/{{$division}}/{{$member}}",
          "type": "GET"
        }
	  });
	  
	  $("#filterButton").click(function () {
		  let begin = $("#begin").val();
		  let end = $("#end").val();
		  let division = $("#division").val();
		  let member_id = $("#member_id").val();

		  table.ajax.url("{{url('debit-report/data')}}/"+begin+"/"+end+"/"+division+"/"+member_id);
		  table.ajax.reload();
	  });

	$("#exportPDF").click(function () {
		let begin = $("#begin").val();
		let end = $("#end").val();
		let division = $("#division").val();
		let member_id = $("#member_id").val();
		window.open('{{url("debit-report/pdf")}}/'+begin+'/'+end+'/'+division+'/'+member_id, '_blank')
	});

    });
  </script>
  @endsection