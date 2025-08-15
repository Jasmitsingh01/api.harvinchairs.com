@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="card-header">
            {{ trans('global.create') }} {{ trans('cruds.product.title_singular') }}
        </div>
        <div class="card-body">

            <div class="row add-product-wrap">
                <div class="col-md-3">
                    <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                        <a class="nav-link active" id="v-pills-home-tab" data-bs-toggle="pill" href="#v-pills-information"
                            role="tab" aria-controls="v-pills-information" aria-selected="true">Information</a>
                        <a class="nav-link disabled" id="v-pills-association-tab" data-bs-toggle="pill"
                            href="#v-pills-association" role="tab" aria-controls="v-pills-association"
                            aria-selected="false">Category</a>
                        <a class="nav-link disabled" id="v-pills-dimension-tab" data-bs-toggle="pill"
                            href="#v-pills-dimension" role="tab" aria-controls="v-pills-dimension"
                            aria-selected="false">Dimensions</a>
                        <a class="nav-link disabled" id="v-pills-seo-tab" data-bs-toggle="pill" href="#v-pills-seo"
                            role="tab" aria-controls="v-pills-seo" aria-selected="false">SEO</a>
                        <a class="nav-link disabled" id="v-pills-gallery-tab" data-bs-toggle="pill" href="#v-pills-gallery"
                            role="tab" aria-controls="v-pills-gallery" aria-selected="false">Gallery</a>
                        <a class="nav-link disabled" id="v-pills-combination-tab" data-bs-toggle="pill"
                            href="#v-pills-combination" role="tab" aria-controls="v-pills-combination"
                            aria-selected="false">Combination</a>
                        <a class="nav-link disabled" id="v-pills-profile-tab" data-bs-toggle="pill" href="#v-pills-discount"
                            role="tab" aria-controls="v-pills-discount" aria-selected="false">Discount</a>
                        <a class="nav-link disabled" id="v-pills-hotdeals-tab" data-bs-toggle="pill" href="#v-pills-feature"
                            role="tab" aria-controls="v-pills-feature" aria-selected="false">Features</a>
                        <a class="nav-link disabled" id="v-pills-video-tab" data-bs-toggle="pill" href="#v-pills-video"
                            role="tab" aria-controls="v-pills-video" aria-selected="false">Videos</a>
                        {{-- <a class="nav-link disabled" id="v-pills-postcode-tab" data-bs-toggle="pill" href="#v-postcode-video"
                            role="tab" aria-controls="v-postcode-video" aria-selected="false">Postal Codes</a> --}}
                        <a class="nav-link disabled" id="v-pills-faq-tab" data-bs-toggle="pill" href="#v-pills-faq"
                            role="tab" aria-controls="v-pills-faq" aria-selected="false">FAQ</a>
                        <a class="nav-link disabled" id="v-pills-gst-tab" data-bs-toggle="pill" href="#v-pills-gst"
                            role="tab" aria-controls="v-pills-gst" aria-selected="false">GST</a>
                        {{-- <a class="nav-link disabled" id="v-pills-hotdeals-tab" data-bs-toggle="pill"
                            href="#v-pills-hotdeals" role="tab" aria-controls="v-pills-hotdeals"
                            aria-selected="false">Hot Deals</a> --}}
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="tab-content" id="v-pills-tabContent">
                        <div class="tab-pane fade show active" id="v-pills-information" role="tabpanel"
                            aria-labelledby="v-pills-information-tab">
                            <h3>Product Information</h3>
                            <form method="POST" id="information_form" action="{{ route('admin.products.store') }}"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label class="required" for="name">{{ trans('cruds.product.fields.name') }}</label>
                                    <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                                        type="text" name="name" id="name" value="{{ old('name', '') }}" required>
                                    @if ($errors->has('name'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('name') }}
                                        </div>
                                    @endif
                                    <span class="help-block">{{ trans('cruds.product.fields.name_helper') }}</span>
                                </div>
                                <div class="form-group">
                                    <label class="required"
                                        for="name">{{ trans('cruds.product.fields.reference_code') }}</label>
                                    <input class="form-control {{ $errors->has('reference_code') ? 'is-invalid' : '' }}"
                                        type="text" name="reference_code" id="reference_code"
                                        value="{{ old('reference_code', '') }}" required>
                                    @if ($errors->has('reference_code'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('reference_code') }}
                                        </div>
                                    @endif
                                    <span
                                        class="help-block">{{ trans('cruds.product.fields.reference_code_helper') }}</span>
                                </div>
                                <div class="form-group">
                                    <label class="required"
                                        for="name">{{ trans('cruds.product.fields.assembly_charges') }}</label>
                                    <input class="form-control {{ $errors->has('assembly_charges') ? 'is-invalid' : '' }}"
                                        type="number" name="assembly_charges" id="assembly_charges"
                                        value="{{ old('assembly_charges', 0) }}" step="0" min="0" required>
                                    @if ($errors->has('assembly_charges'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('assembly_charges') }}
                                        </div>
                                    @endif
                                    <span
                                        class="help-block">{{ trans('cruds.product.fields.assembly_charges_helper') }}</span>
                                </div>
                                <div class="form-group">
                                    {{-- <label class="required"
                                        for="unit_price">{{ trans('cruds.product.fields.price') }}</label>
                                        <input class="form-control {{ $errors->has('unit_price') ? 'is-invalid' : '' }}" type="text" name="unit_price" pattern="[0-9]*([.][0-9]+)?"
                                        inputmode="numeric" title="Please enter a valid number (digits only)">

                                </div> --}}


                                {{-- <div class="form-group">
                                    <label
                                        for="redirect_when_disabled">{{ trans('cruds.product.fields.redirect_when_disabled') }}</label>
                                    <select
                                        class="form-control select2 {{ $errors->has('redirect_when_disabled') ? 'is-invalid' : '' }}"
                                        name="redirect_when_disabled" id="redirect_when_disabled">
                                        <option value="404"
                                            {{ old('redirect_when_disabled') == '404' ? 'selected' : '' }}> No redirect
                                            [404]</option>
                                        <option value="301"
                                            {{ old('redirect_when_disabled') == '301' ? 'selected' : '' }}>Permanently
                                            display another product instead</option>
                                        <option value="302"
                                            {{ old('redirect_when_disabled') == '302' ? 'selected' : '' }}>Temporarily
                                            display another product instead</option>

                                    </select>
                                    @if ($errors->has('redirect_when_disabled'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('redirect_when_disabled') }}
                                        </div>
                                    @endif
                                    <span
                                        class="help-block">{{ trans('cruds.product.fields.redirect_when_disabled_helper') }}</span>
                                </div> --}}

                                <div class="form-group">
                                    <label>Options</label>

                                    <div class="form-check">
                                        <input class="form-check-input" checked type="radio" value="1"
                                            name="available_for_order" id="available_for_order_true">
                                        <label class="form-check-label" for="available_for_order_true">
                                            {{ trans('cruds.product.fields.available_for_order') }}
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" value="0"
                                            name="available_for_order" id="available_for_order_false">
                                        <label class="form-check-label" for="available_for_order_false">
                                            Available For Enquiry
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div
                                        class="custom-control custom-switch {{ $errors->has('is_active') ? 'is-invalid' : '' }}">
                                        <input type="checkbox" class="custom-control-input" id="is_active"
                                            name="is_active" value="1" {{ old('is_active') == 1 ? 'checked' : '' }}>
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
                                    <div
                                        class="custom-control custom-switch {{ $errors->has('creative_cuts') ? 'is-invalid' : '' }}">
                                        <input type="checkbox" class="custom-control-input" id="creative_cuts"
                                            name="creative_cuts" value="1" {{ old('creative_cuts') == 1 ? 'checked' : '' }}>
                                        <label class="custom-control-label"
                                            for="creative_cuts">Home Catalogue</label>
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
                                    <select
                                        class="form-control select2 {{ $errors->has('conditions') ? 'is-invalid' : '' }}"
                                        name="conditions" id="conditions">
                                        <option value="new" {{ old('conditions') == 'new' ? 'selected' : '' }}>New
                                        </option>
                                        <option value="used" {{ old('conditions') == 'used' ? 'selected' : '' }}>Used
                                        </option>
                                        <option value="refurbished"
                                            {{ old('conditions') == 'refurbished' ? 'selected' : '' }}>Refurbished</option>

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
                                        id="description">{{ old('description') }}</textarea>
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
                                        id="warranty_details">{{ old('warranty_details') }}</textarea>
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
                                        id="maintenance_details">{{ old('maintenance_details') }}</textarea>
                                    @if ($errors->has('maintenance_details'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('maintenance_details') }}
                                        </div>
                                    @endif
                                    <span class="help-block">{{ trans('cruds.product.fields.description_helper') }}</span>
                                </div>

                                <input type="hidden" name="tabname" value="information">
                                <button type="submit" name="product_submit" class="btn btn-primary"
                                    value="save">Save</button>
                                <button type="submit" name="product_submit" class="btn btn-primary"
                                    value="save_and_stay">Save & Stay</button>
                                <a href="{{ route('admin.products.index') }}" class="btn btn-primary">Cancel</a>

                            </form>
                        </div>
                        <div class="tab-pane fade" id="v-pills-discount" role="tabpanel"
                            aria-labelledby="v-pills-discount-tab">
                            <h3>Discount</h3>
                            <form method="POST" id="discount_form" action="{{ route('admin.products.store') }}"
                                enctype="multipart/form-data">
                                @csrf
                                <a href="javascript:void(0);" id="toggle-form-btn" class="btn btn-primary">Show
                                    Form</a>
                                <div id="discount-form" style="display: none;">
                                    <form>
                                        <div class="form-group">
                                            <label for="customer">Customer:</label>

                                            <select class="form-control" id="customer" name="customer" required>
                                                <option value="0">All Customers</option>
                                                @foreach ($customers as $customer)
                                                    <option value="{{ $customer->id }}">
                                                        {{ $customer->name }}({{ $customer->email }})</option>
                                                @endforeach
                                            </select>

                                        </div>
                                        <div class="form-group">
                                            <label for="combination">Combination:</label>
                                            <input type="text" class="form-control" id="combination"
                                                name="combination" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="from">From:</label>
                                            <input type="date" class="form-control" id="from" name="from"
                                                required>
                                        </div>
                                        <div class="form-group">
                                            <label for="to">To:</label>
                                            <input type="date" class="form-control" id="to" name="to"
                                                required>
                                        </div>
                                        <div class="form-group">
                                            <label for="starting_unit">Starting Unit:</label>
                                            <input type="number" class="form-control" id="starting_unit"
                                                name="starting_unit" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="discount_amount">Discount Amount:</label>
                                            <input type="number" class="form-control" id="discount_amount"
                                                name="discount_amount" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="discount_type">Discount Type:</label>
                                            <select class="form-control" id="discount_type" name="discount_type"
                                                required>
                                                <option value="flat_rate">Flat Rate</option>
                                                <option value="percent">Percent</option>
                                            </select>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </form>
                                </div>
                                <div class="discount-table">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Customer</th>
                                                <th>Combination</th>
                                                <th>From</th>
                                                <th>To</th>
                                                <th>Starting Unit</th>
                                                <th>Discount Amount</th>
                                                <th>Discount Type</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>John Doe</td>
                                                <td>ABC123</td>
                                                <td>2023-07-01</td>
                                                <td>2023-07-31</td>
                                                <td>100</td>
                                                <td>10</td>
                                                <td>Flat Rate</td>
                                                <td>
                                                    <div class="row" style="user-select: auto;">
                                                        <a class="btn btn-primary" href="javascript:void(0);">
                                                            <i class="fa fa-pencil-square-o fa-align-center"></i>
                                                        </a>
                                                        <a onclick="javascript:return confirm('Are you sure you want to delete ?');"
                                                            class="btn btn-small btn-danger" href="javascript:void();">
                                                            <i class="fa-solid fa-trash-can"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <button type="button" id="discount_form_submit" class="btn btn-primary"
                                    type="submit">Save</button>
                                <button type="button" id="discount_form_submit" class="btn btn-primary next-tab btn-lg"
                                    type="submit">Save & Next</button>
                            </form>
                        </div>
                        <div class="tab-pane fade" id="v-pills-seo" role="tabpanel" aria-labelledby="v-pills-seo-tab">
                            <h3>SEO</h3>
                            <form method="POST" id="seo_form" action="{{ route('admin.products.store') }}"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label for="meta_title">{{ trans('cruds.product.fields.meta_title') }}</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="inputGroup-sizing-default"><span
                                                    id="mtitle">70</span></span>
                                        </div>
                                        <input class="form-control {{ $errors->has('meta_title') ? 'is-invalid' : '' }}"
                                            type="text" name="meta_title" id="meta_title"
                                            value="{{ old('meta_title', '') }}">
                                        @if ($errors->has('meta_title'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('meta_title') }}
                                            </div>
                                        @endif
                                        <span
                                            class="help-block">{{ trans('cruds.product.fields.meta_title_helper') }}</span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label
                                        for="meta_description">{{ trans('cruds.product.fields.meta_description') }}</label>
                                    <div class="input-group mb-3">

                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="inputGroup-sizing-default"><span
                                                    id="mDes">70</span></span>
                                        </div>
                                        <textarea class="form-control {{ $errors->has('meta_description') ? 'is-invalid' : '' }}" name="meta_description"
                                            id="meta_description">{{ old('meta_description') }}</textarea>
                                        @if ($errors->has('meta_description'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('meta_description') }}
                                            </div>
                                        @endif
                                        <span
                                            class="help-block">{{ trans('cruds.product.fields.meta_description_helper') }}</span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="required"
                                        for="slug">{{ trans('cruds.product.fields.slug') }}</label>
                                    <input class="form-control {{ $errors->has('slug') ? 'is-invalid' : '' }}"
                                        type="text" name="slug" id="slug" value="{{ old('slug', '') }}"
                                        required>
                                    @if ($errors->has('slug'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('slug') }}
                                        </div>
                                    @endif
                                    <span class="help-block">{{ trans('cruds.product.fields.slug_helper') }}</span>
                                </div>

                                <div class="form-group">
                                    <label for="shop_id">{{ trans('cruds.product.fields.tags') }}</label>
                                    <select multiple
                                        class="form-control select2 {{ $errors->has('tags') ? 'is-invalid' : '' }}"
                                        name="tags[]" id="selected-tags">
                                        @foreach ($tags as $tag)
                                            <option value="{{ $tag->id }}"
                                                {{ old('tags') == $tag->id ? 'selected' : '' }}>{{ $tag->name }}
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
                                <button type="button" id="seo_form_submit" class="btn btn-primary"
                                    type="submit">Save</button>
                                <button type="button" id="seo_form_submit" class="btn btn-primary next-tab btn-lg"
                                    type="submit">Save & Next</button>

                            </form>
                        </div>

                        <div class="tab-pane fade" id="v-pills-association" role="tabpanel"
                            aria-labelledby="v-pills-association-tab">
                            <h3>Association</h3>
                            <form method="POST" id="association_form" action="{{ route('admin.products.store') }}"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label for="shop_id">{{ trans('cruds.product.fields.category') }}</label>
                                    <select multiple
                                        class="form-control select2 {{ $errors->has('shop') ? 'is-invalid' : '' }}"
                                        name="categories[]" id="selected-categories">
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}"
                                                {{ old('shop_id') == $category->id ? 'selected' : '' }}>
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
                                    </select>
                                    @if ($errors->has('default_category'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('default_category') }}
                                        </div>
                                    @endif
                                    <span
                                        class="help-block">{{ trans('cruds.product.fields.default_category_helper') }}</span>
                                </div>
                                <button type="button" id="association_form_submit"
                                    class="btn btn-primary next-tab btn-lg" type="submit">Save</button>
                                <button type="button" id="association_form_submit"
                                    class="btn btn-primary next-tab btn-lg" type="submit">Save & Next</button>
                            </form>
                        </div>

                        <div class="tab-pane fade" id="v-pills-combination" role="tabpanel"
                            aria-labelledby="v-pills-combination-tab">
                            <h3>Combination</h3>
                            <form method="POST" id="combination_form" action="{{ route('admin.products.store') }}"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="alert alert-info">
                                    You can also use the
                                    <a href="#" class="add-feature-link btn btn-link" data-toggle="modal"
                                        data-target="#combinationModal" id="combinationLink"
                                        style="user-select: auto;"><i class="icon-external-link-sign">Product Combinations
                                            Generator</i></a>
                                    in order to automatically create a set of combinations.
                                </div>

                                <div class="fixed-table-body">
                                    <table id="table" data-toggle="table" data-toolbar="#toolbar" class="table">
                                        <thead style="">
                                            <tr>
                                                <th class=" left" style="" data-field="0" tabindex="0">
                                                    <div class="th-inner sortable both">
                                                        <span class="title_box">Attribute - value pair
                                                        </span>
                                                    </div>
                                                    <div class="fht-cell"></div>
                                                </th>

                                                <th class=" left" style="" data-field="3" tabindex="0">
                                                    <div class="th-inner sortable both">
                                                        <span class="title_box">Reference
                                                        </span>
                                                    </div>
                                                    <div class="fht-cell"></div>
                                                </th>
                                                <th class=" left" style="" data-field="4" tabindex="0">
                                                    <div class="th-inner sortable both">
                                                        <span class="title_box">Min. Qty
                                                        </span>
                                                    </div>
                                                    <div class="fht-cell"></div>
                                                </th>
                                                <th style="" data-field="5" tabindex="0">
                                                    <div class="th-inner sortable both">&emsp;&emsp;&emsp;&emsp;&emsp;
                                                    </div>
                                                    <div class="fht-cell"></div>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr id="Body_repComb_trActive_0" class="activeshan" data-index="0">
                                                <td class=" left" style="">
                                                    Color - Pink, Cutting - Smooth, Drill - Side Drill, Shape - Pears
                                                    (Drops), Size - 5.50x3mm to
                                                    9.50x5mm, Stone Name - Pink Tourmaline


                                                </td>
                                                <td class=" left" style="">PTPSSB9HK1</td>
                                                <td class=" left" style=""><span style="margin-left:30%;">1</span>
                                                </td>
                                                <td class="text-right" style="">
                                                    <div class="row">
                                                        <a onclick="specific();" id="Body_repComb_lnkEdit_0"
                                                            class="btn btn-custon-rounded-three btn-primary"
                                                            href="javascript:__doPostBack('ctl00$Body$repComb$ctl00$lnkEdit','')"><i
                                                                class="fa fa-pencil-square-o fa-align-center"
                                                                style="color: white;"></i></a>
                                                        <a onclick="javascript:return confirm('Are you sure you want to delete ?');"
                                                            id="Body_repComb_LinkButton9_0"
                                                            class="btn btn-small btn-danger"
                                                            href="javascript:__doPostBack('ctl00$Body$repComb$ctl00$LinkButton9','')"
                                                            style="width: 25px; padding: 4px 3px;"><i
                                                                class="fa-solid fa-trash-can"
                                                                style="color: white;"></i></a>
                                                    </div>
                                                </td>
                                            </tr>

                                        </tbody>

                                    </table>
                                </div>

                                <button type="button" id="combination_form_submit" class="btn btn-primary"
                                    type="submit">Save</button>
                                <button type="button" id="combination_form_submit"
                                    class="btn btn-primary next-tab btn-lg" type="submit">Save & Next</button>
                            </form>
                        </div>

                        <div class="tab-pane fade" id="v-pills-gallery" role="tabpanel"
                            aria-labelledby="v-pills-gallery-tab">
                            <h3>Gallery</h3>
                            <form method="POST" id="gallery_form" action="{{ route('admin.products.store') }}"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label for="gallery">{{ trans('cruds.product.fields.gallery') }}</label>
                                    <div class="needsclick dropzone {{ $errors->has('gallery') ? 'is-invalid' : '' }}"
                                        id="gallery-dropzone">
                                    </div>
                                    @if ($errors->has('gallery'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('gallery') }}
                                        </div>
                                    @endif
                                    <span class="help-block">{{ trans('cruds.product.fields.gallery_helper') }}</span>
                                </div>
                                <div class="form-group">
                                    <label for="gallery">{{ trans('cruds.product.fields.caption') }}</label>
                                    <input class="form-control {{ $errors->has('caption') ? 'is-invalid' : '' }}"
                                        type="text" name="caption" id="caption" value="{{ old('caption', '') }}">
                                    @if ($errors->has('caption'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('caption') }}
                                        </div>
                                    @endif
                                    <span class="help-block">{{ trans('cruds.product.fields.caption_helper') }}</span>
                                </div>
                                <div class="datatable-dashv1-list custom-datatable-overright">
                                    <table class="table" id="image_table" style="display: none;">
                                        <thead>
                                            <tr>
                                                <th style="text-align: left; width: 15%;">Image</th>
                                                <th style="text-align: left; width: 15%;">Caption</th>
                                                <th style="text-align: left; width: 15%;">Position</th>
                                                <th style="text-align: left; width: 15%;">Actions</th>
                                                <!-- Add more table headers as needed -->
                                            </tr>
                                        </thead>
                                        <tbody id="image-table-body">
                                            <!-- Table body content will be dynamically added using JavaScript -->
                                        </tbody>
                                    </table>
                                    <a id="save_images" class="btn btn-prmiary" style="display: none;">save</a>
                                </div>
                                <button type="button" id="gallery_form_submit" class="btn btn-primary"
                                    type="submit">Save</button>
                                <button type="button" id="gallery_form_submit" class="btn btn-primary next-tab btn-lg"
                                    type="submit">Save & Next</button>
                            </form>
                        </div>
                        <div class="tab-pane fade" id="v-pills-feature" role="tabpanel"
                            aria-labelledby="v-pills-feature-tab">
                            <h3>Features</h3>
                            <form method="POST" id="feature_form" action="{{ route('admin.products.store') }}"
                                enctype="multipart/form-data">
                                @csrf
                                <table id="feature_table" class="table">
                                    <thead>
                                        <tr class="nodrag nodrop">
                                            <th class=" left">
                                                <span class="title_box">Feature
                                                </span>
                                            </th>
                                            <th class=" left">
                                                <span class="title_box">Pre-defined value
                                                </span>
                                            </th>
                                            <th class=" left">
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
                                                            <a href="#" class="add-feature-link btn btn-link"
                                                                data-toggle="modal" data-feature="{{ $feature->id }}"
                                                                data-target="#addFeatureValueModal"
                                                                id="addFeaturevalueLink"><i class="icon-plus-sign"></i>Add
                                                                pre-defined values first <i
                                                                    class="icon-external-link-sign"></i></a>
                                                        </span>
                                                    @else
                                                        <select name="value" id="value">
                                                            <option value=""
                                                                name="feature_value{{ $feature->id }}">Select</option>
                                                            @foreach ($feature->featureValues as $value)
                                                                <option value="{{ $value->id }}">{{ $value->value }}
                                                                </option>
                                                            @endforeach

                                                        </select>
                                                    @endif


                                                </td>
                                                <td>
                                                    <textarea name="feature_value_text{{ $feature->id }}" rows="2" cols="20"
                                                        id="feature_value_text{{ $feature->id }}" class="form-control"></textarea>
                                                </td>
                                            </tr>
                                        @endforeach


                                    </tbody>
                                </table>
                                <div class="form-group">
                                    {{-- <a href="#" data-toggle="modal" data-feature="{{ $feature->id }}"
                                        data-target="#addFeatureModal" id="add_feature_modal"
                                        class="btn btn-link bt-icon confirm_leave"><i class=" fa fa-plus"></i> Create new
                                        Feature <i class=" fa fa-external-link"></i></a> --}}
                                </div>
                                <button type="button" id="feature_form_submit" class="btn btn-primary"
                                    type="submit">Save</button>
                                <button type="button" id="feature_form_submit" class="btn btn-primary next-tab btn-lg"
                                    type="submit">Save & Next</button>
                            </form>
                        </div>
                        <div class="tab-pane fade" id="v-pills-video" role="tabpanel"
                            aria-labelledby="v-pills-video-tab">
                            <h3>Videos</h3>
                            <form method="POST" id="video_form" action="{{ route('admin.products.store') }}"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label for="video_link">{{ trans('cruds.product.fields.video_link') }}</label>
                                    <input class="form-control {{ $errors->has('video_link') ? 'is-invalid' : '' }}"
                                        type="text" name="video_link" id="video_link"
                                        value="{{ old('video_link', '') }}">
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
                                    <span
                                        class="help-block">{{ trans('cruds.product.fields.cover_image_helper') }}</span>
                                </div>

                                <div class="form-group">
                                    <label for="video_heading">{{ trans('cruds.product.fields.video_heading') }}</label>
                                    <input class="form-control {{ $errors->has('video_heading') ? 'is-invalid' : '' }}"
                                        type="text" name="video_heading" id="video_heading"
                                        value="{{ old('video_heading', '') }}">
                                    @if ($errors->has('video_heading'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('video_heading') }}
                                        </div>
                                    @endif
                                    <span
                                        class="help-block">{{ trans('cruds.product.fields.video_heading_helper') }}</span>
                                </div>

                                <div class="form-group">
                                    <label
                                        for="video_description">{{ trans('cruds.product.fields.video_description') }}</label>
                                    <textarea class="form-control ckeditor{{ $errors->has('video_description') ? 'is-invalid' : '' }}"
                                        name="video_description" id="video_description">{{ old('video_description') }}</textarea>
                                    @if ($errors->has('video_description'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('video_description') }}
                                        </div>
                                    @endif
                                    <span
                                        class="help-block">{{ trans('cruds.product.fields.video_description_helper') }}</span>
                                </div>
                                <button type="button" id="video_form_submit" class="btn btn-primary"
                                    type="submit">Save</button>
                                <button type="button" id="video_form_submit" class="btn btn-primary next-tab btn-lg"
                                    type="submit">Save & Next</button>
                            </form>
                        </div>

                        <div class="tab-pane fade" id="v-pills-hotdeals" role="tabpanel"
                            aria-labelledby="v-pills-hotdeals-tab">
                            <h3>Hot Deals</h3>
                            <form method="POST" id="hotdeals_form" action="{{ route('admin.products.store') }}"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label for="from_date">{{ trans('cruds.product.fields.from_date') }}</label>
                                    <input class="form-control date {{ $errors->has('from_date') ? 'is-invalid' : '' }}"
                                        type="text" name="from_date" id="from_date" value="{{ old('from_date') }}">
                                    @if ($errors->has('from_date'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('from_date') }}
                                        </div>
                                    @endif
                                    <span class="help-block">{{ trans('cruds.product.fields.from_date_helper') }}</span>
                                </div>
                                <div class="form-group">
                                    <label for="to_date">{{ trans('cruds.product.fields.to_date') }}</label>
                                    <input class="form-control date {{ $errors->has('to_date') ? 'is-invalid' : '' }}"
                                        type="text" name="to_date" id="to_date" value="{{ old('to_date') }}">
                                    @if ($errors->has('to_date'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('to_date') }}
                                        </div>
                                    @endif
                                    <span class="help-block">{{ trans('cruds.product.fields.to_date_helper') }}</span>
                                </div>
                                <div class="form-group">
                                    <label for="from_time">{{ trans('cruds.product.fields.from_time') }}</label>
                                    <input
                                        class="form-control timepicker {{ $errors->has('from_time') ? 'is-invalid' : '' }}"
                                        type="text" name="from_time" id="from_time" value="{{ old('from_time') }}">
                                    @if ($errors->has('from_time'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('from_time') }}
                                        </div>
                                    @endif
                                    <span class="help-block">{{ trans('cruds.product.fields.from_time_helper') }}</span>
                                </div>
                                <div class="form-group">
                                    <label for="to_time">{{ trans('cruds.product.fields.to_time') }}</label>
                                    <input
                                        class="form-control timepicker {{ $errors->has('to_time') ? 'is-invalid' : '' }}"
                                        type="text" name="to_time" id="to_time" value="{{ old('to_time') }}">
                                    @if ($errors->has('to_time'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('to_time') }}
                                        </div>
                                    @endif
                                    <span class="help-block">{{ trans('cruds.product.fields.to_time_helper') }}</span>
                                </div>
                                <div class="form-group">
                                    <button class="btn btn-danger" id="hotdeals_form_submit" type="submit">
                                        {{ trans('global.save') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- <div class="card-body">
        <form method="POST" action="{{ route("admin.products.store") }}" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label for="language">{{ trans('cruds.product.fields.language') }}</label>
                <input class="form-control {{ $errors->has('language') ? 'is-invalid' : '' }}" type="text" name="language" id="language" value="{{ old('language', '') }}">
                @if ($errors->has('language'))
                    <div class="invalid-feedback">
                        {{ $errors->first('language') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.product.fields.language_helper') }}</span>
            </div>
            <div class="form-group">
                <label>{{ trans('cruds.product.fields.status') }}</label>
                @foreach (App\Models\Product::STATUS_RADIO as $key => $label)
                    <div class="form-check {{ $errors->has('status') ? 'is-invalid' : '' }}">
                        <input class="form-check-input" type="radio" id="status_{{ $key }}" name="status" value="{{ $key }}" {{ old('status', '') === (string) $key ? 'checked' : '' }}>
                        <label class="form-check-label" for="status_{{ $key }}">{{ $label }}</label>
                    </div>
                @endforeach
                @if ($errors->has('status'))
                    <div class="invalid-feedback">
                        {{ $errors->first('status') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.product.fields.status_helper') }}</span>
            </div>

            <div class="form-group">
                <label class="required" for="unit">{{ trans('cruds.product.fields.unit') }}</label>
                <input class="form-control {{ $errors->has('unit') ? 'is-invalid' : '' }}" type="text" name="unit" id="unit" value="{{ old('unit', '') }}" required>
                @if ($errors->has('unit'))
                    <div class="invalid-feedback">
                        {{ $errors->first('unit') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.product.fields.unit_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="external_product_url">{{ trans('cruds.product.fields.external_product_url') }}</label>
                <input class="form-control {{ $errors->has('external_product_url') ? 'is-invalid' : '' }}" type="text" name="external_product_url" id="external_product_url" value="{{ old('external_product_url', '') }}">
                @if ($errors->has('external_product_url'))
                    <div class="invalid-feedback">
                        {{ $errors->first('external_product_url') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.product.fields.external_product_url_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="external_product_button_text">{{ trans('cruds.product.fields.external_product_button_text') }}</label>
                <input class="form-control {{ $errors->has('external_product_button_text') ? 'is-invalid' : '' }}" type="text" name="external_product_button_text" id="external_product_button_text" value="{{ old('external_product_button_text', '') }}">
                @if ($errors->has('external_product_button_text'))
                    <div class="invalid-feedback">
                        {{ $errors->first('external_product_button_text') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.product.fields.external_product_button_text_helper') }}</span>
            </div>

            <div class="form-group">
                <label for="options">{{ trans('cruds.product.fields.options') }}</label>
                <input class="form-control {{ $errors->has('options') ? 'is-invalid' : '' }}" type="text" name="options" id="options" value="{{ old('options', '') }}">
                @if ($errors->has('options'))
                    <div class="invalid-feedback">
                        {{ $errors->first('options') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.product.fields.options_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="out_of_stock">{{ trans('cruds.product.fields.out_of_stock') }}</label>
                <input class="form-control {{ $errors->has('out_of_stock') ? 'is-invalid' : '' }}" type="number" name="out_of_stock" id="out_of_stock" value="{{ old('out_of_stock', '') }}" step="1">
                @if ($errors->has('out_of_stock'))
                    <div class="invalid-feedback">
                        {{ $errors->first('out_of_stock') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.product.fields.out_of_stock_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="advanced_stock_management">{{ trans('cruds.product.fields.advanced_stock_management') }}</label>
                <input class="form-control {{ $errors->has('advanced_stock_management') ? 'is-invalid' : '' }}" type="number" name="advanced_stock_management" id="advanced_stock_management" value="{{ old('advanced_stock_management', '0') }}" step="1">
                @if ($errors->has('advanced_stock_management'))
                    <div class="invalid-feedback">
                        {{ $errors->first('advanced_stock_management') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.product.fields.advanced_stock_management_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="available_now">{{ trans('cruds.product.fields.available_now') }}</label>
                <input class="form-control {{ $errors->has('available_now') ? 'is-invalid' : '' }}" type="text" name="available_now" id="available_now" value="{{ old('available_now', '') }}">
                @if ($errors->has('available_now'))
                    <div class="invalid-feedback">
                        {{ $errors->first('available_now') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.product.fields.available_now_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="available_later">{{ trans('cruds.product.fields.available_later') }}</label>
                <input class="form-control {{ $errors->has('available_later') ? 'is-invalid' : '' }}" type="text" name="available_later" id="available_later" value="{{ old('available_later', '') }}">
                @if ($errors->has('available_later'))
                    <div class="invalid-feedback">
                        {{ $errors->first('available_later') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.product.fields.available_later_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="is_new">{{ trans('cruds.product.fields.is_new') }}</label>
                <input class="form-control {{ $errors->has('is_new') ? 'is-invalid' : '' }}" type="number" name="is_new" id="is_new" value="{{ old('is_new', '0') }}" step="1">
                @if ($errors->has('is_new'))
                    <div class="invalid-feedback">
                        {{ $errors->first('is_new') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.product.fields.is_new_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="is_featured">{{ trans('cruds.product.fields.is_featured') }}</label>
                <input class="form-control {{ $errors->has('is_featured') ? 'is-invalid' : '' }}" type="number" name="is_featured" id="is_featured" value="{{ old('is_featured', '0') }}" step="1">
                @if ($errors->has('is_featured'))
                    <div class="invalid-feedback">
                        {{ $errors->first('is_featured') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.product.fields.is_featured_helper') }}</span>
            </div>

            <div class="form-group">
                <button class="btn btn-primary btn-lg" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div> --}}
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
        <div class="modal-dialog" role="document">
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
                            <label for="modal_attribute_id">Attribute:</label>
                            <br />
                            @foreach ($attributes as $attribute)
                                @if ($attribute->values->isNotEmpty())
                                    <strong>{{ $attribute->name }}</strong>
                                    <select class="form-control" id="modal_attribute_id" data-id="{{ $attribute->id }}"
                                        style="height: 100px;" name="attribute-{{ $attribute->id }}[]" multiple>
                                        @foreach ($attribute->values as $value)
                                            <option class="" value="{{ $value->id }}">{{ $value->value }}
                                            </option>
                                        @endforeach
                                    </select>
                                @endif
                            @endforeach
                        </div>
                        <div class="form-group">
                            <label for="default_quantity">Default Quantity:</label>
                            <input type="number" class="form-control" id="default_quantity" name="default_quantity">
                        </div>
                        <div class="form-group">
                            <label for="default_reference">Default Reference:</label>
                            <input type="text" class="form-control" id="default_reference" name="default_reference">
                        </div>
                        <div class="form-group">
                            <input type="submit" class="btn btn-primary " name='save_combinations' value="Save">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {

            $('#toggle-form-btn').click(function() {
                $('#discount-form').toggle();
                var formVisible = $('#discount-form').is(':visible');
                if (formVisible) {
                    $(this).text('Hide Form');
                } else {
                    $(this).text('Show Form');
                }
            });

            $('#selected-categories').on('change', function() {
                var selectedCategories = $(this).val();
                var defaultCategoryDropdown = $('#default-category');

                defaultCategoryDropdown.empty();

                if (selectedCategories) {
                    $.each(selectedCategories, function(index, categoryId) {
                        var categoryText = $('#selected-categories option[value="' + categoryId +
                            '"]').text();
                        defaultCategoryDropdown.append('<option value="' + categoryId + '">' +
                            categoryText + '</option>');
                    });
                }
            });

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
                                            '{{ route('admin.categories.storeCKEditorImages') }}',
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
            // console.log(allEditors);
            for (var i = 0; i < allEditors.length; ++i) {
                ClassicEditor.create(
                    allEditors[i], {
                        extraPlugins: [SimpleUploadAdapter]
                    }
                );
            }

            $('#name').on('input', function() {
                var name = $(this).val();
                var slug = name.trim().toLowerCase().replace(/[^a-z0-9]+/g, '-');
                $('#slug').val(slug);
            });
        });
        if (document.body.contains(document.getElementById('meta_title'))) {
            $('#meta_title').keyup(function() {
                var max = 70;
                var len = $(this).val().length;
                var char = max - len;
                $('#mtitle').text(char);
                //if (len >= max) {
                //    $('#mtitle').text(' you have reached the limit');
                //} else {
                //    debugger
                //    var char = max - len;
                //    $('#mtitle').text(char + ' characters left');
                //}updateLinkRewrite
            });
            $('#meta_description').keyup(function() {
                var max = 160;
                var len = $(this).val().length;
                var char = max - len;
                $('#mDes').text(char);
            });
            $(document).ready(function() {
                $('#Body_Selectall').prop('checked', false);
                $('#Body_Unselectall').prop('checked', false);
                var max = 70;
                var len = $('#meta_title').val().length;
                var char = max - len;
                $('#mtitle').text(char);

                var max1 = 160;
                var len1 = $('#meta_description').val().length;
                var char1 = max1 - len1;
                $('#mDes').text(char1);
            });

            function DisableButton(id) {

                document.getElementbyId(id).disabled = true;
            }
        } else {
            //alert('Element does not exist!');
        }
    </script>
    <script>
        Dropzone.options.imageDropzone = {
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
                $('form').find('input[name="image"]').remove()
                $('form').append('<input type="hidden" name="image" value="' + response.name + '">')
                $(':input[type="submit"]').prop('disabled', false);
            },
            removedfile: function(file) {
                file.previewElement.remove()
                if (file.status !== 'error') {
                    $('form').find('input[name="image"]').remove()
                    this.options.maxFiles = this.options.maxFiles + 1
                }
                $(':input[type="submit"]').prop('disabled', false);
            },
            init: function() {
                @if (isset($product) && $product->image)
                    var file = {!! json_encode($product->image) !!}
                    this.options.addedfile.call(this, file)
                    this.options.thumbnail.call(this, file, file.preview ?? file.preview_url)
                    file.previewElement.classList.add('dz-complete')
                    $('form').append('<input type="hidden" name="image" value="' + file.file_name + '">')
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
                    this.options.thumbnail.call(this, file, file.preview ?? file.preview_url)
                    //   file.previewElement.classList.add('dz-complete')
                    $('form').append('<input type="hidden" name="cover_image" value="' + file.file_name + '">')
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
        var uploadedGalleryMap = {}
        var saveButton = document.getElementById('save_images');
        var table = document.getElementById('image_table');

        var caption = document.getElementById('caption').value;
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
            maxFilesize: 2, // MB
            acceptedFiles: '.jpeg,.jpg,.png,.gif',
            // addRemoveLinks: true,
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            params: {
                size: 2,
                width: 4096,
                height: 4096
            },
            // previewTemplate: '',
            success: function(file, response) {
                file.previewElement.remove();
                var newRow = '<tr>' +
                    '<td><img src="' + file.dataURL + '" alt="Image" width="50"></td>' +
                    '<td class="caption-td" id="caption[' + (imageTableBody.children.length + 1) +
                    ']"><input type="text" data-filename="' + response.name + '" name="caption[' + (imageTableBody
                        .children.length + 1) + ']" value="' + caption + '"></td>' +
                    '<td class="position">' + (imageTableBody.children.length + 1) + '</td>' +
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
                $('form').append('<input type="hidden" name="gallery[]" id="gallery" value="' + JSON.stringify(
                    data) + '">');
                //   uploadedGalleryMap[file.name] = response.name
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
                        this.options.addedfile.call(this, file)
                        this.options.thumbnail.call(this, file, file.preview ?? file.preview_url)
                        file.previewElement.classList.add('dz-complete')
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
        $(document).ready(function() {
            $('#save_images').click(function() {
                var rows = imageTableBody.querySelectorAll('tr');
                var data = [];
                for (var i = 0; i < rows.length; i++) {
                    // console.log(rows[i].querySelector('.position').textContent);
                    var caption = document.querySelector('input[name="caption[' + rows[i].querySelector(
                        '.position').textContent + ']"]');
                    if (caption) {
                        var filename = caption.getAttribute('data-filename');
                        data[i] = {
                            'filename': filename,
                            'position': rows[i].querySelector('.position').textContent,
                            'caption': caption.value
                        };
                    }
                    rows[i].querySelector('.position').textContent = i + 1;
                }
                $('input[name="gallery[]"]').val(JSON.stringify(data));
            });

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
                updatePositions();
                if (document.querySelectorAll('#image-table-body tr').length === 0) {
                    saveButton.style.display = 'none'; // Hide the save button
                }
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
                        ${newData.feature_values.length === 0 ? `
                                                                                                                                                                                                                                                                                                                                                                                        <span id="Body_repFeature_AddPre_defined_0">N/A -
                                                                                                                                                                                                                                                                                                                                                                                            <a href="#" class="add-feature-link btn btn-link" data-toggle="modal" data-feature="${newData.id}" data-target="#addFeatureValueModal">
                                                                                                                                                                                                                                                                                                                                                                                            <i class="icon-plus-sign"></i>Add pre-defined values first <i class="icon-external-link-sign"></i>
                                                                                                                                                                                                                                                                                                                                                                                            </a>
                                                                                                                                                                                                                                                                                                                                                                                        </span>
                                                                                                                                                                                                                                                                                                                                                                                        ` : `
                                                                                                                                                                                                                                                                                                                                                                                        <select name="value" id="value">
                                                                                                                                                                                                                                                                                                                                                                                            <option value="">Select</option>
                                                                                                                                                                                                                                                                                                                                                                                            ${newData.feature_values.map(value => `<option value="${value.id}">${value.value}</option>`).join('')}
                                                                                                                                                                                                                                                                                                                                                                                        </select>
                                                                                                                                                                                                                                                                                                                                                                                        `}
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
@endsection
