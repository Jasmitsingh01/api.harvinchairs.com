<div class="tab-pane fade" id="v-pills-hotdeals" role="tabpanel" aria-labelledby="v-pills-hotdeals-tab">
    <h3>Hot Deals</h3>
    <form method="POST" action="{{ route('admin.products.update', [$product->id]) }}" enctype="multipart/form-data">
        @method('PUT')
        @csrf
        <div class="form-group">
            <label for="from_date">{{ trans('cruds.product.fields.from_date') }}</label>
            <input class="form-control date {{ $errors->has('from_date') ? 'is-invalid' : '' }}" type="text"
                name="from_date" id="from_date" value="{{ old('from_date', $product->from_date) }}">
            @if ($errors->has('from_date'))
                <div class="invalid-feedback">
                    {{ $errors->first('from_date') }}
                </div>
            @endif
            <span class="help-block">{{ trans('cruds.product.fields.from_date_helper') }}</span>
        </div>
        <div class="form-group">
            <label for="to_date">{{ trans('cruds.product.fields.to_date') }}</label>
            <input class="form-control date {{ $errors->has('to_date') ? 'is-invalid' : '' }}" type="text"
                name="to_date" id="to_date" value="{{ old('to_date', $product->to_date) }}">
            @if ($errors->has('to_date'))
                <div class="invalid-feedback">
                    {{ $errors->first('to_date') }}
                </div>
            @endif
            <span class="help-block">{{ trans('cruds.product.fields.to_date_helper') }}</span>
        </div>
        <div class="form-group">
            <label for="from_time">{{ trans('cruds.product.fields.from_time') }}</label>
            <input class="form-control timepicker {{ $errors->has('from_time') ? 'is-invalid' : '' }}" type="text"
                name="from_time" id="from_time" value="{{ old('from_time', $product->from_time) }}">
            @if ($errors->has('from_time'))
                <div class="invalid-feedback">
                    {{ $errors->first('from_time') }}
                </div>
            @endif
            <span class="help-block">{{ trans('cruds.product.fields.from_time_helper') }}</span>
        </div>
        <div class="form-group">
            <label for="to_time">{{ trans('cruds.product.fields.to_time') }}</label>
            <input class="form-control timepicker {{ $errors->has('to_time') ? 'is-invalid' : '' }}" type="text"
                name="to_time" id="to_time" value="{{ old('to_time', $product->to_time) }}">
            @if ($errors->has('to_time'))
                <div class="invalid-feedback">
                    {{ $errors->first('to_time') }}
                </div>
            @endif
            <span class="help-block">{{ trans('cruds.product.fields.to_time_helper') }}</span>
        </div>
        <input type="hidden" name="tabname" value="hotdeals">
        <button type="submit" name="product_submit" class="btn btn-primary next-tab btn-lg"
            value="save">Save</button>

        <button type="submit" name="product_submit" class="btn btn-primary btn-lg" value="save_and_stay">Save &
            Stay</button>
        <a href="{{ route('admin.products.index') }}" class="btn btn-primary btn-lg">Cancel</a>
    </form>
</div>
