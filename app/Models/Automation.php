<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Automation extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'time',
        'pin',
        'state',
        'is_repeat',
        'device_id',
    ];

    public function device()
    {
        return $this->belongsTo(Device::class);
    }
}
