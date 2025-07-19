<div class="tab-pane fade" id="v-pills-gallery" role="tabpanel" aria-labelledby="v-pills-gallery-tab">
    <h3>Gallery</h3>
    <form method="POST" action="{{ route('admin.products.update', [$product->id]) }}" enctype="multipart/form-data">
        @method('PUT')
        @csrf
        <div class="form-group">
            <label for="gallery">{{ trans('cruds.product.fields.gallery') }}</label>
            <div class="needsclick dropzone {{ $errors->has('gallery') ? 'is-invalid' : '' }}" id="gallery-dropzone">
            </div>
            @if ($errors->has('gallery'))
                <div class="invalid-feedback">
                    {{ $errors->first('gallery') }}
                </div>
            @endif
            <span class="help-block">{{ trans('cruds.product.fields.gallery_helper') }}</span>
            <div class="recommended-settings">
                <b>Recommended:</b>583 x 480 pixels [jpeg, jpg, png, gif], {{ config('constants.FILEMAXSIZE') }} MB
            </div>
        </div>
        <div class="form-group">
            <label for="gallery">{{ trans('cruds.product.fields.caption') }}</label>
            <input class="form-control {{ $errors->has('caption') ? 'is-invalid' : '' }}" type="text" name="caption"
                id="caption" value="{{ old('caption', '') }}">
            @if ($errors->has('caption'))
                <div class="invalid-feedback">
                    {{ $errors->first('caption') }}
                </div>
            @endif
            <span class="help-block">{{ trans('cruds.product.fields.caption_helper') }}</span>
        </div>
        <div class="datatable-dashv1-list custom-datatable-overright">
            <table class="table display" id="image_table"
                @if (!isset($product->gallery)) style="display: none;" @endif>
                <thead>
                    <tr>
                        <th style="text-align: center; width: 5%;">Image</th>
                        <th style="text-align: center; width: 50%;">Caption</th>
                        <th style="text-align: center; width: 15%;">Position</th>
                        <th style="text-align: center; width: 15%;">Actions</th>
                        <!-- Add more table headers as needed -->
                    </tr>
                </thead>
                <tbody id="image-table-body" class="image-table-body">
                    @if (isset($product->gallery))
                    @php
                        $sortedGallery = $product->gallery;
                        usort($sortedGallery, function ($a, $b) {
                            return $a['position'] - $b['position'];
                        });
                    @endphp
                            @foreach ($sortedGallery as $key => $gallery)
                            <tr data-entry-id="{{ $product->id }}" id="comb_{{ $product->id }}" draggable="true">

                                <td class="gallery-td">
                                    <input type="hidden" name="gallery_ext[{{ $key }}]"
                                        value="{{ $product->gallery[$key]['original'] }}">
                                    <img src="{{ $product->gallery[$key]['thumbnail'] }}" alt="Image"
                                        width="50">
                                </td>
                                <td class="caption-td" id="caption_ext[{{ $key }}]">
                                    <input type="text" class="form-control caption_ext"
                                        name="caption_ext[{{ $key }}]"
                                        value="{{ $product->gallery[$key]['caption'] }}">
                                </td>

                                <td class="position"><input type="text" readonly
                                        class="form-control position-ext-input  text-center p-0"
                                        name="position_ext[{{ $key }}]"
                                        value="{{ $product->gallery[$key]['position'] }}"></td>
                                <td class="text-center"><a id="Body_ItemsListView_lnkDelete_0"
                                        class="dz-remove delete-btn" style="width: 25px; padding: 4px 3px;"><i
                                            class="fa-solid fa-trash-can"></i></a></td>
                            </tr>
                        @endforeach
                    @endif

                </tbody>
            </table>
            <a id="save_images" class="btn btn-prmiary " style="display: none;"></a>
        </div>
        {{-- <button type="button" id="save_images" style="display: none;" class="btn btn-primary save_images"
                                    type="submit">Save</button> --}}
        <input type="hidden" name="tabname" value="gallery">
        <button type="submit" name="product_submit" class="btn btn-primary btn-lg" value="save">Save</button>
        <button type="submit" name="product_submit" class="btn btn-primary btn-lg" value="save_and_stay">Save &
            Stay</button>
        <a href="{{ route('admin.products.index') }}" class="btn btn-primary btn-lg">Cancel</a>
    </form>
</div>
