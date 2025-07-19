<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreStaticPageRequest;
use App\Http\Requests\UpdateStaticPageRequest;
use App\Http\Resources\Admin\StaticPageResource;
use App\Models\StaticPage;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StaticPageController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        // abort_if(Gate::denies('static_page_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new StaticPageResource(StaticPage::all());
    }

    public function store(StoreStaticPageRequest $request)
    {
        $staticPage = StaticPage::create($request->all());

        return (new StaticPageResource($staticPage))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(StaticPage $staticPage)
    {
        // abort_if(Gate::denies('static_page_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new StaticPageResource($staticPage);
    }

    public function update(UpdateStaticPageRequest $request, StaticPage $staticPage)
    {
        $staticPage->update($request->all());

        return (new StaticPageResource($staticPage))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(StaticPage $staticPage)
    {
        // abort_if(Gate::denies('static_page_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $staticPage->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function showStaticPages(Request $request)
    {
        $slug =  $request->input('slug');
       $staticPages = StaticPage::where('slug',$slug)->first();
       if( $staticPages){
        return response()->json(['status' => true, 'media' => $staticPages], 200);
       }else{
        return response()->json(['status' => false, 'message' =>'Page Not Found'], 400);
       }

    }

}
