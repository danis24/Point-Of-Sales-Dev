<div class="modal fade" id="modal-repayment" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<form id="form_validation" method="POST" data-toggle="validator">
				{{csrf_field()}} {{method_field('POST')}}
				<div class="modal-header">
					<h4 class="modal-title" id="defaultModalLabel"></h4>
				</div>

				<div class="modal-body">
					<input type="hidden" id="pre_order_id" name="pre_order_id">
					<div class="form-group form-float">
						<div class="form-line">
							<label class="form-label">Tanggal</label>
							<input type="date" name="date" id="date" class="form-control">
						</div>
					</div>
					<div class="form-group form-float">
						<div class="form-line">
							<label class="form-label">Divisi</label>
							<select name="division_id" id="division_id" class="form-control">
								@if($divisions->count() > 0)
								@foreach($divisions as $key => $value)
								<option value="{{ $value->id }}">{{ $value->name }}</option>
								@endforeach
								@endif
							</select>
						</div>
					</div>
					<div class="form-group form-float">
						<div class="form-line">
							<label class="form-label">Jenis Pembayaran</label>
							<select name="payment_id" id="payment_id" class="form-control">
								@if($payments->count() > 0)
								@foreach($payments as $key => $value)
								<option value="{{ $value->id }}">
									@if($value->bank_name == "")
									CASH
									@else
									{{$value->bank_name}} - {{$value->account_number}} - {{$value->account_name}}
									@endif
								</option>
								@endforeach
								@endif
							</select>
						</div>
					</div>
					<div class="form-group form-float">
						<div class="form-line">
							<label class="form-label">Keterangan</label>
							<textarea name="details" id="details" cols="30" rows="10" class="form-control"
								placeholder="Keterangan .."></textarea>
						</div>
					</div>
					<div class="form-group form-float">
						<div class="form-line">
							<label class="form-label">Nominal</label>
							<input type="number" name="nominal" id="nominal" class="form-control" placeholder="Rp. ">
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">BATAL</button>
					<button type="submit" class="btn btn-primary">SIMPAN</button>
				</div>
			</form>
		</div>
	</div>
</div>