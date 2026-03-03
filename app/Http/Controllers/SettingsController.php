<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;

class SettingsController extends Controller
{
    public function index()
    {
        $keys = [
            'site_name',
            'google_maps_key',
            'default_locale',
            'support_email',
        ];

        $settings = Setting::whereIn('key', $keys)->get()->pluck('value', 'key')->toArray();

        // provide sensible defaults
        $data = [];
        foreach ($keys as $k) {
            $data[$k] = $settings[$k] ?? config('app.' . ($k === 'site_name' ? 'name' : 'locale')) ?? '';
        }

        $data['support_email'] = $settings['support_email'] ?? config('mail.from.address') ?? '';

        return view('settings.index', ['settings' => $data]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'site_name' => 'nullable|string|max:255',
            'google_maps_key' => 'nullable|string|max:255',
            'default_locale' => 'nullable|string|max:10',
            'support_email' => 'nullable|email|max:255',
        ]);

        foreach ($validated as $k => $v) {
            Setting::setValue($k, $v);
        }

        return back()->with('success', 'Settings saved successfully.');
    }
}
