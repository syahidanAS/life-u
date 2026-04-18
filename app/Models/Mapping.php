<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mapping extends Model
{
    use HasFactory;

    protected $table = 'mappings';

    protected $fillable = [
        'alias',
        'pin',
        'device_id',
    ];

    public function device()
    {
        return $this->belongsTo(Device::class);
    }
}
