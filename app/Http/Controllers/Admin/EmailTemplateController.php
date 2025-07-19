<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyEmailTemplateRequest;
use App\Http\Requests\StoreEmailTemplateRequest;
use App\Http\Requests\UpdateEmailTemplateRequest;
use App\Models\EmailTemplate;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class EmailTemplateController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = EmailTemplate::query()->select(sprintf('%s.*', (new EmailTemplate)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'email_template_show';
                $editGate      = 'email_template_edit';
                $deleteGate    = 'email_template_delete';
                $crudRoutePart = 'email-templates';

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
            $table->editColumn('template_code', function ($row) {
                return $row->template_code ? $row->template_code : '';
            });
            $table->editColumn('template_description', function ($row) {
                return $row->template_description ? $row->template_description : '';
            });
            $table->editColumn('subject', function ($row) {
                return $row->subject ? $row->subject : '';
            });
            $table->editColumn('email_file_name', function ($row) {
                return $row->email_file_name ? $row->email_file_name : '';
            });

            $table->editColumn('status', function ($row) {
                return $row->status == 'Active' ?
                '<button class="border-0 text-success bg-transparent btn-active" data-id="' .$row->id.
                '"><i class="fa-solid fa-circle-check"></i></button>' :
                '<button class="border-0 text-danger bg-transparent btn-inactive" data-id="'  .$row->id.
                '"><i class="fa-solid fa-circle-xmark"></i></button>';
            });

            $table->rawColumns(['actions', 'placeholder', 'status']);

            return $table->make(true);
        }

        return view('admin.emailTemplates.index');
    }

    public function create()
    {

        return view('admin.emailTemplates.create');
    }

    public function store(StoreEmailTemplateRequest $request)
    {
        $emailTemplate = EmailTemplate::create($request->all());

        return redirect()->route('admin.email-templates.index');
    }

    public function edit(EmailTemplate $emailTemplate)
    {

        return view('admin.emailTemplates.edit', compact('emailTemplate'));
    }

    public function update(UpdateEmailTemplateRequest $request, EmailTemplate $emailTemplate)
    {
        $emailTemplate->update($request->all());

        return redirect()->route('admin.email-templates.index');
    }

    public function show(EmailTemplate $emailTemplate)
    {

        return view('admin.emailTemplates.show', compact('emailTemplate'));
    }

    public function destroy(EmailTemplate $emailTemplate)
    {

        $emailTemplate->delete();

        return back();
    }

    public function massDestroy(MassDestroyEmailTemplateRequest $request)
    {
        $emailTemplates = EmailTemplate::find(request('ids'));

        foreach ($emailTemplates as $emailTemplate) {
            $emailTemplate->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
    public function updateStatus(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'field' => 'required|in:status',
            'value' => 'required',
        ]);

        $template = EmailTemplate::find($request->id);
        if (!$template) {
            return response()->json(['error' => 'template not found.'], 404);
        }

        $template->{$request->field} = $request->value;
        $template->save();

        return response()->json(['message' => 'Status updated successfully.']);
    }
}
