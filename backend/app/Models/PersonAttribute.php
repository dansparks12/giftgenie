<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PersonAttribute extends Model
{
    use HasFactory;

    protected $fillable = [
        'person_id',
        'type',
        'value',
    ];

    public function person()
    {
        return $this->belongsTo(Person::class);
    }
}