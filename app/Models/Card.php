<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Override;

class Card extends Model
{
    /** @use HasFactory<\Database\Factories\CardFactory> */
    use HasFactory;
    protected $fillable = ['name', 'description', 'available', 'show', 'price'];


    #[Override]
    public function casts(): array
    {
        return [
            'available' => 'boolean',
            'pice' => 'decimal:2',
            'created_at' => 'datetime:d-m-Y'
        ];
    }

    //função que formata o valor e deixa ele ajustado ja para exibir na tela.
    protected function formattedPrice(): Attribute
    {
        return Attribute::make(
            get: fn () => 'R$ ' . number_format($this->price, 2, ',', '.'),
        );
    }

    public function categories():BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_card');
    }

    public function cardImages(): HasMany
    {
        return $this->hasMany(CardImage::class);
    }
}
