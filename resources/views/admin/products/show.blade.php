@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.product.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.products.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.product.fields.id') }}
                        </th>
                        <td>
                            {{ $product->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.product.fields.name') }}
                        </th>
                        <td>
                            {{ $product->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.product.fields.slug') }}
                        </th>
                        <td>
                            {{ $product->slug }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.product.fields.description') }}
                        </th>
                        <td>
                            {{ $product->description }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.product.fields.type') }}
                        </th>
                        <td>
                            {{ $product->type }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.product.fields.price') }}
                        </th>
                        <td>
                            {{ $product->price }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.product.fields.shop') }}
                        </th>
                        <td>
                            {{ $product->shop->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.product.fields.sale_price') }}
                        </th>
                        <td>
                            {{ $product->sale_price }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.product.fields.language') }}
                        </th>
                        <td>
                            {{ $product->language }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.product.fields.min_price') }}
                        </th>
                        <td>
                            {{ $product->min_price }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.product.fields.max_price') }}
                        </th>
                        <td>
                            {{ $product->max_price }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.product.fields.sku') }}
                        </th>
                        <td>
                            {{ $product->sku }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.product.fields.quantity') }}
                        </th>
                        <td>
                            {{ $product->quantity }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.product.fields.minimum_quantity') }}
                        </th>
                        <td>
                            {{ $product->minimum_quantity }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.product.fields.in_stock') }}
                        </th>
                        <td>
                            {{ $product->in_stock }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.product.fields.is_taxable') }}
                        </th>
                        <td>
                            {{ $product->is_taxable }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.product.fields.additional_shipping_fees') }}
                        </th>
                        <td>
                            {{ $product->additional_shipping_fees }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.product.fields.status') }}
                        </th>
                        <td>
                            {{ App\Models\Product::STATUS_RADIO[$product->status] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.product.fields.product_type') }}
                        </th>
                        <td>
                            {{ App\Models\Product::PRODUCT_TYPE_RADIO[$product->product_type] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.product.fields.unit') }}
                        </th>
                        <td>
                            {{ $product->unit }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.product.fields.height') }}
                        </th>
                        <td>
                            {{ $product->height }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.product.fields.width') }}
                        </th>
                        <td>
                            {{ $product->width }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.product.fields.length') }}
                        </th>
                        <td>
                            {{ $product->length }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.product.fields.depth') }}
                        </th>
                        <td>
                            {{ $product->depth }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.product.fields.weight') }}
                        </th>
                        <td>
                            {{ $product->weight }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.product.fields.image') }}
                        </th>
                        <td>
                            @if($product->image)
                                <a href="{{ $product->image->getUrl() }}" target="_blank" style="display: inline-block">
                                    <img src="{{ $product->image->getUrl('thumb') }}">
                                </a>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.product.fields.video') }}
                        </th>
                        <td>
                            @if($product->video)
                                <a href="{{ $product->video->getUrl() }}" target="_blank" style="display: inline-block">
                                    <img src="{{ $product->video->getUrl('thumb') }}">
                                </a>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.product.fields.gallery') }}
                        </th>
                        <td>
                            @foreach($product->gallery as $key => $media)
                                <a href="{{ $media->getUrl() }}" target="_blank" style="display: inline-block">
                                    <img src="{{ $media->getUrl('thumb') }}">
                                </a>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.product.fields.is_digital') }}
                        </th>
                        <td>
                            {{ $product->is_digital }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.product.fields.is_external') }}
                        </th>
                        <td>
                            {{ $product->is_external }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.product.fields.external_product_url') }}
                        </th>
                        <td>
                            {{ $product->external_product_url }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.product.fields.external_product_button_text') }}
                        </th>
                        <td>
                            {{ $product->external_product_button_text }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.product.fields.redirect_when_disabled') }}
                        </th>
                        <td>
                            {{ $product->redirect_when_disabled }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.product.fields.options') }}
                        </th>
                        <td>
                            {{ $product->options }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.product.fields.online_only') }}
                        </th>
                        <td>
                            {{ $product->online_only }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.product.fields.available_for_order') }}
                        </th>
                        <td>
                            {{ $product->available_for_order }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.product.fields.show_price') }}
                        </th>
                        <td>
                            {{ $product->show_price }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.product.fields.conditions') }}
                        </th>
                        <td>
                            {{ $product->conditions }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.product.fields.retail_price') }}
                        </th>
                        <td>
                            {{ $product->retail_price }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.product.fields.unit_price') }}
                        </th>
                        <td>
                            {{ $product->unit_price }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.product.fields.unity') }}
                        </th>
                        <td>
                            {{ $product->unity }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.product.fields.meta_title') }}
                        </th>
                        <td>
                            {{ $product->meta_title }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.product.fields.meta_description') }}
                        </th>
                        <td>
                            {{ $product->meta_description }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.product.fields.from_date') }}
                        </th>
                        <td>
                            {{ $product->from_date }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.product.fields.to_date') }}
                        </th>
                        <td>
                            {{ $product->to_date }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.product.fields.from_time') }}
                        </th>
                        <td>
                            {{ $product->from_time }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.product.fields.to_time') }}
                        </th>
                        <td>
                            {{ $product->to_time }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.product.fields.video_link') }}
                        </th>
                        <td>
                            {{ $product->video_link }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.product.fields.cover_image') }}
                        </th>
                        <td>
                            {{ $product->cover_image }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.product.fields.video_heading') }}
                        </th>
                        <td>
                            {{ $product->video_heading }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.product.fields.video_description') }}
                        </th>
                        <td>
                            {{ $product->video_description }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.product.fields.default_category') }}
                        </th>
                        <td>
                            {{ $product->default_category->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.product.fields.out_of_stock') }}
                        </th>
                        <td>
                            {{ $product->out_of_stock }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.product.fields.advanced_stock_management') }}
                        </th>
                        <td>
                            {{ $product->advanced_stock_management }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.product.fields.available_now') }}
                        </th>
                        <td>
                            {{ $product->available_now }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.product.fields.available_later') }}
                        </th>
                        <td>
                            {{ $product->available_later }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.product.fields.is_new') }}
                        </th>
                        <td>
                            {{ $product->is_new }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.product.fields.is_featured') }}
                        </th>
                        <td>
                            {{ $product->is_featured }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.product.fields.is_active') }}
                        </th>
                        <td>
                            {{ $product->is_active }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.products.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection