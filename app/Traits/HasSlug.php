<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait HasSlug
{
    protected static function bootHasSlug()
    {
        static::creating(function ($model) {
            $model->slug = $model->generateSlug();
        });

        static::updating(function ($model) {
            if ($model->isDirty('title')) {
                $model->slug = $model->generateSlug();
            }
        });
    }

    protected function generateSlug()
    {
        $slug = Str::slug($this->title);
        $count = 2;

        while (static::where('slug', $slug)->where('id', '!=', $this->id)->exists()) {
            $slug = Str::slug($this->title) . '-' . $count;
            $count++;
        }

        return $slug;
    }
}
