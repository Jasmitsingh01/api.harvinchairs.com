@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.user.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.users.update", [$user->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="first_name">{{ trans('cruds.user.fields.name') }}</label>
                <input class="form-control {{ $errors->has('first_name') ? 'is-invalid' : '' }}" type="text" name="first_name" id="first_name" value="{{ old('first_name', $user->first_name) }}" required>
                @if($errors->has('first_name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('first_name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="email">{{ trans('cruds.user.fields.email') }}</label>
                <input class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required>
                @if($errors->has('email'))
                    <div class="invalid-feedback">
                        {{ $errors->first('email') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.email_helper') }}</span>
            </div>

            <div class="form-group">
                <label class="required" for="roles">{{ trans('cruds.user.fields.roles') }}</label>

                {{-- <select class="form-control select2 {{ $errors->has('is_admin') ? 'is-invalid' : '' }}" name="is_admin" id="is_active" required>

                     @if ( $user->is_admin == 0)
                     <option value="0"  selected>Customer</option>
                     <option value="1"  >Admin</option>
                     @else
                     <option value="1"  selected>Admin</option>
                     <option value="0"  >Customer</option>

                     @endif

                </select> --}}
                <select class="form-control select2 {{ $errors->has('roles') ? 'is-invalid' : '' }}" name="roles[]" id="roles" multiple required>
                    @foreach($roles as $id => $role)
                        <option value="{{ $id }}" {{ (in_array($id, old('roles', [])) || $user->roles->contains($id)) ? 'selected' : '' }}>{{ $role }}</option>
                    @endforeach
                </select>
                @if($errors->has('is_admin'))
                    <div class="invalid-feedback">
                        {{ $errors->first('is_admin') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.roles_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="form-check-label" for="enabled"> {{ trans('global.update') }} {{ trans('cruds.user.fields.password') }}</label>
                <div class="form-check mt-1 ">
                    <input class="form-check-input" type="checkbox"  id="enabled" value="0">
                    <label class="form-check-label" for="enabled"> {{ trans('global.yes') }}</label>
                </div>
            </div>
             <div class="form-group" id="passwordDiv" style="display:none">
                <label class="required" for="password">{{ trans('cruds.user.fields.password') }}</label>
                <input class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" type="password" name="password" id="password" >
                @if($errors->has('password'))
                    <div class="invalid-feedback">
                        {{ $errors->first('password') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.password_helper') }}</span>
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

<script>
   document.getElementById('enabled').addEventListener('change', function() {
        var passwordDiv = document.getElementById('passwordDiv');
        if (this.checked) {
            passwordDiv.style.display = 'block';
        } else {
            passwordDiv.style.display = 'none';
        }
    });
</script>

@endsection
