<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\UpdateSettingRequest;
use App\Models\Setting;
use App\Traits\SaveImageTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SettingController extends Controller
{
    use SaveImageTrait;

    public function index()
    {
        $data['settings'] = new Setting();
        return view('admin.settings.index' , $data);
    }

    public function update(Request $request)
    {
        $data = $request->all();
        try {
            foreach ($data as $key => $value) {
                if (!is_null($value)) {
                    if ($key === 'map_embed') {
                        $value = $this->extractIframeSrc($value);
                    }

                    if (request()->has('company_logo') && $key == 'company_logo') {
                        $value = $this->saveImage($request->company_logo, 'company_logo');
                    }
                    Setting::updateOrCreate(['key' => $key], ['value' => $value]);
                }
            }
            return $this->response_api(200, __('admin.form.added_successfully'), '');

        } catch (Exception $e) {
            // return $this->response_api(400, $e->getMessage());
            return back()->with(['msg_status' => 'error','msg_content' => $e->getMessage()]);
        }

    }

    private function extractIframeSrc($input)
    {
        // إذا أدخل المستخدم iframe
        if (Str::contains($input, '<iframe')) {
            // استخراج src باستخدام regex
            preg_match('/src="([^"]+)"/', $input, $matches);
            return $matches[1] ?? $input;
        }

        // إذا كان إدخال المستخدم فعلاً رابط، نُعيده كما هو
        return $input;
    }

}
