<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyTagRequest;
use App\Http\Requests\StoreTagRequest;
use App\Http\Requests\UpdateTagRequest;
use App\Models\Tag;
use App\Models\Product;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Symfony\Component\HttpFoundation\Response;

class TagController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('tag_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $language = $request->language ?? config('shop.default_language');
        $tags = Tag::withCount('products')->where('language' , $language)->get();
        return view('admin.tags.index', compact('tags'));
    }

    public function create(Request $request)
    {
        abort_if(Gate::denies('tag_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $language = $request->language ?? config('shop.default_language');
        $products = Product::orderBy('name','asc')->where('language' , $language)->pluck('id','name');
        return view('admin.tags.create', compact('products'));
    }

    public function store(Request $request)
    {
        abort_if(Gate::denies('tag_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if($request->ajax()){
            $existing_tag = Tag::where('name',$request->input('name'))->exists();
            if($existing_tag == true){
                $tag = Tag::where('name',$request->input('name'))->first();
            }
            else{
                $tag = new Tag();
                $tag->name = $request->input('name');
                $tag->slug = $this->customSlugify($request->input('name'));
                $tag->save();
            }
            return response()->json($tag);
        }
        $data=$request->all();
        $data['slug'] =$this->customSlugify($request->input('name'));
        $tag = Tag::create($data);
        $tag->products()->sync($request['category_product']);

        return redirect()->route('admin.tags.index');
    }

    public function edit(Tag $tag)
    {
        abort_if(Gate::denies('tag_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $language = $request->language ?? config('shop.default_language');

        $products = Product::where('language' , $language)->orderBy('name','asc')->pluck('id','name')->toArray();
       $tagWithProducts = Tag::with(['products' => function ($query) {
                             $query->orderBy('name', 'asc');}])
        ->where('language' , $language)
        ->where('id',$tag->id)->get();


        $existingProducts = Product::whereHas('tags', function ($query) use ($tag) {
            $query->where('tag_id', $tag->id);
        })->pluck('id')->toArray();

        $productsList = array_intersect($products, array_diff($products, $existingProducts));


        foreach ($tagWithProducts as $value ){
              $productWithTag =$value;
        }
        return view('admin.tags.edit', compact('tag','productsList','productWithTag'));
    }

    public function update(UpdateTagRequest $request, Tag $tag)
    {
        abort_if(Gate::denies('tag_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $data=$request->all();
        $data['slug'] =$this->customSlugify($request->input('name'));
        $tag->update($data);
        $tag->products()->sync($request['category_product'],true);
        return redirect()->route('admin.tags.index');
    }

    public function show(Tag $tag)
    {
        abort_if(Gate::denies('tag_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.tags.show', compact('tag'));
    }

    public function destroy(Tag $tag)
    {
        abort_if(Gate::denies('tag_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $tag->delete();

        return back();
    }

    public function massDestroy(MassDestroyTagRequest $request)
    {
        abort_if(Gate::denies('tag_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $tags = Tag::find(request('ids'));

        foreach ($tags as $tag) {
            $tag->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
    public function customSlugify($text, string $divider = '-')
    {
        $slug      = str_replace(' ', '-', $text);
        $slugCount = Tag::where('slug', $slug)->orWhere('slug', 'like',  $slug . '%')->count();

        if (empty($slugCount)) {
            return $slug;
        }

        return $slug . $divider . $slugCount;
    }
}
