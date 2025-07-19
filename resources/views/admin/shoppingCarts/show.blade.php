@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.shoppingCart.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.shopping-carts.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            {{-- <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.shoppingCart.fields.id') }}
                        </th>
                        <td>
                            {{ $shoppingCart->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.shoppingCart.fields.user') }}
                        </th>
                        <td>
                            {{ $shoppingCart->user->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.shoppingCart.fields.delivery_address') }}
                        </th>
                        <td>
                            {{  $shoppingCart->orderShippingAddress->country ?? ''}}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.shoppingCart.fields.billing_address') }}
                        </th>
                        <td>
                            {{ $shoppingCart->orderBillingAddress->country ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.shoppingCart.fields.total') }}
                        </th>
                        <td>
                            {{ $shoppingCart->total }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.shoppingCart.fields.language') }}
                        </th>
                        <td>
                            {{ $shoppingCart->language }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.shoppingCart.fields.is_empty') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $shoppingCart->is_empty ? 'checked' : '' }}>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.shoppingCart.fields.is_confirm') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $shoppingCart->is_confirm ? 'checked' : '' }}>
                        </td>
                    </tr>
                </tbody>
            </table> --}}

            <table class=" table table-bordered table-striped table-hover datatable datatable-showCart">
                <thead>
                    <tr>
                        <th >
                           Date
                        </th>

                        <th>
                           Product
                        </th>
                        <th>
                            Price
                         </th>
                         <th>
                            Quantity
                         </th>
                         <th>
                            Total
                         </th>
                    </tr>
                    {{-- <tr>
                        <td>
                        </td>
                        <td>
                            <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
                        </td>
                        <td>
                            <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
                        </td>

                    </tr> --}}
                </thead>
                <tbody>

                       @foreach ( $shoppingCart->cartProducts as $cartProduct)
                       <tr>
                       <td>
                        {{$cartProduct->created_at->format('d/m/Y')}}
                       </td>
                       <td>
                      <h6>{{$cartProduct->product->name}}</h6>
                        @if(isset($cartProduct->product->attributeCombinations))
                        @foreach ($cartProduct->product->attributeCombinations as $combination)
                            @if($combination->id == $cartProduct->product_attribute_id)
                                {{$combination->all_combination}}
                            @endif
                        @endforeach
                         @endif
                       </td>
                       <td>

                       {{$cartProduct->productAttribute ? $cartProduct->productAttribute->price : 0}}
                       </td>
                       <td>
                        {{$cartProduct->quantity}}
                       </td>
                       <td>

                        {{($cartProduct->productAttribute ? $cartProduct->productAttribute->price : 0)*($cartProduct->quantity)}}
                       </td>
                    </tr>
                       @endforeach

                        <tr>
                            <td  class="text-right" colspan="4">
                                {{ trans('cruds.shoppingCart.fields.total') }}
                            </td>
                            <td>
                                $ {{ $shoppingCart->total }}
                            </td>


                        </tr>

                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.shopping-carts.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection
<script>

$(function () {
$.extend(true, $.fn.dataTable.defaults, {
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 100,
  });
  let table = $('.datatable-showCart:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });

let visibleColumnsIndexes = null;
$('.datatable thead').on('input', '.search', function () {
      let strict = $(this).attr('strict') || false
      let value = strict && this.value ? "^" + this.value + "$" : this.value

      let index = $(this).parent().index()
      if (visibleColumnsIndexes !== null) {
        index = visibleColumnsIndexes[index]
      }

      table
        .column(index)
        .search(value, strict)
        .draw()
  });
table.on('column-visibility.dt', function(e, settings, column, state) {
      visibleColumnsIndexes = []
      table.columns(":visible").every(function(colIdx) {
          visibleColumnsIndexes.push(colIdx);
      });
  })
})


</script>
