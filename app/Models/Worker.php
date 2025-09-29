<?php
// app/Models/Worker.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Worker extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'position',
        'status',
        'online',
    ];

    protected $casts = [
        'online' => 'boolean',
    ];

  public function shifts()
{
    return $this->hasMany(\App\Models\Shift::class, 'worker_id');
}


    public function user()
    {
        return $this->belongsTo(User::class, 'email', 'email');
    }

     public function notifications()
    {
        return $this->hasMany(Notification::class);
    }



    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
