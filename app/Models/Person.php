<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Person extends Model
{
    protected $keyType = 'int';
    public $incrementing = true;
    
    protected $fillable = [
        'name',
        'age',
        'location',
        'popularity_notified_at',
        'last_notified_like_count'
    ];

    protected $casts = [
        'popularity_notified_at' => 'datetime',
        'last_notified_like_count' => 'integer',
        'age' => 'integer'
    ];


    public function pictures(): HasMany
    {
        return $this->hasMany(Picture::class, 'person_id', 'id')->orderBy('order');
    }

    public function interactions(): HasMany
    {
        return $this->hasMany(Interaction::class, 'person_id', 'id');
    }

    public function likes(): HasMany
    {
        return $this->hasMany(Interaction::class, 'person_id', 'id')->where('action', 'like');
    }
}
