<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class CustomerNewsLetterController extends Controller
{
    public function index(Request $request)
    {


        if ($request->ajax()) {
            $query = User::query()->select(sprintf('%s.*', (new User)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'news_letter_show';
                $editGate      = 'news_letter_edit';
                $deleteGate    = 'news_letter_delete';
                $crudRoutePart = 'news-letters';

                return view('partials.HideActions', compact(
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
                return $row->first_name ? $row->first_name : '';
            });
            $table->editColumn('email', function ($row) {
                return $row->email ? $row->email : '';
            });
            $table->editColumn('newsletter', function ($row) {
                return $row->newsletter ?
                '<button class="border-0 text-success bg-transparent btn-active" data-id="' .$row->id.
                '"><i class="fa-solid fa-circle-check"></i></button>' :
                '<button class="border-0 text-danger bg-transparent btn-inactive" data-id="'  .$row->id.
                '"><i class="fa-solid fa-circle-xmark"></i></button>';
            });
            $table->editColumn('created_at', function ($row) {
                return $row->created_at->format('d/m/Y') ? $row->created_at->format('d/m/Y') : '';
            });

            $table->rawColumns(['actions', 'placeholder','newsletter']);

            return $table->make(true);
        }

        return view('admin.customerNewsletters.index');
    }

    // public function create()
    // {


    //     return view('admin.newsLetters.create');
    // }

    // public function store(StoreNewsLetterRequest $request)
    // {
    //     $newsLetter = NewsLetter::create($request->all());

    //     return redirect()->route('admin.news-letters.index');
    // }

    // public function edit(NewsLetter $newsLetter)
    // {

    //     return view('admin.newsLetters.edit', compact('newsLetter'));
    // }

    // public function update(UpdateNewsLetterRequest $request, NewsLetter $newsLetter)
    // {
    //     $newsLetter->update($request->all());

    //     return redirect()->route('admin.news-letters.index');
    // }

    // public function show(NewsLetter $newsLetter)
    // {

    //     return view('admin.newsLetters.show', compact('newsLetter'));
    // }

    // public function destroy(NewsLetter $newsLetter)
    // {

    //     $newsLetter->delete();

    //     return back();
    // }

    // public function massDestroy(MassDestroyNewsLetterRequest $request)
    // {
    //     $newsLetters = NewsLetter::find(request('ids'));

    //     foreach ($newsLetters as $newsLetter) {
    //         $newsLetter->delete();
    //     }

    //     return response(null, Response::HTTP_NO_CONTENT);
    // }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:users,id',
            'field' => 'required|in:newsletter',
            'value' => 'required|boolean',
        ]);

        $user = User::find($request->id);
        if (!$user) {
            return response()->json(['error' => 'Customer not found.'], 404);
        }

        $user->{$request->field} = $request->value;
        $user->save();

        return response()->json(['message' => 'Status updated successfully.']);
    }
}
