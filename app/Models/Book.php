<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'title',
        'author',
        'publisher',
        'isbn',
        'year',
        'price',
        'stock',
        'description',
        'cover_image',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'float',
            'stock' => 'integer',
            'year'  => 'integer',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function canDelete(): bool
    {
        return $this->orderItems()->count() === 0;
    }

    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    public function getCoverUrlAttribute(): string
    {
        if (!empty($this->cover_image) && file_exists(public_path('assets/uploads/' . $this->cover_image))) {
            return asset('assets/uploads/' . $this->cover_image);
        }
        return asset('assets/uploads/default_book.png');
    }
}
