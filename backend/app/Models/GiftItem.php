<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GiftItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'gift_recommendation_id',
        'title',
        'description',
        'price_min',
        'price_max',
        'source',
        'url',
        'ai_reason',
    ];
}