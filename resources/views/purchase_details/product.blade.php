<div class="modal fade" id="modal-product" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="defaultModalLabel"></h4>
            </div>
                <div class="modal-body">
                    <div class="body table-responsive table-supplier">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Nama Produk</th>
                                        <th>Harga</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($product as $data)
                                    <tr>
                                        <td>{{$data->product_name}}</td>
                                        <td>Rp. {{currency_format($data->price)}}</td>
                                        <td><button class="btn btn-primary" onclick="selectItem({{$data->id}})">Pilih</button></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
	            </div>
	            <div class="modal-footer">
	                <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">BATAL</button>
	            </div>
        </div>
    </div>
</div>
