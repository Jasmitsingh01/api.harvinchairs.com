<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\Feature;
use App\Models\FeatureValue;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;

class FeatureValueController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $featureId = $request->get('id');
        return view('admin.featureValues.create', compact('featureId'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        try{
            $data = $request->all();
            $feature=$request->get('feature_id');
            $data['is_custom'] = false;
            $value = FeatureValue::create($data);
            return redirect()->route('admin.features.show',$feature);
       }catch(Exception $e){
            return false;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $featureValueId=$id;
        $features_values = FeatureValue::where('id',$id)->where('is_custom',false)->first();

        return view('admin.featureValues.edit', compact('featureValueId','features_values'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $value=$request->get('value');
        $data = FeatureValue::where('id',$id)->update(['value'=>$value]);
        $feature = FeatureValue::where('id',$id)->pluck('feature_id')->first();
      return redirect()->route('admin.features.show',$feature);
    }

    public function destroy( $value)
    {
         $featureValue= FeatureValue::find($value);

         $featureValue->delete();
        return back();
    }

    public function massDestroy(Request $request)
    {
        $values = FeatureValue::find(request('ids'));

        foreach ($values as $value) {
            $value->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
