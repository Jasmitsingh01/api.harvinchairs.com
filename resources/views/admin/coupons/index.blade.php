@extends('layouts.admin')
@section('content')
<div style="margin-bottom: 10px;" class="row">
    <div class="col-lg-12">
        <a class="btn btn-success" href="{{ route('admin.coupons.create') }}">
            {{ trans('global.add') }} {{ trans('cruds.coupon.title_singular') }}
        </a>
    </div>
</div>
<div class="card">
    <div class="card-header">
        {{ trans('cruds.coupon.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-Coupon">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th width="10">
                            {{ trans('cruds.coupon.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.coupon.fields.title') }}
                        </th>
                        <th width="10">
                            {{ trans('cruds.coupon.fields.code') }}
                        </th>
                        {{-- <th>
                            {{ trans('cruds.coupon.fields.customer') }}
                        </th>
                        <th>
                            {{ trans('cruds.coupon.fields.language') }}
                        </th>
                        <th>
                            {{ trans('cruds.coupon.fields.description') }}
                        </th> --}}
                        {{-- <th>
                            {{ trans('cruds.coupon.fields.image') }}
                        </th> --}}
                        {{-- <th>
                            {{ trans('cruds.coupon.fields.max_redemption_per_user') }}
                        </th>
                        <th>
                            {{ trans('cruds.coupon.fields.discount') }}
                        </th>
                        <th>
                            {{ trans('cruds.coupon.fields.discount_type') }}
                        </th>
                        <th>
                            {{ trans('cruds.coupon.fields.active_from') }}
                        </th> --}}
                        <th>
                            {{ trans('cruds.coupon.fields.max_usage') }}
                        </th>
                        <th>
                            {{ trans('cruds.coupon.fields.expire_at') }}
                        </th>
                        {{-- <th>
                            {{ trans('cruds.coupon.fields.min_amount') }}
                        </th>
                        <th>
                            {{ trans('cruds.coupon.fields.min_qty') }}
                        </th> --}}

                        {{-- <th>
                            {{ trans('cruds.coupon.fields.usage_count_per_user') }}
                        </th>
                        <th>
                            {{ trans('cruds.coupon.fields.country') }}
                        </th>
                        <th>
                            {{ trans('cruds.coupon.fields.free_shipping') }}
                        </th> --}}
                        <th>
                            {{ trans('cruds.coupon.fields.category') }}
                        </th>
                        <th>
                            {{ trans('cruds.coupon.fields.product') }}
                        </th>
                        {{-- <th>
                            {{ trans('cruds.coupon.fields.is_used') }}
                        </th> --}}
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                    <tr>
                        <td>
                        </td>
                        <td>
                            <input class="search input-id" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
                            <input class="search input-category" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
                            <input class="search input-category" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        {{-- <td>
                            <select class="search">
                                <option value>{{ trans('global.all') }}</option>
                                @foreach($users as $key => $item)
                                    <option value="{{ $item->name }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </td> --}}
                        <td>
                            <input class="search input-id" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
                            <input class="search input-category" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        {{-- <td>
                        </td> --}}
                        {{-- <td>
                            <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
                            <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
                            <select class="search" strict="true">
                                <option value>{{ trans('global.all') }}</option>
                                @foreach(App\Models\Coupon::DISCOUNT_TYPE_SELECT as $key => $item)
                                    <option value="{{ $item }}">{{ $item }}</option>
                                @endforeach
                            </select>
                        </td> --}}
                        {{-- <td>
                            <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                        </td> --}}
                        {{-- <td>
                            <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                        </td> --}}
                        {{-- <td>
                            <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
                            <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
                            <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
                            <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
                            <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
                        </td> --}}
                        <td>
                            <select class="search input-category">
                                <option value>{{ trans('global.all') }}</option>
                                @foreach($categories as $key => $item)
                                    <option value="{{ $item->name }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <select class="search input-category">
                                <option value>{{ trans('global.all') }}</option>
                                @foreach($products as $key => $item)
                                    <option value="{{ $item->name }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </td>
                        {{-- <td>
                        </td> --}}
                        <td>
                        </td>
                    </tr>
                </thead>
                <tbody>
                    @foreach($coupons as $key => $coupon)
                        <tr data-entry-id="{{ $coupon->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $coupon->id ?? '' }}
                            </td>
                            <td>
                                {{ $coupon->coupon_title ?? '' }}
                            </td>
                            <td>
                                {{ $coupon->code ?? '' }}
                            </td>
                            {{-- <td>
                                {{ $coupon->customer->name ?? '' }}
                            </td>
                            <td>
                                {{ $coupon->language ?? '' }}
                            </td>
                            <td>
                                {{ $coupon->description ?? '' }}
                            </td> --}}
                            {{-- <td>
                                @foreach($coupon->image as $key => $media)
                                    <a href="{{ $media->getUrl() }}" target="_blank" style="display: inline-block">
                                        <img src="{{ $media->getUrl('thumb') }}">
                                    </a>
                                @endforeach
                            </td> --}}
                            {{-- <td>
                                {{ $coupon->max_redemption_per_user ?? '' }}
                            </td>
                            <td>
                                {{ $coupon->discount ?? '' }}
                            </td>
                            <td>
                                {{ App\Models\Coupon::DISCOUNT_TYPE_SELECT[$coupon->discount_type] ?? '' }}
                            </td> --}}
                            {{-- <td>
                                {{ $coupon->active_from ?? '' }}
                            </td> --}}
                            <td>
                                {{ $coupon->max_usage ?? '' }}
                            </td>
                            <td>
                                {{ $coupon->expire_at ?? '' }}
                            </td>
                            {{-- <td>
                                {{ $coupon->min_amount ?? '' }}
                            </td>
                            <td>
                                {{ $coupon->min_qty ?? '' }}
                            </td> --}}

                           {{-- <td>
                                {{ $coupon->usage_count_per_user ?? '' }}
                            </td>
                            <td>
                                {{ $coupon->country ?? '' }}
                            </td>
                            <td>
                                <span style="display:none">{{ $coupon->free_shipping ?? '' }}</span>
                                <input type="checkbox" disabled="disabled" {{ $coupon->free_shipping ? 'checked' : '' }}>
                            </td> --}}
                            <td>
                                {{ $coupon->category->name ?? '' }}
                            </td>
                            <td>
                                {{ $coupon->product->name ?? '' }}
                            </td>
                            {{-- <td>
                                <span style="display:none">{{ $coupon->is_used ?? '' }}</span>
                                <input type="checkbox" disabled="disabled" {{ $coupon->is_used ? 'checked' : '' }}>
                            </td> --}}
                            <td>
                                <div class="text-nowrap text-theme-color">
                                    <a class="text-theme-color" href="{{ route('admin.coupons.show', $coupon->id) }}">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>

                                    <a class="text-theme-color" href="{{ route('admin.coupons.edit', $coupon->id) }}">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>

                                    <form action="{{ route('admin.coupons.destroy', $coupon->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <button type="submit" class="text-theme-color border-0 bg-transparent px-0" ><i class="fa-solid fa-trash-can"></i> </button>
                                    </form>
                                </div>

                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>



@endsection
@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.coupons.massDestroy') }}",
    className: 'btn-danger',
    action: function (e, dt, node, config) {
      var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
          return $(entry).data('entry-id')
      });

      if (ids.length === 0) {
        alert('{{ trans('global.datatables.zero_selected') }}')

        return
      }

      if (confirm('{{ trans('global.areYouSure') }}')) {
        $.ajax({
          headers: {'x-csrf-token': _token},
          method: 'POST',
          url: config.url,
          data: { ids: ids, _method: 'DELETE' }})
          .done(function () { location.reload() })
      }
    }
  }
  dtButtons.push(deleteButton)


  $.extend(true, $.fn.dataTable.defaults, {
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 100,
  });
  let table = $('.datatable-Coupon:not(.ajaxTable)').DataTable({ buttons: dtButtons })
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
@endsection
