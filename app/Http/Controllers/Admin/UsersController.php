<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Role;
use App\Models\User;
use Hash;

use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UsersController extends Controller
{
    public function index(Request $request)
    {

        $users = User::get();

        if ($request->has('user_id')) {
            $id = $request->input('user_id');
                $user = User::findOrFail($id);
               $result= $user->update(['notification' => 0]);
               return redirect()->route('admin.users.index');
        }

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {

        $roles = Role::pluck('title', 'id');

        return view('admin.users.create', compact('roles'));
    }

    public function store(StoreUserRequest $request)
    {
        $data=$request->all();
        $data['is_admin'] = false;
        if(in_array(1,$request->input('roles'))){
            $data['is_admin'] = true;
        }
        $user = User::create($data);
        $user->roles()->sync($request->input('roles', []));
        return redirect()->route('admin.users.index');
    }

    public function edit(User $user)
    {
        abort_if(Gate::denies('user_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $roles = Role::pluck('title', 'id');

        $user->load('roles');

        return view('admin.users.edit', compact('roles', 'user'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $data=$request->all();
        $data['is_admin'] = false;
        if(isset($request->password)){
            $data['password'] = Hash::make($request->input('password'));
            $user->update($data);
        }
        if(in_array(1,$request->input('roles'))){
            $data['is_admin'] = true;
        }
        $user->update($data);
        $user->roles()->sync($request->input('roles', []));
        return redirect()->route('admin.users.index');
    }

    public function show(User $user)
    {

        $user=User::with(['orders','carts' ,'address','wishLists','shops', 'managed_shop'])->where('id',$user->id)->first();
          //  dd($userData->carts);
        return view('admin.users.show', compact('user'));
    }

    public function destroy(User $user)
    {

        $user->delete();

        return back();
    }

    public function massDestroy(MassDestroyUserRequest $request)
    {
        $users = User::find(request('ids'));

        foreach ($users as $user) {
            $user->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
