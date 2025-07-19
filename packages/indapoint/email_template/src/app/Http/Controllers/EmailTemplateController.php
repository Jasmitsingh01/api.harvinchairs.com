<?php

namespace Indapoint\EmailTemplate\App\Http\Controllers;

use Indapoint\EmailTemplate\EmailTemplate;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Validator;

class EmailTemplateController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
       
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $emailTemplates = EmailTemplate::orderBy('id', 'desc')->get();
        return $emailTemplates->isEmpty() ? $this->success(null, 'NO_RECORD_FOUND') : $this->success($emailTemplates);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validations = config('email_template.validation');
        $validation = Validator::make($request->all(), $validations);

        if ($validation->fails()) {
            return $this->validationError($validation);
        }

        EmailTemplate::createRecord($request->all());
        return $this->success(null, 'EMAIL_TEMPLATE_CREATED', 201);
    }

    /**
     * Display the specified resource.
     * @param type $id
     * @return type
     */
    public function show($id)
    {
        $emailTemplate = EmailTemplate::find($id);
        return (!$emailTemplate) ? $this->error('EMAIL_TEMPLATE_DOESNT_EXIST', 404) : $this->success($emailTemplate);
    }

    /**
     * Update the specified resource in storage
     * @param Request $request
     * @param type $id
     * @return type
     */
    public function update(Request $request, $id)
    {
        try {
            EmailTemplate::findOrFail($id);
        } catch (ModelNotFoundException $ex) {
            return $this->error('EMAIL_TEMPLATE_DOESNT_EXIST', 404);
        }

        $validations = config('email_template.validation_update');
        $validation = Validator::make($request->all(), $validations);

        if ($validation->fails()) {
            return $this->validationError($validation);
        }

        EmailTemplate::updateRecord($request->all(), $id);
        return $this->success(null, 'EMAIL_TEMPLATE_UPDATED', 200);
    }

    /**
     * Remove the specified resource from storage
     * @param type $id
     * @return type
     */
    public function destroy($id)
    {
        try {
            $emailTemplate = EmailTemplate::findOrFail($id);
            $emailTemplate->delete();
            return $this->success(null, 'EMAIL_TEMPLATE_DELETED', 200);
        } catch (ModelNotFoundException $ex) {
            return $this->error('EMAIL_TEMPLATE_DOESNT_EXIST', 404);
        }
    }

    /**
     * Change status
     * @param Request $request
     * @return type
     */
    public function changeStatus(Request $request)
    {
        $validation = Validator::make($request->all(), [
                    'status' => 'required',
                    'id' => 'required|integer'
        ]);

        if ($validation->fails()) {
            return $this->validationError($validation);
        }

        try {
            $emailTemplate = EmailTemplate::findOrFail($request->get('id'));
            $emailTemplate->status = $request->get('status');
            $emailTemplate->save();
            return $this->success(null, ($request->get('status') == 'Active' ? 'EMAIL_TEMPLATE_STATUS_ACTIVE' : 'EMAIL_TEMPLATE_STATUS_INACTIVE'), 200);
        } catch (ModelNotFoundException $ex) {
            return $this->error('EMAIL_TEMPLATE_DOESNT_EXIST', 404);
        }
    }
}
