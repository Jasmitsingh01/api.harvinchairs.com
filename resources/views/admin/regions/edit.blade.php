@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} Shipping Region
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.regions.update", [$region->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label for="region_name">Region</label>
                <input class="form-control {{ $errors->has('region_name') ? 'is-invalid' : '' }}"
                type="text" name="region_name" id="region_name" value="{{$region->region_name}}" required>
                @if($errors->has('region_name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('region_name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.zipcode.fields.country_helper') }}</span>
            </div>

            <div class="form-group">
                <label for="postcode">{{ trans('cruds.zipcode.fields.zip_code') }}</label>
                <select class="form-control select2 {{ $errors->has('postcode') ? 'is-invalid' : '' }}" name="postcode[]" id="postcode" multiple="multiple">
                    @foreach($postcodes as $code)
                        <option value="{{ $code }}" {{ in_array($code, $selected_postcodes) ? 'selected' : '' }}>{{ $code }}</option>
                    @endforeach
                </select>
                @if($errors->has('postcode'))
                    <div class="invalid-feedback">
                        {{ $errors->first('postcode') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.zipcode.fields.zip_code_helper') }}</span>
            </div>
            <div class="form-group">
                <button class="btn btn-primary btn-lg" type="submit">
                    {{ trans('global.save') }}
                </button>
                <button class="btn btn-primary btn-lg" type="button" onclick="window.history.back()">
                    {{ trans('global.cancel') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
@section('scripts')
<script>
    $('#region_name').on('change', function() {
        var state = $(this).val();

        // Make an AJAX request to fetch postal codes based on the selected country/region
        $.ajax({
            url: "{{ route('admin.get-postcodes') }}",
            type: 'POST',
            dataType: 'json',
            data: {
                state: state,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                // Populate the postal codes dropdown with fetched data
                $('#postcode').empty();
                $.each(response.postcodes, function(key, value) {
                    $('#postcode').append('<option value="' + value + '">' + value + '</option>');
                });
                $('#postcode').select2();
            }
        });
    });
    $(document).ready(function() {
        $('#postcode').select2({
            ajax: {
                url: "{{ route('admin.postcode-suggestions') }}",
                type: 'POST',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        postcode: params.term, // Search term entered by the user
                        _token: '{{ csrf_token() }}' // CSRF token
                    };
                },
                processResults: function(data) {
                    return {
                        results: $.map(data.suggestions, function(postcode) {
                            return {
                                id: postcode,
                                text: postcode
                            };
                        })
                    };
                },
                cache: true
            },
            minimumInputLength: 3, // Minimum characters before making AJAX call
            placeholder: 'Type to search postal codes...'
        });
    });
  </script>
@endsection
