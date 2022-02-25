<?php

namespace App\Http\Controllers;

use App\Components\BreadcrumbComponent;
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
            $flag = ZoomSetting::where('zoom_setting_id', $request->id)
            ->update([
                'join_before_host' => $request->join_before_host,
                'auto_recording' => $request->auto_recording,
                'waiting_room' => $request->waiting_room,
             ]);
            
            if ($flag) {
                $this->setFlash(__('ZOOM連携設定情報変更が完了しました。'));
                return redirect()->route('zoomSetting.edit');
            }

        } catch (\Exception $e) {
            return $e->getMessage();
        }
        
    }
}
