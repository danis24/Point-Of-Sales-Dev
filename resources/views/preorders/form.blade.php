<div class="modal fade" id="modal-form" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="form_validation" method="POST" data-toggle="validator">
                {{csrf_field()}} {{method_field('POST')}}
                <div class="modal-header">
                    <h4 class="modal-title" id="defaultModalLabel"></h4>
                </div>

                <div class="modal-body">
                    <input type="hidden" id="id" name="id">
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
                            <label class="form-label">Members</label>
                            <select name="member_id" id="member_id" class="form-control">
                                @if($members->count() > 0)
                                @foreach($members as $key => $value)
                                <option value="{{ $value->member_id }}">{{ $value->member_name }}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="form-group form-float">
                        <div class="form-line">
                            <label class="form-label">Rincian</label>
                            <textarea name="details" id="details" cols="30" rows="10" class="form-control"
                                placeholder="Rincian .."></textarea>
                        </div>
                    </div>
                    <div class="form-group form-float">
                        <div class="form-line">
                            <label class="form-label">Kuantitas</label>
                            <input type="number" step="any" name="qty" id="qty" class="form-control" placeholder="0">
                        </div>
                    </div>
                    <div class="form-group form-float">
                        <div class="form-line">
                            <label class="form-label">Harga</label>
                            <input type="number" name="price" id="price" class="form-control" placeholder="Rp. ">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">BATAL</button>
                    <button type="submit" id="preOrderSave" class="btn btn-primary">SIMPAN</button>
                </div>
            </form>
        </div>
    </div>
</div>