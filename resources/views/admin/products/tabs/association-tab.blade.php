<div class="tab-pane fade" id="v-pills-association" role="tabpanel" aria-labelledby="v-pills-association-tab">
    <h3>Category</h3>
    <form method="POST" id="association_form" action="{{ route('admin.products.update', [$product->id]) }}"
        enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="shop_id">{{ trans('cruds.product.fields.category') }}</label>
            <select multiple class="form-control select2 {{ $errors->has('shop') ? 'is-invalid' : '' }}"
                name="categories[]" id="selected-categories">
                @foreach ($categories as $category)
                    @php
                        $selected = false;
                        foreach ($selected_categories as $selected_category) {
                            if ($category->id == $selected_category['id']) {
                                $selected = true;
                                break;
                            }
                        }
                    @endphp
                    <option value="{{ $category->id }}" {{ $selected ? 'selected' : '' }}>
                        {{ $category->name }}</option>
                @endforeach
            </select>
            @if ($errors->has('shop'))
                <div class="invalid-feedback">
                    {{ $errors->first('shop') }}
                </div>
            @endif
            <span class="help-block">{{ trans('cruds.product.fields.shop_helper') }}</span>
        </div>
        <div class="form-group">
            <label for="shop_id">{{ trans('cruds.product.fields.default_category') }}</label>
            <select id="default-category" name="default_category"
                class="form-control select2 {{ $errors->has('default_category') ? 'is-invalid' : '' }}">
                <option value="">None</option>
                @foreach ($selected_categories as $selected_category)
                    <option value="{{ $selected_category['id'] }}"
                        {{ $selected_category['id'] == $product->default_category ? 'selected' : '' }}>
                        {{ $selected_category['name'] }}</option>
                @endforeach
            </select>
            @if ($errors->has('default_category'))
                <div class="invalid-feedback">
                    {{ $errors->first('default_category') }}
                </div>
            @endif
            <span class="help-block">{{ trans('cruds.product.fields.default_category_helper') }}</span>
        </div>
        <input type="hidden" name="tabname" value="association">
        <button type="submit" id="product_submit" name="product_submit" class="btn btn-primary btn-lg"
            type="submit">Save</button>
        <button type="submit" id="product_submit" name="product_submit" class="btn btn-primary btn-lg"
            value="save_and_stay">Save & Stay</button>
        <a href="{{ route('admin.products.index') }}" class="btn btn-primary btn-lg">Cancel</a>
    </form>
</div>
