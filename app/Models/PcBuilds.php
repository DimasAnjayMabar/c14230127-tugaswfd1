<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PcBuilds extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'total_price'];

    public function parts()
    {
        return $this->belongsToMany(PcParts::class, 'detailed_build')->withPivot('quantity');
    }
}

