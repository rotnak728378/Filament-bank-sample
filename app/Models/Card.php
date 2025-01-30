<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'card_number',
        'holder_name',
        'expired_date',
        'balance',
        'bank',
        'status',
        'user_id'
    ];

    protected $casts = [
        'expired_date' => 'date',
        'balance' => 'decimal:2'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
