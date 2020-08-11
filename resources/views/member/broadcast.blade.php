<div class="modal fade" id="modal-broadcast" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="form_validation" action="{{ route('preorders.broadcast') }}" method="POST" data-toggle="validator" target="_blank">
					{{csrf_field()}} {{method_field('POST')}}
            <div class="modal-header">
                <h4 class="modal-title" id="defaultModalLabel"></h4>
            </div>
				
                <div class="modal-body">
                    <input type="hidden" id="id" name="id">
                    <div class="form-group form-float">
                        <div class="form-line">
                            <input type="hidden" name="member_id" id="member_id">                      	
                        	<label class="form-label">Pilih Pembayaran</label>
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
	            </div>
	            <div class="modal-footer">
	                <button type="button" class="btn btn-secondary" data-dismiss="modal">BATAL</button>
                    <button type="submit" class="btn btn-primary">Kirim</button>
	            </div>
            </form>
        </div>
    </div>
</div>
