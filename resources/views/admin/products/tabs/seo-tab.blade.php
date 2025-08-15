<div class="tab-pane fade" id="v-pills-seo" role="tabpanel" aria-labelledby="v-pills-seo-tab">
    <h3>SEO</h3>
    <form method="POST" action="{{ route('admin.products.update', [$product->id]) }}" enctype="multipart/form-data">
        @method('PUT')
        @csrf
        <div class="form-group">
            <label for="meta_title">{{ trans('cruds.product.fields.meta_title') }}</label>
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="inputGroup-sizing-default"><span id="mtitle">70</span></span>
                </div>
                <input class="form-control {{ $errors->has('meta_title') ? 'is-invalid' : '' }}" type="text"
                    name="meta_title" id="meta_title" value="{{ old('meta_title', $product->meta_title) }}">
                @if ($errors->has('meta_title'))
                    <div class="invalid-feedback">
                        {{ $errors->first('meta_title') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.product.fields.meta_title_helper') }}</span>
            </div>
        </div>
        <div class="form-group">
            <label for="meta_description">{{ trans('cruds.product.fields.meta_description') }}</label>
            <div class="input-group mb-3">

                <div class="input-group-prepend">
                    <span class="input-group-text" id="inputGroup-sizing-default"><span id="mDes">70</span></span>
                </div>
                <textarea class="form-control {{ $errors->has('meta_description') ? 'is-invalid' : '' }}" name="meta_description"
                    id="meta_description">{{ old('meta_description', $product->meta_description) }}</textarea>
                @if ($errors->has('meta_description'))
                    <div class="invalid-feedback">
                        {{ $errors->first('meta_description') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.product.fields.meta_description_helper') }}</span>
            </div>
        </div>
        <div class="row">
            <label class="required" for="slug">{{ trans('cruds.product.fields.slug') }}</label>
            <div class="col-md-8">
                <div class="form-group">

                    <div class="input-group mb-4">
                        <input class="form-control {{ $errors->has('slug') ? 'is-invalid' : '' }}" type="text"
                            name="slug" id="slug" value="{{ old('slug', $product->slug) }}" readonly required>
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="inputGroup-sizing-default"> <a
                                    class="mx-1 text-theme-color enable-slug">
                                    <i class="fa-solid fa-pen-to-square"></i></a>
                            </span>
                        </div>
                        @if ($errors->has('slug'))
                            <div class="invalid-feedback">
                                {{ $errors->first('slug') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.product.fields.slug_helper') }}</span>
                    </div>
                    <span id="slug_message"></span>
                </div>
            </div>
            <div class="col-md-4">
                <div class=" alignleft">
                    <a id="generate_slug" class="btn btn btn-default generate-slug"><i class="fa fa-random"></i>
                        Generate</a>
                </div>
            </div>
        </div>



        <div class="form-group">
            <label for="shop_id">{{ trans('cruds.product.fields.tags') }}</label>

            <select multiple class="form-control select2 tag-selector{{ $errors->has('tags') ? 'is-invalid' : '' }}"
                name="tags[]" id="selected-tags">
                @foreach ($tags as $tag)
                    <option value="{{ $tag->id }}"
                        {{ in_array($tag->id, $product_tags) || old('tags') == $tag->id ? 'selected' : '' }}>
                        {{ $tag->name }}
                    </option>
                @endforeach
            </select>


            @if ($errors->has('tags'))
                <div class="invalid-feedback">
                    {{ $errors->first('tags') }}
                </div>
            @endif
            <span class="help-block">{{ trans('cruds.product.fields.shop_helper') }}</span>
        </div>

        <div class="form-group">
            @if (isset($product->default_product_category))
                <input type="hidden" id="product_url" name="product_url"
                    value="{{ config('shop.dashboard_url') . '/' . $product->default_product_category->slug . '/' . $product->id . '-' }}">
                <div class="Alert alert-product-view">
                    <ul style="padding: 10px">
                        <i style="margin-left: 5px; margin-right: 10px; margin-top: 10px; margin-bottom: 5px;"
                            class=" fa fa-link" aria-hidden="true"></i> <em>The product link will
                            look like this</em>
                        <br>

                        <a class="product_url" target="_blank"
                            href="{{ config('shop.dashboard_url') . '/' . $product->default_product_category->slug . '/' . $product->id . '-' . $product->slug . '.html' }}"><span
                                id="Body_lblUrl">{{ config('shop.dashboard_url') . '/' . $product->default_product_category->slug . '/' . $product->id . '-' . $product->slug . '.html' }}</span></a>


                    </ul>
                </div>
            @else
                <input type="hidden" id="product_url" name="product_url"
                    value="{{ config('shop.dashboard_url') . '/' . $product->id . '-' }}">

                <div class="Alert alert-product-view">
                    <ul style="padding: 10px">
                        <i style="margin-left: 5px; margin-right: 10px; margin-top: 10px; margin-bottom: 5px;"
                            class=" fa fa-link" aria-hidden="true"></i><em>The product link will
                            look like this</em>
                        <br>
                        <a class="product_url" target="_blank"
                            href="{{ config('shop.dashboard_url') . '/' . $product->id . '-' . $product->slug . '.html' }} "><span
                                id="Body_lblUrl">{{ config('shop.dashboard_url') . '/' . $product->id . '-' . $product->slug . '.html' }}</span></a>

                    </ul>
                </div>
            @endif
        </div>
        <input type="hidden" name="tabname" value="seo">
        <button type="submit" name="product_submit" class="btn btn-primary btn-lg" value="save">Save</button>
        <button type="submit" name="product_submit" class="btn btn-primary btn-lg" value="save_and_stay">Save &
            Stay</button>
        <a href="{{ route('admin.products.index') }}" class="btn btn-primary btn-lg">Cancel</a>

    </form>
</div>
