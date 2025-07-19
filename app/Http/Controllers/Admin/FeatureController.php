<?php

namespace App\Http\Controllers\Admin;

use Gate;
use Exception;
use App\Models\Feature;
use App\Models\FeatureValue;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use App\Http\Requests\StoreFeatureRequest;
use App\Http\Requests\UpdateFeatureRequest;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\MassDestroyFeatureRequest;

class FeatureController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('feature_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $language = $request->language ?? config('shop.default_language');
        $features = Feature::orderBy('position','asc')->where('language',$language)->get();
        return view('admin.features.index', compact('features'));
    }

    public function create()
    {
        abort_if(Gate::denies('feature_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.features.create');
    }

    public function store(StoreFeatureRequest $request)
    {
        abort_if(Gate::denies('feature_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if($request->ajax()){
            try{
                $language = $request->language ?? config('shop.default_language');
                // $position = Feature::where('language',$language)->orderby('id','desc')->pluck('position')->first();
                $feature_data = $request->all();
                $feature_data['language'] = $language;
                // $feature_data['position'] = $position + 1;

                $feature = Feature::create($feature_data);

                $featuredata = Feature::with('featureValues')->find($feature->id);

                return response()->json(['data'=>$featuredata]);
            }
            catch(Exception $e){
                return false;
            }

        }

        $language = $request->language ?? config('shop.default_language');
        // $position = Feature::where('language',$language)->orderby('id','desc')->pluck('position')->first();
        $feature_data = $request->all();
        $feature_data['language'] = $language;
        // $feature_data['position'] = $position + 1;

        $feature = Feature::create($feature_data);

        return redirect()->route('admin.features.index');
    }
    public function storeFeatureValue(Request $request)
    {


    }

    public function edit(Feature $feature)
    {
        abort_if(Gate::denies('feature_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.features.edit', compact('feature'));
    }

    public function update(UpdateFeatureRequest $request, Feature $feature)
    {
        abort_if(Gate::denies('feature_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $feature->update($request->all());

        return redirect()->route('admin.features.index');
    }

    public function show($feature,Request $request)
    {
        abort_if(Gate::denies('feature_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $language = $request->language ?? config('shop.default_language');
        $featureId=$feature;
        $features_values = FeatureValue::where('feature_id',$feature)->where('language',$language)->where('is_custom',false)->with(['feature'])->get();
        return view('admin.featureValues.index', compact('features_values','featureId'));
    }

    public function destroy(Feature $feature)
    {
        abort_if(Gate::denies('feature_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $feature->delete();

        return back();
    }

    public function massDestroy(MassDestroyFeatureRequest $request)
    {
        $features = Feature::find(request('ids'));

        foreach ($features as $feature) {
            $feature->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
    public function updatePositions(Request $request)
    {
        abort_if(Gate::denies('feature_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $sortedIds = $request->input('product');
        $position = 1;
        $positions = [];

        foreach ($sortedIds as $productId) {
            $product = Feature::find($productId);
            $product->position = $position;
            $product->save();

            // Store the updated position for each product ID
            $positions[$productId] = $position;

            $position++;
        }

        return response()->json(['positions' => $positions]);

    }
}
