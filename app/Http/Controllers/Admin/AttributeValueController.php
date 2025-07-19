<?php

namespace App\Http\Controllers\Admin;

use Gate;
use App\Models\Attribute;
use Illuminate\Http\Request;
use App\Models\AttributeValue;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\StoreAttributeValueRequest;
use App\Http\Requests\UpdateAttributeValueRequest;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyAttributeValueRequest;

class AttributeValueController extends Controller
{
    use MediaUploadingTrait;
    public function index(Request $request)
    {
        abort_if(Gate::denies('attribute_values_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $attributeValues = AttributeValue::with(['attribute'])->get();
        return view('admin.attributeValues.index', compact('attributeValues'));
    }

    public function create(Request $request)
    {
        abort_if(Gate::denies('attribute_value_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $attribute= Attribute::find($request->get('id'));
        $language = $request->language ?? config('shop.default_language');
        $attributes = Attribute::where('language',$language)->pluck('slug', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.attributeValues.create', compact('attributes','attribute'));
    }

    public function store(StoreAttributeValueRequest $request)
    {
        abort_if(Gate::denies('attribute_value_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $attribute_data = $request->all();
        $attribute_data['slug'] = $this->customSlugify($request->value);
        $attributeId=$request->get('attribute_id');
        $attributedetail = Attribute::find($attributeId);

        if($attributedetail->group_type == 'image_radio' && empty($request->input('cover_image'))){
            return redirect()->back()->with('error', 'Cover Image is required.');
        }

        $attributeValue = AttributeValue::create($attribute_data);
        if ($request->input('cover_image', false)) {
            $attributeValue->addMedia(storage_path('tmp/uploads/' . basename($request->input('cover_image'))))->preservingOriginal()->toMediaCollection('cover_image');
            $converted_url = [
                'thumbnail' => $attributeValue->cover_image->getUrl('thumb'),
                'original' => $attributeValue->cover_image->getUrl(),
                //'filepath' =>$attributeValue->cover_image->getPath(),
            ];
            $attributeValue->update(['cover_image'=>$converted_url]);
        }
        if ($request->input('fabric_image', false)) {
            $fabric_image_url = $attributeValue->addMedia(storage_path('tmp/uploads/' . basename($request->input('fabric_image'))))->preservingOriginal()->toMediaCollection('fabric_image');
            $converted_url = [
                'thumbnail' => $fabric_image_url->getUrl('thumb'),
                'original' => $fabric_image_url->getUrl(),
                //'filepath' =>$fabric_image_url->getPath(),
            ];
            $attributeValue->update(['fabric_image'=>$converted_url]);
        }
        $attributeValues = AttributeValue::where('attribute_id',$attributeId)->with(['attribute'])->get();
        return redirect()->route('admin.attributes.show',$attributeId);
    }

    public function edit(Request $request ,AttributeValue $attributeValue)
    {
        abort_if(Gate::denies('attribute_value_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $language = $request->language ?? config('shop.default_language');
        $attributes = Attribute::where('language',$language)->pluck('slug', 'id')->prepend(trans('global.pleaseSelect'), '');

        $attributeValue->load('attribute');
        // dd($attributeValue->fabric_image);
        return view('admin.attributeValues.edit', compact('attributeValue', 'attributes'));
    }

    public function update(UpdateAttributeValueRequest $request, AttributeValue $attributeValue)
    {
        abort_if(Gate::denies('attribute_value_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $attributeId = $request->attribute_id;
        $language = $request->language ?? config('shop.default_language');
        $attribute_data =  $request->except('cover_image', 'fabric_image');
        $attribute_data['slug'] = $this->customSlugify($request->value);
        $attribute_data['language'] = $language;

        $attributeValue->update($attribute_data);


        if ($request->input('cover_image', false)) {

            $newFileName = basename($request['cover_image']);
            if (!$attributeValue->cover_image || $request->input('cover_image') !== $attributeValue->cover_image->name) {
                // Delete the existing image if it exists
                if ($attributeValue->cover_image) {
                    $attributeValue->cover_image->delete();
                }

                // Store the new image
                $coverImage = $attributeValue->addMedia(storage_path('tmp/uploads/' . $newFileName))->preservingOriginal()->toMediaCollection('cover_image');
                $coverImage->url = $coverImage->getUrl();
                $coverImage->thumbnail = $coverImage->getUrl('thumb');
                $coverImage->filepath = $coverImage->getPath();

                $converted_url = [
                    'thumbnail' =>  $coverImage->thumbnail,
                    'original' =>    $coverImage->url,
                    //'filepath' =>    $coverImage->filepath,
                ];

                // Update the product with the new image details
                $attributeValue->update(['cover_image' => $converted_url]);
            }
        } elseif($attributeValue->cover_image){
             $attributeValue->cover_image->delete();
            $attributeValue->update(['cover_image'=>null]);
        }
        if ($request->input('fabric_image', false)) {

            $newFileName = basename($request['fabric_image']);
            if (!$attributeValue->fabric_image || $request->input('fabric_image') !== $attributeValue->fabric_image->name) {
                // Delete the existing image if it exists
                if ($attributeValue->fabric_image) {
                    $attributeValue->fabric_image->delete();
                }

                // Store the new image
                $fabricImage = $attributeValue->addMedia(storage_path('tmp/uploads/' . $newFileName))->preservingOriginal()->toMediaCollection('fabric_image');
                $fabricImage->url = $fabricImage->getUrl();
                $fabricImage->thumbnail = $fabricImage->getUrl('thumb');
                $fabricImage->filepath = $fabricImage->getPath();

                $converted_url = [
                    'thumbnail' =>  $fabricImage->thumbnail,
                    'original' =>    $fabricImage->url,
                    //'filepath' =>    $fabricImage->filepath,
                ];

                // Update the product with the new image details
                $attributeValue->update(['fabric_image' => $converted_url]);
            }
        } elseif($attributeValue->fabric_image){
             $attributeValue->fabric_image->delete();
            $attributeValue->update(['fabric_image'=>null]);
        }

        return redirect()->route('admin.attributes.show',$attributeId);
    }

    public function show(AttributeValue $attributeValue)
    {
        abort_if(Gate::denies('attribute_value_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $attributeValue->load('attribute');

        return view('admin.attributeValues.show', compact('attributeValue'));
    }

    public function destroy(AttributeValue $attributeValue)
    {
        abort_if(Gate::denies('attribute_value_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $attributeId=$attributeValue->attribute_id;
        $attributeValue->delete();
        $attributeValues = AttributeValue::where('attribute_id',$attributeId)->with(['attribute'])->get();
        return redirect()->route('admin.attributes.show',$attributeId);
    }

    public function massDestroy(MassDestroyAttributeValueRequest $request)
    {
        abort_if(Gate::denies('attribute_value_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $attributeValues = AttributeValue::find(request('ids'));

        foreach ($attributeValues as $attributeValue) {
            $attributeValue->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
    public function getAttributeValues(Request $request)
    {
        $attributeId = $request->input('attribute_id');
        $language = $request->language ?? config('shop.default_language');
        $attributeValues = AttributeValue::where('attribute_id',$attributeId)->where('language',$language)->get();

        return response()->json($attributeValues);
    }
    public function customSlugify($text, string $divider = '-')
    {
        $slug      = str_replace(' ', '-', $text);
        $slugCount = AttributeValue::where('slug', $slug)->orWhere('slug', 'like',  $slug . '%')->count();

        if (empty($slugCount)) {
            return $slug;
        }

        return $slug . $divider . $slugCount;
    }
    public function updatePositions(Request $request)
    {
        abort_if(Gate::denies('attribute_value_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $sortedIds = $request->input('attributevalue');
        $position = 1;
        $positions = [];

        foreach ($sortedIds as $productId) {
            $attribute = AttributeValue::find($productId);
            $attribute->position = $position;
            $attribute->save();

            // Store the updated position for each product ID
            $positions[$productId] = $position;

            $position++;
        }

        return response()->json(['positions' => $positions]);

    }
}
