<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PageSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PageSettingController extends Controller
{
    public function index($slug){
        $data = PageSetting::first();
        return view('admin.page.setting.page-setup',compact('data','slug'));
    }
    public function store(Request $request)
    {
        try {
            $request->validate([
                'about_us' => 'nullable|string',
                'cancellation_policy' => 'nullable|string',
                'privacy_policy' => 'nullable|string',
                'refund_policy' => 'nullable|string',
                'terms_and_conditions' => 'nullable|string',
            ]);

            $settingsData = [];

            $existingSettings = PageSetting::first();

            foreach ($request->all() as $field => $value) {
                if (!empty($value)) {
                    $settingsData[$field] = $value;
                }
            }

            if ($existingSettings) {
                $existingSettings->update($settingsData);
            } else {
                PageSetting::create($settingsData);
            }
            Session::flash('toaster', ['status' => 'success', 'message' => 'Page setup  successfully!']);
            return redirect()->back(); // Replace with your desired redirection route
        }catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('toaster', [
                    'status' => 'error',
                    'message' => 'There were some validation errors.',
                    'errors' => $e->errors()
                ]);
        }

    }
}
