<?php

namespace App\Http\Controllers\Web\Admin;

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
        $data = $request->except(
            '_token',
            'hero_image', 'hero_video',
            'trending_video',
            'remove_hero_video', 'remove_trending_video',
            'about_hero_image', 'about_image_1', 'about_image_2'
        );

        // ── Hero video suppression ──
        if ($request->boolean('remove_hero_video')) {
            $this->deleteStoredFile(SiteConfig::where('key', 'hero_video')->value('value'));
            SiteConfig::where('key', 'hero_video')->delete();
        }

        if ($request->boolean('remove_trending_video')) {
            $this->deleteStoredFile(SiteConfig::where('key', 'trending_video')->value('value'));
            SiteConfig::where('key', 'trending_video')->delete();
        }

        // ── Hero image ──
        if ($request->hasFile('hero_image')) {
            $path = $request->file('hero_image')->store('site', 'public');
            SiteConfig::updateOrCreate(['key' => 'hero_image'], ['value' => $path]);
        }

        // ── Hero video ──
        if ($request->hasFile('hero_video')) {
            $old = SiteConfig::where('key', 'hero_video')->value('value');
            $this->deleteStoredFile($old);
            $path = $request->file('hero_video')->store('site/videos', 'public');
            SiteConfig::updateOrCreate(['key' => 'hero_video'], ['value' => $path]);
        }

        // ── Trending video ──
        if ($request->hasFile('trending_video')) {
            $old = SiteConfig::where('key', 'trending_video')->value('value');
            $this->deleteStoredFile($old);
            $path = $request->file('trending_video')->store('site/videos', 'public');
            SiteConfig::updateOrCreate(['key' => 'trending_video'], ['value' => $path]);
        }

        // ── À propos images ──
        foreach (['about_hero_image', 'about_image_1', 'about_image_2'] as $field) {
            if ($request->hasFile($field)) {
                $old = SiteConfig::where('key', $field)->value('value');
                $this->deleteStoredFile($old);
                $path = $request->file($field)->store('site/about', 'public');
                SiteConfig::updateOrCreate(['key' => $field], ['value' => $path]);
            }
        }

        // ── Autres champs texte ──
        foreach ($data as $key => $value) {
            SiteConfig::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        return redirect()->back()->with('success', 'Configurations mises à jour avec succès.');
    }

    private function deleteStoredFile(?string $path): void
    {
        if (! $path || str_starts_with($path, 'http')) {
            return;
        }

        Storage::disk('public')->delete($path);
    }
}
