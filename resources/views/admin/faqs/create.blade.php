@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.faq.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.faqs.store") }}" enctype="multipart/form-data">
            @csrf
            {{-- <div class="form-group">
                <label for="product_id">{{ trans('cruds.faq.fields.product') }}</label>
                <select class="form-control select2 {{ $errors->has('product') ? 'is-invalid' : '' }}" name="product_id" id="product_id">
                    @foreach($products as $id => $entry)
                        <option value="{{ $id }}" {{ old('product_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('product'))
                    <div class="invalid-feedback">
                        {{ $errors->first('product') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.faq.fields.product_helper') }}</span>
            </div> --}}

            <div class="form-group">
                <label for="faq_type_id">{{ trans('cruds.faq.fields.faq_type') }}</label>
                <select class="form-control select2 {{ $errors->has('faq_type_id') ? 'is-invalid' : '' }}" name="faq_type_id" id="faq_type_id" required>
                    <option value="">Please Select</option>
                    @foreach($faqtypes as $id => $entry)
                        <option value="{{ $entry->id }}" {{ old('faq_type_id') == $entry->id ? 'selected' : '' }}>{{ $entry->name }}</option>
                    @endforeach
                </select>
                @if($errors->has('faq_type_id'))
                    <div class="invalid-feedback">
                        {{ $errors->first('faq_type_id') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.faq.fields.product_helper') }}</span>
            </div>

            <div class="form-group">
                <label for="question">{{ trans('cruds.faq.fields.question') }}</label>
                <textarea class="form-control {{ $errors->has('question') ? 'is-invalid' : '' }}" name="question" id="question" required>{{ old('question') }}</textarea>
                @if($errors->has('question'))
                    <div class="invalid-feedback">
                        {{ $errors->first('question') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.faq.fields.question_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="answer">{{ trans('cruds.faq.fields.answer') }}</label>
                <textarea class="form-control {{ $errors->has('answer') ? 'is-invalid' : '' }}" name="answer" id="answer" required>{{ old('answer') }}</textarea>
                @if($errors->has('answer'))
                    <div class="invalid-feedback">
                        {{ $errors->first('answer') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.faq.fields.answer_helper') }}</span>
            </div>
            <div class="form-group">
                <div class="form-check {{ $errors->has('status') ? 'is-invalid' : '' }}">
                    <input type="hidden" name="status" value="0">
                    <input class="form-check-input" type="checkbox" name="status" id="status" value="1" {{ old('status', 0) == 1 ? 'checked' : '' }}>
                    <label class="form-check-label" for="status">{{ trans('cruds.faq.fields.status') }}</label>
                </div>
                @if($errors->has('status'))
                    <div class="invalid-feedback">
                        {{ $errors->first('status') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.faq.fields.status_helper') }}</span>
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
