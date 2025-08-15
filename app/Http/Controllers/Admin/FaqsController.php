<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyFaqRequest;
use App\Http\Requests\StoreFaqRequest;
use App\Http\Requests\UpdateFaqRequest;
use App\Models\Faq;
use App\Models\FaqType;
use App\Models\Product;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class FaqsController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('faq_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $query = Faq::with(['product','faqType'])->select(sprintf('%s.*', (new Faq)->table));
        // add condition product id is null
        $query->where('product_id', null);
        if ($request->ajax()) {
            $query = Faq::with(['product','faqType'])->select(sprintf('%s.*', (new Faq)->table));
            $query->where('product_id', null);
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'faq_show';
                $editGate      = 'faq_edit';
                $deleteGate    = 'faq_delete';
                $crudRoutePart = 'faqs';

                return view('partials.Actions', compact(
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
            // $table->addColumn('product_name', function ($row) {
            //     return $row->product ? $row->product->name : '';
            // });

            $table->editColumn('faq_type', function ($row) {
                return $row->faq_type_id ? $row->faqType->name : '';
            });

            $table->editColumn('question', function ($row) {
                return $row->question ? $row->question : '';
            });
            $table->editColumn('answer', function ($row) {
                return $row->answer ? $row->answer : '';
            });
            $table->editColumn('status', function ($row) {
                return '<input type="checkbox" disabled ' . ($row->status ? 'checked' : null) . '>';
            });

            $table->rawColumns(['actions', 'placeholder', 'product', 'status']);

            return $table->make(true);
        }

        $products = Product::get();

        $faqtypes = FaqType::where('status','active')->get();

        return view('admin.faqs.index', compact('products','faqtypes'));
    }

    public function create()
    {
        abort_if(Gate::denies('faq_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $products = Product::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $faqtypes = FaqType::where('status','active')->get();
        return view('admin.faqs.create', compact('products','faqtypes'));
    }

    public function store(StoreFaqRequest $request)
    {
        $faq = Faq::create($request->all());

        return redirect()->route('admin.faqs.index');
    }

    public function edit(Faq $faq)
    {
        abort_if(Gate::denies('faq_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $products = Product::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $faqtypes = FaqType::where('status','active')->get();

        $faq->load('product');

        return view('admin.faqs.edit', compact('faq', 'products','faqtypes'));
    }

    public function update(UpdateFaqRequest $request, Faq $faq)
    {
        $faq->update($request->all());

        return redirect()->route('admin.faqs.index');
    }

    public function show(Faq $faq)
    {
        abort_if(Gate::denies('faq_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $faq->load('product');

        return view('admin.faqs.show', compact('faq'));
    }

    public function destroy(Faq $faq)
    {
        abort_if(Gate::denies('faq_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $faq->delete();

        return back();
    }

    public function massDestroy(MassDestroyFaqRequest $request)
    {
        $faqs = Faq::find(request('ids'));

        foreach ($faqs as $faq) {
            $faq->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
