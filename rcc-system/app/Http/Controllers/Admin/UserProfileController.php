<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UserProfileController extends Controller
{
    public function show(User $user)
    {
        $user->load([
            'group:id,name,color_hex',
            'groups:id,name,color_hex',
            'ministries:id,name',
            'photos:id,user_id,file_path,is_active,created_at',
            'activities:id,user_id,activity_type,details,created_at',
            'messages:id,user_id,subject,content,status,created_at',
        ]);

        return view('admin.users.profile', [
            'user' => $user,
            'isPdf' => false,
        ]);
    }

    public function pdf(User $user)
    {
        $user->load([
            'group:id,name,color_hex',
            'groups:id,name,color_hex',
            'ministries:id,name',
            'photos:id,user_id,file_path,is_active,created_at',
            'activities:id,user_id,activity_type,details,created_at',
            'messages:id,user_id,subject,content,status,created_at',
        ]);

        $tplVersion = @filemtime(resource_path('views/admin/users/profile-pdf.blade.php')) ?: time();
        $key = 'user_profile_pdf_'.$user->id.'_'.optional($user->updated_at)->timestamp.'_'.$tplVersion;
        if (request()->boolean('reset') || request()->boolean('fresh')) {
            \Illuminate\Support\Facades\Cache::forget($key);
        }
        $bytes = Cache::remember($key, now()->addMinutes(30), function () use ($user) {
            $photoDataUri = null;
            try {
                $active = $user->activePhoto()->first();
                if ($active) {
                    $disk = Storage::disk('public');
                    $file = $active->file_path;
                    $contents = $disk->get($file);
                    $mime = 'image/jpeg';
                    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                    if ($ext === 'png') {
                        $mime = 'image/png';
                    } elseif ($ext === 'gif') {
                        $mime = 'image/gif';
                    }
                    $photoDataUri = 'data:'.$mime.';base64,'.base64_encode($contents);
                }
                if (! $photoDataUri) {
                    $initials = collect(explode(' ', trim((string) $user->name)))
                        ->filter()
                        ->map(fn ($p) => mb_substr($p, 0, 1))
                        ->take(2)->implode('');
                    $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="160" height="160"><rect width="160" height="160" rx="80" fill="#EBF4FF"/><text x="50%" y="55%" dominant-baseline="middle" text-anchor="middle" font-size="64" font-family="Segoe UI, Arial" fill="#0f172a">'.$initials.'</text></svg>';
                    $photoDataUri = 'data:image/svg+xml;base64,'.base64_encode($svg);
                }
                $logoDataUri = null;
                foreach (['brand/logo com nome,sem paroquia.png', 'brand/logo-rcc.png', 'brand/logo.png'] as $logoPath) {
                    if (Storage::disk('public')->exists($logoPath)) {
                        $bytes = Storage::disk('public')->get($logoPath);
                        $logoDataUri = 'data:image/png;base64,'.base64_encode($bytes);
                        break;
                    }
                }
            } catch (\Throwable $e) {
                // ignore
            }
            $pdf = Pdf::loadView('admin.users.profile-pdf', [
                'user' => $user,
                'isPdf' => true,
                'photoDataUri' => $photoDataUri,
                'logoDataUri' => $logoDataUri,
            ])->setPaper('a4', 'portrait');

            return $pdf->output();
        });

        $safe = Str::slug($user->name ?: 'perfil');

        return response($bytes, 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="'.$safe.'-perfil.pdf"');
    }
}
