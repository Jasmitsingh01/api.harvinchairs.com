@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="card-header">
            {{ trans('global.edit') }} {{ trans('cruds.specificPrice.title_singular') }}
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('admin.specific-prices.update', [$specificPrice->id]) }}"
                enctype="multipart/form-data">
                @method('PUT')
                @csrf
                <div class="form-group">
                    <input type="hidden" name="product_id" value="{{ $specificPrice->product_id }}">
                    <label for="customer">Customer:</label>

                    <select class="form-control" id="customer" name="customer_id" required>
                        <option value="0">All Customers</option>
                        @foreach ($customers as $customer)
                            <option value="{{ $customer->id }}"
                                {{ $specificPrice->customer_id == $customer->id ? 'selected' : '' }}>
                                {{ $customer->first_name }}({{ $customer->email }})</option>
                        @endforeach
                    </select>

                </div>
                <div class="form-group">
                    <label for="combination">Combination:</label>
                    <select class="form-control" id="combination" name="product_attribute_id"
                        required>
                        <option value="0">All Combinations</option>

                        @foreach ($specificPrice->product->attributeCombinations as $combination)
                            <option value="{{ $combination->id }}"
                                {{ $specificPrice->product_attribute_id == $combination->id ? 'selected' : '' }}>
                                {{ $combination->all_combination }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="from">From:</label>
                    <input class="form-control datetime" type="text" name="from" id="from"
                        value="{{ $specificPrice->from }}">
                </div>
                <div class="form-group">
                    <label for="to">To:</label>
                    <input class="form-control datetime" type="text" name="to" id="to"
                        value="{{ $specificPrice->to }}">
                </div>
                <div class="form-group">
                    <label for="starting_unit">Starting Unit:</label>
                    <input type="number" class="form-control" id="starting_unit" name="from_quantity"
                        value="{{ $specificPrice->from_quantity }}" required>
                </div>
                <div class="form-group">
                    <label for="discount_amount">Discount Amount:</label>
                    <input type="number" class="form-control" id="discount_amount" name="reduction" step="any"
                        value="{{ $specificPrice->reduction }}" required>
                </div>
                <div class="form-group">
                    <label for="discount_type">Discount Type:</label>
                    <select class="form-control" id="discount_type" name="reduction_type" required>
                        <option value="percentage" {{ $specificPrice->reduction_type == 'percentage' ? 'selected' : '' }}>
                            Percentage</option>
                        <option value="dollar" {{ $specificPrice->reduction_type == 'dollar' ? 'selected' : '' }}>Dollar
                        </option>
                    </select>
                </div>
                <input type="hidden" name="tabname" value="discount">
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
@endsection
