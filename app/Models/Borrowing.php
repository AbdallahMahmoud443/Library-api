<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Borrowing extends Model
{
    /** @use HasFactory<\Database\Factories\BorrowingFactory> */
    use HasFactory;


    /** @var array<int,string> */
    protected $fillable = [
        'user_id',
        'book_id',
        'borrow_date',
        'due_date',
        'return_date',
        'status',
    ];
    /** @var array<int,string> */
    protected $casts = [
        'borrow_date' => 'date',
        'due_date' => 'date',
        'return_date' => 'date',
    ];

    /** @return  BelongsTo<Member>*/
    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    /** @return  BelongsTo<Book>*/
    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    /**
     * Check if the borrowing is overdue
     * @return bool
     */
    public function isOverdue(): bool
    {
        return $this->due_date < (Carbon::today() && $this->status === 'borrowed');
    }
}
