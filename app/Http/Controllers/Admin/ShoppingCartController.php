<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyShoppingCartRequest;
use App\Http\Requests\StoreShoppingCartRequest;
use App\Http\Requests\UpdateShoppingCartRequest;
use App\Models\Cart;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class ShoppingCartController extends Controller
{
    public function index(Request $request)
    {

        if ($request->ajax()) {
            $query = Cart::where('is_confirm',0)->with(['user'])->select(sprintf('%s.*', (new Cart)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'shopping_cart_show';
                $editGate      = 'shopping_cart_edit';
                $deleteGate    = 'shopping_cart_delete';
                $crudRoutePart = 'shopping-carts';

                return view('partials.ViewDeleteActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });
            $table->addColumn('user_name', function ($row) {
                return $row->user ? $row->user->first_name .' '.$row->user->last_name : '';
            });

            // $table->editColumn('delivery_address', function ($row) {
            //     return $row->delivery_address_id ? $row->delivery_address_id : '';
            // });
            // $table->editColumn('billing_address', function ($row) {
            //     return $row->billing_address_id ? $row->billing_address_id : '';
            // });
            $table->editColumn('total', function ($row) {
                return $row->total ? $row->total : '';
            });
            $table->editColumn('created_at', function ($row) {
                return $row->created_at->format('d/m/Y') ? $row->created_at->format('d/m/Y') : '';
            });
            // $table->editColumn('is_empty', function ($row) {
            //     return '<input type="checkbox" disabled ' . ($row->is_empty ? 'checked' : null) . '>';
            // });
            // $table->editColumn('is_confirm', function ($row) {
            //     return '<input type="checkbox" disabled ' . ($row->is_confirm ? 'checked' : null) . '>';
            // });

            $table->rawColumns(['actions', 'placeholder', 'user']);

            return $table->make(true);
        }

        $users = User::get();

        return view('admin.shoppingCarts.index', compact('users'));
    }

    public function create()
    {

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.shoppingCarts.create', compact('users'));
    }

    public function store(StoreShoppingCartRequest $request)
    {
        $shoppingCart = Cart::create($request->all());

        return redirect()->route('admin.shopping-carts.index');
    }

    public function edit(Cart $shoppingCart)
    {

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $shoppingCart->load('user');

        return view('admin.shoppingCarts.edit', compact('shoppingCart', 'users'));
    }

    public function update(UpdateShoppingCartRequest $request, Cart $shoppingCart)
    {
        $shoppingCart->update($request->all());

        return redirect()->route('admin.shopping-carts.index');
    }

    public function show(Cart $shoppingCart)
    {
           $shoppingCart = Cart::with(['user','cartProducts','orderShippingAddress','orderBillingAddress'])->where('id',$shoppingCart->id)->first();
        // $shoppingCart->load('user');
            //   dd($shoppingCart);
        return view('admin.shoppingCarts.show', compact('shoppingCart'));
    }

    public function destroy(Cart $shoppingCart)
    {

        $shoppingCart->delete();

        return back();
    }

    public function massDestroy(MassDestroyShoppingCartRequest $request)
    {
        $shoppingCarts = Cart::find(request('ids'));

        foreach ($shoppingCarts as $shoppingCart) {
            $shoppingCart->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
