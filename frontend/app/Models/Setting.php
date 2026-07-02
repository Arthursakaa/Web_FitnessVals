<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = ['key', 'value', 'type'];

    public static function getVal($key, $default = null)
    {
        return \Illuminate\Support\Facades\Cache::rememberForever("setting.{$key}", function () use ($key, $default) {
            $setting = self::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        });
    }

    protected static function booted()
    {
        static::saved(function ($setting) {
            \Illuminate\Support\Facades\Cache::forget("setting.{$setting->key}");
        });

        static::deleted(function ($setting) {
            \Illuminate\Support\Facades\Cache::forget("setting.{$setting->key}");
        });
    }
}
