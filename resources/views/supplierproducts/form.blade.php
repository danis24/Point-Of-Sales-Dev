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
                    <input type="hidden" id="supplier_id" name="supplier_id" value="{{$supplier_id}}">
                    <div class="form-group form-float">
                        <div class="form-line">
                        	<label class="form-label">Nama Produk</label>
                            <input type="text" class="form-control" id="product_name" name="product_name" autofocus required>
                        </div>
                    </div>
                    <div class="form-group form-float">
                        <div class="form-line">
                        	<label class="form-label">Merk</label>
                            <input type="text" class="form-control" id="product_brand" name="product_brand" autofocus required>
                        </div>
                    </div>
                    <div class="form-group form-float">
                        <div class="form-line">
                        	<label class="form-label">Harga</label>
                            <input type="number" class="form-control" id="price" name="price" autofocus required>
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
