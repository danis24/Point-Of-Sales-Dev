<!DOCTYPE html>
<html>
<head>
	<title>Cetak Barcode</title>
	<style>
		@page { margin-top: 2mm; margin-bottom: 5mm; margin-left: 0mm; margin-right: 2mm}
		body { margin: 0px; }
	</style>
</head>
<body>
	<table style="border-spacing: 2mm;">
		<tr>
			@foreach($data_product as $data)
			<td align="center" style="padding-left: 12px; padding-right: 15px">
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