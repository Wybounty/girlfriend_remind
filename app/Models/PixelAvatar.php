<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PixelAvatar extends Model
{
    protected $fillable = [
        'girlfriend_id',
        'original_photo',
        'segmented_photo',
        'pixel_art_photo',
        'animation_frames',
        'pixel_size',
        'characteristics',
        'avatar_type',
        'rpm_url',
    ];

    protected $casts = [
        'animation_frames' => 'array',
        'characteristics' => 'array',
    ];

    public function girlfriend(): BelongsTo
    {
        return $this->belongsTo(Girlfriend::class);
    }
}
