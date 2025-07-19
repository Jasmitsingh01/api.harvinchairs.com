<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Database\Models\Address;
use App\Database\Models\Settings;
use App\Models\SiteConfiguration;
use Illuminate\Http\JsonResponse;
use App\Exceptions\MarvelException;
use Illuminate\Support\Facades\Crypt;
use App\Http\Requests\SettingsRequest;
use Illuminate\Database\Eloquent\Collection;
use App\Database\Repositories\SettingsRepository;
use Prettus\Validator\Exceptions\ValidatorException;

class SettingsController extends CoreController
{
    public $repository;

    public function __construct(SettingsRepository $repository)
    {
        $this->repository = $repository;
    }


    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Collection|Address[]
     */
    public function index(Request $request)
    {
        return $this->repository->getData($request->language);
    }
    public function getSystemConfigurations(Request $request)
    {
        try {
            $settings =  SiteConfiguration::get();
            $encryptedSettings = [];
            foreach ($settings as $setting) {
                $encryptedSettings[$setting->varname] = Crypt::encryptString($setting->value);
            }

            return response()->json(['settings' => $encryptedSettings]);
        } catch (\Exception $e) {
            throw new \Exception("Internal server error.");
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param SettingsRequest $request
     * @return mixed
     * @throws ValidatorException
     */
    public function store(SettingsRequest $request)
    {
        $data = $this->repository->where('language', $request->language)->first();

        if ($data) {
            return $data->update($request->only(['options']));
        }

        return $this->repository->create(['options' => $request['options'], 'language' => $request->language ?? DEFAULT_LANGUAGE]);
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return JsonResponse
     */
    public function show($id)
    {
        try {
            return $this->repository->first();
        } catch (\Exception $e) {
            throw new MarvelException(NOT_FOUND);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param SettingsRequest $request
     * @param int $id
     * @return JsonResponse
     * @throws ValidatorException
     */
    public function update(SettingsRequest $request, $id)
    {
        $settings = $this->repository->first();
        if (isset($settings->id)) {
            return $this->repository->update($request->only(['options']), $settings->id);
        } else {
            return $this->repository->create(['options' => $request['options']]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return array
     */
    public function destroy($id)
    {
        throw new MarvelException(ACTION_NOT_VALID);
    }
}
