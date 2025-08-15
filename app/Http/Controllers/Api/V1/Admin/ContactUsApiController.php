<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreContactUsRequest;
use App\Http\Requests\UpdateContactUsRequest;
use App\Http\Resources\Admin\ContactUsResource;
use App\Models\ContactUs;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ContactUsApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {

        return new ContactUsResource(ContactUs::all());
    }

    public function store(StoreContactUsRequest $request)
    {
        $contactUs = ContactUs::create($request->all());

        if ($request->input('attach_file', false)) {
            $contactUs->addMedia(storage_path('tmp/uploads/' . basename($request->input('attach_file'))))->toMediaCollection('attach_file');
        }

        return (new ContactUsResource($contactUs))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(ContactUs $contactUs)
    {

        return new ContactUsResource($contactUs);
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

        return (new ContactUsResource($contactUs))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(ContactUs $contactUs)
    {

        $contactUs->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
