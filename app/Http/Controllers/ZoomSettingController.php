<?php

namespace App\Http\Controllers;

use App\Components\BreadcrumbComponent;
use App\Enums\StatusCode;
use App\Http\Requests\ZoomSettingUpdateRequest;
use App\Models\ZoomSetting;
use Illuminate\Http\Request;

class ZoomSettingController extends BaseController
{
    public function edit()
    {
        $breadcrumbComponent = new BreadcrumbComponent();
        $breadcrumbs = $breadcrumbComponent->generateBreadcrumb([
            ['name' => 'zoom_setting']
        ]);
        $zoomSetting = ZoomSetting::first();

        return view('zoomSetting.edit', [
            'breadcrumbs' => $breadcrumbs,
            'zoomSetting' => $zoomSetting
        ]);
    }

    public function update(ZoomSettingUpdateRequest $request)
    {
        try {
            
            $zoomSetting = ZoomSetting::where('zoom_setting_id', $request->zoom_setting_id)->first();
            $zoomSetting->join_before_host = $request->join_before_host;
            $zoomSetting->auto_recording = $request->auto_recording;
            $zoomSetting->waiting_room = $request->waiting_room;
            
            if (!$zoomSetting->save()) {
                return response()->json(['result' => false], StatusCode::INTERNAL_ERR);
            }

            return response()->json(['redirectUrl' => route('zoomSetting.edit')], StatusCode::OK);

        } catch (\Exception $e) {
            return $e->getMessage();
        }
        
    }
}
