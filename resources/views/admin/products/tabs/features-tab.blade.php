<div class="tab-pane fade" id="v-pills-features" role="tabpanel" aria-labelledby="v-pills-features-tab">
    <h3>Features</h3>
    <form method="POST" action="{{ route('admin.products.update', [$product->id]) }}" enctype="multipart/form-data">
        @method('PUT')
        @csrf
        <table id="feature_table" class="table">
            <thead>
                <tr class="nodrag nodrop">
                    <th class="left">
                        <span class="title_box">Feature
                        </span>
                    </th>
                    <th class="left">
                        <span class="title_box">Pre-defined value
                        </span>
                    </th>
                    <th class="left">
                        <span class="title_box">or Customized value
                        </span>
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($features as $feature)
                    <tr class="highlighted odd selected-line">
                        <td>
                            {{ $feature->title }}

                        </td>
                        <td id="feature_list{{ $feature->id }}">
                            @if ($feature->featureValues->isEmpty())
                                <span id="Body_repFeature_AddPre_defined_0">N/A -
                                    <a href="#" class="add-feature-link btn btn-link" data-toggle="modal"
                                        data-feature="{{ $feature->id }}" data-target="#addFeatureValueModal"
                                        id="addFeaturevalueLink"><i class="icon-plus-sign"></i>Add
                                        pre-defined values first <i class="icon-external-link-sign"></i></a>
                                </span>
                            @else
                                <select class="form-control" name="feature_value[{{ $feature->id }}]" id="value"
                                    style="width: 150px">
                                    <option value="">Select</option>
                                    @foreach ($feature->featureValues as $value)
                                        <option
                                            @foreach ($product->productFeatures as $prodFeature) {{ $prodFeature->featureValue->is_custom == false && $prodFeature->featureValue->feature_id == $feature->id ? 'selected' : '' }} @endforeach
                                            value="{{ $value->id }}">{{ $value->value }}
                                        </option>
                                    @endforeach

                                </select>
                            @endif


                        </td>
                        <td>
                            <textarea name="feature_value_text[{{ $feature->id }}]" rows="2" cols="20"
                                id="feature_value_text{{ $feature->id }}" class="form-control">@php
                                $featureValue = '';
                                foreach ($product->productFeatures as $prodFeature) {
                                    if ($prodFeature->featureValue->is_custom == true && $prodFeature->featureValue->feature_id == $feature->id) {
                                        $featureValue = $prodFeature->featureValue->value;
                                        break; // Exit the loop once the value is found
                                    }
                                }
                                echo $featureValue;
                            @endphp</textarea>
                        </td>
                    </tr>
                @endforeach


            </tbody>
        </table>
        <div class="form-group">
            <a href="#" data-toggle="modal" data-feature="{{ $feature->id }}" data-target="#addFeatureModal"
                id="add_feature_modal" class="btn btn-link bt-icon confirm_leave"><i class=" fa fa-plus"></i> Create new
                Feature <i class=" fa fa-external-link"></i></a>
        </div>
        <input type="hidden" name="tabname" value="features">
        <button type="submit" name="product_submit" class="btn btn-primary btn-lg" value="save">Save</button>
        <button type="submit" name="product_submit" class="btn btn-primary btn-lg" value="save_and_stay">Save &
            Stay</button>
        <a href="{{ route('admin.products.index') }}" class="btn btn-primary btn-lg">Cancel</a>
    </form>
</div>
<div class="tab-pane fade" id="v-pills-video" role="tabpanel" aria-labelledby="v-pills-video-tab">
    <h3>Videos</h3>
    <form method="POST" action="{{ route('admin.products.update', [$product->id]) }}" enctype="multipart/form-data">
        @method('PUT')
        @csrf
        <div class="form-group">
            <label for="video_link">{{ trans('cruds.product.fields.video_link') }}</label>
            <input class="form-control {{ $errors->has('video_link') ? 'is-invalid' : '' }}" type="text"
                name="video_link" id="video_link"
                value="{{ old('video_link', $product->video_link) ? old('video_link', $product->video_link) : 'https://www.youtube.com/embed/' }}">
            @if ($errors->has('video_link'))
                <div class="invalid-feedback">
                    {{ $errors->first('video_link') }}
                </div>
            @endif
            <span class="help-block">{{ trans('cruds.product.fields.video_link_helper') }}</span>
        </div>
        <div class="form-group">
            <label for="cover_image">{{ trans('cruds.product.fields.cover_image') }}</label>
            <div class="needsclick dropzone {{ $errors->has('cover_image') ? 'is-invalid' : '' }}"
                id="cover_image-dropzone">
            </div>
            @if ($errors->has('cover_image'))
                <div class="invalid-feedback">
                    {{ $errors->first('cover_image') }}
                </div>
            @endif
            <span class="help-block">{{ trans('cruds.product.fields.cover_image_helper') }}</span>
            <div class="recommended-settings">
                <b>Recommended:</b>583 x 480 pixels [jpeg, jpg, png, gif], {{ config('constants.FILEMAXSIZE') }} MB
            </div>
        </div>
{{--
        <div class="form-group">
            <label for="video_heading">{{ trans('cruds.product.fields.video_heading') }}</label>
            <input class="form-control {{ $errors->has('video_heading') ? 'is-invalid' : '' }}" type="text"
                name="video_heading" id="video_heading" value="{{ old('video_heading', $product->video_heading) }}">
            @if ($errors->has('video_heading'))
                <div class="invalid-feedback">
                    {{ $errors->first('video_heading') }}
                </div>
            @endif
            <span class="help-block">{{ trans('cruds.product.fields.video_heading_helper') }}</span>
        </div>

        <div class="form-group">
            <label for="video_description">{{ trans('cruds.product.fields.video_description') }}</label>
            <textarea class="form-control ckeditor{{ $errors->has('video_description') ? 'is-invalid' : '' }}"
                name="video_description" id="video_description">{{ old('video_description', $product->video_description) }}</textarea>
            @if ($errors->has('video_description'))
                <div class="invalid-feedback">
                    {{ $errors->first('video_description') }}
                </div>
            @endif
            <span class="help-block">{{ trans('cruds.product.fields.video_description_helper') }}</span>
        </div> --}}
        <input type="hidden" name="tabname" value="video">
        <button type="submit" name="product_submit" class="btn btn-primary btn-lg" value="save">Save</button>
        <button type="submit" name="product_submit" class="btn btn-primary btn-lg" value="save_and_stay">Save &
            Stay</button>
        <a href="{{ route('admin.products.index') }}" class="btn btn-primary btn-lg">Cancel</a>
    </form>
</div>
