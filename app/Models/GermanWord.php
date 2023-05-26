<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GermanWord extends Model
{
    use HasFactory;

    protected $fillable = [
        'word',
        'translation',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}