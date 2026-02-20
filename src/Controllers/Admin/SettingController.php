<?php

namespace Azuriom\Plugin\WebMap\Controllers\Admin;

use Azuriom\Http\Controllers\Controller;
use Azuriom\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Display the config page.
     */
    public function show()
    {
        return view('webmap::admin.settings', [
            'url' => setting('webmap.url', 'http://play.nexaria.fr:8123/')
        ]);
    }

    /**
     * Save the config.
     */
    public function save(Request $request)
    {
        $request->validate([
            'url' => 'required|url',
        ]);

        Setting::updateSettings([
            'webmap.url' => $request->input('url'),
        ]);

        return redirect()->route('webmap.admin.settings')
            ->with('success', trans('webmap::admin.settings.saved'));
    }
}
