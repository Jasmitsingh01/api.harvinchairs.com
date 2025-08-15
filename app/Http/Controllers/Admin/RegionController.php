<?php

namespace App\Http\Controllers\Admin;

use App\Models\State;
use App\Models\PostalCode;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\PostcodeRegion;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRegionRequest;
use App\Http\Requests\RegionUpdateRequest;

class RegionController extends Controller
{
    public function index()
    {

        $zipcodes = PostcodeRegion::get();

        $states = PostalCode::get();
        //get unique state names from postal_codes table
        // $states = $states->unique('state')->pluck('state');
        return view('admin.regions.index', compact('states', 'zipcodes'));
    }

    public function create()
    {

        $states = PostalCode::take(5)->get();
        // $all_pincodes = PostalCode::pluck('pincode');

        //get unique state names from postal_codes table
        $states = $states->unique('state')->pluck('state');

        return view('admin.regions.create', compact('states'));
    }

    public function store(StoreRegionRequest $request)
    {
            $data =$request->all();
            $data['postcode'] = implode(',',$request->postcode);
            $zipcode = PostcodeRegion::create($data);

        return redirect()->route('admin.regions.index');
    }

    public function edit(PostcodeRegion $region)
    {
        $states = PostalCode::get();
        $states = $states->unique('state')->pluck('state');
        $selected_postcodes = explode(',',$region->postcode);
        // dd( $selected_postcodes);
        $postcodes = PostalCode::whereIn('pincode',$selected_postcodes)->distinct()->pluck('pincode');

        return view('admin.regions.edit', compact('states', 'region','postcodes','selected_postcodes'));
    }

    public function update(RegionUpdateRequest $request, PostcodeRegion $region)
    {
        $data =$request->all();
        $data['postcode'] = implode(',',$request->postcode);
        $region->update($data);

        return redirect()->route('admin.regions.index');
    }

    public function show(PostcodeRegion $zipcode)
    {

        return view('admin.zipcodes.show', compact('zipcode'));
    }

    public function destroy($zipcode)
    {
        $zipcode = PostcodeRegion::find($zipcode);
        $zipcode->delete();

        return back();
    }

    public function massDestroy(Request $request)
    {
        $zipcodes = PostcodeRegion::find(request('ids'));

        foreach ($zipcodes as $zipcode) {
            $zipcode->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
    public function validatePostcode(Request $request)
    {
        $request->validate([
            'postcode' => 'required|string',
        ]);


        $postcode = $request->postcode;

        // Check if the length of the entered postal code is three or more characters
        if (strlen($postcode) >= 3) {
            // Fetch suggestions from the database based on the partial postal code
            $suggestions = PostalCode::where('pincode', 'LIKE', $postcode . '%')->distinct()->pluck('pincode')->toArray();
        } else {
            $suggestions = []; // If the length of the postal code is less than three, return an empty array
        }

        return response()->json(['suggestions' => $suggestions]);
    }
    public function getPostcodes(Request $request)
    {
        $state = $request->state;

        // Retrieve postal codes based on the selected country/region
        $region = PostalCode::where('state', $state)->distinct()->pluck('pincode');
        return response()->json(['postcodes' => $region]);
    }
}
