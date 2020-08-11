<div class="modal" id="modal-detail" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Detail Pembayaran</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">
						&times; </span> </button>
			</div>
			<div class="modal-body">
				<button class="btn btn-success" id="wa_broadcast"><i class="fab fa-whatsapp"></i> Whatsapp Broadcast</button><br><br>
				<div class="table-responsive">
					<table class="table table-bordered table-detail">
						<thead>
							<tr>
								<th width="30">No</th>
								<th>Tanggal</th>
								<th>Divisi</th>
								<th>Nama</th>
								<th>Nomor WA/HP</th>
								<th>Rincian</th>
								<th>Qty</th>
								<th>Harga</th>
								<th>Total Harga</th>
								<th>Pelunasan</th>
								<th>Sisa</th>
								<th>Status</th>
								<th>Option</th>
							</tr>
						</thead>
						<tbody></tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
@section('css')
<style>
	.modal-lg {
		max-width: 100%;
	}
</style>
@stop