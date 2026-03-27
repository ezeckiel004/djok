<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inscription extends Model
{
    protected $fillable = [
        'user_id',
        'formation_id',
        'status',
        'start_date',
        'end_date',
        'amount_paid',
        'payment_method',
        'notes'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'amount_paid' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function formation()
    {
        return $this->belongsTo(Formation::class);
    }

        public function session()
    {
        return $this->belongsTo(FormationSession::class, 'session_id');
    }

}
