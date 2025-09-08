<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Member extends Model
{
    /** @use HasFactory<\Database\Factories\MemberFactory> */
    use HasFactory;

    /** @var array<int,string> */
    protected $fillable = [
        'name',
        'email',
        'address',
        'phone',
        'membership_date',
        'status'
    ];
    /** @var array<int,string> */
    protected $casts = [
        'membership_date' => 'date'
    ];

    /** @return HasMany<Borrowing> */
    public function borrowings(): HasMany
    {
        return $this->hasMany(Borrowing::class);
    }

    /**
     * Get the member's active borrowings.
     * @return HasMany<Borrowing>
     */
    public function activeBorrowings()
    {
        return $this->borrowings()->where('status', 'active');
    }
}
