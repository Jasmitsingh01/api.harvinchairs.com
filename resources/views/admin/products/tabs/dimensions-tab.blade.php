<div class="tab-pane fade" id="v-pills-dimensions" role="tabpanel" aria-labelledby="v-pills-dimensions-tab">
    <h3>Dimensions</h3>
    <button type="button" id="toggle-form-dimension-btn" class="btn btn-primary dimension-toggle-btn mb-3">Add Dimensions</button>
    <div id="dimension-form" class="mb-3" style="display: none;">
        <div class="form-group">
            <label for="from">Dimension Name:</label>
            <input type="text" class="form-control" id="dimension_name" name="dimension_name">
        </div>
        <div class="form-group">
            <label for="to">Dimension Value:</label>
            <input type="text" class="form-control" id="dimension_value" name="dimension_value">
        </div>
        <input type="hidden" name="tabname" value="dimension_array">
        <button id="dimension-array-submit" class="btn btn-primary">Submit</button>
    </div>

    <form method="POST" action="{{ route('admin.products.update', [$product->id]) }}" enctype="multipart/form-data">
        @method('PUT')
        @csrf
        <table id="dimension_table" class="table">
            <thead>
                <tr>
                    <th>Dimension Name</th>
                    <th>Value</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @if (isset($product->dimension))
                    @foreach (json_decode($product->dimension, true) as $dimension)
                        <tr>
                            <td>{{ $dimension['name'] }}</td>
                            <td>{{ $dimension['value'] }}</td>
                            <td>
                                <a type="button" class="mx-1 text-theme-color"><i class="fa-solid fa-pen-to-square edit-row"></i></a>
                                  <a type="button" class="text-theme-color border-0 bg-transparent px-0"><i class="fa-solid fa-trash-can delete-row"></i></a>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>

        <div class="form-group">
            <label for="dimension_image">{{ trans('cruds.product.fields.dimensions_image') }}</label>
            <div class="needsclick dropzone {{ $errors->has('dimension_image') ? 'is-invalid' : '' }}" id="dimension_image-dropzone">
            </div>
            @if ($errors->has('dimension_image'))
            <div class="invalid-feedback">
                {{ $errors->first('dimensions_image') }}
            </div>
            @endif
            <span class="help-block">{{ trans('cruds.product.fields.dimensions_image_helper') }}</span>
            <div class="recommended-settings">
                <b>Recommended:</b>450 x 370 pixels [jpeg, jpg, png, gif], {{ config('constants.FILEMAXSIZE') }} MB
            </div>
        </div>
        <input type="hidden" name="tabname" value="dimensions">

        <input type="hidden" name="dimension" id="dimension_data" value="{{ $product->dimension }}">
        <button type="submit" name="product_submit" class="btn btn-primary btn-lg" value="save">Save</button>
        <button type="submit" name="product_submit" class="btn btn-primary btn-lg" value="save_and_stay">Save & Stay</button>
        <a href="{{ route('admin.products.index') }}" class="btn btn-primary btn-lg">Cancel</a>
    </form>
</div>
