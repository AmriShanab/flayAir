<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Flight extends Model
{
    use HasFactory;

    protected $fillable = [
        'flight_number',
        'type',
        'scheduled_time',
        'date',
        'origin',
        'destination',
        'airline',
        'status',
        'notes'
    ];

    protected $casts = [
        'date' => 'date',
        'scheduled_time' => 'datetime:H:i',
    ];

    /**
     * Scope a query to only include flights for a specific date.
     */
    public function scopeForDate($query, $date)
    {
        return $query->where('date', $date);
    }

    /**
     * Scope a query to only include arrivals.
     */
    public function scopeArrivals($query)
    {
        return $query->where('type', 'arrival');
    }

    /**
     * Scope a query to only include departures.
     */
    public function scopeDepartures($query)
    {
        return $query->where('type', 'departure');
    }

    /**
     * Get the flight type with proper formatting.
     */
    public function getFormattedTypeAttribute()
    {
        return ucfirst($this->type);
    }

    /**
     * Get the flight status with proper formatting.
     */
    public function getFormattedStatusAttribute()
    {
        return ucfirst($this->status);
    }
}