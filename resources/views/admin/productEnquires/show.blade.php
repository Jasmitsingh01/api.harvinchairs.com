@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.productEnquire.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.product-enquires.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.productEnquire.fields.id') }}
                        </th>
                        <td>
                            {{ $productEnquire->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.productEnquire.fields.customer_name') }}
                        </th>
                        <td>
                            {{ $productEnquire->customer_name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.productEnquire.fields.customer_email') }}
                        </th>
                        <td>
                            {{ $productEnquire->customer_email }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.productEnquire.fields.subject') }}
                        </th>
                        <td>
                            {{ $productEnquire->subject }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.productEnquire.fields.message') }}
                        </th>
                        <td>
                            {{ $productEnquire->message }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.productEnquire.fields.product_title') }}
                        </th>
                        <td>
                            {{ $productEnquire->product_title }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.productEnquire.fields.product_price') }}
                        </th>
                        <td>
                            {{ $productEnquire->product_price }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.productEnquire.fields.product_img') }}
                        </th>
                        <td>
                            @if($productEnquire->product_img)
                                <a href="{{ $productEnquire->product_img->getUrl() }}" target="_blank">
                                    {{ trans('global.view_file') }}
                                </a>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.productEnquire.fields.product') }}
                        </th>
                        <td>
                            {{ $productEnquire->product->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.productEnquire.fields.product_attributes') }}
                        </th>
                        <td>
                            {{ $productEnquire->product_attributes->reference_code ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.productEnquire.fields.url') }}
                        </th>
                        <td>
                            {{ $productEnquire->url }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.product-enquires.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection