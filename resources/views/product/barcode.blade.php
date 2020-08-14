<!DOCTYPE html>
<html>
<head>
	<title>Cetak Barcode</title>
	<style>
		@page { margin-top: -1mm; margin-bottom: 5mm; margin-left: -4mm; margin-right: 2mm}
		body { margin: 0px; }
	</style>
</head>
<body>
	<table style="border-spacing: 18px 17px">
		<tr>
			@foreach($data_product as $data)
			<td align="center" style="width: 35mm; height: 15mm">
				<span style="font-size: 4">{{$data['product_name']}}</span><br>
				<img src="data:image/png;base64,{{$data['barcode']}}" alt="barcode"/><br>
				<span style="font-size: 5">{{$data['product_code']}}</span>
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