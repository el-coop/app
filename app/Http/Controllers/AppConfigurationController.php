<?php

namespace App\Http\Controllers;

use App\Http\Requests\DestroyAppConfigurationRequest;
use App\Http\Requests\StoreAppConfigurationRequest;
use App\Models\AppConfiguration;
use Illuminate\Http\Request;

class AppConfigurationController extends Controller {

    public function index() {
        return [
            'configurations' => AppConfiguration::select('id', 'key', 'value')->get()
        ];
    }

    public function get($key) {
        return [
            'configuration' => AppConfiguration::select('id', 'key', 'value')->where('key', $key)->first(),
        ];
    }

    public function store(StoreAppConfigurationRequest $request) {
        return $request->commit();
    }


    public function update(StoreAppConfigurationRequest $request, AppConfiguration $appConfiguration) {
        return $request->commit();
    }

    public function destroy(DestroyAppConfigurationRequest $request, AppConfiguration $appConfiguration) {
        return [
            'success' => $request->commit()
        ];

    }
}
