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
                            <label class="form-label">Jenis Pembayaran</label>
                            <select name="type" id="payment_type" class="form-control" required>
                                <option value="bank">BANK</option>
                                <option value="cash">CASH</option>
                            </select>
                        </div>
                    </div>
                    <div id="bank">
                        <div class="form-group form-float">
                            <div class="form-line">
                                <label class="form-label">Nama Bank</label>
                                <input type="text" class="form-control" id="bank_name" name="bank_name">
                            </div>
                        </div>

                        <div class="form-group form-float">
                            <div class="form-line">
                                <label class="form-label">Nomor Rekening</label>
                                <input type="text" class="form-control" id="account_number" name="account_number">
                            </div>
                        </div>

                        <div class="form-group form-float">
                            <div class="form-line">
                                <label class="form-label">Nama Pemilik Rekening</label>
                                <input type="text" class="form-control" id="account_name" name="account_name">
                            </div>
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