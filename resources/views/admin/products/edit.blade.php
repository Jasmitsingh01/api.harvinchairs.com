@extends('layouts.admin')
@section('content')
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <div class="card">
        <div class="card-header">
            {{ trans('global.edit') }} {{ trans('cruds.product.title_singular') }} : <span
                class="prod_name">{{ $product->name }}</span>
            <span class="float-right">
                @if (isset($product->default_product_category))
                    <a class="text-theme-color text-decoration-none"
                        href="{{ config('shop.dashboard_url') . '/' . $product->default_product_category->slug . '/' . $product->id . '-' . $product->slug }}"
                        target="_blank">
                        <i class="fa-solid fa-eye"></i> Preview
                    </a>
                @else
                    <a class="text-theme-color text-decoration-none"
                        href="{{ config('shop.dashboard_url') . '/' . $product->id . '-' . $product->slug }}"
                        target="_blank">
                        <i class="fa-solid fa-eye"></i> Preview
                    </a>
                @endif
            </span>
        </div>
        <div class="card-body">

            <div class="row add-product-wrap">
                <div class="col-md-3">
                    <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                        <a class="nav-link active" id="v-pills-home-tab" data-bs-toggle="pill" href="#v-pills-information"
                            role="tab" aria-controls="v-pills-information" aria-selected="true">Information</a>
                        <a class="nav-link" id="v-pills-association-tab" data-bs-toggle="pill" href="#v-pills-association"
                            role="tab" aria-controls="v-pills-association" aria-selected="false">Category</a>
                        <a class="nav-link" id="v-pills-dimensions-tab" data-bs-toggle="pill" href="#v-pills-dimensions"
                            role="tab" aria-controls="v-pills-dimensions" aria-selected="false">Dimensions</a>
                        <a class="nav-link" id="v-pills-seo-tab" data-bs-toggle="pill" href="#v-pills-seo" role="tab"
                            aria-controls="v-pills-seo" aria-selected="false">SEO</a>
                        <a class="nav-link" id="v-pills-gallery-tab" data-bs-toggle="pill" href="#v-pills-gallery"
                            role="tab" aria-controls="v-pills-gallery" aria-selected="false">Gallery</a>
                        <a class="nav-link" id="v-pills-combination-tab" data-bs-toggle="pill" href="#v-pills-combination"
                            role="tab" aria-controls="v-pills-combination" aria-selected="false">Combination</a>
                        <a class="nav-link" id="v-pills-discount-tab" data-bs-toggle="pill" href="#v-pills-discount"
                            role="tab" aria-controls="v-pills-discount" aria-selected="false">Discount</a>
                        <a class="nav-link" id="v-pills-features-tab" data-bs-toggle="pill" href="#v-pills-features"
                            role="tab" aria-controls="v-pills-features" aria-selected="false">Features</a>
                        <a class="nav-link" id="v-pills-video-tab" data-bs-toggle="pill" href="#v-pills-video"
                            role="tab" aria-controls="v-pills-video" aria-selected="false">Videos</a>
                        {{-- <a class="nav-link" id="v-pills-postcode-tab" data-bs-toggle="pill" href="#v-pills-postcode"
                            role="tab" aria-controls="v-pills-postcode" aria-selected="false">Postal Codes</a> --}}
                        <a class="nav-link" id="v-pills-faq-tab" data-bs-toggle="pill" href="#v-pills-faq"
                            role="tab" aria-controls="v-pills-faq" aria-selected="false">FAQ</a>
                        <a class="nav-link" id="v-pills-gst-tab" data-bs-toggle="pill" href="#v-pills-gst"
                            role="tab" aria-controls="v-pills-gst" aria-selected="false">GST</a>
                        {{-- <a class="nav-link" id="v-pills-hotdeals-tab" data-bs-toggle="pill" href="#v-pills-hotdeals"
                            role="tab" aria-controls="v-pills-hotdeals" aria-selected="false">Hot Deals</a> --}}
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="tab-content" id="v-pills-tabContent">
                        @include('admin.products.tabs.information-tab')

                        @include('admin.products.tabs.discount-tab')

                        @include('admin.products.tabs.seo-tab')

                        @include('admin.products.tabs.association-tab')

                        @include('admin.products.tabs.combination-tab')

                        @include('admin.products.tabs.dimensions-tab')

                        @include('admin.products.tabs.gallery-tab')

                        @include('admin.products.tabs.features-tab')

                        {{-- @include('admin.products.tabs.postcode-tab') --}}

                        @include('admin.products.tabs.faq-tab')

                        @include('admin.products.tabs.gst-tab')

                        {{-- @include('admin.products.tabs.hotdeals-tab') --}}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addFeatureValueModal" tabindex="-1" role="dialog"
        aria-labelledby="addFeatureModalValueLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addFeatureModalValueLabel">Add Feature and Value</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <label for="feature">Feature:</label>
                            <select class="form-control" id="modal_feature_id" name="modal_feature_id">
                                @foreach ($features as $feature)
                                    <option value="{{ $feature->id }}">{{ $feature->title }}</option>
                                @endforeach
                                <!-- Add more options as needed -->
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="value">Value:</label>
                            <input type="text" class="form-control" id="feature_value" name="feature_value">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Save</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="combinationModal" tabindex="-1" role="dialog" aria-labelledby="combinationModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="combinationModalLabel">Combinations</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" id="information_form" action="{{ route('admin.product-attributes.store') }}">
                        @csrf
                        <div class="form-group">
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <label for="modal_attribute_id">Attribute:</label>
                            <br />
                            <div class="container">
                                <div class="row">
                                    @foreach ($attributes as $key => $attribute)
                                        @if ($attribute->values->isNotEmpty())
                                            <div class="col-md-6 mt-4">
                                                <strong class="sel_attr_name">{{ $attribute->name }}</strong>
                                                <select class="form-control" data-attr="{{ $attribute->name }}"
                                                    id="modal_attribute_id" data-id="{{ $attribute->id }}"
                                                    style="height: 100px;" name="attribute-{{ $attribute->id }}[]" multiple>
                                                    @foreach ($attribute->values as $value)
                                                        <option class="" value="{{ $value->id }}">
                                                            {{ trim($value->value) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                {{-- <div class="form-group">
                    <label for="default_quantity">Default Quantity:</label>
                    <input type="number" class="form-control" id="default_quantity" name="default_quantity">
                </div>
                <div class="form-group">
                    <label for="default_reference">Default Reference:</label>
                    <input type="text" class="form-control" id="default_reference" name="default_reference">
                </div> --}}
                <input type="hidden" name="tabname" value="combination">
                <input type="hidden" id="selected_attrs" value="{{ json_encode($all_attr_ids) }}">
                <div class="form-group">
                    <button type="button" class="btn btn-primary " id="viewButton">View Selected Attributes</button>
                </div>

                <div id="selectedAttributesBox">
                    <h5>Selected Attributes:</h5>
                    <ul id="selectedAttributesList" class="p-4 list-unstyled border border-info rounded rounded-9"></ul>
                </div>
                <div id="customErrorMessage" class="alert alert-danger" style="display: none;"></div>
                <div class="form-group">
                    <label class="text-danger" id="attr_combination_error"></label><br>
                    <input type="submit" class="btn btn-primary " name='save_combinations' id="save_combinations"
                        value="Save">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    </div>

    <div class="modal fade" id="addFeatureModal" tabindex="-1" role="dialog" aria-labelledby="addFeatureModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addFeatureModalLabel">Add New Feature</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <label for="new_feature_title">Feature:</label>
                            <input type="text" class="form-control" id="new_feature_title" name="new_feature_title">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addCombinationModal" tabindex="-1" role="dialog"
        aria-labelledby="addCombinationModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCombinationModalLabel">Add New Combination</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" id="new_combination_form" action="{{ route('admin.product-attributes.store') }}">
                    @csrf
                    <div class="modal-body">

                        <div class="form-group">
                            <label for="new_feature_title">Attributes:</label>
                            <select class="form-control" id="new_attribute" name="new_attribute" required>
                                @foreach ($attributes as $attribute)
                                    <option value="{{ $attribute->id }}">{{ $attribute->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="attributeValue">Attribute Value:</label>
                            <select id="attributeValue" class="form-control" name="attributeValue" required>
                                <option value="">Select an attribute value</option>
                                <!-- This dropdown will be populated dynamically -->
                            </select>
                        </div>
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="tabname" value="combination">


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <div class="form-group">
                            <input type="submit" class="btn btn-primary" name='new_combination_btn' value="Save">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="linkImageModal" tabindex="-1" role="dialog" aria-labelledby="linkImageModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <span class="modal-title" id="linkImageModalLabel">
                        <h5>Link Image</h5>
                        <strong>{{ trans('global.edit') }} {{ trans('cruds.product.title_singular') }} : <span
                                class="prod_name">{{ $product->name }}</strong>
                    </span>
                    </span>
                    <button id="closeImageModalButton" type="button" class="close" data-dismiss="modal"
                        aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" id="new_combination_form" action="{{ route('admin.product-attributes.store') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group" id="checkboxContainer">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" name="link_photos" id="link_photos">
                                <label class="custom-control-label" for="link_photos">Link Photos to Combination</label>
                            </div>
                        </div>
                        <div id="imageContainer" style="display: none;">
                            <div class="combination-img-listing">
                                @if (isset($product->gallery))
                                    @foreach ($product->gallery as $image)
                                        @if (isset($image['id']))
                                            @php
                                                $id = $image['id'];
                                            @endphp
                                        @else
                                            @php
                                                $id = '';
                                            @endphp
                                        @endif
                                        <div class="image-wrapper position-relative"
                                            id="img-wrapper-{{ $id }}">
                                            <img src="{{ $image['thumbnail'] }}" alt="alt-img" class="img-fluid"
                                                data-image="{{ json_encode($image) }}" id="img-tag-{{ $id }}">
                                            <i class="fa-regular fa-solid fa-circle-check opacity-0"></i>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                            <input type="hidden" name="selected_images" id="selected_images">
                            <div class="modal-footer justify-content-start ps-0 mt-4">
                                <button type="button" id="closeImageModalButton1" class="btn btn-secondary">Close</button>
                                <a type="button" class="btn btn-primary" id="linkAttributeImages">Save</a>
                            </div>
                            {{-- <a id="linkAttributeImages">Submit Form</a> --}}
                        </div>
                        <div id="tableContainer" style="display: none;">
                            <div class="datatable-dashv1-list custom-datatable-overright">
                                @if (!isset($product->gallery))
                                    <tr id="Body_trNoData">
                                        <td class="text-center" colspan="13"><i
                                                class="bi bi-exclamation-triangle"></i>&nbsp;No Images Found.</td>
                                    </tr>
                                @else
                                    <table class="table" id="image_table">
                                        <thead>
                                            <tr>
                                                <th style="text-align: left; width: 15%;">Image</th>
                                                <th style="text-align: left; width: 15%;">Combinations</th>
                                                <th style="text-align: left; width: 15%;">Actions</th>
                                                <!-- Add more table headers as needed -->
                                            </tr>
                                        </thead>
                                        <tbody id="comb-table-body">
                                            @if ($product->product_combinations->isNotEmpty())
                                                @foreach ($product->product_combinations as $combination)
                                                    <tr>
                                                        @if (isset($combination->images))
                                                            <td id="image{{ $combination->id }}"><img
                                                                    src="{{ $combination->images[0]['thumbnail'] }}"
                                                                    alt="Image" width="50">
                                                            </td>
                                                        @else
                                                            <td class=" left" style=""><img
                                                                    src="{{ asset('images/placeholder.png') }} "
                                                                    alt="Image" width="50"></td>
                                                        @endif

                                                        <td> {{ $combination->all_combination }}</td>
                                                        <td><a id="photo_select_btn" class="btn btn-secondary"
                                                                data-attribute-id="{{ $combination->id }}"
                                                                data-allimages="{{ json_encode($combination->images) }}">Select
                                                                Photos</a></td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr id="Body_trNoData">
                                                    <td class="text-center" colspan="13"><i
                                                            class="bi bi-exclamation-triangle"></i>&nbsp;No Combinations.
                                                    </td>
                                                </tr>
                                            @endif

                                        </tbody>
                                    </table>
                                @endif


                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" id="closeImageModalButton"
                                    data-dismiss="modal">Close</button>
                                <div class="form-group">
                                    <a type="button" class="btn btn-primary" id="updateAttributePostion"
                                        data-dismiss="modal">Save</a>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="tabname" value="combination">


                    </div>
                    {{-- <div class="modal-footer">

                </div> --}}
                </form>
            </div>
        </div>
    </div>

    <!--Modal popup to update combination values-->
    <div class="modal fade" id="combination_value_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">Modal Header</h4>
                    <button type="button" class="close combination_value_modal_close" data-dismiss="modal"
                        aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="modal-body">
                    <div class="form-group" style="user-select: auto;">
                        <input class="form-control " type="text" id="combination_value">
                        <span class="validation-errors text-danger"></span>
                    </div>
                    <div class="form-group" style="user-select: auto;">
                        <button class="btn btn-primary btn-lg" id="update_combination">Update</button>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary combination_value_modal_close"
                        data-dismiss="modal">Close</button>
                    <!-- Add any other buttons if needed -->
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="{{ asset('js/meta_fields.js') }}"></script>
    <script src="{{ asset('js/product_category_discount.js') }}"></script>

    <script>
        Dropzone.options.coverImageDropzone = {
            url: '{{ route('admin.products.storeMedia') }}',
            maxFilesize: {{ config('constants.FILEMAXSIZE')}}, // MB
            acceptedFiles: '.jpeg,.jpg,.png,.gif',
            maxFiles: 1,
            addRemoveLinks: true,
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            params: {
                size: {{ config('constants.FILEMAXSIZE')}},
                width: {{ config('constants.FILEWIDTH')}},
                height: {{ config('constants.FILEWIDTH')}}
            },
            success: function(file, response) {
                $('form').find('input[name="cover_image"]').remove()
                $('form').append('<input type="hidden" name="cover_image" value="' + response.name + '">')
                $(':input[type="submit"]').prop('disabled', false);
            },
            removedfile: function(file) {
                file.previewElement.remove()
                if (file.status !== 'error') {
                    $('form').find('input[name="cover_image"]').remove()
                    this.options.maxFiles = this.options.maxFiles + 1
                }
                $(':input[type="submit"]').prop('disabled', false);
            },
            init: function() {
                @if (isset($product) && $product->cover_image)
                    var file = {!! json_encode($product->cover_image) !!}
                    this.options.addedfile.call(this, file)
                    this.options.thumbnail.call(this, file, file.thumbnail ?? file.thumbnail)
                    //   file.previewElement.classList.add('dz-complete')
                    // $('form').append('<input type="hidden" name="cover_image" value="' + file.thumbnail + '">')
                    this.options.maxFiles = this.options.maxFiles - 1
                @endif
            },
            error: function(file, response) {
                if ($.type(response) === 'string') {
                    var message = response //dropzone sends it's own error messages in string
                } else {
                    var message = response.errors.file
                }
                file.previewElement.classList.add('dz-error')
                _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
                _results = []
                for (_i = 0, _len = _ref.length; _i < _len; _i++) {
                    node = _ref[_i]
                    _results.push(node.textContent = message)
                }
                $(':input[type="submit"]').prop('disabled', true);
                return _results
            }
        }
    </script>
     <script>
        Dropzone.options.dimensionImageDropzone = {
            url: '{{ route('admin.products.storeMedia') }}',
            maxFilesize: {{ config('constants.FILEMAXSIZE')}}, // MB
            acceptedFiles: '.jpeg,.jpg,.png,.gif',
            maxFiles: 1,
            addRemoveLinks: true,
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            params: {
                size: {{ config('constants.FILEMAXSIZE')}},
                width: {{ config('constants.FILEWIDTH')}},
                height: {{ config('constants.FILEWIDTH')}}
            },
            success: function(file, response) {
                $('form').find('input[name="dimension_image"]').remove()
                $('form').append('<input type="hidden" name="dimension_image" value="' + response.name + '">')
                $(':input[type="submit"]').prop('disabled', false);
            },
            removedfile: function(file) {
                file.previewElement.remove()
                if (file.status !== 'error') {
                    $('form').find('input[name="dimension_image"]').remove()
                    this.options.maxFiles = this.options.maxFiles + 1
                }
                $(':input[type="submit"]').prop('disabled', false);
            },
            init: function() {
                @if (isset($product) && $product->dimension_image)
                    var file = {!! json_encode($product->dimension_image) !!}
                    this.options.addedfile.call(this, file)
                    this.options.thumbnail.call(this, file, file.preview ?? file.thumbnail)
                      file.previewElement.classList.add('dz-complete')
                    $('form').append('<input type="hidden" name="dimension_image" value="' + file.name + '">')
                    this.options.maxFiles = this.options.maxFiles - 1
                @endif
            },
            error: function(file, response) {
                if ($.type(response) === 'string') {
                    var message = response //dropzone sends it's own error messages in string
                } else {
                    var message = response.errors.file
                }
                file.previewElement.classList.add('dz-error')
                _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
                _results = []
                for (_i = 0, _len = _ref.length; _i < _len; _i++) {
                    node = _ref[_i]
                    _results.push(node.textContent = message)
                }
                $(':input[type="submit"]').prop('disabled', true);
                return _results
            }
        }
    </script>
    <script>
        $(document).ready(function() {
            function SimpleUploadAdapter(editor) {
                editor.plugins.get('FileRepository').createUploadAdapter = function(loader) {
                    return {
                        upload: function() {
                            return loader.file
                                .then(function(file) {
                                    return new Promise(function(resolve, reject) {
                                        // Init request
                                        var xhr = new XMLHttpRequest();
                                        xhr.open('POST',
                                            '{{ route('admin.products.storeCKEditorImages') }}',
                                            true);
                                        xhr.setRequestHeader('x-csrf-token', window._token);
                                        xhr.setRequestHeader('Accept', 'application/json');
                                        xhr.responseType = 'json';

                                        // Init listeners
                                        var genericErrorText =
                                            `Couldn't upload file: ${ file.name }.`;
                                        xhr.addEventListener('error', function() {
                                            reject(genericErrorText)
                                        });
                                        xhr.addEventListener('abort', function() {
                                            reject()
                                        });
                                        xhr.addEventListener('load', function() {
                                            var response = xhr.response;

                                            if (!response || xhr.status !== 201) {
                                                return reject(response && response
                                                    .message ?
                                                    `${genericErrorText}\n${xhr.status} ${response.message}` :
                                                    `${genericErrorText}\n ${xhr.status} ${xhr.statusText}`
                                                );
                                            }

                                            $('form').append(
                                                '<input type="hidden" name="ck-media[]" value="' +
                                                response.id + '">');

                                            resolve({
                                                default: response.url
                                            });
                                        });

                                        if (xhr.upload) {
                                            xhr.upload.addEventListener('progress', function(
                                                e) {
                                                if (e.lengthComputable) {
                                                    loader.uploadTotal = e.total;
                                                    loader.uploaded = e.loaded;
                                                }
                                            });
                                        }

                                        // Send request
                                        var data = new FormData();
                                        data.append('upload', file);
                                        data.append('crud_id', '{{ $category->id ?? 0 }}');
                                        xhr.send(data);
                                    });
                                })
                        }
                    };
                }
            }

            var allEditors = document.querySelectorAll('.ckeditor');
            for (var i = 0; i < allEditors.length; ++i) {
                ClassicEditor.create(
                    allEditors[i], {
                        image: {
                            styles: [
                                'alignCenter',
                                'alignLeft',
                                'alignRight'
                            ],
                            resizeOptions: [
                                {
                                    name: 'resizeImage:original',
                                    label: 'Original',
                                    value: null
                                },
                                {
                                    name: 'resizeImage:50',
                                    label: '50%',
                                    value: '50'
                                },
                                {
                                    name: 'resizeImage:75',
                                    label: '75%',
                                    value: '75'
                                }
                            ],
                            toolbar: [
                                'imageTextAlternative', 'toggleImageCaption', '|',
                                'imageStyle:inline', 'imageStyle:wrapText', 'imageStyle:breakText', '|',
                                'resizeImage', '|', 'ckboxImageEdit'
                            ]
                        },
                        extraPlugins: [SimpleUploadAdapter]
                    }
                );
            }
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var urlParams = new URLSearchParams(window.location.search);
            var activeTab = urlParams.get('activeTab');
            var popup = urlParams.get('popup');

            if (popup == 'imagelink') {
                var modal = document.getElementById('linkImageModal');
                var btn = $('#image_link_btn').click();
                $("#tableContainer").show();
                var checkbox = document.getElementById('link_photos');
                checkbox.checked = true;
                var currentUrl = window.location.href;
                var newUrl = currentUrl.replace('&popup=imagelink', '');
                window.history.replaceState({}, document.title, newUrl);

                // $('#linkImageModal').modal('show');
            }
            if (activeTab) {
                var tabLink = document.querySelector('#v-pills-' + activeTab + '-tab');
                if (tabLink) {
                    tabLink.click();
                }
            }
        });
    </script>
    <script>
        Dropzone.options.videoDropzone = {
            url: '{{ route('admin.products.storeMedia') }}',
            maxFilesize: {{ config('constants.FILEMAXSIZE')}}, // MB
            acceptedFiles: '.jpeg,.jpg,.png,.gif',
            maxFiles: 1,
            addRemoveLinks: true,
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            params: {
                size: {{ config('constants.FILEMAXSIZE')}},
                width: {{ config('constants.FILEWIDTH')}},
                height: {{ config('constants.FILEWIDTH')}}
            },
            success: function(file, response) {
                $('form').find('input[name="video"]').remove()
                $('form').append('<input type="hidden" name="video" value="' + response.name + '">')
            },
            removedfile: function(file) {
                file.previewElement.remove()
                if (file.status !== 'error') {
                    $('form').find('input[name="video"]').remove()
                    this.options.maxFiles = this.options.maxFiles + 1
                }
            },
            init: function() {
                @if (isset($product) && $product->video)
                    var file = {!! json_encode($product->video) !!}
                    this.options.addedfile.call(this, file)
                    this.options.thumbnail.call(this, file, file.preview ?? file.preview_url)
                    file.previewElement.classList.add('dz-complete')
                    $('form').append('<input type="hidden" name="video" value="' + file.file_name + '">')
                    this.options.maxFiles = this.options.maxFiles - 1
                @endif
            },
            error: function(file, response) {
                if ($.type(response) === 'string') {
                    var message = response //dropzone sends it's own error messages in string
                } else {
                    var message = response.errors.file
                }
                file.previewElement.classList.add('dz-error')
                _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
                _results = []
                for (_i = 0, _len = _ref.length; _i < _len; _i++) {
                    node = _ref[_i]
                    _results.push(node.textContent = message)
                }

                return _results
            }
        }
    </script>
    <script>
        var uploadedGalleryMap = {}
        // var saveButton = $('.save_images');
        var saveButton = document.getElementById('save_images');
        var table = document.getElementById('image_table');

        var caption = document.getElementById('caption').value;
        var caption_title = '{{ $product->name }}';
        var imageTableBody = document.getElementById('image-table-body');
        var updatePositions = function() {
            var rows = imageTableBody.querySelectorAll('tr');
            for (var i = 0; i < rows.length; i++) {
                rows[i].querySelector('.position').textContent = i + 1;
            }
        };
        var data = [];
        Dropzone.options.galleryDropzone = {
            url: '{{ route('admin.products.storeMedia') }}',
            maxFilesize: {{ config('constants.FILEMAXSIZE')}}, // MB
            acceptedFiles: '.jpeg,.jpg,.png,.gif',
            // addRemoveLinks: true,
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            params: {
                size: {{ config('constants.FILEMAXSIZE')}},
                width: {{ config('constants.FILEWIDTH')}},
                height: {{ config('constants.FILEWIDTH')}}
            },
            // previewTemplate: '',
            success: function(file, response) {
                file.previewElement.remove();
                var newRow = '<tr data-entry-id="1" id="comb_' + imageTableBody.children.length + 1 +
                    '" draggable="true">' +
                    '<td><img src="' + file.dataURL + '" alt="Image" width="50"></td>' +
                    '<td class="caption-td" id="caption[' + (imageTableBody.children.length + 1) +
                    ']"><input type="text"  class="form-control caption_new" data-filename="' + response.name +
                    '" name="caption[' + (imageTableBody
                        .children.length + 1) + ']" value="' + caption_title + '"></td>' +
                    '<td class="position" draggable="true"><input type="text" readonly data-filename="' + response
                    .name + '"class="form-control position-input" name="position[' + (imageTableBody.children
                        .length + 1) + ']" value="' + (imageTableBody.children.length + 1) + '"></td>' +
                    '<td><a id="Body_ItemsListView_lnkDelete_0" class="dz-remove delete-btn" style="width: 25px; padding: 4px 3px;"><i class="fa-solid fa-trash-can"></i></a></td>' +
                    '</tr>';
                imageTableBody.innerHTML += newRow;

                // Show the save button
                table.style.display = 'block';
                saveButton.style.display = 'block';
                data.push({
                    'filename': response.name,
                    'position': (imageTableBody.children.length + 1),
                    'caption': caption
                });
                var gallery = JSON.stringify(data);
                // console.log(gallery);
                $('form').append('<input type="hidden" name="gallery[' + (imageTableBody.children.length) +
                    ']" value="' + response.name + '">')

            },
            removedfile: function(file) {
                file.previewElement.remove()
                var name = ''
                if (typeof file.file_name !== 'undefined') {
                    name = file.file_name
                } else {
                    name = uploadedGalleryMap[file.name]
                }
                // Update positions
                updatePositions();
                $('form').find('input[name="gallery[]"][value="' + name + '"]').remove()
            },
            init: function() {
                @if (isset($product) && $product->gallery)
                    var files = {!! json_encode($product->gallery) !!}

                    for (var i in files) {

                        var file = files[i]
                        // this.options.addedfile.call(this, file)
                        // this.options.thumbnail.call(this, file, file.thumbnail ?? file.thumbnail)
                        // file.previewElement.classList.add('dz-complete')
                        //   $('form').append('<input type="hidden" name="gallery[]" value="' + file.file_name + '">')
                    }
                @endif
            },
            error: function(file, response) {
                if ($.type(response) === 'string') {
                    var message = response //dropzone sends it's own error messages in string
                } else {
                    var message = response.errors.file
                }
                file.previewElement.classList.add('dz-error')
                _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
                _results = []
                for (_i = 0, _len = _ref.length; _i < _len; _i++) {
                    node = _ref[_i]
                    _results.push(node.textContent = message)
                }

                return _results
            }
        }
    </script>
    <script>
        // resources/js/attribute-form.js
        $(document).ready(function() {
            $('table').on('click', '.delete-btn', function() {
                // console.log('clicked');
                var saveButton = document.getElementById('save_images');
                var updatePositions = function() {
                    var rows = imageTableBody.querySelectorAll('tr');
                    for (var i = 0; i < rows.length; i++) {
                        rows[i].querySelector('.position').textContent = i + 1;
                    }
                };
                // Remove the parent row when delete button is clicked
                $(this).closest('tr').remove();
                // Update positions
                // updatePositions();
                if (document.querySelectorAll('#image-table-body tr').length === 0) {
                    saveButton.style.display = 'none'; // Hide the save button
                }
            });

            $('#new_attribute').on('change', function() {
                var attributeId = $(this).val();
                // console.log(attributeId);
                // Make the AJAX request
                $.ajax({
                    url: '{{ route('admin.attribute-values.get') }}',
                    method: 'GET',
                    data: {
                        attribute_id: attributeId
                    },
                    success: function(response) {
                        // Update the dynamic dropdown with the fetched attribute values
                        var attributeValueDropdown = $('#attributeValue');
                        attributeValueDropdown.empty();
                        attributeValueDropdown.append(
                            '<option value="">Select an attribute value</option>');

                        $.each(response, function(key, value) {
                            attributeValueDropdown.append('<option value="' + value.id +
                                '">' + value.value + '</option>');
                        });
                    },
                    error: function(xhr, status, error) {
                        // Handle the error
                        console.error(error);
                    }
                });
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('.delete-combination').click(function() {
                var combinationId = $(this).data('combination-id');
                var confirmationMessage = '{{ trans('global.areYouSure') }}';
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Delete it!'
                    }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ route('admin.product-attributes.destroy', ':id') }}'
                                .replace(
                                    ':id', combinationId),
                            type: 'POST',
                            data: {
                                _method: 'DELETE',
                                _token: '{{ csrf_token() }}',
                                tabname: 'combination',
                                product_id: '{{ $product->id }}'
                            },
                            success: function(response) {
                                // Handle the success response
                                // For example, you can remove the row from the table
                                window.location.href = window.location.pathname + '?' + 'activeTab=combination';
                            },
                            error: function(xhr) {
                                // Handle the error response
                                // console.log(xhr.responseText);
                            }
                        });
                        Swal.fire(
                        'Deleted!',
                        'Attribute has been Deleted.',
                        'success'
                        )
                    }
                });
            });

            $('.delete-faq').click(function() {
                var faqId = $(this).data('faq-id');
                var confirmationMessage = '{{ trans('global.areYouSure') }}';
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Delete it!'
                    }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ route('admin.faqs.destroy', ':id') }}'
                                .replace(
                                    ':id', faqId),
                            type: 'POST',
                            data: {
                                _method: 'DELETE',
                                _token: '{{ csrf_token() }}',
                                tabname: 'faq',
                                product_id: '{{ $product->id }}'
                            },
                            success: function(response) {
                                // Handle the success response
                                // For example, you can remove the row from the table
                                window.location.href = window.location.pathname + '?' + 'activeTab=faq';
                            },
                            error: function(xhr) {
                                // Handle the error response
                                // console.log(xhr.responseText);
                            }
                        });
                        Swal.fire(
                        'Deleted!',
                        'Faq has been Deleted.',
                        'success'
                        )
                    }
                });
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            // Function to handle the click on the "Close Modal" button
            $('#closeImageModalButton').on('click', function() {
                $("#imageContainer").hide();
                $("#tableContainer").show();
                $("#checkboxContainer").show();
            });
            $('#closeImageModalButton1').on('click', function() {
                $("#imageContainer").hide();
                $("#tableContainer").show();
                $("#checkboxContainer").show();
            });
            // Handle checkbox click event
            $("#link_photos").on("change", function() {

                if ($(this).is(":checked")) {
                    $("#tableContainer").show();
                } else {
                    $("#tableContainer").hide();
                }
            });

            $("a#photo_select_btn").on("click", function() {
                var dataAttributeId = $(this).data("attribute-id");
                var allImages = $(this).data("allimages");
                if (dataAttributeId) {
                    $("#tableContainer").hide();
                    $("#checkboxContainer").hide();

                    // Check if the element exists
                    if ($('input[name="product_attribute_id"]').length > 0) {
                        // If the element exists, update its value
                        $('input[name="product_attribute_id"]').val(dataAttributeId);
                    } else {
                        // If the element does not exist, append it to the desired container
                        $("#imageContainer").append(
                            '<input type="hidden" name="product_attribute_id" value="' +
                            dataAttributeId + '">');
                    }

                    // console.log(allImages);
                    if (allImages) {
                        $.each(allImages, function(index, item) {
                            var id = item.id;
                            // $("#img-wrapper-" + id).addClass('selected');
                            var imgTagElement = $("#img-tag-" + id);

                            if (!imgTagElement.hasClass('selected')) {
                                imgTagElement.click();
                            }
                        });
                        // $('.image-wrapper img.selected').click();
                        // $('input[name="selected_images"]').val(JSON.stringify(allImages));
                    }

                    $("#imageContainer").show();

                } else {
                    $("#imageContainer").hide();
                    $("#tableContainer").show();
                }
            });

            // Handle button click event (optional, if you want to show/hide using a button)
            $("#btnToggleTable").on("click", function() {
                $("#tableContainer").toggle();
            });

            $("#linkAttributeImages").click(function(e) {
                e.preventDefault();

                // Get the selected image references
                // var selectedImages = [];
                // $('input[name="selected_images[]"]').each(function() {
                //     var value = JSON.parse($(this).val());
                //     selectedImages.push(value);
                // });

                var selectedImages = $('input[name="selected_images"]').val();
                var productAttributeId = $('input[name="product_attribute_id"]').val();
                // You can do further processing with the selected image references here,
                // such as submitting the form data with the selected images using AJAX.

                // For example, if you want to submit the form with the selected images:
                $.ajax({
                    url: '{{ route('admin.product-attributes.update-image') }}',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    data: {
                        // Add your other form data here
                        selected_images: selectedImages,
                        product_attribute_id: productAttributeId
                    },
                    success: function(response) {
                        var url = response.data.images[0]['thumbnail'];
                        var imageTd = $('#image' + productAttributeId);

                        imageTd.html('<img src="' + url + '" alt="Updated Image" width="50">');

                        $('input[name="selected_images"]').val('');
                        $('.image-wrapper').removeClass('selected');
                        $('.img-fluid').removeClass('selected');

                        $("#imageContainer").hide();
                        $("#tableContainer").show();
                        window.location.href = window.location.pathname + '?' +
                            'activeTab=combination&popup=imagelink';

                    },
                    error: function(xhr, status, error) {
                        // Handle errors
                    }
                });
            });
        });
    </script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>
    <script>
        $(".sortable-list").sortable({
            axis: 'y',
            update: function(event, ui) {
                var data = $(this).sortable('serialize');
                console.log(data);
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    url: '{{ route('admin.product-attributes.updatePositions') }}',
                    type: 'POST',
                    data: data,
                    success: function(response) {
                        //  updateDisplayedPositions(response.positions);
                    }
                });
            }
        });

        function updateDisplayedPositions(positions) {
            $('#category-table tbody tr').each(function(index) {
                // Get the product ID from the row data attribute
                var productId = $(this).data('entryId');
                // Find the position for the corresponding product ID
                var position = positions[productId];
                // Update the position number in the relevant column
                $(this).find('.position').text(position);
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            const tableBody = document.querySelector('#image_table tbody');

            Sortable.create(tableBody, {
                animation: 150,
                onEnd: function(evt) {
                    updateDisplayedPositions(tableBody);
                }
            });

            function updateDisplayedPositions(tableBody) {
                const rows = tableBody.querySelectorAll('tr');
                rows.forEach((row, index) => {
                    const positionCell = row.querySelector('.position');
                    const captionCell = row.querySelector('.caption-td');

                    const positionExtInput = positionCell.querySelector('.position-ext-input');
                    const positionInput = positionCell.querySelector('.position-input');
                    const positionNumber = index + 1;
                    const id = captionCell.querySelector('.caption_ext');
                    const new_id = captionCell.querySelector('.caption_new');
                    var key = 0;
                    var newkey = 0;

                    if (id !== null) {
                        inputString = id.name;
                        var regex = /(\d+)/; // Regular expression to match digits
                        var key = inputString.match(regex)[1];
                    }
                    if (new_id !== null) {
                        inputString = new_id.name;
                        var regex = /(\d+)/; // Regular expression to match digits
                        var newkey = inputString.match(regex)[1];
                    }
                    if (positionExtInput) {
                        positionCell.innerHTML =
                            '<input type="text" class="form-control position-ext-input" name="position_ext[' +
                            (key) + ']" value="' + (index + 1) + '">';

                    }
                    if (positionInput) {
                        positionCell.innerHTML =
                            '<input type="text" class="form-control position-input" name="position[' + (
                                newkey) + ']" value="' + (index + 1) + '">';
                    }
                });
            }
        });
    </script>
    <script>
        $(document).ready(function() {
            let selectedImages = [];

            // Function to update the selected_images input field with selected images
            function updateSelectedImages() {
                const selectedImageIds = selectedImages.map(image => image.id);
                $('#selected_images').val(JSON.stringify(selectedImages));
            }
            // Function to check if an image with a given ID is in the selectedImages array
            function isImageSelected(imageId) {
                    return selectedImages.some(image => image.id === imageId);
                }
            // Function to handle image selection/deselection
            $('.image-wrapper img').on('click', function() {
                const image = $(this).data('image');
                const imageId = image.id;
                $(this).toggleClass("selected");
                $(this).parent(".image-wrapper").toggleClass("selected");

                const isSelected = isImageSelected(imageId);

                if (!isSelected) {
                    // Image not selected, so add it to the selectedImages array
                    selectedImages.push(image);
                } else {
                    // Image already selected, so remove it from the selectedImages array
                    selectedImages = selectedImages.filter(img => img.id !== imageId);
                }

                updateSelectedImages();
            });

            // $('#linkAttributeImages').on('click', function() {
            //     selectedImages = [];
            // });

        });
    </script>
    <script>
        $(document).ready(function() {
            function nextTab() {
                const $activeTab = $('.nav-pills .nav-link.active');
                $activeTab.next('a.nav-link').tab('show');
            }

            // Function to switch to the previous tab
            function prevTab() {
                const $activeTab = $('.nav-pills .nav-link.active');
                $activeTab.prev('a.nav-link').tab('show');
            }

            // Bind click event for the "Next" button
            $('.next-tab').click(function() {
                nextTab();
            });

            // Bind click event for the "Previous" button
            $('.prev-tab').click(function() {
                prevTab();
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('a.add-feature-link').click(function(e) {
                e.preventDefault();
                var selectedFeature = $(this).data('feature');
                $('#modal_feature_id').val(selectedFeature);
                // $('#addFeatureModal').modal('show');
            });

            $('#addFeatureValueModal').on('click', '.btn-primary', function() {
                var featureId = $('#modal_feature_id').val();
                var value = $('#feature_value').val();

                // AJAX request
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{ route('admin.feature-values.store') }}",
                    type: 'POST',
                    data: {
                        feature_id: featureId,
                        value: value
                    },
                    success: function(response) {
                        // Handle success response
                        // console.log(response);

                        var tdElement = $('#feature_list' + featureId);
                        var selectElement = $('<select name="value" id="value"></select>');
                        // console.log(response.data.value);

                        // Iterate through the options and create <option> elements
                        var optionElement1 = $('<option></option>').val('').text('select');
                        var optionElement = $('<option></option>').val(response.data.value)
                            .text(response.data.value);
                        optionElement.attr('selected', 'selected');
                        selectElement.append(optionElement1);
                        selectElement.append(optionElement);

                        // Append the select dropdown to the <td> element
                        tdElement.empty().append(selectElement);
                        $('#feature_value').val('');
                    },
                    error: function(xhr, status, error) {
                        // Handle error response
                        console.error(xhr.responseText);
                    }
                });
            });


            $('#addFeatureModal').on('click', '.btn-primary', function() {
                var featureTitle = $('#new_feature_title').val();

                // AJAX request
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{ route('admin.features.store') }}",
                    type: 'POST',
                    data: {
                        title: featureTitle,
                    },
                    success: function(response) {
                        var newData = response.data;
                        // console.log(newData);
                        // Create a new <tr> element with the provided condition
                        var newTr = $('<tr class="highlighted odd selected-line">').html(`
                    <td>${newData.title}</td>
                    <td id="feature_list${newData.id}">
                        ${newData.feature_values.length === 0 ?
                            `<span id="Body_repFeature_AddPre_defined_0">N/A -
                                                                    <a href="#" class="add-feature-link btn btn-link" data-toggle="modal" data-feature="${newData.id}" data-target="#addFeatureValueModal">
                                                                    <i class="icon-plus-sign"></i>Add pre-defined values first <i class="icon-external-link-sign"></i>
                                                                    </a>
                                                                </span>`
                         :
                         `<select name="value" id="value">
                                                                    <option value="">Select</option>
                                                                    ${newData.feature_values.map(value => `<option value="${value.id}">${value.value}</option>`).join('')}
                                                                </select>`
                        }
                    </td>
                    <td>
                        <textarea name="ctl00$Body$repFeature$ctl00$txtCustomeValue" rows="2" cols="20" id="Body_repFeature_txtCustomeValue_0" class="form-control"></textarea>
                    </td>
                    `);

                        // Append the new <tr> element to the table
                        $('#feature_table').append(newTr);
                        $('#addFeatureModal').modal('hide');

                    },
                    error: function(xhr, status, error) {
                        // Handle error response
                        console.error(xhr.responseText);
                    }
                });
            });

            function nextTab() {
                const $activeTab = $('.nav-pills .nav-link.active');
                $activeTab.next('a.nav-link').tab('show');
            }

            // Function to switch to the previous tab
            function prevTab() {
                const $activeTab = $('.nav-pills .nav-link.active');
                $activeTab.prev('a.nav-link').tab('show');
            }

            // Bind click event for the "Next" button
            $('.next-tab btn-lg').click(function() {
                nextTab();
            });

            // Bind click event for the "Previous" button
            $('.prev-tab').click(function() {
                prevTab();
            });
        });
    </script>
    <script>
        $(document).ready(function(e) {
            $('a.enable-slug').click(function(e) {
                $("#slug").attr("readonly", false);
            });

        });
    </script>
    <script>
        $(document).ready(function() {
            var tagsData = @json($tags);
            var existingTags = @json($tags->pluck('name'));
            var newTags = {};

            $('#selected-tags').select2({
                tags: true,
                tokenSeparators: [','],
                createTag: function(params) {
                    var term = $.trim(params.term);
                    if (term === '' || existingTags.includes(term)) {
                        // Return null for existing tags or empty input to prevent the "New Tag" option
                        return null;
                    }

                    return {
                        id: term,
                        text: term,
                        newTag: true // Add a custom property to identify new tags
                    };
                },
                templateResult: function(data) {
                    var $result = $("<span></span>");

                    $result.text(data.text);

                    if (data.newTag) {
                        $result.append(" <em>(New Tag)</em>");
                    }

                    return $result;
                },
            });
            // Show/hide "New Tag" option on select2 open and close events
            $('.tag-selector').on('select2:open', function(e) {
                toggleNewTagOption();
            });

            $('.tag-selector').on('select2:close', function(e) {
                toggleNewTagOption();
            });

            // Function to show/hide the "New Tag" option
            function toggleNewTagOption() {
                var searchTerm = $('.select2-search__field').val();
                var $select2Results = $('.select2-results__options');
                var $newTagOption = $select2Results.find('li[data-select2-tag="true"]');
                if (existingTags.includes(searchTerm)) {
                    // If the search term matches an existing tag, hide the "New Tag" option
                    if ($newTagOption.length > 0) {
                        $newTagOption.hide();
                    }
                } else {
                    // If the search term doesn't match any existing tag, show the "New Tag" option
                    if ($newTagOption.length > 0) {
                        $newTagOption.show();
                    }
                }
            }
            $('.tag-selector').on('select2:select', function(e) {
                var data = e.params.data;
                if (data.newTag) {
                    $.ajax({
                        url: "{{ route('admin.tags.store') }}",
                        type: 'POST',
                        data: {
                            name: data.text,
                            _token: '{{ csrf_token() }}',
                        },
                        success: function(response) {
                            if (existingTags.includes(response.name) && !newTags[response.id]) {
                                // If the tag already exists in the database, don't add a new entry
                                var existingOption = new Option(response.name, response.id,
                                    true, true);
                                $('#selected-tags').append(existingOption).trigger('change');
                                $('#selected-tags').find("option[value='" + data.id + "']")
                                    .remove();
                            } else if (!existingTags.includes(response.name)) {
                                // Assuming the response contains the newly created tag's ID and name
                                tagsData[response.id] = response.id;

                                var newOption = new Option(response.name, response.id, true,
                                    true);
                                $('#selected-tags').append(newOption).trigger('change');
                                $('#selected-tags').find("option[value='" + data.id + "']")
                                    .remove();
                            }

                        },
                        error: function(xhr, textStatus, errorThrown) {
                            console.error(xhr.responseText);
                        }
                    });
                }
            });
            $('.tag-selector').on('select2:selecting', function(e) {
                var data = e.params.args.data;
                if (data.newTag && existingTags.includes(data.text)) {
                    e.preventDefault();
                }
                if (!data.text || data.text.trim() === '') {
                    // If the tag input is empty, prevent adding it as a tag
                    e.preventDefault();
                }
            });
        });
    </script>
    <!--Below script to display and remove selected attributes in popup-->

    <script>
        $(document).ready(function() {
            // Hide the selectedAttributesBox if there are no li elements
            $('#selectedAttributesBox').css('display', $('#selectedAttributesList li').length > 0 ? 'block' :
                'none');

            $('#viewButton').on('click', function() {
                $('#selectedAttributesList').empty();

                const selectedAttributes = {}; // Object to store selected attributes grouped by label

                $('select#modal_attribute_id').each(function() {
                    const selectedOptions = $(this).find('option:selected');

                    if (selectedOptions.length > 0) {
                        const label = $(this).prev('strong').text().trim();

                        selectedOptions.each(function() {
                            const optionValue = $(this).val();
                            const optionText = $(this).text().trim();

                            // Add the attribute to the selectedAttributes object with its label as the key
                            if (!selectedAttributes[label]) {
                                selectedAttributes[label] = [];
                            }

                            selectedAttributes[label].push({
                                value: optionValue,
                                text: optionText
                            });
                        });
                    }
                });

                // Clear the existing attribute groups
                $('#selectedAttributesList').empty();

                // Create a single row to contain all attribute groups
                const attributeGroupsRow = $('<div>').addClass('row');

                // Loop through the selectedAttributes object and create attribute groups
                for (const label in selectedAttributes) {
                    if (selectedAttributes.hasOwnProperty(label)) {
                        const attributeGroup = $('<div>').addClass(
                            'col-md-6 attribute-group mt-3'); // Two columns per group
                        const attributeGroupLabel = $('<div>').addClass(
                            'attribute-group-label font-weight-bold').text(
                            label);
                        attributeGroup.append(attributeGroupLabel);

                        const attributes = selectedAttributes[label];
                        attributes.forEach(function (attribute) {
                            const listItem = $('<li>').text(`${attribute.text}`).attr(
                                'data-option-value', attribute.value).addClass('p-1');
                            const deleteLink = $('<a>').addClass(
                                'text-theme-color border-0 bg-transparent float-right px-0 del-attr ml-2'
                            );
                            const deleteIcon = $('<i>').addClass('fa-solid fa-trash-can');
                            deleteLink.append(deleteIcon);
                            listItem.append(deleteLink);

                            attributeGroup.append(listItem);
                        });

                        // Check if the attribute is "Size" or "Stone Name" and set a different height for the group
                        if (label === "Size" || label === "Stone Name") {
                            attributeGroup.addClass('size-stone-name-group'); // Add a CSS class for styling
                        }

                        attributeGroupsRow.append(attributeGroup);
                    }
                }

                // Append the row containing all attribute groups to the selectedAttributesList
                $('#selectedAttributesList').append(attributeGroupsRow);

                // Hide the selectedAttributesBox if there are no li elements
                $('#selectedAttributesBox').css('display', $('#selectedAttributesList li').length > 0 ?
                    'block' : 'none');
            });

            // Event delegation to handle click on delete link/icon
            $('#selectedAttributesList').on('click', '.del-attr', function() {
                const listItem = $(this).closest('li'); // Get the parent list item
                const optionValue = listItem.data(
                    'option-value'); // Retrieve the stored option value from the data attribute

                // Loop through all the select elements and find the corresponding option to deselect
                $('select#modal_attribute_id').each(function() {
                    const selectElement = $(this);
                    selectElement.find(`option[value="${optionValue}"]`).prop('selected', false);
                });

                // Check if the attribute-group is now empty and remove it if it is
                const attributeGroup = listItem.closest('.attribute-group');
                const isLastAttributeInGroup = attributeGroup.find('li').length === 1;
                if (isLastAttributeInGroup) {
                    attributeGroup.remove();
                } else {
                    listItem.remove();
                }

                // Check if there are any remaining attribute groups, hide the selectedAttributesBox if there are none
                $('#selectedAttributesBox').css('display', $('#selectedAttributesList .attribute-group')
                    .length > 0 ? 'block' : 'none');
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            var isDropdownOpen = false;
            $('#bulkActionsDropdown').on('click', function(e) {
                e.stopPropagation();
                isDropdownOpen = !isDropdownOpen;
                $(this).next('.dropdown-menu').toggle();
            });

            // Handle clicks outside the dropdown to close it
            $(document).on('click', function(e) {
                var bulkActionsDropdown = $('#bulkActionsDropdown');

                // Check if the dropdown is open
                if (!bulkActionsDropdown.is(e.target) && !bulkActionsDropdown.has(e.target).length) {
                    if (isDropdownOpen) {
                        // Dropdown is open, so we can hide it
                        isDropdownOpen = false;
                        $('.dropdown-menu').hide();
                    }
                }
            });

            // Add click listener to the dropdown items
            $('a#bulk_action').on('click', function() {
                var action = $(this).data('action');
                var value = $(this).data('value');

                handleBulkAction(action, value, table);
                $('.dropdown-menu').hide(); // Hide the dropdown after selection
            });

            $('a#bulk_update').on('click', function() {
                const ids = [];
                selectItemCheckboxes.forEach(checkbox => {

                    if (checkbox.checked) {
                        ids.push(checkbox.dataset.id);
                        checkbox.checked = false;
                    }
                });

                if (ids.length === 0) {
                    Swal.fire({
                        title: '{{ trans('global.datatables.zero_selected') }}!',
                        timer: 2000,
                        timerProgressBar: true,
                    });
                    return;
                }

                var field = $(this).data('update');
                $("#combination_value").attr('data-field', field);
                $("#combination_value").attr('data-ids', JSON.stringify(ids));
                $("#combination_value_modal").modal('show');
                $('#myModalLabel').text($(this).text())
                $('.dropdown-menu').hide(); // Hide the dropdown after selection
            });

            // Close the modal when the close button is clicked
            $("#combination_value_modal").on("click", ".combination_value_modal_close", function() {
                $("#combination_value_modal").modal('hide');
            });
        });

        // Select All checkbox functionality
        const selectAllCheckbox = document.getElementById('select-all');
        const selectItemCheckboxes = document.querySelectorAll('.select-item');

        selectAllCheckbox.addEventListener('change', function() {
            selectItemCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });

        // Perform Bulk Action
        function handleBulkAction(action, value, table) {
            const ids = [];
            selectItemCheckboxes.forEach(checkbox => {

                if (checkbox.checked) {
                    ids.push(checkbox.dataset.id);
                    checkbox.checked = false;
                }
            });

            if (ids.length === 0) {
                Swal.fire({
                    title: '{{ trans('global.datatables.zero_selected') }}!',
                    timer: 2000,
                    timerProgressBar: true,
                });
                return;
            }
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Update it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                            headers: {
                                'x-csrf-token': _token,
                            },
                            method: 'POST',
                            url: "{{ route('admin.product-attributes.massUpdate') }}",
                            data: {
                                ids: ids,
                                action: action,
                                value: value,
                                _token: '{{ csrf_token() }}'
                            },
                        })
                        .done(function() {
                            window.location.href = window.location.pathname + '?' + 'activeTab=combination';
                        });
                    Swal.fire(
                        'Updated!',
                        'Product has been Updated.',
                        'success'
                    )
                }
            });
        }

        $('#update_combination').on('click', function() {
            var attr_value = $('#combination_value').val();
            var field = $('#combination_value').data('field');
            var ids = $('#combination_value').data('ids');
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Update it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                            headers: {
                                'x-csrf-token': _token,
                            },
                            method: 'POST',
                            url: "{{ route('admin.product-attributes.bulkUpdate') }}",
                            data: {
                                attr_value: attr_value,
                                field: field,
                                ids: ids,
                                _token: '{{ csrf_token() }}'
                            },
                        })
                        .done(function(response) {
                            if (response.errors) {
                                $('.validation-errors').html(response.errors);
                            } else {
                                window.location.href = window.location.pathname + '?' +
                                    'activeTab=combination';

                            }
                        });
                    Swal.fire(
                        'Updated!',
                        'Product has been Updated.',
                        'success'
                    )
                }
            });
        });

        $(document).ready(function() {
            $('#save_combinations').on('click', function(e) {
                var selected_attrs = JSON.parse($('#selected_attrs').val());
                var attributeNames = [];

                $('select#modal_attribute_id').each(function() {
                    var dataId = parseInt($(this).attr('data-id'));
                    var attribute_name = $(this).attr('data-attr');

                    // Check if the data-id value exists in the idArray
                    if (selected_attrs.includes(dataId)) {
                        var selectedOptions = $(this).val();
                        var selectedOptionsCount = selectedOptions ? selectedOptions.length : 0;

                        if (selectedOptions.length == 0) {
                            attributeNames.push(attribute_name);
                        }
                    }

                });

                if (attributeNames.length > 0) {
                    var message = "Select at least one option from: ";

                    if (attributeNames.length === 1) {
                        message += attributeNames[0];
                    } else if (attributeNames.length === 2) {
                        message += attributeNames.join(' and ');
                    } else {
                        var lastAttributeName = attributeNames.pop();
                        message += attributeNames.join(', ') + ', and ' + lastAttributeName;
                    }

                    $('#attr_combination_error').text(message);

                    return false;
                }
            });
        });
        $(document).on('submit', '#information_form', function(event) {
            event.preventDefault();

            // Get the form data
            var formData = $(this).serialize();

            // Send the AJAX request
            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: formData,
                success: function(response) {
                    // If the response contains a custom error message
                    if (response.custom_error) {
                        // Show the custom error message inside the modal
                        $('#customErrorMessage').html('<p>' + response.custom_error + '</p>').show();
                    } else {
                        // If successful, close the modal or take any other action as needed
                        window.location.href = window.location.pathname + '?' + 'activeTab=combination';
                    }
                },
                error: function(xhr, status, error) {
                    // Handle errors, if any
                    console.error(error);
                }
            });
        });
    </script>
  <script>
     $('#toggle-form-dimension-btn').click(function() {
        $('#dimension-form').toggle();
        var formVisible = $('#dimension-form').is(':visible');
    });
    document.addEventListener("DOMContentLoaded", function () {
        const form = document.getElementById('dimension-form');
    const tableBody = document.querySelector('#dimension_table tbody');
    const dimensionDataInput = document.getElementById('dimension_data');

    document.getElementById('dimension-array-submit').addEventListener('click', function (e) {
        e.preventDefault();

        const dimensionName = document.querySelector('#dimension_name').value;
        const dimensionValue = document.querySelector('#dimension_value').value;

        if (dimensionName && dimensionValue) {
            // Check if editing a row or adding a new one
            const editedRow = document.querySelector('.editing-row');

            if (editedRow) {
                // Editing existing row
                const cells = Array.from(editedRow.querySelectorAll('td'));
                cells[0].innerText = dimensionName;
                cells[1].innerText = dimensionValue;
                editedRow.classList.remove('editing-row');
            } else {
                // Adding a new row
                const row = tableBody.insertRow();
                const cell1 = row.insertCell(0);
                const cell2 = row.insertCell(1);
                const cell3 = row.insertCell(2);

                cell1.innerHTML = dimensionName;
                cell2.innerHTML = dimensionValue;
                cell3.innerHTML = '<a type="button" class="mx-1 text-theme-color"><i class="fa-solid fa-pen-to-square edit-row"></i></a>' +
                                  '<a type="button" class="text-theme-color border-0 bg-transparent px-0"><i class="fa-solid fa-trash-can delete-row"></i></a>';
            }

            // Reset form fields
            document.querySelector('#dimension_name').value = '';
            document.querySelector('#dimension_value').value = '';

            // Update the hidden input with JSON data
            updateDimensionData();
            $('#dimension-form').toggle();
        } else {
            alert('Please enter both dimension name and value.');
        }
    });

    tableBody.addEventListener('click', function (e) {
        if (e.target.classList.contains('delete-row')) {
            const row = e.target.closest('tr');
            deleteRow(row);
        } else if (e.target.classList.contains('edit-row')) {
            const row = e.target.closest('tr');
            editRow(row);
        }
    });

    function editRow(row) {
        // Mark the row as editing
        row.classList.add('editing-row');

        // Get the data from the clicked row
        const cells = Array.from(row.querySelectorAll('td'));
        const dimensionName = cells[0].innerText;
        const dimensionValue = cells[1].innerText;

        // Populate the form with the row data
        document.querySelector('#dimension_name').value = dimensionName;
        document.querySelector('#dimension_value').value = dimensionValue;
        $('#dimension-form').toggle();


    }
        function deleteRow(row) {
            row.remove();

            // Update the hidden input with JSON data
            updateDimensionData();
        }

        function updateDimensionData() {
            const rows = Array.from(tableBody.querySelectorAll('tr'));
            const dimensionArray = rows.map(row => {
                const cells = Array.from(row.querySelectorAll('td'));
                return {
                    name: cells[0].innerText,
                    value: cells[1].innerText
                };
            });
            dimensionDataInput.value = JSON.stringify(dimensionArray);
        }
    });
</script>

@endsection
