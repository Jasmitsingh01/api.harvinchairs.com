<div class="tab-pane fade" id="v-pills-postcode" role="tabpanel" aria-labelledby="v-pills-postcode-tab">
    <h3>Postal Codes</h3>
    <form method="POST" action="{{ route('admin.products.update', [$product->id]) }}" enctype="multipart/form-data">
        @method('PUT')
        @csrf

        <div class="form-group row">
            <label for="shop_id" class="col-form-label">Post Code</label>
            <input type="text" class="form-control postcodes-selector {{ $errors->has('postcodes') ? ' is-invalid' : '' }}"
            name="postcodes" id="selected-postcodes" data-role="tagsinput"
            value="{{ $product->postcodes ? implode(', ', $product->postcodes) : '' }}" />
                @if ($errors->has('postcodes'))
                    <div class="invalid-feedback">
                        {{ $errors->first('postcodes') }}
                    </div>
                @endif

                <span class="help-block text-muted">{{ trans('cruds.product.fields.shop_helper') }}</span>
        </div>

        <input type="hidden" name="tabname" value="postcode">
        <button type="submit" name="product_submit" class="btn btn-primary btn-lg" value="save">Save</button>
        <button type="submit" name="product_submit" class="btn btn-primary btn-lg" value="save_and_stay">Save &
            Stay</button>
        <a href="{{ route('admin.products.index') }}" class="btn btn-primary btn-lg">Cancel</a>

    </form>
</div>

