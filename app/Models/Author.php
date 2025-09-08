<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Author extends Model
{
    /** @use HasFactory<\Database\Factories\AuthorFactory> */
    use HasFactory;

    /** @var array<int,string> */
    protected $fillable = [
        'name',
        'bio',
        'nationality',
    ];

    /** @return HasMany<Book> */
    public function books(): HasMany
    {
        return $this->hasMany(Book::class);
    }
}
