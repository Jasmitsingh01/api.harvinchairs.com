<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreNewsLetterRequest;
use App\Http\Requests\UpdateNewsLetterRequest;
use App\Http\Resources\Admin\NewsLetterResource;
use App\Models\NewsLetter;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class NewsLetterApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('news_letter_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new NewsLetterResource(NewsLetter::all());
    }

    public function store(StoreNewsLetterRequest $request)
    {
        $newsLetter = NewsLetter::create($request->all());

        return (new NewsLetterResource($newsLetter))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(NewsLetter $newsLetter)
    {
        abort_if(Gate::denies('news_letter_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new NewsLetterResource($newsLetter);
    }

    public function update(UpdateNewsLetterRequest $request, NewsLetter $newsLetter)
    {
        $newsLetter->update($request->all());

        return (new NewsLetterResource($newsLetter))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(NewsLetter $newsLetter)
    {
        abort_if(Gate::denies('news_letter_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $newsLetter->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
