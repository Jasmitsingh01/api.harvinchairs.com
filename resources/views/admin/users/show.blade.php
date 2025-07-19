@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.user.title') }}
    </div>
</div>
        <div class="form ">
          <div class="row p-2">
            <div class=" col-lg-6 ">
                <div class="card  p-3 ">
                    <div class="card-body p-2 " >
                        <div class="card-header pb-1">
                            {{ trans('global.customer') }}
                         </div>
            <table class="table table-bordered"  >
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.user.fields.id') }}
                        </th>
                        <td>
                            {{ $user->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.user.fields.name') }}
                        </th>
                        <td>
                            {{ $user->first_name }} {{ $user->last_name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.user.fields.email') }}
                        </th>
                        <td>
                            {{ $user->email }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.user.fields.email_verified_at') }}
                        </th>
                        <td>
                            {{ $user->email_verified_at }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.user.fields.roles') }}
                        </th>
                        <td>
                            @if ($user->is_admin ==1)
                            {{ trans('global.admin') }}
                            @else
                            {{ trans('global.customer') }}
                            @endif

                        </td>
                    </tr>
                </tbody>
            </table>
                    </div>
        </div>

        <div class=" card overflow-auto d-flex flex-column" style="height: 200px" >

            <div class="card-body p-2 " >
                <div class="card-header">
                    {{ trans('cruds.order.title') }}
                 </div>
         <table class="table table-bordered table-stripedd  ">

            <thead>

                <tr>
                    <th>Id</th>
                    <th>date</th>
                    <th>payment</th>
                    <th>status</th>
                    <th>total Payment</th>
                </tr>
            </thead>
            <tbody>
                @foreach($user->orders as $data)
                <tr>
                   <td>
                    {{$data->id}}
                </td>
                <td>
                    {{$data->created_at}}
                </td>
                <td>
                    {{$data->payment_gateway    }}
                </td>
                <td>
                    {{$data->order_status    }}
                </td>
                <td>
                    {{$data->total    }}
                </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    </div>
    <div class=" card  overflow-auto d-flex flex-column" style="height: 200px" >
        <div class="card-body p-2 " >
            <div class="card-header">
                {{ trans('Card') }}
             </div>
             <table class="table table-bordered table-stripedd  ">

            <thead>
                <tr>
                    <th>ID</th>
                    <th>date</th>
                    <th>Carrier</th>
                    <th>Total</th>
                    <th>View</th>
                </tr>
            </thead>
            <tbody>
                @foreach($user->carts as $data)
                <tr>
                   <td>
                    {{$data->id}}
                </td>
                <td>
                    {{$data->created_at}}
                </td>
                <td>
                 {{$data->delivery_address_id    }}
                </td>
                <td>
                    {{$data->total    }}
                </td>
                <td>
                    {{$data->billing_address_id    }}
                </td>
                </tr>
                @endforeach
            </tbody>
        </table>

    </div>
    </div>


    </div>
     </div>
    <div class=" card overflow-auto d-flex flex-column" >
        <div class="card-body p-2 " >
            <div class="card-header">
                {{ trans('Address') }}
             </div>
             <table class="table table-bordered table-stripedd  ">

            <thead>
                <tr>

                    <th>Name</th>
                    <th>Address type</th>
                    <th>Address</th>
                    <th>Country</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>
                @if(!@empty($user->address))
                    @foreach($user->address as $data)
                    <tr>
                    <td>
                        {{$data->title}}
                    </td>
                    <td>
                        {{$data->type}}
                    </td>

                    <td>
                        @if(!@empty($data->address))
                            @foreach ( $data->address as $key => $value )
                            {{$key}} : {{$value}},
                            @endforeach
                        @endif
                    </td>
                    <td>
                        {{$data->country }}
                    </td>
                    <td>
                        {{$user->email    }}
                    </td>
                    </tr>
                    @endforeach
                @endif
            </tbody>
        </table>

</div>
    </div>

</div>


        <div class=" col-lg-6 " >

    </div>
</div>
    <div class="form-group">
        <a class="btn btn-default" href="{{ route('admin.users.index') }}">
            {{ trans('global.back_to_list') }}
        </a>
    </div>
</div>


@endsection
