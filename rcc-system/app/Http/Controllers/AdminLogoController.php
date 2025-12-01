<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Setting;

class AdminLogoController extends Controller
{
    public function show()
    {
        abort_unless(Auth::check(), 403);
        $brand = Setting::where('key','brand')->first();
        $value = $brand?->value ?? [];
        $url = null;
        if (!empty($value['logo'])) {
            $url = Storage::disk('public')->url($value['logo']);
        }
        return response()->json([
            'logo_url' => $url,
            'settings' => $value['norm'] ?? null,
            'history' => $value['history'] ?? [],
            'formats' => ['image/png','image/jpeg','image/svg+xml'],
        ]);
    }

    public function save(Request $request)
    {
        abort_unless(Auth::check(), 403);
        $data = $request->validate([
            'format' => ['required','in:png,jpg,jpeg,svg'],
            'file' => ['nullable','file'],
            'settings' => ['nullable','array'],
            'history' => ['nullable','array'],
            'filename' => ['nullable','string'],
        ]);

        $ext = $data['format'] === 'jpg' ? 'jpeg' : $data['format'];
        $path = null;
        if ($request->file('file')) {
            $fname = $data['filename'] ?? ('logo_'.time().'.'.$ext);
            $path = Storage::disk('public')->putFileAs('brand', $request->file('file'), $fname);
        }

        $brand = Setting::firstOrCreate(['key'=>'brand']);
        $value = $brand->value ?? [];
        if ($path) { $value['logo'] = $path; }
        if (!empty($data['settings'])) { $value['norm'] = $data['settings']; }
        if (!empty($data['history'])) { $value['history'] = $data['history']; }
        $brand->value = $value;
        $brand->save();

        return response()->json([
            'status' => 'ok',
            'logo_path' => $value['logo'] ?? null,
            'logo_url' => isset($value['logo']) ? Storage::disk('public')->url($value['logo']) : null,
        ]);
    }
}

