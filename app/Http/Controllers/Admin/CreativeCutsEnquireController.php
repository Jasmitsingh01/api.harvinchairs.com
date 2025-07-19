<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyCreativeCutsEnquireRequest;
use App\Http\Requests\StoreCreativeCutsEnquireRequest;
use App\Http\Requests\UpdateCreativeCutsEnquireRequest;
use App\Models\CreativeCutsEnquire;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class CreativeCutsEnquireController extends Controller
{
    public function index(Request $request)
    {
        // abort_if(Gate::denies('creative_cuts_enquire_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = CreativeCutsEnquire::query()->select(sprintf('%s.*', (new CreativeCutsEnquire)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'creative_cuts_enquire_show';
                $editGate      = 'creative_cuts_enquire_edit';
                $deleteGate    = 'creative_cuts_enquire_delete';
                $crudRoutePart = 'creative-cuts-enquires';

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
            $table->editColumn('name', function ($row) {
                return $row->name ? $row->name : '';
            });
            $table->editColumn('email', function ($row) {
                return $row->email ? $row->email : '';
            });
            // $table->editColumn('description', function ($row) {
            //     return $row->description ? $row->description : '';
            // });
            // $table->editColumn('upload_file', function ($row) {
            //     return $row->upload_file ? $row->upload_file : '';
            // });
            $table->editColumn('product_name', function ($row) {
                return $row->product_name ? $row->product_name : '';
            });
            $table->editColumn('created_at', function ($row) {
                return $row->created_at->format('d/m/Y')  ? $row->created_at->format('d/m/Y')  : '';
            });
            // $table->editColumn('is_active', function ($row) {
            //     return '<input type="checkbox" disabled ' . ($row->is_active ? 'checked' : null) . '>';
            // });
            // $table->editColumn('notification', function ($row) {
            //     return '<input type="checkbox" disabled ' . ($row->notification ? 'checked' : null) . '>';
            // });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }
        if ($request->has('creative_cuts_id')) {
            $id = $request->input('creative_cuts_id');
                $creativeCuts = CreativeCutsEnquire::findOrFail($id);
               $result= $creativeCuts->update(['notification' => 0]);
               return redirect()->route('admin.creative-cuts-enquires.index');
        }

        return view('admin.creativeCutsEnquires.index');
    }

    public function create()
    {
        // abort_if(Gate::denies('creative_cuts_enquire_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.creativeCutsEnquires.create');
    }

    public function store(StoreCreativeCutsEnquireRequest $request)
    {
        $creativeCutsEnquire = CreativeCutsEnquire::create($request->all());

        return redirect()->route('admin.creative-cuts-enquires.index');
    }

    public function edit(CreativeCutsEnquire $creativeCutsEnquire)
    {
        // abort_if(Gate::denies('creative_cuts_enquire_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.creativeCutsEnquires.edit', compact('creativeCutsEnquire'));
    }

    public function update(UpdateCreativeCutsEnquireRequest $request, CreativeCutsEnquire $creativeCutsEnquire)
    {
        $creativeCutsEnquire->update($request->all());

        return redirect()->route('admin.creative-cuts-enquires.index');
    }

    public function show(CreativeCutsEnquire $creativeCutsEnquire)
    {
        // abort_if(Gate::denies('creative_cuts_enquire_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.creativeCutsEnquires.show', compact('creativeCutsEnquire'));
    }

    public function destroy(CreativeCutsEnquire $creativeCutsEnquire)
    {
        // abort_if(Gate::denies('creative_cuts_enquire_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $creativeCutsEnquire->delete();

        return back();
    }

    public function massDestroy(MassDestroyCreativeCutsEnquireRequest $request)
    {
        $creativeCutsEnquires = CreativeCutsEnquire::find(request('ids'));

        foreach ($creativeCutsEnquires as $creativeCutsEnquire) {
            $creativeCutsEnquire->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
