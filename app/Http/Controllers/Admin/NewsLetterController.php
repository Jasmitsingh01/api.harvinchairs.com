<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyNewsLetterRequest;
use App\Http\Requests\StoreNewsLetterRequest;
use App\Http\Requests\UpdateNewsLetterRequest;
use App\Models\NewsLetter;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class NewsLetterController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('news_letter_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = NewsLetter::query()->select(sprintf('%s.*', (new NewsLetter)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'news_letter_show';
                $editGate      = 'news_letter_edit';
                $deleteGate    = 'news_letter_delete';
                $crudRoutePart = 'news-letters';

                return view('partials.DeleteActions', compact(
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
            $table->editColumn('email', function ($row) {
                return $row->email ? $row->email : '';
            });
            // $table->editColumn('ip_registration_newsletter', function ($row) {
            //     return $row->ip_registration_newsletter ? $row->ip_registration_newsletter : '';
            // });
            // $table->editColumn('http_referer', function ($row) {
            //     return $row->http_referer ? $row->http_referer : '';
            // });

            $table->editColumn('is_active', function ($row) {
                return $row->is_active ?
                '<button class="border-0 text-success bg-transparent btn-active" data-id="' .$row->id.
                '"><i class="fa-solid fa-circle-check"></i></button>' :
                '<button class="border-0 text-danger bg-transparent btn-inactive" data-id="'  .$row->id.
                '"><i class="fa-solid fa-circle-xmark"></i></button>';
            });
              $table->editColumn('created_at', function ($row) {
                return $row->created_at->format('d/m/Y') ? $row->created_at->format('d/m/Y') : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'is_active']);

            return $table->make(true);
        }

        return view('admin.newsLetters.index');
    }

    public function create()
    {


        return view('admin.newsLetters.create');
    }

    public function store(StoreNewsLetterRequest $request)
    {
        $newsLetter = NewsLetter::create($request->all());

        return redirect()->route('admin.news-letters.index');
    }

    public function edit(NewsLetter $newsLetter)
    {

        return view('admin.newsLetters.edit', compact('newsLetter'));
    }

    public function update(UpdateNewsLetterRequest $request, NewsLetter $newsLetter)
    {
        $newsLetter->update($request->all());

        return redirect()->route('admin.news-letters.index');
    }

    public function show(NewsLetter $newsLetter)
    {

        return view('admin.newsLetters.show', compact('newsLetter'));
    }

    public function destroy(NewsLetter $newsLetter)
    {

        $newsLetter->delete();

        return back();
    }

    public function massDestroy(MassDestroyNewsLetterRequest $request)
    {
        $newsLetters = NewsLetter::find(request('ids'));

        foreach ($newsLetters as $newsLetter) {
            $newsLetter->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:news_letters,id',
            'field' => 'required|in:is_active',
            'value' => 'required|boolean',
        ]);

        $newsLetter = NewsLetter::find($request->id);
        // dd($user);
        if (!$newsLetter) {
            return response()->json(['error' => 'Customer not found.'], 404);
        }

        $newsLetter->{$request->field} = $request->value;
        $newsLetter->save();

        return response()->json(['message' => 'Status updated successfully.']);
    }
}
