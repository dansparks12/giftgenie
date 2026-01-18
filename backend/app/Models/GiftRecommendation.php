<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GiftRecommendation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'person_id',
        'occasion',
        'budget_min',
        'budget_max',
        'ai_profile_summary',
    ];

    public function giftItems()
    {
        return $this->hasMany(GiftItem::class);
    }
}