<div class="tab-pane fade show active" id="v-pills-information" role="tabpanel" aria-labelledby="v-pills-information-tab">
    <h3>Product Information</h3>
    <form method="POST" action="{{ route('admin.products.update', [$product->id]) }}" enctype="multipart/form-data">
        @method('PUT')
        @csrf
        <div class="form-group">
            <label class="required" for="name">{{ trans('cruds.product.fields.name') }}</label>
            <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name"
                id="name" value="{{ old('name', $product->name) }}" required>
            @if ($errors->has('name'))
                <div class="invalid-feedback">
                    {{ $errors->first('name') }}
                </div>
            @endif
            <span class="help-block">{{ trans('cruds.product.fields.name_helper') }}</span>
        </div>
        <div class="form-group">
            <label class="required" for="name">{{ trans('cruds.product.fields.reference_code') }}</label>
            <input class="form-control {{ $errors->has('reference_code') ? 'is-invalid' : '' }}" type="text"
                name="reference_code" id="reference_code" value="{{ old('reference_code', $product->reference_code) }}"
                required>
            @if ($errors->has('reference_code'))
                <div class="invalid-feedback">
                    {{ $errors->first('reference_code') }}
                </div>
            @endif
            <span class="help-block">{{ trans('cruds.product.fields.reference_code_helper') }}</span>
        </div>
        <div class="form-group">
            <label class="required"
                for="name">{{ trans('cruds.product.fields.assembly_charges') }}</label>
            <input class="form-control {{ $errors->has('assembly_charges') ? 'is-invalid' : '' }}"
                type="number" name="assembly_charges" id="assembly_charges"
                value="{{ old('assembly_charges', $product->assembly_charges) }}" step="0" min="0" required>
            @if ($errors->has('assembly_charges'))
                <div class="invalid-feedback">
                    {{ $errors->first('assembly_charges') }}
                </div>
            @endif
            <span
                class="help-block">{{ trans('cruds.product.fields.assembly_charges_helper') }}</span>
        </div>
        {{-- <div class="form-group">
            <label for="redirect_when_disabled">{{ trans('cruds.product.fields.redirect_when_disabled') }}</label>
            <select class="form-control select2 {{ $errors->has('redirect_when_disabled') ? 'is-invalid' : '' }}"
                name="redirect_when_disabled" id="redirect_when_disabled">
                <option value="404" {{ $product->redirect_when_disabled == 404 ? 'selected' : '' }}> No redirect
                    [404]</option>
                <option value="301" {{ $product->redirect_when_disabled == 301 ? 'selected' : '' }}>Permanently
                    display another product instead</option>
                <option value="302" {{ $product->redirect_when_disabled == 302 ? 'selected' : '' }}>Temporarily
                    display another product instead</option>

            </select>
            @if ($errors->has('redirect_when_disabled'))
                <div class="invalid-feedback">
                    {{ $errors->first('redirect_when_disabled') }}
                </div>
            @endif
            <span class="help-block">{{ trans('cruds.product.fields.redirect_when_disabled_helper') }}</span>
        </div> --}}

        <div class="form-group">
            <label>Options</label>

            <div class="form-check">
                <input class="form-check-input" type="radio" value="1" name="available_for_order"
                    id="available_for_order_true"
                    {{ $product->available_for_order == '1' || old('available_for_order') == '1' ? 'checked' : '' }}>
                <label class="form-check-label" for="available_for_order_true">
                    {{ trans('cruds.product.fields.available_for_order') }}
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" value="0" name="available_for_order"
                    id="available_for_order_false"
                    {{ $product->available_for_order == '0' || old('available_for_order') == '0' ? 'checked' : '' }}>
                <label class="form-check-label" for="available_for_order_false">
                    Available For Enquiry
                </label>
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch {{ $errors->has('is_active') ? 'is-invalid' : '' }}">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1"
                    {{ old('is_active', $product->is_active) == 1 ? 'checked' : '' }}>
                <label class="custom-control-label"
                    for="is_active">{{ trans('cruds.product.fields.is_active') }}</label>
            </div>
            @if ($errors->has('is_active'))
                <div class="invalid-feedback">
                    {{ $errors->first('is_active') }}
                </div>
            @endif
            <span class="help-block">{{ trans('cruds.product.fields.is_active_helper') }}</span>
        </div>
        {{-- <div class="form-group">
            <div class="custom-control custom-switch {{ $errors->has('creative_cuts') ? 'is-invalid' : '' }}">
                <input type="hidden" name="creative_cuts" value="0">
                <input type="checkbox" class="custom-control-input" id="creative_cuts" name="creative_cuts"
                    value="1" {{ old('creative_cuts', $product->creative_cuts) == 1 ? 'checked' : '' }}>
                <label class="custom-control-label" for="creative_cuts">Home Catalogue</label>
            </div>
            @if ($errors->has('creative_cuts'))
                <div class="invalid-feedback">
                    {{ $errors->first('creative_cuts') }}
                </div>
            @endif
            <span class="help-block">{{ trans('cruds.product.fields.is_active_helper') }}</span>
        </div> --}}
        {{-- <div class="form-group">
            <label for="conditions">{{ trans('cruds.product.fields.conditions') }}</label>
            <select class="form-control select2 {{ $errors->has('conditions') ? 'is-invalid' : '' }}" name="conditions"
                id="conditions">
                <option value="new"
                    {{ $product->conditions == 'new' || old('conditions') == 'new' ? 'selected' : '' }}>
                    New
                </option>
                <option value="used"
                    {{ $product->conditions == 'used' || old('conditions') == 'used' ? 'selected' : '' }}>
                    Used
                </option>
                <option value="refurbished"
                    {{ $product->conditions == 'refurbished' || old('conditions') == 'refurbished' ? 'selected' : '' }}>
                    Refurbished</option>

            </select>
            @if ($errors->has('conditions'))
                <div class="invalid-feedback">
                    {{ $errors->first('conditions') }}
                </div>
            @endif
            <span class="help-block">{{ trans('cruds.product.fields.conditions_helper') }}</span>
        </div> --}}

        <div class="form-group">
            <label for="description">{{ trans('cruds.product.fields.description') }}</label>
            <textarea class="form-control ckeditor{{ $errors->has('description') ? 'is-invalid' : '' }}" name="description"
                id="description">{{ old('description', $product->description) }}</textarea>
            @if ($errors->has('description'))
                <div class="invalid-feedback">
                    {{ $errors->first('description') }}
                </div>
            @endif
            <span class="help-block">{{ trans('cruds.product.fields.description_helper') }}</span>
        </div>
        <div class="form-group">
            <label for="description">Product Warranty Details</label>
            <textarea class="form-control ckeditor{{ $errors->has('warranty_details') ? 'is-invalid' : '' }}" name="warranty_details"
                id="warranty_details">{{ old('warranty_details', $product->warranty_details) }}</textarea>
            @if ($errors->has('warranty_details'))
                <div class="invalid-feedback">
                    {{ $errors->first('warranty_details') }}
                </div>
            @endif
            <span class="help-block">{{ trans('cruds.product.fields.description_helper') }}</span>
        </div>

        <div class="form-group">
            <label for="description">Product Maintenance Details</label>
            <textarea class="form-control ckeditor{{ $errors->has('maintenance_details') ? 'is-invalid' : '' }}" name="maintenance_details"
                id="maintenance_details">{{ old('maintenance_details', $product->maintenance_details) }}</textarea>
            @if ($errors->has('maintenance_details'))
                <div class="invalid-feedback">
                    {{ $errors->first('maintenance_details') }}
                </div>
            @endif
            <span class="help-block">{{ trans('cruds.product.fields.description_helper') }}</span>
        </div>

        <button type="submit" name="product_submit" class="btn btn-primary btn-lg" value="save">Save</button>
        <button type="submit" name="product_submit" class="btn btn-primary btn-lg" value="save_and_stay">Save &
            Stay</button>
        <a href="{{ route('admin.products.index') }}" class="btn btn-primary btn-lg">Cancel</a>
    </form>
</div>
