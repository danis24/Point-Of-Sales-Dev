<!DOCTYPE html>
<html>
<head>
  <title>Produk PDF</title>
  <style>
      .vuln {
         font-size: 12px;
         font-family: 'Trebuchet MS', Arial, Helvetica, sans-serif;
         border-collapse: collapse;
         width: 100%;
      }

      .vuln td, .vuln th {
         border: 1px solid #ddd;
         padding: 8px;
         word-wrap:break-word
      }

      .vuln tr:nth-child(even){background-color: #f2f2f2;}

      .vuln tr:hover {background-color: #ddd;}

      .vuln th {
         padding-top: 12px;
         padding-bottom: 12px;
         text-align: left;
         background-color: #131633;
         color: white;
      }
      table{
         table-layout: fixed;
      }
   </style>
</head>
<body>

<h1>Laporan Penjualan</h1>
<h2>Divisi : {{$division_name}}</h2>
<h3>Tanggal {{ indo_date($begin) }} s/d {{ indo_date($end) }} </h3>
<table class='vuln'>
   <thead>
      <tr>
         <th width='5%'>No</th>
         <th width='15%'>Tanggal</th>
         <th width='15%'>Sumber Pembelian</th>
         <th width='30%'>Detail Product</th>
         <th width="7%">Jumlah</th>
         <th>Total Harga</th>
      </tr>
   </thead>
   <tbody>
      @if(count($data) > 0)
      @foreach($data as $key => $value)
      <tr>
         <td>{{$key+1}}</td>
         <td>{{indo_date($value['created_at'], 'false')}}</td>
         <td>{{ $value['member_name'] }}</td>
         <td>
            @foreach($value['selling_detail_data'] as $k => $v)
            {{$v[2]}} x [{{$v[4]}}]<br>
            @endforeach
         </td>
         <td>{{$value['total_item']}}</td>
         <td>Rp. {{currency_format($value['total_price'])}}</td>
      </tr>
      @endforeach
      @endif
      <tr>
         <td colspan="4" align="center"><b>Jumlah</b></td>
         <td>{{ $count_item }}</td>
         <td>Rp. {{ currency_format($count_price) }}</td>
      </tr>
   </tbody>
</table>
<h1>Laporan Penjualan Berdasarkan Produk</h1>
<h2>Divisi : {{$division_name}}</h2>
<h3>Tanggal {{ indo_date($begin) }} s/d {{ indo_date($end) }} </h3>

<table class='vuln'>
   <thead>
      <tr>
         <th width="2%">No</th>
         <th width='30%'>Nama Product</th>
         <th width="7%">Jumlah Terjual</th>
      </tr>
   </thead>
   <tbody>
      @if(count($result_selling) > 0)
      @foreach($result_selling as $key => $value)
         <tr>
            <td>{{ $key+1 }}</td>
            <td>{{ $value['product_name']}}</td>
            <td>{{ $value['total']}}</td>
         </tr>
      @endforeach
      @endif
      <tr>
         <td colspan="2"></td>
         <td>{{ $count_item }}</td>
      </tr>
   </tbody>
</table>


</body>
</html>