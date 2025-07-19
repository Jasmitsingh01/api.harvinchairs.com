@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.specialOffer.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.special-offers.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.specialOffer.fields.id') }}
                        </th>
                        <td>
                            {{ $specialOffer->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.specialOffer.fields.product') }}
                        </th>
                        <td>
                            {{ $specialOffer->product->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.specialOffer.fields.offer_type') }}
                        </th>
                        <td>
                            {{ $specialOffer->offer_type }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.specialOffer.fields.discount_type') }}
                        </th>
                        <td>
                            {{ App\Models\SpecialOffer::DISCOUNT_TYPE_RADIO[$specialOffer->discount_type] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.specialOffer.fields.discount') }}
                        </th>
                        <td>
                            {{ $specialOffer->discount }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.specialOffer.fields.order_total_condition') }}
                        </th>
                        <td>
                            {{ $specialOffer->order_total_condition }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.special-offers.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection