<?php

namespace App\Http\Controllers\Admin;

use Gate;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\ProductAttribute;
use Illuminate\Support\Collection;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\ProductAttributeCombination;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\StoreProductAttributeRequest;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\UpdateProductAttributeRequest;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use App\Http\Requests\MassDestroyProductAttributeRequest;
use App\Http\Controllers\Traits\AttributeCombinationTrait;

class ProductAttributeController extends Controller
{
    use MediaUploadingTrait;
    use AttributeCombinationTrait;

    public function index()
    {
        // abort_if(Gate::denies('product_attribute_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $productAttributes = ProductAttribute::with(['product', 'media'])->get();

        return view('admin.productAttributes.index', compact('productAttributes'));
    }

    public function create()
    {
        // abort_if(Gate::denies('product_attribute_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $products = Product::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.productAttributes.create', compact('products'));
    }

    public function store(request $request)
    {
        $tabname = "product-information";
        if ($request->has('tabname')) {
            $tabname = $request->input('tabname');
        }
        if ($request->has('new_combination_btn')) {
            $productAttr  = new productAttribute();
            $productAttr->product_id = $request->input('product_id');
            $productAttr->out_of_stock = 0;
            $productAttr->quantity = 0;
            $prod_attr = $productAttr->save();

            $productAttrCombi = new ProductAttributeCombination();
            $productAttrCombi->product_attribute_id = $productAttr->id;
            $productAttrCombi->attribute_id =  $request->input('new_attribute');
            $productAttrCombi->attribute_value_id =  $request->input('attributeValue');
            $productAttrCombi->save();

            return redirect()->route('admin.products.edit', ['product' => $request->input('product_id'), 'activeTab' => $tabname]);
        }
        $prefix = 'attribute-';
        $attribute_arr = [];

        foreach ($request->all() as $key => $value) {
            if (strpos($key, $prefix) === 0) {
                $newKey = substr($key, strlen('attribute-'));
                $attribute_arr[$newKey] = $value;
            }
        }
        if(empty($attribute_arr)){
            return response()->json(['custom_error' => 'Please select at least one attribute.']);
        }
        $attribures = $this->generateCombinations($attribute_arr);

        // Fetch existing combinations for the product
        $existingCombinations = ProductAttributeCombination::whereHas('productAttribute', function ($query) use ($request) {
            $query->where('product_id', $request->input('product_id'));
        })->with('attributeValue')->get();

        $existingCombinationsGrouped = [];

        foreach ($existingCombinations as $combination) {
            $productAttributeId = $combination->product_attribute_id;

            if (!isset($existingCombinationsGrouped[$productAttributeId])) {
                $existingCombinationsGrouped[$productAttributeId] = [];
            }

            $attributeId = $combination->attributeValue->attribute_id;
            $valueId = $combination->attribute_value_id;

            $existingCombinationsGrouped[$productAttributeId][$attributeId] = $valueId;
        }
        $existingCombStrings= [];
        foreach($existingCombinationsGrouped as $key=>$comb){
            $existingCombStrings[] = '{' . implode(',', array_map(
                function ($k, $v) {
                    return "$k:$v";
                },
                array_keys($comb),
                $comb
            )) . '}';
        }
        $newCombinations = new Collection();

        foreach ($attribures as $attribute) {
            ksort($attribute);
            $combinationString = '{' . implode(',', array_map(
                function ($k, $v) {
                    return "$k:$v";
                },
                array_keys($attribute),
                $attribute
            )) . '}';
            // Create a unique representation of the combination
            if (!in_array($combinationString, $existingCombStrings)) {
                $newCombinations->push($attribute);
                $existingCombStrings[] = $combinationString;
            }
        }
        foreach ($newCombinations as $attribute) {
            $productAttr = new productAttribute();
            $productAttr->product_id = $request->input('product_id');
            $productAttr->reference_code = $request->input('default_reference');
            $productAttr->quantity = $request->input('default_quantity');
            $productAttr->out_of_stock = 0;
            $productAttr->save();

            if ($productAttr) {
                $newRecord = productAttribute::find($productAttr->id);

                foreach ($attribute as $key => $combination) {
                    $productAttrCombi = new ProductAttributeCombination();
                    $productAttrCombi->product_attribute_id = $newRecord->id;
                    $productAttrCombi->attribute_id = $key;
                    $productAttrCombi->attribute_value_id = $combination;
                    $productAttrCombi->save();
                }
            }
        }

        return response()->json(['success' => true]);
        // return redirect()->route('admin.products.edit', ['product' => $request->input('product_id'), 'activeTab' => $tabname]);
    }

