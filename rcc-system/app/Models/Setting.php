<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
    ];

    protected $casts = [
        // store as JSON; selectively encrypt sensitive fields
        'value' => 'array',
    ];

    public function setValueAttribute($value): void
    {
        if (is_array($value)) {
            $key = $this->attributes['key'] ?? $this->key ?? null;
            $sensitive = [
                'email' => ['password'],
                'sms' => ['api_key'],
                'mercadopago' => ['access_token'],
                'whatsapp' => ['token'],
            ];
            if ($key && isset($sensitive[$key])) {
                foreach ($sensitive[$key] as $f) {
                    if (isset($value[$f]) && $value[$f] !== null && ! str_starts_with((string) $value[$f], 'ENC::')) {
                        $value[$f] = 'ENC::'.base64_encode(Crypt::encryptString((string) $value[$f]));
                    }
                }
            }
        }
        $this->attributes['value'] = json_encode($value);
    }

    public function getValueAttribute($value)
    {
        $arr = json_decode($value, true) ?? [];
        $key = $this->attributes['key'] ?? $this->key ?? null;
        $sensitive = [
            'email' => ['password'],
            'sms' => ['api_key'],
            'mercadopago' => ['access_token'],
            'whatsapp' => ['token'],
        ];
        if ($key && isset($sensitive[$key])) {
            foreach ($sensitive[$key] as $f) {
                if (isset($arr[$f]) && is_string($arr[$f]) && str_starts_with($arr[$f], 'ENC::')) {
                    try {
                        $enc = substr($arr[$f], 5);
                        $arr[$f] = Crypt::decryptString(base64_decode($enc));
                    } catch (\Throwable $e) {
                        // leave as-is if cannot decrypt
                    }
                }
            }
        }

        return $arr;
    }

    protected static function booted(): void
    {
        static::saved(function (Setting $setting) {
            try {
                app(\App\Services\SiteSettings::class)->invalidate($setting->key);
            } catch (\Throwable $e) {
            }
        });
        static::deleted(function (Setting $setting) {
            try {
                app(\App\Services\SiteSettings::class)->invalidate($setting->key);
            } catch (\Throwable $e) {
            }
        });
    }
}
