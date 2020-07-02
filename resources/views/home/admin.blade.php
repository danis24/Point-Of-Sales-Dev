@extends('layouts.app') @section('content-header') Dashboard @endsection @section('content')
<div class="row">
    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
            <div class="card-icon bg-primary">
                <i class="fas fa-boxes"></i>
            </div>
            <div class="card-wrap">
                <div class="card-header">
                    <h4>Total Kategori</h4>
                </div>
            </div>
            <div class="card-body">
                {{ $category }}
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
            <div class="card-icon bg-primary">
                <i class="fas fa-box"></i>
            </div>
            <div class="card-wrap">
                <div class="card-header">
                    <h4>Total Produk</h4>
                </div>
            </div>
            <div class="card-body">
                {{ $product }}
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
            <div class="card-icon bg-primary">
                <i class="fas fa-dolly-flatbed"></i>
            </div>
            <div class="card-wrap">
                <div class="card-header">
                    <h4>Total Supplier</h4>
                </div>
            </div>
            <div class="card-body">
                {{ $supplier }}
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
            <div class="card-icon bg-primary">
                <i class="fas fa-credit-card"></i>
            </div>
            <div class="card-wrap">
                <div class="card-header">
                    <h4>Total Member</h4>
                </div>
            </div>
            <div class="card-body">
                {{ $member }}
            </div>
        </div>
    </div>
</div>

<div class="row">
    @if(count($balance))
    @foreach($balance as $key => $value)
    <div class="col-lg-3 col-md-6 col-12">
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
                </div>
            </div>
        </div>
    </div>
    @endforeach
    @endif
</div>

<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
            <div class="card-icon bg-primary">
                <i class="fas fa-boxes"></i>
            </div>
            <div class="card-wrap">
                <div class="card-header">
                    <h4>Total Pemasukan</h4>
                </div>
            </div>
            <div class="card-body">
                {{ $debit_count }}
            </div>
        </div>
        <div class="card card-statistic-1">
            <div class="card-icon bg-primary">
                <i class="fas fa-box"></i>
            </div>
            <div class="card-wrap">
                <div class="card-header">
                    <h4>Total Pengeluaran</h4>
                </div>
            </div>
            <div class="card-body">
                {{ $credit_count }}
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-12 col-12 col-sm-12">
        <div class="card">
        <div class="card-header">
            <h4>Top 5 Member Debt in Company</h4>
        </div>
        <div class="card-body">             
            <ul class="list-unstyled list-unstyled-border">
                @if(count($sortDebt))
                @foreach(array_slice($sortDebt, 0, 5) as $key => $value)
                <li class="media">
                    <div class="media-body">
                        <div class="float-right text-primary">Rp. {{currency_format($value['reminder'])}}</div>
                        <div class="media-title">{{$value['member_name']}}</div>
                    </div>
                </li>
                @endforeach
                @endif
            </ul>
            <div class="text-center pt-1 pb-1">
            <a href="#" class="btn btn-primary btn-lg btn-round">
                View All
            </a>
            </div>
        </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4>Grafik Pendapatan {{ indo_date($begin) }} - {{ indo_date($end) }}</h4>
            </div>
            <div class="card-body">
                <div class="chart">
                    <canvas id="salesChart" height="400" width="1000"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection @section('script')
<script type="text/javascript">
    var ctx = $("#salesChart").get(0).getContext("2d");
    var chart = new Chart(ctx, {
        // The type of chart we want to create
        type: 'line',

        // The data for our dataset
        data: {
            labels: {{json_encode($data_date)}},
            datasets: [{
                label: 'Penjualan',
                backgroundColor: 'transparent',
                borderColor: '#d71149',
                data: {{json_encode($data_income)}}
            }, {
                label: 'Pre Orders',
                backgroundColor: 'transparent',
                borderColor: '#3262a8',
                data: {{json_encode($data_income_preorder)}}
            }]
        },

        // Configuration options go here
        options: {
            pointDot: false,
            responsive: true
        }
    });
</script>
@endsection