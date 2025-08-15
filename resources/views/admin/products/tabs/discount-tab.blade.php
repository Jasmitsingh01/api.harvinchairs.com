<div class="tab-pane fade" id="v-pills-discount" role="tabpanel" aria-labelledby="v-pills-discount-tab">
    <h3>Discount</h3>
    <a href="javascript:void(0);" id="toggle-form-btn" class="btn btn-primary discount-toggle-btn mb-3">Add Discount</a>
    <div id="discount-form" style="display: none;">
        <form method="POST" id="discount_form" action="{{ route('admin.specific-prices.store') }}"
            enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <label for="customer">Customer:</label>

                <select class="form-control" id="customer" name="customer" required>
                    <option value="0">All Customers</option>
                    @foreach ($customers as $customer)
                        <option value="{{ $customer->id }}">
                            {{ $customer->first_name }}({{ $customer->email }})</option>
                    @endforeach
                </select>

            </div>
            <div class="form-group">
                <label for="combination">Combination:</label>
                <select class="form-control" id="combination" name="combination" required>
                    <option value="0">All Combinations</option>
                    @foreach ($product->product_combinations as $combination)
                        <option value="{{ $combination->id }}">
                            {{ $combination->all_combination }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="from">From:</label>
                <input type="date" class="form-control" id="from" name="from">
            </div>
            <div class="form-group">
                <label for="to">To:</label>
                <input type="date" class="form-control" id="to" name="to">
            </div>
            <div class="form-group">
                <label for="starting_unit">Starting Unit:</label>
                <input type="number" class="form-control" id="starting_unit" name="starting_unit" required>
            </div>
            <div class="form-group">
                <label for="discount_amount">Discount Amount:</label>
                <input type="number" class="form-control" id="discount_amount" name="discount_amount" step="any" required>
            </div>
            <div class="form-group">
                <label for="discount_type">Discount Type:</label>
                <select class="form-control" id="discount_type" name="discount_type" required>
                    <option value="percentage">Percentage</option>
                    <option value="dollar">Dollar</option>
                </select>
            </div>
            <input type="hidden" name="tabname" value="discount">
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
                    <th>Discount</th>
                    <th>Discount Type</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @if ($product->specificPrices->isNotEmpty())
                    @foreach ($product->specificPrices as $specific_price)
                        <tr>
                            <td>{{ $specific_price->customer ? $specific_price->customer->first_name : 'All Customers' }}
                            </td>
                            <td>{{ $specific_price->product_attribute ? $specific_price->product_attribute->all_combination : 'All Combinations' }}
                            </td>
                            <td>{{ $specific_price->from }}</td>
                            <td>{{ $specific_price->to }}</td>
                            <td>{{ $specific_price->from_quantity }}</td>
                            <td>{{ $specific_price->reduction }}</td>
                            <td>{{ $specific_price->reduction_type == 'percentage' ? '%' : 'Flat Discount' }}
                            </td>
                            <td>
                                <div class="text-nowrap text-theme-color">
                                    <a class="mx-1 text-theme-color"
                                        href="{{ route('admin.specific-prices.edit', $specific_price->id) }}"><i
                                            class="fa-solid fa-pen-to-square"></i></a>

                                    <form action="{{ route('admin.specific-prices.destroy', $specific_price->id) }}"
                                        method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');"
                                        style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="hidden" name="tabname" value="discount">
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <button type="submit" class="text-theme-color border-0 bg-transparent px-0"
                                            value=""><i class="fa-solid fa-trash-can"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr id="Body_trNoData">
                        <td class="text-center" colspan="13"><i class="bi bi-exclamation-triangle"></i>&nbsp;No
                            specific prices.
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
    <a href="{{ route('admin.products.index') }}"class="btn btn-primary btn-lg">Save</a>
    <button type="button" id="" class="btn btn-primary next-tab btn-lg">Next</button>
    <a href="{{ route('admin.products.index') }}" class="btn btn-primary btn-lg">Cancel</a>
</div>
