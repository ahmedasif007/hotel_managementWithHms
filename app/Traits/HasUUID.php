<?php

namespace App\Traits;

trait HasUUID
{
    public static function bootHasUUID()
    {
        static::creating(function ($model) {
            $model->uuid = \Illuminate\Support\Str::uuid();
        });
    }
}
