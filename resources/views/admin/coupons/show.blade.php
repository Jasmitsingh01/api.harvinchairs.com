@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.coupon.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.coupons.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.coupon.fields.id') }}
                        </th>
                        <td>
                            {{ $coupon->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.coupon.fields.code') }}
                        </th>
                        <td>
                            {{ $coupon->code }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.coupon.fields.title') }}
                        </th>
                        <td>
                            {{ $coupon->coupon_title }}
                        </td>
                    </tr>
                    {{-- <tr>
                        <th>
                            {{ trans('cruds.coupon.fields.customer') }}
                        </th>
                        <td>
                            @foreach ($customer as $name)
                            {{$name ?? ''}} @if(!$loop->last) ,
                                                                 @endif
                            @endforeach
                            {{ $coupon->customer->name ?? '' }}
                        </td>
                    </tr> --}}
                    <tr>
                        <th>
                            {{ trans('cruds.coupon.fields.language') }}
                        </th>
                        <td>
                            {{ $coupon->language }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.coupon.fields.description') }}
                        </th>
                        <td>
                            {{ $coupon->description }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.coupon.fields.image') }}
                        </th>
                        <td>
                            @if (isset($coupon->image))
                            {{-- @foreach($coupon->image as $key => $media) --}}
                            <a href="{{ $coupon->image->getUrl() }}" target="_blank" style="display: inline-block">
                                <img src="{{ $coupon->image->getUrl('thumb') }}">
                            </a>
                             {{-- @endforeach --}}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.coupon.fields.max_redemption_per_user') }}
                        </th>
                        <td>
                            {{ $coupon->max_redemption_per_user }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.coupon.fields.discount') }}
                        </th>
                        <td>
                            {{ $coupon->discount }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.coupon.fields.discount_type') }}
                        </th>
                        <td>
                            {{ App\Models\Coupon::DISCOUNT_TYPE_SELECT[$coupon->discount_type] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.coupon.fields.active_from') }}
                        </th>
                        <td>
                            {{ $coupon->active_from }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.coupon.fields.expire_at') }}
                        </th>
                        <td>
                            {{ $coupon->expire_at }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.coupon.fields.min_amount') }}
                        </th>
                        <td>
                            {{ $coupon->min_amount }}
                        </td>
                    </tr>
                    {{-- <tr>
                        <th>
                            {{ trans('cruds.coupon.fields.min_qty') }}
                        </th>
                        <td>
                            {{ $coupon->min_qty }}
                        </td>
                    </tr> --}}
                    <tr>
                        <th>
                            {{ trans('cruds.coupon.fields.max_usage') }}
                        </th>
                        <td>
                            {{ $coupon->max_usage }}
                        </td>
                    </tr>
                    {{-- <tr>
                        <th>
                            {{ trans('cruds.coupon.fields.usage_count_per_user') }}
                        </th>
                        <td>
                            {{ $coupon->usage_count_per_user }}
                        </td>
                    </tr> --}}
                    {{-- <tr>
                        <th>
                            {{ trans('cruds.coupon.fields.country') }}
                        </th>
                        <td>
                            {{ $coupon->country }}
                        </td>
                    </tr> --}}
                    {{-- <tr>
                        <th>
                            {{ trans('cruds.coupon.fields.free_shipping') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $coupon->free_shipping ? 'checked' : '' }}>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.coupon.fields.free_shipping_min_amount') }}
                        </th>
                        <td>
                            {{ $coupon->free_shipping ? $coupon->free_shipping_min_amount : '' }}
                        </td>
                    </tr> --}}
                    {{-- <tr>
                        <th>
                            {{ trans('cruds.coupon.fields.category') }}
                        </th>
                        <td>
                            {{ $coupon->category->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.coupon.fields.product') }}
                        </th>
                        <td>
                            {{ $coupon->product->name ?? '' }}
                        </td>
                    </tr> --}}
                    {{-- <tr>
                        <th>
                            {{ trans('cruds.coupon.fields.is_used') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $coupon->is_used ? 'checked' : '' }}>
                        </td>
                    </tr> --}}
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.coupons.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection
