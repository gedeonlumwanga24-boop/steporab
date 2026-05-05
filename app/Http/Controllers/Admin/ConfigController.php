<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ConfigController extends Controller
{
    public function index()
    {
        $configs = SiteConfig::pluck('value', 'key');
        return view('admin.config.index', compact('configs'));
    }

    public function update(Request $request)
    {
        $data = $request->except('_token');

        // Handle Hero Image Upload
        if ($request->hasFile('hero_image')) {
            $path = $request->file('hero_image')->store('site', 'public');
            SiteConfig::updateOrCreate(['key' => 'hero_image'], ['value' => $path]);
            unset($data['hero_image']);
        }

        foreach ($data as $key => $value) {
            SiteConfig::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        return redirect()->back()->with('success', 'Configurations mises à jour avec succès.');
    }
}
