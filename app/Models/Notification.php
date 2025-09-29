<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'worker_id',
        'title',
        'message',
        'is_read',
        'shift_start',
        'shift_end',
        'flight_id',
        'acknowledged',
        'acknowledged_at',
        'read_at'
    ];

    protected $casts = [
        'shift_start' => 'datetime',
        'shift_end'   => 'datetime',
    ];


    public function worker()
    {
        return $this->belongsTo(Worker::class);
    }

    public function flight()
    {
        return $this->belongsTo(Flight::class);
    }
}
