<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Picture extends Model
{
    protected $fillable = [
        'person_id',
        'url',
        'order'
    ];

    protected $casts = [
        'order' => 'integer',
        'person_id' => 'integer'
    ];

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'person_id', 'id');
    }
}
