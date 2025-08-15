<div class="tab-pane fade" id="v-pills-gst" role="tabpanel" aria-labelledby="v-pills-gst-tab">
    <h3>GST</h3>
    <form method="POST" action="{{ route('admin.products.update', [$product->id]) }}" enctype="multipart/form-data">
        @method('PUT')
        @csrf

        <div class="form-group">
            <label for="CGST" class="col-form-label">CGST Rate In Percentage</label>
            <input type="number" class="form-control {{ $errors->has('cgst_rate') ? ' is-invalid' : '' }}"
            name="cgst_rate" id="cgst_rate" value="{{old('cgst_rate',$product->cgst_rate)}}" min=0 max=100/>
                @if ($errors->has('cgst_rate'))
                    <div class="invalid-feedback">
                        {{ $errors->first('cgst_rate') }}
                    </div>
                @endif

                <span class="help-block text-muted">{{ trans('cruds.product.fields.shop_helper') }}</span>
        </div>

        <div class="form-group">
            <label for="shop_id" class="col-form-label">SGST Rate In Percentage</label>
            <input type="number" class="form-control {{ $errors->has('sgst_rate') ? ' is-invalid' : '' }}"
            name="sgst_rate" id="sgst_rate" value="{{old('cgst_rate',$product->sgst_rate)}}" min=0 max="100"/>
                @if ($errors->has('sgst_rate'))
                    <div class="invalid-feedback">
                        {{ $errors->first('answer') }}
                    </div>
                @endif

                <span class="help-block text-muted">{{ trans('cruds.product.fields.shop_helper') }}</span>
        </div>

        <input type="hidden" name="tabname" value="gst">
        <button type="submit" name="product_submit" class="btn btn-primary btn-lg" value="save">Save</button>
        <button type="submit" name="product_submit" class="btn btn-primary btn-lg" value="save_and_stay">Save &
            Stay</button>
        <a href="{{ route('admin.products.index') }}" class="btn btn-primary btn-lg">Cancel</a>

    </form>
</div>

