<?php
// app/Models/Shift.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Shift extends Model
{
    use HasFactory;

   protected $fillable = [
        'worker_id',
        'flight_id',
        'start_time',
        'end_time',
        'break_time_start',
        'break_time_end',
        'shift_type',
        'status',
        'notes',
    ];

   protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'break_time_start' => 'datetime',
        'break_time_end' => 'datetime',
    ];

    public function worker()
{
    return $this->belongsTo(\App\Models\Worker::class, 'worker_id');
}


        public function flight()
    {
        return $this->belongsTo(Flight::class);
    }

    public function scopeForDate($query, $date)
    {
        return $query->whereDate('start_time', $date);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('start_time', '>=', now());
    }

}