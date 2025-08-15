@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.review.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.reviews.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.review.fields.id') }}
                        </th>
                        <td>
                            {{ $review->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.review.fields.order') }}
                        </th>
                        <td>
                            {{ $review->order->tracking_number ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.review.fields.user') }}
                        </th>
                        <td>
                            {{ $review->user->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.review.fields.product') }}
                        </th>
                        <td>
                            {{ $review->product->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.review.fields.product_attributes') }}
                        </th>
                        <td>
                            {{ $review->product_attributes->reference_code ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.review.fields.title') }}
                        </th>
                        <td>
                            {{ $review->title }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.review.fields.comment') }}
                        </th>
                        <td>
                            {{ $review->comment }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.review.fields.rating') }}
                        </th>
                        <td>
                            {{ $review->rating }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.review.fields.is_active') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $review->is_active ? 'checked' : '' }}>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.review.fields.customer_name') }}
                        </th>
                        <td>
                            {{ $review->customer_name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.review.fields.photos') }}
                        </th>
                        <td>
                            @foreach($review->photos as $key => $media)
                                <a href="{{ $media->getUrl() }}" target="_blank" style="display: inline-block">
                                    <img src="{{ $media->getUrl('thumb') }}">
                                </a>
                            @endforeach
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.reviews.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection