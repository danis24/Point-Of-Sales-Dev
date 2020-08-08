<div class="modal" id="modal-label" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form method="POST" action="{{ route('product.singleprint') }}" target="_blank">
                {{csrf_field()}} {{method_field('POST')}}
                <div class="modal-body">
                    <input type="hidden" id="id" name="id">
                    <div class="form-group form-float">
                        <label class="form-label">Kode Produk</label>
                        <div class="form-line">
                            <input type="number" class="form-control" id="product_code_label" name="product_code_label" autofocus
                                required>
                        </div>
                    </div>
                    <div class="form-group form-float">
                        <label class="form-label">Nama Produk</label>
                        <div class="form-line">
                            <input type="hidden" name="product_id_label" id="product_id_label">
                            <input type="text" class="form-control" id="product_name_label" name="product_code_label" autofocus
                                required>
                        </div>
                    </div>
                    <div class="form-group form-float">
                        <label class="form-label">Jumlah Label</label>
                        <div class="form-line">
                            <input type="number" class="form-control" id="count_label" name="count_label" autofocus
                                required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">BATAL</button>
                    <button type="submit" class="btn btn-primary">Print Label</button>
                </div>
            </form>
        </div>
    </div>
</div>