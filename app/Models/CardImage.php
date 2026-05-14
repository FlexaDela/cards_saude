<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CardImage extends Model
{
    
    public function card(): BelongsTo
    {
        return $this->belongsTo(Card::class);
    }
}
