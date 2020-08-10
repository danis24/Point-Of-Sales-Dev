@extends('layouts.app')

@section('content-header')
Print Label
@endsection

@section('content')
<div class="card">
  <div class="card-body">
    <form class="form form-horizontal form-product" method="post">
      {{ csrf_field() }}
      <div class="section-title">Kode Produk</div>
      <div class="form-group">
        <div class="input-group mb-3">
          <input id="product_code" name="product_code" type="text" class="form-control" placeholder="" aria-label=""
            autofocus required>
          <div class="input-group-append">
            <button class="btn btn-primary" type="button" onclick="showProduct()">....</button>
          </div>
        </div>
      </div>
    </form>

    <form class="form-shopping-cart" method="POST" action="{{ route('label.print') }}">
      {{ csrf_field() }}
      <div class="table-responsive">
        <table class="table table-striped table-selling">
          <thead>
            <tr>
              <th>Kode Produk</th>
              <th>Nama Produk</th>
              <th>Jumlah Label</th>
            </tr>
          </thead>
          <tbody class="product-content">
           
          </tbody>
        </table>
      </div>
      <button type="submit" class="btn btn-primary pull-right save float-right mb-5 mr-4">
      Print Label
    </button>
    </form>
  </div>
</div>
@endsection

@section('script')
@include('label.product')
<script type="text/javascript">
    var table;
    $(function () {
        $('.table-product').DataTable();
    });

    function showProduct() {
        $('#modal-product').modal('show');
    }

    function selectItem(product_id) {
        $('#modal-product').modal('hide');
        addItem(product_id);
    }

    function addItem(product_id) {
        $.ajax({
            url: "{{ url('product') }}/"+product_id,
            type: "GET",
            success: function (data) {
                $(".product-content").append("<tr><td>"+data.product_code+"</td><td>"+data.product_name+"</td><td><input type='hidden' name='product_id[]' value='"+data.product_id+"'><input type='number' class='form-control' name='count_label[]'></td></tr>");
            },
            error: function () {
                alert("Silahkan Coba Lagi");
            }
        });
    }
</script>
@endsection