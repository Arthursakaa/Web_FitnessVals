<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;

class AdminSettingController extends Controller
{
    public function update(Request $request)
    {
        $data = $request->except(['_token']);

        // Handle text settings
        foreach ($data as $key => $value) {
            if (is_string($value) || is_numeric($value)) {
                Setting::updateOrCreate(
                    ['key' => $key],
                    ['value' => $value]
                );
            }
        }

        // Handle file uploads
        foreach ($request->allFiles() as $key => $file) {
            $path = $file->store('settings', 'public');
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => '/storage/' . $path]
            );
        }

        return back()->with('success', 'Pengaturan CMS berhasil diperbarui.');
    }
}
