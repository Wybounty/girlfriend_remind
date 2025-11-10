<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GirlfriendInfo extends Model
{
    /**
     * Les attributs qui peuvent être assignés en masse.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'girlfriend_id',
        'titre',
        'reponses',
    ];

    /**
     * Relation avec Girlfriend.
     */
    public function girlfriend(): BelongsTo
    {
        return $this->belongsTo(Girlfriend::class);
    }
}
