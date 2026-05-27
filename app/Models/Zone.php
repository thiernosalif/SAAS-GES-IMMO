<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Zone extends Model
{
    protected $fillable = [
        'agency_id', 'nom', 'ville', 'adresse', 'telephone', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function biens()
    {
        return $this->hasMany(Bien::class);
    }
}
