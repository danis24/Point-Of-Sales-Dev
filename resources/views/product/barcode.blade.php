<!DOCTYPE html>
<html>
<head>
	<title>Cetak Barcode</title>
</head>
<body>
	<table width="100%">
		<tr>
			@foreach($data_product as $data)
			<td align="center" style="border: 1px solid #ccc">
				<span style="font-size: 8">{{$data['product_name']}}</span><br>
				<img src="data:image/png;base64,{{$data['barcode']}}" alt="barcode"/><br>
				<span style="font-size: 8">{{$data['product_code']}}</span>
			</td>
			@if($no++ % 5 == 0)
		</tr>
		<tr>
			@endif
			@endforeach
		</tr>
	</table>
</body>
</html>