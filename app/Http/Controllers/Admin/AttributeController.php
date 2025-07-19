<?php

namespace App\Http\Controllers\Admin;

use Gate;
use App\Models\Shop;
use App\Models\Attribute;
use Illuminate\Http\Request;
use App\Models\AttributeValue;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAttributeRequest;
use App\Http\Requests\UpdateAttributeRequest;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\MassDestroyAttributeRequest;
class AttributeController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('attribute_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $language = $request->language ?? config('shop.default_language');
        $attributes = Attribute::orderBy('position','asc')->with(['shop','values'])->where('language',$language)->get();

        $shops = Shop::get();

        return view('admin.attributes.index', compact('attributes', 'shops'));
    }

    public function create()
    {
        abort_if(Gate::denies('attribute_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $shops = Shop::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.attributes.create', compact('shops'));
    }

    public function store(StoreAttributeRequest $request)
    {
        abort_if(Gate::denies('attribute_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $language = $request->language ?? config('shop.default_language');
        $attribute_data = $request->all();
        $attribute_data['slug'] = $this->customSlugify($request->name);
        $attribute_data['language'] = $language;


        $feature = Attribute::create($attribute_data);

        return redirect()->route('admin.attributes.index');
    }

    public function edit(Attribute $attribute)
    {
        abort_if(Gate::denies('attribute_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $shops = Shop::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $attribute->load('shop');

        return view('admin.attributes.edit', compact('attribute', 'shops'));
    }

    public function update(UpdateAttributeRequest $request, Attribute $attribute)
    {
        abort_if(Gate::denies('attribute_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $language = $request->language ?? config('shop.default_language');
        $attribute_data = $request->all();
        $attribute_data['slug'] = $this->customSlugify($request->name);
        $attribute_data['language'] = $language;

        $attribute->update($attribute_data);

        return redirect()->route('admin.attributes.index');
    }

    public function show(Request $request, $attribute)
    {
        abort_if(Gate::denies('attribute_value_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $language = $request->language ?? config('shop.default_language');
        $attributeId=$attribute;
        $attributeValues = AttributeValue::where('attribute_id',$attribute)->orderBy('position','asc')->where('language',$language)->with(['attribute'])->get();

        return view('admin.attributeValues.index', compact('attributeValues','attributeId'));
    }

    public function destroy(Attribute $attribute)
    {
        abort_if(Gate::denies('attribute_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $attribute->delete();

        return back();
    }

    public function massDestroy(MassDestroyAttributeRequest $request)
    {
        $attributes = Attribute::find(request('ids'));

        foreach ($attributes as $attribute) {
            $attribute->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
    public function updatePositions(Request $request)
    {
        abort_if(Gate::denies('attribute_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sortedIds = $request->input('attribute');
        $position = 1;
        $positions = [];

        foreach ($sortedIds as $productId) {
            $attribute = Attribute::find($productId);
            $attribute->position = $position;
            $attribute->save();

            // Store the updated position for each product ID
            $positions[$productId] = $position;

            $position++;
        }

        return response()->json(['positions' => $positions]);

    }

    public function customSlugify($text, string $divider = '-')
    {
        $slug      = str_replace(' ', '-', $text);
        $slugCount = Attribute::where('slug', $slug)->orWhere('slug', 'like',  $slug . '%')->count();

        if (empty($slugCount)) {
            return $slug;
        }

        return $slug . $divider . $slugCount;
    }
}
