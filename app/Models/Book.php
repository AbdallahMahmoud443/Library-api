<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    /** @use HasFactory<\Database\Factories\BookFactory> */
    use HasFactory;
    /** @var array<int,string> */
    protected $guarded = ['id'];

    /** @return BelongTo<Author> */
    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class);
    }

    /** @return HasMany<Borrowing> */
    public function borrowings(): HasMany
    {
        return $this->hasMany(Borrowing::class);
    }

    /**
     * Decrements the available copies of the book by 1
     */
    public function borrowingBook(): void
    {
        if ($this->available_copies > 0) {
            $this->decrement('available_copies');
        }
    }

    /**
     * Increments the available copies of the book by 1
     */
    public function returningBook(): void
    {
        if ($this->available_copies < $this->total_copies) {
            $this->increment('available_copies');
        }
    }

    public function borrowingsCount(): int
    {
        return $this->borrowings()->where('status', 'borrowed')->count();
    }

    public function isAvailableCopies(): bool
    {
        if ($this->available_copies > 0) {
            return true;
        }
        return false;
    }
}
