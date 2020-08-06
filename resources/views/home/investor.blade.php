@extends('layouts.app')

@section('content-header')
	Dashboard
@endsection

@section('content')
	<div class="card">
		<div class="card-body text-center">
			<div class="row">
				@if(count($balance))
				@foreach($balance as $key => $value)
				<div class="col-lg-12 col-md-6 col-12">
					<div class="card">
						<div class="card-header">
							<h4>{{$value['division']}}</h4>
						</div>
						<div class="card-body">
							<div class="summary">
								<div class="summary-info">
									<div class="text-muted">Saldo</div>
									<h4>{{$value['division_balance']}}</h4>
								</div>
								<br>
								<div class="summary-info">
									<p>
										<i class="fas fa-boxes"></i> Pemasukan :
										<b>{{ $value['debit'] }}</b>
									</p>
									<p>
										<i class="fas fa-box"></i> Pengeluaran :
										<b>{{ $value['credit'] }}</b>
									</p>
								</div>
								<br>
								<div class="summary-info">
									<p>
										<i class="fas fa-boxes"></i> Pemasukan/Bulan :
										<b>{{ $value['debit_month'] }}</b>
									</p>
									<p>
										<i class="fas fa-box"></i> Pengeluaran/Bulan :
										<b>{{ $value['credit_month'] }}</b>
									</p>
								</div>
							</div>
						</div>
					</div>
				</div>
				@endforeach
				@endif
			</div>
		</div>
	</div>
@endsection