    public function edit(ProductAttribute $productAttribute)
    {
        // abort_if(Gate::denies('product_attribute_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $products = Product::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $productAttribute->load('product');

        return view('admin.productAttributes.edit', compact('productAttribute', 'products'));
    }

    public function update(UpdateProductAttributeRequest $request, ProductAttribute $productAttribute)
    {
        $productAttribute->update($request->all());

        if ($request->input('images', false)) {
            if (!$productAttribute->images || $request->input('images') !== $productAttribute->images->file_name) {
                if ($productAttribute->images) {
                    $productAttribute->images->delete();
                }
                $productAttribute->addMedia(storage_path('tmp/uploads/' . basename($request->input('images'))))->toMediaCollection('images');
            }
        } elseif ($productAttribute->images) {
            $productAttribute->images->delete();
        }

        return redirect()->route('admin.product-attributes.index');
    }

    public function show(ProductAttribute $productAttribute)
    {
        // abort_if(Gate::denies('product_attribute_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $productAttribute->load('product');

        return view('admin.productAttributes.show', compact('productAttribute'));
    }

    public function destroy(Request $request, ProductAttribute $productAttribute)
    {
        // abort_if(Gate::denies('product_attribute_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        if ($request->has('tabname')) {
            $tabname = $request->input('tabname');
        }
        $productAttribute->delete();

        return redirect()->route('admin.products.edit', ['product' => $request->input('product_id'), 'activeTab' => $tabname]);
    }

    public function massDestroy(MassDestroyProductAttributeRequest $request)
    {
        $productAttributes = ProductAttribute::find(request('ids'));

        foreach ($productAttributes as $productAttribute) {
            $productAttribute->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        // abort_if(Gate::denies('product_attribute_create') && Gate::denies('product_attribute_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new ProductAttribute();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
    public function updateImage(Request $request)
    {
        // dd($request);
        // $data = "[" . $request->selected_images . "]";
        $data =  $request->selected_images;

        $data2 = json_decode($data, true);
        $product_attribute = ProductAttribute::find($request->product_attribute_id);
        if (isset($product_attribute)) {
            $product_attribute->images = $data2;
            // dd($product_attribute);
            $product_attribute->save();
        }
        return response()->json(['data' => $product_attribute]);
    }
    public function updatePositions(Request $request)
    {
        $sortedIds = $request->input('prod_comb');
        $position = 1;
        $positions = [];

        foreach ($sortedIds as $productId) {
            $attribute = ProductAttribute::find($productId);
            $attribute->position = $position;
            $attribute->save();

            // Store the updated position for each product ID
            $positions[$productId] = $position;

            $position++;
        }

        return response()->json(['positions' => $positions]);
    }
    public function massUpdate(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'action' => 'required|in:enabled,delete',
            'value' => 'required|boolean',
        ]);
        $attributes = ProductAttribute::find(request('ids'));

        foreach ($attributes as $attribute) {
            if (isset($request->action)) {
                if ($request->action == "delete") {
                    $attribute->delete();
                } else {
                    $attribute->{$request->action} = $request->value;
                    $attribute->save();
                }
            } else {
            }
        }

        return response()->json(['message' => 'Status updated successfully.']);
    }

    public function bulkUpdate(Request $request)
    {
        $rules = [
            'ids' => 'required|array',
            'field' => 'required',
            'attr_value' => 'required',
        ];
        // If the field contains 'reference code', add a validation rule for 'value' to be alphanumeric
        if ($request->input('field') === 'reference_code') {
            $rules['attr_value'] .= '|alpha_num';
        } else {
            // Otherwise, add a validation rule for 'value' to be numeric
            $rules['attr_value'] .= '|numeric';
        }


        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => 'Only Numbers Allowed.']);
        }

        $attributes = ProductAttribute::find(request('ids'));

        foreach ($attributes as $attribute) {
            $attribute->{$request->field} = $request->attr_value;
            $attribute->save();
        }
        return response()->json(['message' => 'Values updated successfully.']);
    }
}
