<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormationSession extends Model
{
    use HasFactory;

    protected $table = 'formation_sessions';

    protected $fillable = [
        'formation_id',
        'name',
        'type',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'location',
        'max_places',
        'available_places',
        'price',
        'is_active',
        'description',
        'metadata',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'max_places' => 'integer',
        'available_places' => 'integer',
        'price' => 'decimal:2',
        'is_active' => 'boolean',
        'metadata' => 'array',
    ];

    protected $appends = [
        'formatted_dates',
        'formatted_price',
        'places_remaining',
        'is_full',
    ];

    public function formation()
    {
        return $this->belongsTo(Formation::class);
    }

    public function participants()
    {
        return $this->hasMany(Participant::class, 'session_id');
    }

    public function inscriptions()
    {
        return $this->hasMany(Inscription::class, 'session_id');
    }

    public function getFormattedDatesAttribute()
    {
        $start = $this->start_date->format('d/m/Y');
        $end = $this->end_date->format('d/m/Y');

        if ($start === $end) {
            return $start;
        }

        return $start . ' - ' . $end;
    }

    public function getFormattedPriceAttribute()
    {
        $price = $this->price ?? $this->formation->price;
        return number_format($price, 0, ',', ' ') . ' €';
    }

    public function getPlacesRemainingAttribute()
    {
        return max(0, $this->available_places);
    }

    public function getIsFullAttribute()
    {
        return $this->available_places <= 0;
    }

    public function getFormattedScheduleAttribute()
    {
        $schedule = '';

        if ($this->start_time && $this->end_time) {
            $start = $this->start_time->format('H:i');
            $end = $this->end_time->format('H:i');
            $schedule = $start . ' - ' . $end;
        }

        return $schedule;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('start_date', '>=', now());
    }

    public function scopeAvailable($query)
    {
        return $query->active()
            ->where('start_date', '>=', now())
            ->where('available_places', '>', 0);
    }
}
