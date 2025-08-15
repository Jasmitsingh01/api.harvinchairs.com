@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.tag.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.tags.update", [$tag->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label for="name">{{ trans('cruds.tag.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', $tag->name) }}">
                @if($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.tag.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="details">{{ trans('cruds.tag.fields.details') }}</label>
                <textarea class="form-control {{ $errors->has('details') ? 'is-invalid' : '' }}" name="details" id="details">{{ old('details', $tag->details) }}</textarea>
                @if($errors->has('details'))
                    <div class="invalid-feedback">
                        {{ $errors->first('details') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.tag.fields.details_helper') }}</span>
            </div>
            {{-- <div class="form-group">
                <label for="slug">{{ trans('cruds.tag.fields.slug') }}</label>
                <input class="form-control {{ $errors->has('slug') ? 'is-invalid' : '' }}" type="text" name="slug" id="slug" value="{{ old('slug', $tag->slug) }}">
                @if($errors->has('slug'))
                    <div class="invalid-feedback">
                        {{ $errors->first('slug') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.tag.fields.slug_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="language">{{ trans('cruds.tag.fields.language') }}</label>
                <input class="form-control {{ $errors->has('language') ? 'is-invalid' : '' }}" type="text" name="language" id="language" value="{{ old('language', $tag->language) }}">
                @if($errors->has('language'))
                    <div class="invalid-feedback">
                        {{ $errors->first('language') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.tag.fields.language_helper') }}</span>
            </div> --}}
            <div class="form-group" style="display: flex; justify-content: space-evenly;">
                <div class="col-lg-4">
                    <label for="details">{{ trans('cruds.product.title') }}</label>
                    <div class="login-input-area">
                        <select size="4"  multiple="multiple" id="productSelect" style="height:240px;width:345px;">
                            @foreach ($productsList as $key=>$value )
                            <option value="{{$value}}">{{$key}} </option>
                            @endforeach
                        </select>
                        <button   type="button" id="addProduct" class="btn btn-default btn-block " style="height:30px;width:345px;"> {{ trans('global.add') }} <i class="fa fa-arrow-right"></i></button>
                    </div>
                </div>

                <div class="col-lg-4">
                    <label for="details">{{ trans('cruds.product.title') }}</label>
                    <div class="login-input-area">
                        <div id="category_product">
                        @foreach ($productWithTag->products as $product )
                        <input type="hidden"  name="category_product[]"  id="category_product_id" value="{{$product->id}}">
                        @endforeach
                       </div>
                        <select size="4"  multiple="multiple" id="selectedProducts" style="height:240px;width:345px;">
                            @foreach ($productWithTag->products as $product )
                            <option value="{{$product->id}}">{{$product->name}} </option>

                            @endforeach
                        </select>
                        <button type="button" id="removeProduct" class="btn btn-default btn-block "  style="height:30px;width:345px;"><i class="fa fa-arrow-left" ></i> {{ trans('global.remove') }} </button>

                    </div>
                </div>
                </div>

            <div class="form-group">
                <button class="btn btn-primary btn-lg" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>



@endsection

@section('scripts')
@parent
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
 $(document).ready(function () {
  function addProductToSelected(product) {
    var value = product.val();
    if (value != null) {
      // Create hidden input for form submission
      $('#selectedProducts').append($('<input>', {
        type: 'hidden',
        name: 'category_product[]',
        value: value
      }));  // Append the option to the selected products list
      $('#selectedProducts').append($('<option>', {
        value: value,
        text: product.text()
      }));
      product.remove();
    } else {
      alert('Please select a product to add');
    }
  }

  function removeProductFromSelected(productValues) {
    var productSelect = $('#productSelect');

    productValues.forEach(function (productValue) {
      var product = $('#selectedProducts option[value="' + productValue + '"]');
      $('#selectedProducts option[value="' + productValue + '"]').remove();
      $('#selectedProducts input[value="' + productValue + '"]').remove();
      $('#category_product input[value="' + productValue + '"]').remove();

      // Append the removed product option back to the available products
      productSelect.append(product);
    });
  }

  $('#addProduct').on('click', function () {
    var selectedProducts = $('#productSelect option:selected');

    selectedProducts.each(function () {
      addProductToSelected($(this));
    });
  });

  $('#removeProduct').on('click', function () {
    var selectedProducts = $('#selectedProducts option:selected');
    var productValues = [];

    selectedProducts.each(function () {
      productValues.push($(this).val());
    });

    removeProductFromSelected(productValues);
  });
});



</script>
@endsection
