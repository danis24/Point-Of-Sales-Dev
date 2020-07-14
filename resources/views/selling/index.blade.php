@extends('layouts.app')

@section('content-header')
Penjualan
@endsection

@section('content')
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
                "url": "{{route('selling.data')}}",
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