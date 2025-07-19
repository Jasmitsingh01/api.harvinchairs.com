<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyContactUsRequest;
use App\Http\Requests\StoreContactUsRequest;
use App\Http\Requests\UpdateContactUsRequest;
use App\Models\ContactUs;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class ContactUsController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {

        if ($request->ajax()) {
            $query = ContactUs::query()->select(sprintf('%s.*', (new ContactUs)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'contact_us_show';
                $editGate      = 'contact_us_edit';
                $deleteGate    = 'contact_us_delete';
                $crudRoutePart = 'contact-uss';

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
            // $table->editColumn('subject', function ($row) {
            //     return $row->subject ? $row->subject : '';
            // });
            // $table->editColumn('message', function ($row) {
            //     return $row->message ? $row->message : '';
            // });
            // $table->editColumn('attach_file', function ($row) {
            //     return $row->attach_file ? '<a href="' . $row->attach_file->getUrl() . '" target="_blank">' . trans('global.downloadFile') . '</a>' : '';
            // });
            // $table->editColumn('notification', function ($row) {
            //     return '<input type="checkbox" disabled ' . ($row->notification ? 'checked' : null) . '>';
            // });
            $table->editColumn('created_at', function ($row) {
                     return date('d/m/Y', strtotime($row->created_at)) ? date('d/m/Y ', strtotime($row->created_at)) : '';
                });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }
        if ($request->has('contact_us_id')) {
            $id = $request->input('contact_us_id');
                $contactUs = ContactUs::findOrFail($id);
               $result= $contactUs->update(['notification' => 0]);
               return redirect()->route('admin.contact-uss.index');
        }

        return view('admin.contactUss.index');
    }

    public function create()
    {

        return view('admin.contactUss.create');
    }

    public function store(StoreContactUsRequest $request)
    {
        $contactUs = ContactUs::create($request->all());

        if ($request->input('attach_file', false)) {
            $contactUs->addMedia(storage_path('tmp/uploads/' . basename($request->input('attach_file'))))->toMediaCollection('attach_file');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $contactUs->id]);
        }

        return redirect()->route('admin.contact-uss.index');
    }

    public function edit($id)
    {
        $contactUs = ContactUs::find($id);
        return view('admin.contactUss.edit', compact('contactUs'));
    }

    public function update(UpdateContactUsRequest $request, ContactUs $contactUs)
    {
        $contactUs->update($request->all());

        if ($request->input('attach_file', false)) {
            if (! $contactUs->attach_file || $request->input('attach_file') !== $contactUs->attach_file->file_name) {
                if ($contactUs->attach_file) {
                    $contactUs->attach_file->delete();
                }
                $contactUs->addMedia(storage_path('tmp/uploads/' . basename($request->input('attach_file'))))->toMediaCollection('attach_file');
            }
        } elseif ($contactUs->attach_file) {
            $contactUs->attach_file->delete();
        }

        return redirect()->route('admin.contact-uss.index');
    }

    public function show($id)
    {
        $contactUs = ContactUs::find($id);
        return view('admin.contactUss.show', compact('contactUs'));
    }

    public function destroy($id)
    {
        $contactUs = ContactUs::find($id);
        $contactUs->delete();

        return back();
    }

    public function massDestroy(Request $request)
    {
        $contactUss = ContactUs::find($request->get('ids'));

        foreach ($contactUss as $contactUs) {
            $contactUs->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {

        $model         = new ContactUs();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
