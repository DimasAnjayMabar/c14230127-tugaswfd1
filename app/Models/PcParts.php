<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PcParts extends Model
{
    use HasFactory;

    protected $fillable = ['type', 'name', 'price', 'picture'];

    public function builds()
    {
        return $this->belongsToMany(PcBuilds::class, 'detailed_build');
    }
}

